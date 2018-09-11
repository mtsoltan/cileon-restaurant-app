<?php

class LoginAttempt extends MY_Model {

  const MAXIMUM_LOGIN_ATTEMPTS = 6;
  const BAN_TIME = 21600; // 6 Hours

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Gets records from the database based on the provided filters.
   * WARNING: Override this function if you're using it for a table without a state field.
   * @param array $data An array of data filters to use in WHERE.
   * @param callable $overload A function that takes the query builder as an argument by reference and overloads it.
   * @param boolean $includeDisabled Disregarded in this model.
   * @return \Entity\Entity[]
   */
  public function getByData($data, $overload = null, $includeDisabled = false)
  {
    return parent::getByData($data, $overload, true);
  }

  public function getTableName()
  {
    return 'login_attempts';
  }

  public function entityBuilder(array $data)
  {
    return new \Entity\User($data, $this);
  }

  public function clearOldAttempts()
  {
    $this->deleteByData(['create_timestamp <' => time() - self::BAN_TIME]);
  }
}
