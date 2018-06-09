 <?php $this->load->view('administrator/header'); ?>
 
 <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap.min.js"></script>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

 <!-- Style sheet -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/Style.css" />
<!-- End>
    <!-- Header Creation -->
     <style>
html * :not(i){
	 -webkit-font-smoothing: antialiased !important;
    -moz-osx-font-smoothing: grayscale !important;
 }
       </style>
         <script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>	
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
    $("#myform").validate({
        rules: 
            {
                 
                 'new_password':
                {
                   required :true
                },
                 'confirm_password':
                 {
                  required :true,
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
.login{
	display: none !important;
}

.forgot_pass{
	padding-top: 90px;
	
}
.rest_pass{
	min-height: 554px;
}
@media only screen and (min-device-width : 960px) and (max-device-width : 990px){
	.text_box_text{
		margin: 4px 0 0 0px !important; 
	}
}
@media only screen and (min-device-width : 300px) and (max-device-width : 768px){
	.footer_sec{
	position: relative;
	bottom: 0;
}
}
</style>

<div class="container-fluid rest_pass">


    <div class="row top-sp padding_zero">
    	<div class="col-md-12 col-sm-12 col-xs-12">
    			<?php 
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	 ?>
	
    		<h2 class="page-header"><?php echo 'Reset Password'; ?></h2>
		</div>
		
		
	<form action="<?php echo base_url('home/change_password'); ?>" method="post" id="myform"  name="myform" >	
<input type="hidden" name="userid" id="userid" value="<?php if(isset($userid)) echo $userid; ?>" />
<input type="hidden" name="type" id="type" value="<?php if(isset($type)) echo $type; ?>" />

<div class="col-md-offset-2 col-md-8 col-sm-12 col-xs-12 forgot_pass">
    	
    	<div class="col-md-3 col-sm-4 col-xs-12 text_box_text">
    		<?php echo 'New Password'; ?><span style="color: #FF0000;">*</span></div>
			<div class="col-md-9 col-sm-8 col-xs-12">
			<input class="text_box" id="new_password" type="password" name="new_password" size="55" value=""> 
			
			</div>

    	<div class="col-md-3 col-sm-4 col-xs-12 text_box_text">
    		<?php echo 'Confirm Password'; ?><span style="color: #FF0000;">*</span></div>
			<div class="col-md-9 col-sm-8 col-xs-12">
				<input class="text_box" id="confirm_password" type="password" size="55" name="confirm_password" value=""></div>

		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="col-md-8 col-sm-10 col-xs-9" style="padding: 0px 36px;">
		<input class="btn-default" type="submit" id="upd-but" name="update" value="<?php echo 'Submit'; ?>" style="text-align: center; float: right;"/>
		</div>
		</div>	
		
		</div>	
		</form>
		</div>
		<span style="float:left;"><div id="message"></div></span>
		</div>
		

<div id="clear"></div>
<?php $this->load->view('administrator/footer'); ?>
