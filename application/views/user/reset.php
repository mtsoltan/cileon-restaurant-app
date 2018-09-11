<div class="row">
  <div class="col s12">
    <div class="container">
      <div class="center">
        <div class="card-panel teal lighten-2 white-text">
          <h3><?= $title ?></h3>
        </div>
        <div class="row">
          <?= form_open('reset') ?>
            <input name="username" type="hidden" class="validate" value="<?= $username ?>">
            <input name="recovery_key" type="hidden" class="validate" value="<?= $recovery_key ?>">
            <div class="row white-text">
              <div class="input-field col s12">
                <i class="material-icons prefix">https</i>
                <input id="password" name="password" type="password" class="validate" minlength="<?= $min_pass_length ?>">
                <label for="password"><?= $this->lang->line('form_field_password') ?></label>
              </div>
              <div class="input-field col s12">
                <i class="material-icons prefix">https</i>
                <input id="password2" name="password2" type="password" class="validate" minlength="<?= $min_pass_length ?>">
                <label for="password2"><?= $this->lang->line('form_field_password_r') ?></label>
              </div>
              <button type="submit" class="btn waves-effect waves-light teal lighten-2"><?= $this->lang->line('form_control_login') ?>
              <i class="material-icons right">send</i>
              </button>
            </div>
          <?= form_close() ?>
        </div>
      </div>
    </div>
  </div>
</div>