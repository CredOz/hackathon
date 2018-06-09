<style>

@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1284px)  {
	.res_table td:nth-of-type(1):before { content: "S.No"; }
	.res_table td:nth-of-type(2):before { content: "Food Name" ; }
	.res_table td:nth-of-type(3):before { content: "Preparation Time"; }
	.res_table td:nth-of-type(4):before { content: "Food Image"; }
	.res_table td:nth-of-type(5):before { content: "Action"; }

}  
</style>

		<div class="container">

    <div class="">
    	    	<div class="col-md-12 col-sm-8 col-xs-12 padding_zero" style="padding-bottom: 6px;">
    		  <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
		
	  ?>
    	<div class="col-md-12 col-sm-12 col-xs-12 padding_zero">

    	    	<div class="col-md-9 col-sm-12 col-xs-12">
          <h1 class="page-header-ab" ><?php echo "View all Foods"; ?></h1>
          
          </div>
          
         
              	    	<div class="col-md-2 col-sm-12 col-xs-12">
              	    		<a class="btn-default" href="<?php echo base_url('administrator/settings'); ?>">Add Food</a>
</div>

 </div>
 <div class="col-md-4 col-sm-6 col-xs-12">
 <form id="search" action="<?php echo base_url('administrator/Settings/search'); ?>" method="post" name="search"> 
	<div class="col-md-8 col-sm-6 col-xs-12 btn_text" style="position: relative;top: 15px;">
	<input class="text_box_tb" type="text" class="search_input" placeholder="search" name="keyword" id="tags"/></div>
	<div class="col-md-4 col-sm-6 col-xs-12 btn_sub2" style="float: right;">
	<input class="btn-default btn_sub" type="submit" style="position: relative;top: 0px; margin-left: 10px;" id="submit" name="search" value="Search"  /></div>
	
	<!--<input type="hidden" id="url" name="url" value= ""/>-->
         </form></div>
    	<div class="col-md-12 col-sm-12 col-xs-12 padding_zero">
    		<?php
		
		$tmpl = array (
                    'table_open'          => '<table class="table res_table sortable" border="0" cellpadding="4" cellspacing="0" >',

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
$this->table->set_heading('S.NO','Food Name','Preparation Time','Food Image','Action');

						if($memberUsers->count() < 1)				
						{
					$this->table->add_row("No Records has made!!");		
						}else{
							$i = 1;
	
				 foreach($memberUsers as $document)
				 {
						
			$this->table->add_row(
				$i,
				$document['food_name'],
				$document['prepare_time'], 		
				'<img width="50" height="50" src='.$document['food_image'].' />',
				anchor('administrator/management/memberEdit/'.$document['_id']->{'$id'}, 'Edit').' / '.anchor('administrator/management/memberDelete/'.$document['_id']->{'$id'}, 'Delete',array('onclick' => "return confirm('Do you want delete this record')"))
				);
		$i++;	
		}
						}

				?>	
		<?php
		echo $this->table->generate(); 	
	?>
		
		</div>
		</div> 
    	</div>
		</div> 	</div>
<script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>
	
</html>