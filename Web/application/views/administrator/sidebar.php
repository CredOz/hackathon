<div style="padding: 0px" class="col-md-2 col-sm-2" >

<ul id="menu" class="unstyled accordion in collapse ">

<!--  dashboard -->

 <li class="accordion-group">
 	 <a data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#dashboard-nav" href="<?php echo base_url('administrator/admin/home'); ?>"> 
	<i class="fa fa-dashboard fav_icon" aria-hidden="true"></i> <?php echo 'Dashboard'; ?></a>
</li>  

<!--  Site Management -->

<li class="accordion-group ">
 	<a data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#site-nav" href="javascript:void(0);">
 <i class="fa fa-cogs fav_icon" aria-hidden="true"></i> <?php echo 'Site Management'; ?></a>

   <ul class="collapse " id="site-nav">
   	<li>
   		<a href="<?php echo base_url('administrator/settings'); ?>">
	<i class="icon-angle-right"></i>
   	<?php echo 'Site Settings'; ?></a>
</li>
<li>
	<a href="<?php echo base_url('administrator/settings/change_password'); ?>" >
			<i class="icon-angle-right"></i>
			<?php echo 'Change Password'; ?></a>
</li>
</ul> 
</li> 

      
<!--  Member Management -->

<li class="accordion-group ">
<a data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#form-nav" href="javascript:void(0);">
<i class="fa fa-users fav_icon" aria-hidden="true"></i>
     	<?php echo 'Member Management'; ?>
</a>   	
     <ul class="collapse" id="form-nav">
     <li>
     	<a href="<?php echo base_url('administrator/members/Rider'); ?>" >
	<i class="icon-angle-right"></i>
     	<?php echo 'Rider Management'; ?></a>
     	</li>
     	<li>
     		<li><a href="<?php echo base_url('administrator/members/Driver'); ?>" >
	<i class="icon-angle-right"></i>
     		<?php echo 'Driver Management'; ?></a>	
     </li>		
     </ul>
</li>


<!--  Category Management -->
 <li class="accordion-group " <?php
	if ($this -> uri -> segment(2) == 'category')
		echo "selected";
 ?>">
   	<a data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#cate-nav" href="javascript:void(0);">
	<i class="fa fa-tags fav_icon"></i>
   	<?php echo 'Category Management '; ?></a>
   
    <ul class="collapse " id="cate-nav">
		 <li>
		 	<a href="<?php echo base_url('administrator/category/view_all_category'); ?>">
				<i class="icon-angle-right"></i>
				<?php echo 'View Category'; ?>
			</a>
		 </li>
     </ul>
 </li>
 
 <!--  Car Management -->
   <li class="accordion-group " <?php
	if ($this -> uri -> segment(2) == 'car')
		echo "selected";
 ?>">
    	    <a data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#car-nav" href="javascript:void(0);">
     	<i class="fa fa-car fav_icon" aria-hidden="true"></i> <?php echo 'Car Management '; ?></a>
   
    <ul id="car-nav" class="collapse">
						 <li><a href="<?php echo base_url('administrator/car/view_car'); ?>">
						<i class="icon-angle-right"></i>
						 <?php echo 'Add Car'; ?></a></li>
						 <li><a href="<?php echo base_url('administrator/car/view_all_car'); ?>">
						<i class="icon-angle-right"></i>
						 <?php echo 'View Car'; ?></a></li>
                        </ul>
      </li>
<!-- ​Social connect management (FB and Google)-->

 <li class="accordion-group">
 	 <a data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#dashboard-nav" href="<?php echo base_url('administrator/management/RequestDuration'); ?>"> 
	<i class="fa fa-share-square-o fav_icon" aria-hidden="true"></i> <?php echo 'Request Duration'; ?></a>
</li>

<!-- ​Transaction history management -->

 <li class="accordion-group">
 	 <a data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#dashboard-nav" href="<?php echo base_url('administrator/payment/transaction'); ?>"> 
	<i class="fa fa-history fav_icon" aria-hidden="true"></i> <?php echo '​Transaction History'; ?></a>
</li>

<!-- ​Map -->

 <li class="accordion-group">
 	 <a data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#dashboard-nav" href="<?php echo base_url('administrator/livemap/'); ?>"> 
	<i class="fa fa-map fav_icon" aria-hidden="true"></i> <?php echo '​Map'; ?></a>
</li>

<!-- API management (Firebase and onsignal) -->

 <li class="accordion-group">
 	 <a data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#dashboard-nav" href="<?php echo base_url('administrator/management/apiconnect'); ?>"> 
	<i class="fa fa-sitemap fav_icon" aria-hidden="true"></i> <?php echo 'API Management'; ?></a>
</li>

<li class="accordion-group">
 	 <a data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#dashboard-nav" href="<?php echo base_url('administrator/management/fileupload'); ?>"> 
	<i class="fa fa-cloud-upload fav_icon" aria-hidden="true"></i> <?php echo 'Firebase File Upload'; ?></a>
</li>

<!-- Email management (Firebase and onsignal) -->
 <li class="accordion-group ">
 	<a data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#error-nav" href="javascript:void(0);" >
 	<!-- <img width="20" height="20" src="<?php echo base_url();?>images/email.png"  />-->
	<i class="fa fa-envelope fav_icon" aria-hidden="true"></i>
 	 Email </a>
                  	 <ul class="collapse" id="error-nav">
        <li><a href="<?php echo base_url('administrator/members/add_email'); ?>">
        	<i class="icon-angle-right"></i>
        	Add Email Template</a></li>
        <li><a href="<?php echo base_url('administrator/members/manage_email'); ?>">
        	<i class="icon-angle-right"></i>
        	Manage Templates</a></li>     
  </ul>
</li>	

 <!-- Price and distance managemanent -->
 
<li class="accordion-group">
 	 <a data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#dashboard-nav" href="<?php echo base_url('administrator/management/pricedetails'); ?>"> 
	<i class="fa fa-money fav_icon" aria-hidden="true"></i> <?php echo 'Price and Distance'; ?></a>
</li>

 <!-- For Promo Code -->
 <!--
      <li class="accordion-group " <?php
	if ($this -> uri -> segment(2) == 'promocode')
		echo "selected";
 ?>">
    	    <a data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#promocode-nav" href="javascript:void(0);">
    	 	<i class="fa fa-gift fav_icon"></i> <?php echo 'Promo code '; ?></a>
   		<ul id="promocode-nav" class="collapse">
						<li><a href="<?php echo base_url('administrator/coupon/add_coupon'); ?>">
						<i class="icon-angle-right"></i>
						<?php echo 'Add Promocode'; ?></a></li>
						<li><a href="<?php echo base_url('administrator/coupon/view_all_coupon'); ?>">
						<i class="icon-angle-right"></i>
						 <?php echo 'View Promocode'; ?></a></li>
                        </ul>
      </li> 
     --> 
      
 </ul>
</div>