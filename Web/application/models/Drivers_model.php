<?php
class Drivers_model extends CI_Model
{
	function Drivers_model()
	{
		parent::__Construct();
	$this->load->library('mongo_db');

	}
 /*
 public function img_upload($data)
     {
         $this->db->set('file_name',$data['file_name']);
         $this->db->set('file_path',$data['file_path']);
         $this->db->set('full_path',$data['full_path']);
         $this->db->insert('users');
         return TRUE;
     }*/
 	function find_data($tablename, $data)
	{
		$result = $this->mongo_db->db->$tablename->find($data);
		return $result;
	}
		function update_driver($updatekey, $updatevalue)
	{
		$result = $this->mongo_db->db->drivers->update($updatekey,array('$set'=> $updatevalue));
		return $result;
	}
		function update_table($tablename, $updatekey, $updatevalue)
	{
		$result = $this->mongo_db->db->$tablename->update($updatekey,array('$set'=> $updatevalue));
		return $result;
	}


public function search($keyword)
{
	$regex = new MongoRegex("/^$keyword/i");
	$data = $this->mongo_db->db->drivers->find(array('$or' => array(array('email' => $regex), array('first_name' => $regex), array('lastname' => $regex))));
	return $data;
}

function check_valid_user_or_not()
	{
	       $this->load->library('mongo_db');
		$collection = $this->mongo_db->db->selectCollection('drivers');

		  $username = $this->input->post('drivermailid');
          $password = $this->input->post('driverpasswd');
          //  $db = $this->mongo_db->db();
          $collection = $this->mongo_db->db->drivers;
          $user = $collection->findOne(array("email" => $username, "password" => ($password)));
		  if (count($user))
          {
                return $user;
           }
          else
           {
                return false;
           }
	}

function getriderid($keyword)
	{

		 $result = $this->mongo_db->db->riders->find(array("first_name"=>$keyword));

		 if($result){
	//	 print_r($result);
			foreach($result as $document)
                 {
                  $id[] = $document['_id'];
	//			  print_r($id);
				 //  echo implode("",$id);
		//		$data['TransactionDetails']=$this->drivers_model->search_keyword($document['_id']);	// print_r($id);
				 }}
	//	print_r($result);
 //$data = $this->mongo_db->db->trips->find(array("rider_id"=>$result['rider_id']),array("_id"=>1,"rider_id"=>1,"driver_id"=>1,"amount"=>1,'hostamount'=>1,'status'=>1,'tripid'=>1));
			return $result;
	}

	public function search_keyword($result)
{
	$data = $this->mongo_db->db->trips->find(array("rider_id"=>$result));
	return $data;

}



	function insert_data($collection, $data)
	{
		$result = $this->mongo_db->db->$collection->insert($data);
		return $result;
	}

