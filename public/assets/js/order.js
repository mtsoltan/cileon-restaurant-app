var type_threshold = 600; // Typing delay before autocomplete is triggered.
var type_minlength = 2; // Min character count before autocomplete.
var type_timeout = 0; // Timeout to be set by typing.
var customers = []; // Gets filled with customer entities.

$(document).ready(function(){
  // Add the initial product field and setup the add more button.
  if (!$('#purchased_products .row').length)
    addProductField(false);
  else
    recalculateTax();
  $('#add_more').click(function(ev) {
    addProductField();
    return false;
  });

  // Setup autocomplete for customers.
  $('#customer_id').autocomplete({
    minLength: type_minlength,
    delay: type_threshold,
    source: function(request, response) {
      $.ajax('/customer/api', {
        method: 'POST',
        data: { 'term' : request.term },
        success: function (res, st, qx) { if (res) customerCallback(res.records, response); },
      });
    },
    select: function(ev, ui) {
      getFromCustomersArray(ui.item.value);
    },
  });

  // Fetch customer by ID if an ID is input.
  $('#customer_id').on('change', fetchCustomer);

  // Price and tax calculation.
  $('#tax').on('input', recalculateTax);

  // Allow using different address.
  $('#usediff').on('change', function() {
    this.checked ? $('.extra-addr').removeClass('hidden') : $('.extra-addr').addClass('hidden');
    this.checked ? $('#address').removeAttr('disabled') : $('#address').attr('disabled', '1');
  });
  if ($('#usediff')[0].checked) $('#usediff').change();
});

// Recalculates the tax on the change of tax or one of the prices / qtys.
function recalculateTax(ev) {
  var sum = 0;
  var rows = $('#purchased_products .row');
  console.log(rows);
  for(var i = 0; i < rows.length; i++){
    cProduct = products[$('.product_assigned_id', rows[i]).val()];
    sum += (cProduct.price * (1 + cProduct.tax / 100)) * $('.product_quantity', rows[i]).val();
  }
  var tax = sum * $('#tax').val() / 100;
  $('#ttax').text(tax.toFixed(2));
  $('#tprice').text((sum + tax).toFixed(2));
}

//Checks the already-built lookup for a customer, and fills fields with their data.
function getFromCustomersArray(id) {
  selected = customers[id];
  if (!selected) return;
  $('#customer_name').val(selected.name).removeClass('invalid').addClass('valid');
  $('label[for=customer_name]').addClass('active');
  $('#customer_contact').val(selected.contact).removeClass('invalid').addClass('valid');
  $('label[for=customer_contact]').addClass('active');
  $('#customer_address').val(selected.address).removeClass('invalid').addClass('valid');
  $('label[for=customer_address]').addClass('active');
}

//Checks the already-built lookup for a product, and fills fields with their data.
function getFromProductsArray(id, row) {
  selected = products[id];
  if (!selected) return;
  $('.product_assigned_id', row).val(selected.assigned_id).removeClass('invalid').addClass('valid');
  $('.product_name', row).val(selected.name).removeClass('invalid').addClass('valid');
  $('label', row).addClass('active');
  $('.product_price', row).text((selected.price * (1 + selected.tax / 100)).toFixed(2) +
    ' (' + selected.price + ' ' + WITHOUT_TAX + ')');
  recalculateTax();
}

//Callback function for XHR success of customer lookup.
function customerCallback(arr, response) {
  data = (function(a){
    var rv = [];
    for (o in a) {
      customers[a[o].id] = a[o];
      rv[o] = { label : a[o].name + " | " + a[o].contact, value: a[o].id };
    }
    return rv;
  })(arr);
  if (response) {
     response(data);
  }
}

//Callback function for XHR success of product lookup.
function productCallback(arr, response) {
  data = (function(a){
    var rv = [];
    for (o in a) {
      products[a[o].assigned_id] = a[o];
      rv[o] = { label: a[o].name, value: a[o].name, id: a[o].assigned_id };
    }
    return rv;
  })(arr);
  if (response) {
    response(data);
  }
}

//Fetches customer by ID from api.
function fetchCustomer(ev) {
  $.ajax('customer/api', {
    method: 'POST',
    data: { 'id' : $('#customer_id').val() },
    success: function (res, st, qx) {
      customerCallback(res.records, false);
      getFromCustomersArray($('#customer_id').val());
    },
  });
}

//Fetches product by ID from api.
function fetchProduct(ev) {
  $.ajax('product/api', {
    method: 'POST',
    data: { 'id' : $(ev.target).val() },
    success: function (res, st, qx) {
      productCallback(res.records, false);
      getFromProductsArray($(ev.target).val(), $(ev.target).closest('.row'));
    },
  });
}

// Adds a product field.
function addProductField(focus = true) {
  $('#purchased_products').append(NEW_PRODUCT_HTML
    .format(~~(Math.random()*1e6), ~~(Math.random()*1e6), ~~(Math.random()*1e6)));
  // Fetch products by ID if an ID is input.
  $('.product_assigned_id', $('#purchased_products .row').last()).on('change', fetchProduct);
  $('input', $('#purchased_products .row').last()).on('input', recalculateTax);

  // Set up autocomplete for product name.
  $('.product_name', $('#purchased_products .row').last()).autocomplete({
    minLength: type_minlength,
    delay: type_threshold,
    source: function(request, response) {
      $.ajax('/product/api', {
        method: 'POST',
        data: { 'term' : request.term },
        success: function (res, st, qx) { if (res) productCallback(res.records, response); },
      });
    },
    select: function(ev, ui) {
      getFromProductsArray(ui.item.id, $(ev.target).closest('.row'));
    },
  });

  // Autofocus new product assigned_id input.
  if (focus) $('#purchased_products .row').last().find('input').first().focus();
}