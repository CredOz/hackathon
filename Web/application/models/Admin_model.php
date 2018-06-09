<?php

class Admin_model extends CI_Model {

	function Admin_model()
	{
		parent::__Construct();
		$this->load->library('mongo_db');
	}
	function check_valid_user_or_not()
	{
	       $this->load->library('mongo_db');
		   $collection = $this->mongo_db->db->selectCollection('admin');
		
		  $username = $this->input->post('username');
          $password = $this->input->post('password');
         
          $collection = $this->mongo_db->db->admin;
          $user = $collection->findOne(array("username" => $username, "password" => md5($password))); 	
		  if (count($user))
          {
                return "1";
           }      
                 return false;     
          		
	}
	
	function get_settings()
	{
		$result = $this->mongo_db->db->settings->find();
		return $result;
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
		$this->mongo_db->db->settings->update(array("_id"=>new MongoId($settingid)),array('$set'=>array("title"=>$data1['title'],"sitelogan"=>$data1['sitelogan'],"admin_mail"=>$data1['admin_mail'],"admin_no"=>$data1['admin_no'],"image_name"=>$data1['image_name'] )));
		$result = $this->mongo_db->db->settings->find(array("_id"=>new MongoId($settingid)));

		return $result;
	}
	
}
?>