	function insert_doc($userid,$licence,$insurance)
	{
	//$result = $this->mongo_db->db->users->find(array("email"=>$email,"password"=>$password));
	$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($userid)),array('$set'=>array("licence"=>$licence,"insurance"=>$insurance,"proof_status"=>"InProgress")));
	$result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($userid)));
	return $result;

	}
	function get_user()
	{
		$result=$this->mongo_db->db->riders->find()->count();
		return $result;

	}
	function get_driver()
	{
		$result=$this->mongo_db->db->drivers->find()->count();
		return $result;

	}

	function get_driver_merchantid($acc)
	{
		 echo $acc;
		$result=$this->mongo_db->db->drivers->find(array("_id" => $acc));
		print_r($result);
		return $result;

	}


	function get_transaction()
	{
	$created=strtotime ("00:00:00");
	$result = $this->mongo_db->db->trips->find(array("created"=>array('$gte'=>$created)))->count();
	return $result;
		
	}
	function check_data_email($email)
	{
	  $result = $this->mongo_db->db->drivers->find(array("email"=>$email));
if($result->hasNext())
{
	return true;
}
else
{
	return false;
}

	}
	function check_data_no($mobile_no)
	{
	  $result = $this->mongo_db->db->drivers->find(array("mobile"=>$mobile_no));
if($result->hasNext())
{
	return true;
}
else
{
	return false;
}

	}

		function check_data_fb($id)
	{
	  $result = $this->mongo_db->db->drivers->find(array("facebook_id"=>$id));
if($result->hasNext())
{
	return true;
}
else
{
	return false;
}

	}
			function check_data_google($id)
	{
	  $result = $this->mongo_db->db->drivers->find(array("google_id"=>$id));
if($result->hasNext())
{
	return true;
}
else
{
	return false;
}

	}


	function login($email,$password)
	{
	$result = $this->mongo_db->db->drivers->find(array("email"=>$email,"password"=>($password)));

	return $result;

	}
	function update_location($userid,$lat,$long,$str_update)
	{

	$result =	$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($userid)),array('$set'=>array("lat"=>$lat,"long"=>$long,"location"=> $str_update)));
	return $result;

	}
	function driverstatus($userid,$status)
	{
	//$result = $this->mongo_db->db->users->find(array("email"=>$email,"password"=>$password));
	$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($userid)),array('$set'=>array("status"=>$status)));
	$result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($userid)));

	return $result;

	}

    function update_location_region($userid,$lat,$long,$status,$region,$degree,$country)
    {
    	if(empty($status))
		{ $this->mongo_db->db->drivers->update(array("_id"=>new MongoId($userid)),array('$set'=>array("lat"=>$lat,"long"=>$long,"region"=>$region,"degree"=>$degree,"country"=>$country)));
		}else{
		  $this->mongo_db->db->drivers->update(array("_id"=>new MongoId($userid)),array('$set'=>array("lat"=>$lat,"long"=>$long,"status"=>$status,"region"=>$region,"degree"=>$degree,"country"=>$country)));
		}
            $result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($userid)));

            return $result;
    }

	function update_availability($_id,$availability)
    {
    $this->mongo_db->db->drivers->update(array("_id"=>new MongoId($_id)),array('$set'=>array("availability"=>$availability)));
    $result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($_id)));
    return $result;
	}

	function accept_status($userid,$accept)
	{
		$this->mongo_db->db->riders->update(array("_id"=>new MongoId($userid)),array('$set'=>array("accept"=>$accept)));
	  $result = $this->mongo_db->db->riders->find(array("_id"=>new MongoId($userid)));

	return $result;

	}
	function clear_status($userid)
	{
		$this->mongo_db->db->riders->update(array("_id"=>new MongoId($userid)),array('$set'=>array("driverid"=>"")));
	  //$result = $this->mongo_db->db->users->find(array("_id"=>new MongoId($userid)));

	//return $result;

	}
	function fb_accept_status($userid,$accept)
	{
		$this->mongo_db->db->fb_users->update(array("_id"=>new MongoId($userid)),array('$set'=>array("accept"=>$accept)));
	  $result = $this->mongo_db->db->fb_users->find(array("_id"=>new MongoId($userid)));

	return $result;

	}
	function fb_clear_status($userid)
	{
		$this->mongo_db->db->fb_users->update(array("_id"=>new MongoId($userid)),array('$set'=>array("driverid"=>"")));
	  //$result = $this->mongo_db->db->users->find(array("_id"=>new MongoId($userid)));

	//return $result;

	}
	function update_amount($userid,$amount,$driveramount,$tax,$pickup,$drop,$timestamp,$service,$distance,$tollfee,$waitingtime,$arrive,$driverid,$tripid,$ridername,$drivername)
	{
$update = array();
		$update['rider_id'] = $userid ;
		$update['driver_id'] = $driverid ;
		$update['amount'] = $amount ;
		$update['hostamount'] = $driveramount ;
		$update['tax'] = $tax ;
		if($pickup !=""){
		$update['pickup'] = $pickup ;
		}
		$update['drop'] = $drop ;
		$update['timestamp'] = $timestamp;
		$update['service'] = $service ;
		$update['distance'] = $distance ;
		$update['tollfee'] = $tollfee ;
		$update['arrive'] = $arrive ;
		$update['waitingtime'] = $waitingtime ;
        $update['drivername'] = $drivername ;
		$update['ridername'] = $ridername ;
		$update['status'] = 'pending' ;
		if($arrive == 'yes')
		{
			///// insert data into trips table for the first time
			$count_trip  = $this->mongo_db->db->trips->count();
			if($count_trip == 0)
			{
				$count_trip_all = 1 ;
			}else{
			$s_trip = $this->mongo_db->db->trips->find();
				$i =1 ;
				foreach($s_trip as $r)
				{
					if($i == $count_trip)
					{
				$count_trip_all = $r['tripid']+1;
				}
					$i++;
				}


			}
			$update['tripid'] = $count_trip_all ;

	$result=$this->mongo_db->db->trips->insert($update);
		  //////
		  ////// update the trip id and amount in users(rider table)
		$last_id =  "'".$update['_id']."'";

		$last_id = trim($last_id,"'");
			$id_trip = 	$last_id ;


		$this->mongo_db->db->riders->update(array("_id"=>new MongoId($userid)),array('$set'=>array("last_tripid"=> $last_id,"amount"=> $amount)));
		//$this->mongo_db->db->users->update(array("_id"=>new MongoId($userid)),array('$set'=>array("amount"=> $amount)));
		 $result = $this->mongo_db->db->trips->find(array("_id"=>new MongoId($last_id)));
		  ///////
		}else{
			///// update the data again to the trips table for the second time
			$id_trip = 	$tripid ;

	$result=$this->mongo_db->db->trips->update(array("_id"=>new MongoId($tripid)),array('$set'=>$update));
		  $this->mongo_db->db->riders->update(array("_id"=>new MongoId($userid)),array('$set'=>array("amount"=> $amount)));
		$result = $this->mongo_db->db->trips->find(array("_id"=>new MongoId($tripid)));
		}

	//	$result = $this->mongo_db->db->trips->find(array("_id"=>new MongoId($id_trip)));
/*
if($result)
		{ echo "success";
			foreach($result as $row_trip)
			{
				echo "success";

			$tripid = 	$row_trip['_id'];

			$autotripid= $row_trip['tripid'];

			echo $tripid;
			echo $autotripid;
			}
		}*/

	return $result;

	}

