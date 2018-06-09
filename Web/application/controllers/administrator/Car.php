<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Car extends CI_Controller {

	public function __construct() {

		parent::__construct();

		$this -> load -> model('rider_model');
		$this -> load -> model('admin_model');
		$this -> load -> model('Common_model');
		$this -> load -> model('users_model');
		$this -> load -> model('drivers_model');
		$this -> load -> helper('form_helper');
		$this -> load -> library('form_validation');
		$this -> load -> library('mongo_db');
	}

	public function index() {

	}

	function view_car() {
		if (check_logged()) {
			$data['category'] = $this -> mongo_db -> db -> category -> find();

			$data['message_element'] = "administrator/car/view_add_car";
			$this -> load -> view('administrator/admin_template', $data);
		} else {
			redirect('administrator/admin/login');
		}

	}

	function check() {
		$carname = trim($this -> input -> post('addcar'));
		$this -> load -> library('mongo_db');

		$collection = $this -> mongo_db -> db -> car;
		$user = $collection -> findOne(array("carname" => $carname));
		if (count($user)) {
			$this -> session -> set_flashdata('flash_message', $this -> Common_model -> admin_flash_message('error', 'car name is already exist'));
			redirect('administrator/car/view_car/');

		} else {
			$this -> addcar();
		}

	}

	function addcar() {

		if (check_logged()) {

			$data = array('carname' => $this -> input -> post('addcar'), 'category' => $this -> input -> post('category'));
			$result1 = $this -> mongo_db -> db -> car -> insert($data);
			$this -> session -> set_flashdata('flash_message', $this -> Common_model -> admin_flash_message('success', 'Car Added successfully'));
			redirect('administrator/car/view_all_car');

		} else {
			redirect('administrator/admin/login');
		}

	}

	function view_all_car() {
		if (check_logged()) {
			$car = $this -> mongo_db -> db -> car -> find();
			$data['car'] = $car;
			$data['message_element'] = "administrator/car/view_car";
			$this -> load -> view('administrator/admin_template', $data);
		} else {
			redirect('administrator/admin/login');
		}
	}

	function editcar($id) {
		if (check_logged()) {

			//Intialize values for library and helpers
			$this -> form_validation -> set_error_delimiters($this -> config -> item('field_error_start_tag'), $this -> config -> item('field_error_end_tag'));

			if ($this -> input -> post('submit'))
			//print_r($this->input->post()); exit;
			{
				$car = $this -> input -> post('carname');
				$category = $this -> input -> post('category');
				//Set rules
				$this -> form_validation -> set_rules('carname', 'carname', 'required|trim');

				if ($this -> form_validation -> run()) {
					//prepare update data
					$updateData = array();
					$updateData['carname'] = $this -> input -> post('carname');
					$updateData['category'] = $this -> input -> post('category');
					$updateData['price_km'] = $this -> input -> post('price_km');
					$updateData['price_minute'] = $this -> input -> post('price_minute');
					$updateData['price_fare'] = $this -> input -> post('price_fare');
					$updateData['currency'] = 'USD';

					$check = $this -> mongo_db -> db -> car -> update(array("_id" => new MongoId($id)), array('$set' => $updateData));
					//$check = $this->mongo_db->db->car->update(array("_id"=>new MongoId($id)),array('$set'=>array("category"=>$category)));

					//Notification message
					$this -> session -> set_flashdata('flash_message', $this -> Common_model -> admin_flash_message('success', 'Car updated successfully'));
					redirect('administrator/car/view_all_car');
				}

			}//If - Form Submission End

			//Get Groups
			$data['car'] = $this -> mongo_db -> db -> car -> find(array('_id' => new MongoId($id)));
			$data['category'] = $this -> mongo_db -> db -> category -> find();

			$data['message_element'] = "administrator/car/edit_car";
			$this -> load -> view('administrator/admin_template', $data);
			//Load View
			//$this->load->view('administrator/car/edit_car', $data);
		} else {
			redirect('administrator/admin/login');
		}
	}

	function delete_car($id) {
		if (check_logged()) {

			$this -> mongo_db -> db -> car -> remove(array('_id' => new MongoId($id)));

			//Notification message
			$this -> session -> set_flashdata('flash_message', $this -> Common_model -> admin_flash_message('success', 'Car deleted successfully'));
			redirect('administrator/car/view_all_car');
		} else {
			redirect('administrator/admin/login');
		}

	}

}
