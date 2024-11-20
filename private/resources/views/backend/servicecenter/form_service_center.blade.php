@extends('backend.layouts.backend-app')
@section('title', ($statusAction == 'insert' ? 'Add' : 'Edit').' Service Center')
@section('content')
<link rel="stylesheet" href="{{ url('assets/plugins/datepicker/datepicker3.css') }}" type="text/css" />
<script type="text/javascript" src="{{ url('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Service Center
      <small>{{ ($statusAction == 'insert' ? 'Add' : 'Edit') }}</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <form autocomplete="off" class="col-md-12 fdata" action="{{ ($statusAction == 'insert' ? url('artmin/servicecenter/process-add') : url('artmin/servicecenter/process-edit') ) }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="sc_id" value="{{ ($service_center->sc_id ?? null) }}">
        <input type="hidden" name="region_id" value="{{ $region_id }}">

        <div class="col-md-6">
          <div class="box box-solid">
            <!-- <div class="box-header">
              <h3 class="box-title">Products</h3>
            </div> -->
            <div class="box-body">
              @include('backend.layouts.alert')

              <div class="form-group">
                <label>
                  Code
                  <span class="error_message_sc_code"></span>
                </label>
                <input type="text" class="form-control form-dark" id="sc_code" name="sc_code" placeholder="Service Center Code" value="{{ ($service_center->sc_code ?? null) }}">
              </div>

              <div class="form-group">
                <label>
                  Name
                  <span class="error_message_sc_location"></span>
                </label>
                <input type="text" class="form-control form-dark" id="sc_location" name="sc_location" placeholder="Service Center Name" value="{{ ($service_center->sc_location ?? null) }}">
              </div>

              <div class="form-group">
                <label>
                  Address
                  <span class="error_message_sc_address"></span>
                </label>
                <textarea name="sc_address" class="form-control" cols="10" rows="5" placeholder="Service Center Address">{{ ($service_center->sc_address ?? null) }}</textarea>
              </div>

              <div class="form-group">
                <label>
                  Phone
                  <span class="error_message_sc_phone"></span>
                </label>
                <input class="form-control form-dark" id="sc_phone" name="sc_phone" placeholder="Phone" value="{{ ($service_center->sc_phone ?? null) }}">
              </div>

            </div>
          </div>
          <button class="btn btn-primary btn-submit"><i class="fa fa-plus"></i> {{($statusAction == 'insert' ? 'Add' : 'Edit')}} Service Center</button>
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

    location.href = '{{ url("artmin/servicecenter/".$region_id) }}';
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
            swal('Berhasil', 'Data Telah Disimpan', 'success').then((confirm) => location.href = '{{ url("artmin/servicecenter/".$region_id) }}');
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