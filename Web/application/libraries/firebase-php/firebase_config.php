<?php 

			$CI =& get_instance();
			require_once APPPATH.'libraries/firebase-php/vendor/autoload.php';
			require_once APPPATH.'libraries/firebase-php/src/Firebase.php';
			require_once APPPATH.'libraries/firebase-php/src/Configuration.php';
			$config = new Kreait\Firebase\Configuration;
			$result = $CI->mongo_db->db->settings->find();
		    foreach($result as $res) {}
            $config->setAuthConfigFile(FCPATH.'firebase_auth/auth.json');
			$firebase = new Kreait\Firebase\Firebase($res['DB_URL'], $config);
 ?>