<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Loader Extender, just in case we need to override view loading
class MY_Loader extends CI_Loader
{

  public function view($view, $vars = array(), $return = FALSE)
  {
    return parent::view($view, $vars, $return);
  }
}
