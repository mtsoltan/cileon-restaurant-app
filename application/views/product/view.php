<div class="row">
  <div class="col s3 sidenav">
    <?php $this->load->view('templates/navigation.php'); ?>
  </div>
  <div class="col s9">
    <div class="row">
      <div class="col s12">
        <div class="topbar"></div>
        <div class="topsecbar valign-wrapper">
          <a href="<?php echo base_url('admin/product/edit/'.$Product['prod_id']); ?>" class="waves-effect waves-light btn">
            <i class="material-icons left">edit</i>
            Wijzig Product
          </a>
          <a href="<?php echo base_url('admin/product/delete/'.$Product['prod_id']); ?>" class="waves-effect waves-light btn red ml-small">
            <i class="material-icons left">delete_forever</i>
            Verwijder Product
          </a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="ml-large">
        <h5><?php echo $Product['prod_naam']; ?></h5>
        <p>
          <strong>Product Prijs : </strong>
          &euro; <?php echo $Product['prod_prijs']; ?>
        </p>
        <p>
          <strong>Product BTW : </strong>
          <?php echo $Product['prod_btw']; ?>%
        </p>
        <p>
          <strong>Product Omschrijving : </strong>
          <?php echo $Product['prod_omschrijving']; ?>
        </p>
      </div>
    </div>
  </div>
</div>
