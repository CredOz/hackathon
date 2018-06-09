<!-- datatable functions script for search -->

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/datatable/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/datatable/dataTables.bootstrap.css">
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>js/datatable/jquery.dataTables.js"></script>		
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>js/datatable/dataTables.bootstrap.js"></script>

<style>
.res_table td {
word-break: break-all;
}
@media only screen and (max-width: 760px) and (max-device-width: 1024px) {

	.res_table td:nth-of-type(1):before { content: "First Name" ; }
	.res_table td:nth-of-type(2):before { content: "Last Name"; }
	.res_table td:nth-of-type(3):before { content: "Mail"; }
	.res_table td:nth-of-type(4):before { content: "Phone"; }
	.res_table td:nth-of-type(5):before { content: "Wallet Balance"; }
	.res_table td:nth-of-type(6):before { content: "Action"; }
	.res_table td:nth-of-type(7):before { content: "Photo"; }
	.res_table td:nth-of-type(8):before { content: "Change Password"; }
	.res_table td:nth-of-type(9):before { content: "Create Date"; }
} 

#sort_list {
    border-bottom: 0px solid;
   
} 
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

    <div class="padding_zero">
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
				//Show Flash Message
				if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}?>
			
	<div class="col-xs-12 col-md-12 col-sm-12 padding_zero">
	<h1 class="page-header3" style="font-size: 30px;color: #000;"><?php echo "Rider Management"; ?></h1>
	<div class="but-set padding_zero">
<!-- Export txt and csv -->
<?php echo form_open('administrator/members'); ?>
	<!--	<span3><input type="submit" name="export" class="" value="Export as Txt file"></span3>
		<span3><input type="submit" name="export_csv" class="" value="Export as Csv file"></span3>-->
<?php form_close(); ?>
<!-- Export txt and csv -->	</div>
	</div>
<?php

		// Show reset password message if exist
		if (isset($reset_message))
		echo $reset_message;
		// Show error
		echo validation_errors();
		$tmpl = array (
                    'table_open'          => '<table id="sort_list"  class="table res_table" border="0" cellpadding="4" cellspacing="0">',
					
					'thead_open'          =>'<thead>',
					'thead_close'          =>'</thead>',
					
                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th><a href=#>',
                    'heading_cell_end'    => '</th></a>',
                    
					'tbody_open'          =>'<tbody>',
					'tbody_close'          =>'</tbody>',

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
			$this->table->set_heading('First Name','Last Name', 'Email', 
							'Phone','Wallet Balance','Action','Photo','Change Password','Create Date'
						);

						if($RiderUsers->count()==0)

					

						{
					$this->table->add_row("No Records has made!!");		
						}else{
		foreach ($RiderUsers as $user) {
			
			$last_name = '-';
			if(isset($user['last_name'])&&$user['last_name']!='')
		  $last_name = $user['last_name'];
			
			$creditcard = 'Null';
			if(isset($user['credit_card_num']))
			$creditcard = $user['credit_card_num'];

			$driver_mobile = 'Null';
			if(isset($user['mobile']))
			$driver_mobile = $user['mobile'];
			
			$wallet_balance = '-';
			if(isset($user['wallet_amount']))
			$wallet_balance = $user['wallet_amount'];

			if($user['password'] != '') 
			$via_login = anchor('administrator/members/changepassword/'.$user['_id'], 'Change Password');
			elseif (isset($user['fb_id']) && $user['fb_id'] != '')
			$via_login = 'Login with Facebook';
			else 
			$via_login = 'Login with Google+';	
			
			
			$created_date = date('m-d-y',$user['created']);
			
			
						$this->load->helper('html'); 
			
	 
			$image_properties = array(
          'src' => $user['profile_pic'], 
          'class' => 'thumb-img',
          'width' => '20',
          'height' => '20',
          'title' => 'Photo',
          "onerror"=>"this.src='".base_url('images/index.png')."'"
          
);
		$image_properties2 = array(
          'src' => $user['profile_pic'], 
          'class' => 'thumb-img',
          'width' => '150',
          'height' => '150',
          'title' => 'Photo',
          
);
          $photo1 = array(
          'src' => base_url('images/index.png'), 
          'class' => 'thumb-img',
          'width' => '20',
          'height' => '20',
          'title' => 'Photo',
);
             $photo=img($photo1);
			if(isset($user['profile_pic'])&&$user['profile_pic']!=''&&$user['profile_pic']!='null'&&$photo==TRUE)
			$photo= "<div class='popup-img popup-img1'>" . img($image_properties) ."<div class='popup-img-hover popup-img1-hover'>".img($image_properties2).
  "</div> </div>";
			
				$this->table->add_row(
			    
				$user['first_name'],
				$last_name,  
				$user['email'], 
				$driver_mobile,
				$wallet_balance,
				anchor('administrator/members/RiderEdit/'.$user['_id'], 'Edit').' / '.anchor('administrator/members/RiderDelete/'.$user['_id'], 'Delete',array('onclick' => "return confirm('Do you want delete this record')")),
				$photo,
				$via_login,
				$created_date
				);

	

		}
}
		echo form_open('administrator/members');
		?>
						
	<div class="col-md-12 col-sm-12 col-xs-12 padding_zero">
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