<?php
/*
  error_reporting(E_ALL);
ini_set('display_errors', 'On');

*/


class Members extends CI_Controller
{
	// Used for registering and changing password form validation
	var $min_username = 4;
	var $max_username = 20;
	var $min_password = 4;
	var $max_password = 20;
	function Members()
	{
		parent::__construct();
		
		$this->load->model('drivers_model');
		$this->load->model('users_model');
		$this->load->model('fb_drivers_model');
		$this->load->model('fb_users_model');
		$this->load->model('Common_model');
		
		$this->load->library('Pagination');
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('url');
 		$this->load->helper('file');
		$this->load->library('Table');
		$this->load->library('mongo_db');
		
	}
	public function Search()
	{

		$this->load->library('pagination');

		if(check_logged()){
			$start = (int) $this->uri->segment(4,0);
			$row_count = 15;
		
		if($start > 0)
		   $offset			  = ($start-1) * $row_count;
		else
		   $offset			  =  $start * $row_count; 
			$keyword=$this->input->post('keyword');



if(!$keyword){
			redirect('administrator/members/Rider');
		}
	$data['RiderUsers']=$this->users_model->search($keyword);	
    $p_config['base_url'] 			=  base_url('administrator/members/Search');
		$p_config['uri_segment'] = 4;
		$p_config['num_links'] 		= 5;
		$p_config['total_rows'] 	= $this->drivers_model->get_user();
		$p_config['per_page'] 			= $row_count;
				
		// Init pagination
	$data['pagination'] =	$this->pagination->initialize($p_config);		
		
		// Create pagination links
	//	$data['pagination']     = $this->pagination->create_links();

$data['message_element'] = "administrator/members/view_rider";
	$this->load->view('administrator/admin_template', $data);
	//redirect('administrator/members/Rider');
		}
	
	}
	public function Searchdriver()
	{
		if(check_logged()){
			$start = (int) $this->uri->segment(4,0);
			$row_count = 15;
		
		if($start > 0)
		   $offset			  = ($start-1) * $row_count;
		else
		   $offset			  =  $start * $row_count; 
			$keyword=$this->input->post('keyword');
			
			
			if(!$keyword){
			redirect('administrator/members/Driver');
		}
		
$data['DriverUsers']=$this->drivers_model->search($keyword);
$p_config['base_url'] 			=  base_url('administrator/members/Searchdriver');
		$p_config['uri_segment'] = 4;
		$p_config['num_links'] 		= 5;
		$p_config['total_rows'] 	= $this->drivers_model->get_driver();


		$p_config['per_page'] 			= $row_count;
				
		// Init pagination
	$data['pagination']     = 	$this->pagination->initialize($p_config);		
		
		// Create pagination links
	//	$data['pagination']     = $this->pagination->remove_links2();
$data['message_element'] = "administrator/members/view_driver";
	$this->load->view('administrator/admin_template', $data);
//	redirect('administrator/members/Rider');

	
	}
	
	}
function driver_callrequest()
{
		require(APPPATH."/libraries/Services/Twilio/Capability.php");
		
		$result = $this->mongo_db->db->twilio->find(array("no"=>'1'));
		if($result->hasNext())
		{		
			foreach($result as $document)
			{
				$account_sid=$document['twilio_sid'];
				$auth_token=$document['twilio_token'];
				//$application_id=$document['application_id'];
			}
		}
		else 
		{
				$account_sid='00aa';
				$auth_token='54';
				$application_id='AP3e';
		}
		// put your Twilio API credentials here
		$accountSid = $account_sid;
		$authToken  = $auth_token;
 
		// put your Twilio Application Sid here
		//$appSid     = $application_id;
 
		$capability = new Services_Twilio_Capability($accountSid, $authToken);
		//$capability->allowClientOutgoing($appSid);
		$capability->allowClientIncoming('jenny');
		$data1['token'] = $capability->generateToken();


        $id=$this->mongo_db->db->riderdetails->find();
		
		foreach ($id as $user_detail) {					
		$data1['userid']=$user_detail['userid'];
		$data1['lat']=$user_detail['lat'];
		$data1['long']=$user_detail['long'];				
		}
			//print_r($data1);exit;
		$request_detail=$this->mongo_db->db->requests->find(array("status"=>'inprogress'));
		if($request_detail->hasNext())
		{
			$data1['request_detail']=$request_detail;
		}
		else 
		{
			$data1['request_detail']= "";
		}
		$data1['message_element'] = "administrator/requestdriver/driver_call_request";
		$this->load->view('administrator/admin_template', $data1);
}

function view_callreq_driver($mobile_number)
	{		
			
		$data['ridername']= $ridername = $this->mongo_db->db->users->find(array("mobile_no"=>$mobile_number));
		//print $data['ridername'];exit;
		if($ridername->hasNext())
		{
			foreach ($ridername as $riderdetail) {
				 $data['check_userid']=$userid = $riderdetail['_id'];
			}
			if($userid){	
			 $data['oldtrips'] = json_decode(riderOldtrips($userid));
			}else{
			$data['oldtrips'] = "" ; 	
			}
		}
		else
		{
			$data['oldtrips'] = "" ; 
		}
		
		 //print_r($data['oldtrips']);
		//$data['drivername'] = $driver['firstname'];
		
	    $data['message_element'] = "administrator/staff/view_callreq_driver";
	    $this->load->view('administrator/staff/view_callreq_driver',$data);
		
		//$this->load->view('administrator/staff/view_req_driver');
	}
function call_request()
{

		extract($this->input->get());		
		$result=$this->mongo_db->db->riderdetails->find();	
		$data = array(
					'userid'   => $userid,
					'lat'      => $lat,
					'long'     => $long, );	
		if($result->hasNext())
		{			
			$result1 = $this->mongo_db->db->riderdetails->update(array(),$data);
		}
		else 
		{
			
				$result1 = $this->mongo_db->db->riderdetails->insert($data);
		}
					
				
	
/*
$data1['userid']=$userid;
			$data1['lat']=$lat;
			$data1['long']=$long;*/

	
		//echo $this->userid;exit;
		
		
		
		$data1['message_element'] = "administrator/requestdriver/driver_request";
		$this->load->view('administrator/admin_template', $data1);
		}

	


function driver_request()
{

$id=$this->mongo_db->db->riderdetails->find();
		
	foreach ($id as $user_detail) {
					
		$data1['userid']=$user_detail['userid'];
		$data1['lat']=$user_detail['lat'];
		$data1['long']=$user_detail['long'];
					
				
		}
            // echo "<pre>";        
			// print_r($data1); exit;
		 
		
	$data1['message_element'] = "administrator/requestdriver/driver_request";
		$this->load->view('administrator/admin_template', $data1);
}

function reloadview()
{
	   
    $result=$this->mongo_db->db->drivers->find(array("status"=>'on'));
	
	if($result->hasNext())
	{
		$j_arr[]=1;
			foreach($result as $document)
				 {
					$data['firstname'] = $document['firstname'];
					$data['lastname'] = $document['lastname'];
					$data['prof_pic'] = $document['prof_pic'];
					$data['email'] = $document['email'];
					$data['lat'] = $document['lat'];
					$data['long'] = $document['long'];
					$data['mobile_no'] = $document['mobile_no'];
					$data['password'] = $document['password'];
					$data['proof_status'] = $document['proof_status'];
					$data['_id'] = $document['_id'];
					$data['dbregid'] = $document['regid'];			
					$arr_temp[] = $data['lat'];
					$arr_temp1[] =$data['long'];
					$arr_drivername[] =$data['firstname'];
					$arr_email[] =$data['email'];
					$arr_mobile[]=$data['mobile_no'];
						$arr_id[] = 	$data['_id'];
					
					 }
	
	}	
	else {
		$j_arr[]=0;
		//$arr_temp[] = 13.060422000000000000;
				//$arr_temp1[] =80.249583000000030000;
					//$arr_email[] ="rk@gmail.com";
					//$arr_drivername[] ="admin";
					//$arr_mobile[]=900000000;
						//$arr_id[] = 23402982002;
						
				    $arr_temp = array();
				    $arr_temp1 = array();
					$arr_email[] = array();
					$arr_drivername[] = array();
					$arr_mobile[]= array();
				    $arr_id[] =  array();
	}
	
$returnValue['j'] = $j_arr;
$returnValue['lat'] = $arr_temp;
$returnValue['long1'] = $arr_temp1;
$returnValue['email'] = $arr_email;
$returnValue['drivername'] = $arr_drivername;
$returnValue['mobile'] = $arr_mobile;
$returnValue['id'] = $arr_id;

echo json_encode($returnValue);
	
 
	}
	function reloadview1()
{
	   
    $result=$this->mongo_db->db->drivers->find(array("status"=>'off',"tripstatus"=>'busy'));
	
	
	if($result->hasNext())
	{
			foreach($result as $document)
				 {
					$data['firstname'] = $document['firstname'];
					$data['lastname'] = $document['lastname'];
					$data['prof_pic'] = $document['prof_pic'];
					$data['email'] = $document['email'];
					$data['lat'] = $document['lat'];
					$data['long'] = $document['long'];
					$data['mobile_no'] = $document['mobile_no'];
					$data['password'] = $document['password'];
					$data['proof_status'] = $document['proof_status'];
					$data['_id'] = $document['_id'];
					$data['dbregid'] = $document['regid'];			
					$arr_temp[] = $data['lat'];
					$arr_temp1[] =$data['long'];
					$arr_drivername[] =$data['firstname'];
					$arr_email[] =$data['email'];
					$arr_mobile[]=$data['mobile_no'];
						$arr_id[] = 	$data['_id'];
					
					 }
	
	}	
	else {
		
		$arr_temp[] = 348;
				$arr_temp1[] =45;
					$arr_email[] ="rk@gmail.com";
					$arr_drivername[] ="admin";
					$arr_mobile[]=900000000;
						$arr_id[] = 23402982002;
	}
	
$returnValue['lat'] = $arr_temp;
$returnValue['long1'] = $arr_temp1;
$returnValue['email'] = $arr_email;
$returnValue['drivername'] = $arr_drivername;
$returnValue['mobile'] = $arr_mobile;
$returnValue['id'] = $arr_id;

echo json_encode($returnValue);
	
 
	}
	function reloadview2()
{
	   
    $result=$this->mongo_db->db->drivers->find(array("status"=>'off',"tripstatus"=>'start'));
	
	
	if($result->hasNext())
	{
			foreach($result as $document)
				 {
					$data['firstname'] = $document['firstname'];
					$data['lastname'] = $document['lastname'];
					$data['prof_pic'] = $document['prof_pic'];
					$data['email'] = $document['email'];
					$data['lat'] = $document['lat'];
					$data['long'] = $document['long'];
					$data['mobile_no'] = $document['mobile_no'];
					$data['password'] = $document['password'];
					$data['proof_status'] = $document['proof_status'];
					$data['_id'] = $document['_id'];
					$data['dbregid'] = $document['regid'];			
					$arr_temp[] = $data['lat'];
					$arr_temp1[] =$data['long'];
					$arr_drivername[] =$data['firstname'];
					$arr_email[] =$data['email'];
					$arr_mobile[]=$data['mobile_no'];
						$arr_id[] = 	$data['_id'];
					
					 }
	
	}	
	else {
		
		$arr_temp[] = 348;
				$arr_temp1[] =45;
					$arr_email[] ="rk@gmail.com";
					$arr_drivername[] ="admin";
					$arr_mobile[]=900000000;
						$arr_id[] = 23402982002;
	}
	
$returnValue['lat'] = $arr_temp;
$returnValue['long1'] = $arr_temp1;
$returnValue['email'] = $arr_email;
$returnValue['drivername'] = $arr_drivername;
$returnValue['mobile'] = $arr_mobile;
$returnValue['id'] = $arr_id;

echo json_encode($returnValue);
	
 
	}
function reloadview3()
{
	   
    $result=$this->mongo_db->db->drivers->find(array("status"=>'off',"tripstatus"=>'end'));
	
	
	if($result->hasNext())
	{
			foreach($result as $document)
				 {
					$data['firstname'] = $document['firstname'];
					$data['lastname'] = $document['lastname'];
					$data['prof_pic'] = $document['prof_pic'];
					$data['email'] = $document['email'];
					$data['lat'] = $document['lat'];
					$data['long'] = $document['long'];
					$data['mobile_no'] = $document['mobile_no'];
					$data['password'] = $document['password'];
					$data['proof_status'] = $document['proof_status'];
					$data['_id'] = $document['_id'];
					$data['dbregid'] = $document['regid'];			
					$arr_temp[] = $data['lat'];
					$arr_temp1[] =$data['long'];
					$arr_drivername[] =$data['firstname'];
					$arr_email[] =$data['email'];
					$arr_mobile[]=$data['mobile_no'];
						$arr_id[] = 	$data['_id'];
					
					 }
	
	}	
	else {
		
		$arr_temp[] = 348;
				$arr_temp1[] =45;
					$arr_email[] ="rk@gmail.com";
					$arr_drivername[] ="admin";
					$arr_mobile[]=900000000;
						$arr_id[] = 23402982002;
	}
	
$returnValue['lat'] = $arr_temp;
$returnValue['long1'] = $arr_temp1;
$returnValue['email'] = $arr_email;
$returnValue['drivername'] = $arr_drivername;
$returnValue['mobile'] = $arr_mobile;
$returnValue['id'] = $arr_id;

echo json_encode($returnValue);
	
 
	}
	public function Driver() {
	 
	 if(check_logged()) 
	    {
	 	$start = (int) $this->uri->segment(4,0);
		$row_count = 10;
		
		if($start > 0)
		   $offset			  = ($start-1) * $row_count;
		else
		   $offset			  =  $start * $row_count; 
	
		
		$data['DriverUsers'] = $this->drivers_model->GetAllDriverUsers($offset, $row_count);
	  
	  
		// Pagination config
		$p_config['base_url'] 			= base_url('administrator/members/Driver');
		$p_config['uri_segment'] 		= 4;
		$p_config['num_links'] 			= 5;
		$p_config['total_rows'] 		= $this->drivers_model->get_driver();
		
		$p_config['per_page'] 			= $row_count;
				
		// Init pagination
		$this->pagination->initialize($p_config);		
		
		// Create pagination links
		$data['pagination']     = $this->pagination->create_links2();
		
		// Load view
	$data['message_element'] = "administrator/members/view_driver";
	$this->load->view('administrator/admin_template', $data);
		}
else{
	redirect('administrator/admin/login');
}
		
	} 
	
