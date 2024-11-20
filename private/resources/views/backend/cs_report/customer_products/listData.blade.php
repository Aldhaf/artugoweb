@extends('backend.layouts.backend-app')
@section('title', 'Customer Products')
@section('content')
<link rel="stylesheet" type="text/css" href="{{url('assets/backend/plugins/daterangepicker/daterangepicker.css?v=3.1')}}" />
<script type="text/javascript" src="{{ url('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Customer Products
      <small>Data</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <div class="col-sm-12">

        <div class="box box-solid form-filter" hidden="true">
          <div class="box-header">
            <h3 class="box-title">Filter</h3>
          </div>
          <div class="box-body">
            <div class="form-group">
              <form method="GET">
                <div class="row">
                  <div class="col-md-6">
                    <label for="">Purchase Date</label>
                    <input type="text" class="form-control dp_range" name="purchase_date_filter" value="{{ $period }}" placeholder="Purchase Date">
                  </div>
                  <div class="col-md-6">
                    <label for="">Limit <i>[hanya menampilkan baris data sesuai limit]</i></label>
                    <input type="number" class="form-control dp_range" name="limit" value="{{ ($limit ?? null) }}" placeholder="Registered Date">
                  </div>
                </div>
                <br>


                <div class="row" style="margin-top: 20px;">
                  <div class="col-md-12">
                    <input type="submit" value="Apply" class="btn btn-primary">
                    <button style="margin-left: 10px;" class="btn">Reset</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>


        <div class="form-group">
          <div class="row">
            <div class="col-md-6">
              <button style="margin-right: 10px;" class="btn btn-primary btn-export-excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Data Excel</button>
            </div>
            <div class="col-md-6 pull-right">
              <button class="btn btn-primary pull-right toggle-filter"><i class="fa fa-filter"></i>Filter</button>
            </div>
          </div>
        </div>

        <div class="box box-solid">
          <!-- <div class="box-header">
			          <h3 class="box-title">Products</h3>
			        </div> -->
          <div class="box-body">
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Registered Product</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($customer_products as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->name }}</td>
                    <td>{{ $val->phone }}</td>
                    <td>{{ $val->email }}</td>
                    <td>{{ $val->registered_products }}</td>
                    <td>
                      <?php
                      if (isset($_GET['purchase_date_filter'])) {
                      ?>
                        <a href="{{ url('artmin/csreport/customer_products/'.$val->id.'?purchase_date_filter='.$_GET['purchase_date_filter']) }}">
                          <button class="btn btn-sm btn-primary">Detail</button>
                        </a>
                      <?php
                      } else {
                      ?>

                        <a href="{{ url('artmin/csreport/customer_products/'.$val->id) }}">
                          <button class="btn btn-sm btn-primary">Detail</button>
                        </a>
                      <?php
                      }
                      ?>
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
            <input type="text" class="form-control dp_range" placeholder="Period" name="period">
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
  var toggle_filter = false;
  $(document).on('click', '.toggle-filter', function(e) {
    e.preventDefault();

    if (toggle_filter) {
      $('.form-filter').attr('hidden', true);
      toggle_filter = false;
    } else {
      $('.form-filter').attr('hidden', false);
      toggle_filter = true;
    }

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

  $('.dp_range').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
  });



  $('.datepicker-today').datepicker({
    format: 'yyyy-mm-dd',
    endDate: '0d',
    todayHighlight: true
  });



  $(document).on('click', '.btn-export-excel', function(e) {
    e.preventDefault();
    $('.m-export-excel').modal('show');
  });


  $(document).on('click', '.btn-exec-export', function(e) {
    e.preventDefault();


    let tanggal = $('[name="period"]').val();
    let tglFrom = tanggal.split(' ')[0].split('/').join('-');
    let tglTo = tanggal.split(' ')[2].split('/').join('-');

    let url = "{{ url('artmin/csreport/export-customer-products') }}" + '/' + tglFrom + '/' + tglTo;

    window.open(url, '_blank');


  });
</script>



@endsection