<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'entities/'.'Entity.php');

// Loader Extender, just in case we need to override view loading
abstract class MY_Model extends CI_Model
{
  const PER_PAGE = 50;

  /** @var CI_DB_mysqli_driver $db */
  public $db;

  public function __construct()
  {
    require_once(APPPATH . 'entities/' . get_class($this) . '.php');
    $this->db = $this->__get('db'); // Enables me to declare it as public to allow for syntax highlight.
    parent::__construct();
  }

  abstract public function getTableName();

  /**
   * @param array $data
   * @return \Entity\Entity
   */
  abstract public function entityBuilder(array $data);

  protected function mapResult($rows)
  {
    return array_map(function ($row) {
      return $this->entityBuilder($row);
    }, $rows);
  }

  /**
   * Gets records from the database based on the provided filters.
   * WARNING: Override this function if you're using it for a table without a state field.
   * @param array $data An array of data filters to use in WHERE.
   * @param callable $overload A function that takes the query builder as an argument by reference and overloads it.
   * @param boolean $includeDisabled Set to true to include disabled records.
   * @return \Entity\Entity[]
   */
  public function getByData($data, $overload = null, $includeDisabled = false)
  {
    if (count($data)) $this->db->where($data);
    if (!is_null($overload)) $overload($this->db);
    if (!$includeDisabled) $this->db->where(['state' => 1]);
    $query = $this->db->get($this->getTableName());
    return $this->mapResult($query->result_array());
  }

  public function getAll()
  {
    return $this->getByData([]);
  }

  /**
   * @param array $data
   * @param callable $overload
   * @return mixed
   */
  public function deleteByData($data, $overload = null)
  {
    if (!is_null($overload)) $overload($this->db);
    if (count($data)) $this->db->where($data);
    return $this->db->delete($this->getTableName());
  }

  public function getById($id) {
    if (!ctype_digit("$id")) {
      return null;
    }

    $arr = $this->getByData(['id' => $id], function (&$qb) {
      return $qb->limit(1);
    });

    return count($arr) ? $arr[0] : null;
  }

  public function deleteById($id)
  {

    if (!ctype_digit("$id")) {
      return null;
    }

    return $this->deleteByData(['id' => $id]);
  }

  /**
   * @param array $arr
   * @return \Entity\Entity
   * @throws \InvalidArgumentException
   */
  public function createEntity($arr) {
    if (!is_array($arr)) {
      throw new \InvalidArgumentException("createEntity expects array as argument");
    }

    $entity = $this->entityBuilder($arr);
    $entity->setNew();
    return $entity;
  }

  /**
   * @param \Entity\Entity $entity
   * @return \Entity\Entity|null
   */
  public function save($entity) {
    $entity = clone $entity;

    if ($entity->isNew()) {
      $values = $entity->getSaveableData();

      $values = array_merge($values, array(
        'create_timestamp' => time(),
        'update_timestamp' => 0,
      ));

      $result = $this->db->insert($this->getTableName(), $values);

      if (!$result) {
        throw new \InvalidArgumentException("Unable to save entity - failed to retrieve id after save");
      }

      $id = $this->db->insert_id();

      return $this->getById($id);
    }

    if ($entity->hasChanged()) {
      $id = $entity->id;
      if (!ctype_digit("$id")) {
        throw new \InvalidArgumentException("Unable to save entity - primary key not set");
      }

      $values = $entity->getChangedValues();

      if (count($values) == 0) {
        throw new \InvalidArgumentException('Unable to save entity - nothing was changed, but marked as changed');
      }

      $values = array_merge($values, array(
        'update_timestamp' => time(),
      ));

      if (array_key_exists('id', $values)) {
        throw new \InvalidArgumentException("Unable to save entity - primary key was changed");
      }

      $this->db->where('id', $id);

      $this->db->update($this->getTableName(), $values);

      return $this->getById($id);
    }

    return $entity;
  }

  public function getPaginated($data, $page = false, $includeDisabled = false)
  {
    $overload = null;

    if ($page) {
      $overload = function (&$qb) use ($page) {
        return $qb->limit(self::PER_PAGE)->offset($page * self::PER_PAGE);
      };
    }

    return $this->getByData($data, $overload, $includeDisabled);
  }
}
