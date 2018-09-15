<?= form_open('customer/' . (isset($edit) && $edit ? 'edit/' . $this->input->post('id') : 'add')) ?>
  <?php if (!(isset($edit) && $edit) || $logged_user->hasPermission('customer/edit')): ?>
  <?php $this->load->view('templates/fieldinput', [
    'name' => 'name',
    'langline' => 'form_field_customername',
    'properties' => [
      'type' => 'text',
      'required' => true,
    ]
  ]); ?>
  <?php $this->load->view('templates/fieldinput', [
    'name' => 'contact',
    'langline' => 'form_field_customercont',
    'properties' => [
      'type' => 'text',
      'required' => true,
    ]
  ]); ?>
  <?php endif; ?>
  <?php $this->load->view('templates/fieldtextarea', [
    'name' => 'address',
    'langline' => 'form_field_customeraddr',
    'properties' => [
      'required' => true,
    ]
  ]); ?>
  <?php if (isset($edit) && $edit && $logged_user->hasPermission('customer/edit')): ?>
    <?php $this->load->view('templates/fieldinput', [
      'name' => 'num_purchases',
      'langline' => 'form_field_customer_np',
      'properties' => [
        'type' => 'number',
        'pattern' => '\d+',
        'required' => true,
      ]
    ]); ?>
  <?php endif; ?>

  <button type="submit" class="btn waves-effect waves-light teal lighten-2">
    <?= $this->lang->line('form_control_' . (isset($edit) && $edit ? 'edit' : 'add')) ?></button>
<?= form_close() ?>
<?php if (isset($edit) && $edit): ?>
  <br>
  <?= form_open('customer/delete/' . $this->input->post('id')) ?>
    <button type="submit" class="btn waves-effect waves-light red lighten-2">
      <?= $this->lang->line('form_control_delete') ?></button>
  <?= form_close() ?>
<?php endif; ?>

