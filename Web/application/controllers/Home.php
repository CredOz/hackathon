<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

 	 	public function Home()
	{
		parent::__Construct();
		$this->load->library('mongo_db');
		$this->load->model('Common_model');
		$this->load->model('Drivers_model');
		$this->load->model('Rider_model');
		$this->load->model('Email_model');
		$this->load->helper('global');
	}
	public function index()
	{
		$this->load->view('home');
	}
		public function signin()
	{
	    $data['carname']= 'test';
		$data['category']= 'new';
		//$this->mongo_db->db->users->insert($data);
		
		$this->load->view('home');
	}
	
		
public function emailConfirmation()
{
	

		if($this->input->get("email") != "" && $this->input->get("passkey") != "")
		{
		if($this->input->get("user") == "rider")
		{
			$table = "riders";	
		}else if($this->input->get("email") == 'driver')
		{
			$table = "drivers";
		}else{
			$table = "drivers";
		}
	    $email=$this->input->get('email');
	    $passkey=$this->input->get('passkey');
		$data['_id'] = new MongoId($passkey);
		$result=$this->Drivers_model->find_data($table,$data);
	if($result->hasNext())
	{	
			foreach($result as $document)
				 {
							$email = $document['email'];
							$user_id = $document["_id"]->{'$id'};
							$user_name = $document['first_name'];
					
		
			$data['userid'] = $user_id;
			$data['type'] =$table ;
		$this->load->view('reset_password',$data);
			
	}
	
	}
	 else {
	$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error',"Invalid Confirmation link"));
	redirect('home/view_reset/');	
			}
			
		}else{
		redirect('Home');			
		}
		
	
}	 
	
	public function view_reset()
	{
$s = $this->session->userdata('home_redirect');
		if($s == "1")
		{
			redirect('Home');	
		}else{
				$this->load->view('reset_password');	
		}
	}
	
	public function change_password()
	{
			if($this->input->post('update'))
		{
				$newpassword = $this->input->post('new_password');
				$confirm_password = $this->input->post('confirm_password');
				$userid = $this->input->post('userid');
				$table = $this->input->post('type');
				
				if($userid != "" && $table != "")
				{
					
				if($newpassword==$confirm_password){
						
		$data1=	$this->mongo_db->db->$table->update(array("_id"=> new MongoId($userid)),array('$set'=>array("password"=>md5($newpassword))));
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Password updated successfully'));	
		$this->session->set_userdata('home_redirect',1);
			 redirect('home/view_reset');
		 
		}else {
			$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Your password is wrong'));	
			 redirect('home/view_reset');
		}
	
				}else{
		$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Invalid details'));	
			 redirect('home/view_reset');			
				}
				
				
}
else{
		redirect('Home');
}
		
	}
	
	public function test()
	{

		 	$result = $this->mongo_db->db->settings->find();
			$data = ""; 
			$apikey = '';
		  foreach($result as $res) {	
  		  	$apikey = $res['google_api_key'];				
		  }
		  $url = 	 "https://www.googleapis.com/plus/v1/people/105289107518123143986?fields=image&key=AIzaSyD_nwCI7RqGsWkwWeEoJb-KkdVaIfDhFxc" ; 
			$geocode     = file_get_contents($url);
			if($geocode)
			{
			$output      = json_decode($geocode);
			$data = $output->image->url;				
			echo $data ;
					
			}else{
			$data = "";	
			}
			
			if($data == "")
			{
				
			$forJson = file_get_contents('http://picasaweb.google.com/data/entry/api/user/105289107518123143986?alt=json', true);
			
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
	
}
