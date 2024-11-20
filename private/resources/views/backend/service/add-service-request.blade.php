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
              <input type="hidden" name="warranty_id" value="{{ $warranty->warranty_id }}">
              <div class="form-group">
                <label>Jenis Layanan</label>
                <select class="form-control select2" name="svc_type">
                  <option value="">Pilih Jenis Layanan</option>
                  <option value="presale" {{old('svc_type') == 'presale' ? 'selected' : ''}}>Presale</option>
                  <option value="enduser" {{old('svc_type') == 'enduser' ? 'selected' : ''}}>End User</option>
                </select>
                @if ($errors->has('svc_type'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
									Jenis Layanan wajib diisi!
                </label>
								@endif
              </div>
              <div class="form-group">
                <label>Kategori Masalah</label>
                <select class="form-control select2" name="problem_category">
                  <option value="">Pilih kendala</option>
                  <option value="Tidak Nyala" {{old('problem_category') == 'Tidak Nyala' ? 'selected' : ''}}>Tidak nyala</option>
                  <option value="Tidak Dingin" {{old('problem_category') == 'Tidak Dingin' ? 'selected' : ''}}>Tidak dingin</option>
                  <option value="Lain-lain" {{old('problem_category') == 'Lain-lain' ? 'selected' : ''}}>Lain-lain</option>
                </select>
                @if ($errors->has('problem_category'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
                  Kategori Masalah wajib diisi!
                </label>
								@endif
              </div>
              <div class="form-group">
                <label>Deskripsi Masalah</label>
                <textarea class="form-control form-dark" name="problem" rows="10" placeholder="Mohon deskripsikan masalah pada produk anda.">{{old('problem')}}</textarea>
                @if ($errors->has('problem'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
                  Deskripsi Masalah wajib diisi!
                </label>
								@endif
              </div>
              <div class="form-group">
                <label>Preferensi Tanggal Kunjungan</label>
                <input type="text" class="form-control form-dark" id="prefered_date" name="prefered_date"  placeholder="dd-mm-yyyy" value="{{old('prefered_date')}}">
                @if ($errors->has('prefered_date'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
                  Preferensi Tanggal Kunjungan wajib diisi!
                </label>
								@endif
              </div>
              <div class="form-group">
                <label>Preferensi Jam Kunjungan</label>
                <select class="form-control form-dark" name="prefered_time">
                  <option value="1" {{old('prefered_time') == '1' ? 'selected' : ''}}>10:00 - 12:00</option>
                  <option value="2" {{old('prefered_time') == '2' ? 'selected' : ''}}>13:00 - 15:00</option>
                  <option value="3" {{old('prefered_time') == '3' ? 'selected' : ''}}>15:00 - 17:00</option>
                </select>
                @if ($errors->has('prefered_time'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
                  Preferensi Jam Kunjungan wajib diisi!
                </label>
								@endif
              </div>
              <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control form-dark" name="name" value="{{ old('name', $warranty->reg_name ?? '') }}" placeholder="Nama">
                @if ($errors->has('name'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
                  Nama wajib diisi!
                </label>
								@endif
              </div>
              <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" class="form-control form-dark" name="phone" value="{{ old('phone', $warranty->reg_phone ?? '') }}" placeholder="Phone Number">
                @if ($errors->has('phone'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
                  Nomor Telepon wajib diisi!
                </label>
								@endif
              </div>
              <div class="form-group">
                <label>Alamat Kunjungan</label>
                <textarea class="form-control form-dark" name="address" rows="5" placeholder="Alamat kunjungan">{{ old('address', $warranty->reg_address ?? '') }}</textarea>
                @if ($errors->has('address'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
                  Alamat Kunjungan wajib diisi!
                </label>
								@endif
              </div>
              <div class="form-group">
                <label>Kota</label>
                <select class="form-control select2" name="city">
                  <option value="">Pilih Kota</option>
                  @foreach($city as $key => $val)
                  <option {{ ($val->city_id == old('city', $warranty->reg_city_id ?? '') ? 'selected="true"' : null) }} value="{{ $val->city_id }}">{{ $val->city_name }}</option>
                  @endforeach
                </select>
                @if ($errors->has('city'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
                  Kota wajib diisi!
                </label>
								@endif
              </div>
              <div class="form-group">
                <button id="btn-reload" class="btn btn-warning"><i class="fa fa-undo"></i> Refresh Page</button>
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

  $("#btn-reload").on('click', function(e){
    e.preventDefault();
    location.reload()
  });

  // $(document).on('submit', '.fdata', function(e) {
  //   e.preventDefault();

  //   swal({
  //       title: "Konfirmasi",
  //       text: "Apakah data yang anda masukan telah sesuai?",
  //       icon: "info",
  //       buttons: true,
  //     })
  //     .then((willDelete) => {
  //       if (willDelete) {
  //         let url = $(this).attr('action');
  //         let data = $(this).serializeArray();

  //         $.post(url, data, function(retData) {
  //           console.log(retData);
  //           // swal('Berhasil','Installation Request Telah Dibuat','success').then((confirm) => location.href='{{ url("artmin/service/request") }}');
  //           // location.href = '{{ url("artmin/service/request") }}';
  //         })
  //       }
  //     });
  // });

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
      // startDate: date,
      format: 'dd-mm-yyyy',
  })
</script>

@endsection