<div id="confirm" style="background-color: #000; opacity:0.5;" onclick="document.getElementById('confirm').style.display='none';
	document.getElementById('confirmbox').style.display='none';">
	</div>
<!-- Export CSV-->


<div class="container-fluid"> 

    <div class="row top-sp">
    		
	<!--<form id="search" action="<?php echo base_url('administrator/payment/toPay'); ?>" method="post" name="search"> 
	<div class="col-md-3 col-sm-6 col-xs-6" style="float: right;">
	<input class="text_box_tb" type="text" class="search_input" placeholder="Rider name" name="keyword" id="tags"/></div>
	<div class="col-md-3 col-sm-6 col-xs-6" style="float: right;">
	<input class="btn-default" type="submit" id="submit" name="search" value="Search"  /></div>
			
                <input type="hidden" id="url" name="url" value= ""/>
           </form> -->
<script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>
		
<?php				
		echo '<div class="col-md-10 col-sm-8 col-xs-12"><h2 class="page-header">'.'Transaction Details'.'</h2></div>';
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	//	echo '<br/>';
		echo '<div class="clsFloatRight">';
		//echo '<form name="myform" action="'.site_url('./administrator/members/getusers').'" method="post">'; ?>
    <?php echo '</form>';
		echo '</div>';
	
		//echo form_open('./administrator/members');
		//echo '<div class="clsUser_Buttons"><ul class="clfearfix"><li>';
	?>
 <form method=post action=<?php  echo base_url('/administrator/payment/transaction/');?> >
<div class="col-md-10 col-sm-8 col-xs-12">
 
<?php 
  foreach ($results as $result) {
  	 $test_id= $result['_id'];
	 $amount = $result['total_price'];  
	 $hamount = '0';
	 $ridername = GetRiderNameByID($result['rider_id']);
	 $drivername = GetDriverNameByID($result['driver_id']);
	 $pickup_location = $result['pickup'];
	 $drop_location = $result['destination'];
	 $lat = $pickup_location['lat'];
	 $lng = $pickup_location['long'];
	 $lat1 = $drop_location['lat'];
	 $lng1 = $drop_location['long'];
	 	
	$pickup = GetAddress($lat,$lng);
	$drop = GetAddress($lat1,$lng1);
							$array_status = array('1'=> 'Request Pending', '2'=> 'Arrive' ,'3'=> 'Begin', '4' => 'End', '5' => 'Cancel');
	
				if(isset($result['accept_status']))
			{
			$acce_status = $result['accept_status'];			
			$acce_status = $array_status[$acce_status];	
			}else{
			$acce_status = "-";	
			}

			
			if(isset($result['trip_status']))
			$status = ucfirst($result['trip_status']);
			
						if(isset($result['created']))			
			$created_date = date('m-d-y',$result['created']);
	
  }
   ?> 

	<input type="hidden" value="<?php echo $test_id;?>" name="test_id"/>

 <div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php echo ('Rider Name'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
	
<?php if(!$ridername==null)
{
	echo $ridername;
} else {
	echo '-';
} ?></div>
 
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text">
<?php echo ('Driver Name'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text">
<?php if(!$drivername==null)
{
echo $drivername;
} else {
	echo '-';
}
 ?></div>
 

<div class="col-md-6 col-sm-6 col-xs-12 text_box_text">
<?php echo ('Pickup location'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text">
<?php if(!$pickup==null)
{
echo $pickup;
} else {
	echo '-';
}
 ?></div>

<div class="col-md-6 col-sm-6 col-xs-12 text_box_text">
<?php echo ('Drop location'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text">
<?php if(!$drop==null)
{
echo $drop;
} else {
	echo '-';
}
 ?></div>  
	
<!--<div class="col-md-6 col-sm-6 col-xs-12 text_box_text">
<?php echo ('Waiting time'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text">
<?php if(!$waiting_time==null)
{
echo $waiting_time;
} else {
	echo '-';
}
 ?></div>-->  
	
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php echo ('Amount'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php 
if(!$amount==null)
{
echo "&#36;".$amount;
} else {
	echo '-';
}?></div>
 
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php echo ('Accept Status'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php 
echo $acce_status;
?>
 </div>
 
 <div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php echo ('Trip Status'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php 
echo $status;
?>
 </div>
 	     
 	     
 	      <div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php echo ('Date'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php 
echo $created_date;
?>
 </div>
 	      
	</form>		
	
			
	<?php
	//	echo '</ul></div>';
		//echo "<div id='usertable'>";
	//	echo "<div id='css_user_atleast_user'>";
	//	echo "</div>";
		//echo $this->table->generate(); 
		echo "</div>";
		echo form_close();
		?>

</div>
</div>
</div>