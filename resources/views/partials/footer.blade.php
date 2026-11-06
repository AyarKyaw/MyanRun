<!-- Footer -->
    <footer class="footer background-black">
        <div class="footer-widgets">
            <div class="themeflat-container">
                <div class="row footer-top">
                    <div class="col-xxl-4 col-lg-4 col-xl-4 col-md-12 logo-footer">
                        <div class="widget">
                            <div class="textwidget">
                                <!--<img id="a1" src="images/logo/myarun(logo).jpg" alt="images"> -->
                                <!-- <p>Welcome to our running community! Discover the joy of running, connect with fellow
                                    enthusiasts, and unlock your full potential with our expert resources and training
                                    programs.
                                </p> -->
                                <div class="social-icon-footer">
                                @php
                                    // Decode the JSON from your site settings. 
                                    // Fallback to an empty array if it's not set or null.
                                    $socials = json_decode($global_info->social_links ?? '[]', true);
                                @endphp

                                @forelse($socials as $social)
                                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer">
                                        {{-- Dynamically picks the icon based on the platform name (e.g., icon-facebook, icon-youtube) --}}
                                        <i class="icon-{{ strtolower($social['platform']) }}"></i>
                                    </a>
                                @empty
                                    {{-- Fallback default links matching your original layout if the database is empty --}}
                                    <a href="https://www.facebook.com/share/g/1G6ZtYxVfj/" target="_blank" rel="noopener noreferrer">
                                        <i class="icon-facebook"></i>
                                    </a>
                                    <a href="https://www.facebook.com/share/1CFptZmwGM/" target="_blank" rel="noopener noreferrer">
                                        <i class="icon-facebook"></i>
                                    </a>
                                    <a href="http://www.youtube.com/@RUNderfulMyanmar-j9x" target="_blank" rel="noopener noreferrer">
                                        <i class="icon-youtube"></i>
                                    </a>
                                @endforelse
                            </div>
                            </div>
                        </div><!-- /.widget -->
                    </div><!-- /.col-md-4 -->

                    <div class="col-xxl-4 col-lg-4 col-xl-4 col-md-6 link-footer">
                        <div class="widget widget_menu-footer">
                            <h5 class="widget-title">Quick Links</h5>
                            <ul class="menu-footer">
                                <li><a href="/about">About Us</a></li>
                                <li><a href="/event">Our Event</a></li>
                                <li><a href="/race">Our Race</a></li>
                                 <li><a href="/blog">Our Stories</a></li> 
                                <li><a href="/contact">Contact Us</a></li>
                            </ul><!-- /.menu -->
                        </div><!-- /.widget -->
                        <div class="widget widget_menu-footer">
                            <h5 class="widget-title">Page</h5>
                            <ul class="menu-footer">
                                 <li><a href="/blog">Our Stories</a></li>
                                <!-- <li><a href="event-details.html">Events Detail</a></li>  -->
                                 <li><a href="/race">Our Race</a></li>
                                <li><a href="/event">Events</a></li>
                                <li><a href="/about">About Us</a></li>
                            </ul><!-- /.menu -->
                        </div><!-- /.widget -->
                    </div><!-- /.col-md-4 -->

                    <div class="col-xxl-4 col-lg-4 col-xl-4 col-md-6 new-letter">
                        <div class="widget widget_text widget_menu-footer">
                            <h5 class="widget-title">Contact Us</h5>
                            <div class="menu-footer">
                                <div class="text-phone">
                                    <svg width="36" height="37" viewBox="0 0 36 37" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M26.43 16.3754C25.785 16.3754 25.275 15.8504 25.275 15.2204C25.275 14.6654 24.72 13.5104 23.79 12.5054C22.875 11.5304 21.87 10.9604 21.03 10.9604C20.385 10.9604 19.875 10.4354 19.875 9.80539C19.875 9.17539 20.4 8.65039 21.03 8.65039C22.53 8.65039 24.105 9.46039 25.485 10.9154C26.775 12.2804 27.6 13.9754 27.6 15.2054C27.6 15.8504 27.075 16.3754 26.43 16.3754Z"
                                            fill="white" />
                                        <path
                                            d="M31.8456 16.375C31.2006 16.375 30.6906 15.85 30.6906 15.22C30.6906 9.895 26.3556 5.575 21.0456 5.575C20.4006 5.575 19.8906 5.05 19.8906 4.42C19.8906 3.79 20.4006 3.25 21.0306 3.25C27.6306 3.25 33.0006 8.62 33.0006 15.22C33.0006 15.85 32.4756 16.375 31.8456 16.375Z"
                                            fill="#C3E92D" />
                                        <path
                                            d="M17.685 21.565L12.78 26.47C12.24 25.99 11.715 25.495 11.205 24.985C9.66 23.425 8.265 21.79 7.02 20.08C5.79 18.37 4.8 16.66 4.08 14.965C3.36 13.255 3 11.62 3 10.06C3 9.04 3.18 8.065 3.54 7.165C3.9 6.25 4.47 5.41 5.265 4.66C6.225 3.715 7.275 3.25 8.385 3.25C8.805 3.25 9.225 3.34 9.6 3.52C9.99 3.7 10.335 3.97 10.605 4.36L14.085 9.265C14.355 9.64 14.55 9.985 14.685 10.315C14.82 10.63 14.895 10.945 14.895 11.23C14.895 11.59 14.79 11.95 14.58 12.295C14.385 12.64 14.1 13 13.74 13.36L12.6 14.545C12.435 14.71 12.36 14.905 12.36 15.145C12.36 15.265 12.375 15.37 12.405 15.49C12.45 15.61 12.495 15.7 12.525 15.79C12.795 16.285 13.26 16.93 13.92 17.71C14.595 18.49 15.315 19.285 16.095 20.08C16.635 20.605 17.16 21.115 17.685 21.565Z"
                                            fill="#C3E92D" />
                                        <path
                                            d="M32.9554 27.7436C32.9554 28.1636 32.8804 28.5986 32.7304 29.0186C32.6854 29.1386 32.6404 29.2586 32.5804 29.3786C32.3254 29.9186 31.9954 30.4286 31.5604 30.9086C30.8254 31.7186 30.0154 32.3036 29.1004 32.6786C29.0854 32.6786 29.0704 32.6936 29.0554 32.6936C28.1704 33.0536 27.2104 33.2486 26.1754 33.2486C24.6454 33.2486 23.0104 32.8886 21.2854 32.1536C19.5604 31.4186 17.8354 30.4286 16.1254 29.1836C15.5404 28.7486 14.9554 28.3136 14.4004 27.8486L19.3054 22.9436C19.7254 23.2586 20.1004 23.4986 20.4154 23.6636C20.4904 23.6936 20.5804 23.7386 20.6854 23.7836C20.8054 23.8286 20.9254 23.8436 21.0604 23.8436C21.3154 23.8436 21.5104 23.7536 21.6754 23.5886L22.8154 22.4636C23.1904 22.0886 23.5504 21.8036 23.8954 21.6236C24.2404 21.4136 24.5854 21.3086 24.9604 21.3086C25.2454 21.3086 25.5454 21.3686 25.8754 21.5036C26.2054 21.6386 26.5504 21.8336 26.9254 22.0886L31.8904 25.6136C32.2804 25.8836 32.5504 26.1986 32.7154 26.5736C32.8654 26.9486 32.9554 27.3236 32.9554 27.7436Z"
                                            fill="white" />
                                    </svg>
                                    
                                </div><div class="address">
                                <div class="contact-numbers">
                                    @php
    $phones = json_decode($global_info->phone_numbers ?? '[]', true)
        ?: ['09 540 5026', '09 513 5324'];
