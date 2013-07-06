<?PHP

require_once APPPATH."/libraries/couchphp/couch.php";
require_once APPPATH."/libraries/couchphp/couchClient.php";
require_once APPPATH."/libraries/couchphp/couchDocument.php";
require_once APPPATH."/libraries/couchphp/couchReplicator.php";

class couchdb extends couchClient {

	function __construct() {
		$ci =& get_instance();
		$ci->config->load("couchdb");
		parent::__construct($ci->config->item("couch_dsn"), $ci->config->item("couch_database"));
	}

}
