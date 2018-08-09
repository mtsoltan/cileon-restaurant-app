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
    $this->create_purchases_table();
    $this->create_login_attempts_table();
    $this->create_sessions_table();
    $this->create_session_data_table();
  }

  public function down()
  {
    $this->dbforge->drop_table('users');
    $this->dbforge->drop_table('groups');
    $this->dbforge->drop_table('products');
    $this->dbforge->drop_table('customers');
    $this->dbforge->drop_table('purchases');
    $this->dbforge->drop_table('login_attempts');
    $this->dbforge->drop_table('sessions');
    $this->dbforge->drop_table('session_data');
  }

  private function create_users_table() {
    $this->dbforge->add_field(array(
      'id' => array('type' => 'SMALLINT', 'constraint' => 16, 'unsigned' => true, 'auto_increment' => true),
      'username' => array('type' => 'VARCHAR', 'constraint' => 20, 'unique' => true),
      'passhash' => array('type' => 'CHAR', 'constraint' => 60),
      'email' => array('type' => 'VARCHAR', 'constraint' => 255),
      'force_reset' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'recovery_key' => array('type' => 'CHAR', 'constraint' => 32),
      'last_login' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'last_access' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'ip' => array('type' => 'VARCHAR', 'constraint' => 255),
      'session_id' => array('type' => 'CHAR', 'constraint' => 32, 'null' => true, 'unique' => true),
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
      'top' => array('type' => 'TINYINT', 'unsigned' => true, 'constraint' => 8), // Bool
      'state' => array('type' => 'TINYINT', 'unsigned' => true, 'constraint' => 8), // ['disabled', 'enabled']
      'price' => array('type' => 'SMALLINT', 'unsigned' => true, 'constraint' => 16),
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
      'name' => array('type' => 'VARCHAR', 'constraint' => 20, 'unique' => true),
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

  private function create_purchases_table() {
    $this->dbforge->add_field(array(
      'id' => array('type' => 'SMALLINT', 'constraint' => 16, 'unsigned' => true, 'auto_increment' => true),
      'user_id' => array('type' => 'SMALLINT', 'unsigned' => true, 'constraint' => 16),
      'group_id' => array('type' => 'SMALLINT', 'unsigned' => true, 'constraint' => 16),
      'customer_id' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'state' => array('type' => 'TINYINT', 'unsigned' => true, 'constraint' => 8), // ['disabled', 'enabled']
      'cart' => array('type' => 'TEXT'),
      'tax' => array('type' => 'TINYINT', 'unsigned' => true, 'constraint' => 8),
      'total_price' => array('type' => 'SMALLINT', 'unsigned' => true, 'constraint' => 16),
      'address' => array('type' => 'VARCHAR', 'constraint' => 1023),
      'create_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'update_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
    ));
    $this->dbforge->add_key('id', true); // true for PRIMARY
    $this->dbforge->add_key('group_id');
    $this->dbforge->add_key('customer_id');
    $this->dbforge->add_key('state');
    $this->dbforge->add_key('total_price');
    $this->dbforge->create_table('purchases', true); // true for IF NOT EXISTS
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

  private function create_sessions_table() {
    $this->dbforge->add_field(array(
      'id' => array('type' => 'INT', 'constraint' => 32, 'unsigned' => true, 'auto_increment' => true),
      'session_id' => array('type' => 'CHAR', 'constraint' => 32, 'unique' => true),
      'user_id' => array('type' => 'SMALLINT', 'constraint' => 16, 'unsigned' => true),
      'last_access' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'ip' => array('type' => 'VARCHAR', 'constraint' => 255),
      'device' => array('type' => 'VARCHAR', 'constraint' => 255),
      'browser' => array('type' => 'VARCHAR', 'constraint' => 255),
      'create_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'update_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
    ));
    $this->dbforge->add_key('id', true); // true for PRIMARY
    $this->dbforge->add_key('user_id');
    $this->dbforge->create_table('sessions', true); // true for IF NOT EXISTS
  }

  private function create_session_data_table() {
    $this->dbforge->add_field(array(
      'id' => array('type' => 'SMALLINT', 'constraint' => 16, 'unsigned' => true, 'auto_increment' => true),
      'session_id' => array('type' => 'CHAR', 'constraint' => 32, 'null' => true),
      'data' => array('type' => 'LONGTEXT', 'null' => true),
      'create_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
      'update_timestamp' => array('type' => 'INT', 'unsigned' => true, 'constraint' => 32),
    ));
    $this->dbforge->add_key('id', true); // true for PRIMARY
    $this->dbforge->add_key('session_id');
    $this->dbforge->create_table('session_data', true); // true for IF NOT EXISTS
  }
}