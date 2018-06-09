<style>
@media only screen and (min-device-width : 300px) and (max-device-width : 1024px)
{
	.res_table thead, table.res_table {
	.res_table-com td:nth-of-type(1):before { content: "Commission Type" ; }
	.res_table-com td:nth-of-type(2):before { content: "Commission Fees";<br/> }
	.res_table-com td:nth-of-type(3):before { content: "Is Active?"; }
}  
.table {
    margin: 100px -100px 0px 5px !important;
}
.btn-default {
    line-height: 10px;
    padding: 11px;
    border-radius: 2px;
    border: 1px solid #2A3F54;
    background-color: #2A3F54;
    color: #FFF;
    font-weight: bold;
    margin: 0px 0px 0px -650px;
}}
</style>
<div class="container-fluid">

    <div class="row top-sp">
<?php 
  echo form_open(base_url().'administrator/payment/paymode');
			
			
			//Show Flash Message
			
			
				$tmpl = array (
                    'table_open'          => '<table style="margin:80px 0px 35px 115px;" class="table res_table sortable" border="0" cellpadding="4" cellspacing="0">',

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

                    'table_close'         => '</table></div>'
              );

		$this->table->set_template($tmpl); 
		

		$this->table->set_heading( 'Commission Type', 'Commission Fees'); /*'Is Active?'*/

		
		
				foreach ($payMode as $row) 
				{ //print_r($row);
					if($row['is_premium'] == 1)
					{
							$is_premium = 'Yes';
							$change_to  = 'Change to free mode';
					}
					else
					{
							$is_premium = 'No';
							$change_to  = 'Change to premium mode';
					}
					
					if($row['is_fixed'] == 1)
					{
					  $commission = $row['fixed_amount'];
					}
					else
					{
					  $commission = $row['percentage_amount']. '%';
					}
					
					$change = '<a href="'.base_url('administrator/payment/paymode/'.$row['_id']).'"><img src="'.base_url().'images/change.jpg" title="'.$change_to.'" alt="'.$change_to.'" /></a>';
					
					$this->table->add_row(
						form_checkbox('check[]', $row['_id']).'&nbsp;'.
						$row['mod_name'],
						$commission 
						//$is_premium.'&nbsp;&nbsp;&nbsp;'.$change
						);
					}

		
		//echo form_open($this->uri->uri_string());
		// echo '<div class="col-md-12">';
		//echo form_submit('edit', 'Edit Commission Settings');
		//echo '</li></ul></div>';
		
		
		echo '<div class="col-md-10 col-sm-8 col-xs-12">';
		if($msg = $this->session->flashdata('flash_message'))
			{
				echo $msg;
			}
		
		echo '<div class="col-md-6 col-sm-12 col-xs-12"><input style="margin-top:20px;position: relative;" class="btn-default" type="submit" value="Edit Commission Settings" name="edit"><br/></div>';
		echo $this->table->generate(); 
		
		echo form_close();
	?>
	<script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>
</div>
</div>