<?= form_open('order/' . (isset($edit) && $edit ? 'edit/' . $this->input->post('id') : 'add')) ?>
  <input type="hidden" value="" id="final_customer_id" name="customer_id">
  <?php $this->load->view('templates/fieldcheckbox', [
    'name' => 'usediff',
    'langline' => 'form_desc_usediffaddr',
    'properties' => ($this->input->post('customer_address') == $this->input->post('address') ? [] : ['checked' => true])
  ]); ?>
  <?php $this->load->view('templates/fieldtextarea', [
    'name' => 'address',
    'langline' => 'form_field_extraaddr',
    'properties' =>  [
      'disabled' => true,
      'required' => true,
      'parent-class' => 'extra-addr hidden',
    ]
  ]); ?>
  <?php $this->load->view('templates/fieldinput', [
    'name' => 'tax',
    'langline' => 'form_field_order_tax',
    'properties' => [
      'type' => 'number',
      'required' => true,
      'step' => '0.01',
      'pattern' => '\d+(\.\d{2})?',
      'value' => '0',
    ]
  ]); ?>
  <hr class="wide">
  <div class="section">
    <h5><?= $this->lang->line('form_desc_order_addprods') ?></h5>
    <div id="purchased_products">
      <?php
      $js_products = [];
      $assigned = $this->input->post('product_assigned_id[]');
      if($assigned) {
        foreach ($assigned as $key => $value) {
          $a_products = $this->Product->getByData(['assigned_id' => $value, 'group_id' => $logged_user->group_id]);
          if (!count($a_products)) continue;
          $a_product = $a_products[0];
          $js_products[$a_product->assigned_id] = '{' .
            'id : ' . $a_product->id . ',' .
            'name : "' . $a_product->name . '",' .
            'assigned_id : ' . $a_product->assigned_id . ',' .
            'price : ' . $a_product->price . ',' .
            'tax : ' . $a_product->tax . ',' .
            '}';
          echo '<div class="row">';
          $this->load->view('templates/fieldinput', [
            'name' => 'product_assigned_id[]',
            'langline' => 'form_field_productid',
            'properties' => [
              'parent-class' => 'col s3',
              'type' => 'number',
              'required' => true,
              'value' => $a_product->assigned_id,
            ]
          ]);
          $this->load->view('templates/fieldinput', [
            'name' => 'product_name[]',
            'langline' => 'form_field_productname',
            'properties' => [
              'parent-class' => 'col s3',
              'type' => 'text',
              'required' => true,
              'value' => $a_product->name,
            ]
          ]);
          echo '<div class="product_price col s3">' .
            number_format($a_product->price * (1 + $a_product->tax / 100), 2, '.', '') .
            ' (' . $a_product->price . ' ' . $this->lang->line('form_desc_withouttax') . ')</div>';
          $this->load->view('templates/fieldinput', [
            'name' => 'product_quantity[]',
            'langline' => 'form_field_productqty',
            'properties' => [
              'parent-class' => 'col s3',
              'type' => 'number',
              'required' => true,
              'value' => $this->input->post('product_quantity[]')[$key],
            ]
          ]);
          echo '</div>';
        }
      } ?>
    </div>
    <button class="btn waves-effect waves-light red lighten-2" id="add_more">
      <?= $this->lang->line('form_control_more') ?></button>
  </div>
  <hr class="wide">
  <div class="section">
    <div class="col s3"><?= $this->lang->line('form_desc_order_totaltax') ?></div>
    <div class="col s3" id="ttax">0.00</div>
    <div class="col s3"><?= $this->lang->line('form_desc_order_tp') ?></div>
    <div class="col s3" id="tprice">0.00</div>
  </div>
  <hr class="wide">
  <button type="submit" class="btn waves-effect waves-light teal lighten-2">
    <?= $this->lang->line('form_control_' . (isset($edit) && $edit ? 'edit' : 'add')) ?></button>
<?= form_close() ?>
<?php if (isset($edit) && $edit): ?>
  <br>
  <?= form_open('order/delete/' . $this->input->post('id')) ?>
    <button type="submit" class="btn waves-effect waves-light red lighten-2">
      <?= $this->lang->line('form_control_delete') ?></button>
  <?= form_close() ?>
<?php endif; ?>
<script>
var products = []; // Gets filled with product entities.
<?php
foreach ($js_products as $js_key => $js_product) {
  echo 'products['.$js_key.'] = '.$js_product . ';';
}
?>

var WITHOUT_TAX = "<?= $this->lang->line('form_desc_withouttax') ?>";
var NEW_PRODUCT_HTML = `
<div class="row">
  <div class="input-field  col s3">
    <input name="product_assigned_id[]" id="product_assigned_id_{0}"
      class="validate product_assigned_id" type="number" required>
    <label for="product_assigned_id_{0}"><?= $this->lang->line('form_field_productid') ?></label>
  </div>
  <div class="input-field  col s3">
    <input name="product_name[]" id="product_name_{1}"
      class="validate product_name" type="text" required>
    <label for="product_name_{1}"><?= $this->lang->line('form_field_productname') ?></label>
  </div>
  <div class="product_price col s3">0.00</div>
  <div class="input-field  col s3">
    <input name="product_quantity[]" id="product_quantity_{2}"
      class="validate product_quantity valid" type="number" value="1" required>
    <label for="product_quantity_{2}" class="active"><?= $this->lang->line('form_field_productqty') ?></label>
  </div>
</div>`;
</script>
<script src="assets/js/order.js"></script>