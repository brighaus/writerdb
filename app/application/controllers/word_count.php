<?php
/**
 * Page controller loads a main template page and loads content based
 *  on the arguments passed to the default function
 */
class Word_count extends CI_Controller {

	var $page;
	var $data;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('pageloader');
	}

	public function index ()
	{
		$this->page = 'word_count';
		$this->pageloader->load_page();
	}
}
?>