function update_amount1($userid,$amount1,$pickup1,$service1,$distance1,$waitingtime1,$tripid)
	{
		$this->mongo_db->db->riders->update(array("_id"=>new MongoId($userid)),array('$set'=>array("amount1"=> $amount1)));
        $result=$this->mongo_db->db->trips->update(array("_id"=>new MongoId($tripid)),array('$set'=>array("amount1"=> $amount1,"pickup1"=> $pickup1,"service1"=> $service1,"distance1"=> $distance1,"waitingtime1"=> $waitingtime1)));
        $result = $this->mongo_db->db->trips->find(array("_id"=>new MongoId($tripid)));
		$result=$this->mongo_db->db->riders->find(array("split_id"=>$userid));

		return $result;

	}


    function get_car_region($location,$category)
    {
        $result=$this->mongo_db->db->drivers->find(array('$and' => array(array('status' =>'on'),array('$or' => array(array('trip_driver_status' =>'Home Page'),array('trip_driver_status' =>'Request'))),array('region' => $location), array('carcategory' => $category))));
        return $result;
    }


	function display_location($carcategory,$country)
	{
		//echo $carcategory;
       $result = $this->mongo_db->db->drivers->find(array('$and' => array(array('status' =>'on'),array('$or' => array(array('trip_driver_status' =>'Home Page'),array('trip_driver_status' =>'Request'))),array('country' =>$country), array('carcategory' => $carcategory))));
      //$result = $this->mongo_db->db->drivers->find(array('$and' => array(array('status' =>'on'),array('trip_driver_status' =>'Home Page'),array('country' =>@$country), array('carcategory' => $carcategory))));
    //  var_dump($result);
	   return $result;

	}
	function GetFixAmount()
	{

	  $result = $this->mongo_db->db->paymode->find();

	return $result;

	}

	function near_location($carcategory,$region,$country)
    {

		//find( { $or: [ { quantity: { $lt: 20 } }, { price: 10 } ] } )
		//find(array('$and' => array(array('status' =>'on'),array('$or' => array(array('trip_driver_status' =>'Home Page'),array('trip_driver_status' =>'Request'))),array('country' =>$country), array('carcategory' => $carcategory))));
		//db.Profiles.find ( { "name" : { $in: ["gary", "rob"] } } );


        if($region == "null")
        {
        	//echo '<br />'.$region.'<br />'.$carcategory.'<br />';
			//$result = $this->mongo_db->db->drivers->find(array("trip_driver_status" => array('$in' => array('Home Page','Request'))));
	          $result = $this->mongo_db->db->drivers->find(array("status"=>'on',"trip_driver_status" => array('$in' => array('Home Page','Request')),"carcategory" => $carcategory,"country" => $country,"value"=>array('$lte'=>1200)))->sort(array('value' => 1));
        }
        else
        {   //echo '<br />'.$region.'<br />'.$carcategory.'<br />';
          ($result = $this->mongo_db->db->drivers->find(array("status"=>'on',"trip_driver_status" => array('$in' => array('Home Page','Request')),"region" => $region,"carcategory" => $carcategory,"country" => $country,"value"=>array('$lte'=>1200)))->sort(array('value' => 1)));
        //echo 'best';
		}
	return $result;

	}

    function near_location_ridelater($carcategory)
    {


    $result = $this->mongo_db->db->drivers->find(array("status"=>'on',"trip_driver_status" => array('$in' => array('Home Page','Request')),"carcategory" => $carcategory,"value"=>array('$lte'=>1200)))->sort(array('value' => 1));

               return $result;

    }



	// Get Driver Information using Distance
	/*
	function near_location($carcategory,$driver_request_distance)
		{

		$distance=$driver_request_distance*1000;
			$result = $this->mongo_db->db->drivers->find(array("status"=>'on',"carcategory" => $carcategory,"valuedis"=>array('$lte'=>$distance)))->sort(array('valuedis' => 1));

		return $result;

		}*/

	function get_userlocation($driverid)
	{

	  //$result = $this->mongo_db->db->users->find(array("driverid"=>$driverid,"requesttime"=>array('$lt'=>time())))->sort(array('requesttime' => 1));
    $result = $this->mongo_db->db->riders->find(array("driverid"=>$driverid))->sort(array('requesttime' => 1));
	return $result;

	}
	function get_imei($imei)
	{

	  //$result = $this->mongo_db->db->users->find(array("driverid"=>$driverid,"requesttime"=>array('$lt'=>time())))->sort(array('requesttime' => 1));
    $result = $this->mongo_db->db->drivers->find(array("imei"=>$imei));
	return $result;

	}
	function empty_regid($_id,$regid)
	{
	$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($_id)),array('$set'=>array("regid"=>$regid,"imei"=>"nil","status"=>"off","availability"=>"off")));

	}
	function facebook_get_userlocation($driverid)
	{
	  $result = $this->mongo_db->db->fb_users->find(array("driverid"=>$driverid));
    return $result;
	}



	function display_details2($user_id,$email)
	{

$result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($user_id),"email"=>$email));
	return $result;

	}
	function update_details($user_id,$firstname,$lastname,$prof_pic,$email,$paypalemail,$mobile_no,$password,$car,$carcategory)
	{
	  $result = $this->mongo_db->db->drivers->update(array("_id"=>new MongoId($user_id)),array('$set'=>array("first_name"=>$firstname,"last_name"=>$lastname,"profile_pic"=>$prof_pic,"email"=>$email,"paypalemail"=>$paypalemail,"mobile_no"=>$mobile_no,"password"=>$password,"carname"=>$car,"carcategory"=>$carcategory)));

	return $result;

	}
	function update_paypal_details($user_id,$paypalemail)
	{
	  $result = $this->mongo_db->db->drivers->update(array("_id"=>new MongoId($user_id)),array('$set'=>array("paypalemail"=>$paypalemail)));

	return $result;

	}
	function braintree_update_details($user_id,$firstname,$lastname,$dob,$staddress,$locality,$region,$postalcode,$accountno,$routingno,$submerchantaccountid,$mobile)
	{
	  $result = $this->mongo_db->db->drivers->update(array("_id"=>new MongoId($user_id)),array('$set'=>array("first_name"=>$firstname,"last_name"=>$lastname,"dob"=>$dob,"staddress"=>$staddress,"locality"=>$locality,"region_braintree"=>$region,"postalcode"=>$postalcode,"accountno"=>$accountno,"routingno"=>$routingno,"submerchantaccountid"=>$submerchantaccountid,"mobile_no"=>$mobile)));

	return $result;

	}
	function display_details($user_id)//,$email)
	{

	$result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($user_id)));
	return $result;

	}

	function display_star_rate($user_id)//,$email)
	{
	$result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($user_id)));
	return $result;
	}

	function update_mapdetails($driver_id,$value,$time)
	{

	$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($driver_id)),array('$set'=>array("trip_time"=>$time)));
		$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($driver_id)),array('$set'=>array("value"=>$value)));
			  $result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($driver_id)));

	return $result;

	}

	// Get Driver infromation using Distance
