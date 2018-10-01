<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserController extends MY_Controller
{

  const DEFAULT_PASSLENGTH = 12; // The length of the random generated one-time password.
  const MIN_PASSWORD_LENGTH = 9;
  const RECOVERY_KEY_LENGTH = 32;
  const RECOVERY_TIMEOUT = 1800; // 30 minutes

  protected $topnavItems = [
    [ 'route' => 'user/add', 'title' => 'page_title_user_add', 'permission' => 'user/add', 'active' => 'user/add']
  ];

  // FIXME: edit, handleEdit, delete

  public function login()
  {
    $this->isPublic();
    $this->load->model('LoginAttempt');
    $loginAttemptModel = $this->LoginAttempt;

    $loginAttempts = $loginAttemptModel->getByData(['ip' => $this->input->ip_address()]);
    $attemptsLeft = $loginAttemptModel::MAXIMUM_LOGIN_ATTEMPTS - ($loginAttempts ? count($loginAttempts) : 0);

    // Login view
    return $this->respondWithView('user/login', [
      'title' => $this->lang->line('page_title_login'),
      'style' => '1',
      'sidenav' => false,
      'attempts_left' => $attemptsLeft,
    ]);
  }

  public function handleLogin()
  {
    $this->isPublic();
    /** @var User $userModel */
    $userModel = $this->User;

    $this->load->model('LoginAttempt');
    /** @var LoginAttempt $loginAttemptModel */
    $loginAttemptModel = $this->LoginAttempt;

    // Set required rules
    $this->form_validation->set_rules('username', $this->lang->line('form_field_username'), 'required');
    $this->form_validation->set_rules('password', $this->lang->line('form_field_password'), 'required');

    if(!$this->form_validation->run()) {
      return $this->redirectToHome();
    }

    $ip = $this->input->ip_address();
    $loginAttemptModel->clearOldAttempts();
    $loginAttempts = $loginAttemptModel->getByData(['ip' => $ip]);
    if (($loginAttempts ? count($loginAttempts) : 0) >= $loginAttemptModel::MAXIMUM_LOGIN_ATTEMPTS) {
      $this->addFlash($this->lang->line('notices.login_spam', $loginAttemptModel::BAN_TIME / 3600), 'error');
      return $this->login();
    }

    $username = $this->input->post('username');
    $user = $userModel->validateLogin($username, $this->input->post('password'));

    if(!$user) {
      $uids = $userModel->getByData(['username' => $username]);
      $loginAttemptModel->createEntity([
        'user_id' => count($uids) ? $uids[0]->id : 0,
        'ip' => $ip,
        'browser' => $this->getBrowser(),
        'device' => $this->agent->platform(),
      ])->save();

      $this->addFlash($this->lang->line('notice_login_wrong_403'), 'error');
      return $this->redirectToHome();
    }

    if (!$user->state) {
      $this->addFlash($this->lang->line('notice_login_banned_403'), 'error');
      return $this->redirectToHome();
    }

    if ($user->group_id && !$user->getGroup($this->Group)->state) {
      $this->addFlash($this->lang->line('notice_login_group_403'), 'error');
      return $this->redirectToHome();
    }

    if ($user->force_reset) {
      // We don't want to log the user in yet, so we won't start a session here.
      $user->recovery_key = random_string('alnum', self::RECOVERY_KEY_LENGTH); // Used in place of authentication session.
      $user->force_reset = time() + self::RECOVERY_TIMEOUT; // Using force reset for recovery expiration time.
      $user->save();
      return $this->loadResetView($user->username, $user->recovery_key);
    }

    // Login check approved send to dashboard.
    $user->last_login = time();
    $user->ip = $ip;
    $user->save();
    $loginAttemptModel->deleteByData(['ip' => $ip]);
    $this->session->user_id = $user->id;
    $this->user = $user;
    return $this->redirectToHome();
  }

  private function loadResetView($username, $recoveryKey) {
    return $this->respondWithView('user/reset', [
      'title' => $this->lang->line('page_title_user_reset'),
      'username' => $username, // To be put in a hidden input.
      'recovery_key' => $recoveryKey, // To be put in a hidden input.
      'min_pass_length' => self::MIN_PASSWORD_LENGTH, // For javascript validation.
      'style' => '1',
      'sidenav' => false,
    ]);
  }

  public function handleReset()
  {
    $this->isPublic();
    /** @var User $userModel */
    $userModel = $this->User;

    // Set required rules
    $this->form_validation->set_rules('username', $this->lang->line('form_field_username'), 'required');
    $this->form_validation->set_rules('recovery_key', $this->lang->line('form_field_recovery_key'), 'required');
    $this->form_validation->set_rules('password', $this->lang->line('form_field_password'), 'required|min_length['.self::MIN_PASSWORD_LENGTH.']');
    $this->form_validation->set_rules('password2', $this->lang->line('form_field_password_r'), 'required|matches[password]');

    if(!$this->form_validation->run()) {
      return $this->loadResetView($this->input->post('username'), $this->input->post('recovery_key'));
    }

    $user = $userModel->getByUsername($this->input->post('username'));
    if (!$user->force_reset) {
      $this->addFlash($this->lang->line('notice_no_reset_403'), 'error');
      return $this->redirectToHome();
    }
    if (!hash_equals($user->recovery_key, $this->input->post('recovery_key')) || $user->force_reset < time()) {
      $this->addFlash($this->lang->line('notice_recovery_key_403'), 'error');
      return $this->redirectToHome();
    }

    $user->passhash = hash('sha512', $this->input->post('password'));
    $user->force_reset = 0;
    $user->save();
    return $this->handleLogin();
  }

  public function logout()
  {
    $this->session->user_id = false;
    return $this->redirectToHome();
  }

  public function index()
  {
    $this->isPrivate();
    /** @var User $userModel */
    $userModel = $this->User;
    if ($this->user->hasPermission('user/own') && $this->user->group_id) {
      return $this->respondWithView('user/index', [
        'items' => $userModel->getByData([
          'group_id' => $this->user->group_id,
          'class' => $userModel::CLASSES['user']]),
        'title' => $this->lang->line('page_title_users'),
        'table_layout' => true,
      ]);
    }

    if ($this->user->hasPermission('user/view')) {
      return $this->respondWithView('user/index', [
        'items' => $userModel->getByData(['class >' => $this->user->class]),
        'title' => $this->lang->line('page_title_admin_users'),
        'table_layout' => true,
      ]);
    }

    return $this->showError(403, 'notice_permission_403');
  }

  public function add()
  {
    $this->requiresPermission('user/add');
    $this->topnavBack = 'users';
    $check = ($this->user->hasPermission('user/own') && $this->user->group_id);
    $check = $check || $this->user->hasPermission('admin');
    $validClasses = [];
    for ($i = 3; $i > $this->user->class; $i--) {
      $validClasses[$i] = $this->lang->line('class_' . $i);
    }

    if ($check) {
      return $this->respondWithView('user/form', [
        'title' => $this->lang->line('page_title_user_add'),
        'form_layout' => true,
        'valid_classes' => $validClasses,
      ]);
    }

    return $this->showError(403, 'notice_permission_403');
  }

  public function handleAdd()
  {
    $this->requiresPermission('user/add');
    $errorFlag = true;
    /** @var User $userModel */
    $userModel = $this->User;

    // Validate username and email rules.
    $this->form_validation->set_rules('username',
      $this->lang->line('form_field_username'),
      'trim|required|min_length[5]|max_length[20]|alpha_numeric|is_unique[users.username]');
      $this->form_validation->set_rules('email',
        $this->lang->line('form_field_email'),
        'trim|required|valid_email|is_unique[users.email]');

    // Set the data for user creation.
    $data = [
      'username' => $this->input->post('username'),
      'email' => $this->input->post('email'),
      'state_text' => $this->input->post('state_text'), // Escaped in view.
      'logged_user' => $this->user, // Used for blame_id and state_text
    ];

    // Check whether we're adding as a groupadmin or as an admin.
    if ($this->user->hasPermission('user/own') && $this->user->group_id) {
      $errorFlag = false;
      $data['class'] = $userModel::CLASSES['user'];
      $data['group_id'] = $this->user->group_id;
    }

    if ($this->user->hasPermission('admin')) {
      $errorFlag = false;
      $this->form_validation->set_rules('class',
        $this->lang->line('form_field_class'), 'required|is_natural|greater_than['.$this->user->class.']');
      $this->form_validation->set_rules('group_id',
        $this->lang->line('form_field_group_id'), 'trim|required|is_natural');
      $data['class'] = $this->input->post('class');
      $data['group_id'] = $this->input->post('group_id');
    }

    if ($errorFlag) { // If the user has neither permission.
      $this->addFlashNow($this->lang->line('notice_permission_403'));
      return $this->add();
    }

    if(!$this->form_validation->run()) { // If form did not validte.
      return $this->add();
    }

    // If we've reached this far, then we should add the user.
    $password = random_string('alnum', self::DEFAULT_PASSLENGTH);
    $data['passhash'] = hash('sha512', $password);
    $data['ip'] = '127.0.0.1';
    $user = $userModel->createUser($data);

    if (!$user) {
      $this->addFlashNow($this->lang->line('notice_db_soft_error'));
      return $this->add();
    }

    $this->addFlash($this->lang->line('notice_user_add_200', $password));
    return $this->redirect('users');
  }

  public function view($id) {
    $this->topnavBack = 'users';
    /** @var User $model */
    $model = $this->User;

    /** @var \Entity\User $item */
    $item = $model->getById($id);
    $proceed =
      $item
      && (
        $this->user->hasPermission('user/view')
        || (
          $this->user->hasPermission('user/own')
          &&
          $this->user->group_id == $item->group_id
        )
      );

    if (!$proceed) {
      $this->addFlash($this->lang->line('notice_no_such_x_404'), 'error');
      return $this->redirect('users');
    }

    $group = $item->getGroup($this->Group);

    $items = [
      [$this->lang->line('table_users_username'), $item->username],
      [$this->lang->line('table_users_email'), $item->email],
      [$this->lang->line('table_users_notes'), htmlspecialchars($item->state_text)],
    ];

    if ($this->user->hasPermission('admin')) {
      $items = array_merge($items, [
        [$this->lang->line('table_users_last_login'), $item->login_datetime],
        [$this->lang->line('table_users_ip'), $item->ip],
        [$this->lang->line('table_users_class'), $this->lang->line('class_' . $item->class)],
      ]);
      if ($group) {
        $items[] =
          [$this->lang->line('table_users_group_id'), '<a href="' . ($item->group_id ?
            base_url('group/'.$item->group_id) : '#') . '">' . ($group ? $group->name : '') . '</a>'];
      }
      $items[] = [$this->lang->line('table_create_ts'), $item->create_datetime];
    }

    $items[] = 'buttons';

    return $this->respondWithView('view', [
      'items' => $items,
      'item' => $item,
      'type' => 'user',
      'title' => $this->lang->line('page_title_user'),
    ]);
  }

  protected function getBrowser()
  {
    if ($this->agent->is_browser()) {
      return $this->agent->browser().' '.$this->agent->version();
    } elseif ($this->agent->is_mobile()) {
      return $this->agent->mobile().' '.$this->agent->version();
    }
    return 'ROBOT';
  }
}
