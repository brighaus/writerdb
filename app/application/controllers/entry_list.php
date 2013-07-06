<?php
/**
 * Page controller loads a main template page and loads content based on the arguments passed to the default function
 */
class Entry_list extends CI_Controller {

	var $page;
	var $data;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('pageloader');
		$this->load->library('datautils');
		$this->load->helper('date');
	}

	/* index is expecting a post from an editor with a new entry */
	public function index ()
	{
		$this->data['entries'] = $this->couchdb->getView("entries", "titles");
		if (!empty($this->data['entries'])) {
			$cleanme = (array) $this->data['entries'];
			$sortme = $this->datautils->get_rows($cleanme);
			/* This sorts an array of objects, assuming one member of each object
			 *  has the name 'key' and that its value is a string parseable into a date,
			 *  sorts by that date ascending
			 */
			function key_string_date_sort($a,$b) {
				return strtotime($a->key)>strtotime($b->key);
			}
			usort($sortme, "key_string_date_sort");
			$this->data['entries'] = $sortme;
		}
		$this->page = 'entries';
		$this->pageloader->load_page();
	}
}
?>
