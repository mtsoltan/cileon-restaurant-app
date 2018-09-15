<?php
namespace Entity;

defined('BASEPATH') or exit('No direct script access allowed');

class User extends Entity {

  public function getData() {
    $data = parent::getData();
    $dt =  new \DateTime();
    $dt->setTimestamp($data['last_login']);
    $data['login_datetime'] = $dt->format('Y-m-d H:i:s');
    return $data;
  }

  public function hasPermission($permission)
  {
    $model = $this->model;
    if (is_string($permission)) {
      $permission = $model::PERMISSIONS[$permission];
    }
    if (!$permission) {
      return false;
    }
    return ($this->permission & $permission) > 0;
  }

  public function isTechnical() {
    $model = $this->model;
    return
      $this->class == $model::CLASSES['root'] ||
      $this->class == $model::CLASSES['admin'];
  }

  public function getGroup($group_model) {
    return $group_model->getById($this->group_id);
  }
}
