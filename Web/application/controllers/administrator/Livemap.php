<?php
/*
  error_reporting(E_ALL);
ini_set('display_errors', 'On');

*/


class Livemap extends CI_Controller
{
	// Used for registering and changing password form validation
	var $min_username = 4;
	var $max_username = 20;
	var $min_password = 4;
	var $max_password = 20;
	public $google_api_key;
	function Livemap()
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
	
	}
		function index()
		{
			
	$data['api_credentials'] = GetSettings();	
	$all_category = GetAllCategory();
	$category_name = array();

	foreach ($all_category as $name) {
		$category_name[] = $name['categoryname'];
	}
	$data['categories'] = $category_name;
	$data['message_element'] = "administrator/view_map";
	$this->load->view('administrator/admin_template', $data);
		
		}
		




} // Class
?>