<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/css.css') }}">
        <title>{{ $pageTitle }}</title>
    </head>
    <body>

        <header>
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="images/logo.jpg" alt="Powells Automotive's logo">
                    <h2 class="page-title">Powells Automotive</h2>
                </a>
            </div>
            <nav>
                
                <ul class="nav-links">
                    <!-- TODO - make a for loop to add all pages to the menu, and adjust styling to accomodate this  -->
                    <li><x-nav-link href="{{ url('/') }}" class="header-menu-item-active">Home</x-nav-link></li>
                    <li><x-nav-link href="{{ url('/about') }}" class="header-menu-item">About</x-nav-link></li>
                    <li><x-nav-link href="{{ url('/services') }}" class="header-menu-item">Services</x-nav-link></li>
                    <li><x-nav-link href="{{ url('/testimonials') }}" class="header-menu-item">Testimonials</x-nav-link></li>
                    <li><x-nav-link href="{{ url('/faq') }}" class="header-menu-item">FAQ</x-nav-link></li>
                    <li><x-nav-link href="{{ url('/appointment') }}" class="header-menu-item">Appointment</x-nav-link></li>
                    <li><x-nav-link href="{{ url('/contact') }}" class="header-menu-item">Contact Us</x-nav-link></li>
                </ul>
            </nav>
            <div class="contact-info">
                
                <a href="{{ url('/contact') }}"><span class="phone-icon"><img src="images/gold-phone.png" alt="gold phone icon"></span></a>
                <a href="{{ url('/contact') }}"><span class="phone-number">(856) 492-7602</span></a>
                
            </div>
        </header>
        
        <!--Page path section-->
        @if(!isset($pageTitle) || $pageTitle !== 'Home')
            <!-- This code only runs if $pageTitle is NOT "Home" -->
            <div class="page-path">
                <h1>{{ $pageTitle }}</h1>
                <h5>Home/{{ $pageTitle }}</h5>
            </div>
        @endif

        
        <main>

        {{ $slot }}




            
        </main>



        

        <footer>
            <!-- ========== EMAIL SECTION OF FOOTER ========== -->
            <div class="footer-enter-email"><h5>
                    Enter you email and we'll get in 
                    touch or just walk in.  Laborum voluptate 
                    laborum labore ex ullamco excepteur aliqua 
                    nostrud culpa. 
                </h5>
                <input type="email" placeholder="Enter your email..">

                <!--button for email submit  maybe do another way?-->
                <div a href="" class="footer-submit-email">
                    <p>submit</p>
                </div>
            </div>
            <!-- ========== END OF EMAIL SECTION OF FOOTER ========== -->

            <div class="footer-logo-and-text">
                <img src="images/logo.jpg" alt="Powells Automotive's logo">
                <h4>POWELLS AUTOMOTIVE</h4>
                <P>
                    Lorem Ipsum is simply dummy text of the printing
                    and typesetting industry. Lorem Ipsum has been the
                    industry's stan. Lorem Ipsum is.
                </P>
            </div>

            <div class="footer-phone">
                <img src="images/gold-phone.png" alt="golden coloured phone icon">
                <h3>07946 28945</h3>
            </div>

            <div class="footer-links">
                <div class="qlinks-1">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="{{ url('/') }}" class="footer-menu-item">Home</a></li>
                        <li><a href="{{ url('/about') }}" class="footer-menu-item">About</a></li>
                        <li><a href="{{ url('/services') }}" class="footer-menu-item">Services</a></li>
                        <li><a href="{{ url('/testimonials') }}" class="footer-menu-item">Testimonials</a></li>
                    </ul>
                </div>

                <div class="qlinks-2">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="{{ url('/appointment') }}" class="footer-menu-item">Appointment</a></li>
                        <li><a href="{{ url('/faq') }}" class="footer-menu-item">Questions</a></li>
                        <li><a href="{{ url('/contact') }}" class="footer-menu-item">Contact us</a></li>
                        <li><a href="{{ url('/login') }}" class="footer-menu-item">Login</a></li>
                    </ul>
                </div>

                <div class="footer-contact">
                    <h5>Location & Contact</h5>
                    <div class="footer-contact-item f-email">
                        <img src="images/gold-email.png" alt="gold email icon">
                        <p>andreasanchez@gmail.com</p>
                    </div>
                      
                          <!-- Location -->
                    <div class="footer-contact-item f-location">
                        
                        <img src="images/gold-location-pin.png" alt="gold location pin icon">
                        <p>Unit 16 Vale supplier, park, 
                            Resolven, Neath SA11 4SR</p>
                    </div>
                      
                          <!-- Opening Hours -->
                    <div class="footer-contact-item f-hours">
                        <img src="images/gold-calender.png" alt="gold calendar icon">
                        <p>Mon - Sat: Open 9:00 - 18:00</p>
                    </div>      
                </div> 
            </div>
            
            <p class="copyright">&copy; 2025, all rights reserved</p>

        </footer>



    </body>
</html>
