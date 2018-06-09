<script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>
<style>
	@media only screen and (min-device-width : 300px) and (max-device-width : 1024px)
{
	
		.text_box {
    border: 1px solid rgb(195, 195, 195);
    border-radius: 2px;
    line-height: 30px;
    margin: 10px 0 10px;
    padding-left: 5px;
    width: 100%;
} }
</style>

<div class="container-fluid padding_zero">

    <div class="row top-sp car_view_manag padding_zero">
    	<div class="col-md-10 col-sm-12 col-xs-12">
    		
    		
<?php
	 
	 if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
?>

	 <h2 class="page-header"><?php echo 'Site Settings'; ?></h2>
	 </div>
	
		
<form id="myform" action="<?php echo base_url('administrator/settings'); ?>" method="post" enctype="multipart/form-data" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">	
<input type="hidden" name="setting_id" value="">
    	
    	<div class="col-md-10 col-sm-12 col-xs-12 car_view_manag">
    	<div class="col-md-2 col-sm-4 col-xs-12   text_box_cont text-box1"style="position: relative;top: 12px;left: 40px">
			<?php echo 'Site Title'; ?><span style="color:#FF0000">*</span></div>
    	<div class="col-md-10 col-sm-8 col-xs-12">
			<input class="text_box" type="text" size="55" name="site_title" value="<?php if(isset($sitetitle)) echo $sitetitle; ?>">
		</div>
		<div class="col-md-2 col-sm-4 col-xs-12   text_box_cont text-box1"style="position: relative;top: 12px;left: 38px">	
		<?php echo 'Site Slogan'; ?><span style="color:#FF0000">*</span></div>
		<div class="col-md-10 col-sm-8 col-xs-12">
			<input class="text_box" type="text" size="55" name="site_slogan" value="<?php if(isset($sitelogan)) echo $sitelogan; ?>">		
		</div>
		<div class="col-md-2 col-sm-4 col-xs-12   text_box_cont text-box1"style="position: relative;top: 12px;left: 38px">
			<?php echo 'Admin Email'; ?><span style="color:#FF0000">*</span></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
			<input class="text_box" type="text" size="55" name="super_admin" value="<?php if(isset($admin_email)) echo $admin_email; ?>">
			</div>
			<div class="col-md-2 col-sm-4 col-xs-12   text_box_cont text-box1"style="position: relative;top: 5px;left: 38px">
			<?php echo 'Admin Mobile No'; ?><span style="color:#FF0000">*</span></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
			<input class="text_box" type="text" size="55" name="super_no" value="<?php if(isset($admin_no)) echo $admin_no; ?>">
			</div>    
			
			<div class="col-md-2 col-sm-4 col-xs-12   text_box_cont text-box1"style="position: relative;top:7px;left:36px;">
			<?php echo 'Change Logo'; ?><span style="color:#FF0000">*</span></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
			<input style="margin:10px 0px 0px 20px;" id="new_photo_image" name="logo"  size="24" type="file" /></div>
<!--<p id="img_logo"> <img src="<?php echo base_url('logo/'.$image_name); ?>" alt="logo" height="50" width="50"></p>!-->


		<div class="clearfix">
			<div class="col-md-2 col-sm-4 col-xs-12"></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
		<input class="btn-default upd-but" type="submit" name="update" value="<?php echo 'Update'; ?>" /></div>
		</div>
		</div>	
		</div>	
		<span style="float:left;"><div id="message"></div></span>
		</div>


<?php echo form_close(); ?>



<style>
	.error_msg {
    color: red;
}
</style>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
 	$("#myform").validate({
        rules: 
	        {
				 site_title:
				 {
				  required :true,
				 },
				 site_slogan:{
				 	required :true,
				 },
				  super_admin:{
				 	required :true,
				 	email:true,
				 },
				  
				 super_no:{
				 	required: true,
                	digits : true,
                	maxlength : 15,
					minlength : 10  
				 },
				
				 logo:
								 {
									 required:true,
								 },
				
				 
			},
		 messages:
		    {
				site_title:
				{
				  required:"Please enter title",
				},
				site_slogan:{
				 	required :"Please enter site slogan",
				},
				super_admin:{
				 	required :"Please enter super admim mail",
				 },
				 super_no:{
				 	required :"Please enter valid admim mobile no.",
				 }
				
		    },
    errorClass:'error_msg',
    errorElement: 'div',
    errorPlacement: function(error, element)
    {
        error.appendTo(element.parent());
    }
    });
  });					
				 
</script>