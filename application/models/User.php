<?php

class User extends MY_Model {

  const PERMISSIONS = [
    'product/view'  => 0x00000001,
    'product/edit'  => 0x00000002, // Edit, Delete
    'product/add'   => 0x00000004,
    'order/view'    => 0x00000008,
    'order/edit'    => 0x00000010, // Edit, Delete
    'order/add'     => 0x00000020,
    'customer/view' => 0x00000040,
    'customer/edit' => 0x00000080, // Edit, Delete
    'customer/add'  => 0x00000100,
    'group/view'    => 0x00000200,
    'group/edit'    => 0x00000400, // Edit, Delete
    'group/add'     => 0x00000800,
    'group/own'     => 0x00001000, // View and edit own group as a groupadmin
    'user/view'     => 0x00002000,
    'user/edit'     => 0x00004000, // Only gives right to edit / ban users below own user class
    'user/add'      => 0x00008000, // Only gives right to add users below own user class
    'user/own'      => 0x00010000, // View and edit own group's users as a groupadmin
    'financial/own' => 0x00020000, // Permission to view budget, etc. of own group.
    'override'      => 0x10000000, // This permission allows customer, order, and product permissions to override group closure.
    'admin'         => 0x20000000, // Site safe tools and statistics.
    'root'          => 0x40000000, // Site dangerous tools
  ];

  const CLASSES = [
    'root' => 0,
    'admin' => 1,
    'groupadmin' => 2,
    'user' => 3
  ];

  const DEFAULT_CLASS_PERMISSIONS = [
    0 => 0x7FFFFFFF,
    1 => 0x3000EFFF,
    2 => 0x000391FF,
    3 => 0x000001E9,
  ];

  const STATES = [
    'disabled' => 0,
    'enabled' => 1,
  ];

  public function __construct() {
    parent::__construct();
  }

  public function getTableName() {
    return 'users';
  }

  public function entityBuilder(array $data) {
    return new \Entity\User($data, $this);
  }

  /**
   * Data contains username, passhash, state_text
   * @param array $data
   * @return \Entity\User
   */
  public function createUser($data) {
    return $this->createEntity(array(
      'username' => $data['username'],
      'passhash' => $data['passhash'],
      'email' => $data['email'],
      'ip' => $data['ip'],
      'force_reset' => true,
      'recovery_key' => '',
      'last_login' => 0,
      'last_access' => 0,
      'class' => $data['class'],
      'state' => self::STATES['enabled'],
      'state_text' => (is_string($data['state_text']) ? $data['state_text'] : '').' ~~ '.$data['logged_user']->username,
      'permission' => self::DEFAULT_CLASS_PERMISSIONS[$data['class']],
      'remember_token' => '',
      'group_id' => $data['group_id'],
      'blame_id' => $data['logged_user']->id
    ))->save();
  }

  public function getByUsername($username) {
    $users = $this->getByData(array(
      'username' => $username,
    ));
    return count($users) == 1 ? $users[0] : false;
  }

  /**
   * Returns the user row for the given username and password.
   * Returns false when no match is found.
   * @param string $username
   * @param string $password
   * @return boolean|array
   */
  public function validateLogin($username, $password) {
    $users = $this->getByData(array(
      'username' => $username,
      'passhash' => hash('sha512', $password),
    ));
    return count($users) == 1 ? $users[0] : false;
  }
}
