@extends('backend.layouts.backend-app')
@section('title', ( $statusAction == 'insert' ? 'Add New' : 'Edit' ).' User')
@section('content')
<div>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Category
			<small>{{ $statusAction == 'insert' ? 'New' : 'Edit' }}</small>
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
						<form class="fdata"
							action="{{ ($statusAction == 'insert' ? url('artmin/category/add-category-process') : url('artmin/categories/edit-categories-process')) }}"
							method="post" enctype="multipart/form-data">
							{{ csrf_field() }}
							<input type="hidden" name="userid" value="{{ ($user->id ?? null) }}">
							<div class="form-group">
								<label>Parent</label>
								<select class="form-control select2" name="category_model">
									<option value="-">Select Parent Category</option>
									@foreach ($category as $val)
									<option value="{{ $val->category_id }}">{{ $val->name }}</option>
									@endforeach
								</select>
								@if ($errors->has('product_id'))
								<label class="control-label input-error" for="inputError"><i
										class="fa fa-exclamation-circle"></i>
									{{ $errors->first('product_id') }}</label>
								@endif
							</div>
							<div class="form-group">
								<label>Nama categori</label>
								<input type="text" class="form-control" name="name" value="{{ ($product->product_name_odoo ?? null) }}" placeholder="Nama Kategori">
								@if ($errors->has('name'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('name') }}</label>
								@endif
							</div>
							<div class="form-group">
                                <label>Slug</label>
                                <input type="text" class="form-control" name="slug" id="url" value="{{ old('slug') }}">
                                @if ($errors->has('slug'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('slug') }}</label>
                                @endif
                            </div>

							<div class="form-group">
								<button class="btn btn-primary"><i class="fa fa-plus"></i>
									{{( $statusAction == 'insert' ? 'Add New' : 'Edit' )}} Category</button>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div><!-- /.row -->
	</section><!-- /.content -->
</div>


<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script type="text/javascript">
	<?php
	if ($statusAction == 'update') {
	?>
	$('[name="roles"]').val('{{ $user->roles }}').trigger('click');
	<?php
	}
	?>
	$(document).on('submit',
	'.fdata',
	function(e)
	{
	e.preventDefault();
	let
	url
	=
	$(this).attr('action');
	let
	data
	=
	$(this).serializeArray();
	swal({
	title:
	"Confirmation",
	text:
	"Are
	you
	sure
	to
	submit
	this
	data?",
	icon:
	"info",
	buttons:
	true,
	})
	.then((willDelete)
	=>
	{
	if
	(willDelete)
	{
	$.post(url,
	data,
	function(data)
	{
	swal('Success',
	'Data
	Has
	Been
	Saved',
	'success').then((confirm) => location.reload());
	//
	console.log(data);
	});
	}
	});
	});
</script>

@endsection