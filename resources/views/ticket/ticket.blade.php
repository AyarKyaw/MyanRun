@extends('layouts.master')

@section('title', 'Ticket Sales - MYAN RUN')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

    #ticket-page-scope {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        background-color: #F8FAFC;
    }

    .ticket-card { 
        position: relative !important;
        overflow: hidden !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;      
        justify-content: flex-end !important; 
        padding-bottom: 3rem !important;      
        transition: all 0.5s ease !important;
        cursor: pointer !important;
        border-radius: 32px !important;
        min-height: 580px !important; 
        border: 4px solid transparent !important;
        background-size: cover !important;
        background-position: center center !important;
        background-repeat: no-repeat !important;
        width: 100% !important;
    }

    .ticket-card::before {
        content: '' !important;
        position: absolute !important;
        inset: 0 !important;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0) 60%) !important;
        transition: opacity 0.5s !important;
        opacity: 0.8 !important;
        z-index: 1 !important;
    }
    
    .ticket-card.active { 
        border-color: #C3E92D !important;
        transform: translateY(-10px) !important;
        box-shadow: 0 25px 50px -12px rgba(195, 233, 45, 0.4) !important;
    }

    .price-tag { 
        position: relative !important;
        z-index: 10 !important;
        color: #ffffff !important;
        font-weight: 900 !important;
        font-size: 3rem !important; 
        text-align: center !important;
        letter-spacing: -0.05em !important;
        transition: all 0.3s ease !important;
    }

    .ticket-card.active .price-tag {
        color: #C3E92D !important;
        transform: scale(1.05) !important;
    }

    .category-badge { 
        position: absolute !important;
        top: 2rem !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        white-space: nowrap !important;
        padding: 0.5rem 1.25rem !important;
        border-radius: 9999px !important;
        font-size: 12px !important;
        font-weight: 900 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.1em !important;
        z-index: 10 !important;
        color: white !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        backdrop-filter: blur(12px) !important;
    }

    .ticket-card.active .category-badge {
        background-color: #C3E92D !important;
        color: #0f172a !important;
        border-color: #C3E92D !important;
    }

    .content-wrapper {
        padding-top: 100px;
    }
</style>
@endpush

