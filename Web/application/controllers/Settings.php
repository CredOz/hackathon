<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Settings extends REST_Controller {
	
		 	public function Settings()
	{
		parent::__Construct();
		$this->load->library('mongo_db');
		$this->load->model('Common_model');

	}
	
	public function getDetails_get()
	{		

		$result = $this->mongo_db->db->settings->find();
		
				$count=$result->count();
				if($count>0)
				{
					foreach($result as $row)
					{
						
  $final['status']="Success";
  
  $final['fb_api_key']=$row['fb_api_key'];	
  $final['fb_secret_key']=$row['fb_secret_key'];
  
  $final['google_api_key']=$row['google_api_key'];
  $final['google_project_id']=$row['google_project_id'];
  $final['Client_ID']=$row['Client_ID'];

  $final['FireBase_Project_ID']=$row[ 'Project_ID'];
  $final['FireBase_WebAPI_Key']=$row['WebAPI_Key'];
  $final['FireBase_DB_URL']=$row['DB_URL'];

  $final['is_live_stripe']=$row['is_live_stripe'];   
  $final['Test_ApiKey']=$row['Test_ApiKey'];
  $final['Test_PublishKey']=$row['Test_PublishKey'];
  $final['Live_ApiKey']=$row['Live_ApiKey'];
  $final['Live_ApiKey']=$row['Live_PublishKey'];
  	
					}	
				}
				
		$result_2 = $this->mongo_db->db->limit->find();
		
				$count_2=$result_2->count();
				if($count_2>0)
				{
					foreach($result_2 as $row_2)
					{
						
  $final['request_duration']=$row_2['request_duration_time'];	
  $final['nearby_distance']=$row_2['nearby_distance'];
  $final['price_per_km']=$row_2['price_per_km'];
  	
					}	
				}
						
				
		print_r(json_encode(array($final),JSON_UNESCAPED_SLASHES));exit;
	}	
		
		public function getCategory_get()
	{
		
		
		 		$category = $this->mongo_db->db->category->find()->sort(array('_id' => 1));
 		if($category->hasNext())
 		{
 			$i=0;
 			foreach ($category  as $row) {

  $final[$i]['categoryname']=$row['categoryname'];	
  $final[$i]['price_km']=$row['price_km'];
  $final[$i]['price_minute']=$row['price_minute'];
  $final[$i]['max_size']=$row['max_size'];
  $final[$i]['price_fare']=$row['price_fare'];
  $final[$i]['prime_time_precentage']=$row[ 'prime_time_precentage'];
  $final[$i]['Logo']=$row['Logo'];
  $final[$i]['Marker']=$row['Marker'];
		$i++;
			}
 		}
				
        $this->response(($final), REST_Controller::HTTP_OK);
	}
			
	
}