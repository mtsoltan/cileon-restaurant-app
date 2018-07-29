<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// The Actual Restaurant Dashboard
class SettingsController extends MY_Controller {

	public function __construct ()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('templates/header.php');
		$this->load->view('settings/dashboard.php');
		$this->load->view('templates/footer.php');
	}

}
