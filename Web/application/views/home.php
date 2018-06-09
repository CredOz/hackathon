<!DOCTYPE html>
<html class="load-full-screen"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="LimpidThemes">
	
	<title>Arcane 3.1</title>
	
    <!-- STYLES -->
	<link href="css_home/animate.css" rel="stylesheet">
	<link href="css_home/bootstrap-select.css" rel="stylesheet">
	<link href="css_home/owl.css" rel="stylesheet">
	<link href="css_home/owl-carousel-theme.css" rel="stylesheet">
    <link href="css_home/bootstrap.css" rel="stylesheet" media="screen">
	<link href="css_home/flexslider.css" rel="stylesheet" media="screen">
	<link href="css_home/style.css" rel="stylesheet" media="screen">
	<!-- LIGHT -->
	<link rel="stylesheet" type="text/css" href="css_home/dummy.html" id="select-style">
	<link href="css_home/font-awesome.css" rel="stylesheet">
	
	<link href="css_home/light.css" rel="stylesheet" media="screen">
	
	<link href="css_home/css.css" rel="stylesheet" type="text/css">

</head>
<body class="load-full-screen"><div style="display: block;" class="quality" id="supersized">
	<a class="prevslide" style="display: block; opacity: 1;" target="_blank">
		<img style="width: 1427px; height: 956.09px; left: 0px; top: -113.5px;" src="image_home/car-slide1.jpg"></a>
		<a class="activeslide" style="display: block; opacity: 1;" target="_blank">
			<img style="width: 1427px; height: 941.82px; left: 0px; top: -106.5px;" src="image_home/car-slide2.jpg"></a>
			<a target="_blank"><img style="width: 1427px; height: 956.09px; left: 0px; top: -113.5px;" src="image_home/car-slide1.jpg"></a></div>

<!-- BEGIN: PRELOADER -->
<div style="display: none;" id="loader" class="load-full-screen">
	<div class="loading-animation">
		<span><i class="fa fa-plane"></i></span>
		<span><i class="fa fa-bed"></i></span>
		<span><i class="fa fa-ship"></i></span>
		<span><i class="fa fa-suitcase"></i></span>
	</div>
