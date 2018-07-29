<?php

class Product extends CI_Model {

  public function __construct ()
  {
    parent::__construct();
  }

  // Fetch all Products
  public function fetchAll()
  {
    $query = $this->db->get('producten')->result_array();
    return $query;
  }

  // Fetch product by id
  public function fetch($id)
  {
    $query = $this->db->get_where('producten', array(
      'prod_id' => $id
    ))->row_array();
    return $query;
  }

  // Insert the product
  public function insert()
  {
    $this->db->insert('producten', $this->input->post());
    return $this->db->insert_id();
  }

  // Update the product by id
  public function update($id)
  {
    $this->db->where('prod_id', $id)->update('producten', $this->input->post());
  }

  // Delete the product by id
  public function remove($id)
  {
    $this->db->delete('producten', array('prod_id' => $id));
  }

}


?>
