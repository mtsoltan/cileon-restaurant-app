<div class="row">
  <div class="col s12">
    <?php if (!isset($items) || !is_array($items)) $users = array(); ?>
    <table class="main-table striped highlight">
      <thead>
        <tr class="headers">
          <th><?= $this->lang->line('table_products_id') ?></th>
          <th><?= $this->lang->line('table_products_name') ?></th>
          <th><?= $this->lang->line('table_products_price') ?></th>
          <th><?= $this->lang->line('table_products_tax') ?></th>
          <th><?= $this->lang->line('table_products_np') ?></th>
          <?php if ($logged_user->hasPermission('admin')): ?>
            <th><?= $this->lang->line('table_users_group_id') ?></th>
          <?php endif; ?>
          <th><?= $this->lang->line('form_control_action') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($items as $item): ?>
          <tr data-href="<?= base_url('product/'.$item->assigned_id) ?>">
            <td><?= $item->assigned_id ?></td>
            <td><?= htmlspecialchars($item->name) ?></td>
            <td><?= $this->lang->line('c') . $item->price ?></td>
            <td><?= $item->tax . $this->lang->line('p') ?></td>
            <td><?= $item->num_purchases ?></td>
            <?php if ($logged_user->hasPermission('admin')): ?>
              <?php $group = $item->getGroup($this->Group); ?>
              <td><a href="<?= base_url('group/'.$item->group_id) ?>"><?= $group ? $group->name : '' ?></a></td>
            <?php endif; ?>
            <td><a href="<?= base_url('product/edit/' . $item->id) ?>">
              <button class="btn waves-effect waves-light teal lighten-2">
                <?= $this->lang->line('form_control_edit') ?></button></a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
