@extends('backend.layouts.backend-app')
@section('title', 'Store Location')
@section('content')
<link rel="stylesheet" type="text/css" href="{{url('assets/backend/plugins/daterangepicker/daterangepicker.css?v=3.1')}}" />
<script type="text/javascript" src="{{ url('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Store Location
      <small>Region</small>
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
              <a href="{{ url('artmin/region/add') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Region</a>
              <!-- <a href="{{ url('artmin/region/export-excel') }}"> -->
              <button style="margin-right: 10px;" class="btn btn-primary pull-right btn-export-excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Data Excel</button>
              <!-- </a> -->
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th width="50">#</th>
                    <th>Region</th>
                    <th width="60">Store</th>
                    <th width="120"><center>Action</center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  function count_store($regional_id)
                  {
                    return DB::table('store_location')->where('regional_id', $regional_id)->count();
                  }
                  ?>
                  @foreach($region as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->regional_name }}</td>
                    <td>{{ count_store($val->id) }}</td>
                    <td align="center">
                      <a href="{{ url('artmin/region/edit/'.$val->id) }}">
                        <button class="btn btn-default">Edit</button>
                      </a>
                      <a href="{{ url('artmin/storelocation/'.$val->id) }}">
                        <button class="btn btn-default">List Store</button>
                      </a>
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




<div class="modal m-export-excel" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Export Excel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <label for="">Period</label>
            <input type="text" class="form-control dp_range" placeholder="Period" value="{{ $period }}" name="period">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-exec-export" type="excel">Export</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="{{url('assets/backend/plugins/daterangepicker/moment.min.js')}}"></script>
<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{url('assets/backend/plugins/daterangepicker/daterangepicker.min.js?v=3.1')}}"></script>

<script>
  $(document).on('click', '.btn-exec-export', function(e) {
    e.preventDefault();


    let tanggal = $('[name="period"]').val();
    let tglFrom = tanggal.split(' ')[0].split('/').join('-');
    let tglTo = tanggal.split(' ')[2].split('/').join('-');

    let url = "{{ url('artmin/storelocation/region/export-excel') }}" + '/' + tglFrom + '/' + tglTo;

    window.open(url, '_blank');


  });

  $('.dp_range').daterangepicker({
    autoUpdateInput: false,
    allowEmpty: true,
    locale: {
      format: 'YYYY-MM-DD'
    }
  });

  $('.dp_range').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
  });


  $(document).on('click', '.btn-export-excel', function(e) {
    e.preventDefault();
    $('.m-export-excel').modal('show');
  });
</script>

@endsection