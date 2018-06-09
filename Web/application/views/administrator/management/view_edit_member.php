 <script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js"></script>
 
 <script type="text/javascript">
 	$(document).ready(function()
{
	$("#user_edit").validate({
     rules: {
                Fname: { 
                	required: true,
                	minlength: 4,
                	maxlength: 25
                },
                Lname: { 
                	minlength: 4,
                	maxlength: 25
                },
                email_value: { 
                	required: true,
                	email:true,
                	
                },
                mobile: { 
                	required: true,
                	digits : true,
                	maxlength : 15,
					minlength : 10  
                }
            },
            messages:
		    {
				email_value:
				{
				  required:"This field is required",
				}, 
           },
           errorClass:'error_msg',
    errorElement: 'div',
    errorPlacement: function(error, element)
    {
        error.appendTo(element.parent());
    },
	});
});
</script>

<div class="container-fluid">
    <div class="row top-sp">
    	<div class="col-md-10 col-sm-8 col-xs-12">
<h2 class="page-header"><?php echo ('Edit Member'); ?></h2></div>
<?php foreach($MemberDetails as $Member) {
		
	$MemberId = $Member['_id'];
	
	$food_name  = $Member['food_name'];
	if($food_name == 'null') $food_name = '';
		
	$category_id = $Member['category_id'];
	$food_description = $Member['food_description'];	
	$food_ingredient = $Member['food_ingredient'];
	$food_preparation = $Member['food_preparation'];
	$food_image = $Member['food_image'];
	$prepare_time = $Member['prepare_time'];

}
?>

<div class="col-md-10 col-sm-8 col-xs-12">
<form action="<?php echo base_url('administrator/management/memberEdit/'.$MemberId ); ?>" method="post" id="user_edit" name="user_edit">
	<input type="hidden" id="id_driver" value="<?php echo $MemberId; ?>">

	    	<div class="col-md-10 col-sm-8 col-xs-12">
    	<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 12px;left: 40px">
			<?php echo 'Food Name'; ?><span style="color:#FF0000">*</span></div>
    	<div class="col-md-10 col-sm-8 col-xs-12">
			<input class="text_box" type="text" size="55" name="food_name" value="<?php echo $food_name; ?>">
		</div>
		<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 12px;left: 38px">	
		<?php echo 'Category Name'; ?><span style="color:#FF0000">*</span></div>
		<div class="col-md-10 col-sm-8 col-xs-12">
			<select class="text_box" name="category_id">
				<?php 
								$response=$this->mongo_db->db->category->find();
							if($response->count() >= 1){
								foreach($response as $document)
								{ ?>
					<option value="<?php echo $document['category_name'] ; ?>" <?php if($category_id == $document['category_name']) echo "selected=selected";  ?>  ><?php echo $document['category_name'] ; ?></option>								
									<?php
								}
							}else{
								?>
				<option value="veg">Vegetarian</option>
				<option value="non-veg">Non-vegetarian</option>		
			<?php	}
				?>

			</select>		
		</div>
		<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 12px;left: 38px">
			<?php echo 'Food Description'; ?><span style="color:#FF0000">*</span></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
			<textarea name="food_description" class="text_box" value="<?php echo $food_description; ?>" cols="60" rows="4"><?php echo $food_description; ?></textarea>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 5px;left: 38px">
			<?php echo 'Food Preparation'; ?><span style="color:#FF0000">*</span></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
			<textarea name="food_preparation" class="text_box" value="<?php echo $food_preparation; ?>" cols="60" rows="4"><?php echo $food_preparation; ?></textarea>
			</div>    
			
			<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top:7px;left:36px;">
			<?php echo 'Food Image'; ?><span style="color:#FF0000">*</span></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
			<input style="margin:10px 0px 0px 20px;" id="new_photo_image" name="logo"  size="24" type="file" /></div>
			
			<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 5px;left: 38px">
			<?php echo 'Food Ingredient'; ?><span style="color:#FF0000">*</span></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
			<textarea name="food_ingredient" value="<?php echo $food_ingredient; ?>" rows="4" cols="60" class="text_box"><?php echo $food_ingredient; ?></textarea>
			</div>
			
			<div class="col-md-2 col-sm-4 col-xs-12 text_box_text"style="position: relative;top: 5px;left: 38px">
			<?php echo 'Preparation Time'; ?><span style="color:#FF0000">*</span></div>
			<div class="col-md-10 col-sm-8 col-xs-12">
			<input class="text_box" type="text" size="55" name="prepare_time" value="<?php echo $prepare_time; ?>" id="timepicker">
			</div>



<div class="col-md-2 col-sm-4 col-xs-12"></div>
<div class="col-md-1 col-sm-1 col-xs-12">
<input class="btn-default" type="submit" name="editmember"style="position: relative;left: -8px;" value="<?php echo ("Update"); ?>"></div>
<div class="col-md-1 col-sm-1 col-xs-12">
<input class="btn-default" type="submit" name="editmember" value="<?php echo ("Cancel"); ?>"></div>
<!--<p style="margin-left: 222px;">
<button type="submit" class="btn pink gotomsg"  name="editdriver"><span><span><?php echo ("Update"); ?></span></span></button>
</p> -->
</div>
</form>	
</div>		
</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#send_message').click(function(){
			var driver_id=$('#id_driver').val();
			var driver_mobile_no=$('#driver_mobile_no').val();
			 $.ajax({
            url: "<?php echo base_url()?>administrator/management/send_message",             
            type: "POST",
            data: 'driver_id='+driver_id+'&driver_mobile_no='+driver_mobile_no,             
            cache: false,             
             
            success: function (data) {  
            	var message=$.trim(data); 
if(message == 'Success')
{
	alert('Message sent successfully');
	location.reload(true);
	}
else
{
	//alert('Message not send'); return false;
}
            }
            });
		})
		$('#reject').click(function(){
			var driver_id=$('#id_driver').val();
			
			 $.ajax({
            url: "<?php echo base_url()?>administrator/management/reject",             
            type: "POST",
            data: 'driver_id='+driver_id,             
            cache: false,             
             
            success: function (data) {  
            	location.reload(true);
            }
            });
		})
	})


</script>

 <script>
      // This example displays an address form, using the autocomplete feature
      // of the Google Places API to help users fill in the information.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      var placeSearch, autocomplete;
      var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
      };

      function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete"
        async defer></script>