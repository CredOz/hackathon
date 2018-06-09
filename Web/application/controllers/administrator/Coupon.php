<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Coupon extends CI_Controller {

	public function Coupon() {
		parent::__construct();

		$this -> load -> library('Table');
		$this -> load -> library('Pagination');

		$this -> load -> library('form_validation');

		$this -> load -> helper('form');
		$this -> load -> helper('url');

		$this -> load -> model('Common_model');
		$this -> load -> model('Coupon_model');
		$this -> load -> model('Users_model');

	}

	public function add_coupon()// Add coupon
	{
		if (check_logged()) {

			$data['message_element'] = "administrator/coupon/viewcoupon";
			$this -> load -> view('administrator/admin_template', $data);
		} else {
			redirect('administrator/admin/login');
		}

	}// Add coupon

	public function price_check($str) {
		if ($str < round(get_currency_value_lys('USD', $this -> input -> post('currency'), 10))) {
			$this -> form_validation -> set_message('price_check', 'Coupon Price should be above or equal to ' . $this -> input -> post('currency') . ' ' . round(get_currency_value_lys('USD', $this -> input -> post('currency'), 10)) . '.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function addcoupon() {
		$expire_in = $this -> input -> post('expire_in');

		$data = array('id' => NULL, 'expire_in' => $this -> input -> post('expire_in'), 'price' => $this -> input -> post('price'), 'code' => $this -> input -> post('gencode'), 'send_status' => 0, 'status' => 0);
		$result1 = $this -> mongo_db -> db -> promocode -> insert($data);
		$this -> session -> set_flashdata('flash_message', $this -> Common_model -> admin_flash_message('success', 'Promo Code Added successfully'));
		redirect('administrator/coupon/view_all_coupon');

	}

	function editcoupon($id) {
		if (check_logged()) {

			//Intialize values for library and helpers
			$this -> form_validation -> set_error_delimiters($this -> config -> item('field_error_start_tag'), $this -> config -> item('field_error_end_tag'));

			if ($this -> input -> post('submit'))
			//print_r($this->input->post()); exit;
			{

				$price = $this -> input -> post('coupon_price');
				$expire_in = $this -> input -> post('expirein');

				//Set rules

				$this -> form_validation -> set_rules('coupon_price', 'price', 'required|trim|xss_clean');
				$this -> form_validation -> set_rules('expirein', 'expire_in', 'required|trim|xss_clean');

				if ($this -> form_validation -> run()) {

					//prepare update data
					$updateData = array();
					$updateData['code'] = $this -> input -> post('coupon_code');
					$updateData['price'] = $this -> input -> post('coupon_price');
					$updateData['expire_in'] = $this -> input -> post('expirein');

					$check = $this -> mongo_db -> db -> promocode -> update(array("_id" => new MongoId($id)), array('$set' => $updateData));
					//$check = $this->mongo_db->db->car->update(array("_id"=>new MongoId($id)),array('$set'=>array("category"=>$category)));

					//Notification message
					$this -> session -> set_flashdata('flash_message', $this -> Common_model -> admin_flash_message('success', 'Coupon updated successfully'));
					redirect('administrator/coupon/view_all_coupon');
				}

			}//If - Form Submission End

			// //Get Groups

			$data['promocode'] = $this -> mongo_db -> db -> promocode -> find(array('_id' => new MongoId($id)));

			//Load View
			//$data['category']	= $category;
			$data['message_element'] = "administrator/coupon/edit_coupon";
			//  print_r($data);
			$this -> load -> view('administrator/admin_template', $data);

		} else {
			redirect('administrator/admin/login');
		}
	}

	function delete_coupon($id) {

		if (check_logged()) {
			$this -> mongo_db -> db -> promocode -> remove(array('_id' => new MongoId($id)));

			//Notification message
			$this -> session -> set_flashdata('flash_message', $this -> Common_model -> admin_flash_message('success', 'Coupon deleted successfully'));
			redirect('administrator/coupon/view_all_coupon');
		} else {
			redirect('administrator/admin/login');
		}
	}

	public function view_all() {
		$this -> load -> model('coupon_model');
		$data['coupon'] = $this -> coupon_model -> get_coupon();
		//$data['row']    = $this->db->get_where('coupon', array('id' => '1'))->row();
		$data['message_element'] = "administrator/coupon/view_coupon_table";
		$this -> load -> view('administrator/admin_template', $data);
	}// View All coupon

	public function view_all_coupon() {
		if (check_logged()) {
			$promo = $this -> mongo_db -> db -> promocode -> find();

			if ($promo -> hasNext()) {

				foreach ($promo as $promocode) {
					$expire_in = $promocode['expire_in'];
					$price = $promocode['price'];
					$gencode = $promocode['code'];

					//$data['promocode']	= $promo;
					$data['expire_in'] = $expire_in;
					$data['price'] = $price;
					$data['code'] = $gencode;

				}
			}

			$data['message_element'] = "administrator/coupon/view_coupon_table";
			$this -> load -> view('administrator/admin_template', $data);
		} else {
			redirect('administrator/admin/login');
		}
	}

	public function get_coupon() {

		$promo = $this -> mongo_db -> db -> promocode -> find();

		if ($promo -> hasNext()) {

			foreach ($promo as $promocode) {

				$price = $promocode['price'];
				$gencode = $promocode['code'];

			}
			echo '[{"status":"Success","code":"' . $gencode . '","price":"' . $price . '"}]';
		}

	}

	public function edit_coupon() {
		$this -> load -> model('coupon_model');
		//Get id of the category
		//Intialize values for library and helpers
		$this -> form_validation -> set_error_delimiters($this -> config -> item('field_error_start_tag'), $this -> config -> item('field_error_end_tag'));
		if ($this -> input -> post('submit')) {
			$check_data = $this -> db -> where('id', $this -> uri -> segment(4)) -> get('coupon');
			if ($check_data -> num_rows() == 0) {
				$this -> session -> set_flashdata('flash_message', $this -> Common_model -> admin_flash_message('error', translate_admin('This coupon already deleted.')));
				redirect_admin('coupon/view_all_coupon');
			}
			//Set rules
			$this -> form_validation -> set_rules('expirein', 'Expire In', 'required|trim|xss_clean');
			$this -> form_validation -> set_rules('coupon_price', 'Coupon Price', 'required|numeric|trim|xss_clean|callback_price_check');
			if ($this -> form_validation -> run()) {
				//prepare update data
				$updateData = array();
				$updateData['expirein'] = strtotime(str_replace('/', '-', $this -> input -> post('expirein')));
				$updateData['coupon_price'] = round($this -> input -> post('coupon_price'));
				$updateData['status'] = 0;
				// 0 -> Active Status & 1-> Expired
				$updateData['currency'] = $this -> input -> post('currency');

				//Edit Faq Category
				$updateKey = array('coupon.id' => $this -> uri -> segment(4));
				$id = $this -> uri -> segment(4);
				if ($updateData['coupon_price'] > 0 && $updateData['coupon_price'] < 60001) {
					$this -> coupon_model -> updatecoupon($updateKey, $updateData);

					$this -> db -> where('used_coupon_code', $this -> input -> post('coupon_code')) -> update('coupon_users', array('status' => 1));
					//Notification message
					$this -> session -> set_flashdata('flash_message', $this -> Common_model -> admin_flash_message('success', translate_admin('Coupon updated successfully')));
				} else if ($updateData['coupon_price'] > 60000) {
					$this -> session -> set_flashdata('flash_message', $this -> Common_model -> admin_flash_message('error', translate_admin('Your price is too long. The maximum is $60000.')));
					redirect_admin('coupon/edit_coupon/' . $id);
				} else {
					$this -> session -> set_flashdata('flash_message', $this -> Common_model -> admin_flash_message('error', translate_admin('Please give the valid amount.')));
					redirect_admin('coupon/edit_coupon/' . $id);
				}
				redirect_admin('coupon/view_all_coupon');
			}
		}
		$check_status = $this -> db -> where('id', $this -> uri -> segment(4)) -> get('coupon') -> row() -> status;
		if ($check_status == 1) {
			$this -> session -> set_flashdata('flash_message', $this -> Common_model -> admin_flash_message('error', translate_admin("Sorry! you're not able to edit expired coupon.")));
			redirect_admin('coupon/view_all_coupon');
		}

		//If - Form Submission End
		//Set Condition To Fetch The Faq Category
		$condition = array('coupon.id' => $this -> uri -> segment(4));
		//Get Groups
		$data['coupon'] = $this -> coupon_model -> get_coupon($condition);

		if ($data['coupon'] -> num_rows() == 0) {
			$this -> session -> set_flashdata('flash_message', $this -> Common_model -> admin_flash_message('error', translate_admin('This coupon already deleted.')));
			redirect_admin('coupon/view_all_coupon');
		}
		$data['currencies'] = $this -> Common_model -> getTableData('currency', array('status' => 1));
		//Load View
		$data['message_element'] = "administrator/coupon/edit_coupon";
		$this -> load -> view('administrator/admin_template', $data);
	} // edit_coupon

} // Class
?>