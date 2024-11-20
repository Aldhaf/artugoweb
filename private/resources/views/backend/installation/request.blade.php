@extends('backend.layouts.backend-app')
@section('title', 'Installation Request')
@section('content')
<link rel="stylesheet" type="text/css" href="{{url('assets/backend/plugins/daterangepicker/daterangepicker.css?v=3.1')}}" />
<script type="text/javascript" src="{{ url('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<div>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Installation
			<small>Request List</small>
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
							<div class="row">
								<div class="col-md-6">
									<!-- <a href="{{ url('artmin/installation/request/browse-warranty') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Create Installation Request</a> -->
								</div>
								<div class="col-md-6 pull-right">
									<!-- <a href="{{ url('artmin/installation/request/export-installation-request-pdf') }}" class="btn btn-primary pull-right"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Export Data PDF</a> -->
									<!-- <a href="{{ url('artmin/installation/request/export-installation-request-excel') }}" style="margin-right: 10px;" class="btn btn-primary pull-right"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Data Excel</a> -->
									<button style="margin-right: 10px;" class="btn btn-primary pull-right btn-export" rpt-type="excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Data Excel</button>
									<button style="margin-right: 10px;" class="btn btn-primary pull-right btn-export" rpt-type="pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Export Data PDF</button>
								</div>
							</div>
						</div>

						<!-- <div class="col-sm-12 table-container">
							<table class="table table-bordered data-table"> -->
						<div class="col-sm-12 table-responsive">
							<table class="table data-table table-sm table-bordered table-hover installation-table">
								<thead>
									<tr>
										<th><label>#</label></th>
										<th><label style="width: 105px;">Installation No.</label></th>
										<th><label style="width: 110px;">Installation Date</label></th>
										<th><label style="width: 110px;">Prefered Visit</label></th>
										<th><label style="width: 130px;">Name</label></th>
										<th><label style="width: 100px;">Phone</label></th>
										<th><label style="width: 100px;">City</label></th>
										<th><label style="width: 150px;">Product</label></th>
										<th><label style="width: 95px;">Serial No</label></th>
										<th><label style="width: 80px;">Status</label></th>
										<th><center><label style="width: 80px;">Action</label></center></th>
									</tr>
								</thead>
								<?php /*
								<tbody>
									<?php $i = 1; ?>
									<?php foreach ($service as $srv) : ?>
										<tr>
											<td><?= $i++ ?></td>
											<td><span style="width: 105px;">{{ $srv->service_no }}</span></td>
											<td><span style="width: 110px;">{{ date('d-m-Y', strtotime($srv->created_at)) }}</span></td>
											<td><span style="width: 110px;">{{ date('d-m-Y', strtotime($srv->prefered_date)) }}</span></td>
											<td><span style="width: 130px;">{{ $srv->contact_name }}</span></td>
											<td><span style="width: 100px;">{{ $srv->contact_phone }}</span></td>
											<td><span style="width: 100px;">{{ $srv->service_city }}</span></td>
											<td><span style="width: 150px;">{{ $srv->product_name_odoo }}</span></td>
											<td><span style="width: 95px;">{{ $srv->serial_no }}</span></td>
											<td><span style="width: 80px;">
												<?php if ($srv->status == 0) echo "On Progress";
												else echo "Completed"; ?>
												</span>
											</td>
											<td>
												<center>
												<a title="Detail" href="{{ url('artmin/installation/request-details/' . $srv->service_id) }}" class="btn btn-primary btn-xs"><i class="fa fa-search"></i></a>
												<!-- <button title="Delete" class="btn btn-danger btn-xs btn-delete" service_id="{{ $srv->service_id }}" service_no="{{ $srv->service_no }}"><i class="fa fa-trash"></i></button> -->
												@if(Auth::user()->roles == '1')
												<button title="Delete" class="btn btn-danger btn-xs btn-delete" service_id="{{ $srv->service_id }}" service_no="{{ $srv->service_no }}"><i class="fa fa-trash"></i></button>
												@endif
												</center>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
								*/ ?>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div><!-- /.row -->
	</section><!-- /.content -->
</div>




<div class="modal m-export" tabindex="-1" role="dialog">
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



<div class="modal m-delete" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Delete Confirmation</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<center>
					<h3>Are you sure to delete this service request data?</h3>
				</center>
				<br>
				<form action="" class="fdata-delete">
					{{ csrf_field() }}
					<input type="hidden" class="form-control" name="service_id_del">
					<div class="row">
						<div class="col-md-12">
							<label for="">Delete Reason Description</label>
							<textarea name="delete-description" placeholder="Delete Reason Description" cols="30" rows="10" class="form-control"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-submit-delete">Delete</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script src="{{url('assets/backend/plugins/daterangepicker/moment.min.js')}}"></script>
<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{url('assets/backend/plugins/daterangepicker/daterangepicker.min.js?v=3.1')}}"></script>

