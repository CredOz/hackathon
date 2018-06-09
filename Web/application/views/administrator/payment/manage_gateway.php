
<div class="container-fluid">

    <div class="row top-sp">
    	<div class="col-md-10 col-sm-8 col-xs-12">
    		<?php 
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	 ?>
	
	 <h2 class="page-header"><?php echo 'Edit Paypal GateWay'; ?></h2></div>
	 
   	 <script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>
<style>
	@media only screen and (min-device-width : 300px) and (max-device-width : 640px)
{
	.text_box_payment {
    margin: 2px 10px 6px;
}
.text_box
{
	width:210px;
}}
</style>
  	
		<?php 
		
		
		echo form_open('administrator/payment/manage_gateway'); ?>
	 
	 <div class="col-md-10 col-sm-8 col-xs-12">
	 <div class="col-md-2 col-sm-4 col-xs-12 text_box_payment">
	 <?php echo 'Paypal API Username'; ?><span style="color: red;">*</span></div>
	<div class="col-md-10 col-sm-8 col-xs-12">
    <input class="text_box" type="text" size="70" name="pe_user" id="pe_user" value="<?php echo $apiuser ; ?>">
        <span style="color:red;"><?php echo form_error('pe_user'); ?></span>
							</div>
				
	<div class="col-md-2 col-sm-4 col-xs-12 text_box_payment"style="position: relative;top: 5px;">
	<?php echo 'Paypal API Password'; ?><span style="color: red;">*</span></div>
	<div class="col-md-10 col-sm-8 col-xs-12">         <input class="text_box" type="text" size="70" name="pe_password" id="pe_password" value="<?php echo $apipass;  ?>">
         <span style="color:red;"><?php echo form_error('pe_password'); ?></span>
							</div>
				
	<div class="col-md-2 col-sm-4 col-xs-12 text_box_payment" style="position:relative;top:12px;">
	<?php echo 'Paypal API Key'; ?><span style="color: red;">*</span></div>
	<div class="col-md-10 col-sm-8 col-xs-12">         <input class="text_box" type="text" size="70" name="pe_key" id="pe_key" value="<?php echo $pe_key; ?>">
         <span style="color:red;"><?php echo form_error('pe_key'); ?></span>
							</div>
				
	 <div class="col-md-2 col-sm-4 col-xs-12 text_box_payment" style="position:relative;top:12px;">
	 <?php echo 'Paypal Email Id'; ?><span style="color: red;">*</span></div>



	<div class="col-md-10 col-sm-8 col-xs-12">
	<input class="text_box" type="text" size="70" name="paypal_id" id="paypal_id" value="<?php echo $email;  ?>">
         <span style="color:red;"><?php echo form_error('paypal_id'); ?></span>
							</div>


 <div class="col-md-2 col-sm-4 col-xs-12 text_box_payment" style="position:relative;top:12px;">
	 <?php echo 'Paypal Client Id'; ?><span style="color: red;">*</span>
	<!-- <input class="text_box" type="text" size="70" name="paypal_id" id="paypal_id" value="<?php echo $email;  ?>"> -->
</div>

<div class="col-md-10 col-sm-8 col-xs-12">
	<input class="text_box" type="text" size="70" name="paypal_client" id="paypal_client" value="<?php echo $clientid;  ?>">
         <span style="color:red;"><?php echo form_error('Paypal Client Id'); ?></span>
							</div>

				
	<div class="col-md-2 col-sm-4 col-xs-12 text_box_payment" style="position:relative;top:12px;">
	<?php echo 'Payment URL'; ?><span style="color: red;">*</span></div>
			<div class="col-md-10 col-sm-8 col-xs-12" style="position:relative;left: 5px;">
									<select class="select" style="width:155px;" name="paypal_url" id="paypal_url" >
								<option value="1" <?php  if($paymenturl == 1){ echo 'selected'; } ?>> Yes (Paypal Live)</option>
								<option value="0" <?php  if($paymenturl == 0){ echo 'selected'; } ?>> No (Paypal Sandbox) </option>
								</select>
								<span style="color:red;"><?php echo form_error('paypal_url'); ?></span>
							</div>

				
						<div class="col-md-2 col-sm-4 col-xs-12"></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
						<input type="hidden" name="Id" value="<?php  echo $id; ?>"/>
						<input value="<?php echo 'update'; ?>" class="btn-default" name="update" type="submit" >
					</div>

		<?php echo form_close(); ?>
		</div>
   </div>
  </div>
