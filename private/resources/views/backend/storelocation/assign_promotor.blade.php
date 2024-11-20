@extends('backend.layouts.backend-app')
@section('title', 'Assign Promotor')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Assign Promotor
      <small>{{ $region->regional_name }} | {{ $store->nama_toko }}</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <div class="col-sm-12">
        <div class="box box-solid">
          <!-- <div class="box-header">
			          <h3 class="box-title">Products</h3>
			        </div> -->
          <div class="box-body">
            <div class="form-group">
              <button class="btn btn-primary btn-assign-promotor"><i class="fa fa-plus"></i> Assign Promotor</button>
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($inchargespg as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->name }}</td>
                    <td>
                      <button class="btn btn-sm btn-danger btn-remove" users_id="{{ $val->id }}">Remove</button>
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



<div class="modal" tabindexxxxx="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Assign Promotor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="fdata">
          {{ csrf_field() }}
          <input type="hidden" name="store_id" value="{{ $store->id }}">
          <div class="row">
            <div class="col-md-12">
              <label for="">Promotor</label>
              <select name="users_id" class="form-control select2">
                <option value="-">[Choose Promotor]</option>
                @foreach($spg as $key => $val)
                <option value="{{ $val->id }}">{{ $val->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-assign-promotor-process">Assign Promotor</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script>
  $(document).on('click', '.btn-assign-promotor', function(e) {
    e.preventDefault();
    $('.modal').modal('show');
  });

  $(document).on('click', '.btn-assign-promotor-process', function(e) {
    e.preventDefault();

    swal({
        title: "Confirmation",
        text: "Are you sure to assign this promotor to this store?",
        icon: "info",
        buttons: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/storelocation/assign_promotor_process") }}';
          let data = $('.fdata').serializeArray();
          $.post(url, data, function(ret) {
            swal('Success', 'Data Has Been Saved', 'success').then((confirm) => location.reload());
          });
        }
      });
  });

  $(document).on('click', '.btn-remove', function(e) {
    e.preventDefault();

    swal({
        title: "Confirmation",
        text: "Are you sure to remove this promotor from this store",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/storelocation/remove_promotor_process") }}';
          let data = {
            "_token": "{{ csrf_token() }}",
            "users_id": $(this).attr('users_id'),
            "store_id": {{$store->id}}
          };

          $.post(url, data, function(ret) {
            swal('Success', 'Promotr Has Been Removed', 'success').then((confirm) => location.reload());
          });
        }
      });
  });
</script>

@endsection