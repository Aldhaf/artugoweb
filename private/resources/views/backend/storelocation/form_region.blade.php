@extends('backend.layouts.backend-app')
@section('title', ($statusAction == 'insert' ? 'Add' : 'Edit').' Region')
@section('content')
<link rel="stylesheet" href="{{ 'assets/plugins/datepicker/datepicker3.css') }}" type="text/css" />
<script type="text/javascript" src="{{ 'assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Region
      <small>{{ ($statusAction == 'insert' ? 'Add' : 'Edit') }}</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <form autocomplete="off" class="fdata" action="{{ ($statusAction == 'insert' ? url('artmin/storelocation/region/process-add') : url('artmin/storelocation/region/process-edit') ) }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="region_id" value="{{ ($region->id ?? null) }}">

        <div class="col-md-6">
          <div class="box box-solid">
            <!-- <div class="box-header">
              <h3 class="box-title">Products</h3>
            </div> -->
            <div class="box-body">
              @include('backend.layouts.alert')




              <div class="form-group">
                <label>
                  Nama Region
                  <span class="error_message_nama_region"></span>
                </label>
                <input type="text" class="form-control form-dark" id="nama_region" name="nama_region" placeholder="Nama Toko" value="{{ ($region->regional_name ?? null) }}">
              </div>

            </div>
          </div>
          <button class="btn btn-primary btn-submit"><i class="fa fa-plus"></i> {{($statusAction == 'insert' ? 'Add' : 'Edit')}} Region</button>
          <button class="btn btn-back">Back</button>
        </div>

      </form>
    </div><!-- /.row -->
  </section><!-- /.content -->
</div>

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>
  $(document).on('click', '.btn-back', function(e) {
    e.preventDefault();

    location.href = '{{ url("artmin/storelocation") }}';
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