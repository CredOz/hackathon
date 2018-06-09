<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=10">
<meta name="title" content="<?php if(isset($title)) echo $title; else echo ""; ?>" />
<meta name="keywords" content="<?php if(isset($meta_keyword)) echo $meta_keyword; else echo ""; ?>" />
<meta name="description" content="<?php if(isset($meta_description)) echo $meta_description; else echo ""; ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="google-translate-customization" content="376d9d52f1776ee3-46f58b508c85587c-ged0a34236ce0763e-1e"></meta>
<title><?php echo "Admin Section"; ?></title>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
 <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>js/bootstrap.min.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.min.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>js/jquery.mousewheel.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>js/jquery.sparkline.min.js"></script>

 
 <script type="text/javascript" src="<?php echo base_url();?>js/main.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url();?>js/style-switcher.js"></script>-->
 <script type="text/javascript" src="<?php echo base_url();?>js/dhtmlxslider.js"></script>

 <script type="text/javascript" src="<?php echo base_url(); ?>js/sliderbar.jquery.js"></script>

 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

<!-- This JS is Used for work a Toggle Button in Responsive Mode -->
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/collapse.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/transition.js"></script>
<!-- End -->
 
 <!-- Style sheet -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/Style.css" />
<!-- End>
    <!-- Header Creation -->
     <style>
html * :not(i){
	 -webkit-font-smoothing: antialiased !important;
    -moz-osx-font-smoothing: grayscale !important;
 }
       </style>
       <body> 
      <header class="navbar navbar-static-top bs-docs-nav">
	<div class="container-fluid">
		<div class="navbar-header">
			<button class="navbar-toggle" data-target=".bs-navbar-collapse" data-toggle="collapse" type="button">
			<span class="sr-only"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<div class="head-logo">
			<a class="logo" style=";" href="<?php echo base_url();?>"><img src="<?php echo base_url();?>logo/logo.png"/></a>
		</div>
      </div>
		<nav class="collapse navbar-collapse bs-navbar-collapse">
			<ul class="nav navbar-nav navbar-right">
		
				<?php if(!check_logged()) {?>  
					<li>
					<a class="login" href="<?php echo base_url('administrator/admin/login');?>">L<small>OGIN</small></a></li>
				<?php } else { ?>
					<li>
					<a class="login" href="<?php echo base_url('administrator/admin/logout');?>">L<small>OG OUT</small></a></li> <?php } ?>

				
			</ul>
		</nav>
	</div>
	</header>

<!-- End -->
      
               
       </body>
       
    </html>   
    <script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>	