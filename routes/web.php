<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\DinnerController;
use App\Http\Controllers\SponsorController;
use App\Models\SponsorCode;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\RaceController;
use App\Http\Controllers\PromoCodeController;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () { return view('index'); });
Route::get('/about', function () { return view('about'); });
Route::get('/contact', function () { return view('contact'); });
Route::get('/race', function () { return view('race'); });
Route::get('/term', function () { return view('term'); });
Route::get('/pp', function () { return view('pp'); });
Route::get('/result', function () { return view('result'); });
Route::get('/forgot_password', function () { return view('password.forgot'); });
Route::get('/race_guide', function () { return view('race_guide'); });
Route::get('/blog', [StoryController::class, 'index'])->name('blog.index');
Route::get('/event', [EventController::class, 'showPublicEvents'])->name('public.events');
Route::get('/event/{id}', [EventController::class, 'show'])->name('events.show');
Route::post('/forgot-password/verify', [RegisterController::class, 'verifyUser'])->name('password.verify_user');
Route::get('/forgot-password/reset', [RegisterController::class, 'showResetForm'])->name('password.reset_view');
Route::post('/forgot-password/update', [RegisterController::class, 'updatePassword'])->name('password.update');


Route::prefix('ITPLUS/agent')->group(function () {
    // Login Routes
    Route::get('/login', [AgentController::class, 'showLogin'])->name('agent.login');
    Route::post('/login', [AgentController::class, 'login'])->name('agent.login.submit');

    // Protected Routes (Only for logged-in agents)
    Route::middleware('auth:agent')->group(function () {
        Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('agent.tickets');
        Route::get('/ticket/{id}', [AgentController::class, 'viewTicket'])->name('agent.ticket.view');
        Route::post('/logout', [AgentController::class, 'logout'])->name('agent.logout');
    });
});

Route::get('/score', function (Request $request) {
    set_time_limit(120);
    $cacheKey = 'race_scores_104742_v2';

    // 1. Fetch or Retrieve the Cached Data
    $data = Cache::remember($cacheKey, 10, function () {
        $pc = "000001";
        $rid = "104742";
        $token = "fe95a4e0d6c442129469f65e9c7a3ff9";
        $base = "https://rqs.racetigertiming.com/Dif";
        $headers = ['User-Agent' => 'Mozilla/5.0'];

        try {
            // Get Event Config
            $configReq = Http::timeout(15)->withoutVerifying()->withHeaders($headers)
                ->post("$base/info?pc=$pc&rid=$rid&token=$token");
            $raceData = $configReq->json()['data'] ?? [];

            // Fetch Athletes
            $athletes = [];
            $page = 1;
            do {
                $res = Http::timeout(15)->withoutVerifying()->withHeaders($headers)
                    ->post("$base/bio?pc=$pc&rid=$rid&token=$token&page=$page");
                $pageData = $res->json()['data'] ?? [];
                $athletes = array_merge($athletes, $pageData);
                $page++;
            } while (count($pageData) >= 50 && $page <= 20);

            // Fetch Main Scores
            $baseScores = [];
            $page = 1;
            do {
                $res = Http::timeout(15)->withoutVerifying()->withHeaders($headers)
                    ->post("$base/score?pc=$pc&rid=$rid&token=$token&page=$page");
                $pageData = $res->json()['data'] ?? [];
                $baseScores = array_merge($baseScores, $pageData);
                $page++;
            } while (count($pageData) >= 50 && $page <= 20);

            // Fetch Split Scores
            $allSplits = [];
            $page = 1;
            do {
                $res = Http::timeout(15)->withoutVerifying()->withHeaders($headers)
                    ->post("$base/splitScore?pc=$pc&rid=$rid&token=$token&page=$page");
                $pageData = $res->json()['data'] ?? [];
                $allSplits = array_merge($allSplits, $pageData);
                $page++;
            } while (count($pageData) >= 50 && $page <= 20);

            // Merge Logic
            $groupedSplits = collect($allSplits)->groupBy('AthleteId');
            $finalScores = collect($baseScores)->map(function ($score) use ($groupedSplits) {
                $athleteId = $score['AthleteId'];
                $score['TimingPoints'] = $groupedSplits->get($athleteId, collect())->values()->all();
                return $score;
            })->all();

            return [
                'raceData' => $raceData,
                'athletes' => $athletes,
                'scores'   => $finalScores,
                'last_updated' => now()->toDateTimeString()
            ];

        } catch (\Exception $e) {
            Log::error("RaceTiger API Error: " . $e->getMessage());
            return null;
        }
    });

    if (!$data) {
        return "The timing service is currently unavailable. Please refresh in a moment.";
    }

    // 2. Manual Pagination Logic (10 per page)
    $perPage = 10;
    $currentPage = (int) $request->input('page', 1);
    $allScores = collect($data['scores']);
    
    // Slice the collection to get only the items for the current page
    $pagedData = $allScores->slice(($currentPage - 1) * $perPage, $perPage)->values();

    // Create the Paginator instance
    $paginatedScores = new LengthAwarePaginator(
        $pagedData, 
        $allScores->count(), 
        $perPage, 
        $currentPage, 
        [
            'path'  => $request->url(),
            'query' => $request->query(),
        ]
    );

    // 3. Return View with Paginated Scores
    return view('score', [
        'raceData'     => $data['raceData'],
        'athletes'     => $data['athletes'],
        'scores'       => $paginatedScores, // Use this in your @foreach
        'last_updated' => $data['last_updated']
    ]);
});


