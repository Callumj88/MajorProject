<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/css.css') }}">
        <title>Home</title>
    </head>
    <body>

        <header>
            <div class="logo">
                <a href="index.html">
                    <img src="images/logo.jpg" alt="Powells Automotive's logo">
                    <h2 class="page-title">Powells Automotive</h2>
                </a>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="{{ url('/') }}" class="header-menu-item-active">Home</a></li>
                    <li><x-nav-link href="{{ url('/about') }}" class="header-menu-item">About</x-nav-link></li>
                    <li><a href="services.html" class="header-menu-item">Services</a></li>
                    <li><a href="testimonials.html" class="header-menu-item">Testimonials</a></li>
                    <li><a href="faq.html" class="header-menu-item">FAQ</a></li>
                    <li><a href="appointment.html" class="header-menu-item">Appointment</a></li>
                    <li><a href="{{ url('/contact') }}" class="header-menu-item">Contact Us</a></li>
                </ul>
            </nav>
            <div class="contact-info">
                
                <a href="contact-us.html"><span class="phone-icon"><img src="images/gold-phone.png" alt="gold phone icon"></span></a>
                <a href="contact-us.html"><span class="phone-number">(856) 492-7602</span></a>
                
            </div>
        </header>


        
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
                        <li><a href="index.html" class="footer-menu-item">Home</a></li>
                        <li><a href="about.html" class="footer-menu-item">About</a></li>
                        <li><a href="services.html" class="footer-menu-item">Services</a></li>
                        <li><a href="testimonials.html" class="footer-menu-item">Testimonials</a></li>
                    </ul>
                </div>

                <div class="qlinks-2">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="appointment.html" class="footer-menu-item">Appointment</a></li>
                        <li><a href="faq.html" class="footer-menu-item">Questions</a></li>
                        <li><a href="contact-us.html" class="footer-menu-item">Contact us</a></li>
                        <li><a href="Login.html" class="footer-menu-item">Login</a></li>
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
