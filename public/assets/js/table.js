$(document).ready( function(e) {
  $('table').DataTable();
  // Table row on click go to page
  $('table').find('tbody>tr[data-href]').on('click', function (e) {
    window.location.href = $(this).attr('data-href');
  });
});