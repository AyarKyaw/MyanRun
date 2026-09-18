@extends('layouts.master')

@section('title', 'Consent - MYANRUN')

@section('content')
    <div class="page-title">
        <div class="themeflat-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-heading">
                        <h1 class="title">Review & Registration</h1>
                    </div>
                    <div class="breadcrumbs">
                        <ul>
                            <li><a href="/">Homepage</a></li>
                            <li><i class="icon-Arrow---Right-2"></i></li>
                            <li><a>Final Consent</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <div class="main-content" style="padding-top: 80px; padding-bottom: 80px; background-color: #fdfdfd;">
    <div class="themeflat-container">
        <div class="row">
            
            <div class="col-md-12">
                <div class="about-box">
                    <div class="title-box">
                        <span class="sub-title" style="text-transform: uppercase; letter-spacing: 2px; color: #e63946; font-weight: bold; display: block; margin-bottom: 10px;">Verify Your Details</span>
                        <h2 class="title-section" style="font-size: 32px; font-weight: 700; margin-bottom: 30px;">Registration Summary</h2>
                    </div>

                    <div class="post" style="color: #333; line-height: 1.8; font-size: 15px; text-align: justify;">
                        <h3 style="font-size: 22px; font-weight: 700; margin-bottom: 20px;">Disclaimer and Waiver of Liability</h3>
                        
                        <p style="background: #f8f9fa; padding: 25px; border-left: 5px solid #e63946; font-style: italic; margin-bottom: 35px;">
                            "The following Terms of Service constitute a binding legal agreement between you and MyanRun. By completing this registration, you acknowledge that you have read, understood, and agreed to these terms."
                        </p>

                        <div class="waiver-section mb-5">
                            <h4 style="font-size: 18px; font-weight: 700; color: #e63946;">1. Waiver and Release of Liability</h4>
                            <p class="mb-2">I acknowledge and accept all risks associated with participation in the Bagan Marathon 2026 (the "Event"), including the potential for injury, accident, or property damage. On behalf of myself and all my heirs, executors, administrators, and assigns, I hereby agree to release and forever discharge the event organizers, sponsors, and all associated parties from any and all claims and liabilities.</p>
                            <p style="color: #666; line-height: 2;">တာဝန်ယူမှုမှ ကင်းလွတ်ခွင့်ပြုခြင်း (Waiver and Release): ကျွနု်ပ်သည်  Bagan Marathon 2026(the "Event) ပါဝင်ခြင်းနှင့် ဆက်စပ်၍ မတော်တဆမှု၊ ထိခိုက်ဒဏ်ရာ သို့မဟုတ် ပစ္စည်းပျက်စီးမှုများ ဖြစ်ပေါ်လာနိုင်သည့် အန္တရာယ်အားလုံးကို နားလည်လက်ခံပါသည်။ ကျွနု်ပ်ကိုယ်စားနှင့် ကျွနု်ပ်၏ အမွေခံများ၊ အုပ်ချုပ်သူများ၊ တာဝန်ခံပုဂ္ဂိုလ်များနှင့် လွှဲအပ်ခြင်းခံရသူများအားလုံးအတွက်၊ ပြိုင်ပွဲစီစဉ်သူများ၊ ပံ့ပိုးသူများနှင့် သက်ဆိုင်သူများ အားလုံးကို တောင်းဆိုမှုများမှ အပြီးအပိုင် စွန့်လွှတ်ကြောင်းနှင့် တာဝန်ယူမှုများအားလုံးမှ ကင်းလွတ်ခွင့်ပြုကြောင်း သဘောတူပါသည်။</p>
                        </div>

                        <div class="waiver-section mb-5" style="margin-top: 30px;">
                            <h4 style="font-size: 18px; font-weight: 700; color: #e63946;">2. Registration Fee Policy</h4>
                            <p class="mb-2">I understand that the registration fee is non-refundable and non-transferable under any circumstances, including event cancellation or modification due to force majeure, extreme weather, or circumstances beyond the control of the organizers.</p>
                            <p style="color: #666; line-height: 2;">ပြိုင်ပွဲဝင်ကြေး (Registration Fee Policy): မလွှဲမရှောင်သာသော အကြောင်းတရားများ (force majeure)၊ ဆိုးရွားသော ရာသီဥတု သို့မဟုတ် စီစဉ်သူများ၏ ထိန်းချုပ်ရန် တတ်နိုင်ခြင်းမရှိသည့် အခြေအနေများကြောင့် ပြိုင်ပွဲဖျက်သိမ်းရခြင်း သို့မဟုတ် အပြောင်းအလဲများ ပြုလုပ်ရခြင်း အပါအဝင် မည်သည့်အခြေအနေမျိုးတွင်မဆို ပြိုင်ပွဲဝင်ကြေးသည် ပြန်အမ်းမည်မဟုတ်ကြောင်းနှင့် လွှဲပြောင်းနိုင်မည်မဟုတ်ကြောင်း ကျွနု်ပ်သိရှိနားလည်ပါသည်။</p>
                        </div>

                        <div class="waiver-section mb-5" style="margin-top: 30px;">
                            <h4 style="font-size: 18px; font-weight: 700; color: #e63946;">3. Photo/Media Release</h4>
                            <p class="mb-2">I grant permission for my likeness and images captured during the event to be used for any legitimate promotional purposes.</p>
                            <p style="color: #666; line-height: 2;">မီဒီယာအသုံးပြုခွင့် (Media Release): ပြိုင်ပွဲအတွင်း ရိုက်ကူးထားသော ကျွနု်ပ်၏ ဓာတ်ပုံများနှင့် ရုပ်ပုံများကို တရားဝင်သော ကြော်ငြာများတွင် အသုံးပြုခြင်းကို ကျွနု်ပ်ခွင့်ပြုပါသည်။</p>
                        </div>

                        <div id="scroll-finish"></div>
                    </div>

                    <div id="consent-section" style="margin-top: 60px; padding: 40px; border-top: 2px dashed #eee; opacity: 0.2; pointer-events: none; transition: all 0.6s ease;">
                        
                        <div style="background: #fff5f5; border: 1px solid #feb2b2; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                            <!-- <h5 style="font-weight: 700; color: #c53030; margin-bottom: 10px;">
                                <i class="fa fa-exclamation-triangle"></i> For Participants Under 18 
                                <span style="display: block; font-size: 13px; font-weight: 400;">(အသက် ၁၈ နှစ်အောက် ပြိုင်ပွဲဝင်များအတွက်)</span>
                            </h5>
                            <p style="font-size: 14px; color: #4a5568; margin-bottom: 15px;">
                                If you are under 18, please download, sign, and bring the Parental Consent Form to the bib collection point.
                                <br>အသက် ၁၈ နှစ်အောက်ဖြစ်ပါက မိဘအုပ်ထိန်းသူသဘောတူညီချက်ပုံစံကို ဒေါင်းလုဒ်ဆွဲ၍ လက်မှတ်ရေးထိုးကာ BIB ထုတ်ယူချိန်တွင် ယူဆောင်လာပေးရပါမည်။
                            </p> -->
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ asset('images/P/ConsentEnglish.pdf') }}" target="_blank" class="btn btn-sm" style="background: #4a5568; color: white; font-weight: 600; border-radius: 4px; padding: 8px 15px;">
                                    <i class="fa fa-download me-1"></i> Consent Form (EN)
                                </a>
                                <a href="{{ asset('images/P/ConsentBurmese.pdf') }}" target="_blank" class="btn btn-sm" style="background: #4a5568; color: white; font-weight: 600; border-radius: 4px; padding: 8px 15px;">
                                    <i class="fa fa-download me-1"></i> Consent Form (MM)
                                </a>
                            </div>
                        </div>

                        <form action="{{ route('checkout.review') }}" method="GET">
                            <div style="margin-bottom: 30px;">
                                <label style="display: flex; align-items: flex-start; gap: 15px; cursor: pointer;">
                                    <input type="checkbox" id="agree-check" style="width: 22px; height: 22px; margin-top: 3px; accent-color: #e63946;">
                                    <span style="font-size: 15px; font-weight: 600; color: #333;">
                                        I have read the terms above and I agree to the conditions. <br>
                                        <small class="text-muted">အထက်ပါ စည်းကမ်းချက်များကို ဖတ်ရှုပြီး သဘောတူညီပါသည်။</small>
                                    </span>
                                </label>
                            </div>

                            <button type="submit" id="payBtn" disabled 
                                style="padding: 20px 60px; background: #333; color: #fff; border: none; border-radius: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; cursor: not-allowed; transition: all 0.3s;">
                                Proceed to Payment
                            </button>
                        </form>
                    </div>

                    <div id="read-reminder" style="text-align: center; margin-top: 20px; color: #e63946; font-weight: 700; font-size: 13px;">
                        <i class="icon-Arrow---Down-2"></i> PLEASE SCROLL DOWN TO ACCEPT TERMS
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    // Unlocks the checkbox section when the user scrolls to the bottom of the text
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                document.getElementById('consent-section').style.opacity = "1";
                document.getElementById('consent-section').style.pointerEvents = "auto";
                document.getElementById('read-reminder').style.display = "none";
            }
        });
    }, { threshold: 1.0 });

    observer.observe(document.getElementById('scroll-finish'));

    // Enable/Disable button based on checkbox
    const check = document.getElementById('agree-check');
    const btn = document.getElementById('payBtn');

    check.onchange = function() {
        if(this.checked) {
            btn.disabled = false;
            btn.style.background = "#e63946"; 
            btn.style.cursor = "pointer";
        } else {
            btn.disabled = true;
            btn.style.background = "#333";
            btn.style.cursor = "not-allowed";
        }
    };
</script>
@endpush
@endsection
