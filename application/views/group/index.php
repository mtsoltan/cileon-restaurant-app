<div class="row">
  <div class="col s12">
    <?php if (!isset($items) || !is_array($items)) $users = array(); ?>
    <table class="main-table striped highlight">
      <thead>
        <tr class="headers">
          <th><?= $this->lang->line('table_groups_name') ?></th>
          <th><?= $this->lang->line('table_groups_admin') ?></th>
          <th><?= $this->lang->line('table_create_ts') ?></th>
          <th><?= $this->lang->line('form_control_action') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($items as $item): ?>
          <tr data-href="<?= base_url('group/view/'.$item->id) ?>">
            <td><?= htmlspecialchars($item->name) ?></td>
            <?php $admin = $item->getAdmin($this->User); ?>
            <td><a href="<?= $admin ? base_url('user/view/'.$admin->id) : '#' ?>"><?= $admin ? $admin->username : '' ?></a></td>
            <td><?= $item->create_datetime ?></td>
            <td><a href="<?= base_url('group/edit/' . $item->id) ?>">
              <button class="btn waves-effect waves-light teal lighten-2">
                <?= $this->lang->line('form_control_edit') ?></button></a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
