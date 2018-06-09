<style>
	@media only screen and (min-device-width: 300px) and (max-device-width: 1024px) {
		.res_table thead, table.res_table {
			.res_table
			td: nth-of-type(1) :before {
			content: "S.No";
		}
		.res_table td:nth-of-type(2):before {
			content: "Promo Code";
		}
		.res_table td:nth-of-type(3):before {
			content: "Promocode Amount";
		}
		.res_table td:nth-of-type(4):before {
			content: "Expired Date";
		}
		.res_table td:nth-of-type(5):before {
			content: "Send_status";
		}
		.res_table td:nth-of-type(6):before {
			content: "Status";
		}
		.res_table td:nth-of-type(7):before {
			content: "Action";
		}
	}  }
</style>


<style type="text/css" media="screen">
	td {
		border-top: 1px solid #FFF !important;
	}
</style>    

<div id="page-wrapper2">
    	<div id="page-inner2">
    				<div class="head-pad2">
    			<div class="row">
          <div class="col-md-12">
<!--<script src="<?php echo base_url();?>/js/sorttable.js"></script>-->
<form action="<?php echo base_url('administrator/coupon/add_coupon/') ?>"name="managepage" method="post">
	<input class="btn-default" style="position: relative;top: 10px;left: 947px;" type="submit" value="Add Coupon" >
	</form> 
 <h3 class="page-header3" style="position: absolute;padding-bottom: 9px;margin:-45px 0 19px 8px;font-size: 30px;text-align: left;"><?php echo "Manage Coupon"; ?></h3>
	
</div>
</div>
</div>	
<br><br>
<?php //Show Flash Message
if ($msg = $this -> session -> flashdata('flash_message')) {
	echo $msg;
}
	  ?>
	

  <table id="sort_list" style="margin:10px 0px 0px 20px; " class="table res_table" cellpadding="2" cellspacing="0">
  	<thead>
        <th><a href=#><?php echo 'S.No'; ?></th>
		<th><a href=#><?php echo 'Promo Code'; ?></a></th>
		<th><a href=#><?php echo 'Promocode Amount'; ?></a></th>
		<th><a href=#><?php echo 'Expired Date'; ?></a></th>
		<th><a href=#><?php echo 'Send Status'; ?></a></th>
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
			  <td><a href="<?php echo base_url('administrator/coupon/editcoupon/'.$promocode['_id'])?>">
                <img src="<?php echo base_url()?>images/edit-new.png" alt="Edit" title="Edit" /></a>
				
			 <a href="<?php echo base_url('administrator/coupon/delete_coupon/'.$promocode['_id'])?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url()?>images/Delete.png" alt="Delete" title="Delete" /></a>
			  </td>
			  </tr>	

<?php }
}else{
	?>
	
	<?php
}
?>

</table>
	
    </div>
   </div>
		
	