<?php
/* This requires $topnav_back to be defined.
 * topnav.php loads this view if $topnav_back is defined.
 */
echo anchor($topnav_back, $this->lang->line('nav_button_back') . ' <i class="material-icons left">arrow_back</i>', [
  'class' => 'waves-effect waves-light grey lighten-4 btn ml-small text-black'
]);