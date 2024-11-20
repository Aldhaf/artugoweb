@extends('backend.layouts.backend-app')
@section('title', 'Serial Number')
@section('content')
<div>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Serial Number
			<small>Data</small>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->

		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<a href="{{ url('artmin/product/serialnumber/add-serialnumber') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Serial Number</a>
					</div>
				</div>
				<br>
				<div class="box box-solid">
					<div class="box-body">
						<form method="GET">
							<div class="row">
								<div class="col-md-6">
									<label for="">Product</label>
									<select name="product" class="form-control select2">
										<option value="-">All Product</option>
										@foreach($products as $val)
										<option value="{{ $val->product_id }}">{{ $val->product_name_odoo }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-md-3">
									<label for="">Prefix</label>
									<input type="text" name="prefix" placeholder="Prefix (Optional)" class="form-control">
								</div>
								<div class="col-md-3">
									<label for="">Postfix</label>
									<input type="text" name="postfix" placeholder="Postfix (Optional)" class="form-control">
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary"><i class="fa fa-search"></i> Search Serial Number</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<?php
		if (!empty($serialnumber)) {
		?>
			<div class="row">
				<div class="col-sm-12">
					<div class="box box-solid">
						<div class="box-body">
							<div class="form-group">
								<a href="{{ url('artmin/product/serialnumber/add-serialnumber') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Serial Number</a>
							</div>
							<div class="col-sm-12 table-container">
								<table class="table table-bordered data-table">
									<thead>
										<tr>
											<th>#</th>
											<th>Product</th>
											<th>Serial No.</th>
											<th>Registered At</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@foreach($serialnumber as $key => $val)
										<tr>
											<td>{{ $key + 1 }}</td>
											<td>{{ (!empty($val->product_name_odoo) ? $val->product_name_odoo : $val->product_name) }}</td>
											<td>{{ $val->serial_no }}</td>
											<td>{{ date('d M Y',strtotime($val->craeted_at)) }}</td>
											<td>{{ ($val->status == '1' ? 'Redeemed' : 'Available') }}</td>
											<td>
												<?php
												if ($val->status == '1') {
												?>
													<button class="btn btn-primary btn-xs btn-reactivate" serial_id="{{ $val->id }}" serialnumber="{{ $val->serial_no }}">Re-activate</button>
												<?php
												} else {
												?>
													<!-- <button class="btn btn-primary btn-xs">Deactivate</button> -->
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
			</div>
		<?php
		}
		?>

		<!-- /.row -->
	</section><!-- /.content -->
</div>


<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>
	$(document).on('click', '.btn-reactivate', function(e) {
		e.preventDefault();

		let serial_id = $(this).attr('serial_id');
		let serialnumber = $(this).attr('serialnumber');

		swal({
				title: "Confirmation",
				text: "Are you sure to re-activate this serial number? (" + serialnumber + ")",
				icon: "info",
				buttons: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					let url = '{{ url("artmin/product/serialnumber/reactivate") }}';
					let data = {
						"_token": "{{ csrf_token() }}",
						"serial_id": serial_id
					};
					
					$.post(url, data, function(r) {
						swal('Success', 'Serial Number Has Been Actived', 'success').then((confirm) => location.reload());
					});
				}
			});

	});
</script>

@endsection