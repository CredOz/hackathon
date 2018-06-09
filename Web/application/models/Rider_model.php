<?php
class Rider_model extends CI_Model
{
	function Rider_model()
	{
		parent::__Construct();
	$this->load->library('mongo_db');
		$collection = $this->mongo_db->db->selectCollection('riders');
	}
	function insert_data($data)
	{
		$result = $this->mongo_db->db->riders->insert($data);
		return $result;
	}
	
	function insert_data1($tablename, $data)
	{
		$result = $this->mongo_db->db->$tablename->save($data);
		return $result;
	}
	
	function find_data($tablename, $data)
	{
		$result = $this->mongo_db->db->$tablename->find($data);
		return $result;
	}
	function find_Onedata($tablename, $data)
	{
		$result = $this->mongo_db->db->$tablename->findOne($data);
		return $result;
	}
	
	function update_Tabdata($tablename, $updatekey, $updatevalue)
	{
		$result = $this->mongo_db->db->$tablename->update($updatekey,array('$set'=> $updatevalue));
		return $result;
	}
	
	
	/*
		function count_data($tablename, $data)
		{
			 $collection = $this->mongo_db->db->$tablename;
			 $result = $collection->count($data);
			return $result;
		}*/
		function get_ridername($riderid)
	{
			
		$result = $this->mongo_db->db->riders->find(array("_id"=>new MongoId($riderid)));
		foreach ($result as $res) {
		               $ridername =$res['first_name'];
			
		}
		return $ridername;
		
		
	}
	function update_location($userid,$lat,$long)
	{		

	$result = 	$this->mongo_db->db->riders->update(array("_id"=>new MongoId($userid)),array('$set'=>array("lat"=>$lat,"long"=>$long)));
	
	return $result;
	}
	function get_user()
	{
		$result=$this->mongo_db->db->riders->find()->count();
		return $result;
		
	}
	
	function get_DocCount($tablename)
	{
		$result=$this->mongo_db->db->$tablename->find()->count();
		return $result;
		
	}

	function update_riders($updatekey, $updatevalue)
	{
		$result = $this->mongo_db->db->riders->update($updatekey,array('$set'=> $updatevalue));
		return $result;
	}
	
	function update_matchedpost($updatekey, $updatevalue)
	{
		$result = $this->mongo_db->db->matched_post->update($updatekey,array('$set'=> $updatevalue));
		return $result;
	}

	function twilio_getconfig()
	{
	$result = $this->mongo_db->db->twilio->find(array("no"=>"1"));
	return $result;
	}
	
	function display_details($user_id)//,$email)
	{
	$result = $this->mongo_db->db->riders->find(array("_id"=>new MongoId($user_id)));
	return $result;
	}
	
	///// Email management
	
	function getemail()
	{
	 	        $db = $this->mongo_db->db;
				$message=$db->email->find();
				return $message;
	}

	function getemailbyid()
	{	
	$id=$this->uri->segment(4);
	$db = $this->mongo_db->db;
	$realmongoid = new MongoId($id);
	     $message = $db->email->find(array("_id" => $realmongoid));
		 $count=$db->email->find(array("_id" => $realmongoid))->count();
		 return array(
    'records' => $message,
    'count' =>  $count
	);
	return $message;
	}
	
	function getemailbyidcount($id)
	{
	 $db = $this->mongo_db->db;
	 $realmongoid = new MongoId($id);
	 $message = $db->email->find(array("_id" => $realmongoid))->count();
	 return $message;
	}
	
	function addemail()
	{
		
    	 $db = $this->mongo_db->db;
		 $user = $db->email->findOne(array("code" => $this->input->post('code')));
        if(count($user))
		{
			return 0;
			
		}
		else {
		 $insertData = array(
        'subject'=> $this->input->post('sub'),
        'code'=> $this->input->post('code'),
         'message'=> $this->input->post('message'),
                            );
        $db->email->insert($insertData);
		return 1;
    }
		
	}
	
	function updatemailbyid()
	{
	  $db = $this->mongo_db->db;
        $updatedata=array(
        'subject'=> $this->input->post('sub'),
        'code'=> $this->input->post('code'),
        'message'=> $this->input->post('message'),
                            );
							$id=$this->uri->segment(4);
	
										 $realmongoid = new MongoId($id);
	      $db->email->update(array("_id" => $realmongoid), array('$set' => $updatedata));
		  $updatedata=$db->email->find(array("_id" => $realmongoid))->count();
		  return $updatedata;
	}

