<script language="Javascript" type="text/javascript" src="<?php echo base_url().'js/jquery-1.9.1.js';?>"></script>
<script type="text/javascript">
		function startCallback() {
		document.getElementById('message').innerHTML = '<img src="<?php echo base_url().'images/loading.gif' ?>">';
		// make something useful before submit (onStart)
		return true;
	}

	function completeCallback(response) {
		if(response.length > 100 )
{
window.location.href = "<?php echo base_url().'administrator';?>";
}
else
{
document.getElementById('message').innerHTML = response;
}
	}

$(function () {
$(':text').keydown(function (e) {
if (e.shiftKey || e.ctrlKey || e.altKey) {
e.preventDefault();
} else {
var key = e.keyCode;  
if (!((key == 8) || (key == 46) || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105))) {
e.preventDefault();
}
}
});
});
</script>


    <div class="container-fluid">

    <div class="row top-sp">
    	
    	
    	<?php
				//Show Flash Message
	echo '<div class="col-md-10 col-sm-8 col-xs-12" style="
    padding-bottom: 6px;
">';
				if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
		/*
		echo '<div class="col-md-6 col-sm-12 col-xs-12" style="
			position: absolute;
			top: 2px;
		"><h2 class="page-header-ab">'.'Driver Management'.'</h2> </div></div>';
		echo '<br/>';*/
		
?>
    	<div class="col-md-10 col-sm-8 col-xs-12">
     			<!--<sp1><input type="submit" action="<?php echo base_url('administrator/payment/paymode'); ?>" value="<?php echo 'View All'; ?>"></sp1>-->
				<h2 class="page-header"><?php echo 'Edit Commission'; ?></h2>     		
     			</div>
  <form id="myform"></form> 		
<form action="<?php echo base_url('administrator/payment/paymode'); ?>" id="myform" method="post" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">	

	 <div class="col-md-10 col-sm-8 col-xs-12">
    	<div class="col-md-2 col-sm-4 col-xs-12 text_box_text" style="position:relative;left:-4px">
    		<?php echo 'Is Active?'; ?><span style="color: red"> *</span></div><br>
							<div class="col-md-10 col-sm-8 col-xs-12">
							<select name="is_premium" class="select" id="is_premium" onchange="javascript:showpremium(this.value);"style="position: relative;top: -12px;">
							<option value="0"> No </option>
							<option value="1"> Yes </option>
							</select> 
							</div>
					</div>
				
				
				<?php
				
			//print_r($result); echo "dfgdfg";
			
				if($result['is_premium'] == 0)
				{ $show = 'none'; }
				else
				{ $show = 'inline-table'; }
				?>
				<!--
				<?php
								
							//print_r($result); echo "dfgdfg";
							
								if($result['is_premium'] == 0)
								{ $show = 'none'; }
								else
								{ $show = 'inline-table'; }
								?>-->
				
				
				
				
				
				<div class="col-md-10 col-sm-8 col-xs-12" id="showhide" style="display:<?php echo $show; ?>;">
				
		<div class="col-md-2 col-sm-4 col-xs-12 text_box_text" style="position:relative;left: -6px;top: 12px;"><?php echo 'Promotion Type'; ?></div>
							<div class="col-md-5 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 12px;"><input type="radio" <?php if($result['is_fixed'] == 1) echo 'checked="checked"'; ?> name="is_fixed" onclick="javacript:showhideF(this.value);" value="1"> Fixed Pay</div>
							<div class="col-md-5 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 12px;left: -210px;"><input type="radio" <?php if($result['is_fixed'] == 0) echo 'checked="checked"'; ?> name="is_fixed" onclick="javacript:showhideF(this.value);" value="0"> Percentage Pay</div>
				
				
				<?php
				if($result['is_fixed'] == 1)
				{ $showF = 'block'; $showP = 'none'; }
				else
				{ $showF = 'none'; $showP = 'inline-table'; }
				?>	
				
				<tr id="fixed" style="display:<?php echo $showF; ?>;">
       <td style="padding:20px 0px 0px 50px;"><?php echo 'Fixed Amount'; ?><span style="color: red"> *</span></td>
							<td style="padding:20px 0px 0px 8px;"> <input type="text" class="" style="position: relative;left: 37px;margin: 32px 42px 0px 0px;" name="fixed_amount" value="<?php echo $result['fixed_amount']; ?>"></td>
				</tr>		
				
					<tr id="percentage" style="display:<?php echo $showP; ?>;">
       <td style="position:relative;left:10px"><?php echo 'Percentage Amount'; ?><span style="color: red"> *</span></td>
							<td style="padding:20px 0px 0px 8px;"> <input type="text" class="" name="percentage_amount"style="margin: 19px 25px 0px;" value="<?php echo $result['percentage_amount']; ?>"></td>
				</tr>			
				</table>
				
				<tr>
						<td></td>
						<td style="padding:20px 0px 0px 8px;">
						<input type="hidden" name="payId" value="<?php echo $payId; ?>" />
						<div class="clearfix">
						<p style="float:left; margin:0 10px 0 0;position:relative;left: 133px;top: 12px;"><input class="btn-default" type="submit" name="update" value="<?php echo 'Update'; ?>" style="width:90px;" /></p>
						<span style="float:left;"><div id="message"></div></span>
						</div></span>
						</td>
				</tr>
		
		</table>	
		<?php echo form_close(); ?>
		
    </div>

<script language="Javascript">
jQuery("#is_premium").val('<?php echo $result['is_premium']; ?>');

function showpremium(id)
{ 
	if(id == 1)
	{
		
			 document.getElementById("showhide").style.display = "inline-table";
			  //document.getElementById("myform").submit();
	}
	else
	{
	   document.getElementById("showhide").style.display = "none";
	   
	   			  //document.getElementById("myform").submit();

	   
	 
	}
}

function showhideF(id)
{
	if(id == 1)
	{
	document.getElementById("fixed").style.display      = "inline-table";
	document.getElementById("percentage").style.display = "none";
	}
	else
	{
	document.getElementById("fixed").style.display      = "none";
	document.getElementById("percentage").style.display = "inline-table";	
	}
}
</script>
