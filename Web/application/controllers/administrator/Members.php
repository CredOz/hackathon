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
	public $google_api_key;
	function Members()
	{
		parent::__construct();
		
		$this->load->model('drivers_model');
		$this->load->model('users_model');
	//	$this->load->model('fb_drivers_model');
	//	$this->load->model('fb_users_model');
		$this->load->model('Common_model');
		
		$this->load->library('Pagination');
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('url');
 		$this->load->helper('file');
		$this->load->library('Table');
		$this->load->library('mongo_db');
		$this->load->helper('download');
		$this->path = realpath(APPPATH . '../images');
		
		
	$apisettings = $this->mongo_db->db->googleconnect->find(array( "id"=>'1'));
	if($apisettings->hasNext())
	{	
	foreach($apisettings as $google){
       
       $this->google_api_key= $google['google_api_key'];
	} 
	}
		
	}
		function index()
	{
		// Export for Rider		
		if(isset($_POST['export']))
		{
			 // txt file
		if($this->input->post('export') !='')
				{
				$this->users_model->export_txt();
		  		}
		}
		if(isset($_POST['export_csv']))
		{
			// csv file	
				if($this->input->post('export_csv') !='')
				{
					$this->users_model->export_csv();
				
				}
		}// Export for Rider	
		// Export for Driver		
		if(isset($_POST['export1']))
		{
			 // txt file
		if($this->input->post('export1') !='')
				{
				$this->users_model->export_driver_txt();
		  		}
		}
		if(isset($_POST['export_csv1']))
		{
			// csv file	
				if($this->input->post('export_csv1') !='')
				{
					$this->users_model->export_driver_csv();
				
				}
		}// Export for Driver	
		// Export for Transcation		
		if(isset($_POST['export2']))
		{
			 // txt file
		if($this->input->post('export2') !='')
				{
				$this->users_model->export_trans_txt();
		  		}
		}
		if(isset($_POST['export_csv2']))
		{
			// csv file	
				if($this->input->post('export_csv2') !='')
				{
					$this->users_model->export_trans_csv();
				
				}
		}// Export for Transcation
		// Export for Ride later		
		if(isset($_POST['export3']))
		{
			 // txt file
		if($this->input->post('export3') !='')
				{
				$this->users_model->export_ride_txt();
		  		}
		}
		if(isset($_POST['export_csv3']))
		{
			// csv file	
				if($this->input->post('export_csv3') !='')
				{
					$this->users_model->export_ride_csv();
				
				}
		}// Export for Ride later
		
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

function view_req_driver($driverid)
	{
		$data['drivername']= $this->mongo_db->db->drivers->findone(array("_id"=>new Mongoid($driverid)));
		//$data['drivername'] = $driver['firstname'];
		
		
		$data['ridername_select']=$this->mongo_db->db->users->find()->sort(array('firstname' => 1));
	
	//carcategory
	$data['category_select']=$this->mongo_db->db->category->find();
		$data['google_apikey']=$this -> google_api_key;
		
		$data['message_element'] = "administrator/staff/view_req_driver";
		
	    $this->load->view('administrator/admin_template',$data);
		
		//$this->load->view('administrator/staff/view_req_driver');
	}
	function view_req_driver2($driverid)
	{
        // $data= $this->mongo_db->db->drivers->find();
		//$data['drivername'] = $driver['firstname'];
		$id=$this->uri->segment(4,0);
		$data['ridername_select']=$this->mongo_db->db->users->find(array("_id"=>new Mongoid($id)));
		$data['drivername']= $this->mongo_db->db->drivers->find(array("_id"=>new Mongoid($driverid)));
		
	
	//carcategory
		$data['carcategory']= $this->mongo_db->db->drivers->findone(array("_id"=>new Mongoid($driverid)));
		//$data['category_select']=$this->mongo_db->db->category->find();
		$data['google_apikey']=$this -> google_api_key;
		
		$data['message_element'] = "administrator/staff/view_req_driver2";
		
	    $this->load->view('administrator/rider_view',$data);
		
		//$this->load->view('administrator/staff/view_req_driver');
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
		$data1['google_apikey']=$this -> google_api_key;
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
	$data1['google_apikey']=$this -> google_api_key;
	$data1['message_element'] = "administrator/requestdriver/driver_request";
	$this->load->view('administrator/admin_template', $data1);
	
	}
else{
    redirect('administrator/admin/login');
}
	}

function driver_request2()
{
	
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
		$data1['history']=$this->mongo_db->db->requests->find()->sort(array('_id' => -1))->limit(10);
		$data1['availdriver']=$drivers=get_avail_drivers();
	//autocomplete ridername
	$id=$this->uri->segment(4,0);
	$data1['ridername_select']=$this->mongo_db->db->users->find(array("_id"=>new MongoId($id)));
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
	$data1['google_apikey']=$this -> google_api_key;
	$data1['message_element'] = "administrator/requestdriver2/driverrequest2";
	$this->load->view('administrator/rider_view', $data1);
	
	}