/*
	function update_mapdetails($driver_id,$value,$time,$valuedis,$distance)
	{

	$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($driver_id)),array('$set'=>array("trip_time"=>$time)));
		$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($driver_id)),array('$set'=>array("value"=>$value)));
		$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($driver_id)),array('$set'=>array("valuedis"=>$valuedis)));
		$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($driver_id)),array('$set'=>array("distance"=>$distance)));
			  $result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($driver_id)));

	return $result;

	}*/

	function googleconnect(){

	return $apisettings = $this->mongo_db->db->googleconnect->find();
	}

	function update_same_location_driver($driver_id)
	{

	$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($driver_id)),array('$set'=>array("trip_time"=>"1 min")));
		$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($driver_id)),array('$set'=>array("value"=>60)));

			  $result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($driver_id)));

	return $result;

	}
function get_arrived_driverdetails($driverid)
	{
	  $result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($driverid)));

     return $result;
	}

function get_driver_rate($driverid)
	{
	   $result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($driverid)));
	   return $result;
	}

	function get_today_user(){
	$created=strtotime ("00:00:00");
	$result = $this->mongo_db->db->drivers->find(array("created"=>array('$gte'=>$created)))->count();
	return $result;
}


	function GetAllDriverUsers_today()
	{
	$created=strtotime ("00:00:00");
	$result = $this->mongo_db->db->drivers->find(array("created"=>array('$gte'=>$created)))->sort(array('_id' => -1));
	return $result;
	}

	function GetAllRiderUsers_today(){
	$created=strtotime ("00:00:00");
	$result = $this->mongo_db->db->riders->find(array("created"=>array('$gte'=>$created)))->sort(array('_id' => -1));
	return $result;
}

	function GetAllDriverUsers($offset, $limit)
	{
	 $result = $this->mongo_db->db->drivers->find()->skip($offset)->limit($limit)->sort(array('_id' => -1));
		return $result;

	}

		function GetAllRiderUsers($offset, $limit)
	{
	 $result = $this->mongo_db->db->riders->find()->skip($offset)->limit($limit)->sort(array('_id' => -1));
		return $result;

	}


		function GetAllDriverUsers_all()
	{
	 $result = $this->mongo_db->db->drivers->find()->sort(array('_id' => -1));
		return $result;

	}

		function GetAllRiderUsers_all()
	{
	 $result = $this->mongo_db->db->riders->find()->sort(array('_id' => -1));
		return $result;

	}


	function GetAllTransactionByLimit($offset, $limit) {
		//$result = $this->mongo_db->db->drivers->find(array("status"=>'on',"carcategory" => $carcategory,"value"=>array('$lte'=>1200)))->sort(array('value' => 1));

	    $result = $this->mongo_db->db->trips->find()->skip($offset)->limit($limit)->sort(array('tripid' => -1));

		return $result;


	}
		function GetAllTransactionBy() {
		//$result = $this->mongo_db->db->drivers->find(array("status"=>'on',"carcategory" => $carcategory,"value"=>array('$lte'=>1200)))->sort(array('value' => 1));

	    $result = $this->mongo_db->db->trips->find()->sort(array('_id' => -1));

		return $result;


	}
				function GetAllTransactionBycomplete() {
		//$result = $this->mongo_db->db->drivers->find(array("status"=>'on',"carcategory" => $carcategory,"value"=>array('$lte'=>1200)))->sort(array('value' => 1));

	    $result = $this->mongo_db->db->trips->find(array('status'=>'Paid'))->sort(array('_id' => -1));

		return $result;


	}
		function GetAllTransactionBycancel() {
		//$result = $this->mongo_db->db->drivers->find(array("status"=>'on',"carcategory" => $carcategory,"value"=>array('$lte'=>1200)))->sort(array('value' => 1));

	    $result = $this->mongo_db->db->trips->find(array('status'=>'pending'))->sort(array('_id' => -1));

		return $result;


	}



	function GetAllTransactionByLimit1($search)
	{

		$regex = new MongoRegex("/^$search/");
	    $result = $this->mongo_db->db->trips->find(array('$or' => array(array('drivername' =>$regex), array('ridername' => $regex))));

	    return $result;

	}

	function GetAllTransaction() {

	    $result = $this->mongo_db->db->trips->find()->count();
		return $result;

	}
	function GetAllTransactionToday() {

	$created=strtotime ("00:00:00");
	$result = $this->mongo_db->db->trips->find(array("created"=>array('$gte'=>$created)))->sort(array('_id' => -1));
	return $result;


	}


	public function DeleteRider($RiderId) {
		$result = $this->mongo_db->db->riders->remove(array('_id' => new MongoId($RiderId)));
		return $result;
	}
	public function DeleteDriver($DriverId) {
		$result = $this->mongo_db->db->drivers->remove(array('_id' => new MongoId($DriverId)));
		return $result;
	}
	public function GetRiderDetailsBasedOnId($RiderId) {
		$result = $this->mongo_db->db->riders->find(array('_id' => new MongoId($RiderId)));
		return $result;
	}
	public function UpdateRiderDetailsBasedOnId($RiderId) {
		extract($this->input->post());
		$this->mongo_db->db->riders->update(array('_id' =>  new MongoId($RiderId) ),array('$set'=>array("first_name"=> $Fname,"last_name"=> $Lname ,"email"=> $email_value,'mobile' => $mobile)));
	}

	public function GetDriverDetailsBasedOnId($DriverId) {
	$result = $this->mongo_db->db->drivers->find(array('_id' => new MongoId($DriverId)));
	return $result;
	}
	public function UpdateDriverDetailsBasedOnId($DriverId) {
		extract($this->input->post());
		$this->mongo_db->db->drivers->update(array('_id' =>  new MongoId($DriverId) ),array('$set'=>array("first_name"=> $Fname,"last_name"=> $Lname ,"email"=> $email_value,'mobile' => $mobile)));
	}

	public function DriverChangePasswordBasedOnId($DriverId) {
		extract($this->input->post());
		$this->mongo_db->db->drivers->update(array('_id' =>  new MongoId($DriverId) ),array('$set'=>array("password"=> ($new_password))));
	}

	public function GetDurationTime() {
		$result = $this->mongo_db->db->limit->find();
		return $result;
	}
	public function DriverRequestNotification() {
		$result = $this->mongo_db->db->driver_request_notification->find();
		return $result;
	}

		public function UpdateDriverRequestNotification() {
		extract($this->input->post());
		$this->mongo_db->db->driver_request_notification->update(array('id' => 1 ),array('$set'=>array("distance"=> $distance)));
	}
		public function UpdateRequestDuration() {
		extract($this->input->post());
		$this->mongo_db->db->limit->update(array('id' => '1' ),array('$set'=>array("request_duration_time"=> $durationtime)));
	}
		public function GetFixedAmount() {
		$result = $this->mongo_db->db->durationtime->find();
		return $result;
	}

		public function UpdateFixedAmount() {
		extract($this->input->post());
		$this->mongo_db->db->paymode->update(array('is_fixed' => 1 ),array('$set'=>array("fixed_amount"=> $fixedamount)));
	}

		public function Updateproofstatus($DriverId) {

		$this->mongo_db->db->drivers->update(array('_id' =>  new MongoId($DriverId)  ),array('$set'=>array("proof_status"=> 'Accepted','status'=> '1')));
	}

		public function rejectproofstatus($DriverId) {

		$this->mongo_db->db->drivers->update(array('_id' =>  new MongoId($DriverId)  ),array('$set'=>array("proof_status"=> 'Rejected')));
	}

		  function get_configuration() {
	    $db = $this->mongo_db->db;
	    $configuration=$db->settings->find();
        return $configuration;
    }

