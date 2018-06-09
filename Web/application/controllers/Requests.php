<?php
header('Content-Type: application/json; charset=utf-8');
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
require APPPATH . '/libraries/REST_Controller.php';

class Requests extends REST_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 	 	public function Requests()
	{
		parent::__Construct();
		$this->load->library('mongo_db');
		$this->load->model('Common_model');
		$this->load->model('Drivers_model');
		$this->load->model('Rider_model');
		$this->load->model('Request_model');
	}
  public function index_get()
  {
     $data = array('returned: '. $this->get('books'));
        $this->response(array($data));
  }

  public function index_post()
  {
      $this->response($book, 201); // Send an HTTP 201 Created
  }
  
  	//Set new request
	public function setRequest_get()    // For Rider
  	{
  		
				if(checkisEmpty($this->get()))
		{
				
				$data['pickup']['lat'] = $this->get('start_lat');	
				$data['pickup']['long'] = $this->get('start_long');	
				$data['destination']['lat'] = $this->get('end_lat');	
				$data['destination']['long'] = $this->get('end_long');	
				$data['payment_mode'] = $this->get('payment_mode');	
				$data['rider_id'] = $this->get('userid');	
				$data['category'] = $this->get('category');	
				$data['pickup_address'] = urldecode(utf8_encode($this->get('pickup_address')));	
				$data['drop_address'] = urldecode(utf8_decode($this->get('drop_address')));	
				$data['trip_id'] = "" ; 
				
				$response_array = array();
			if($this->get('category') === NULL || $this->get('userid') === NULL || $data['pickup']['lat'] === NULL || $data['pickup']['long']=== NULL || $data['destination']['lat'] === NULL || $data['destination']['long'] === NULL  || $data['payment_mode'] === NULL ) //Check whether params are passed
       		{
			$final['status']="Fail";
			$final['message']="Missing Parameter.";
			array_push($response_array,$final);
			}
			else
			{
				$data['request_status'] = "processing";
				$data['driver_id'] = "";
				$data['payment_mode'] =$data['payment_mode'];
				$data['eta'] = "";
				$data['driver_location'] = "";
				$data['driver_category'] = "";
				$data['created'] = time();
				$result=$this->Request_model->insert_data('request', $data);
				$data['request_id'] = $data['_id']->{'$id'};
				unset($data['_id']);
				array_push($response_array,$data);
			    //print_r(json_encode(array($data),JSON_UNESCAPED_SLASHES));exit;		
			}
				
		}else{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";	
				array_push($response_array,$final);
		}
		        $this->response($response_array, REST_Controller::HTTP_OK);
	
	
	}


	public function getRequest_get()   // For Rider
  	{
  		
				if(checkisEmpty($this->get()))
		{
				$request_id = $this->get('request_id');	
				$response_array = array();
			if($this->get('request_id') === NULL ) //Check whether params are passed
       		{
			$final['status']="Fail";
			$final['message']="Missing Parameter.";
			array_push($response_array,$final);
			}
			else
			{
				
				$result=$this->Request_model->find_data('request',array("_id" => new MongoId($request_id) ) );
					foreach($result as $getRequestData){}
					$getRequestData['request_id'] = $getRequestData['_id']->{'$id'};
					unset($getRequestData['_id']);
				array_push($response_array,$getRequestData);
			    //print_r(json_encode(array($data),JSON_UNESCAPED_SLASHES));exit;		
			}
				
		}else{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";	
				array_push($response_array,$final);
		}
		        $this->response($response_array, REST_Controller::HTTP_OK);
	
	
	}
	
	public function updateRequest_get()   //For Driver
  	{
  		
				if(checkisEmpty($this->get()))
		{
				$request_id = $this->get('request_id');	
				$data['request_status'] = $this->get('request_status');	 // "accept" , "no_driver" , "cancel"
				$data['driver_id'] = $this->get('driver_id');	
				$data['driver_location']['lat'] = $this->get('lat');	
				$data['driver_location']['long'] = $this->get('long');	
			$response_array = array();
			if($request_id === NULL || $this->get('driver_id') === NULL || $data['driver_location']['lat'] === NULL || $data['driver_location']['long'] === NULL || $data['request_status']  === NULL ) //Check whether params are passed
       		{
			$final['status']="Fail";
			$final['message']="Missing Parameter.";
			array_push($response_array,$final);
			}
			else
			{
				$updatekey = array("_id"=> new MongoId($request_id));
				$update_data = $data;
				unset($update_data['$request_id']);
				$this->Request_model->update_request($updatekey, $update_data);
				$result=$this->Request_model->find_data('request',array("_id" => new MongoId($request_id) ) );
					foreach($result as $getRequestData){}
					$getRequestData['request_id'] = $getRequestData['_id']->{'$id'};
					unset($getRequestData['_id']);
				array_push($response_array,$getRequestData);
			    //print_r(json_encode(array($data),JSON_UNESCAPED_SLASHES));exit;		
			}
				
		}else{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";	
				array_push($response_array,$final);
		}
		        $this->response($response_array, REST_Controller::HTTP_OK);
	
	
	}

	public function cancelRequest_get()   //For Driver
  	{
  		
				if(checkisEmpty($this->get()))
		{
				$request_id = $this->get('request_id');	
				$data['request_status'] = 'cancel';	 // "cancel"
				
			$response_array = array();
			if($request_id === NULL) //Check whether params are passed
       		{
			$final['status']="Fail";
			$final['message']="Missing Parameter.";
			array_push($response_array,$final);
			}
			else
			{
				$updatekey = array("_id"=> new MongoId($request_id));
				$update_data = $data;
				
				$this->Request_model->update_request($updatekey, $update_data);
				$result=$this->Request_model->find_data('request',array("_id" => new MongoId($request_id) ) );
					foreach($result as $getRequestData){}
					$getRequestData['request_id'] = $getRequestData['_id']->{'$id'};
					unset($getRequestData['_id']);
				array_push($response_array,$getRequestData);
			    //print_r(json_encode(array($data),JSON_UNESCAPED_SLASHES));exit;		
			}
				
		}else{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";	
				array_push($response_array,$final);
		}
		        $this->response($response_array, REST_Controller::HTTP_OK);
	
	
	}


	public function processRequest_get()   // For Rider
	{

			if(checkisEmpty($this->get()))
		{
				$request_id = $this->get('request_id');	
				$response_array = array();
			if($this->get('request_id') === NULL ) //Check whether params are passed
       		{
			$final['status']="Fail";
			$final['message']="Missing Parameter.";
			array_push($response_array,$final);
			}
			else
			{
				
			require_once APPPATH.'libraries/firebase-php/firebase_config.php';
				
					$result=$this->Request_model->find_data('request',array("_id" => new MongoId($request_id) ) );
					foreach($result as $getRequestData){}
					 $lat  = $getRequestData['pickup']['lat'] ;
					 $long= $getRequestData['pickup']['long'] ;
					 $category= $getRequestData['category'] ;
					 
				//	get_nearby_drivers( $lat , $long );
				$get_config = GetLimitConfig();
				$maxDistance = (intval($get_config['nearby_distance'])*1000) >0 ? intval($get_config['nearby_distance'])*1000 : 10000 ;	
				$request_duration_time = intval($get_config['request_duration_time']) >0 ? intval($get_config['request_duration_time']) : 10;	
				
			//Getting nearby drivers and process with FB starts 		
			
			$this->mongo_db->db->drivers->ensureIndex(array("location" => "2dsphere"));	// Set this index to get the nearby drivers
			      
     		$coll = $this->mongo_db->db->drivers;
    		 $condition = array(
                'location' => array(
                    '$nearSphere' => array(
                        '$geometry' => array(
                            'type' => "Point",
                            'coordinates' => array(floatval($long), floatval($lat))
                        ),
                        '$maxDistance' => $maxDistance
                    )
                ), 'online_status' => '1' , 'category' => $category 
				);

   				 $cursor = $coll->find($condition);
					 $count_drivers = $cursor->count() ; 
						$i = 0 ;
						
				if($getRequestData['request_status'] != 'cancel') { // Don't process if cancelled		
				if($count_drivers >=1 ){	
    			foreach ($cursor as $doc) { // Foreach starts
				if($getRequestData['request_status'] != 'cancel') { // Don't process if cancelled in the mid		
		 				
		 			$current_status = $firebase->get('drivers_data/'.$doc['_id']->{'$id'}); // Get driver data from fb
	
    					if(($current_status['request']['status'] == 0 || $current_status['request']['status'] == "")  &&  ($current_status['accept']['status'] == 0 ||  $current_status['accept']['status'] == '')){  // Check driver data 
    					
    					$get_dist = GetDrivingDistance($doc['lat'],$doc['long'],$getRequestData['pickup']['lat'] ,$getRequestData['pickup']['long'] );
				
						$firebase->update(array(
						'request/status' => 1,
						'request/eta' => $get_dist['time'],
    					'request/req_id' => $request_id,
						),'drivers_data/'.$doc['_id']->{'$id'}); // Update driver req status in fb
	
						}
	
					sleep($request_duration_time);   // Wait for driver accept time
	
					$current_status = $firebase->get('drivers_data/'.$doc['_id']->{'$id'}); // Again Get driver data from fb
	
						if( $current_status['request']['status'] == 1 
						&&  $current_status['request']['req_id'] == $request_id 
						&&  $current_status['accept']['status'] == 1 ) // Check driver accepted or not
						{
				$data_trip['pickup']['lat'] =  $getRequestData['pickup']['lat'] ;
				$data_trip['pickup']['long'] =  $getRequestData['pickup']['long'] ;
				$data_trip['destination']['lat'] = 	 $getRequestData['destination']['lat'] ;
				$data_trip['destination']['long'] = 	 $getRequestData['destination']['long'] ;
				$data_trip['pickup_address'] = 	 $getRequestData['pickup_address'] ;
				$data_trip['drop_address'] = 	 $getRequestData['drop_address'] ;
				$data_trip['payment_mode'] = 	 $getRequestData['payment_mode'] ;
				$data_trip['rider_id'] = $getRequestData['rider_id'];	
				$data_trip['accept_status'] = "1"; // Accepted
				$data_trip['trip_status'] = "off";
				$data_trip['driver_id'] = $doc['_id']->{'$id'};
				//$get_dist = GetDrivingDistance($doc['lat'],$doc['long'],$getRequestData['pickup']['lat'] ,$getRequestData['pickup']['long'] );
				
				//$data_trip['eta'] = $get_dist['time'];
				$data_trip['total_price'] = "";
				$data_trip['driver_location']['lat'] =  $doc['lat'];
				$data_trip['driver_location']['long'] =  $doc['long'];
				$data_trip['car_category'] =  $category;
				$data_trip['request_id'] = $request_id;
				$data_trip['created'] = time();
				$result=$this->Request_model->insert_data('trips', $data_trip);  // Insert into trips
				$data_trip['trip_id'] = $data_trip['_id']->{'$id'};
				
				$updatekey = array("_id"=> new MongoId($request_id));
				$update_data_req['trip_id'] = $data_trip['trip_id'];
				$this->Request_model->update_request($updatekey, $update_data_req); // Update trip id in request table
							  
				unset($data_trip['_id']);
				
				$firebase->update(array(
						'accept/trip_id' => $data_trip['trip_id']
						),'drivers_data/'.$doc['_id']->{'$id'});
						
				 $response_array = $data_trip ;
				break;
						}
						else 
						{
						$firebase->update(array(
						'request/status' => 0,
						'request/eta' => 0,
    					'request/req_id' => ''
						),'drivers_data/'.$doc['_id']->{'$id'});	// Update driver req status as not accepted fb
	
							if ($i == $count_drivers - 1) {
                			  $updatekey = array("_id"=> new MongoId($request_id));
							  $update_data['request_status'] = "no_driver"	;
							  $this->Request_model->update_request($updatekey, $update_data);
							}
                       $result=$this->Request_model->find_data('request',array("_id" => new MongoId($request_id) ) );
					   foreach($result as $getRequestData){}
	                   $response_array = $getRequestData ;
						}

					}else{
						      $result=$this->Request_model->find_data('request',array("_id" => new MongoId($request_id) ) );
							  foreach($result as $getRequestData){}
							   $response_array = $getRequestData ;
					break ;	
					}
   				 $i++ ; 
				} //Foreach ends		
				}else{ // No Drivers in DB
					 		 $updatekey = array("_id"=> new MongoId($request_id));
							  $update_data['request_status'] = "no_driver"	;
							  $this->Request_model->update_request($updatekey, $update_data);
							  $result=$this->Request_model->find_data('request',array("_id" => new MongoId($request_id) ) );
							  foreach($result as $getRequestData){}
							   $response_array = $getRequestData ;
				}	
				}else{
						      $result=$this->Request_model->find_data('request',array("_id" => new MongoId($request_id) ) );
							  foreach($result as $getRequestData){}
							   $response_array = $getRequestData ;
					
					}	
			//Getting nearby drivers and process with FB ends 		
			
}
				
		}else{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";	
				array_push($response_array,$final);
		}
		        $this->response(array($response_array), REST_Controller::HTTP_OK);
	
	}
	
	
	public function getTrips_get()   // For Rider
  	{
  		
				if(checkisEmpty($this->get()))
		{
				$trip_id = $this->get('trip_id');	
				$response_array = array();
			if($this->get('trip_id') === NULL ) //Check whether params are passed
       		{
			$final['status']="Fail";
			$final['message']="Missing Parameter.";
			array_push($response_array,$final);
			}
			else
			{
				
				$result=$this->Request_model->find_data('trips',array("_id" => new MongoId($trip_id) ) );
					foreach($result as $getTripsData){}
					$getTripsData['request_id'] = $getTripsData['_id']->{'$id'};
					$driverData  =  getUserData( 'drivers',  new MongoId($getTripsData['driver_id']) );
					$driverProfile = $driverData['profile_pic'];
					$getTripsData['driver_profile'] = $driverProfile;
					$getTripsData['driver_name'] = $driverData['first_name']." ".$driverData['last_name'];
					unset($getTripsData['_id']);
				array_push($response_array,$getTripsData);
			    //print_r(json_encode(array($data),JSON_UNESCAPED_SLASHES));exit;		
			}
				
		}else{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";	
				array_push($response_array,$final);
		}
		        $this->response($response_array, REST_Controller::HTTP_OK);
	
	
	}
	
	public function updateTrips_get()   //For Driver
  	{
  		
				if(checkisEmpty($this->get()))
		{
				$trip_id = $this->get('trip_id');	
				$data['trip_status'] = $this->get('trip_status');	// on,off,end
				$data['accept_status'] = $this->get('accept_status'); // 2=> Arrive ,3=> Begin, 4 => End, 5 => Cancel
				$data['total_distance'] = $this->get('distance');
				if($this->get('total_amount'))
				{
				$total_price = ($this->get('total_amount') * 100);
					
				}else{
					
				$total_price = ($price_per_km * $data['total_distance'] * 100);					
				}
				
				
			$response_array = array();
			if( $trip_id === NULL || $data['trip_status'] === NULL || $data['accept_status'] === NULL ) //Check whether params are passed
       		{
			$final['status']="Fail";
			$final['message']="Missing Parameter.";
			array_push($response_array,$final);
			}
			else
			{
				$get_config = GetLimitConfig();
				$price_per_km = $get_config['price_per_km'];
				
				require_once APPPATH.'libraries/firebase-php/firebase_config.php';
				
					$result=$this->Request_model->find_data('trips',array("_id" => new MongoId($trip_id) ) );
					foreach($result as $getTripsData){}
					$getTripsData['trip_id'] = $getTripsData['_id']->{'$id'};
					unset($getTripsData['_id']);
					$updatekey = array("_id"=> new MongoId($trip_id));
										
					if($data['accept_status'] == 4){   // Ending the trip; clear all status for driver
					// Payment process 
					if($getTripsData['payment_mode'] == "stripe"){
					require_once APPPATH.'libraries/stripe/init.php';
					$get_settings = GetSettings();
					\Stripe\Stripe::setApiKey($get_settings['Test_ApiKey']);
					$getData  =  getUserData( 'riders', $getTripsData['rider_id']);
					$customer_id = $getData['s_customerid'];
					 $charge = \Stripe\Charge::create(array(
     				 'customer' => $customer_id,
    				  'amount'   => $total_price,
     	 				'currency' => 'usd'
  						));
  					$data_finance['s_transid'] = $charge ->id ; 
					}
					$firebase->update(array(
						'request/status' => 0,
						'request/eta' => 0,
    					'request/req_id' => '',
    					'accept/status' => 0,
    					'accept/trip_id' => ''
						),'drivers_data/'.$getTripsData['driver_id']);	// Update driver req status as not accepted fb
					 
					$data_finance['total_price'] = ($total_price/100);
					$data_finance['total_distance'] = $data['total_distance'];
					$data_finance['payment_status'] = 'completed';
					$data_finance['trip_status'] = $data['trip_status'];
					$data_finance['accept_status'] = $data['accept_status'];
					
					$this->Request_model->update_trips($updatekey, $data_finance);
					
					}
					else{
					
					$data_finance['total_price'] = ($total_price/100);
					$data_finance['total_distance'] = $data['total_distance'];
					$data_finance['payment_status'] = 'Not completed';
					$data_finance['trip_status'] = $data['trip_status'];
					$data_finance['accept_status'] = $data['accept_status'];
					
					$this->Request_model->update_trips($updatekey, $data_finance);
				}
			$result=$this->Request_model->find_data('trips',array("_id" => new MongoId($trip_id) ) );
			foreach($result as $getTripsData){}
			$getTripsData['trip_id'] = $getTripsData['_id']->{'$id'};
			unset($getTripsData['_id']);
			array_push($response_array,$getTripsData);
					
		 	}
				
		}else{
				$response_array = array();
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";	
				array_push($response_array,$final);
		}
		        $this->response($response_array, REST_Controller::HTTP_OK);
	
	
	}

	public function yourTrips_get()   // For Rider
  	{
  		
				if(checkisEmpty($this->get()))
		{
				$userid = $this->get('userid');	
				$response_array = array();
			if($userid === NULL ) //Check whether params are passed
       		{
			$final['status']="Fail";
			$final['message']="Missing Parameter.";
			array_push($response_array,$final);
			}
			else
			{
				
				$result=$this->Request_model->find_data('trips',array("rider_id" => $userid ) );
				foreach($result as $getTripsData){
					$driverData  =  getUserData( 'drivers',  new MongoId($getTripsData['driver_id']) );
					$driverProfile = $driverData['profile_pic'];
					$getTripsData['trip_id'] = $getTripsData['_id']->{'$id'};					
					$getTripsData['driver_profile'] = $driverProfile;
					$getTripsData['driver_name'] = $driverData['first_name']." ".$driverData['last_name'];
					unset($getTripsData['_id']);
					array_push($response_array,$getTripsData);
					}
					
				
			    //print_r(json_encode(array($data),JSON_UNESCAPED_SLASHES));exit;		
			}
				
		}else{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";	
				array_push($response_array,$final);
		}
		        $this->response($response_array, REST_Controller::HTTP_OK);
	
	
	}

      /*public function getEarning_get()   // For Rider
  	{
  		$totalprice=0;
				if(checkisEmpty($this->get()))
		{
				$userid = $this->get('userid');	
				$response_array = array();
			if($userid === NULL ) //Check whether params are passed
       		{
			$final['status']="Fail";
			$final['message']="Missing Parameter.";
			array_push($response_array,$final);
			}
			else
			{
				
				$result=$this->Request_model->find_data('trips',array("driver_id" => $userid));
				
					foreach($result as $document)
										{
										 $totalprice = $totalprice + $document['total_price']; 
										 }
										
				$final['status']="Success";
				$final['total_earning']=$totalprice;	
				array_push($response_array,$final);
			
			}
				
		}else{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";	
				array_push($response_array,$final);
		}
				$this->response($response_array, REST_Controller::HTTP_OK);
	
	
	}*/
	
	
	
	
	//Rough function	
