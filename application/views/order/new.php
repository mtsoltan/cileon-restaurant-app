
<div class="row">
  <div class="col s3 sidenav">
    <div class="infosec">
      <div class="white-text compname">
        <h4>
          {Company name}
        </h4>
        <small>Nieuwe bestelling</small>
      </div>
    </div>
    <div class="firstblock">
      <div class="row">
       <form class="col s12">
         <div class="row mb-0">
           <div class="col s12">
             <strong>Persoon gegevens</strong>
           </div>
         </div>
         <div class="row mb-0">
           <div class="input-field col s12">
             <input id="name" type="text" class="validate">
             <label for="name">Naam</label>
           </div>
         </div>
         <div class="row mb-0">
           <div class="input-field col s12">
             <input id="phonenumber" type="text" class="validate">
             <label for="phonenumber">Telefoonnummer</label>
           </div>
         </div>
         <div class="row mb-0">
           <div class="input-field col s8">
             <input id="streetname" type="text" class="validate">
             <label for="streetname">Straatnaam</label>
           </div>
           <div class="input-field col s4">
             <input id="housenumber" type="text" class="validate">
             <label for="housenumber">Huis nr.</label>
           </div>
         </div>
         <div class="row mb-0">
           <div class="input-field col s12">
             <input id="zipcode" type="text" class="validate">
             <label for="zipcode">Postcode</label>
           </div>
         </div>
       </form>
     </div>
    </div>
  </div>
  <div class="col s9">
    <div class="row">
      <div class="col s12">
        <div class="topbar"></div>
        <div class="topsecbar valign-wrapper">
          <a class="waves-effect waves-light btn red">Wissen</a>
          <a class="waves-effect waves-light btn ml-small">Print</a>
          <strong class="font-lg ml-large">
            Order nr. #2017000001
          </strong>
          <a class="btn-floating btn-small waves-effect waves-light blue-grey darken-3 rights mr-small">
           <i class="material-icons">menu</i>
          </a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="container-fluid">
        <div class="col s12">
          <div class="row" id="product-rows">
            <div class="product-row col s12" data-id="0">
              <input class="col s3" type="text" placeholder="Product code" />
              <input class="col s2 ml-small" type="number" placeholder="Product aantal" />
              <input class="col s3 ml-small" type="text" placeholder="Product naam" />
              <input class="col s3 ml-small" type="text" placeholder="Basis prijs" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col s12">
            <button id="new-order-row" class="waves-effect waves-light btn">Nieuw product</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Product rules function
  $('#new-order-row').on('click', function () {
    // Get the container and the first row
    var container = $('#product-rows');
        productRow = $(container).find('.product-row:first').clone(); // Clone

    // Mutate the clone, and count the data-id by row count
    productRow.attr('data-id', container.find('.product-row').length);

    // Append the new mutated clone
    container.append(productRow);
  });
</script>
