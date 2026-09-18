@extends('dashboard.layouts.master')

@push('styles')
<style>
    /* Racing Theme Variables */
    :root {
        --runner-red: #ef4444;
        --runner-dark: #0f172a;
        --runner-green: #10b981;
    }

    /* Modern Entrance Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-card {
        animation: fadeInUp 0.5s ease backwards;
    }

    /* Content Body Overhaul */
    .content-body { background: #fdfdfd; }

    /* Page Header Styles */
    .page-title h1 { letter-spacing: -1px; }
    
    /* Premium Stats Card */
    .revenue-summary-card {
        background: linear-gradient(135deg, var(--runner-dark) 0%, #1e293b 100%);
        color: white;
        border-radius: 16px;
        padding: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
    }
    .revenue-summary-card::after {
        content: '\f70c'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
        position: absolute; right: -10px; bottom: -10px; font-size: 80px;
        opacity: 0.1; transform: rotate(-15deg);
    }

    /* Event Card Upgrades */
    .event-card { 
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); 
        border: 1px solid #f1f5f9; 
        border-radius: 20px; 
        background: #fff;
    }
    .event-card:hover { 
        transform: translateY(-10px); 
        box-shadow: 0 20px 30px rgba(0,0,0,0.08);
        border-color: var(--runner-red);
    }

    .event-card-img { 
        height: 200px; 
        object-fit: cover; 
        border-radius: 20px 20px 0 0; 
        filter: grayscale(20%);
        transition: 0.4s;
    }
    .event-card:hover .event-card-img { filter: grayscale(0%); scale: 1.02; }

    /* Athletic Header Style */
    .section-header {
        display: flex; align-items: center; padding: 18px 25px; 
        background: #fff; border-radius: 12px; margin-bottom: 25px;
        border-left: 6px solid var(--runner-red);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .section-header.past { border-left-color: #64748b; background: #f8fafc; }

    /* Grid/Box Style for Data */
    .data-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 1px;
        background: #e2e8f0; border-radius: 12px; overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .data-item { background: #f8fafc; padding: 12px; }

    .stat-label { 
        font-size: 9px; font-weight: 800; text-transform: uppercase; 
        letter-spacing: 1px; color: #64748b; display: block; margin-bottom: 4px;
    }

    /* Buttons */
    .btn-manage {
        background: var(--runner-dark); color: white; font-weight: 600;
        padding: 10px 20px; border-radius: 12px; transition: 0.3s;
        border: none; width: 100%;
    }
    .btn-manage:hover { background: var(--runner-red); color: white; transform: scale(1.02); }

    .bricks-divider { display: flex; gap: 12px; margin: 60px 0; justify-content: center; }
    .brick { height: 6px; width: 30px; background: #cbd5e1; border-radius: 10px; }
    .brick.active { background: var(--runner-red); width: 60px; }

    /* Past Events Container */
    .past-container { 
        background: #f1f5f9; padding: 40px; border-radius: 30px; 
        border: 2px dashed #cbd5e1; 
    }
</style>
@endpush

@section('content')
@php
    $user = Auth::guard('admin')->user();
    $canSeeMoney = in_array($user->role, ['super_admin', 'finance_admin']);
@endphp

<div class="content-body">
    <div class="container-fluid">
        <div class="row mb-5 align-items-center">
            <div class="col-md-7">
                <div class="page-title">
                    <h1 class="font-weight-bold text-dark display-4 mb-1">Race Director</h1>
                    <p class="text-muted lead">Live registration management portal.</p>
                </div>
            </div>
            
            {{-- ONLY SUPER ADMIN & FINANCE SEE GRAND TOTAL --}}
            @if($canSeeMoney)
            <div class="col-md-5">
                <div class="revenue-summary-card animate-card">
                    <span class="stat-label text-white-50">Total Approved Revenue</span>
                    <h2 class="font-weight-bold mb-0" style="font-size: 2rem;">
                        {{ number_format($grandTotalRevenue ?? 0) }} <small style="font-size: 0.6em;">MMK</small>
                    </h2>
                    <div class="mt-2">
                        <span class="badge badge-pill" style="background: rgba(16, 185, 129, 0.2); color: #10b981;">
                            <i class="fas fa-shield-check mr-1"></i> Verified Payments Only
                        </span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Section 1: ACTIVE EVENTS --}}
        <div class="section-header animate-card">
            <div>
                <h3 class="font-weight-bold mb-0" style="color: var(--runner-dark);">
                    <i class="fas fa-bolt mr-2 text-danger"></i> ACTIVE RACES
                </h3>
                <small class="text-muted text-uppercase font-weight-bold">Live registration in progress</small>
            </div>
            <div class="ml-auto">
                <span class="badge badge-danger px-4 py-2 shadow-sm">
                    <i class="fas fa-broadcast-tower mr-1"></i> LIVE
                </span>
            </div>
        </div>

        <div class="row">
            @forelse($nowEvents as $event)
                <div class="col-xl-4 col-lg-6 col-md-6 mb-4 animate-card" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <div class="card event-card h-100 border-0">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $event->image_path) }}" class="event-card-img" alt="event">
                            <div class="position-absolute" style="top: 15px; right: 15px;">
                                <span class="badge badge-white shadow-sm px-3 py-2">
                                    <i class="far fa-calendar-alt text-danger mr-1"></i> 
                                    {{ \Carbon\Carbon::parse($event->date)->format('M d') }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h4 class="font-weight-bold text-dark mb-3">{{ $event->name }}</h4>
                            
                            <div class="data-grid mb-4 shadow-sm">
                                <div class="data-item">
                                    <span class="stat-label">Capacity</span>
                                    @if($event->total_max_slots)
                                        <span class="font-weight-bold text-dark">
                                            {{ number_format($event->total_max_slots) }} <small class="text-muted">SLOTS</small>
                                        </span>
                                    @else
                                        <span class="font-weight-bold text-success">
                                            UNLIMITED
                                        </span>
                                    @endif
                                </div>

                                {{-- REVENUE ITEM: SHOW ONLY TO FINANCE/SUPER --}}
                                <div class="data-item">
                                    <span class="stat-label">Revenue</span>
                                    @if($canSeeMoney)
                                        <span class="font-weight-bold text-success">
                                            {{ number_format($event->approved_revenue ?? 0) }} <small>MMK</small>
                                        </span>
                                    @else
                                        <span class="text-muted small"><i class="fas fa-lock mr-1"></i> Restricted</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-7">
                                    <a href="{{ route('dashboard.events.ticket', ['event' => $event->name]) }}" class="btn btn-manage">
                                        Manage Race <i class="fas fa-chevron-right ml-2 small"></i>
                                    </a>
                                </div>
                                <div class="col-5 text-right">
                                    <div class="bg-light rounded-pill px-3 py-1 d-inline-block border">
                                        <span class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                                            {{ $event->approved_ticket_count ?? 0 }}
                                        </span>
                                        <small class="text-muted font-weight-bold">PAID</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 animate-card">
                    <div class="mb-3"><i class="fas fa-running fa-3x text-muted opacity-20"></i></div>
                    <p class="text-muted lead">No active races currently scheduled.</p>
                </div>
            @endforelse
        </div>

        <div class="bricks-divider">
            <div class="brick"></div><div class="brick active"></div><div class="brick"></div>
        </div>

        {{-- Section 2: PAST EVENTS --}}
        <div class="past-container animate-card">
            <div class="section-header past mb-4">
                <div>
                    <h3 class="font-weight-bold mb-0 text-secondary">
                        <i class="fas fa-history mr-2"></i> ARCHIVED RACES
                    </h3>
                </div>
                <div class="ml-auto">
                    <span class="badge badge-secondary px-3 py-2">COMPLETED</span>
                </div>
            </div>
            <div class="row">
                @forelse($pastEvents as $event)
                    <div class="col-xl-4 col-md-6 mb-4 animate-card" style="filter: grayscale(0.8); opacity: 0.8;">
                         <div class="card event-card h-100">
                            <img src="{{ asset('storage/' . $event->image_path) }}" class="event-card-img" alt="event">
                            <div class="card-body p-4">
                                <h5 class="font-weight-bold text-dark mb-2">{{ $event->name }}</h5>
                                
                                {{-- ARCHIVED REVENUE: SHOW ONLY TO FINANCE/SUPER --}}
                                <div class="d-flex justify-content-between border-top pt-3 mt-2">
                                    <span class="stat-label">Total Revenue</span>
                                    @if($canSeeMoney)
                                        <span class="font-weight-bold text-dark">{{ number_format($event->approved_revenue ?? 0) }} MMK</span>
                                    @else
                                        <span class="text-muted small"><i class="fas fa-lock mr-1"></i> Restricted</span>
                                    @endif
                                </div>
                                <a href="{{ route('dashboard.events.ticket', ['event' => $event->name]) }}" class="btn btn-manage">
				<a href="{{ route('dashboard.events.ticket', ['event' => $event->name]) }}" class="btn btn-manage">
                                    Manage Race <i class="fas fa-chevron-right ml-2 small"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4"><p class="text-muted italic">History is clear.</p></div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
