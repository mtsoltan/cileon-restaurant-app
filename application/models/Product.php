<?php

class Product extends MY_Model {

  const STATES = [
    'disabled' => 0,
    'enabled' => 1,
  ];

  public function __construct () {
    parent::__construct();
  }

  public function getTableName() {
    return 'products';
  }

  /**
   * @param array $data
   * @return \Entity\Product
   */
  public function entityBuilder(array $data) {
    return new \Entity\Product($data, $this);
  }
}
