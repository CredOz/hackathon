<?php
/*
  error_reporting(E_ALL);
ini_set('display_errors', 'On');

*/


class Management extends CI_Controller
{
	// Used for registering and changing password form validation
	var $min_username = 4;
	var $max_username = 20;
	var $min_password = 4;
	var $max_password = 20;
	
		public function __construct()
	{

		parent::__construct();
		$this->load->model('Rider_model');
		$this->load->model('Common_model');
		$this->load->model('Drivers_model');
		$this->load->library('Pagination');
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('url');
 		$this->load->helper('file');
		$this->load->library('Table');
		$this->load->library('mongo_db');
		
	}


function view_req_driver($driverid)
	{
		$data['drivername']= $this->mongo_db->db->drivers->findone(array("_id"=>new Mongoid($driverid)));
		//$data['drivername'] = $driver['firstname'];
		
		$data['ridername_select']=$this->mongo_db->db->users->find()->sort(array('firstname' => 1));
	
	//carcategory
	$data['category_select']=$this->mongo_db->db->category->find();
		
		
		$data['message_element'] = "administrator/staff/view_req_driver";
	    $this->load->view('administrator/admin_template',$data);
		
		//$this->load->view('administrator/staff/view_req_driver');
	}
	public function search()
	{
		if(check_logged()){
			$start = (int) $this->uri->segment(4,0);
			$row_count = 15;
		
		if($start > 0)
		 $data['offset'] =   $offset			  = ($start-1) * $row_count;
		else
		  $data['offset'] =  $offset			  =  $start * $row_count; 
			$keyword=$this->input->post('keyword');
			
			
			if(!$keyword){
			redirect('administrator/management/member');
		}
		
		$data['memberUsers']=$this->Rider_model->search($keyword);
		$p_config['base_url'] 			=  base_url('administrator/management/search');
		$p_config['uri_segment'] = 4;
		$p_config['num_links'] 		= 5;
		$p_config['total_rows'] 	= $this->Rider_model->get_user();


		$p_config['per_page'] 			= $row_count;
				
		// Init pagination
	$data['pagination']     = 	$this->pagination->create_links();		
		
		// Create pagination links
	//	$data['pagination']     = $this->pagination->remove_links2();
$data['message_element'] = "administrator/management/view_member";
	$this->load->view('administrator/admin_template', $data);
//	redirect('administrator/management/Rider');

	
	}
	
	}

function pricedetails()
{
		if(check_logged()) 
	    {
	
		
	if(isset($_POST['update'])) {
		
    $updatekey = array("id"=> "1");
	$updatevalue = array("price_per_km"=> $_POST['price'], "nearby_distance" => $_POST['distance']);
	$result =  $this->Drivers_model->update_table("limit",$updatekey, $updatevalue);
	
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Values Updated Successfully'));
	redirect('administrator/management/pricedetails');
	}

    	
	$result =  $this->Drivers_model->GetDurationTime();
	foreach($result as $document){
		$data['distance']= $document['nearby_distance'];
		$data['price']= $document['price_per_km'];
	} 
	$data['message_element'] = "administrator/pricedetails";
	$this->load->view('administrator/admin_template', $data);
		}
	else{
	redirect('administrator/admin/login');
}
	
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
		
		
			$data1['order']=$this->mongo_db->db->requests->find(array('$or' => array(array('status' => 'request'), array('status' => 'process'))));
		$data1['preorder']=$this->mongo_db->db->requests->find(array('$or' => array(array('status' => 'inprogress'), array('status' => 'process'))));
		$data1['history']=$this->mongo_db->db->requests->find()->sort(array('_id' => -1))->limit(10);
		
		$data1['message_element'] = "administrator/requestdriver/driver_request";
		$this->load->view('administrator/admin_template', $data1);
		}

	



function driver_request()
{
 
if(check_logged()){
		require(APPPATH."/libraries/Services/Twilio/Capability.php");
		
		$result = $this->mongo_db->db->twilio->find(array("no"=>'1'));
		if($result->hasNext())
		{		
			foreach($result as $document)
			{
			 	$account_sid=$document['twilio_sid'];
			 	$auth_token=$document['twilio_token'];
				//$application_id=$document['twilio_number'];
			}
		}
		else 
		{
				$account_sid='AC04ced8a6fd92ee0a1a55f22b78dcc73a';
				$auth_token='d460f042a85acb616c33ff42cb9ecdff';
				$application_id='AP3e';
		}
		// put your Twilio API credentials here
		$accountSid = $account_sid;
		$authToken  = $auth_token;
 
		// put your Twilio Application Sid here
		//$appSid     = $application_id;
 
		$capability = new Services_Twilio_Capability($accountSid, $authToken);
		//$capability->allowClientOutgoing($accountSid);
		$capability->allowClientIncoming('jenny');
		$data1['token'] = $capability->generateToken();
		
$id=$this->mongo_db->db->riderdetails->find();
		
		foreach ($id as $user_detail) {					
		$data1['userid']=$user_detail['userid'];
		$data1['lat']=$user_detail['lat'];
		$data1['long']=$user_detail['long'];				
		}
			//print_r($data1);exit;
			$data1['order']=$this->mongo_db->db->requests->find(array('$or' => array(array('status' => 'request'), array('status' => 'process'))))->sort(array('_id' => -1));
		$data1['preorder']=$this->mongo_db->db->requests->find(array('$or' => array(array('status' => 'inprogress'), array('status' => 'process'))))->sort(array('_id' => -1));
			$data1['history']=$this->mongo_db->db->requests->find()->sort(array('_id' => -1));
	$data1['availdriver']=$drivers=get_avail_drivers();
	//autocomplete ridername
	$data1['ridername_select']=$this->mongo_db->db->users->find()->sort(array('firstname' => 1));
	//carcategory
	$data1['category_select']=$this->mongo_db->db->category->find()->sort(array('firstname' => 1));
	
	if($drivers=="1")
	{
		$data1['availdriver']=array();
	}
	else
	{
		$data1['availdriver']=$drivers;
	}
	$data1['message_element'] = "administrator/requestdriver/driver_request";
	$this->load->view('administrator/admin_template', $data1);
	
	}
else{
    redirect('administrator/admin/login');
}
	}


function dumdriver_request()
{
	$data1['message_element'] = "administrator/requestdriver/driver_requestpage";
	$this->load->view('administrator/admin_template', $data1);
}
function add_drivers()
{
	$data1['message_element'] = "administrator/requestdriver/add_drivers";
	$this->load->view('administrator/admin_template', $data1);
}
function addnewdriver()
{
	//echo "hai";
	extract($this->input->post());
	///print_r($_POST);
	$data1['firstname']=$fname;
	$data1['lastname']=$lname;
	$data1['password']=$password;
	$data1['email']=$email;
	$data1['created']=time();
	$data1['mobile_no']=$mobile_no;
	$data1['carname']=$carname;
	$data1['carcategory']=$carcategory;
	$data1['prof_pic']='null';
	$data1['proof_status']='InProgress';
	$email      =$email;
	//$data['licence']=$licenphoto;
	//$data['voterid']=$addressphoto;
	$result=$this->Drivers_model->check_data($email);
	//print_r($result);exit;
	if($result==false)
	{
		
	$proof_status="InProgress";
	$data1['proof_status']=$proof_status;
	$status = "";
			$msg = "";
			$file_element_name = 'image1';
			$file_element_name1 = 'image2';
			if ($status != "error")	
			{
				
	//	$config['upload_path'] = '/var/ftp/virtual_users/tastenote/tastenote.com/files/gastronote/';			
		$config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/documents'; //Set the upload path
	//$config['upload_path'] = '/opt/lampp/htdocs/celebspot.git/files/'; 
				$config['allowed_types'] = 'gif|jpg|png|jpeg'; // Set image type
				$config['encrypt_name']	= TRUE; // Change image name
				
				$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if(!$this->upload->do_upload($file_element_name)){
						$status = 'error';
						$msg = $this->upload->display_errors('','');
						$error = array('error' => $this->upload->display_errors());print_r($error);
						$data = "";
					}
					else {
							
						$data = $this->upload->data(); // Get the uploaded file information
						
						$this->load->library('image_lib');
						
						$config['image_library'] = 'gd2';
						$config['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/documents/'.$data['raw_name'].$data['file_ext'];
						$config['create_thumb'] = FALSE;
						$config['maintain_ratio'] = TRUE;
						
						$config['width']     = 125;
    					$config['height']   = 125;
						
    				   $this->image_lib->initialize($config);
    				   $this->image_lib->resize();
					//  $imagespath= '.$this->config->item('base_url').'images/documents/'.$data['raw_name'].$data['file_ext'].';
						//echo $this->config->item('base_url').'images/'.$data['raw_name'].$data['file_ext'];
						$imagepath= $this->config->item('base_url').'images/documents/'.$data['raw_name'].$data['file_ext'];
						//print_r($imagepath);exit;
					}
					if(!$this->upload->do_upload($file_element_name1)){
						$status = 'error';
						$msg = $this->upload->display_errors('','');
						$error = array('error' => $this->upload->display_errors());print_r($error);
						$data = "";
					}
					else {
							
						$data = $this->upload->data(); // Get the uploaded file information
						
						$this->load->library('image_lib');
						
						$config['image_library'] = 'gd2';
						$config['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/documents/'.$data['raw_name'].$data['file_ext'];
						$config['create_thumb'] = FALSE;
						$config['maintain_ratio'] = TRUE;
						
						$config['width']     = 125;
    					$config['height']   = 125;
						
    				   $this->image_lib->initialize($config);
    				   $this->image_lib->resize();
					//  $imagespath= '.$this->config->item('base_url').'images/documents/'.$data['raw_name'].$data['file_ext'].';
						//echo $this->config->item('base_url').'images/'.$data['raw_name'].$data['file_ext'];
						$imagepath2= $this->config->item('base_url').'images/documents/'.$data['raw_name'].$data['file_ext'];
						//print_r($imagepath);exit;
					}
					
				@unlink($_FILES[$file_element_name]);
			}

	//$result=$this->Drivers_model->check_data($email);
	//$result1=$this->Drivers_model->check_data1($mobile_no);
	$data1['licence']=$imagepath;
	$data1['voterid']=$imagepath2;
	$avaliable="on";
	$data1[availability]=$avaliable;
	// $status="on";
	// $data1['status']=$status;
		$result = $this->mongo_db->db->drivers->insert($data1);
	//$this->Drivers_model->insertReservationDetailsBasedOnId($RiderId);
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success1','member Added Successfully'));
	redirect('administrator/management/member');
	}
else {
$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success1','Email Address already haven'));
		redirect('administrator/management/add_drivers');
}
}
function check_data($email)
	{
	//$result = $this->mongo_db->db->users->find(array("email"=>$email,"password"=>$password));
	
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
		
		
		$data1['order']=$this->mongo_db->db->requests->find(array('$or' => array(array('status' => 'request'), array('status' => 'process'))));
		$data1['preorder']=$this->mongo_db->db->requests->find(array('$or' => array(array('status' => 'inprogress'), array('status' => 'process'))));
		$data1['history']=$this->mongo_db->db->requests->find()->sort(array('_id' => -1))->limit(10);
		$drivers=get_avail_drivers();
		if($drivers=="1")
		{
			$data1['availdriver']=array();
		}
		else
		{
			$data1['availdriver']=$drivers;
		}
		
		$data1['message_element'] = "administrator/requestdriver/driver_call_request";
		$this->load->view('administrator/admin_template', $data1);
}
//

function apiconnect(){
	
    if(check_logged()) 
        {
        	
$this->form_validation->set_error_delimiters('<p>', '</p>');

    if($this->input->post('update')) {
   
    $this->form_validation->set_rules('fb_api_key','Facebook API key','required|trim');
    $this->form_validation->set_rules('fb_secret_key','Facebook secret key','required|trim');
	
    $this->form_validation->set_rules('google_api_key','Google api key','required|trim');
    $this->form_validation->set_rules('google_project_id','Google project id','required|trim');
	$this->form_validation->set_rules('Client_ID','Google Client ID','required|trim');
	
	$this->form_validation->set_rules('Project_ID','Client ID','required|trim');
    $this->form_validation->set_rules('WebAPI_Key','Web API Key','required|trim'); 
	$this->form_validation->set_rules('DB_URL','Databse URL','required|trim'); 
	
	$this->form_validation->set_rules('Test_PublishKey','Test Puublish Key','required|trim'); 
	$this->form_validation->set_rules('Test_ApiKey','Test API Key','required|trim'); 
	
	$this->form_validation->set_rules('Live_PublishKey','Live Puublish Key','required|trim'); 
	$this->form_validation->set_rules('Live_ApiKey','Live API Key','required|trim'); 

    if($this->form_validation->run()){
    	$updatekey = array("id"=> '1');
    	$updatevalue = array(
    	"fb_api_key"=>$this->input->post('fb_api_key'),
    	"fb_secret_key"=>$this->input->post('fb_secret_key'),
    	"google_api_key"=>$this->input->post('google_api_key'),
    	"google_project_id"=>$this->input->post('google_project_id'),
    	"Client_ID"=>$this->input->post('Client_ID'),
    	"Project_ID"=>$this->input->post('Project_ID'),
    	"WebAPI_Key"=>$this->input->post('WebAPI_Key'),
    	"DB_URL"=>$this->input->post('DB_URL'),
    	"Test_ApiKey"=>$this->input->post('Test_ApiKey'),
    	"Test_PublishKey"=>$this->input->post('Test_PublishKey'),
    	"Live_PublishKey"=>$this->input->post('Live_PublishKey'),
    	"Live_ApiKey"=>$this->input->post('Live_ApiKey'),
    	"is_live_stripe"=>$this->input->post('is_live_stripe')
		);
     $this->mongo_db->db->settings->update($updatekey,array('$set'=> $updatevalue));
     $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Values Updated Successfully'));
    redirect('administrator/management/apiconnect');
    }
	else
	{
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Enter all required field values'));
	redirect('administrator/management/apiconnect');		
	}
    }

    $apisettings = $this->mongo_db->db->settings->find();
    
    foreach($apisettings as $apisetting){
        
    $data['fb_api_key'] = $apisetting['fb_api_key'];
    $data['fb_secret_key'] = $apisetting['fb_secret_key'];
    $data['google_api_key'] = $apisetting['google_api_key'];
    $data['google_project_id'] = $apisetting['google_project_id'];
	$data['Client_ID'] = $apisetting['Client_ID'];
    $data['Project_ID'] = $apisetting['Project_ID'];
	$data['WebAPI_Key'] = $apisetting['WebAPI_Key'];
    $data['DB_URL'] = $apisetting['DB_URL'];
	
	$data['Test_PublishKey'] = $apisetting['Test_PublishKey'];
    $data['Test_ApiKey'] = $apisetting['Test_ApiKey'];
	
	$data['Live_PublishKey'] = $apisetting['Live_PublishKey'];
    $data['Live_ApiKey'] = $apisetting['Live_ApiKey'];
	
	$data['is_live_stripe'] = $apisetting['is_live_stripe'];
   
    }
  
   $data['message_element'] = "administrator/apiconnect";
   $this->load->view('administrator/admin_template', $data);
   
    }
else{
    redirect('administrator/admin/login');
}

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
function view_callreq_driver($mobile_number)
	{		
			
		$data['ridername']= $ridername = $this->mongo_db->db->users->find(array("mobile_no"=>$mobile_number));
		if($ridername->hasNext())
		{
			foreach ($ridername as $riderdetail) 
			{
				 $data['check_userid']=$userid = $riderdetail['_id'];
				 $data['email']=$riderdetail['email'];
				 $data['mobile']=$riderdetail['mobile_no'];
				 $data['firstname']=$riderdetail['firstname'];
				 $data['lat']=$riderdetail['lat'];
				 $data['long']=$riderdetail['long'];
			}
			if($userid)
			{	
				$data['oldtrips'] = riderOldtrips($userid);
			}else{
				$data['oldtrips'] = "" ; 	
			}
		}
		else
		{
			$data['oldtrips'] = "" ; 
			$data['check_userid']= "" ; 
			$data['mobile']=$mobile_number;
		}
		
			$data['category_select']=$this->mongo_db->db->category->find();
			
		 //print_r($data['oldtrips']);
		//$data['drivername'] = $driver['firstname'];
		
	    $data['message_element'] = "administrator/staff/view_callreq_driver";
	    $this->load->view('administrator/staff/view_callreq_driver',$data);
		
		//$this->load->view('administrator/staff/view_req_driver');
	}
// 		
	// function reloadview()
// {
// 	   
    // $result=$this->mongo_db->db->drivers->find(array("status"=>'on'));
// 	
	// if($result->hasNext())
	// {
			// foreach($result as $document)
				 // {
					// $data['firstname'] = $document['firstname'];
					// $data['lastname'] = $document['lastname'];
					// $data['prof_pic'] = $document['prof_pic'];
					// $data['email'] = $document['email'];
					// $data['lat'] = $document['lat'];
					// $data['long'] = $document['long'];
					// $data['mobile_no'] = $document['mobile_no'];
					// $data['password'] = $document['password'];
					// $data['proof_status'] = $document['proof_status'];
					// $data['_id'] = $document['_id'];
					// $data['dbregid'] = $document['regid'];			
					// $arr_temp[] = $data['lat'];
					// $arr_temp1[] =$data['long'];
					// $arr_drivername[] =$data['firstname'];
					// $arr_email[] =$data['email'];
					// $arr_mobile[]=$data['mobile_no'];
						// $arr_id[] = 	$data['_id'];
// 					
					 // }
// 	
	// }	
	// else {
// 		
		// $arr_temp[] = 348;
				// $arr_temp1[] =45;
					// $arr_email[] ="rk@gmail.com";
					// $arr_drivername[] ="admin";
					// $arr_mobile[]=900000000;
						// $arr_id[] = 23402982002;
	// }
// 	
// $returnValue['lat'] = $arr_temp;
// $returnValue['long1'] = $arr_temp1;
// $returnValue['email'] = $arr_email;
// $returnValue['drivername'] = $arr_drivername;
// $returnValue['mobile'] = $arr_mobile;
// $returnValue['id'] = $arr_id;
// 
// echo json_encode($returnValue);
// 	
//  
	// }
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
function reloadview11()
{
	   
    $result=$this->mongo_db->db->drivers->find(array("status"=>'off'));
	
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
				//	$arr_temp[] = $data['lat'];
					//$arr_temp1[] =$data['long'];
					//$arr_drivername[] =$data['firstname'];
				//	$arr_email[] =$data['email'];
					//$arr_mobile[]=$data['mobile_no'];
					//	$arr_id[] = 	$data['_id'];
					$busyid= (string)$document['_id'];
//echo "<br>".$busyid;
						
						$result1=$this->mongo_db->db->users->find(array("driverid"=>$busyid));
	
							if($result1->hasNext())
							{
 //  echo "<br>"."4";
									foreach($result1 as $doc)
									 {
   //echo "<br>"."5";
								$rider=$doc['firstname'];
									//echo $rider;
								if(isset($data['lat']) && isset($data['long']))	
								{
								$arr_temp[] = $data['lat'];
								$arr_temp1[] =$data['long'];		
								}		
			
								$arr_drivername[] =$data['firstname'];
								$arr_email[] =$data['email'];
								$arr_mobile[]=$data['mobile_no'];
									$arr_id[] = 	$data['_id'];
						//$locationsbusy[]=$data;
									//$this->session->set_userdata('rajiii',$rider);
    
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
	
	
	
	public function member() {
	// $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Rider Updated Successfully'));
	 if(check_logged()) 
	    {
	    	$data_1 = array();
		
		$data['memberUsers'] = $this->Rider_model->find_data("foods",$data_1);

		// Load view
	$data['message_element'] = "administrator/management/view_member";
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
		$data['RiderUsers'] = $this->Drivers_model->GetAllRiderUsers($offset, $row_count);
		
		// Pagination config
		$p_config['base_url'] 			=  base_url('administrator/management/Rider');
		$p_config['uri_segment'] = 4;
		$p_config['num_links'] 		= 5;
		$p_config['total_rows'] 	= $this->Drivers_model->get_user();
		$p_config['per_page'] 			= $row_count;
				
		// Init pagination
		$this->pagination->initialize($p_config);		
		
		// Create pagination links
		$data['pagination']     = $this->pagination->create_links();
//$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Rider Updated Successfully'));		
		// Load view
	$data['message_element'] = "administrator/management/view_rider";
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
		$this->Drivers_model->DeleteRider($RiderId);
		
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Rider Deleted Successfully'));
		redirect('administrator/management/Rider');
		}
		else{
	redirect('administrator/admin/login');
}
		}
	
		public function memberDelete() {
			
		if(check_logged()) 
	    {
		$TotalSegments = $this->uri->total_segments();
		$memberId = $this->uri->segment($TotalSegments);
		$this->Rider_model->DeleteUser($memberId);
		
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Food Deleted Successfully'));
		redirect('administrator/management/member');
		}
		else{
	redirect('administrator/admin/login');
}
	}
public function webmemberDelete() {
			
		if(check_logged()) 
	    {
		$TotalSegments = $this->uri->total_segments();
		$memberId = $this->uri->segment($TotalSegments);
		$this->Rider_model->DeleteUser($memberId);
		
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','member Deleted Successfully'));
		redirect('administrator/management/dumdriver_request');
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
	$this->Drivers_model->UpdateRiderDetailsBasedOnId($RiderId);
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Rider Updated Successfully'));
	redirect('administrator/management/Rider');
	}
	if(isset($_POST['edituser'])){
		redirect('administrator/management/Rider');
	}
		
	$data['RiderDetails'] =  $this->Drivers_model->GetRiderDetailsBasedOnId($RiderId);
		
	$data['message_element'] = "administrator/management/view_edit_users";
	$this->load->view('administrator/admin_template', $data);
		}
			else{
	redirect('administrator/admin/login');
}
	}
	public function memberstatus() {
		if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	$memberId = $this->uri->segment($TotalSegments);
	//print_r($memberId);exit;
	$this->mongo_db->db->drivers->update(array('_id' =>  new MongoId($memberId) ),array('$set'=>array("status"=> 'off')));
	redirect('administrator/management/dumdriver_request');
		}
		else{
	redirect('administrator/admin/login');
}
	}
		public function memberstatuson() {
		if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	$memberId = $this->uri->segment($TotalSegments);
	//print_r($memberId);exit;
	$this->mongo_db->db->drivers->update(array('_id' =>  new MongoId($memberId) ),array('$set'=>array("status"=> 'on')));
	redirect('administrator/management/dumdriver_request');
		}
		else{
	redirect('administrator/admin/login');
}
	}
	public function memberEdit() {
		if(check_logged()) 
	    {
 	$TotalSegments = $this->uri->total_segments();
	
	
	$memberId = $this->uri->segment($TotalSegments);
	if(isset($_POST['editmember'])) {
	extract($this->input->post());
	
	$data_update =	array("food_name"=> $food_name, "food_decription"=> $food_decription,'food_ingredient' => $food_ingredient,
	'prepare_time'=> $prepare_time,
	'food_preparation'=> $food_preparation
	
	);
	
	$this->Rider_model->update_Tabdata("foods",array("_id"=>new MongoId($memberId)), $data_update);
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Food data Updated Successfully'));
	redirect('administrator/management/member');
	}
	if(isset($_POST['canceldriver'])){
		
		redirect('administrator/management/member');
	}
		
	$data['MemberDetails'] =  $this->Rider_model->GetDetailsById("foods", $memberId);
		
	$data['message_element'] = "administrator/management/view_edit_member";
	$this->load->view('administrator/admin_template', $data);
	}
else{
	redirect('administrator/admin/login');
}
	}
	
	public function memberBan() {
		if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	$memberId = $this->uri->segment($TotalSegments);
	
	$this->mongo_db->db->drivers->update(array('_id' =>  new MongoId($memberId) ),array('$set'=>array("status"=> 'off')));
		
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success1','member Banned Successfully'));
	redirect('administrator/management/member');
		}
		else{
	redirect('administrator/admin/login');
}
	}
	
	
	public function edit_orders()
	{
		if($this->input->post())
		{
			extract($this->input->post());
			$this->mongo_db->db->requests->update(array('_id' =>  new MongoId($id) ),array('$set'=>array("pickup"=> $pickup,"dropup"=> $drop)));
			if($lat!="" and $long!="")
			{
				$this->mongo_db->db->users->update(array('_id' =>  new MongoId($userid) ),array('$set'=>array("lat"=> $lat,"long"=> $long)));				
			}
			redirect('administrator/management/member_request');
		}
		else 
		{
			extract($this->input->get());
			$result=$this->mongo_db->db->requests->findOne(array("_id"=>new MongoId($id)));
			$data['id']=$id;
			$data['userid']=$userid=$result['user_id'];
			$data['pickup']=$result['pickup'];
			$data['drop']=$result['dropup'];
			$result_user=$this->mongo_db->db->users->findOne(array("_id"=>new MongoId($userid)));
			$data['firstname']=$result_user['firstname'];
			$data['mobile']=$result_user['mobile_no'];
			$data['lat']=$result_user['lat'];
			$data['long']=$result_user['long'];
			$data['message_element'] = "administrator/requestdriver/editorders";
			$this->load->view('administrator/admin_template', $data);	
		}

		

	}
	
	
	public function Searchres()
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
			redirect('administrator/management/Reservation');
		}
	$data['RiderUsers']=$this->Drivers_model->searchres($keyword);	
    $p_config['base_url'] 			=  base_url('administrator/management/Searchres');
		$p_config['uri_segment'] = 4;
		$p_config['num_links'] 		= 5;
		$p_config['total_rows'] 	= $this->Drivers_model->get_reservation();
		$p_config['per_page'] 			= $row_count;
				
		// Init pagination
	$data['pagination'] =	$this->pagination->initialize($p_config);		
		
		// Create pagination links
	//	$data['pagination']     = $this->pagination->create_links();

$data['message_element'] = "administrator/management/view_reservation";
	$this->load->view('administrator/admin_template', $data);
	//redirect('administrator/management/Rider');
		}
	
	}
