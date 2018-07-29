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
  </head>
  <body>
    <div class="container">
      <div class="center">
        <div class="card-panel teal lighten-2 white-text">
          <h3>Login</h3> 
        </div>
        <div class="row">
          <form method="post" action="<?php echo base_url(); ?>" class="col s12">
            <div class="row white-text">
              <div class="input-field col s12 white-text">
                <i class="material-icons prefix">account_box</i>
                <input id="icon_prefix" name="username" type="text" class="validate">
                <label for="icon_prefix">Inlognaam</label>
              </div>
              <div class="input-field col s12">
                <i class="material-icons prefix">https</i>
                <input id="icon_telephone" name="password" type="password" class="validate">
                <label for="icon_telephone">Wachtwoord</label>
              </div>
              <button type="submit" class="btn waves-effect waves-light teal lighten-2">Login
              <i class="material-icons right">send</i>
              </button>
            </div>
          </form>
        </div>
        <!-- Modal Trigger -->
        <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Wachtwoord vergeten</a>
        <!-- Modal Structure -->
        <div id="modal1" class="modal bottom-sheet">
          <div class="modal-content center-align">
            <h4>Wachtwoord vergeten</h4>
            <p>Vul hier uw gekoppelde email in en wij zullen u uw wachtwoord toesturen.</p>
            <form class="col s12">
              <div class="row">
                <div class="col s12">
                  <i class="material-icons prefix">email</i>
                  <div class="input-field inline">
                    <input id="email" type="email" class="validate">
                    <label for="email" data-error="wrong" data-success="right">Email</label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Verstuur</a>
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