		public function Rider() {
			if(check_logged()) 
	    {
			
			$start = (int) $this->uri->segment(4,0);
		
	 // Number of record showing per page
		$row_count = 10;
		
		if($start > 0)
		   $offset			  = ($start-1) * $row_count;
		else
		   $offset			  =  $start * $row_count; 
		
		// Get all users
		$data['RiderUsers'] = $this->drivers_model->GetAllRiderUsers($offset, $row_count);
		
		// Pagination config
		$p_config['base_url'] 			=  base_url('administrator/members/Rider');
		$p_config['uri_segment'] = 4;
		$p_config['num_links'] 		= 5;
		$p_config['total_rows'] 	= $this->drivers_model->get_user();
		$p_config['per_page'] 			= $row_count;
				
		// Init pagination
		$this->pagination->initialize($p_config);		
		
		// Create pagination links
		$data['pagination']     = $this->pagination->create_links2();
		
		// Load view
	$data['message_element'] = "administrator/members/view_rider";
	$this->load->view('administrator/admin_template', $data);
		}
		else{
	redirect('administrator/admin/login');
}
	} 

	public function RiderDelete() {
			if(check_logged()) 
	    {
		$TotalSegments = $this->uri->total_segments();
		$RiderId = $this->uri->segment($TotalSegments);
		$this->drivers_model->DeleteRider($RiderId);
		
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Rider Deleted Successfully'));
		redirect('administrator/members/Rider');
		}
		else{
	redirect('administrator/admin/login');
}
		}
	
