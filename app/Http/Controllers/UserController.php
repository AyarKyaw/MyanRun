<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Find athlete using runner_id
        $athlete = \App\Models\Athlete::where(
            'runner_id',
            $user->runner_id
        )->first();

        // Status filter
        $status = $request->get('status');

        // Fetch athlete tickets
        $tickets = \App\Models\Ticket::query()
            ->where('status', '!=', 'rejected')

            ->when($athlete, function ($query) use ($athlete) {
                return $query->where('athlete_id', $athlete->id);
            })

            ->unless($athlete, function ($query) {
                return $query->whereRaw('1 = 0');
            })

            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })

            ->latest()
            ->paginate(3)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Attach Event Athlete-Edit Settings
        |--------------------------------------------------------------------------
        |
        | Tickets currently store the event name in the `event` column.
        | So we resolve the Event using its name.
        |
        */

        $tickets->getCollection()->transform(function ($ticket) {

            $event = \App\Models\Event::where(
                'name',
                trim($ticket->event)
            )->first();

            $ticket->athlete_edit_event = $event;

            if ($event) {
                $ticket->athlete_edit_enabled = (bool) $event->allow_athlete_edit;

                $ticket->can_edit_required =
                    (bool) $event->allow_edit_required;

                $ticket->can_edit_personal =
                    (bool) $event->allow_edit_personal;

                $ticket->can_edit_contact =
                    (bool) $event->allow_edit_contact;

                $ticket->can_edit_participant =
                    (bool) $event->allow_edit_participant;
            } else {
                $ticket->athlete_edit_enabled = false;
                $ticket->can_edit_required = false;
                $ticket->can_edit_personal = false;
                $ticket->can_edit_contact = false;
                $ticket->can_edit_participant = false;
            }

            return $ticket;
        });

        $fullName = trim(
            "{$user->first_name} " .
            ($athlete->middle_name ?? '') . " " .
            "{$user->last_name}"
        );

        return view(
            'user.dashboard',
            compact(
                'user',
                'tickets',
                'fullName',
                'athlete'
            )
        );
    }

    public function dashboard()
    {
        // Fetch all users and the total count
        $customers = User::all(); 
        $totalCount = User::count();

        return view('dashboard.register.register-level-1', compact('customers', 'totalCount'));
    }

    public function showChangePasswordForm() {
        return view('user.change-password'); // Create this view file
    }

    public function updatePassword(Request $request) 
    {
        $request->validate([
            'current_password' => 'required',
            // Define the rules manually here, instead of using Password::defaults()
            'new_password' => ['required', 'string', 'min:8', 'confirmed'], 
        ], [
            'new_password.required' => 'The new password field is required.',
            'new_password.confirmed' => 'The new password confirmation does not match.',
            'new_password.min' => 'The new password must be at least 8 characters.',
        ]);

        // dd($request->all());

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        // Use the direct DB update to bypass session-killing events
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password' => Hash::make($request->new_password),
                'updated_at' => now(),
            ]);

        return redirect()->route('user.dashboard')->with('success', 'Password updated successfully!');
    }
}