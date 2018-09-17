<?php
$now =  new \DateTime('now');
$today = $now->format('Y-m-d');
?>
<h4 class="center"><?= $this->lang->line('page_sub_sum_orders') ?></h4>
<div class="row">
  <div class="col s1 m3"></div>
  <div class="col s10 m6 card">
    <div class="card-content"><form id="financials">
      <?php $this->load->view('templates/fieldinput', [
        'name' => 'from',
        'langline' => 'form_field_date_from',
        'properties' => [
          'type' => 'date',
          'parent-class' => 'col s4 ml-small',
          'required' => true,
          'value' => $today,
        ]
      ]); ?>
      <?php $this->load->view('templates/fieldinput', [
        'name' => 'to',
        'langline' => 'form_field_date_to',
        'properties' => [
          'type' => 'date',
          'parent-class' => 'col s4 ml-small',
          'required' => true,
          'value' => $today,
        ]
      ]); ?>
      <div class="col s2 input-field ml-small">
        <button type="submit" class="btn waves-effect waves-light red lighten-2">
          <?= $this->lang->line('form_control_view') ?></button>
      </div>
    </form>

    <div class="output" style="display:block; clear: both;"></div>

    </div>
  </div>
  <div class="col s1 m3"></div>
</div>
<hr class="wide">
<h4 class="center"><?= $this->lang->line('page_sub_pending_orders') ?></h4>
<?php $this->load->view('order/index') ?>
<script>
function getFinancials(ev) {
  $('.output').load('dashboard/api', $('#financials').serialize());
  return false;
}

$(document).ready(function () {
  $viewButton = $('#financials button')
  $viewButton.on('click', getFinancials);
  $viewButton.click();
});
</script>