		public function DriverDelete() {
			
		if(check_logged()) 
	    {
		$TotalSegments = $this->uri->total_segments();
		$DriverId = $this->uri->segment($TotalSegments);
		$this->drivers_model->DeleteDriver($DriverId);
		
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Driver Deleted Successfully'));
		redirect('administrator/members/Driver');
		}
		else{
	redirect('administrator/admin/login');
}
	}
	
	public function RiderEdit() {
		
		if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	$RiderId = $this->uri->segment($TotalSegments);
	
	if(isset($_POST['editrider'])) {
	$this->drivers_model->UpdateRiderDetailsBasedOnId($RiderId);
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Rider Updated Successfully'));
	redirect('administrator/members/Rider');
	}
	if(isset($_POST['edituser'])){
		redirect('administrator/members/Rider');
	}
		
	$data['RiderDetails'] =  $this->drivers_model->GetRiderDetailsBasedOnId($RiderId);
		
	$data['message_element'] = "administrator/members/view_edit_users";
	$this->load->view('administrator/admin_template', $data);
		}
			else{
	redirect('administrator/admin/login');
}
	}
	
	public function DriverEdit() {
		if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	$DriverId = $this->uri->segment($TotalSegments);
	
	if(isset($_POST['editdriver'])) {
	$this->drivers_model->UpdateDriverDetailsBasedOnId($DriverId);
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Driver Updated Successfully'));
	redirect('administrator/members/Driver');
	}
	if(isset($_POST['canceldriver'])){
		
		redirect('administrator/members/Driver');
	}
		
	$data['DriverDetails'] =  $this->drivers_model->GetDriverDetailsBasedOnId($DriverId);
		
	$data['message_element'] = "administrator/members/view_edit_driver";
	$this->load->view('administrator/admin_template', $data);
	}
