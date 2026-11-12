<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Runner Dashboard - MYAN RUN</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        rel="stylesheet"
    >

    <link
        rel="icon"
        type="image/x-icon"
        href="{{ asset('images/icon/Myan Run icon.png') }}"
    >
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#C3E92D',
                        brandDark: '#aacc28',
                    },
                    boxShadow: {
                        brand: '0 10px 15px -3px rgba(195, 233, 45, 0.3)',
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .sidebar-active {
            background: #C3E92D;
            color: #000;
            box-shadow: 0 4px 20px rgba(195, 233, 45, 0.4);
            transform: translateX(5px);
        }

        .sidebar-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .edit-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
        }

        .label-text {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
        }

        .edit-input {
            width: 100%;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 700;
            outline: none;
            background: white;
        }

        .edit-input:focus {
            border-color: #0f172a;
            box-shadow: 0 0 0 1px #0f172a;
        }
    </style>
</head>

<body class="bg-[#F8FAFC] text-slate-900 custom-scrollbar">

<div class="flex h-screen overflow-hidden">

    <!-- ========================================================= -->
    <!-- SIDEBAR -->
    <!-- ========================================================= -->

    <aside class="w-72 bg-white border-r border-slate-100 hidden lg:flex flex-col z-20">

        <div class="p-8">

            <a href="{{ url('/') }}" class="inline-block group">

                <img
                    src="{{ asset('images/MyanRun_Orange_RM2.png') }}"
                    alt="Myan Run Logo"
                    class="h-10 w-auto object-contain group-hover:scale-105 transition-transform duration-300"
                >

            </a>

        </div>

        <nav class="flex-1 px-6 space-y-1.5 mt-4">

            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-[2px] mb-4 px-2">
                Main Menu
            </p>

            <a
                href="{{ url('/user/dashboard') }}"
                class="sidebar-active flex items-center p-3.5 rounded-2xl group"
            >

                <div class="w-8">
                    <i class="fas fa-th-large text-lg"></i>
                </div>

                <span class="font-bold text-sm tracking-tight">
                    Dashboard
                </span>

            </a>

            <a
                href="{{ route('user.password.change') }}"
                class="{{ request()->routeIs('user.password.change')
                    ? 'sidebar-active'
                    : 'sidebar-item text-slate-500' }}
                    flex items-center p-3.5 rounded-2xl group"
            >

                <div class="w-8">
                    <i class="fas fa-lock text-lg"></i>
                </div>

                <span class="font-bold text-sm tracking-tight">
                    Change Password
                </span>

            </a>

            <button
                type="button"
                onclick="openProfileModal()"
                class="sidebar-item w-full flex items-center p-3.5 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 group text-left"
            >

                <div class="w-8">
                    <i class="fas fa-user text-lg group-hover:text-brandDark transition-colors"></i>
                </div>

                <span class="font-bold text-sm tracking-tight">
                    Profile
                </span>

            </button>

        </nav>

        <div class="p-6 border-t border-slate-50">

            <form method="POST" action="{{ route('logout') }}">

                @csrf

                <button
                    type="submit"
                    class="w-full flex items-center text-slate-400 font-bold p-3 hover:text-red-500 transition-all group"
                >

                    <i class="fas fa-sign-out-alt mr-4 text-lg group-hover:rotate-12 transition-transform"></i>

                    <span class="text-sm">
                        Logout
                    </span>

                </button>

            </form>

        </div>

    </aside>


    <!-- ========================================================= -->
    <!-- MAIN -->
    <!-- ========================================================= -->

    <main class="flex-1 overflow-y-auto custom-scrollbar relative">

        <!-- MOBILE HEADER -->

        <div class="lg:hidden flex items-center justify-between p-6 bg-white border-b">

            <a href="{{ url('/') }}" class="inline-block group">

                <img
                    src="{{ asset('images/MyanRun_Orange_RM2.png') }}"
                    class="h-8"
                    alt="Myan Run"
                >

            </a>

            <button
                type="button"
                class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600"
            >

                <i class="fas fa-bars"></i>

            </button>

        </div>


        <!-- ========================================================= -->
        <!-- HERO -->
        <!-- ========================================================= -->

        <div class="relative w-full mb-12 group">

            <div class="relative h-[400px] w-full overflow-hidden shadow-2xl shadow-brand/10">

                <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">

                    <i class="fas fa-running text-white/10 text-[10rem] rotate-12"></i>

                </div>

                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent"></div>

                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/60 via-transparent to-transparent"></div>

                <div class="absolute bottom-0 left-0 p-8 md:p-12 w-full flex flex-col md:flex-row md:items-end justify-between gap-6">

                    <div class="space-y-2">

                        <div class="flex items-center gap-3 mb-4">

                            <span class="bg-brand text-black text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-[3px] shadow-lg shadow-brand/20">
                                Elite Member
                            </span>

                            <span class="bg-white/10 backdrop-blur-md text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-[3px] border border-white/20">
                                Verified Runner
                            </span>

                        </div>

                        <h1 class="text-5xl md:text-7xl font-black text-white italic uppercase tracking-tighter leading-none">

                            {{ $user->first_name }}

                            @if($user->middle_name)
                                {{ $user->middle_name }}
                            @endif

                            <span class="text-brand">
                                {{ $user->last_name }}
                            </span>

                        </h1>

                        <div class="flex items-center gap-6 mt-4">

                            <div class="flex items-center text-white/70 font-bold uppercase text-xs tracking-widest">

                                <i class="fas fa-map-marker-alt text-brand mr-2"></i>

                                Yangon, MM

                            </div>

                            <div class="flex items-center text-white/70 font-bold uppercase text-xs tracking-widest">

                                <i class="fas fa-calendar-alt text-brand mr-2"></i>

                                Joined {{ $user->created_at->format('Y') }}

                            </div>

                        </div>

                    </div>

                    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-[32px] text-right">

                        <p class="text-white/50 text-[10px] font-black uppercase tracking-[4px] mb-1">
                            Personal ID
                        </p>

                        <p class="text-white text-3xl font-black tracking-tighter">
                            {{ $user->runner_id }}
                        </p>

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================================= -->
        <!-- CONTENT -->
        <!-- ========================================================= -->

        <div class="p-6 md:p-12 max-w-7xl mx-auto">

            <header class="flex flex-col md:flex-row justify-between md:items-center mb-12 gap-6">

                <div>

                    <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight leading-none">

                        Hello,
                        {{ $user->first_name }}
                        {{ $user->middle_name }}
                        {{ $user->last_name }}! 👋

                    </h2>

                    <p class="text-slate-500 font-medium mt-3 text-lg italic">
                        The road is calling. Are you ready?
                    </p>

                </div>

                <div class="flex items-center space-x-4">

                    <div class="hidden md:flex flex-col items-end mr-2">

                        <span class="text-sm font-bold text-slate-900">
                            {{ $fullName }}
                        </span>

                        <span
                            class="text-[11px] font-bold text-brandDark uppercase tracking-widest cursor-pointer"
                            onclick="openProfileModal()"
                        >
                            {{ $athlete?->id_number ?: 'NRC Needed' }}
                        </span>

                    </div>

                    <button
                        type="button"
                        class="bg-white w-12 h-12 rounded-2xl border border-slate-100 text-slate-400 relative hover:shadow-lg transition-all flex items-center justify-center group"
                    >

                        <i class="fas fa-bell group-hover:rotate-12 transition-transform"></i>

                        <span class="absolute top-3 right-3 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white ring-2 ring-red-100"></span>

                    </button>

                    <div class="w-32 h-32 bg-white p-1 border-2 border-brand rounded-2xl shadow-brand overflow-hidden cursor-pointer hover:rotate-3 transition-transform">

                        @if($athlete && $athlete->face_image_path)

                            <img
                                src="{{ asset('storage/' . $athlete->face_image_path) }}"
                                class="w-full h-full object-contain bg-slate-800"
                                alt="Profile"
                            >

                        @else

                            <div class="w-full h-full bg-slate-800 flex items-center justify-center font-black text-4xl text-brand uppercase">

                                {{ substr($user->first_name, 0, 1) }}

                            </div>

                        @endif

                    </div>

                </div>

            </header>


            <!-- ========================================================= -->
            <!-- TICKETS -->
            <!-- ========================================================= -->

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-6">

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-2">

                        <div>

                            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">
                                My Race Tickets
                            </h3>

                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                {{ $tickets->total() }} Total Registrations
                            </p>

                        </div>

                        <div class="flex bg-slate-100 p-1 rounded-2xl overflow-x-auto no-scrollbar border border-slate-200/50">

                            @foreach([
                                'all' => null,
                                'pending' => 'pending',
                                'accepted' => 'accepted',
                                'completed' => 'completed'
                            ] as $label => $val)

                                <a
                                    href="{{ request()->fullUrlWithQuery([
                                        'status' => $val,
                                        'page' => 1
                                    ]) }}"
                                    class="px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider transition-all whitespace-nowrap
                                    {{ request('status') == $val
                                        ? 'bg-white shadow-sm text-slate-900'
                                        : 'text-slate-500 hover:text-slate-700' }}"
                                >
                                    {{ $label }}
                                </a>

                            @endforeach

                        </div>

                    </div>


                    <div class="space-y-6">

                        @forelse($tickets as $ticket)

                            <div class="bg-white rounded-[40px] shadow-sm border border-slate-50 overflow-hidden group hover:shadow-md transition-all">

                                <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8 items-center">

                                    <div class="relative shrink-0">

                                        <div class="bg-slate-50 p-4 rounded-[24px] border-2 border-dashed border-slate-200 group-hover:border-brand transition-colors">

                                            <img
                                                src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=TICKET-{{ $ticket->id }}"
                                                alt="QR"
                                                class="w-20 h-20 mix-blend-multiply opacity-80"
                                            >

                                        </div>

                                    </div>

                                    <div class="flex-1 w-full">

                                        <div class="flex justify-between items-start mb-4">

                                            <div>

                                                <h4 class="text-xl font-extrabold text-slate-900 tracking-tight uppercase">
                                                    {{ $ticket->category }}
                                                </h4>

                                                <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">
                                                    ID: #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                                                </p>

                                            </div>

                                            <span
                                                class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider
                                                {{ $ticket->status === 'pending'
                                                    ? 'bg-amber-100 text-amber-700'
                                                    : ($ticket->status === 'accepted'
                                                        ? 'bg-blue-100 text-blue-700'
                                                        : 'bg-emerald-100 text-emerald-700') }}"
                                            >
                                                {{ $ticket->status }}
                                            </span>

                                        </div>

                                        <div class="grid grid-cols-2 gap-4 mb-6">

                                            <div>

                                                <p class="text-[9px] text-slate-400 font-black uppercase tracking-tighter">
                                                    Registered
                                                </p>

                                                <p class="text-xs font-bold text-slate-700">
                                                    {{ $ticket->created_at->format('M d, Y') }}
                                                </p>

                                            </div>

                                            <div>

                                                <p class="text-[9px] text-slate-400 font-black uppercase tracking-tighter">
                                                    Amount Paid
                                                </p>

                                                <p class="text-xs font-bold text-slate-700">
                                                    {{ $ticket->price }} MMK
                                                </p>

                                            </div>

                                        </div>

                                        <div class="flex gap-2">

                                            @if($ticket->status === 'approved')

                                                <a
                                                    href="{{ route('ticket.download', $ticket->id) }}"
                                                    class="flex-1 bg-slate-900 text-white text-[11px] text-center font-bold py-3 rounded-xl hover:bg-black transition-all"
                                                >

                                                    <i class="fas fa-file-pdf mr-2 text-brand"></i>

                                                    Download Ticket

                                                </a>

                                            @else

                                                <div class="flex-1 bg-slate-100 text-slate-400 text-[11px] text-center font-bold py-3 rounded-xl cursor-not-allowed">

                                                    <i class="fas fa-lock mr-2"></i>

                                                    Payment Pending

                                                </div>

                                            @endif

                                            <button
                                                type="button"
                                                class="px-4 bg-slate-50 text-slate-400 rounded-xl hover:bg-slate-100 transition-all"
                                            >

                                                <i class="fas fa-ellipsis-h"></i>

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="bg-white p-12 rounded-[40px] text-center border-2 border-dashed border-slate-100">

                                <p class="text-slate-500 font-bold italic">
                                    No {{ request('status') ?? 'active' }} tickets found.
                                </p>

                                <a
                                    href="/event"
                                    class="text-brandDark font-black text-sm uppercase mt-2 inline-block"
                                >
                                    Browse Races →
                                </a>

                            </div>

                        @endforelse

                    </div>

                    <div class="mt-8 px-2">
                        {{ $tickets->links() }}
                    </div>

                </div>

            </div>

        </div>

    </main>

