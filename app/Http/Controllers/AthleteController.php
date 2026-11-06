<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Athlete;
use App\Models\Ticket;
use App\Models\User;
use App\Models\EventTicketType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AthleteController extends Controller
{   
    public function showAthleteForm(Request $request)
    {   
        if (!session()->has('ticket_category')) {
            return redirect()->route('ticket')->with('error', 'Please select a ticket first!');
        }

        // Use runner_id as the lookup key as per your database structure
        $athlete = Athlete::where('runner_id', Auth::user()->runner_id)->first();
        $category = session('ticket_category');
        $gender = old('gender', $athlete->gender ?? 'male');

        // Generate BIB based on the Tickets table
        $bibNumber = $this->generateBib($gender, $category);

        $nrcParts = ['state' => '', 'district' => '', 'naing' => 'နိုင်', 'number' => ''];
        if ($athlete && $athlete->nat_type === 'national' && !empty($athlete->id_number)) {
            try {
                $parts = explode('/', $athlete->id_number);
                $nrcParts['state'] = $parts[0];
                $subParts = explode('(', $parts[1]);
                $nrcParts['district'] = $subParts[0];
                $numberParts = explode(')', $subParts[1]);
                $nrcParts['naing'] = $numberParts[0];
                $nrcParts['number'] = $numberParts[1];
            } catch (\Exception $e) { }
        }

        $friendUser = null;

        if (session()->has('friend_user_id')) {
            $friendUser = \App\Models\User::find(session('friend_user_id'));
        }

        return view('ticket.register-athlete', [
            'athlete' => $athlete,
            'nrcParts' => $nrcParts,
            'category' => $category,
            'bibNumber' => $bibNumber,
            'price' => session('ticket_price'),
            'type' => session('nat_type'),
            'eventName' => session('event_name', 'Official Race'),
            'friendUser' => $friendUser,
        ]);
    }

    private function generateBib($gender, $category)
    {
        $prefix = (strtolower($gender) === 'female') ? 'F' : 'M';
        
        preg_match('/\d+/', $category, $matches);
        $distance = $matches[0] ?? '00';
        $searchPattern = $prefix . $distance; // e.g., "F16"

        // Find the highest existing BIB for this specific prefix + distance
        $lastBib = Ticket::where('bib_number', 'LIKE', $searchPattern . '%')
            ->where('status', '!=', 'rejected')
            ->orderBy('bib_number', 'desc')
            ->first();

        if ($lastBib) {
            // Extract the last 4 digits from the string (e.g., F160011 -> 0011)
            $lastNumber = (int) substr($lastBib->bib_number, -4);
            $newNumberInt = $lastNumber + 1;
        } else {
            $newNumberInt = 11; // Starting point
        }

        return $searchPattern . str_pad($newNumberInt, 4, '0', STR_PAD_LEFT);
    }

    public function handleSelection(Request $request) 
    {   
        $ticketType = EventTicketType::find($request->ticket_type_id);

        if (!$ticketType) {
            return back()->with('error', 'Ticket type not found.');
        }

        session([
            'ticket_type_id' => $ticketType->id,
            'ticket_type'    => $ticketType->type, // 'relay' or 'solo'
            'ticket_price'   => $request->price,
            'nat_type'       => $request->nationality,
            'event_id'       => $request->event_id,
            'ticket_category' => $ticketType->name, 
        ]);

        return redirect()->route('athlete.register');
    }

    public function dashboard()
    {
        $customers = \App\Models\Athlete::all(); 
        $totalCount = \App\Models\Athlete::count();

        return view('dashboard.register.register-level-2', compact('customers', 'totalCount'));
    }

    public function submit(Request $request)
{   
    // 1. Define standard validation rules
    $rules = [
        'face_image'   => 'nullable|image|max:2048',
        'first_name'   => 'required|string',
        'last_name'    => 'required|string',
        'bib_name'     => 'required|string|max:20', 
        'contact'      => 'required|digits_between:9,11',
        'dob'          => 'required|date_format:d/m/Y',
        'gender'       => 'required',
        't_shirt_size' => 'required',
        'blood_type'   => 'required',
        'exp'           => 'required',
        'nrc_number'   => 'nullable|required_if:type,national|digits:6',
        'type'           => 'required|in:national,foreigner',
        'job_work'       => 'required_if:type,foreigner|nullable|string|max:255',
        'passport_image' => 'required_if:type,foreigner|nullable|image|mimes:jpeg,png,jpg,webp|dimensions:max_width=1920,max_height=1080|max:2048',
        'visa_image'     => 'required_if:type,foreigner|nullable|image|mimes:jpeg,png,jpg,webp|dimensions:max_width=1920,max_height=1080|max:2048',
    ];

    // 2. ADD UNIQUE CHECKS: Only if it's a relay and they are NOT linking an existing runner
    if (session('ticket_type') === 'relay' && !$request->filled('friend_runner_id')) {
        $rules['friend_email'] = 'required|email|unique:users,email';
        $rules['friend_phone'] = 'required|digits_between:9,15'; 
    }

    \Log::info('NRC SUBMIT DATA', [
        'all' => $request->all(),
        'nat_type' => $request->type,
        'nationality' => $request->nationality,
        'nrc_state' => $request->nrc_state,
        'nrc_district' => $request->nrc_district,
        'nrc_naing' => $request->nrc_naing,
        'nrc_number' => $request->nrc_number,
    ]);

    $request->validate($rules);

    // 3. Format Date
    $formattedDob = Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');

    // 4. Handle ID Number
    $idNumber = ($request->type === 'national') 
        ? "{$request->nrc_state}/{$request->nrc_district}({$request->nrc_naing})" . str_pad($request->nrc_number, 6, '0', STR_PAD_LEFT)
        : strtoupper($request->passport_id);

    // Fetch existing athlete record if present to prevent wiping image paths on edit
    $existingAthlete = Athlete::where('runner_id', Auth::user()->runner_id)->first();

    // 5. Handle Images (Corrected paths to singular: 'athletes/job' and 'athletes/visa')
    $path = $request->existing_face;
    if ($request->hasFile('face_image')) {
        $path = $request->file('face_image')->store('athletes', 'public');
    }

    // Handle Passport Copy Image Upload
    $passportImagePath = $existingAthlete->passport_image_path ?? null;
    if ($request->hasFile('passport_image')) {
        \Storage::disk('public')->makeDirectory('athletes/passport');
        $passportImagePath = $request->file('passport_image')->store('athletes/passport', 'public');
    }

    // Handle Visa Document Image Upload
    $visaImagePath = $existingAthlete->visa_image_path ?? null;
    if ($request->hasFile('visa_image')) {
        \Storage::disk('public')->makeDirectory('athletes/visa');
        $visaImagePath = $request->file('visa_image')->store('athletes/visa', 'public');
    }

    $finalBib = $this->generateBib($request->gender, session('ticket_category'));

    // 6. Save Mode B Data to Session
    if (session('ticket_type') === 'relay' && !$request->filled('friend_runner_id')) {
        session([
            'partner_mode_b_data' => [
                'first_name' => $request->friend_first_name,
                'last_name'  => $request->friend_last_name,
                'email'      => $request->friend_email,
                'phone'      => $request->friend_phone,
            ]
        ]);
    }
    

    // 7. Update/Create Athlete
    $athlete = Athlete::updateOrCreate(
        ['runner_id' => Auth::user()->runner_id],
        [
            'face_image_path'       => $path,
            'nat_type' => $request->nat_type ?? $request->type ?? 'national',
            'job_work'              => $request->job_work,
            'passport_image_path'   => $passportImagePath,
            'visa_image_path'       => $visaImagePath,
            'id_number'             => $idNumber,
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name,
            'father_name'           => $request->father_name,
            'address'               => $request->address,
            'dob'                   => $formattedDob,
            'gender'                => $request->gender,
            'nationality'           => $request->nationality,
            'blood_type'            => $request->blood_type,
            'contact'               => $request->contact,
            'state'                 => $request->state,
            'viber'                 => $request->viber,
            'has_medical_condition' => $request->has_condition === 'yes',
            'medical_details'       => $request->medical_conditions,
            'has_itra'              => $request->has_itra === 'yes',
            'itra_details'          => $request->itra_details,
        ]
    );

    // 8. Set Primary Registration Session
    session([
        'pending_registration' => [
            'athlete_id'     => $athlete->id,
            'bib_name'       => $request->bib_name,
            'bib_number'     => $finalBib,
            't_shirt_size'   => $request->t_shirt_size,
            'category'       => session('ticket_category'),
            'ticket_type_id' => session('ticket_type_id'), 
            'price'          => session('ticket_price'),
            'event_id'       => session('event_id'), 
            'exp_level'      => $request->exp,
            'status'         => 'pending'
        ]
    ]);

    // 9. Relay Handling Logic
    if (session('ticket_type') === 'relay') {
        if ($request->filled('friend_runner_id')) {
            $friend = User::where('runner_id', $request->friend_runner_id)->first();
            
            if (!$friend || !\Hash::check($request->friend_password, $friend->password)) {
                return back()->withErrors(['friend_password' => 'Partner Runner ID or Password does not match.'])->withInput();
            }

            session(['friend_user_id' => $friend->id]);
        } else {
            session()->forget('friend_user_id');
        }

        return redirect()->route('friend.register'); 
    }

    return redirect()->route('athlete.consent');
}

    public function showFriendRegisterForm(Request $request)
    {
        if (!session()->has('pending_registration')) {
            return redirect()->route('athlete.register')->with('error', 'Please register yourself first.');
        }

        $friendAthlete = null;
        $friendUser = null;
        $nrcParts = ['state' => '', 'district' => '', 'naing' => 'နိုင်', 'number' => ''];

        $modeBData = session('partner_mode_b_data');

        if (session()->has('friend_user_id')) {
            $friendUser = User::find(session('friend_user_id'));
            
            if ($friendUser) {
                $friendAthlete = Athlete::where('runner_id', $friendUser->runner_id)->first();
                
                if ($friendAthlete && $friendAthlete->nat_type === 'national' && !empty($friendAthlete->id_number)) {
                    try {
                        $parts = explode('/', $friendAthlete->id_number);
                        $nrcParts['state'] = $parts[0];
                        $subParts = explode('(', $parts[1]);
                        $nrcParts['district'] = $subParts[0];
                        $numberParts = explode(')', $subParts[1]);
                        $nrcParts['naing'] = $numberParts[0] ?? 'နိုင်';
                        $nrcParts['number'] = $numberParts[1] ?? '';
                    } catch (\Exception $e) { }
                }
            }
        }

        return view('ticket.friend_register', [
            'friendAthlete' => $friendAthlete,
            'friendUser' => $friendUser, 
            'modeBData' => $modeBData,
            'nrcParts' => $nrcParts,
            'category' => session('ticket_category'),
            'type' => session('nat_type', 'national'), 
        ]);
    }

    public function submitFriend(Request $request)
    {
        $hasFriendSession = session()->has('friend_user_id');

        $request->validate([
            'face_image' => 'nullable|image|max:2048', // Face ID hidden/optional for now
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => $hasFriendSession ? 'nullable|email' : 'required|email|unique:users,email',
            'phone_1' => [
                $hasFriendSession ? 'nullable' : 'required',
                'regex:/^\+?[0-9]{9,15}$/' 
            ],
            'bib_name' => 'required|string|max:20', 
            'contact' => 'required|digits_between:9,11',
            'dob' => 'required|date_format:d/m/Y',
            'gender' => 'required',
            't_shirt_size' => 'required',
            'blood_type' => 'required',
            'nrc_number' => 'nullable|required_if:nat_type,national|digits:6',
        ]);

        if ($hasFriendSession) {
            $friendUser = User::find(session('friend_user_id'));
            $newPartnerRunnerId = $friendUser->runner_id;
        } else {
            $lastUser = User::orderBy('runner_id', 'desc')->first();
            $nextNumber = $lastUser ? ((int)substr($lastUser->runner_id, 5) + 1) : 1;
            $newPartnerRunnerId = 'RUN-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $friendUser = User::create([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'phone'      => '+959' . ltrim($request->phone_1, '0'),
                'password'   => Hash::make($request->phone_1),
                'runner_id'  => $newPartnerRunnerId,
            ]);

            session(['friend_user_id' => $friendUser->id]);
        }

        $formattedDob = Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');
        
        $idNumber = ($request->nat_type === 'national') 
            ? "{$request->nrc_state}/{$request->nrc_district}({$request->nrc_naing})" . str_pad($request->nrc_number, 6, '0', STR_PAD_LEFT)
            : strtoupper($request->passport_id);

        $path = $request->existing_face;
        if ($request->hasFile('face_image')) {
            $path = $request->file('face_image')->store('athletes', 'public');
        }

        $friendAthlete = Athlete::updateOrCreate(
            ['runner_id' => $newPartnerRunnerId],
            [
                'face_image_path' => $path,
                'nat_type' => $request->nat_type,
                'id_number' => $idNumber,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'father_name' => $request->father_name,
                'address' => $request->address,
                'dob' => $formattedDob,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'blood_type' => $request->blood_type,
                'contact' => $request->contact,
                'state' => $request->state,
                'viber' => $request->viber,
                'has_medical_condition' => $request->has_condition === 'yes',
                'medical_details' => $request->medical_conditions,
                'has_itra' => $request->has_itra === 'yes',
                'itra_details' => $request->itra_details,
            ]
        );

        session([
            'friend_registration' => [
                'athlete_id'   => $friendAthlete->id,
                'bib_name'     => $request->bib_name,
                'bib_number'   => $this->generateBib($request->gender, session('ticket_category')),
                't_shirt_size' => $request->t_shirt_size,
            ]
        ]);

        return redirect()->route('athlete.consent');
    }

    public function verifyFriend(Request $request)
    {
        $request->validate([
            'friend_runner_id' => 'required',
            'friend_password' => 'required'
        ]);

        $user = User::where('runner_id', $request->friend_runner_id)->first();

        if ($user && Hash::check($request->friend_password, $user->password)) {
            session(['friend_user_id' => $user->id]);
            session()->save(); 
            return redirect()->route('friend.register'); 
        }

        return back()->withErrors(['friend_password' => 'Invalid credentials.']);
    }

    public function showConsent()
    {
        $data = session('pending_registration');

        if (!$data) {
            return redirect()->route('athlete.register')->with('error', 'Session expired. Please fill the form again.');
        }

        // Get the event name from the session or via the event_id
        // If you don't have 'event_name' in session, we fetch it from the DB using event_id
        $eventId = session('event_id');
        $event = \App\Models\Event::find($eventId);
        $eventName = $event ? $event->name : (session('event_name') ?? 'Official Race');

        // --- Event Specific Consent Routing ---
        if ($eventName === 'Alaingni Monsoon Duathlon 2026') {
            // Returns resources/views/ticket/consent_alaingni.blade.php
            return view('ticket.consent_alaingni', compact('data', 'event'));
        }

        // Default consent page
        // Returns resources/views/ticket/consent.blade.php
        return view('ticket.consent', compact('data', 'event'));
    }

    public function editRegistration(Request $request, \App\Models\Event $event)
{
    $user = auth()->user();

    $athlete = \App\Models\Athlete::where(
        'runner_id',
        $user->runner_id
    )->first();

    if (!$athlete) {
        return response()->json([
            'success' => false,
            'message' => 'Athlete profile not found.',
        ], 404);
    }

    /*
    |--------------------------------------------------------------------------
    | Master permission
    |--------------------------------------------------------------------------
    */
    if (!$event->allow_athlete_edit) {
        return response()->json([
            'success' => false,
            'message' => 'Registration editing is disabled for this event.',
        ], 403);
    }

    /*
    |--------------------------------------------------------------------------
    | Find this athlete's registration
    |--------------------------------------------------------------------------
    */
    $ticket = \App\Models\Ticket::where(
        'athlete_id',
        $athlete->id
    )
        ->where('event', $event->name)
        ->where('status', '!=', 'rejected')
        ->latest()
        ->first();

    if (!$ticket) {
        return response()->json([
            'success' => false,
            'message' => 'Registration not found for this event.',
        ], 404);
    }

    /*
    |--------------------------------------------------------------------------
    | Parse NRC / Passport
    |--------------------------------------------------------------------------
    */
    $nrc = $this->parseNrcNumber($athlete->id_number);

    $natType = $athlete->nat_type ?: 'national';

    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */
    return response()->json([
        'success' => true,

        'event' => [
            'id' => $event->id,
            'name' => $event->name,
        ],

        'permissions' => [
            'required' => (bool) $event->allow_edit_required,
            'personal' => (bool) $event->allow_edit_personal,
            'contact' => (bool) $event->allow_edit_contact,
            'participant' => (bool) $event->allow_edit_participant,
        ],

        'athlete' => [
            'id' => $athlete->id,

            'nat_type' => $natType,

            'first_name' => $athlete->first_name,
            'middle_name' => $athlete->middle_name,
            'last_name' => $athlete->last_name,

            /*
            |--------------------------------------------------------------------------
            | Original combined ID
            |--------------------------------------------------------------------------
            */
            'id_number' => $athlete->id_number,

            /*
            |--------------------------------------------------------------------------
            | Parsed NRC
            |--------------------------------------------------------------------------
            */
            'nrc_state' => $nrc['state'],
            'nrc_district' => $nrc['district'],
            'nrc_naing' => $nrc['naing'],
            'nrc_number' => $nrc['number'],

            /*
            |--------------------------------------------------------------------------
            | Foreigner passport
            |--------------------------------------------------------------------------
            */
            'passport_id' => $natType === 'foreigner'
                ? $athlete->id_number
                : '',

            /*
            |--------------------------------------------------------------------------
            | Profile image
            |--------------------------------------------------------------------------
            */
            'face_image_path' => $athlete->face_image_path,

            'face_image_url' => $athlete->face_image_path
                ? asset('storage/' . $athlete->face_image_path)
                : null,

            'father_name' => $athlete->father_name,
            'dob' => $athlete->dob,
            'nationality' => $athlete->nationality,
            'gender' => $athlete->gender,
            'state' => $athlete->state,
            'address' => $athlete->address,

            'viber' => $athlete->viber,
            'contact' => $athlete->contact,

            'blood_type' => $athlete->blood_type,

            'has_medical_condition' =>
                (bool) $athlete->has_medical_condition,

            'medical_details' => $athlete->medical_details,

            'job_work' => $athlete->job_work,
        ],

        'user' => [
            'email' => $user->email,
            'phone' => $user->phone,
        ],

        'ticket' => [
            'id' => $ticket->id,

            /*
            |--------------------------------------------------------------------------
            | Read-only
            |--------------------------------------------------------------------------
            */
            'bib_number' => $ticket->bib_number,

            /*
            |--------------------------------------------------------------------------
            | Editable participant information
            |--------------------------------------------------------------------------
            */
            'bib_name' => $ticket->bib_name,
            't_shirt_size' => $ticket->t_shirt_size,
            'experience_level' => $ticket->experience_level,
        ],
    ]);
}

