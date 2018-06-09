<style>
	@media only screen and (min-device-width: 300px) and (max-device-width: 1024px) {
		.res_table thead, table.res_table {
			.res_table
			td: nth-of-type(1) :before {
			content: "Rider ID";
		}
		.res_table td:nth-of-type(2):before {
			content: "First Name";
		}
		.res_table td:nth-of-type(3):before {
			content: "Last Name";
		}
		.res_table td:nth-of-type(4):before {
			content: "Mail ID";
		}
		.res_table td:nth-of-type(5):before {
			content: "Phone Number";
		}
		.res_table td:nth-of-type(6):before {
			content: "Credit Card";
		}
		.res_table td:nth-of-type(7):before {
			content: "Action";
		}
		.res_table td:nth-of-type(8):before {
			content: "Change Password";
		}
	}
</style>


  <style>
	.clsShow_Notification {
		float: right;
		width: 775px;
		margin-top: 15px;
	}
 	</style>
 <script type="text/javascript">
        
	$(document).ready(function() {
  
    
 $("#delete").live("click",function(){ 
            var val = $(this).attr('rel');
            $.ajax({
            url: "<?php echo base_url()?>
				administrator / members / delete_email",
				type: "POST",
				data: 'user_id='+val,
				cache: false,

				success: function (data) {
				$("#"+data).html('');
				window.location="
<?php echo base_url()?>
	administrator / members / manage_email"
	}
	});
	});

	$("#edit").live("click",function(){
	var val = $(this).attr('rel');

	});

	});
</script>
<script>
	$(document).ready(function() {
		$('.clsShow_Notification').fadeOut(5000);
	}); 
</script>

<span id="mess">
	<script>
		$(document).ready(function() {
			$("#flash").delay(1000).fadeOut('slow');
		}); 
</script>
	
</span>
	
			<div class="container-fluid">

    <div class="row top-sp">
    	<div class="col-md-10 col-sm-8 col-xs-12">
    		
	<?php
	if ($msg = $this -> session -> flashdata('flash_message')) {
		echo $msg;
	}
 ?>
    		<div class="col-md-6 col-sm-6 col-xs-12">
    	  <h2 class="page-header3"><?php echo "Manage Templates"; ?></h2></div>
				
	<div class="col-md-6 col-sm-6 col-xs-12">
	<input class="btn-default" type="submit"style="position: relative;top: 10px;left: 402px;" Value="<?php echo 'Add Email template'; ?>" onclick="window.location='<?php echo base_url('administrator/members/add_email'); ?>';"></a></div>
	</div>
	<table class="table res_table " id="sort_list"  border="0" cellpadding="4" cellspacing="0">
<thead>
<tr>
 <th>ID</th><th>Subject</th><th>Code</th><th><a href="#" style="position:relative;left: 0px;">Edit</a></th><th>Delete</th></tr>
</thead>
<tbody>
	<?php if($count !=0 ){ ?>


   <?php $i=1; foreach ($message as $msg) {
     //echo $obj["title"] . "\n";
     //print_r($obj);
 //    echo $users['email'];
     ?>
    <tr id="<?php  echo $msg['_id']?>">
    	<td><?php  echo $i; ?></td>
        <td><?php  echo $msg['subject']; ?></td>
        <td><?php  echo $msg['code']?></td>
        <td id="edit" rel="<?php  echo $msg['_id']?>"><a href="<?php echo base_url('administrator/members/edit_email').'/'.$msg['_id']?>"><img title="Edit" style="position: relative;left: 0px;" src="<?php echo base_url()?>images/edit-new.png"></a></td>
        <td  >
        	<a onclick="return confirm('Are you sure want to delete??');" href="<?php echo base_url('administrator/members/delete_email').'/'.$msg['_id']?>">
        	<img id="delete" style="cursor: pointer" rel="<?php  echo $msg['_id']?>" title="Delete" src="<?php echo base_url()?>images/Delete.png">
        		</a>
        	</td>
        </tr>
     <?php $i++;

			}
		?>

 

<?php } else {

	echo "<tr><td colspan='5' >No email templates to display.</td></tr>" ;
	}
	?>
</table>
     </div>
   </div>
   
    