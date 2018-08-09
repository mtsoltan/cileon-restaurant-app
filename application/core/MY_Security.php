<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Overriding CSRF Error
class MY_Security extends CI_Security {

  public function csrf_show_error()
  {
    // Could error 403 or provide some sort of feedback here.
    return;
  }

  public function csrf_verify()
  {
    // In case I need to dump anything.
    return parent::csrf_verify();
  }

}
