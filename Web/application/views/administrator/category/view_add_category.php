 
 <script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>
 
			
<div class="container-fluid">

    <div class="row top-sp">
    	<div class="col-md-10 col-sm-8 col-xs-12">
    		 <?php
	 
	 if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
?>

	 <h2 class="page-header"><?php echo 'Add Category'; ?></h2></div>
	 
	
	 
<form action="<?php echo base_url('/administrator/category/addcategory'); ?>" id="myform" method="post">

<div class="col-md-10 col-sm-8 col-xs-12">
<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">
<?php echo "Additional Category"; ?><span style="color:#FF0000">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="text_box" type="text" name="addcategory" value=""></div>

<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 10px;">
<?php echo "Price per Km"; ?><span style="color:#FF0000">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="text_box" type="text" name="price_km" value=""></div>

<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">
<?php echo "Price per Minute"; ?><span style="color:#FF0000">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="text_box" type="text" name="price_minute" value=""></div>

<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 5px;">
<?php echo "Minimum fare amount"; ?><span style="color:#FF0000">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="text_box" type="text" name="price_fare" value=""></div>

<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 5px;">
<?php echo "Prime time percentage"; ?><span style="color:#FF0000">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="text_box" type="text" name="prime_time_precentage" value=""></div>

<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 10px;">
<?php echo "Maximum size"; ?><span style="color:#FF0000">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="text_box" type="text" name="max_size" value=""></div>


<div class="col-md-2 col-sm-4 col-xs-12"></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="btn-default" type="submit" name="update_price" value="<?php echo "Add"; ?>" style="width:90px;" /></div>
</form>
</div>
</div>
</div>
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
				 'addcategory':
				 {
				  required :true,
				 },
				 'price_km':
				{
				   required :true,
				   number: true
				},
				'price_minute':
				{
				   required :true,
				   number: true
				},
				'prime_price_km':
				{
				   required :true,
				   number: true
				},
				'prime_price_minute':
				{
				   required :true,
				   number: true
				},
				'prime_time_precentage':
				{
				   required :true,
				   number: true
				},
				'max_size':
				{
				   required :true,
				   number: true
				},
				'price_fare':
				{
				   required :true,
				   number: true
				}
						
				 
			},
		 messages:
		    {
				'addcategory':
				{
				  required:"Please enter category",
				},
				'price_km':
				{
				  required:"Please enter price per kilometer"
				},
				'price_minute':
				{
				  required:"Please enter price per minute"
				},
				'prime_price_km':
				{
				  required:"Please enter prime price per kilometer"
				},
				
				'prime_time_precentage':
				{
				  required:"Please enter prime price per minute"
				},
				
				'max_size':
				{
				  required:"Please enter Maximum size"
				},
				'price_fare':
				{
					  required:"Please enter minimum fare amount"
				}
				
		    },
    errorClass:'error_msg',
    errorElement: 'div',
    errorPlacement: function(error, element)
    {
        error.appendTo(element.parent());
    },
   
    submitHandler: function()
    {
        document.myform.submit();
    }
    });
  });					
				 
</script>
