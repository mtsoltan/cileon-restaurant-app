<?php
namespace Entity;

defined('BASEPATH') or exit('No direct script access allowed');

class Product extends Entity {

  public function getData() {
    $data = parent::getData();
    return $data;
  }

  public function getGroup($group_model) {
    return $group_model->getById($this->group_id);
  }

  public function isUnique() {
    if ($this->isNew()) {
      $id = 0;
    } else {
      $id = $this->id;
    }
    $others = array_merge($this->model->getByData([
      'group_id' => $this->group_id,
      'assigned_id' => $this->assigned_id
    ]), $this->model->getByData([
      'group_id' => $this->group_id,
      'name' => $this->name
    ]));

    foreach ($others as $product) {
      if ($product->id != $id) {
        return false;
      }
    }
    return true;
  }
}
