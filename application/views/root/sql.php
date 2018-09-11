<div class="container">
  <div class="center">
    <div class="card-panel teal lighten-2 white-text">
      <h3><?= $title ?></h3>
    </div>
    <div class="row">
      <?= form_open(current_url()) ?>
        <div class="row">
          <div class="input-field col s12">
            <i class="material-icons prefix">arrow_forward_ios</i>
            <textarea style="padding:0;"
              id="sql" name="sql" class="validate materialize-textarea"></textarea>
            <label for="sql"><?= $this->lang->line('form_field_sql_ph') ?></label>
          </div>
          <button type="submit" class="btn waves-effect waves-light teal lighten-2"><?= $this->lang->line('form_control_execute') ?>
          <i class="material-icons right">arrow_forward_ios</i>
          </button>
        </div>
      <?= form_close() ?>
      <?php
      if (isset($result)) {
        echo '<div class="row" style="text-align: left;">';
        if (is_object($result)) var_dump($result->result());
        else var_dump($result);
        echo '</div>';
      }
      ?>
    </div>
  </div>
</div>
<script>
$('.btn-migrate').on('click', function(ev) {
  return confirm('Are you sure you want to migrate the site?');
});
</script>