else{
	redirect('administrator/admin/login');
}
	}
	
	public function DriverBan() {
		if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	$DriverId = $this->uri->segment($TotalSegments);
	
	$this->mongo_db->db->drivers->update(array('_id' =>  new MongoId($DriverId) ),array('$set'=>array("status"=> 'off')));
		
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Driver Banned Successfully'));
	redirect('administrator/members/Driver');
		}
		else{
	redirect('administrator/admin/login');
}
	}
	
	public function DriverUnban() {
		if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	$DriverId = $this->uri->segment($TotalSegments);
	
	$this->mongo_db->db->drivers->update(array('_id' =>  new MongoId($DriverId) ),array('$set'=>array("status"=> 'on')));
		
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Driver Unbanned Successfully'));
	redirect('administrator/members/Driver');
		}
			else{
	redirect('administrator/admin/login');
}
	}
	
	public function DriverChangePassword() {
			if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	$DriverId = $this->uri->segment($TotalSegments);
	
	if(isset($_POST['changepassword'])) {
	$this->drivers_model->DriverChangePasswordBasedOnId($DriverId);
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Password Changed Successfully'));
	redirect('administrator/members/Driver');
	}
		
	$data['DriverDetails'] =  $this->drivers_model->GetDriverDetailsBasedOnId($DriverId);
		
	$data['message_element'] = "administrator/members/view_change_password";
	$this->load->view('administrator/admin_template', $data);
		}
		else{
	redirect('administrator/admin/login');
}
	}
	
	public function changepassword() {
			if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	$RiderId = $this->uri->segment($TotalSegments);
	
	if(isset($_POST['changepassword'])) {
	$this->users_model->RiderChangePasswordBasedOnId($RiderId);
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Password Changed Successfully'));
	redirect('administrator/members/Rider');
	}
		
	$data['DriverDetails'] =  $this->users_model->GetRiderDetailsBasedOnId($RiderId);
		
	$data['message_element'] = "administrator/members/view_riderchange_password";
	$this->load->view('administrator/admin_template', $data);
		}
		else{
	redirect('administrator/admin/login');
}
	}
	
	public function RequestDuration() {
		if(check_logged()) 
	    {
		
	if(isset($_POST['requestaccept'])) {
	$this->drivers_model->UpdateRequestDuration();
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Request Accepted Duration Updated Successfully'));
	redirect('administrator/members/RequestDuration');
	}
	
	$data['DurationTime'] =  $this->drivers_model->GetDurationTime();
	
	$data['message_element'] = "administrator/RequestDuration";
	$this->load->view('administrator/admin_template', $data);
		}
	else{
	redirect('administrator/admin/login');
}	
	}
	
	public function DriverRequestNotification() {
		if(check_logged()) 
	    {
		
	if(isset($_POST['driver_request_notification'])) {
	$this->drivers_model->UpdateDriverRequestNotification();
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Driver Request Notification Updated Successfully'));
	redirect('administrator/members/DriverRequestNotification');
	}
	
	$data['distance'] =  $this->drivers_model->DriverRequestNotification();
	
	$data['message_element'] = "administrator/DriverRequestNotification";
	$this->load->view('administrator/admin_template', $data);
		}
	else{
	redirect('administrator/admin/login');
}	
	}

