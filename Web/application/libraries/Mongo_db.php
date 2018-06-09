<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mongo_db
{
	
		private $debug;
	private $write_concerns;
	private $journal;
	private $selects = array();
	private $updates = array();
	private $wheres	= array();
	private $limit	= 999999;
	private $offset	= 0;
	private $sorts	= array();
	private $return_as = 'array';
	public $benchmark = array();
	public function __construct()
	{

	//Check mongodb is installed in your server otherwise display an error
	if ( ! class_exists('Mongo') && ! class_exists('MongoClient'))
		{
			show_error("The MongoDB PECL extension has not been installed or enabled", 500);
		}

			//get instance of CI class
			if (function_exists('get_instance'))
			{
			$this->_ci = get_instance();
			}

			else
			{
			$this->_ci = NULL;
			}

//load the config file which we have created in 'config' directory
$this->_ci->load->config('mongodb');

$config='default';
// Fetch Mongo server and database configuration from config file which we have created in 'config' directory
$config_data = $this->_ci->config->item($config);

try{
//connect to the mongodb server
$this->mb = new MongoClient('mongodb://'.$config_data['mongo_hostbase']);

//select the mongodb database

$this->db=$this->mb->selectDB($config_data['mongo_database']);


}
catch (MongoConnectionException $exception)
{
//if mongodb is not connect, then display the error
show_error('Unable to connect to Database', 500);
}

}


/**
	* --------------------------------------------------------------------------------
	* Aggregation Operation
	* --------------------------------------------------------------------------------
	*
	* Perform aggregation on mongodb collection
	*
	* @usage : $this->mongo_db->aggregate('foo', $ops = array());
	*/
	public function aggregate($collection, $operation)
	{
        if (empty($collection))
	 	{
	 		show_error("In order to retreive documents from MongoDB, a collection name must be passed", 500);
	 	}
 		
 		if (empty($operation) && !is_array($operation))
	 	{
	 		show_error("Operation must be an array to perform aggregate.", 500);
	 	}

	 	try
	 	{
	 		$documents = $this->db->{$collection}->aggregate($operation);
	 		//$this->_clear();
	 		
	 		if ($this->return_as == 'object')
			{
				return (object)$documents;
			}
			else
			{
				return $documents;
			}
	 	}
	 	catch (MongoResultException $e)
		{
			
			if(isset($this->debug) == TRUE && $this->debug == TRUE)
			{
				show_error("Aggregation operation failed: {$e->getMessage()}", 500);
			}
			else
			{
				show_error("Aggregation operation failed: {$e->getMessage()}", 500);
			}
		}
    }



}
?>
