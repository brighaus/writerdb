<?php
/**
* Query view controller
*/
class Inquisitor extends CI_Controller {
	var $data;
	var $page = 'inquisitor';
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('pageloader');
		$this->load->library('datautils');
	}
	
	private function __load_page () {
		$this->pageloader->load_page();
	}
	
	
	
	private function __sort_data($sort_type, $sortme)
	{
		switch ($sort_type){
			default: // fall through
			case 'by_number':
				foreach ($sortme as $array) {
					$nums[] = $array['value'];
				}
				array_multisort($nums,SORT_NUMERIC,SORT_DESC,$sortme);
				//var_dump($sortme);
				break;
			case 'by_tag':
				// sort by tag name
				foreach ($sortme as $array) {
					$tags[] = strtolower($array['key']);
				}
				array_multisort($tags,SORT_STRING,$sortme);
				break;
		}
		return $sortme;
	}

	/* index is expecting a post from an editor with a new entry */
	public function index ()
	{      	
	    $this->load->helper('form');
	    $input_opts = array('name'=>'searchTerm');
	    
	    // by tag field set
	    $tag_field_set = form_fieldset("Tell me... what do you know about the tags?");
	    $tag_field_set .= anchor('inquisitor/tags', '[get info by tags]');
	    $tag_field_set .= form_fieldset_close();
	    $this->data['tag_field_set'] = $tag_field_set;
	    
	    // by types field set
	    $type_field_set = form_fieldset("Tell me... what do you know about the types?");
	    $type_field_set .= anchor('inquisitor/types', '[get info by types]');
	    $type_field_set .= form_fieldset_close();
	    $this->data['type_field_set'] = $type_field_set;
	    
	    // search by term
	    $content_form =  form_open('inquisitor/content');
	    $content_form .=  form_fieldset("Do any entires have content that contains this term?");
	    $content_form .= form_input($input_opts);
	    $content_form .= form_submit('contentSubmit', 'interrogate');
	    $content_form .= form_fieldset_close();
	    $content_form .= form_close();
	    $this->data['content_form'] = $content_form; 
		
	    $this->__load_page();	 
	}
	
	/**
	 * show a list of tags used and # of entries that have that tag
	 * */
	public function tags ($sort = NULL) {
		
		try {
			$this->load->helper('url');
			$this->data['byNumLink'] = anchor('inquisitor/tags/by_number',
					                          'Sort by Number',
					                          array("style"=>"margin-right: 15px;"));
			$this->data['byTagLink'] = anchor('inquisitor/tags/by_tag', 'Sort by Tag');
			$this->couchdb->setQueryParameters(array("group"=>"true"));
		    //$tag_blob = json_decode($this->couchdb->getView("entries","tags"), true);
			$tag_blob = (array)$this->couchdb->getView("entries","tags");
			//var_dump($tag_blob['rows']);
			//$tag_display = $tag_blob['rows'];
			$tag_display = array();
			foreach($tag_blob['rows'] as $item) {
				array_push ($tag_display, (array)$item);
			}
		    if ($sort) {
		    	$tag_display = $this->__sort_data($sort, $tag_display);
		    }
		    $this->data['tags'] = $tag_display;			
		} catch (Exception $e) {
			$this->data['error'] = "Error: ".$e->getMessage()." (errcode=".$e->getCode().")<br/>";
		}		
		$this->page = 'tags';	
		$this->__load_page();
	}
	
	/**
	 * returns a list of id:date objects based on tag name
	 * */
	public function by_tag ($key) {
		$tag_parts = explode("%20", $key);
		$new_tag = implode(" ",$tag_parts);
		$this->couchdb->setQueryParameters(array("key"=> "$new_tag"));		
		try {
			$cleanme = $this->couchdb->getView("entries","by_tag");
			$this->data['entries'] = $this->datautils->get_rows($cleanme);
			
		} catch (Exception $e) {
			$this->data['error'] = "Error: ".$e->getMessage()." (errcode=".$e->getCode().")<br/>";
		}
		$this->page = "entries";
		$this->__load_page();
				
	}
	
	// begin by type
	/**
	* show a list of types used and # of entries that have that type
	* */
	public function types () {
		try {
			$this->couchdb->setQueryParameters(array("group"=>"true"));
			$type_blob = (array) $this->couchdb->getView("entries","types");
			$this->data['types'] = $type_blob['rows'];
		} catch (Exception $e) {
			$this->data['error'] = "Error: ".$e->getMessage()." (errcode=".$e->getCode().")<br/>";
		}
		$this->page = 'types';
		$this->__load_page();
	}
	
	/**
	 * returns a list of id:date objects based on type name
	 * */
	public function by_type ($key) {
		$type_parts = explode("%20", $key);
		$new_type = implode(" ",$type_parts);
		$this->couchdb->setQueryParameters(array("key"=> "$new_type"));
		try {
			$cleanme  = $this->couchdb->getView("entries","by_type");
			$this->data['entries'] = $this->datautils->get_rows($cleanme);
		} catch (Exception $e) {
			$this->data['error'] = "Error: ".$e->getMessage()." (errcode=".$e->getCode().")<br/>";
		}
		$this->page = "entries";
		$this->__load_page();
	
	}
	
	// end by type
	
	/**
	* returns a list of id:date objects based on single word serch term
	* */
	public function content () {
		$searchTerm = $this->input->post('searchTerm');
		$this->couchdb->setQueryParameters(array("key"=> "$searchTerm"));
		try {
			$cleanme = $this->couchdb->getView("entries","content");
			if (empty ($cleanme->rows)) {
				$this->data['error'] = "No match for: " . $searchTerm;
			}
			else {
				$this->data['entries'] = $this->datautils->get_rows($cleanme);
			}
		} catch (Exception $e) {
			$this->data['error'] = "Error: ".$e->getMessage()." (errcode=".$e->getCode().")<br/>";
		}
		$this->page = "entries";
		$this->__load_page();	
	}
}
?>