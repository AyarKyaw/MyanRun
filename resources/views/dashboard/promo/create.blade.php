@extends('dashboard.layouts.master')

@section('content')
<style>
    /* Improve contrast and readability for inputs */
    .form-control, .form-select {
        color: #1a1d20 !important;
        font-weight: 500 !important;
        background-color: #ffffff !important;
        border: 1px solid #ced4da !important;
    }

    /* Muted placeholder text so input text stands out */
    .form-control::placeholder {
        color: #a0aec0 !important;
        opacity: 1 !important;
        font-weight: 400 !important;
    }

    /* Darker text styling when typing or read-only */
    .form-control:focus {
        color: #000000 !important;
        background-color: #ffffff !important;
        border-color: #4361ee !important;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15) !important;
    }

    .form-control[readonly] {
        background-color: #f8f9fa !important;
        color: #6c757d !important;
    }
</style>

<div class="content-body">
    <div class="container-fluid">
        <div class="page-title">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.promo_codes.index') }}">Promo Codes</a></li>
                    <li class="breadcrumb-item active">Generate Single-Use Promo Codes</li>
                </ol>
            </nav>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.promo_codes.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <!-- Main Form Details -->
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Promo Code Generation</h4>
                            <span class="badge badge-info">Single-Use Codes</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Promo Code Group Name -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label text-black fw-bold">Organization / Company / Group</label>
                                    <input type="text" name="group_name" class="form-control" placeholder="e.g. Bagan Marathon 21KM" value="{{ old('group_name', old('name')) }}" required>
                                    <small class="text-muted">A group name to classify and view these generated codes together.</small>
                                </div>

                                <!-- Event Selection -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Select Event</label>
                                    <select name="event_id" id="event_id" class="form-control" onchange="filterTickets()" required>
                                        <option value="">-- Select Event --</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                                {{ $event->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Dynamic Ticket Type Selection -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Applicable Ticket Type (Optional)</label>
                                    <select name="event_ticket_type_id" id="event_ticket_type_id" class="form-control">
                                        <option value="">-- All Ticket Types --</option>
                                    </select>
                                    <small class="text-muted">Leave as "All Ticket Types" to apply to every category in this event.</small>
                                </div>

                                <!-- Prefix Field (Default set to PRM) -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Code Prefix</label>
                                    <input type="text" name="prefix" id="prefix" class="form-control text-uppercase" placeholder="e.g. PRM" value="{{ old('prefix', 'PRM') }}" oninput="toggleCodeInput()">
                                    <small class="text-muted">Prefix appended to code(s) (e.g. PRM-XXXXX).</small>
                                </div>

                                <!-- Quantity -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Quantity (Number of Codes)</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', 1) }}" min="1" max="1000" oninput="toggleCodeInput()" required>
                                    <small class="text-muted">How many unique codes to generate.</small>
                                </div>

                                <!-- Custom Promo Code String (Optional for Qty = 1) -->
                                <div class="mb-3 col-md-6" id="code_input_wrapper">
                                    <label class="form-label text-black fw-bold">Custom Promo Code String</label>
                                    <input type="text" name="code" id="code" class="form-control text-uppercase" placeholder="e.g. PRM-VIPRUN2026" value="{{ old('code') }}">
                                    <small class="text-muted">Leave empty to auto-generate random code string.</small>
                                </div>

                                <!-- Discount Amount -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Discount Amount (MMK)</label>
                                    <input type="number" step="0.01" name="amount" class="form-control" placeholder="e.g. 5000" value="{{ old('amount') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Actions -->
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-2">Generate Codes</button>
                            <a href="{{ route('admin.promo_codes.index') }}" class="btn btn-light w-100">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Map event ticket data into JavaScript directly
const eventTicketsMap = {
    @foreach($events as $event)
        "{{ $event->id }}": [
            @foreach($event->eventTicketTypes as $ticketType)
                @php
                    $priceText = isset($ticketType->national_price) || isset($ticketType->foreign_price)
                        ? 'Local: ' . number_format($ticketType->national_price ?? $ticketType->foreign_price) . ' MMK'
                        : 'Price: ' . number_format($ticketType->price) . ' MMK';
                @endphp
                {
                    id: "{{ $ticketType->id }}",
                    name: @json($ticketType->name),
                    priceText: @json($priceText)
                },
            @endforeach
        ],
    @endforeach
};

const selectedOldTicketId = "{{ old('event_ticket_type_id') }}";

function toggleCodeInput() {
    const qty = parseInt(document.getElementById('quantity').value) || 1;
    const rawPrefix = document.getElementById('prefix').value.trim().toUpperCase();
    const prefix = rawPrefix || 'PRM';
    const codeInput = document.getElementById('code');
    
    const prefixText = prefix + '-';

    if (qty > 1) {
        codeInput.value = '';
        codeInput.placeholder = 'Auto-generated for bulk (' + prefixText + 'XXXXXX)';
        codeInput.readOnly = true;
    } else {
        codeInput.placeholder = 'e.g. ' + prefixText + 'VIPRUN2026';
        codeInput.readOnly = false;
    }
}

function filterTickets() {
    const selectedEventId = document.getElementById('event_id').value;
    const ticketSelect = document.getElementById('event_ticket_type_id');

    // Clear existing options
    ticketSelect.innerHTML = '<option value="">-- All Ticket Types --</option>';

    if (selectedEventId && eventTicketsMap[selectedEventId]) {
        eventTicketsMap[selectedEventId].forEach(function(ticket) {
            const option = document.createElement('option');
            option.value = ticket.id;
            option.textContent = ticket.name + ' (' + ticket.priceText + ')';
            
            if (selectedOldTicketId && selectedOldTicketId == ticket.id) {
                option.selected = true;
            }
            
            ticketSelect.appendChild(option);
        });
    }

    // Refresh select picker if Bootstrap Select/Select2 plugin is present
    if (typeof $(ticketSelect).selectpicker === 'function') {
        $(ticketSelect).selectpicker('refresh');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleCodeInput();
    filterTickets();
});
</script>
@endsection