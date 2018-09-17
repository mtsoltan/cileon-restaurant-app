<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IndexController extends MY_Controller {

  protected $topnavItems = [
    [ 'route' => 'order/add', 'title' => 'page_title_order_add', 'permission' => 'order/add', 'active' => 'order/add'],
    [ 'route' => 'customer/add', 'title' => 'page_title_customer_add', 'permission' => 'customer/add', 'active' => 'customer/add']
  ];

  public function index()
  {
    $this->redirectToHome();
  }

  public function dashboard()
  {
    $this->isPrivate();
    $this->load->model('Order');
    $this->load->model('Customer');
    $this->load->model('Product');
    /** @var Order $model */
    $model = $this->Order;
    $items = $model->getPending($this->user->group_id);

    $this->isPrivate(); // TODO: View different dashboards for admin and root.
    return $this->respondWithView('dashboard', [
      'title' => $this->lang->line('page_title_dashboard'),
      'items' => $items,
      'table_layout' => true,
    ]);
  }

  public function xhrFinancials() {
    $this->isPrivate();
    $this->load->model('Order');
    $this->load->model('Product');

    $from = new DateTime($this->input->get('from'));
    $from->setTime(0, 0, 0);
    $fromTs = $from->getTimestamp();
    $to = new DateTime($this->input->get('to'));
    $to->setTime(23, 59, 59);
    $toTs = $to->getTimestamp();

    /** @var Order $model */
    $model = $this->Order;
    $items = $model->getFinalized($this->user->group_id, $fromTs, $toTs);
    $total = 0;
    foreach ($items as $item) {
      $total += $item->total_price;
    }

    return $this->load->view('financials', [
      'total' => $total,
      'num_orders' => count($items),
    ]);
  }
}
