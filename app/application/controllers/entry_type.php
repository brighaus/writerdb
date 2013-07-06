<?php
/**
 * Page controller loads a main template page and loads content based on the arguments passed to the default function
 */
class Entry_type extends CI_Controller {
    var $entry_types;
    var $entry_types_default = "Story Idea, Character, Experience, non prose, Rant, dream, random thought";
    var $db_list_id;
    var $page;
    var $data;
    
    public function __construct()
    {
    	parent::__construct();
    	$this->load->library('pageloader');
    	$this->config->load('writerdb_props');
    	$this->db_list_id = $this->config->item('db_list_id');
    }
    
    // needs to get abstracted into a library       
    private function __clean_data () {
    	$cleaned = array();    	 
    	$postArray = $this->input->post(NULL, TRUE);
    	if (!empty($postArray)) {
    		foreach ( $postArray as $sForm => $value ) {
    		if ( get_magic_quotes_gpc() ){
    			$postedValue = htmlspecialchars( stripslashes( $value ) );
    		}
    		else {
    			$postedValue = htmlspecialchars( $value );
    		}
    		$cleaned[htmlspecialchars($sForm)] = $postedValue;
    	}
    	    
    	}
    	return $cleaned;
    }
    
    private function __types_from_string($str) {
    	$ret_val = array();
    	$types = explode(',', $str);
    	foreach ($types as $type){
    		array_push($ret_val, trim($type));
    	}
    	return $ret_val;
    }
    
    private function __types_to_string($arr) {
    	$ret_val = "";
    	$first_iter = false;
    	if (empty($arr)) {
    		$this->data['error'] = "cannot stringify, got null";
    		return $ret_val;
    	}
    	foreach ($arr as $type) {
    		if ($first_iter) {    			
    			$ret_val .= ", $type";    			
    		} else{
    			$ret_val = $type;
    			$first_iter = true;
    		}
    	}
    	return $ret_val;
    }
    
    // returns an array of types from the post request
    private function __types_from_request () {
    	$ret_val = array();
    	$cleaned = $this->__clean_data();
    	// set up types
    	if (!empty($cleaned['entryTypes'])) {
    		// split by comma and trim lead/end whitespace
    		$ret_val = $this->__types_from_string($cleaned['entryTypes']);
    	}
    	
    	return $ret_val;
    }
    
    private function __types_from_db () {
    	// get by id
    	$id = $this->db_list_id;
    	$doc = (array)$this->couchdb->getDoc($id);
    	return $doc['entryTypes'];
    }
    
       
    /* index is expecting a post from an editor with a new entry */
    public function index ()
    {
    	
    	// view the existing list
       $this->data['entry_types'] = $this->__types_from_db();       
       $this->page = 'entry_type_list';
       $this->pageloader->load_page();
    }
    
    // edit it
    public function edit () {
    	$this->load->helper('form');
    	$types_arr = $this->__types_from_db();
    	$types = ($types_arr !== NULL && !empty($types_arr)) ?
    	$this->__types_to_string($types_arr):
    	$this->entry_types_default;

    	 
    	$input_opts = array('name'=>'entryTypes',
 	                        'rows'=>'5',
 	                        'cols'=>'100');
    	$entry_type_edit_form =  form_open('entry_type/update');
    	$entry_type_edit_form .= form_fieldset("Edit types");
    	$entry_type_edit_form .= form_textarea($input_opts, $types);
    	$entry_type_edit_form .= "<br/>";
    	$entry_type_edit_form .= form_submit('listSubmit', 'update list');
    	$entry_type_edit_form .= form_fieldset_close();
    	$entry_type_edit_form .= form_close();
    	$this->data['entry_type_edit_form'] = $entry_type_edit_form;
    	 
    	$this->page = "entry_type_edit";
    	$this->pageloader->load_page();
    }
    
    public function update () {
        $this->load->helper('form');
    	$entry_types = $this->__types_from_request();
    	 
    	if (!empty($entry_types)) {

    		$doc = (array)$this->couchdb->getDoc($this->db_list_id);
    		$doc['entryTypes'] = $entry_types;    		
    		try {
    			$response = $this->couchdb->storeDoc((object)$doc);
    		}
    		catch (Exception $e) {
    			$this->data['error'] = "Error: ".$e->getMessage()." (errcode=".$e->getCode().")<br/>";
    		}
    		
    	
    	}
    	
    	if (empty($this->data['error'])) {
    		redirect('entry_type');
    	}
    	else {
    		$this->page = 'pages/error';
    		$this->pageloader->load_page();
    	}
    	
    }
            
}
?>
