
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
	
	 <h2 class="page-header"><?php echo 'Edit Braintree Payment GateWay'; ?></h2></div>
	 
   	 <script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>
  	
		<?php 
		
		
		echo form_open('administrator/payment/braintree_manage_gateway'); ?>
	 
	 <div class="col-md-12 col-sm-8 col-xs-12">
	 <div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 5px;">
	 <?php echo 'Braintree Merchant ID'; ?><span style="color: red;">*</span></div>
	<div class="col-md-10 col-sm-8 col-xs-12">
    <input class="text_box" type="text" size="70" name="merchant_id" id="merchant_id" value="<?php echo $merchant_id ; ?>">
        <span style="color:red;"><?php echo form_error('merchant_id'); ?></span>
							</div>
				
	<div class="col-md-2 col-sm-4 col-xs-12 text_box_text" style="position: relative;top: 10px;">
	<?php echo 'Braintree Private key'; ?><span style="color: red;">*</span></div>
	<div class="col-md-10 col-sm-8 col-xs-12">         <input class="text_box" type="text" size="70" name="privatekey" id="privatekey" value="<?php echo $privatekey;  ?>">
         <span style="color:red;"><?php echo form_error('privatekey'); ?></span>
							</div>
				
	<div class="col-md-2 col-sm-4 col-xs-12 text_box_text" style="position: relative;top: 10px;">
	<?php echo 'Braintree Public Key'; ?><span style="color: red;">*</span></div>
	<div class="col-md-10 col-sm-8 col-xs-12">         <input class="text_box" type="text" size="70" name="publickey" id="publickey" value="<?php echo $publickey; ?>">
         <span style="color:red;"><?php echo form_error('publickey'); ?></span>
							</div>
				
	 <div class="col-md-2 col-sm-4 col-xs-12 text_box_text" style="position: relative;top: 10px;">
	 <?php echo 'Braintree Account Id'; ?><span style="color: red;">*</span></div>
	<div class="col-md-10 col-sm-8 col-xs-12">
	<input class="text_box" type="text" size="70" name="account_id" id="account_id" value="<?php echo $account_id;  ?>">
         <span style="color:red;"><?php echo form_error('account_id'); ?></span>
							</div>
				
	 <div class="col-md-2 col-sm-4 col-xs-12 text_box_text" style="position: relative;left: 86px;top: 5px;">
	 <?php echo 'Is Live'; ?><span style="color: red;">*</span></div>
	<div class="col-md-10 col-sm-8 col-xs-12" style="position:relative;top:10px;">
	<select name="is_live" id="is_live">
		<option <?php if($is_live==0) echo "selected";?> value=0>No (Sandbox account)</option>
		<option <?php if($is_live==1) echo "selected";?> value=1>Yes (Live Account)</option>
	</select>
         <span style="color:red;"><?php echo form_error('is_live'); ?></span>

			<div class="col-md-10 col-sm-8 col-xs-12">
						<input type="hidden" name="Id" value="<?php  echo $id; ?>"/>
						<input class="btn-default" value="<?php echo 'Update'; ?>" name="update" type="submit" >
					</div>

		<?php echo form_close(); ?>
		
    </div>
   </div>
  </div>
