<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerController extends MY_Controller
{
  protected $topnavItems = [
    [ 'route' => 'customer/add', 'title' => 'page_title_customer_add', 'permission' => 'customer/add', 'active' => 'customer/add']
  ];

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Customer');
  }

  public function index() {
    $this->requiresPermission('customer/view');
    /** @var Customer $model */
    $model = $this->Customer;

    if ($this->user->hasPermission('admin')) {
      $items = $model->getAll(); // TODO: Pagination ($model->getPaginated)
    } else {
      $items = $model->getByData(['group_id' => $this->user->group_id]);
    }

    return $this->respondWithView('customer/index', [
      'table_layout' => true,
      'items' => $items,
      'title' => $this->lang->line('page_title_customers'),
    ]);
  }

  private function setValidationRules() {
    // Validate name, contact, and address.
    $this->form_validation->set_rules('name',
      $this->lang->line('form_field_customername'), 'trim|required|max_length[245]');
    $this->form_validation->set_rules('contact',
      $this->lang->line('form_field_customercont'), 'trim|required|max_length[255]');
    $this->form_validation->set_rules('address',
      $this->lang->line('form_field_customeraddr'), 'trim|required|max_length[1023]');
  }

  public function xhrGet() {
    $term = $this->input->post_get('term');
    $id = $this->input->post_get('id');

    /** @var Customer $model */
    $model = $this->Customer;
    if (!is_null($term)) {
      $records = $model->getByData(['group_id' => $this->user->group_id], function(&$qb) use ($term) {
        $qb->like('name', $term)->or_like('contact', $term);
        return $qb;
      });
    }
    if (!is_null($id)) {
      $records = $model->getByData(['group_id' => $this->user->group_id, 'id' => $id]);
    }

    foreach ($records as $k => $v) {
      $records[$k] = [
        'id' => $v->id,
        'name' => $v->name,
        'contact' => $v->contact,
        'address' => $v->address,
      ];
    }

    return $this->respondWithJson(['records' => $records]);
  }

  public function edit($id) {
    $this->requiresPermission('customer/view');
    $this->topnavBack = 'customers';

    /** @var Customer $model */
    $model = $this->Customer;
    $item = $model->getById($id);
    if (!$item || $item->group_id !== $this->user->group_id) {
      $this->addFlash($this->lang->line('notice_no_such_x_404'), 'error');
      return $this->redirect('customers');
    }

    $_POST = array_merge($_POST, $item->getData()); // Dirty fix.

    return $this->respondWithView('customer/form', [
      'title' => $this->lang->line('page_title_customer_edit', $item->name),
      'form_layout' => true,
      'edit' => true,
    ]);
  }

  public function handleEdit($id)
  {
    $this->requiresPermission('customer/view');

    /** @var Customer $model */
    $model = $this->Customer;
    $item = $model->getById($id);
    if (!$item) {
      $this->addFlash($this->lang->line('notice_no_such_x_404'), 'error');
      return $this->redirect('customers');
    }

    $this->setValidationRules();
    $this->form_validation->set_rules('num_purchases',
      $this->lang->line('form_field_customer_np'), 'required|is_natural');

    if(!$this->form_validation->run()) { // If form did not validte.
      return $this->edit($id);
    }

    $fillable = '';
    if (!$item->isUnique($fillable)) {
      $this->addFlashNow($this->lang->line('notice_customer_repeated', $fillable), 'error');
      return $this->edit($id);
    }

    if ($this->user->hasPermission('customer/edit')) {
      $item->name = $this->input->post('name');
      $item->contact = $this->input->post('contact');
      $item->num_purchases = $this->input->post('num_purchases');
    }
    $item->address = $this->input->post('address');
    $item->save();

    $this->addFlash($this->lang->line('notice_action_200'), 'success');
    return $this->redirect('customers');
  }

  public function add() {
    $this->requiresPermission('customer/add');
    $this->topnavBack = 'customers';

    return $this->respondWithView('customer/form', [
      'title' => $this->lang->line('page_title_customer_add'),
      'form_layout' => true,
    ]);
  }

  public function handleAdd()
  {
    $this->requiresPermission('customer/add');

    /** @var Customer $model */
    $model = $this->Customer;

    $this->setValidationRules();

    if(!$this->form_validation->run()) { // If form did not validte.
      return $this->add();
    }

    // Set the data for item creation.
    $item = $model->createEntity([
      'name' => $this->input->post('name'),
      'contact' => $this->input->post('contact'),
      'address' => $this->input->post('address'),
      'num_purchases' => 0,
      'state' => $model::STATES['enabled'],
      'group_id' => $this->user->group_id,
    ]);

    if (!$item) {
      $this->addFlashNow($this->lang->line('notice_db_soft_error'), 'error');
      return $this->add();
    }

    $fillable = '';
    if (!$item->isUnique($fillable)) {
      $this->addFlashNow($this->lang->line('notice_customer_repeated', $fillable), 'error');
      return $this->add();
    }

    $item->save();

    $this->addFlash($this->lang->line('notice_action_200'), 'success');
    return $this->redirect('customers');
  }

  public function delete($id)
  {
    $this->requiresPermission('customer/edit');

    /** @var Customer $model */
    $model = $this->Customer;
    $item = $model->getById($id);

    if (!$item || $item->group_id !== $this->user->group_id) {
      $this->addFlash($this->lang->line('notice_no_such_x_404'), 'error');
      return $this->redirect('customers');
    }

    // We don't need to free name because it isn't unique
    $item->name = $item->name . '-' . random_string(); // Free the customer name either way.
    $item->state = $model::STATES['disabled'];
    $item->save();

    $this->addFlash($this->lang->line('notice_action_200'), 'success');
    return $this->redirect('customers');
  }

  public function view($id) {
    $this->requiresPermission('customer/view');
    $this->topnavBack = 'customers';
    $this->load->model('Order');
    $this->load->model('Product');
    /** @var Customer $model */
    $model = $this->Customer;

    $item = $model->getById($id);
    if (!$item || $item->group_id !== $this->user->group_id) {
      $this->addFlash($this->lang->line('notice_no_such_x_404'), 'error');
      return $this->redirect('customers');
    }

    $diThis = $this;
    $ordersString = '<ul class="browser-default">' . implode('', array_map(function($o) use ($diThis) {
      list($strings, $price) = $o->getCartStrings($this->Product);
      $strings[] =
        floatval($o->tax) . $diThis->lang->line('p') . '(' .
        $this->lang->line('c') . number_format($price * $o->tax / 100, 2, '.', '') . ')';
      return '<li>' .
        '<a href="order/' . $o->id . '">' . $o->serial . '</a> (' . $diThis->lang->line('ostate_' . $o->state) . ')<br>' .
        $this->lang->line('table_orders_tp') . ': ' . $diThis->lang->line('c') . $o->total_price . '<br>' .
        $diThis->lang->line('page_title_products') . ': <ul class="browser-default"><li>' .
        implode('</li><li>', $strings) .
        '</li></ul>' .
      '</li>';
    }, $this->Order->getByData(['customer_id' => $item->id]))) . '</ul>';

    $items = [
      [$this->lang->line('table_customers_name'), htmlspecialchars($item->name)],
      [$this->lang->line('table_customers_contact'), htmlspecialchars($item->contact)],
      [$this->lang->line('table_customers_address'), htmlspecialchars($item->address)],
      [$this->lang->line('table_customers_np'), $item->num_purchases],
      [$this->lang->line('table_create_ts'), $item->create_datetime],
      'buttons',
      [$this->lang->line('page_title_orders'), $ordersString]
    ];

    return $this->respondWithView('view', [
      'items' => $items,
      'item' => $item,
      'type' => 'customer',
      'title' => $this->lang->line('page_title_customer'),
    ]);
  }
}
