<style>
	@media only screen and (min-device-width: 300px) and (max-device-width: 1024px) {
		.res_table thead, table.res_table {
			.res_table
			td: nth-of-type(1) :before {
			content: "S.No";
		}
		.res_table td:nth-of-type(2):before {
			content: "Car Name";
		}
		.res_table td:nth-of-type(3):before {
			content: "Category Name";
		}
		.res_table td:nth-of-type(4):before {
			content: "Action";
		}
	}  }
</style>
 <script>
			$(document).ready(function() {
				$("#flash").delay(1000).fadeOut('slow');
			}); 
</script>	
      
   
	<script src="<?php echo base_url(); ?>/js/sorttable.js"></script>
			<div class="container-fluid">

    <div class="row top-sp">
    	<div class="col-md-10 col-sm-8 col-xs-12">
    		   <?php //Show Flash Message
			if ($msg = $this -> session -> flashdata('flash_message')) {
				echo $msg;
			}
	  ?>  
    
    	<div class="col-md-6 col-sm-6 col-xs-12">
          <h2 class="page-header3"><?php echo "Manage Car"; ?></h2></div>
          <div class="col-md-6 col-sm-6 col-xs-12">
		<input class="btn-default" type="submit"style="position:relative;top: 4px;left: 500px;" value="<?php echo 'Add Car'; ?>" onclick="window.location='<?php echo base_url('administrator/car/view_car'); ?>';">
		</div>
	  </div>

  <table id="sort_list" class="table res_table" border="0" cellpadding="4" cellspacing="0">
  	<thead>
											<th><?php echo 'S.No'; ?></th>
											<th><?php echo 'Car Name'; ?></th>
											<th><?php echo 'Category Name'; ?></th>									
							                              <th><a href=><?php echo 'Action'; ?></a></th>
											</thead>
        
					<?php $i=1;
						if(isset($car) and $car->count()>0)
						{  
						foreach ($car as $car) {

					?>
					
			 <tr>
			 <td><?php echo $i++; ?></td>
			<td><?php echo $car['carname']; ?></td>	
			<td><?php echo $car['category']; ?></td>
			
		
			  <td><a href="<?php echo base_url('administrator/car/editcar/'.$car['_id'])?>">
                <img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a>
				
			 <a href="<?php echo base_url('administrator/car/delete_car/'.$car['_id'])?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>
			  </td>
        	</tr>
			
   <?php
}//Foreach End
}//If End
else
{
echo '<tr><td colspan="5">'.'No Car Found'.'</td></tr>';
}
		?>
		</table>

    </div>
   </div>