function FCM_push_notification($registatoin_ids, $message) {

	    // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';
		$apikey='AIzaSyC_mYdhP2UYOGR0bspbXghludmwwMQ82C0';
        $fields = array(
        	//'collapse_key' => "arcane",
        	//'delay_while_idle' => true,
            'registration_ids' => $registatoin_ids,
            'data' => $message,
            'time_to_live'=>5,
      		'delay_while_idle'=> true,
      		//'delivery_receipt_requested'=> true,



            //'time_to_live' => 5,

        );

        $headers = array(
            'Authorization: key=' . $apikey,
            'Content-Type: application/json'
        );
		//print_r($headers);
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);

		//print_r($result);
		//exit;
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);


	    }

function send_push_notification($registatoin_ids, $message) {

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
		$apikey='AIzaSyDJ6wbuMgKlCG4hCFlYBuKha4AeV7PaxSs';
        $fields = array(
        	//'collapse_key' => "arcane",
        	//'delay_while_idle' => true,
            'registration_ids' => $registatoin_ids,
            'data' => $message,
            'time_to_live'=>5,
      		'delay_while_idle'=> true,
      		//'delivery_receipt_requested'=> true,
      		     );

        $headers = array(
            'Authorization: key=' . $apikey,
            'Content-Type: application/json'
        );
		//print_r($headers);
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

			//echo $result;
		//$decode_result = json_decode($result);
	//	print_r($decode_result->canonical_ids);

	    }

   	function get_driver_information($driverid)
	{
	 	$result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($driverid)));
		return $result;
	}

	function get_devicetoken($driverid)
	{
	 $result = $this->mongo_db->db->riders->find(array("_id"=>new MongoId($driverid)));
    return $result;

	}