public function updateRegistration(
    Request $request,
    \App\Models\Event $event
) {
    $user = auth()->user();

    $athlete = \App\Models\Athlete::where(
        'runner_id',
        $user->runner_id
    )->first();

    if (!$athlete) {
        return back()->with(
            'error',
            'Athlete profile not found.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Find registration
    |--------------------------------------------------------------------------
    */
    $ticket = \App\Models\Ticket::where(
        'athlete_id',
        $athlete->id
    )
        ->where('event', $event->name)
        ->where('status', '!=', 'rejected')
        ->latest()
        ->first();

    if (!$ticket) {
        return back()->with(
            'error',
            'Registration not found for this event.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Master permission
    |--------------------------------------------------------------------------
    */
    if (!$event->allow_athlete_edit) {
        return back()->with(
            'error',
            'Registration editing is disabled for this event.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 01 — Required Information
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Name + Profile Picture — Always Editable
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'first_name' => [
            'required',
            'string',
            'max:255',
        ],

        'middle_name' => [
            'nullable',
            'string',
            'max:255',
        ],

        'last_name' => [
            'required',
            'string',
            'max:255',
        ],

        'face_image' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:5120',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Update Athlete
    |--------------------------------------------------------------------------
    */

    $athlete->first_name =
        $validated['first_name'];

    $athlete->middle_name =
        $validated['middle_name'] ?? null;

    $athlete->last_name =
        $validated['last_name'];

    /*
    |--------------------------------------------------------------------------
    | Update User name too
    |--------------------------------------------------------------------------
    */

    $user->first_name =
        $validated['first_name'];

    $user->middle_name =
        $validated['middle_name'] ?? null;

    $user->last_name =
        $validated['last_name'];

    $user->save();

    /*
    |--------------------------------------------------------------------------
    | Profile Picture
    |--------------------------------------------------------------------------
    */

    if ($request->hasFile('face_image')) {

        $path = $request
            ->file('face_image')
            ->store(
                'athletes/faces',
                'public'
            );

        $athlete->face_image_path = $path;
    }
    if ($event->allow_edit_required) {

        $validated = $request->validate([

            'nat_type' => [
                'required',
                'in:national,foreigner',
            ],

            /*
            |--------------------------------------------------------------------------
            | National NRC
            |--------------------------------------------------------------------------
            */
            'nrc_state' => [
                'required_if:nat_type,national',
                'nullable',
                'string',
                'max:10',
            ],

            'nrc_district' => [
                'required_if:nat_type,national',
                'nullable',
                'string',
                'max:50',
            ],

            'nrc_naing' => [
                'required_if:nat_type,national',
                'nullable',
                'string',
                'max:20',
            ],

            'nrc_number' => [
                'required_if:nat_type,national',
                'nullable',
                'digits:6',
            ],

            /*
            |--------------------------------------------------------------------------
            | Foreigner passport
            |--------------------------------------------------------------------------
            */
            'passport_id' => [
                'required_if:nat_type,foreigner',
                'nullable',
                'string',
                'max:15',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | National / Foreigner
        |--------------------------------------------------------------------------
        */
        $athlete->nat_type = $validated['nat_type'];

        if ($validated['nat_type'] === 'national') {

            /*
            |--------------------------------------------------------------------------
            | Rebuild NRC
            |
            | Example:
            | 12/ကမန(နိုင်)123456
            |--------------------------------------------------------------------------
            */
            $athlete->id_number =
                $validated['nrc_state']
                . '/'
                . $validated['nrc_district']
                . '('
                . $validated['nrc_naing']
                . ')'
                . $validated['nrc_number'];

        } else {

            /*
            |--------------------------------------------------------------------------
            | Passport
            |--------------------------------------------------------------------------
            */
            $athlete->id_number =
                strtoupper(
                    trim($validated['passport_id'])
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 02 — Personal Information
    |--------------------------------------------------------------------------
    */
    if ($event->allow_edit_personal) {

        $validated = $request->validate([
            'father_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'dob' => [
                'required',
                'date_format:d/m/Y',
            ],

            'gender' => [
                'required',
                'in:male,female',
            ],

            'nationality' => [
                'required',
                'string',
                'max:255',
            ],

            'state' => [
                'nullable',
                'string',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
            ],
        ]);

        $athlete->father_name =
            $validated['father_name'] ?? null;

        $athlete->dob =
            \Carbon\Carbon::createFromFormat(
                'd/m/Y',
                $validated['dob']
            )->format('Y-m-d');

        $athlete->gender =
            $validated['gender'];

        $athlete->nationality =
            $validated['nationality'];

        $athlete->state =
            $validated['state'] ?? null;

        $athlete->address =
            $validated['address'] ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | 03 — Contact Information
    |--------------------------------------------------------------------------
    */
    if ($event->allow_edit_contact) {

        $validated = $request->validate([
            'viber' => [
                'nullable',
                'string',
                'max:255',
            ],

            'contact' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $athlete->viber =
            $validated['viber'] ?? null;

        $athlete->contact =
            $validated['contact'] ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | 04 — Participant Information
    |--------------------------------------------------------------------------
    */
    if ($event->allow_edit_participant) {

        $validated = $request->validate([
            'bib_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            't_shirt_size' => [
                'nullable',
                'string',
                'max:50',
            ],

            'experience_level' => [
                'nullable',
                'string',
                'max:255',
            ],

            'blood_type' => [
                'nullable',
                'string',
                'max:50',
            ],

            'has_medical_condition' => [
                'nullable',
                'boolean',
            ],

            'medical_details' => [
                'nullable',
                'string',
            ],

            'job_work' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | bib_number is NEVER updated.
        |--------------------------------------------------------------------------
        */
        $ticket->bib_name =
            $validated['bib_name'] ?? null;

        $ticket->t_shirt_size =
            $validated['t_shirt_size'] ?? null;

        $ticket->experience_level =
            $validated['experience_level'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Athlete participant information
        |--------------------------------------------------------------------------
        */
        $athlete->blood_type =
            $validated['blood_type'] ?? null;

        $athlete->has_medical_condition =
            $request->boolean(
                'has_medical_condition'
            );

        $athlete->medical_details =
            $validated['medical_details'] ?? null;

        $athlete->job_work =
            $validated['job_work'] ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | Save
    |--------------------------------------------------------------------------
    */
    $athlete->save();

    $ticket->save();

    return redirect()
        ->route('user.dashboard')
        ->with(
            'success',
            'Your registration has been updated successfully.'
        );
}

private function parseNrcNumber(?string $idNumber): array
{
    if (!$idNumber) {
        return [
            'state' => '',
            'district' => '',
            'naing' => '',
            'number' => '',
        ];
    }

    $idNumber = trim($idNumber);

    /*
    |--------------------------------------------------------------------------
    | National NRC
    | Example: 12/ကမန(နိုင်)123456
    |--------------------------------------------------------------------------
    */
    if (preg_match(
        '/^(\d+)\/([^\(]+)\(([^)]+)\)(\d{6})$/u',
        $idNumber,
        $matches
    )) {
        return [
            'state' => $matches[1],
            'district' => trim($matches[2]),
            'naing' => trim($matches[3]),
            'number' => $matches[4],
        ];
    }

    return [
        'state' => '',
        'district' => '',
        'naing' => '',
        'number' => '',
    ];
}
}