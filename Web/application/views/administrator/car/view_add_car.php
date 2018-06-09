<div class="container-fluid">

    <div class="row top-sp">
    	<div class="col-md-10 col-sm-8 col-xs-12">
    		<?php
	 
	 if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
?>

 <script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>
 			

	 <h2 class="page-header"><?php echo 'Add Car'; ?></h2>
	 </div>
	
	 
<form action="<?php echo base_url('/administrator/car/check'); ?>" id="myform" method="post">

<div class="col-md-10 col-sm-8 col-xs-12">

<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 10px;">
<?php echo "Car Name"; ?><span style="color:#FF0000">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="text_box" type="text" name="addcar" value=""></div>

<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 10px;">
<?php echo 'Category'; ?><span style="color: red;">*</span></label></div>
		<div class="col-md-10 col-sm-8 col-xs-12">
			<select class="select" name='category'>
				<?php foreach($category as $row)
				{
					echo '<option value="'.$row['categoryname'].'">'.$row['categoryname'].'</option>';
				}
				?>
				</select>
				<?php //echo form_error('city'); ?>
		</div>

<!--
<tr>
<td class="contentwidths"><?php echo "Price per Km"; ?> <span style="color:#FF0000">*</span></td>
<td class="contentwidth"><input type="text" name="price_km" value=""></td>
</tr>
<tr>
<td class="contentwidths"><?php echo "Price per Minute"; ?> <span style="color:#FF0000">*</span></td>
<td class="contentwidth"><input type="text" name="price_minute" value=""></td>
</tr>
<tr>
<td class="contentwidths"><?php echo "Minimum fare amount"; ?> <span style="color:#FF0000">*</span></td>
<td class="contentwidth"><input type="text" name="price_fare" value=""></td>
</tr>-->


			<div class="col-md-2 col-sm-4 col-xs-12"></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
				
<input class="btn-default" type="submit" name="update_price" onclick="check()" value="<?php echo "Add"; ?>" style="width:90px;" /></div>


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
				 'addcar':
				 {
				  required :true
				 },
				 /*
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
								  'price_fare':
								  {
								   required :true,
								   number: true
								  }			*/
				 		 
			},
		 messages:
		    {
				'addcar':
				{
				  required:"Please enter Car name"
				},
				/*
				'price_km':
								{
								  required:"Please enter price per kilometer"
								},
								'price_minute':
								{
								  required:"Please enter price per minute"
								},
								'price_fare':
								{
								  required:"Please enter minimum fare amount"
								}*/
				
				
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