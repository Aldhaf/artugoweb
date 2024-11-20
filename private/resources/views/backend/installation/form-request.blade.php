@extends('backend.layouts.backend-app')
@section('title', 'Add New Service Request')
@section('content')
<script type="text/javascript" src="{{ url('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Service Request
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
            <form class="fdata" action="{{ url('artmin/service/request/add-service-request') }}" method="post" autocomplete="off">
              {{ csrf_field() }}
              
              <div class="form-group">
                <label>Kategori Masalah</label>
                <select class="form-control select2" name="problem_category">
                  <option value="">Pilih kendala</option>
                  <option value="Tidak Nyala">Tidak nyala</option>
                  <option value="Tidak Dingin">Tidak dingin</option>
                  <option value="Lain-lain">Lain-lain</option>
                </select>
              </div>
              <div class="form-group">
                <label>Deskripsi Masalah</label>
                <textarea class="form-control form-dark" name="problem" rows="10" placeholder="Mohon deskripsikan masalah pada produk anda."></textarea>
              </div>
              <div class="form-group">
                <label>Preferensi Tanggal Kunjungan</label>
                <input type="text" class="form-control form-dark" id="prefered_date" name="prefered_date"  placeholder="dd-mm-yyyy">
              </div>
              <div class="form-group">
                <label>Preferensi Jam Kunjungan</label>
                <select class="form-control form-dark" name="prefered_time">
                  <option value="1">10:00 - 12:00</option>
                  <option value="2">13:00 - 15:00</option>
                  <option value="3">15:00 - 17:00</option>
                </select>
              </div>
              <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control form-dark" name="name" value="{{ $warranty->reg_name }}" placeholder="Nama">
              </div>
              <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" class="form-control form-dark" name="phone" value="{{ $warranty->reg_phone }}" placeholder="Phone Number">
              </div>
              <div class="form-group">
                <label>Alamat Kunjungan</label>
                <textarea class="form-control form-dark" name="address" rows="5" placeholder="Alamat kunjungan">{{ $warranty->reg_address }}</textarea>
              </div>
              <div class="form-group">
                <label>Kota</label>
                <select class="form-control select2" name="city">
                  <option value="">Pilih Kota</option>
                  @foreach($city as $key => $val)
                  <option {{ ($val->city_id == $warranty->reg_city_id ? 'selected="true"' : null) }} value="{{ $val->city_id }}">{{ $val->city_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <button class="btn btn-primary"><i class="fa fa-plus"></i> Submit Service Request</button>
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
              
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                Serial Number
              </div>
              <div class="col-md-9">
              
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-3">
                Member Name
              </div>
              <div class="col-md-9">
              
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                Member Phone
              </div>
              <div class="col-md-9">
              
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                Member Email
              </div>
              <div class="col-md-9">
              
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
            location.href = '{{ url("artmin/service/request") }}';
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
    date.setDate(date.getDate()+1);

    $('#prefered_date').datepicker({
        daysOfWeekDisabled: [0],
        startDate: date,
        format: 'dd-mm-yyyy',
    })
</script>

@endsection