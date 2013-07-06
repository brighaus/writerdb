<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Replicator
{
	
	// property declarations
	private $replicator;
	
	function __construct() {
		$ci =& get_instance();
		$this->replicator = new couchReplicator($ci->couchdb);
	}
		
	/**
	 * database $this->couchdb will be replicated to/from $targ
	 * @param $targ -- remote db url
	 * @param $is_pull -- true to get a pull from the remote resource
	 *
	 **/
	public function replicate ($targ, $is_pull) {
		if ($is_pull) {
			return $this->replicator->from($targ);
		}
		else {
			return $this->replicator->to($targ);
		}
				
	}
}
?>