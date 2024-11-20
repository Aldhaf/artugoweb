@extends('backend.layouts.backend-app')
@section('title', 'Add New Installation Request')
@section('content')
<script type="text/javascript" src="{{ url('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Installation Request
      <small>New</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <div class="col-md-7">
        <div class="box box-solid">
          <!-- <div class="box-header">
			          <h3 class="box-title">Products</h3>
			        </div> -->
          <div class="box-body">
            @include('backend.layouts.alert')
            {{ csrf_field() }}
            <form class="fdata" action="{{ url('artmin/installation/request/add-installation-request') }}" method="post" autocomplete="off">
              {{ csrf_field() }}
              <input type="hidden" name="warranty_no" value="{{ $warranty->warranty_no }}">
              <div class="form-group">
                <label>Preferensi Tanggal Kunjungan</label>
                <input type="text" class="form-control form-dark" id="prefered_date" name="prefered_date" value="{{ old('prefered_date', date('d-m-Y', strtotime('+1 day'))) }}" placeholder="dd-mm-yyyy">
              </div>
              <div class="form-group">
                <label>Preferensi Jam Kunjungan</label>
                <select class="form-control form-dark" name="prefered_time">
                  <?php foreach ($service_time as $time) : ?>
                    <option value="<?= $time->id ?>"><?= $time->time ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control form-dark" name="name" value="{{ old('name', $warranty->reg_name) }}" placeholder="Nama">
              </div>
              <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" class="form-control form-dark" name="phone" value="{{ old('phone', $warranty->reg_phone) }}" placeholder="Phone Number">
              </div>
              <div class="form-group">
                <label>Alamat Kunjungan</label>
                <textarea class="form-control form-dark" name="address" rows="5" placeholder="Alamat kunjungan">{{ old('address', $warranty->reg_address )}}</textarea>
              </div>
              <div class="form-group">
                <label>Kota</label>
                <?php
                $city = DB::table('ms_loc_city')->orderBy('province_id')->get();
                ?>
                <select class="form-control select2" name="city">
                  <option value="">Pilih Kota</option>
                  <?php foreach ($city as $cit) : ?>
                    <option value="<?= $cit->city_id ?>" <?php if (old('city', $warranty->reg_city_id) == $cit->city_id) echo "selected"; ?>><?= $cit->city_name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <button class="btn btn-primary"><i class="fa fa-plus"></i> Submit Installation Request</button>
              </div>
            </form>





          </div>
        </div>
      </div>

      <div class="col-md-5">
        <div class="box box-solid">
          <div class="box-body">
            <center>
              <img src="{{ $warranty->product_image??'' }}" style="width:50%">
            </center>
            <div class="row">
              <div class="col-md-3">
                Warranty No
              </div>
              <div class="col-md-9">
                : {{ $warranty->warranty_no }}
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                Serial Number
              </div>
              <div class="col-md-9">
                : {{ $warranty->serial_no }}
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-3">
                Member Name
              </div>
              <div class="col-md-9">
                : {{ $warranty->reg_name }}
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                Member Phone
              </div>
              <div class="col-md-9">
                : {{ $warranty->reg_phone }}
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                Member Email
              </div>
              <div class="col-md-9">
                : {{ $warranty->reg_email }}
              </div>
            </div>
          </div>
        </div>
      </div>



    </div>



  </section><!-- /.content -->
</div>

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script type="text/javascript">
  $(document).on('submit', '.fdata', function(e) {
    e.preventDefault();

    swal({
        title: "Konfirmasi",
        text: "Apakah data yang anda masukan telah sesuai?",
        icon: "info",
        buttons: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = $(this).attr('action');
          let data = $(this).serializeArray();

          $.post(url, data, function(retData) {
            // console.log(retData);
            swal('Berhasil', 'Installation Request Telah Dibuat', 'success').then((confirm) => location.href = '{{ url("artmin/installation/request") }}');
            // location.href = '{{ url("artmin/warranty") }}';
          })
        }
      });
  });

  $('.datepicker').datepicker({
    format: 'dd/mm/yyyy',
    todayHighlight: true
  });

  $('.datepicker-today').datepicker({
    format: 'dd-mm-yyyy',
    endDate: '0d',
    todayHighlight: true
  })

  var date = new Date();
  date.setDate(date.getDate() + 1);

  $('#prefered_date').datepicker({
    daysOfWeekDisabled: [0],
    // startDate: date,
    format: 'dd-mm-yyyy',
  })
</script>

@endsection