@endphp

                                    @foreach($phones as $phone)
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">
                                            <span class="elementor-icon-list-text">{{ $phone }}</span>
                                        </a>
                                    @endforeach
                                </div>

                                <p>
                                    <i class="icon-MapPin"></i>
                                    {{ $global_info->street_address ?? 'No.68, Htan Ta Pin Street, Aung Myay Thar Si Housing, No(1) Quarter, Kamaryut, Yangon 11041' }}
                                </p>
                            </div>
                                <form action="#">
                                    <div class="input-new-letter">
                                        <input class="btn-email" name="email" id="email" type="email"
                                            placeholder="Your email address" required>
                                        <button class="btn-submit" type="submit"><i class="icon-uniE925"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div><!-- /.widget -->
                    </div><!-- /.col-md-4 -->

                </div><!-- /.row -->
                <div class="row footer-bottom">
                    <div class="col-md-6 col-sm-12">
                        <div class="copyright">
                            <p>©2026 <a href="index.html" target="_blank"> Myanrun</a> All Rights Reserved.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <ul class="link-footer-bottom">
                            <li><a href="/term">Terms Of Services</a></li>
                            <li><a href="/pp">Privacy Policy</a></li>
                        </ul>
                    </div>

                    <!-- CENTER TEXT -->
                    <div class="col-12" style="width: 100%; text-align: center; margin-top: 30px; padding-bottom: 50px; position: relative; z-index: 9999;">
                        <p style="font-size: 16px; color: #fff; margin: 0;">Powered by 
                            <a href="https://itplus.net.mm/" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            style="
                                    color: #39FF14 !important; 
                                    font-weight: 900 !important; 
                                    font-size: 22px !important; 
                                    text-decoration: none !important; 
                                    display: inline-block !important;
                                    position: relative !important;
                                    z-index: 10000 !important;
                                    pointer-events: auto !important;
                                    text-shadow: 0 0 10px rgba(57, 255, 20, 0.7), 0 0 20px rgba(57, 255, 20, 0.4);
                                    transition: all 0.3s ease;
                            ">
                            IT PLUS
                            </a>
                        </p>
                    </div>
                </div>
            </div><!-- /.container -->
        </div><!-- /.footer-widgets -->
    </footer>

    <!-- Go Top -->
    <a class="go-top">
        <i class="icon-ctrl"></i>
    </a>

    <!-- Modal-login -->
