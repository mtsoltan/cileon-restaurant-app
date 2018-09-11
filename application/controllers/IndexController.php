<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IndexController extends MY_Controller {

  public function index()
  {
    $this->redirectToHome();
  }

  public function dashboard()
  {
    $this->isPrivate(); // TODO: View different dashboards based on permissions.
    return $this->respondWithView('dashboard', [
      'title' => $this->lang->line('page_title_dashboard'),
    ]);
  }
}
