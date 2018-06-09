

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
	
	 <h2 class="page-header"><?php echo 'Facebook Connect'; ?></h2></div>
	 
   	 <script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>
  	
		<?php 
		
		
		echo form_open('administrator/members/facebooklogin'); ?>
		
<div class="col-md-10 col-sm-8 col-xs-12">
<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">
<?php echo 'Facebook API Key'; ?><span style="color: red;">*</span></div>

<div class="col-md-10 col-sm-8 col-xs-12">
<input class="text_box" type="text" size="70" name="fb_api" id="fb_api" value="<?php echo $key; ?>">
<span><?php echo form_error('pe_user'); ?></span>
</div>
				
<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">
<?php echo 'Facebook Secret Key'; ?><span style="color: red;">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="text_box" type="text" size="70" name="fb_secret" id="fb_secret" value="<?php echo $secret; ?>">
<span><?php echo form_error('pe_password'); ?></span>
</div>

<div class="col-md-2 col-sm-4 col-xs-12"></div>
<div class="col-md-10 col-sm-8 col-xs-12">				
<input type="hidden" name="Id" value="<?php  echo $id; ?>"/>
<input class="btn-default" value="<?php echo 'Update'; ?>" name="update" type="submit" >
</div>

		
		<?php echo form_close(); ?>
		
    </div>
   </div>
</div>