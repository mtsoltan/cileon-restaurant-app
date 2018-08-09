<?php

class User extends CI_Model {

  public function loginCheck()
  {
    $loginCheck = $this->db->get_where('users',
    array(
      'username' => $this->input->post('username'),
      'passhash' => hash('sha512', $this->input->post('password'))
    ))->num_rows();
    return $loginCheck == 1;
  }

}


?>
