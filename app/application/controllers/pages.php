<?php
/**
 * Page controller loads a main template page and loads content based on the arguments passed to the default function
 */
class Pages extends CI_Controller {
	var $page;
	//     public function index ($page = 'editor') {
	public function index ($page) {
		if (!empty($page)) {
			$this->load->helper('form');
			$this->load->helper('url');
			if (empty($data['error'])) {
				$data['error'] = "";
			}
			if ( ! file_exists('app/application/views/pages/'.$page.'.php')) {
				// Whoops, we don't have a page for that!
				//show_404();
				$data['error'] = "Could not find: " . $page;
				$this->page = 'not_found';
			}
			else {
				$this->page =  $page;
			}

			$data['form_target'] = site_url('entry');
			$data['title'] = ucfirst($this->page); // Capitalize the first letter
			$data['page'] = $this->page;

			$this->load->view('templates/header', $data);
			$this->load->view('pages/' . $this->page, $data);
			$this->load->view('templates/footer', $data);
		}
			
		else {
			redirect('entry/edit');
		}

	}
}
?>
