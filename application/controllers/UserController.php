<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// The Actual Restaurant Dashboard
class UserController extends MY_Controller {


	public function index()
	{
		$this->load->view('templates/header.php');
		$this->load->view('user/index.php');
		$this->load->view('templates/footer.php');
	}
}
