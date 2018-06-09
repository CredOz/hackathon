<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


	function check_logged() {
	$CI =& get_instance();	
	if($CI->session->userdata('logged_in'))
    return true;
    else
    return false;
    }
	
	function get_usernamebyid($userid) {
		$CI =& get_instance();
		$CI->load->library('mongo_db');	
		
		  $result = $CI->mongo_db->db->riders->find(array("_id"=> new MongoId($userid)));
		  
		  foreach($result as $res) {
		  	$username =$res['username'];
	  }
		  return @$username;
	}
	
	function get_userProfile($userid) {
			$CI =& get_instance();
			$CI->load->library('mongo_db');	
		
	  		error_reporting(0);
		 		
		 	$result = $CI->mongo_db->db->settings->find();
			$data = ""; 
			$apikey = '';
		  foreach($result as $res) {	
  		  	$apikey = $res['google_api_key'];				
		  }
		  $url = 	 "https://www.googleapis.com/plus/v1/people/".$userid."?fields=image&key=".$apikey ; 
			$geocode     = file_get_contents($url);
			if($geocode)
			{
			$output      = json_decode($geocode);
			$data = $output->image->url;				
			//echo $data ;
					
			}else{
			$data = "";	
			}
			
			if($data == "")
			{
				
			$forJson = file_get_contents('http://picasaweb.google.com/data/entry/api/user/'.$userid.'?alt=json', true);
			
			$withowtBacs = str_replace('$','',$forJson);
			$toArr = json_decode($withowtBacs);			
			$imgPath = $toArr->entry->gphotothumbnail;
			if(isset($imgPath))
			{
			$data = 	$imgPath->t."?imgmax=560&crop=1&height=160&width=160" ;
				
			}else{
			$data = "";	
			}			
			}
	return $data;
			
		}
		function GetRiderNameByID($RiderId) {
		$CI =& get_instance();
		$CI->load->library('mongo_db');	
		
		  $result = $CI->mongo_db->db->riders->find(array("_id"=>new MongoId($RiderId)));
		  
		  foreach($result as $res) {
		  	return urldecode($res['first_name']);
		  }
		}	
		function GetDriverNameByID($DriverId) {
		$CI =& get_instance();
		$CI->load->library('mongo_db');	
		
		  $result = $CI->mongo_db->db->drivers->find(array("_id"=>new MongoId($DriverId)));
		  
		  foreach($result as $res) {
		  	return urldecode($res['first_name']);
		  }
		}
		function updateDriverDetails($userid,$lat,$long,$regid,$devicetoken)
		{
			$CI =& get_instance();
			$CI->load->library('mongo_db');
			$update_data['last_login']=time();	
			$key=array('_id'=>new MongoId($userid));
			$lat = $update_data['lat']= $lat;
			$long = $update_data['long']= $long;		
			$str = '{ "coordinates": [ '.$long.', '.$lat.' ],"type": "Point" }';
			$str_update = json_decode($str);
			$update_data['location'] = $str_update ;

			if($regid != "")
			{
				$update_data['reg_id']=$regid;
				
			}
		    if($devicetoken != "")
		    {
				$update_data['devicetoken']=$devicetoken;
		    }
			
			$CI->mongo_db->db->drivers->update($key,array('$set'=>$update_data));
			
			
		}
		
		function updateRiderDetails($userid,$lat,$long,$regid,$devicetoken)
		{
					$CI =& get_instance();
		$CI->load->library('mongo_db');
			$update_data['last_login']=time();	
			$update_data['lat']= $lat;
			$update_data['long']= $long;
			
			if($regid != "")
			{
			$update_data['reg_id']=$regid;
				
			}
		    if($devicetoken != "")
		    {
			$update_data['devicetoken']=$devicetoken;
		    }
			$key=array('_id'=>new MongoId($userid));
			
			$CI->mongo_db->db->riders->update($key,array('$set'=>$update_data));
			
			
		}
	
	function get_userdetail($userid) {
		$CI =& get_instance();
		$CI->load->library('mongo_db');	
		
		  $result = $CI->mongo_db->db->riders->find(array("_id"=>new MongoId($userid)));
		  
		  foreach($result as $res) {
		  	$array['username']=@$res['username'];
			$array['email']=@$res['email'];
			$array['mobile']=@$res['mobile'];
			$array['twitterid']=@$res['twitterid'];
			$array['facebookid']=@$res['facebookid'];
			$array['linkedinid']=@$res['linkedinid'];
			$array['twitterurl']=@$res['twitterurl'];
			$array['facebookurl']=@$res['facebookurl'];
			$array['linkedinurl']=@$res['linkedinurl'];
			$array['notification_count']=@$res['notification_count'];
			$array['regid']=@$res['regid'];
			}
		  return $array;
	}
	
	
	function get_admin_num() {
		$CI =& get_instance();
		$CI->load->library('mongo_db');	
		
		  $result = $CI->mongo_db->db->twilio->find(array("no"=>'1'));
		  
		  foreach($result as $res) {
		  	return $res['twilio_number'];
		  }
	}

	
		function auto_logout($field)
{
	$CI =& get_instance();
    $t = time();
    $t0 = $CI->session->userdata($field);
    $diff = $t - $t0;
	
	$getexpire = $CI->mongo_db->db->expiretime->find(array("id"=> 1)); 
	 foreach($getexpire as $Duration) {
	 $DurationTime = $Duration['expiretime'];
     }
						  if(!isset($DurationTime) || $DurationTime == "" || $DurationTime == 0 ){
						  	$DurationTime = 300 ;
						  }else{
						    $DurationTime = $DurationTime ;					  	
						  }
						  //echo $DurationTime ;
    if ($diff > $DurationTime || !isset($t0))
    {          
        return true;
    }
    else
    {
        $CI->session->set_userdata($field,time()) ;
		return false ;
    }
}

	 function getDateTimeFromMongoId(MongoId $mongoId)
{
    $dateTime = new DateTime('@'.$mongoId->getTimestamp());
    $dateTime->setTimezone(new DateTimeZone(date_default_timezone_get()));
    return $dateTime;
}

