<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Loader Extender, just in case we need to override view loading
class MY_Session extends CI_Session
{
  public function __construct($params = [])
  {
    parent::__construct($params);
  }

  // Whoever wrote CI_Session must have not had this in mind?
  public function __set($key, $value)
  {
    if (is_object($value)) {
      $_SESSION[$key.'__OBJECT'] = true;
      $value = serialize($value);
    }
    $_SESSION[$key] = $value;
  }

  public function __get($key)
  {
    if (isset($_SESSION[$key]))
    {
      if (isset($_SESSION[$key.'__OBJECT'])) {
        return unserialize($_SESSION[$key]);
      }
      return $_SESSION[$key];
    }
    elseif ($key === 'session_id')
    {
      return session_id();
    }

    return NULL;
  }

}
