<div class="row" style="margin-left: 0.5rem;">
  <div class="col s12">
    <?php if (!isset($items) || !is_array($items)) $items = array(); ?>
    <div class="col s12 m4">
    <ul class="collection">
    <?php foreach($items as $value): ?>
      <li class="collection-item">
        <?php if ($value == 'buttons'): $this->load->view('buttons'); else: ?>
        <b><?= $value[0] ?></b>:<br>
        <?= $value[1] ?></li>
        <?php endif; ?>
    <?php endforeach; ?>
    </ul><div style="clear: both"></div>
    </div>
  </div>
</div>
