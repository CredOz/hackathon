<script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>
 
            
<div class="container-fluid">

    <div class="row top-sp">
        <div class="col-md-10 col-sm-8 col-xs-12">
             <?php
     
     if($msg = $this->session->flashdata('flash_message'))
            {
                echo $msg;
            }
?>

     <h2 class="page-header"><?php echo 'Add email'; ?></h2></div>
     
    
     
<form autocomplete="off" method="post" id="addemail" action="<?php echo base_url('administrator/members/add_email'); ?>">

<div class="col-md-10 col-sm-8 col-xs-12">
<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">
<?php echo "Email Subject"; ?><span style="color:#FF0000">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="text_box" type="text" name="sub" value=""></div>

<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 0px;">
<?php echo "Email Code"; ?><span style="color:#FF0000" top:0px>*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="text_box" type="text" name="code" value=""></div>

<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">
<?php echo "Email Message"; ?><span style="color:#FF0000">*</span></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<textarea class="text_box" style="width:90%; height:224px;" type="text" name="message" value="" title="Numeric"></textarea></div>



<div class="col-md-2 col-sm-4 col-xs-12"></div>
<div class="col-md-10 col-sm-8 col-xs-12">
<input class="btn-default" type="submit" value="Submit"></div>
</form>
</div>
</div>
</div>
<style>
    .error_msg {
    color: red;
}
</style>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
    $("#addemail").validate({
        rules: 
            {
                 'sub':
                 {
                  required :true,
                 },
                 'code':
                {
                   required :true,
                  
                },
                'message':
                {
                   required :true,
                  
                },
                           
                },
         messages:
            {
                'sub':
                {
                  required:"Please enter the subject",
                },
                'code':
                {
                  required:"Please enter the code"
                },
                'message':
                {
                  required:"Please enter the message"
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
        document.addemail.submit();
    }
    });
  });                   
                 
</script>
