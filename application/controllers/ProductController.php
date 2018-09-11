<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProductController extends MY_Controller
{
  protected $topnavItems = [
    [ 'route' => 'product/add', 'title' => 'page_title_product_add', 'permission' => 'product/add', 'active' => 'product/add']
  ];

  protected $validTaxes = [6, 21];

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Product');
  }

  public function index() {
    $this->requiresPermission('product/view');
    /** @var Product $productModel */
    $productModel = $this->Product;

    if ($this->user->hasPermission('admin')) {
      $items = $productModel->getAll();
    } else {
      $items = $productModel->getByData(['group_id' => $this->user->group_id]);
    }

    return $this->respondWithView('product/index', [
      'table_layout' => true,
      'items' => $items,
      'title' => $this->lang->line('page_title_products'),
    ]);
  }

  private function setValidationRules() {
    // Validate product assigned_id name, price, description, and tax.
    $this->form_validation->set_rules('assigned_id',
      $this->lang->line('form_field_productid'), 'required|is_natural');
    $this->form_validation->set_rules('name',
      $this->lang->line('form_field_productname'), 'trim|required|max_length[245]');
    $this->form_validation->set_rules('price',
      $this->lang->line('form_field_productprice'), 'required|decimal|regex_match[/\d+(\.\d{2})/]');
    $this->form_validation->set_rules('description',
      $this->lang->line('form_field_productdesc'), 'trim|max_length[2047]');
    $this->form_validation->set_rules('tax',
      $this->lang->line('form_field_producttax'), 'trim|required|is_natural|in_list['.
      implode(',', $this->validTaxes).']');

  }

  public function edit($id) {
    $this->requiresPermission('product/edit');
    $this->topnavBack = 'products';

    /** @var Product $productModel */
    $productModel = $this->Product;
    $product = $productModel->getById($id);
    if (!$product || $product->group_id !== $this->user->group_id) {
      $this->addFlash($this->lang->line('notice_no_such_x_404'), 'error');
      return $this->redirect('products');
    }

    $_POST = array_merge($_POST, $product->getData()); // Dirty fix.

    return $this->respondWithView('product/form', [
      'title' => $this->lang->line('page_title_product_edit', $product->name),
      'form_layout' => true,
      'edit' => true,
      'valid_taxes' => array_combine($this->validTaxes, array_map(function ($tax) {
      return $tax . '% ' . $this->lang->line('tax');
      }, $this->validTaxes)),
    ]);
  }

  public function handleEdit($id)
  {
    $this->requiresPermission('product/edit');

    /** @var Product $productModel */
    $productModel = $this->Product;
    $product = $productModel->getById($id);
    if (!$product) {
      $this->addFlash($this->lang->line('notice_no_such_x_404'), 'error');
      return $this->redirect('products');
    }

    $this->setValidationRules();
    $this->form_validation->set_rules('num_purchases',
      $this->lang->line('form_field_productprice'), 'required|is_natural');

    if(!$this->form_validation->run()) { // If form did not validte.
      return $this->edit($id);
    }

    if (!$product->isUnique()) {
      $this->addFlashNow($this->lang->line('notice_product_repeated'), 'error');
      return $this->edit($id);
    }

    $product->assigned_id = $this->input->post('assigned_id');
    $product->name = $this->input->post('name');
    $product->description = $this->input->post('description');
    $product->top = is_null($this->input->post('top')) ? 0 : 1;
    $product->price = $this->input->post('price');
    $product->tax = $this->input->post('tax');
    $product->num_purchases = $this->input->post('num_purchases');
    $product->save();

    $this->addFlash($this->lang->line('notice_action_200'), 'success');
    return $this->redirect('products');
  }

  public function add() {
    $this->requiresPermission('product/add');
    $this->topnavBack = 'products';

    return $this->respondWithView('product/form', [
      'title' => $this->lang->line('page_title_product_add'),
      'form_layout' => true,
      'valid_taxes' => array_combine($this->validTaxes, array_map(function ($tax) {
        return $tax . '% ' . $this->lang->line('tax');
      }, $this->validTaxes)),
    ]);
  }

  public function handleAdd()
  {
    $this->requiresPermission('product/add');

    /** @var Product $productModel */
    $productModel = $this->Product;

    $this->setValidationRules();

    if(!$this->form_validation->run()) { // If form did not validte.
      return $this->add();
    }

    // Set the data for product creation.
    $product = $productModel->createEntity([
      'assigned_id' => $this->input->post('assigned_id'),
      'name' => $this->input->post('name'),
      'description' => $this->input->post('description'),
      'top' => 0,
      'price' => $this->input->post('price'),
      'tax' => $this->input->post('tax'),
      'num_purchases' => 0,
      'group_id' => $this->user->group_id,
      'state' => $productModel::STATES['enabled'],
    ]);

    if (!$product) {
      $this->addFlashNow($this->lang->line('notice_db_soft_error'), 'error');
      return $this->add();
    }

    if (!$product->isUnique()) {
      $this->addFlashNow($this->lang->line('notice_product_repeated'), 'error');
      return $this->add();
    }

    $product->save();

    $this->addFlash($this->lang->line('notice_action_200'), 'success');
    return $this->redirect('products');
  }

  public function delete($id)
  {
    $this->requiresPermission('product/edit');

    /** @var Product $productModel */
    $productModel = $this->Product;
    $product = $productModel->getById($id);

    if (!$product) {
      $this->addFlash($this->lang->line('notice_no_such_x_404'), 'error');
      return $this->redirect('products');
    }

    $this->load->helper('string');
    // We don't need to free name and assigned_id because isUnique checks against only enabled products.
    $product->name = $product->name . '-' . random_string(); // Free the product name either way.
    $product->state = $productModel::STATES['disabled'];
    $product->save();

    $this->addFlash($this->lang->line('notice_action_200'), 'success');
    return $this->redirect('products');
  }
}
