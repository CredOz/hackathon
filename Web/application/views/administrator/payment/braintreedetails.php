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
		//echo '<div class="clsUser_Buttons"><ul class="clfearfix"><li>'; //toPay
	?>
	
 <form method=post action=<?php  echo base_url('/administrator/payment/toBraintree/');?> >   
<div class="col-md-10 col-sm-8 col-xs-12">
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php echo ('Transaction ID'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php 
  foreach ($results as $result) {
  	 $test_id= $result['_id'];
	 $amount = $result['amount'];  
	 $hamount = '-';
	 $ridername = GetRiderNameByID($result['rider_id']);
	 $drivername = GetDriverNameByID($result['driver_id']);
	 $pickup_location = $result['pickup'];
	 $drop_location = $result['drop'];
	 $waiting_time = $result['waitingtime'];
  if(isset($result['hostamount']))
			$hamount = $result['hostamount'];
	 $tamount = '-';
  if(isset($result['tollamount']))
			$tamount = $result['tollamount'];
			
	if($tamount!="")
 	{
 	$totalpay=$hamount+$tamount;
 	}
 	else
 	{
 	$totalpay=$hamount;
 	}		
	
	$check_accept_pay = $status = $result['status'];	
	
	$tid = 	$result['tripid'] ;
			
  }
  echo $tid; ?> </div>

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
} ?></div>
 

<div class="col-md-6 col-sm-6 col-xs-12 text_box_text">
<?php echo ('Prickup location'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text">
<?php if(!$pickup_location==null)
{
echo $pickup_location;
} else {
	echo '-';
} ?></div>

<div class="col-md-6 col-sm-6 col-xs-12 text_box_text">
<?php echo ('Drop location'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text">
<?php if(!$drop_location==null)
{
echo $drop_location;
} else {
	echo '-';
} ?></div>  

	
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text">
<?php echo ('Waiting time'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text">
<?php if(!$waiting_time==null)
{
echo $waiting_time;
} else {
	echo '-';
} ?></div>  

<input type='hidden' name='driveridfromview' value='<?php echo $drivername ?>' onload="get_driveraccount(this)">

<script>

 function get_driveraccout(driverfromview){
      document.write(data=driverfromview);
      		 	   $.ajax({
                        url: '<?php echo base_url(); ?>model/drivers_model/account'+data,
                        type: "POST",
                        data:data,
                        success: function(data) {
console.log(data); write(data);
                        //$('#get_details').empty().prepend(data);
                        
                        }
                    })
      	
     }
    

</script>	
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php echo ('Amount'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php if(!$amount==null)
{
echo "&#36;".$amount;
} else {
	echo '-';
}?></div>
 
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php echo ('Driver Amount'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php if(!$hamount==null)
{
echo "&#36;".$hamount;
} else {
	echo '-';
} ?>
 <input type="hidden" name="hamount" value="<?php echo $hamount; ?>" />
 </div>
 
 
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php echo ('TollFee Amount'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php if(!$tamount==null)
{
echo "&#36;".$tamount;
} else {
	echo '-';
} ?>
 <input type="hidden" name="tamount" value="<?php echo $tamount; ?>" />
 </div>

 <div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php echo ('Total Payout'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php if(!$totalpay==null)
{
echo "&#36;".$totalpay;
} else {
	echo '-';
} ?>
 <input type="hidden" name="totalpay" value="<?php echo $totalpay; ?>" />
 </div>
 
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php echo ('Status'); ?></div>
 <div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> <?php 
 echo ucfirst($status);
 ?></div>


<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php echo ('Account Id'); ?></div>
 <div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> <?php 
 foreach ($acc as $result) {
 	if(isset($result['accountno']))
	{
 		$accno = $result['accountno'];
	}
	else {
		$accno = null;
	}
 //$submerchant = $result['submerchantaccountid'];
if($accno == null)
  echo '-';
  else
  echo ($accno); 

 }

?></div>

<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php echo ('SubMerchant account id'); ?></div>

<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
 <div class="col-md-6 col-sm-6 col-xs-12 text_box_text"><?php 

if(isset($result['submerchantaccountid']))
{
 $submerchant = $result['submerchantaccountid'];
 }
else
{
	$submerchant=null;
}

if($submerchant == null)
echo '-';
else
echo $submerchant;

?></div></div>

  
 
 
  <div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php echo ('Total Payout'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php 

if($totalpay == null)
  echo '-';
  else
  echo "&#36;".$totalpay;?>
 <input type="hidden" name="totalpay" value="<?php echo $totalpay; ?>" />
 </div>
 




<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php echo ('Trip ID'); ?></div>
<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php 
  foreach ($results as $result) {
 $tid = 	$result['tripid'] ;
echo $tid ;}?></div>

  <?php 	$detail_trip = $this->mongo_db->db->trips->find(array( "_id"=>new MongoId($test_id)));
	
	foreach($detail_trip as $details_row) {
	
	$check_accept_pay = $details_row['status'];
	$driverid = $details_row['driver_id'];
	//$check_address = strrchr($check_address,' ');
	
	}
	$driverdetails = $this->mongo_db->db->drivers->find(array( "_id"=>new MongoId($driverid)));
	
	foreach($driverdetails as $driver_row) {
	
	@$country = $driver_row['country'];
		
	}

 if($check_accept_pay!="Paid" and $country!="US"){?> 
<div class="col-md-6 col-sm-6 col-xs-12">
<input class="btn-default" type="submit" id="btn_CommitAll"  name="payviapaypal" value="Pay Using PayPal"/>	</div>
<div class="col-md-6 col-sm-6 col-xs-12">
<input class="btn-default" type="submit" id="btn_CommitAll"  name="payviapaypal" value="Pay Using Cash"/>	</div>

 <?php
}
else if($check_accept_pay!="Paid"){
	?>
<div class="col-md-6 col-sm-6 col-xs-12"></div>
<div class="col-md-6 col-sm-6 col-xs-12">
<input class="btn-default" type="submit" id="btn_CommitAll"  name="payviapaypal" value="Pay Using Braintree to Driver"/>	</div>
 		
		<?php
	}
	else{
	?>
	<?php echo "Already amount paid to Driver.";?>
		<?php
	}?>

     
	</form>		

<div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> 
<?php  //echo ('Status'); ?></div>
 <div class="col-md-6 col-sm-6 col-xs-12 text_box_text"> <?php 
foreach ($results as $result) {
	if(isset($result['payment_id']))
	{
 	$payment_id = $result['payment_id'];
	}else{
			$payment_id=null;
		}

 if($payment_id == null)
  echo '-';
  else
  ucfirst($payment_id); 
} ?></div>
<input type='hidden' name="payment_id" value="<?php  echo $payment_id; ?>">
	
		<?php
	
		echo "</div>";
		echo form_close();
		?>
 
</div>
</div>
</div>
