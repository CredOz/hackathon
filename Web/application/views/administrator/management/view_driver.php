<!-- datatable functions script for search -->

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/datatable/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/datatable/dataTables.bootstrap.css">
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>js/datatable/jquery.dataTables.js"></script>		
<script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>js/datatable/dataTables.bootstrap.js"></script>

<script>
	$(document).ready(function(){
    $('#sort_list').DataTable({
    	
    	 stateSave: true
    });
	});
</script>

<style>
.res_table td {
word-break: break-all;
}
@media only screen and (max-width: 760px) and (max-device-width: 1024px) {
	
	.res_table td:nth-of-type(1):before { content: "First Name" ; }
	.res_table td:nth-of-type(2):before { content: "Last Name"; }
	.res_table td:nth-of-type(3):before { content: "Email"; }
	.res_table td:nth-of-type(4):before { content: "Availability"; }
	.res_table td:nth-of-type(6):before { content: "Phone"; }
	.res_table td:nth-of-type(7):before { content: "Doc Status"; }
	.res_table td:nth-of-type(8):before { content: "Action"; }
	.res_table td:nth-of-type(9):before { content: "Photo"; }
	.res_table td:nth-of-type(10):before { content: "Change Password"; }
	.res_table td:nth-of-type(11):before { content: "Create Date"; }

}
</style>


<!-- Export CSV-->
<div id="confirm" style="background-color: #000; opacity:0.5;" onclick="document.getElementById('confirm').style.display='none';
	document.getElementById('confirmbox').style.display='none';">
	</div>
<!-- Export CSV-->

<div class="container-fluid padding_zero">

    <div class="">
	
<div class="col-md-12 col-sm-12 col-xs-12 padding_zero">
		<?php  
				//Show Flash Message
				if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}?>
			
	<div class="col-xs-12 col-md-12 col-sm-12" >
	<h1 class="page-header3" style="font-size: 30px;color: #000;font-family:"><?php echo "Driver Management"; ?></h1>
	<div class="but-set">
<!-- Export txt and csv -->
<?php echo form_open('administrator/members'); ?>
		<!--	<span3><input type="submit" name="export1" class="" value="Export as Txt file"></span3>
			<span3><input type="submit" name="export_csv1" class="" value="Export as Csv file"></span3>-->
<?php form_close(); ?>
<!-- Export txt and csv -->	</div>
	</div>


						
<?php		// Show reset password message if exist
		if (isset($reset_message))
		echo $reset_message;
		// Show error
		echo validation_errors();
		$tmpl = array (
                    'table_open'          => '<table  class="table res_table"  id="sort_list" cellpadding="2" cellspacing="0" align="left">',

                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th>',
                    'heading_cell_end'    => '</a></th>',

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
$this->table->set_heading('First Name','Last Name', 'Email', 'Availablity',
							'Phone','Doc Status','Action','Photo',
							'Change Password','Create Date'
						);

						if($DriverUsers->count()==0)

			

						{
					$this->table->add_row("No Records has made!!");		
						}else{
		foreach ($DriverUsers as $user) {
			
			$last_name = '-';
			if(isset($user['last_name'])&&$user['last_name']!='')
		  $last_name = $user['last_name'];
			
		if(isset($user['status']) && $user['status'] == 'on')
			$banned = anchor('administrator/members/DriverBan/'.$user['_id'], 'ON');
		else 
			$banned =  anchor('administrator/members/DriverUnban/'.$user['_id'], 'OFF');
		
		$driverid  = $user['_id'] ; 
		$rider_check = $this->mongo_db->db->users->find(array("driverid"=> "$driverid" ));
	 /*
		if((isset($user['trip_driver_status']) &&  $user['trip_driver_status'] != 'Home Page' && $user['trip_driver_status'] != 'Request' ) || ($rider_check->hasnext()))
		{
			
		$release = anchor('administrator/members/releaseDriver/'.$user['_id'], 'Release',"onclick='return confirm(\"Are you sure?\")'" );
			
		}
		else 
			$release = "" ; 
		*/
		$created = date('m-d-y',$user['created']);
		
		$carcategory = '-';
			if(isset($user['carcategory'])&&$user['carcategory']!='')
		  $carcategory = $user['carcategory'];
		
			if(isset($user['proof_status'])&&$user['proof_status']!='')
		  $proof_status = $user['proof_status'];
			
			if($user['password'] != '') 
			$via_login = anchor('administrator/members/DriverChangePassword/'.$user['_id'], 'Change Password');
			elseif (isset($user['fb_id']) && $user['fb_id'] != '')
			$via_login = 'Login with Facebook';
			else 
			$via_login = 'Login with Google+';	
			$this->load->helper('html'); 
	
	 		$photo1 = array(
          'src' => base_url('images/index.png'), 
          'class' => 'thumb-img',
          'width' => '20',
          'height' => '20',
          'title' => 'Photo',
);
             $photo=img($photo1);
			
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
			if(isset($user['profile_pic'])&&$user['profile_pic']!=''&&$user['profile_pic']!='null') 			
			$photo= "<div class='popup-img popup-img1'>" . img($image_properties) ."<div class='popup-img-hover popup-img1-hover'>".img($image_properties2).
  "</div> </div>";
		
			
    
			
			$this->table->add_row(
				
				$user['first_name'],
				$last_name,  
				$user['email'], 
				$banned,
				$user['mobile'],
				$proof_status,
				anchor('administrator/members/DriverEdit/'.$user['_id'], 'Edit').' / '.anchor('administrator/members/DriverDelete/'.$user['_id'], 'Delete',array('onclick' => "return confirm('Do you want delete this record')")),
				$photo,
				$via_login,
				$created
				);
			
		}}
		echo form_open('administrator/members');
		
		//echo '<div class="clsUser_Buttons"><ul class="clearfix">';
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

<script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>