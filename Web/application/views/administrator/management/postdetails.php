<!--<div id="confirm" style="background-color: #000; opacity:0.5;" onclick="document.getElementById('confirm').style.display='none';
	document.getElementById('confirmbox').style.display='none';">
</div>-->
<!-- Export CSV-->


<div class="container-fluid">

    <div class="row top-sp">

<script src="<?php echo base_url();?>/js/sorttable.js"></script>
<script>
	$(function () { $('.table-sort').tablesort(); });
</script>
	<?php  	
echo '<div class="col-md-10 col-sm-8 col-xs-12">';
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
		echo '<div class="col-md-6 col-sm-12 col-xs-12"><h2>'.'List of Post requests'.'</h2></div></div>';
		
		// Show reset password message if exist
		
		$tmpl = array (
                	'table_open'          => '<table class="table res_table table-sort table-sort-search" border="0" cellpadding="4" cellspacing="0">',

                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th class="table-sort">',
                    'heading_cell_end'    => '</th></a>',

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
			$this->table->set_heading('Posted User','Created Date', 'State','City','Country','Pin','Title','Description','Product','Product','Product','Product','Product'
						);
						if($TransactionDetails->count() == 0 )
						{

					$this->table->add_row("No Post Available!!");		
						}
				
		foreach ($TransactionDetails as $user => $value) 
		{
			
			unset($value['_id']);
			$userid = $value['userid'];
			$created = @$value['created'] ; 
			unset($value['userid']);	
				unset($value['created']);	
				$value['created'] = date('m d y H:i', $created);
			$value['userid'] = get_usernamebyid($userid);
			$value = array_reverse($value);
			$this->table->add_row(
				$value
				);
			
		
		}		

		?>

		<?php
	//	echo '</ul></div>';
		echo "<div id='usertable'>";
	//	echo "<div id='css_user_atleast_user'>";
	//	echo "</div>";
		echo $this->table->generate(); 
		echo "</div>";
		echo form_close();
		 echo $pagination;
		
			
	?>
	</div>
	</div>