function facebooklogin()
{
	if($this->input->post('update')) {
	$Id = 1;
	$this->mongo_db->db->facebookkey->update(array("_id"=>new MongoId($this->input->post('Id'))),array('$set'=> array("fb_api_key"=>$this->input->post('fb_api'),"fb_secret_key"=>$this->input->post('fb_secret'))));
	
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Facebook Detail Updated Successfully'));
	redirect('administrator/members/facebooklogin');
	}
	$apisettings = $this->mongo_db->db->facebookkey->find();
	foreach($apisettings as $apisetting){
	$data['key'] = $apisetting['fb_api_key'];
	$data['secret'] = $apisetting['fb_secret_key'];
	$data['id'] = $apisetting['_id'];
	
	}
	
	$data['message_element'] = "administrator/fb_key";
	$this->load->view('administrator/admin_template', $data);
}



 function add_email()
{
	$this->session->unset_userdata('success');
	if(check_logged()) {
				  if($this->input->post('code') !="")
                    {
                  $mail=$this->users_model->addemail();
				  if($mail==1){
	
						$data['msg']=	//$this->session->set_userdata('success', $this->users_model->flash_message('success','Email Template Added successfully'));
						 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('success','Template added successfully'));
      redirect('administrator/members/manage_email');
						}
else{
	
				$data['msg']=	//$this->session->set_userdata('success', $this->users_model->flash_message('error','Email Code Already Taken'));
				 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('error','Email Code already Taken'));
      redirect('administrator/members/add_email');
}
           	$data['message_element'] = "administrator/email/add_email";
	$this->load->view('administrator/admin_template', $data);
                    }  
				  else{	
					$data['msg']='';
					  	$data['message_element'] = "administrator/email/add_email";
           $this->load->view('administrator/admin_template', $data);
				  }
             }
            else
            {
                $this->load->view('admin/login');
            }
}
function manage_email(){
	
	$this->session->unset_userdata('success');
	if(check_logged()) {
					
					$data['message']=$this->users_model->getemail();
					$data['msg']='';
					$data['count'] = $this->users_model->getemail()->count();
           	$data['message_element'] = "administrator/email/manage_email";
	        $this->load->view('administrator/admin_template', $data);
             }
            else
            {
                $this->load->view('admin/login');
            }
	
}
function manage_sms(){
	
	$this->session->unset_userdata('success');
	if(check_logged()) {
					
					$data['message']=$this->users_model->getsms();
					$data['msg']='';
					$data['count'] = $this->users_model->getsms()->count();
           	$data['message_element'] = "administrator/sms/manage_sms";
	        $this->load->view('administrator/admin_template', $data);
             }
            else
            {
                 $this->load->view('administrator/admin/login');
				 
            }
	
}

