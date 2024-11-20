@extends('backend.layouts.backend-app')
@section('title', 'Warranty')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
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
				<div class="box box-solid">
					<!-- <div class="box-header">
			          <h3 class="box-title">Products</h3>
			        </div> -->
					<div class="box-body">
						<!-- <div class="form-group">
							<a href="{{ url('artmin/warranty/add-warranty') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Warranty</a>
						</div> -->
						<div class="col-sm-12 table-container">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>Warranty No.</th>
										<th>Serial Number</th>
										<th>Registered At</th>
										<th>Member Name</th>
										<th>Phone</th>
										<th>Email</th>
										<th>Product</th>
										<th>Status</th>
										<th>Verified</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; ?>
									<?php foreach ($warranty as $row) : ?>
										<tr>
											<td><?= $i++ ?></td>
											<td>{{ $row->warranty_no }}</td>
											<td>{{ $row->serial_no }}</td>
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
											<td>
												<a href="{{ url('artmin/warranty/information/' . $row->warranty_id) }}" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Details"><i class="fa fa-search"></i></a>
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

<!-- <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->
<script>
	$('.table').DataTable({
		"sScrollX": "100%",
    "sScrollXInner": "110%",
	});
</script>

@endsection