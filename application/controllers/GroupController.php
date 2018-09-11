<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GroupController extends MY_Controller
{
  protected $topnavItems = [
    [ 'route' => 'group/add', 'title' => 'page_title_group_add', 'permission' => 'group/add', 'active' => 'group/add']
  ];

  public function index()
  {
    $this->requiresPermission('group/view');
    /** @var Group $groupModel */
    $groupModel = $this->Group;

    return $this->respondWithView('group/index', [
      'table_layout' => true,
      'items' => $groupModel->getAll(),
      'title' => $this->lang->line('page_title_admin_groups'),
    ]);

  }

  public function editOwn() { // FIXME: Fix this.
    $this->requiresPermission('group/own');
    if (!$this->user->group_id)
      return $this->showError(403, 'notice_permission_403');

    /** @var Group $groupModel */
    $groupModel = $this->Group;

    return $this->respondWithView('group/own', [
      'item' => $this->user->getGroup(),
      'title' => $this->lang->line('page_title_settings'),
    ]);
  }

  private function setValidationRules() {
    // Validate group name.
    $this->form_validation->set_rules('name', $this->lang->line('form_field_groupname'), 'trim|required|min_length[5]|max_length[245]|is_unique[groups.name]');
  }

  public function edit($id) {
    $this->requiresPermission('group/edit');
    $this->topnavBack = 'groups';

    /** @var Group $groupModel */
    $groupModel = $this->Group;

    $group = $groupModel->getById($id);
    if (!$group) {
      $this->addFlash($this->lang->line('notice_no_such_x_404'), 'error');
      return $this->redirect('groups');
    }

    $_POST = array_merge($_POST, $group->getData()); // Dirty fix.

    return $this->respondWithView('group/form', [
      'title' => $this->lang->line('page_title_group_edit', $group->name),
      'form_layout' => true,
      'edit' => true,
    ]);
  }

  public function handleEdit($id)
  {
    $this->requiresPermission('group/edit');

    /** @var Group $groupModel */
    $groupModel = $this->Group;
    $group = $groupModel->getById($id);
    if (!$group) {
      $this->addFlash($this->lang->line('notice_no_such_x_404'), 'error');
      return $this->redirect('groups');
    }

    $this->setValidationRules();

    if(!$this->form_validation->run()) { // If form did not validte.
      return $this->edit($id);
    }

    $group->name = $this->input->post('name');
    $group->save();

    $this->addFlash($this->lang->line('notice_action_200'), 'success');
    return $this->redirect('groups');
  }

  public function add()
  {
    $this->requiresPermission('group/add');
    $this->topnavBack = 'groups';

    return $this->respondWithView('group/form', [
      'title' => $this->lang->line('page_title_group_add'),
      'form_layout' => true,
    ]);
  }

  public function handleAdd()
  {
    $this->requiresPermission('group/add');

    /** @var Group $groupModel */
    $groupModel = $this->Group;

    $this->setValidationRules();

    if(!$this->form_validation->run()) { // If form did not validte.
      return $this->add();
    }

    // Set the data for group creation.
    $group = $groupModel->createEntity([
      'name' => $this->input->post('name'),
      'blame_id' => $this->user->id,
      'state' => $groupModel::STATES['enabled'],
    ])->save();

    if (!$group) {
      $this->addFlashNow($this->lang->line('notice_db_soft_error'), 'error');
      return $this->add();
    }

    $this->addFlash($this->lang->line('notice_action_200'), 'success');
    return $this->redirect('groups');
  }

  public function delete($id)
  {
    $this->requiresPermission('group/edit');

    /** @var Group $groupModel */
    $groupModel = $this->Group;
    $group = $groupModel->getById($id);

    if (!$group) {
      $this->addFlash($this->lang->line('notice_no_such_x_404'), 'error');
      return $this->redirect('groups');
    }

    $this->load->helper('string');
    $group->name = $group->name . '-' . random_string();
    $group->blame_id = $this->user->id;
    $group->state = $groupModel::STATES['disabled'];
    $group->save();

    $this->addFlash($this->lang->line('notice_action_200'), 'success');
    return $this->redirect('groups');
  }
}