function getordercnt(){
	
	  set_time_limit(0);
	   while (true) {
	   	
		  $last_ajax_call = isset($_GET['total_count']) ? (int)$_GET['total_count']: null;

    $last_count = $this->mongo_db->db->requests->find(array('$or' => array(array('status' => 'request'), array('status' => 'process'))))->sort(array('_id' => -1))->count();

    if ( $last_count != $last_ajax_call) {
    	
	$returnValue['total_count'] = $last_count;
			
	echo json_encode($returnValue);
	
	break;	
	}
	else{
	
        sleep( 1 );
        continue;
	}
}

}


function getpreordercnt(){
	
	  set_time_limit(0);
	   while (true) {
	   	
		  $last_ajax_call = isset($_GET['total_count']) ? (int)$_GET['total_count']: null;

    $last_count = $this->mongo_db->db->requests->find(array('$or' => array(array('status' => 'inprogress'), array('status' => 'process'))))->sort(array('_id' => -1))->count();

    if ( $last_count != $last_ajax_call) {
    	
	$returnValue['total_count'] = $last_count;
			
	echo json_encode($returnValue);
	
	break;	
	}
	else{
	
        sleep( 1 );
        continue;
	}
}

}



function gethistorycnt(){
	
	  set_time_limit(0);
	   while (true) {
	   	
		  $last_ajax_call = isset($_GET['total_count']) ? (int)$_GET['total_count']: null;

    $last_count = $this->mongo_db->db->requests->find(array('user_id'=> $id))->sort(array('_id' => -1))->count();

    if ( $last_count != $last_ajax_call) {
    	
	$returnValue['total_count'] = $last_count;
			
	echo json_encode($returnValue);
	
	break;	
	}
	else{
	
        sleep( 1 );
        continue;
	}
}

}																																																																																																																																																																												




function dumdriver_request()
{
	$data1['message_element'] = "administrator/requestdriver/driver_requestpage";
	$this->load->view('administrator/admin_template', $data1);
}
function dumdriver_request2()
{
	
	$data1['message_element'] = "administrator/requestdriver2/driver_requestpage2";
	$this->load->view('administrator/rider_view', $data1);
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
	$result=$this->drivers_model->check_data($email);
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

	//$result=$this->drivers_model->check_data($email);
	//$result1=$this->drivers_model->check_data1($mobile_no);
	$data1['licence']=$imagepath;
	$data1['voterid']=$imagepath2;
	$avaliable="on";
	$data1[availability]=$avaliable;
	// $status="on";
	// $data1['status']=$status;
		$result = $this->mongo_db->db->drivers->insert($data1);
	//$this->drivers_model->insertReservationDetailsBasedOnId($RiderId);
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Driver Added Successfully'));
	redirect('administrator/members/Driver');
	}
