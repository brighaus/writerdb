<?php
/**
 * Entry controller, view and manipulate a single entry
 */
class Entry extends CI_Controller {
    var $entry;
    var $page;
    var $data;
    
    public function __construct()
    {
    	parent::__construct();
    	$this->load->library('pageloader');
    	$this->config->load('writerdb_props');
    	$this->db_list_id = $this->config->item('db_list_id');
    }

    private function __load_page () {
    	$this->pageloader->load_page();
    }
    
    private function __add()
    {
        $entry = $this->__clean_data();
    	try {
    		$response = $this->couchdb->storeDoc((object)$entry);
    	}
    	catch (Exception $e) {
    		$this->data['error'] = "Error: ".$e->getMessage()." (errcode=".$e->getCode().")<br/>";
    	}
    }
    
    private function __get_tag_list ($tags) {
    	$ret_val = "";
    	if (!empty($tags)) {
    		$tag_list = implode(', ', $tags);
    		$ret_val =  html_entity_decode($tag_list);   		
    	}
    	return $ret_val;
    }
    
    /* takes a flat list and returns a hashmap, used for type selection options, markup needs k/v */
    private function __tags_to_kv($list) {
    	$ret_val = array();
    	foreach ($list as $kv) {
    		$ret_val[$kv] = $kv;
    	}
    	return $ret_val;
    }
    
    private function __clean_data () {
    	$entry = array();    	 
    	$postArray = $this->input->post(NULL, TRUE);
    	if (!empty($postArray)) {
    		foreach ( $postArray as $sForm => $value ) {
    		if ( get_magic_quotes_gpc() ){
    			$postedValue = htmlspecialchars( stripslashes( $value ) );
    		}
    		else {
    			$postedValue = htmlspecialchars( $value );
    		}
    		$entry[htmlspecialchars($sForm)] = $postedValue;
    	}
    	// set up tags
    	    if (!empty($entry['entryTags'])) {
    	    	// split by comma and trim lead/end whitespace
    	    	$tags = explode(',', $entry['entryTags']);
    	    	$ret_val = array();
    	    	foreach ($tags as $tag){
    	    		array_push($ret_val, trim($tag));
    	    	}
    	    	$entry['entryTags'] = $ret_val;
    	    }

    	    //HACK!! use a generated hash id? probably...rrrgh...
    	    // done, using this for legacy support 
    	    if (!empty($entry['_id'])) {
    	    	$entry['_id'] = str_replace(',', '--', $entry['_id']);
    	    }
    	}
    	return $entry;
    }
    
    /* returns a list of entry types */
    private function __types_from_db () {
    	// get by id
    	$id = $this->db_list_id;
    	$doc = (array)$this->couchdb->getDoc($id);
    	return $doc['entryTypes'];
    }
    
       
    /* index is expecting a post from an editor with a new entry */
    public function index ()
    {
       
       $this->__add();
       if (empty($this->data['error'])) {
       	redirect('entry_list');
       }
       else {
       	$this->page = 'pages/error';
       	$this->__load_page();
       }
       
    }
    
    /**
     * displays an entry
     * @param $entId -- document id
     * */
    public function view ($entId)
    {
    	$id = urldecode($entId);
    	$doc = (array)$this->couchdb->getDoc($id);    	
    	$this->data['doc_id'] = $id;
    	$this->data['title'] = !empty($doc['entryTitle'])? html_entity_decode($doc['entryTitle']): "";
    	$this->data['content'] = html_entity_decode($doc['content']);
    	$this->data['edit_anchor'] = anchor('entry/edit/' . $id, "edit", null /*attributes*/);
    	$this->data['tags'] = $this->__get_tag_list($doc['entryTags']);
    	$this->page = 'view_entry';
    	$this->__load_page();
    }
    
    /**
     * puts an entry into edit mode
     * @param $entId -- document id
     * */
    public function edit ($entId = null) {
    	$this->load->helper('form');
    	$this->data['form_target'] = site_url('entry');
    	$entry_type = null;
    	if ($entId != null) {
    		$id = urldecode($entId);
    		$doc = (array)$this->couchdb->getDoc($id);
    		$this->data['form_target'] = site_url('entry/update');
    		$this->data['doc_id'] = form_hidden('_id', $doc['_id']);
    		$this->data['doc_rev'] = form_hidden('_rev', $doc['_rev']);
    		$this->data['doc_title'] = $doc['entryTitle'];
    		$this->data['entryDate'] = $doc['entryDate'];
    		$this->data['entryTags'] = $this->__get_tag_list($doc['entryTags']);
    		$this->data['content'] = $doc['content'];
    		$entry_type = $doc['entryType'];    		
    	}
    	
    	// types
    	$entry_types = $this->__tags_to_kv($this->__types_from_db());
    	$this->data['entryTypes'] = form_dropdown('entryType', $entry_types, $entry_type);
    	$this->data['type_edit_launch'] =
    	    anchor(site_url('entry_type/edit'),
    	           '[edit types]',
    	           array('style'=>'padding-left:10px'));
    	$this->page = 'pages/editor';
    	$this->__load_page();
    }
    
    // takes post data , cleans it, posts to db 
    public function update () {
    	$this->load->helper('form');
    	$entry = $this->__clean_data();
    	$doc = (array)$this->couchdb->getDoc($entry['_id']);
    	
    	try {
    		$response = $this->couchdb->storeDoc((object)$entry);
    	}
    	catch (Exception $e) {
    		$this->data['error'] = "Error: ".$e->getMessage()." (errcode=".$e->getCode().")<br/>";
    	}
    	if (empty($this->data['error'])) {
    		redirect('entry/view/' . $entry['_id']);
    	}
    	else {
    		$this->page = 'pages/error';
    		$this->__load_page();
    	}
    	
    }


    /**
     * deletes an entry
     * @param $entId -- document id
     * @param $isVerified -- if null, user gets promted to verify delete.
     * */
    public function delete ($entId, $isVerified=null)
    {
    	$id = urldecode($entId);
        // if not verified
	// get doc and display: are you sure you want to delete this?
        // make sure the call to verified delete is really from the user's request in this session, store a hash and reference it in the delete part.
	if ($isVerified === null) {
	$doc = (array)$this->couchdb->getDoc($id);    	
    	$this->data['doc_id'] = $id;
    	$this->data['title'] = !empty($doc['entryTitle'])? html_entity_decode($doc['entryTitle']): "";
    	$this->data['content'] = html_entity_decode($doc['content']);
    	$this->data['edit_anchor'] = anchor('entry/edit/' . $id, "edit", null);
    	$this->data['tags'] = $this->__get_tag_list($doc['entryTags']);
    	$this->data['delete_anchor'] = anchor('entry/delete/' . $id . '/1', "delete", array('style'=>'color: red;'));
        $this->page = 'view_entry';

	}
        
        // if verified
	// TODO: reference session hash, not '1' (see comment above)
        elseif ($isVerified === '1') {
        // delete doc and display result 
	    try{
		$doc = $this->couchdb->getDoc($id);    	
		$this->data['delete_response'] = (array)$this->couchdb->deleteDoc($doc);
            }

	    catch (Exception $e) {
		$this->data['delete_response'] = array('ERROR'=>$e->getMessage());
	    }
	    $this->page = 'delete_result';
        }

    	$this->__load_page();
    }
    
}
?>
