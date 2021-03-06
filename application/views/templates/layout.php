<!DOCTYPE html>
<html>
  <head>
    <base href="<?php echo base_url(); ?>" >
    <link rel="stylesheet"
      href="<?= isset($cdn) ? 'https://fonts.googleapis.com/icon?family=Material+Icons' : 'assets/css/cdn/icon.css' ?>">
    <link rel="stylesheet"
      href="<?= isset($cdn) ? 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css' : 'assets/css/cdn/materialize.min.css' ?>">
    <?php if (isset($table_layout)): ?>
    <link rel="stylesheet" type="text/css"
      href="<?= isset($cdn) ? 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' : 'assets/css/cdn/jquery.dataTables.min.css' ?>">
    <?php endif ?>
    <?php if (isset($styles)) {
      foreach ($styles as $item) {
        echo '<link rel="stylesheet" type="text/css" href="' . $item . '">';
      }
    } ?>
    <link type="text/css" rel="stylesheet" href="assets/css/style-<?= $style ?>.css?v=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <script type="text/javascript"
      src="<?= isset($cdn) ? 'https://code.jquery.com/jquery-3.2.1.min.js' : 'assets/js/cdn/jquery-3.2.1.min.js' ?>"></script>
    <!-- Materialize loads faster when placed at end of body, but for convenience, we'll place it here. -->
    <script src="<?= isset($cdn) ? 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js' : 'assets/js/cdn/materialize.min.js' ?>"></script>
    <script>
    $.autocomplete = undefined;
    SURE_MSG = "<?= $this->lang->line('confirm_action'); ?>";
    </script>
    <script type="text/javascript" src="assets/js/jquery-ui.min.js"></script>
    <?php if (isset($table_layout)): ?>
    <script type="text/javascript" src="<?= isset($cdn) ? 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js' : 'assets/js/cdn/jquery.dataTables.min.js' ?>"></script>
    <?php endif ?>
    <script src="assets/js/main.js"></script>
  </head>
  <body>
    <div class="notices">
    <?php if (count($validation_errors) || count($flash)) { // TODO: Move those and style them.
      foreach ($validation_errors as $item)  echo '<div class="error">' . $item . '</div>';
      foreach ($flash as $type => $list)
        if (in_array($type, ['error', 'warning', 'success']))
          foreach ($list as $item)
            echo '<div class="' . $type . '">' . $item . '</div>';
    } ?>
    </div>
    <div class="row">
      <?php if (isset($sidenav) && $sidenav) {
        if (!is_string($sidenav)) $this->load->view('templates/sidenav');
        else $this->load->view($sidenav);
      } ?>
      <div class="col <?= isset($sidenav) && $sidenav ? 's9' : 's12' ?>">
        <?php if ( (isset($topnav_items) && $topnav_items) ||
                   (isset($topnav_back)  &&  $topnav_back) )
                $this->load->view('templates/topnav'); ?>
        <?php if (isset($form_layout) && $form_layout): ?>
        <div class="row">
          <div class="col m3"></div>
          <div class="col s12 m6">
            <h3><?= $title ?></h3>
        <?php endif ?>
        <?php $this->load->view($VIEW); ?>
        <?php if (isset($table_layout) && $table_layout): ?>
        <script src="assets/js/table.js"></script>
        <?php endif ?>
        <?php if (isset($form_layout) && $form_layout): ?>
            <script src="assets/js/form.js"></script>
          </div>
        </div>
        <?php endif ?>
      </div>
    </div>
  </body>
</html>
