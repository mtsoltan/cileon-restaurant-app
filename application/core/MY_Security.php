<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Security Extender, just in case we need to override CSRF verification.
class MY_Security extends CI_Security
{

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
