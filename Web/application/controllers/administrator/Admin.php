<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{

		parent::__construct();
		$this->load->model('rider_model');
		$this->load->model('admin_model');
		//$this->load->model('rider_model');
		//$this->load->model('fb_drivers_model');
		//$this->load->model('fb_rider_model');
		$this->load->model('Common_model');
		$this->load->model('users_model');
		$this->load->model('drivers_model');
		
		$this->load->library('mongo_db');
	}
	public function index()
	{
	     
	     if(check_logged()) 
	     {
	       redirect('administrator/admin/home');
	   	 
	   }
	   else 
	   {
	    
	   	   $this->load->view('administrator/view_login');
		}
	  
	}
	public function login()
	{
	   $this->load->model('Admin_model');
	   $this->load->library('form_validation');
	   $this->form_validation->set_rules('username', 'Username', 'trim|required');
	   $this->form_validation->set_rules('password', 'Password', 'trim|required');
	   if($this->form_validation->run())
		{
			  $username=$this->input->post('username');
                    $password=$this->input->post('password');   
                     $validate=$this->admin_model->check_valid_user_or_not();
                    // print_r($validate);
                      if( $validate!="") 
                     {
                          
                          $data = array('last_activity'=> time(), 'username' => $this->input->post('username'),'logged_in' => true);
                          
                          
                        
                          $this->session->set_userdata($data);
            	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Logged In Successfully'));
                          redirect('administrator/admin/home');
                    }
                    else
                    {
                      
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Invalid Username or Password'));
	redirect('administrator/admin/login');                    
                    
                    }
                }   
			
		$this->load->view('administrator/view_login');
	}
	
	public function home()
	{
		
	    if(check_logged()) 
	    {
	    	$data['total_riders'] = $this->rider_model->get_DocCount('riders');
			$data['total_drivers'] = $this->rider_model->get_DocCount('drivers');
	        $data['today_Riders']=$this->users_model->get_today_user();
			$data['today_Driver']=$this->drivers_model->get_today_user();
			$data['total_TodayTrips']=$this->drivers_model->get_transaction();
			$data['total_trips']=$this->users_model->get_total_trips();
			   
			$this->load->view('administrator/home',$data);
		
	   	}
	    else 
	    {
		  redirect('administrator/admin/login');
	    }	
	 
	 }
	  
	  function logout()
      {
           $this->session->sess_destroy();
           redirect('administrator/admin/login');
     
      }
	  
	  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */