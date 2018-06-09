
<div class="container-fluid padding_zero">

    <div class="row top-sp-2 padding_zero">
    	<div class="col-md-10 col-sm-8 col-xs-12">
    		<?php 
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}
	 ?>
	
	 <h2 class="page-header"><?php echo 'API Management'; ?></h2></div>
	 
   	 <script>
$(document).ready(function(){
                    $("#flash").delay(1000).fadeOut('slow');
        });
</script>
<style>
.error_msg {
    color: #ff0000;
    margin-left: 20px;
}
.select_box
{
margin: 10px 0px 0px 23px;
width: 83%;
}
</style>

  	
		
<div class="col-md-12 col-sm-12 col-xs-12">

<?php 
		
		$data=array('id'=>"myform");
		echo form_open('administrator/management/apiconnect',$data); ?>
	
<div class="col-md-3 col-sm-4 col-xs-12 text_box_text">
<?php echo 'Facebook API Key'; ?><span style="color:red;">*</span></div>
<div class="col-md-9 col-sm-8 col-xs-12">
<input class="text_box" type="text" size="70" name="fb_api_key" id="fb_api_key" value="<?php echo $fb_api_key; ?>">
</div>
				
<div class="col-md-3 col-sm-4 col-xs-12 text_box_text" >
<?php echo 'Facebook Secret Key'; ?><span style="color: red;">*</span></div>
<div class="col-md-9 col-sm-8 col-xs-12">
<input class="text_box" type="text"  size="70" name="fb_secret_key" id="fb_secret_key" value="<?php echo $fb_secret_key; ?>">
</div>

<div class="col-md-3 col-sm-4 col-xs-12 text_box_text">
<?php echo 'Google API Key'; ?><span style="color:red;">*</span></div>
<div class="col-md-9 col-sm-8 col-xs-12">
<input class="text_box" type="text" size="70" name="google_api_key" id="google_api_key" value="<?php echo $google_api_key; ?>">
</div>

<div class="col-md-3 col-sm-4 col-xs-12 text_box_text">
<?php echo 'Google project Id'; ?><span style="color:red;">*</span></div>
<div class="col-md-9 col-sm-8 col-xs-12">
<input class="text_box" type="text" size="70" name="google_project_id" id="google_project_id" value="<?php echo $google_project_id; ?>">
</div>

<div class="col-md-3 col-sm-4 col-xs-12 text_box_text">
<?php echo 'Google Client Id'; ?><span style="color:red;">*</span></div>
<div class="col-md-9 col-sm-8 col-xs-12">
<input class="text_box" type="text" size="70" name="Client_ID" id="Client_ID" value="<?php echo $Client_ID; ?>">
</div>

<div class="col-md-3 col-sm-4 col-xs-12 text_box_text">
<?php echo 'Firebase Project Id'; ?><span style="color:red;">*</span></div>
<div class="col-md-9 col-sm-8 col-xs-12">
<input class="text_box" type="text" size="70" name="Project_ID" id="Project_ID" value="<?php echo $Project_ID; ?>">
</div>

<div class="col-md-3 col-sm-4 col-xs-12 text_box_text">
<?php echo 'Firebase API Key'; ?><span style="color:red;">*</span></div>
<div class="col-md-9 col-sm-8 col-xs-12">
<input class="text_box" type="text" size="70" name="WebAPI_Key" id="WebAPI_Key" value="<?php echo $WebAPI_Key; ?>">
</div>

<div class="col-md-3 col-sm-4 col-xs-12 text_box_text">
<?php echo 'Firebase Databse URL'; ?><span style="color:red;">*</span></div>
<div class="col-md-9 col-sm-8 col-xs-12">
<input class="text_box" type="text" size="70" name="DB_URL" id="DB_URL" value="<?php echo $DB_URL; ?>">
</div>

<div class="col-md-3 col-sm-4 col-xs-12 text_box_text">
<?php echo 'Stripe Test Api Key'; ?><span style="color:red;">*</span></div>
<div class="col-md-9 col-sm-8 col-xs-12">
<input class="text_box" type="text" size="70" name="Test_ApiKey" id="Test_ApiKey" value="<?php echo $Test_ApiKey; ?>">
</div>

<div class="col-md-3 col-sm-4 col-xs-12 text_box_text">
<?php echo 'Stripe Test Publish Key'; ?><span style="color:red;">*</span></div>
<div class="col-md-9 col-sm-8 col-xs-12">
<input class="text_box" type="text" size="70" name="Test_PublishKey" id="Test_PublishKey" value="<?php echo $Test_PublishKey; ?>">
</div>

<div class="col-md-3 col-sm-4 col-xs-12 text_box_text">
<?php echo 'Stripe Live Api Key'; ?><span style="color:red;">*</span></div>
<div class="col-md-9 col-sm-8 col-xs-12">
<input class="text_box" type="text" size="70" name="Live_ApiKey" id="Live_ApiKey" value="<?php echo $Live_ApiKey; ?>">
</div>

<div class="col-md-3 col-sm-4 col-xs-12 text_box_text">
<?php echo 'Stripe Live Publish Key'; ?><span style="color:red;">*</span></div>
<div class="col-md-9 col-sm-8 col-xs-12">
<input class="text_box" type="text" size="70" name="Live_PublishKey" id="Live_PublishKey" value="<?php echo $Live_PublishKey; ?>">
</div>

<div class="col-md-3 col-sm-4 col-xs-12 text_box_text">
<?php echo 'Stripe Mode'; ?><span style="color:red;">*</span></div>
<div class="col-md-9 col-sm-8 col-xs-12">
<select name="is_live_stripe" class="select_box" id="is_live_stripe">
	<option value="0" <?php if($is_live_stripe == 0) echo "selected=selected"; ?> >Sandbox</option>
	<option value="1" <?php if($is_live_stripe == 1) echo "selected=selected"; ?> >Live</option>
</select>
</div>

<div class="col-md-5 col-sm-5 col-xs-12"></div>
<div class="col-md-7 col-sm-7 col-xs-12">			
<input class="btn-default" value="<?php echo 'Update'; ?>" name="update" type="submit" >
</div>

		
		<?php echo form_close(); ?>
		</div>
		
    </div>
   </div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
    $("#myform").validate({
        rules: 
            {
                 'fb_api_key':
                 {
                  required :true,
                 },
                 'fb_secret_key':
                {
                   required :true,
                  
                },
                 'google_api_key':
                 {
                  required :true,
                 },
                 'google_project_id':
                {
                   required :true,
                  
                },
                 'Client_ID':
                 {
                  required :true,
                 },
                 'Project_ID':
                {
                   required :true,
                  
                },
                 'WebAPI_Key':
                {
                   required :true,
                  
                },
                 'DB_URL':
                {
                   required :true,
                  
                },
                 'Test_ApiKey':
                {
                   required :true,
                  
                },
                 'Test_PublishKey':
                {
                   required :true,
                  
                },
                 'Live_ApiKey':
                {
                   required :true,
                  
                },
                 'Live_PublishKey':
                {
                   required :true,
                  
                },
                 'is_live_stripe':
                {
                   required :true,
                  
                }
 
            },
               errorClass:'error_msg',
    errorElement: 'div',
    errorPlacement: function(error, element)
    {
        error.appendTo(element.parent());
    }
    
    });
  });                   
</script>