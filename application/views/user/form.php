<?= form_open('user/' . (isset($edit) && $edit ? 'edit/' . $this->input->post('id') : 'add')) ?>
  <?php $this->load->view('templates/fieldinput', [
    'name' => 'username',
    'langline' => 'form_field_username',
    'properties' => [
      'type' => 'text',
      'required' => true,
      'minlength' => '5'
    ]
  ]); ?>
  <?php $this->load->view('templates/fieldinput', [
    'name' => 'email',
    'langline' => 'form_field_email',
    'properties' => [
      'type' => 'email',
      'required' => true,
    ]
  ]); ?>
  <?php $this->load->view('templates/fieldinput', [
    'name' => 'state_text',
    'langline' => 'form_field_state_text',
    'properties' => [
      'type' => 'text',
    ]
  ]); ?>
  <?php if ($logged_user->hasPermission('admin')): ?>
    <?php $this->load->view('templates/fieldselect', [
      'name' => 'class',
      'langline' => 'form_field_class',
      'options' => $valid_classes,
    ]); ?>
    <?php $this->load->view('templates/fieldinput', [
      'name' => 'group_id',
      'langline' => 'form_field_group_id',
      'properties' => [
        'type' => 'number',
        'required' => true,
      ]
    ]); ?>
  <?php endif; ?>
  <button type="submit" class="btn waves-effect waves-light teal lighten-2">
    <?= $this->lang->line('form_control_' . (isset($edit) && $edit ? 'edit' : 'add')) ?></button>
<?= form_close() ?>
<?php if (isset($edit) && $edit): ?>
  <br>
  <?= form_open('user/delete/' . $this->input->post('id')) ?>
    <button type="submit" class="btn waves-effect waves-light red lighten-2">
      <?= $this->lang->line('form_control_delete') ?></button>
  <?= form_close() ?>
<?php endif; ?>