/*
 function update_devicetoken($email,$devicetoken)
  {

	//echo $devicetoken;
    //echo $email;
  	$result=$this->mongo_db->db->drivers->update(array("email"=> $email),array('$set'=>array("devicetoken"=>$devicetoken)));
	//print_r($result);
	return $result;

  }*/


   function update_devicetoken($id,$devicetoken)
  {
  	//print_r($id);
	//print_r($devicetoken);

    $this->mongo_db->db->drivers->update(array("_id"=>new MongoId($id)),array('$set'=>array("devicetoken"=>$devicetoken)));

    //$result=$this->mongo_db->db->users->update(array("email"=> $email),array('$set'=>array("devicetoken"=>$devicetoken)));



  }


  public function get_star_rating($driverid)
  {

  	 $result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($driverid)));



	       foreach ($result as $res)
           {

		      $res['star_rate']= $res['star_rate'];

	       }
	   // echo $res['star_rate'];
	   // exit;

	    return $res['star_rate'];


  }
  public function update_star_rating($driverid,$star_rate)
  {

	    $this->mongo_db->db->drivers->update(array("_id"=>new MongoId($driverid)),array('$set'=>array("star_rate"=>$star_rate)));


  }




            //'time_to_live' => 5,
public function upload_image($fileName)
{
if($fileName!='' ){
      $filename1 = explode(',',$fileName);
  foreach($filename1 as $file){
  $file_data = array(
  'prof_pic' => $file,

  );
 $this->mongo_db->db->riders->update(array("_id"=>new MongoId($userid)),array('$set'=>$file_data));
  }
  }
}

function send_push_notification12($registatoin_ids, $message) {


        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
		$apikey='AIzaSyDJ6wbuMgKlCG4hCFlYBuKha4AeV7PaxSs';
        $fields = array(
        	//'collapse_key' => "arcane",
        	//'delay_while_idle' => true,
            'registration_ids' => $registatoin_ids,
            'data' => $message,
            'time_to_live'=>5,
      		'delay_while_idle'=> true,
      		//'delivery_receipt_requested'=> true,



            //'time_to_live' => 5,

        );

        $headers = array(
            'Authorization: key=' . $apikey,
            'Content-Type: application/json'
        );
		//print_r($headers);
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);

		//print_r($result);
		//exit;
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
		//echo $message;



	    }

