<?php

class Product extends MY_Model {

  const PER_PAGE = 50;

  const STATES = [
    'disabled' => 0,
    'enabled' => 1,
  ];

  public function __construct ()
  {
    parent::__construct();
  }

  public function getTableName()
  {
    return 'products';
  }

  public function entityBuilder(array $data)
  {
    return new \Entity\Product($data, $this);
  }

  public function getProducts($data, $page = false, $includeDisabled = false)
  {
    $overload = null;

    if (!$includeDisabled) {
      $this->db->where(['state' => self::STATES['enabled']]);
    }

    if ($page) {
      $overload = function (&$qb) use ($page) {
        return $qb->limit(self::PER_PAGE)->offset($page * self::PER_PAGE);
      };
    }

    return $this->getByData($data, $overload);
  }

}
