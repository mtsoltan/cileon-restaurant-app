<?= form_open('group/' . (isset($edit) && $edit ? 'edit/' . $this->input->post('id') : 'add')) ?>
  <?php $this->load->view('templates/fieldinput', [
    'name' => 'name',
    'langline' => 'form_field_groupname',
    'properties' => [
      'type' => 'text',
      'required' => true,
    ]
  ]); ?>
  <button type="submit" class="btn waves-effect waves-light teal lighten-2">
    <?= $this->lang->line('form_control_' . (isset($edit) && $edit ? 'edit' : 'add')) ?></button>
<?= form_close() ?>
<?php if (isset($edit) && $edit): ?>
  <br>
  <?= form_open('group/delete/' . $this->input->post('id')) ?>
    <button type="submit" class="btn waves-effect waves-light red lighten-2">
      <?= $this->lang->line('form_control_delete') ?></button>
  <?= form_close() ?>
<?php endif; ?>
