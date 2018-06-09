<?php

	 class Email_model extends CI_Model 
		{
	 
	/**
	 * Constructor 
	 *
	 */
	
	  function Email_model() 
	  {
      parent::__construct();
	  $this->load->model('Rider_model');
   }//Controller End
	
  
	  function sendMail($to,$from_email,$title,$splvars,$code)
	  {
	  	
	  	$emailres = $this->Rider_model->getemailbycode($code);
		
		$this->load->library('email');
		
		$config['protocol'] = 'sendmail';
        $config['charset'] = 'utf-8';
        $config['mailtype'] = 'html';
		$config['wordwrap'] = TRUE;
		$config['validate']=TRUE;
		
        $this->email->set_mailtype("html");	
		
		$copy_resv = "&copy; ".$title." - All rights reserved";
		$logo = base_url()."image_home/logo.png" ; 
		$message = '
					<head>
					<style>
					@media all only screen and (max-width: 768px) { .main_cnt {background-color: white;!important}}
					</style>
					</head>
					<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#333">
    <tbody><tr>
        <td align="center" class="main_cnt" valign="top" bgcolor="#f4f4f4" style="border-radius:6px;background-color:#f4f4f4">
        
        <table border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:600px; margin-top: 54px; padding: 10px 20px;">
	            <tr>
																	<td>
																					<table  height="120" style=" width: 100%;" cellspacing="0" cellpadding="0">
																									<tr>
																													<td align="center" style=" background: white; " >
																													<a href ="'.base_url().'">
																																	<img src="'.$logo.'" />
																																	</a>
																																</td>
																																
																												</tr>
																								</table>
																				</td>
																</tr>
																<tr>
																	<td style="padding: 0 30px 0px 30px; font-size:14px; background: white;">';
					
					$message .= strtr($emailres['msg'], $splvars);			
					
		$message .='<br><br></td>	
                  </tr>
																			<tr>
																			<td>
																			<table cellpadding="0" cellspacing="0" style=" width: 100%; text-align:center;background: #3B5998;height: auto;padding-top: 15px;padding-bottom: 10px;">
																			<tr>
																			<td align="center" style=" padding-bottom: 7px; ">
																			<a style=" margin-right: 2px;" href ="https://www.facebook.com/cogzidel" ><img src="'.base_url().'images/email/fb_email.png" alt="FB" /></a>
																			  <a href ="https://twitter.com/cogzidel"> <img src="'.base_url().'images/email/tw_email.png" alt="TW" /></a>
																			  </td>
																			</tr>													
																			</table>
																			</td>
																			</tr>
																			<tr><td style="font-size: 12px; padding-top: 10px; padding-bottom: 3px;" align="center">'.$copy_resv.'</td></tr>
																			<tr> <td style=" font-size: 12px;padding-bottom: 10px; "  align="center"><a style=" color: #3B5998; text-decoration: none; " target="_blank" href="'.base_url().'">'.$title.'</a></td></tr>
																			</table>
																			
																			</td></tr></tbody></table>';			
		$this->email->initialize($config);
		$this->email->set_newline("\r\n");						
        $this->email->to($to);
        $this->email->from($from_email,$title);
        $this->email->subject($emailres['sub']);
        $this->email->message($message);
		//print_r($message); exit ; 
        $headers = "MIME-Version: 1.0" . "\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\n";
        $headers .= 'From: ' .'Arcane'. '<'.$from_email.'>' . "\r\n";
 
        if ( ! $this->email->send())
			{
			//echo $this->email->print_debugger();
			}
		
	  }
 

	 
}
// End Email_model Class
   
/* End of file Email_model.php */ 
/* Location: ./app/models/Email_model.php */