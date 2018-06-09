<style>
	.error_msg {
    color: red;
}

</style> <script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js"></script>
 
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
                	required:true,
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
                }
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

<div class="container-fluid padding_zero">
    <div class="">
    	<div class="col-md-12 col-sm-12 col-xs-12">
<h2 class="page-header"><?php echo ('Edit Driver'); ?></h2>
</div>
<?php foreach($DriverDetails as $Driver) {
	$DriverId = $Driver['_id'];
	
	$FirstName  = $Driver['first_name'];
	if($FirstName == 'null') $FirstName = '';
	
	$LastName = $Driver['last_name'];
	if($LastName == 'null') $LastName = '';
	
	$Email = $Driver['email'];

	$driver_mobile = '';
	if(isset($Driver['mobile']))
	$driver_mobile = $Driver['mobile'];
	
	$driver_car = '';
	if(isset($Driver['carcategory']))
	$driver_car = $Driver['carcategory'];
	
	$driver_license_proof='';
	if(isset($Driver['license']))
	$driver_license_proof = $Driver['license'];
	
	$driver_voterid_proof='';
	if(isset($Driver['insurance']))
	$driver_voterid_proof = $Driver['insurance'];
	
	$driver_status_proof='';
	if(isset($Driver['proof_status']))
	$driver_status_proof = $Driver['proof_status'];
	
	
}
?>
<form class="form-group" action="<?php echo base_url('administrator/members/DriverEdit/'.$DriverId ); ?>" method="post" id="user_edit" name="user_edit">
<input type="hidden" id="id_driver" value="<?php echo $DriverId; ?>">

<div class="col-md-12 col-sm-12 col-xs-12">
	
<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 10px;">
<?php echo ("Driver ID"); ?><span style="color:red">*</span>
</div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="col-md-4 col-sm-8 col-xs-12 text_box" type="text" name="addcar" value="<?php echo $DriverId; ?>" readonly=readonly disable=disable>
</div>

<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 10px;">
<?php echo ("First Name"); ?><span style="color:red">*</span>
</div>

<div class="col-md-10 col-sm-8 col-xs-12">
<input class="col-md-4 col-sm-8 col-xs-12 text_box" type="text" name="Fname" value="<?php echo $FirstName; ?>" >
</div>

<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 10px;">
<?php echo ("Last Name"); ?><span style="color:red">*</span>
</div>

<div class="col-md-10 col-sm-8 col-xs-12">
<input class="col-md-4 col-sm-8 col-xs-12 text_box" type="text" name="Lname" value="<?php echo $LastName; ?>" >
</div>

<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 10px;">
<?php echo ("Email"); ?><span style="color:red">*</span>
</div>

<div class="col-md-10 col-sm-8 col-xs-12">
<input class="col-md-4 col-sm-8 col-xs-12 text_box" type="text"  id="user_email" name="email_value" value="<?php echo $Email; ?>" >
</div>

<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 10px;">
<?php echo ("Driver Mobile Number"); ?><span style="color:red">*</span>
</div>

<div class="col-md-10 col-sm-8 col-xs-12">
<input class="col-md-4 col-sm-8 col-xs-12 text_box" id="user_profile_info_current_city" type="text" name="mobile" value="<?php echo $driver_mobile; ?>" >
</div>

<input type="hidden" id="driver_mobile_no" value="<?php echo $driver_mobile; ?>">

<!--<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 10px;">
<?php echo ("Category"); ?><span style="color:red">*</span>
</div>


<div class="col-md-10 col-sm-8 col-xs-12">
		<select class="col-md-4 col-sm-8 col-xs-12 select" name='category'>
		<?php foreach($category as $row)
				{
					echo '<option value="'.$row['categoryname'].'">'.$row['categoryname'].'</option>';
				}
			
				?>			
		</select>
</div>-->
<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 10px;">
</div>

<div class="col-md-10 col-sm-8 col-xs-12">

