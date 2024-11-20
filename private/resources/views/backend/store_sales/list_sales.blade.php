@extends('backend.layouts.backend-app')
@section('title', 'Store Sales')
@section('content')
<link rel="stylesheet" type="text/css" href="{{url('assets/backend/plugins/daterangepicker/daterangepicker.css?v=3.1')}}" />
<script type="text/javascript" src="{{ url('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<div>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Store Sales
			<small>Data</small>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->

		<!-- <div class="row">
			<div class="col-md-12">
				<button class="btn btn-primary btn-toggle-filter">Filter</button>
			</div>
		</div>
		<br> -->
		<div class="section-filter">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-solid">
						<div class="box-body">
							<div class="col-md-12">
								<form action="" method="GET" class="fdata_filter">
									<div class="row">
										<?php /* @if (Auth::user()->roles == '8') */ ?>
										<div class="col-md-3">
											<div class="form-group">
												<label>Branch Store</label>
												<select class="form-control" name="store_id_filter">
													<option value="" {{array_search(intval($store_id_filter), array_column($store_location->toArray(),'id')) === false ? 'selected' : ''}}>All</option>
													@foreach($store_location as $val)
													<option value="{{ $val->id }}" {{ $val->id == $store_id_filter ? 'selected' : '' }}>{{ $val->nama_toko }}</option>
													@endforeach ?>
												</select>
											</div>
										</div>
										<?php /* @endif */ ?>
										<div class="col-md-3">
											<label for="">Sales Date
												<!-- <i>[kosongkan jika tidak memakai parameter ini]</i> -->
											</label>
											<input type="text" class="form-control dp_range" name="sales_date_filter" value="{{ $period }}" placeholder="Sales Date" autocomplete="off">
										</div>
										<div class="col-md-2">
											<label for="">Status</label>
											<select class="form-control" name="status_filter">
												<option value="" {{ $status_filter=='' ? 'selected' : '' }}>All</option>
												<option value="0" {{ $status_filter=='0' ? 'selected' : '' }}>Draft</option>
												<option value="1" {{ $status_filter=='1' ? 'selected' : '' }}>Request Approve</option>
												<option value="2" {{ $status_filter=='2' ? 'selected' : '' }}>Approved</option>
												<option value="3" {{ $status_filter=='3' ? 'selected' : '' }}>Un Approve</option>
											</select>
										</div>
										<div class="col-md-3">
											<label for="">Keywords</label>
											<input type="search" class="form-control" name="keywords" value="{{ $keywords }}" placeholder="Masukan kata kunci..." autocomplete="off" >
										</div>
										<div class="col-md-1">
											<div class="form-group">
												<label for="" style="color: transparent;">.</label>
												<button class="btn btn-primary btn-submit-filter">Submit</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">

			<div class="col-sm-12">
				<div class="box box-solid">
					<!-- <div class="box-header">
			          <h3 class="box-title">Products</h3>
			        </div> -->
					<div class="box-body">
						<?php if (Auth::user()->roles == '5') { ?>
							<div class="form-group">
								<a href="{{ url('artmin/storesales/report-sales') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Buat Laporan Penjualan</a>
							</div>
						<?php } ?>
						<!-- <div class="col-sm-12 table-container">
							<table class="table datat" style="width: 100%;"> -->
						<div class="col-sm-12 table-responsive">
							<table class="table data-table table-sm table-bordered table-hover sales-table" style="width: 100%;">
								<thead>
									<tr>
										<th>#</th>
										<th width="190">Store</th>
										<th width="100">Date / Sales No</th>
										<!-- <th>No Penjualan</th> -->
										<th width="90">APC</th>
										<th>Customer Name</th>
										<th>Customer Phone</th>
										<th>Status</th>
										<th></th>
									</tr>
								</thead>
								<?php /*
								<tbody>
									@foreach($sales as $key => $val)
									<tr>
										<td>{{ $key + 1 }}</td>
										<td>{{ date('d M Y',strtotime($val->sales_date)) }}</td>
										<td>{{ $val->sales_no }}</td>
										<td>{{ $val->apcName }}</td>
										<td>{{ $val->customer_nama }}</td>
										<td align="right">
											<a href="{{ url('artmin/storesales/products/'.$val->sales_id) }}">
												<button style="margin:5px" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Detail</button>
											</a>
											<?php if (Auth::user()->roles == '5') { ?>
												<?php if ($val->status == 0) { ?>
												<a href="{{ url('artmin/storesales/edit/'.$val->sales_id) }}">
												<button style="margin:5px" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</button>
												</a>
												<button style="margin:5px" class="btn btn-xs btn-danger btn-success btn-approve" sales_id="{{ $val->sales_id }}" sales_no="{{ $val->sales_no }}"><i class="fa fa-paper-plane"></i> Approve</button>
												<?php } ?>
												<button style="margin:5px" class="btn btn-xs btn-danger btn-delete" sales_id="{{ $val->sales_id }}" sales_no="{{ $val->sales_no }}"><i class="fa fa-trash"></i> Hapus</button>
											<?php } ?>
										</td>
									</tr>
									@endforeach
								</tbody> */
								?>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div><!-- /.row -->
	</section><!-- /.content -->
