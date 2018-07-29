<div class="row">
  <div class="col s3 sidenav">
    <?php $this->load->view('templates/navigation.php'); ?>
  </div>
  <form method="post">
    <div class="col s9">
      <div class="row">
        <div class="col s12">
          <div class="topbar"></div>
          <div class="topsecbar valign-wrapper">
            <button type="submit" class="waves-effect waves-light btn">Wijzigingen Opslaan</button>
            <a href="<?php echo base_url('admin/product/'.$Product['prod_id']); ?>" class="waves-effect waves-light grey lighten-4 btn ml-small text-black">
              <i class="material-icons left">arrow_back</i>
              Terug
            </a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col s12 small-gutter">
          <div class="row">
            <div class="input-field col s12 small-gutter">
              <input id="prod_name" type="text" class="validate" name="prod_naam" value="<?php echo $Product['prod_naam']; ?>">
              <label for="prod_name">Product Naam</label>
            </div>
            <div class="input-field col s12 small-gutter">
              <input id="prod_price" type="text" class="validate" name="prod_prijs" value="<?php echo $Product['prod_prijs']; ?>">
              <label for="prod_price">Product Prijs</label>
            </div>
            <div class="input-field col s12 small-gutter">
              <select name="prod_btw">
                <option disabled >Kies een optie</option>
                <option value="6" <?php if($Product['prod_btw'] == 6) echo "selected" ?> >6% BTW</option>
                <option value="21" <?php if($Product['prod_btw'] == 21) echo "selected" ?> >21% BTW</option>
              </select>
              <label>Product BTW</label>
            </div>
            <div class="input-field col s12 small-gutter">
              <textarea id="prod_description" class="materialize-textarea" name="prod_omschrijving"><?php echo $Product['prod_omschrijving']; ?></textarea>
              <label for="prod_description">Product Beschrijving</label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<?php if($this->session->flashdata('form_status') == TRUE): ?>
  <script type="text/javascript">
    Materialize.toast('Wijzigingen opgeslagen', 4000);
  </script>
<?php endif; ?>

<!-- Select input initialisation #Materialize -->
<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });
</script>
