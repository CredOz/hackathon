<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
    $("#myform").validate({
        rules: 
            {
                 'old_password':
                 {
                  required :true,
                 },
                 'new_password':
                {
                   required :true,
                   minlength : 5
                },
                 'confirm_password':
                 {
                  required :true,
                  minlength : 5,
                  equalTo: "#new_password"
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
<style>
.error_msg {
    color: #ff0000;
    margin-left: 20px;
}

</style>

<div class="container-fluid padding_zero">


    <div class="row top-sp padding_zero">
    	<div class="col-md-12 col-sm-12 col-xs-12">
    			<?php 
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	 ?>
	
    		<h2 class="page-header"><?php echo 'Change Password'; ?></h2>
		</div>
		
		
	<form action="<?php echo base_url('administrator/settings/change_password'); ?>" method="post" id="myform"  name="myform" >	


<div class="col-md-12 col-sm-12 col-xs-12">
    	<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">
    		<?php echo 'Old Password'; ?><span style="color: #FF0000;">*</span></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
				<input class="text_box" id="old_password" type="password" size="55" name="old_password" value=""> <?php echo form_error('old_password'); ?> </div>
			
    	<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">
    		<?php echo 'New Password'; ?><span style="color: #FF0000;">*</span></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
			<input class="text_box" id="new_password" type="password" name="new_password" size="55" value=""> 
			
			</div>

    	<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">
    		<?php echo 'Confirm Password'; ?><span style="color: #FF0000;">*</span></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
				<input class="text_box" id="confirm_password" type="password" size="55" name="confirm_password" value=""></div>

		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="col-md-2 col-sm-4 col-xs-12"></div>
			<div class="col-md-7 col-sm-8 col-xs-12">
		<input class="btn-default" type="submit" id="upd-but" name="update" value="<?php echo 'Submit'; ?>" />
		</div>
		</div>	
		
		</div>	
		</div>
		<span style="float:left;"><div id="message"></div></span>
		</div>
		
<?php echo form_close(); ?>
<div id="clear"></div>