function check_mail($email)
	{

	  $result = $this->mongo_db->db->drivers->find(array("email"=>$email),array("_id"=>1,"email"=>1));
	return $result;
	}
	function sendMail($to,$from_email,$from_name,$subject,$message)
	{
		$this->load->library('email');

		$config['mailtype'] = 'html';
		$config['wordwrap'] = TRUE;

$this->email->set_mailtype("html");

		$this->email->to($to);
  $this->email->from($from_email,$from_name);
  $this->email->subject($subject);
  $this->email->message($message);

 $headers = "MIME-Version: 1.0" . "\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\n";

$headers .= 'From: ' .'Arcane'. '<'.$from_email.'>' . "\r\n";
// Inform the user
		if(mail($to,$subject,$message,$headers))
			{
			return 1;
			}
			else {
				echo $this->email->print_debugger();
				return 0;
			}
			}

	function check_id($id)
	{
	  $result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($id)));
	  return $result;
	}
	function reset_password($user_id,$password)
	{
		$password1=($password);
		$result = $this->mongo_db->db->drivers->update(array("_id"=>new MongoId($user_id)),array('$set'=>array("password"=>$password1)));
	    return $result;
	}


	function update_regid($_id,$regid,$devicetoken,$imei)
	{
	if(empty($imei))
	{
		$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($_id)),array('$set'=>array("regid"=>$regid,"devicetoken"=>$devicetoken)));
	}else
	{
		$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($_id)),array('$set'=>array("regid"=>$regid,"devicetoken"=>$devicetoken,"imei"=>$imei)));
	}
    $result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($_id)));



	return $result;

	}

	function get_drivername($driverid)
	{
		$result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($driverid)));
		foreach ($result as $res) {
		               $drivername =$res['first_name'];

		}
		return $drivername;


	}

function gp_login($gp_id)
	{
	  $this->mongo_db->db->drivers->update(array("gp_id"=>$gp_id,"password"=>array('$exists'=>false)),array('$set'=>array("password"=>"null")));
 $result = $this->mongo_db->db->drivers->find(array("gp_id"=>$gp_id));
	return $result;

	}
	function check_gp_data($gp_id)
	{
	//$result = $this->mongo_db->db->users->find(array("email"=>$email,"password"=>$password));
	//$this->mongo_db->db->fb_users->update(array("fb_id"=>$fb_id,"password"=>array('$exists'=>false)),array('$set'=>array("password"=>"null")));
	  //$result = $this->mongo_db->db->fb_users->find(array("fb_id"=>$fb_id),array("_id"=>1,"firstname"=>1,"lastname"=>1,"email"=>1,"mobile_no"=>1,"fb_id"=>1,"password"=>1));
$result = $this->mongo_db->db->drivers->find(array("gp_id"=>$gp_id));
	  if($result->hasNext())
{
	return true;
}
else
{
	return false;
}
}

function get_gp_data($gp_id)
	{
	//$result = $this->mongo_db->db->users->find(array("email"=>$email,"password"=>$password));
	$this->mongo_db->db->drivers->update(array("gp_id"=>$gp_id,"prof_pic"=>array('$exists'=>false)),array('$set'=>array("prof_pic"=>"null")));
		$this->mongo_db->db->drivers->update(array("gp_id"=>$gp_id,"password"=>array('$exists'=>false)),array('$set'=>array("password"=>"null")));
	  //$result = $this->mongo_db->db->fb_users->find(array("fb_id"=>$fb_id),array("_id"=>1,"firstname"=>1,"lastname"=>1,"email"=>1,"mobile_no"=>1,"fb_id"=>1,"prof_pic"=>1,"password"=>1));
 $result = $this->mongo_db->db->drivers->find(array("gp_id"=>$gp_id));
	return $result;

	}

function getcategory()
{
 $result = $this->mongo_db->db->category->find()->sort(array('_id' => 1));
      return $result;
}

