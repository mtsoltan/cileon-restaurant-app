<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RootController extends MY_Controller
{

  protected $topnavItems = [
    ['route' => 'root/migrate', 'title' => 'page_title_root_migrate', 'permission' => 'root', 'active' => 'root/migrate', 'class' => 'btn-migrate'],
  ];

  public function migrate()
  {
    $this->requiresPermission('root');
    $this->load->library('migration'); // Uncomment this line whenever you want to migrate.
    if ($this->input->is_ajax_request()) {
      return $this->respondWithJson(['success' => 'true']);
    } else {
      $this->addFlash($this->lang->line('notice_migration_200'));
      return $this->redirect('root/sql');
    }
    return $this->respondWithView('root/sql', [
      'title' => $this->lang->line('page_title_root_sql')
    ]);
  }

  public function sql()
  {
    $this->requiresPermission('root');
    return $this->respondWithView('root/sql', [
      'title' => $this->lang->line('page_title_root_sql'),
    ]);
  }

  public function handleSql()
  {
    $this->requiresPermission('root');
    // Initialize defaults.
    $result = false;

    // Setting the form validation rules
    $this->form_validation->set_rules('sql', $this->lang->line('form_field_sql'), 'required');

    // Rules are applied and accepted
    if ($this->form_validation->run()) {
      // Execute the SQL. There's no need to make a model specifically for this.
      if ($sql = $this->input->post('sql')) {
        $result = $this->db->query($sql);
        $this->addFlashNow($this->lang->line('notice_sql_200'));
      }
    }

    return $this->respondWithView('root/sql', [
      'title' => $this->lang->line('page_title_root_sql'),
      'result' => isset($result) ? $result : '#',
    ]);
  }
}
