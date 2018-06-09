<style>
@media only screen and (min-device-width : 300px) and (max-device-width : 640px)
{
	.res_table thead, table.res_table {
	.res_table td:nth-of-type(1):before { content: "S.No" ; }
	.res_table td:nth-of-type(2):before { content: "Category Name"; }
	.res_table td:nth-of-type(3):before { content: "Price/KM"; }
	.res_table td:nth-of-type(4):before { content: "Price/Minute"; }
	.res_table td:nth-of-type(5):before { content: "Max Size"; }
	.res_table td:nth-of-type(6):before { content: "Minimum Fare"; }
	.res_table td:nth-of-type(9):before { content: "Prime time precentage"; }
	.res_table td:nth-of-type(10):before { content: "Photo"; }
	.res_table td:nth-of-type(11):before { content: "Photo"; }
	.res_table td:nth-of-type(12):before { content: "Action"; }
}  }
</style>
<script>
$(document).ready(function(){
                    $("#flash").delay(100).fadeOut('slow');
        });
</script>

      
	  
        <script src="<?php echo base_url();?>/js/sorttable.js"></script>
		<div class="container-fluid">

    <div class="row top-sp">
    	<div class="col-md-10 col-sm-8 col-xs-12">
    		  <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	  ?>
	
    		<div class="col-md-6 col-sm-6 col-xs-12">
          <h2 class="page-header-cd"><?php echo "Manage Category"; ?></h2></div>

 <!--
      <div class="col-md-6 col-sm-6 col-xs-12">
       <input class="btn-default" type="submit"style="position: relative;top: 4px;left: 467px;" onclick="window.location='<?php echo base_url('administrator/category/view_category');?>';" value="<?php echo 'Add Category'; ?>">
       </div>-->
 
 </div><br><br><br>
	<form action="<?php echo base_url('administrator/category/delete_category') ?>" name="managepage" method="post">
  <table id="sort_list" class="table res_table"  border="0" cellpadding="4" cellspacing="0">
	<thead>		 	
											<th><?php echo 'S.No'; ?></th>
											<th><?php echo 'Category Name'; ?></th>
											<th><?php echo 'Price/km'; ?></th>
											<th><?php echo 'Price/minute'; ?></th>
											<th><?php echo 'Max Size';?></th>
											<th><?php echo 'Minimum fare'; ?></th>
											<th><?php echo 'Prime time percentage '; ?></th>
											<th><?php echo 'Logo';?> </th>
											<th><?php echo 'Marker';?></th>
											<th><?php echo 'Action'; ?></th>
        					</thead>
					<?php $i=1;
						if(isset($category) and $category->count()>0)
						{  
						foreach ($category as $cat) {


					?>
					
			 <tr>
			<!--<td><input type="checkbox" class="clsNoborder" name="categorylist[]" id="categorylist[]" value="<?php echo $cat['_id']; ?>"  /> </td>--> 
			
			<td><!--<input type="checkbox" class="clsNoborder" name="car[]" id="car[]" value="<?php echo $i; ?>"  /> -->
			  <?php echo $i++; ?></td>
			  <td><?php echo $cat['categoryname']; ?></td>
			  <td><?php echo $cat['price_km']; ?></td>
			  <td><?php echo $cat['price_minute']; ?></td>
			  <td><?php echo $cat['max_size']; ?></td>	
			  <td><?php echo $cat['price_fare']; ?></td>
			  <td><?php echo $cat['prime_time_precentage']; ?></td>
			  <!--<td> <img src="<?php echo base_url('uploads/logo/'.$cat['Logo'] )?>" alt="Smiley face" height="30" width="30"/> </td>-->
			  <td> <img border="0"  width="30" height="30" src="<?php echo base_url('images/arcane category icons/'.$cat['Logo'] )?>" alt="Category Logo" >
<script>

</script></td>

<td> <img border="0" width="30" height="30" src="<?php echo base_url('images/arcane category icons/'.$cat['Marker'] )?>" alt="Marker" >
<script>

</script></td>
			  <td><a href="<?php echo base_url('administrator/category/editcategory/'.$cat['_id'])?>">
                <img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a>
				
			 <!--<a href="<?php echo base_url('administrator/category/delete_category/'.$cat['_id'])?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>-->
			  </td>
        	</tr>
			
   <?php
				}//Foreach End
			}//If End
			else
			{
			echo '<tr><td colspan="5">'.'No category Found'.'</td></tr>'; 
		}
		?>
		</table>
		<br />

		</form>
		</div>
		</div> 
    

