<body>
  <div  id="map"> </div> 


 <script src="<?php echo base_url();?>js/firebase_364.js"></script>
    <!-- GeoFire -->
    <script src="<?php echo base_url();?>js/geofire.min_410.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAz8HZXjZNQzgMnedxfqSWfogq77NwztJs">
    </script>
  <script>
  var config = {
   apiKey: "<?php echo $api_credentials['WebAPI_Key']; ?>",
  	databaseURL: "<?php echo $api_credentials['DB_URL']; ?>",
  };

  var image = "<?php echo base_url();?>"+"images/marker_m.png";
  firebase.initializeApp(config);
   var firebaseRef = firebase.database().ref();
   var map ;
    var marker = [];
   map = new google.maps.Map(document.getElementById('map'), {
    center: {
      lat: 0,
      lng: 0
    },
    zoom: 2,
    styles: [{
      featureType: 'poi',
      stylers: [{
          visibility: 'off'
        }] // Turn off points of interest.
    }, {
      featureType: 'transit.station',
      stylers: [{
          visibility: 'off'
        }] // Turn off bus stations, train stations, etc.
    }],
    disableDoubleClickZoom: false,
       mapTypeId: google.maps.MapTypeId.ROADMAP,
      streetViewControl: true,
       mapTypeControlOptions: {
      	position: google.maps.ControlPosition.RIGHT_TOP
      },
      zoomControlOptions: {
         position: google.maps.ControlPosition.RIGHT_BOTTOM
     }
  });

  /*map.addListener('click', function(e) {
   firebaseRef_new.push( {
      lat: e.latLng.lat(),
      lng: e.latLng.lng()
    });
    var marker = new google.maps.Marker({
      position: {
        lat: e.latLng.lat(),
        lng: e.latLng.lng()
      },
      map: map
    });
  });*/
var markers = [];
var markers_temp = [];
 // var firebaseRef = firebase.database().ref().push();
   var category_arr = <?php echo json_encode($categories) ?>;
  var i, s, caArray = category_arr, len = caArray.length;
for (i=0; i<len; ++i) {
  if (i in caArray) {
 var firebaseRef_new = firebase.database().ref('drivers_location/'+caArray[i]);
  firebaseRef_new.on("child_added", function(snapshot, prevChildKey) {
  	 var newPosition = snapshot.val();
  	 console.log(newPosition);
  	// console.log(newPosition['geolocation']['l'][1]);
    var latLng = new google.maps.LatLng(newPosition['l'][0],
      newPosition['l'][1]);
     marker = new google.maps.Marker({
      position: latLng,
      map: map,
      icon: image
    });
     markers.push(marker);
  });
  	
   
  }
}

 // Sets the map on all markers in the array.
      function setMapOnAll(map) {
    
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
      }

      // Removes the markers from the map, but keeps them in the array.
      function clearMarkers() {
        setMapOnAll(null);
      }
      function deleteMarkers() {
        clearMarkers();
        markers = [];
      }
 	//var commentsRef = firebase.database().ref().child('KZaEvq_mipwphn6wgmJ');
var infowindow = new google.maps.InfoWindow({
        	
        });
        var geoFire = new GeoFire(firebaseRef);
        var firebaseRef_ch = firebase.database().ref('drivers_location');
firebaseRef_ch.on('child_changed', function(snapshot, prevChildKey) {
   
	
	var newPosition =snapshot.val();
	console.log(newPosition);
	var arr = Object.keys(newPosition).map(function(k) { return newPosition[k] });
	console.log(arr);

	if( 'l' in arr[caArray[i]][0] )  {
		markers_temp = markers ;
		 deleteMarkers();
	}
	
	
	//console.log(Object.keys(newPosition).length)
	// console.log(Object.keys(newPosition)[0][0].lat);
  
       for (var i = 0; i < Object.keys(newPosition).length; i++) {
      
       	if( 'l' in arr[i] )  {
      // 	console.log(arr[i]['l'][0]);
       	   marker = new google.maps.Marker({
        position: new google.maps.LatLng(arr[i]['l'][0], arr[i]['l'][1]),
        map: map,
         icon: image
       });
        markers.push(marker);
       google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(arr[i]['Name']);
          infowindow.open(map, marker);
        				   }
      					})(marker, i));
       }
   //markers.push(marker);
		}
		
		
    
});       

google.maps.event.addDomListener(window, "load");
</script>

</body>
<style type="text/css">
 html,
body {
  height: 100%;
  margin: 0;
  padding: 0;
}

#map {
 height:650px;position:relative;
}
</style>