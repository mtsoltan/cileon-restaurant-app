<?php
namespace Entity;

defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends Entity {

  public function getData() {
    $data = parent::getData();
    return $data;
  }

  public function getGroup($group_model) {
    return $group_model->getById($this->group_id);
  }

  public function isUnique(&$string_for_name = null) {
    $others = $this->model->getByData(['contact' => $this->contact]);
    if (!$this->isNew()) {
      foreach ($others as $key => $other) {
        if ($other->id == $this->id) {
          unset($others[$key]);
        }
      }
    }
    if (!is_null($string_for_name) && count($others)) {
      $string_for_name = $others[0]->name;
    }
    return (count($others) == 0);
  }
}