</div>


<!-- ========================================================= -->
<!-- MOBILE BOTTOM NAV -->
<!-- ========================================================= -->

<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t flex justify-around p-4 z-50">

    <button class="text-brandDark">
        <i class="fas fa-th-large text-xl"></i>
    </button>

    <button class="text-slate-300">
        <i class="fas fa-running text-xl"></i>
    </button>

    <button class="text-slate-300">
        <i class="fas fa-shopping-bag text-xl"></i>
    </button>

    <button
        type="button"
        onclick="openProfileModal()"
        class="text-slate-300"
    >
        <i class="fas fa-user text-xl"></i>
    </button>

</div>


<!-- ========================================================= -->
<!-- SUCCESS MODAL -->
<!-- ========================================================= -->

@if(session('success'))

<div
    id="successModal"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[100] flex items-center justify-center p-4"
>

    <div class="bg-white rounded-[3rem] w-full max-w-sm p-10 shadow-2xl text-center transform transition-all animate-bounce-short">

        <div class="w-24 h-24 bg-[#C3E92D] rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-lime-200">

            <i class="fas fa-check text-4xl text-slate-900"></i>

        </div>

        <h2 class="text-2xl font-black text-slate-800 uppercase italic mb-2">
            Registration Updated!
        </h2>

        <p class="text-slate-500 text-sm font-semibold mb-8">
            {{ session('success') }}
        </p>

        <button
            type="button"
            onclick="document.getElementById('successModal').remove()"
            class="w-full py-4 bg-slate-900 text-white font-black rounded-2xl uppercase tracking-widest text-xs hover:bg-slate-800 transition-all"
        >
            Awesome!
        </button>

    </div>

</div>

<style>

    @keyframes bounce-short {

        0%, 100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

    }

    .animate-bounce-short {
        animation: bounce-short 0.5s ease-in-out;
    }

</style>

@endif


<!-- ========================================================= -->
<!-- PROFILE MODAL -->
<!-- ========================================================= -->

<div
    id="profileModal"
    class="fixed inset-0 bg-slate-950/70 backdrop-blur-md z-[200] hidden items-center justify-center p-4"
