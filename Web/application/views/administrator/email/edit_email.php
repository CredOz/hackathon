 <style>
     
     .check_error
     {
         
         margin-left:10px !important;
         text-align: left !important;
     }
     .clslog_form label
 	{
 		width:20%;
 	}
 </style>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js"></script>
<script type="text/javascript">
        
$(document).ready(function() {
   
   $("#myform").validate({
        rules: {
                     
                email:{
                 required: true,
                 email: true,
            <?php /*  remote: "<?php echo site_url('home');?>/check_login_email" */?>
                 
            },
                 message:{
                 required: true,
                                         
            },
                 code:{
                 required: true,
                                         
            },
             sub:{
                 required: true,
                                         
            },
            
        },
        messages: {
             
            message: {
                required: "Email Message Required",
                 },
            code: {
                required: "Email Code Required",
                 },
                 sub: {
                required: "Email Subject Required",
                 },
          
        }
    });
    });
 </script>
 
 
<div class="container-fluid">

    <div class="row top-sp">
    	<div class="col-md-10 col-sm-8 col-xs-12">
	<h2 class="page-header">Edit Template</h2> 
           </div>
         
          <?php
 //print_r($message);exit;
 
  foreach($message as $msg) {
  	//echo $msg['errorcode']."ram";exit;  ?>           



 <form autocomplete="off" method="post" id="myform" action="<?php echo base_url('administrator/members/edit_email').'/'.$msg['_id']; ?>" style="margin-top: 15px;">
                  
                  <div class="col-md-10 col-sm-8 col-xs-12">
    	<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">
                	Email Subject<span style="color:red;">*</span></div>                        
                	    <div class="col-md-10 col-sm-8 col-xs-12"><input class="text_box" type="text" name="sub" value="<?php echo $msg['subject'];?>" title="Numeric"/></div>
                		
                		<div class="col-md-2 col-sm-4 col-xs-12 text_box_text">Email Code<span style="color:red;">*</span></div>
                        <div class="col-md-10 col-sm-8 col-xs-12"><input class="text_box" type="text" name="code" value="<?php echo $msg['code'];?>" title="Numeric"/></div>
 
                        <div class="col-md-2 col-sm-4 col-xs-12 text_box_text">Email Message<span style="color:red;">*</span></div>
                        <!--<textarea class="focus" type="text" name="message" value="" hight="40px" width="100px" /></textarea>-->
                    	<div class="col-md-10 col-sm-8 col-xs-12"><textarea class="text_box" style="width:90%; height:224px;" type="text" name="message" value="" title="Numeric"><?php echo $msg['message']?></textarea></div>
 
                    	<div class="col-md-2 col-sm-4 col-xs-12"></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
                        <input class="btn-default" name="addmessaeg" type="submit" value="Submit"></div>


            
                
                        
                         
                </p>
     <?php    } ?>   </form>
       </div>
        <div class="clear"></div>
</div>
<!--END OF CONTENT-->
</div>
  
  
 <?php   echo '</div></div></div>';
?>
 