@extends('dashboard.layouts.master')

@section('content') 
<style>
    body {
        height: 2000px;
    }
    /* Table & Scrollbar Styling */
    .table-responsive {
        width: 100%;
        overflow-x: auto; 
        display: block;
        -webkit-overflow-scrolling: touch;
    }
    .table-responsive::-webkit-scrollbar { height: 6px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
    .table-responsive::-webkit-scrollbar-track { background: #f1f1f1; }

    #ticketTable td, #ticketTable th {
        white-space: nowrap;
        vertical-align: middle;
    }

    /* Tab Styling */
    .nav-tabs { border-bottom: 2px solid #f1f1f1; margin-bottom: 20px; border-top: 0; }
    .nav-tabs .nav-link { border: none; color: #6e6e6e; font-weight: 600; padding: 1rem 1.5rem; }
    .nav-tabs .nav-link.active {
        color: #C3E92D !important; 
        background: transparent;
        border-bottom: 3px solid #C3E92D;
    }

    /* Table Content Styling */
    .table td { font-size: 16px !important; padding: 15px 15px !important; color: #333; }
    .table th { font-size: 13px !important; text-transform: uppercase; letter-spacing: 0.5px; color: #888; background: #fcfcfc; }
    
    #tableSearch { font-size: 16px !important; height: 50px !important; border-radius: 10px 0 0 10px !important; }
    .table td small.text-muted { font-size: 13px !important; }

    /* Modal Styling Enhancements */
    .modal-content { border-radius: 20px; border: none; overflow: hidden; }
    .modal-header { background: #fff; border-bottom: 1px solid #f1f1f1; padding: 25px 30px; }
    .info-section { background: #f8f9fa; border-radius: 15px; padding: 20px; height: 100%; border: 1px solid #eee; }
    .section-title { font-size: 12px; text-transform: uppercase; letter-spacing: 1.2px; color: #999; margin-bottom: 15px; display: block; font-weight: 800; }
    
    .custom-table tr td { border: none; padding: 6px 0; font-size: 14px; }
    .custom-table tr td:first-child { color: #777; width: 45%; }
    
    .nrc-group .form-control { 
        border: 1px solid #e2e2e2; 
        font-size: 13px; 
        padding: 6px 8px;
        background-color: #fff;
        border-radius: 6px;
    }
    
    #modal-transaction-img {
        transition: all 0.3s ease;
        border: 5px solid #fff;
        border-radius: 12px;
    }
    #modal-transaction-img:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

    /* Stats Card Styling */
    .stat-widget { background: #fff; padding: 20px; border-radius: 15px; height: 100%; }
    .progress-thin { height: 6px; border-radius: 10px; background: #eee; }

    .container, .container-fluid, .row {
        /* If you find the parent has overflow: hidden, use this to override */
        overflow: visible !important;
    }

    @media print {

    @page {
        size: 80mm auto;
        margin: 0 !important;
    }

    body {
        margin: 0 !important;
        padding: 0 !important;
    }

    body * {
        visibility: hidden !important;
    }

    .print-active, .print-active * {
        visibility: visible !important;
    }

    .print-active {
        position: fixed;

        top: 40%;
        left: 50%;

        transform: translate(-50%, -50%) scale(1.4);
        transform-origin: center;

        width: 90mm !important;
        margin: 0 !important;
        padding: 0 !important;

        text-align: center !important;
    }

    /* ✅ Force all inner elements to center */
    .print-active div,
    .print-active span {
        text-align: center !important;
    }

    .print-header {
        text-align: center !important;
    }

    .bib-number {
        font-size: 35pt;
        font-weight: 900;
        padding-bottom: 8mm;
        border-bottom: 3px solid #000;
        margin-bottom: 8mm;
    }

    .tshirt-size {
        font-size: 80pt;
        font-weight: 900;
        line-height: 1;
        margin: 8mm 0;
    }

    .category {
        font-size: 14pt;
        font-weight: 900;
        margin-bottom: 5mm;
    }

    .runner-name {
        font-size: 14pt;
        font-weight: 900;
        text-transform: uppercase;
        margin-bottom: 6mm;
        word-break: break-word;
    }

    .nrc {
        font-size: 14pt;
        margin-top: 4mm;
    }

    .address {
        font-size: 12pt;
        margin-top: 3mm;
    }

    .event-footer {
        margin-top: 10mm;
        border-top: 2px dashed #000;
        padding-top: 5mm;
        font-size: 10pt;
    }
}
</style>       

<main class="content-body">
    <div class="container-fluid">
        <div class="page-title d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1>Event Tickets</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">Event Tickets</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-4 col-md-5 mb-3">
                <div class="d-flex flex-column gap-2">
                    <!-- <a href="#" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center rounded-3 shadow-sm py-3">
                        <i class="fa fa-plus-circle me-2 fs-20"></i> <span class="fw-bold">New Customer Registration</span>
                    </a> -->

                    <div class="dropdown">
                        <button type="button" class="btn btn-light border btn-lg w-100 rounded-3 dropdown-toggle d-flex align-items-center justify-content-between" data-bs-toggle="dropdown">
                            <span><i class="fa fa-file-excel me-2 text-success"></i>Export {{ ucfirst(request('status', 'all')) }}</span>
                        </button>
                        <ul class="dropdown-menu w-100 shadow-lg border-0 mt-2">
                            {{-- Status Filters --}}
                            <li><h6 class="dropdown-header">Status</h6></li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('dashboard.tickets.export', ['status' => 'all', 'event' => $event->id, 'category' => request('category', 'all'), 'print_status' => request('print_status', 'all')]) }}">
                                    All Status
                                </a>
                            </li>
                            @if(auth('admin')->user()->role === 'supporter')
                            <div class="dropdown-divider"></div>
                            
                            {{-- Print Status Filters --}}
                            <li><h6 class="dropdown-header">Print Status</h6></li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('dashboard.tickets.export', ['event' => $event->id, 'print_status' => 'all', 'category' => request('category', 'all'), 'status' => request('status', 'all')]) }}">
                                    All (Printed & Not Printed)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('dashboard.tickets.export', ['event' => $event->id, 'print_status' => 'printed', 'category' => request('category', 'all'), 'status' => request('status', 'all')]) }}">
                                    Only Printed
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('dashboard.tickets.export', ['event' => $event->id, 'print_status' => 'not_printed', 'category' => request('category', 'all'), 'status' => request('status', 'all')]) }}">
                                    Only Not Printed
                                </a>
                            </li>
                            @endif
                            <div class="dropdown-divider"></div>
                            
                            {{-- Category Filters --}}
                            <li><h6 class="dropdown-header">Categories</h6></li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('dashboard.tickets.export', ['event' => $event->id, 'category' => 'all', 'print_status' => request('print_status', 'all'), 'status' => request('status', 'all')]) }}">
                                    All Categories
                                </a>
                            </li>
                            @foreach($event->ticketTypes as $type)
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('dashboard.tickets.export', ['event' => $event->id, 'category' => $type->name, 'print_status' => request('print_status', 'all'), 'status' => request('status', 'all')]) }}">
                                        {{ $type->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-8 col-md-7">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                {{-- Wrap this block so it only shows for Admins (not Supporters) --}}
                                @if(auth('admin')->user()->role !== 'supporter')
                                    <div class="d-flex gap-4 mb-4">
                                        <div>
                                            <p class="mb-0 fs-11 text-muted text-uppercase fw-bold">Total Registered</p>
                                            <h5 class="mb-0 text-dark fw-bold">{{ number_format($totalTickets) }}</h5>
                                        </div>
                                        <div class="vr" style="opacity: 0.1;"></div>
                                        <div>
                                            <p class="mb-0 fs-11 text-muted text-uppercase fw-bold">Event Capacity</p>
                                            <h5 class="mb-0 text-dark fw-bold">{{ number_format($totalApproved ?? 0) }} / 
                                                {{ $eventLimit > 0 ? number_format($eventLimit) : 'Unlimited' }}</h5>
                                        </div>
                                    </div>
                                @endif

                                {{-- Operational Stats (Always visible or adjusted as per your logic) --}}
                                <div class="d-flex align-items-center gap-4 mb-3">
                                    {{-- Total Approved --}}
                                    <div>
                                        <p class="mb-0 fs-12 text-muted text-uppercase fw-bold">Approved</p>
                                        <h2 class="mb-0 text-primary fw-bold">{{ number_format($totalApproved ?? 0) }}</h2>
                                    </div>
                                    
                                    <div class="vr mx-2" style="height: 40px; opacity: 0.1;"></div>

                                    {{-- Total Printed --}}
                                    @if(auth('admin')->user()->role === 'supporter')
                                        <div>
                                            <p class="mb-0 fs-12 text-muted text-uppercase fw-bold">Printed</p>
                                            <h2 class="mb-0 text-success fw-bold">{{ number_format($totalPrinted ?? 0) }}</h2>
                                        </div>
                                        
                                        <div class="vr mx-2" style="height: 40px; opacity: 0.1;"></div>

                                        {{-- To Print --}}
                                        <div>
                                            <p class="mb-0 fs-12 text-muted text-uppercase fw-bold">To Print</p>
                                            <h2 class="mb-0 text-warning fw-bold">{{ number_format($toPrint ?? 0) }}</h2>
                                        </div>
                                    @endif
                                </div>

                                {{-- Progress Bar remains the same --}}
                                @if(($totalApproved ?? 0) > 0)
                                    <div class="progress progress-thin">
                                        @php 
                                            $percent = (($totalPrinted ?? 0) / $totalApproved) * 100; 
                                        @endphp
                                        <div class="progress-bar bg-success" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <p class="fs-12 text-muted mt-2 mb-0">
                                        {{ number_format($percent, 1) }}% of approved tickets printed
                                    </p>
                                @else
                                    <p class="fs-12 text-muted mt-2 mb-0">No approved tickets yet.</p>
                                @endif
                            </div>                          
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white pt-4 px-4 border-0">
                        <ul class="nav nav-tabs" role="tablist">
                        @if(auth('admin')->user()->role === 'supporter')
                            {{-- SUPPORTER VIEW: ONLY APPROVED TAB --}}
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('dashboard.events.ticket', ['status' => 'approved', 'event' => $eventName]) }}">
                                    Approved Tickets <span class="badge rounded-pill bg-success ms-2">{{ $counts['approved'] ?? 0 }}</span>
                                </a>
                            </li>
                        @else
                            {{-- ADMIN VIEW: ALL TABS --}}
                            <li class="nav-item">
                                <a class="nav-link {{ request('status') == 'all' ? 'active' : '' }}" 
                                href="{{ route('dashboard.events.ticket', ['status' => 'all', 'event' => $eventName]) }}">
                                    All <span class="badge rounded-pill bg-secondary ms-2">{{ $counts['all'] ?? 0 }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request('status', 'pending') == 'approved' ? 'active' : '' }}" 
                                href="{{ route('dashboard.events.ticket', ['status' => 'approved', 'event' => $eventName]) }}">
                                    Approved <span class="badge rounded-pill bg-success ms-2">{{ $counts['approved'] ?? 0 }}</span>
                                </a>
                            </li>
                            @foreach(['pending' => 'warning', 'rejected' => 'danger'] as $status => $color)
                                <li class="nav-item">
                                    <a class="nav-link {{ request('status') == $status ? 'active' : '' }}" 
                                    href="{{ route('dashboard.events.ticket', ['status' => $status, 'event' => $eventName]) }}">
                                        {{ ucfirst($status) }} 
                                        <span class="badge rounded-pill bg-{{ $color }} ms-2">{{ $counts[$status] ?? 0 }}</span>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="ticketTable" style="min-width: 1200px;">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="80">ID</th>
                                        @if(auth('admin')->check() && in_array(auth('admin')->user()->role, ['supporter']))
                                        <th class="text-center" width="80">Print</th>
                                        <th class="text-center" width="80">Print At</th>
                                        @endif
                                        <th class="text-center" width="80">View</th>
                                        <th>Athlete Details</th>
                                        <th>BIB Number</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Event / Category</th>
                                        <th>Registration Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($customers as $customer)
                                    <tr>
                                        <td><span class="badge badge-outline-dark fs-14">{{ $customer->id }}</span></td>
                                        @if(auth('admin')->check() && in_array(auth('admin')->user()->role, ['supporter']))
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-sm {{ $customer->is_printed ? 'btn-warning' : 'btn-primary' }}" 
                                                    onclick="printAthleteSlip('slip-{{ $customer->id }}', {{ $customer->id }}, {{ $customer->is_printed ? 'true' : 'false' }})">
                                                <i class="fas fa-{{ $customer->is_printed ? 'exclamation-triangle' : 'print' }}"></i> 
                                                {{ $customer->is_printed ? 'Reprint' : 'Print Slip' }}
                                            </button>

                                            <div id="slip-{{ $customer->id }}" class="d-none">
                                                <div class="print-active" style="width:70mm; font-family:Arial Black; line-height:1.1;">

                                            <div class="bib-number">
                                                {{ $customer->bib_number ?? '0000' }}
                                            </div>

                                            <div class="tshirt-size">
                                                {{ $customer->tshirt_size ?? 'XL' }}
                                            </div>

                                            <div class="category">
                                                {{ $customer->category ?? 'RUN TYPE' }}
                                            </div>

                                            <div class="runner-name">
                                                {{ strtoupper($customer->bib_name ?? 'RUNNER NAME') }}
                                            </div>

                                            <div class="nrc">
                                                NRC: {{ $customer->athlete->id_number ?? '---' }}
                                            </div>

                                            <div class="address">
                                                {{ $customer->athlete->address ?? '' }}
                                            </div>

                                            <div class="event-footer">
                                                {{ strtoupper($event->name ?? 'EVENT') }} <br>
                                                {{ now()->format('d/m/Y H:i A') }}
                                            </div>

                                        </div>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-outline-dark fs-14">{{ $customer->printed_at ? \Carbon\Carbon::parse($customer->printed_at)->format('d M, H:i') : 'Not Printed' }}</span></td>
                                        @endif
                                        <td class="text-center">
                                            <button type="button" class="btn btn-light btn-sm border view-details" 
                                                data-bs-toggle="modal" data-bs-target="#ticketDetailsModal" 
                                                data-info="{{ json_encode($customer) }}" 
                                                data-image="{{ $customer->transaction_id ? asset('uploads/payments/' . $customer->transaction_id) : asset('images/no-image.png') }}">
                                                <i class="fa fa-eye text-primary"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-black">{{ $customer->athlete?->user?->full_name ?? 'Guest Runner' }}</span>
                                                <small class="text-muted">BIB: {{ $customer->bib_name }}</small>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-outline-dark fs-14">{{ $customer->bib_number }}</span></td>
                                        <td>
                                            @php $badgeClass = match($customer->status) { 'approved' => 'success', 'rejected' => 'danger', default => 'warning' }; @endphp
                                            <span class="badge badge-{{ $badgeClass }} text-dark badge-sm">{{ ucfirst($customer->status) }}</span>
                                        </td>
                                        <td><span class="fw-bold text-dark">{{ number_format($customer->price) }} <small>MMK</small></span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2 rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                    <i class="fa fa-running text-primary fs-12"></i>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-bold fs-14">{{ $customer->event }}</span>
                                                    <small class="text-muted">{{ $customer->category }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $customer->created_at->format('M d, Y') }}<br><small class="text-muted">{{ $customer->created_at->format('h:i A') }}</small></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="py-4">
                                                <img src="{{ asset('images/no-data.png') }}" alt="" style="width: 60px; opacity: 0.3;">
                                                <p class="mt-3 text-muted">No {{ request('status', 'pending') }} tickets found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 px-4 py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted fs-13">
                                Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of <strong>{{ $customers->total() }}</strong>
                            </div>
                            <div>
                                {{ $customers->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<div style="position: fixed; bottom: 30px; right: 30px; z-index: 1000; width: 600px;">
    <form action="{{ URL::current() }}" method="GET" class="shadow-lg rounded-4 overflow-hidden">
        <input type="hidden" name="status" value="{{ request('status') }}">
        
        <div class="input-group">
            <input type="text" id="tableSearch" name="search" class="form-control border-0 p-3" 
                   placeholder="Name or BIB..." value="{{ request('search') }}">
            <button class="btn btn-primary px-4" type="submit"><i class="fa fa-search"></i></button>
            
            @if(request('search'))
                <a href="{{ route('dashboard.events.ticket', ['event' => $eventName, 'status' => request('status')]) }}" 
                   class="btn btn-dark d-flex align-items-center px-3">
                    <i class="fa fa-times"></i>
                </a>
            @endif
        </div>
    </form>
</div>
<div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Printer Override</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>This slip was already printed. Enter password to reprint:</p>
                <input type="password" id="printerPassword" class="form-control" placeholder="Enter password">
                <input type="hidden" id="modalTicketId">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" onclick="verifyAndPrint()">Unlock & Print</button>
            </div>
        </div>
    </div>
</div>
{{-- Ticket Details Modal --}}
<div class="modal fade" id="ticketDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0">
                <h4 class="modal-title fw-bold">Runner Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/dashboard/update-ticket-info" method="POST" id="update-ticket-form">
                @csrf
                <input type="hidden" name="id" id="modal-ticket-id-input">
                <div class="modal-body px-4 pt-0">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-section">
                                <span class="section-title"><i class="fa fa-user-circle me-1"></i> Identity Info</span>
                                <table class="table table-sm custom-table mb-0">
                                    <tr><td>Full Name</td> <td id="modal-name-container"></td></tr>
                                    <tr><td>Bib Name</td> <td id="modal-bib-container"></td></tr>
                                    <tr><td>BIB Number</td> <td id="modal-bib-number" class="text-primary fw-bold"></td></tr>
                                    <tr><td>T-Shirt</td> <td id="modal-tshirt-container"></td></tr>
                                    <tr><td>ID Type</td> <td id="modal-nat" class="text-capitalize"></td></tr>
                                    <tr><td>ID / NRC</td> <td><div id="modal-id-container" class="nrc-group"></div></td></tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-section">
                                <span class="section-title"><i class="fa fa-flag me-1"></i> Participation Details</span>
                                <table class="table table-sm custom-table mb-0">
                                    <tr><td>Event</td> <td id="modal-event" class="fw-bold text-black"></td></tr>
                                    <tr><td>Category</td> <td id="modal-category"></td></tr>
                                    <tr><td>Level</td> <td id="modal-exp"></td></tr>
                                    <tr><td>Price</td> <td id="modal-price" class="fw-bold text-success"></td></tr>
                                    <tr><td>Blood Type</td> <td id="modal-blood" class="text-danger fw-bold"></td></tr>
                                    <tr><td>Region</td> <td id="modal-state" class="text-capitalize"></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <small class="text-muted d-block mb-1 text-uppercase fw-bold fs-10">Medical Information</small>
                                <div id="modal-medical" class="text-dark small"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <small class="text-muted d-block mb-1 text-uppercase fw-bold fs-10">ITRA Details</small>
                                <div id="modal-itra-container" class="text-dark small"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <!-- Foreigner Specific Text Details (Job / Occupation) -->
                        <div id="modal-foreigner-details" class="mb-3 p-3 bg-light rounded-3 border hidden">
                            <span class="text-muted small fw-bold uppercase d-block mb-1">Job / Occupation</span>
                            <span id="modal-job-work-text" class="fw-bold text-dark fs-6">-</span>
                        </div>

                        <div class="row g-3">
                            <!-- Passport Copy Image (Replaced Job Image) -->
                            <div class="col-md-6">
                                <div class="text-center p-4 bg-light rounded-4 border-dashed border-2 h-100 flex-column justify-content-center">
                                    <h6 class="fw-bold mb-3"><i class="fa fa-id-card me-1 text-primary"></i> Passport Copy</h6>
                                    <img id="modal-passport-img" src="" alt="Passport Copy" class="img-fluid shadow-sm rounded" style="max-height: 250px; cursor: zoom-in;" onclick="if(this.src) window.open(this.src)">
                                    <p class="text-muted small mt-3 mb-0">Click image to expand full size</p>
                                </div>
                            </div>

                            <!-- Visa Document Image -->
                            <div class="col-md-6">
                                <div class="text-center p-4 bg-light rounded-4 border-dashed border-2 h-100 flex-column justify-content-center">
                                    <h6 class="fw-bold mb-3"><i class="fa fa-passport me-1 text-info"></i> Visa Document</h6>
                                    <img id="modal-visa-img" src="" alt="Visa Document" class="img-fluid shadow-sm rounded" style="max-height: 250px; cursor: zoom-in;" onclick="if(this.src) window.open(this.src)">
                                    <p class="text-muted small mt-3 mb-0">Click image to expand full size</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-none" id="modal-save-button-container">
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 shadow-sm fw-bold">
                            <i class="fa fa-save me-2"></i>SAVE UPDATED INFORMATION
                        </button>
                    </div>
                </div>
            </form>
            <div class="modal-footer border-0 bg-light p-4">
    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">Close</button>
    @php
    // Get the user from the guard within the view
    $user = Auth::guard('admin')->user();
@endphp
    @if(auth()->user()->role === 'super_admin')
        <a href="#" id="btn-download-ticket" class="btn btn-dark px-4 py-2 d-none">
            <i class="fa fa-download me-2"></i>Download Ticket
        </a>
    @endif
    <div class="ms-auto d-flex gap-2">
        {{-- Container 1: Only for Super Admin (Approve/Reject) --}}
        @if($user && ($user->role === 'super_admin' || $user->role === 'finance_admin'))

            <div id="modal-action-buttons" class="d-flex gap-2">
                <button type="submit" class="btn btn-danger px-4 py-2 rounded-2" onclick="submitAction('reject')">Reject</button>
                <button type="submit" class="btn btn-success px-4 py-2 rounded-2" onclick="submitAction('approve')">Approve</button>
                
            </div>
        @endif

        {{-- Container 2: For both (Save Button) --}}
        <div id="modal-save-button-container" class="d-none">
            <button type="submit" form="edit-ticket-form" class="btn btn-primary px-4 py-2">Save Changes</button>
        </div>
    </div>
</div>

<script>
// All existing JavaScript logic preserved exactly as requested
const districtOptions = {
    "1": ["ကပတ", "ကမတ", "ခပန", "ခလဖ", "ဆဒန", "ဆပရ", "ဆဘန", "တဆလ", "တနန", "ဒဖယ", "နမန", "ပတအ", "ပနဒ", "ပဝန", "ဖကန", "ဗမန", "မကတ", "မကန", "မခဘ", "မစန", "မညန", "မမန", "မလန", "ရှကန", "ရှဗယ", "လဂျန", "ဟပန", "အဂျယ", "၀မန"],
    "2": ["ဒမဆ", "ဖဆန", "ဖရဆ", "ဘလခ", "မစန", "ရတန", "ရသန", "လကန"],
    "3": ["ကကရ", "ကဆက", "ကဒတ", "ကဒန", "ကမမ", "စကလ", "ပကန", "ဖပန", "ဘဂလ", "ဘသဆ", "ဘအန", "မဝတ", "ရသန", "လဘန", "လသန", "ဝလမ", "သတက", "သတန"],
    "4": ["ကခန", "ကပလ", "ဆမန", "တဇန", "တတန", "ထတလ", "ပလဝ", "ဖလန", "မတန", "မတပ", "ရခဒ", "ရဇန", "ဟခန"],
    "5": ["ကနန", "ကဘလ", "ကမန", "ကလတ", "ကလထ", "ကလန", "ကလဝ", "ကသန", "ခတန", "ခပန", "ခဥတ", "ခဥန", "ငဇန", "စကန", "ဆလက", "တဆန", "တမန", "ထခန", "ဒပယ", "နယန", "ပလန", "ပလဘ", "ဖပန", "ဗမန", "ဘတလ", "မကန", "မမတ", "မမန", "မရန", "မလန", "ယမပ", "ရဘန", "ရဥန", "လရန", "လဟန", "ဝလန", "ဝသန", "ဟမလ", "အတန", "အရတ"],
    "6": ["ကစန", "ကရရ", "ကလအ", "ကသန", "ခမန", "တသရ", "ထဝန", "ပလတ", "ပလန", "ဘပန", "မတန", "မမန", "ရဖြန", "လလန", "သရခ"],
    "7": ["ကကန", "ကတခ", "ကပက", "ကဝန", "ဇကန", "ညလပ", "တငန", "ထတပ", "ဒဥန", "နတလ", "ပခတ", "ပခန", "ပတဆ", "ပတတ", "ပတန", "ပနက", "ပမန", "ဖမန", "မညန", "မလန", "ရကန", "ရတန", "ရတရှ", "လပတ", "ဝမန", "သကန", "သဆန", "သနပ", "သဝတ", "အတန", "အဖန"],
    "8": ["ကထန", "ကမရ", "ခဆန", "ခဇန", "ပမန", "ဘလန", "မဒန", "မလမ", "ရမန", "လမန", "သထန", "သဖြရ"],
    "9": ["ကဆန", "ကပတ", "ခမစ", "ခအစ", "ငဇန", "ငသရ", "စကတ", "စကန", "ဇဗသ", "ဇယသ", "ညဥန", "တကတ", "တကန", "တတဥ", "တသန", "ဒခသ", "နထက", "ပကခ", "ပဗသ", "ပဘန", "ပမန", "ပသက", "ပဥလ", "မကန", "မခန", "မတရ", "မထလ", "မမန", "မလန", "မသန", "မဟမ", "ရမသ", "လဝန", "ဝတန", "သစန", "သပက", "အမစ", "အမရ", "ဥတသ"],
    "10": ["ကထန", "ကမရ", "ခဆန", "ခဇန", "ပမန", "ဘလန", "မဒန", "မလမ", "ရမန", "လမန", "သထန", "သဖြရ"],
    "11": ["ကတန", "ကတလ", "ကဖန", "ဂမန", "စတန", "တကန", "တပဝ", "ပဏတ", "ပတန", "ဗတထ", "ဘသတ", "မတန", "မပတ", "မပန", "မအတ", "မအန", "မဥန", "ရဗန", "ရသတ", "သတန", "အမန"],
    "12": ["ကကက", "ကခက", "ကတတ", "ကတန", "ကမတ", "ကမန", "ကမရ", "ခရန", "စခန", "ဆကခ", "ဆကန", "တကန", "တတထ", "တတန", "တမန", "ထတပ", "ဒဂဆ", "ဒဂတ", "ဒဂန", "ဒဂမ", "ဒဂရ", "ဒပန", "ဒလန", "ပဇတ", "ပဘတ", "ဗဟန", "မဂတ", "မဂဒ", "မဘန", "မရက", "ရကန", "ရပသ", "လကန", "လမတ", "လမန", "လသန", "လသယ", "သကတ", "သခန", "သဃက", "သလန", "အစန", "အလန", "ဥကတ", "ဥကန", "ဥကမ"],
    "13": ["ကခန", "ကတတ", "ကတန", "ကတလ", "ကမဆ", "ကမန", "ကရန", "ကလတ", "ကလဒ", "ကလန", "ကလဖ", "ကသန", "ကဟန", "ခမန", "ခရဟ", "ခလန", "ဆဆန", "ဆဖန", "ညရန", "တကန", "တခလ", "တမည", "တယန", "တလန", "နကန", "နခတ", "နခန", "နခဝ", "နဆန", "နတန", "နတယ", "နဖန", "နမတ", "နဝန", "ပခန", "ပဆန", "ပတယ", "ပပက", "ပယန", "ပလတ", "ပလန", "ပဝန", "ဖခန", "မကန", "မခန", "မငန", "မဆတ", "မဆန", "မတတ", "မတန", "မနန", "မပန", "မဖန", "မဗတ", "မဘန", "မမဆ", "မမတ", "မမန", "မယန", "မရတ", "မရန", "မလန", "မဟရ", "ယလန", "ရငန", "ရစန", "ရဖန", "လကတ", "လခတ", "လခန", "လရန", "လလန", "လဟန", "သနန", "သပန", "ဟတန", "ဟပတ", "ဟပန", "အခန", "အတန"],
    "14": ["ကကထ", "ကကန", "ကခန", "ကပန", "ကလန", "ငဆန", "ငပတ", "ငရက", "ငသခ", "ငသယ", "ဇလန", "ညတန", "ဒဒရ", "ဒနဖြ", "ပစလ", "ပတန", "ပသန", "ဖပန", "ဘကလ", "မမက", "မမန", "မအန", "မအပ", "ရကန", "ရသယ", "လပတ", "လမန", "ဝခမ", "သပန", "ဟကကျ", "ဟသတ", "အဂပ", "အမတ", "အမန"]
};

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('tableSearch');
    const ticketTable = document.getElementById('ticketTable');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase().trim();
            const rows = ticketTable.querySelectorAll('tbody tr:not(.no-results)');
            let visibleCount = 0;

            rows.forEach(row => {
                const isMatch = row.textContent.replace(/\s+/g, ' ').toLowerCase().includes(searchTerm);
                row.style.display = isMatch ? '' : 'none';
                if (isMatch) visibleCount++;
            });

            let noResRow = ticketTable.querySelector('.no-results');
            if (visibleCount === 0 && searchTerm !== "") {
                if (!noResRow) {
                    const tr = document.createElement('tr');
                    tr.className = 'no-results';
                    tr.innerHTML = `<td colspan="8" class="text-center p-5"><div class="text-muted">No results found for "${searchTerm}"</div></td>`;
                    ticketTable.querySelector('tbody').appendChild(tr);
                }
            } else if (noResRow) noResRow.remove();
        });
    }

    const detailButtons = document.querySelectorAll('.view-details');
    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const data = JSON.parse(this.getAttribute('data-info'));
            const transactionImageUrl = this.getAttribute('data-image');
            const athlete = data.athlete || {};
            const fullName = [athlete.first_name, athlete.middle_name, athlete.last_name].filter(Boolean).join(' ') || 'Guest Runner';
            
            document.getElementById('modal-ticket-id-input').value = data.id;
            document.getElementById('modal-bib-number').innerText = data.bib_number || 'Not Assigned';
            document.getElementById('modal-category').innerText = data.category || 'N/A';
            document.getElementById('modal-event').innerText = data.event || 'N/A';
            document.getElementById('modal-price').innerText = new Intl.NumberFormat().format(data.price || 0) + ' MMK';
            document.getElementById('modal-exp').innerText = data.experience_level || 'N/A';
            document.getElementById('modal-blood').innerText = athlete.blood_type || 'N/A';
            document.getElementById('modal-medical').innerText = athlete.medical_details || 'None';
            document.getElementById('modal-state').innerText = athlete.state || 'None';
            document.getElementById('modal-nat').innerText = athlete.nat_type || 'None';
            // document.getElementById('modal-transaction-img').src = transactionImageUrl;
            // 1. Populate Job / Occupation Text
            const jobWorkText = document.getElementById('modal-job-work-text');
            const foreignerContainer = document.getElementById('modal-foreigner-details');

            if (athlete.job_work) {
                if (jobWorkText) jobWorkText.textContent = athlete.job_work;
                if (foreignerContainer) foreignerContainer.classList.remove('hidden');
            } else {
                if (foreignerContainer) foreignerContainer.classList.add('hidden');
            }

            // 2. Populate Passport Image
            const passportImg = document.getElementById('modal-passport-img');
            if (passportImg) {
                if (athlete.passport_image_path) {
                    const cleanPath = athlete.passport_image_path.replace(/^\/+/, '');
                    passportImg.src = cleanPath.startsWith('storage/') ? '/' + cleanPath : '/storage/' + cleanPath;
                    passportImg.style.display = 'inline-block';
                } else {
                    passportImg.src = '';
                    passportImg.style.display = 'none';
                }
            }

            // 3. Populate Visa Image
            const visaImg = document.getElementById('modal-visa-img');
            if (visaImg) {
                if (athlete.visa_image_path) {
                    const cleanPath = athlete.visa_image_path.replace(/^\/+/, '');
                    visaImg.src = cleanPath.startsWith('storage/') ? '/' + cleanPath : '/storage/' + cleanPath;
                    visaImg.style.display = 'inline-block';
                } else {
                    visaImg.src = '';
                    visaImg.style.display = 'none';
                }
            }
            
            
            // --- MOVED ALL OF THIS INSIDE THE CLICK FUNCTION ---
            const actionContainer = document.getElementById('modal-action-buttons');
            const saveBtnContainer = document.getElementById('modal-save-button-container');
            const restrictedRoles = ['finance_admin', 'agent', 'supporter', 'printer'];
            const userRole = "{{ auth()->user()->role ?? 'guest' }}";
            const isEditable = ['pending', 'approved'].includes(data.status) && !restrictedRoles.includes(userRole);
            
            if (actionContainer) {
                if (data.status === 'pending') {
                    actionContainer.classList.remove('d-none');
                } else {
                    actionContainer.classList.add('d-none');
                }
            }

            const downloadBtn = document.getElementById('btn-download-ticket');
            // 1. Check if the status is approved AND user is super_admin
            const isApproved = data.status === 'approved';
            const isAdmin = "{{ auth()->user()->role ?? '' }}" === 'super_admin';

            if (downloadBtn) {
                if (isApproved && isAdmin) {
                    downloadBtn.classList.remove('d-none'); // Show button
                    downloadBtn.href = `/ticket/download/${data.id}`;
                } else {
                    downloadBtn.classList.add('d-none'); // Hide button
                }
            }

            if (isEditable) {
                saveBtnContainer.classList.remove('d-none');
                
                document.getElementById('modal-name-container').innerHTML = `
                <input type="text" name="full_name" class="form-control form-control-sm border-primary" value="${fullName}" placeholder="Enter Full Name">
                `;
                document.getElementById('modal-itra-container').innerHTML = `
                <textarea name="itra_details" class="form-control form-control-sm border-primary" rows="2" placeholder="ITRA Score or Link">${athlete.itra_details || ''}</textarea>
                `;
                document.getElementById('modal-bib-container').innerHTML = `
                <input type="text" name="bib_name" class="form-control form-control-sm border-primary" value="${data.bib_name || ''}" placeholder="Enter BIB Name">
                `;
                const sizes = ["XS", "S", "M", "L", "XL", "2XL", "3XL", "5XL"];
                let sizeOptions = sizes.map(s => `<option value="${s}" ${data.t_shirt_size === s ? 'selected' : ''}>${s}</option>`).join('');
                document.getElementById('modal-tshirt-container').innerHTML = `
                <select name="t_shirt_size" class="form-control form-control-sm border-primary">
                ${sizeOptions}
                </select>
                `;
                
                const idContainer = document.getElementById('modal-id-container');
                const currentId = athlete.id_number || '';
                if (athlete.nat_type === 'national') {
                    let state = '', district = '', type = 'နိုင်', num = '';
                    const match = currentId.match(/^(\d+)\/([^\(]+)\(([^)]+)\)(\d+)$/);
                    if(match) { state = match[1]; district = match[2]; type = match[3]; num = match[4]; }
                    idContainer.innerHTML = `
                    <div class="d-flex gap-1 flex-wrap">
                    <select name="nrc_state" id="edit_nrc_state" class="form-control" style="width: 55px;" required>
                    <option value="">St</option>
                    ${[...Array(14)].map((_,i)=>`<option value="${i+1}" ${state == i+1 ? 'selected':''}>${i+1}/</option>`).join('')}
                    </select>
                    <select name="nrc_district" id="edit_nrc_district" class="form-control" style="width: 85px;" required>
                    <option value="${district}">${district || 'Dist'}</option>
                    </select>
                    <select name="nrc_type" id="edit_nrc_type" class="form-control" style="width: 65px;">
                    ${['နိုင်','ဧည့်','စ','ပြု','သ','သီ'].map(t => `<option value="${t}" ${type == t ? 'selected':''}>${t}</option>`).join('')}
                    </select>
                    <input type="text" name="nrc_number" id="edit_nrc_number" class="form-control" style="flex: 1; min-width: 80px;" value="${num}" placeholder="123456" maxlength="6" required>
                    </div>
                    `;
                } else {
                    idContainer.innerHTML = `
                    <input type="text" name="id_number" id="edit_passport" class="form-control form-control-sm border-primary" value="${currentId}" placeholder="Passport Number" required>
                    `;
                }
                if(athlete.nat_type === 'national' && state) {
                    setTimeout(() => document.getElementById('edit_nrc_state').dispatchEvent(new Event('change')), 50);
                }
            } else {
                saveBtnContainer.classList.add('d-none');
                // Explicitly set the text for everyone, regardless of role
                document.getElementById('modal-name-container').innerText = fullName;
                document.getElementById('modal-itra-container').innerText = athlete.itra_details || 'None';
                document.getElementById('modal-bib-container').innerText = data.bib_name || 'N/A';
                document.getElementById('modal-tshirt-container').innerHTML = `<span class="badge light badge-primary">${data.t_shirt_size || 'N/A'}</span>`;
                document.getElementById('modal-id-container').innerText = athlete.id_number || 'None';
                
            }
            const approveForm = document.getElementById('approve-form');
            const rejectForm = document.getElementById('reject-form');

            if (approveForm) {
                approveForm.action = `/dashboard/tickets/approve/${data.id}`;
            }
            if (rejectForm) {
                rejectForm.action = `/dashboard/tickets/reject/${data.id}`;
            }
            
            
        });
    });
});

