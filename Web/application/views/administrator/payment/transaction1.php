<!--<div id="confirm" style="background-color: #000; opacity:0.5;" onclick="document.getElementById('confirm').style.display='none';
	document.getElementById('confirmbox').style.display='none';">
</div>-->
<!-- Export CSV-->
<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "Transaction ID"; }
	.res_table td:nth-of-type(2):before { content: "Rider Name" ; }
	.res_table td:nth-of-type(3):before { content: "Driver Name"; }
	.res_table td:nth-of-type(4):before { content: "Amount"; }
	.res_table td:nth-of-type(5):before { content: "Driver Amount"; }
	.res_table td:nth-of-type(5):before { content: "PayMode"; }
	.res_table td:nth-of-type(6):before { content: "Status"; }
	.res_table td:nth-of-type(7):before { content: "Option"; }
	.res_table td:nth-of-type(8):before { content: "Trip ID"; }
}  
</style>

<!--<div class="container-fluid">-->

    <div class="row top-sp" style="position: relative;top: 10px;">
<!--	<form id="search" action="<?php echo base_url('administrator/payment/search'); ?>" method="post" name="search"> 
	<div class="col-md-3 col-sm-6 col-xs-6" style="float: right;top: 10px;">
	<input class="text_box_tb" type="text" placeholder="Rider Name / Driver Name" name="keyword" id="tags"/></div>
    <div class="col-md-3 col-sm-6 col-xs-6" style="float: right;top: 10px;">           
	<input class="btn-default" type="submit"style="position: relative;left: 170px;top: -9px;" id="submit" name="search" value="Search"  /></div>
		<input type="hidden" id="url" name="url" value= ""/>
        
            </form>-->

<!--<script src="<?php echo base_url();?>/js/sorttable.js"></script>-->
	<?php  	
	
		// Show reset password message if exist
		
		$tmpl = array (
                    'table_open'          => '<table class="table res_table " id="sort_list" border="0" cellpadding="4" cellspacing="0">',

                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th><a href=#>',
                    'heading_cell_end'    => '</th></a>',

                    'row_start'           => '<tr>',
                    'row_end'             => '</tr>',
                    'cell_start'          => '<td>',
                    'cell_end'            => '</td>',

                    'row_alt_start'       => '<tr>',
                    'row_alt_end'         => '</tr>',
                    'cell_alt_start'      => '<td>',
                    'cell_alt_end'        => '</td>',

                    'table_close'         => '</table>'
              );

			$this->table->set_template($tmpl); 
			$this->table->set_heading('Rider Name','Driver Name', 'Amount', 
							'Driver Amount','Paymode','Status','Option','Trip ID'
						);
						if($TransactionDetails->count() == 0 )
						{

					$this->table->add_row("No Transaction has made!!");		
						}
						
						else{
							//var_dump($TransactionDetails);
						//	exit;
		foreach ($TransactionDetails as $user) 
		{
			
			$ridername = '-';
			if(isset($user['rider_id']))
		$ridername = GetRiderNameByID($user['rider_id']);
			if($ridername=='')
             $ridername = '-';
			
			
			if(isset($user['driver_id']))
			$drivername = GetDriverNameByID($user['driver_id']);
			if($drivername=='')
			$drivername = '-';
			
			
			$amount = '-';
			if(isset($user['amount']))
			$amount = $user['amount'];
			
			$hostamount = '-';
			if(isset($user['hostamount']))
			$hostamount = $user['hostamount'];
			
            $paymode = '-';
            if(isset($user['paymode']))
            $paymode = $user['paymode'];
            
			if($paymode == 'Braintree')
			{
				
					$status = '-';
			if(isset($user['status']))
			$status = $user['status'];
			
							if(isset($user['tripid']))
				{
				$tid = 	$user['tripid'] ;
				}else{
				$tid = 0;
				}
			$this->table->add_row(
				
				$ridername,
				$drivername,  
				"&#36;".$amount, 
				"&#36;".$hostamount,
				
                $paymode,
				$status,
				anchor(base_url('administrator/staff_payment/braintreedetails/'.$user['_id']),('View Details')),
				$tid
				);
		
				
				
			}
else if($paymode == 'PayPal'){
	
		$status = '-';
			if(isset($user['status']))
			$status = $user['status'];
			
				if(isset($user['tripid']))
				{
				$tid = 	$user['tripid'] ;
				}else{
				$tid = 0;
				}
			$this->table->add_row(
				
				$ridername,
				$drivername,  
				"&#36;".$amount, 
				"&#36;".$hostamount,
				
                $paymode,
				$status,
				anchor(base_url('administrator/staff_payment/details/'.$user['_id']),('View Details')),
				$tid
				);
		
	
	
}
else if($paymode == 'Credit Card'){
	
		$status = '-';
			if(isset($user['status']))
			$status = $user['status'];
			
				if(isset($user['tripid']))
				{
				$tid = 	$user['tripid'] ;
				}else{
				$tid = 0;
				}
			$this->table->add_row(
				
				$ridername,
				$drivername,  
				"&#36;".$amount, 
				"&#36;".$hostamount,
				
                $paymode,
				$status,
				anchor(base_url('administrator/staff_payment/braintreedetails/'.$user['_id']),('View Details')),
				$tid
				);
		
	
	
}

else{ 	$status = '-';
			if(isset($user['status']))
			$status = $user['status'];
			
	
	
				if(isset($user['tripid']))
				{
				$tid = 	$user['tripid'] ;
				}else{
				$tid = 0;
				}
			$this->table->add_row(
				
				$ridername,
				$drivername,  
				"&#36;".$amount, 
				"&#36;".$hostamount,
				
                $paymode,
				$status,
				anchor(base_url('administrator/staff_payment/details/'.$user['_id']),('View Details')),
				$tid
				);
		
	
	
}

			
			
			$status = '-';
			if(isset($user['status']))
			$status = $user['status'];
			
	/*
			if(isset($user['tripid']))
					{
					$tid = 	$user['tripid'] ;
					}else{
					$tid = 0;
					}
				$this->table->add_row(
					$user['_id'],
					$ridername,
					$drivername,  
					"&#36;".$amount, 
					"&#36;".$hostamount,
										 $paymode,
					$status,
					anchor(base_url('administrator/payment/details/'.$user['_id']),('View Details')),
					$tid
					);*/
	
		}
		}
	    //	test				
			
		
					
						
		//test				
						
		echo '<div class="col-md-10 col-sm-8 col-xs-12">';
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	
		echo '<div class="col-md-6 col-sm-12 col-xs-12" ><br><br><br><h2 class="page-header-ab" >'.'Transaction Management'.'</h2></div></div>';
		//Show Flash Message
		
	//	echo '<br/>';
		echo '<div class="">';
		echo '<form name="myform" action="'.site_url('administrator/staffmembers/getusers').'" method="post">'; ?>

		<?php echo '</form>';
		echo '</div>';
		echo form_open('administrator/staffmembers');
		
		//echo '<div class=""><ul class="clfearfix"><li>';

		?>

		<?php
	//	echo '</ul></div>';
		echo "<div id='usertable'>";
	//	echo "<div id='css_user_atleast_user'>";
	//	echo "</div>";
		echo $this->table->generate(); 
		echo "</div>";
		echo form_close();
		 //echo $pagination;
		
			
	?>
	</div>
	</div>