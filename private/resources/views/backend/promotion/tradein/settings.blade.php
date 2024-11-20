@extends('backend.layouts.backend-app')
@section('title', 'Trade In')
@section('content')

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Trade In
      <small>Settings</small>
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
              <!-- <button class="btn btn-primary btn-add-period"><i class="fa fa-gear"></i> Add Period</button> -->
              <a href="{{ url('artmin/promotion/tradein/settings/add-period') }}">
                <button class="btn btn-primary"><i class="fa fa-gear"></i> Add Period</button>
              </a>
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Periode Mulai Dari</th>
                    <th>Periode Sampai</th>
                    <th>Status</th>
                    <!-- <th>Action</th> -->
                  </tr>
                </thead>
                <tbody>
                  @foreach($periode as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ date('d M Y',strtotime($val->startDate)) }}</td>
                    <td>{{ date('d M Y',strtotime($val->endDate)) }}</td>
                    <td>{{ ($val->status == '1' ? 'Active' : 'Non-Active') }}</td>
                    <!-- <td>
                      <button class="btn btn-primary">Edit</button>
                    </td> -->
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



<div class="modal mperiod" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Periode</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" class="fdata">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-6">
              <label for="">Start Date</label>
              <input type="text" placeholder="Start Date" class="form-control datepicker" name="startDate">
            </div>

            <div class="col-md-6">
              <label for="">End Date</label>
              <input type="text" placeholder="End Date" class="form-control datepicker" name="endDate">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-submit-period">Save Data</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>
  var statusAction = '';

  $(document).on('click', '.btn-add-period', function(e) {
    e.preventDefault();

    $('.modal-title').text('Add New Period');

    $('.mperiod').modal('show');
  });

  $(document).on('click', '.btn-submit-period', function(e) {
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
          let url = '{{ url("artmin/promotion/tradein/settings/period") }}';
          let data = $(this).serializeArray();

          $.post(url, data, function(ret) {
            console.log(ret);
            // swal('Success','Data Has Been Saved','success').then((confirm) => location.reload());
          });
        }
      });

  });
</script>

@endsection