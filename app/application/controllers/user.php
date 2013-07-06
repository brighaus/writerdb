<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function db($dbname = 'writerdb')
	{
		$this->config->load('couchdb');
		// set db name based on the user name passed into the URI
		$this->config->set_item('couch_database', $dbname);
		echo "user specific db now set to: " . $this->config->item('couch_database');
	}
	
	public function index ()
	{
		echo "you found the user page. enter url+ db/username to log into user based database...";
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */