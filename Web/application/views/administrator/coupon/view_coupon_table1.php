<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S.No"; }
	.res_table td:nth-of-type(2):before { content: "Promo Code" ; }
	.res_table td:nth-of-type(3):before { content: "Promocode Amount"; }
	.res_table td:nth-of-type(4):before { content: "Expired Date"; }
	.res_table td:nth-of-type(5):before { content: "Send_status"; }
	.res_table td:nth-of-type(6):before { content: "Status"; }
	.res_table td:nth-of-type(7):before { content: "Action"; }
}  
</style>


<style type="text/css" media="screen">
    td
    {
         border-top: 1px solid #FFF !important; 
    }
</style>    

<div id="page-wrapper2">
    	<div id="page-inner2">
    				<div class="head-pad2">
    			<div class="row">
          <div class="col-md-12">
<!--<script src="<?php echo base_url();?>/js/sorttable.js"></script>-->
<form action="<?php echo base_url('administrator/staff_coupon/add_coupon/') ?>"name="managepage" method="post">
	<input class="btn-default" style="position: relative;top: 10px;left: 947px;" type="submit" value="Add Coupon" onclick="http://appgoapp.com/smartmove/administrator/coupon/add_coupon">
 <h3 class="page-header3" style="position: absolute;padding-bottom: 9px;margin:-45px 0 19px 8px;font-size: 30px;color: #1ABB9C;font-family: "source_sans_proregular";text-align: left;"><?php echo "Manage Coupon"; ?></h3>
	
</div>
</div>
</div>	
<br><br>
<?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	  ?>
	

  <table id="sort_list" style="margin:10px 0px 0px 20px; " class="table res_table" cellpadding="2" cellspacing="0">
  	<thead>
        <th><a href=#><?php echo 'S.No'; ?>15a></th>
		<th><a href=#><?php echo 'Promo Code'; ?></a></th>
		<th><a href=#><?php echo 'Promocode Amount'; ?></a></th>
		<th><a href=#><?php echo 'Expired Date'; ?></a></th>
		<th><a href=#><?php echo 'Send_status'; ?></a></th>
		<th><a href=#><?php echo 'Status'; ?></a></th>																		
		<th><a href=#><?php echo 'Action'; ?></a></th>
	</thead>
	
	<?php $i=1;
					//$coupon	=	$this->mongo_db->db->promocode->find(array('_id' => new MongoId($id)));
					 
					$promo = $this->mongo_db->db->promocode->find();
 
 if($promo->hasNext())
 {
 	$i=1;
 	foreach ($promo  as $promocode) {
		 $expire_in=$promocode['expire_in'];
		 $price=$promocode['price'];
		 $gencode=$promocode['code'];
		 $status=$promocode['status'];
		 $send_status=$promocode['send_status'];
		//$data['promocode']	= $promo;
 		$data['expire_in']=$expire_in;
 		$data['price']=$price;		
 		$data['code']=$gencode;
			
		
		?>
		
<tr>
			  	
			  <td><?php echo $i++; ?></td>
			  <td><?php echo $gencode; ?></td>		 
			  <td><?php echo $price; ?></td>
			  <td><?php echo $expire_in; ?></td>
			  <td><?php echo $send_status; ?></td>
			  <td><?php echo $status; ?></td>
			  <td><a href="<?php echo base_url('administrator/staff_coupon/editcoupon/'.$promocode['_id'])?>">
                <img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a>
				
			 <a href="<?php echo base_url('administrator/staff_coupon/delete_coupon/'.$promocode['_id'])?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>
			  </td>
			  </tr>	

<?php
		
	 }
 }
 
					
						if(isset($coupon) and $coupon->num_rows()>0)
						{  
							foreach($coupon->result() as $coupon)
							{
															
					?>	
			  <tr>
			  <td><input type="checkbox" class="clsNoborder" name="couponlist[]" id="couponlist[]" value="<?php echo $coupon->id; ?>"  /> </td>
			  <td><?php echo $i++; ?></td>
			  <td><?php echo $coupon->couponcode; ?></td>		 
			  <td><?php echo $coupon->coupon_price; ?></td>
			  <td><?php echo $coupon->currency; ?></td>
			  <td><?php  echo date('d/m/y',$coupon->expirein);  ?></td>
			  <td><?php 
			  
			 
 if($coupon->status == 0)
 {
 	echo "Active";
 }else{
 	echo "Expired";
 } ?></td>
			   <!-- <td><?php echo (date("d-m-Y", strtotime($row_date['coupon']))); ?></td>   -->
			  <td><a href="#">
                <img src="<"#" alt="Edit" title="Edit" /></a>
		        <a href="<?php echo ('staff_coupon/delete_coupon/'.$coupon->id)?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>
			  </td>
        	</tr>
   <?php
			}//Foreach End
			}//If End
			/*
			else
						{
						echo '<tr><td colspan="5">'.('No Promocode Found').'</td></tr>'; 
						}*/
			
		?>

</table>
		<br />

		</form> 
    </div>
   </div>
		
	