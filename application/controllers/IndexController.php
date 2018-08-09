<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IndexController extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User');
	}

	public function index()
	{
		// Set required rules
		$this->form_validation->set_rules('username', $this->lang->line('form_field_username'), 'required');
		$this->form_validation->set_rules('password', $this->lang->line('form_field_password'), 'required');

		// Send the post to the loginCheck (rules applied)
		if($this->form_validation->run() == TRUE) {

			if($this->User->loginCheck()) {
				// Login check approved send to dashboard
				$this->session->set_userdata('logged_in', true);
				redirect(base_url('admin/dashboard'));
			}
		}

		// Login view
		$this->load->view('login');
	}
}
