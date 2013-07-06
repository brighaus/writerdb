<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Pageloader {

    /*
     * takes $cfg and uses it to load the view
     * $cfg['error'] -- any error messages
     * */
	public function load_page()
    {
    	$CI =& get_instance();
    	if (empty($CI->data['error'])) {
    		$CI->data['error'] = "";
    	}
    	$CI->load->view('templates/header', $CI->data);
    	$CI->load->view($CI->page, $CI->data);
    	$CI->load->view('templates/footer', $CI->data);
    }
}

/* End of file Pageloader.php */