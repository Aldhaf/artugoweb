@extends('backend.layouts.backend-app')
@section('title', 'Service Center')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Service Center
      <small>{{ $region->regional_name }} | Data</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    {{ csrf_field() }}
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <div class="col-sm-12">
        <div class="box box-solid">
          <!-- <div class="box-header">
			          <h3 class="box-title">Products</h3>
			        </div> -->
          <div class="box-body">
            <div class="form-group">
              <a href="{{ url('artmin/servicecenter/add/'.$region->id) }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Service Center</a>
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th width="70">Code</th>
                    <th width="120">Service Center</th>
                    <th>Address</th>
                    <th><center>Action</center></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($service_center as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->sc_code }}</td>
                    <td>{{ $val->sc_location }}</td>
                    <td>{{ $val->sc_address }}</td>
                    <td align="center">
                      <a href="{{ url('artmin/servicecenter/edit/'.$val->region_id.'/'.$val->sc_id) }}" class="btn btn-primary btn-xs"><i class="fa fa-search"></i></a>
                      <button action="{{ url('artmin/servicecenter/process-delete/'.$val->region_id.'/'.$val->sc_id) }}" sc_location="{{$val->sc_location}}" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></button>
                      <!-- <a href="{{ url('artmin/servicecenter/assignpromotor/'.$val->region_id.'/'.$val->sc_id) }}"><button class="btn btn-primary">Service</button></a>
                      <a href="{{ url('artmin/servicecenter/salesreport/'.$val->region_id.'/'.$val->sc_id) }}"><button class="btn btn-primary">Installation</button></a> -->
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /.row -->
  </section><!-- /.content -->
</div>

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>
$(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();

    var sc_location = $(this).attr('sc_location');
    swal({
        title: "Konfirmasi",
        text: `Apakah anda yakin akan menghapus Service Center ${sc_location}?`,
        icon: "warning",
        buttons: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = $(this).attr('action');
          $.ajax({
            url: url,
            method: 'DELETE',
            data: { '_token': $('[name="_token"]').val()},
            success: function(res) {
              if (res.success) {
                swal('Berhasil', `Service Center ${sc_location} telah dihapus.`, 'success').then((confirm) => location.reload());
              } else {
                swal('Gagal', res.message, 'error');
              }
            }
          });

        }
      });
  });
</script>

@endsection