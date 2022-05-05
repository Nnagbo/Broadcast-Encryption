<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{{env('SITE_NAME')}}</title>

    <link rel="shortcut icon" href="img/core-img/favicon.ico">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/responsive.css">

    <!--[if IE]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

    <style type="text/css">
        #why li {
            margin: 0 0 15px;
            font-weight: 700;
            font-size: 18px;
            line-height: 1.1;
            list-style: all;
        }
    </style>

</head>

<body class="dark_version">
    <!-- preloader start -->
    <!-- /.end preloader -->

    <!-- ***************** Header Start ***************** -->
    <header class="header_area">
        <div class="main_header_area" id="sticky">
            <div class="container">
                <div class="row">

                    <div class="col-sm-2 col-xs-9">
                        <div class="logo_area">
                            <a href=""><img src="img/core-img/logo.png" alt=""></a>
                        </div>
                    </div>

                    <div class="col-sm-10 col-xs-12">
                        <!-- Menu Area Start -->
                        <div class="main_menu_area">
                            <div class="mainmenu">
                                <nav>
                                    <ul id="nav">
                                        <li class="current_page_item"><a href="{{url('/')}}">Home</a></li>
                                        <!-- <li><a href="#about">About</a></li> -->
                                        <li><a href="#service">Features</a></li>
                                        <li><a href="#contact">Contact Us</a></li>

                                        @if (!Session::has('uid'))
                                        <li><a href="{{url('login')}}">Login</a></li>
                                        <li><a href="{{url('register')}}">Sign Up</a></li>

                                        @else
                                        <li><a href="{{url('/dashboard')}}">Dashboard</a></li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>

                        </div>
                        <!-- Menu Area End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Header Area End -->
    </header>
    <!-- ***************** Header End ***************** -->

    <!-- ***************** Welcome Area Start ***************** -->
    <section class="welcome_area" id="home">
        <div class="welcome_slides">
            <!-- Single Slide Start -->
            <div class="single_slide" style="background-image: url(img/bg-pattern/bg-2.jpg);">
                <div class="slide_text">
                    <div class="table">
                        <div class="table_cell">
                            <h2><span>Royal</span> Donors</h2>
                            <h3>We're all working together; that's the secret.</h3>
                            <h2></h2>
                            @if (!Session::has('uid'))
                            <a class="btn btn-default" href="{{url('register')}}" role="button">Join Now</a>
                            @else
                            <a class="btn btn-default" href="{{url('dashboard')}}" role="button">Dashboard</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Single Slide Start -->
            <div class="single_slide" style="background-image: url(img/bg-pattern/bg-1.jpg);">
                <div class="slide_text">
                    <div class="table">
                        <div class="table_cell">
                            <h2>DONATE, EARN <span>AND BE HAPPY</span></h2>
                            <h3>If you don't drive your finance, you will be driven out of wealth.</h3>
                            <h2></h2>
                            @if (!Session::has('uid'))
                            <a class="btn btn-default" href="{{url('register')}}" role="button">Join Now</a>
                            @else
                            <a class="btn btn-default" href="{{url('dashboard')}}" role="button">Dashboard</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ************** Welcome Area End ************** -->

    <!-- ************** Special Feature Area Start ************** -->
    <section class="special_feature_area">
        <div class="container">
            <div class="row">

                <div class="col-sm-6 col-md-3">
                    <!-- Single Feature Area Start -->
                    <div class="single_feature wow fadeInUp" data-wow-delay=".2s">
                        <div class="bg-icon">
                            <span class="icon-tools"></span>
                        </div>
                        <div class="feature_img">
                            <span class="icon-tools"></span>
                        </div>
                        <!-- Single Feature Image Area End -->
                        <div class="feature_text">
                            <h5>Investment Return</h5>
                            <p>You get instant 20% return of your investment. That is if you donate &#8358;10,000 you get &#8358;12,000 in return.</p>
                        </div>
                        <!-- Single Feature Text Area End -->
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <!-- Single Feature Area Start -->
                    <div class="single_feature wow fadeInUp" data-wow-delay=".4s">
                        <div class="bg-icon">
                            <span class="icon-gift"></span>
                        </div>
                        <div class="feature_img">
                            <span class="icon-gift"></span>
                        </div>
                        <!-- Single Feature Image Area End -->
                        <div class="feature_text">
                            <h5>Fast &amp; Reliable</h5>
                            <p>Paired members make donations within a period of 7 days, enabling everyone to get their ROI very fast.</p>
                        </div>
                        <!-- Single Feature Text Area End -->
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <!-- Single Feature Area Start -->
                    <div class="single_feature wow fadeInUp" data-wow-delay=".6s">
                        <div class="bg-icon">
                            <span class="icon-genius"></span>
                        </div>
                        <div class="feature_img">
                            <span class="icon-genius"></span>
                        </div>
                        <!-- Single Feature Image Area End -->
                        <div class="feature_text">
                            <h5>Support</h5>
                            <p>Our effective support team are always online to attend to your demands and help you solve any issue you are having.</p>
                        </div>
                        <!-- Single Feature Text Area End -->
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <!-- Single Feature Area Start -->
                    <div class="single_feature wow fadeInUp" data-wow-delay=".8s">
                        <div class="bg-icon">
                            <span class="icon-adjustments"></span>
                        </div>
                        <div class="feature_img">
                            <span class="icon-adjustments"></span>
                        </div>
                        <!-- Single Feature Image Area End -->
                        <div class="feature_text">
                            <h5>Live Stats</h5>
                            <p>You can see precise &amp; accurate site statistics and prediction of pairing and donation time as well as other site activities.</p>
                        </div>
                        <!-- Single Feature Text Area End -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ************** Special Feature Area End ************** -->

    <!-- ************** About Us Area Start ************** -->
    <section class="about_area" id="about">
        <div class="container">
            <div class="about_us_area">
                <div class="row">
                    <div class="col-xs-12 col-md-8 section_padding_100 wow fadeInUp">
                        <!-- Section Heading Start -->
                        <div class="section_heading">
                            <p>What you need to know</p>
                            <h3>Welcome to {{ env('SITE_NAME') }}</h3>
                        </div>
                        <!-- Section Heading End -->

                        <!-- About Us Text Start -->
                        <div class="about_us_text">
                            <p>
                                {{ env('SITE_NAME') }} is not a bank, Investment Company, or get rich quick scheme. {{ env('SITE_NAME') }} does not collect money from anyone’s bank account. {{ env('SITE_NAME') }} is acommunity of people that understand it is more important to work together in order to beat the "unfair financial order'" we live in today in this world, where only a few of the people enjoy and control the world's riches and wealth, where the rich keep on getting richer and the poor keep on getting poorer. {{ env('SITE_NAME') }} participants from all over the world, selflessly and unselfishly help each other to fulfill one another's dreams by donating among themselves in order to advance each other’s financial lives. They understand that nothing beats community work, {{ env('SITE_NAME') }} members understand that “together we stand, but divided we fall”.
                            </p>

                            <p>

                            <div class="section_heading">
                                <h3>BENEFITS OF {{ env('SITE_NAME') }} MEMBER’S REFERRAL BONUS</h3>
                            </div>
                            
                                By referring others into the program a member will receive 5% commission in Tokens. Commissions (Tokens) do not grow they remain the same, this is done to sustain the system and make it stable. The commission (Tokens) increases your PH and GH capacity.
                            </p>
                        </div>

                        <div class="section_heading">
                            <h3>Why Choose Us</h3>
                        </div>

                        <ol id="why">
                            <li>Register for free!!! No joining or activation fee</li>
                            <li>Automatic matching integration.</li>
                            <li>Fastest growing donation exchange in the World.</li>
                            <li>Fast and well maintained servers – no unscheduled down time and slow system.</li>
                            <li>We are all about sustaining the system, no greed to collapse the system.</li>
                            <li>We value opinions of our members and make effort to live to their expectations.</li>
                            <li>We believe in financial freedom for all.</li>
                            <li>Well secured and security conscious platform.</li>
                        </ol>

                        <div class="section_heading">
                            <h3>Rules</h3>
                        </div>

                        <ol id="why">
                            <li>Contribution must be paid within 24hrs of merging and confirmation must be done within 24hrs of payment or each defaulting participant account will be deleted.</li>
                            <li>Any deleted account connected to FAKE POP, LATE PAYMENT, LATE CONFIRMATION will not be revisited and will no longer exist in the system.</li>
                            <li>You must be MOBILE TRANSFER enabled as merging can occur at any TIME and DAY of the WEEK.</li>
                            <li>We ask all members to exercise high level of honesty and kindness in our platform because any mischief or unethical behavior will not be tolerated.</li>
                            <li>Any account that is not ACTIVE in ONE WEEK will become dormant. You will need to buy extra TOKENS of at least N1000 to reactive it.</li> 
                            <li>Any account that is dormant for ONE MONTH will be automatically deleted.</li>
                            <li>After PHing and GHing 10 times, your TOKENS will be wiped out, that means you will need to BUY NEW TOKENS to continue your transactions.</li>
                        </ol>
                    </div>

                    <!-- About Us Chart Area -->
                    <div class="col-xs-12 col-md-4 wow fadeInRight">
                        <div class="about_us_thumb">
                            <img src="img/bg-pattern/about-us.jpg" alt="">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- end./ container -->
    </section>
    <!-- ************** Awesome Feature Area End ************** -->

    <!-- ************** Cool Facts Area Start ************** -->
    
    <!-- ************** Cool Facts Area End ************** -->

    <!-- ************** Our Speciality Area Start ************** -->
    <section class="our_speciality_area section_padding_60" id="service" data-stellar-background-ratio="0.6">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-8">
                    <div class="row">
                        <div class="col-xs-12">
                            <!-- Section Heading Start -->
                            <div class="section_heading">
                                <p>our best values</p>
                                <h3>Core Features</h3>
                            </div>
                            <!-- Section Heading End -->
                        </div>

                        <!-- Single Speciality Area Start -->
                        <div class="col-xs-12 col-sm-6 wow fadeInUp" data-wow-delay=".1s">
                            <div class="single_speciality">
                                <div class="single_speciality_icon">
                                    <i class="fa fa-check-square" aria-hidden="true"></i>
                                </div>
                                <div class="single_speciality_text">
                                    <h5>No joining or activation fee</h5>
                                </div>
                            </div>
                        </div>
                        <!-- Single Speciality Area Start -->
                        <div class="col-xs-12 col-sm-6 wow fadeInUp" data-wow-delay=".2s">
                            <div class="single_speciality">
                                <div class="single_speciality_icon">
                                    <i class="fa fa-check-square" aria-hidden="true"></i>
                                </div>
                                <div class="single_speciality_text">
                                    <h5>Full Control</h5>
                                </div>
                            </div>
                        </div>
                        <!-- Single Speciality Area Start -->
                        <div class="col-xs-12 col-sm-6 wow fadeInUp" data-wow-delay=".3s">
                            <div class="single_speciality">
                                <div class="single_speciality_icon">
                                    <i class="fa fa-check-square" aria-hidden="true"></i>
                                </div>
                                <div class="single_speciality_text">
                                    <h5>Token Integration</h5>
                                </div>
                            </div>
                        </div>
                        <!-- Single Speciality Area Start -->
                        <div class="col-xs-12 col-sm-6 wow fadeInUp" data-wow-delay=".4s">
                            <div class="single_speciality">
                                <div class="single_speciality_icon">
                                    <i class="fa fa-check-square" aria-hidden="true"></i>
                                </div>
                                <div class="single_speciality_text">
                                    <h5>Interactive User Interface</h5>
                                </div>
                            </div>
                        </div>
                        <!-- Single Speciality Area Start -->
                        <div class="col-xs-12 col-sm-6 wow fadeInUp" data-wow-delay=".5s">
                            <div class="single_speciality">
                                <div class="single_speciality_icon">
                                    <i class="fa fa-check-square" aria-hidden="true"></i>
                                </div>
                                <div class="single_speciality_text">
                                    <h5>Instant Investment Return</h5>
                                </div>
                            </div>
                        </div>
                        <!-- Single Speciality Area Start -->
                        <div class="col-xs-12 col-sm-6 wow fadeInUp" data-wow-delay=".6s">
                            <div class="single_speciality">
                                <div class="single_speciality_icon">
                                    <i class="fa fa-check-square" aria-hidden="true"></i>
                                </div>
                                <div class="single_speciality_text">
                                    <h5>Awesome Unique Design</h5>
                                </div>
                            </div>
                        </div>
                        <!-- Single Speciality Area Start -->
                        <div class="col-xs-12 col-sm-6 wow fadeInUp" data-wow-delay=".7s">
                            <div class="single_speciality">
                                <div class="single_speciality_icon">
                                    <i class="fa fa-check-square" aria-hidden="true"></i>
                                </div>
                                <div class="single_speciality_text">
                                    <h5>Rewards on Activeness</h5>
                                </div>
                            </div>
                        </div>
                        <!-- Single Speciality Area Start -->
                        <div class="col-xs-12 col-sm-6 wow fadeInUp" data-wow-delay=".8s">
                            <div class="single_speciality">
                                <div class="single_speciality_icon">
                                    <i class="fa fa-check-square" aria-hidden="true"></i>
                                </div>
                                <div class="single_speciality_text">
                                    <h5>Easy to Use</h5>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end. container -->
    </section>
    <!-- ************** Our Speciality Area End ************** -->

    <!-- ***************** Price and Plans Area Start ***************** -->
    <div class="price_plan_area section_padding_100_70" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <!-- Section Heading Start -->
                    <div class="section_heading">
                        <h3>Contact Us</h3>
                    </div>
                    <!-- Section Heading End -->
                </div>
            </div>

            <div class="col-md-12">
                <form class="form-horizontal form-bordered" action="mailto:{{ env('SITE_EMAIL')}}" method="post" >
                    {{csrf_field()}}
                    <div class="form-group">
                        <label class="control-label col-md-3"> </label>
                        <div class="col-md-6">
                            <input type="email" name="email" class="form-control" placeholder="Enter your email" required="required">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3"> </label>
                        <div class="col-md-6">
                            <input type="text" name="subject" class="form-control" placeholder="Subject" required="required">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3"> </label>
                        <div class="col-md-6">
                            <textarea name="body" rows="8" class="form-control" placeholder="Message" required="required"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                    <label class="control-label col-md-3"></label>
                        <div class="col-md-6">
                            <button class="btn btn-success btn-block">Submit</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <!-- ***************** Price and Plans Area End ***************** -->

    <!-- ************** Call to action Area Start ************** -->
    <div class="call_to_action section_padding_60">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <!-- call to action text -->
                    <div class="call_to wow fadeInUp" data-wow-delay=".2s">
                        <h3>We provide the best donation services.</h3>
                        <div class="call_to_action_button">
                            <a class="btn btn-default" href="{{url('register')}}" role="button">Join Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ************** Call to action Area End ************** -->

    <!-- ************** Footer Area Start ************** -->
    <footer class="footer_area">
        <!-- Bottom Footer Area Start -->
        <div class="footer_bottom_area">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="footer_bottom wow fadeInDown" data-wow-delay=".2s">
                            <p>Copyright &copy; 2017 {{env('SITE_NAME')}} | <a href="{{url('tos')}}">Terms &amp; Conditions</a> | <a href="mailto:{{env('SITE_EMAIL')}}" class="">{{env('SITE_EMAIL')}}</a></p>
                        </div>
                        <!-- Bottom Footer Copywrite Text Area End -->
                    </div>
                </div>
                <!-- end./ row -->
            </div>
            <!-- end./ container -->
        </div>
        <!-- Bottom Footer Area End -->
    </footer>
    <!-- ************** Footer Area End ************** -->

    <!-- ************** All jQuery Plugins ************** -->

    <!-- jQuery (necessary for all JavaScript plugins) -->
    <script src="js/jquery-2.2.4.min.js"></script>

    <!-- Bootstrap js -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Waypoint js -->
    <script src="js/jquery.waypoints.min.js"></script>

    <!-- Owl-carousel js -->
    <script src="js/owl.carousel.min.js"></script>

    <!-- Ajax Contact js -->
    <script src="js/ajax-contact.js"></script>

    <!-- Meanmenu js -->
    <script src="js/meanmenu.js"></script>

    <!-- Onepage Nav js -->
    <script src="js/jquery.nav.min.js"></script>

    <!-- Magnific Popup js -->
    <script src="js/jquery.magnific-popup.min.js"></script>

    <!-- Counterup js -->
    <script src="js/counterup.min.js"></script>

    <!-- Back to top js -->
    <script src="js/jquery.scrollUp.js"></script>

    <!-- jQuery easing js -->
    <script src="js/jquery.easing.1.3.js"></script>

    <!-- Sticky js -->
    <script src="js/jquery.sticky.js"></script>

    <!-- WOW js -->
    <script src="js/wow.min.js"></script>

    <!-- parallux js -->
    <script src="js/jquery.stellar.min.js"></script>

    <!-- Active js -->
    <script src="js/custom.js"></script>

</body>

</html>