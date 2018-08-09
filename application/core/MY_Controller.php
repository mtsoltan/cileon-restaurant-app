<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// The Actual Restaurant Controller Base
class MY_Controller extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->lang->load('strings');

    // $this->load->library('migration'); // Uncomment this line whenever you want to migrate.
    //$this->checkLoginOrRedirect();
  }

  // Check if user is logged in
  private function checkLoginOrRedirect ()
  {
    // Send user back to login screen
    if($this->session->userdata('logged_in') == false && current_url() !== base_url() . 'index.php') {
      redirect(base_url());
    }
  }

}
