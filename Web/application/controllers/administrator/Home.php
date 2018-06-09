<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{

		parent::__construct();
		$this->load->library('encrypt');
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('mongo_db');
		$this->load->library('Table');
		$this->load->library('Pagination');

		$this->load->library('session');
	
		
	}
	public function index()
	{
			 if(check_logged()) 
	    {
		$this->load->view('administrator/home');
		}else{
	redirect('administrator/admin/login');
			}
	}
 	function logout()
      {
           $this->session->sess_destroy();
           redirect('');
     
    }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
