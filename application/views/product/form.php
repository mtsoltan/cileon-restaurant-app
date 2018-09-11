<?= form_open('product/' . (isset($edit) && $edit ? 'edit/' . $this->input->post('id') : 'add')) ?>
  <?php $this->load->view('templates/fieldinput', [
    'name' => 'assigned_id',
    'langline' => 'form_field_productid',
    'properties' => [
      'type' => 'number',
      'required' => true,
    ]
  ]); ?>
  <?php $this->load->view('templates/fieldinput', [
    'name' => 'name',
    'langline' => 'form_field_productname',
    'properties' => [
      'type' => 'text',
      'required' => true,
    ]
  ]); ?>
  <?php $this->load->view('templates/fieldinput', [
    'name' => 'price',
    'langline' => 'form_field_productprice',
    'properties' => [
      'type' => 'number',
      'pattern' => '\d+(\.\d{2})?',
      'step' => '0.01',
      'required' => true,
    ]
  ]); ?>
  <?php $this->load->view('templates/fieldtextarea', [
    'name' => 'description',
    'langline' => 'form_field_productdesc',
    'properties' => []
  ]); ?>
  <?php $this->load->view('templates/fieldselect', [
    'name' => 'tax',
    'langline' => 'form_field_producttax',
    'options' => $valid_taxes,
  ]); ?>
  <?php if (isset($edit) && $edit): ?>
    <?php $this->load->view('templates/fieldinput', [
      'name' => 'num_purchases',
      'langline' => 'form_field_product_np',
      'properties' => [
        'type' => 'number',
        'pattern' => '\d+',
        'required' => true,
      ]
    ]); ?>
    <?php $this->load->view('templates/fieldcheckbox', [
      'name' => 'top',
      'langline' => 'form_field_producttop',
      'properties' => []
    ]); ?>
  <?php endif; ?>

  <button type="submit" class="btn waves-effect waves-light teal lighten-2">
    <?= $this->lang->line('form_control_' . (isset($edit) && $edit ? 'edit' : 'add')) ?></button>
<?= form_close() ?>
<?php if (isset($edit) && $edit): ?>
  <br>
  <?= form_open('product/delete/' . $this->input->post('id')) ?>
    <button type="submit" class="btn waves-effect waves-light red lighten-2">
      <?= $this->lang->line('form_control_delete') ?></button>
  <?= form_close() ?>
<?php endif; ?>

