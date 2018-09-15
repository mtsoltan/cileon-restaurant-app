<div class="row">
  <div class="col s12">
    <?php if (!isset($items) || !is_array($items)) $users = array(); ?>
    <table class="main-table">
      <thead>
        <tr class="headers">
          <th><?= $this->lang->line('table_users_username') ?></th>
          <th><?= $this->lang->line('table_users_email') ?></th>
          <th><?= $this->lang->line('table_users_notes') ?></th>
          <?php if ($logged_user->hasPermission('admin')): ?>
            <th><?= $this->lang->line('table_users_last_login') ?></th>
            <th><?= $this->lang->line('table_users_ip') ?></th>
            <th><?= $this->lang->line('table_users_class') ?></th>
            <th><?= $this->lang->line('table_users_group_id') ?></th>
            <th><?= $this->lang->line('table_users_create_ts') ?></th>
          <?php endif ?>
          <th><?= $this->lang->line('form_control_action') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($items as $item): ?>
          <tr data-href="<?= base_url('user/view/'.$item->id) ?>">
            <td><?= $item->username ?></td>
            <td><?= $item->email ?></td>
            <td><?= htmlspecialchars($item->state_text) ?></td>
            <?php if ($logged_user->hasPermission('admin')): ?>
              <td><?= $item->login_datetime ?></td>
              <td><?= $item->ip ?></td>
              <td><?= $this->lang->line('class_' . $item->class) ?></td>
              <?php $group = $item->getGroup($this->Group); ?>
              <td><a href="<?= base_url('group/view/'.$item->group_id) ?>"><?= $group? $group->name : '' ?></a></td>
              <td><?= $item->create_datetime ?></td>
            <?php endif ?>
            <td><a href="<?= base_url('user/edit/' . $item->id) ?>">
              <button class="btn waves-effect waves-light teal lighten-2">
                <?= $this->lang->line('form_control_edit') ?></button></a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