function edit_sms()
{
	if(check_logged()) {
				  if($this->input->post('code') !="")
                    {
                  $success= $this->users_model->updatsmsbyid();
				   	$data['message']=$this->users_model->getsms();
				   if($success!=0){
	
					 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('success','Template updated successfully'));
				   }
				   else{
				   	 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('error',' '.'Error updating the template'));
				   }
      redirect('administrator/members/manage_sms');
	       $this->load->view('administrator/sms/manage_sms',$data);
                    }  
				  else{
				  	
				  	 $result=$this->users_model->getsmsbyid();
					  if($result['count']!=0){
					  $data['message']=$result['records'];	
					$data['msg']='';
           
		   	 $data['message_element'] = "administrator/sms/edit_sms";
	         $this->load->view('administrator/admin_template', $data);
					  }else{
					  	 redirect('administrator/members/manage_sms');
					  }
					  
				  }
             }
            else
            {
                $this->load->view('administrator/view_login');
            }
	
}
function edit_email()
{
	if(check_logged()) {
				  if($this->input->post('code') !="")
                    {
                  $success= $this->users_model->updatemailbyid();
				   	$data['message']=$this->users_model->getemail();
				   if($success!=0){
	
					 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('success','Template updated successfully'));
				   }
				   else{
				   	 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('error',' '.'Error updating the template'));
				   }
      redirect('administrator/members/manage_email');
	       $this->load->view('administrator/email/manage_email',$data);
                    }  
				  else{
				  	
				  	 $result=$this->users_model->getemailbyid();
					  if($result['count']!=0){
					  $data['message']=$result['records'];	
					$data['msg']='';
           
		   	 $data['message_element'] = "administrator/email/edit_email";
	         $this->load->view('administrator/admin_template', $data);
					  }else{
					  	 redirect('administrator/members/manage_email');
					  }
					  
				  }
             }
            else
            {
                $this->load->view('administrator/view_login');
            }
	
}
function delete_email()
{
	if(check_logged()) {
				  if( $this->input->post('user_id') !="")
                    {
                    	$this->session->set_flashdata('success', $this->Common_model->admin_flash_message('success','Template deleted successfully'));
                   $this->users_model->deletemailbyid();
	                }  
				  else{
				  }
             }
            else
            {
                $this->load->view('administrator/view_login');
            }
	
}


