<script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>
<style>
	@media only screen and (min-device-width : 300px) and (max-device-width : 1024px)
{
	
		.text_box {
    border: 1px solid rgb(195, 195, 195);
    border-radius: 2px;
    line-height: 30px;
    margin: 10px 0 10px;
    padding-left: 5px;
    width: 100%;
} }
</style>

<div class="container-fluid padding_zero">

    <div class="row top-sp car_view_manag padding_zero">
    	<div class="col-md-10 col-sm-12 col-xs-12">
    		
    		
<?php
	 
	 if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
?>

	 <h2 class="page-header"><?php echo 'Firebase File Upload'; ?></h2>
	 </div>
	
		
<form id="myform" action="<?php echo base_url('administrator/management/fileupload'); ?>" method="post" enctype="multipart/form-data" >	
    	
    	<div class="col-md-10 col-sm-12 col-xs-12 col-md-offset-2">
    
			<div class="col-md-2 col-sm-4 col-xs-12" >
			<?php echo 'Library File'; ?><span style="color:#FF0000">*</span></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
			<input  id="userfile" name="userfile"  size="24" type="file" />
			<p>Only .json format file allowed to upload</p></div>

		<div class="clearfix">
			<div class="col-md-2 col-sm-4 col-xs-12"></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
		<input class="btn-default" type="submit" name="update" value="<?php echo 'Update'; ?>" /></div>
		</div>
		
		</div>	
		</form>
		</div>	
		
		<span style="float:left;">
			<div id="message">
			
		</div>
		</span>
		
		</div>

<style>
	.error_msg {
    color: red;
}
@media only screen and (min-width:320px) and (max-width:480px){
.text_box
{
	width: 100%;
}
.upd-but, .upd-but:hover
{
	margin-left: 20px;
}
}
@media only screen and (min-width:481px) and (max-width:640px){
.upd-but, .upd-but:hover
{
	margin-left: 20px;
}	
}
@media only screen and (min-width:768px) and (max-width:1024px){
.upd-but, .upd-but:hover
{
	margin-left: 10px;
}	
.text_box
{
	width: 100%;
}
}
</style>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
 	$("#myform").validate({
        rules: 
	        {
		 userfile:
			 {
			   required: true,
			 extension: "docx|json"
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