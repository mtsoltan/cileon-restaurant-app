<!DOCTYPE html>
<html>
  <head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="assets/css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="assets/css/style-1.css">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= $this->lang->line('page_title_login') ?></title>
  </head>
  <body>
    <div class="container">
      <div class="center">
        <div class="card-panel teal lighten-2 white-text">
          <h3><?= $this->lang->line('page_title_login') ?></h3>
        </div>
        <div class="row">
          <?= form_open(current_url()) ?>
            <div class="row white-text">
              <div class="input-field col s12 white-text">
                <i class="material-icons prefix">account_box</i>
                <input id="icon_prefix" name="username" type="text" class="validate">
                <label for="icon_prefix"><?= $this->lang->line('form_field_username') ?></label>
              </div>
              <div class="input-field col s12">
                <i class="material-icons prefix">https</i>
                <input id="icon_telephone" name="password" type="password" class="validate">
                <label for="icon_telephone"><?= $this->lang->line('form_field_password') ?></label>
              </div>
              <button type="submit" class="btn waves-effect waves-light teal lighten-2"><?= $this->lang->line('form_control_login') ?>
              <i class="material-icons right">send</i>
              </button>
            </div>
          <?= form_close() ?>
        </div>
        <!-- Modal Trigger -->
        <a class="waves-effect waves-light btn modal-trigger" href="#modal1"><?= $this->lang->line('form_control_forgot') ?></a>
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
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="assets/js/materialize.min.js"></script>
    <script>
      $(document).ready(function(){
      // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
      $('#modal1').modal();
      });
    </script>
  </body>
</html>