public function RequestExpire() {
		if(check_logged()) 
	    {
		
	if(isset($_POST['requestaccept'])) {
	$this->Common_model->UpdateExpireDuration();
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Session Expire Duration Updated Successfully'));
	redirect('administrator/members/RequestExpire');
	}
	
	$data['DurationTime'] =  $this->Common_model->GetExpireTime();
	
	$data['message_element'] = "administrator/Session_expire";
	$this->load->view('administrator/admin_template', $data);
		}
	else{
	redirect('administrator/admin/login');
}	
	}
public function send_message(){
	
	
	
	$this->load->library('nexmo');
	$this->nexmo->set_format('json');
	
	extract ($this->input->post());
	
	  $setting_details = $this->mongo_db->db->settings->find();
	foreach($setting_details as $document){
		$nexmo_mobile_no= $document['nexmo_mobile_no'];
	} 
	$from =trim($nexmo_mobile_no);
        $to = '91'.$driver_mobile_no;
		$message = array(
            'text' => "Your document was verified successfully by Admin
            
         Arcane   
         
                     
            "
        );
		$response = $this->nexmo->send_message($from, $to, $message);  
		 if($this->nexmo->get_http_status() == 200)
        {
        	 $this->drivers_model->Updateproofstatus($driver_id); 
		echo "Success"; exit;
		}
else{
	echo "Error"; exit;
}
	
}
public function reject(){
	extract ($this->input->post());
	$this->drivers_model->rejectproofstatus($driver_id); 
}
public function twilio()
{
//	$data['count'] = $this->users_model->gettwilio()->count();
           	$data['message_element'] = "administrator/sms/sms_settings";
	        $this->load->view('administrator/admin_template', $data);
	// $this->load->view('administrator/sms/sms_settings');
}

