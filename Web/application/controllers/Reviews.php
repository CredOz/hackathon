<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Reviews extends REST_Controller {

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
	 	public function Reviews()
	{
		parent::__Construct();
		$this->load->library('mongo_db');
		$this->load->model('Common_model');
		$this->load->model('Drivers_model');
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
  
  public function insertReview_get()
  {
  	
  }
  
  public function getReview_get()
  {
  	
  }
  
}  