<div class="col-md-2 col-sm-4 col-xs-6" style="margin-left: 7px;">				
<input class="btn-default" type="submit" name="editdriver"  value="<?php echo "Update"; ?>"  />
</div>

<div class="col-md-2 col-sm-4 col-xs-6">			
<a href="<?php echo base_url('administrator/members/Driver'); ?>" ><input class="btn-default" type="button" name="editdriver"  value="<?php echo "Cancel"; ?>"  /></a>
</div>

</div>


</div>
</form>


<?php if(!empty($driver_license_proof) && !empty($driver_voterid_proof)) {
	$noimage =base_url()."images/no_image.png";
	if($noimage == TRUE){
	?>
	
<div class="col-md-10 col-sm-8 col-xs-12" id="proof">
	
<div class="col-md-3 col-sm-4 col-xs-8 text_box_text">
	<?php echo 'License Proof'; ?><span style="color:red">*</span>
	</div>
<div class="col-md-9 col-sm-8 col-xs-4">
<p id="img_logo"><img style="margin: 10px 0px 0px -21px;" class="" src="<?php echo $driver_license_proof; ?>"  alt="logo" height="200" width="280" onerror="this.src='<?php echo $noimage; ?>'" /></p>
</div>

<div class="col-md-3 col-sm-4 col-xs-8 text_box_text">
	<?php echo 'Address Proof'; ?><span style="color:red">*</span>
	</div>
<div class="col-md-9 col-sm-8 col-xs-4">
<p id="img_logo"><img style="margin: 10px 0px 0px -21px;" class="" src="<?php echo $driver_voterid_proof; ?>" alt="logo" height="200" width="280" onerror="this.src='<?php echo $noimage; ?>'"></p>
</div>

<?php if($driver_status_proof != 'Accepted') {?>
	
<div class="col-md-2 col-sm-4 col-xs-8"></div>
<div class="col-md-1 col-sm-1 col-xs-4" style="left:40px;">
<input class="btn-default" type="submit" id="send_message" name="send_message" value="<?php echo ("Accept"); ?>">
</div>
<div class="col-md-1 col-sm-1 col-xs-12" style="left:40px;">
<input class="btn-default" type="submit" id="reject"style="position: relative;left: 20px;" name="reject" value="<?php echo ("Reject"); ?>">
</div>

	<?php } else {?>

<div class="col-md-3 col-sm-4 col-xs-6 text_box_text" style="margin-bottom:10px;">
<?php echo ("Proof Status"); ?><span style="color:red">*</span>
</div>
<div class="col-md-9 col-sm-8 col-xs-6 text_box_text" style="margin:10px 0px 0px -21px;">
	<?php echo $driver_status_proof; ?>
</div>
<?php } ?>
<?php } ?>
</div>
 <?php } ?>
		
</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#send_message').click(function(){
			var driver_id=$('#id_driver').val();
			var driver_mobile_no=$('#driver_mobile_no').val();
			 $.ajax({
            url: "<?php echo base_url()?>administrator/Members/send_message",             
            type: "POST",
            data: 'driver_id='+driver_id+'&driver_mobile_no='+driver_mobile_no,             
            cache: false,             
             
            success: function (data) {  
            	var message=$.trim(data); 
if(message == 'Success')
{
	alert('Driver accepted successfully');
	location.reload(true);
	}
else
{
	//alert('Message not send'); return false;
}
            }
            });
		})
		$('#reject').click(function(){
			var driver_id=$('#id_driver').val();
			
			 $.ajax({
            url: "<?php echo base_url()?>administrator/members/reject",             
            type: "POST",
            data: 'driver_id='+driver_id,             
            cache: false,             
             
            success: function (data) {  
            	location.reload(true);
            }
            });
		})
	})
</script>
<style>
	.upd-button, .can-but
	{
		margin-top:30px ; 
	}
	.upd-button:hover, .can-but:hover
	{
		margin-top:30px ;
	}
</style>