else {
$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Email Address already haven'));
		redirect('administrator/members/add_drivers');
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
function facebooklogin(){
	if(check_logged()) 
	{
	if($this->input->post('update')) {
		$this->form_validation->set_error_delimiters('<p>', '</p>');
	    $this->form_validation->set_rules('fb_api_key','Facebook API key','required|trim|xss_clean');
        $this->form_validation->set_rules('fb_secret_key','Facebook secret key','required|trim|xss_clean');
		
	if($this->form_validation->run()){
	$this->mongo_db->db->facebookkey->update(array("id"=> '1'),array('$set'=> array("fb_api_key"=>$this->input->post('fb_api_key'),"fb_secret_key"=>$this->input->post('fb_secret_key'))));
    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Facebook API Updated Successfully'));
    redirect('administrator/members/facebooklogin');				
	}
	else
	{
 $this->mongo_db->db->facebookkey->update(array("id"=> '1'),array('$set'=> array("fb_api_key"=>$this->input->post('fb_api_key'),"fb_secret_key"=>$this->input->post('fb_secret_key'))));	
   $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Facebook Connect Field name required'));
	redirect('administrator/members/facebooklogin');
			
	}
	}
	$apisettings = $this->mongo_db->db->facebookkey->find(array( "id"=>'1'));
    $data['apisetting'] = $this->mongo_db->db->facebookkey->find(array( "id"=>'1'));
    
    foreach($apisettings as $apisetting){
        
    $data['fb_api'] = $apisetting['fb_api_key'];
    $data['fb_secret'] = $apisetting['fb_secret_key'];
   // $data['pe_key'] = $apisetting['apikey'];
    //$data['email'] = $apisetting['email'];
    //$data['paypal_url'] = $apisetting['paymenturl'];
    $data['id'] = $apisetting['_id'];
}				
   $data['message_element'] = "administrator/fb_key";
	$this->load->view('administrator/admin_template', $data);
    }
else{
    redirect('administrator/admin/login');
}
}
//

/*function facebooklogin()
{
	if($this->input->post('update')) {
	$Id = 1;
	$this->mongo_db->db->facebookkey->update(array("_id"=>new MongoId($this->input->post('Id'))),array('$set'=> array("fb_api_key"=>$this->input->post('fb_api'),"fb_secret_key"=>$this->input->post('fb_secret'))));
	
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Facebook Detail Updated Successfully'));
	redirect('administrator/members/facebooklogin');
	}
	$apisettings = $this->mongo_db->db->facebookkey->find();
	foreach($apisettings as $apisetting){
	$fb_api = $apisetting['fb_api_key'];
	$fb_secret = $apisetting['fb_secret_key'];
	$Id = $apisetting['_id'];
	
	}
	
	$data['message_element'] = "administrator/fb_key";
	$this->load->view('administrator/admin_template', $data);
}
*/
function googleconnect()
{
	if(check_logged()) 
	{
	if($this->input->post('update')) {
		$this->form_validation->set_error_delimiters('<p>', '</p>');
	    $this->form_validation->set_rules('google_api_key','Google api key','required|trim|xss_clean');
		$this->form_validation->set_rules('google_project_id','Google project id','required|trim|xss_clean');
	
	if($this->form_validation->run()){
		
	$this->mongo_db->db->googleconnect->update(array("id"=> '1'),array('$set'=> array("google_api_key"=>$this->input->post("google_api_key"),"google_project_id"=>$this->input->post("google_project_id"))));
	
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Google Connect Updated Successfully'));
	redirect('administrator/members/googleconnect');
	}
	else
	{
	$this->mongo_db->db->googleconnect->update(array("id"=> '1'),array('$set'=> array("google_api_key"=>$this->input->post("google_api_key"),"google_project_id"=>$this->input->post("google_project_id"))));		
    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Google Connect Field name required'));
	redirect('administrator/members/googleconnect');		
	}
	}
	$apisettings = $this->mongo_db->db->googleconnect->find();
	
	foreach($apisettings as $apisetting){
	$data['apikey']= $apisetting['google_api_key'];
	$data['p_id'] = $apisetting['google_project_id'];
	$data['id'] = $apisetting['_id'];
	
	}
	
	$data['message_element'] = "administrator/googleconnect";
	$this->load->view('administrator/admin_template', $data);
}
}

function googleconnectall(){
	
if(check_logged()) 
	{
	if($this->input->post('update')) {
		$this->form_validation->set_error_delimiters('<p>', '</p>');
	    
	    $this->form_validation->set_rules('google_api_key1','Google api key','required|trim|xss_clean');
		$this->form_validation->set_rules('google_project_id1','Google project id','required|trim|xss_clean');
		
		$this->form_validation->set_rules('google_api_key2','Google api key','required|trim|xss_clean');
		$this->form_validation->set_rules('google_project_id2','Google project id','required|trim|xss_clean');
		
		$this->form_validation->set_rules('google_api_key3','Google api key','required|trim|xss_clean');
		$this->form_validation->set_rules('google_project_id3','Google project id','required|trim|xss_clean');
		
		$this->form_validation->set_rules('google_api_key4','Google api key','required|trim|xss_clean');
		$this->form_validation->set_rules('google_project_id4','Google project id','required|trim|xss_clean');
	
	if($this->form_validation->run()){
		
/*
	$this->mongo_db->db->googleconnect->update(array("id"=> '1' ),array('$set'=> array("google_api_key1"=>$this->input->post('google_api_key1'),"google_project_id1"=>$this->input->post('google_project_id1'),
	
	"google_api_key2"=>$this->input->post('google_api_key2'),"google_project_id2"=>$this->input->post('google_project_id2'),
	
	"google_api_key3"=>$this->input->post('google_api_key3'),"google_project_id3"=>$this->input->post('google_project_id3'),
	
	"google_api_key4"=>$this->input->post('google_api_key4'),"google_project_id4"=>$this->input->post('google_project_id4')
	)));
*/

    $this->mongo_db->db->googleconnect_pool->update(array("id"=> '1' ),array('$set'=> array("google_api_key1"=>$this->input->post('google_api_key1'),"google_project_id1"=>$this->input->post('google_project_id1'))));
	$this->mongo_db->db->googleconnect_pool->update(array("id"=> '2' ),array('$set'=> array("google_api_key2"=>$this->input->post('google_api_key2'),"google_project_id2"=>$this->input->post('google_project_id2'))));
	$this->mongo_db->db->googleconnect_pool->update(array("id"=> '3' ),array('$set'=> array("google_api_key3"=>$this->input->post('google_api_key3'),"google_project_id3"=>$this->input->post('google_project_id3'))));
	$this->mongo_db->db->googleconnect_pool->update(array("id"=> '4' ),array('$set'=> array("google_api_key4"=>$this->input->post('google_api_key4'),"google_project_id4"=>$this->input->post('google_project_id4'))));
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Google Connect Updated Successfully'));
	redirect('administrator/members/googleconnectall');
	}
	else
	{
	//$this->mongo_db->db->googleconnect->update(array("id"=> '2' ),array('$set'=> array("google_api_key"=>$this->input->post('google_api_key'),"google_project_id"=>$this->input->post('google_project_id'))));
	
	$this->mongo_db->db->googleconnect_pool->update(array("id"=> '1' ),array('$set'=> array("google_api_key1"=>$this->input->post('google_api_key1'),"google_project_id1"=>$this->input->post('google_project_id1'))));
	$this->mongo_db->db->googleconnect_pool->update(array("id"=> '2' ),array('$set'=> array("google_api_key2"=>$this->input->post('google_api_key2'),"google_project_id2"=>$this->input->post('google_project_id2'))));
	$this->mongo_db->db->googleconnect_pool->update(array("id"=> '3' ),array('$set'=> array("google_api_key3"=>$this->input->post('google_api_key3'),"google_project_id3"=>$this->input->post('google_project_id3'))));
	$this->mongo_db->db->googleconnect_pool->update(array("id"=> '4' ),array('$set'=> array("google_api_key4"=>$this->input->post('google_api_key4'),"google_project_id4"=>$this->input->post('google_project_id4'))));
	
	
/*
	
	"google_api_key2"=>$this->input->post('google_api_key2'),"google_project_id2"=>$this->input->post('google_project_id2'),
	
	"google_api_key3"=>$this->input->post('google_api_key3'),"google_project_id3"=>$this->input->post('google_project_id3'),
	
	"google_api_key4"=>$this->input->post('google_api_key4'),"google_project_id4"=>$this->input->post('google_project_id4')
	)));
*/
			
    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Google Connect Field name required'));
	redirect('administrator/members/googleconnectall');		
	}
	}
	$apisettings = $this->mongo_db->db->googleconnect_pool->find();
	
	$i=1;
	foreach($apisettings as $apisetting){
		
	$data['apikey'.$i]= $apisetting['google_api_key'.$i];
		$data['p_id'.$i] = $apisetting['google_project_id'.$i];
	/*
	
		$data['apikey2']= $apisetting['google_api_key2'];
		$data['p_id2'] = $apisetting['google_project_id2'];
		
		$data['apikey3']= $apisetting['google_api_key3'];
		$data['p_id3'] = $apisetting['google_project_id3'];
		
		$data['apikey4']= $apisetting['google_api_key4'];
		$data['p_id4'] = $apisetting['google_project_id4'];
		*/
	
	//$data['id'] = $apisetting['_id'];
	
	$i++;
	}
	
	$data['message_element'] = "administrator/googleconnectall";
	$this->load->view('administrator/admin_template', $data);
}	
	
	
	
}


	function reloadview()
{
	   set_time_limit(0);
	   while (true) {
	   	
		  $last_ajax_call = isset($_GET['count_driver']) ? (int)$_GET['count_driver']: null;

    $last_count_driver = $this->mongo_db->db->drivers->find(array("status"=>'on'))->count();

    if ( $last_count_driver != $last_ajax_call) {
    
	$result=$this->mongo_db->db->drivers->find(array("status"=>'on'));
	
	if($result->hasNext())
	{
		$j_arr[]=1;
		$count_driver = $result->count() ;
			foreach($result as $document)
				 {
					$data['firstname'] = $document['firstname'];
					$data['lastname'] = $document['lastname'];
					$data['prof_pic'] = $document['prof_pic'];
					$data['email'] = $document['email'];
					$data['lat'] = @$document['lat'];
					$data['long'] = @$document['long'];
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
		$count_driver = 0 ;
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
$returnValue['count_driver'] = $count_driver;

echo json_encode($returnValue);


	
	break;	
	}
	else{
	
        sleep( 1 );
        continue;
	}
	
	
	}
 
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
		$data1['google_apikey']=$this -> google_api_key;
	    $data['message_element'] = "administrator/staff/view_callreq_driver";
	    $this->load->view('administrator/staff/view_callreq_driver',$data);
		
		//$this->load->view('administrator/staff/view_req_driver');
	}

function reloadview1()
{
	   
	    set_time_limit(0);
	   while (true) {
	   	
		  $last_ajax_call = isset($_GET['count_driver']) ? (int)$_GET['count_driver']: null;

    $last_count_driver = $this->mongo_db->db->drivers->find(array("status"=>'off',"tripstatus"=>'busy'))->count();

    if ( $last_count_driver != $last_ajax_call) {
    	
    $result=$this->mongo_db->db->drivers->find(array("status"=>'off',"tripstatus"=>'busy'));
	
	
	if($result->hasNext())
	{
		$count_driver = $result->count() ;
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
		
		    $arr_temp = array();
				    $arr_temp1 = array();
					$arr_email[] = array();
					$arr_drivername[] = array();
					$arr_mobile[]= array();
				    $arr_id[] =  array(); $count_driver = 0;
	}
	
$returnValue['lat'] = $arr_temp;
$returnValue['long1'] = $arr_temp1;
$returnValue['email'] = $arr_email;
$returnValue['drivername'] = $arr_drivername;
$returnValue['mobile'] = $arr_mobile;
$returnValue['id'] = $arr_id;
$returnValue['count_driver'] = $count_driver;

echo json_encode($returnValue);
	
	break;	
	}
	else{
	
        sleep( 1 );
        continue;
	}
	
}
}	


function reloadview2()
{
	   
	    set_time_limit(0);
	   while (true) {
	   	
		  $last_ajax_call = isset($_GET['count_driver']) ? (int)$_GET['count_driver']: null;

    $last_count_driver = $this->mongo_db->db->drivers->find(array("status"=>'off',"tripstatus"=>'start'))->count();

    if ( $last_count_driver != $last_ajax_call) {
    	
    $result=$this->mongo_db->db->drivers->find(array("status"=>'off',"tripstatus"=>'start'));
	
	
	if($result->hasNext())
	{
		$count_driver = $result->count() ;
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
		
		    $arr_temp = array();
				    $arr_temp1 = array();
					$arr_email[] = array();
					$arr_drivername[] = array();
					$arr_mobile[]= array();
				    $arr_id[] =  array(); $count_driver = 0;
	}
	
$returnValue['lat'] = $arr_temp;
$returnValue['long1'] = $arr_temp1;
$returnValue['email'] = $arr_email;
$returnValue['drivername'] = $arr_drivername;
$returnValue['mobile'] = $arr_mobile;
$returnValue['id'] = $arr_id;
$returnValue['count_driver'] = $count_driver;

echo json_encode($returnValue);
	
	break;	
	}
	else{
	
        sleep( 1 );
        continue;
	}
	
}
}	


function reloadview3()
{
	   
	    set_time_limit(0);
	   while (true) {
	   	
		  $last_ajax_call = isset($_GET['count_driver']) ? (int)$_GET['count_driver']: null;

    $last_count_driver = $this->mongo_db->db->drivers->find(array("status"=>'off',"tripstatus"=>'end'))->count();

    if ( $last_count_driver != $last_ajax_call) {
    	
    $result=$this->mongo_db->db->drivers->find(array("status"=>'off',"tripstatus"=>'end'));
	
	
	if($result->hasNext())
	{
		$count_driver = $result->count() ;
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
		
		    $arr_temp = array();
				    $arr_temp1 = array();
					$arr_email[] = array();
					$arr_drivername[] = array();
					$arr_mobile[]= array();
				    $arr_id[] =  array(); $count_driver = 0;
	}
	
$returnValue['lat'] = $arr_temp;
$returnValue['long1'] = $arr_temp1;
$returnValue['email'] = $arr_email;
$returnValue['drivername'] = $arr_drivername;
$returnValue['mobile'] = $arr_mobile;
$returnValue['id'] = $arr_id;
$returnValue['count_driver'] = $count_driver;

echo json_encode($returnValue);
	
	break;	
	}
	else{
	
        sleep( 1 );
        continue;
	}
	
}
}	

	
	
	
	public function Driver() {
	// $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Rider Updated Successfully'));
	 if(check_logged()) 
	    {
	    if(($this->input->get()))
			{
				extract($this->input->get());
				if(isset($status))
				{
					if($status == "today")
				{
					$data['DriverUsers'] = $this->drivers_model->GetAllDriverUsers_today();
				}
				else
				{
					$data['DriverUsers'] = $this->drivers_model->GetAllDriverUsers_all();
				}				
			}
			else{
					$data['DriverUsers'] = $this->drivers_model->GetAllDriverUsers_all();					
				}

		}else{			
		
		$data['DriverUsers'] = $this->drivers_model->GetAllDriverUsers_all();
			}
			
			$data['message_element'] = "administrator/management/view_driver";
			$this->load->view('administrator/admin_template', $data);
		}
		else{
			redirect('administrator/admin/login');
		}
		
	} 
	
public function Rider() {
	
if(check_logged()) 
{	
	if(($this->input->get()))
		{
			extract($this->input->get());
			if(isset($status)){
				if($status == "today")
				{
					$data['RiderUsers'] = $this->drivers_model->GetAllRiderUsers_today();					
				}else{
					$data['RiderUsers'] = $this->drivers_model->GetAllRiderUsers_all();	
				}				
		}else{			
			$data['RiderUsers'] = $this->drivers_model->GetAllRiderUsers_all();				
		}
		}else{			
			$data['RiderUsers'] = $this->drivers_model->GetAllRiderUsers_all();				
		}
	$data['message_element'] = "administrator/management/view_rider";
	$this->load->view('administrator/admin_template', $data);
		}
		else{
	redirect('administrator/admin/login');
}
	} 
function display(
){
	$database = 'arcane';
$collection = 'users';

$m = new MongoClient();
$col = $m->selectDB($database)->$collection;

$json = json_encode(iterator_to_array($col->find()));
echo $json;
}


/*
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
//$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Rider Updated Successfully'));		
		// Load view
	$data['message_element'] = "administrator/members/view_rider";
	$this->load->view('administrator/admin_template', $data);
		}
		else{
	redirect('administrator/admin/login');
}
	} */


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
public function webDriverDelete() {
			
		if(check_logged()) 
	    {
		$TotalSegments = $this->uri->total_segments();
		$DriverId = $this->uri->segment($TotalSegments);
		$this->drivers_model->DeleteDriver($DriverId);
		
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Driver Deleted Successfully'));
		redirect('administrator/members/dumdriver_request');
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
		
	$data['message_element'] = "administrator/management/view_edit_users";
	$this->load->view('administrator/admin_template', $data);
		}
			else{
	redirect('administrator/admin/login');
}
	}
public function Riderchange() {
		
		if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	$RiderId = $this->uri->segment($TotalSegments);
	
	if(isset($_POST['editrider'])) {
	$this->drivers_model->UpdateRiderDetailsBasedOnId($RiderId);
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Rider Updated Successfully'));
	redirect('templates/home/dashboard');
	}
	if(isset($_POST['edituser'])){
		redirect('templates/home/dashboard');
	}
		
	$data['RiderDetails'] =  $this->drivers_model->GetRiderDetailsBasedOnId($RiderId);
		
	$data['message_element'] = "templates/view";
	$this->load->view('templates/admin_template', $data);
		}
			else{
	redirect('administrator/admin/login');
}
	}
	public function Driverstatus() {
		if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	$DriverId = $this->uri->segment($TotalSegments);
	//print_r($DriverId);exit;
	$this->mongo_db->db->drivers->update(array('_id' =>  new MongoId($DriverId) ),array('$set'=>array("status"=> 'off')));
	redirect('administrator/members/dumdriver_request');
		}
		else{
	redirect('administrator/admin/login');
}
	}
		public function Driverstatuson() {
		if(check_logged()) 
	    {
	$TotalSegments = $this->uri->total_segments();
	$DriverId = $this->uri->segment($TotalSegments);
	//print_r($DriverId);exit;
	$this->mongo_db->db->drivers->update(array('_id' =>  new MongoId($DriverId) ),array('$set'=>array("status"=> 'on')));
	redirect('administrator/members/dumdriver_request');
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
	$data['category'] = $this->mongo_db->db->category->find();
		
	$data['message_element'] = "administrator/management/view_edit_driver";
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
		
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Driver goes offline!'));
	redirect('administrator/members/Driver');
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
			$this->mongo_db->db->requests->update(array('_id' =>  new MongoId($id) ),array('$set'=>array("pickup"=> $pickup,"dropup"=> $drop,"category"=>$category)));
			if($lat!="" and $long!="")
			{
				$this->mongo_db->db->users->update(array('_id' =>  new MongoId($userid) ),array('$set'=>array("lat"=> $lat,"long"=> $long)));				
			}
			$data1['google_apikey']=$this -> google_api_key;
			redirect('administrator/members/driver_request');
		}
		else 
		{
			extract($this->input->get());
			$result=$this->mongo_db->db->requests->findOne(array("_id"=>new MongoId($id)));
			$data['id']=$id;
			$data['userid']=$userid=$result['user_id'];
			$data['pickup']=$result['pickup'];
			$data['drop']=$result['dropup'];
			$data['category']=$result['category'];
			$result_user=$this->mongo_db->db->users->findOne(array("_id"=>new MongoId($userid)));
			$data['firstname']=$result_user['firstname'];
			$data['mobile']=$result_user['mobile_no'];
			$data['lat']=$result_user['lat'];
			$data['long']=$result_user['long'];
			$data['message_element'] = "administrator/requestdriver/editorders";
			$this->load->view('administrator/admin_template', $data);	
		}

		

	}
	public function edit_orders2()
	{
		if($this->input->post())
		{
			extract($this->input->post());
			$this->mongo_db->db->requests->update(array('_id' =>  new MongoId($id) ),array('$set'=>array("pickup"=> $pickup,"dropup"=> $drop)));
			if($lat!="" and $long!="")
			{
				$this->mongo_db->db->users->update(array('_id' =>  new MongoId($userid) ),array('$set'=>array("lat"=> $lat,"long"=> $long)));				
			}
			$data1['google_apikey']=$this -> google_api_key;
					$user = $this->mongo_db->db->users->find(array("_id"=>new MongoId($id)));
			redirect('administrator/members/driver_request2/'.$userid);
		}
		else 
		{
			//$id=$this->uri->segment(4,0);
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
			$data['message_element'] = "administrator/requestdriver2/editorders2";
			$this->load->view('administrator/requestdriver2/edit_order_view', $data);	
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
			redirect('administrator/members/Reservation');
		}
	$data['RiderUsers']=$this->drivers_model->searchres($keyword);	
    $p_config['base_url'] 			=  base_url('administrator/members/Searchres');
		$p_config['uri_segment'] = 4;
		$p_config['num_links'] 		= 5;
		$p_config['total_rows'] 	= $this->drivers_model->get_reservation();
		$p_config['per_page'] 			= $row_count;
				
		// Init pagination
	$data['pagination'] =	$this->pagination->initialize($p_config);		
		
		// Create pagination links
	//	$data['pagination']     = $this->pagination->create_links();

$data['message_element'] = "administrator/members/view_reservation";
	$this->load->view('administrator/admin_template', $data);
	//redirect('administrator/members/Rider');
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
		$data['RiderUsers'] = $this->drivers_model->GetAllReservationUsers($offset, $row_count);
		
		// Pagination config
		$p_config['base_url'] 			=  base_url('administrator/members/Reservation');
		$p_config['uri_segment'] = 4;
		$p_config['num_links'] 		= 5;
		$p_config['total_rows'] 	= $this->drivers_model->get_reservation();
		$p_config['per_page'] 			= $row_count;
				
		// Init pagination
		$this->pagination->initialize($p_config);		
		
		// Create pagination links
		$data['pagination']     = $this->pagination->create_links2();
		
		// Load view
	$data['message_element'] = "administrator/members/view_reservation";
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
		$DriverId = $this->uri->segment($TotalSegments);
		$this->drivers_model->DeleteReservation($DriverId);
		
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Reservation Deleted Successfully'));
		redirect('administrator/members/Reservation');
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
	//$this->drivers_model->insertReservationDetailsBasedOnId($RiderId);
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Reservation Added Successfully'));
	redirect('administrator/members/Reservation');
	}
	if(isset($_POST['cancelbook'])){
		redirect('administrator/members/Reservation');
	}
		
	//$data['DriverDetails'] =  $this->drivers_model->GetReservationDetailsBasedOnId($RiderId);
		
	$data['message_element'] = "administrator/members/view_add_reservation";
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
	$this->drivers_model->UpdateReservationDetailsBasedOnId($RiderId);
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Reservation Updated Successfully'));
	redirect('administrator/members/Reservation');
	}
	if(isset($_POST['edituser'])){
		redirect('administrator/members/Reservation');
	}
		
	$data['DriverDetails'] =  $this->drivers_model->GetReservationDetailsBasedOnId($RiderId);
		
	$data['message_element'] = "administrator/members/view_edit_reservation";
	$this->load->view('administrator/admin_template', $data);
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
		
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Driver goes online!'));
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
	$this->users_model->RiderChangePasswordBasedOnId($RiderId);
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Password Changed Successfully'));
	redirect('administrator/members/Rider');
	}
		
	$data['DriverDetails'] =  $this->users_model->GetRiderDetailsBasedOnId($RiderId);
		
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


 function add_email()
{
	$this->session->unset_userdata('success');
	if(check_logged()) {
				  if($this->input->post('code') !="")
                    {
                  $mail=$this->users_model->addemail();
				  if($mail==1){
	
						$data['msg']=	//$this->session->set_userdata('success', $this->users_model->flash_message('success','Email Template Added successfully'));
						 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Template added successfully'));
      redirect('administrator/members/manage_email');
						}
else{
	
				$data['msg']=	//$this->session->set_userdata('success', $this->users_model->flash_message('error','Email Code Already Taken'));
				 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Email Code already Taken'));
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
	
					 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Template updated successfully'));
				   }
				   else{
				   	 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Error in updating the template'));
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
	
					 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Template updated successfully'));
				   }
				   else{
				   	 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Error in updating the template'));
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
function delete_email($id)
{
	if(check_logged()) {
	
	$this->users_model->deletemailbyid($id);
   $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Template deleted successfully'));
	redirect('administrator/members/manage_email');

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
	
	
	
	extract ($this->input->post());
	
	  /*$setting_details = $this->mongo_db->db->settings->find();
	foreach($setting_details as $document){
		$nexmo_mobile_no= $document['nexmo_mobile_no'];
	}*/
	
	require_once APPPATH.'libraries/firebase-php/firebase_config.php';
	
		$firebase->update(array(
						'proof_status' => 'Accepted',
						),'drivers_data/'.$driver_id); // Update driver req status in fb
						
	$this->drivers_model->Updateproofstatus($driver_id); 
		/*$from =trim($nexmo_mobile_no);
        $to = $driver_mobile_no;
		$message = array(
            'text' => "Your document was verified successfully by Admin"
        );*/
		//$response = $this->nexmo->send_message($from, $to, $message);
		 
		echo "Success"; exit;  
	/*
		 if($this->nexmo->get_http_status() == 200)
			{
				 $this->drivers_model->Updateproofstatus($driver_id); 
			echo "Success"; exit;
			}
	else{
		echo "Error"; exit;
	}*/
	
	
}

public function reject(){
	extract ($this->input->post());
	$this->drivers_model->rejectproofstatus($driver_id); 
}
public function twilio()
{
	 $data['count'] = $this->users_model->gettwilio();
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
				   	 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('error','Error updating Twilio setting'));
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
				   	 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('error','Error updating Twilio setting'));
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
				   	 $this->session->set_flashdata('success', $this->Common_model->admin_flash_message('error','Error updating the template'));
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

function orderpend()
{
	extract($this->input->post());
	
	if ($currentId == 'Pending')	
	{ 
			echo 'pending';exit;
	}
	else if($currentId == 'Orders')
		{
			echo 'order';exit;
		}
	else
		{
			echo 'history';exit;
		}
}
//twilio connect

function twilioconnect(){
	if(check_logged()) 
	{
	if($this->input->post('update')) {
		$this->form_validation->set_error_delimiters('<p>', '</p>');
	    $this->form_validation->set_rules('twilio_sid','Twilio sid','required|trim|xss_clean');
		$this->form_validation->set_rules('twilio_token','Twilio token','required|trim|xss_clean');
		$this->form_validation->set_rules('application_id','Application id','required|trim|xss_clean');
		
	if($this->form_validation->run()){
					
	$this->mongo_db->db->twilio->update(array("no"=>'1'),array('$set'=> array("twilio_sid"=>$this->input->post('twilio_sid'),"twilio_token"=>$this->input->post('twilio_token'),"twilio_number"=>$this->input->post('application_id'))));
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Twilio Connect Updated Successfully'));
	redirect('administrator/members/twilioconnect');
	}
	else
	{
	$this->mongo_db->db->twilio->update(array("no"=>'1'),array('$set'=> array("twilio_sid"=>$this->input->post('twilio_sid'),"twilio_token"=>$this->input->post('twilio_token'),"twilio_number"=>$this->input->post('application_id'))));		
    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Twilio Connect Field name required'));
	redirect('administrator/members/twilioconnect');		
	}
	}
	$apisettings = $this->mongo_db->db->twilio->find();
	
	foreach($apisettings as $apisetting){
	 $data['twilio_sid']= $apisetting['twilio_sid'];
	 $data['twilio_token'] = $apisetting['twilio_token'];
	 $data['application_id'] = $apisetting['twilio_number'];
	
	}
	
	$data['message_element'] = "administrator/twilioconnect";
	$this->load->view('administrator/admin_template', $data);
}				
}

//test twilio function
function tw(){

	// this line loads the library 
//require('library/twilio-php/Services/Twilio.php'); 
require(APPPATH."/libraries/Services/Twilio.php");
require(APPPATH."/libraries/Services/Twilio/Capability.php");
$accountSid=$account_sid = 'AC04ced8a6fd92ee0a1a55f22b78dcc73a'; 
$authToken=$auth_token = 'd460f042a85acb616c33ff42cb9ecdff'; 
$client = new Services_Twilio($account_sid, $auth_token); 
print_r($capability = new Services_Twilio_Capability($accountSid, $authToken));
$capability->allowClientIncoming('satheesh');
print_r($data1['token'] = $capability->generateToken());

 
/* $tw=$client->account->calls->create('+12568010480', '[To]', '[ApplicationSid]', array( 
	'Method' => 'GET',  
	'FallbackMethod' => 'GET',  
	'StatusCallbackMethod' => 'GET',    
	'Record' => 'false', 
));

var_dump($tw);
	*/
	
	
}
	public function releaseDriver() {
		
	if(check_logged()) 
		{
	$TotalSegments = $this->uri->total_segments();
	$DriverId = $this->uri->segment($TotalSegments);
	
	$this->mongo_db->db->drivers->update(array('_id' =>  new MongoId($DriverId) ),array('$set'=>array("status"=> 'on', "availability"=>"on", "trip_driver_status"=> "Home Page" )));
	$result = $this->mongo_db->db->users->find(array("driverid"=>$DriverId));
		  	if($result->hasNext())
			{
				foreach($result as $document)
			{
			 	$RiderID = $document['_id'];
			}
	$this->mongo_db->db->users->update(array('_id' =>  new MongoId($RiderID) ),array('$set'=>array("driverid"=> '',"trip_rider_status"=>'Home Page')));					
			}
				
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Driver released from trip!'));
	redirect('administrator/members/Driver');
		}
	else{
	redirect('administrator/admin/login');
		}
	}



} // Class
?>