<div class="modal fade modal-login @if($errors->has('email') || $errors->has('password')) show @endif" id="exampleModalToggle" aria-hidden="true" aria-label="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="tfre_login-form">
                <h2>Login:</h2>
                
                @if($errors->any())
                    <div class="alert alert-danger" style="margin: 0 15px 15px 15px; padding: 10px; font-size: 14px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" class="tfre_login" method="post" id="tfre_custom-login-form">
                    @csrf    
                    <div class="container">
                        <div class="form-group">
                            <label>Email Address: <span style="color: red;">*</span></label>
                            <input type="email" name="email" id="email" placeholder="Enter your email" value="{{ old('email') }}" required="">
                        </div>
                        <div class="form-group">
                            <label>Password: <span style="color: red;">*</span></label>
                            <input type="password" name="password" id="password" placeholder="Your password" required="">
                        </div>
                        <div class="d-flex justify-content-between align-items-center" style="margin-top: 10px;">
                            <label style="font-size: 13px; cursor: pointer;">
                                <input type="checkbox" name="remember"> Remember Me
                            </label>
                            <a href="#" data-bs-target="#exampleModalToggle3" data-bs-toggle="modal" data-bs-dismiss="modal" class="tfre-reset-password">Forgot password?</a>
                        </div>
                        <button type="submit" class="flat-button" style="width: 100%; margin-top: 20px;">Login</button>
                    </div>
                </form>
            </div>
            <div class="container tfre_register" id="tfre_register_redirect" style="text-align: center; padding-bottom: 20px;">
                <p>Don't you have an account? <a href="#" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" data-bs-dismiss="modal">Register.</a></p>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal fade modal-login" id="exampleModalToggle2" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content" style="padding: 15px;">
            <button type="button" class="btn-close" data-bs-dismiss="modal" style="position: absolute; right: 15px; top: 15px; z-index: 10;"></button>
            <div class="tfre_registration-form">
                <h4 style="margin-bottom: 15px; font-weight: bold;">Register</h4>
                <form class="tfre_register" method="post" id="tfre_custom-register-form" action="{{ route('register.submit') }}">
                    @csrf
                    <div class="row g-2"> 
                        <div class="col-6 form-group">
                            <label style="font-size: 12px;">First Name <span style="color: red;">*</span></label>
                            <input type="text" name="first_name" placeholder="John" required style="height: 35px; font-size: 13px; width: 100%;">
                        </div>
                        <div class="col-6 form-group">
                            <label style="font-size: 12px;">Middle Name</label>
                            <input type="text" name="middle_name" placeholder="Quincy" style="height: 35px; font-size: 13px; width: 100%;">
                        </div>
                        <div class="col-6 form-group">
                            <label style="font-size: 12px;">Last Name <span style="color: red;">*</span></label>
                            <input type="text" name="last_name" placeholder="Doe" required style="height: 35px; font-size: 13px; width: 100%;">
                        </div>
                        <div class="col-12 form-group">
                            <label style="font-size: 12px;">Email <span style="color: red;">*</span></label>
                            <input type="email" name="email" placeholder="example@mail.com" required style="height: 35px; font-size: 13px; width: 100%;">
                        </div>
                        <div class="col-12 form-group">
                            <label style="font-size: 12px;">Phone <span style="color: red;">*</span></label>
                            <input type="tel" name="phone" value="+95 9" placeholder="+95 9XXXXXXXX" required style="height: 35px; font-size: 13px; width: 100%;">
                        </div>
                        <div class="col-6 form-group">
                            <label style="font-size: 12px;">Password <span style="color: red;">*</span></label>
                            <input type="password" name="password" placeholder="••••••••" required style="height: 35px; width: 100%;">
                        </div>
                        <div class="col-6 form-group">
                            <label style="font-size: 12px;">Confirm <span style="color: red;">*</span></label>
                            <input type="password" name="password_confirmation" placeholder="••••••••" required style="height: 35px; width: 100%;">
                        </div>
                    </div>
                    <button type="submit" class="flat-button" style="width: 100%; margin-top: 15px; padding: 10px;">Sign Up</button>
                </form>
                <p style="text-align: center; margin-top: 10px; font-size: 13px;">
                    Already have an account? <a href="#" data-bs-target="#exampleModalToggle" data-bs-toggle="modal" data-bs-dismiss="modal">Sign in.</a>
                </p>
            </div>
        </div>
    </div>
