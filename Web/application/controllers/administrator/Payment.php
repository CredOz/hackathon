<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {
	
		public function __construct()
	{

		parent::__construct();
		
		$this->load->model('drivers_model');
		$this->load->model('users_model');
	//	$this->load->model('fb_drivers_model');
	//	$this->load->model('fb_users_model');
		$this->load->model('Common_model');
		$this->load->library('Pagination');
		$this->load->helper('form_helper');
		$this->load->library('form_validation');
		$this->load->library('mongo_db');
		$this->load->library('Table');



		
		
/*	$apisett = $this->mongo_db->db->paymentsetting->find(array( "id"=>'1'));
	foreach($apisett as $apisetting){
	
	$api_user = $apisetting['apiusername'];
	$api_pwd = $apisetting['apipassword'];
	$api_key = $apisetting['apikey'];
	$api_email = $apisetting['email'];
	$paymode = $apisetting['paymenturl'];
	}
	
	
	
		
		$paypal_details = array(
// you can get this from your Paypal account, or from your
// test accounts in Sandbox
'API_username' => $api_user,
'API_signature' => $api_key,
'API_password' => $api_pwd,
// Paypal_ec defaults sandbox status to true
// Change to false if you want to go live and
// update the API credentials above
 'sandbox_status' => $paymode,
);
$this->load->library('paypal_ec', $paypal_details);

//braintree requirement
require_once APPPATH.'libraries/braintree/lib/Braintree.php';
		
		$result = $this->mongo_db->db->paymentsetting->findOne(array('payment_name' => 'CreditCard'));
		
		if($result['is_live'] == 0)
		{
			$paymode = 'sandbox';
		}
		else 
		{
			$paymode = 'production';
		}
		
		Braintree_Configuration::merchantId($result['Merchant_ID']);
    	Braintree_Configuration::publicKey($result['Public_Key']);
    	Braintree_Configuration::privateKey($result['Private_Key']); 
	    Braintree_Configuration::environment($paymode);
		// echo "<pre>";
		// print_r($result);exit;	*/	

 
	}

	public function index()
	{
	  
	  $this->load->views('view_pay');
	}
	
	function paymode($id = '')
	{
		if(check_logged()) 
	    { 
		$check = $this->input->post('check');
		
		if($this->input->post('edit'))
		{
				if(empty($check))
				{
				 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Sorry, You have select atleast one!'));
					redirect('administrator/payment/paymode');
				}
			if(count($check) == 1)
			{ 
		 	$data['payId'] = $check[0];
$pay = $this->mongo_db->db->paymode->find();
foreach($pay as $row){

	if($check[0] == $row['_id'])
					{
						
					$data['result'] = $this->mongo_db->db->paymode->findOne(array( "_id"=>new MongoId($row['_id'])));
					// $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success',' Updated Successfully!'));
						$data['message_element'] = "administrator/payment/list_pay";
		$this->load->view('administrator/admin_template',$data);
					
					//$this->load->view('administrator/payment/list_pay', $data);
					}
}
					
					
			}
			else
			{
			 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Please select any one pay mode to edit!'));
				redirect('administrator/payment/paymode');
			}
		}
		else if($this->input->post('update'))
		{ 
	 	$payId = $this->input->post('payId');
		
		    $data['payId'] = $this->input->post('payId');
			$data['is_premium']        = $this->input->post('is_premium');
			if($this->input->post('percentage_amount') > 99)
			{
				 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Please give the below 100%'));
				
				$data['result'] = $this->mongo_db->db->paymode->findOne();
			    $data['message_element'] = "administrator/payment/list_pay";
		        $this->load->view('administrator/admin_template',$data);
				//echo "<p class='percentage_commission'>Please give the below 100%.</p>";
			}
			elseif($this->input->post('percentage_amount') == 0) {
				 
				 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','invalid! Transaction "Try Again"'));
								 redirect('administrator/payment/paymode/');
				 //$this->load->view('commission');
			}
           else{ 
				
if($this->input->post('fixed_amount') == 0)
{
 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','invalid! Transaction "Try Again"'));
				redirect('administrator/payment/paymode/');
}
else if(strlen($this->input->post('fixed_amount')) > 14)
{
echo "<p class='percentage_commission'>Please give the minimum amount.Maximum digit is 14.</p>";exit;
}
else {
                  /*      $data['is_fixed']          = $this->input->post('is_fixed');
			$data['fixed_amount']      = $this->input->post('fixed_amount');
			$data['percentage_amount'] = $this->input->post('percentage_amount'); */
			
			$this->mongo_db->db->paymode->update(array("_id"=>new MongoId($payId)),array('$set'=>array("is_fixed"=>$this->input->post('is_fixed'),"is_premium"=>$this->input->post('is_premium'),
			             "fixed_amount"=>$this->input->post('fixed_amount'),"percentage_amount"=>$this->input->post('percentage_amount')
			)));
			

			 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Commission Updated Successfully!'));
					redirect('administrator/payment/paymode');
		 
		   
}
			}
			
		}
		else
		{  
				if(isset($id) && $id != '')
				{$pay = $this->mongo_db->db->paymode->find();
				foreach($pay as $row){
					  
							$get = $this->mongo_db->db->paymode->findOne(array( "_id"=>new MongoId($row['_id'])));
							if($get['is_premium'] == 1)
							{
									$change = 0;
							}
							else
							{
									$change = 1;
							}
							
				
	$this->mongo_db->db->paymode->update(array("_id"=>new MongoId($id)),array('$set'=>array("is_premium"=>$change)));
	}
				}
	
	$data['payMode'] = $this->mongo_db->db->paymode->find();
	
		$data['message_element'] = "administrator/payment/commission";
		$this->load->view('administrator/admin_template',$data);
	
			// $this->load->view('administrator/payment/commission', $data);
		}
		}
else{
	redirect('administrator/admin/login');
}	
}
	public function Searchdriver()
	{
		if(check_logged()){
			$start = (int) $this->uri->segment(4,0);
			$row_count = 15;
		
		if($start > 0)
		   $offset			  = ($start-1) * $row_count;
		else
		   $offset			  =  $start * $row_count; 
			$keyword=$this->input->post('keyword');
			if(!$keyword){
			redirect('administrator/payment/transaction');
		}
		
$result[]=$this->drivers_model->getriderid($keyword);
$data['TransactionDetails']=$this->drivers_model->getriderid($result);
$p_config['base_url'] 			=  base_url('administrator/payment/Searchdriver');
		$p_config['uri_segment'] = 4;
		$p_config['num_links'] 		= 5;
		$p_config['total_rows'] 	= $this->drivers_model->get_user();
		$p_config['per_page'] 			= $row_count;	
	$data['pagination']     = 	$this->pagination->initialize($p_config);			
$data['message_element'] = "administrator/payment/transaction";
	$this->load->view('administrator/admin_template', $data);

	
	}
	
	}
	

function details(){
	$id=$this->uri->segment(4);
	$data['results'] = $this->drivers_model->GetAllTransactionByLimit2($id);
	$data['message_element'] = "administrator/management/view_details";
	$this->load->view('administrator/admin_template', $data);
	}


	function transaction(){
		
			 if(check_logged()) 
	    {
	    	
	    if(($this->input->get()))
		{
		extract($this->input->get());
		if(isset($status)){
							if($status == "completed")
				{
				$data['TransactionDetails'] = $this->drivers_model->GetAllTransactionBycomplete();					
				}else if($status == "cancel"){
				$data['TransactionDetails'] = $this->drivers_model->GetAllTransactionBycancel();					
				}else if($status == "today")
				{
				$data['TransactionDetails'] = $this->drivers_model->GetAllTransactionToday();										
				}
				else
				{
				$data['TransactionDetails'] = $this->drivers_model->GetAllTransactionBy();	
				}		
		}else{
							$data['TransactionDetails'] = $this->drivers_model->GetAllTransactionBy();	
		}
	
		}else{			
					$data['TransactionDetails'] = $this->drivers_model->GetAllTransactionBy();				
		}
		// Load view	
	$data['message_element'] = "administrator/management/transaction";
	$this->load->view('administrator/admin_template', $data);
		}
		else{
	redirect('administrator/admin/login');
		}
		
	}

function braintreedetails(){/*

		$id=$this->uri->segment(4);
	$data['results'] = $this->drivers_model->GetAllTransactionByLimit2($id);
$data['merchant_detail'] = $this->drivers_model->GetAllTransactionByLimit($offset, $row_count);	
	
$data['message_element'] = "administrator/payment/sub_merchant";
$this->load->view('administrator/admin_template', $data);
	*/
	
/*
$id=$this->uri->segment(4);
	$data['results'] = $this->drivers_model->get_driver_braintree($id);
	//$data['driverdob']=$this->drivers_model->GetAllBraintreeBydob($id);
	$data['message_element'] = "administrator/payment/braintreedetails";
	$this->load->view('administrator/admin_template', $data);
	*/
/*
					  if(check_logged()) 
			{
						  $start = (int) $this->uri->segment(4,0);
			//var_dump($start);
			$row_count = 15;
			
			if($start > 0)
			   $offset			  = ($start-1) * $row_count;
			else
			  $offset			  =  $start * $row_count; 
		
	
		$id=$this->uri->segment(4);
	($data['merchant_detail'] = $this->drivers_model->GetAllTransactionByLimit($offset, $row_count));
		$data['results'] = $this->drivers_model->GetAllBraintreeByLimit2($id);
		$data['driverdob']=$this->drivers_model->GetAllBraintreeBydob($id);
		$data['message_element'] = "administrator/payment/braintreedetails";
		$this->load->view('administrator/admin_template', $data);
			
			}
			
	else{
		redirect('administrator/admin/login');
	}
	*/

	$id=$this->uri->segment(4);
	
$results=$data['results']=$this->drivers_model->GetAllTransactionByLimit2($id);	


foreach($results as  $result){
		
	 $tripsdriverid=$result['driver_id'];
 	 $data['paymentid']=$paymentid=$result['payment_id'];
	
 	
 	
	/*
				   $id =$data['_id']=$result['_id'];
				 $drivername=$data['drivername']=$result['drivername'];
					 $ridername=$data['ridername']=$result['ridername'];
					 $status=$data['status']=$result['status'];
					 $tripid=$data['tripid']=$result['tripid'];
					$amount=$data['tollamount']=$result['tollamount'];
			 $hostamount=$data['hostamount']=$result['hostamount'];
			  $amount=$data['amount']=$result['amount'];
	*/
		
		}	

		
		$data['acc']=$this->drivers_model->account($tripsdriverid);
		
	
//var_dump($result);
//var_dump($data['value']=array_push($result,'s'));
//print_r($result);echo $acc;
//$acc=array($result,$acc);
//$s=array_push($result,$acc);



		//var_dump($data['r']=array_push($result,'s'));


//$data['results']=array_push($result,$acc);


//echo $this->input->post('driveridfromview');

/*
if($this->input->post('driveridfromview'))
echo $tripsdriverid=$this->input->post('driveridfromview');
$acc=$this->drivers_model->account($tripsdriverid);	

foreach($acc as $a){
echo 	 $a['accountno'];
	
}
*/





//print_r($dataresult);

/*

 
foreach($dataresult as $d){
	echo $d['driver_id'];
	
} 
*/
/*
foreach($dataresult as $d){
	echo $d['acc'];
	
	
}
*/

		
	$data['message_element'] = "administrator/payment/braintreedetails";
	$this->load->view('administrator/admin_template', $data);
	
			
}

function toBraintree(){
	
$payment= $this->input->post('payment_id');
$tripid= $this->input->post('test_id');

if($this->input->post('payviapaypal') == "Pay Using Cash"){
	$this->mongo_db->db->trips->update(array("_id"=>new MongoId($tripid)),array('$set'=>array("status"=>"Paid")));
	$this->load->view('administrator/payment/success');
}else if($this->input->post('payviapaypal') == "Pay Using PayPal"){
	//$this->load->view('administrator/payment/fail');
	$this->toPay(); 
}
else{
		
	$result = Braintree_Transaction::submitForSettlement($payment);
	
	if ($result->success) {
			
		 //$this->session->set_userdata('paid_to','host');
		//update
		//$this->mongo_db->db->trips->update(array("_id"=>new MongoId($resultid)),array('$set'=>array("status"=>'Paid')));
	
    $this->mongo_db->db->trips->update(array("_id"=>new MongoId($tripid)),array('$set'=>array("status"=>"Paid")));
	$this->load->view('administrator/payment/success');	
  
	}
	 else {
  
	$this->load->view('administrator/payment/fail'); 
	}
 
 
	}
}


function credit_card(){
		$id=$this->uri->segment(4);
	$data['results'] = $this->drivers_model->GetAllTransactionByLimit2($id);
	
	
$data['message_element'] = "administrator/payment/credit_card";
$this->load->view('administrator/admin_template', $data);
	
}

function manage_gateway(){

//$d=$this->input->post();
//var_dump($d);

    if(check_logged()) 
        {
$this->form_validation->set_error_delimiters('<p>', '</p>');

    if($this->input->post('update')) {
        $this->form_validation->set_rules('pe_user','Paypal API username','required|trim|xss_clean');
        $this->form_validation->set_rules('pe_password','Paypal API password','required|trim|xss_clean');
    $this->form_validation->set_rules('pe_key','Paypal API Key','required|trim|xss_clean');
    $this->form_validation->set_rules('paypal_id','Paypal Email id','required|trim|xss_clean');
    $this->form_validation->set_rules('paypal_url','Paypal URL','required|trim|xss_clean'); 
    $this->form_validation->set_rules('paypal_client','Paypal Client Id','required|trim|xss_clean');     

    if($this->form_validation->run()){
    //$this->drivers_model->UpdateDriverDetailsBasedOnId($DriverId);
    
    $Id = 1;
    $this->mongo_db->db->paymentsetting->update(array("_id"=>new MongoId($this->input->post('Id'))),array('$set'=> array("apiusername"=>$this->input->post('pe_user'),"apipassword"=>$this->input->post('pe_password'),
    "apikey"=>$this->input->post('pe_key'), "email"=>$this->input->post('paypal_id'),"clientid"=>$this->input->post('paypal_client'), "paymenturl"=>$this->input->post('paypal_url')
    )));
    
    
    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Payment Detail Updated Successfully'));
    redirect('administrator/payment/manage_gateway');
    }
    else{
    	
 $Id = 1;
    $this->mongo_db->db->paymentsetting->update(array("_id"=>new MongoId($this->input->post('Id'))),array('$set'=> array("apiusername"=>$this->input->post('pe_user'),"apipassword"=>$this->input->post('pe_password'),
    "apikey"=>$this->input->post('pe_key'), "email"=>$this->input->post('paypal_id'),"clientid"=>$this->input->post('paypal_client'), "paymenturl"=>$this->input->post('paypal_url')
    )));
    
    
    //$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Payment Detail field required'));
    //redirect('administrator/payment/manage_gateway');
		
		
    } 
    }
   // $apisettings = $this->mongo_db->db->paymentsetting->find();
      $apisettings = $this->mongo_db->db->paymentsetting->find(array( "id"=>'1'));
        
    
    foreach($apisettings as $apisetting){
        
    $data['apiuser'] = $apisetting['apiusername'];
    $data['apipass'] = $apisetting['apipassword'];
    $data['pe_key'] = $apisetting['apikey'];
    $data['email'] = $apisetting['email'];
    $data['paymenturl'] = $data['paypal_url'] = $apisetting['paymenturl'];
    $data['id'] = $apisetting['_id'];
        $data['clientid'] = $apisetting['clientid'];
}
    $data['message_element'] = "administrator/payment/manage_gateway";
    $this->load->view('administrator/admin_template', $data);
    }
else{
    redirect('administrator/admin/login');
}
}

function braintree_manage_gateway()
{
	if(check_logged()) 
	{
		if($this->input->post('update')) 
		{
			$this->form_validation->set_error_delimiters('<p>', '</p>');
			$this->form_validation->set_rules('merchant_id','Merchant ID','required|trim|xss_clean');
			$this->form_validation->set_rules('privatekey','Private Key','required|trim|xss_clean');
		    $this->form_validation->set_rules('publickey','Public Key','required|trim|xss_clean');
		    $this->form_validation->set_rules('account_id',' Merchant Account ID','required|trim|xss_clean');
		
			if($this->form_validation->run()){
			//$this->drivers_model->UpdateDriverDetailsBasedOnId($DriverId);
			//$Id = 1;
			$this->mongo_db->db->paymentsetting->update(array("_id"=>new MongoId($this->input->post('Id'))),array('$set'=> array("Merchant_ID"=>$this->input->post('merchant_id'),"Private_Key"=>$this->input->post('privatekey'),
			"Public_Key"=>$this->input->post('publickey'), "is_live"=>$this->input->post('is_live'), "merchant_account_ID"=>$this->input->post('account_id'))));
		
		
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Braintree Details Updated Successfully'));
			redirect('administrator/payment/braintree_manage_gateway');
		} 
		}
		$apisettings = $this->mongo_db->db->paymentsetting->find(array( "id"=>'2'));
		$data['apisetting'] = $this->mongo_db->db->paymentsetting->find(array( "id"=>'2'));
		//foreach($apisettings as $apisetting){
	
		
		foreach($apisettings as $apisetting){
	
		$data['merchant_id'] = $apisetting['Merchant_ID'];
		$data['privatekey'] = $apisetting['Private_Key'];
		$data['publickey'] = $apisetting['Public_Key'];
		$data['account_id'] = $apisetting['merchant_account_ID'];
		$data['is_live'] = $apisetting['is_live'];
		$data['id'] = $apisetting['_id'];
		}
	
		
		
		
		
		//}
		$data['message_element'] = "administrator/payment/braintree_manage_gateway";
		$this->load->view('administrator/admin_template', $data);
	}
	else
	{
	redirect('administrator/admin/login');
	}
}

function sinch_manage_gateway()
{
	if(check_logged()) 
	{
		if($this->input->post('update')) 
		{
			$this->form_validation->set_error_delimiters('<p>', '</p>');
			$this->form_validation->set_rules('merchant_id','Merchant ID','required|trim|xss_clean');
			$this->form_validation->set_rules('privatekey','Private Key','required|trim|xss_clean');
		    //$this->form_validation->set_rules('publickey','Public Key','required|trim|xss_clean');
		   // $this->form_validation->set_rules('account_id',' Merchant Account ID','required|trim|xss_clean');
		
			if($this->form_validation->run()){
			//$this->drivers_model->UpdateDriverDetailsBasedOnId($DriverId);
			$Id = 3;
			$this->mongo_db->db->paymentsetting->update(array("_id"=>new MongoId($this->input->post('Id'))),array('$set'=> array("APP_Key"=>$this->input->post('merchant_id'),"Secret_key"=>$this->input->post('privatekey'),
			"is_live"=>$this->input->post('is_live'))));
		
		
			$this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Braintree Details Updated Successfully'));
			redirect('administrator/payment/sinch_manage_gateway');
		} 
		}
		$apisettings = $this->mongo_db->db->paymentsetting->find(array( "id"=>'3'));
		$data['apisetting'] = $this->mongo_db->db->paymentsetting->find(array( "id"=>'3'));
		//foreach($apisettings as $apisetting){
	
		
		foreach($apisettings as $apisetting){
	
		$data['merchant_id'] = $apisetting['APP_Key'];
		$data['privatekey'] = $apisetting['Secret_key'];
		/*
		$data['publickey'] = $apisetting['Public_Key'];
				$data['account_id'] = $apisetting['merchant_account_ID'];*/
		
		$data['is_live'] = $apisetting['is_live'];
		$data['id'] = $apisetting['_id'];
		}
	
		
		
		
		
		//}
		$data['message_element'] = "administrator/payment/sinch_manage_gateway";
		$this->load->view('administrator/admin_template', $data);
	}
	else
	{
	redirect('administrator/admin/login');
	}
}


function submission(){
	
extract($this->input->get());

//$userid;
	
$setting = $this->mongo_db->db->settings->find();
	foreach($setting as $sett){
	$site_title = $sett['title'];
	}
	
$admin = $this->mongo_db->db->admin->find();
	foreach($setting as $sett){
	$adminid = $sett['_id'];
	}


	//$amount = $details['amount'];
	//$driverid = $details['driverid'];
	$trip_id = $tripid ;
	
	$detail_trip = $this->mongo_db->db->trips->find(array( "_id"=>new MongoId($trip_id)));
	
	foreach($detail_trip as $details_row) {
	
	$amount = $details_row['amount'];
	$driverid = $details_row['driver_id'];
	
	}

$detail = $this->mongo_db->db->users->find(array( "_id"=>new MongoId( $userid) ));

foreach( $detail as $details) {
	
	$userpaypalmail = $details['paypalemail'];
	$username = $details['firstname'];
	}

$commission = $this->mongo_db->db->paymode->find();
foreach( $commission as $comm){
	$fixedamt = $comm['fixed_amount'];
	$isfixed = $comm['is_fixed'];
	$ispremium = $comm['is_premium'];
	$percentageamt = $comm['percentage_amount'];
}

if($isfixed==1){
	$commissionamount = $fixedamt;
	
} else {
	 $commissionamount = (($amount*$percentageamt)/100);
}
  $hostamount = ($amount-$commissionamount);



$driverdetail = $this->mongo_db->db->drivers->find(array( "_id"=>new MongoId( $driverid) ));

foreach( $driverdetail as $dri){
	$driverpaypalid = $dri['paypalemail'];
	$drivername = $dri['firstname'];
}

$admindetail = $this->mongo_db->db->paymentsetting->find(array( "id"=>'1'));;
foreach( $admindetail as $adm){
	$adminpaypalemail = $adm['email'];
}

if($amount<=$commissionamount) {
	echo '[{"status":"There is commission amount exceeded than travel amount"}]';
	exit;
} else {
	
$this->session->set_userdata('amount',$amount);
$this->session->set_userdata('hostamount',$hostamount);
$this->session->set_userdata('riderid',$userid);
$this->session->set_userdata('driverid',$driverid);
}


$PassingData = 'amount='.$amount.'&hostamount='.$hostamount.'&riderid='.$userid.'&driverid='.$driverid.'&tripid='.$trip_id;



$to_buy = array(
'desc' => 'Purchase from ACME Store',
'type' => 'sale',
'return_URL' => site_url('administrator/payment/paypal_success?'.$PassingData),
// see below have a function for this -- function back()
// whatever you use, make sure the URL is live and can process
// the next steps
'cancel_URL' => site_url('administrator/payment/paypal_cancel?.'.$PassingData), // this goes to this controllers index()
'shipping_amount' => 0,
'get_shipping' => false);

// I am just iterating through $this->product from defined
// above. In a live case, you could be iterating through
// the content of your shopping cart.
//foreach($this->product as $p) {
$temp_product = array(
'name' => $site_title.' Transaction',
'number' => $userid,
'quantity' => 1, // simple example -- fixed to 1
'amount' => $hostamount,
'amount1' => $commissionamount,
'receiver1' => $driverpaypalid,
'receiver2' => $adminpaypalemail,
'req_id1' => $userid,
'req_id2' => $userid+1
 );
 
//print_r($temp_product);exit;
// add product to main $to_buy array
$to_buy['products'][] = $temp_product;
//}
// enquire Paypal API for token
$set_ec_return = $this->paypal_ec->set_ec($to_buy);

if (isset($set_ec_return['ec_status']) && ($set_ec_return['ec_status'] === true)) {
	
	
// redirect to Paypal
 $token = $this->paypal_ec->redirect_to_paypal($set_ec_return['TOKEN']);
 
 echo '[{"token": "'.$token.'","status":"success"}]';
 

// You could detect your visitor's browser and redirect to Paypal's mobile checkout
// if they are on a mobile device. Just add a true as the last parameter. It defaults
// to false
//$this->paypal_ec->redirect_to_paypal( $set_ec_return['TOKEN'], true);
} else {
	print_r($set_ec_return);
	if($set_ec_return['L_LONGMESSAGE0'] == 'Security header is not valid')
	{
		
	}
//$this->_error($set_ec_return);
}	
}

function paypal_success()
{
$token = $_GET['token'];
$payer_id = $_GET['PayerID'];
// GetExpressCheckoutDetails
$get_ec_return = $this->paypal_ec->get_ec($token);
if (isset($get_ec_return['ec_status']) && ($get_ec_return['ec_status'] === true)) {
	
// at this point, you have all of the data for the transaction.
// you may want to save the data for future action. what's left to
// do is to collect the money -- you do that by call DoExpressCheckoutPayment
// via $this->paypal_ec->do_ec();
//
// I suggest to save all of the details of the transaction. You get all that
// in $get_ec_return array
//$currency_code = $this->session->userdata('currency_code_payment');
//print_r($get_ec_return) ; exit ; 
$ec_details = array(
'token' => $token,
'payer_id' => $payer_id,
'amount' => $get_ec_return['PAYMENTREQUEST_0_AMT'],
//'IPN_URL' => site_url('payments/ipn'),
// in case you want to log the IPN, and you
// may have to in case of Pending transaction
'amount1' => $get_ec_return['PAYMENTREQUEST_1_AMT'],
'receiver1' => $get_ec_return['PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID'],
'receiver2' => $get_ec_return['PAYMENTREQUEST_1_SELLERPAYPALACCOUNTID'],
'req_id1' => $get_ec_return['PAYMENTREQUEST_0_PAYMENTREQUESTID'],
'req_id2' => $get_ec_return['PAYMENTREQUEST_1_PAYMENTREQUESTID'],

'type' => 'sale');

// DoExpressCheckoutPayment
$do_ec_return = $this->paypal_ec->do_ec($ec_details);

if (isset($do_ec_return['ec_status']) && ($do_ec_return['ec_status'] === true)) {
	$data1['driver_id']=$_GET['driverid'];
		$data1['rider_id']=$_GET['riderid'];
		$data1['amount']=$_GET['amount'];
		$data1['hostamount']=$_GET['hostamount'];
		$data1['status']= "success";
		$data1['created']=time();	
		$tripid = 	$_GET['tripid'];
	///	$this->mongo_db->db->transaction->insert($data1);
		$this->mongo_db->db->trips->update(array("_id"=>new MongoId($tripid)),array('$set'=>$data1));
		
	echo json_encode($do_ec_return);
}
else {
$this->_error($do_ec_return);
}
 } else {
$this->_error($get_ec_return);
}
 }

 function paypal_cancel()
	{
        $data1['driverid']=$_GET['driverid'];
		$data1['riderid']=$_GET['riderid'];
		$data1['amount']=$_GET['amount'];
		$data1['hostamount']=$_GET['hostamount'];
		$data1['status']= "failed";
		$data1['created']=time();
		$tripid = 	$_GET['tripid'];
		//$this->mongo_db->db->transaction->insert($data1);
		$this->mongo_db->db->trips->update(array("_id"=>new MongoId($tripid)),array('$set'=>$data1));
		
		echo '[{"status":"Failed"}]';
	}
	
	
	
function submission1(){
	
extract($this->input->get());

//$userid;
	
$setting = $this->mongo_db->db->settings->find();
	foreach($setting as $sett){
	$site_title = $sett['title'];
	}
	
$admin = $this->mongo_db->db->admin->find();
	foreach($setting as $sett){
	$adminid = $sett['_id'];
	}


	//$amount = $details['amount'];
	//$driverid = $details['driverid'];
	$trip_id = $tripid ;
	
	$detail_trip = $this->mongo_db->db->trips->find(array( "_id"=>new MongoId($trip_id)));
	
	foreach($detail_trip as $details_row) {
	
	$amount = $details_row['amount'];
	$driverid = $details_row['driver_id'];
	
	}

$detail = $this->mongo_db->db->users->find(array( "_id"=>new MongoId( $userid) ));

foreach( $detail as $details) {
	
	$userpaypalmail = $details['paypalemail'];
	$username = $details['firstname'];
	}

$commission = $this->mongo_db->db->paymode->find();
foreach( $commission as $comm){
	$fixedamt = $comm['fixed_amount'];
	$isfixed = $comm['is_fixed'];
	$ispremium = $comm['is_premium'];
	$percentageamt = $comm['percentage_amount'];
}

if($isfixed==1){
	$commissionamount = $fixedamt;
	
} else {
	 $commissionamount = (($amount*$percentageamt)/100);
}
  $hostamount = ($amount-$commissionamount);



$driverdetail = $this->mongo_db->db->drivers->find(array( "_id"=>new MongoId( $driverid) ));

foreach( $driverdetail as $dri){
	$driverpaypalid = $dri['paypalemail'];
	$drivername = $dri['firstname'];
}

$admindetail = $this->mongo_db->db->paymentsetting->find(array( "id"=>'1'));;
foreach( $admindetail as $adm){
	$adminpaypalemail = $adm['email'];
}

if($amount<=$commissionamount) {
	echo '[{"status":"There is commission amount exceeded than travel amount"}]';
	exit;
} else {
	
$this->session->set_userdata('amount',$amount);
$this->session->set_userdata('hostamount',$hostamount);
$this->session->set_userdata('riderid',$userid);
$this->session->set_userdata('driverid',$driverid);
}


$PassingData = 'amount='.$amount.'&hostamount='.$hostamount.'&riderid='.$userid.'&driverid='.$driverid.'&tripid='.$trip_id;



$to_buy = array(
'desc' => 'Purchase from ACME Store',
'type' => 'sale',
'return_URL' => site_url('administrator/payment/paypal_success?'.$PassingData),
// see below have a function for this -- function back()
// whatever you use, make sure the URL is live and can process
// the next steps
'cancel_URL' => site_url('administrator/payment/paypal_cancel?.'.$PassingData), // this goes to this controllers index()
'shipping_amount' => 0,
'get_shipping' => false);

// I am just iterating through $this->product from defined
// above. In a live case, you could be iterating through
// the content of your shopping cart.
//foreach($this->product as $p) {
$temp_product = array(
'name' => $site_title.' Transaction',
'number' => $userid,
'quantity' => 1, // simple example -- fixed to 1
'amount' => $hostamount,
'amount1' => $commissionamount,
'receiver1' => $driverpaypalid,
'receiver2' => $adminpaypalemail,
'req_id1' => $userid,
'req_id2' => $userid+1
 );
 
//print_r($temp_product);exit;
// add product to main $to_buy array
$to_buy['products'][] = $temp_product;
//}
// enquire Paypal API for token
$set_ec_return = $this->paypal_ec->set_ec($to_buy);

if (isset($set_ec_return['ec_status']) && ($set_ec_return['ec_status'] === true)) {
	
	
// redirect to Paypal
 $token = $this->paypal_ec->redirect_to_paypal($set_ec_return['TOKEN']);
 
 echo '[{"token": "'.$token.'","status":"success"}]';
 

// You could detect your visitor's browser and redirect to Paypal's mobile checkout
// if they are on a mobile device. Just add a true as the last parameter. It defaults
// to false
//$this->paypal_ec->redirect_to_paypal( $set_ec_return['TOKEN'], true);
} else {
	print_r($set_ec_return);
	if($set_ec_return['L_LONGMESSAGE0'] == 'Security header is not valid')
	{
		
	}
//$this->_error($set_ec_return);
}	
}
	
function paypal_success1()
{
	echo "successfully paid";
	
}
 
	function paypal_cancel1()
	{
        $data1['driverid']=$_GET['driverid'];
		$data1['riderid']=$_GET['riderid'];
		$data1['amount']=$_GET['amount'];
		$data1['hostamount']=$_GET['hostamount'];
		$data1['status']= "failed";
		$data1['created']=time();
		$tripid = 	$_GET['tripid'];
		//$this->mongo_db->db->transaction->insert($data1);
		$this->mongo_db->db->trips->update(array("_id"=>new MongoId($tripid)),array('$set'=>$data1));
		
		echo '[{"status":"Failed"}]';
	}
	
	function PPHttpPost($methodName_, $nvpStr_)
{
 global $environment;
 
 $apisettings = $this->mongo_db->db->paymentsetting->find(array( "id"=>'1'));
	foreach($apisettings as $apisetting){
	$id=$apisetting['id'];
		
		if($id=='1')
		{
	$api_user = $apisetting['apiusername'];
	 $api_pwd = $apisetting['apipassword'];
	 $api_key = $apisetting['apikey'];
	$admin_mail = $apisetting['email'];
		}
	
	$paymode="0";
	}
 
	if($paymode == 0)
     $environment = 'sandbox';
	else
	 $environment = '';
	  
 // Set up your API credentials, PayPal end point, and API version.
 // How to obtain API credentials:
 // https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_NVPAPIBasics#id084E30I30RO
 $API_UserName = urlencode($api_user);
 $API_Password = urlencode($api_pwd);
 $API_Signature = urlencode($api_key);
 $API_Endpoint = "https://api-3t.paypal.com/nvp";
 if("sandbox" === $environment || "beta-sandbox" === $environment)
 {
  $API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
 }
 $version = urlencode('51.0');

 // Set the curl parameters.
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
 curl_setopt($ch, CURLOPT_VERBOSE, 1);

 // Turn off the server and peer verification (TrustManager Concept).
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_POST, 1);

 // Set the API operation, version, and API signature in the request.
 $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

 // Set the request as a POST FIELD for curl.
 curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

 // Get response from the server.
 $httpResponse = curl_exec($ch);

 if( !$httpResponse)
 {
 	 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',('PayPal Service is currently Not Available Please Try Again Later.')));
 redirect('/administrator/payment/transaction');
  exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) .')');
 }

 // Extract the response details.
 $httpResponseAr = explode("&", $httpResponse);

 $httpParsedResponseAr = array();
 foreach ($httpResponseAr as $i => $value)
 {
  $tmpAr = explode("=", $value);
  if(sizeof($tmpAr) > 1)
  {
   $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
  }
 }

 if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr))
 {
  exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
 }

 return $httpParsedResponseAr;
}
	
