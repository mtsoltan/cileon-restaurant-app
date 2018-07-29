<?php

class User extends CI_Model {

  public function loginCheck()
  {
    $loginCheck = $this->db->get_where('user',
    array(
      'username' => $this->input->post('username'),
      'password' => hash('sha512', $this->input->post('password'))
    ))->num_rows();
    return $loginCheck;
  }

}


?>
