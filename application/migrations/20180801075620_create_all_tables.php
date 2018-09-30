<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_all_tables extends CI_Migration
{

  public function up()
  {
    $this->create_users_table();
    $this->create_groups_table();
    $this->create_products_table();
    $this->create_customers_table();
    $this->create_orders_table();
    $this->create_login_attempts_table();
    $this->insert_root();
  }

  public function down()
  {
    $this->dbforge->drop_table('users');
    $this->dbforge->drop_table('groups');
    $this->dbforge->drop_table('products');
    $this->dbforge->drop_table('customers');
    $this->dbforge->drop_table('orders');
    $this->dbforge->drop_table('login_attempts');
  }

  private function create_users_table() {
    $this->dbforge->add_field(array(
      'id' => array('type' => 'SMALLINT', 'constraint' => 16, 'unsigned' => true, 'auto_increment' => true),
      'username' => array('type' => 'VARCHAR', 'constraint' => 20, 'unique' => true),
      'passhash' => array('type' => 'CHAR', 'constraint' => 128),
      'email' => array('type' => 'VARCHAR', 'constraint' => 255),
      'force_reset' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'recovery_key' => array('type' => 'CHAR', 'constraint' => 32),
      'last_login' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'last_access' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'ip' => array('type' => 'VARCHAR', 'constraint' => 255),
      'class' => array('type' => 'TINYINT', 'unsigned' => true, 'constraint' => 8), // ['root', 'admin', 'groupadmin', 'user']
      'state' => array('type' => 'TINYINT', 'unsigned' => true, 'constraint' => 8), // ['disabled', 'enabled']
      'state_text' => array('type' => 'TEXT'),
      'blame_id' => array('type' => 'SMALLINT', 'unsigned' => true, 'constraint' => 16),
      'permission' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'group_id' => array('type' => 'SMALLINT', 'unsigned' => true, 'constraint' => 16),
      'remember_token' => array('type' => 'VARCHAR', 'constraint' => 255),
      'create_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'update_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
    ));
    $this->dbforge->add_key('id', true); // true for PRIMARY
    $this->dbforge->add_key('email');
    $this->dbforge->add_key('last_access');
    $this->dbforge->add_key('ip');
    $this->dbforge->add_key('class');
    $this->dbforge->add_key('state');
    $this->dbforge->add_key('group_id');
    $this->dbforge->create_table('users', true); // true for IF NOT EXISTS
  }

  private function create_groups_table() {
    $this->dbforge->add_field(array(
      'id' => array('type' => 'SMALLINT', 'constraint' => 16, 'unsigned' => true, 'auto_increment' => true),
      'name' => array('type' => 'VARCHAR', 'constraint' => 255, 'unique' => true),
      'blame_id' => array('type' => 'SMALLINT', 'unsigned' => true, 'constraint' => 16),
      'state' => array('type' => 'TINYINT', 'unsigned' => true, 'constraint' => 8), // ['disabled', 'enabled']
      'create_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'update_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
    ));
    $this->dbforge->add_key('id', true); // true for PRIMARY
    $this->dbforge->add_key('state');
    $this->dbforge->create_table('groups', true); // true for IF NOT EXISTS
  }

  private function create_products_table() {
    $this->dbforge->add_field(array(
      'id' => array('type' => 'INT', 'constraint' => 32, 'unsigned' => true, 'auto_increment' => true),
      'assigned_id' => array('type' => 'SMALLINT', 'constraint' => 16, 'unsigned' => true),
      'name' => array('type' => 'VARCHAR', 'constraint' => 255),
      'description'=> array('type' => 'VARCHAR', 'constraint' => 2047),
      'top' => array('type' => 'TINYINT', 'unsigned' => true, 'constraint' => 8), // Bool
      'state' => array('type' => 'TINYINT', 'unsigned' => true, 'constraint' => 8), // ['disabled', 'enabled']
      'price' => array('type' => 'DECIMAL(7,2) NOT NULL'),
      'tax' => array('type' => 'TINYINT', 'unsigned' => true, 'constraint' => 8),
      'num_purchases' => array('type' => 'SMALLINT', 'unsigned' => true, 'constraint' => 16),
      'group_id' => array('type' => 'SMALLINT', 'unsigned' => true, 'constraint' => 16),
      'create_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'update_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
    ));
    $this->dbforge->add_key('id', true); // true for PRIMARY
    $this->dbforge->add_key('name');
    $this->dbforge->add_key('top');
    $this->dbforge->add_key('state');
    $this->dbforge->add_key('price');
    $this->dbforge->add_key('num_purchases');
    $this->dbforge->add_key('group_id');
    $this->dbforge->create_table('products', true); // true for IF NOT EXISTS
  }

  private function create_customers_table() {
    $this->dbforge->add_field(array(
      'id' => array('type' => 'INT', 'constraint' => 32, 'unsigned' => true, 'auto_increment' => true),
      'name' => array('type' => 'VARCHAR', 'constraint' => 255, 'unique' => true),
      'contact' => array('type' => 'VARCHAR', 'constraint' => 255), // Phone, etc.
      'address' => array('type' => 'VARCHAR', 'constraint' => 1023),
      'num_purchases' => array('type' => 'SMALLINT', 'unsigned' => true, 'constraint' => 16),
      'state' => array('type' => 'TINYINT', 'unsigned' => true, 'constraint' => 8), // ['disabled', 'enabled']
      'group_id' => array('type' => 'SMALLINT', 'unsigned' => true, 'constraint' => 16),
      'create_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'update_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
    ));
    $this->dbforge->add_key('id', true); // true for PRIMARY
    $this->dbforge->add_key('contact');
    $this->dbforge->add_key('num_purchases');
    $this->dbforge->add_key('state');
    $this->dbforge->add_key('group_id');
    $this->dbforge->create_table('customers', true); // true for IF NOT EXISTS
  }

  private function create_orders_table() {
    $this->dbforge->add_field(array(
      'id' => array('type' => 'SMALLINT', 'constraint' => 16, 'unsigned' => true, 'auto_increment' => true),
      'user_id' => array('type' => 'SMALLINT', 'unsigned' => true, 'constraint' => 16),
      'group_id' => array('type' => 'SMALLINT', 'unsigned' => true, 'constraint' => 16),
      'customer_id' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'state' => array('type' => 'TINYINT', 'unsigned' => true, 'constraint' => 8), // ['pending', 'finalized', 'cancelled']
      'cart' => array('type' => 'TEXT'),
      'tax' => array('type' => 'DECIMAL(4,2) NOT NULL'),
      'total_price' => array('type' => 'DECIMAL(7,2) NOT NULL'),
      'address' => array('type' => 'VARCHAR', 'constraint' => 1023),
      'create_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'update_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
    ));
    $this->dbforge->add_key('id', true); // true for PRIMARY
    $this->dbforge->add_key('group_id');
    $this->dbforge->add_key('customer_id');
    $this->dbforge->add_key('state');
    $this->dbforge->add_key('total_price');
    $this->dbforge->create_table('orders', true); // true for IF NOT EXISTS
  }

  private function create_login_attempts_table() {
    $this->dbforge->add_field(array(
      'id' => array('type' => 'SMALLINT', 'constraint' => 16, 'unsigned' => true, 'auto_increment' => true),
      'user_id' => array('type' => 'SMALLINT', 'constraint' => 16, 'unsigned' => true),
      'ip' => array('type' => 'VARCHAR', 'constraint' => 255),
      'device' => array('type' => 'VARCHAR', 'constraint' => 255),
      'browser' => array('type' => 'VARCHAR', 'constraint' => 255),
      'create_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'update_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
    ));
    $this->dbforge->add_key('id', true); // true for PRIMARY
    $this->dbforge->add_key('user_id');
    $this->dbforge->add_key('ip');
    $this->dbforge->add_key('create_timestamp');
    $this->dbforge->create_table('login_attempts', true); // true for IF NOT EXISTS
  }

  private function insert_root() {
    $this->db->where('id', 1);
    $query = $this->db->get('users');
    $root = $query->result_array();
    if ($root) return;
    $this->db->insert('users', array(
      'id' => 1,
      'username' => 'root',
      'passhash' => $_SERVER['CI_ENV'] == 'development' ?
        'b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86' :
        'd19137b7a25087095faad33d366ca876a03be603c32b0cb153e105d0d31e9a19f9cae0e09314e43d3695471baab13d586ece7bab7110fcbeb9765b319cebd01d',
      'email' => 'root@cileon.xyz',
      'force_reset' => 0,
      'recovery_key' => '',
      'last_login' => 0,
      'last_access' => 0,
      'ip' => '127.0.0.1',
      'class' => 0,
      'state' => 1,
      'state_text' => 'Root User',
      'blame_id' => 1,
      'permission' => 0x7FFFFFFF,
      'group_id' => 0,
      'remember_token' => 'You can not forget something this simple...',
      'create_timestamp' => 0,
      'update_timestamp' => 0,
    ));
  }
}
