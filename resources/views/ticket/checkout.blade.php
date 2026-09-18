@extends('layouts.master')

@section('title', 'Event - MYANRUN')

@section('content')
    <div class="page-title page-shop">
        <div class="themeflat-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-heading">
                        <h1 class="title">Check Out</h1>
                    </div>
                    <div class="breadcrumbs">
                        <ul>
                            <li><a href="{{ url('/') }}">Homepage</a></li>
                            <li><i class="icon-Arrow---Right-2"></i></li>
                            <li><a>Check Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="tf-spacing-1">
        <div class="themeflat-container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="checkout-wrap" style="background: #fff; padding: 30px; border-radius: 12px; border: 1px solid #eee;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 style="font-weight: 800; margin: 0;">Runner Details</h3>
                            <a href="{{ route('athlete.register') }}" class="text-muted" style="font-size: 13px; text-decoration: underline;">Edit Info</a>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Full Name</label>
                                <p style="font-size: 16px; font-weight: 600; color: #000;">{{ $fullName ?? 'Not provided' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">
                                    {{ ($order['nat_type'] ?? '') == 'national' ? 'NRC Number' : 'Passport ID' }}
                                </label>
                                <p style="font-size: 16px; font-weight: 600; color: #000;">{{ Auth::user()->athlete->id_number ?? '---' }}</p>
                            </div>

                            <div class="col-md-6">
                                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Date of Birth</label>
                                <p style="font-size: 16px; font-weight: 600; color: #000;">{{ Auth::user()->athlete->dob ?? '---' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Gender</label>
                                <p style="font-size: 16px; font-weight: 600; color: #000; text-transform: capitalize;">{{ Auth::user()->athlete->gender ?? '---' }}</p>
                            </div>

                            <div class="col-12">
                                <hr style="border-top: 1px solid #eee;">
                            </div>

                            <div class="col-md-6">
                                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Email Address</label>
                                <p style="font-size: 16px; font-weight: 600; color: #000;">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Phone Number</label>
                                <p style="font-size: 16px; font-weight: 600; color: #000;">{{ Auth::user()->phone ?? 'No secondary phone' }}</p>
                            </div>

                            <div class="col-12">
                                <div class="p-3" style="background: #fdfdfd; border-radius: 8px; border: 1px solid #f0f0f0;">
                                    <label style="font-size: 11px; text-transform: uppercase; color: #999; font-weight: 700; display: block; margin-bottom: 5px;">
                                        <i class="icon-shield-check" style="color: #CEF531;"></i> Race Verification
                                    </label>
                                    <p class="mb-0" style="font-size: 13px; color: #666;">
                                        Please ensure the ID above matches the one you will bring to the race kit collection.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="checkout-cart" style="background: #f9f9f9; padding: 30px; border-radius: 12px; border: 1px solid #eee;">
                        <h4 class="mb-4">Review Order</h4>
                        
                        <div class="checkout-product-wrap">
                            <div class="checkout-product-item d-flex align-items-center mb-3" style="gap: 15px;">
                                <div class="image" style="width: 70px; height: 70px; background: #000; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; overflow: hidden;">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15 5C15 6.10457 14.1046 7 13 7C11.8954 7 11 6.10457 11 5C11 3.89543 11.8954 3 13 3C14.1046 3 15 3.89543 15 5Z" fill="#CEF531"/>
                                        <path d="M13.5 19L9.5 13L11 11L13.5 15L18 14L17 12L14.5 12.5L12 8H8L4 13L5.5 14.5L8 11.5L9 14.5L6 21H8.5L10.5 17.5L13.5 19Z" fill="#CEF531"/>
                                    </svg>
                                    <div style="position: absolute; width: 40px; height: 40px; background: #CEF531; filter: blur(25px); opacity: 0.2;"></div>
                                </div>
                                
                                <div class="content flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="text-title d-block" style="font-weight: 700; line-height: 1.2;">
                                                {{ $order['event'] ?? 'Race Registration' }}
                                            </span>
                                            <small class="text-muted">{{ $order['category'] ?? 'General' }}</small>
                                            
                                            {{-- Early Bird Badge --}}
                                            @if($isEarlyBirdActive ?? false)
                                                <div class="mt-1">
                                                    <span style="font-size: 10px; background: #CEF531; color: #000; padding: 2px 8px; border-radius: 20px; font-weight: 700; text-transform: uppercase;">Early Bird Applied</span>
                                                </div>
                                            @endif

                                            <div class="mt-1">
                                                <small class="d-block" style="font-size: 11px; color: #888;">Runner: {{ $order['runner_name'] ?? 'Guest' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Promo Code Box --}}
                        <div class="promo-box my-3 p-3" style="background: #fff; border-radius: 8px; border: 1px solid #e0e0e0;">
                            <label style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: #555; display: block; margin-bottom: 6px;">Have a Promo Code?</label>
                            <div class="d-flex" style="gap: 8px;">
                                <input type="text" 
                                       id="promo_code_input" 
                                       class="form-control" 
                                       placeholder="ENTER CODE" 
                                       value="{{ $appliedPromo['code'] ?? '' }}"
                                       {{ !empty($appliedPromo) ? 'disabled' : '' }}
                                       style="text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">
                                <button type="button" 
                                        id="btn-apply-promo" 
                                        {{ !empty($appliedPromo) ? 'disabled' : '' }}
                                        style="background: #000; color: #fff; border: none; padding: 0 16px; border-radius: 4px; font-weight: 700; font-size: 12px; text-transform: uppercase; cursor: pointer; flex-shrink: 0;">
                                    {{ !empty($appliedPromo) ? 'Applied' : 'Apply' }}
                                </button>
                            </div>
                            <small id="promo-message" class="d-block mt-2 font-weight-bold" style="font-size: 12px; color: {{ !empty($appliedPromo) ? '#28a745' : '#dc3545' }};">
                                {{ !empty($appliedPromo) ? '✓ Promo code applied!' : '' }}
                            </small>
                        </div>

                        <div class="line my-3" style="border-top: 1px dashed #ddd;"></div>

                        <div class="checkout-cart-by">
                            {{-- Subtotal --}}
                            <div class="d-flex justify-content-between mb-2">
                                <div class="text-title text-muted">Registration Fee</div>
                                <div class="text-title">{{ number_format($subtotal, 2) }}</div>
                            </div>

                            {{-- Early Bird Discount Row --}}
                            @if($isEarlyBirdActive ?? false)
                            <div class="d-flex justify-content-between mb-2">
                                <div class="text-title" style="color: #28a745;">Early Bird Discount</div>
                                <div class="text-title" style="color: #28a745;">-{{ number_format($discountAmount, 2) }}</div>
                            </div>
                            @endif

                            {{-- Promo Discount Row --}}
                            <div id="promo-discount-row" class="{{ !empty($appliedPromo) ? 'd-flex' : 'd-none' }} justify-content-between mb-2">
                                <div class="text-title" style="color: #28a745;">Promo Code Discount</div>
                                <div class="text-title" style="color: #28a745;" id="promo-discount-val">
                                    -{{ number_format($promoDiscount ?? 0, 2) }}
                                </div>
                            </div>

                            {{-- Service Fee --}}
                            <div class="d-flex justify-content-between mb-2">
                                <div class="text-title text-muted">Service Fee</div>
                                <div class="text-title">{{ number_format($serviceFee, 2) }}</div>
                            </div>
                            
                            {{-- Final Total --}}
                            <div class="d-flex justify-content-between mt-3 mb-4">
                                <h4 style="font-weight: 800; margin: 0;">Total</h4>
                                <h4 style="font-weight: 800; color: #000; margin: 0;" id="display-total-price">{{ number_format($total, 2) }} MMK</h4>
                            </div>

                            <form action="{{ route('initiatePayment', ['id' => Auth::user()->runner_id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="promo_code" id="applied_promo_code" value="{{ $appliedPromo['code'] ?? '' }}">
                                <button type="submit" class="tf-btn w-100 justify-content-center" 
                                    style="background-color: #CEF531; color: #000; border: none; padding: 20px; font-weight: 800; font-size: 18px; text-transform: uppercase; letter-spacing: 1.5px; border-radius: 4px; box-shadow: 0 4px 14px rgba(206, 245, 49, 0.4); transition: transform 0.2s;">
                                    PROCEED TO PAYMENT
                                </button>
                            </form>
                            
                            <p class="text-center mt-3" style="font-size: 11px; text-transform: uppercase; color: #999; letter-spacing: 1px;">
                                Secure Payment via KBZPay
                            </p>
                        </div>
                    </div>
                </div> 
            </div> 
        </div> 
    </section>

    @if(session('error'))
        <div style="color:red; text-align: center; margin-top: 15px;">
            {{ session('error') }}
        </div>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const applyBtn = document.getElementById('btn-apply-promo');
        if (!applyBtn) return;

        applyBtn.addEventListener('click', function () {
            const codeInput = document.getElementById('promo_code_input');
            const code = codeInput.value.trim();
            const eventId = "{{ $order['event_id'] ?? '' }}";
            const ticketTypeId = "{{ $order['ticket_type_id'] ?? '' }}";
            const originalPrice = "{{ $subtotal - $discountAmount }}";

            const msg = document.getElementById('promo-message');
            const discountRow = document.getElementById('promo-discount-row');
            const discountVal = document.getElementById('promo-discount-val');
            const totalEl = document.getElementById('display-total-price');
            const hiddenPromoInput = document.getElementById('applied_promo_code');

            if (!code) {
                msg.style.color = '#dc3545';
                msg.innerText = 'Please enter a promo code.';
                return;
            }

            applyBtn.innerText = 'CHECKING...';
            applyBtn.disabled = true;

            fetch("{{ route('promo.apply') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    code: code,
                    event_id: eventId,
                    event_ticket_type_id: ticketTypeId,
                    original_price: originalPrice
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    applyBtn.innerText = 'APPLIED';
                    applyBtn.disabled = true;
                    codeInput.disabled = true;

                    msg.style.color = '#28a745';
                    msg.innerText = '✓ ' + (data.message || 'Promo code applied successfully!');

                    if (hiddenPromoInput) {
                        hiddenPromoInput.value = code;
                    }

                    discountRow.classList.remove('d-none');
                    discountRow.classList.add('d-flex');
                    discountVal.innerText = '-' + Number(data.discount).toLocaleString('en-US', { minimumFractionDigits: 2 });

                    totalEl.innerText = Number(data.final_price).toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' MMK';
                } else {
                    applyBtn.innerText = 'APPLY';
                    applyBtn.disabled = false;

                    if (hiddenPromoInput) {
                        hiddenPromoInput.value = '';
                    }
                    msg.style.color = '#dc3545';
                    msg.innerText = '✕ ' + (data.message || 'Invalid promo code.');
                }
            })
            .catch(err => {
                applyBtn.innerText = 'APPLY';
                applyBtn.disabled = false;
                console.error(err);
            });
        });
    });
    </script>
@endsection