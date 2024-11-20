@extends('backend.layouts.backend-app')
@section('title', 'Warranty')
@section('content')
<div>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Warranty
			<small>Information</small>
			<div class="pull-right">
				<!-- <a href="" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</a> -->
			</div>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">

			<div class="col-sm-4">
				<div class="box box-solid">
					<div class="box-header">
						<h3 class="box-title">Products Information</h3>
					</div>
					<div class="box-body">
						<img src="{{ $product->product_image??'' }}" class="img-responsive">
						<div class="table-responsive">
							<table class="table">
								<tr>
									<td><b>Product Name</b></td>
									<td>{{ $product->product_name??'' }}</td>
								</tr>
								<tr>
									<td colspan="2"><b>Product Warranty</b></td>
								</tr>
								<?php foreach ($product_warranty as $prod_war) : ?>
									<tr>
										<td><b>{{ $prod_war->warranty_title }}</b></td>
										<td>{{ ($prod_war->warranty_year > 0 ? $prod_war->warranty_value : 'Lifetime') }}</td>
									</tr>
								<?php endforeach; ?>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="box box-solid">
					<div class="box-header">
						<h3 class="box-title">Warranty Registration</h3>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-bordered">
								<tr>
									<td><b>Warranty Number</b></td>
									<td>{{ $warranty->warranty_no }}</td>
								</tr>
								<tr>
									<td><b>Serial Number</b></td>
									<td>{{ $warranty->serial_no }}</td>
								</tr>
								<tr>
									<td><b>Name</b></td>
									<td>{{ $warranty->reg_name }}</td>
								</tr>
								<tr>
									<td><b>Phone</b></td>
									<td>{{ $warranty->reg_phone }}</td>
								</tr>
								<tr>
									<td><b>Email</b></td>
									<td>{{ $warranty->reg_email }}</td>
								</tr>
								<tr>
									<td><b>Address</b></td>
									<td>{{ $warranty->reg_address }}</td>
								</tr>
								<tr>
									<td><b>City</b></td>
									<td>{{ $warranty->reg_city }}</td>
								</tr>
								<tr>
									<td><b>Purchase Date</b></td>
									<td>{{ date('d M Y', strtotime($warranty->purchase_date)) }}</td>
								</tr>
								<tr>
									<td><b>Purchase Location</b></td>
									<td>{{ $warranty->purchase_loc }}</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="box box-solid">
					<div class="box-header">
						<h3 class="box-title">Product Warranty</h3>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-bordered">
								<tr>
									<th>Warranty Type</th>
									<th>Expired Date</th>
									<th>Remaining Days</th>
								</tr>
								<?php
								$now = time();
								foreach ($warranty_list as $key => $wl) {
									if ($wl->warranty_period > 0) {
								?>
										<tr>
											<td><b>{{ $wl->warranty_type }}</b></td>
											<td>{{ date('d M Y', strtotime($wl->warranty_end)) }}</td>
											<td>
												<?php
												$warranty_date = strtotime($wl->warranty_end);
												$datediff = $warranty_date - $now;

												$diff = round($datediff / (60 * 60 * 24));
												if ($diff > 0) {
													echo number_format($diff, 0, ',', '.') . " Days left";
												} else {
													echo "Expired";
												}
												?>
											</td>
										</tr>
									<?php
									} else {
									?>
										<tr>
											<td><b>{{ $wl->warranty_type }}</b></td>
											<td colspan="2">Lifetime</td>
										</tr>
								<?php
									}
								}
								?>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div><!-- /.row -->
	</section><!-- /.content -->
</div>



@endsection