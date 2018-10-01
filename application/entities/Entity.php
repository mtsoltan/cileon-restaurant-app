<?php
namespace Entity;

defined('BASEPATH') or exit('No direct script access allowed');

class Entity {

  protected $data = [];
  protected $model;
  protected $new = false;
  /** @var bool|array */
  protected $changed = false;

  public function __construct($data, $model) {
    $this->data = $data;
    $this->model = $model;
  }

  public function __isset($el) {
    return array_key_exists($el, $this->getData());
  }

  public function __get($el) {
    $rv = $this->getData()[$el];
    if (ctype_digit("$rv") && (strpos($rv, '0') !== 0 || count($rv) === 1))
      return ($rv > PHP_INT_MAX ? (float)$rv : (int)$rv);
    return $rv;
  }

  public function getData() { // Overridable.
    $data = $this->data;
    if (isset($data['create_timestamp'])) {
      $dt =  new \DateTime();
      $dt->setTimestamp($data['create_timestamp']);
      $data['create_datetime'] = $dt->format('Y-m-d H:i:s');
      if (isset($data['id']))
        $data['serial'] = $dt->format('ymdHis') . $data['id'];
    }

    return $data;
  }

  public function getSaveableData() { // Non-overridable.
    return $this->data;
  }

  public function delete() {
    return $this->model->deleteById($this->data['id']);
  }

  public function save() {
    return $this->model->save($this);
  }

  public function setNew() {
    $this->new = true;
    return $this;
  }

  public function isNew() {
    return $this->new;
  }

  public function __set($el, $val) {
    if ($el == 'id') {
      throw new \InvalidArgumentException("Unable to set primary key on entity.");
    }

    if (isset($this->{$el})) {
      $this->changed[$el] = $val;
      $this->data[$el] = $val;
    }
    return $this;
  }

  public function getChangedValues() {
    return $this->changed;
  }

  public function hasChanged() {
    return (count($this->changed) !== 0);
  }
}
