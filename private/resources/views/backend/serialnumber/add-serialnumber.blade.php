@extends('backend.layouts.backend-app')
@section('title', 'Add New Serial Number')
@section('content')
<div>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Serial Number
			<small>New</small>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">

			<div class="col-md-6">
				<div class="box box-solid">
					<!-- <div class="box-header">
			          <h3 class="box-title">Products</h3>
			        </div> -->
					<div class="box-body">
						@include('backend.layouts.alert')
						<form class="fdata" action="{{ url('artmin/product/serialnumber/add-serialnumber-process') }}" method="post" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="form-group">
								<label>Product Model</label>
								<select class="form-control select2" name="product_id">
									<option value="">Select Product Model</option>
									<?php foreach ($products as $prod) : ?>
										<option value="<?= $prod->product_id ?>" <?php if (old('product_id') == $prod->product_id) echo "selected"; ?>><?= $prod->product_name_odoo; ?></option>
									<?php endforeach; ?>
								</select>
								@if ($errors->has('product_id'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('product_id') }}</label>
								@endif
							</div>
							<div class="form-group">
								<label>Prefix</label>
								<input type="text" class="form-control" name="prefix" value="{{ old('prefix') }}" placeholder="Prefix">
								@if ($errors->has('prefix'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('prefix') }}</label>
								@endif
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Start</label>
										<input type="number" class="form-control" name="start" value="{{ old('start') }}" placeholder="Start">
										@if ($errors->has('start'))
										<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('start') }}</label>
										@endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>End</label>
										<input type="number" class="form-control" name="end" value="{{ old('end') }}" placeholder="End">
										@if ($errors->has('end'))
										<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('end') }}</label>
										@endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<label>Postfix</label>
								<input type="text" class="form-control" name="postfix" value="{{ old('postfix') }}" placeholder="Postfix">
								@if ($errors->has('postfix'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('postfix') }}</label>
								@endif
							</div>


							<div class="form-group">
								<button class="btn btn-primary"><i class="fa fa-plus"></i> Submit Serial Number</button>
								<button class="btn btn-default btn-preview"> Preview Serial Number</button>
							</div>

						</form>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="box box-solid">
					<div class="box-body">
						<h4>Preview Serial Number</h4>
						<table class="table tpreview">
							<thead>
								<tr>
									<th>Prefix</th>
									<th>Digit</th>
									<th>Postfix</th>
									<th>Serial Number</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="4">
										<center>No Data Preview</center>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div><!-- /.row -->
	</section><!-- /.content -->
</div>

<script type="text/javascript">
	$(document).on('submit', '.fdata', function(e) {
		e.preventDefault();

		var r = confirm("Are You Sure To Submit This Data?");
		if (r) {
			let url = $(this).attr('action');
			let data = $(this).serializeArray();

			$.post(url, data, function(retData) {
				location.href = '{{ url("artmin/product/serialnumber") }}';
			})
		}

	});

	function pad_with_zeroes(number, length) {
		var my_string = '' + number;
		while (my_string.length < length) {
			my_string = '0' + my_string;
		}
		return my_string;
	}


	$(document).on('click', '.btn-preview', function(e) {
		e.preventDefault();

		let prefix = $('[name="prefix"]').val();

		let start = Number($('[name="start"]').val());
		let end = Number($('[name="end"]').val());

		let postfix = $('[name="postfix"]').val();

		$('.tpreview tbody tr').remove();

		for (let i = start; i <= end; i++) {
			let sn = prefix + pad_with_zeroes(i, 4) + postfix;

			$('.tpreview tbody').append('<tr>' +
				'<td>' + prefix + '</td>' +
				'<td>' + pad_with_zeroes(i, 4) + '</td>' +
				'<td>' + postfix + '</td>' +
				'<td>' + sn + '</td>' +
				'</tr>');
		}

	});
</script>

@endsection