Route::get('/staff/scanner', function () {
    return view('staff.scanner');
})->name('staff.scanner');
/*
|--------------------------------------------------------------------------
| Dinner Registration Flow (Public)
|--------------------------------------------------------------------------
*/
Route::prefix('dinner')->name('dinner.')->group(function () {
    // 1. List all available dinners
    Route::get('/', [DinnerController::class, 'index'])->name('index');
    
    // 2. Select Ticket Type (Standard/VIP) for a specific dinner
    Route::get('/tickets/{id}', [DinnerController::class, 'selectTickets'])->name('tickets');
    
    // 3. Form to enter Guest Name, Email, Phone
    Route::get('/register', [DinnerController::class, 'registerPage'])->name('register');
    
    // 4. Temporary store and redirect to checkout
    Route::post('/register/store', [DinnerController::class, 'storeRegistration'])->name('register.store');
    
    // 5. Review page
    Route::get('/checkout', [DinnerController::class, 'checkoutPage'])->name('checkout');
    
    // 6. Final save to database
    Route::post('/process', [DinnerController::class, 'process'])->name('process');
    
    // 7. Success page
    Route::get('/confirmation/{id}', [DinnerController::class, 'confirmation'])->name('confirmation');
    
    // 8. Handle payment slip upload
    Route::post('/upload-payment/{id}', [DinnerController::class, 'uploadPayment'])->name('upload.payment');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::get('/login', [RegisterController::class, 'showLoginForm'])->name('login');
Route::post('/login', [RegisterController::class, 'login'])->name('login.submit');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/')->with('success', 'You have been logged out!');
})->name('logout');
Route::get('/get-new-bib', [TicketController::class, 'getNewBib']);
/*
|--------------------------------------------------------------------------
| Protected Routes (Athlete Registration)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/ticket', [TicketController::class, 'showTicket'])->name('ticket');
    Route::post('/select-race', [AthleteController::class, 'handleSelection'])->name('athlete.selection.handle');
    Route::match(['get', 'post'], '/payment/initiate/{id}', [App\Http\Controllers\TicketController::class, 'initiatePayment'])->name('initiatePayment');
    // Route::post('/payment/initiate/{id}', [TicketController::class, 'initiatePayment_s'])->name('initiatePayment_s');
    Route::post('/payment/verify', [PaymentController::class, 'verifyPayment'])->name('payment.verify');
    Route::get('/register-athlete', [AthleteController::class, 'showAthleteForm'])->name('athlete.register');
    Route::post('/register-athlete', [AthleteController::class, 'submit'])->name('athlete.register.submit');
   Route::post('/register-friend', [AthleteController::class, 'submitFriend'])->name('friend.register.submit');
   Route::get('/register-friend', [AthleteController::class, 'showFriendRegisterForm'])->name('friend.register');
    Route::get('/checkout/review', [TicketController::class, 'showReviewPage'])->name('checkout.review');
    Route::get('/payment/method', [TicketController::class, 'showPaymentMethod'])->name('payment.method');
    Route::post('/payment/select', [TicketController::class, 'selectPaymentMethod'])->name('payment.method.post');
    Route::post('/checkout/process', [TicketController::class, 'processPayment'])->name('tickets.process-payment');
    Route::post('/user/dashboard/change-password', [App\Http\Controllers\UserController::class, 'updatePassword'])->name('user.password.update');
    Route::get('/user/dashboard/change-password', [App\Http\Controllers\UserController::class, 'showChangePasswordForm'])->name('user.password.change');
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/ticket/preview/{id}', [TicketController::class, 'previewPDF'])->name('ticket.preview');
    Route::post('/athlete/verify-friend', [AthleteController::class, 'verifyFriend'])
    ->name('athlete.verify.friend');
    Route::get('/athlete/registration/{event}/edit', [AthleteController::class, 'editRegistration'])
        ->name('athlete.registration.edit');

    Route::put('/athlete/registration/{event}', [AthleteController::class, 'updateRegistration'])
        ->name('athlete.registration.update');
    });
    
Route::get('/ticket/download/{id}', [TicketController::class, 'downloadPNG'])->name('ticket.download');

Route::middleware(['auth:admin,agent'])->group(function () {
Route::get('/tickets/export/excel', [TicketController::class, 'exportExcel'])->name('dashboard.tickets.export');
Route::get('/events/ticket/{event}', [TicketController::class, 'dashboard'])->name('dashboard.events.ticket');

});
/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['admin'])->prefix('dashboard')->group(function () {
    Route::get('/register/1', [UserController::class, 'dashboard'])->name('dashboard.register-level-1');
    Route::get('/register/2', [AthleteController::class, 'dashboard'])->name('dashboard.register-level-2');

    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');
    
    Route::get('/ticket-management', [TicketController::class, 'index'])->name('dashboard.tickets.index');
    Route::post('/update-ticket-info', [TicketController::class, 'updateId'])->name('tickets.updateId');
    Route::get('/events/{status}', [EventController::class, 'index'])
          ->where('status', 'now|past|coming')
          ->name('events.index');

          
          Route::resource('events', EventController::class)->except(['index', 'show']);
          Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
          
          // Dinner Management
          Route::get('/dinner-management/{timeframe}', [DinnerController::class, 'manageDinners'])->name('admin.dinner.manage');
          Route::get('/dinner/create', [DinnerController::class, 'create'])->name('admin.dinner.create');
          Route::post('/dinner/store', [DinnerController::class, 'store'])->name('admin.dinner.store');
          Route::get('/dinner/{id}/edit', [DinnerController::class, 'edit'])->name('admin.dinner.edit');
          Route::put('/dinner/update/{id}', [DinnerController::class, 'update'])->name('admin.dinner.update');
          Route::delete('/dinner/{id}', [DinnerController::class, 'destroy'])->name('admin.dinner.destroy');
          Route::post('/dinner/{id}/toggle-scan', [App\Http\Controllers\DinnerController::class, 'toggleScanning'])
          ->name('admin.dinner.toggle-scan');
          
          // Dinner Tickets (Master/Detail)
          Route::get('/dinner-tickets', [DinnerController::class, 'dinnerTicketsIndex'])->name('admin.dinner.tickets.index');
          Route::get('/dinner-tickets/{id}', [DinnerController::class, 'showDinnerTickets'])->name('admin.dinner.tickets.show');
          Route::post('/dinner-tickets/{id}/approve', [DinnerController::class, 'adminApprove'])->name('admin.dinner.approve');
          Route::post('/dinner-tickets/reject/{id}', [DinnerController::class, 'adminReject'])->name('admin.dinner.reject');
          
          Route::get('/sponsors/{status}', [SponsorController::class, 'index'])
          ->where('status', 'now|past') // Only allow these two words
          ->name('admin.sponsor.index');
          Route::get('/sponsors/create', [SponsorController::class, 'create'])->name('admin.sponsor.create');
          Route::post('/sponsors/store', [SponsorController::class, 'store'])->name('admin.sponsor.store');
          Route::get('/sponsors/details/{id}', [SponsorController::class, 'show'])->name('admin.sponsor.show');
          Route::post('/sponsors/toggle/{id}', [SponsorController::class, 'toggleStatus'])->name('admin.sponsor.toggle');
          Route::get('/sponsor/{id}/batch-print', [SponsorController::class, 'batchPrint'])->name('admin.sponsor.batchPrint');
          Route::post('/tickets/approve/{id}', [TicketController::class, 'approve'])->name('tickets.approve');
          Route::post('/tickets/reject/{id}', [TicketController::class, 'reject'])->name('tickets.reject');
          Route::post('/tickets/{id}/mark-printed', [TicketController::class, 'markPrinted'])->name('tickets.markPrinted');
          Route::post('/tickets/{id}/reprint', [TicketController::class, 'reprint']);
          }); 

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth:admin']], function () {
    
    // Admin Management
    Route::get('/admins', [AdminManagementController::class, 'index'])
        ->name('admin.admins.index');
        
    Route::get('/admins/create', [AdminManagementController::class, 'create'])
        ->name('admin.admins.create');
        
    // CHANGE THIS NAME TO MATCH YOUR BLADE FORM
    Route::post('/admins/store', [AdminManagementController::class, 'store'])
        ->name('admin.store'); 

        Route::delete('/admins/{id}', [AdminManagementController::class, 'destroy'])->name('admin.admins.destroy');

        Route::get('/admins/{id}/edit', [AdminManagementController::class, 'edit'])->name('admin.admins.edit');
    Route::put('/admins/{id}/update', [AdminManagementController::class, 'update'])->name('admin.admins.update');
    Route::get('/agents/create', [AgentController::class, 'create'])->name('admin.agents.create');
    Route::post('/agents/store', [AgentController::class, 'store'])->name('admin.agents.store');
    Route::get('/agents/{id}/edit', [AgentController::class, 'edit'])->name('admin.agents.edit');
    Route::put('/agents/{id}/update', [AgentController::class, 'update'])->name('admin.agents.update');
    Route::delete('/agents/{id}', [AgentController::class, 'destroy'])->name('admin.agents.destroy');
});
Route::post('/api/verify-ticket', [DinnerController::class, 'publicVerify'])->name('dinner.verify');
// Route::get('/test-kbz/{id}', function($id) {
//     // Fake session for testing
//     session(['checkout_data' => ['price' => '1000']]);

//     // Fake login if required
//     Auth::loginUsingId(1); // Use a real user id

//     return app(App\Http\Controllers\TicketController::class)
//         ->initiatePayment($id);
// });
Route::get('/api/validate-discount', function (Illuminate\Http\Request $request) {
    // 1. Find the code in the new table
    $codeRecord = \App\Models\SponsorCode::where('code', $request->code)->first();

    if (!$codeRecord) {
        return response()->json(['success' => false, 'message' => 'Invalid sponsor code.']);
    }

    // 2. NEW LOGIC: Check if quota is full instead of is_used
    if ($codeRecord->used_count >= $codeRecord->max_uses) {
        return response()->json(['success' => false, 'message' => 'This code has reached its guest limit.']);
    }

    // 3. Optional: Check if the sponsor is active
    if ($codeRecord->sponsor->status !== 'active') {
        return response()->json(['success' => false, 'message' => 'This sponsor is currently inactive.']);
    }

    // 4. Return the discount from the SponsorCode record
    return response()->json([
        'success' => true,
        'discount' => $codeRecord->discount, // This is the % off
        'message' => 'Code applied!'
    ]);
});
Route::get('/register/consent', [AthleteController::class, 'showConsent'])->name('athlete.consent');
/*
|--------------------------------------------------------------------------
| External Callbacks
|--------------------------------------------------------------------------
*/
Route::post('/payment/kbz/callback', [TicketController::class, 'kbzCallback'])->name('kbz.callback');
Route::post('/test-kbz-package', [TicketController::class, 'testPackageCallback']);
Route::get('/payment/status/{id}', [TicketController::class, 'checkStatus']);

