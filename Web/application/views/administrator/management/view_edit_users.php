 <script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js"></script>
 <script type="text/javascript">
 	$(document).ready(function()
{
	$("#user_edit").validate({
     rules: {
                Fname: { 
                	required: true,
                	minlength: 4,
                	maxlength: 25
                },
                 Lname: { 
                	required: true,
                	minlength: 4,
                	maxlength: 25
                },
                email_value: { 
                	required: true,
                	email:true,
                	
                },
                mobile: { 
                	required: true,
                	digits : true,
                	maxlength : 15,
					minlength : 10  
                },
                
            },
           messages:
		    {
				email_value:
				{
				  required:"This field is required",
				}, 
           },
           errorClass:'error_msg',
    errorElement: 'div',
    errorPlacement: function(error, element)
    {
        error.appendTo(element.parent());
    },
	});
});
</script>
<style>
	.error_msg {
color: red !important;
}
</style>
<div class="container-fluid">

    <div class="row top-sp padding_zero">
    	<div class="col-md-10 col-sm-8 col-xs-12">
<h2 class="page-header"><?php echo ('Edit Rider'); ?></h2></div>
<?php foreach($RiderDetails as $Rider) {
	$RiderId = $Rider['_id'];
	
	$FirstName  = $Rider['first_name'];
	if($FirstName == 'null') $FirstName = '';
	
	$LastName = $Rider['last_name'];
	if($LastName == 'null') $LastName = '';
	
	$Email = $Rider['email'];
	if($Email == 'null') $Email = '';
	
	$creditcard = '';
	if(isset($Rider['credit_card_num']))
	$creditcard = $Rider['credit_card_num'];

	$driver_mobile = '';
	if(isset($Rider['mobile']))
	$driver_mobile = $Rider['mobile'];
	
}
?>
<form action="<?php echo base_url('administrator/members/RiderEdit/'.$RiderId ); ?>" method="post" id="user_edit" name="user_edit">
<div class="col-md-10 col-sm-8 col-xs-12 ">
<div class="col-md-2 col-sm-4 col-xs-12 text_box_text" >
	<?php echo ("Rider ID"); ?><span style="color:red">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="text_box" id="riderid" name="riderid" size="30" type="text" value="<?php echo $RiderId; ?>" readonly=readonly disable=disable />
</div>
<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">
	<?php echo ("First Name"); ?><span style="color:red">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
	<input class="text_box" id="user_first_name" name="Fname" size="30" type="text" value="<?php echo $FirstName; ?>" />
</div>
<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">
<?php echo ("Last Name"); ?><span style="color:red">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
	<input class="text_box" id="user_email" name="Lname" size="30" type="text" value="<?php echo $LastName ; ?>" />
</div>
<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">
<?php echo ("Email"); ?><span style="color:red">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
	<input class="text_box" id="user_email" name="email_value" size="30" type="text" value="<?php echo $Email ; ?>" />
</div>
<div class="col-md-2 col-sm-4 col-xs-12 text_box_text" >
	<?php echo ("Rider Mobile Number"); ?><span style="color:red">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
	<input class="text_box" id="user_profile_info_current_city" name="mobile" value="<?php echo $driver_mobile; ?>" size="30" type="text" />
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
	
<div class="col-md-2 col-sm-4 col-xs-12"></div>

<div class="col-md-2 col-sm-1 col-xs-12">
<input class="btn-default" type="submit"  name="editrider" value="<?php echo ("Update"); ?>">
</div>
<div class="col-md-2 col-sm-1 col-xs-12">

	<a  href="<?php echo base_url('administrator/members/Rider'); ?>" class="btn-default" name="edituser">
		<?php echo ("Cancel"); ?></a>
	</div>
	
</div>


</div>

</form>		
</div>
</div>		
</div>			