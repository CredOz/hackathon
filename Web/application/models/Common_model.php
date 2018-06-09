<?php
/**
 * Dropinn Common_model Class
 ** helps to achieve common tasks related to the site like flash message formats,pagination variables.
 *
 * @package	Dropinn
 * @subpackage	Models
 * @category	Common_model 
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com

 */ 
	 class Common_model extends CI_Model {
	 
		/**
			* Constructor 
			*
			*/
	  function Common_model() 
	  {
			parent::__construct();
	
		 // load model
	 // $this->load->model('page_model');

   }//Controller End
	  

	
	/**
	 * Set Style for the flash messages
	 *
	 * @access	public
	 * @param	string	the type of the flash message
	 * @param	string  flash message 
	 * @return	string	flash message with proper style
	 */
	 function flash_message($type,$message)
	 {
	 	switch($type)
		{
			case 'success':
					$data = '<div id="flash" class="clsShow_Notification"><p class="success"><span>'.$message.'</span></p></div>';
					break;
			case 'error':
					$data = '<div id="flash" class="clsShow_Notification"><p class="error"><span>'.$message.'</span></p></div>';
					break;		
		}
		return $data;
	 }//End of flash_message Function
	 
	
	/**
	 * Set Style for the flash messages in admin section
	 *
	 * @access	public
	 * @param	string	the type of the flash message
	 * @param	string  flash message 
	 * @return	string	flash message with proper style
	 */
	 function admin_flash_message($type,$message)
	 {
	 	switch($type)
		{
			        case 'success':
					$data = '<div class="message"><div id="flash" class="success">'.$message.'</div></div>';
					break;
				case 'error':
					$data = '<div class="message"><div id="flash" class="error" style="background-color:#ffb3b3; border-color:#ffb3b3;">'.$message.'</div></div>';
					break;		
		}
		return $data;
	 }

	
	 function get_duration()
	{
		$tmp=$this->mongo_db->db->durationtime->find(array("id"=>1));
		return $tmp;
		
	}
	 function inserTableData($table='',$data=array())
	 {
		$result = $this->mongo_db->db->$table->insert($data);
		return $result;	
	 }//End of inserTableData Function		
		
   
   public function GetExpireTime() {
		$result = $this->mongo_db->db->expiretime->find();
		return $result;
	}
	
		public function UpdateExpireDuration() {
		extract($this->input->post());
		$this->mongo_db->db->expiretime->update(array('id' => 1 ),array('$set'=>array("expiretime"=> $durationtime)));
	}
	
   	
}
// End Common_model Class
   
/* End of file Common_model.php */ 
/* Location: ./app/models/Common_model.php */
?>
