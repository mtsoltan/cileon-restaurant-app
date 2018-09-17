<?php
namespace Entity;

defined('BASEPATH') or exit('No direct script access allowed');

class Order extends Entity {

  public function getData() {
    $data = parent::getData();
    return $data;
  }

  public function getGroup($group_model) {
    return $group_model->getById($this->group_id);
  }

  public function getCustomer($customer_model) {
    return $customer_model->getById($this->customer_id);
  }

  public function finalize() {
    $model = $this->model;
    if ($this->state == $model::STATES['pending']) {
      $this->state = $model::STATES['finalized'];
      $this->save();
    }
    return $this;
  }

  public function cancel() {
    $model = $this->model;
    if ($this->state == $model::STATES['pending']) {
      $this->state = $model::STATES['cancelled'];
      $this->save();
    }
    return $this;
  }

  public function getCart($product_model) {
    $cart = unserialize($this->cart);
    $rv = [];
    foreach ($cart as $item) {
      $fetcheds = $product_model->getByData([
        'assigned_id' => $item[0],
        'group_id' => $this->group_id]);
      $fetched = count($fetcheds) ? $fetcheds[0] : null;
      $fetched->quantity = $item[1];
      $rv[] = $fetched;
    }
    return $rv;
  }

  /**
   * @param \MY_Controller $diThis
   * @return array [string[], number]
   */
  public function getCartStrings($diThis) {
    if (!isset($diThis->Product)) {
      $diThis->load->model('Product');
    }
    $cart = $this->getCart($diThis->Product);
    $cartString = [];
    $price = 0;
    foreach ($cart as $element) {
      $element_price = $element->price * (1 + $element->tax / 100) * $element->quantity;
      $price += $element_price;
      $cartString[]= $element->quantity . ' x ' . htmlspecialchars($element->name) . ' = ' .
        $diThis->lang->line('c') . number_format($element_price, 2, '.', '') . '<br>';
    }
    return [$cartString, $price];
  }
}
