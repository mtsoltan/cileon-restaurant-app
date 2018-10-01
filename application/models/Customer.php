<?php

class Customer extends MY_Model {

  const PER_PAGE = 50;

  const STATES = [
    'disabled' => 0,
    'enabled' => 1,
  ];

  public function __construct () {
    parent::__construct();
  }

  public function getTableName() {
    return 'customers';
  }

  /**
   * @param array $data
   * @return \Entity\Customer
   */
  public function entityBuilder(array $data) {
    return new \Entity\Customer($data, $this);
  }
}