/*function get_nearby_drivers_get( $lat, $long )
{

echo "<pre>" ; 
$lat = 78.119775 ; 
$long = 9.925201 ;




     $coll = $this->mongo_db->db->places;
     $condition = array(
                'loc' => array(
                    '$nearSphere' => array(
                        '$geometry' => array(
                            'type' => "Point",
                            'coordinates' => array(9.925201, 78.119775)
                        ),
                        '$maxDistance' => 140000
                    )
                ), 'online_status' => 1 
				);

    $cursor = $coll->find($condition);
	$count_drivers = $cursor->count() ; exit ;
	$i = 0 ;
    foreach ($cursor as $doc) {
		
	$current_status = $firebase->get('users_location/'.$doc['_id']->{'$id'}); // Get driver data from fb
	
    if($current_status['request']['status'] == 0 &&  $current_status['accept']['status'] == 0){  // Check driver data 
    
		$firebase->update(array(
		'request/status' => 1,
    	'request/req_id' => '123456'
		),'users_location/'.$doc['_id']->{'$id'}); // Update driver req status in fb
	
	}
	
	sleep(6);   // Wait for driver accept time
	
	$current_status = $firebase->get('users_location/'.$doc['_id']->{'$id'}); // Again Get driver data from fb
	
	if( $current_status['request']['status'] == 1 
	&&  $current_status['request']['req_id'] == 123456 
	&&  $current_status['accept']['status'] == 1 ) // Check driver data 
	{
	echo "inside req 1";
	break ; 	
	}
	else 
	{
	$firebase->update(array(
	'request/status' => 0,
    'request/req_id' => ''
	),'users_location/'.$doc['_id']->{'$id'});	// Update driver req status as not accepted fb
	
	if ($i == $count_drivers - 1) {
    
    }
	
	}

    $i++ ; 
	}
	
	
//$firebase->set($data, 'users_location/'.$doc['_id']->{'$id'});
echo $doc['_id']->{'$id'};


}*/

	
	
  		

}