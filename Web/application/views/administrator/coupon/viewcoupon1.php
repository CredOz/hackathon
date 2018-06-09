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
function gen_code()
{

$("#gencode").val("<?php echo createRandomStringCollection();?>");

}
</script>
<div id="page-wrapper2" style="padding-left: 8em">
	<div id="page-inner2">
 <?php
	 
	 if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
?>
	</div>
	
<style type="text/css" media="screen">
	td
	{
	     border-top: 1px solid #FFF !important; 
	}
</style>	
	
			
<div class="head-pad1">
	 <h3 class="page-header1"style="font-size: 30px;color: #1ABB9C;font-family: " source_sans_proregular";text-align:="" left;"=""><?php echo 'Promo code'; ?></h3>
	 </div>


<form action="<?php echo base_url('/administrator/staff_coupon/addcoupon'); ?>" id="myform" method="post" style="margin: -100px" >
<table class="table" cellpadding="2" cellspacing="0" style="position: relative;top:0px">		
 	
<tr>
<td style="padding:20px 0px 0px 50px;"><?php echo "Expire in"; ?> <span style="color:#FF0000"> *</span></td>
<td style="margin: 0px 0px 0px 0px; padding:20px 0px 0px 8px; float: left; width: 50%;"><input type="text" name="expire_in" value="" id="expirein" class="valid_expire"></td>
</tr> 	

<tr>
<td style="padding:20px 0px 0px 50px;"><?php echo "Enter Price"; ?> <span style="color:#FF0000"> *</span></td>
<td style="margin: 0px 0px 0px 0px; padding:20px 0px 0px 8px; float: left; width: 50%;"><input type="text" name="price" value="" id="price" class="valid_expire"></td>
</tr> 	
		 		
						 
<!-- <tr> 
		<td class="contentwidth"><?php echo "Currency";?></td>
			<td><select id="currency" name="currency">
			
			<option value=""  ><?php  ?></option>
			
			}
			else
				{?>
				<option value="USD"></option>	
				<?php 
			 ?>
			</select> </td>
	</tr> -->
		<tr>
        <td style="padding:20px 0px 0px 50px;"><?php echo "Promo Code"; ?> <span style="color:#FF0000"> *</span></td>
        <td style="margin: 0px 0px 0px 0px; padding:20px 0px 0px 8px; float: left; width: 50%;"><input type="text" name="gencode" id="gencode" value="" readonly="readonly"/>
	 	
	 	
 	   <input class="clsCoupon" id="codegen" disabled="disabled" type="button" style="width:150px;" value="Generate Code" name="codegen"onclick="gen_code()">
  		<?php "gencode"?>
  		<label id="gen_code_valid" class="help" style="display:none">Please fill all above fields.</label> 
  		<label id="gen_code_char_valid" class="help" style="display:none">Please give valid price amount.</label> 
  		<label id="gen_code_price_valid" class="help" style="display:none">Please give the above or equal to <?php echo $currency_code.' '.$min_value;?>.</label> 
	 	</td>
	 	<input type="hidden" id="currency_hidden" value="">
	 </tr>		
		  
		<tr>
		<td>
		  <input  type="hidden" name="id"  value=""/></td><td>
          <input  name="submit"  class="btn-default" type="submit" value="Submit" top="2px"></td>
	  	</tr>  
        
	  </table>
	</form>
	  <?php
				
	 
	  ?>
    </div>
    <style>
	.error_msg {
    color: red;
}
</style>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js"></script>
<script type="text/javascript">

$( document ).ready(function() {
	
	$('input.valid_expire').keyup(function()
	{
		var expire=$('input#expirein').val();
		
		var price=$('input#price').val();
		//alert(price);
		 if(expire !='' && price !='')
		 { 
			  $('#codegen').removeAttr('disabled');
		 }
		 else
		 {
			 $('#codegen').attr('disabled', 'disabled'); 	
		 }
	})
	
	  
 	$("#myform").validate({
        rules: 
	        {
				 'expire_in':
				 {
				  required :true,
				 },
				 'price':
				{
				   required :true,
				   number: true
				},
				'gencode':
				{
				   required :true,
				   
				}
					
				 
			},
		 messages:
		    {
				'expire_in':
				{
				  required:"Please enter date",
				},
				'price':
				{
				  required:"Please enter price "
				},
				'gencode':
				{
				  required:" generate Promo code"
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

 
