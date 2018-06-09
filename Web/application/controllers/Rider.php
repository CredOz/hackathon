<?php
header('Content-Type: application/json');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Rider extends REST_Controller {

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
	 	 	public function Rider()
	{
		parent::__Construct();
		$this->load->library('mongo_db');
		$this->load->model('Common_model');
		$this->load->model('Drivers_model');
		$this->load->model('Rider_model');
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
  
  public function pwd_get()
  {
  	echo 'fgfg';
  	echo base64_decode($this->get('test'));
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
		$data['country_code'] = $this->get('country_code');
		$data['mobile']=$this->get('mobile');
		$data['password']=md5($this->get('password'));
		$data['profile_pic'] = "";
		$data['lat']= $this->get('lat');
		$data['long']= $this->get('long');	
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
		$data['status']=1;  /// status 0 - not approved by admin , 1 - apporved		
		$data['created']=time();
		if($data['email'] === NULL || $data['password']=== NULL)
        {
			$final['status']="Fail";
			$final['message']="Missing Parameter.";
		}
		else
		{
		
	$result=$this->Rider_model->check_data_email($data['email']);
	$result1=$this->Rider_model->check_data_no($data['mobile']);
	
	if($result==false&&$result1==false)
	{
	$result=$this->Rider_model->insert_data($data);
	
	if($result!=0)
	
	{
	$result=$this->Rider_model->login($data['email'],$data['password']);
	
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
						$final['country_code']=$document["country_code"];
						$final['mobile']=$document["mobile"];
	 
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
				$final['status']="Fail";
				$final['message']="Email or Mobile number Already exists";
	}
	
	
		}
	
	}else{
		$final['status']="Fail";
		$final['message']="Missing Parameter value.";	
		}
		
        $this->response(array($final), REST_Controller::HTTP_OK);
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
				$response=$this->Rider_model->find_data("riders",array('email' => $email, 'password' => md5($password)));
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
							$final['email']=$row['email'];
							$final['created']=$row['created'];
							
							/// update ride details //	
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
								updateRiderDetails($final['userid'],$lat,$long,$regid,$devicetoken);
							///////
							
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
		
	$result2=$this->Rider_model->check_data_fb($fb_id);	
		if($result2 == false)
		{
			
		$data['email']=$this->get('email');
		$result1=$this->Rider_model->check_data_email($data['email']);	
		
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
		$data['mobile']= '';
		$data['country_code']='';	
		$data['password']=null;
		$data['profile_pic']="http://graph.facebook.com/".$fb_id."/picture?type=large";
		$data['status']=1;  /// status 0 - not approved by admin , 1 - apporved		
		$data['created']=time();
		$data['lat']= $this->get('lat');
		$data['long']= $this->get('long');
			$this->Rider_model->insert_data($data);		
			$data_array = array('facebook_id'=>$fb_id);		
			$result=$this->Rider_model->find_data('riders', $data_array);					
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
							
							}
				}else{
									$final['status']="Fail";
				}
			
		}else{
			
			/// update the fb id and log in details		
			$updatevalue = array("facebook_id"=>$fb_id);
			$updatekey = array("email",$data['email']);	
			$this->Rider_model->update_Tabdata('riders',$updatekey, $updatevalue);
			$data_array = array('email'=>$data['email']);		
			$result=$this->Rider_model->find_data('riders', $data_array);					
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
							
								/// update ride details //	
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
								updateRiderDetails($final['userid'],$lat,$long,$regid,$devicetoken);
							///////
							}
				}else{
									$final['status']="Fail";
				}		
			/////	
		}
			
		}else{
			
			$data_array = array('facebook_id'=>$fb_id);			
			$result=$this->Rider_model->find_data('riders', $data_array);					
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
							
							/// update ride details //	
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
								updateRiderDetails($final['userid'],$lat,$long,$regid,$devicetoken);
							///////								
							}
				}else{
									$final['status']="Fail";
				}	
		}
		
	}else{
		$final['status']="Fail";	
		$final['message']="Missing Parameter value.";	
		}

		print_r(json_encode(array($final),JSON_UNESCAPED_SLASHES));exit;	
	}

function googleSignup_get()
	{
		
		if(checkisEmpty($this->get()))
	{

		if(($this->get('fb_id')))
		{
				$fb_id = $data['facebook_id']=$this->get('fb_id');
					
		}else{
				$fb_id = $data['facebook_id']="";	
		}
				if($this->get('google_id'))
		{
			$gb_id=	$data['google_id']=$this->get('google_id');	
		}else{
			$gb_id = $data['google_id']= "";		
		}
	$result2=$this->Rider_model->check_data_google($gb_id);
			if($result2 == false)
		{
		
		$data['email']=$this->get('email');
		$result1=$this->Rider_model->check_data_email($data['email']);	
		
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
		$data['password']=null;
		$profile_pic = get_userProfile($gb_id);
		$data['profile_pic']= $profile_pic;
		$data['status']=1;  /// status 0 - not approved by admin , 1 - apporved		
		$data['created']=time();
		$data['lat']= $this->get('lat');
		$data['long']= $this->get('long');
		$data['mobile']= '';
		$data['country_code']='';	
		
			$this->Rider_model->insert_data($data);		
			$data_array = array('google_id'=>$gb_id);		
			$result=$this->Rider_model->find_data('riders', $data_array);					
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
							
							}
				}else{
									$final['status']="Fail";
				}
			
		}else{
			
			/// update the google id and log in details		
			$updatevalue = array("google_id"=>$gb_id);
			$updatekey = array("email",$data['email']);	
			$this->Rider_model->update_Tabdata('riders',$updatekey, $updatevalue);
			$data_array = array('email'=>$data['email']);		
			$result=$this->Rider_model->find_data('riders', $data_array);					
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
														/// update ride details //	
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
								updateRiderDetails($final['userid'],$lat,$long,$regid,$devicetoken);
							///////
							}
				}else{
									$final['status']="Fail";
				}		
			/////	
		}
		
		}else{
			
			$data_array = array('google_id'=>$gb_id);			
			$result=$this->Rider_model->find_data('riders', $data_array);
					
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
							/// update ride details //	
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
								updateRiderDetails($final['userid'],$lat,$long,$regid,$devicetoken);
							///////					
							}
				}else{
									$final['status']="Fail";
				}	
		}


}else{
	$final['status']="Fail";
	$final['message']="Missing Parameter value.";	
}
		print_r(json_encode(array($final),JSON_UNESCAPED_SLASHES));exit;	
		
		}

