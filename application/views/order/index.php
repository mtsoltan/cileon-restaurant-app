<div class="row">
  <div class="col s12">
    <?php if (!isset($items) || !is_array($items)) $users = array(); ?>
    <table class="main-table striped highlight">
      <thead>
        <tr class="headers">
          <th><?= $this->lang->line('table_orders_number') ?></th>
          <th><?= $this->lang->line('table_orders_customer_id') ?></th>
          <th><?= $this->lang->line('table_orders_address') ?></th>
          <th><?= $this->lang->line('table_orders_cart') ?></th>
          <th><?= $this->lang->line('table_orders_tax') ?></th>
          <th><?= $this->lang->line('table_orders_tp') ?></th>
          <th><?= $this->lang->line('table_create_ts') ?></th>
          <?php if ($logged_user->hasPermission('admin')): ?>
            <th><?= $this->lang->line('table_users_group_id') ?></th>
          <?php endif; ?>
          <th><?= $this->lang->line('form_control_action') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($items as $item): ?>
          <tr data-href="<?= base_url('order/'.$item->id) ?>">
            <td><?= $item->serial ?></td>
              <?php $customer = $item->getCustomer($this->Customer); ?>
              <td><a href="<?= base_url('customer/'.$item->customer_id) ?>">
                <?= $customer ? htmlspecialchars($customer->name) : '' ?></a></td>
            <td><?= htmlspecialchars($item->address) ?></td>
            <?php
            $cart = $item->getCart($this->Product);
            $html_cart = '';
            $price = 0;
            foreach ($cart as $element) {
              $element_price = $element->price * (1 + $element->tax / 100) * $element->quantity;
              $price += $element_price;
              $html_cart .= $element->quantity . ' x ' . $element->name . ' = ' .
                $this->lang->line('c') . number_format($element_price, 2, '.', '') . '<br>';
            }
            ?>
            <td><?= $html_cart ?></td>
            <td><?= floatval($item->tax) . $this->lang->line('p') ?> (<?= $this->lang->line('c') . number_format($price * $item->tax / 100, 2, '.', '') ?>)</td>
            <td><?= $this->lang->line('c') . $item->total_price ?></td>
            <td><?= $item->create_datetime ?></td>
            <?php if ($logged_user->hasPermission('admin')): ?>
              <?php $group = $item->getGroup($this->Group); ?>
              <td><a href="<?= base_url('group/'.$item->group_id) ?>"><?= $group ? $group->name : '' ?></a></td>
            <?php endif; ?>
            <td class="dense-actions">
              <?php if (!$item->state): ?>
                <a href="<?= base_url('order/edit/' . $item->id) ?>">
                  <button class="btn-small waves-effect waves-light teal lighten-2">
                    <?= $this->lang->line('form_control_edit') ?></button></a><br>
                <a href="<?= base_url('order/edit/' . $item->id . '?action=finalize') ?>" class="confirm">
                  <button class="btn-small waves-effect waves-light grey text-black lighten-2">
                    <?= $this->lang->line('form_control_finalize') ?></button></a><br>
                <a href="<?= base_url('order/edit/' . $item->id . '?action=cancel') ?>" class="confirm">
                  <button class="btn-small waves-effect waves-light red lighten-2">
                    <?= $this->lang->line('form_control_cancel') ?></button></a><br>
              <?php endif; ?>
                <button id="print" class="btn-small waves-effect waves-light teal lighten-2">
                  <?= $this->lang->line('form_control_print') ?></button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
