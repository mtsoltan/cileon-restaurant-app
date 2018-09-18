<?php
defined('BASEPATH') or exit('No direct script access allowed');

// The Actual Restaurant Controller Base
class MY_Lang extends CI_Lang {

  /**
   * Language line
   *
   * Fetches a single line of text from the language array
   *
   * @param string  $line   Language line key
   * @param bool  $log_errors Whether to log an error message if the line is not found
   * @return  string  Translation
   */
  public function line($line, $log_error = true, ...$sprintfArgs)
  {
    if (!count($sprintfArgs) && $log_error === true) return parent::line($line, true);
    return sprintf(parent::line($line), $log_error, ...$sprintfArgs);
  }
}