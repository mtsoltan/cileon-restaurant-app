<div class="row">
  <div class="col s3 sidenav">
    <?php $this->load->view('templates/navigation.php'); ?>
  </div>
  <div class="col s9">
    <div class="row">
      <div class="col s12">
        <div class="topbar"></div>
        <div class="topsecbar valign-wrapper">
          <!-- Dropdown Trigger -->
          <a href="<?php echo base_url('admin/order/new'); ?>" class="waves-effect waves-light btn">Nieuwe Bestelling</a>
          <!-- <div class="rights">
            <a class='dropdown-button btn' href='#' data-activates='dropdown1'>Menu</a>
            <ul id='dropdown1' class='dropdown-content'>
              <li><a href="#!">Nieuwe Bestelling</a></li>
              <li><a href="#!">Omzet</a></li>
              <li><a href="#!">Beheer</a></li>
              <li><a href="#!">Uitloggen</a></li>
            </ul>
          </div> -->
        </div>
      </div>
    </div>
    <div class="row">
      <div class="ml-large">
        <div class="input-field col s6">
         <input id="last_name" type="text" class="validate">
         <label for="last_name">Last Name</label>
        </div>
      </div>
    </div>
  </div>
</div>
