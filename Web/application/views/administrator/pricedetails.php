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
    	<div class="col-md-12 col-sm-12 col-xs-12">
    		
    		
<?php
	 
	 if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
?>

	 <h2 class="page-header"><?php echo 'Price and Distance Management'; ?></h2>
	 </div>
	
		
<form id="myform" action="<?php echo base_url('administrator/management/pricedetails'); ?>" method="post" enctype="multipart/form-data" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">	
<input type="hidden" name="setting_id" value="">
    	
    	<div class="col-md-12 col-sm-12 col-xs-12 car_view_manag">
    	<div class="col-md-3 col-sm-4 col-xs-12 text_box_text text_box_cont">
			<?php echo 'Nearby Distance'; ?>(In KM)<span style="color:#FF0000">*</span></div>
    	<div class="col-md-9 col-sm-8 col-xs-12">
			<input class="text_box" type="text" size="55" name="distance" value="<?php if(isset($distance)) echo $distance; ?>">
		</div>
		<div class="col-md-3 col-sm-4 col-xs-12 text_box_text text_box_cont">	
		<?php echo 'Price per kilometer'; ?>(In USD)<span style="color:#FF0000">*</span></div>
		<div class="col-md-9 col-sm-8 col-xs-12">
			<input class="text_box" type="text" size="55" name="price" value="<?php if(isset($price)) echo $price; ?>">		
		</div>
		
		<div class="clearfix">
			<div class="col-md-3 col-sm-4 col-xs-12"></div>
			<div class="col-md-9 col-sm-8 col-xs-12">
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
				 distance:
				 {
				  required :true,
				  digits : true
				 }, 
				 price:{
				 	required: true,
                	digits : true
                	 
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