</div>
<!-- END: PRELOADER -->
<!-- BEGIN: SITE-WRAPPER -->
<div class="site-wrapper">
	
	<div class="clearfix"></div>
	<div class="row transparent-menu">
		<div class="clear-padding">
			<!-- BEGIN: HEADER -->
			<div class="navbar-wrapper">
				<div class="navbar navbar-default" role="navigation">
					<!-- BEGIN: NAV-CONTAINER -->
					<div class="nav-container">
						<div class="navbar-header">
							<!-- BEGIN: TOGGLE BUTTON (RESPONSIVE)-->
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							
							
							<!-- BEGIN: LOGO -->
							<div class="site_logo">
								<a class="logo" href="<?php echo base_url();?>"><img class="navbar-brand logo" src="<?php echo base_url();?>image_home/logo.png"/>3.1</a>
							</div>
						</div>
						
						<!-- BEGIN: NAVIGATION -->  
						<!--     
						<div class="navbar-collapse collapse">
							<ul class="nav navbar-nav navbar-right">
								<li class="dropdown">
									<a class="transition-effect dropdown-toggle log_text" href="#" data-toggle="dropdown"><i class="fa fa-sign-in"></i></i>Log In</a>
										<ul class="dropdown-menu drop_menu">
										<li class="login_rd" id="rider_login"><a class="transition-effect" onclick="window.location='#riderloginScreen'">Login Rider</a></li>
										<li class="login_rd" id="driver_login"><a class="transition-effect" onclick="window.location='#driverloginScreen'">Login Driver</a></li>
									</ul>
								</li>
							</ul>
						</div>
							-->
						<!-- END: NAVIGATION -->
				
					</div>
					<!--END: NAV-CONTAINER -->
				</div>
			</div>
			<!-- END: HEADER -->
		</div>
	</div>
	<!-- BEGIN: SEARCH SECTION -->
	<div class="row banner_content">
		<div class="container">
			<div class="col-md-8 col-sm-6 text-center">
				<div>
					<div class="hotel-tagline text-center">
						<h3>Arcane 3.1</h3>
						<h1>There is Always a Guide For Your Travel</h1>
					</div>
				</div>
			</div>
			
			<!--rider login open-->
				<div class="room-check col-md-4 col-sm-6" id="riderloginScreen" style="display: none">
					<h4 class="text-center">Login Rider</h4>
					<div class="room-check-body">
						<form>
							
								<input onclick='riderlogin();' type="submit" class="facebook input-group" value="Connect with Facebook">
							
								<input id="customBtn" onclick="javascript:googleAuth()" type="button" class="customGPlusSignIn input-group" value="Sign in With Google Plus">
							<div class="Sign_Or_Row">
							<span style="text-align: center; color: #000; font-size:15px;">Or</span>
							</div>
							<div class="input-group text_conten">
								<form id="myform" method="post" action="<?php echo base_url('templates/home/riderlogin'); ?>">
				<p style="color: #000; font-size:15px;">Email
				<input class="form-control email-txt" type="email" placeholder="Email Address" id="ridermailid" name="ridermailid" value=""></p>
				<p style="color: #000 font-size:15px;">Password
				<input class="form-control email-txt" type="password" placeholder="Password" id="riderpasswd" name="riderpasswd" value=""></p>
				<input type="checkbox">&#160;&#160;&#160;<span class="check-txt" >Remember Me</span>
				<div class="text-center sub_btn"><input class="signin-but" type="submit" value="Submit" id="btnSubmit" name="btnSubmit" onClick="pasuser(this.form)"></div>
				<p style="color:#1FBAD6; text-align: center; font-size:15px;"><a href="<?php echo base_url().'templates/home/riderforgotpasswd'; ?>">Forgot Password</a></p>
				
				<?php
		
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo '<span style="color:red">'.$msg.'</span>';
		}
	  ?>
				
				</form>
							</div>
						</form>
					</div>
				</div>
				
				<!--rider login end-->
				
			<!--driver login open-->	
				<div class="room-check col-md-4 col-sm-6" id="driverloginScreen" style="display: none">
					<h4 class="text-center drive_log">Login Driver</h4>
					<div class="room-check-body">
						<form>
							
								<input onclick='riderlogin();' type="submit" class="facebook input-group" value="Connect with Facebook">
							
								<input  id="customBtn" onclick="javascript:googleAuth()" type="button" class="customGPlusSignIn input-group" value="Sign in With Google Plus">
							<div class="Sign_Or_Row">
							<span style="text-align: center; color: #000; font-size:15px;">Or</span>
							</div>
							<div class="input-group text_conten">
								<form id="myform" method="post" action="<?php echo base_url('templates/home/riderlogin'); ?>">
				<p style="color: #000; font-size:15px;">Email
				<input class="form-control email-txt" type="email" placeholder="Email Address" id="ridermailid" name="ridermailid" value=""></p>
				<p style="color: #000; font-size:15px;">Password
				<input class="form-control email-txt" type="password" placeholder="Password" id="riderpasswd" name="riderpasswd" value=""></p>
				<input type="checkbox">&#160;&#160;&#160;<span class="check-txt" >Remember Me</span>
				<div class="text-center sub_btn"><input class="signin-but" type="submit" value="Submit" id="btnSubmit" name="btnSubmit" onClick="pasuser(this.form)"></div>
				<p style="color:#1FBAD6; text-align: center; font-size:15px;"><a href="<?php echo base_url().'templates/home/riderforgotpasswd'; ?>">Forgot Password</a></p>
				
				<?php
		
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo '<span style="color:red">'.$msg.'</span>';
		}
	  ?>
				
				</form>
							</div>
						</form>
					</div>
				</div>
				<!--driver login end-->
		</div>
	</div>
	<!-- END: SEARCH SECTION -->
<!-- START: HOW IT WORK -->
<section id="how-it-work">
		<div class="row work-row">
			<div class="container">
				<div class="section-title text-center">
					<h2>HOW IT WORKS?</h2>
					<h4>REQUEST - RIDE - PAY AND GO</h4>
					<div class="space"></div>
					<p>
						Lorem Ipsum is simply dummy text. Lorem Ipsum is simply dummy text of the printing and typesetting industry.<br>
						Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
					</p>
				</div>
				<div class="work-step">
					<div class="col-md-4 col-sm-4 first-step text-center">
						<i class="fa fa-search"></i>
						<h5>Request</h5>
						<p>Tap each ride option to to see wait time, size, and price. Then enter your pickup location and tap request—your driver will arrive in minutes.</p>
					</div>
					<div class="col-md-4 col-sm-4 second-step text-center">
						<i class="fa fa-heart-o"></i>
						<h5>Ride</h5>
						<p>Your ride comes to you. You’ll see your driver’s contact information and vehicle details in the app, so you know you’re getting in the right car.</p>
					</div>
					<div class="col-md-4 col-sm-4 third-step text-center">
						<i class="fa fa-shopping-cart"></i>
						<h5>Pay and go</h5>
						<p>Hop out and rate your driver when you reach your destination. We automatically charge the credit card on file, so you never need cash.</p>
					</div>
				</div>
			</div>
		</div>
