<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller {

	public function __construct()
	{

		parent::__construct();
		
		//$this->load->model('drivers_model');
		//$this->load->model('rider_model');
		//$this->load->model('fb_drivers_model');
		$this->load->model('Common_model');
		$this->load->helper('form_helper');
		$this->load->library('form_validation');
		$this->load->library('mongo_db');
	}
	public function index()
	{
	  
	  $this->load->view('administrator/category/view_primetime');
	}
	
		function view_category()
	{

	 if(check_logged()) 
	    {
	
    //$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Category Added successfully'));
$data['message_element'] = "administrator/category/view_add_category";
		$this->load->view('administrator/admin_template',$data);
		}
		 else 
	    {
		  redirect('administrator/admin/login');
	   }	

	}
	
	    function addcategory()
  {
  	 if(check_logged()) 
	    {
  $category = $this->input->post('addcategory'); 
 
 
  
    $category1=trim($category);

if(empty($category1))
			{
			 redirect('administrator/category/view_category');
			}else{
				
					$nul ="NULL";
			$data = array(
							
							'category_name'       => $this->input->post('addcategory'),
							);
		$result1 = $this->mongo_db->db->category->insert($data);
		
		 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Category Added successfully'));
		 redirect('administrator/category/view_all_category');
			}
		}
		 else 
	    {
		  redirect('administrator/admin/login');
	   }	
			
  }
function primecheck()
{

	$fromtime = $this->input->post('fromtime');
    $totime = $this->input->post('totime');
	if($totime<$fromtime)
	{
		 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','please put the large value in totime'));
		redirect('administrator/category/primecheck/');
	}
	else if($totime && $fromtime=='00:00')
	{
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','please put the valid value'));
		redirect('administrator/category/primecheck/');
	}
	
	

else
		{
			$this->primetime();
		}
	}

  
  function primetime()
  {
  	

	$fromtime = $this->input->post('fromtime');
    $totime = $this->input->post('totime');
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Prime time Updated Successfully'));
	if(isset($totime )&& isset($fromtime))
	
	{
		if($totime!='')	
	
	{
			$setting_result=$this->mongo_db->db->primetime->find();
		if($setting_result->hasNext())
	{
			foreach($setting_result as $document)
				 {
				 	$settingid=$document['_id'];
    
				 }

	$this->mongo_db->db->primetime->update(array("_id"=>new MongoId($settingid)),array('$set'=>array("fromtime"=>$fromtime)));
    $this->mongo_db->db->primetime->update(array("_id"=>new MongoId($settingid)),array('$set'=>array("totime"=>$totime)));
	
	}
	}
  }
	
  	$data['message_element'] = "administrator/category/view_primetime";
    $this->load->view('administrator/admin_template',$data);
  
  }
  
  
	
	
  
  
  
  function view_all_category()
	{
		if(check_logged()) 
	    {	
 		$category = $this->mongo_db->db->category->find()->sort(array('_id' => 1));
 		if($category->hasNext())
 		{
 			foreach ($category  as $cat) {
			 $catname=$cat['categoryname'];
			  $data['category']	= $category;
			}
 		}
 
	$data['message_element'] = "administrator/category/view_category";

		$this->load->view('administrator/admin_template',$data);
		}
		else{
			 redirect('administrator/admin/login');
		}
	}
	
	function imagupload($file_element_name,$id)
	{

	$config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/arcane category icons'; //Set the upload path
	$config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|PNG|JPG'; // Set image type
	$this->load->library('upload', $config);
	
	$this->upload->initialize($config);
		if(!$this->upload->do_upload($file_element_name))
		{
		$status = 'error';
		$error = $this->upload->display_errors();
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',$error));
		redirect('administrator/category/editcategory/'.$id);	
		}
		else
		{
		$data = $this->upload->data(); // Get the uploaded file information
		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$config['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/arcane category icons/'.$data['raw_name'].$data['file_ext'];
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
        $config['width']     = 125;
	    $config['height']   = 125;
	    $this->image_lib->initialize($config);
	    $this->image_lib->resize();
		$image_name = $data['raw_name'].$data['file_ext'];
		return $image_name ;
		}
	}		
 function editcategory($id)
	{
				
if(check_logged()) 
	    {	
		
		//Intialize values for library and helpers	
		$this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
	
		if($this->input->post('submit'))
		{			
           	//Set rules
			$this->form_validation->set_rules('categoryname','categoryname','required|trim|xss_clean');
			$category = $this->input->post('categoryname');
						
			if($category != "")
			{		  
		  $updateData                  	  	= array();	
		  $updateData['categoryname'] 		    = $this->input->post('categoryname');
		  $updateData['price_km'] 		    = $this->input->post('price_km');
		  $updateData['price_minute']  		    = $this->input->post('price_minute');
		  $updateData['max_size']		    = $this->input->post('max_size');
		  $updateData['price_fare']		    = $this->input->post('price_fare');
		  $updateData['prime_time_precentage'] 		    = $this->input->post('prime_time_precentage');
	
	if($_FILES["uploadedimage"]["tmp_name"])
	{
		$file_element_name_image = "uploadedimage";
		 $CI = &get_instance();
		 $updateData['Logo'] 		    =  $CI->imagupload($file_element_name_image,$id);
	}	
	if($_FILES["uploadedmarker"]["tmp_name"])
	{
		 $file_element_name_marker = "uploadedmarker";
		 $CI = &get_instance();
		 $updateData['Marker'] 		    =  $CI->imagupload($file_element_name_marker,$id);
	} 
	$this->mongo_db->db->category->update(array("_id"=>new MongoId($id)),array('$set'=>$updateData)); 
	//Notification message
	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Category updated successfully'));
	redirect('administrator/category/view_all_category');
		 	} 
		
		} //If - Form Submission End

			//Load View	
		$data['category']	=	$this->mongo_db->db->category->find(array('_id' => new MongoId($id)));
		$data['message_element'] = "administrator/category/edit_category";
		$this->load->view('administrator/admin_template',$data);
		
		}
else{
			 redirect('administrator/admin/login');
		}
	}
		
function delete_category($id)
	{

if(check_logged()) 
	    {
	    	
		$this->mongo_db->db->category->remove(array('_id' => new MongoId($id)));

		//Notification message
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Category deleted successfully'));
		redirect('administrator/category/view_all_category');
	
		}
		else{
			 redirect('administrator/admin/login');
		}
	}
	 
	
	
}
