<div class="row">
  <div class="col s12">
    <?php if (!isset($items) || !is_array($items)) $users = array(); ?>
    <table class="main-table striped highlight">
      <thead>
        <tr class="headers">
          <th><?= $this->lang->line('table_customers_name') ?></th>
          <th><?= $this->lang->line('table_customers_contact') ?></th>
          <th><?= $this->lang->line('table_customers_address') ?></th>
          <th><?= $this->lang->line('table_customers_np') ?></th>
          <?php if ($logged_user->hasPermission('admin')): ?>
            <th><?= $this->lang->line('table_users_group_id') ?></th>
          <?php endif; ?>
          <th><?= $this->lang->line('form_control_action') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($items as $item): ?>
          <tr data-href="<?= base_url('customer/view/'.$item->id) ?>">
            <td><?= htmlspecialchars($item->name) ?></td>
            <td><?= htmlspecialchars($item->contact) ?></td>
            <td><?= htmlspecialchars($item->address) ?></td>
            <td><?= $item->num_purchases ?></td>
            <?php if ($logged_user->hasPermission('admin')): ?>
              <?php $group = $item->getGroup($this->Group); ?>
              <td><a href="<?= base_url('group/view/'.$item->group_id) ?>"><?= $group? $group->name : '' ?></a></td>
            <?php endif; ?>
            <td>
              <a href="<?= base_url('customer/edit/' . $item->id) ?>">
                <button class="btn waves-effect waves-light teal lighten-2">
                  <?= $this->lang->line('form_control_edit') ?></button></a>
              <a href="<?= base_url('order/add?customer=' . $item->id) ?>">
                <button class="btn waves-effect waves-light teal lighten-2">
                  <?= $this->lang->line('form_control_order') ?></button></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
