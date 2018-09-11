<div class="row">
  <div class="col s12">
    <div class="container">
      <div class="center">
        <div class="card-panel teal lighten-2 white-text">
          <h3><?= $title ?></h3>
        </div>
        <div class="row">
          <?= form_open(current_url()) ?>
            <div class="row white-text">
              <div class="input-field col s12 white-text">
                <i class="material-icons prefix">account_box</i>
                <input id="username" name="username" type="text" class="validate" required>
                <label for="username"><?= $this->lang->line('form_field_username') ?></label>
              </div>
              <div class="input-field col s12">
                <i class="material-icons prefix">https</i>
                <input id="password" name="password" type="password" class="validate" required>
                <label for="password"><?= $this->lang->line('form_field_password') ?></label>
              </div>
              <p><?= $this->lang->line('notice_login_atts_left', $attempts_left) ?></p>
              <button type="submit" class="btn waves-effect waves-light teal lighten-2"><?= $this->lang->line('form_control_login') ?>
              <i class="material-icons right">send</i>
              </button>
            </div>
          <?= form_close() ?>
        </div>
        <!-- Modal Trigger -->
        <div class="input-field">
          <a class="waves-effect waves-light btn modal-trigger" href="#modal1"><?= $this->lang->line('form_control_forgot') ?></a>
        </div>
        <!-- Modal Structure -->
        <div id="modal1" class="modal bottom-sheet">
          <div class="modal-content center-align">
            <h4><?= $this->lang->line('form_control_forgot') ?></h4>
            <p><?= $this->lang->line('form_desc_forgot') ?></p>
            <form class="col s12">
              <div class="row">
                <div class="col s12">
                  <i class="material-icons prefix">email</i>
                  <div class="input-field inline">
                    <input id="email" type="email" class="validate">
                    <label for="email" data-error="wrong" data-success="right"><?= $this->lang->line('form_field_email') ?></label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat"><?= $this->lang->line('form_control_send') ?></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
  // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
  $('#modal1').modal();
  });
</script>