<?php $this->load->view('administrator/header'); ?>
<?php
   echo '<div id="wrapper"><div id="content" style="overflow:hidden; min-height: 540px;">';

   $this->load->view('administrator/sidebar');
    echo '<div id="main"  class="col-md-10 col-sm-10 col-xs-12">';

	$this->load->view($message_element);
	echo '</div></div></div>'; 
	
	$this->load->view('administrator/footer.php');
?>
