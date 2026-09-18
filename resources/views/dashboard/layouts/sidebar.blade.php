<div class="deznav" style="margin-top: 30px">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            
            {{-- START ADMIN CHECK --}}
            @if(Auth::guard('admin')->check())
                @php 
                    $admin = Auth::guard('admin')->user(); 
                @endphp

                {{-- 1. Admin Management - Super Admin Only --}}
                @if($admin->role === 'super_admin')
                <li>
                    <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                        <i class="flaticon-381-user-9"></i>
                        <span class="nav-text" data-i18n="Admins">Admin Management</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('admin.admins.index') }}" data-i18n="All Admins">All Admins</a></li>
                        <li><a href="{{ route('admin.admins.create') }}" data-i18n="Admin Create / Roles">Admin Create / Roles</a></li>
                    </ul>
                </li>
                @endif

                {{-- Registration Section - Super Admin Only --}}
                @if($admin->role === 'super_admin')
                <li>
                    <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                        <i class="flaticon-381-id-card"></i>
                        <span class="nav-text" data-i18n="Registration">Registration</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="/dashboard/register/1" data-i18n="Registration (Level 1)">Registration (Level 1)</a></li>
                        <li><a href="/dashboard/register/2" data-i18n="Registration (Level 2)">Registration (Level 2)</a></li>
                    </ul>
                </li>
                @endif

                {{-- 2. Ticket Sales Section - Visible to Super Admin AND Event Admin --}}
                <li>
                    <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                        <i class="flaticon-381-diploma"></i>
                        <span class="nav-text" data-i18n="Ticket Sales">Ticket Sales</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('dashboard.tickets.index') }}" data-i18n="Event Tickets">Event Tickets</a></li>
                        
                        @if($admin->role === 'super_admin')
                        <li>
                            <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Dinner Tickets</a>
                            <ul aria-expanded="false">
                                <li><a href="/dashboard/dinner-tickets">Sales Tickets</a></li>
                                <li>
                                    <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Present Tickets</a>
                                    <ul aria-expanded="false">
                                        <li><a href="{{ route('admin.sponsor.index', 'now') }}">Now Present</a></li>
                                        <li><a href="{{ route('admin.sponsor.index', 'past') }}">Past Present</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </li>

                {{-- 3. General Management Sections - Super Admin Only --}}
                @if($admin->role === 'super_admin')
                <li>
                    <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                        <i class="flaticon-381-calendar-1"></i>
                        <span class="nav-text" data-i18n="Events">Events</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('events.index', 'now') }}">Now Event</a></li>
                        <li><a href="{{ route('events.index', 'coming') }}">Coming Event</a></li>
                        <li><a href="{{ route('events.index', 'past') }}">Past Event</a></li>
                    </ul>
                </li>

                <!-- NEW: Manage Races Section -->
                <li>
                    <a class="ai-icon" href="{{ route('admin.races.index') }}" aria-expanded="false">
                        <i class="flaticon-381-layer"></i>
                        <span class="nav-text">Manage Races</span>
                    </a>
                </li>

                <li>
                    <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                        <i class="flaticon-381-television"></i>
                        <span class="nav-text" data-i18n="Dinner">Dinner</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('admin.dinner.manage', 'now') }}">Now Dinner</a></li>
                        <li><a href="{{ route('admin.dinner.manage', 'past') }}">Past Dinner</a></li>
                        <li><a href="{{ route('staff.scanner') }}">Scanner</a></li>
                    </ul>
                </li>

                <li>
                    <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                        <i class="flaticon-381-heart"></i>
                        <span class="nav-text" data-i18n="Sponsor">Sponsor</span>
                    </a>
                    <ul aria-expanded="false">
                         <li><a href="{{ route('admin.sponsor.index', 'now') }}">Now Sponsor</a></li>
                         <li><a href="{{ route('admin.sponsor.index', 'past') }}">Past Sponsor</a></li>
                    </ul>
                </li>

                {{-- Settings option safely enclosed inside the Super Admin check block --}}
                <li>
                    <a class="ai-icon" href="{{ route('admin.settings') }}" aria-expanded="false">
                        <i class="fa fa-cog"></i>
                        <span class="nav-text">Settings</span>
                    </a>
                </li>
                <li>
                    <a class="ai-icon" href="{{ route('admin.promo_codes.index') }}" aria-expanded="false">
                        <i class="fa fa-cog"></i>
                        <span class="nav-text">Promo Codes</span>
                    </a>
                </li>
                @endif
            @endif {{-- END ADMIN GUARD --}}

            {{-- Agent Guard Section --}}
            @if(Auth::guard('agent')->check())
                <li>
                    <a class="ai-icon" href="{{ route('agent.tickets') }}" aria-expanded="false">
                        <i class="flaticon-381-diploma"></i>
                        <span class="nav-text">Event Tickets</span>
                    </a>
                </li>
            @endif
            
        </ul>
    </div>
</div>