<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/datatable/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/datatable/dataTables.bootstrap.css">
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>js/datatable/jquery.dataTables.js"></script>		
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>js/datatable/dataTables.bootstrap.js"></script>
<style>
@media only screen and (max-width: 760px) and (max-device-width: 1024px) {

	.res_table td:nth-of-type(1):before { content: "S.No" ; }
	.res_table td:nth-of-type(2):before { content: "Rider Name" ; }
	.res_table td:nth-of-type(3):before { content: "Driver Name" ; }
	.res_table td:nth-of-type(4):before { content: "Amount"; }
	.res_table td:nth-of-type(5):before { content: "Accept Status"; }
	.res_table td:nth-of-type(6):before { content: "Trip Status"; }
	.res_table td:nth-of-type(7):before { content: "Option"; }
	.res_table td:nth-of-type(8):before { content: "Date"; }
	
 
}
</style>
<style>
	.btn-default1 {
    line-height: 10px;
    padding: 11px;
    border-radius: 2px;
    border: 1px solid #2A3F54;
    background-color: #2A3F54;
    color: #FFF;
    font-weight: bold;
margin-top:20px;
margin-bottom: 20px;
}
</style>
<script>
	$(document).ready(function(){
    $('#sort_list').DataTable({
    	
    	 stateSave: true
    });
	});
</script>
<div class="container-fluid padding_zero">

    <div class="">
<script>
$(document).ready(function(){
            $("#flash").delay(1000).fadeOut('slow');
            $("#sort_list_previous").focus(function() {
            	$("li").removeClass("disabled");        	
            });     
        });
</script>
<div class="col-md-12 col-sm-12 col-xs-12 padding_zero">
	<?php  	
	
		// Show reset password message if exist
		$i=1;
		$tmpl = array (
                    'table_open'          => '<table class="table res_table " id="sort_list" border="0" cellpadding="4" cellspacing="0" >',

                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th>',
                    'heading_cell_end'    => '</th>',

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
			$this->table->set_heading('S.No','Rider Name','Driver Name', 'Amount', 
							'Accept Status','Trip Status','Option','Date'
						);
						if($TransactionDetails->count() == 0 )
						{

					$this->table->add_row("No Transaction has made!!");		
						}
						
						else{
							//var_dump($TransactionDetails);
						//	exit;
						$array_status = array('1'=> 'Request Pending', '2'=> 'Arrive' ,'3'=> 'Begin', '4' => 'End', '5' => 'Cancel');
		foreach ($TransactionDetails as $user) 
		{
				
			if(isset($user['rider_id']))
			$ridername = GetRiderNameByID($user['rider_id']);
			if($ridername=='')
             $ridername = '-';
			
			
			
			if(isset($user['driver_id']))
			$drivername = GetDriverNameByID($user['driver_id']);
			if($drivername=='')
			$drivername = '-';
			
			
			$amount = '0';
			if(isset($user['total_price']))
			$amount = $user['total_price'];
				
			if(isset($user['accept_status']))
			{
			$acce_status = $user['accept_status'];			
			$acce_status = $array_status[$acce_status];	
			}else{
			$acce_status = "-";	
			}

			
			if(isset($user['trip_status']))
			$status = ucfirst($user['trip_status']);
		
			if(isset($user['created']))
			$created = $user['created'];
			$created_date = date('m-d-y',$created);
			$this->table->add_row(
				$i,
				$ridername,
				$drivername,  
				"&#36;".$amount,
				$acce_status, 				
				$status,
				anchor(base_url('administrator/payment/details/'.$user['_id']),('View Details')),
				$created_date
				);
				$i++;
		}
		}
			?>
					<?php  
				//Show Flash Message
				if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}?>
			
	<div class="col-xs-12 col-md-12 col-sm-12" >
	<h1 class="page-header3" style="font-size: 30px;color: #1ABB9C;font-family:" onclick="details()"><?php echo "Transaction History"; ?></h1>
	<div class="but-set">
<!-- Export txt and csv -->
<?php echo form_open('administrator/members'); ?>
		<!--	<span3><input type="submit" name="export2" class="" value="Export as Txt file"></span3>
			<span3><input type="submit" name="export_csv2" class="" value="Export as Csv file"></span3>-->
<?php form_close(); ?>
<!-- Export txt and csv -->	</div>
	</div>

	<?php
		echo form_open('payment/transaction');
				?>
<div class="col-md-12 col-sm-12 col-xs-12">
		<?php 
		echo $this->table->generate(); 
	?>
	</div>
		<?php

		echo form_close();
	?>
 	

	</div>
	</div>
		</div>
<style>
	#sort_list_previous a:hover {
    	color: #111!important;
	}
	#sort_list_next a:hover {
		color: #111!important;
	}
	.paginate_button a:hover {
		color: white !important;
    	border: 1px solid #111;
    	background-color: #337AB7 !important;	
	} 		
</style>