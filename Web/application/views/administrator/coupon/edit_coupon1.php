<script src="<?php echo base_url(); ?>js/jquery-ui.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.ui.css" />
<script>
$(function() {
$( "#expirein" ).datepicker(
	{
	 minDate: new Date(),
	           dateFormat: "dd/mm/yy",
                maxDate: "+20Y",
                nextText: "",
                prevText: "",
                numberOfMonths: 1,
                showButtonPanel: true
               }
);
});
</script>


    
<style type="text/css" media="screen">
    td
    {
         border-top: 1px solid #FFF !important; 
    }
</style>    
    

<div id="page-wrapper2" style="padding-left: 8em">
	<div id="page-inner2">
		<div class="head-pad1"style="font-size: 30px;color: #1ABB9C;font-family: " source_sans_proregular";">

	  <?php 
	  
	  	//Content of a group
		if(isset($promocode) and $promocode->count()>0)
		{  

				foreach ($promocode as $promocode) {

	  ?>
	 	<h3 class="page-header1"><?php echo 'Edit Coupon'; ?></h3></div></div>
	 	<form action="<?php echo base_url('administrator/staff_coupon/editcoupon'); ?>/<?php echo $promocode['_id'];  ?>" method="post" style="margin: -100px">
	 	<input type="hidden" name="id"  value="<?php echo $promocode['_id'];  ?>"/></td><td>
   <table cellpadding="2" cellspacing="0"style="margin: 115px 0px 0px -135px;">
   	
   	<tr>
					<td style="padding:20px 0px 0px 50px;position: relative;left: 360px"><?php echo 'Expirein'; ?><span style="color: red;"> *</span></td>
					<td style="padding:20px 0px 0px 362px;">
					<input class="" id="expirein" name="expirein" type="text"  value="<?php echo $promocode['expire_in']; ?>">
					</td>
	</tr>
	
	<tr>
					<td style="padding:20px 0px 0px 50px;position: relative;left: 360px"><?php echo 'Coupon_price'; ?><span style="color: red;"> *</span></td>
					<td style="padding:20px 0px 0px 362px;">
					<input class="" id="coupon_price" name="coupon_price" type="text"  value="<?php echo $promocode['price']; ?>">
					</td>
	</tr>
	
	<tr>
					<td style="padding:20px 0px 0px 50px;position: relative;left: 360px"><?php echo 'Coupon Code:'; ?><span style="color: red;"> *</span></td>
					<td style="padding:20px 0px 0px 362px;">
					<input class="" id="coupon_code" name="coupon_code" type="text" readonly value="<?php echo $promocode['code']; ?>">
					</td>
	</tr>
   	<tr>
			<td></td>
		<td>
		 
   <input class="btn-default" id="codegen" type="submit"  value="update" name="submit" style="position: relative;left: 360px;top: 3px;" ></td>
	  	</tr>  
        
	  </table>
	</form>
	  <?php
				}
	 }
	  ?>
    </div>
    
    