Route::get('/race', [RaceController::class, 'showPublicRaces'])->name('public.races');

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Management CRUD Endpoints
    Route::get('/races', [RaceController::class, 'index'])->name('races.index');
    Route::post('/races', [RaceController::class, 'store'])->name('races.store');
    Route::put('/races/{id}', [RaceController::class, 'update'])->name('races.update'); // <-- Added for editing
    Route::patch('/races/{id}/toggle', [RaceController::class, 'toggleStatus'])->name('races.toggle');
    Route::delete('/races/{id}', [RaceController::class, 'destroy'])->name('races.destroy');
    Route::delete('/races/cards/{id}', [RaceController::class, 'destroyCard'])->name('races.card.destroy'); // <-- Added for single card removal

    // Your remaining admin routes live safely here...
});

Route::post('/apply-promo', [PromoCodeController::class, 'apply'])->name('promo.apply');

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Specific promo code routes (must be defined BEFORE wildcard {id})
    Route::get('/promo-codes', [PromoCodeController::class, 'index'])->name('promo_codes.index');
    Route::get('/promo-codes/create', [PromoCodeController::class, 'create'])->name('promo_codes.create');
    Route::post('/promo-codes', [PromoCodeController::class, 'store'])->name('promo_codes.store');
    Route::get('/promo-codes/export-group', [PromoCodeController::class, 'exportGroup'])->name('promo_codes.export_group');
    Route::delete('/promo-codes/destroy-group', [PromoCodeController::class, 'destroyGroup'])->name('promo_codes.destroy_group');
    
    // Dynamic parameter route
    Route::delete('/promo-codes/{id}', [PromoCodeController::class, 'destroy'])->name('promo_codes.destroy');
});