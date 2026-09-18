<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PromoCodeController extends Controller
{
    /**
     * Apply and validate promo code via AJAX on checkout
     */
    public function apply(Request $request)
    {
        $request->validate([
            'code'                 => 'required|string',
            'event_id'             => 'required|exists:events,id',
            'original_price'       => 'required|numeric',
            'event_ticket_type_id' => 'required|exists:event_ticket_types,id',
        ]);

        $promo = PromoCode::with('eventTicketTypes')
            ->where('code', strtoupper(trim($request->code)))
            ->where('event_id', $request->event_id)
            ->first();

        // 1. Check if code exists and belongs to event
        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid promo code for this event.'
            ], 422);
        }

        // 2. Check if code is already used (single-use)
        if ($promo->is_used) {
            return response()->json([
                'success' => false,
                'message' => 'This promo code has already been used.'
            ], 422);
        }

        // 3. Check ticket type restrictions (if specific ticket type is configured)
        $allowedTicketTypeIds = $promo->eventTicketTypes->pluck('id')->toArray();
        if (!empty($allowedTicketTypeIds) && !in_array($request->event_ticket_type_id, $allowedTicketTypeIds)) {
            return response()->json([
                'success' => false,
                'message' => 'This promo code is not valid for the selected ticket category.'
            ], 422);
        }

        $originalPrice  = (float) $request->original_price;
        $discountAmount = (float) $promo->amount;
        $finalPrice     = max(0, $originalPrice - $discountAmount);

        session([
            'applied_promo' => [
                'id'       => $promo->id,
                'code'     => $promo->code,
                'discount' => $discountAmount,
            ]
        ]);

        return response()->json([
            'success'     => true,
            'message'     => $finalPrice === 0.0 ? 'Promo covers full price! Ticket will be auto-approved.' : 'Promo code applied!',
            'discount'    => $discountAmount,
            'final_price' => $finalPrice,
            'is_free'     => ($finalPrice === 0.0),
        ]);
    }

    /**
     * Display list of promo codes in Admin Dashboard
     */
    public function index(Request $request)
{
    $query = PromoCode::with(['event', 'eventTicketTypes']);

    // Filter by group name or code if search query present
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('group_name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%");
        });
    }

    // Fetch codes ordered by group and creation time
    $allCodes = $query->latest()->get();

    // Group the models by group_name (defaulting 'Uncategorized' for missing group names)
    $groupedPromoCodes = $allCodes->groupBy(function ($item) {
        return $item->group_name ?? 'Uncategorized';
    });

    return view('dashboard.promo.index', compact('groupedPromoCodes'));
}

    /**
     * Show view to bulk generate promo codes
     */
    public function create()
    {
        $events = Event::with('eventTicketTypes')->get();
        return view('dashboard.promo.create', compact('events'));
    }

    /**
     * Generate promo codes under a group name and sync ticket type relationship
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_name'           => 'required|string|max:255',
            'prefix'               => 'nullable|string|max:20',
            'event_id'             => 'required|exists:events,id',
            'amount'               => 'required|numeric|min:0',
            'quantity'             => 'required|integer|min:1|max:1000',
            'code'                 => 'nullable|string|max:50',
            'event_ticket_type_id' => 'nullable|exists:event_ticket_types,id',
        ]);

        $quantity = (int) $request->quantity;
        $groupName = $request->group_name;
        
        // Use prefix provided by request or fallback to PRM
        $prefix = $request->filled('prefix') 
            ? strtoupper(trim($request->prefix)) 
            : 'PRM';

        for ($i = 0; $i < $quantity; $i++) {
            // If single generation and custom code supplied, use it directly. Otherwise append random code to prefix.
            $codeStr = ($quantity === 1 && $request->filled('code')) 
                ? strtoupper(trim($request->code)) 
                : $prefix . '-' . strtoupper(Str::random(8));

            $promo = PromoCode::create([
                'group_name' => $groupName,
                'code'       => $codeStr,
                'amount'     => $request->amount,
                'event_id'   => $request->event_id,
                'is_used'    => false,
            ]);

            // Sync with selected single ticket type option
            if ($request->filled('event_ticket_type_id')) {
                $promo->eventTicketTypes()->sync([$request->event_ticket_type_id]);
            }
        }

        return redirect()->route('admin.promo_codes.index')
            ->with('success', "{$quantity} promo code(s) generated under group '{$groupName}' successfully!");
    }

    /**
     * Delete a promo code
     */
    public function destroy($id)
    {
        $promo = PromoCode::findOrFail($id);
        $promo->delete();

        return back()->with('success', 'Promo code deleted successfully!');
    }

    public function exportGroup(Request $request)
{
    $groupName = $request->query('group_name');

    if (!$groupName) {
        return back()->with('error', 'Group name is required for export.');
    }

    $promoCodes = PromoCode::with(['event', 'eventTicketTypes'])
        ->where('group_name', $groupName)
        ->latest()
        ->get();

    $fileName = 'promo_group_' . \Str::slug($groupName) . '_' . date('Y-m-d') . '.csv';

    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $callback = function () use ($promoCodes) {
        $file = fopen('php://output', 'w');
        
        // Add CSV Headers
        fputcsv($file, ['Group Name', 'Code', 'Event', 'Ticket Types', 'Amount (MMK)', 'Status', 'Created At']);

        foreach ($promoCodes as $promo) {
            $ticketTypes = $promo->eventTicketTypes->pluck('name')->implode(', ');

            fputcsv($file, [
                $promo->group_name,
                $promo->code,
                $promo->event->name ?? 'N/A',
                $ticketTypes ?: 'All Categories',
                $promo->amount,
                $promo->is_used ? 'Used' : 'Available',
                $promo->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($file);
    };

    return new StreamedResponse($callback, 200, $headers);
}

/**
 * Delete all promo codes in a specific group.
 */
public function destroyGroup(Request $request)
{
    // Validate request input
    $validator = \Validator::make($request->all(), [
        'group_name' => 'required|string',
        'password'   => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => $validator->errors()->first()
        ], 422);
    }

    // Verify current authenticated admin password
    if (!Hash::check($request->password, $request->user()->password)) {
        return response()->json([
            'message' => 'The provided password does not match our records.'
        ], 422);
    }

    $groupName = $request->input('group_name');

    // Delete promo codes and detach pivot entries
    $promoCodes = PromoCode::where('group_name', $groupName)->get();

    foreach ($promoCodes as $promo) {
        $promo->eventTicketTypes()->detach();
        $promo->delete();
    }

    return response()->json([
        'success'    => true,
        'group_name' => $groupName,
        'message'    => "Promo code group \"{$groupName}\" deleted successfully."
    ]);
}
}