</div>
       <!-- modal find_size -->
       <div class="modal fade modalDemo tf-product-modal" id="find_size">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="">
                    <h3>Size chart</h3>
                    <span class="icon-cross icon-close-popup" data-bs-dismiss="modal"></span>
                </div>
                <div class="tf-rte">
                    <div class="tf-table-res-df">
                        <h4>Size guide</h4>
                        <table class="tf-sizeguide-table">
                            <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>US</th>
                                    <th>Bust</th>
                                    <th>Waist</th>
                                    <th>Low Hip</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>XS</td>
                                    <td>2</td>
                                    <td>32</td>
                                    <td>24 - 25</td>
                                    <td>33 - 34</td>
                                </tr>
                                <tr>
                                    <td>S</td>
                                    <td>4</td>
                                    <td>34 - 35</td>
                                    <td>26 - 27</td>
                                    <td>35 - 26</td>
                                </tr>
                                <tr>
                                    <td>M</td>
                                    <td>6</td>
                                    <td>36 - 37</td>
                                    <td>28 - 29</td>
                                    <td>38 - 40</td>
                                </tr>
                                <tr>
                                    <td>L</td>
                                    <td>8</td>
                                    <td>38 - 29</td>
                                    <td>30 - 31</td>
                                    <td>42 - 44</td>
                                </tr>
                                <tr>
                                    <td>XL</td>
                                    <td>10</td>
                                    <td>40 - 41</td>
                                    <td>32 - 33</td>
                                    <td>45 - 47</td>
                                </tr>
                                <tr>
                                    <td>XXL</td>
                                    <td>12</td>
                                    <td>42 - 43</td>
                                    <td>34 - 35</td>
                                    <td>48 - 50</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tf-page-size-chart-content">
                        <div>
                            <h4>Measuring Tips</h4>
                            <h6>Bust</h6>
                            <p>Measure around the fullest part of your bust.</p>
                            <h6>Waist</h6>
                            <p>Measure around the narrowest part of your torso.</p>
                            <h6>Low Hip</h6>
                            <p class="mb-0">With your feet together measure around the fullest part of your hips/rear.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /modal find_size -->
    <!-- modal quick_view -->
    <div class="modal fade modalDemo" id="quick_view">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="">
                    <span class="icon-cross icon-close-popup" data-bs-dismiss="modal"></span>
                </div>
                <div class="wrap">
                    <div class="tf-product-media-wrap">
                        <div class="swiper tf-single-slide">
                            <div class="swiper-wrapper" >
                                <div class="swiper-slide">
                                    <div class="item">
                                        <img src="images/product/product-detail-1.jpg" alt="img-product">
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="item">
                                        <img src="images/product/product-detail-2.jpg" alt="img-product">
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-button-next button-style-arrow single-slide-prev"></div>
                            <div class="swiper-button-prev button-style-arrow single-slide-next"></div>
                        </div>
                    </div>
                    <div class="tf-product-info-wrap">
                        <div class="tf-product-info-list">
                            <div class="tf-product-info-heading">
                                <div class="tf-product-info-name">
                                    <div class="text text-sub-heading">SHOES</div>
                                    <h3 class="name">Ribbed long-sleeved t-shirt</h3>
                                    <div class="tf-product-info-rate">
                                        <div class="list-star d-flex">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.99229 8.16559L1.87977 6.41682C1.48105 6.09796 1.65593 5.45674 2.16191 5.38446L5.15814 5.25097L6.43766 2.04313C6.52684 1.86534 6.70813 1.75342 6.90691 1.75342C7.10569 1.75342 7.28698 1.86592 7.37617 2.04313L8.65568 5.25097L11.6519 5.38446C12.1579 5.45674 12.3328 6.09796 11.9341 6.41682L9.82153 8.16559L10.398 11.5798C10.4645 12.0455 9.98067 12.3941 9.56038 12.1837L6.90691 10.4973L4.25344 12.1831C3.83257 12.3935 3.34933 12.045 3.41578 11.5792L3.99229 8.16559Z" fill="#ECB018"/>
                                            </svg>
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.99229 8.16559L1.87977 6.41682C1.48105 6.09796 1.65593 5.45674 2.16191 5.38446L5.15814 5.25097L6.43766 2.04313C6.52684 1.86534 6.70813 1.75342 6.90691 1.75342C7.10569 1.75342 7.28698 1.86592 7.37617 2.04313L8.65568 5.25097L11.6519 5.38446C12.1579 5.45674 12.3328 6.09796 11.9341 6.41682L9.82153 8.16559L10.398 11.5798C10.4645 12.0455 9.98067 12.3941 9.56038 12.1837L6.90691 10.4973L4.25344 12.1831C3.83257 12.3935 3.34933 12.045 3.41578 11.5792L3.99229 8.16559Z" fill="#ECB018"/>
                                            </svg>
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.99229 8.16559L1.87977 6.41682C1.48105 6.09796 1.65593 5.45674 2.16191 5.38446L5.15814 5.25097L6.43766 2.04313C6.52684 1.86534 6.70813 1.75342 6.90691 1.75342C7.10569 1.75342 7.28698 1.86592 7.37617 2.04313L8.65568 5.25097L11.6519 5.38446C12.1579 5.45674 12.3328 6.09796 11.9341 6.41682L9.82153 8.16559L10.398 11.5798C10.4645 12.0455 9.98067 12.3941 9.56038 12.1837L6.90691 10.4973L4.25344 12.1831C3.83257 12.3935 3.34933 12.045 3.41578 11.5792L3.99229 8.16559Z" fill="#ECB018"/>
                                            </svg>
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.99229 8.16559L1.87977 6.41682C1.48105 6.09796 1.65593 5.45674 2.16191 5.38446L5.15814 5.25097L6.43766 2.04313C6.52684 1.86534 6.70813 1.75342 6.90691 1.75342C7.10569 1.75342 7.28698 1.86592 7.37617 2.04313L8.65568 5.25097L11.6519 5.38446C12.1579 5.45674 12.3328 6.09796 11.9341 6.41682L9.82153 8.16559L10.398 11.5798C10.4645 12.0455 9.98067 12.3941 9.56038 12.1837L6.90691 10.4973L4.25344 12.1831C3.83257 12.3935 3.34933 12.045 3.41578 11.5792L3.99229 8.16559Z" fill="#ECB018"/>
                                            </svg>
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.1085 8.16559L1.99598 6.41682C1.59726 6.09796 1.77214 5.45674 2.27812 5.38446L5.27435 5.25097L6.55387 2.04313C6.64306 1.86534 6.82435 1.75342 7.02312 1.75342C7.2219 1.75342 7.40319 1.86592 7.49238 2.04313L8.7719 5.25097L11.7681 5.38446C12.2741 5.45674 12.449 6.09796 12.0503 6.41682L9.93774 8.16559L10.5143 11.5798C10.5807 12.0455 10.0969 12.3941 9.67659 12.1837L7.02312 10.4973L4.36965 12.1831C3.94878 12.3935 3.46554 12.045 3.53199 11.5792L4.1085 8.16559Z" fill="#E9E9E9"/>
                                            </svg>
                                        </div>
                                        <div class="number-rate text-caption-1">(1.234 reviews)</div>
                                    </div>
                                </div>
                                <div class="tf-product-info-desc">
                                    <div class="tf-product-info-price">
                                        <div class="price-on-sale">$68.00</div>
                                        <div class="line"></div>
                                        <div class="compare-at-price">$98.00</div>
                                        <div class="badges-on-sale text-sub-heading">
                                            -25%
                                        </div>
                                    </div>
                                    <p>The garments labelled as Committed are products that have been produced using sustainable fibres or processes, reducing their environmental impact.</p>
                                </div>
                            </div>
                            <div class="tf-product-info-choose-option">
                                <div class="variant-picker-item">
                                    <div class="variant-picker-label text-title">
                                        Colors: <span class="variant-picker-label-value value-currentColor">Gray</span>
                                    </div>
                                    <div class="variant-picker-values">
                                        <input id="values-beige1" type="radio" name="color2">
                                        <label class="style-text rectangle-color radius-60" for="values-beige1" data-value="Beige" data-price="68.00">
                                            <span class="btn-checkbox bg-beige"></span>
                                            Beige
                                        </label>
                                        <input id="values-black2" type="radio" name="color2" checked="">
                                        <label class="style-text rectangle-color radius-60" for="values-black2" data-value="Black" data-price="79.99">
                                            <span class="btn-checkbox bg-black"></span>
                                            Black
                                        </label>
                                    </div>
                                </div>
                                <div class="variant-picker-item">
                                    <div class="d-flex justify-content-between">
                                        <div class="text-title variant-picker-label">
                                            Size: <span class="variant-picker-label-value">L</span>
                                        </div>
                                    </div>
                                    <div class="variant-picker-values gap12">
                                        <input type="radio" name="size2" id="values-s1">
                                        <label class="text-button style-text size-btn" for="values-s1" data-value="S" data-price="68.00">
                                            S
                                        </label>
                                        <input type="radio" name="size2" id="values-m1">
                                        <label class="text-button style-text size-btn" for="values-m1" data-value="M" data-price="68.00">
                                            M
                                        </label>
                                        <input type="radio" name="size2" id="values-l1" checked="">
                                        <label class="text-button style-text size-btn" for="values-l1" data-value="L" data-price="89.99">
                                            L
                                        </label>
                                        <input type="radio" name="size2" id="values-xl1">
                                        <label class="text-button style-text size-btn" for="values-xl1" data-value="XL" data-price="89.99">
                                            XL
                                        </label>
                                    </div>
                                </div>
                                <div class="tf-product-info-btn-buy">
                                    <div class="text-title mb_12">Quantity:</div>
                                    <div class="tf-product-info-quantity">
                                        <div class="wg-quantity">
                                            <span class="btn-quantity minus-btn">-</span>
                                            <input class="quantity-input" type="text" name="number" value="1">
                                            <span class="btn-quantity plus-btn">+</span>
                                        </div>
                                        <a href="#" class="text-button-uppercase2 btn-add-cart">ADD TO CART</a>
                                    </div>
                                    <a class="tf-product-info-by-now text-button-uppercase2 mb-0" href="#">Buy It Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /modal quick_view -->
    <!-- compare -->
    <div class="modal fullBottom fade modal-compare" id="compare">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="header">
                    <span class="icon-cross icon-close-popup" data-bs-dismiss="modal"></span>
                </div>
                <div class="wrap">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="tf-compare-list list-file-delete">
                                    <div class="tf-compare-head">
                                        <h5 class="title mb-0">Compare Products</h5>
                                    </div>
                                    <div class="tf-compare-wrap">
                                        <div class="tf-compare-item file-delete-item">
                                            <span class="icon icon-cross remove-item"></span>
                                            <a href="shop-detail.html" class="image">
                                                <img src="images/product/5.png" alt="">
                                            </a>
                                            <div class="content">
                                                <div class="text-title">
                                                    <a class="link text-line-clamp-2" href="shop-detail.html">V-neck cotton T-shirt</a>
                                                </div>
                                                <div class="text-button">$59.99</div>
                                            </div>
                                        </div>
                                        <div class="tf-compare-item file-delete-item">
                                            <span class="icon icon-cross remove-item"></span>
                                            <a href="shop-detail.html" class="image">
                                                <img src="images/product/6.png" alt="">
                                            </a>
                                            <div class="content">
                                                <div class="text-title">
                                                    <a class="link text-line-clamp-2" href="shop-detail.html">V-neck cotton T-shirt</a>
                                                </div>
                                                <div class="text-button">$59.99</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tf-compare-buttons">
                                        <div class="tf-compare-buttons-wrap">
                                            <div class="tf-compapre-button-clear-all clear-list-file tf-btn w-100 btn-white radius-4 has-border">
                                                <span class="text text-btn-uppercase">Clear All Products</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>