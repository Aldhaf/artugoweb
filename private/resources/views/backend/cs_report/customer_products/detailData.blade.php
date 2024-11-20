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

        


        <div class="form-group">
          <div class="row">
            <div class="col-md-6">
              <button style="margin-right: 10px;" class="btn btn-primary btn-export-excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Data Excel</button>
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
										<th>Warranty No.</th>
										<th>Serial Number</th>
										<th>Purchase Date</th>
										<th>Registered At</th>
										<th>Member Name</th>
										<th>Phone</th>
										<th>Email</th>
										<th>Product</th>
                  </tr>
                </thead>
                <tbody>
									<?php $i = 1; ?>

									<?php foreach ($customer_products as $row) : ?>
										<tr>

											<td>{{ $row->warranty_no }}</td>
											<td>{{ $row->serial_no }}</td>
											<td>{{ date('d-m-Y', strtotime($row->purchase_date)) }}</td>
											<td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
											<td>{{ $row->reg_name }}</td>
											<td>{{ $row->reg_phone }}</td>
											<td>{{ $row->reg_email }}</td>
											<td>{{ $row->product_name??'' }}</td>
										</tr>
									<?php endforeach; ?>
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