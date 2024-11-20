@extends('backend.layouts.backend-app')
@section('title', 'Warranty')
@section('content')
<link rel="stylesheet" type="text/css" href="{{url('assets/backend/plugins/daterangepicker/daterangepicker.css?v=3.1')}}" />
<script type="text/javascript" src="{{ url('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<div>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Warranty
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
										<label for="">Purchase Date <i>[kosongkan jika tidak memakai parameter ini]</i></label>
										<input type="text" class="form-control dp_range" name="purchase_date_filter" value="{{ $period }}" placeholder="Purchase Date">
									</div>
									<div class="col-md-6">
										<label for="">Registered Date <i>[kosongkan jika tidak memakai parameter ini]</i></label>
										<input type="text" class="form-control dp_range" name="registered_date_filter" value="{{ ($registered ?? null) }}" placeholder="Registered Date">
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-4">
										<label for="">Warranty No</label>
										<input type="text" class="form-control" name="warranty_no_filter" value="{{ ($_GET['warranty_no_filter'] ?? null) }}" placeholder="Warranty No">
									</div>
									<div class="col-md-4">
										<label for="">Serial No</label>
										<input type="text" class="form-control" name="serial_no_filter" value="{{ ($_GET['serial_no_filter'] ?? null) }}" placeholder="Serial No">
									</div>
									<div class="col-md-4">
										<label for="">Product</label>
										<select name="product_id_filter" class="form-control select2">
											<option value="-">[All Product]</option>
											@foreach($products as $val)
											<option {{ ( !empty($_GET['product_id_filter']) ? ( $val->product_id == $_GET['product_id_filter'] ? 'selected="true"' : null ) : null ) }} value="{{ $val->product_id }}">{{ $val->product_name??'' }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-3">
										<label for="">Artugo Product Consultant</label>
										<select name="promotor_id" class="form-control select2">
											<option value="-">[All Promotor]</option>
											@foreach($promotor as $val)
											<option {{ ( !empty($_GET['promotor_id']) ? ( $val->id == $_GET['promotor_id'] ? 'selected="true"' : null ) : null ) }} value="{{ $val->id }}">{{ $val->name }}</option>
											@endforeach
										</select>
									</div>
									<div class="col-md-3">
										<label for="">Member Name</label>
										<input type="text" class="form-control" placeholder="Member Name" value="{{ ($_GET['member_name_filter'] ?? null) }}" name="member_name_filter">
									</div>
									<div class="col-md-3">
										<label for="">Member Phone</label>
										<input type="text" class="form-control" placeholder="Member Phone" value="{{ ($_GET['member_phone_filter'] ?? null) }}" name="member_phone_filter">
									</div>
									<div class="col-md-3">
										<label for="">Status Verify</label>
										<select name="status_verify" class="form-control">
											<option {{ ( !empty($_GET['status_verify']) ? ($_GET['status_verify'] == '-' ? 'selected="true"' : null) : null ) }} value="-">[All Status]</option>
											<option {{ ( !empty($_GET['status_verify']) ? ($_GET['status_verify'] == '3' ? 'selected="true"' : null) : null ) }} value="3">Pending</option>
											<option {{ ( !empty($_GET['status_verify']) ? ($_GET['status_verify'] == '1' ? 'selected="true"' : null) : null ) }} value="1">Verified</option>
											<option {{ ( !empty($_GET['status_verify']) ? ($_GET['status_verify'] == '2' ? 'selected="true"' : null) : null ) }} value="2">Rejected</option>
										</select>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-3">
										<label for="">Status</label>
										<select name="status" class="form-control">
											<option {{ ( !empty($_GET['status']) ? ($_GET['status'] == '-' ? 'selected="true"' : null) : null ) }} value="-">[All Status]</option>
											<option {{ ( !empty($_GET['status']) ? ($_GET['status'] == '1' ? 'selected="true"' : null) : null ) }} value="1">Active</option>
											<option {{ ( !empty($_GET['status']) ? ($_GET['status'] == '2' ? 'selected="true"' : null) : null ) }} value="2">Non-Active</option>
										</select>
									</div>
								</div>

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

				<br>
				<div class="box box-solid">
					<div class="box-header">
						<h3 class="box-title">List Warranty</h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<a href="{{ url('artmin/warranty/add-warranty') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Warranty</a>
								</div>
								<div class="col-md-6 pull-right">
									<button class="btn btn-primary pull-right toggle-filter"><i class="fa fa-filter"></i>Filter</button>

									<a href="{{ url('artmin/warranty/export-warranty-pdf') }}" style="margin-right: 10px;" class="btn btn-primary pull-right"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Export Data PDF</a>
									<!-- <a href="{{ url('artmin/warranty/export-warranty-excel') }}" style="margin-right: 10px;" class="btn btn-primary pull-right"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Data Excel</a> -->
									<button style="margin-right: 10px;" class="btn btn-primary pull-right btn-export-excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Data Excel</button>

								</div>
							</div>
						</div>

						<div class="col-sm-12 table-responsive">
							<table class="table table-bordered data-table-ns">
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
										<th>Status</th>
										<th>Verified</th>
										<th>Promotor</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; ?>

									<?php foreach ($warranty as $row) : ?>
										<tr>

											<td>{{ $row->warranty_no }}</td>
											<td>{{ $row->serial_no }}</td>
											<td>{{ date('d-m-Y', strtotime($row->purchase_date)) }}</td>
											<td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
											<td>{{ $row->reg_name }}</td>
											<td>{{ $row->reg_phone }}</td>
											<td>{{ $row->reg_email }}</td>
											<td>{{ $row->product_name??'' }}</td>
											<td>
												<?php
												$now = date('Y-m-d H:i:s');

												if ($row->status == '1') {
												?>
													Active
												<?php
												} else {
												?>
													Non-Active
												<?php
												}
												?>
											</td>
											<td>
												<center>
													<?php
													if ($row->verified == '1') {
													?>
														<i data-toggle="tooltip" title="Verified" style="color:green" class="fa fa-check-circle"></i>
													<?php
													} elseif ($row->verified == '2') {
													?>
														<i data-toggle="tooltip" title="Rejected" style="color:red" class="fa fa-times-circle"></i>
													<?php
													} else {
													?>
														<i data-toggle="tooltip" title="Pending" style="color:#294E5D" class="fa fa-minus-circle"></i>
													<?php
													}
													?>
												</center>
											</td>
											<td>{{ (!empty($row->spg_name) ? $row->spg_name : '-') }}</td>
											<td>
												<a href="{{ url('artmin/warranty/information/' . $row->warranty_id) }}" style="margin:3px" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Details"><i class="fa fa-search"></i></a>


												<?php
												if ($row->status == '1') {
												?>
													<a style="margin:3px" class="btn btn-primary btn-xs btn-assign-promotor" data-toggle="tooltip" title="Assign Promotor" warranty_id="{{ $row->warranty_id }}" warranty_no="{{ $row->warranty_no }}"><i class="fa fa-user" aria-hidden="true"></i></a>
													<?php
													if ($row->verified == '0') {
													?>
														<button style="margin:3px" class="btn btn-primary btn-xs btn-check-verification-data" data='<?php echo str_replace("'", " ", json_encode($row)) ?>' type="verification" data-toggle="tooltip" title="Verification Data Check"><i class="fa fa-check-circle"></i></button>
													<?php
													} else {
													?>
														<button style="margin:3px" class="btn btn-primary btn-xs btn-check-verification-data" data='<?php echo str_replace("'", " ", json_encode($row)) ?>' type="revisi" data-toggle="tooltip" title="Revisi Data"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
														<a href="{{ url('artmin/service/request/add-service-request/'.$row->warranty_id) }}"><button style="margin:3px" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Create Service Request"><i class="fa fa-cogs" aria-hidden="true"></i></button></a>

														<?php
														$initial = explode(' ', $row->product_code)[0];
														$check_installation = DB::table('ms_problems_initial')->where('initial', $initial)->first();
														if (!empty($check_installation)) {
															if ($check_installation->need_installation == '1') {
														?>
																<a href="{{ url('artmin/installation/request/add-installation-request/'.$row->warranty_id) }}"><button style="margin:3px" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Create Installation Request"><i class="fa fa-wrench" aria-hidden="true"></i></button></a>
														<?php
															}
														}
														?>

													<?php
													}
													?>

													<?php
													$show_exchange = false;
													if (Auth::user()->roles == '1') {
														$show_exchange = true;
													}
													// }elseif (Auth::user()->id == '8' ) {
													// 	$show_exchange = true;
													// }


													if ($show_exchange) {
													?>
														<button style="margin:3px" class="btn btn-primary btn-xs btn-exchange" warranty_id="{{ $row->warranty_id }}" warranty_no="{{ $row->warranty_no }}" serial_no="{{ $row->serial_no }}" product_name_exchange="{{ $row->product_name??'' }}" type="cancel" data-toggle="tooltip" title="Exchange Product"><i class="fa fa-exchange"></i></button>
													<?php
													}
													?>
													@if ($row->stock_type !== 'stkdisplay')
													<button style="margin:3px" class="btn btn-primary btn-xs btn-stkdisplay-warranty" warranty_id="{{ $row->warranty_id }}" warranty_no="{{ $row->warranty_no }}" type="stkdisplay" data-toggle="tooltip" title="Stok Display"><i class="fa fa-th"></i></button>
													@endif
													<button style="margin:3px" class="btn btn-primary btn-xs btn-cancel-warranty" warranty_id="{{ $row->warranty_id }}" warranty_no="{{ $row->warranty_no }}" type="cancel" data-toggle="tooltip" title="Cancel Warranty"><i class="fa fa-times-circle"></i></button>
												<?php
												}
												?>
											</td>
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


<div class="modal mdata" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title modal-header-verification">Verification Data Check</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="fdata">
					{{ csrf_field() }}
					<input type="hidden" name="warranty_id">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-4">
									Member Name
								</div>
								<div class="col-md-6">
									: <span class="member_name"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									Member Email
								</div>
								<div class="col-md-6">
									: <span class="member_email"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									Member Phone Number
								</div>
								<div class="col-md-6">
									: <span class="member_phone_number"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									NIK / KTP
								</div>
								<div class="col-md-6">
									: <span class="member_ktp"></span>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-md-4">
									Serial Number
								</div>
								<div class="col-md-6">
									: <span class="serial_number"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									Product
								</div>
								<div class="col-md-6">
									: <span class="product_name"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									Purchase Date
								</div>
								<div class="col-md-6">
									: <span class="purchase_date"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									Purchase Location
								</div>
								<div class="col-md-6">
									: <span class="purchase_location"></span>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-md-12">
									<center>
										<img src="" class="purchase_invoice" style="width:100%" alt="">
										<br> Purchase Invoice
									</center>
								</div>
							</div>

						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-accept-data">Accept</button>
				<button type="button" class="btn btn-danger btn-denied-data">Reject</button>
				<button class="btn btn-primary btn-revisi-data">Revisi Data</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


<div class="modal m_revisi" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Revisi Data Warranty</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{ url('artmin/warranty/revisi?' . Arr::query(app('request')->request->all()) ) }}" method="POST" class="fdata_revisi" enctype="multipart/form-data" autocomplete="off">
					{{ csrf_field() }}
					<input type="hidden" name="warranty_id">
					<div class="form-group">
						<label>
							Purchase Date
						</label>
						<input type="text" class="form-control datepicker-today" name="purchase_date" placeholder="DD-MM-YYYY" value="">
					</div>
					<div class="form-group">
						<label>
							Purchase Location
						</label>
						<input type="text" class="form-control" name="purchase_loc" placeholder="Toko Pembelian Produk" value="">
					</div>
					<div class="form-group">
						<label>
							Purchase Invoice
						</label>
						<center>
							<img class="preview_image" id="preview" src="" style="width:50%" alt="">
						</center>
						<input type="file" class="form-control" id="image" name="purchase_invoice" value="" accept="image/*" style="height: 43px;">
					</div>
					<div class="form-group">
						<label>Full Name</label>
						<input type="text" class="form-control" name="name" placeholder="Nama Anda">
					</div>
					<div class="form-group">
						<label>NIK / KTP</label>
						<input type="text" class="form-control" name="nik_ktp" placeholder="NIK / KTP">
					</div>
					<div class="form-group">
						<label>Email</label>
						<input type="email" class="form-control" name="email" placeholder="Email">
					</div>
					<div class="form-group">
						<label>Phone Number</label>
						<input type="text" class="form-control" name="phone" placeholder="Nomor Telepon">
					</div>
					<div class="form-group">
						<label>Address</label>
						<textarea type="text" class="form-control" name="address" placeholder="Alamat"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-save-revisi">Save changes</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal m_assignpromotor" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Assign Promotor</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="f_assignpromotor">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-12">
							<label for="">Promotor</label>
							<select name="promotor" class="form-control select2">
								<option value="-">[Pilih Promotor]</option>
								@foreach($promotor as $val)
								<option value="{{ $val->id }}">{{ $val->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-save-assign-promotor" warranty_id="" warranty_no="">Save changes</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>



<div class="mExchange modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Product Exchange</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="fExchange">
					{{ csrf_field() }}
					<input type="hidden" name="exchange_warranty_id">
					<input type="hidden" name="serial_no_old">

					<div class="row">
						<div class="col-md-3">
							<b>Product</b>
						</div>
						<div class="col-md-6">
							: <span class="product_name_exchange"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<b>Serial Number</b>
						</div>
						<div class="col-md-6">
							: <span class="serial_no_old"></span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<label for="">New Serial Number</label>
							<input type="text" name="serial_no_new" class="form-control" placeholder="New Serial Number">
							<label for="" style="color: red;" class="info_valid_serial"></label>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-submit-exchange">Exchange</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
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
	// $('.data-table-ns').DataTable({
	// 	"aaSorting": []
	// });


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


	$(document).on('click', '.btn-exec-export', function(e) {
		e.preventDefault();


		let tanggal = $('[name="period"]').val();
		let tglFrom = tanggal.split(' ')[0].split('/').join('-');
		let tglTo = tanggal.split(' ')[2].split('/').join('-');

		let url = "{{ url('artmin/warranty/export-warranty-excel') }}" + '/' + tglFrom + '/' + tglTo;

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

	$('.dp_range').on('cancel.daterangepicker', function(ev, picker) {
		$(this).val('');
	});

	$(document).on('click', '.btn-export-excel', function(e) {
		e.preventDefault();
		$('.m-export-excel').modal('show');
	});

	$(document).on('click', '.btn-submit-exchange', function(e) {
		e.preventDefault();
		$('.fExchange').submit();
	});


	$(document).on('submit', '.fExchange', function(e) {
		e.preventDefault();
		swal({
				title: "Confirmation",
				text: "Are you sure to exchange this product?",
				icon: "info",
				buttons: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$('.btn-submit-exchange').text('Loading...');
					let url = '{{ url("artmin/warranty/exchange") }}';
					let data = $(this).serializeArray();

					$.post(url, data, function(retData) {
						let retd = JSON.parse(retData);
						console.log(retd);
						console.log('==================================');
						console.log(data);
						if (retd.status) {
							swal('Success', 'Product has been exchanged', 'success').then((confirm) => location.reload());
						} else {
							$('.info_valid_serial').text(retd.message);
						}

						$('.btn-submit-exchange').text('Exchange');
					});
				}
			});
	});


	$(document).on('click', '.btn-exchange', function(e) {
		e.preventDefault();

		let warranty_id = $(this).attr('warranty_id');
		let warranty_no = $(this).attr('warranty_no');
		let serial_no = $(this).attr('serial_no');
		let product_name_exchange = $(this).attr('product_name_exchange');

		$('[name="exchange_warranty_id"]').val(warranty_id);
		$('[name="serial_no_old"]').val(serial_no);
		$('.serial_no_old').text(serial_no);
		$('.product_name_exchange').text(product_name_exchange);

		$('.mExchange').modal('show');

	});

	$(document).on('click', '.btn-cancel-warranty', function(e) {
		e.preventDefault();

		let warranty_id = $(this).attr('warranty_id');
		let warranty_no = $(this).attr('warranty_no');

		swal({
				title: "Confirmation",
				text: "Are you sure to cancel Warranty No. " + warranty_no,
				icon: "info",
				buttons: true,
				dangerMode: true
			})
			.then((willDelete) => {
				if (willDelete) {
					let url = '{{ url("artmin/warranty/cancel") }}';
					let data = {
						"_token": "{{ csrf_token() }}",
						"warranty_id": warranty_id,
					}

					$.post(url, data, function(ret) {
						swal('Success', 'Warranty Has Been Canceled', 'success').then((confirm) => location.reload());
					});
				}
			});

	});

	$(document).on('click', '.btn-stkdisplay-warranty', function(e) {
		e.preventDefault();

		let warranty_id = $(this).attr('warranty_id');
		let warranty_no = $(this).attr('warranty_no');
		let stock_type = $(this).attr('type');

		swal({
			title: "Confirmation",
			text: "Are you sure to set Warranty No. " + warranty_no + " as Stock Display?",
			icon: "info",
			buttons: true,
			dangerMode: true
		})
		.then((confirm) => {
			if (confirm) {
				let url = '{{ url("artmin/warranty/set-stocktype") }}';
				let data = {
					"_token": "{{ csrf_token() }}",
					"warranty_id": warranty_id,
					"stock_type": stock_type,
				}

				$.post(url, data, function(ret) {
					swal('Success', 'Warranty Has Been Canceled', 'success').then((confirm) => location.reload());
				});
			}
		});

	});

	$(document).on('click', '.btn-save-assign-promotor', function(e) {
		e.preventDefault();

		let users_id = $('[name="promotor"] option:selected').val();
		let users_name = $('[name="promotor"] option:selected').text();
		let warranty_id = $(this).attr('warranty_id');
		let warranty_no = $(this).attr('warranty_no');

		if (users_id != '-') {
			swal({
					title: "Confirmation",
					text: "Are you sure to assign Warranty No. " + warranty_no + " to " + users_name,
					icon: "info",
					buttons: true,
				})
				.then((willDelete) => {
					if (willDelete) {
						let url = '{{ url("artmin/warranty/assign-promotor") }}';
						let data = {
							"_token": "{{ csrf_token() }}",
							"warranty_id": warranty_id,
							"users_id": users_id
						}

						$.post(url, data, function(ret) {
							swal('Success', 'Data Has Been Saved', 'success').then((confirm) => location.reload());
						});

					}
				});
		} else {
			swal('Please Choose Promotor');
		}


	});

	$(document).on('click', '.btn-assign-promotor', function(e) {
		e.preventDefault();
		$('.m_assignpromotor').modal('show');

		let warranty_id = $(this).attr('warranty_id');
		let warranty_no = $(this).attr('warranty_no');

		$('.btn-save-assign-promotor').attr('warranty_id', warranty_id);
		$('.btn-save-assign-promotor').attr('warranty_no', warranty_no);

	});

	$(document).ready(function(e) {
		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function(e) {
					$('#preview').attr('src', e.target.result);
				}

				reader.readAsDataURL(input.files[0]); // convert to base64 string
			}
		}

		$("#image").change(function() {
			readURL(this);
		});
	});

	$(document).on('click', '.btn-save-revisi', function(e) {
		e.preventDefault();
		swal({
				title: "Confirmation",
				text: "Are you sure to edit this data warranty registration?",
				icon: "info",
				buttons: true
			})
			.then((willDelete) => {
				if (willDelete) {
					// let url = $('.fdata_revisi').attr('action');
					// let data = $('.fdata_revisi').serializeArray();

					$('.fdata_revisi').submit();

				}
			});
	});


	$('.datepicker-today').datepicker({
		format: 'yyyy-mm-dd',
		endDate: '0d',
		todayHighlight: true
	});

	function clear_val_revisi() {
		$('[name="warranty_id"]').val('');
		$('[name="purchase_date"]').val('');
		$('[name="purchase_loc"]').val('');
		$('[name="name"]').val('');
		$('[name="email"]').val('');
		$('[name="phone"]').val('');
		$('[name="address"]').val('');
		$('[name="nik_ktp"]').val('');
	}

	$(document).on('click', '.btn-revisi-data', function(e) {
		e.preventDefault();
		clear_val_revisi();

		let data = JSON.parse($(this).attr('data'));

		$('[name="warranty_id"]').val(data.warranty_id);
		$('[name="purchase_date"]').val(data.purchase_date);
		$('[name="purchase_loc"]').val(data.purchase_loc);
		$('[name="name"]').val(data.reg_name);
		$('[name="email"]').val(data.reg_email);
		$('[name="phone"]').val(data.reg_phone);
		$('[name="address"]').val(data.reg_address);
		$('[name="nik_ktp"]').val(data.nik_ktp);
		$('.preview_image').attr('src', data.files);

		$('.mdata').modal('hide');
		$('.m_revisi').modal('show');
	});

	$('.m_revisi').on('hidden.bs.modal', function() {
		$('.mdata').modal('show');
	});


	function clear_val() {
		$('[name="warranty_id"]').val('');
		$('.member_name').text('');
		$('.member_email').text('');
		$('.member_phone_number').text('');
		$('.member_ktp').text('');
		$('.purchase_date').text('');
		$('.purchase_location').text('');
		$('.serial_number').text('');
		$('.product_name').text('');
		$('.purchase_invoice').attr('src', '');
	}

	$(document).on('click', '.btn-check-verification-data', function(e) {
		e.preventDefault();
		let data = JSON.parse($(this).attr('data'));
		let type = $(this).attr('type');

		if (type == 'verification') {
			$('.btn-accept-data').show();
			$('.btn-denied-data').show();
			$('.modal-header-verification').text('Verification Data Check');
		} else {
			$('.btn-accept-data').hide();
			$('.btn-denied-data').hide();
			$('.modal-header-verification').text('Revisi Data');
		}


		$('.btn-revisi-data').attr('data', $(this).attr('data'));

		clear_val();

		$('[name="warranty_id"]').val(data.warranty_id);
		$('.member_name').text(data.reg_name);
		$('.member_email').text(data.reg_email);
		$('.member_phone_number').text(data.reg_phone);
		$('.member_ktp').text(data.nik_ktp);
		$('.purchase_date').text(data.purchase_date);
		$('.purchase_location').text(data.purchase_loc);
		$('.serial_number').text(data.serial_no);
		$('.product_name').text(data.product_name);
		$('.purchase_invoice').attr('src', data.files);

		console.log(data);
		$('.mdata').modal('show');

	});

	function updateStatusVerified(status) {
		let url = '{{ url("artmin/warranty/verified") }}';
		let data = {
			"_token": "{{ csrf_token() }}",
			"warranty_id": $('[name="warranty_id"]').val(),
			"status": status
		};

		$.post(url, data, function(data) {
			if (!data.success && data.message) {
				var message = data.message;
				if (data.messages && Array.isArray(data.messages)) {
					message += "\n\n" + data.messages.join("\n");
				} else if (data.messages && Object.keys(data.messages).length > 0) {
					message += "\n\n" + Object.keys(data.messages).map((key) => data.messages[key].join("\n")).join("\n");
				}
				swal('Attention!', message, 'error');
			} else {
				swal('Success', 'Berhasil di Verifikasi!', 'success').then((confirm) => location.reload());
			}
		});
	}

	$(document).on('click', '.btn-accept-data', function(e) {
		e.preventDefault();
		swal({
				title: "Confirmation",
				text: "Are you sure to accept this warranty data?",
				icon: "info",
				buttons: true
			})
			.then((confirmed) => {
				if (confirmed) {
					updateStatusVerified(1);
				}
			});
	});

	$(document).on('click', '.btn-denied-data', function(e) {
		e.preventDefault();
		swal({
				title: "Confirmation",
				text: "Are you sure to denied this warranty data?",
				icon: "warning",
				buttons: true,
				dangerMode: true
			})
			.then((willDelete) => {
				if (willDelete) {
					updateStatusVerified(2);
				}
			});
	});
</script>

@endsection