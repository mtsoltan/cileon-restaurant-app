<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OrderController extends MY_Controller
{
  protected $topnavItems = [
    [ 'route' => 'order/add', 'title' => 'page_title_group_add', 'permission' => 'group/add', 'active' => 'group/add']
  ];


  public function __construct() {
    parent::__construct();
  }

  public function add() {
  }
}
