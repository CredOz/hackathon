<?php 
    $this->load->view('administrator/header.php');
?>    

<?php
    echo '<div id="page-wrapper2"><div id="page-inner2">';

   // $this->load->view('admin/sidebar');
   
 
    echo '<div id="">';
?>

<style>
	.error_msg {
    color: white;
}
header{
	display:none;
}
.footer{
	display:none;
}
.page-header,.text_box_text{
	color: white;
}
.top-sp{
	background: linear-gradient(230deg,#a24bcf,#4b79cf,#4bc5cf);
}
.login-hg
{
	height:800px;
}
</style>


<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js"></script>
<script src="<?php echo base_url() ?>js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>js/jquery-ui.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>js/jquery.validation.js" type="text/javascript"></script>

<?php 
        $this->load->library('mongo_db');
        $category = $this->mongo_db->db->admin->find();
       foreach ($category  as $cat)
	   {
        $name=$cat['username'];
       $passwd=$cat['password'];
       }
         ?>

<script type="text/javascript">
$( document ).ready(function() {
 	$("#myform").validate({
        rules: 
	        {
				 'username':
				 {
				  required :true,
				 },
				 'password':
				 {
				  required:true,
				  
				 
				 },
			},
		 messages:
		    {
				'username':
				{
				  required:"Please enter your username",
				},
				'password':
				{
				  required:"Please enter your password",
				},
		    },
    errorClass:'error_msg',
    errorElement: 'div',
    errorPlacement: function(error, element)
    {
        error.appendTo(element.parent());
    },
   
    submitHandler: function()
    {
        document.myform.submit();
    }
    });
  });	
		
				 
</script>
 
 <style>
 	.check_error{
 		margin-left:85px !important;
 		width: 170px !important;
 	}
 	.login{
 		padding: 0px 5px;
 	}
 </style>

<!--CONTENT-->
<div class="container-fluid">

    <div class="row top-sp login-hg">
    	<div class="col-md-12 col-sm-12 col-xs-12">
        <h2 class="page-header0">Admin Panel</h2>
        <?php	if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		} ?>
        </div>
            <form style="float:left;" autocomplete="off" method="post" id="myform" action="<?php echo base_url('administrator/admin/login'); ?>">
   
        <div class="container">
        	<div class="col-md-offset-5 col-md-8">
                <div class="col-md-2 col-sm-4 col-xs-12 text_box_text" >
                        Username<span>*</span></div>
                        <div class="col-md-10 col-sm-8 col-xs-12"><input class="text_box_admin" type="text" name="username" id="username" value=""/></div>
 
                        <div class="col-md-2 col-sm-4 col-xs-12 text_box_text" >Password<span>*</span></div>
                        <div class="col-md-10 col-sm-8 col-xs-12"><input class="text_box_admin" type="password" name="password" id="password" value=""/></div>
                		
                		
                		<div class="col-md-2 col-sm-4 col-xs-12"></div>
                		<div class="col-md-2 col-sm-2 col-xs-6 padding_zero_1">
                		<input class="btn-default" name="loginAdmin" id="admin_login" type="submit" value="Submit"></div>
                		<div class="col-md-2 col-sm-2 col-xs-6 padding_zero padding_zero_1">
                        <input class="btn-default" name="reset" type="reset" value="Reset"></div>
  <!--                      <a href="<?php echo base_url('administrator/forgotpassword/'); ?>">Forget password?</a>
        
        <p class="login-text1">Use a valid email and password to gain access to the Administrator Back-end.</p>
        <p class="login-text2"><a href="<?php echo base_url(); ?>">Return to site Home Page</a></p>-->
<!--END OF CONTENT-->
</div>
</div>
</div>




  <?php  echo '</div></div></div>';
?>
<?php 
    $this->load->view('administrator/footer.php');
?>    
