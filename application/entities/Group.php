<?php
namespace Entity;

defined('BASEPATH') or exit('No direct script access allowed');

class Group extends Entity {

  public function getData() {
    $data = parent::getData();
    return $data;
  }

  public function getAdmin($user_model) {
    $users = $user_model->getByData([
      'group_id' => $this->id,
      'class' => $user_model::CLASSES['groupadmin'],
    ]);
    if (!$users || !count($users)) return false;
    return $users[0];
  }
}
