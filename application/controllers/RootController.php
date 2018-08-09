<?php
defined('BASEPATH') or exit('No direct script access allowed');

// The Actual Restaurant Dashboard
class RootController extends MY_Controller
{

  public function sql()
  {
    $this->load->view('templates/header.php');
    $this->load->view('root/execute.php');
    $this->load->view('templates/footer.php');
  }

  public function handleSql()
  {
    // Initialize defaults.
    $result = false;

    // Setting the form validation rules
    $this->form_validation->set_rules('sql', $this->lang->line('form_field_sql'), 'required');

    // Rules are applied and accepted
    if ($this->form_validation->run()) {
      // Execute the SQL. There's no need to make a model specifically for this.
      if ($sql = set_value('sql'))
        $result = $this->db->query($sql);
    }

    // Load the views
    $this->load->view('templates/header.php');
    $this->load->view('root/execute.php', [
      'result' => $result
    ]);
    $this->load->view('templates/footer.php');
  }
}
