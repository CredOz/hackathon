<?php
class Request_model extends CI_Model
{
	function Request_model()
	{
		parent::__Construct();
	$this->load->library('mongo_db');
	
	}

 		function find_data($tablename, $data)
	{
		$result = $this->mongo_db->db->$tablename->find($data);
		return $result;
	}
		function insert_data($collection, $data)
	{		
		$result = $this->mongo_db->db->$collection->insert($data);
		return $result;
	}
	
		function update_request($updatekey, $updatevalue)
	{
		$result = $this->mongo_db->db->request->update($updatekey,array('$set'=> $updatevalue));
		return $result;
	}
		function update_trips($updatekey, $updatevalue)
	{
		$result = $this->mongo_db->db->trips->update($updatekey,array('$set'=> $updatevalue));
		return $result;
	}
	


}
?>