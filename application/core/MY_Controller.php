<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// The Actual Restaurant Dashboard
class MY_Controller extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
		$this->isUserLogged();
  }

  // Check if user is logged in
	private function isUserLogged ()
  {
    // Send user back to login screen
		if($this->session->userdata('logged_in') == false && $this->uri->segment(1) == 'admin') {
      redirect(base_url());
    }
  }

}
