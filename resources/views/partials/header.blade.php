<div class="header-top">
    <div class="error_message" style="margin-bottom: 15px; font-weight: bold;"></div>
    <div class="themeflat-container">
        <div class="header-top-inner">
            <div class="login" style="display: flex; align-items: center; gap: 10px; margin-left: 30px">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.6484 17.8756C18.2206 15.4072 16.0203 13.6372 13.4525 12.7981C14.7226 12.042 15.7094 10.8898 16.2614 9.51853C16.8134 8.14726 16.8999 6.63272 16.5078 5.20749C16.1157 3.78226 15.2666 2.52515 14.0909 1.62921C12.9151 0.733275 11.4778 0.248047 9.99964 0.248047C8.52146 0.248047 7.08414 0.733275 5.90842 1.62921C4.73269 2.52515 3.88358 3.78226 3.49146 5.20749C3.09935 6.63272 3.18592 8.14726 3.73788 9.51853C4.28984 10.8898 5.27668 12.042 6.54683 12.7981C3.97902 13.6362 1.77871 15.4062 0.350893 17.8756C0.298533 17.961 0.263803 18.056 0.248752 18.155C0.233701 18.254 0.238634 18.355 0.26326 18.4521C0.287886 18.5492 0.331707 18.6404 0.392136 18.7202C0.452565 18.8001 0.528379 18.867 0.615104 18.9171C0.70183 18.9672 0.79771 18.9995 0.897088 19.0119C0.996466 19.0243 1.09733 19.0167 1.19373 18.9896C1.29012 18.9624 1.3801 18.9162 1.45835 18.8537C1.5366 18.7912 1.60154 18.7136 1.64933 18.6256C3.41558 15.5731 6.53746 13.7506 9.99964 13.7506C13.4618 13.7506 16.5837 15.5731 18.35 18.6256C18.3977 18.7136 18.4627 18.7912 18.5409 18.8537C18.6192 18.9162 18.7092 18.9624 18.8056 18.9896C18.902 19.0167 19.0028 19.0243 19.1022 19.0119C19.2016 18.9995 19.2975 18.9672 19.3842 18.9171C19.4709 18.867 19.5467 18.8001 19.6072 18.7202C19.6676 18.6404 19.7114 18.5492 19.736 18.4521C19.7607 18.355 19.7656 18.254 19.7505 18.155C19.7355 18.056 19.7008 17.961 19.6484 17.8756ZM4.74964 7.0006C4.74964 5.96224 5.05755 4.94721 5.63443 4.08385C6.21131 3.2205 7.03124 2.54759 7.99056 2.15023C8.94987 1.75287 10.0055 1.6489 11.0239 1.85147C12.0423 2.05405 12.9777 2.55406 13.712 3.28829C14.4462 4.02251 14.9462 4.95797 15.1488 5.97637C15.3513 6.99477 15.2474 8.05037 14.85 9.00969C14.4527 9.969 13.7797 10.7889 12.9164 11.3658C12.053 11.9427 11.038 12.2506 9.99964 12.2506C8.60771 12.2491 7.27322 11.6955 6.28898 10.7113C5.30473 9.72702 4.75113 8.39253 4.74964 7.0006Z" fill="white" />
                </svg>
                
                @guest
                    <a href="/login" style="color: white; font-size: 20px;">Login/Register</a>
                @endguest

                @auth
                    <div class="user-info" style="display: flex; align-items: center; gap: 10px;">
                        <a href="{{ route('user.dashboard') }}" style="text-decoration: none;">
                            <span style="color: white; font-weight: bold; font-size: 13px;">Hi, {{ Auth::user()->first_name }}</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" style="background: none; border: 1px solid white; color: white; padding: 2px 8px; font-size: 11px; border-radius: 4px;">Logout</button>
                        </form>
                    </div>
                @endauth
            </div>

            <div class="address">
                <a href="mailto:{{ $global_info->email ?? 'info@myanrun.com' }}">
                    <i class="icon-Vector"></i>{{ $global_info->email ?? 'info@myanrun.com' }}
                </a>

                <address>
                    <i class="icon-Vector-22"></i>{{ $global_info->street_address ?? 'No.68, Htan Ta Pin Street, Kamaryut, Yangon, Myanmar' }}
                </address>
            </div>

            <div class="social-icon">
    @php
        // Decode the JSON social links from site settings
        $socials = json_decode($global_info->social_links ?? '[]', true);
    @endphp

    @foreach($socials as $social)
        <a href="{{ $social['url'] }}" target="_blank">
            {{-- Dynamically sets icon class, e.g., icon-facebook, icon-youtube, icon-tiktok --}}
            <i class="icon-{{ strtolower($social['platform']) }}"></i>
        </a>
    @endforeach

    {{-- Fallback: If no social links are set in admin, you can keep this or leave it empty --}}
    @if(empty($socials))
        <a href="https://www.facebook.com/" target="_blank"><i class="icon-facebook"></i></a>
    @endif
</div>
        </div>
    </div>
</div>

<header id="header" class="header style1 clearfix">
    <div class="themeflat-container">
        <div class="header-inner">
            <div id="logo" class="logo">
                <a href="/" rel="home">
                    <img src="{{ asset('images/logo/myarun(logo).jpg') }}" alt="image" style="width: 200px; background: transparent;">
                </a>
            </div>
            
            <div class="nav-wrap">
                <div class="btn-menu"><span class="icon-menu"></span></div>
                <nav id="mainnav" class="mainnav">
                    <ul class="menu">
                        <li><a href="/">Home</a></li>
                        <li><a href="/about">About us</a></li>
                        <li><a href="/event">Our Events</a></li>
			<li><a href="/race_guide">Race Guide</a></li>
                      
                        <li><a href="/race">Our Race</a></li>
                        <!-- <li><a href="/score">Our Score</a></li> -->
                        <li><a href="/result">Our Results</a></li>
                        <li><a href="/dinner">Our Dinner</a></li>
                        <li><a href="/blog">Our Stories</a></li>
                        <li><a href="/contact">Contact us</a></li>
                        
                        @guest
                            <li class="mobile-only-menu">
                                <a href="/register" role="button"><i class="icon-user-plus"></i> Register</a>
                            </li>
                            <li class="mobile-only-menu">
                                <a href="/login" role="button"><i class="icon-user-plus"></i> Login</a>
                            </li>
                        @endguest

                        @auth
                            <li class="mobile-only-menu">
                                <a href="/user/dashboard">My Account ({{ Auth::user()->first_name }})</a>
                            </li>
                            <li class="mobile-only-menu">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-contact">Logout</button>
                                </form>
                            </li>
                        @endauth
                    </ul>
                </nav>
            </div>

            <div class="header-right" style="padding-right: 20px;">
                <div class="search">
                    <a class="show-search">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M21.0004 21.0004L16.6504 16.6504" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    </a>
                </div>

                <!-- <a href="/contact" class="btn-contact">Contact Us</a> -->
            </div>
        </div>
    </div>
</header>

    <div class="overlay-menu-mobie">
        <div class="close-btn">
            <span class="close-menus"></span>
        </div>
    </div>
