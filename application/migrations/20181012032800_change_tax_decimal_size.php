<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Change_tax_decimal_size extends CI_Migration
{

  public function up()
  {
    $this->dbforge->modify_column('orders', array(
      'tax' => array('type' => 'DECIMAL(7,2) NOT NULL'),
    ));

  }

  public function down()
  {
    $this->dbforge->modify_column('orders', array(
      'tax' => array('type' => 'DECIMAL(4,2) NOT NULL'),
    ));
  }
}