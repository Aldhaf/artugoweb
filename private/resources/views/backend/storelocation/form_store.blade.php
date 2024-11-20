@extends('backend.layouts.backend-app')
@section('title', ($statusAction == 'insert' ? 'Add' : 'Edit').' Store Location')
@section('content')
<link rel="stylesheet" href="{{ url('assets/plugins/datepicker/datepicker3.css') }}" type="text/css" />
<script type="text/javascript" src="{{ url('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Store Location
      <small>{{ ($statusAction == 'insert' ? 'Add' : 'Edit') }}</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <form autocomplete="off" class="fdata" action="{{ ($statusAction == 'insert' ? url('artmin/storelocation/process-add') : url('artmin/storelocation/process-edit') ) }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="store_id" value="{{ ($store->id ?? null) }}">
        <input type="hidden" name="regional_id" value="{{ $regional_id }}">

        <div class="col-md-12">
          <div class="box box-solid">
            <!-- <div class="box-header">
              <h3 class="box-title">Products</h3>
            </div> -->
            <div class="box-body">
              @include('backend.layouts.alert')
              <div class="form-group">
                <label>
                  Nama Toko
                  <span class="error_message_nama_toko"></span>
                </label>
                <input type="text" class="form-control form-dark" id="nama_toko" name="nama_toko" placeholder="Nama Toko" value="{{ ($store->nama_toko ?? null) }}">
              </div>

              <div class="form-group">
                <label>
                  Partner ID (ODOO)
                  <span class="error_message_nama_toko"></span>
                </label>
                <select name="partner_id" class="form-control select2">
                  @foreach($data_partner as $val)
                  <option value="{{ $val[0] }}">{{ $val[1] }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label>
                  Alamat Toko
                  <span class="error_message_alamat_toko"></span>
                </label>
                <textarea name="alamat_toko" class="form-control" cols="10" rows="5" placeholder="Alamat Toko">{{ ($store->alamat_toko ?? null) }}</textarea>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>
                      Latitude
                      <span class="error_message_alamat_toko"></span>
                    </label>
                    <input type="text" class="form-control form-dark" value="{{ ($store->latitude ?? null) }}" id="latitude" name="latitude" placeholder="Latitude">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>
                      Longitude
                      <span class="error_message_alamat_toko"></span>
                    </label>
                    <input type="text" class="form-control form-dark" id="longitude" name="longitude" placeholder="Longitude" value="{{ ($store->longitude ?? null) }}">
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label>
                  Discount Area
                  <span class="error_message_discount_area"></span>
                </label>
                <input type="number" class="form-control form-dark" id="discount_area" name="discount_area" placeholder="Discount Area" value="{{ ($store->discount_area ?? null) }}">
              </div>
              

            </div>
          </div>
          <button class="btn btn-primary btn-submit"><i class="fa fa-plus"></i> {{($statusAction == 'insert' ? 'Add' : 'Edit')}} Store Location</button>
          <button class="btn btn-back">Back</button>
        </div>

      </form>
    </div><!-- /.row -->
  </section><!-- /.content -->
</div>

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>
  <?php
  if($statusAction == 'update'){
    ?>
    $('[name="partner_id"]').val('{{ $store->partner_id }}');
    <?php
  }
  ?>
  $(document).on('click', '.btn-back', function(e) {
    e.preventDefault();

    location.href = '{{ url("artmin/storelocation/".$regional_id) }}';
  });

  $(document).on('submit', '.fdata', function(e) {
    e.preventDefault()
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

          $.post(url, data, function(e) {
            swal('Berhasil', 'Data Telah Disimpan', 'success').then((confirm) => location.reload());
          });
        }
      });
  });

  $(document).on('click', '.btn-submit', function(e) {
    e.preventDefault();
    $('.fdata').submit();

  });
</script>


@endsection