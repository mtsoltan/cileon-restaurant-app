<?php
defined('BASEPATH') or exit('No direct script access allowed');

// The Actual Restaurant Dashboard
class ProductController extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();
    // Load the Model
    $this->load->model('Product');
  }

  // All the Products
  public function index()
  {
    // Fetch all products
    $data['Products'] = $this->Product->fetchAll();

    // Load views
    $this->load->view('templates/header.php');
    $this->load->view('product/dashboard.php', $data);
    $this->load->view('templates/footer.php');
  }

  // View the product
  public function view($id)
  {
    // Fetch the product
    $data['Product'] = $this->Product->fetch($id);

    // Load the views
    $this->load->view('templates/header.php');
    $this->load->view('product/view.php', $data);
    $this->load->view('templates/footer.php');
  }

  // Add a product
  public function add()
  {
    // Setting the form validation rules
    $this->form_validation->set_rules('prod_naam', 'Product Naam', 'required');
    $this->form_validation->set_rules('prod_prijs', 'Product Prijs', 'required');
    $this->form_validation->set_rules('prod_btw', 'Product BTW', 'required');

    // Rules are applied and accepted
    if ($this->form_validation->run() == true) {
      // Insert the product, retrieve the id back
      $id = $this->Product->insert();
      redirect(base_url('admin/product/' . $id));
    }

    // Load the views
    $this->load->view('templates/header.php');
    $this->load->view('product/add.php');
    $this->load->view('templates/footer.php');
  }

  // Edit the product
  public function edit($id)
  {
    // Setting the form validation rules
    $this->form_validation->set_rules('prod_naam', 'Product Naam', 'required');
    $this->form_validation->set_rules('prod_prijs', 'Product Prijs', 'required');
    $this->form_validation->set_rules('prod_btw', 'Product BTW', 'required');

    // Rules are applied and accepted
    if ($this->form_validation->run() == true) {
      // Update the product
      $this->Product->update($id);

      // Generate message
      $this->session->set_flashdata('form_status', TRUE);
    }

    // Fetch the product by id
    $data['Product'] = $this->Product->fetch($id);

    // Load the views
    $this->load->view('templates/header.php');
    $this->load->view('product/edit.php', $data);
    $this->load->view('templates/footer.php');
  }

  // Delete the product
  public function delete($id)
  {
    $this->Product->remove($id);
    redirect(base_url('admin/products'));
  }
}
