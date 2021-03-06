<?php

class Order extends MY_Model {

  const PER_PAGE = 50;

  const STATES = [
    'pending' => 0,
    'finalized' => 1,
    'cancelled' => 2,
  ];

  public function __construct () {
    parent::__construct();
  }

  public function getTableName() {
    return 'orders';
  }

  /**
   * @param array $data
   * @return \Entity\Order
   */
  public function entityBuilder(array $data) {
    return new \Entity\Order($data, $this);
  }

  // Orders can't be disabled. They have different states and different state constants.
  public function getByData($data, $overload = null, $includeDisabled = false) {
    return parent::getByData($data, $overload, true);
  }

  public function getPending($group_id) {
    return $this->getByData([
      'group_id' => $group_id,
      'state' => self::STATES['pending'],
    ]);
  }

  public function getFinalized($group_id, $startTimestamp = null, $endTimestamp = null) {
    $data = [
      'group_id' => $group_id,
      'state' => self::STATES['finalized'],
    ];
    if ($startTimestamp) {
      $data['create_timestamp >'] = $startTimestamp;
    }
    if ($endTimestamp) {
      $data['create_timestamp <'] = $endTimestamp;
    }
    return $this->getByData($data);
  }
}
