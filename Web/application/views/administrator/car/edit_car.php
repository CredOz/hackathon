
<div class="container-fluid">

    <div class="row top-sp">
    	<div class="col-md-10 col-sm-8 col-xs-12">
	  <?php 
	  
	  	//Content of a group
		if(isset($car) and $car->count()>0)
		{  

				foreach ($car as $car) {

	  ?>
	 	<h2 class="page-header"><?php echo 'Edit Car'; ?></h2></div>
	 	
			<form method="post" action="<?php echo base_url('administrator/car/editcar'); ?>/<?php echo $car['_id'];  ?>" id="myform" >
				 <input type="hidden" name="id"  value="<?php echo $car['_id'];  ?>"/>
   
<div class="col-md-10 col-sm-8 col-xs-12">		
<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"><?php echo 'Car Name'; ?><span style="color: red;">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="text_box" type="text" name="carname" id="carname" value="<?php echo $car['carname']; ?>">
<span style="color:red";><?php echo form_error('carname');?></span>
</div>
	<?php	//} ?>
				<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"><?php echo 'Category'; ?><span style="color: red;">*</span></div>
	<div class="col-md-10 col-sm-8 col-xs-12">
		<select class="select" name="category">
				 
				<?php 

				foreach($category as $row)
				{  
if ($row['categoryname']== $car['category']){
			$s="selected='selected'";
		}
		else{
			$s="";
		} 
					echo '<option value="'.$row['categoryname'].'"'.$s.'>'.$row['categoryname'].'</option>';
				}
				?>
				</select>
				</div>
		 
<!--
		  <tr>
<td class="contentwidths"><?php echo "Price per Km"; ?> <span style="color:#FF0000">*</span></td>
<td class="contentwidth"><input type="text" name="price_km" value="<?php echo $car['price_km']; ?>"></td>
</tr>
<tr>
<td class="contentwidths"><?php echo "Price per Minute"; ?> <span style="color:#FF0000">*</span></td>
<td class="contentwidth"><input type="text" name="price_minute" value="<?php echo $car['price_minute']; ?>"></td>
</tr>
<tr>
<td class="contentwidths"><?php echo "Minimum fare amount"; ?> <span style="color:#FF0000">*</span></td>
<td class="contentwidth"><input type="text" name="price_fare" value="<?php echo $car['price_fare']; ?>"></td>
</tr>-->

		
		<div class="col-md-2 col-sm-4 col-xs-12"></div>
			<div class="col-md-10 col-sm-8 col-xs-12"> 
   <input class="btn-default" name="submit" type="submit" value="Submit"></div>

	</form>
	  <?php
				}
	 }
	  ?>
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
				 'carname':
				 {
				  required :true
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
				 'price_fare':
				 {
				  required :true,
				  number: true
				 }					 
			},
		 messages:
		    {
				'carname':
				{
				  required:"Please enter Car name"
				},
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