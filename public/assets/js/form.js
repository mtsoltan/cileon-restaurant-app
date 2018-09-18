$(document).ready(function(){
  $('select').formSelect();
  $('select').on('change', function (ev) {
    $this = $(this);
    $opts = $this.find('option');
    for (i in $opts) {
      if (i == ~~i && $opts[i].selected) {
        $('input#'+this.id.substr(1)).val($opts[i].value);
        break;
      }
    }
  });
  $('input:visible').first().focus();
});