</section>
<!-- END: HOW IT WORK -->

<!-- BEGIN: TOP DESTINATION --->
<section id="top-tour-row">
		<div class="row sm-footer back_img_cont">
			<div class="container clear-padding">
				<div class="col-md-4 col-sm-4 col-xs-10 car1_details">
					<h2>Gets you there faster<br>
					The new app</h2></br></br>
					<p class="car_app_details" style="color: #fff">
						The updated Arcane 3.1 app is rolling out now to cities around the world. And it’s filled with features that get you where you’re going faster and easier.
					</p>
				</div>
			</div>
		</div>
</section>
<!-- END: TOP DESTINATION --->
<!-- START: HOTEL GALLERY -
<section id="hotel-gallery">
	<div class="row flex-row">
		<div class="col-lg-5 col-md-5 col-sm-5 hotel-gallery-desc flex-item">
			<div class="section-title">
				<h2>Choice is a Beautiful Think</h2>
				<h4>Get a ride that matches your style and budget</h4>
			</div>
			<div class="tour-gallery">
				<p><i class="fa fa-clock-o"></i><strong>Base Rate: </strong>$5</p>
				<p><i class="fa fa-tint"></i><strong>Per Mile/KM: </strong>$3</p>
				<p><i class="fa fa-tint"></i><strong>Class: </strong>Sedan</p>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="col-lg-7 col-md-7 col-sm-7 clear-padding flex-item hotel-gallery-img">
			<div id="hotel-images" class="carousel slide" data-ride="carousel">
					<ol class="carousel-indicators">
						<li data-target="#hotel-images" data-slide-to="0" class="active"></li>
						<li class="" data-target="#hotel-images" data-slide-to="1"></li>
					</ol>
					<div class="carousel-inner" role="listbox">
						<div class="item next left">
							<img src="image_home/car-gallery.jpg" alt="Cruise">
						</div>
						<div class="item active left">
							<img src="image_home/car-gallery1.jpg" alt="Cruise">
						</div>
					</div>
					<a class="left carousel-control" href="#hotel-images" role="button" data-slide="prev">
						<span class="fa fa-chevron-left" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#hotel-images" role="button" data-slide="next">
						<span class="fa fa-chevron-right" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
			</div>
		</div>
	</div>
</section>
 END: HOTEL GALLERY -->

<!-- BEGIN: SUBSCRIBE SECTION -->
<section id="subscribe">
	<div class="row subscribe-row" id="contact">
			<div class="container text-center">
				<div class="section-title">
					<h2>GET IN TOUCH</h2>
					<h4>SUBSCRIBE</h4>
					<div class="space"></div>
					<p>
						Lorem Ipsum is simply dummy text. Lorem Ipsum is simply dummy text of the printing and typesetting industry.<br>
						Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
					</p>
				</div>
				<div class="col-md-8 col-md-offset-2 subscribe-box">
						<!-- CONTACT FORM-->
						<div class="row" style="float: none;margin: 0 auto;max-width: 750px;">

							
							<form role="form" id="user_edit" method="POST" action="<?php echo base_url('home/add_contact_us') ;?>" class="contact-form" data-form-processed="true">

								<input name="scrollPosition" type="hidden">

								<input name="submitted" id="submitted" value="true" type="hidden">
								
								<div class="col-lg-5 col-sm-5">

									<div class="col-xs-12 zerif-rtl-contact-name" data-scrollreveal="enter left after 0s over 1s" data-sr-init="true" data-sr-complete="true">
										<!-- <label for="myname" class="screen-reader-text">Your Name</label> -->
										<input required="required" name="firstname" id="firstname" placeholder="Your Name" class="form-control input-box" type="text">
									</div>
	
									<div class="col-xs-12 zerif-rtl-contact-email" data-scrollreveal="enter left after 0s over 1s" data-sr-init="true" data-sr-complete="true">
										<!-- <label for="myemail" class="screen-reader-text">Your Email</label> -->
										<input required="required" name="mailid" id="mailid" placeholder="Your Email" class="form-control input-box" type="email">
									</div>
	
									<div class="col-xs-12 zerif-rtl-contact-subject" data-scrollreveal="enter left after 0s over 1s" data-sr-init="true" data-sr-complete="true">
										<!-- <label for="mysubject" class="screen-reader-text">Subject</label> -->
										<input required="required" name="subject" id="subject" placeholder="Subject" class="form-control input-box" type="text">
									</div>
								
								</div>
								
								<div class="col-lg-7 col-sm-7">

									<div class="col-lg-12 col-sm-12" data-scrollreveal="enter right after 0s over 1s" data-sr-init="true" data-sr-complete="true">
										<!-- <label for="mymessage" class="screen-reader-text">Your Message</label> -->
										<textarea rows="4" cols="50" required="required" name="mymessage" id="mymessage" class="form-control textarea-box" placeholder="Your Message"></textarea>
										<!-- <grammarly-btn><div style="z-index: 2; opacity: 1; transform: translate(1094px, 219px);" class="_e725ae-textarea_btn _e725ae-not_focused" data-grammarly-reactid=".0"><div class="_e725ae-transform_wrap" data-grammarly-reactid=".0.0"><div title="Protected by Grammarly" class="_e725ae-status" data-grammarly-reactid=".0.0.0">&nbsp;</div></div></div></grammarly-btn> -->
										<button class="btn btn-primary custom-button red-btn" type="submit" data-scrollreveal="enter left after 0s over 1s" data-sr-init="true" data-sr-complete="true">Send Message</button>
									</div>
									
								</div>

								
								
							</form>

						</div>

						<!-- / END CONTACT FORM-->
				</div>
			</div>
	</div>
