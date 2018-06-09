<?php
header('Content-Type: application/json');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Driver extends REST_Controller {

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
	 	public function Driver()
	{
		parent::__Construct();
		$this->load->library('mongo_db');
		$this->load->model('Common_model');
		$this->load->model('Drivers_model');
		$this->load->model('Email_model');
		$this->load->helper('global');
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

  public function test_get()
  {

  		error_reporting(0);
		$userid = '118132839736301825135';
		  $result = $this->mongo_db->db->settings->find();
			$data = "";
		  foreach($result as $res) {
  		  	$apikey = "AIzaSyBhyYAujDqFR0aoqZULRyOD0OPobfD0-b8";

			$url = 	 "https://www.googleapis.com/plus/v1/people/".$userid."?fields=image&key=".$apikey ;
			$geocode     = file_get_contents($url);
			if($geocode)
			{

			$output      = json_decode($geocode);
			$data = $output->image->url;
			return $data ;

			}else{
			$data = "Expired";
			return $data ;
			}

		  }

  }
  	//users signup
	public function signUp_get()
  	{
  				if(checkisEmpty($this->get()))
			{

  			if($this->get('regid'))
	{
	$regid=$this->get('regid');
	}else{
	$regid=null;
	}
    if($this->get('devicetoken'))
    {
    	$devicetoken=$this->get('devicetoken');
    }else{
    	    	$devicetoken=null;
    }
  		$data['reg_id']=$regid;
		$data['devicetoken']=$devicetoken;
     	$data['first_name']=$this->get('first_name');
		$data['last_name']=$this->get('last_name');
		$data['email']=$this->get('email');
		$data['mobile']=$this->get('mobile');
		$data['country_code']=$this->get('country_code');
		$data['password']=md5($this->get('password'));
		$data['city']=$this->get('city');
		$data['category']=$this->get('category');

		$lat = $data['lat']= $this->get('lat');
		$long = $data['long']= $this->get('long');
		$str = '{ "coordinates": [ '.$long.', '.$lat.' ],"type": "Point" }';
		$str_update = json_decode($str);
		$data['location'] = $str_update ;

		$data['profile_pic']=base_url()."images/".$this->get('profile_pic');

		$data['license']=base_url()."images/documents/".$this->get('license');

		$data['insurance']=base_url()."images/documents/".$this->get('insurance');

		if(($this->get('fb_id')))
		{
				$data['facebook_id']=$this->get('fb_id');
		}else{
				$data['facebook_id']="";
		}
				if(($this->get('google_id')))
		{
				$data['google_id']=$this->get('google_id');
		}else{
			$data['google_id']= "";
		}


		$data['status']=0;  /// status 0 - not approved by admin , 1 - apporved
		$data['created']=time();
		$data['proof_status'] = "Pending";
		$data['online_status'] = 0;

		if($data['email'] === NULL || $data['password']=== NULL)
        {
			$final['status']="Fail";
			$final['message']="Missing Parameter.";
		}
		else
		{

//print_r($data);exit;
	$result=$this->Drivers_model->check_data_email($data['email']);
	$result1=$this->Drivers_model->check_data_no($data['mobile']);
	if($result==false&&$result1==false)
	{

	$result=$this->Drivers_model->insert_data('drivers',$data);

	if($result!='')
	{
		$result=$this->Drivers_model->login($data['email'],$data['password']);

	if($result->hasNext())
	{
			foreach($result as $document)
				 {
				 	$_id = $document['_id'];
						$final['status']="Success";
						$final['userid']=$document["_id"]->{'$id'};
						$final['first_name']=$document["first_name"];
						$final['last_name']=$document["last_name"];
						$final['email']=$document["email"];
						$final['profile_pic']=$document["profile_pic"];
						$final['license']=$document["license"];
						$final['insurance']=$document["insurance"];
						$final['mobile']=$document["mobile"];
						$final['category']=$document["category"];
				}
	}else{
						$final['status']="Fail";
	}

	}else{
				$final['status']="Fail";
				$final['message']="Insert error";
	}

	}
	else {
		if($result==true)
		{
							$final['status']="Fail";
				$final['message']="Email already exists";
		}
		else if($result1==true)
		{
							$final['status']="Fail";
				$final['message']="Mobile number already exists";
		}
		else {
							$final['status']="Fail";
				$final['message']="Already exists";
		}

	}




	}

	}else{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";
	}

		print_r(json_encode(array($final),JSON_UNESCAPED_SLASHES));exit;
  	}


public function imageUpload_post()
  {
  	        	$status = "";
	$msg = "";

	$file_element_name = 'uploadedfile';
	if ($status != "error")
	{
	//	$config['upload_path'] = '/var/ftp/virtual_users/tastenote/tastenote.com/files/gastronote/';
	$config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images'; //Set the upload path
	//$config['upload_path'] = '/opt/lampp/htdocs/celebspot.git/files/';
	$config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|PNG|JPG'; // Set image type
	$config['encrypt_name']	= TRUE; // Change image name

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	if(!$this->upload->do_upload($file_element_name)){
	$status = 'error';
	$msg = $this->upload->display_errors('','');
	$error = array('error' => $this->upload->display_errors());
		$final['status']="Fail";
	$final['message']= $error ;
	//print_r($error);
	$data = "";
	}
	else {

	$data = $this->upload->data(); // Get the uploaded file information

	$this->load->library('image_lib');

	$config['image_library'] = 'gd2';
	$config['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$data['raw_name'].$data['file_ext'];
	$config['create_thumb'] = FALSE;
	$config['maintain_ratio'] = TRUE;

	$config['width']     = 125;
    	$config['height']   = 125;

    	   $this->image_lib->initialize($config);
    	   $this->image_lib->resize();
		   $final['status']="Success";
		   $final['imageurl']= $this->config->item('base_url').'images/'.$data['raw_name'].$data['file_ext'];
		   $final['image_name']= $data['raw_name'].$data['file_ext'];
	//echo $this->config->item('base_url').'images/'.$data['raw_name'].$data['file_ext'];
	//echo '[{"status":"Success","imageurl":"'.$this->config->item('base_url').'images/'.$data['raw_name'].$data['file_ext'].'"}]';
	}

	@unlink($_FILES[$file_element_name]);
		print_r(json_encode(array($final),JSON_UNESCAPED_SLASHES));exit;
	}

  }


public function documentUpload_post()
  {
  	        	$status = "";
	$msg = "";
	$file_element_name = 'uploadedfile';
	if ($status != "error")
	{

	//	$config['upload_path'] = '/var/ftp/virtual_users/tastenote/tastenote.com/files/gastronote/';
	$config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/documents'; //Set the upload path
	//$config['upload_path'] = '/opt/lampp/htdocs/celebspot.git/files/';
	$config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|PNG|JPG'; // Set image type
	$config['encrypt_name']	= TRUE; // Change image name

	$this->load->library('upload', $config);
	$this->upload->initialize($config);
	if(!$this->upload->do_upload($file_element_name)){
	$status = 'error';
	$msg = $this->upload->display_errors('','');
	$error = array('error' => $this->upload->display_errors());

	$final['status']="Fail";
	$final['message']= $error ;

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
		   $final['status']="Success";
		   $final['imageurl']= $this->config->item('base_url').'images/documents/'.$data['raw_name'].$data['file_ext'];
		   $final['image_name']= $data['raw_name'].$data['file_ext'];
	//echo $this->config->item('base_url').'images/'.$data['raw_name'].$data['file_ext'];
	//echo '[{"status":"Success","imageurl":"'.$this->config->item('base_url').'images/documents/'.$data['raw_name'].$data['file_ext'].'"}]';

	}

	@unlink($_FILES[$file_element_name]);

	print_r(json_encode(array($final),JSON_UNESCAPED_SLASHES));exit;

}

  }

	public function signIn_get()
  	{

if(checkisEmpty($this->get()))
	{
		$email=$this->get('email');
		$password=$this->get('password');

		if($email === NULL || $password === NULL)
        {
			$final['status']="Fail";
			$final['message']="Missing Parameter.";
		}
		else
		{
			if(checkisEmpty($this->get()))
			{
				$response=$this->Drivers_model->find_data("drivers",array('email' => $email, 'password' => md5($password)));
				$count=$response->count();
				if($count>0)
				{
					foreach($response as $row)
					{
						if(md5($password)==$row['password'])
						{

							$final['status']="Success";
							$final['userid']=$row['_id']->{'$id'};
							$final['first_name']=$row['first_name'];
							$final['last_name']=$row['last_name'];
							$final['category']=$row['category'];
							$final['email']=$row['email'];
							$final['created']=$row['created'];

///// update lat, long, regid, device token ///
$lat = $this->get('lat');
$long = $this->get('long');
if($this->get('regid'))
{
$regid =	$this->get('regid');
}else{
$regid =	"";
}
if($this->get('devicetoken'))
{
$devicetoken = 	$this->get('devicetoken');
}else{
$devicetoken =	"";
}
updateDriverDetails($final['userid'],$lat,$long,$regid,$devicetoken);
///////////////////////////////////////////////////

						}
						else
						{
							$final['status']="Fail";
							$final['message']="Invalid password";
						}
					}

				}
				else
				{
					$final['status']="Fail";
					$final['message']="Invalid Username or password";
				}
			}
			else
			{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";
			}
		}
	}else{
		$final['status']="Fail";
		$final['message']="Missing Parameter value.";
		}

        $this->response(array($final), REST_Controller::HTTP_OK);
  	}


function fbSignup_get()
	{
	if(checkisEmpty($this->get()))
	{
				if(($this->get('fb_id')))
		{
				$fb_id = $data['facebook_id']=$this->get('fb_id');

		}else{
				$fb_id = $data['facebook_id']="";
		}
				if(($this->get('google_id')))
		{
				$data['google_id']=$this->get('google_id');
		}else{
			$data['google_id']= "";
		}

		$result2=$this->Drivers_model->check_data_fb($fb_id);

			if($result2 == false)
		{
		$data['email'] = 	$this->get('email');
		$result1=$this->Drivers_model->check_data_email($data['email']);

		if($result1==false)
		{
				if($this->get('regid'))
				{
				$regid=$this->get('regid');
				}else{
				$regid=null;
				}
			    if($this->get('devicetoken'))
			    {
			    	$devicetoken=$this->get('devicetoken');
			    }else{
			    	    	$devicetoken=null;
			    }
	  		$data['reg_id']=$regid;
			$data['devicetoken']=$devicetoken;
	     	$data['first_name']=$this->get('first_name');
			$data['last_name']=$this->get('last_name');
			$data['email']=$this->get('email');
			$data['mobile']= "";
			$data['country_code']= "";
			$data['password']= null;
			$data['city']= "";
			$data['category']=$this->get('category');

			$lat = $data['lat']= $this->get('lat');
			$long = $data['long']= $this->get('long');
			$str = '{ "coordinates": [ '.$long.', '.$lat.' ],"type": "Point" }';
			$str_update = json_decode($str);
			$data['location'] = $str_update ;

			//$data['profile_pic']=base_url()."images/".$this->get('profile_pic');
		$data['license']=base_url()."images/documents/".$this->get('license');

		$data['insurance']=base_url()."images/documents/".$this->get('insurance');
	
			$data['profile_pic']="http://graph.facebook.com/".$fb_id."/picture?type=large";
			$data['status']=0;  /// status 0 - not approved by admin , 1 - apporved		
			$data['created']=time();
			$data['proof_status'] = "null";
			$data['online_status'] = 0;
			$result=$this->Drivers_model->insert_data('drivers',$data);

		$data_array = array('facebook_id'=>$fb_id);
		$result=$this->Drivers_model->find_data('drivers', $data_array);

	if($result->hasNext())
	{
			foreach($result as $document)
				 {
				 	$_id = $document['_id'];
						$final['status']="Success";
						$final['_id']=$document["_id"]->{'$id'};
						$final['first_name']=$document["first_name"];
						$final['last_name']=$document["last_name"];
						$final['email']=$document["email"];
						$final['profile_pic']=$document["profile_pic"];
						$final['category']=$document["category"];
				}

	}else{
						$final['status']="Fail";
						$final['message']="Insert error";
	}


		}else{

			/// update the facebook id and log in details
			$updatevalue = array("facebook_id"=>$fb_id);
			$updatekey = array("email",$data['email']);
			$this->Drivers_model->update_driver($updatekey, $updatevalue);
			$data_array = array('email'=>$data['email']);
			$result=$this->Drivers_model->find_data('drivers', $data_array);
				if($result->hasNext())
				{

						foreach($result as $document)
							 {
							 	$_id = $document['_id'];
									$final['status']="Success";
									$final['status_extra']="Exist";
									$final['userid']=$document["_id"]->{'$id'};
									$final['first_name']=$document["first_name"];
									$final['last_name']=$document["last_name"];
									$final['email']=$document["email"];
									$final['profile_pic']=$document["profile_pic"];
									$final['category']=$document["category"];

							}

	///// update lat, long, regid, device token ///
$lat = $this->get('lat');
$long = $this->get('long');
if($this->get('regid'))
{
$regid =	$this->get('regid');
}else{
$regid =	"";
}
if($this->get('devicetoken'))
{
$devicetoken = 	$this->get('devicetoken');
}else{
$devicetoken =	"";
}
updateDriverDetails($final['userid'],$lat,$long,$regid,$devicetoken);
///////////////////////////////////////////////////


				}else{
									$final['status']="Fail";
				}
			/////

		}

		}else{
		$data_array = array('facebook_id'=>$fb_id);
		$result=$this->Drivers_model->find_data('drivers', $data_array);

	if($result->hasNext())
	{
			foreach($result as $document)
				 {
				 	$_id = $document['_id'];
						$final['status']="Success";
						$final['status_extra']="Exist";
						$final['userid']=$document["_id"]->{'$id'};
						$final['first_name']=$document["first_name"];
						$final['last_name']=$document["last_name"];
						$final['email']=$document["email"];
						$final['profile_pic']=$document["profile_pic"];
						$final['category']=$document["category"];
				}

///// update lat, long, regid, device token ///
$lat = $this->get('lat');
$long = $this->get('long');
if($this->get('regid'))
{
$regid =	$this->get('regid');
}else{
$regid =	"";
}
if($this->get('devicetoken'))
{
$devicetoken = 	$this->get('devicetoken');
}else{
$devicetoken =	"";
}
updateDriverDetails($final['userid'],$lat,$long,$regid,$devicetoken);
///////////////////////////////////////////////////


	}else{
						$final['status']="Fail";
						$final['message']="Insert error";
	}



		}
}else{
		$final['status']="Fail";
		$final['message']="Missing Parameter value.";
		}
		print_r(json_encode(array($final),JSON_UNESCAPED_SLASHES));exit;
	}


public function googleSignup_get()
	{
		
		  			if(checkisEmpty($this->get()))
	{

				if(($this->get('fb_id')))
		{
				$fb_id = $data['facebook_id']=$this->get('fb_id');

		}else{
				$fb_id = $data['facebook_id']="";
		}
				if(($this->get('google_id')))
		{
				$google_id = $data['google_id']=$this->get('google_id');
		}else{
			$google_id = $data['google_id']= "";
		}

		$result2=$this->Drivers_model->check_data_google($google_id);

			if($result2 == false)
		{
		$data['email'] = 	$this->get('email');
		$result1=$this->Drivers_model->check_data_email($data['email']);

		if($result1==false)
		{
				if($this->get('regid'))
				{
				$regid=$this->get('regid');
				}else{
				$regid=null;
				}
			    if($this->get('devicetoken'))
			    {
			    	$devicetoken=$this->get('devicetoken');
			    }else{
			    	    	$devicetoken=null;
			    }
	  		$data['reg_id']=$regid;
			$data['devicetoken']=$devicetoken;
	     	$data['first_name']=$this->get('first_name');
			$data['last_name']=$this->get('last_name');
			$data['email']=$this->get('email');
			$data['mobile']= "";
			$data['country_code']= "";
			$data['password']= null;
			$data['city']= "";
			$data['category']=$this->get('category');
			$data['lat']= $this->get('lat');
			$data['long']= $this->get('long');
			//$data['profile_pic']=base_url()."images/".$this->get('profile_pic');
		$data['license']=base_url()."images/documents/".$this->get('license');
		$data['insurance']=base_url()."images/documents/".$this->get('insurance');
			$profile_pic = get_userProfile($google_id);
			$data['profile_pic']= $profile_pic;
			$data['status']=0;  /// status 0 - not approved by admin , 1 - apporved
			$data['created']=time();
			$data['proof_status'] = "null";
			$data['online_status'] = 0;
			$result=$this->Drivers_model->insert_data('drivers',$data);

		$data_array = array('google_id'=>$google_id);
		$result=$this->Drivers_model->find_data('drivers', $data_array);

	if($result->hasNext())
	{
			foreach($result as $document)
				 {
				 		$_id = $document['_id'];
						$final['status']="Success";
						$final['userid']=$document["_id"]->{'$id'};
						$final['first_name']=$document["first_name"];
						$final['last_name']=$document["last_name"];
						$final['email']=$document["email"];
						$final['profile_pic']=$document["profile_pic"];
						$final['category']=$document["category"];

				}

	}else{
						$final['status']="Fail";
						$final['message']="Insert error";
	}


		}else{

			/// update the google id and log in details
			$updatevalue = array("google_id"=>$google_id);
			$updatekey = array("email",$data['email']);
			$this->Drivers_model->update_driver($updatekey, $updatevalue);
			$data_array = array('email'=>$data['email']);
			$result=$this->Drivers_model->find_data('drivers', $data_array);
				if($result->hasNext())
				{

						foreach($result as $document)
							 {
							 	$_id = $document['_id'];
									$final['status']="Success";
									$final['status_extra']="Exist";
									$final['userid']=$document["_id"]->{'$id'};
									$final['first_name']=$document["first_name"];
									$final['last_name']=$document["last_name"];
									$final['email']=$document["email"];
									$final['profile_pic']=$document["profile_pic"];
									$final['category']=$document["category"];

							}
 ///// update lat, long, regid, device token ///
$lat = $this->get('lat');
$long = $this->get('long');
if($this->get('regid'))
{
$regid =	$this->get('regid');
}else{
$regid =	"";
}
if($this->get('devicetoken'))
{
$devicetoken = 	$this->get('devicetoken');
}else{
$devicetoken =	"";
}
updateDriverDetails($final['userid'],$lat,$long,$regid,$devicetoken);
///////////////////////////////////////////////////

				}else{
									$final['status']="Fail";
				}
			/////

		}

		}else{
		$data_array = array('google_id'=>$google_id);
		$result=$this->Drivers_model->find_data('drivers', $data_array);

	if($result->hasNext())
	{
			foreach($result as $document)
				 {
				 	$_id = $document['_id'];
						$final['status']="Success";
						$final['status_extra']="Exist";
						$final['userid']=$document["_id"]->{'$id'};
						$final['first_name']=$document["first_name"];
						$final['last_name']=$document["last_name"];
						$final['email']=$document["email"];
						$final['profile_pic']=$document["profile_pic"];
						$final['category']=$document["category"];

				}

///// update lat, long, regid, device token ///
$lat = $this->get('lat');
$long = $this->get('long');
if($this->get('regid'))
{
$regid =	$this->get('regid');
}else{
$regid =	"";
}
if($this->get('devicetoken'))
{
$devicetoken = 	$this->get('devicetoken');
}else{
$devicetoken =	"";
}
updateDriverDetails($final['userid'],$lat,$long,$regid,$devicetoken);
///////////////////////////////////////////////////


	}else{
						$final['status']="Fail";
						$final['message']="Insert error";
	}



		}
}else{
		$final['status']="Fail";
		$final['message']="Missing Parameter value.";
		}

		print_r(json_encode(array($final),JSON_UNESCAPED_SLASHES));exit;
	}



	public function updateLocation_get(){

  				if(checkisEmpty($this->get()))
			{
				$lat = $this->get('lat');
				$long = $this->get('long');
				$userid = $this->get('userid');
						$str = '{ "coordinates": [ '.(string)$long.', '.(string)$lat.' ], "type": "Point" }';
		$str_update = json_decode($str);
//print_r($str_update) ;exit;
          $result=$this->Drivers_model->update_location($userid,$lat,$long,$str_update);

		if($result)
		{
							$final['status']="Success";
		}
		else {
							$final['status']="Fail";
		}
		}else{
						$final['status']="Fail";
				$final['message']="Missing Parameter value.";
		}
		        $this->response(array($final), REST_Controller::HTTP_OK);

	}

	public function getnearbyDrivers_get()
{


	$userid=$res_request['user_id'];
	$res_user=$this->mongo_db->db->riders->findOne(array('_id'=> new MongoId($userid)));
	$lat=$res_user['lat'];
	$long=$res_user['long'];
	$this->get_driving_information_manual($userid,$lat,$long,$category,"null");

	$this->response(array($final), REST_Controller::HTTP_OK);

}


	public function updateOnlineStatus_get(){

  		if(checkisEmpty($this->get()))
		{

				$userid = $this->get('userid');
				$online_status = $this->get('online_status');	// 0:Offline 1:Online
				if (in_array($online_status, array("1", "0"), true)) {
				$updatekey = array("_id"=> new MongoId($userid));
				$update_data = array("online_status"=>$online_status);
				//$this->Drivers_model->update_driver($updatekey, $update_data);
			    $this->Drivers_model->update_driver($updatekey, $update_data);
				$final['status']="Success";
				$final['online_status']= $online_status=='1'?'online':'offline';
				}else{
				$final['status']="Fail";
				$final['message']="Parameter should be boolean";
				}
		}else{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";
		}
		        $this->response(array($final), REST_Controller::HTTP_OK);

	}

public function editProfile_get()
{
	if(checkisEmpty($this->get()))
	{
			if($this->get('user_id'))
			{
			$user_id=$this->get('user_id');
			$result=$this->Drivers_model->display_details($user_id);
			if($result->hasNext())
			{
				foreach($result as $document)
				 {

				$final['firstname'] = $document['first_name'];
				$final['lastname'] = $document['last_name'];
				$final['profile_pic'] = $document['profile_pic'];
				$final['email'] = $document['email'];
				$final['country_code'] = $document['country_code'];
				$final['mobile'] = $document['mobile'];
				$final['password'] = $document['password'];
				$final['city'] = $document['city'];
				$final['category'] = $document['category'];
				$final['status']="Success";

				 }
			}
			else
			{
						$final['status']="Fail";
						$final['message']="Error";
			}

			}else{
						$final['status']="Fail";
						$final['message']="Parameter Missing";
			}
	}else{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";
		}
	print_r(json_encode(array($final),JSON_UNESCAPED_SLASHES));exit;

}

public function updateDetails_get()
	{

	if(checkisEmpty($this->get()))
	{
		$user_id=$this->get('user_id');

		$updatevalue['first_name']=$this->get('firstname');
		$updatevalue['last_name']=$this->get('lastname');
		$updatevalue['mobile']=$this->get('mobile');
		$updatevalue['email']=$this->get('email');
		if(($this->get('profile_pic')))
		{
				$updatevalue['profile_pic']=base_url()."images/".$this->get('profile_pic');

		}
		if(($this->get('license')))
		{
				$updatevalue['license']=base_url()."images/documents/".$this->get('license');

		}
		if(($this->get('insurance')))
		{
				$updatevalue['insurance']=base_url()."images/documents/".$this->get('insurance');

		}
		$updatevalue['country_code']=$this->get("country_code");
		$updatevalue['city']=$this->get("city");

		$updatekey = array("_id"=> new MongoId($user_id));
		$result=$this->Drivers_model->update_driver($updatekey, $updatevalue);
		if(!empty($result))
		{
				$final['userid'] = $user_id;
				$final['status']="Success";
		}else{
				$final['status']="Fail";
				$final['message']="Update Error";
		}

	}else{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";
		}
	$this->response(array($final), REST_Controller::HTTP_OK);

	}

function forgotPassword_get()
	{
		if(checkisEmpty($this->get()))
	{
		if($this->get("email"))
		{

			$data['email']=$this->get("email");
			$result=$this->Drivers_model->find_data("drivers",$data);

			if($result->hasNext())
			{
					foreach($result as $document)
						 {
							$email = $document['email'];
							$user_id = $document["_id"]->{'$id'};
							$user_name = $document['first_name'];

						$link = $this->config->item('base_url').'home/emailConfirmation?user=driver&email='.$email.'&passkey='.$user_id;
						$link = anchor($link, 'Reset Password link');
						$splvars = array("{link}"=> $link ,"{email_id}"=>$email, "{username}"=>$user_name);
						$code = "reset_password";

						 $setting_image = $this->mongo_db->db->settings->find();

							if($setting_image->hasNext())
						{
								foreach($setting_image as $documentimage)
									 {
									 $admin_mail   = $documentimage['admin_mail'];
									 $title = $documentimage['title'];
									 }
						}else{
							$admin_mail = "productcogz@gmail.com";
							$title = "Arcane";
						}

						$from_email = $admin_mail ;
						$mail_result = $this->Email_model->sendMail($email,$from_email,$title,$splvars,$code);

						$final['status']="Success";
						$final['message']="Email sent";
					}

				}
				else{
				$final['status']="Fail";
				$final['message']="Email not exist";
				}

			}else {
				$final['status']="Fail";
				$final['message']="Missing email";
			}

	}else{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";
		}
	$this->response(array($final), REST_Controller::HTTP_OK);

}


public function checkEmailPhone_get()
{
	if(checkisEmpty($this->get()))
	{
	$data['email']=$this->get('email');
	$data['mobile']=$this->get('mobile');
	$result=$this->Drivers_model->check_data_email($data['email']);
	$result1=$this->Drivers_model->check_data_no($data['mobile']);
		if($result ==false )
		{
	$final['status']="Success";
		}
		else{
	$final['status']="Fail";
	$final['message']="Email exist";
	//$final['message']="Already exist";
		}
		
		if($result1 ==false )
		{
//	$final['status']="Success";
		}
		else if($result ==true && $result1 ==true)
		{
			$final['status']="Fail";
	$final['message']="Both Exits";
		}
		else{
	$final['status']="Fail";
	$final['message']="Mobile exist";
	
		}
		
		

	}else
	{
	$final['status']="Fail";
	$final['message']="Missing Parameter value.";
	}
	

   $this->response(array($final), REST_Controller::HTTP_OK);

}

public function mobileExist_get()
{
	if(checkisEmpty($this->get()))
	{
	$data['mobile']=$this->get('mobile');
	$result=$this->Drivers_model->check_data_no($data['mobile']);
		if($result ==false)
		{
	$final['status']="Success";
		}else{
	$final['status']="Fail";
	$final['message']="Already exist";
		}

	}else
	{
	$final['status']="Fail";
	$final['message']="Missing Parameter value.";
	}

   $this->response(array($final), REST_Controller::HTTP_OK);

}


public function emailExist_get()
{
	if(checkisEmpty($this->get()))
	{
	$data['email']=$this->get('email');
	$result=$this->Drivers_model->check_data_email($data['email']);
		if($result ==false)
		{
	$final['status']="Success";
		}else{
	$final['status']="Fail";
	$final['message']="Already exist";
		}

	}else
	{
	$final['status']="Fail";
	$final['message']="Missing Parameter value.";
	}

   $this->response(array($final), REST_Controller::HTTP_OK);

}

public function FBExist_get()
{
	if(checkisEmpty($this->get()))
	{
	$data['fb_id']=$this->get('fb_id');
	$result=$this->Drivers_model->check_data_fb($data['fb_id']);
		if($result ==false)
		{
	$final['status']="Fail";
	$final['message']="New user";
		}else{

	$data_array = array('facebook_id'=>$data['fb_id']);
	$result=$this->Drivers_model->find_data('drivers', $data_array);

	if($result->hasNext())
	{
			foreach($result as $document)
				 {
				 	$_id = $document['_id'];
						$final['status']="Success";
						$final['status_extra']="Exist";
						$final['userid']=$document["_id"]->{'$id'};
						$final['first_name']=$document["first_name"];
						$final['last_name']=$document["last_name"];
						$final['email']=$document["email"];
						$final['profile_pic']=$document["profile_pic"];
						$final['category']=$document["category"];
				}

	}

		}

	}else
	{
	$final['status']="Fail";
	$final['message']="Missing Parameter value.";
	}

	print_r(json_encode(array($final),JSON_UNESCAPED_SLASHES));exit;

}

public function GBExist_get()
{
	if(checkisEmpty($this->get()))
	{
	$data['google_id']=$this->get('google_id');
	$result=$this->Drivers_model->check_data_google($data['google_id']);
		if($result ==false)
		{
	$final['status']="Fail";
	$final['message']="New user";
		}else{

		$data_array = array('google_id'=>$data['google_id']);
		$result=$this->Drivers_model->find_data('drivers', $data_array);

	if($result->hasNext())
	{
			foreach($result as $document)
				 {
				 	$_id = $document['_id'];
						$final['status']="Success";
						$final['status_extra']="Exist";
						$final['userid']=$document["_id"]->{'$id'};
						$final['first_name']=$document["first_name"];
						$final['last_name']=$document["last_name"];
						$final['email']=$document["email"];
						$final['profile_pic']=$document["profile_pic"];
						$final['category']=$document["category"];

				}

	}
		}

	}else
	{
	$final['status']="Fail";
	$final['message']="Missing Parameter value.";
	}

	print_r(json_encode(array($final),JSON_UNESCAPED_SLASHES));exit;

}


public function yourEarnings_get(){

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

	$result=$this->Drivers_model->findTotalEarning($userid);
	if(count($result['result'])){
	$getTripsData['total_price'] = $result['result'][0]['total_price'];
	$getTripsData['total_trips'] = $result['result'][0]['total_trips'];
	$getTripsData['last_tripDate'] = $result['result'][0]['last_tripDate'];
	}else{
	$getTripsData['total_price'] = 0;
	$getTripsData['total_trips'] = 0;
	$getTripsData['last_tripDate'] = "";	
	}
	array_push($response_array,$getTripsData);
	
		//print_r(json_encode(array($data),JSON_UNESCAPED_SLASHES));exit;
	}

	}	else{
	$final['status']="Fail";
	$final['message']="Missing Parameter value.";
	array_push($response_array,$final);
}
			$this->response($response_array, REST_Controller::HTTP_OK);

}

public function overallRating_get()   // For Driver
  	{
  		$rate =0;
  		$count =0;
		$total_rating=0;
  		
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
				
					$result=$this->mongo_db->db->ratedriver->find(array('driverid'=>$userid));
			
				
					foreach($result as $document)
										{
										 $rate +=  $document['rate'];
										 $count = $count+1; 
										 
										$total_rating=$rate/$count;
										 
										 }										 
										
			if($total_rating!=0){
				
				$final['status']="Success";
				$final['total_rating']=$rate;
				$final['total_star']=ceil($total_rating);
				$final['total_count']=$count;	
				array_push($response_array,$final);
			
			}
			else{
					$final['status']="Fail";
				$final['message']="No rating given";
				array_push($response_array,$final);
			}							
				
			}
				
		}else{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";	
				array_push($response_array,$final);
		}
				$this->response($response_array, REST_Controller::HTTP_OK);
	
	
	}
	
	public function checkProofStatus_get()   // For Driver
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
				$result=$this->mongo_db->db->drivers->find(array('_id'=>new MongoId($userid)));
				
				if($result->hasNext())
		{
						foreach($result as $document)
					{
						$proof_status =  $document['proof_status'];		
						
					 }										 
		
		}
     			 
			if($proof_status=="Accepted")
			{
				
				$final['status']="Success";
				$final['proof_status']=$proof_status;	
				array_push($response_array,$final);
			
			}
			else{
					$final['status']="Fail";
				$final['proof_status']=$proof_status;
				array_push($response_array,$final);
			}							
				
			}
				
		}else{
				$final['status']="Fail";
				$final['message']="Missing Parameter value.";	
				array_push($response_array,$final);
		}
				$this->response($response_array, REST_Controller::HTTP_OK);
	
	
	}
	
}