function checkisEmpty($data)
{
	$CI =& get_instance();
	foreach($data as $row)
	{
		$final[]=$row;
	}

	for ($i=0,$j=0 ; $i < count($final) ; $i++)
	{
		if($final[$i]=="")
		{
			$j++;
		}
		
	}
	if($j==0)
	{
		return true;
	}
	else 
	{
		return false;
	}
}

function GetDrivingDistance($lat1, $long1, $lat2, $long2)
{
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);

    curl_close($ch);
    $response_a = json_decode($response, true);
	$dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
    $time = $response_a['rows'][0]['elements'][0]['duration']['text'];

    return array('distance' => $dist, 'time' => $time);
}
  
function GetDistance($latitudeFrom , $longitudeFrom ,  $latitudeTo , $longitudeTo ){
	$unit = 'K';
   //Calculate distance from latitude and longitude
    $theta = $longitudeFrom - $longitudeTo;
    $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);
    if ($unit == "K") {
        return ($miles * 1.609344).' km';
    } else if ($unit == "N") {
        return ($miles * 0.8684).' nm';
    } else {
        return $miles.' mi';
    }  
}
	
function GetLimitConfig(){
	
	$CI =& get_instance();
	 $configuration= $CI->mongo_db->db->limit->find();
     foreach($configuration as $pconf){}
	 return $pconf ; 
	 
}

	
function GetSettings(){
	
	$CI =& get_instance();
	 $configuration= $CI->mongo_db->db->settings->find();
     foreach($configuration as $pconf){}
	 return $pconf ; 
}

function GetAllCategory(){
	
	$CI =& get_instance();
	 $configuration= $CI->mongo_db->db->category->find();
	 return $configuration ; 
}

function getUserData($collection, $userid) {
		$CI =& get_instance();
		$CI->load->library('mongo_db');	
		$result = $CI->mongo_db->db->$collection->find(array("_id"=>new MongoId($userid)));
		 foreach($result as $res) {}
		  return @$res;
	}
function GetAddress($lat, $long)
{
	if($lat != "" && $long != "")
	{
		 $geocodeFromLatLong = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($long).'&sensor=false'); 
    	 $output = json_decode($geocodeFromLatLong);
    	 $status = $output->status;
    	 $address = ($status=="OK")?$output->results[0]->formatted_address:'';
    	 if(!empty($address))
    	 {
         	return $address;;
        }
        else
        {
        	return false;
        }
    	}      
    else
    {
      	return false;   
    }
}


function createRandomString($string_length, $character_set) {
  $random_string = array();
  for ($i = 1; $i <= $string_length; $i++) {
    $rand_character = $character_set[rand(0, strlen($character_set) - 1)];
    $random_string[] = $rand_character;
  }
  shuffle($random_string);
  return implode('', $random_string);
}

function validUniqueString($string_collection, $new_string, $existing_strings='') {
  if (!strlen($string_collection) && !strlen($existing_strings))
    return true;
  $combined_strings = $string_collection . ", " . $existing_strings;
  return (strlen(strpos($combined_strings, $new_string))) ? false : true;
}
  
function createRandomStringCollection() {
  $string_collection = '';
  
$character_set = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
$existing_strings = "NXC, BRL, CVN";
$string_length = 5;
$number_of_strings = 10;
  
  
  for ($i = 1; $i <= $number_of_strings; $i++) {
    $random_string = createRandomString($string_length, $character_set);
    while (!validUniqueString($string_collection, $random_string, $existing_strings)) {
      $random_string = createRandomString($string_length, $character_set);
    }
   // $string_collection .= ( !strlen($string_collection)) ? $random_string : ", " . $random_string;
  }
 // return $string_collection;
 return $random_string;
}	
	
?>