</div>

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<!-- <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->
<script src="{{url('assets/backend/plugins/daterangepicker/moment.min.js')}}"></script>
<script src="{{url('assets/backend/plugins/daterangepicker/daterangepicker.min.js?v=3.1')}}"></script>
<script>

	// var initFilter = false;

	// if(initFilter){
	// 	$('.section-filter').show();
	// }else{
	// 	$('.section-filter').hide();
	// }

	// $(document).on('click', '.btn-toggle-filter', function(e) {
	// 	e.preventDefault();
	// 	initFil();
	// });

	// function initFil() {
	// 	if (initFilter) {
	// 		initFilter = false;
	// 		$('.section-filter').hide();
	// 	} else {
	// 		initFilter = true;
	// 		$('.section-filter').show();
	// 	}
	// }

	// $('.sales-table').DataTable({
	// 	"sScrollX": '100%',
    //     "aaSorting": []
	// });

	var store_id_filter = "{{$store_id_filter}}";

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

	function approveProcess (sales_id, sales_no) {

		const role = {{Auth::user()->roles}};
		const confirmMessage = role === 8 ? "Setelah di Approve data akan di registrasikan ke Warranty." : "Setelah di Approve data tidak bisa di ubah.";

		swal({
			title: "Konfirmasi",
			text: confirmMessage,
			icon: "info",
			buttons: true,
			dangerMode: true,
		})
		.then((willApprove) => {
			if (willApprove) {
				let url = '{{ url("artmin/storesales/approve") }}';
				let data = {
					"_token": "{{ csrf_token() }}",
					"sales_id": sales_id
				}
				$.post(url, data, function(res) {
					if (res.success) {
						swal('Berhasil', `Data penjualan ${sales_no} ${role === 8 ? "telah di Approve" : "menunggu di Approve!"}`, 'success').then((confirm) => location.reload());
					} else {
						const message = res.message || `Gagal ${role === 8 ? "Approve" : "Request Approve"} penjualan ${sales_no}!`;
						swal('Gagal', message, 'error');
					}
				});
			}
		});
	}

	$(document).on('click', '.btn-approve', function(e) {
		e.preventDefault();

		let sales_id = $(this).attr('sales_id');
		let sales_no = $(this).attr('sales_no');
		let apc_name = $(this).attr('apc_name');

		let url = '{{ url("artmin/storesales/approve-confirm") }}';
		let data = {
			"_token": "{{ csrf_token() }}",
			"sales_id": sales_id
		}

		$.post(url, data, function(res) {
			if (res.message === "") {
				approveProcess(sales_id, sales_no);
			} else {
				swal({
					title: "Konfirmasi!",
					text: res.message,
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willApprove) => {
					if (willApprove) {
						approveProcess(sales_id, sales_no);
					}
				});
			}
		});
	});
	
	$(document).on('click', '.btn-delete', function(e) {
		e.preventDefault();

		let sales_id = $(this).attr('sales_id');
		let sales_no = $(this).attr('sales_no');

		swal({
				title: "Konfirmasi",
				text: `Apakah anda yakin untuk menghapus laporan penjualan ${sales_no}?`,
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					let url = '{{ url("artmin/storesales/delete") }}';
					let data = {
						"_token": "{{ csrf_token() }}",
						"sales_id": sales_id
					}
					$.post(url, data, function(e) {
						swal('Berhasil', 'Data penjualan ' + sales_no + ' telah dihapus', 'success').then((confirm) => location.reload());
					});
				}
			});
	});

	$(document).ready( function() {

		$('.data-table').DataTable().clear().destroy();
		$('.sales-table').DataTable({
			paging: true,
			info: false,
			searching: false,
			processing: true,
			serverSide: true,
			retrieve: true,
			responsive: false,
			destroy: true,

			ajax: '{!! route('artmin.storesales.list') !!}' + location.search, // memanggil route yang menampilkan data json
			columns: [
				{
					data: "sales_id",
					name: "sales_id",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{
					data: 'nama_toko',
					name: 'nama_toko',
					// searchable: false
					render : function (data, type, row) {
						return `<p>${row.regional_name}</p><p>${row.nama_toko}</p>`;
					}
				},
				{
					data: 'sales_date',
					name: 'sales_date',
					render : function (data, type, row) {
						return `<p>${moment(data).format('DD-MMM-YYYY')}</p><p>${row.sales_no}</p>`;
					}
				},
				// {
				// 	data: 'sales_no',
				// 	name: 'sales_no',
				// 	searchable: true
				// },
				{
					data: 'apc_name',
					name: 'apc_name',
					searchable: false
				},
				{
					data: 'customer_nama',
					name: 'customer_nama',
					searchable: false
				},
				{
					data: 'customer_telp',
					name: 'customer_telp',
					searchable: false
				},
				{
					data: 'status',
					name: 'status',
					searchable: false,
					render : function (data, type, row) {
						if(data === 0){
							return 'Draft';
						} else if(data === 1) {
							return 'Request Approve';
						} else if(data === 2) {
							return 'Approved';
						} else if(data === 3) {
							return 'Rejected';
						}
					}
				},
				{
					data: null,
					searchable: false,
					render : function (data, type, row) {

						const role = {{Auth::user()->roles}};

						let btnApprove = "";
						let btnDelete = "";
						const iconClass = role === 5 ? "paper-plane": "check-circle";
						const tooltip = role === 5 ? "Request Approve": "Approve";

						if (row.status !== 3 && ((role === 5 && row.status === 0) || (role === 8 && row.status === 1))) {
							btnApprove = `<a sales_id="${row.sales_id}" sales_no="${row.sales_no}" apc_name="${row.apc_name}" class="btn btn-success btn-xs btn-approve" data-toggle="tooltip" title data-original-title="${tooltip}"><i class="fa fa-${iconClass}"></i></a>`;
						} else {
							btnApprove = `<a class="btn btn-success btn-xs disabled"><i class="fa fa-${iconClass} disabled"></i></a>`;
						}

						if (row.status < 2) {
							btnDelete = `<a sales_id="${row.sales_id}" sales_no="${row.sales_no}" apc_name="${row.apc_name}" class="btn btn-danger btn-xs btn-delete" data-toggle="tooltip" title data-original-title="Delete"><i class="fa fa-trash"></i></a>`;
						}

						return `
							<center style="display: flex; gap: 4px;">
							<a href="{{ url('artmin/storesales/edit/${row.sales_id}') }}" class="btn btn-primary btn-xs" data-toggle="tooltip" title data-original-title="Detail"><i class="fa fa-search"></i></a>
							${btnApprove}
							${btnDelete}
							</center>
						`;
					}
				}
			],
			order: [[1, 'desc' ],[2, 'desc' ]],
			drawCallback: function (settings) {
			},
		});

	});
</script>
@endsection