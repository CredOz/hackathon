<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js"></script>
 <script type="text/javascript">
 	$(document).ready(function()
{
	$("#user_edit").validate({
     rules: {
                new_password: { 
                	required: true,
                	minlength: 4,
                	maxlength: 25
                },
                confirm_new_password: { 
                	equalTo: "#new_password",
                	required: true,
                	minlength: 4,
                	maxlength: 25
                }
            },
             errorClass:'error_msg',
	});
});
</script>

<div class="container-fluid">

    <div class="row top-sp">
    	<div class="col-md-10 col-sm-8 col-xs-12">
<h2 class="page-header"><?php echo ('Change Password'); ?></h2></div>

<form id="user_edit" action="<?php echo base_url('administrator/management/memberChangePassword/'.$this->uri->segment(4,0)); ?>" method="post">

<div class="col-md-10 col-sm-8 col-xs-12">
<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">
	<?php echo ("New Password"); ?><span style="color: red;">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="text_box" type="password"  id="new_password" name="new_password" value="" />
<span style="color:#FF0000"><?php echo form_error('new_password'); ?></span>
 </div>
<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">
<?php echo ("Confirm Password"); ?><span style="color: red;">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="text_box" type="password" name="confirm_new_password" value="" />
<span style="color:#FF0000"><?php echo form_error('confirm_new_password'); ?></span>
</div>
<div class="col-md-2 col-sm-4 col-xs-12"></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="btn-default" type="submit" name="changepassword" value="<?php echo ("Update"); ?>"></div>
</form>
</div>
</div>
</div>