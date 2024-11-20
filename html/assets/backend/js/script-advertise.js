$(document).ready(function(){
  var ads_spot = $('#ads_spot').val();
  // var ads_range = $();
  $('#ads-spec').load(site_url + "page/get-advertise-data/" + ads_spot);
  $('#time_of_service').load(site_url + "page/get-advertise-range/" + ads_spot, function(){
    default_script();
  });

  // $('#ads-price').load(site_url + "page/get-advertise-price/" + ads_spot + "/" + ads_range, function(){
  //   default_script();
  // });

  $('#ads_spot').on("change", function(){
    var ads_spot = $(this).val();

    $('#ads-spec').load(site_url + "page/get-advertise-data/" + ads_spot);
    $('#time_of_service').load(site_url + "page/get-advertise-range/" + ads_spot, function(){
      default_script();
    });

  });


});

function set_ads_range(range){

  var ads_spot = $('#ads_spot').val();

  $('#ads-price').load(site_url + "page/get-advertise-price/" + ads_spot + "/" + range, function(){
    default_script();
  });

  console.log(range);

}

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $('#temp').attr('src', e.target.result);
      $('#tempURL').attr('href', e.target.result);
      $('#preview').removeClass('hide');
    }

    reader.readAsDataURL(input.files[0]);
  }
}

function readURLPayment(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $('#temp_attachment').attr('src', e.target.result);
      $('#tempURL').attr('href', e.target.result);
      $('#preview_payment').removeClass('hide');
    }

    reader.readAsDataURL(input.files[0]);
  }
}