>

    <div
        id="profileModalContent"
        class="bg-white w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-[2.5rem] shadow-2xl transform scale-95 opacity-0 transition-all duration-300"
    >

        <!-- ===================================================== -->
        <!-- PROFILE HEADER -->
        <!-- ===================================================== -->

        <div class="relative bg-slate-900 px-8 py-8 overflow-hidden">

            <div class="absolute -right-10 -top-10 w-40 h-40 bg-brand/20 rounded-full"></div>

            <div class="absolute -left-16 -bottom-20 w-48 h-48 bg-brand/10 rounded-full"></div>

            <button
                type="button"
                onclick="closeProfileModal()"
                class="absolute top-5 right-5 w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all z-10"
            >

                <i class="fas fa-times"></i>

            </button>

            <div class="relative z-10 flex items-center gap-5">

                <div class="w-24 h-24 rounded-2xl bg-white p-1 border-2 border-brand overflow-hidden shrink-0">

                    @if($athlete && $athlete->face_image_path)

                        <img
                            src="{{ asset('storage/' . $athlete->face_image_path) }}"
                            class="w-full h-full object-cover rounded-xl"
                            alt="Profile"
                        >

                    @else

                        <div class="w-full h-full rounded-xl bg-slate-800 flex items-center justify-center text-brand font-black text-3xl uppercase">

                            {{ substr($user->first_name, 0, 1) }}

                        </div>

                    @endif

                </div>

                <div>

                    <p class="text-brand text-[10px] font-black uppercase tracking-[3px] mb-1">
                        Runner Profile
                    </p>

                    <h2 class="text-2xl md:text-3xl font-black text-white uppercase italic tracking-tight">

                        {{ $user->first_name }}

                        @if($user->middle_name)
                            {{ $user->middle_name }}
                        @endif

                        {{ $user->last_name }}

                    </h2>

                    <p class="text-white/50 text-xs font-bold mt-1">
                        {{ $user->runner_id }}
                    </p>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- PROFILE VIEW -->
        <!-- ===================================================== -->

        <div id="profileView" class="p-6 md:p-8">

            <div class="mt-2">

                <div class="flex items-center justify-between mb-4">

                    <div>

                        <p class="text-[10px] font-black text-brand uppercase tracking-[3px]">
                            My Registrations
                        </p>

                        <h3 class="text-xl font-black text-slate-900">
                            Registered Events
                        </h3>

                    </div>

                </div>

                <div class="space-y-3">

                    @forelse($tickets->take(5) as $profileTicket)

                        <div class="bg-slate-50 rounded-2xl p-4">

                            <div class="flex items-center justify-between gap-3">

                                <div class="min-w-0">

                                    <p class="text-sm font-black text-slate-800 truncate">
                                        {{ $profileTicket->event }}
                                    </p>

                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-1">
                                        {{ $profileTicket->category ?? 'Registration' }}
                                    </p>

                                </div>

                                <span
                                    class="shrink-0 px-3 py-1 rounded-full text-[8px] font-black uppercase
                                    {{ $profileTicket->status === 'pending'
                                        ? 'bg-amber-100 text-amber-700'
                                        : ($profileTicket->status === 'approved'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-slate-200 text-slate-600') }}"
                                >
                                    {{ $profileTicket->status }}
                                </span>

                            </div>

                        </div>

                    @empty

                        <p class="text-sm text-slate-400 font-bold">
                            No registrations found.
                        </p>

                    @endforelse

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- REGISTRATION EDIT VIEW -->
        <!-- ===================================================== -->

        <div
            id="registrationEditView"
            class="hidden"
        >

            <div class="p-6 md:p-8">

                <!-- EDIT HEADER -->

                <div class="flex items-start justify-between gap-4 mb-6">

                    <div>

                        <p class="text-[10px] font-black text-brand uppercase tracking-[3px] mb-1">
                            Edit Registration
                        </p>

                        <h3
                            id="editEventName"
                            class="text-xl font-black text-slate-900 uppercase italic"
                        >
                            Registration
                        </h3>

                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                            Only information enabled by this event can be edited.
                        </p>

                    </div>

                </div>


                <!-- LOADING -->

                <div
                    id="registrationEditLoading"
                    class="hidden py-12 text-center"
                >

                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 mb-4">

                        <i class="fas fa-spinner fa-spin text-slate-900"></i>

                    </div>

                    <p class="text-xs font-bold text-slate-400">
                        Loading registration...
                    </p>

                </div>


                <!-- ERROR -->

                <div
                    id="registrationEditError"
                    class="hidden mb-5 p-4 rounded-2xl bg-red-50 border border-red-100 text-red-700 text-xs font-bold"
                ></div>


                <!-- FORM -->

                <form
                    id="registrationEditForm"
                    method="POST"
                    enctype="multipart/form-data"
                    class="hidden"
                >

                    @csrf

                    @method('PUT')

                    <input
                        type="hidden"
                        name="event_id"
                        id="editEventId"
                    >


                    <!-- ================================================= -->
                    <!-- 01 REQUIRED -->
                    <!-- ================================================= -->

                    <div
                        id="editSectionRequired"
                        class="hidden mb-8"
                    >

                        <div class="flex items-center mb-10">

                            <span class="w-10 h-10 bg-[#C3E92D] text-slate-900 rounded-xl flex items-center justify-center font-black mr-4 shadow-lg shadow-lime-100">
                                01
                            </span>

                            <h3 class="text-xl font-black text-slate-800 uppercase italic">
                                Required Information
                            </h3>

                        </div>


                        <div class="space-y-8">


                           <!-- =========================================================
     PROFILE PICTURE
========================================================= -->

<div class="flex flex-col items-center">

    <label class="label-text mb-3">
        Profile Picture
    </label>

    <!--
        IMPORTANT:
        This input is NOT clicked by the user.

        JavaScript creates a File from the camera
        and puts that File into this input.
    -->
    <input
        type="file"
        name="face_image"
        id="edit_face_image"
        class="hidden"
        accept="image/jpeg,image/png,image/webp"
    >

    <input
        type="hidden"
        name="existing_face"
        id="edit_existing_face"
    >

    <!-- Profile preview -->
    <div
        class="relative w-24 h-24 rounded-full overflow-hidden border-4 border-slate-100 bg-slate-50 cursor-pointer group"
        onclick="openEditCamera()"
    >

        <img
            id="edit_face_preview"
            src=""
            alt="Profile"
            class="hidden absolute inset-0 w-full h-full object-cover"
        >

        <div
            id="edit_face_placeholder"
            class="absolute inset-0 flex flex-col items-center justify-center"
        >

            <i
                class="fas fa-face-viewfinder text-2xl text-slate-300 group-hover:text-[#C3E92D] transition-colors"
            ></i>

            <span
                class="text-[8px] font-black text-slate-400 mt-1 uppercase"
            >
                Tap to Setup
            </span>

        </div>

        <div
            class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all"
        ></div>

    </div>

    <p
        id="edit_face_status"
        class="text-[9px] text-slate-400 mt-3 text-center font-bold uppercase"
    >
        Profile picture
    </p>

</div>


<!-- =========================================================
     FACE CAMERA MODAL
========================================================= -->

<div
    id="editCameraModal"
    class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm z-[300] hidden items-center justify-center p-4"
>

    <div
        class="bg-white rounded-[2.5rem] w-full max-w-md p-6 md:p-8 shadow-2xl"
    >

        <!-- =================================================
             TITLE
        ================================================== -->

        <h3
            class="text-center text-slate-800 font-black uppercase italic mb-6"
        >
            Setup Face ID
        </h3>


        <!-- =================================================
             CAMERA SELECTION
        ================================================== -->

        <div
            id="editCameraSelection"
            class="grid grid-cols-1 gap-4"
        >

            <button
                type="button"
                onclick="startEditCamera()"
                class="flex flex-col items-center gap-3 p-6 rounded-3xl border-2 border-slate-100 hover:border-[#C3E92D] hover:bg-lime-50 transition-all group"
            >

                <div
                    class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:text-[#C3E92D]"
                >

                    <i class="fas fa-camera text-2xl"></i>

                </div>

                <span
                    class="text-[10px] font-black uppercase text-slate-600"
                >
                    Open Camera
                </span>

            </button>

        </div>


        <!-- =================================================
             CAMERA INTERFACE
        ================================================== -->

        <div
            id="editCameraInterface"
            class="hidden flex-col items-center"
        >

            <!-- Camera container -->

            <div
                id="editCameraContainer"
                class="relative w-full aspect-square rounded-3xl overflow-hidden bg-black border-4 border-slate-100 transition-all"
            >

                <video
                    id="editVideoFeed"
                    autoplay
                    playsinline
                    muted
                    class="w-full h-full object-cover"
                ></video>


                <!-- Face guide -->

                <div
                    class="absolute inset-0 pointer-events-none flex items-center justify-center"
                >

                    <div
                        id="faceGuide"
                        class="w-[62%] h-[72%] rounded-[50%] border-4 border-white/60 transition-all duration-300"
                    ></div>

                </div>


                <!-- Camera status overlay -->

                <div
                    class="absolute left-3 right-3 bottom-3"
                >

                    <div
                        id="cameraFaceStatus"
                        class="rounded-2xl px-4 py-3 bg-black/60 backdrop-blur-md text-center text-white"
                    >

                        <div
                            id="cameraFaceStatusIcon"
                            class="text-lg mb-1"
                        >
                            <i class="fas fa-face-smile"></i>
                        </div>

                        <div
                            id="cameraFaceStatusText"
                            class="text-[10px] font-black uppercase tracking-wider"
                        >
                            Detecting face...
                        </div>

                    </div>

                </div>

            </div>


            <!-- Hidden canvas -->

            <canvas
                id="editFaceCanvas"
                class="hidden"
            ></canvas>


            <!-- Capture button -->

            <button
                type="button"
                id="editCaptureButton"
                onclick="takeEditSnapshot()"
                disabled
                class="mt-6 w-20 h-20 bg-slate-300 rounded-full border-8 border-slate-50 flex items-center justify-center shadow-xl transition-all cursor-not-allowed"
            >

                <i
                    class="fas fa-camera text-2xl text-slate-500"
                ></i>

            </button>


            <!-- Status -->

            <p
                id="faceDetectionStatus"
                class="mt-4 text-center text-[10px] font-black uppercase tracking-wider text-slate-400"
            >
                Loading face detection...
            </p>

        </div>


        <!-- =================================================
             CLOSE BUTTON
        ================================================== -->

        <button
            type="button"
            onclick="closeEditCamera()"
            class="w-full mt-6 py-3 text-slate-400 font-bold uppercase text-[10px] tracking-widest hover:text-red-500 transition-colors"
        >
            Cancel
        </button>

    </div>

</div>


<!-- Hidden canvas used to capture the camera frame -->

<canvas
    id="editFaceCanvas"
    class="hidden"
></canvas>


                            <!-- NATIONALITY TYPE -->

                            <input
                                type="hidden"
                                name="nat_type"
                                id="edit_nat_type"
                            >


                            <!-- NAME -->

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                <div>

                                    <label class="label-text">
                                        First Name
                                    </label>

                                    <input
                                        type="text"
                                        name="first_name"
                                        id="edit_first_name"
                                        class="edit-input"
                                        required
                                    >

                                </div>

                                <div>

                                    <label class="label-text">
                                        Middle Name
                                    </label>

                                    <input
                                        type="text"
                                        name="middle_name"
                                        id="edit_middle_name"
                                        class="edit-input"
                                    >

                                </div>

                                <div>

                                    <label class="label-text">
                                        Last Name
                                    </label>

                                    <input
                                        type="text"
                                        name="last_name"
                                        id="edit_last_name"
                                        class="edit-input"
                                        required
                                    >

                                </div>

                            </div>


                            <!-- ================================================= -->
                            <!-- NRC -->
                            <!-- ================================================= -->

                            <div
                                id="edit_nrc_container"
                                class="space-y-4"
                            >

                                <label class="label-text">
                                    NRC Information
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-2">

                                    <select
                                        name="nrc_state"
                                        id="edit_nrc_state"
                                        class="edit-input !py-3 !text-sm"
                                        required
                                    >

                                        <option value="" disabled>State</option>

                                        <option value="1">၁/</option>
                                        <option value="2">၂/</option>
                                        <option value="3">၃/</option>
                                        <option value="4">၄/</option>
                                        <option value="5">၅/</option>
                                        <option value="6">၆/</option>
                                        <option value="7">၇/</option>
                                        <option value="8">၈/</option>
                                        <option value="9">၉/</option>
                                        <option value="10">၁၀/</option>
                                        <option value="11">၁၁/</option>
                                        <option value="12">၁၂/</option>
                                        <option value="13">၁၃/</option>
                                        <option value="14">၁၄/</option>

                                    </select>


                                    <select
                                        name="nrc_district"
                                        id="edit_nrc_district"
                                        class="edit-input !py-3 !text-sm"
                                        required
                                    >

                                        <option value="" disabled>District</option>

                                    </select>


                                    <select
                                        name="nrc_naing"
                                        id="edit_nrc_naing"
                                        class="edit-input !py-3 !text-sm"
                                        required
                                    >
                                        <option value="" disabled>Town</option>
                                        <option value="နိုင်">
                                            နိုင်
                                        </option>

                                        <option value="ဧည့်">
                                            ဧည့်
                                        </option>

                                        <option value="စ">
                                            စ
                                        </option>

                                        <option value="ပြု">
                                            ပြု
                                        </option>

                                        <option value="သ">
                                            သ
                                        </option>

                                        <option value="သီ">
                                            သီ
                                        </option>

                                    </select>


                                    <input
                                        type="text"
                                        name="nrc_number"
                                        id="edit_nrc_number"
                                        inputmode="numeric"
                                        maxlength="6"
                                        pattern="\d{6}"
                                        placeholder="123456"
                                        class="edit-input !py-3 !text-sm"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                                        required
                                    >

                                </div>

                            </div>


                            <!-- ================================================= -->
                            <!-- PASSPORT -->
                            <!-- ================================================= -->

                            <div
                                id="edit_passport_container"
                                class="hidden"
                            >

                                <label class="label-text">
                                    Passport Number
                                </label>

                                <input
                                    type="text"
                                    name="passport_id"
                                    id="edit_passport_id"
                                    maxlength="15"
                                    class="edit-input"
                                    placeholder="Passport Number"
                                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase()"
                                >

                            </div>

                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- 02 PERSONAL -->
                    <!-- ================================================= -->

                    <div
                        id="editSectionPersonal"
                        class="hidden mb-8"
                    >

                        <div class="mb-4">

                            <p class="text-[10px] font-black text-brand uppercase tracking-[2px]">
                                02
                            </p>

                            <h4 class="text-lg font-black text-slate-900">
                                Personal Information
                            </h4>

                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>

                                <label class="edit-label">
                                    Father Name
                                </label>

                                <input
                                    type="text"
                                    name="father_name"
                                    id="edit_father_name"
                                    class="edit-input"
                                >

                            </div>


                            <div>

                                <label class="edit-label">
                                    Date of Birth
                                </label>

                                <input
                                    type="text"
                                    name="dob"
                                    id="edit_dob"
                                    placeholder="DD/MM/YYYY"
                                    class="edit-input"
                                >

                            </div>


                            <div>

                                <label class="edit-label">
                                    Gender
                                </label>

                                <select
                                    name="gender"
                                    id="edit_gender"
                                    class="edit-input"
                                    required
                                >

                                    <option value="male">
                                        Male
                                    </option>

                                    <option value="female">
                                        Female
                                    </option>

                                </select>

                            </div>


                            <div>

                                <label class="edit-label">
                                    Nationality
                                </label>

                                <input
                                    type="text"
                                    name="nationality"
                                    id="edit_nationality"
                                    class="edit-input"
                                    required
                                >

                            </div>


                            <div>

                                <label class="edit-label">
                                    State / Division
                                </label>

                                <input
                                    type="text"
                                    name="state"
                                    id="edit_state"
                                    class="edit-input"
                                    required
                                >

                            </div>


                            <div class="md:col-span-2">

                                <label class="edit-label">
                                    Address
                                </label>

                                <textarea
                                    name="address"
                                    id="edit_address"
                                    rows="3"
                                    class="edit-input"
                                ></textarea>

                            </div>

                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- 03 CONTACT -->
                    <!-- ================================================= -->

                    <div
                        id="editSectionContact"
                        class="hidden mb-8"
                    >

                        <div class="mb-4">

                            <p class="text-[10px] font-black text-brand uppercase tracking-[2px]">
                                03
                            </p>

                            <h4 class="text-lg font-black text-slate-900">
                                Contact Information
                            </h4>

                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>

                                <label class="edit-label">
                                    Email
                                </label>

                                <input
                                    type="text"
                                    id="edit_email"
                                    readonly
                                    class="edit-input bg-slate-100 text-slate-400"
                                >

                            </div>


                            <div>

                                <label class="edit-label">
                                    Phone
                                </label>

                                <input
                                    type="text"
                                    id="edit_phone"
                                    readonly
                                    class="edit-input bg-slate-100 text-slate-400"
                                >

                            </div>


                            <div>

                                <label class="edit-label">
                                    Viber
                                </label>

                                <input
                                    type="text"
                                    name="viber"
                                    id="edit_viber"
                                    class="edit-input"
                                >

                            </div>


                            <div>

                                <label class="edit-label">
                                    Emergency Contact
                                </label>

                                <input
                                    type="text"
                                    name="contact"
                                    id="edit_contact"
                                    class="edit-input"
                                >

                            </div>

                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- 04 PARTICIPANT -->
                    <!-- ================================================= -->

                    <div
                        id="editSectionParticipant"
                        class="hidden mb-8"
                    >

                        <div class="mb-4">

                            <p class="text-[10px] font-black text-brand uppercase tracking-[2px]">
                                04
                            </p>

                            <h4 class="text-lg font-black text-slate-900">
                                Participant's Info
                            </h4>

                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                            <!-- BIB NAME -->

                            <div>

                                <label class="edit-label">
                                    BIB Name
                                </label>

                                <input
                                    type="text"
                                    name="bib_name"
                                    id="edit_bib_name"
                                    maxlength="10"
                                    class="edit-input"
                                    required
                                >

                            </div>


                            <!-- T-SHIRT -->

                            <div>

                                <label class="edit-label">
                                    T-Shirt Size
                                </label>

                                <select
                                    name="t_shirt_size"
                                    id="edit_t_shirt_size"
                                    class="edit-input"
                                    required
                                >

                                    <option value="" disabled>
                                        Select Size
                                    </option>

                                    <option value="XS">XS</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="2XL">2XL</option>
                                    <option value="3XL">3XL</option>
                                    <option value="5XL">5XL</option>
                                </select>

                            </div>


                            <!-- EXPERIENCE -->

                            <div>

                                <label class="edit-label">
                                    Experience Level
                                </label>

                                <select
                                    name="experience_level"
                                    id="edit_experience_level"
                                    class="edit-input"
                                    required
                                >

                                    <option value="" disabled>
                                        Select Experience
                                    </option>

                                    <option value="Never">
                                        Never
                                    </option>

                                    <option value="25KM">
                                        Within 25KM
                                    </option>

                                    <option value="25KM-50KM">
                                        Within 25KM and 50KM
                                    </option>

                                    <option value="50KM+">
                                        50KM+
                                    </option>

                                </select>

                            </div>


                            <!-- BLOOD -->

                            <div>
    <label class="edit-label">
        Blood Type
    </label>

    <select
        name="blood_type"
        id="edit_blood_type"
        class="edit-input"
        required
    >
        <option value="" disabled>
            Select Blood Type
        </option>

        <option value="O">O</option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="AB">AB</option>
        <option value="Unknown">Unknown</option>
    </select>
</div>


                            <!-- MEDICAL CONDITION -->

                            <div>

                                <label class="edit-label">
                                    Medical Condition
                                </label>

                                <select
                                    name="has_medical_condition"
                                    id="edit_has_medical_condition"
                                    class="edit-input"
                                    required
                                >

                                    <option value="0">
                                        No
                                    </option>

                                    <option value="1">
                                        Yes
                                    </option>

                                </select>

                            </div>


                            <!-- MEDICAL DETAILS -->

                            <div
                                id="edit_medical_details_container"
                                class="md:col-span-2 hidden"
                            >

                                <label class="edit-label">
                                    Medical Details
                                </label>

                                <textarea
                                    name="medical_details"
                                    id="edit_medical_details"
                                    rows="3"
                                    class="edit-input"
                                ></textarea>

                            </div>

                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- SAVE -->
                    <!-- ================================================= -->

                    <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">

                        <button
                            type="button"
                            onclick="closeRegistrationEdit()"
                            class="px-6 py-3 bg-slate-100 text-slate-700 text-[10px] font-black uppercase tracking-widest rounded-xl"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            id="saveRegistrationBtn"
                            class="px-6 py-3 bg-slate-900 hover:bg-black text-white text-[10px] font-black uppercase tracking-widest rounded-xl"
                        >

                            <i class="fas fa-save mr-2"></i>

                            Save Changes

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
<script>

let editCameraStream = null;


/* ========================================================= */
/* OPEN CAMERA */
/* ========================================================= */

async function openEditCamera() {

    const modal = document.getElementById('editCameraModal');
    const video = document.getElementById('editVideoFeed');

    if (!modal || !video) {
        return;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    try {

        editCameraStream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: {
                    ideal: 'user'
                },
                width: {
                    ideal: 1280
                },
                height: {
                    ideal: 1280
                }
            },
            audio: false
        });

        video.srcObject = editCameraStream;

        await video.play();

    } catch (error) {

        console.error('Camera error:', error);

        closeEditCamera();

        alert(
            'Unable to access the camera. Please allow camera permission and try again.'
        );
    }
}


/* ========================================================= */
/* TAKE SNAPSHOT */
/* ========================================================= */

function takeEditSnapshot() {

    const video = document.getElementById('editVideoFeed');
    const canvas = document.getElementById('editFaceCanvas');
    const fileInput = document.getElementById('edit_face_image');

    const preview = document.getElementById('edit_face_preview');
    const placeholder = document.getElementById('edit_face_placeholder');
    const status = document.getElementById('edit_face_status');

    if (!video || !canvas || !fileInput) {
        console.error('Camera elements missing.');
        return;
    }

    if (video.videoWidth === 0 || video.videoHeight === 0) {
        alert('Camera is not ready. Please wait a moment and try again.');
        return;
    }

    const size = Math.min(
        video.videoWidth,
        video.videoHeight
    );

    const sx = (video.videoWidth - size) / 2;
    const sy = (video.videoHeight - size) / 2;

    canvas.width = 800;
    canvas.height = 800;

    const ctx = canvas.getContext('2d');

    // Mirror front camera
    ctx.save();

    ctx.translate(800, 0);
    ctx.scale(-1, 1);

    ctx.drawImage(
        video,
        sx,
        sy,
        size,
        size,
        0,
        0,
        800,
        800
    );

    ctx.restore();


    // Convert camera image to actual JPEG file
    canvas.toBlob(function (blob) {

        if (!blob) {
            alert('Could not capture photo.');
            return;
        }

        const file = new File(
            [blob],
            'face_' + Date.now() + '.jpg',
            {
                type: 'image/jpeg',
                lastModified: Date.now()
            }
        );


        // Put File into the actual face_image input
        const dataTransfer = new DataTransfer();

        dataTransfer.items.add(file);

        fileInput.files = dataTransfer.files;


        // Preview
        const previewUrl = URL.createObjectURL(file);

        preview.src = previewUrl;
        preview.classList.remove('hidden');

        if (placeholder) {
            placeholder.classList.add('hidden');
        }

        if (status) {
            status.textContent = 'Photo ready';
            status.classList.remove('text-slate-400');
            status.classList.add('text-green-600');
        }


        // Verify
        console.log('FACE IMAGE:', file);
        console.log('FILE NAME:', fileInput.files[0]?.name);
        console.log('FILE SIZE:', fileInput.files[0]?.size);
        console.log('FILE TYPE:', fileInput.files[0]?.type);


        // Close camera
        closeEditCamera();

    }, 'image/jpeg', 0.90);
}


/* ========================================================= */
/* CLOSE CAMERA */
/* ========================================================= */

function closeEditCamera() {

    const modal = document.getElementById('editCameraModal');
    const video = document.getElementById('editVideoFeed');

    if (editCameraStream) {

        editCameraStream
            .getTracks()
            .forEach(function (track) {
                track.stop();
            });

        editCameraStream = null;
    }


    if (video) {
        video.srcObject = null;
    }


    if (modal) {

        modal.classList.add('hidden');
        modal.classList.remove('flex');

    }
}


/* ========================================================= */
/* CLEAN CAMERA WHEN PAGE IS CLOSED */
/* ========================================================= */

window.addEventListener(
    'beforeunload',
    function () {

        if (editCameraStream) {

            editCameraStream
                .getTracks()
                .forEach(function (track) {
                    track.stop();
                });

        }

    }
);

</script>

<!-- ============================================================= -->
<!-- JAVASCRIPT -->
<!-- ============================================================= -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =============================================================
       NRC DISTRICT DATA
    ============================================================= */

    const editNrcDistricts = {

        "1": ["ကပတ","ကမတ","ခပန","ခလဖ","ဆဒန","ဆပရ","ဆဘန","တဆလ","တနန","ဒဖယ","နမန","ပတအ","ပနဒ","ပဝန","ဖကန","ဗမန","မကတ","မကန","မခဘ","မစန","မညန","မမန","မလန","ရှကန","ရှဗယ","လဂျန","ဟပန","အဂျယ","၀မန"],

        "2": ["ဒမဆ","ဖဆန","ဖရဆ","ဘလခ","မစန","ရတန","ရသန","လကန"],

        "3": ["ကကရ","ကဆက","ကဒတ","ကဒန","ကမမ","စကလ","ပကန","ဖပန","ဘဂလ","ဘသဆ","ဘအန","မဝတ","ရသန","လဘန","လသန","ဝလမ","သတက","သတန"],

        "4": ["ကခန","ကပလ","ဆမန","တဇန","တတန","ထတလ","ပလဝ","ဖလန","မတန","မတပ","ရခဒ","ရဇန","ဟခန"],

        "5": ["ကနန","ကဘလ","ကမန","ကလတ","ကလထ","ကလန","ကလဝ","ကသန","ခတန","ခပန","ခဥတ","ခဥန","ငဇန","စကန","ဆလက","တဆန","တမန","ထခန","ဒပယ","နယန","ပလန","ပလဘ","ဖပန","ဗမန","ဘတလ","မကန","မမတ","မမန","မရန","မလန","ယမပ","ရဘန","ရဥန","လရန","လဟန","ဝလန","ဝသန","ဟမလ","အတန","အရတ"],

        "6": ["ကစန","ကရရ","ကလအ","ကသန","ခမန","တသရ","ထဝန","ပလတ","ပလန","ဘပန","မတန","မမန","ရဖြန","လလန","သရခ"],

        "7": ["ကကန","ကတခ","ကပက","ကဝန","ဇကန","ညလပ","တငန","ထတပ","ဒဥန","နတလ","ပခတ","ပခန","ပတဆ","ပတတ","ပတန","ပနက","ပမန","ဖမန","မညန","မလန","ရကန","ရတန","ရတရှ","လပတ","ဝမန","သကန","သဆန","သနပ","သဝတ","အတန","အဖန"],

        "8": ["ကထန","ကမန","ခမန","ဂဂန","ငဖန","စတရ","စလန","ဆပဝ","ဆဖန","ဆမန","တတက","ထလန","နမန","ပခက","ပဖြန","ပမန","မကန","မတန","မထန","မဘန","မမန","မလန","မသန","ရစက","ရနခ","သရန","အလန"],

        "9": ["ကဆန","ကပတ","ခမစ","ခအစ","ငဇန","ငသရ","စကတ","စကန","ဇဗသ","ဇယသ","ညဥန","တကတ","တကန","တတဥ","တသန","ဒခသ","နထက","ပကခ","ပဗသ","ပဘန","ပမန","ပသက","ပဥလ","မကန","မခန","မတရ","မထလ","မမန","မလန","မသန","မဟမ","ရမသ","လဝန","ဝတန","သစန","သပက","အမစ","အမရ","ဥတသ"],

        "10": ["ကထန","ကမရ","ခဆန","ခဇန","ပမန","ဘလန","မဒန","မလမ","ရမန","လမန","သထန","သဖြရ"],

        "11": ["ကတန","ကတလ","ကဖန","ဂမန","စတန","တကန","တပဝ","ပဏတ","ပတန","ဗတထ","ဘသတ","မတန","မပတ","မပန","မအတ","မအန","မဥန","ရဗန","ရသတ","သတန","အမန"],

        "12": ["ကကက","ကခက","ကတတ","ကတန","ကမတ","ကမန","ကမရ","ခရန","စခန","ဆကခ","ဆကန","တကန","တတထ","တတန","တမန","ထတပ","ဒဂဆ","ဒဂတ","ဒဂန","ဒဂမ","ဒဂရ","ဒပန","ဒလန","ပဇတ","ပဘတ","ဗတထ","ဗဟန","မဂတ","မဂဒ","မဘန","မရက","ရကန","ရပသ","လကန","လမတ","လမန","လသန","လသယ","သကတ","သခန","သဃက","သလန","အစန","အလန","ဥကတ","ဥကန","ဥကမ"],

        "13": ["ကခန","ကတတ","ကတန","ကတလ","ကမဆ","ကမန","ကရန","ကလတ","ကလဒ","ကလန","ကလဖ","ကသန","ကဟန","ခမန","ခရဟ","ခလန","ဆဆန","ဆဖန","ညရန","တကန","တခလ","တမည","တယန","တလန","နကန","နခတ","နခန","နခဝ","နဆန","နတန","နတယ","နဖန","နမတ","နဝန","ပခန","ပဆန","ပတယ","ပပက","ပယန","ပလတ","ပလန","ပဝန","ဖခန","မကန","မခန","မငန","မဆတ","မဆန","မတတ","မတန","မနန","မပန","မဖန","မဗတ","မဘန","မမဆ","မမတ","မမန","မယန","မရတ","မရန","မလန","မဟရ","ယလန","ရငန","ရစန","ရဖန","လကတ","လခတ","လခန","လရန","လလန","လဟန","သနန","သပန","ဟတန","ဟပတ","ဟပန","အခန","အတန"],

        "14": ["ကကထ","ကကန","ကခန","ကပန","ကလန","ငဆန","ငပတ","ငရက","ငသခ","ငသယ","ဇလန","ညတန","ဒဒရ","ဒနဖြ","ပစလ","ပတန","ပသန","ဖပန","ဘကလ","မမက","မမန","မအန","မအပ","ရကန","ရသယ","လပတ","လမန","ဝခမ","သပန","ဟကကျ","ဟသတ","အဂပ","အမတ","အမန"]

    };


    /* =============================================================
       ELEMENTS
    ============================================================= */

    const editNrcStateSelect =
        document.getElementById('edit_nrc_state');

    const editNrcDistrictSelect =
        document.getElementById('edit_nrc_district');

    const editNrcNaingSelect =
        document.getElementById('edit_nrc_naing');

    const editNrcNumber =
        document.getElementById('edit_nrc_number');

    const editNatType =
        document.getElementById('edit_nat_type');

    const editNrcContainer =
        document.getElementById('edit_nrc_container');

    const editPassportContainer =
        document.getElementById('edit_passport_container');

    const editPassportId =
        document.getElementById('edit_passport_id');


    /* =============================================================
       MEDICAL ELEMENTS
    ============================================================= */

    const medicalConditionSelect =
        document.getElementById('edit_has_medical_condition');

    const medicalDetailsContainer =
        document.getElementById('edit_medical_details_container');

    const medicalDetails =
        document.getElementById('edit_medical_details');


    function updateMedicalDetailsVisibility() {

        if (
            !medicalConditionSelect ||
            !medicalDetailsContainer ||
            !medicalDetails
        ) {
            return;
        }

        if (medicalConditionSelect.value === '1') {

            medicalDetailsContainer.classList.remove('hidden');

        } else {

            medicalDetailsContainer.classList.add('hidden');

            medicalDetails.value = '';

        }

    }


    if (medicalConditionSelect) {

        medicalConditionSelect.addEventListener(
            'change',
            updateMedicalDetailsVisibility
        );

    }


    /* =============================================================
       NRC DISTRICTS
    ============================================================= */

    window.populateEditNrcDistricts = function (
        state,
        selectedDistrict = ''
    ) {

        if (!editNrcDistrictSelect) {
            return;
        }

        editNrcDistrictSelect.innerHTML =
            '<option value="">District</option>';

        const districts =
            editNrcDistricts[String(state)] || [];

        districts.forEach(function (district) {

            const option =
                document.createElement('option');

            option.value = district;

            option.textContent = district;

            if (
                String(district).trim() ===
                String(selectedDistrict).trim()
            ) {

                option.selected = true;

            }

            editNrcDistrictSelect.appendChild(option);

        });

    };


    /* =============================================================
       NRC STATE CHANGE
    ============================================================= */

    if (editNrcStateSelect) {

        editNrcStateSelect.addEventListener(
            'change',
            function () {

                populateEditNrcDistricts(
                    this.value,
                    ''
                );

            }
        );

    }


    /* =============================================================
   EDIT REGISTRATION — CAMERA
============================================================= */

let editStream = null;

const editFaceInput =
    document.getElementById('edit_face_image');

const editFacePreview =
    document.getElementById('edit_face_preview');

const editFacePlaceholder =
    document.getElementById('edit_face_placeholder');

const editFaceStatus =
    document.getElementById('edit_face_status');


    /* =============================================================
       OPEN PROFILE MODAL
    ============================================================= */

    window.openProfileModal =
        async function () {

            const modal =
                document.getElementById('profileModal');

            const content =
                document.getElementById('profileModalContent');

            if (!modal || !content) {
                return;
            }

            modal.classList.remove('hidden');

            modal.classList.add('flex');

            document.body.classList.add(
                'overflow-hidden'
            );


            requestAnimationFrame(
                function () {

                    content.classList.remove(
                        'scale-95',
                        'opacity-0'
                    );

                    content.classList.add(
                        'scale-100',
                        'opacity-100'
                    );

                }
            );


            /* =====================================================
               AUTOMATICALLY OPEN REGISTRATION EDIT
            ===================================================== */

            @if($tickets->count() > 0)

                @php
                    $firstProfileTicket = $tickets->first();
                @endphp

                @if(
                    $firstProfileTicket->athlete_edit_enabled &&
                    (
                        $firstProfileTicket->can_edit_required ||
                        $firstProfileTicket->can_edit_personal ||
                        $firstProfileTicket->can_edit_contact ||
                        $firstProfileTicket->can_edit_participant
                    ) &&
                    $firstProfileTicket->athlete_edit_event
                )

                    openRegistrationEdit(
                        {{ $firstProfileTicket->athlete_edit_event->id }}
                    );

                @endif

            @endif

        };


    /* =============================================================
       CLOSE PROFILE MODAL
    ============================================================= */

    window.closeProfileModal =
        function () {

            const modal =
                document.getElementById('profileModal');

            const content =
                document.getElementById('profileModalContent');

            if (!modal || !content) {
                return;
            }

            content.classList.remove(
                'scale-100',
                'opacity-100'
            );

            content.classList.add(
                'scale-95',
                'opacity-0'
            );

            setTimeout(
                function () {

                    modal.classList.remove('flex');

                    modal.classList.add('hidden');

                    document.body.classList.remove(
                        'overflow-hidden'
                    );

                },
                250
            );

        };


    /* =============================================================
       OPEN REGISTRATION EDIT
    ============================================================= */

    window.openRegistrationEdit =
        async function (eventId) {

            const profileView =
                document.getElementById('profileView');

            const editView =
                document.getElementById('registrationEditView');

            const loading =
                document.getElementById('registrationEditLoading');

            const errorBox =
                document.getElementById('registrationEditError');

            const form =
                document.getElementById('registrationEditForm');

            const requiredSection =
                document.getElementById('editSectionRequired');

            const personalSection =
                document.getElementById('editSectionPersonal');

            const contactSection =
                document.getElementById('editSectionContact');

            const participantSection =
                document.getElementById('editSectionParticipant');


            if (
                !profileView ||
                !editView ||
                !loading ||
                !errorBox ||
                !form
            ) {
                console.error(
                    'Registration edit elements are missing.'
                );

                return;
            }


            /* =====================================================
               RESET SECTIONS
            ===================================================== */

            [
                requiredSection,
                personalSection,
                contactSection,
                participantSection
            ].forEach(
                function (section) {

                    if (section) {

                        section.classList.add(
                            'hidden'
                        );

                    }

                }
            );


            errorBox.classList.add('hidden');

            errorBox.textContent = '';

            form.classList.add('hidden');

            loading.classList.remove('hidden');

            profileView.classList.add('hidden');

            editView.classList.remove('hidden');


            try {

                const response =
                    await fetch(
                        `/athlete/registration/${eventId}/edit`,
                        {
                            method: 'GET',

                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    );


                const data =
                    await response.json();


                if (
                    !response.ok ||
                    !data.success
                ) {

                    throw new Error(
                        data.message ||
                        'Unable to load registration.'
                    );

                }


                /* =================================================
                   EVENT
                ================================================= */

                const eventIdInput =
                    document.getElementById('editEventId');

                const eventName =
                    document.getElementById('editEventName');

                if (eventIdInput) {

                    eventIdInput.value =
                        data.event?.id || '';

                }

                if (eventName) {

                    eventName.textContent =
                        data.event?.name || 'Registration';

                }


                /* =================================================
                   PERMISSIONS
                ================================================= */

                if (
                    requiredSection &&
                    data.permissions?.required === true
                ) {

                    requiredSection.classList.remove(
                        'hidden'
                    );

                }

                if (
                    personalSection &&
                    data.permissions?.personal === true
                ) {

                    personalSection.classList.remove(
                        'hidden'
                    );

                }

                if (
                    contactSection &&
                    data.permissions?.contact === true
                ) {

                    contactSection.classList.remove(
                        'hidden'
                    );

                }

                if (
                    participantSection &&
                    data.permissions?.participant === true
                ) {

                    participantSection.classList.remove(
                        'hidden'
                    );

                }


                /* =================================================
                   DATA
                ================================================= */

                const athlete =
                    data.athlete || {};

                const user =
                    data.user || {};

                const ticket =
                    data.ticket || {};


                /* =================================================
                   NATIONALITY
                ================================================= */

                const natType =
                    athlete.nat_type ||
                    'national';


                if (editNatType) {

                    editNatType.value =
                        natType;

                }


                /* =================================================
                   NAME
                ================================================= */

                const firstName =
                    document.getElementById('edit_first_name');

                const middleName =
                    document.getElementById('edit_middle_name');

                const lastName =
                    document.getElementById('edit_last_name');


                if (firstName) {

                    firstName.value =
                        athlete.first_name ?? '';

                }

                if (middleName) {

                    middleName.value =
                        athlete.middle_name ?? '';

                }

                if (lastName) {

                    lastName.value =
                        athlete.last_name ?? '';

                }


                /* =================================================
                   NRC / PASSPORT
                ================================================= */

                if (
                    natType === 'national'
                ) {

                    if (editNrcContainer) {

                        editNrcContainer.classList.remove(
                            'hidden'
                        );

                    }

                    if (editPassportContainer) {

                        editPassportContainer.classList.add(
                            'hidden'
                        );

                    }


                    const nrcState =
                        athlete.nrc_state ?? '';

                    const nrcDistrict =
                        athlete.nrc_district ?? '';


                    if (editNrcStateSelect) {

                        editNrcStateSelect.value =
                            String(nrcState);

                    }


                    populateEditNrcDistricts(
                        String(nrcState),
                        nrcDistrict
                    );


                    if (editNrcNaingSelect) {

                        editNrcNaingSelect.value =
                            athlete.nrc_naing || 'နိုင်';

                    }


                    if (editNrcNumber) {

                        editNrcNumber.value =
                            athlete.nrc_number || '';

                    }

                } else {

                    if (editNrcContainer) {

                        editNrcContainer.classList.add(
                            'hidden'
                        );

                    }

                    if (editPassportContainer) {

                        editPassportContainer.classList.remove(
                            'hidden'
                        );

                    }


                    if (editPassportId) {

                        editPassportId.value =
                            athlete.passport_id ||
                            athlete.id_number ||
                            '';

                    }

                }


                /* =================================================
                   FACE IMAGE
                ================================================= */

                const facePreview =
                    document.getElementById(
                        'edit_face_preview'
                    );

                const facePlaceholder =
                    document.getElementById(
                        'edit_placeholderUI'
                    );

                const faceScanner =
                    document.getElementById(
                        'editScannerLine'
                    );

                const faceStatus =
                    document.getElementById(
                        'edit_face_status'
                    );

                const existingFace =
                    document.getElementById(
                        'edit_existing_face'
                    );


                if (
                    athlete.face_image_url
                ) {

                    if (facePreview) {

                        facePreview.src =
                            athlete.face_image_url;

                        facePreview.classList.remove(
                            'hidden'
                        );

                    }

                    if (facePlaceholder) {

                        facePlaceholder.classList.add(
                            'opacity-0'
                        );

                    }

                    if (faceScanner) {

                        faceScanner.style.display =
                            'none';

                    }

                    if (existingFace) {

                        existingFace.value =
                            athlete.face_image_path || '';

                    }

                    if (faceStatus) {

                        faceStatus.textContent =
                            'CURRENT FACE ID LOADED ✅';

                        faceStatus.classList.remove(
                            'text-slate-400'
                        );

                        faceStatus.classList.add(
                            'text-lime-500'
                        );

                    }

                } else {

                    if (facePreview) {

                        facePreview.src = '';

                        facePreview.classList.add(
                            'hidden'
                        );

                    }

                    if (facePlaceholder) {

                        facePlaceholder.classList.remove(
                            'opacity-0'
                        );

                    }

                    if (faceScanner) {

                        faceScanner.style.display =
                            '';

                    }

                    if (existingFace) {

                        existingFace.value = '';

                    }

                    if (faceStatus) {

                        faceStatus.textContent =
                            'Required for AI Photo Matching';

                        faceStatus.classList.remove(
                            'text-lime-500'
                        );

                        faceStatus.classList.add(
                            'text-slate-400'
                        );

                    }

                }


                /* =================================================
                   PERSONAL
                ================================================= */

                const fatherName =
                    document.getElementById(
                        'edit_father_name'
                    );

                const dob =
                    document.getElementById(
                        'edit_dob'
                    );

                const gender =
                    document.getElementById(
                        'edit_gender'
                    );

                const nationality =
                    document.getElementById(
                        'edit_nationality'
                    );

                const state =
                    document.getElementById(
                        'edit_state'
                    );

                const address =
                    document.getElementById(
                        'edit_address'
                    );


                if (fatherName) {

                    fatherName.value =
                        athlete.father_name ?? '';

                }

                if (dob) {

                    dob.value =
                        athlete.dob
                            ? formatDateForEdit(
                                athlete.dob
                            )
                            : '';

                }

                if (gender) {

                    gender.value =
                        athlete.gender ?? '';

                }

                if (nationality) {

                    nationality.value =
                        athlete.nationality ?? '';

                }

                if (state) {

                    state.value =
                        athlete.state ?? '';

                }

                if (address) {

                    address.value =
                        athlete.address ?? '';

                }


                /* =================================================
                   CONTACT
                ================================================= */

                const email =
                    document.getElementById(
                        'edit_email'
                    );

                const phone =
                    document.getElementById(
                        'edit_phone'
                    );

                const viber =
                    document.getElementById(
                        'edit_viber'
                    );

                const contact =
                    document.getElementById(
                        'edit_contact'
                    );


                if (email) {

                    email.value =
                        user.email ?? '';

                }

                if (phone) {

                    phone.value =
                        user.phone ?? '';

                }

                if (viber) {

                    viber.value =
                        athlete.viber ?? '';

                }

                if (contact) {

                    contact.value =
                        athlete.contact ?? '';

                }


                /* =================================================
                   PARTICIPANT
                ================================================= */

                const bibName =
                    document.getElementById(
                        'edit_bib_name'
                    );

                const tshirt =
                    document.getElementById(
                        'edit_t_shirt_size'
                    );

                const experience =
                    document.getElementById(
                        'edit_experience_level'
                    );

                const blood =
                    document.getElementById(
                        'edit_blood_type'
                    );


                if (bibName) {

                    bibName.value =
                        ticket.bib_name ?? '';

                }

                if (tshirt) {

                    tshirt.value =
                        ticket.t_shirt_size ?? '';

                }

                if (experience) {

                    experience.value =
                        ticket.experience_level ?? '';

                }

                if (blood) {

                    blood.value =
                        athlete.blood_type ?? '';

                }


                /* =================================================
                   MEDICAL
                ================================================= */

                if (medicalConditionSelect) {

                    medicalConditionSelect.value =
                        athlete.has_medical_condition
                            ? '1'
                            : '0';

                }

                if (medicalDetails) {

                    medicalDetails.value =
                        athlete.medical_details ?? '';

                }

                updateMedicalDetailsVisibility();


                /* =================================================
                   FORM ACTION
                ================================================= */

                form.action =
                    `/athlete/registration/${eventId}`;


                /* =================================================
                   DEBUG
                ================================================= */

                console.log(
                    'ATHLETE DATA:',
                    athlete
                );

                console.log(
                    'NRC DATA:',
                    {
                        state: athlete.nrc_state,
                        district: athlete.nrc_district,
                        naing: athlete.nrc_naing,
                        number: athlete.nrc_number,
                        id_number: athlete.id_number,
                        nat_type: athlete.nat_type
                    }
                );


                /* =================================================
                   SHOW FORM
                ================================================= */

                loading.classList.add(
                    'hidden'
                );

                form.classList.remove(
                    'hidden'
                );

            } catch (error) {

                console.error(
                    'Registration edit error:',
                    error
                );

                loading.classList.add(
                    'hidden'
                );

                errorBox.textContent =
                    error.message ||
                    'Unable to load registration.';

                errorBox.classList.remove(
                    'hidden'
                );

            }

        };


    /* =============================================================
       CLOSE REGISTRATION EDIT
    ============================================================= */

    window.closeRegistrationEdit =
        function () {

            const profileView =
                document.getElementById(
                    'profileView'
                );

            const editView =
                document.getElementById(
                    'registrationEditView'
                );

            const form =
                document.getElementById(
                    'registrationEditForm'
                );


            if (editView) {

                editView.classList.add(
                    'hidden'
                );

            }

            if (profileView) {

                profileView.classList.remove(
                    'hidden'
                );

            }

            if (form) {

                form.reset();

            }


            /* =====================================================
               RESET NRC
            ===================================================== */

            if (editNrcDistrictSelect) {

                editNrcDistrictSelect.innerHTML =
                    '<option value="">District</option>';

            }


            /* =====================================================
               RESET FACE
            ===================================================== */

            const preview =
                document.getElementById(
                    'edit_face_preview'
                );

            const placeholder =
                document.getElementById(
                    'edit_placeholderUI'
                );

            const scanner =
                document.getElementById(
                    'editScannerLine'
                );

            const status =
                document.getElementById(
                    'edit_face_status'
                );

            const existingFace =
                document.getElementById(
                    'edit_existing_face'
                );


            if (preview) {

                preview.src = '';

                preview.classList.add(
                    'hidden'
                );

            }

            if (placeholder) {

                placeholder.classList.remove(
                    'opacity-0'
                );

            }

            if (scanner) {

                scanner.style.display =
                    '';

            }

            if (existingFace) {

                existingFace.value = '';

            }

            if (status) {

                status.textContent =
                    'Required for AI Photo Matching';

                status.classList.remove(
                    'text-lime-500'
                );

                status.classList.add(
                    'text-slate-400'
                );

            }


            /* =====================================================
               RESET MEDICAL
            ===================================================== */

            updateMedicalDetailsVisibility();

        };


    /* =============================================================
       DATE FORMAT
    ============================================================= */

    function formatDateForEdit(date) {

        if (!date) {
            return '';
        }

        const parts =
            String(date).split('-');

        if (parts.length !== 3) {
            return date;
        }

        return `${parts[2]}/${parts[1]}/${parts[0]}`;

    }


    /* =============================================================
       CLOSE MODAL OUTSIDE
    ============================================================= */

    const profileModal =
        document.getElementById(
            'profileModal'
        );


    if (profileModal) {

        profileModal.addEventListener(
            'click',
            function (event) {

                if (
                    event.target === this
                ) {

                    closeProfileModal();

                }

            }
        );

    }


    /* =============================================================
       ESC KEY
    ============================================================= */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key !== 'Escape'
            ) {
                return;
            }

            const modal =
                document.getElementById(
                    'profileModal'
                );

            if (
                modal &&
                !modal.classList.contains(
                    'hidden'
                )
            ) {

                closeProfileModal();

            }

        }
    );

});
</script>
<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    let editCameraStream = null;

    let faceDetectionTimer = null;

    let faceApiReady = false;

    let currentFaceDetection = [];

    let currentFaceValid = false;

    let faceApiLoading = false;


    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const cameraModal =
        document.getElementById('editCameraModal');

    const cameraSelection =
        document.getElementById('editCameraSelection');

    const cameraInterface =
        document.getElementById('editCameraInterface');

    const video =
        document.getElementById('editVideoFeed');

    const canvas =
        document.getElementById('editFaceCanvas');

    const fileInput =
        document.getElementById('edit_face_image');

    const preview =
        document.getElementById('edit_face_preview');

    const placeholder =
        document.getElementById('edit_face_placeholder');

    const faceGuide =
        document.getElementById('faceGuide');

    const captureButton =
        document.getElementById('editCaptureButton');

    const cameraFaceStatus =
        document.getElementById('cameraFaceStatus');

    const cameraFaceStatusIcon =
        document.getElementById('cameraFaceStatusIcon');

    const cameraFaceStatusText =
        document.getElementById('cameraFaceStatusText');

    const detectionStatus =
        document.getElementById('faceDetectionStatus');



    /*
    |--------------------------------------------------------------------------
    | LOAD FACE API
    |--------------------------------------------------------------------------
    */

    async function loadFaceApi() {

        if (faceApiReady) {
            return true;
        }

        if (faceApiLoading) {
            return false;
        }

        faceApiLoading = true;


        try {

            console.log(
                'Loading face-api.js model...'
            );


            await faceapi.nets.tinyFaceDetector.loadFromUri(
                'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@0.22.2/weights'
            );


            faceApiReady = true;

            console.log(
                'Face-api.js model loaded.'
            );


            if (detectionStatus) {

                detectionStatus.textContent =
                    'Face detection ready';

                detectionStatus.className =
                    'mt-4 text-center text-[10px] font-black uppercase tracking-wider text-green-600';

            }


            return true;


        } catch (error) {

            console.error(
                'Face-api.js model loading error:',
                error
            );


            faceApiReady = false;


            if (detectionStatus) {

                detectionStatus.textContent =
                    'Face detection unavailable';

                detectionStatus.className =
                    'mt-4 text-center text-[10px] font-black uppercase tracking-wider text-red-500';

            }


            return false;

        } finally {

            faceApiLoading = false;

        }

    }



    /*
    |--------------------------------------------------------------------------
    | INITIALIZE FACE API
    |--------------------------------------------------------------------------
    */

    loadFaceApi();



    /*
    |--------------------------------------------------------------------------
    | OPEN CAMERA
    |--------------------------------------------------------------------------
    */

    window.openEditCamera = async function () {

        if (!cameraModal) {
            console.error(
                'Camera modal not found.'
            );
            return;
        }


        /*
        | Open modal
        */

        cameraModal.classList.remove(
            'hidden'
        );

        cameraModal.classList.add(
            'flex'
        );


        /*
        | Reset camera interface
        */

        if (cameraSelection) {

            cameraSelection.classList.remove(
                'hidden'
            );

            cameraSelection.classList.add(
                'grid'
            );

        }


        if (cameraInterface) {

            cameraInterface.classList.add(
                'hidden'
            );

            cameraInterface.classList.remove(
                'flex'
            );

        }


        currentFaceDetection = [];

        currentFaceValid = false;


        setCaptureButton(false);


        /*
        | Make sure model is loaded
        */

        if (!faceApiReady) {

            if (detectionStatus) {

                detectionStatus.textContent =
                    'Loading face detection...';

            }

            await loadFaceApi();

        }

    };



    /*
    |--------------------------------------------------------------------------
    | START CAMERA
    |--------------------------------------------------------------------------
    */

    window.startEditCamera = async function () {

        if (!video) {

            console.error(
                'Camera video element missing.'
            );

            return;

        }


        /*
        | Ensure face API is ready
        */

        if (!faceApiReady) {

            const ready =
                await loadFaceApi();

            if (!ready) {

                alert(
                    'Face detection could not be loaded. Please refresh the page and try again.'
                );

                return;

            }

        }


        /*
        | Request front camera
        */

        try {

            editCameraStream =
                await navigator.mediaDevices.getUserMedia({

                    video: {

                        facingMode: {
                            ideal: 'user'
                        },

                        width: {
                            ideal: 1280
                        },

                        height: {
                            ideal: 1280
                        }

                    },

                    audio: false

                });


        } catch (error) {

            console.error(
                'Camera access error:',
                error
            );


            alert(
                'Unable to access the camera. Please allow camera permission and try again.'
            );

            return;

        }


        /*
        | Show camera interface
        */

        if (cameraSelection) {

            cameraSelection.classList.add(
                'hidden'
            );

            cameraSelection.classList.remove(
                'grid'
            );

        }


        if (cameraInterface) {

            cameraInterface.classList.remove(
                'hidden'
            );

            cameraInterface.classList.add(
                'flex'
            );

        }


        /*
        | Attach stream
        */

        video.srcObject =
            editCameraStream;


        await video.play();


        /*
        | Reset
        */

        currentFaceDetection = [];

        currentFaceValid = false;


        setCaptureButton(false);


        updateCameraStatus(
            'Detecting face...',
            'waiting'
        );


        /*
        | Start live detection
        */

        startFaceDetection();

    };



    /*
    |--------------------------------------------------------------------------
    | START LIVE FACE DETECTION
    |--------------------------------------------------------------------------
    */

    function startFaceDetection() {

        stopFaceDetection();


        faceDetectionTimer =
            setInterval(
                async function () {

                    if (!faceApiReady) {
                        return;
                    }


                    if (
                        !video ||
                        video.readyState < 2
                    ) {
                        return;
                    }


                    if (
                        !video.videoWidth ||
                        !video.videoHeight
                    ) {
                        return;
                    }


                    try {

                        const detections =
                            await faceapi.detectAllFaces(
                                video,
                                new faceapi.TinyFaceDetectorOptions({

                                    inputSize: 320,

                                    scoreThreshold: 0.70

                                })
                            );


                        currentFaceDetection =
                            detections;


                        validateLiveFace(
                            detections
                        );


                    } catch (error) {

                        console.error(
                            'Live face detection error:',
                            error
                        );

                    }

                },
                300
            );

    }



    /*
    |--------------------------------------------------------------------------
    | STOP FACE DETECTION
    |--------------------------------------------------------------------------
    */

    function stopFaceDetection() {

        if (faceDetectionTimer) {

            clearInterval(
                faceDetectionTimer
            );

            faceDetectionTimer = null;

        }

    }



    /*
    |--------------------------------------------------------------------------
    | VALIDATE LIVE FACE
    |--------------------------------------------------------------------------
    */

    function validateLiveFace(
        detections
    ) {

        currentFaceValid = false;


        /*
        | No face
        */

        if (
            !detections ||
            detections.length === 0
        ) {

            updateCameraStatus(
                'No face detected — look at the camera',
                'error'
            );

            setCaptureButton(false);

            return;

        }


        /*
        | Multiple faces
        */

        if (
            detections.length > 1
        ) {

            updateCameraStatus(
                'Only one face should be visible',
                'error'
            );

            setCaptureButton(false);

            return;

        }


        /*
        | Exactly one face
        */

        const detection =
            detections[0];


        const box =
            detection.box;


        /*
        | Confidence
        */

        const confidence =
            detection.score;


        if (
            confidence < 0.75
        ) {

            updateCameraStatus(
                'Face is not clear enough',
                'error'
            );

            setCaptureButton(false);

            return;

        }


        /*
        | Face dimensions
        */

        const widthRatio =
            box.width /
            video.videoWidth;

        const heightRatio =
            box.height /
            video.videoHeight;


        /*
        | Face too small
        */

        if (
            widthRatio < 0.25 ||
            heightRatio < 0.25
        ) {

            updateCameraStatus(
                'Move closer to the camera',
                'warning'
            );

            setCaptureButton(false);

            return;

        }


        /*
        | Face too large
        */

        if (
            widthRatio > 0.80 ||
            heightRatio > 0.80
        ) {

            updateCameraStatus(
                'Move slightly farther away',
                'warning'
            );

            setCaptureButton(false);

            return;

        }


        /*
        | Face horizontal position
        */

        const faceCenterX =
            box.x +
            (box.width / 2);

        const videoCenterX =
            video.videoWidth / 2;


        const horizontalDistance =
            Math.abs(
                faceCenterX -
                videoCenterX
            );


        /*
        | Face too far left/right
        */

        if (
            horizontalDistance >
            video.videoWidth * 0.20
        ) {

            updateCameraStatus(
                'Center your face',
                'warning'
            );

            setCaptureButton(false);

            return;

        }


        /*
        | Face vertical position
        */

        const faceCenterY =
            box.y +
            (box.height / 2);

        const videoCenterY =
            video.videoHeight / 2;


        const verticalDistance =
            Math.abs(
                faceCenterY -
                videoCenterY
            );


        if (
            verticalDistance >
            video.videoHeight * 0.25
        ) {

            updateCameraStatus(
                'Move your face to the center',
                'warning'
            );

            setCaptureButton(false);

            return;

        }


        /*
        | Everything passed
        */

        currentFaceValid = true;


        updateCameraStatus(
            'Face detected — ready to capture',
            'success'
        );


        setCaptureButton(true);

    }



    /*
    |--------------------------------------------------------------------------
    | CAMERA STATUS
    |--------------------------------------------------------------------------
    */

    function updateCameraStatus(
        message,
        type
    ) {

        if (
            !cameraFaceStatus ||
            !cameraFaceStatusText ||
            !cameraFaceStatusIcon
        ) {
            return;
        }


        cameraFaceStatusText.textContent =
            message;


        /*
        | Reset classes
        */

        cameraFaceStatus.classList.remove(
            'bg-black/60',
            'bg-red-600/80',
            'bg-amber-500/80',
            'bg-green-600/80'
        );


        faceGuide.classList.remove(
            'border-white/60',
            'border-red-500',
            'border-amber-400',
            'border-green-400'
        );


        /*
        | Waiting
        */

        if (type === 'waiting') {

            cameraFaceStatus.classList.add(
                'bg-black/60'
            );

            cameraFaceStatusIcon.innerHTML =
                '<i class="fas fa-face-smile"></i>';

            faceGuide.classList.add(
                'border-white/60'
            );

        }


        /*
        | Error
        */

        else if (type === 'error') {

            cameraFaceStatus.classList.add(
                'bg-red-600/80'
            );

            cameraFaceStatusIcon.innerHTML =
                '<i class="fas fa-circle-xmark"></i>';

            faceGuide.classList.add(
                'border-red-500'
            );

        }


        /*
        | Warning
        */

        else if (type === 'warning') {

            cameraFaceStatus.classList.add(
                'bg-amber-500/80'
            );

            cameraFaceStatusIcon.innerHTML =
                '<i class="fas fa-triangle-exclamation"></i>';

            faceGuide.classList.add(
                'border-amber-400'
            );

        }


        /*
        | Success
        */

        else if (type === 'success') {

            cameraFaceStatus.classList.add(
                'bg-green-600/80'
            );

            cameraFaceStatusIcon.innerHTML =
                '<i class="fas fa-circle-check"></i>';

            faceGuide.classList.add(
                'border-green-400'
            );

        }

    }



    /*
    |--------------------------------------------------------------------------
    | ENABLE / DISABLE CAPTURE BUTTON
    |--------------------------------------------------------------------------
    */

    function setCaptureButton(
        enabled
    ) {

        if (!captureButton) {
            return;
        }


        captureButton.disabled =
            !enabled;


        if (enabled) {

            captureButton.classList.remove(
                'bg-slate-300',
                'cursor-not-allowed'
            );

            captureButton.classList.add(
                'bg-[#C3E92D]',
                'cursor-pointer',
                'hover:scale-105',
                'active:scale-95'
            );


            const icon =
                captureButton.querySelector('i');

            if (icon) {

                icon.classList.remove(
                    'text-slate-500'
                );

                icon.classList.add(
                    'text-slate-900'
                );

            }

        } else {

            captureButton.classList.remove(
                'bg-[#C3E92D]',
                'cursor-pointer',
                'hover:scale-105',
                'active:scale-95'
            );

            captureButton.classList.add(
                'bg-slate-300',
                'cursor-not-allowed'
            );


            const icon =
                captureButton.querySelector('i');

            if (icon) {

                icon.classList.remove(
                    'text-slate-900'
                );

                icon.classList.add(
                    'text-slate-500'
                );

            }

        }

    }



    /*
    |--------------------------------------------------------------------------
    | TAKE SNAPSHOT
    |--------------------------------------------------------------------------
    */

    window.takeEditSnapshot =
        async function () {

            /*
            | Final live validation
            */

            if (!currentFaceValid) {

                alert(
                    'Please position your face correctly before taking the photo.'
                );

                return;

            }


            if (
                !currentFaceDetection ||
                currentFaceDetection.length !== 1
            ) {

                alert(
                    'Exactly one face must be visible.'
                );

                return;

            }


            if (!canvas || !video || !fileInput) {

                console.error(
                    'Camera elements missing.'
                );

                return;

            }


            /*
            | Disable button while processing
            */

            setCaptureButton(false);


            updateCameraStatus(
                'Checking captured photo...',
                'waiting'
            );


            /*
            |--------------------------------------------------------------------------
            | Set canvas size
            |--------------------------------------------------------------------------
            */

            const width =
                video.videoWidth;

            const height =
                video.videoHeight;


            canvas.width =
                width;

            canvas.height =
                height;


            /*
            |--------------------------------------------------------------------------
            | Draw current camera frame
            |--------------------------------------------------------------------------
            */

            const context =
                canvas.getContext(
                    '2d',
                    {
                        willReadFrequently: true
                    }
                );


            context.drawImage(
                video,
                0,
                0,
                width,
                height
            );


            /*
            |--------------------------------------------------------------------------
            | FINAL FACE VALIDATION
            |--------------------------------------------------------------------------
            */

            const faceResult =
                await validateCapturedFace(
                    canvas
                );


            if (!faceResult.valid) {

                updateCameraStatus(
                    faceResult.message,
                    'error'
                );


                alert(
                    faceResult.message
                );


                /*
                | Continue detection
                */

                currentFaceValid = false;

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | IMAGE QUALITY VALIDATION
            |--------------------------------------------------------------------------
            */

            const qualityResult =
                validateImageQuality(
                    canvas
                );


            if (!qualityResult.valid) {

                updateCameraStatus(
                    qualityResult.message,
                    'error'
                );


                alert(
                    qualityResult.message
                );


                currentFaceValid = false;

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Convert canvas to JPEG
            |--------------------------------------------------------------------------
            */

            canvas.toBlob(
                function (blob) {

                    if (!blob) {

                        alert(
                            'Unable to create the photo. Please try again.'
                        );

                        currentFaceValid =
                            false;

                        return;

                    }


                    /*
                    | Generate unique filename
                    */

                    const filename =
                        'face_' +
                        Date.now() +
                        '.jpg';


                    /*
                    | Create real File object
                    */

                    const file =
                        new File(
                            [blob],
                            filename,
                            {
                                type: 'image/jpeg',
                                lastModified:
                                    Date.now()
                            }
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Put File into hidden file input
                    |--------------------------------------------------------------------------
                    */

                    try {

                        const dataTransfer =
                            new DataTransfer();


                        dataTransfer.items.add(
                            file
                        );


                        fileInput.files =
                            dataTransfer.files;


                    } catch (error) {

                        console.error(
                            'Unable to assign camera file:',
                            error
                        );


                        alert(
                            'Unable to prepare the photo for upload.'
                        );

                        currentFaceValid =
                            false;

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Preview
                    |--------------------------------------------------------------------------
                    */

                    if (preview) {

                        const objectUrl =
                            URL.createObjectURL(
                                file
                            );


                        preview.src =
                            objectUrl;


                        preview.classList.remove(
                            'hidden'
                        );


                        if (placeholder) {

                            placeholder.classList.add(
                                'hidden'
                            );

                        }

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Profile status
                    |--------------------------------------------------------------------------
                    */

                    const profileStatus =
                        document.getElementById(
                            'edit_face_status'
                        );


                    if (profileStatus) {

                        profileStatus.textContent =
                            'Face verified';

                        profileStatus.classList.remove(
                            'text-slate-400'
                        );

                        profileStatus.classList.add(
                            'text-green-600'
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Close camera
                    |--------------------------------------------------------------------------
                    */

                    closeEditCamera();


                },
                'image/jpeg',
                0.92
            );

        };



    /*
    |--------------------------------------------------------------------------
    | FINAL FACE VALIDATION
    |--------------------------------------------------------------------------
    */

    async function validateCapturedFace(
        capturedCanvas
    ) {

        try {

            /*
            | Detect faces in actual captured image
            */

            const detections =
                await faceapi.detectAllFaces(
                    capturedCanvas,
                    new faceapi.TinyFaceDetectorOptions({

                        inputSize: 416,

                        scoreThreshold: 0.75

                    })
                );


            /*
            | No face
            */

            if (
                detections.length === 0
            ) {

                return {

                    valid: false,

                    message:
                        'No face detected in the captured photo. Please retake it.'

                };

            }


            /*
            | Multiple faces
            */

            if (
                detections.length > 1
            ) {

                return {

                    valid: false,

                    message:
                        'Multiple faces detected. Only one person should be in the photo.'

                };

            }


            /*
            | Exactly one
            */

            const detection =
                detections[0];


            const box =
                detection.box;


            /*
            |--------------------------------------------------------------------------
            | Confidence
            |--------------------------------------------------------------------------
            */

            if (
                detection.score < 0.75
            ) {

                return {

                    valid: false,

                    message:
                        'The face is not clear enough. Please face the camera directly.'

                };

            }


            /*
            |--------------------------------------------------------------------------
            | Face size
            |--------------------------------------------------------------------------
            */

            const widthRatio =
                box.width /
                capturedCanvas.width;

            const heightRatio =
                box.height /
                capturedCanvas.height;


            if (
                widthRatio < 0.30 ||
                heightRatio < 0.30
            ) {

                return {

                    valid: false,

                    message:
                        'Your face is too small. Please move closer to the camera.'

                };

            }


            if (
                widthRatio > 0.80 ||
                heightRatio > 0.80
            ) {

                return {

                    valid: false,

                    message:
                        'Your face is too close. Please move slightly farther away.'

                };

            }


            /*
            |--------------------------------------------------------------------------
            | Face horizontal position
            |--------------------------------------------------------------------------
            */

            const faceCenterX =
                box.x +
                (box.width / 2);


            const canvasCenterX =
                capturedCanvas.width / 2;


            const horizontalDistance =
                Math.abs(
                    faceCenterX -
                    canvasCenterX
                );


            if (
                horizontalDistance >
                capturedCanvas.width * 0.20
            ) {

                return {

                    valid: false,

                    message:
                        'Please center your face in the photo.'

                };

            }


            /*
            |--------------------------------------------------------------------------
            | Face vertical position
            |--------------------------------------------------------------------------
            */

            const faceCenterY =
                box.y +
                (box.height / 2);


            const canvasCenterY =
                capturedCanvas.height / 2;


            const verticalDistance =
                Math.abs(
                    faceCenterY -
                    canvasCenterY
                );


            if (
                verticalDistance >
                capturedCanvas.height * 0.25
            ) {

                return {

                    valid: false,

                    message:
                        'Please position your face near the center of the photo.'

                };

            }


            /*
            |--------------------------------------------------------------------------
            | Everything passed
            |--------------------------------------------------------------------------
            */

            return {

                valid: true,

                detection: detection

            };


        } catch (error) {

            console.error(
                'Captured face validation error:',
                error
            );


            return {

                valid: false,

                message:
                    'Unable to verify the face. Please try again.'

            };

        }

    }



    /*
    |--------------------------------------------------------------------------
    | IMAGE QUALITY CHECK
    |--------------------------------------------------------------------------
    */

    function validateImageQuality(
        canvas
    ) {

        const context =
            canvas.getContext(
                '2d',
                {
                    willReadFrequently: true
                }
            );


        /*
        | Resize analysis area
        |
        | We don't need to analyze
        | thousands of pixels.
        */

        const analysisWidth =
            200;

        const analysisHeight =
            Math.round(
                canvas.height *
                (
                    analysisWidth /
                    canvas.width
                )
            );


        const tempCanvas =
            document.createElement(
                'canvas'
            );


        tempCanvas.width =
            analysisWidth;

        tempCanvas.height =
            analysisHeight;


        const tempContext =
            tempCanvas.getContext(
                '2d',
                {
                    willReadFrequently: true
                }
            );


        tempContext.drawImage(
            canvas,
            0,
            0,
            analysisWidth,
            analysisHeight
        );


        const imageData =
            tempContext.getImageData(
                0,
                0,
                analysisWidth,
                analysisHeight
            );


        const data =
            imageData.data;


        /*
        |--------------------------------------------------------------------------
        | Brightness
        |--------------------------------------------------------------------------
        */

        let brightnessTotal = 0;

        let pixelCount = 0;


        for (
            let i = 0;
            i < data.length;
            i += 4
        ) {

            const r =
                data[i];

            const g =
                data[i + 1];

            const b =
                data[i + 2];


            const brightness =
                (
                    0.299 * r
                ) +
                (
                    0.587 * g
                ) +
                (
                    0.114 * b
                );


            brightnessTotal +=
                brightness;

            pixelCount++;

        }


        const averageBrightness =
            brightnessTotal /
            pixelCount;


        /*
        | Too dark
        */

        if (
            averageBrightness < 45
        ) {

            return {

                valid: false,

                message:
                    'The photo is too dark. Please move to a brighter area.'

            };

        }


        /*
        | Too bright
        */

        if (
            averageBrightness > 235
        ) {

            return {

                valid: false,

                message:
                    'The photo is too bright. Please avoid strong light behind you.'

            };

        }


        /*
        |--------------------------------------------------------------------------
        | Sharpness / Blur check
        |--------------------------------------------------------------------------
        |
        | Laplacian variance.
        |
        | Higher value = sharper image.
        | Lower value = more blurry image.
        |
        */

        const gray = [];


        for (
            let i = 0;
            i < data.length;
            i += 4
        ) {

            const r =
                data[i];

            const g =
                data[i + 1];

            const b =
                data[i + 2];


            gray.push(
                (
                    0.299 * r
                ) +
                (
                    0.587 * g
                ) +
                (
                    0.114 * b
                )
            );

        }


        let laplacianValues = [];


        for (
            let y = 1;
            y < analysisHeight - 1;
            y++
        ) {

            for (
                let x = 1;
                x < analysisWidth - 1;
                x++
            ) {

                const index =
                    y *
                    analysisWidth +
                    x;


                const value =
                    (
                        gray[index - analysisWidth]
                    ) +
                    (
                        gray[index - 1]
                    ) +
                    (
                        gray[index + 1]
                    ) +
                    (
                        gray[index + analysisWidth]
                    ) -
                    (
                        4 *
                        gray[index]
                    );


                laplacianValues.push(
                    value
                );

            }

        }


        let mean =
            0;


        for (
            let i = 0;
            i < laplacianValues.length;
            i++
        ) {

            mean +=
                laplacianValues[i];

        }


        mean /=
            laplacianValues.length;


        let variance =
            0;


        for (
            let i = 0;
            i < laplacianValues.length;
            i++
        ) {

            variance +=
                Math.pow(
                    laplacianValues[i] -
                    mean,
                    2
                );

        }


        variance /=
            laplacianValues.length;


        console.log(
            'Image brightness:',
            averageBrightness
        );


        console.log(
            'Image sharpness:',
            variance
        );


        /*
        |--------------------------------------------------------------------------
        | Blur threshold
        |--------------------------------------------------------------------------
        */

        if (
            variance < 40
        ) {

            return {

                valid: false,

                message:
                    'The photo is too blurry. Please hold the camera steady and retake it.'

            };

        }


        /*
        |--------------------------------------------------------------------------
        | Everything passed
        |--------------------------------------------------------------------------
        */

        return {

            valid: true,

            brightness:
                averageBrightness,

            sharpness:
                variance

        };

    }



    /*
    |--------------------------------------------------------------------------
    | CLOSE CAMERA
    |--------------------------------------------------------------------------
    */

    window.closeEditCamera =
        function () {

            /*
            | Stop detection
            */

            stopFaceDetection();


            /*
            | Stop camera tracks
            */

            if (editCameraStream) {

                editCameraStream
                    .getTracks()
                    .forEach(
                        function (track) {

                            track.stop();

                        }
                    );

                editCameraStream =
                    null;

            }


            /*
            | Remove video source
            */

            if (video) {

                video.pause();

                video.srcObject =
                    null;

            }


            /*
            | Reset state
            */

            currentFaceDetection =
                [];

            currentFaceValid =
                false;


            /*
            | Reset interface
            */

            if (cameraModal) {

                cameraModal.classList.add(
                    'hidden'
                );

                cameraModal.classList.remove(
                    'flex'
                );

            }


            if (cameraSelection) {

                cameraSelection.classList.remove(
                    'hidden'
                );

                cameraSelection.classList.add(
                    'grid'
                );

            }


            if (cameraInterface) {

                cameraInterface.classList.add(
                    'hidden'
                );

                cameraInterface.classList.remove(
                    'flex'
                );

            }


            /*
            | Reset capture button
            */

            setCaptureButton(
                false
            );


            /*
            | Reset status
            */

            updateCameraStatus(
                'Detecting face...',
                'waiting'
            );

        };



    /*
    |--------------------------------------------------------------------------
    | CLEANUP WHEN PAGE IS LEFT
    |--------------------------------------------------------------------------
    */

    window.addEventListener(
        'beforeunload',
        function () {

            stopFaceDetection();


            if (editCameraStream) {

                editCameraStream
                    .getTracks()
                    .forEach(
                        function (track) {

                            track.stop();

                        }
                    );

            }

        }
    );

});

</script>
</body>
</html>