<?php

class Group extends MY_Model {

  const STATES = [
    'disabled' => 0,
    'enabled' => 1,
  ];

  public function __construct() {
    parent::__construct();
  }

  public function getTableName() {
    return 'groups';
  }

  /**
   * @param array $data
   * @return \Entity\Group
   */
  public function entityBuilder(array $data) {
    return new \Entity\Group($data, $this);
  }
}