public function Reservation() {
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
		$data['RiderUsers'] = $this->Drivers_model->GetAllReservationUsers($offset, $row_count);
		
		// Pagination config
		$p_config['base_url'] 			=  base_url('administrator/management/Reservation');
		$p_config['uri_segment'] = 4;
		$p_config['num_links'] 		= 5;
		$p_config['total_rows'] 	= $this->Drivers_model->get_reservation();
		$p_config['per_page'] 			= $row_count;
				
		// Init pagination
		$this->pagination->initialize($p_config);		
		
		// Create pagination links
		$data['pagination']     = $this->pagination->create_links();
		
		// Load view
	$data['message_element'] = "administrator/management/view_reservation";
	$this->load->view('administrator/admin_template', $data);
		}
		else{
	redirect('administrator/admin/login');
}
	} 
	public function ReservationDelete() {
			
		if(check_logged()) 
	    {
		$TotalSegments = $this->uri->total_segments();
		$memberId = $this->uri->segment($TotalSegments);
		$this->Drivers_model->DeleteReservation($memberId);
		
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success1','Reservation Deleted Successfully'));
		redirect('administrator/management/Reservation');
		}
		else{
	redirect('administrator/admin/login');
}
	}
	public function ReservationAdd() {
		
		if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	//$RiderId = $this->uri->segment($TotalSegments);
	
	if(isset($_POST['addbook'])) {
		$data = array(
							'id'         => NULL,
							'rider_id'       => $this->input->post('riderid'),
							'pickup'          => $this->input->post('pickup'),
							'ridername'          => $this->input->post('Fname'),
							'pickupdate' => $this->input->post('pickupdate'),
							'pickuptime' => $this->input->post('pickuptime'),
						'mobile_no' => $this->input->post('mobile'),
						'carcategory'=>$this->input->post('category'),
						'carname'=>$this->input->post('category1'),						
						 	'dropup'          => $this->input->post('dropup')
							);
		$result1 = $this->mongo_db->db->futurebook->insert($data);
		$result1 = $this->mongo_db->db->futurebookcopy->insert($data);
	//$this->Drivers_model->insertReservationDetailsBasedOnId($RiderId);
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success1','Reservation Added Successfully'));
	redirect('administrator/management/Reservation');
	}
	if(isset($_POST['cancelbook'])){
		redirect('administrator/management/Reservation');
	}
		
	//$data['memberDetails'] =  $this->Drivers_model->GetReservationDetailsBasedOnId($RiderId);
		
	$data['message_element'] = "administrator/management/view_add_reservation";
	$this->load->view('administrator/admin_template', $data);
		}
			else{
	redirect('administrator/admin/login');
}
}
	public function ReservationEdit() {
		
		if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	$RiderId = $this->uri->segment($TotalSegments);
	
	if(isset($_POST['editdriver'])) {
	$this->Drivers_model->UpdateReservationDetailsBasedOnId($RiderId);
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success1','Reservation Updated Successfully'));
	redirect('administrator/management/Reservation');
	}
	if(isset($_POST['edituser'])){
		redirect('administrator/management/Reservation');
	}
		
	$data['memberDetails'] =  $this->Drivers_model->GetReservationDetailsBasedOnId($RiderId);
		
	$data['message_element'] = "administrator/management/view_edit_reservation";
	$this->load->view('administrator/admin_template', $data);
		}
			else{
	redirect('administrator/admin/login');
}
	}
	
	public function memberUnban() {
		if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	$memberId = $this->uri->segment($TotalSegments);
	
	$this->mongo_db->db->drivers->update(array('_id' =>  new MongoId($memberId) ),array('$set'=>array("status"=> 'on')));
		
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success1','member Unbanned Successfully'));
	redirect('administrator/management/member');
		}
			else{
	redirect('administrator/admin/login');
}
	}
	
	public function memberChangePassword() {
			if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	$memberId = $this->uri->segment($TotalSegments);
	
	if(isset($_POST['changepassword'])) {
		
	extract($this->input->post());
	$data_update =	array("password"=> md5($new_password));
	$this->Rider_model->update_Tabdata("drivers",array("_id"=>new MongoId($memberId)), $data_update);
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Password Changed Successfully'));
	redirect('administrator/members/Driver');
	}
		
	$data['message_element'] = "administrator/management/view_change_password";
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
		extract($this->input->post());
	$data_update =	array("password"=> md5($new_password));
	$this->Rider_model->update_Tabdata("riders",array("_id"=>new MongoId($RiderId)), $data_update);
	
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Password Changed Successfully'));
	redirect('administrator/management/Rider');
	}
		
	$data['memberDetails'] =  $this->Rider_model->GetRiderDetailsBasedOnId($RiderId);
		
	$data['message_element'] = "administrator/management/view_riderchange_password";
	$this->load->view('administrator/admin_template', $data);
		}
		else{
	redirect('administrator/admin/login');
}
	}
	
	public function RequestDuration() {
		if(check_logged()) 
	    {
		
	if(isset($_POST['durationtime'])) {
	$this->Drivers_model->UpdateRequestDuration();
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Request Accepted Duration Updated Successfully'));
	redirect('administrator/management/RequestDuration');
	}
	
	$data['DurationTime'] =  $this->Drivers_model->GetDurationTime();
	
	$data['message_element'] = "administrator/RequestDuration";
	$this->load->view('administrator/admin_template', $data);
		}
	else{
	redirect('administrator/admin/login');
}	
	}
	
	public function memberRequestNotification() {
		if(check_logged()) 
	    {
		
	if(isset($_POST['driver_request_notification'])) {
	$this->Drivers_model->UpdatememberRequestNotification();
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success1','member Request Notification Updated Successfully'));
	redirect('administrator/management/memberRequestNotification');
	}
	
	$data['distance'] =  $this->Drivers_model->memberRequestNotification();
	
	$data['message_element'] = "administrator/memberRequestNotification";
	$this->load->view('administrator/admin_template', $data);
		}
	else{
	redirect('administrator/admin/login');
}	
	}


 function add_email()
{
	$this->session->unset_userdata('success');
	if(check_logged()) {
				  if($this->input->post('code') !="")
                    {
                  $mail=$this->Rider_model->addemail();
				  if($mail==1){
	
						$data['msg']=	//$this->session->set_userdata('success', $this->Rider_model->flash_message('success','Email Template Added successfully'));
						 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('success','Template added successfully'));
      redirect('administrator/management/manage_email');
						}
else{
	
				$data['msg']=	//$this->session->set_userdata('success', $this->Rider_model->flash_message('error','Email Code Already Taken'));
				 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('error','Email Code already Taken'));
      redirect('administrator/management/add_email');
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
					
					$data['message']= $this->Rider_model->find_data('email',array());
					$data['msg']='';
					$data['count'] = $this->Rider_model->find_data('email',array())->count();
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
					
					$data['message']=$this->Rider_model->getsms();
					$data['msg']='';
					$data['count'] = $this->Rider_model->getsms()->count();
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
                  $success= $this->Rider_model->updatsmsbyid();
				   	$data['message']=$this->Rider_model->getsms();
				   if($success!=0){
	
					 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('success2','Template updated successfully'));
				   }
				   else{
				   	 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('error',' '.'Error updating the template'));
				   }
      redirect('administrator/management/manage_sms');
	       $this->load->view('administrator/sms/manage_sms',$data);
                    }  
				  else{
				  	
				  	 $result=$this->Rider_model->getsmsbyid();
					  if($result['count']!=0){
					  $data['message']=$result['records'];	
					$data['msg']='';
           
		   	 $data['message_element'] = "administrator/sms/edit_sms";
	         $this->load->view('administrator/admin_template', $data);
					  }else{
					  	 redirect('administrator/management/manage_sms');
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
                  $success= $this->Rider_model->updatemailbyid();
				   	$data['message']=$this->Rider_model->getemail();
				   if($success!=0){
	
					 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('success2','Template updated successfully'));
				   }
				   else{
				   	 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('error',' '.'Error updating the template'));
				   }
      redirect('administrator/management/manage_email');
	       $this->load->view('administrator/email/manage_email',$data);
                    }  
				  else{
				  	
				  	 $result=$this->Rider_model->getemailbyid();
					  if($result['count']!=0){
					  $data['message']=$result['records'];	
					$data['msg']='';
           
		   	 $data['message_element'] = "administrator/email/edit_email";
	         $this->load->view('administrator/admin_template', $data);
					  }else{
					  	 redirect('administrator/management/manage_email');
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
                    	$this->session->set_flashdata('success', $this->Common_model->admin_flash_message('success2','Template deleted successfully'));
                   $this->Rider_model->deletemailbyid();
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
	redirect('administrator/management/RequestExpire');
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
            'text' => "Your document was verified successfully by Admin Fenix"
        );
		$response = $this->nexmo->send_message($from, $to, $message);  
		 if($this->nexmo->get_http_status() == 200)
        {
        	 $this->Drivers_model->Updateproofstatus($driver_id); 
		echo "Success"; exit;
		}
else{
	echo "Error"; exit;
}
	
}
public function reject(){
	extract ($this->input->post());
	$this->Drivers_model->rejectproofstatus($driver_id); 
}

public function fileupload()
{
	if($this->input->post('update'))
	{
		
	$new_name = "auth";
	$config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/firbase_auth'; //Set the upload path
	$config['allowed_types'] = '*';// Set file type
	$config['encrypt_name']	= FALSE; // Change image name
	$config['file_name'] = $new_name;
	$config['overwrite'] = TRUE;
	$file_element_name = "userfile";
	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	if(!$this->upload->do_upload($file_element_name)){
			
		$status = 'error';
		$msg = $this->upload->display_errors('','');
		$error = $this->upload->display_errors();
	
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',$error));
	redirect('administrator/management/fileupload');
	
	}else{
		
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','File uploaded successfully'));
	redirect('administrator/management/fileupload');
	
	}
	
		}else{
	$data['message_element'] = "administrator/fileupload";
	$this->load->view('administrator/admin_template', $data);		
	}
	
	
	
}


} // Class
?>