document.addEventListener('change', function(e) {
    if (e.target.id === 'edit_nrc_state') {
        const state = e.target.value;
        const districtSelect = document.getElementById('edit_nrc_district');
        if (!districtSelect) return;
        const currentVal = districtSelect.value;
        districtSelect.innerHTML = '<option value="">District</option>';
        if (districtOptions[state]) {
            districtOptions[state].forEach(d => {
                const opt = document.createElement('option');
                opt.value = d;
                opt.textContent = d;
                if(d === currentVal) opt.selected = true;
                districtSelect.appendChild(opt);
            });
        }
    }
});
// 1. Initial trigger
async function printAthleteSlip(divId, ticketId, isPrinted) {
    console.log("Triggered print for ID:", ticketId, "isPrinted:", isPrinted);
    if (isPrinted) {
        // Already printed: Open Modal
        console.log("Attempting to open modal...");
        document.getElementById('modalTicketId').value = ticketId;
        new bootstrap.Modal(document.getElementById('passwordModal')).show();
    } else {
        // Not printed: Print immediately
        executePrint(divId, ticketId, '');
    }
}

// 2. Verified reprint
async function verifyAndPrint() {
    const ticketId = document.getElementById('modalTicketId').value;
    const password = document.getElementById('printerPassword').value;
    
    // Get token from meta tag for security
    const token = document.querySelector('meta[name="csrf-token"]').content;

    try {
        const response = await fetch(`/dashboard/tickets/${ticketId}/reprint`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ password: password })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            // Success: Close Modal
            const modalEl = document.getElementById('passwordModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            // Clear password and trigger print
            document.getElementById('printerPassword').value = '';
            executePrint('slip-' + ticketId, ticketId, true);
        } else {
            alert(data.message || "Invalid Password!");
        }
    } catch (error) {
        console.error("Fetch Error:", error);
        alert("Server error. Please check your connection.");
    }
}

// 3. The shared printer logic
async function executePrint(divId, ticketId, isReprint) {
    // 1. If it's a new print, mark it in the database first
    if (!isReprint) {
        const response = await fetch(`/dashboard/tickets/${ticketId}/mark-printed`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        if (!data.success) {
            alert("Error saving print status. Please try again.");
            return;
        }
    }

    // 2. Perform the printing
    var printContents = document.getElementById(divId);
    printContents.classList.add('print-active');
    printContents.classList.remove('d-none');
    
    window.print();
    
    printContents.classList.remove('print-active');
    printContents.classList.add('d-none');
    
    // 3. Finalize
    window.location.reload();
}

function submitAction(type) {
    const ticketId = document.getElementById('modal-ticket-id-input').value;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = (type === 'approve') ? `dashboard/tickets/approve/${ticketId}` : `dashboard/tickets/reject/${ticketId}`;
    
    // Add CSRF Token
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = document.querySelector('meta[name="csrf-token"]').content;
    
    form.appendChild(csrf);
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection