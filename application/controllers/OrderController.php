<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// The Actual Restaurant Dashboard
class OrderController extends MY_Controller {

	public function __construct ()
	{
		parent::__construct();
	}

	public function add()
	{
		$this->load->view('templates/header.php');
		$this->load->view('order/new.php');
		$this->load->view('templates/footer.php'); 
	}
}
