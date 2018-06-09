<div class="container-fluid padding_zero">
    	    	<div class="col-md-12 col-sm-12 col-xs-12 padding_zero">


    	<div class="col-md-12 col-sm-12 col-xs-12">
	<?php	if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		} ?>
<h2 class="page-header"><?php echo ('Request accept duration management'); ?></h2>
</div>
	
<?php
if($DurationTime->count() > 0)
{
 foreach($DurationTime as $Duration) {
	$DurationTime = $Duration['request_duration_time'];
}	
}else{
	$DurationTime = '';
}

?>
<form action="<?php echo base_url('administrator/management/RequestDuration/'); ?>" method="post" name="user_edit">

<div class="col-md-12 col-sm-12 col-xs-12">

<div class="col-md-3 col-sm-4 col-xs-12">
<?php echo ("Request accept duration (In seconds)"); ?><span style="color:red">*</span>
</div>

<div class="col-md-9 col-sm-8 col-xs-12">
<select class="col-md-4 col-sm-8 col-xs-10 select_new" name="durationtime">
	<?php for($value = 5; $value <= 30; $value++) { ?>
		<option value="<?php echo $value; ?>" <?php if( $value == $DurationTime ) echo "selected";?> > <?php echo $value; ?></option> 
	<?php } ?>	
</select>
</div>

<div class="col-md-3 col-sm-4 col-xs-12"></div>
<div class="col-md-8 col-sm-8 col-xs-12">				
<input class="btn-default" type="submit" name="editrider"  value="<?php echo "Update"; ?>"  />
</div>
	
</div>

</form>	

</div>
</div>	
<style>
	.select_new {
		height:40px;
	}
</style>