function toPay()
	{
		//extract($this->input->get());
		//echo $tripid;
		//exit;
		//echo "success";
		if($this->input->post('payviapaypal'))
			{
							
    $setting = $this->mongo_db->db->settings->find();
	foreach($setting as $sett){
	$site_title = $sett['title'];
	}
	
     $admin = $this->mongo_db->db->admin->find();
	foreach($setting as $sett){
	$adminid = $sett['_id'];
	}
	$test_id=$this->input->post('test_id');
    //echo $test_id;
	//$amount = $details['amount'];
	//$driverid = $details['driverid'];
	
	//$id = $tripid ;
	$id=$test_id;
	$detail_trip = $this->mongo_db->db->trips->find(array( "_id"=>new MongoId($id)));
	
	foreach($detail_trip as $details_row) {
	
	$amount = $details_row['amount'];
	$driverid = $details_row['driver_id'];
	
	}
	
	$driverdetail = $this->mongo_db->db->drivers->find(array( "_id"=>new MongoId( $driverid) ));
$driverpaypalid = $this->input->post('paypalemail');
foreach( $driverdetail as $dri){
	$driverpaypalid = $dri['paypalemail'];
	$drivername = $dri['firstname'];
}

$admindetail = $this->mongo_db->db->paymentsetting->find(array( "id"=>'1'));
foreach( $admindetail as $adm){
	if($adm['id']=='1')
	{
	$adminpaypalemail = $adm['email'];
	}
}
//newly implement //old amount passed full trip amount //but only pass driver amount only
//new pass driveramount + tollfee amount
$amount=$this->input->post('totalpay');

// echo $amount;
// exit;

		// echo $host_payout_id->email;exit;
// Set request-specific fields.
$currency_code1 = 'USD';
$vEmailSubject = 'PayPal payment';
$emailSubject = urlencode($vEmailSubject);
$receiverType = urlencode($driverpaypalid);
$currency = urlencode($currency_code1); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

// Receivers
// Use '0' for a single receiver. In order to add new ones: (0, 1, 2, 3...)
// Here you can modify to obtain array data from database.

$receivers = array(
  0 => array(
    'receiverEmail' => "$driverpaypalid", 
    'amount' => "$amount",
    'uniqueID' => "55680f067f8b9a120f3c986a", // 13 chars max
    'note' => " payment of commissions")
);
$receiversLenght = count($receivers);

// Add request-specific fields to the request string.
$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=USD";

$receiversArray = array();

for($i = 0; $i < $receiversLenght; $i++)
{
 $receiversArray[$i] = $receivers[$i];
}

foreach($receiversArray as $i => $receiverData)
{
 $receiverEmail = urlencode($receiverData['receiverEmail']);
 $amount = urlencode($receiverData['amount']);
 $uniqueID = urlencode($receiverData['uniqueID']);
 $note = urlencode($receiverData['note']);
 $nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
}
//$this->session->set_userdata('payout_id',$receiverEmail);
// Execute the API operation; see the PPHttpPost function above.
//echo $receiverEmail;echo $nvpStr;

//$httpParsedResponseAr = $this->PPHttpPost('MassPay', $nvpStr);
//echo $httpParsedResponseAr;
//echo $nvpStr;exit;
	try{
		$httpParsedResponseAr = $this->PPHttpPost('MassPay', $nvpStr);
	}
catch(Exception $e)
{
	//echo $e;
 $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error',('PayPal Service is currently Not Available Please Try Again Later.')));
 redirect('/administrator/payment/transaction');
}

if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
{
 $this->session->set_userdata('paid_to','host');
 
 //exit('MassPay Completed Successfully: ' . print_r($httpParsedResponseAr, true));
 //redirect_admin('/administrator/payment/paypal_success1/');
 
 //$data['message_element'] = "administrator/payment/success";
 //echo "success";
    $this->mongo_db->db->trips->update(array("_id"=>new MongoId($id)),array('$set'=>array("status"=>"Paid")));
	$this->load->view('administrator/payment/success');
}


else
{
	$this->session->set_userdata('error_msg',str_replace('%20',' ',$httpParsedResponseAr['L_LONGMESSAGE0']));
 //exit('MassPay failed: ' . print_r($httpParsedResponseAr['L_LONGMESSAGE0'], true));exit;
// echo "fail";
 //redirect_admin('payment/paypal_cancel');
 $this->load->view('administrator/payment/fail');
}
		
	
		/*if($this->input->post('payviapaypal') )
				{
														$amount = $this->input->get_post('hamount');
				}*/
				
				//$data['message_element'] = "administrator/payment/topay";
			//$this->load->view('administrator/admin_template', $data);
		
	}
}
function braintreesubmerchant(){
				
				
			$this->load->library('braintree/Braintree/MerchantAccout/IndividualDetails.php');
			$this->load->library('braintree/Braintree/MerchantAccout/FundingDetails.php');
			$this->load->library('braintree/Braintree/Transaction/CustomerDetails.php');

	$submerchant['id'] =array(
	    
	    'masterMerchantAccountId' => "14ladders_marketplace"
	);
	$merchantAccountParams['bdetails'] =   array(
    
    'firstName' => 'Jane',
    'lastName' => 'Doe',
    'email' => 'jane@14ladders.com',
    'phone' => '5553334444',
    'dateOfBirth' => '1981-11-19',
    'ssn' => '456-45-4567',
    'address' =>       'streetAddress,111 Main St,locality',
      'region' => 'IL',
      'postalCode' => '60622'
    );
 
  $result = Braintree_MerchantAccount::create($merchantAccountParams);
  
  
  $merchantAccountParams['funding'] = array(
      //'destination' => Braintree_MerchantAccount::FUNDING_DESTINATION_BANK,
      'email' => 'funding@blueladders.com',
      'mobilePhone' => '5555555555',
      'accountNumber' => '1123581321',
      //'routingNumber' => '071101307'
    );
	
	$result = Braintree_Transaction::sale(array(
  'merchantAccountId' => 'provider_sub_merchant_account',
  'amount' => '10.00',
  'paymentMethodNonce' => 'nonce-from-the-client',
  'serviceFeeAmount' => "1.00"
));
	
	
}
function send_braintreepayment(){
		
	$result = Braintree_Transaction::sale(array(
     'merchantAccountId' => 'provider_sub_merchant_account',
     'amount' => '10.00',
     'paymentMethodNonce' => 'nonce-from-the-client',
     'serviceFeeAmount' => "1.00"
));
}
function search()
{
		 if(check_logged()) 
	    {
	 	$start = (int) $this->uri->segment(4,0);
		$row_count = 5;
		
		if($start > 0)
		   $offset			  = ($start-1) * $row_count;
		else
		   $offset			  =  $start * $row_count; 
	
	
		$search=$this->input->post('keyword');	
		if(!$search)
		{
			redirect('administrator/payment/transaction');
		}	
			
		$data['TransactionDetails'] = $this->drivers_model->GetAllTransactionByLimit1($search);
	    
		// Pagination config
		$p_config['base_url'] 			= base_url('administrator/payment/search');
		$p_config['uri_segment'] 		= 4;
		$p_config['num_links'] 			= 5;
		$p_config['total_rows'] 		= $this->drivers_model->GetAllTransaction();
		
		$p_config['per_page'] 			= $row_count;
				
		// Init pagination
		$data['pagination']     =$this->pagination->initialize($p_config);		
		
		// Create pagination links
		//$data['pagination']     = $this->pagination->create_links2();
		
		// Load view
	$data['message_element'] = "administrator/payment/transaction";
	$this->load->view('administrator/admin_template', $data);
		}
	}	


}