function getcarwithcategory($category)
{
 $result = $this->mongo_db->db->car->find(array("category"=>$category));

      return $result;
}
function update_car($data)
	{
		$data1['carname']=$data['carname'];
		$data1['category']=$data['category'];

		$result1 = $this->mongo_db->db->car->insert($data1);
		return $result1;
	}


	function update_cancel_details($_id,$reason)
		{
		//$result = $this->mongo_db->db->users->find(array("email"=>$email,"password"=>$password));
		$this->mongo_db->db->riders->update(array("_id"=>new MongoId($_id)),array('$set'=>array("cancel"=>$reason)));
		$this->mongo_db->db->riders->update(array("split_id"=>$_id),array('$set'=>array("split_id"=>"null")));
	    $result = $this->mongo_db->db->riders->find(array("_id"=>new MongoId($_id)));

		return TRUE;

		}
		function get_cancel_details($_id)
		{

	    $result = $this->mongo_db->db->riders->find(array("_id"=>new MongoId($_id)));

		return $result;

		}
	function getlogoname($driverid)
   {
	$result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($driverid)));
	return $result;
   }
		function get_user_by_id($user_id){
		$result = $this->mongo_db->db->trips->find(array("_id"=>new MongoId($user_id)) );
		if($result->num_rows()!=0)
		{
			return $result;
		}
		else {
			return FALSE;
		}

	}

function get_invite_rider($driverid)
{
	$result = $this->mongo_db->db->riders->find(array("driverid"=>$driverid));
}




	function GetAllTransactionByLimit2($id) {
/*
	    $results = $this->mongo_db->db->trips->find(array("_id"=>new MongoId($id)) );
		//return $results;

		if($results->hasNext())
	{
		foreach ($results as $result) {

 		$data['driver_id']=$result['driver_id'];
 		$driverid=$result['driver_id'];
		//echo $driverid;
		$data['_id']=$result['_id'];
		$data['drivername']=$result['drivername'];
		$data['ridername']=$result['ridername'];
		$data['status']=$result['status'];
		$data['tripid']=$result['tripid'];
		$data['tollamount']=$result['tollamount'];
		$data['hostamount']=$result['hostamount'];
		$data['amount']=$result['amount'];
		//$data['tripid']=$result['tripid'];


		}
	}
if($driverid!=null){
	//echo "inside";
	$driverdata = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($driverid)) );
	var_dump($driverdata);

 			if($driverdata->hasNext())
			{
			foreach ($driverdata as $driverdata1) {

 			$data['accountno']=$driverdata1['accountno'];
 			//$driverid=$driverdata1['submerchantaccountid'];
			$data['submerchantaccountid']=$driverdata1['submerchantaccountid'];

			}
			}
}
return $data;

*/
	//echo $id;

	$results = $this->mongo_db->db->trips->find(array("_id"=>new MongoId($id)) );



	/*
	foreach($results as  $result){


				$data['driver_id']=$result['driver_id'];
				$data['_id']=$result['_id'];
				$data['drivername']=$result['drivername'];
				$data['ridername']=$result['ridername'];
				$data['status']=$result['status'];
				$data['tripid']=$result['tripid'];
				$data['tollamount']=$result['tollamount'];
				$data['hostamount']=$result['hostamount'];
				$data['amount']=$result['amount'];
				}

		return $data;
		*/
	return $results;
/*

  $driverdata = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($tripsdriver)) );

	foreach($driverdata as $accountdata){
	$acc=$data['acc']=$accountdata['accountno'];

	}

		return $data;
	}
*/
}
function account($tripsdirverid){

	return $driverdata = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($tripsdirverid)) );


	/*

		foreach($driverdata as $accountdata){
			$acc=$data['acc']=$accountdata['accountno'];

			}


		return $acc;
	*/


}
	/*
	function update_same_location_driver($driver_id)
		{

		$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($driver_id)),array('$set'=>array("trip_time"=>"1 min")));
			$this->mongo_db->db->drivers->update(array("_id"=>new MongoId($driver_id)),array('$set'=>array("value"=>60)));
							   $result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($driver_id)));

		return $result;

		}*/

/*
function get_arrived_driverdetails($driverid)
	{

	  $result = $this->mongo_db->db->drivers->find(array("_id"=>new MongoId($driverid)));

	return $result;

	}*/

/*

	function get_today_user(){
	$created=strtotime ("00:00:00");
	$result = $this->mongo_db->db->drivers->find(array("created"=>array('$gte'=>$created)))->count();
	return $result;
}*/
//For promo Code

		  function get_configuration1() {
	    $db = $this->mongo_db->db;
	    $configuration=$db->settings->findOne();
        return $configuration;
    }
			function findTotalEarning($userid){ 
				
				$db = $this->mongo_db->db;
				
				$pipeline = array(
  			 array('$match' => array(
     	 	 	 'driver_id' => $userid
   				 )),
   			 array('$group' => array(
       		 '_id' => '',
        		 'total_price' => array('$sum' => '$total_price'),
       			 'total_trips' => array('$sum' => 1),
				  'last_tripDate' => array( '$last' => '$created' )
    				)),
    		array( '$sort'=> array('created'=> -1 ))		
    	
			);
				
				
				$result = $db->trips->aggregate( $pipeline);
			
			return $result ; 
			//	return $total = $this->mongo_db->db->trips->find(array("_id"=>new MongoId($userid)) );
			}



//End


}
?>