</section>
<!-- END: SUBSCRIBE SECTION -->
<!-- START: WHY CHOOSE US SECTION -->
<section id="why-choose-us">
	<div class="row choose-us-row">
		<div class="container clear-padding">
			<div class="light-section-title text-center">
				<h2>WHY CHOOSE US?</h2>
				<h4>REASONS TO TRUST US</h4>
				<div class="space"></div>
				
			</div>
			<div style="visibility: visible; animation-name: slideInLeft;" class="col-md-4 col-sm-4 wow slideInLeft">
				<div class="choose-us-item text-center">
					<div class="choose-icon"><img class="img-icon" src="<?php echo base_url();?>css_home/images/lock.png"/></div>
					<h2>Secure Booking</h2>
					<h4>We ensure safest booking!</h4>
					<p>Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornore odio. Sed non mauris vitae erat conuat auctor eu in elit.</p>
					<a href="#">KNOW MORE</a>
				</div>
			</div>
			<div style="visibility: visible; animation-name: slideInUp;" class="col-md-4 col-sm-4 wow slideInUp">
				<div class="choose-us-item text-center">
					<div class="choose-icon"><img class="img-icon" src="<?php echo base_url();?>css_home/images/hand-holding-up-a-torch.png"/></div>
					<h2>Reliable Service</h2>
					<h4>We ensure safest booking!</h4>
					<p>Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non mauris vitae erat conuat auctor eu in elit.</p>
					<a href="#">KNOW MORE</a>
				</div>
			</div>
			<div style="visibility: visible; animation-name: slideInRight;" class="col-md-4 col-sm-4 wow slideInRight">
				<div class="choose-us-item text-center">
					<div class="choose-icon"><img class="img-icon" src="<?php echo base_url();?>css_home/images/operator.png"/></div>
					<h2>Customer Service</h2>
					<h4>We ensure safest booking!</h4>
					<p>Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non mauris vitae erat conuat auctor eu in elit.</p>
					<a href="#">KNOW MORE</a>
				</div>
			</div>
			<div style="visibility: visible; animation-name: slideInLeft;" class="col-md-offset-4 col-md-4 col-sm-offset-4 col-sm-4 wow slideInLeft">
				<div class="choose-us-item text-center">
					<div class="choose-icon"><img class="img-icon" src="<?php echo base_url();?>css_home/images/earth-globe.png"/></div>
					<h2>No Hidden Charges</h2>
					<h4>We ensure safest booking!</h4>
					<p>Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornore odio. Sed non mauris vitae erat conuat auctor eu in elit.</p>
					<a href="#">KNOW MORE</a>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END: WHY CHOOSE US SECTION -->