public function twilio_settings()
	{
		
	if($this->input->post('update'))
		{
			$sid=$this->input->post('twilio_sid'); 
   $tok=$this->input->post('twilio_token'); 
   $num=$this->input->post('twilio_number');
 // if($sid <> ""&&$tok<> "" && $num<> ""){
		$success= $this->users_model->updatetwilio();
		echo '<p>Twilio Settings updated Successfully</p>';
	/*	$data['message']=$this->users_model->gettwilio();
			$data['message']= $this->twilio();
				 if($success!=0){
	
					 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('success','Twilio setting updated successfully'));
				   }
				   else{
				   	 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('error',' '.'Error updating Twilio setting'));
				   }*/  
				  //}
     
                    } else{ 
		$data['message_element'] = "administrator/sms/sms_settings";
		$this->load->view('administrator/admin_template', $data);
	}}
			/* $db = $this->mongo_db->db;
			 $updatedata=array(
        'twilio_sid'=> $this->input->post('twilio_sid'),
        'twilio_token'=> $this->input->post('twilio_token'),
                            'twilio_number'=> $this->input->post('twilio_number'),
                            );	
							//$id=$this->uri->segment(4);
							//echo $id;exit;
								//		 $realmongoid = new MongoId($id);
							             //echo $realmongoid;exit;
							           //  print_r($updatedata);exit;
       $data1= $db->twilio->update(array("no" => 1), array('$set' => $updatedata))->count;
	//	  $updatedata=$db->twilio->find(array("_id" => $realmongoid))->count();
			$data['message']=$this->users_model->gettwilio();
				   if($data1!=0){
	
					 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('success','Twilio setting updated successfully'));
				   }
				   else{
				   	 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('error',' '.'Error updating Twilio setting'));
				   }
      redirect('administrator/members/manage_sms');
	       $this->load->view('administrator/sms/sms_settings',$data);
		} else{
		//	redirect('administrator/members/manage_sms');
	        redirect('administrator/members/twilio_settings');
		}
		
	}*/
	
//---------------------------------
/* if($this->input->post('code') !="")
                    {
                  $success= $this->users_model->updatemailbyid();
				   	$data['message']=$this->users_model->getemail();
				   if($success!=0){
	
					 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('success','Template updated successfully'));
				   }
				   else{
				   	 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('error',' '.'Error updating the template'));
				   }
      redirect('administrator/members/manage_email');
	       $this->load->view('administrator/email/manage_email',$data);
                    }  
				  else{
				  	
				  	 $result=$this->users_model->getemailbyid();
					  if($result['count']!=0){
					  $data['message']=$result['records'];	
					$data['msg']='';
           
		   	 $data['message_element'] = "administrator/email/edit_email";
	         $this->load->view('administrator/admin_template', $data);
					  }else{
					  	 redirect('administrator/members/manage_email');
					  }
					  
				  }
*/
} // Class
?>
