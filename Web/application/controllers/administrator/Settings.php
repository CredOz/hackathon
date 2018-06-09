<?php
/**
 * ARCANE Admin Social Controller Class
 *
 * helps to achieve common tasks related to the site like flash message formats,pagination variables.
 *
 * @package		ARCANE
 * @subpackage	Controllers
 * @category	Admin 
 * @author		Cogzidel Product Team
 * @version		
 * @link		http://www.cogzidel.com
  
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('file');
		$this->load->model('Common_model');
		$this->load->model('Rider_model');
		//load validation library
		$this->load->library('form_validation');

		$this->load->model('admin_model');	
		
		$this->load->library('image_lib');		
		$this->load->library('mongo_db');

	}
	
	
	public function index()
	{
		if(check_logged()) 
	    { 
		
			if($this->input->post('update'))
			{
				extract($this->input->post());
				
					if($_FILES["logo"]["name"])
				{
		
    	$config1 = array(
						'allowed_types' => 'jpg|jpeg|gif|png',
						'upload_path' => 'logo',
						'file_name'   => 'logo.png',
						'overwrite'=>TRUE,
						'remove_spaces' => TRUE,
						);
					$this->load->library('upload', $config1);
					if(!$this->upload->do_upload('logo'))
					{
						$error = array('error' => $this->upload->display_errors());

           	$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',"Image folder is not writable!"));
						 redirect('administrator/settings/view_settings/');
					}
					else {
						$upload_data = $this->upload->data(); 				
			      $image_name    = $upload_data['file_name'];
						$data1['image_name']    = $image_name;
			
					}
					
				$data1['image_name']    = $image_name;
	
			}
					else{
							
						$setting_image = $this->mongo_db->db->settings->find();				
							
		if($setting_image->hasNext())
	{
			foreach($setting_image as $documentimage)
				 {
				 $data1['image_name']    = $documentimage['image_name'];	
				 }
	}
					}
					
				$data1['title']=$site_title; 
				 $data1['sitelogan']=$site_slogan;
                 $data1['admin_mail']=$super_admin; 
                 $data1['admin_no']=$super_no;
				 $this->Rider_model->update_settings($data1);
				 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Settings updated successfully'));
				 redirect('administrator/settings');
			}
			else{
				$setting_result= $this->Rider_model->get_settings();
		
	  if($setting_result->hasNext())
	{
					foreach($setting_result as $document)
				 {
				 	 $data['sitetitle']=$document['title'];
					 $data['sitelogan']=$document['sitelogan'];
					 $data['admin_email']=$document['admin_mail'];
					 $data['admin_no']=$document['admin_no'];
				 }
				 

	}
	//$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Settings updated successfully'));
	$data['message_element'] = "administrator/settings";
	$this->load->view('administrator/admin_template', $data);
			}
			
			//}	
			//$this->load->view('administrator/settings');
	}
else
{
	redirect('administrator/admin/login');
}	
}




function view_settings()
{
		if(check_logged()) 
	    {
	    	////////////////////
	    	// $data['setting'] = $this->mongo_db->db->settings->find();
	    	$setting_result= $this->Rider_model->get_settings();
				
		
		        if($setting_result->hasNext())
	            {
			    foreach($setting_result as $document)
				{
				 	$data['id']=$document['_id'];
					$data['title']=$document['title'];
					$data['sitelogan']=$document['sitelogan'];
					$data['admin_mail']=$document['admin_mail'];
					$data['admin_no']=$document['admin_no'];
					$data['image_name']    = $document['image_name'];
					
				 }
				
	             }   
				
				
	    	 $data['message_element'] = "administrator/settings";
			 $this->load->view('administrator/admin_template', $data);
	
		}
		else
		{
		 	redirect('administrator/admin/login');
		}

}


public function change_password()
	{
	if(check_logged()) 
	    { 	
		if($this->input->post('update'))
		{
		  $majorsalt = '';
		  //$id='';
				$newpassword = $this->input->post('new_password');
				$confirm_password = $this->input->post('confirm_password');
				$password     = $this->input->post('old_password'); 
		
				if($password == "" && $newpassword == "" && $confirm_password =="" && $password==$newpassword && $newpassword==$confirm_password){
					
			 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Enter proper details'));
			  redirect('administrator/settings/change_password');		
				}else{
		
				if($newpassword==$confirm_password){	
				$data = $this->mongo_db->db->admin->find(array('password' => md5($password)))->count();
		if($data==1)	{
		$data1=	$this->mongo_db->db->admin->update(array("password"=> $password),array('$set'=>array("password"=>$newpassword)));
		$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Password updated successfully'));	
		 redirect('administrator/settings/change_password');
			}
		else
		{
			 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Your Old Password is wrong'));
			  redirect('administrator/settings/change_password');
		}
		}else {
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Your password is wrong. Enter check password'));	
			 redirect('administrator/settings/change_password');
		}
				}
}
else{
	$data['message_element'] = "administrator/change_password";
		$this->load->view('administrator/admin_template', $data);
}
	}
else{
	redirect('administrator/admin/login');
}

}


public function developer_setting()
{
	if(check_logged()){
	$result=$this->input->post('radio');
	if(isset($result)&&$result!='')
	{
		
		$setting_result=$this->mongo_db->db->settings->find();
		if($setting_result->hasNext())
	{
			foreach($setting_result as $document)
				 {
				 	$settingid=$document['_id'];
    
				 }
	$this->mongo_db->db->settings->update(array("_id"=>new MongoId($settingid)),array('$set'=>array("radio"=>$result)));
	
	}	
	}
	
	
	$result=$this->input->post('driverpushsetting');
	if(isset($result)&&$result!='')
	{
		
		$setting_result=$this->mongo_db->db->settings->find();
		if($setting_result->hasNext())
	{
			foreach($setting_result as $document)
				 {
				 	$settingid=$document['_id'];
    
				 }
	$this->mongo_db->db->settings->update(array("_id"=>new MongoId($settingid)),array('$set'=>array("driverpushsetting"=>$result)));
	
	}	
	}
$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Settings update successfully'));			 
$data['message_element'] = "administrator/view_developer";
$this->load->view('administrator/admin_template', $data);

	}
else{
    redirect('administrator/admin/login');
}
	
}
}