<!-- START: FOOTER -->
<section id="footer">
	<footer>
		
		<div class="clearfix"></div>
		<div class="row sm-footer-nav text-center">
			<p>
				<p class="social-media">
						<a href="https://www.facebook.com/cogzidel" target="_blank"><i class="fa fa-facebook"></i></a>
						<a href="https://twitter.com/cogzideltech" target="_blank"><i class="fa fa-twitter"></i></a>
						<a href="https://plus.google.com/+Cogzidel" target="_blank"><i class="fa fa-google-plus"></i></a>
						<a href="http://www.youtube.com/results?search_query=cogzidel" target="_blank"><i class="fa fa-instagram"></i></a>
					</p>
			</p>
			<p class="copyright"> <a href="http://www.cogzidel.com/" target="_blank">Cogzidel Technologies</a>
<a href="http://www.cogzidel.com/uber-clone/" target="_blank"> Arcane Software </a> is licensed under the <a style="color:white;" href="https://opensource.org/licenses/mit-license.php" target="_blank">MIT License</a>
</div></p>
		<div class="go-up">
				<a href="#"><i class="fa fa-arrow-up"></i></a>
			</div>
		</div>
	</footer>
</section>
<!-- END: FOOTER -->

</div>
<!-- END: SITE-WRAPPER -->
<!-- Load Scripts -->
<script src="js_home/analytics.js" async=""></script>
<script src="js_home/respond.js"></script>
<script src="js_home/jquery.js"></script>
<script src="js_home/owl.js"></script>
<script src="js_home/bootstrap.js"></script>
<script src="js_home/jquery-ui.js"></script>
<script src="js_home/bootstrap-select.js"></script>
<script src="js_home/wow.js"></script>
<script type="text/javascript" src="js_home/supersized.js"></script>
<script src="js_home/js.js"></script>
<script type="text/javascript">  
			
			jQuery(function($){
				"use strict";
				$.supersized({
				
					//Functionality
					slideshow               :   1,		//Slideshow on/off
					autoplay				:	1,		//Slideshow starts playing automatically
					start_slide             :   1,		//Start slide (0 is random)
					random					: 	0,		//Randomize slide order (Ignores start slide)
					slide_interval          :   10000,	//Length between transitions
					transition              :   1, 		//0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
					transition_speed		:	500,	//Speed of transition
					new_window				:	1,		//Image links open in new window/tab
					pause_hover             :   0,		//Pause slideshow on hover
					keyboard_nav            :   0,		//Keyboard navigation on/off
					performance				:	1,		//0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
					image_protect			:	1,		//Disables image dragging and right click with Javascript

					//Size & Position
					min_width		        :   0,		//Min width allowed (in pixels)
					min_height		        :   0,		//Min height allowed (in pixels)
					vertical_center         :   1,		//Vertically center background
					horizontal_center       :   1,		//Horizontally center background
					fit_portrait         	:   1,		//Portrait images will not exceed browser height
					fit_landscape			:   0,		//Landscape images will not exceed browser width
					
					//Components
					navigation              :   1,		//Slideshow controls on/off
					thumbnail_navigation    :   1,		//Thumbnail navigation
					slide_counter           :   1,		//Display slide numbers
					slide_captions          :   1,		//Slide caption (Pull from "title" in slides array)
					slides 					:  	[		//Slideshow Images 
														{image : 'image_home/car-slide1.jpg', title : 'Slide 2'},
														{image : 'image_home/car-slide2.jpg', title : 'Slide 1'}
												]
												
				}); 
		    });
		    
</script>
<script>
  $("#rider_login").click(function() {
        $("#riderloginScreen").show();
        $("#driverloginScreen").hide();
     });
     
     $("#driver_login").click(function() {
        $("#driverloginScreen").show();
        $("#riderloginScreen").hide();
     });
</script>
<script>
$(document).ready(function(){ 
    $(window).scroll(function(){ 
        if ($(this).scrollTop() > 200) { 
            $('.go-up').fadeIn(); 
        } else { 
            $('.go-up').fadeOut(); 
        } 
    }); 
    $('go-up').click(function(){ 
        $("html, body").animate({ scrollTop: 0 }, 1100); 
        return false; 
    }); 
});
</script>
<script type="text/javascript">
 
	$(document).ready(function(){
		
		$(".close").click(function()
		{
			$("#riderloginScreen").removeClass('in');
			$("#riderloginScreen").css('display','none');
		})
		
		$(".close").click(function()
		{
			$("#driverloginScreen").removeClass('in');
			$("#driverloginScreen").css('display','none');
		})
		
	});
</script>
<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-68058832-1', 'auto');
		  ga('send', 'pageview');

	</script>

<div id="ui-datepicker-div" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>
<canvas style="display: none;" height="729" width="1427"></canvas><canvas style="display: none;" height="6515" width="1427"></canvas></body></html>