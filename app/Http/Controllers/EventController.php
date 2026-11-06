<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Admin; // Added this
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // Added this

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $status = 'coming')
    {
        $admin = Auth::guard('admin')->user();
        $query = \App\Models\Event::query();

        // --- Role Based Filtering ---
        // If the user is an Event Admin, only show events they are assigned to
        if ($admin->role === 'event_admin') {
            $query->whereHas('admins', function($q) use ($admin) {
                $q->where('admin_id', $admin->id);
            });
        }

        // Map the URL string to your is_active integers
        if ($status === 'now') {
            $query->where('is_active', 1);
            $title = "Live Now";
        } elseif ($status === 'past') {
            $query->where('is_active', 0);
            $title = "Past Events";
        } else {
            // Default to 'coming'
            $query->where('is_active', 2);
            $title = "Upcoming Events";
        }

        $events = $query->orderBy('date', 'desc')->get();
        
        // Sidebar: Always show the 'Coming' (is_active = 2) events
        $sidebarEvents = \App\Models\Event::where('is_active', 2)->take(5)->get();
        $customers = \App\Models\User::all();

        return view('dashboard.events.event', compact('events', 'sidebarEvents', 'title', 'status', 'customers'));
    }

    // Show form to create event
    public function create()
    {
        // Fetch only Event Admins to show in the assignment field
        $eventAdmins = Admin::where('role', 'event_admin')->get();
        return view('dashboard.events.event-create', compact('eventAdmins'));
    }

    public function showPublicEvents()
    {
        $allEvents = \App\Models\Event::orderBy('date', 'asc')->get();

        // Attach "is_full" status to each event
        $allEvents->each(function ($event) {
            $soldSlots = \App\Models\Ticket::where('event_id', $event->id)
                ->whereIn('status', ['pending', 'confirmed', 'approved'])
                ->whereNotNull('bib_number')
                ->distinct()
                ->count('bib_number');

            $event->is_full = !is_null($event->total_max_slots) && ($soldSlots >= $event->total_max_slots);
        });

        $nowEvents = $allEvents->where('is_active', 1);
        $comingEvents = $allEvents->where('is_active', 2);
        $pastEvents = $allEvents->where('is_active', 0);

        $userTickets = [];
        $pendingTickets = []; // NEW: To track tickets waiting for payment

        if (auth()->check()) {
            $athlete = \App\Models\Athlete::where('runner_id', auth()->user()->runner_id)->first();
            
            if ($athlete) {
                // 1. Confirmed/Approved tickets (To show "SECURED")
                $userTickets = \App\Models\Ticket::where('athlete_id', $athlete->id)
                    ->whereIn('status', ['confirmed', 'approved'])
                    ->pluck('event') 
                    ->map(fn($item) => trim($item))
                    ->toArray();

                // 2. Pending tickets (To show "RESUME PAY")
                // We map Event Name => Event ID so the Blade can easily find the ID to link to
                $pendingTickets = \App\Models\Ticket::where('athlete_id', $athlete->id)
                    ->where('status', 'pending')
                    ->pluck('event_id', 'event') 
                    ->toArray();
            }
        }

        return view('events', compact('nowEvents', 'comingEvents', 'pastEvents', 'userTickets', 'pendingTickets'));
    }

    // Save the event
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'company'           => 'required|string',
            'date'              => 'required|date',
            'is_active'         => 'required|integer',
            'image'             => 'required|image|max:2048',
            'location'          => 'nullable|string',
            'video_url'         => 'nullable|string',
            'description'       => 'nullable|string',
            
            'ticket_limit_type' => 'required|in:unlimited,limited',
            'total_max_slots'   => 'nullable|required_if:ticket_limit_type,limited|integer|min:1',

            // Ticket types validation
            'tickets'                        => 'required|array',
            'tickets.*.name'                => 'required|string',
            'tickets.*.type'                => 'required|in:solo,relay',
            'tickets.*.national_price'      => 'required|numeric',
            'tickets.*.foreign_price'       => 'required|numeric',
            'tickets.*.max_slots'           => 'nullable|integer',
            'tickets.*.prefix'              => 'nullable|string|max:10',
            'tickets.*.start_number'        => 'nullable|integer|min:1',
            'tickets.*.early_bird_limit'    => 'nullable|integer|min:0',
            'tickets.*.early_bird_discount' => 'nullable|numeric|min:0',
            'tickets.*.has_gender_bib'      => 'nullable|boolean',
            'allow_athlete_edit'      => 'nullable|boolean',
            'allow_edit_required'     => 'nullable|boolean',
            'allow_edit_personal'     => 'nullable|boolean',
            'allow_edit_contact'      => 'nullable|boolean',
            'allow_edit_participant'  => 'nullable|boolean',
            
            'admin_ids' => 'nullable|array' 
        ]);

        return \DB::transaction(function () use ($request) {
            $event = new \App\Models\Event();
            $event->name        = $request->name;
            $event->company     = $request->company;
            $event->date        = $request->date;
            $event->location    = $request->location;
            $event->video_url   = $request->video_url;
            $event->description = $request->description;
            $event->is_active   = $request->is_active;
            $event->allow_athlete_edit     = $request->boolean('allow_athlete_edit');
            $event->allow_edit_required    = $request->boolean('allow_edit_required');
            $event->allow_edit_personal    = $request->boolean('allow_edit_personal');
            $event->allow_edit_contact     = $request->boolean('allow_edit_contact');
            $event->allow_edit_participant = $request->boolean('allow_edit_participant');

            // Global Limit Logic
            $event->total_max_slots = ($request->ticket_limit_type === 'limited') 
                ? $request->total_max_slots 
                : null;

            if ($request->hasFile('image')) {
                $event->image_path = $request->file('image')->store('events', 'public');
            }

            $event->save();

            // Save Admin Assignments
            if ($request->has('admin_ids')) {
                $event->admins()->attach($request->admin_ids);
            }

            // Save ticket types
            if ($request->has('tickets')) {
                foreach ($request->tickets as $index => $ticket) {
                    if (empty($ticket['name'])) continue;

                    $nationalImage = null;
                    $foreignImage = null;
                    $ticketPng = null;

                    // Handle Image Uploads
                    if ($request->hasFile("tickets.$index.national_image")) {
                        $nationalImage = $request->file("tickets.$index.national_image")->store('tickets/display', 'public');
                    }
                    if ($request->hasFile("tickets.$index.foreign_image")) {
                        $foreignImage = $request->file("tickets.$index.foreign_image")->store('tickets/display', 'public');
                    }
                    if ($request->hasFile("tickets.$index.ticket_png")) {
                        $ticketPng = $request->file("tickets.$index.ticket_png")->store('tickets/templates', 'public');
                    }

                    $event->ticketTypes()->create([
                        'name'                => $ticket['name'],
                        'type'                => $ticket['type'],
                        'national_price'      => $ticket['national_price'],
                        'foreign_price'       => $ticket['foreign_price'],
                        'national_image'      => $nationalImage,
                        'foreign_image'       => $foreignImage,
                        'ticket_png'          => $ticketPng,
                        'max_slots'           => $ticket['max_slots'] ?? null,
                        'prefix'              => $ticket['prefix'] ?? null,
                        'start_number'        => $ticket['start_number'] ?? 1,
                        'has_gender_bib'      => $ticket['has_gender_bib'] ?? 0,
                        'early_bird_limit'    => $ticket['early_bird_limit'] ?? null,
                        'early_bird_discount' => $ticket['early_bird_discount'] ?? 0,
                    ]);
                }
            }

            $statusMap = [0 => 'past', 1 => 'now', 2 => 'coming'];
            return redirect()->route('events.index', $statusMap[$request->is_active] ?? 'coming');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $event = \App\Models\Event::findOrFail($id);

        // Logic for status
        $status = 'past';
        if ($event->is_active == 1) $status = 'live';
        if ($event->is_active == 2) $status = 'coming';

        return view('events.detail', [
            'event'    => $event,
            'title'    => $event->name,
            'location' => $event->location ?? 'Myanmar', // Dynamic Location
            'video'    => $event->video_url ?? 'K_FvDL_anrs', // Default ID if empty
            'date'     => \Carbon\Carbon::parse($event->date)->format('d M Y'),
            'image'    => 'storage/' . $event->image_path,
            'status'   => $status,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $event = \App\Models\Event::with('agents', 'admins')->findOrFail($id);
        
        // Fetch all Event Admins
        $eventAdmins = Admin::where('role', 'event_admin')->get(); 
        
        // Fetch all Support Agents so they appear in the dropdown list
        $agents = \App\Models\Agent::all(); 

        // Pass everything to the view
        return view('dashboard.events.edit', compact('event', 'eventAdmins', 'agents'));
    }

    public function update(Request $request, $id)
    {
        $event = \App\Models\Event::findOrFail($id);

        $request->validate([
            'name'             => 'required|string|max:255',
            'date'             => 'nullable|date',
            'is_active'        => 'required',
            'company'          => 'nullable|string',
            'location'         => 'nullable|string',
            'video_url'        => 'nullable|string',
            'description'      => 'nullable|string',
            'early_bird_limit' => 'nullable|integer|min:0', 
            'image'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tickets'          => 'nullable|array',
            'admin_ids'        => 'nullable|array', // Added validation for admins
            'allow_athlete_edit'      => 'nullable|boolean',
            'allow_edit_required'     => 'nullable|boolean',
            'allow_edit_personal'     => 'nullable|boolean',
            'allow_edit_contact'      => 'nullable|boolean',
            'allow_edit_participant'  => 'nullable|boolean',
        ]);

        // Update Event Details
        $event->name             = $request->name;
        $event->company          = $request->company;
        $event->date             = $request->date;
        $event->is_active        = $request->is_active;
        $event->location         = $request->location;
        $event->video_url        = $request->video_url;
        $event->description      = $request->description;
        $event->early_bird_limit = $request->early_bird_limit ?? 0;
        $event->allow_athlete_edit = $request->boolean('allow_athlete_edit');

        if ($event->allow_athlete_edit) {
            $event->allow_edit_required    = $request->boolean('allow_edit_required');
            $event->allow_edit_personal    = $request->boolean('allow_edit_personal');
            $event->allow_edit_contact     = $request->boolean('allow_edit_contact');
            $event->allow_edit_participant = $request->boolean('allow_edit_participant');
        } else {
            $event->allow_edit_required    = false;
            $event->allow_edit_personal    = false;
            $event->allow_edit_contact     = false;
            $event->allow_edit_participant = false;
        }

        if ($request->ticket_limit_type === 'unlimited') {
            $event->total_max_slots = null;
        } else {
            $event->total_max_slots = $request->total_max_slots;
        }

        if ($request->hasFile('image')) {
            $event->image_path = $request->file('image')->store('events', 'public');
        }

        $event->save();

        // --- Sync Admin Assignments ---
        if ($request->has('admin_ids')) {
            $event->admins()->sync($request->admin_ids);
        } else {
            $event->admins()->detach();
        }

        if ($request->has('agent_ids')) {
            $event->agents()->sync($request->agent_ids);
        } else {
            $event->agents()->detach();
        }

        // Handle Ticket Types
        if ($request->has('tickets')) {
            foreach ($request->tickets as $index => $ticketData) {
                
                $ticketType = isset($ticketData['id']) 
                    ? \App\Models\EventTicketType::find($ticketData['id']) 
                    : new \App\Models\EventTicketType();

                $ticketType->event_id            = $event->id;
                $ticketType->name                = $ticketData['name'];
                $ticketType->type                = $ticketData['type'];
                $ticketType->national_price      = $ticketData['national_price'];
                $ticketType->foreign_price       = $ticketData['foreign_price'];
                $ticketType->max_slots           = $ticketData['max_slots'] ?? null;
                $ticketType->prefix              = $ticketData['prefix'] ?? null;
                $ticketType->start_number        = $ticketData['start_number'] ?? 1;

                $ticketType->has_gender_bib      = $ticketData['has_gender_bib'] ?? 0;
                $ticketType->early_bird_limit    = $ticketData['early_bird_limit'] ?? null;
                $ticketType->early_bird_discount = $ticketData['early_bird_discount'] ?? 0;

                if ($request->hasFile("tickets.$index.national_image")) {
                    $ticketType->national_image = $request->file("tickets.$index.national_image")->store('tickets/display', 'public');
                }
                if ($request->hasFile("tickets.$index.foreign_image")) {
                    $ticketType->foreign_image = $request->file("tickets.$index.foreign_image")->store('tickets/display', 'public');
                }
                if ($request->hasFile("tickets.$index.ticket_png")) {
                    $ticketType->ticket_png = $request->file("tickets.$index.ticket_png")->store('tickets/templates', 'public');
                }

                $ticketType->save();
            }
        }

        $statusMap = [0 => 'past', 1 => 'now', 2 => 'coming'];
        $statusKey = $statusMap[$request->is_active] ?? 'now';

        return redirect()->route('events.index', $statusKey)
                         ->with('success', 'Event and Ticket Types updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $event = \App\Models\Event::findOrFail($id);

        // 1. Delete the image file from the 'public' disk if it exists
        if ($event->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($event->image_path);
        }

        // 2. Delete the database record
        $event->delete();

        // 3. Redirect back with a success message
        return redirect()->back()->with('success', 'Event deleted successfully!');
    }
}