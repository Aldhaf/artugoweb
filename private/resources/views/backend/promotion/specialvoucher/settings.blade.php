@extends('backend.layouts.backend-app')
@section('title', 'Special Voucher')
@section('content')

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Special Voucher
      <small>Data</small>
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
              <!-- <button class="btn btn-primary btn-add-unique-number"><i class="fa fa-gear"></i> Add Unique Number</button> -->
              <a href="{{ url('artmin/promotion/specialvoucher/settings/add') }}">
                <button class="btn btn-primary"><i class="fa fa-gear"></i> Add Unique Number</button>
              </a>
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Unique Number</th>
                    <th>Cashback</th>
                    <th>Period Date From</th>
                    <th>Period Date To</th>
                    <th>Status</th>
                    <th></th>
                  </tr>
                </thead>
                @foreach($unique_number as $key => $val)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $val->unique_number }}</td>
                  <td>{{ number_format($val->cashback) }}</td>
                  <td>{{ date('d M Y',strtotime($val->date_from)) }}</td>
                  <td>{{ date('d M Y',strtotime($val->date_to)) }}</td>
                  <td>{{ ($val->status == '1' ? 'Active' : 'Non-Active') }}</td>
                  <td>
                    <a href="{{ url('artmin/promotion/specialvoucher/settings/edit/'.$val->id) }}">
                      <button class="btn btn-primary">Edit</button>
                    </a>
                    <button class="btn btn-danger">Delete</button>
                  </td>
                </tr>
                @endforeach
                <tbody>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /.row -->
  </section><!-- /.content -->
</div>


<div class="modal mdata" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Unique Number</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="fdata">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-12">
              <label for="">Unique Number</label>
              <input type="text" class="form-control" name="unique_number" placeholder="Unique Number">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6">
              <label for="">Period Date From</label>
              <input type="text" class="form-control datepicker" name="period_from" placeholder="Period Date From">
            </div>
            <div class="col-md-6">
              <label for="">Period Date To</label>
              <input type="text" class="form-control datepicker" name="period_to" placeholder="Period Date To">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-submit-data">Save Data</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>


<script>
  $(document).on('click', '.btn-add-unique-number', function(e) {
    e.preventDefault();

    $('.btn-submit-data').text('Add Unique Number');

    $('.mdata').modal('show');
  });

  $(document).on('click', '.btn-submit-data', function(e) {
    e.preventDefault();

    $('.fdata').submit();

  });

  $(document).on('submit', '.fdata', function(e) {
    e.preventDefault();

    swal({
        title: "Confirmation",
        text: "Are you sure to submit this data?",
        icon: "info",
        buttons: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/promotion/specialvoucher/save") }}';
          let data = $(this).serializeArray();

          $.post(url, data, function(e) {
            swal('Success', 'Data Has Been Saved', 'success').then((confirm) => location.reload());
          });
        }
      });
  });
</script>

@endsection