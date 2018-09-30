<?php
/* This requires $topnav_items OR $topnav_back to be defined.
 * MY_Controller::loadDefaultVars() loads Controller::topnavItems as $topnav_items if it is defined.
 * To hide the topnav in a method of a controller that has topnavItems set, simply empty them inside the method.
 */
?>
<div class="row">
  <div class="col s12">
    <div class="topbar"></div>
    <div class="topsecbar valign-wrapper">
      <?php
      if (isset($topnav_back)) {
        $this->load->view('templates/back.php');
      }
      foreach ($topnav_items as $item) {
        if (!isset($item['permission']) ||
          (isset($logged_user) && $logged_user->hasPermission($item['permission']))
          ) {
          echo anchor($item['route'], $this->lang->line($item['title']), ['class' =>
            'waves-effect waves-light btn' .
            (preg_match('%'.$item['active'].'%', uri_string()) ? ' hidden' : '') .
            (isset($item['class']) ? ' ' . $item['class'] : '')
          ]);
        }
      }
      ?>
      <?php if (isset($sidenav) && $sidenav && is_string($sidenav)): ?>
        <ul id="slide-out" class="sidenav default-sidenav right-aligned">
          <div class="infosec">
            <div class="white-text compname">
              <h4><?php if(isset($logged_user_group) && $logged_user_group) echo $logged_user_group->name; else echo $this->lang->line('dev_missing_usergroup'); ?></h4>
            </div>
          </div>
          <?php $sidenav_for('', '<li>%s</li>'); ?>
        </ul>
        <a class="btn-floating btn-small waves-effect waves-light blue-grey darken-3 rights mr-small sidenav-trigger" data-target="slide-out">
          <i class="material-icons">menu</i></a>
        <script>
          $(document).ready(function(){
            $('.sidenav').sidenav({'edge' : 'right'});
          });
        </script>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php
/*
<!-- Dropdown Trigger -->
<a href="#" class="waves-effect waves-light btn">Nieuwe Bestelling</a>
<!-- <div class="rights">
<a class='dropdown-button btn' href='#' data-activates='dropdown1'>Menu</a>
<ul id='dropdown1' class='dropdown-content'>
<li><a href="#!">Nieuwe Bestelling</a></li>
<li><a href="#!">Omzet</a></li>
<li><a href="#!">Beheer</a></li>
<li><a href="#!">Uitloggen</a></li>
</ul>
</div> -->
*/
?>