@section('content')
<div id="ticket-page-scope" class="content-wrapper py-20 px-4">
    <form action="{{ url('/select-race') }}" method="POST">
        @csrf
        {{-- Default values updated to 16 Kilometer --}}
        <input type="hidden" name="ticket_type_id" id="input-category">
        <input type="hidden" name="ticket_name" id="input-name">
        <input type="hidden" name="price" id="input-price">
        <input type="hidden" name="nationality" id="input-nat" value="national">
        <input type="hidden" name="event_id" value="{{ $event->id }}">

        <div class="max-w-5xl mx-auto"> {{-- Reduced max-width for better 2-card centering --}}
            <header class="flex flex-col items-center justify-center mb-16 text-center">
                <div class="mb-8">
                    <h2 class="text-[#C3E92D] font-black uppercase tracking-widest text-lg mb-2">
                        {{ request('event', 'Official Race') }}
                    </h2>
                    
                    <h1 class="text-6xl font-black italic tracking-tighter uppercase text-slate-900 leading-tight">
                        Pick Your <span class="text-[#C3E92D]">Race</span>
                    </h1>
                    <p class="text-slate-400 font-bold mt-4 uppercase text-[10px] tracking-[0.4em]">Select your category below</p>
                </div>
                
                <div class="flex p-2 bg-slate-900 rounded-[2rem] w-full max-w-md shadow-2xl">
                    <button type="button" id="btn-local" onclick="updatePricing('local')" 
                        class="flex-1 py-3 rounded-[1.5rem] text-xs font-black uppercase tracking-widest transition-all duration-300 bg-[#C3E92D] text-slate-900">
                        National
                    </button>
                  <button type="button" id="btn-foreign" onclick="updatePricing('foreign')" 
                        class="flex-1 py-3 rounded-[1.5rem] text-xs font-black uppercase tracking-widest transition-all duration-300 text-[#C3E92D] hover:opacity-80">
                        Foreigner
                    </button> 
                </div>
            </header>

            {{-- Grid changed to 2 columns on medium screens --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                @foreach($event->ticketTypes as $ticket)
                    <div class="ticket-card"
                        style="background-image: url('{{ asset('storage/' . $ticket->national_image) }}');"
                        data-id="{{ $ticket->id }}"
                        data-name="{{ $ticket->name }}"
                        data-national="{{ $ticket->national_price }}"
                        data-foreign="{{ $ticket->foreign_price }}"
                        data-national-image="{{ $ticket->national_image }}"
                        data-foreign-image="{{ $ticket->foreign_image }}"
                        onclick="selectTicket(this)">
                        <span class="category-badge">{{ $ticket->name }}</span>

                        <div class="price-display price-tag">
                        {{ $ticket->national_price }} <span class="text-lg opacity-60">MMK</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 bg-slate-900 p-10 text-white flex flex-col md:flex-row items-center justify-between gap-8 shadow-2xl rounded-[40px]">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-2xl">
                        <i class="fas fa-bolt text-[#C3E92D]"></i>
                    </div>
                    <div>
                        <h4 class="text-2xl font-black uppercase italic tracking-tighter"><span id="summary-name" class="text-[#C3E92D]">Ready for 16 Kilometer Run</span>?</h4>
                        <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">Selected category</p>
                    </div>
                </div>
                <div class="flex items-center gap-12">
                    <div class="text-right">
                        <p class="text-slate-400 font-black uppercase text-[10px] tracking-widest">Total to Pay</p>
                        <div id="summary-price" class="text-4xl font-black tracking-tighter text-[#C3E92D]"> 0 MMK</div>
                    </div>
                    <button type="submit" onclick="return validateSelection()" class="bg-[#C3E92D] hover:scale-105 active:scale-95 text-slate-900 px-16 py-6 font-black uppercase tracking-widest text-sm transition-all shadow-xl rounded-full">
                        BUY
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const firstCard = document.querySelector('.ticket-card');
        if (firstCard) {
            selectTicket(firstCard);
        }
    });
    let currentType = 'local';
    document.addEventListener('DOMContentLoaded', function() {
        const natType = document.getElementById('hidden_nat_type').value;
        const nrcContainer = document.getElementById('nrc_container');
        const passportContainer = document.getElementById('passport_container');
        const idLabel = document.getElementById('id_label');

        // Logic to toggle containers based on the session/request type
        if (natType === 'foreigner') {
            nrcContainer.classList.add('hidden');
            passportContainer.classList.remove('hidden');
            idLabel.innerText = "Passport Information";
            
            // Highlight the Foreigner label visually
            document.getElementById('label-foreigner').classList.remove('opacity-50', 'cursor-not-allowed');
            document.getElementById('label-foreigner').classList.add('border-[#C3E92D]', 'bg-lime-50');
        } else {
            nrcContainer.classList.remove('hidden');
            passportContainer.classList.add('hidden');
            idLabel.innerText = "NRC Information";
            
            // Highlight the National label visually
            document.getElementById('label-national').classList.remove('opacity-50', 'cursor-not-allowed');
            document.getElementById('label-national').classList.add('border-[#C3E92D]', 'bg-lime-50');
        }
    });

    function validateSelection() {
        const category = document.getElementById('input-category').value;
        const price = document.getElementById('input-price').value;

        if (!category || !price) {
            alert("Please select a ticket first");
            return false;
        }
        return true;
    }

    function updatePricing(type) {
        currentType = type;
        const backendValue = (type === 'local') ? 'national' : 'foreigner';
        document.getElementById('input-nat').value = backendValue;

        const lb = document.getElementById('btn-local');
        const fb = document.getElementById('btn-foreign');
        
        // Updated Classes: Smaller padding (py-3) and Green text for inactive state
        const activeClass = "flex-1 py-3 rounded-[1.5rem] text-xs font-black uppercase tracking-widest transition-all duration-300 bg-[#C3E92D] text-slate-900 shadow-lg";
        const inactiveClass = "flex-1 py-3 rounded-[1.5rem] text-xs font-black uppercase tracking-widest transition-all duration-300 text-[#C3E92D] hover:opacity-80";

        if(type === 'local') {
            lb.className = activeClass;
            fb.className = inactiveClass;
        } else {
            fb.className = activeClass;
            lb.className = inactiveClass;
        }

        document.querySelectorAll('.ticket-card').forEach(card => {
            const price = (type === 'local')
                ? card.getAttribute('data-national')
                : card.getAttribute('data-foreign');
            card.querySelector('.price-display').innerHTML = `${price} <span class="text-lg opacity-60">MMK</span>`;
            const image = (type === 'local')
                ? card.getAttribute('data-national-image')
                : card.getAttribute('data-foreign-image');

            card.style.backgroundImage = `url('/storage/${image}')`;
            
            if(card.classList.contains('active')) {
                document.getElementById('summary-price').innerText = price + ' MMK';
                document.getElementById('input-price').value = price;
            }
        });
    }

    function selectTicket(card) {
        document.querySelectorAll('.ticket-card').forEach(c => c.classList.remove('active'));
        card.classList.add('active');
        const name = card.getAttribute('data-name');
        const price = (currentType === 'local')
        ? card.getAttribute('data-national')
        : card.getAttribute('data-foreign');
        document.getElementById('summary-name').innerText = name;
        document.getElementById('summary-price').innerText = price + ' MMK';
        document.getElementById('input-category').value = card.getAttribute('data-id'); // ✅ ID
        document.getElementById('input-name').value = name;
        document.getElementById('input-price').value = price;
    }
</script>
@endpush
