<div class="container-fluid">

    <div class="row top-sp">
    	<div class="col-md-10 col-sm-8 col-xs-12">
    		<?php 
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}

	  	//Content of a group
		if(isset($category) and $category->count()>0) 
		{ 

				foreach ($category as $category) {
	  ?>
	  
	 	<h2 class="page-header"><?php echo 'Edit Category'; ?></h2></div>
	 	
			<form method="post" id="myform" enctype="multipart/form-data" action="<?php echo base_url('administrator/category/editcategory').'/'.$category['_id']; ?>" >

<div class="col-md-10 col-sm-8 col-xs-12">
<div class="col-md-3 col-sm-4 col-xs-12"style="top: 10px;">
<?php echo 'Category'; ?><span style="color: red;">*</span></div>
<div class="col-md-8 col-sm-8 col-xs-12">
<input class="text_box" type="text" name="categoryname" id="categoryname" value="<?php echo $category['categoryname']; ?>">
<input class="text_box" type="hidden" name="oldcategoryname" id="oldcategoryname" value="<?php echo $category['categoryname']; ?>">

</div>

<div class="col-md-3 col-sm-4 col-xs-12" style="top: 10px;">
<?php echo "Price per Km"; ?><span style="color:#FF0000">*</span></div>
<div class="col-md-8 col-sm-8 col-xs-12">
<input class="text_box" type="text" name="price_km" id="price_km" value="<?php echo $category['price_km']; ?>">

</div>

<div class="col-md-3 col-sm-4 col-xs-12" style="top: 10px;">
<?php echo "Price per Minute"; ?><span style="color:#FF0000">*</span></div>
<div class="col-md-8 col-sm-8 col-xs-12">
<input class="text_box" type="text" name="price_minute" value="<?php echo $category['price_minute']; ?>">

</div>

<div class="col-md-3 col-sm-4 col-xs-12" style="top: 10px;">
<?php echo "Maximum size"; ?><span style="color:#FF0000">*</span></div>
<div class="col-md-8 col-sm-8 col-xs-12">
<input class="text_box" type="text"  name="max_size" value="<?php echo $category['max_size']; ?>">
</div>

<div class="col-md-3 col-sm-4 col-xs-12" style="top: 10px;">
<?php echo "Minimum fare amount"; ?><span style="color:#FF0000">*</span></div>
<div class="col-md-8 col-sm-8 col-xs-12">
<input class="text_box" type="text" name="price_fare" value="<?php echo $category['price_fare']; ?>">
</span></div>

				
<div class="col-md-3 col-sm-4 col-xs-12" style="top: 10px;">
<?php echo "Prime time percentage"; ?><span style="color:#FF0000">*</span></div>
<div class="col-md-8 col-sm-8 col-xs-12">
<input class="text_box" type="text" name="prime_time_precentage" value="<?php echo $category['prime_time_precentage']; ?>">
</div>

<div class="col-md-3 col-sm-4 col-xs-12" style="top: 10px;">
<?php echo 'Category Logo'; ?><br><span>(78 x 78)</span></div>
<div class="col-md-8 col-sm-8 col-xs-12">
<input class="image_cell"  id="uploadedimage" name="uploadedimage"  size="24" type="file" />
<img border="0" class="thumb_image" src="<?php echo base_url('images/arcane category icons/'.$category['Logo'] )?>" alt="Logo" >
</div>


<div class="col-md-3 col-sm-4 col-xs-12" style="top: 10px;">
<?php echo 'Category Marker'; ?><br><span>(28 x 54)</span></div>
<div class="col-md-8 col-sm-8 col-xs-12">
<input class="image_cell"  id="uploadedmarker" name="uploadedmarker"  size="24" type="file" />
<img border="0" class="thumb_image" src="<?php echo base_url('images/arcane category icons/'.$category['Marker'] )?>" alt="Logo" >
</div>


<div class="col-md-3 col-sm-4 col-xs-12"></div>
<div class="col-md-7 col-sm-8 col-xs-12 btn_sub" >
<input type="hidden" name="categoryid"  value="<?php echo $category['_id'];  ?>"/>
<input class="btn-default" name="submit" type="submit" value="Submit">
</div>
	  	

	</form>
	  <?php
				}
	 }
	  ?>
    </div>
</div>
</div>
<style>
	.error_msg {
    color: red;
}
.btn_sub
{
	padding-left: 27px !important;margin-top: 20px !important;
}
.image_cell
{
padding-left: 22px;
margin: 10px 0px 0px 0px;
}
.thumb_image
{
	padding-left: 23px;
}
</style>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js"></script>
<script type="text/javascript">

$( document ).ready(function() {
 	$("#myform").validate({
        rules: 
	        {
				 'categoryname':
				 {
				  required :true
				  
				  
				 },
				  'max_size':
				 {
				  required :true,
				  number:true
				 },
				  
				 'price_km':
				 {
				  required :true,
				  number: true
				 },
				 'price_minute':
				 {
				  required :true,
				  number: true
				 },
				 'price_fare':
				 {
				  required :true,
				  number: true
				 },
				 'prime_time_precentage':
				 {
				 	required:true,
				 	number:true
				 }					 
			},
		 messages:
		    {
		    	'prime_time_precentage':
		    	{
		    		required:"please check the percentage"
		    	},
				'categoryname':
				{
				  required:"Please enter Category name"
				},
				
				'max_size':
				{
				  required:"Please check the size"
				},
				
				'price_km':
				{
				  required:"Please enter price per kilometer"
				},
				'price_minute':
				{
				  required:"Please enter price per minute"
				},
				'price_fare':
				{
				  required:"Please enter minimum fare amount"
				}
				
			
				
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
