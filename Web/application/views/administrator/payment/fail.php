
<html>
<head>
	<title>Payment Cancelled!</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/Style.css" />
</head>
<body>
<div class="container-fluid">
	<div class="row top-sp">
		<div class="col-md-10 col-sm-8 col-xs-12">
			
<h2 class="page-header"><?php echo "Payment Cancelled!"; ?></h2></div>
<div class="col-md-10 col-sm-8 col-xs-12">
	<div class="center">
	<div class="box-shadow">
 <?php
  echo "Check Driver Credentials"; 
echo anchor('administrator/payment/transaction', '<span class="space"/>Click here to return to the home page' );
?>
</div>
</div>
</div>
</div>
</div>
</body>
</html>