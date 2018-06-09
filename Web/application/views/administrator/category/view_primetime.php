<div class="container-fluid">

    <div class="row top-sp">
    	<div class="col-md-10 col-sm-8 col-xs-12">
    		
      	<?php	if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		} ?>
           <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/css/bootstrap-datetimepicker.min.css" />
          <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/css/jquery.timepicker.css" />
          <script src="<?php echo base_url();?>/js/jquery-1.7.1.min.js"></script>
          <script src="<?php echo base_url();?>/js/jquery.timepicker.js"></script>
         
              <script src="<?php echo base_url();?>/js/jquery.js"></script> 
              <script src="<?php echo base_url();?>/js/moment.min.js"></script> 
              <script src="<?php echo base_url();?>/js/combodate.js"></script> 
              <script type="text/javascript" src="<?php echo base_url();?>/js/bootstrap-datepicker.js"></script>

               <script src="<?php echo base_url();?>js/jquery-1.8.3.min.js"></script>
          
          <h2 class="page-header"><?php echo "Prime Time"; ?></h2>
      </div>
		
		 <?php   
		$this->load->library('mongo_db');
		$category = $this->mongo_db->db->primetime->find();
	   foreach ($category  as $cat)
	   {
	    $from=$cat['fromtime'];
		//print_r($catname);
		$to=$cat['totime'];
	   }
	   
		 ?>
		
	<form action="<?php echo base_url('administrator/category/primecheck');?>" name="managepage" method="post">
  
  <div class="col-md-10 col-sm-8 col-xs-12">
    	<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">From Time: </div>
<div class="col-md-10 col-sm-8 col-xs-12"><select id="fromtime" name="fromtime" class="select" >
<?php 

$interval	= '+30 minutes';
$start 		= '00:00';
$end 		= '23:30'; 

$start_str 	= strtotime($start);
$end_str 	= strtotime($end);

echo '<option value="null"> '. "Select".' </option>';
for($now_str = $start_str; $now_str <= $end_str; $now_str = strtotime($interval, $now_str))
{
	$ds=date('H:i', $now_str);
	if($from==$ds)
	{ ?>
	<option value="<?php echo date('H:i', $now_str); ?>" selected><?php echo date('H:i', $now_str); ?></option>';
<?php } 
else { ?>
         <option value="<?php echo date('H:i', $now_str); ?>"><?php echo date('H:i', $now_str); ?></option>';
<?php }
}
?>
</select></div>



<!-- To Time -->
<div id="to_time_container">
<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">To Time: </div>
<div class="col-md-10 col-sm-8 col-xs-12"><select class="select" id="totime" name="totime" >
<?php 

$interval	= '+30 minutes';
$start 		= '00:00';
$end 		= '23:30'; 

$start_str 	= strtotime($start);
$end_str 	= strtotime($end);

echo '<option value="null"> '. "Select".' </option>';
for($now_str = $start_str; $now_str <= $end_str; $now_str = strtotime($interval, $now_str))
{
	$ds=date('H:i', $now_str);
	if($to==$ds)
	{ ?>
	<option value="<?php echo date('H:i', $now_str); ?>" selected><?php echo date('H:i', $now_str); ?></option>';
<?php } 
else { ?>
         <option value="<?php echo date('H:i', $now_str); ?>"><?php echo date('H:i', $now_str); ?></option>';
<?php }
}
?>
</select>
</div>
	
				<div class="col-md-2 col-sm-4 col-xs-12"></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
	<input class="btn-default" type= "submit" name="prime" value="Ok">
	</div>
	</form>
	
	
	
<script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>	


	
		</div>
		</div> 
  </div>
  </div>
   	 