public function editProfile_get()
{	
	if(checkisEmpty($this->get()))
	{
			if($this->get('user_id'))
			{				
			$user_id=$this->get('user_id');
			$result=$this->Rider_model->display_details($user_id);
			if($result->hasNext())
			{	
				foreach($result as $document)
				 {
				 
				$final['firstname'] = $document['first_name'];
				$final['lastname'] = $document['last_name'];
				$final['profile_pic'] = $document['profile_pic'];
				$final['email'] = $document['email'];
				$final['password'] = $document['password'];
				$final['mobile']= $document['mobile'];
				$final['country_code']=$document['country_code'];	
				if(isset($document['creditcard_number']))
				{
				$final['card_number']=$document['creditcard_number'];	
				$final['card_status'] = "1";
				}else{
				$final['card_number']="None";
				$final['card_status'] = "0";
				}
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
		$updatevalue['email']=$this->get('email');
		if(($this->get('profile_pic')))
		{
				$updatevalue['profile_pic']=base_url()."images/".$this->get('profile_pic');
			
		}		$updatevalue['mobile']= $this->get('mobile');
		$updatevalue['country_code']=$this->get('country_code');	
				
		$updatekey = array("_id"=> new MongoId($user_id));
		$result=$this->Rider_model->update_riders($updatekey, $updatevalue);
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

	public function updateLocation_get(){

  				if(checkisEmpty($this->get()))
			{
				$lat = $this->get('lat');	
				$long = $this->get('long');	
				$userid = $this->get('userid');	
          $result=$this->Rider_model->update_location($userid,$lat,$long);
          
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

public function updateStripeToken_get()
{
	if(checkisEmpty($this->get()))
			{
				$stripetoken = $this->get('token');	
				$userid = $this->get('userid');	
				$cardnumber = $this->get('card_number');
				$get_settings = GetSettings();
				if($this->get('token') === NULL || $this->get('userid') === NULL || $this->get('card_number') === NULL  ) //Check whether params are passed
       		{
			$final['status']="Fail";
			$final['message']="Missing Parameter.";
			}
			else
			{
				$getRider = getUserData('riders',$userid);
				require_once APPPATH.'libraries/stripe/init.php';
				
									if($get_settings['is_live_stripe'] == 1)
					{
					$stripe_key = $get_settings['Live_ApiKey'];
					}else{
					$stripe_key = $get_settings['Test_ApiKey'];	
					}
					\Stripe\Stripe::setApiKey($stripe_key);
					
  				$customer = \Stripe\Customer::create(array(
     			 'email' => $getRider['email'],
    	 		 'card'  => $stripetoken
 					));
					
		$updatekey = array("_id"=> new MongoId($userid));
		$updatevalue['stripe_token'] = $stripetoken ;
		$updatevalue['creditcard_number'] =  $cardnumber;
		$updatevalue['creditcard_status'] =  "1"; // 0 - deactive, 1 - active
		$updatevalue['s_customerid'] = $customer->id ;
		$result=$this->Rider_model->update_riders($updatekey, $updatevalue);
          
		if($result)
		{
							$final['status']="Success";
		}
		else {
							$final['status']="Fail";
		}
		
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
			$result=$this->Drivers_model->find_data("riders",$data);
			
			if($result->hasNext())
			{	
					foreach($result as $document)
						 {
							$email = $document['email'];
							$user_id = $document["_id"]->{'$id'};
							$user_name = $document['first_name'];
			
						$link = $this->config->item('base_url').'home/emailConfirmation?user=rider&email='.$email.'&passkey='.$user_id;
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

public function emailExist_get()
{
	if(checkisEmpty($this->get()))
	{
	$data['email']=$this->get('email');
	$result=$this->Rider_model->check_data_email($data['email']);
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

public function mobileExist_get()
{
	if(checkisEmpty($this->get()))
	{
	$data['mobile']=$this->get('mobile');
	$result=$this->Rider_model->check_data_no($data['mobile']);
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

public function riderrating_get()   // For Rider
  	{
  $response_array = array();
				if(checkisEmpty($this->get()))
		{
					$data['userid']=$this->get('userid');
					$data['driverid']=$this->get('driverid');
					$data['rate']=$this->get('rate');
					//$result2 = $this->mongo_db->db->ratedriver->insert($data);
					
					if($data['driverid']==null)
					{
						$final['status']="Failed";
						$final['message']="No Driver ID";
						array_push($response_array,$final);	
					}
					else {
							$result2 = $this->mongo_db->db->ratedriver->insert($data);
					
					if($result2)
					{
						$final['status']="Success";
						$final['message']="Rider Rating";
						array_push($response_array,$final);	
					}
					
					else {
						$final['status']="Fail";
											$final['message']="Failed";
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