<script>

	// $(document).on('click', '.btn-delete', function(e) {
	// 	e.preventDefault();

	// 	let service_id = $(this).attr('service_id');
	// 	let service_no = $(this).attr('service_no');

	// 	swal({
	// 			title: "Confirmation",
	// 			text: "Are you sure to delete this Installation Request?",
	// 			icon: "warning",
	// 			buttons: true,
	// 			dangerMode: true,
	// 		})
	// 		.then((willDelete) => {
	// 			if (willDelete) {
	// 				let url = '{{ url("artmin/installation/delete") }}';
	// 				let data = {
	// 					"_token": "{{ csrf_token() }}",
	// 					"service_id": service_id
	// 				};
	// 				$.post(url, data, function(r) {
	// 					swal('Success', 'Installation Request Has Been Deleted', 'success').then((confirm) => location.reload());
	// 				});
	// 			}
	// 		});
	// });

	
	var service_id;
	var service_no;

	$(document).on('click', '.btn-delete', function(e) {
		e.preventDefault();

		service_id = $(this).attr('service_id');
		service_no = $(this).attr('service_no');

		$('[name="service_id_del"]').val(service_id);

		$('.m-delete').modal('show');
	});

	$(document).on('click', '.btn-submit-delete', function(e) {
		e.preventDefault();

		swal({
				title: "Confirmation",
				text: "Are you sure to delete this Installation Request?",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					let url = '{{ url("artmin/installation/delete") }}';
					let data = {
						"_token": "{{ csrf_token() }}",
						"service_id": service_id,
						"reason": $('[name="delete-description"]').val()
					};
					$.post(url, data, function(r) {
						swal('Success', 'Installation Request Has Been Deleted', 'success').then((confirm) => location.reload());
					});
				}
			});
	});


	$(document).on('click', '.btn-exec-export', function(e) {
		e.preventDefault();


		let tanggal = $('[name="period"]').val();
		let tglFrom = tanggal.split(' ')[0].split('/').join('-');
		let tglTo = tanggal.split(' ')[2].split('/').join('-');

		const rptType = $(".m-export").attr("rpt-type");
		let url = `{{ url('artmin/installation/request/export-installation-request-${rptType}') }}` + '/' + tglFrom + '/' + tglTo;

		window.open(url, '_blank');


	});

	$('.dp_range').daterangepicker({
		locale: {
			format: 'YYYY-MM-DD'
		}
	});

	$(document).on('click', '.btn-export', function(e) {
		e.preventDefault();
		const rptType = $(this).attr("rpt-type");
		$(".m-export").find(".modal-title").html("Export " + (rptType === "pdf" ? "PDF" : "Excel"));
		$(".m-export").attr("rpt-type", rptType);
		$(".m-export").modal('show');
	});

	$(document).ready( function() {
		var isSuperAdmin = "{{ Auth::user()->roles }}" === "1";
		$('.data-table').DataTable().clear().destroy();
		$('.installation-table').DataTable({
			paging: true,
			searching: true,
			processing: true,
			serverSide: true,

			retrieve: true,
			responsive: false,
			destroy: true,

			ajax: '{!! route('artmin.installation.list') !!}', // memanggil route yang menampilkan data json
			columns: [
				{
					data: "service_type",
					name: "service_type",
					// orderable: false,
					// searchable: false,
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{
					data: 'service_no',
					name: 'service_no',
					searchable: true
				},
				{
					data: 'created_at',
					name: 'created_at',
					render : function (data, type, row) {
						return moment(data).format('DD-MM-YYYY');
					}
				},
				{
					data: 'prefered_date',
					name: 'prefered_date',
					render : function (data, type, row) {
						return moment(data).format('DD-MM-YYYY');
					}
				},
				{
					data: 'contact_name',
					name: 'contact_name',
					searchable: true
				},
				{
					data: 'contact_phone',
					name: 'contact_phone',
					searchable: true
				},
				{
					data: 'service_city',
					name: 'service_city',
					searchable: true
				},
				{
					data: 'product_name_odoo',
					name: 'ms_products.product_name_odoo',
					searchable: true
				},
				{
					data: 'serial_no',
					name: 'reg_warranty.serial_no',
					searchable: true
				},
				{
					data: 'status',
					name: 'status',
					searchable: false,
					render : function (data, type, row) {
						if(data === 0){
							return 'On Progress';
						} else if(data === 1) {
							return 'Completed';
						} else if(data === 2) {
							return 'Cancel';
						}
					}
				},
				{
					data: null,
					searchable: false,
					render : function (data, type, row) {
						const delBtn = isSuperAdmin ? `<button title="Delete" class="btn btn-danger btn-xs btn-delete" service_id="${row.service_id}" service_no="${row.service_no}"><i class="fa fa-trash"></i></button>` : "";
						return `
							<center>
							<a href="{{ url('artmin/installation/request-details/${row.service_id}') }}" class="btn btn-primary btn-xs"><i class="fa fa-search"></i></a>
							${delBtn}
							</center>
						`;
					}
				}
			],
			order: [[2, 'desc' ], [9, 'asc' ]]
		});
	});
</script>
@endsection