	function deletemailbyid()
	{
	
	 		$db = $this->mongo_db->db;
       		$id= $this->input->post('user_id');
	       	$realmongoid = new MongoId($id);
		    $db->email->remove(array('_id' => $realmongoid), array("justOne" => true));
			 echo $id;
	}

	function getemailbycode($code)
	{
		     $db =  $this->mongo_db->db;
		    	$message=$db->email->find(array("code" => $code ));
				foreach ($message as $template) {
			$dbmsg=$template['message'];
		 $dbsub=$template['subject'];
				}
	return array(
    'msg' => $dbmsg,
    'sub' =>  $dbsub
	);

	}
    
    
function update_settings($data1)
	{
		$setting_image = $this->mongo_db->db->settings->find();
				
							
		if($setting_image->hasNext())
	{
			foreach($setting_image as $documentimage)
				 {
				 $settingid    = $documentimage['_id'];	
				 }
	}
		$this->mongo_db->db->settings->update(array("_id"=>new MongoId($settingid)),array('$set'=>array("title"=>$data1['title'])));
		$this->mongo_db->db->settings->update(array("_id"=>new MongoId($settingid)),array('$set'=>array("sitelogan"=>$data1['sitelogan'])));
		$this->mongo_db->db->settings->update(array("_id"=>new MongoId($settingid)),array('$set'=>array("admin_mail"=>$data1['admin_mail'])));
		$this->mongo_db->db->settings->update(array("_id"=>new MongoId($settingid)),array('$set'=>array("admin_no"=>$data1['admin_no'])));
		$this->mongo_db->db->settings->update(array("_id"=>new MongoId($settingid)),array('$set'=>array("image_name"=>$data1['image_name'])));
	    $result = $this->mongo_db->db->settings->find(array("_id"=>new MongoId($settingid)));

		return $result;
	}
function get_settings(){
		$result = $this->mongo_db->db->settings->find();
	return $result;
	}	

		function GetLimitData($tablename, $offset, $limit)
	{
	    $result = $this->mongo_db->db->$tablename->find()->skip($offset)->limit($limit)->sort(array('_id' => 1));
		return $result;
		
	}
		function GetTableCount($tablename)
	{
		$result=$this->mongo_db->db->$tablename->find()->count();
		return $result;
		
	}
		public function GetDetailsById($tablename, $MemberId) 
	{
		$result = $this->mongo_db->db->$tablename->find(array('_id' => new MongoId($MemberId)));
		return $result;
	}
	
	public function search($keyword)
	{
	$regex = new MongoRegex("/^$keyword/i");
	$data = $this->mongo_db->db->riders->find(array('$or' => array(array('email' => $regex), array('username' => $regex))));
	return $data;	
	}
	
	public function DeleteUser($DriverId) {
		$result = $this->mongo_db->db->foods->remove(array('_id' => new MongoId($DriverId)));
		return $result;
	} 



	public function GetAllPostsByLimit($offset, $limit) {
		$result = $this->mongo_db->db->post->find()->skip($offset)->limit($limit)->sort(array('_id' => -1));
		return $result;
	}
	
	public function GetAllMatchedByLimit($offset, $limit) {
		$result = $this->mongo_db->db->matched_post->find()->skip($offset)->limit($limit)->sort(array('_id' => -1));
		return $result;
	}
		function login($email,$password)
	{
  $result = $this->mongo_db->db->riders->find(array("email"=>$email,"password"=>($password)),array());
   
	return $result;
	
	}
		function check_data_email($email)
	{
	  $result = $this->mongo_db->db->riders->find(array("email"=>$email));
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
	  $result = $this->mongo_db->db->riders->find(array("mobile"=>$mobile_no));
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
	  $result = $this->mongo_db->db->riders->find(array("facebook_id"=>$id));
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
	  $result = $this->mongo_db->db->riders->find(array("google_id"=>$id));
if($result->hasNext())
{
	return true;
}
else
{
	return false;
}
	
	}
	
	
	

}
?>