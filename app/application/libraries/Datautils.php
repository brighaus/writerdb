<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Datautils {

/* Takes the data set returned by a couch design query,
 * strips the extra data and returns the result set
 * as an array of objects
 */
	public function get_rows($couch_view_result)
	{
		$returnme = array();
		foreach($couch_view_result as $itm) {
			if(is_array($itm)) {
				$returnme = $itm;
				break;
			}
		}
		return $returnme;
	}
}

/* End of file Pageloader.php */