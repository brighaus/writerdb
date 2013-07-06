<?php
class Replicate extends CI_Controller {
	var $page;
	var $data;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('pageloader');
	}

	private function __load_page () {
		$this->pageloader->load_page();
	}

	public function index()
	{
		$this->load->helper('url');
		$this->page = "replicate.php";
		$this->data['push_anchor'] = anchor('replicate/push', '[push]');
		$this->data['pull_anchor'] = anchor('replicate/pull', '[pull]');
		$this->page = 'replicate';
		$this->__load_page();
	}

	/**
	 * Takes current state of this local db and pushes to remote
	 * */
	public function push () {
		$this->data['res'] = "executing push...<br/>";
		$this->load->library('couchphp/replicator');
		$targ = $this->config->item('couch_database_remote');
		$result = (array)$this->replicator->replicate($targ, false);
		foreach ($result as $item) {
			$this->data['res'] .= $item . "<br/>";
		}
		$this->data['res']  .= "Replication complete<br/>";
		$this->page = 'replicate_result';
		$this->__load_page();
	}
	 
	/**
	 * Takes current state of remote db and pulls it to local
	 * */
	public function pull () {
		$this->data['res'] = "executing pull...<br/>";
		$this->load->library('couchphp/replicator');
		$targ = $this->config->item('couch_database_remote');
		$result = (array)$this->replicator->replicate($targ, true);
		foreach ($result as $item) {
			$this->data['res']  .= $item . "<br/>";
		}
		$this->data['res'] .= "Replication complete<br/>";
		$this->page = 'replicate_result';
		$this->__load_page();
	}
}
 
?>