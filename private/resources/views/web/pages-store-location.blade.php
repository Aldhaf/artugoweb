@extends('web.layouts.app')
@section('title', 'Store Location')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
<style>
  .navigation {
    z-index: 1052;
  }
</style>
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.min.js"></script>
<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css">

<div class="content content-dark" style="padding-top: 0px;">
  <div style="background: url('{{ url('assets/img/bg-slider.png') }}') no-repeat bottom right; background-size: cover; padding-top: 80px;">
    <div class="container_dis" style="margin-left: 5%;margin-right:5%">
      <div class="row">
        <div class="col-sm-12 col-xs-12 col-md-12">
          <center>
            <h2>Store Location</h2>
            <hr>
            <div class="row">
              <div class="col-md-3">
                <label for="">Region</label>
                <select name="region" id="" class="form-control form-dark select2">
                  <option value="-">All Branch</option>
                  @foreach($store_location_regional as $val)
                  <option {{ (!empty($selectedRegion) ? ($val->id == $selectedRegion ? 'selected="true"' : null) : null) }} value="{{ strtolower(str_replace(' ','-',$val->regional_name)) }}">{{ $val->regional_name }}</option>
                  @endforeach
                </select>
                <hr>
                <label for="">Store</label>
                <input type="text" placeholder="Pencarian..." name="pencarian" class="form-control form-dark">
                <!-- <select class="cari form-control" name="cari"></select> -->

                <div style="width:100%;height:500px;border:25px solid #25282C;border-radius:10px z-index:1;overflow-y:scroll;overflow-x:hidden;text-align:left">
                  <div style="margin:10px" class="listStore">

                    @foreach($store as $val)
                    <div class="dataStore" style="margin-bottom: 30px;">
                      <div class="row">
                        <div class="col-md-12">
                          <b>{{ $val->nama_toko }}</b>
                          <p>{{ $val->alamat_toko }}</p>
                        </div>
                      </div>
                      <div class="row">
                        <button class="btn btn-white btn-sm div_store" lat="{{ $val->latitude }}" long="{{ $val->longitude}}" style="margin-left: 10px;">Kunjungi Toko</button>
                      </div>
                      <hr style="background-color:white">
                    </div>
                    @endforeach


                  </div>
                </div>
              </div>
              <div class="col-md-9">
                <div id="map" style="width:100%;height:800px;border:25px solid #25282C;border-radius:10px z-index:1"></div>
              </div>
            </div>
          </center>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).on('click', '.div_store', function(e) {
    e.preventDefault();

    let latitude = $(this).attr('lat');
    let longitude = $(this).attr('long');

    // console.log(latitude, longitude);
    map.setView([latitude, longitude], 100);
  });

  var map = L.map('map').setView([-2.548926, 118.0148634], 5);
  L.Path.prototype.setZIndex = function(index) {
    var obj = $(this._container || this._path);
    if (!obj.length) return; // not supported on canvas
    var parent = obj.parent();
    obj.data('order', index).detach();

    var lower = parent.children().filter(function() {
      var order = $(this).data('order');
      if (order == undefined) return false;
      return order <= index;
    });

    if (lower.length) {
      lower.last().after(obj);
    } else {
      parent.prepend(obj);
    }
  };

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);

  // Multiple Markers
  var markers = <?php echo $store; ?>;
  // Control 2: This add a scale to the map
  L.control.scale().addTo(map);

  var artugoIcon = L.icon({
    iconUrl: '{{ (!empty($selectedRegion) ? "../../assets/img/store-location.png" : "assets/img/store-location.png") }}',

    iconSize: [30, 44], // size of the icon
  });

  var results = new L.LayerGroup().addTo(map);
  markers.forEach((obj, i) => {
    if (obj.latitude != "" && obj.longitude != "") {

      L.marker([obj.latitude, obj.longitude], {
        icon: artugoIcon
      }).addTo(map).bindPopup('<b> ' + obj.nama_toko + '</b><br>' + obj.alamat_toko + '<br><a href="http://www.google.com/maps/place/' + obj.latitude + ',' + obj.longitude + '" target="_blank">Kunjungi Toko </a>');
    }
  });


  $(document).on('change', '[name="region"]', function(e) {
    e.preventDefault();

    let region_id = $(this).val();

    window.location = "{{ url('store-location') }}" + "/branch/" + region_id;
    // let url = '{{ url("store-location/branch") }}' + '/' + region_id;
    // $.get(url, function(retData) {
    //   let data = JSON.parse(retData);
    //   console.log(data);
    // });

  });

  $(document).on('keyup', '[name="pencarian"]', function(e) {
    let val = $(this).val();

    if (val) {
      let url = '{{ url("filter")}}';
      let data = {
        "_token": "{{ csrf_token() }}",
        "q": val
      }

      $.post(url, data, function(ret) {
        let retData = JSON.parse(ret);

        if (retData.length > 0) {
          $('.listStore .dataStore').remove();

          for (let i = 0; i < retData.length; i++) {
            $('.listStore').append('<div class="dataStore" style="margin-bottom: 30px;">' +
              '<div class="row">' +
              '<div class="col-md-12">' +
              '<b>' + retData[i].nama_toko + '</b>' +
              '<p>' + retData[i].alamat_toko + '</p>' +
              '</div>' +
              '</div>' +
              '<div class="row">' +
              '<button class="btn btn-white btn-sm div_store" lat="' + retData[i].latitude + '" long="' + retData[i].longitude + '" style="margin-left: 10px;">Kunjungi Toko</button>' +
              '</div>' +
              '<hr style="background-color:white">' +
              '</div>');
          }
        }

      });
      // console.log(data);
    }
  });
</script>

@endsection