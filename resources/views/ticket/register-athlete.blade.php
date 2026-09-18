<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runner Registration - MYAN RUN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/icon/Myan Run icon.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .section-card { background: white; padding: 40px; border-radius: 40px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); border: 1px solid #f1f5f9; margin-bottom: 32px; }
        .input-field { width: 100%; padding: 16px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; outline: none; transition: all 0.3s; font-weight: 600; color: #334155; }
        .input-field:focus { border-color: #C3E92D; background-color: white; box-shadow: 0 0 0 4px rgba(195, 233, 45, 0.1); }
        .label-text { font-size: 10px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-left: 8px; margin-bottom: 8px; display: block; }
        
        .scan-circle { position: relative; width: 160px; height: 160px; border-radius: 50%; border: 4px dashed #cbd5e1; display: flex; flex-direction: column; align-items: center; justify-content: center; overflow: hidden; background: #f1f5f9; cursor: pointer; transition: all 0.3s; }
        .scan-circle:hover { border-color: #C3E92D; background: #eff6ff; }
        /* .scan-line { position: absolute; width: 100%; height: 2px; background: #C3E92D; top: 0; animation: scanning 3s linear infinite; opacity: 0.3; z-index: 25; }
        @keyframes scanning { 0% { top: 0%; } 100% { top: 100%; } }

        .face-guide {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            height: 80%;
            border: 2px solid rgba(195, 233, 45, 0.5);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            pointer-events: none;
            box-shadow: 0 0 0 1000px rgba(0,0,0,0.4);
        } */

        /* Visual Shake Animation */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20% { transform: translateX(-8px); }
    40% { transform: translateX(8px); }
    60% { transform: translateX(-8px); }
    80% { transform: translateX(8px); }
}
.animate-shake { animation: shake 0.4s ease-in-out; }
/* Progress Circle Container */
/* .progress-ring-container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 85%; /* Slightly larger than the face-guide */
    height: 95%;
    pointer-events: none;
    z-index: 30;
}

.progress-ring__circle {
    transition: stroke-dashoffset 0.35s;
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
    stroke-linecap: round;
} */
/* Dynamic Face Guide Colors */
.face-guide.border-red-500 { border-color: #ef4444 !important; box-shadow: 0 0 0 1000px rgba(239, 68, 68, 0.2); }
.face-guide.border-lime-500 { border-color: #C3E92D !important; box-shadow: 0 0 0 1000px rgba(195, 233, 45, 0.2); }
    </style>
<!-- <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script> -->
</head>
<body class="py-16 px-4 relative">
@if(session('success'))
<div id="successModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-[3rem] w-full max-w-sm p-10 shadow-2xl text-center transform transition-all animate-bounce-short">
        <div class="w-24 h-24 bg-[#C3E92D] rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-lime-200">
            <i class="fas fa-check text-4xl text-slate-900"></i>
        </div>
        <h2 class="text-2xl font-black text-slate-800 uppercase italic mb-2">Registration Sent!</h2>
        <p class="text-slate-500 text-sm font-semibold mb-8">Your athlete profile has been created successfully. See you at the starting line!</p>
        
        <button onclick="window.location.href='/'" class="w-full py-4 bg-slate-900 text-white font-black rounded-2xl uppercase tracking-widest text-xs hover:bg-slate-800 transition-all">
            Awesome!
        </button>
    </div>
</div>

<style>
    @keyframes bounce-short {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .animate-bounce-short { animation: bounce-short 0.5s ease-in-out; }
</style>
@endif
    <div id="uploadModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 shadow-2xl scale-95 transition-transform">
            <h3 id="modalTitle" class="text-center text-slate-800 font-black uppercase italic mb-6">Setup Face ID</h3>
            
            <div id="selectionButtons" class="grid grid-cols-2 gap-4">
                <button type="button" onclick="startCamera()" class="flex flex-col items-center gap-3 p-6 rounded-3xl border-2 border-slate-100 hover:border-[#C3E92D] hover:bg-lime-50 transition-all group">
                    <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:text-[#C3E92D] transition-all">
                        <i class="fas fa-camera text-2xl"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase text-slate-600">Camera</span>
                </button>
                <button type="button" onclick="triggerGallery()" class="flex flex-col items-center gap-3 p-6 rounded-3xl border-2 border-slate-100 hover:border-[#C3E92D] hover:bg-lime-50 transition-all group">
                    <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:text-[#C3E92D] transition-all">
                        <i class="fas fa-images text-2xl"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase text-slate-600">Gallery</span>
                </button>
            </div>
            <div id="cameraInterface" class="hidden flex flex-col items-center">
    <div id="videoContainer" class="relative w-full aspect-square rounded-3xl overflow-hidden bg-black border-4 border-slate-100 transition-all duration-300">
    <video id="videoFeed" autoplay playsinline class="w-full h-full object-cover"></video>
    
    <div class="face-guide"></div>

    <div class="progress-ring-container">
        <svg class="w-full h-full" viewBox="0 0 100 100">
            <ellipse cx="50" cy="50" rx="35" ry="40" 
                fill="transparent" 
                stroke="rgba(255,255,255,0.1)" 
                stroke-width="2" />
            <ellipse id="progressCircle" cx="50" cy="50" rx="35" ry="40" 
                fill="transparent" 
                stroke="#C3E92D" 
                stroke-width="2" 
                stroke-dasharray="240" 
                stroke-dashoffset="240" />
        </svg>
    </div>

    <div id="smartStatusBox" class="absolute bottom-4 left-4 right-4 bg-white/10 backdrop-blur-xl border border-white/20 p-4 rounded-2xl flex items-center gap-3 transition-all duration-500 translate-y-24 opacity-0 z-50">
        <div id="statusIcon" class="w-10 h-10 rounded-full flex items-center justify-center text-white shadow-lg shrink-0">
            <i class="fas fa-circle-notch animate-spin"></i>
        </div>
        <div class="flex flex-col">
            <span id="statusHeading" class="text-[9px] font-black text-white uppercase tracking-widest leading-none mb-1">Status</span>
            <span id="statusMessage" class="text-[11px] font-bold text-white/90 leading-tight">Waiting...</span>
        </div>
    </div>
</div>

    <button type="button" onclick="takeSnapshot()" class="mt-6 w-20 h-20 bg-[#C3E92D] rounded-full border-8 border-slate-50 flex items-center justify-center shadow-xl hover:scale-105 active:scale-95 transition-all">
        <i class="fas fa-camera text-2xl text-slate-900"></i>
    </button>
</div>

            <button type="button" onclick="closeUploadModal()" class="w-full mt-6 py-3 text-slate-400 font-bold uppercase text-[10px] tracking-widest hover:text-red-500 transition-colors">Cancel</button>
        </div>
    </div>
@if(session('success'))
    <div id="alert-box" class="mb-6 p-4 bg-lime-500/20 border border-lime-500 rounded-xl flex items-center gap-3">
        <span class="text-lime-500 text-xl">✅</span>
        <p class="text-lime-500 font-bold">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div id="alert-box" class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-xl flex items-center gap-3">
        <span class="text-red-500 text-xl">⚠️</span>
        <p class="text-red-500 font-bold">{{ session('error') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 p-4 bg-orange-500/20 border border-orange-500 rounded-xl">
        <ul class="list-disc list-inside text-orange-500 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="absolute top-6 left-6">
        <a href="/ticket?event={{ urlencode(session('event_name') ?? request('event')) }}" class="flex items-center gap-2 text-slate-500 hover:text-[#C3E92D] transition-colors font-semibold group">
            <div class="w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center group-hover:shadow-[#C3E92D]/20 transition-all border border-slate-100">
                <i class="fas fa-home"></i>
            </div>
            <span class="hidden md:block">Go Back</span>
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <div class="flex items-center justify-center gap-4 mb-2">
                <img src="{{ asset('images/logo/myarun(logo).jpg') }}" alt="Myan Run Logo" class="h-32 w-auto object-contain">
            </div>
            <p class="text-slate-400 font-bold mt-2 uppercase text-[10px] tracking-[0.4em]">Athlete Registration System</p>
        </div>

        <form action="{{ route('athlete.register.submit') }}" method="POST" enctype="multipart/form-data" id="mainForm">
            @csrf
            <input type="hidden" name="event_name" value="{{ session('event_name') ?? request('event') }}">
            <div class="mb-8 p-6 bg-slate-900 rounded-[32px] text-white flex items-center justify-between shadow-xl border-b-4 border-[#C3E92D]">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-[#C3E92D] rounded-2xl flex items-center justify-center text-slate-900 text-xl">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            {{ session('event_name') ?? request('event', 'Official Event') }}
                        </p>
                        <h4 class="text-xl font-black italic uppercase tracking-tighter text-[#C3E92D]">
                            {{ $category ?? 'No Category Selected' }}
                        </h4>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Registration Fee</p>
                    <p class="text-2xl font-black tracking-tighter">{{ session('ticket_price') ?? '0' }} <span class="text-sm opacity-50">MMK</span></p>
                </div>
            </div>
            <div class="section-card">
                <div class="flex items-center mb-10">
                    <span class="w-10 h-10 bg-[#C3E92D] text-slate-900 rounded-xl flex items-center justify-center font-black mr-4 shadow-lg shadow-lime-100">01</span>
                    <h3 class="text-xl font-black text-slate-800 uppercase italic">Required Information</h3>
                </div>

              <div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-center">
    <div class="md:col-span-4 flex flex-col items-center hidden">
        <label class="label-text">Face ID Setup</label>
        
        <input type="file" name="face_image" id="face_image" class="hidden" accept="image/*">
        @if($athlete && $athlete->face_image_path)
            <input type="hidden" name="existing_face" value="{{ $athlete->face_image_path }}">
        @endif

        <div class="scan-circle group" onclick="openUploadModal()">
            <div class="scan-line" id="scannerLine" style="{{ $athlete && $athlete->face_image_path ? 'display: none;' : '' }}"></div>
            
            <img id="facePreview" 
                 class="{{ $athlete && $athlete->face_image_path ? '' : 'hidden' }} w-full h-full object-cover absolute inset-0 z-10" 
                 src="{{ $athlete && $athlete->face_image_path ? asset('storage/' . $athlete->face_image_path) : '' }}">
            
            <div id="placeholderUI" class="flex flex-col items-center justify-center z-20 {{ $athlete && $athlete->face_image_path ? 'opacity-0' : '' }}">
                <i class="fas fa-face-viewfinder text-4xl text-slate-300 group-hover:text-[#C3E92D] transition-colors"></i>
                <span class="text-[9px] font-black text-slate-400 mt-2 uppercase">Tap to Setup</span>
            </div>
        </div>

        <p id="uploadStatus" class="text-[9px] {{ $athlete && $athlete->face_image_path ? 'text-lime-500' : 'text-slate-400' }} mt-4 text-center font-bold uppercase tracking-tighter">
            {{ $athlete && $athlete->face_image_path ? 'CURRENT FACE ID LOADED ✅' : 'Required for AI Photo Matching' }}
        </p>
    </div>

    <div class="md:col-span-12 space-y-6">
        <div class="grid grid-cols-2 gap-4">
            <label class="flex items-center justify-center p-4 border-2 border-slate-100 rounded-2xl transition-all opacity-80 {{ $type == 'national' ? 'border-[#C3E92D] bg-lime-50' : 'bg-slate-50 cursor-not-allowed' }}">
                <input type="radio" name="nat_type_display" value="national" class="mr-2 pointer-events-none" 
                    {{ $type == 'national' ? 'checked' : '' }} disabled>
                <span class="text-xs font-black uppercase {{ $type == 'national' ? 'text-slate-900' : 'text-slate-400' }}">National</span>
            </label>

            <label class="flex items-center justify-center p-4 border-2 border-slate-100 rounded-2xl transition-all opacity-80 {{ $type == 'foreigner' ? 'border-[#C3E92D] bg-lime-50' : 'bg-slate-50 cursor-not-allowed' }}">
                <input type="radio" name="nat_type_display" value="foreigner" class="mr-2 pointer-events-none" 
                    {{ $type == 'foreigner' ? 'checked' : '' }} disabled>
                <span class="text-xs font-black uppercase {{ $type == 'foreigner' ? 'text-slate-900' : 'text-slate-400' }}">Foreigner</span>
            </label>
        </div>

        <input type="hidden" name="nat_type" value="{{ $type ?? request('type') ?? request('nat_type') ?? 'national' }}">
        
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="label-text">First Name</label>
                    <input type="text" name="first_name" 
                        value="{{ old('first_name', auth()->user()->first_name ?? '') }}"
                        placeholder="First Name" required readonly
                        class="input-field">
                </div>
                <div>
                    <label class="label-text">Middle Name (Optional)</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', auth()->user()->middle_name ?? '') }}" placeholder="Middle Name"
                        oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');" readonly
                        class="input-field">
                </div>
                <div>
                    <label class="label-text">Last Name</label>
                    <input type="text" name="last_name" 
                        value="{{ old('last_name', auth()->user()->last_name ?? '') }}" 
                        placeholder="Last Name" required readonly
                        class="input-field">
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <label class="label-text" id="id_label">Identity Information</label>
            @if($type == 'national')
            <div id="nrc_container" class="grid grid-cols-1 md:grid-cols-4 gap-2">
                <select name="nrc_state" id="nrc_state" class="input-field !py-3 !text-sm" required>
                    <option value="">State</option>
                    <option value="1">၁/</option> <option value="2">၂/</option>
                    <option value="3">၃/</option> <option value="4">၄/</option>
                    <option value="5">၅/</option> <option value="6">၆/</option>
                    <option value="7">၇/</option> <option value="8">၈/</option>
                    <option value="9">၉/</option> <option value="10">၁၀/</option>
                    <option value="11">၁၁/</option> <option value="12">၁၂/</option>
                    <option value="13">၁၃/</option> <option value="14">၁၄/</option>
                </select>

                <select name="nrc_district" id="nrc_district" class="input-field !py-3 !text-sm" required>
                    <option value="">District</option>
                </select>

                <select name="nrc_naing" class="input-field !py-3 !text-sm" required>
                    <option value="နိုင်">နိုင်</option>
                    <option value="ဧည့်">ဧည့်</option>
                    <option value="စ">စ</option>
                    <option value="ပြု">ပြု</option>
                    <option value="သ">သ</option>
                    <option value="သီ">သီ</option>
                </select>

                <input name="nrc_number" 
                    type="text" 
                    inputmode="numeric" 
                    placeholder="123456" 
                    maxlength="6"
                    pattern="\d{6}"
                    value="{{ old('nrc_number', $nrcParts['number'] ?? '') }}"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)" 
                    required
                    class="input-field !py-3 !text-sm">
            </div>
            @else
            <div id="passport_container" class="">
                <input type="text" 
                    id="passport_input" 
                    name="passport_id" 
                    placeholder="Passport Number" 
                    maxlength="15"
                    value=""
                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase()"
                    class="input-field">
            </div>
            @endif
        </div>
    </div>
</div>

            <div class="section-card">
                <div class="flex items-center mb-8">
                    <span class="w-10 h-10 bg-[#C3E92D] text-slate-900 rounded-xl flex items-center justify-center font-black mr-4 shadow-lg shadow-lime-100">02</span>
                    <h3 class="text-xl font-black text-slate-800 uppercase italic">Personal Information</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label-text">Father Name</label>
                        <input type="text" placeholder="Optional" name="father_name" value="{{ old('father_name', $athlete->father_name ?? '') }}" 
                               oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');" class="input-field">
                    </div>
                    <div>
                        <label class="label-text">Date of Birth</label>
                        <input type="text" 
                            name="dob" 
                            id="dob" 
                            value="{{ old('dob', $athlete && $athlete->dob ? \Carbon\Carbon::parse($athlete->dob)->format('d/m/Y') : '') }}" 
                            placeholder="DD/MM/YYYY" 
                            required 
                            maxlength="10"
                            oninput="formatDate(this)"
                            class="input-field">
                    </div>
                    <div>
                    <label class="label-text">Nationality</label>
                    
                    @php
                        $countries = [
                            "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", 
                            "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", 
                            "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", 
                            "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", 
                            "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", 
                            "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", 
                            "Congo", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", 
                            "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", 
                            "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Fiji", 
                            "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", 
                            "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", 
                            "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", 
                            "Italy", "Ivory Coast", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", 
                            "Kiribati", "Korea, North", "Korea, South", "Kuwait", "Kyrgyzstan", "Laos", 
                            "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", 
                            "Luxembourg", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", 
                            "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", 
                            "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", 
                            "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", 
                            "Nigeria", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", 
                            "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", 
                            "Russian Federation", "Rwanda", "St Kitts & Nevis", "St Lucia", "Saint Vincent & the Grenadines", 
                            "Samoa", "San Marino", "Sao Tome & Principe", "Saudi Arabia", "Senegal", "Serbia", 
                            "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", 
                            "Somalia", "South Africa", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", 
                            "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", 
                            "Thailand", "Togo", "Tonga", "Trinidad & Tobago", "Tunisia", "Turkey", "Turkmenistan", 
                            "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", 
                            "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", 
                            "Vietnam", "Yemen", "Zambia", "Zimbabwe"
                        ];
                        
                        $isNational = (old('nat_type', $athlete->nat_type ?? $type) == 'national');
                        $currentNationality = old('nationality', $athlete->nationality ?? ($isNational ? 'Myanmar' : ''));
                    @endphp

                    @if($type == 'national')
                        <div class="relative">
                            <input type="text" value="Myanmar" class="input-field bg-slate-100 cursor-not-allowed opacity-75" readonly>
                            <input type="hidden" name="nationality" value="Myanmar">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2">
                                <i class="fas fa-lock text-slate-400 text-[10px]"></i>
                            </div>
                        </div>
                    @else
                        <select name="nationality" id="nationality_select" class="input-field w-full" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country }}" {{ $currentNationality == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
                    <div>
                        <label class="label-text">Gender</label>
                        <select name="gender" id="gender_select" class="input-field" onchange="updateBib()">
                            <option value="male" {{ old('gender', $athlete->gender ?? 'male') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $athlete->gender ?? 'male') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    @if($isNational)
                    <div>
                        <label class="label-text font-semibold">Division</label>
                        <select name="state" class="input-field w-full mt-1">
                            <option value="">Select Division</option>

                            <option value="kachin" {{ (old('state', $athlete->state ?? '') == 'kachin') ? 'selected' : '' }}>Kachin State</option>
                            <option value="kayah" {{ (old('state', $athlete->state ?? '') == 'kayah') ? 'selected' : '' }}>Kayah State</option>
                            <option value="kayin" {{ (old('state', $athlete->state ?? '') == 'kayin') ? 'selected' : '' }}>Kayin State (Karen)</option>
                            <option value="chin" {{ (old('state', $athlete->state ?? '') == 'chin') ? 'selected' : '' }}>Chin State</option>
                            <option value="mon" {{ (old('state', $athlete->state ?? '') == 'mon') ? 'selected' : '' }}>Mon State</option>
                            <option value="rakhine" {{ (old('state', $athlete->state ?? '') == 'rakhine') ? 'selected' : '' }}>Rakhine State</option>
                            <option value="shan" {{ (old('state', $athlete->state ?? '') == 'shan') ? 'selected' : '' }}>Shan State</option>

                            <option value="sagaing" {{ (old('state', $athlete->state ?? '') == 'sagaing') ? 'selected' : '' }}>Sagaing Region</option>
                            <option value="tanintharyi" {{ (old('state', $athlete->state ?? '') == 'tanintharyi') ? 'selected' : '' }}>Tanintharyi Region</option>
                            <option value="bago" {{ (old('state', $athlete->state ?? '') == 'bago') ? 'selected' : '' }}>Bago Region</option>
                            <option value="magway" {{ (old('state', $athlete->state ?? '') == 'magway') ? 'selected' : '' }}>Magway Region</option>
                            <option value="mandalay" {{ (old('state', $athlete->state ?? '') == 'mandalay') ? 'selected' : '' }}>Mandalay Region</option>
                            <option value="yangon" {{ (old('state', $athlete->state ?? '') == 'yangon') ? 'selected' : '' }}>Yangon Region</option>
                            <option value="ayeyarwady" {{ (old('state', $athlete->state ?? '') == 'ayeyarwady') ? 'selected' : '' }}>Ayeyarwady Region</option>

                            <option value="naypyidaw" {{ (old('state', $athlete->state ?? '') == 'naypyidaw') ? 'selected' : '' }}>Naypyidaw</option>
                        </select>
                    </div>
                    @endif
                    <div class="md:col-span-2">
                        <label class="label-text">Full Address</label>
                        <textarea name="address" rows="3" class="input-field resize-none" placeholder="Residential Address">{{ old('address', $athlete->address ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="section-card">
                <div class="flex items-center mb-8">
                    <span class="w-10 h-10 bg-[#C3E92D] text-slate-900 rounded-xl flex items-center justify-center font-black mr-4 shadow-lg shadow-lime-100">03</span>
                    <h3 class="text-xl font-black text-slate-800 uppercase italic">Contact Information</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label-text">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required 
                               oninput="this.value = this.value.replace(/[^a-zA-Z0-9@._-]/g, '');" class="input-field" readonly>
                    </div>
                    <div>
                        <label class="label-text">Viber</label>
                        <input type="text" placeholder="09 Eng-Num Only" name="viber" value="{{ old('viber', $athlete->viber ?? '') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="input-field" minlength="9" 
                        maxlength="11">
                    </div>
                    <div>
                        <label class="label-text">Mobile Number</label>
                        <input type="tel" name="phone_1" value="{{ old('phone', auth()->user()->phone) }}" required 
                               oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="input-field" readonly>
                    </div>
                    <div>
                        <label class="label-text">Emergency Contact Number</label>
                        <input type="tel" placeholder="09 Eng-Num Only" name="contact" value="{{ old('contact', $athlete->contact ?? '') }}" minlength="9" 
                        maxlength="11"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="input-field">
                    </div>
                </div>
            </div>

            <div class="section-card">
                <div class="flex items-center mb-8">
                    <span class="w-10 h-10 bg-[#C3E92D] text-slate-900 rounded-xl flex items-center justify-center font-black mr-4 shadow-lg shadow-lime-100">04</span>
                    <h3 class="text-xl font-black text-slate-800 uppercase italic">Participant's info</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label-text">BIB Name</label>
                        <input type="text" placeholder="Max 10 letter Only" name="bib_name" value="{{ old('bib_name', auth()->user()->bib_name) }}" required 
                               oninput="this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '');" class="input-field" maxlength="10">
                    </div>
                    <!-- <div>
                        <label class="label-text">BIB Number</label>
                        <input type="text" name="bib_number" id="bib_input" value="{{ $bibNumber }}" class="input-field" readonly>
                    </div> -->
                    <input type="hidden" name="bib_number" id="bib_input" value="{{ $bibNumber }}">
                    <div>
                        <label class="label-text">T Shirt Size</label>
                        <select name="t_shirt_size" class="input-field">
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
                    <div>
                        <label class="label-text">Experience</label>
                        <select name="exp" class="input-field">
                            <option value="Never">Never</option>
                            <option value="25KM">Within 25KM</option>
                            <option value="25KM-50KM">Within 25KM and 50KM</option>
                            <option value="50KM+">50KM+</option>
                        </select>
                    </div>
                    <div>
                        <label class="label-text">Blood Type</label>
                        <select name="blood_type" class="input-field">
                            <option value="O">O</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="Unknown">Unknown</option>
                        </select>
                    </div>
                    <input type="hidden" name="nat_type" value="{{ $natType ?? request('type') }}">
                   @php
    // Get $type passed from your controller/request (e.g. 'national' vs 'foreigner')
    $currentType = $type ?? request('type') ?? request('nat_type') ?? 'national';
    
    // Check if the user is a foreigner
    $isForeigner = (strtolower($currentType) === 'foreigner');
@endphp

<!-- Hidden input to maintain the selection for form submission -->
<input type="hidden" name="type" value="{{ $currentType }}">

@if($isForeigner)
    <!-- 2. Passport Copy Image -->
    <div class="mb-4">
        <label class="label-text mb-2 block">Passport Copy (Image) <span class="text-red-500">*</span></label>
        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:border-[#C3E92D] hover:bg-lime-50/50 transition-all">
            <div id="passport_label" class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-xs text-slate-500 font-medium"><span class="font-bold text-slate-700">Click to upload</span> Passport Copy</p>
                <p class="text-[10px] text-slate-400 mt-1">PNG, JPG or WEBP</p>
            </div>
            <input type="file" 
            name="passport_image" 
            id="passport_image" 
            accept="image/png, image/jpeg, image/webp" 
            required 
            onchange="validateImageResolution(this, 1920, 1080, 'passport_label')" 
            class="hidden" />
        </label>
    </div>
    
    <!-- 3. Visa Document Image -->
    <div class="mb-4">
        <label class="label-text mb-2 block">Visa Document (Image) <span class="text-red-500">*</span></label>
        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:border-[#C3E92D] hover:bg-lime-50/50 transition-all">
            <div id="visa_label" class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <p class="text-xs text-slate-500 font-medium"><span class="font-bold text-slate-700">Click to upload</span> Visa Photo</p>
                <p class="text-[10px] text-slate-400 mt-1">PNG, JPG or WEBP</p>
            </div>
            <input type="file" 
            name="visa_image" 
            id="visa_image" 
            accept="image/png, image/jpeg, image/webp" 
            required 
            onchange="validateImageResolution(this, 1920, 1080, 'visa_label')" 
            class="hidden" />
        </label>
    </div>
       <!-- 1. Job / Work Title (Text Input) -->
    <div class="mb-4">
        <label for="job_work" class="label-text mb-2 block">Job / Occupation <span class="text-red-500">*</span></label>
        <input type="text" 
               name="job_work" 
               id="job_work" 
               value="{{ old('job_work', $athlete->job_work ?? '') }}"
               placeholder="e.g. Software Engineer, Teacher, Manager" 
               required 
               class="w-full rounded-2xl border-2 border-slate-200 p-3 text-sm focus:border-[#C3E92D] focus:outline-none transition-all" />
    </div>
    @endif
    <div class="md:col-span-2">
                        <label class="label-text mb-3 block">Do you have any existing medical conditions?</label>
                        <div class="flex gap-4 mb-4">
                            <label class="flex-1 flex items-center justify-center p-3 border-2 border-slate-100 rounded-2xl cursor-pointer transition-all has-[:checked]:border-[#C3E92D] has-[:checked]:bg-lime-50">
                                <input type="radio" name="has_condition" value="no" class="hidden" checked onchange="toggleCondition(false)">
                                <span class="text-xs font-black uppercase text-slate-600">No, I'm Healthy</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center p-3 border-2 border-slate-100 rounded-2xl cursor-pointer transition-all has-[:checked]:border-[#C3E92D] has-[:checked]:bg-lime-50">
                                <input type="radio" name="has_condition" value="yes" class="hidden" onchange="toggleCondition(true)">
                                <span class="text-xs font-black uppercase text-slate-600">Yes, I Have</span>
                            </label>
                        </div>
                        
                        <div id="condition_details_container" class="hidden">
                            <label class="label-text text-[#C3E92D] animate-pulse">Please specify your condition</label>
                            <textarea name="medical_conditions" id="medical_conditions" rows="3" 
                                    class="input-field resize-none border-red-100 focus:border-red-400" 
                                    placeholder="e.g. Asthma, Heart Disease, Recent Surgery, etc.">{{ old('medical_conditions', $athlete->medical_conditions ?? '') }}</textarea>
                        </div>
                    </div>
                    <!-- <div class="md:col-span-2">
                        <label class="label-text mb-3 block">Do you have an ITRA Profile?</label>
                        <div class="flex gap-4 mb-4">
                            {{-- No Option --}}
                            <label class="flex-1 flex items-center justify-center p-3 border-2 border-slate-100 rounded-2xl cursor-pointer transition-all has-[:checked]:border-[#C3E92D] has-[:checked]:bg-lime-50">
                                <input type="radio" name="has_itra" value="no" class="hidden" 
                                    {{ !old('itra_details', $athlete->itra_details ?? '') ? 'checked' : '' }} 
                                    onchange="toggleITRA(false)">
                                <span class="text-xs font-black uppercase text-slate-600">No, I don't</span>
                            </label>
                            {{-- Yes Option --}}
                            <label class="flex-1 flex items-center justify-center p-3 border-2 border-slate-100 rounded-2xl cursor-pointer transition-all has-[:checked]:border-[#C3E92D] has-[:checked]:bg-lime-50">
                                <input type="radio" name="has_itra" value="yes" class="hidden" 
                                    onchange="toggleITRA(true)">
                                <span class="text-xs font-black uppercase text-slate-600">Yes, I have</span>
                            </label>
                        </div>
                        
                        {{-- ITRA Details Input --}}
                        <div id="itra_details_container" class="{{ old('itra_details', $athlete->itra_details ?? '') ? '' : 'hidden' }}">
                            <label class="label-text text-[#C3E92D]">ITRA ID</label>
                            <input type="text" name="itra_details" id="itra_details" 
                                value=""
                                class="input-field border-lime-100 focus:border-[#C3E92D]" 
                                placeholder="Enter Here...">
                        </div>
                    </div> -->
                </div>
            </div>
           @if(session('ticket_type') === 'relay')
    <div class="section-card border-2 border-[#C3E92D]/30">
        <div class="flex items-center mb-8">
            <span class="w-10 h-10 bg-[#C3E92D] text-slate-900 rounded-xl flex items-center justify-center font-black mr-4 shadow-lg shadow-lime-100">05</span>
            <h3 class="text-xl font-black text-slate-800 uppercase italic">Relay Partner</h3>
        </div>

        <div class="mb-8">
            <label class="label-text mb-3 block text-center">Does your partner already have a MyanRun account?</label>
            <div class="flex gap-4">
                <label class="flex-1 flex items-center justify-center p-4 border-2 border-slate-100 rounded-2xl cursor-pointer transition-all has-[:checked]:border-[#C3E92D] has-[:checked]:bg-lime-50">
                    <input type="radio" name="partner_has_account" value="yes" class="hidden" checked onchange="togglePartnerMode('login')">
                    <span class="text-xs font-black uppercase text-slate-600">Yes, Link Account</span>
                </label>
                <label class="flex-1 flex items-center justify-center p-4 border-2 border-slate-100 rounded-2xl cursor-pointer transition-all has-[:checked]:border-[#C3E92D] has-[:checked]:bg-lime-50">
                    <input type="radio" name="partner_has_account" value="no" class="hidden" onchange="togglePartnerMode('register')">
                    <span class="text-xs font-black uppercase text-slate-600">No, Create Account</span>
                </label>
            </div>
        </div>

        {{-- MODE A: Partner Login (Existing User) --}}
        <div id="partner_login_fields" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <label class="label-text">Partner's Runner ID</label>
                <input type="text" name="friend_runner_id" placeholder="e.g. RUN-12345" class="input-field uppercase font-bold border-[#C3E92D]">
            </div>
            <div class="space-y-4">
                <label class="label-text">Partner's Password</label>
                <input type="password" name="friend_password" placeholder="••••••••" class="input-field">
            </div>
        </div>

        {{-- MODE B: Partner Registration (New User) --}}
        <div id="partner_register_fields" class="hidden space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label-text">Partner's First Name</label>
                    <input type="text" name="friend_first_name" class="input-field" placeholder="Enter First Name">
                </div>
                <div>
                    <label class="label-text">Partner's Last Name</label>
                    <input type="text" name="friend_last_name" class="input-field" placeholder="Enter Last Name">
                </div>
                <div>
                    <label class="label-text">Partner's Email</label>
                    <input type="email" name="friend_email" class="input-field" placeholder="email@example.com">
                </div>
                <div>
                    <label class="label-text">Partner's Phone (Mobile)</label>
                    <input type="tel" name="friend_phone" class="input-field" placeholder="09xxxxxxxxx">
                </div>
            </div>
            <p class="text-[10px] text-slate-400 font-bold uppercase text-center bg-slate-50 p-3 rounded-xl">
                Note: A new account will be created for your partner using their phone number as the initial password.
            </p>
        </div>
    </div>
@endif
            
            <div class="flex flex-col md:flex-row gap-4 pt-6">
                <button type="submit" class="flex-1 bg-[#C3E92D] hover:bg-slate-800 text-slate-900 hover:text-white font-black py-5 rounded-[2rem] shadow-xl shadow-lime-100 transition-all uppercase tracking-widest">
                    Submit Registration
                </button>
            </div>
        </form>
    </div>
<script>
    function validateImageResolution(input, maxWidth, maxHeight, labelId) {
    const file = input.files[0];
    if (!file) return;

    const img = new Image();
    const objectUrl = URL.createObjectURL(file);

    img.onload = function() {
        // Check dimension resolution
        if (this.width > maxWidth || this.height > maxHeight) {
            alert(`Selected image resolution (${this.width}x${this.height}) exceeds the maximum allowed limit of ${maxWidth}x${maxHeight}. Please choose a smaller image.`);
            input.value = ''; // Reset file input
            document.getElementById(labelId).innerHTML = `<span class="font-bold text-red-500">Exceeds ${maxWidth}x${maxHeight}!</span>`;
        } else {
            // Display filename upon valid selection
            document.getElementById(labelId).innerHTML = `<span class="font-bold text-lime-600">Selected:</span> ${file.name.substring(0, 18)}...`;
        }
        URL.revokeObjectURL(objectUrl);
    };

    img.onerror = function() {
        alert("Invalid image file format.");
        input.value = '';
        URL.revokeObjectURL(objectUrl);
    };

    img.src = objectUrl;
}
function togglePartnerPassword() {
    const passwordInput = document.getElementById('partner_password');
    const icon = document.getElementById('passwordIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
<script>
  const modal = document.getElementById('uploadModal');
const faceInput = document.getElementById('face_image');
const facePreview = document.getElementById('facePreview');
const placeholder = document.getElementById('placeholderUI');
const scannerLine = document.getElementById('scannerLine');
const statusText = document.getElementById('uploadStatus');
const videoFeed = document.getElementById('videoFeed');
// Define the submit button (ensure your HTML button has this ID or change this)
const submitBtn = document.querySelector('button[type="submit"]'); 

let stream = null;
let modelAI = null;

// UI Helper: Updates the glassmorphism box and the face guide oval
// function updateSmartStatus(type, message) {
//     const box = document.getElementById('smartStatusBox');
//     const icon = document.getElementById('statusIcon');
//     const heading = document.getElementById('statusHeading');
//     const msg = document.getElementById('statusMessage');
//     const container = document.getElementById('videoContainer');
//     const guide = document.querySelector('.face-guide');

//     box.classList.remove('translate-y-24', 'opacity-0');
//     box.classList.add('translate-y-0', 'opacity-100');

//     // Reset Guide Colors
//     guide.classList.remove('border-red-500', 'border-lime-500');

//     if (type === 'error') {
//         box.style.backgroundColor = 'rgba(239, 68, 68, 0.9)'; 
//         icon.innerHTML = '<i class="fas fa-triangle-exclamation"></i>';
//         heading.innerText = "Verification Failed";
//         msg.innerText = message;
//         guide.classList.add('border-red-500');
        
//         container.classList.add('animate-shake');
//         setTimeout(() => container.classList.remove('animate-shake'), 500);
//     } 
//     else if (type === 'processing') {
//         box.style.backgroundColor = 'rgba(30, 41, 59, 0.8)';
//         icon.innerHTML = '<i class="fas fa-circle-notch animate-spin"></i>';
//         heading.innerText = "Analyzing...";
//         msg.innerText = "Stay still for a second";
//     }
//     else if (type === 'success') {
//         box.style.backgroundColor = 'rgba(195, 233, 45, 0.95)';
//         icon.innerHTML = '<i class="fas fa-check text-slate-900"></i>';
//         heading.innerText = "Identity Verified";
//         heading.style.color = "#0f172a";
//         msg.innerText = "Profile photo updated.";
//         msg.style.color = "#0f172a";
//         guide.classList.add('border-lime-500');
//     }
// }

// async function loadAI() {
//     try {
//         // Using a more reliable CDN for weights
//         const MODEL_URL = 'https://raw.githubusercontent.com/justadudewhohacks/face-api.js/master/weights/';
        
//         // Load the 3 models required for "Strict" mode
//         await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL); 
//         await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
//         await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
        
//         modelAI = true; 
//         statusText.innerText = "Strict AI System Online";
//         statusText.classList.replace('text-slate-400', 'text-lime-500');
//     } catch (err) {
//         console.error("AI Loading Error:", err);
//         statusText.innerText = "AI System Offline (Check Connection)";
//         statusText.classList.replace('text-slate-400', 'text-red-500');
//     }
// }
// loadAI();
function toggleFriend(hasAccount) {
    const loginDiv = document.getElementById('friend_login');
    const registerDiv = document.getElementById('friend_register');
    const idInput = document.querySelector('input[name="friend_runner_id"]');
    const passInput = document.querySelector('input[name="friend_password"]');

    if (hasAccount) {
        loginDiv.classList.remove('hidden');
        registerDiv.classList.add('hidden');
        idInput.setAttribute('required', 'required');
        passInput.setAttribute('required', 'required');
    } else {
        loginDiv.classList.add('hidden');
        registerDiv.classList.remove('hidden');
        idInput.removeAttribute('required');
        passInput.removeAttribute('required');
    }
}
function toggleITRA(show) {
    const container = document.getElementById('itra_details_container');
    const input = document.getElementById('itra_details');
    
    if (show) {
        container.classList.remove('hidden');
        input.setAttribute('required', 'required'); // Optional: make it required if they say 'Yes'
    } else {
        container.classList.add('hidden');
        input.removeAttribute('required');
        input.value = ''; // Clear it if they change their mind
    }
}

function updateBib() {
    const genderSelect = document.getElementById('gender_select');
    const bibInput = document.getElementById('bib_input');
    let currentBib = bibInput.value;

    // Only update if it's not a 'PENDING' status
    if (currentBib !== 'PENDING') {
        const prefix = (genderSelect.value === 'female') ? 'F' : 'M';
        
        // Replace the first character (M/F) with the new prefix
        // This keeps the distance (36) and the counter (0001) the same
        bibInput.value = prefix + currentBib.substring(1);
    }
}

// Medical toggle helper (since I noticed the 'hidden' logic in your code)
function toggleCondition(show) {
    const container = document.getElementById('condition_details_container');
    if (show) {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
    }
}
function openUploadModal() { 
    // if(!modelAI) return alert("AI is still warming up...");
    modal.classList.replace('hidden', 'flex'); 
}

function closeUploadModal() {
    stopCamera();
    modal.classList.replace('flex', 'hidden');
}

function stopCamera() {
    if(stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    videoFeed.srcObject = null;
    document.getElementById('selectionButtons').classList.remove('hidden');
    document.getElementById('cameraInterface').classList.add('hidden');
}

async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: "user", width: 640, height: 480 } 
        });
        videoFeed.srcObject = stream;
        document.getElementById('selectionButtons').classList.add('hidden');
        document.getElementById('cameraInterface').classList.remove('hidden');
    } catch (err) { 
        alert("Camera access denied."); 
    }
}

function triggerGallery() { faceInput.click(); }
faceInput.onchange = e => { 
    if(e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = (ev) => {
            facePreview.src = ev.target.result;
            facePreview.classList.remove('hidden');
            placeholder.classList.add('opacity-0');
            closeUploadModal();
        };
        reader.readAsDataURL(e.target.files[0]);
    }
};

async function takeSnapshot() {
    const canvas = document.createElement('canvas');
    canvas.width = videoFeed.videoWidth;
    canvas.height = videoFeed.videoHeight;
    canvas.getContext('2d').drawImage(videoFeed, 0, 0);
    
    canvas.toBlob(blob => {
        const file = new File([blob], "capture.jpg", {type:"image/jpeg"});
        
        // 1. Manually trigger the preview instead of AI
        const reader = new FileReader();
        reader.onload = (e) => {
            facePreview.src = e.target.result;
            facePreview.classList.remove('hidden');
            placeholder.classList.add('opacity-0');
            if(scannerLine) scannerLine.style.display = 'none';
            statusText.innerText = "PHOTO CAPTURED ✅";
            statusText.classList.replace('text-slate-400', 'text-lime-500');
        };
        reader.readAsDataURL(file);

        // 2. Put the file into the hidden input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        faceInput.files = dataTransfer.files;

        // 3. Close modal immediately
        closeUploadModal();
    }, 'image/jpeg');
}


function setProgress(percent) {
    const circle = document.getElementById('progressCircle');
    const circumference = 240; // Approx circumference of our ellipse
    const offset = circumference - (percent / 100 * circumference);
    circle.style.strokeDashoffset = offset;
}
function toggleForeignerFields(nationality) {
    const foreignerContainer = document.getElementById('foreigner-fields');
    const jobInput = document.getElementById('job_image');
    const visaInput = document.getElementById('visa_image');

    if (nationality === 'foreigner') {
        foreignerContainer.classList.remove('hidden');
        jobInput.required = true;
        visaInput.required = true;
    } else {
        foreignerContainer.classList.add('hidden');
        jobInput.required = false;
        visaInput.required = false;
        
        // Reset inputs if user selects local after picking files
        jobInput.value = '';
        visaInput.value = '';
    }
}

// Ensure state is correctly set on page initial load (e.g. after validation redirects)
document.addEventListener('DOMContentLoaded', function () {
    const natSelect = document.getElementById('nat_type');
    if (natSelect) {
        toggleForeignerFields(natSelect.value);
    }
});

// async function processAI(file) {
//     updateSmartStatus('processing');
//     setProgress(20);
    
//     const img = await faceapi.bufferToImage(file);
    
//     // CHANGED: Using SsdMobilenetv1Options for the "Strict" model
//     // minConfidence: 0.8 ensures it only accepts very clear faces
//     const options = new faceapi.SsdMobilenetv1Options({ minConfidence: 0.8 });
    
//     const result = await faceapi.detectSingleFace(img, options)
//                                 .withFaceLandmarks()
//                                 .withFaceDescriptor(); // Added for biometric consistency
    
//     setProgress(60);

//     if (!result) {
//         updateSmartStatus('error', "Strict Check Failed: Face not clear or too dark.");
//         setProgress(0);
//         return;
//     }

//     const { detection, landmarks } = result;
//     const score = detection.score; 
//     const box = detection.box; 

//     // --- STRICT VALIDATION LOGIC ---
//     let errorMsg = "";
    
//     // 1. Check Alignment (Is the person looking straight?)
//     const nose = landmarks.getNose()[0];
//     const leftEye = landmarks.getLeftEye()[0];
//     const rightEye = landmarks.getRightEye()[3];
//     const eyeCenter = (leftEye.x + rightEye.x) / 2;
//     const noseOffset = Math.abs(nose.x - eyeCenter);
    
//     // If nose is too far from center of eyes, they are looking sideways
//     if (noseOffset > (rightEye.x - leftEye.x) * 0.2) {
//         errorMsg = "Please look directly at the camera.";
//     }
//     // 2. Check Size (Is the face large enough in the frame?)
//     else if (box.width < img.width * 0.4) {
//         errorMsg = "Move closer to the camera.";
//     }
//     // 3. Check Head Tilt
//     else if (Math.abs(leftEye.y - rightEye.y) > 15) {
//         errorMsg = "Keep your head level.";
//     }

//     if (errorMsg === "") {
//         setProgress(100);
//         updateSmartStatus('success');
        
//         // Update hidden input and preview
//         const dataTransfer = new DataTransfer();
//         dataTransfer.items.add(file);
//         faceInput.files = dataTransfer.files;
//         facePreview.src = img.src;
//         facePreview.classList.remove('hidden');
//         placeholder.classList.add('opacity-0');
//         if(scannerLine) scannerLine.style.display = 'none';
        
//         setTimeout(closeUploadModal, 1500);
//     } else {
//         setProgress(0);
//         updateSmartStatus('error', errorMsg);
//         faceInput.value = "";
//     }
// }
function toggleCondition(show) {
    const container = document.getElementById('condition_details_container');
    const textarea = document.getElementById('medical_conditions');
    
    if (show) {
        container.classList.remove('hidden');
        textarea.setAttribute('required', 'required');
        textarea.focus();
    } else {
        container.classList.add('hidden');
        textarea.removeAttribute('required');
        textarea.value = ''; // Clear text if they change back to 'No'
    }
}
// Helper Function: Check if the photo is too dark
async function checkLighting(imgElement) {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = imgElement.width;
    canvas.height = imgElement.height;
    ctx.drawImage(imgElement, 0, 0);
    
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const data = imageData.data;
    let colorSum = 0;

    for (let i = 0; i < data.length; i += 4) {
        // Simple luminance formula
        colorSum += (data[i] + data[i+1] + data[i+2]) / 3;
    }

    const brightness = colorSum / (imgElement.width * imgElement.height);
    return brightness > 40; // Returns false if too dark (0-255 scale)
}

document.querySelectorAll('input[name="nat_type"]').forEach((radio) => {
    radio.addEventListener('change', function() {
        const label = document.getElementById('id_label');
        const input = document.getElementById('id_number_input');

        if (this.value === 'national') {
            label.innerText = "NRC Number";
            input.placeholder = "Enter NRC Number";
        } else {
            label.innerText = "Passport ID";
            input.placeholder = "Enter Passport Number";
        }
    });
});

// Run once on page load to ensure the label matches the default/old selection
window.addEventListener('load', () => {
    const selected = document.querySelector('input[name="nat_type"]:checked');
    if (selected && selected.value === 'foreigner') {
        document.getElementById('id_label').innerText = "Passport ID";
        document.getElementById('id_number_input').placeholder = "Enter Passport Number";
    }
});
const districtOptions = {
  "1": ["ကပတ", "ကမတ", "ခပန", "ခလဖ", "ဆဒန", "ဆပရ", "ဆဘန", "တဆလ", "တနန", "ဒဖယ", "နမန", "ပတအ", "ပနဒ", "ပဝန", "ဖကန", "ဗမန", "မကတ", "မကန", "မခဘ", "မစန", "မညန", "မမန", "မလန", "ရှကန", "ရှဗယ", "လဂျန", "ဟပန", "အဂျယ", "၀မန"],
  "2": ["ဒမဆ", "ဖဆန", "ဖရဆ", "ဘလခ", "မစန", "ရတန", "ရသန", "လကန"],
  "3": ["ကကရ", "ကဆက", "ကဒတ", "ကဒန", "ကမမ", "စကလ", "ပကန", "ဖပန", "ဘဂလ", "ဘသဆ", "ဘအန", "မဝတ", "ရသန", "လဘန", "လသန", "ဝလမ", "သတက", "သတန"],
  "4": ["ကခန", "ကပလ", "ဆမန", "တဇန", "တတန", "ထတလ", "ပလဝ", "ဖလန", "မတန", "မတပ", "ရခဒ", "ရဇန", "ဟခန"],
  "5": ["ကနန", "ကဘလ", "ကမန", "ကလတ", "ကလထ", "ကလန", "ကလဝ", "ကသန", "ခတန", "ခပန", "ခဥတ", "ခဥန", "ငဇန", "စကန", "ဆလက", "တဆန", "တမန", "ထခန", "ဒပယ", "နယန", "ပလန", "ပလဘ", "ဖပန", "ဗမန", "ဘတလ", "မကန", "မမတ", "မမန", "မရန", "မလန", "ယမပ", "ရဘန", "ရဥန", "လရန", "လဟန", "ဝလန", "ဝသန", "ဟမလ", "အတန", "အရတ"],
  "6": ["ကစန", "ကရရ", "ကလအ", "ကသန", "ခမန", "တသရ", "ထဝန", "ပလတ", "ပလန", "ဘပန", "မတန", "မမန", "ရဖြန", "လလန", "သရခ"],
  "7": ["ကကန", "ကတခ", "ကပက", "ကဝန", "ဇကန", "ညလပ", "တငန", "ထတပ", "ဒဥန", "နတလ", "ပခတ", "ပခန", "ပတဆ", "ပတတ", "ပတန", "ပနက", "ပမန", "ဖမန", "မညန", "မလန", "ရကန", "ရတန", "ရတရှ", "လပတ", "ဝမန", "သကန", "သဆန", "သနပ", "သဝတ", "အတန", "အဖန"],
  "8": ["ကထန", "ကမန", "ခမန", "ဂဂန", "ငဖန", "စတရ", "စလန", "ဆပဝ", "ဆဖန", "ဆမန", "တတက", "ထလန", "နမန", "ပခက", "ပဖြန", "ပမန", "မကန", "မတန", "မထန", "မဘန", "မမန", "မလန", "မသန", "ရစက", "ရနခ", "သရန", "အလန"],
  "9": ["ကဆန", "ကပတ", "ခမစ", "ခအစ", "ငဇန", "ငသရ", "စကတ", "စကန", "ဇဗသ", "ဇယသ", "ညဥန", "တကတ", "တကန", "တတဥ", "တသန", "ဒခသ", "နထက", "ပကခ", "ပဗသ", "ပဘန", "ပမန", "ပသက", "ပဥလ", "မကန", "မခန", "မတရ", "မထလ", "မမန", "မလန", "မသန", "မဟမ", "ရမသ", "လဝန", "ဝတန", "သစန", "သပက", "အမစ", "အမရ", "ဥတသ"],
  "10": ["ကထန", "ကမရ", "ခဆန", "ခဇန", "ပမန", "ဘလန", "မဒန", "မလမ", "ရမန", "လမန", "သထန", "သဖြရ"],
  "11": ["ကတန", "ကတလ", "ကဖန", "ဂမန", "စတန", "တကန", "တပဝ", "ပဏတ", "ပတန", "ဗတထ", "ဘသတ", "မတန", "မပတ", "မပန", "မအတ", "မအန", "မဥန", "ရဗန", "ရသတ", "သတန", "အမန"],
  "12": ["ကကက", "ကခက", "ကတတ", "ကတန", "ကမတ", "ကမန", "ကမရ", "ခရန", "စခန", "ဆကခ", "ဆကန", "တကန", "တတထ", "တတန", "တမန", "ထတပ", "ဒဂဆ", "ဒဂတ", "ဒဂန", "ဒဂမ", "ဒဂရ", "ဒပန", "ဒလန", "ပဇတ", "ပဘတ", "ဗတထ", "ဗဟန", "မဂတ", "မဂဒ", "မဘန", "မရက", "ရကန", "ရပသ", "လကန", "လမတ", "လမန", "လသန", "လသယ", "သကတ", "သခန", "သဃက", "သလန", "အစန", "အလန", "ဥကတ", "ဥကန", "ဥကမ"],
  "13": ["ကခန", "ကတတ", "ကတန", "ကတလ", "ကမဆ", "ကမန", "ကရန", "ကလတ", "ကလဒ", "ကလန", "ကလဖ", "ကသန", "ကဟန", "ခမန", "ခရဟ", "ခလန", "ဆဆန", "ဆဖန", "ညရန", "တကန", "တခလ", "တမည", "တယန", "တလန", "နကန", "နခတ", "နခန", "နခဝ", "နဆန", "နတန", "နတယ", "နဖန", "နမတ", "နဝန", "ပခန", "ပဆန", "ပတယ", "ပပက", "ပယန", "ပလတ", "ပလန", "ပဝန", "ဖခန", "မကန", "မခန", "မငန", "မဆတ", "မဆန", "မတတ", "မတန", "မနန", "မပန", "မဖန", "မဗတ", "မဘန", "မမဆ", "မမတ", "မမန", "မယန", "မရတ", "မရန", "မလန", "မဟရ", "ယလန", "ရငန", "ရစန", "ရဖန", "လကတ", "လခတ", "လခန", "လရန", "လလန", "လဟန", "သနန", "သပန", "ဟတန", "ဟပတ", "ဟပန", "အခန", "အတန"],
  "14": ["ကကထ", "ကကန", "ကခန", "ကပန", "ကလန", "ငဆန", "ငပတ", "ငရက", "ငသခ", "ငသယ", "ဇလန", "ညတန", "ဒဒရ", "ဒနဖြ", "ပစလ", "ပတန", "ပသန", "ဖပန", "ဘကလ", "မမက", "မမန", "မအန", "မအပ", "ရကန", "ရသယ", "လပတ", "လမန", "ဝခမ", "သပန", "ဟကကျ", "ဟသတ", "အဂပ", "အမတ", "အမန"]
};

const stateSelect = document.getElementById('nrc_state');
const districtSelect = document.getElementById('nrc_district');

// These values come from your PHP Backend
const savedState = "{{ old('nrc_state', $nrcParts['state'] ?? '') }}";
const savedDistrict = "{{ old('nrc_district', $nrcParts['district'] ?? '') }}";

function updateDistricts(stateId, selectedDistrict = "") {
    districtSelect.innerHTML = '<option value="">District</option>';
    if (stateId && districtOptions[stateId]) {
        districtOptions[stateId].forEach(dist => {
            const option = document.createElement('option');
            option.value = dist;
            option.textContent = dist;
            if (dist === selectedDistrict) {
                option.selected = true;
            }
            districtSelect.appendChild(option);
        });
    }
}

// Event listener for user changes
stateSelect.addEventListener('change', function() {
    updateDistricts(this.value);
});

// AUTO-FILL ON LOAD
window.addEventListener('DOMContentLoaded', () => {
    if (savedState) {
        stateSelect.value = savedState;
        updateDistricts(savedState, savedDistrict);
    }
});
// --- NRC Dropdown Logic ---
const nrcState = document.getElementById('nrc_state');
const nrcDistrict = document.getElementById('nrc_district');

nrcState.addEventListener('change', function() {
    const selectedState = this.value;
    nrcDistrict.innerHTML = '<option value="">District</option>'; 
    if (districtOptions[selectedState]) {
        districtOptions[selectedState].forEach(function(district) {
            const option = document.createElement('option');
            option.value = district;
            option.text = district;
            nrcDistrict.add(option);
        });
    }
});

// --- National/Foreigner Toggle Logic ---
const nrcContainer = document.getElementById('nrc_container');
const passportContainer = document.getElementById('passport_container');
const idLabel = document.getElementById('id_label');

function toggleIdFields(type) {
    if (type === 'national') {
        // Show NRC, Hide Passport
        nrcContainer.classList.remove('hidden');
        passportContainer.classList.add('hidden');
        idLabel.innerText = "NRC Information";
        
        // Set Required attributes
        nrcContainer.querySelectorAll('select, input').forEach(el => el.required = true);
        document.getElementById('passport_input').required = false;
    } else {
        // Show Passport, Hide NRC
        nrcContainer.classList.add('hidden');
        passportContainer.classList.remove('hidden');
        idLabel.innerText = "Passport Information";
        
        // Set Required attributes
        nrcContainer.querySelectorAll('select, input').forEach(el => el.required = false);
        document.getElementById('passport_input').required = true;
    }
}

// Listen for clicks on the radio buttons
document.querySelectorAll('input[name="nat_type"]').forEach((radio) => {
    radio.addEventListener('change', function() {
        toggleIdFields(this.value);
    });
});

// Run on page load to set the initial state based on the pre-selected radio button
window.addEventListener('load', () => {
    const selected = document.querySelector('input[name="nat_type"]:checked');
    if (selected) {
        toggleIdFields(selected.value);
    }
});

function formatDate(input) {
    let v = input.value.replace(/\D/g, '').slice(0, 8);
    if (v.length >= 5) {
        input.value = `${v.slice(0, 2)}/${v.slice(2, 4)}/${v.slice(4)}`;
    } else if (v.length >= 3) {
        input.value = `${v.slice(0, 2)}/${v.slice(2)}`;
    } else {
        input.value = v;
    }
}

window.addEventListener('load', () => {
    const stateDropdown = document.getElementById('nrc_state');
    const districtDropdown = document.getElementById('nrc_district');

    // 1. Get the values passed from the Controller (Laravel Blade)
    const savedState = "{{ $nrcParts['state'] ?? '' }}";
    const savedDistrict = "{{ $nrcParts['district'] ?? '' }}";

    if (savedState) {
        // 2. Set the State
        stateDropdown.value = savedState;

        // 3. MANUALLY trigger the 'change' event so the District list populates
        const event = new Event('change');
        stateDropdown.dispatchEvent(event);

        // 4. Now that Districts are loaded, set the District
        if (savedDistrict) {
            // A tiny timeout ensures the browser finished rendering the new options
            setTimeout(() => {
                districtDropdown.value = savedDistrict;
            }, 100); 
        }
    }
});

function syncAthleteType() {
    // Get the type passed from your controller/session
    const selectedType = "{{ $athlete->nat_type ?? $type }}"; 
    
    const radioNat = document.getElementById('radio-national');
    const radioFor = document.getElementById('radio-foreigner');
    const labelNat = document.getElementById('label-national');
    const labelFor = document.getElementById('label-foreigner');
    const hiddenInput = document.getElementById('hidden_nat_type');

    if (selectedType === 'foreigner') {
        radioFor.checked = true;
        labelFor.classList.remove('opacity-50', 'border-slate-100');
        labelFor.classList.add('border-[#C3E92D]', 'bg-lime-50');
        
        // Hide NRC, show Passport
        document.getElementById('nrc_container').classList.add('hidden');
        document.getElementById('passport_container').classList.remove('hidden');
        document.getElementById('id_label').innerText = "Passport ID";
    } else {
        radioNat.checked = true;
        labelNat.classList.remove('opacity-50', 'border-slate-100');
        labelNat.classList.add('border-[#C3E92D]', 'bg-lime-50');
        
        // Show NRC, hide Passport
        document.getElementById('nrc_container').classList.remove('hidden');
        document.getElementById('passport_container').classList.add('hidden');
        document.getElementById('id_label').innerText = "NRC Number";
    }
    
    // Ensure the hidden input has the correct value for the database
    hiddenInput.value = selectedType;
}

function togglePartnerMode(mode) {
    const loginFields = document.getElementById('partner_login_fields');
    const registerFields = document.getElementById('partner_register_fields');
    
    // Inputs
    const loginInputs = loginFields.querySelectorAll('input');
    const registerInputs = registerFields.querySelectorAll('input');

    if (mode === 'login') {
        loginFields.classList.remove('hidden');
        registerFields.classList.add('hidden');
        
        // Toggle Required
        loginInputs.forEach(i => i.required = true);
        registerInputs.forEach(i => i.required = false);
    } else {
        loginFields.classList.add('hidden');
        registerFields.classList.remove('hidden');
        
        // Toggle Required
        loginInputs.forEach(i => i.required = false);
        registerInputs.forEach(i => i.required = true);
    }
}
// Run the sync when the page loads
window.addEventListener('DOMContentLoaded', syncAthleteType);
</script>
</body>
</html>