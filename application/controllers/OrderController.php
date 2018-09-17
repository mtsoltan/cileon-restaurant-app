<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OrderController extends MY_Controller
{
  protected $topnavItems = [
    [ 'route' => 'order/add', 'title' => 'page_title_order_add', 'permission' => 'order/add', 'active' => 'order/add']
  ];

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Order');
    $this->load->model('Customer');
    $this->load->model('Product');
  }

  public function index() {
    $this->requiresPermission('order/view');
    /** @var Order $model */
    $model = $this->Order;

    if ($this->user->hasPermission('admin')) {
      $items = $model->getAll(); // TODO: Pagination ($model->getPaginated)
    } else {
      $items = $model->getFinalized($this->user->group_id);
    }

    return $this->respondWithView('order/index', [
      'table_layout' => true,
      'items' => $items,
      'title' => $this->lang->line('page_title_orders'),
    ]);
  }

  private function setValidationRules() {
    // Validate name, contact, and address.
    $this->form_validation->set_rules('customer_id',
      $this->lang->line('form_field_customersrc'), 'trim|required|is_natural');
    $this->form_validation->set_rules('address',
      $this->lang->line('form_field_customeraddr'), 'trim|max_length[1023]');
    $this->form_validation->set_rules('tax',
      $this->lang->line('form_field_order_tax'), 'required|regex_match[/\d+(\.\d{2})?/]');
    $this->form_validation->set_rules('product_assigned_id[]',
      $this->lang->line('form_field_productid'), 'trim|required|is_natural');
  }

  public function edit($id) {
    $this->requiresPermission('order/view');

    /** @var Order $model */
    $model = $this->Order;
    $item = $model->getById($id);
    if (!$item || $item->group_id !== $this->user->group_id) {
      $this->addFlash($this->lang->line('notice_no_such_x_404'), 'error');
      return $this->redirect('dashboard');
    }
    if ($item->state) {
      $this->addFlash($this->lang->line('notice_order_final_403'), 'error');
      return $this->redirect('orders');
    }

    if ($action = $this->input->get('action')) {
      switch ($action) {
        case 'cancel': $item->cancel(); break;
        case 'finalize': $item->finalize(); break;
      }
      return $this->redirectToHome();
    }

    // $this->requiresPermission('order/edit');
    $this->topnavBack = 'dashboard';

    $_POST = array_merge($_POST, $item->getData()); // Dirty fix.
    /** @var Customer $model */
    $model = $this->Customer;
    $customer = $model->getById($item->customer_id);
    if (!$customer || $customer->group_id !== $this->user->group_id) {
      $this->addFlashNow($this->lang->line('notice_no_such_x_404'), 'warning');
    } else {
      $data = $customer->getData();
      foreach ($data as $k => $v) {
        $data['customer_' . $k] = $v;
        unset($data[$k]);
      }
      $_POST = array_merge($data, $_POST); // Dirty fix.
    }

    $arr = [
      'product_assigned_id[]' => [],
      'product_quantity[]' => [],
    ];
    foreach ($item->getCart($this->Product) as $cartItem) {
      $arr['product_assigned_id[]'][] = $cartItem->assigned_id;
      $arr['product_quantity[]'][] = $cartItem->quantity;
    }

    $_POST = array_merge($arr, $_POST); // Dirty fix.

    return $this->respondWithView('order/form', [
      'title' => $this->lang->line('page_title_order_edit', $item->serial),
      'form_layout' => true,
      'edit' => true,
      'styles' => [
        '/assets/css/jqueryui/jquery-ui.min.css',
        '/assets/css/jqueryui/jquery-ui.structure.min.css',
        '/assets/css/jqueryui/jquery-ui.theme.min.css',
      ],
    ]);
  }

  public function handleEdit($id)
  {
    $this->requiresPermission('order/view');
    return false; // FIXME: handleEdit

    /** @var Order $model */
    $model = $this->Order;
    $item = $model->getById($id);
    if (!$item) {
      $this->addFlash($this->lang->line('notice_no_such_x_404'), 'error');
      return $this->redirect('dashboard');
    }

    $this->setValidationRules();
    $this->form_validation->set_rules('num_purchases',
      $this->lang->line('form_field_order_np'), 'required|is_natural');

    if(!$this->form_validation->run()) { // If form did not validte.
      return $this->edit($id);
    }

    $fillable = '';
    if (!$item->isUnique($fillable)) {
      $this->addFlashNow($this->lang->line('notice_order_repeated', $fillable), 'error');
      return $this->edit($id);
    }

    if ($this->user->hasPermission('order/edit')) {
      $item->name = $this->input->post('name');
      $item->contact = $this->input->post('contact');
      $item->num_purchases = $this->input->post('num_purchases');
    }
    $item->address = $this->input->post('address');
    $item->save();

    $this->addFlash($this->lang->line('notice_action_200'), 'success');
    return $this->redirect('dashboard');
  }

  public function add() {
    $this->requiresPermission('order/add');
    $this->topnavBack = 'dashboard';

    $id = $this->input->get_post('customer');

    if (!is_null($id)) {
      /** @var Customer $model */
      $model = $this->Customer;
      $item = $model->getById($id);
      if (!$item || $item->group_id !== $this->user->group_id) {
        $this->addFlashNow($this->lang->line('notice_no_such_x_404'), 'warning');
      } else {
        $data = $item->getData();
        foreach ($data as $k => $v) {
          $data['customer_' . $k] = $v;
          unset($data[$k]);
        }
        $_POST = array_merge($data, $_POST); // Dirty fix.
      }
    }

    return $this->respondWithView('order/form', [
      'title' => $this->lang->line('page_title_order_add'),
      'form_layout' => true,
      'styles' => [
        '/assets/css/jqueryui/jquery-ui.min.css',
        '/assets/css/jqueryui/jquery-ui.structure.min.css',
        '/assets/css/jqueryui/jquery-ui.theme.min.css',
      ],
    ]);
  }

  public function handleAdd()
  {
    $this->requiresPermission('order/add');

    /** @var Order $model */
    $model = $this->Order;

    $this->setValidationRules();

    if(!$this->form_validation->run()) { // If form did not validte.
      return $this->add();
    }

    $cart = array();
    $sum = 0;
    $tax = floatval($this->input->post('tax'));
    $customerId = $this->input->post('customer_id');
    $customer = $this->Customer->getById($customerId);

    if (!$customer || $customer->group_id !== $this->user->group_id) {
      $this->addFlash($this->lang->line('notice_no_such_x_404'), 'error');
      return $this->add();
    }
    $address = $this->input->post('address') ?
      $this->input->post('address') :
      $customer->address;
    $productIds = $this->input->post('product_assigned_id[]');
    foreach($productIds as $key => $productId) {
      $products = $this->Product->getByData(['assigned_id' => $productId, 'group_id' => $this->user->group_id]);
      $product = count($products) ? $products[0] : null;
      if($product && $this->input->post('product_quantity[]')[$key]) {
        $qty = intval($this->input->post('product_quantity[]')[$key]);
        $sum += $product->price * (1 + $product->tax / 100) * $qty;
        $cart[] = [$productId, $this->input->post('product_quantity[]')[$key]];
        $product->num_purchases += $qty;
        $product->save();
      }
    }

    // Set the data for item creation.
    $item = $model->createEntity([
      'user_id' => $this->user->id,
      'group_id' => $this->user->group_id,
      'customer_id' => $customerId,
      'state' => $model::STATES['pending'],
      'cart' => serialize($cart),
      'tax' => number_format($tax, 2, '.', ''),
      'total_price' => number_format($sum * (1 + $tax / 100), 2, '.', ''),
      'address' => $address,
    ]);

    $customer->num_purchases = $customer->num_purchases + 1;
    $customer->save();

    if (!$item) {
      $this->addFlashNow($this->lang->line('notice_db_soft_error'), 'error');
      return $this->add();
    }

    $item->save();

    $this->addFlash($this->lang->line('notice_action_200'), 'success');
    return $this->redirect('dashboard');
  }

  public function view($id) {
    $this->requiresPermission('order/view');
    $this->topnavBack = 'orders';

    $item = $this->Order->getById($id);
    if (!$item || $item->group_id !== $this->user->group_id) {
      $this->addFlash($this->lang->line('notice_no_such_x_404'), 'error');
      return $this->redirect('customers');
    }

    list($strings, $price) = $item->getCartStrings($this->Product);
    $productsString = '<ul class="browser-default"><li>' . implode('</li><li>', $strings) . '</li></ul>';

    $customer = $item->getCustomer($this->Customer);

    $items = [
      [$this->lang->line('table_orders_number'), $item->serial],
      [$this->lang->line('table_orders_customer_id'), '<a href="' . base_url('customer/'.$item->customer_id) .
        '">' . ($customer ? htmlspecialchars($customer->name) : '') . '</a>'],
      [$this->lang->line('table_orders_address'), $item->address],
      [$this->lang->line('table_orders_cart'), $productsString],
      [$this->lang->line('table_orders_tax'), floatval($item->tax) . $this->lang->line('p') . '(' .
        $this->lang->line('c') . number_format($price * $item->tax / 100, 2, '.', '') . ')'],
      [$this->lang->line('table_orders_tp'), $this->lang->line('c') . $item->total_price],
      [$this->lang->line('table_create_ts'), $item->create_datetime],
      'buttons',
    ];

    return $this->respondWithView('view', [
      'items' => $items,
      'item' => $item,
      'type' => 'customer',
      'title' => $this->lang->line('page_title_customer'),
    ]);
  }
}
