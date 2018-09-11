<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Exception Extender, just in case we need to override language presence.
class MY_Exceptions extends CI_Exceptions
{
  public function __construct()
  {
    parent::__construct();
  }
}
