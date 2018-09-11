<div class="row">
  <div class="col s3 sidenav">
    <?php $this->load->view('templates/navigation.php'); ?>
  </div>
  <div class="col s9">
    <div class="row">
      <div class="col s12">
        <div class="topbar"></div>
        <div class="topsecbar valign-wrapper">
          <a href="<?php echo base_url('admin/product/new'); ?>" class="waves-effect waves-light btn">Nieuwe Product</a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="ml-large">
        <table id="products">
          <thead>
            <tr>
              <th>Naam</th>
              <th>Prijs</th>
              <th>BTW</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($Products as $Product): ?>
              <tr data-href="<?php echo base_url('admin/product/'.$Product['prod_id']); ?>">
                <td><?php echo $Product['prod_naam']; ?></td>
                <td><?php echo $Product['prod_prijs']; ?></td>
                <td><?php echo $Product['prod_btw']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="assets/DataTables/datatables.min.js"></script>

<script type="text/javascript">
  $(document).ready( function(e) {
    $('#products').DataTable();

    // Table row on click go to page
    $('#products').find('tbody>tr').on('click', function (e) {
      window.location.href = $(this).attr('data-href');
    });

  });
</script>
