<div class="dense-actions">
  <a href="<?= base_url($type . '/edit/' . $item->id) ?>">
    <button class="btn waves-effect waves-light teal lighten-2">
      <?= $this->lang->line('form_control_edit') ?></button></a>
<?php if ($type == 'order'): ?>
  <a href="<?= base_url('order/edit/' . $item->id . '?action=finalize') ?>" class="confirm">
    <button class="btn waves-effect waves-light grey text-black lighten-2">
      <?= $this->lang->line('form_control_finalize') ?></button></a>
  <a href="<?= base_url('order/edit/' . $item->id . '?action=cancel') ?>" class="confirm">
    <button class="btn waves-effect waves-light red lighten-2">
      <?= $this->lang->line('form_control_cancel') ?></button></a>
  <button id="print" class="btn-small waves-effect waves-light teal lighten-2">
    <?= $this->lang->line('form_control_print') ?></button>
<?php else: ?>
<?= form_open($type . '/delete/' . $this->input->post('id')) ?>
  <button type="submit" class="btn waves-effect waves-light red lighten-2">
    <?= $this->lang->line('form_control_delete') ?></button>
<?= form_close() ?>
<?php endif; ?>
<?php if ($type == 'customer'): ?>
  <a href="<?= base_url('order/add?customer=' . $item->id) ?>">
    <button class="btn waves-effect waves-light teal lighten-2">
      <?= $this->lang->line('form_control_order') ?></button></a>
<?php endif; ?>
</div>