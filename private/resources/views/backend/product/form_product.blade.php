@extends('backend.layouts.backend-app')
@section('title', ($statusAction == 'insert' ? 'Add New' : 'Edit' ).' Product')
@section('content')

<link rel="stylesheet" href="{{ url('assets/css/pages/detail_product.css') }}">

<div>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Product
			<small>{{ ($statusAction == 'insert' ? 'Add' : 'Edit') }}</small>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<form class="fdata" action="{{ ($statusAction == 'insert' ? url('artmin/product/add-product-process') : url('artmin/product/edit-product-process')) }}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="row">

				<div class="col-md-6">
					<div class="box box-solid">
						<!-- <div class="box-header">
			          <h3 class="box-title">Products</h3>
			        </div> -->
						<div class="box-body">
							@include('backend.layouts.alert')
							<input type="hidden" name="product_id" value="{{ ($statusAction == 'update' ? $product->product_id : null) }}">
							<div class="form-group">
								<label>Category Model</label>
								<select class="form-control select2" name="category_model">
									@if ($statusAction == 'update')

									@foreach ($category as $val)
									<option value="{{ $val->category_id }}" {{ $val->category_id == $product->category ? 'selected' : '' }}>
										{{ $val->name }}
									</option>
									@endforeach

									@else
									<option value="">Select Category</option>
									@foreach ($category as $val)
									<option value="{{ $val->category_id }}">{{ $val->name }}</option>
									@endforeach

									@endif
								</select>
								@if ($errors->has('category_model'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
									{{ $errors->first('category_model') }}</label>
								@endif
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Default Code (key odoo)</label>
										<input type="text" class="form-control" name="default_code" value="{{ ($product->default_code ?? null) }}" placeholder="Default Code (key odoo)">
										@if ($errors->has('product_code'))
										<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
											{{ $errors->first('product_code') }}</label>
										@endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Product Code</label>
										<input type="text" class="form-control" name="product_code" value="{{ ($product->product_code ?? null) }}" placeholder="Product Code">
										@if ($errors->has('product_code'))
										<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
											{{ $errors->first('product_code') }}</label>
										@endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<label>Product Name</label>
								<input type="text" class="form-control" name="product_name" value="{{ ($product->product_name_odoo ?? null) }}" placeholder="Product Name">
								@if ($errors->has('product_name'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
									{{ $errors->first('product_name') }}</label>
								@endif
							</div>
							<div class="form-group">
								<label>Product Description</label>
								<!-- <input type="text" class="form-control" name="product_desc" value="{{ old('product_desc') }}" placeholder="Product Description"> -->
								<textarea name="product_desc" id="" cols="30" rows="5" placeholder="Product Description" class="form-control"><?php echo ($statusAction == 'update' ? nl2br($product->product_desc) : null) ?></textarea>
								@if ($errors->has('product_desc'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
									{{ $errors->first('product_desc') }}</label>
								@endif
							</div>
							<div class="form-group">
								<label>Product Price</label>
								<input type="text" class="form-control" name="price" value="{{ ($product->price ?? null) }}" placeholder="Product Price">
								@if ($errors->has('price'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
									{{ $errors->first('price') }}</label>
								@endif
							</div>
							<div class="form-group">
								<label>Slug</label>
								<input type="text" class="form-control" name="product_slug" value="{{ ($product->slug ?? null) }}" placeholder="Slug">
								@if ($errors->has('product_slug'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
									{{ $errors->first('product_slug') }}</label>
								@endif
							</div>
							<div class="form-group">
								<label>Base Point</label>
								<input type="text" class="form-control" name="base_point" value="{{ ($product->base_point ?? null) }}" placeholder="Base Point">
								@if ($errors->has('base_point'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
									{{ $errors->first('base_point') }}</label>
								@endif
							</div>
							<div class="form-group">
								<label>Product Image</label>
								<?php
								if ($statusAction == 'update') {
								?>
								<?php
								}
								?>
								<center><img id="img_preview" src="{{ ($product->product_image ?? null) }}" style="height:50%;width:50%"></center>
								<!-- <input type="text" class="form-control" name="product_image" value="{{ old('product_image') }}" placeholder="Product Name"> -->
								<div class="d-flex mt-3">
									<input type="file" id="imgInp" class="form-control" name="product_image">
									@if($product->product_image)
									<a href"javascript:" href="{{url('/artmin/product/delete-product-image')}}" class="btn btn-danger ml-3 btn-del-img-prod"><i class="fa fa-trash mr-0"></i></a>
									@endif
								</div>

								@if ($errors->has('product_image'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i>
									{{ $errors->first('product_image') }}</label>
								@endif
							</div>

						</div>
					</div>
					
					<div class="box box-solid">
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<label for="">Product Files</label>
									<br>
									<div id="product_files_container" class="py-4" style="display: flex;flex-direction: column;gap: 12px;max-height: 400px;height: 400px;overflow-y: auto;"></div>
									<br>
									<button type="button" id="btn-add-files" class="btn btn-warning"><i class="fa fa-plus">Add</i></button>
								</div>
							</div>
						</div>
					</div>

					<div class="box box-solid">
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<label for="">Content Product</label>

									<br>
									<div class="body_content">
										<?php
										if ($statusAction == 'insert' || empty($detail_content)) {
										?>
											<div class="row_content row_content_0">
												<div class="row">
													<div class="col-md-12">
														<label for="">Banner</label>
														<br>
														<img id="img_preview_banner_0" style="height:50%;width:100%">
														<input type="file" name="banner[]" index="0" id="banner_prev_0" class="banner_prev form-control">
													</div>
												</div>
												<br>
												<div class="row">
													<div class="col-md-12">
														<label for="">Content Title</label>
														<input type="text" name="content_title[]" placeholder="Content Title" class="form-control">
													</div>
												</div>
												<br>
												<div class="row">
													<div class="col-md-12">
														<label for="">Content Description</label>
														<textarea name="content_description[]" cols="10" rows="8" placeholder="Content Description" class="form-control"></textarea>
													</div>
												</div>
											</div>
											<?php
										} else {
											foreach ($detail_content as $key => $value) {
											?>
												<div class="row_content row_content_{{ $key }}">
													<div class="row">
														<div class="col-md-6">
															<label for="">Banner Web</label>
															<br>
															<input type="hidden" name="current_banner[]" value="{{ $value->banner }}">
															<img id="img_preview_banner_web_{{ $key }}" style="height:50%;width:100%" src="{{ url('uploads/products/banner/' . $value->banner) }}">
															<input type="file" name="banner[]" index="{{ $key }}" id="banner_prev_web_{{ $key }}" class="banner_prev_web form-control">
														</div>
														<div class="col-md-6">
															<label for="">Banner Mobile</label>
															<br>
															<input type="hidden" name="current_banner_mobile[]" value="{{ $value->banner_mobile }}">
															<img id="img_preview_banner_mobile_{{ $key }}" style="height:50%;width:100%" src="{{ url('uploads/products/banner/' . $value->banner_mobile) }}">
															<input type="file" name="banner_mobile[]" index="{{ $key }}" id="banner_prev_mobile_{{ $key }}" class="banner_prev_mobile form-control">
														</div>
													</div>
													<br>
													<div class="row">
														<div class="col-md-12">
															<label for="">Content Title</label>
															<input type="text" name="content_title[]" value="{{ $value->content_title }}" placeholder="Content Title" class="form-control">
														</div>
													</div>
													<br>
													<div class="row">
														<div class="col-md-12">
															<label for="">Content Description</label>
															<textarea name="content_description[]" cols="10" rows="8" placeholder="Content Description" class="form-control">{{ $value->content_description }}</textarea>
														</div>
													</div>
													<?php
													if ($key > 0) {
													?>
														<div class="col-md-12" style="margin-top: 12px; margin-bottom: 24px; padding-right: 0px;">
															<button class="btn btn-danger pull-right btn-remove-row-content" index="{{ $key }}">Remove Row</button>
														</div>
													<?php
													}
													?>
												</div>
										<?php
											}
										}
										?>
									</div>
									<br>
									<button class="btn btn-primary btn-add-row-content">Add Row Content</button>
								</div>
							</div>
						</div>
					</div>

				</div>


				<div class="col-md-6">

					<div class="row">
						<div class="col-md-12">
							<div class="box box-solid">
								<div class="box-body">
									<div class="form-group">
										<label>Icon Product</label>
										<center>
											<img src="" id="preview_icon" alt="" style="width:30%">
										</center>
										<select class="form-control select2" name="icon_id">
											<option value="-">No Icon</option>
											@foreach($icon as $val)
											<option value="{{ $val->id }}" url="{{ $val->icon }}">{{ $val->title }}
											</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="box box-solid">
								<div class="box-body">
									<div class="form-group">
										<label>Product Reference</label>
										<select class="form-control select2" name="refDataSpec">
											<option value="-">Reference Format Data</option>
											@foreach($all_product as $val)
											<option value="{{ $val->product_id }}">{{ $val->product_name_odoo }}
											</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="box box-solid">
								<div class="box-body">
									<div class="form-group">
										<label for="">Warranty <span class="loadingSpec"></span></label>

										<?php
										if ($statusAction == 'insert') {
										?>
											<div class="formWarranty">
												<div class="row r_0">
													<div class="col-md-5">
														<input type="hidden" name="warranty_id[]">
														<input type="text" name="warranty_title[]" class="form-control" placeholder="Warranty Title">
													</div>
													<div class="col-md-5">
														<input type="text" name="warranty_value[]" class="form-control" placeholder="Warranty Value">
													</div>
													<div class="col-md-2" style="display: flex;">
														<button class="btn btn-sm btn-primary btn-add-row-warranty" index="0">+</button>
														<!-- <button class="btn btn-sm btn-default btn-remove-row-warranty" index="0">-</button> -->
													</div>
												</div>
											</div>
											<?php
										} else {
											$dataWarranty = DB::table('ms_products_warranty')->where('product_id', $product->product_id)->get();
											if (!empty($dataWarranty)) {
											?>
												<div class="formWarranty">
													<?php
													if (count($dataWarranty) > 0) {

														foreach ($dataWarranty as $key => $value) {
													?>
															<div class="row r_{{ $key }}">
																<div class="col-md-5">
																	<input type="hidden" name="warranty_id[]" value="{{ $value->id }}">
																	<input type="text" name="warranty_title[]" value="{{ $value->warranty_title }}" class="form-control" placeholder="Warranty Title">
																</div>
																<div class="col-md-5">
																	<input type="text" name="warranty_value[]" value="{{ $value->warranty_year }}" class="form-control" placeholder="Warranty Value">
																</div>
																<div class="col-md-2" style="display: flex;">
																	<button class="btn btn-sm btn-primary btn-add-row-warranty" index="{{ $key }}">+</button>
																	<?php
																	if ($key != 0) {
																	?>
																		<button class="btn btn-sm btn-default btn-remove-row-warranty" index="{{ $key }}">-</button>
																	<?php
																	}
																	?>
																</div>
																<br>
															</div>
														<?php
														}
													} else {
														?>
														<div class="formWarranty">
															<div class="row r_0">
																<div class="col-md-5">
																	<input type="hidden" name="warranty_id[]">
																	<input type="text" name="warranty_title[]" class="form-control" placeholder="Warranty Title">
																</div>
																<div class="col-md-5">
																	<input type="text" name="warranty_value[]" class="form-control" placeholder="Warranty Value">
																</div>
																<div class="col-md-2" style="display: flex;">
																	<button class="btn btn-sm btn-primary btn-add-row-warranty" index="0">+</button>
																	<!-- <button class="btn btn-sm btn-default btn-remove-row-warranty" index="0">-</button> -->
																</div>
															</div>
														</div>
													<?php
													}
													?>
												</div>
											<?php
											} else {
											?>
												<div class="formWarranty">
													<div class="row r_0">
														<div class="col-md-5">
															<input type="hidden" name="warranty_id[]">
															<input type="text" name="warranty_title[]" class="form-control" placeholder="Warranty Title">
														</div>
														<div class="col-md-5">
															<input type="text" name="warranty_value[]" class="form-control" placeholder="Warranty Value">
														</div>
														<div class="col-md-2" style="display: flex;">
															<button class="btn btn-sm btn-primary btn-add-row-warranty" index="0">+</button>
															<!-- <button class="btn btn-sm btn-default btn-remove-row-warranty" index="0">-</button> -->
														</div>
													</div>
												</div>
										<?php
											}
										}
										?>



									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-md-12">
							<div class="box box-solid">
								<div class="box-body">
									<div class="form-group">
										<div class="row">
											<div class="col-md-12">
												<label for="">General Spec. <span class="loadingSpec"></span></label>
												<div class="generalSpec">
													<?php
													if (empty($specification_general)) {
													?>
														<div class="row rg_0">
															<div class="col-md-5">
																<input type="text" name="general_spec_title[]" class="form-control" placeholder="Spec. Title">
															</div>
															<div class="col-md-5">
																<input type="text" name="general_spec_value[]" class="form-control" placeholder="Spec. Value">
															</div>
															<div class="col-md-2" style="display: flex;">
																<button class="btn btn-sm btn-primary btn-add-row" type="general" index="0">+</button>
																<button class="btn btn-sm btn-default btn-delete-row" type="general" index="0">-</button>
															</div>
														</div>
														<?php
													} else {
														if (count($specification_general) > 0) {
															foreach ($specification_general as $key => $value) {
														?>
																<div class="row rg_{{$key}}">
																	<div class="col-md-5">
																		<input type="text" name="general_spec_title[]" value="{{ $value->spec_title }}" class="form-control" placeholder="Spec. Title">
																	</div>
																	<div class="col-md-5">
																		<input type="text" name="general_spec_value[]" value="{{ $value->spec_value }}" class="form-control" placeholder="Spec. Value">
																	</div>
																	<div class="col-md-2" style="display: flex;">
																		<button class="btn btn-sm btn-primary btn-add-row" type="general" index="{{$key}}">+</button>
																		<button class="btn btn-sm btn-default btn-delete-row" type="general" index="{{$key}}">-</button>
																	</div>
																</div>
															<?php
															}
														} else {
															?>
															<div class="row rg_0">
																<div class="col-md-5">
																	<input type="text" name="general_spec_title[]" class="form-control" placeholder="Spec. Title">
																</div>
																<div class="col-md-5">
																	<input type="text" name="general_spec_value[]" class="form-control" placeholder="Spec. Value">
																</div>
																<div class="col-md-2" style="display: flex;">
																	<button class="btn btn-sm btn-primary btn-add-row" type="general" index="0">+</button>
																	<button class="btn btn-sm btn-default btn-delete-row" type="general" index="0">-</button>
																</div>
															</div>
														<?php
														}
														?>
													<?php
													}
													?>
												</div>
											</div>
										</div>
										<div class="row">
											<br>
											<div class="col-md-12">
												<label for="">Special Spec. <span class="loadingSpec"></span></label>
												<div class="specialSpec">
													<?php
													if (empty($specification_special)) {
													?>
														<div class="row rs_0">
															<div class="col-md-5">
																<input type="text" name="special_spec_title[]" class="form-control" placeholder="Spec. Title">
															</div>
															<div class="col-md-5">
																<input type="text" name="special_spec_value[]" class="form-control" placeholder="Spec. Value">
															</div>
															<div class="col-md-2" style="display: flex;">
																<button class="btn btn-sm btn-primary btn-add-row" type="special" index="0">+</button>
																<button class="btn btn-sm btn-default btn-delete-row" type="special" index="0">-</button>
															</div>
														</div>
														<?php
													} else {
														if (count($specification_special) > 0) {
															foreach ($specification_special as $key => $value) {
														?>
																<div class="row rs_{{ $key }}">
																	<div class="col-md-5">
																		<input type="text" name="special_spec_title[]" value="{{ $value->spec_title }}" class="form-control" placeholder="Spec. Title">
																	</div>
																	<div class="col-md-5">
																		<input type="text" name="special_spec_value[]" value="{{ $value->spec_value }}" class="form-control" placeholder="Spec. Value">
																	</div>
																	<div class="col-md-2" style="display: flex;">
																		<button class="btn btn-sm btn-primary btn-add-row" type="special" index="{{ $key }}">+</button>
																		<button class="btn btn-sm btn-default btn-delete-row" type="special" index="{{ $key }}">-</button>
																	</div>
																</div>
															<?php
															}
														} else {
															?>
															<div class="row rs_0">
																<div class="col-md-5">
																	<input type="text" name="special_spec_title[]" class="form-control" placeholder="Spec. Title">
																</div>
																<div class="col-md-5">
																	<input type="text" name="special_spec_value[]" class="form-control" placeholder="Spec. Value">
																</div>
																<div class="col-md-2" style="display: flex;">
																	<button class="btn btn-sm btn-primary btn-add-row" type="special" index="0">+</button>
																	<button class="btn btn-sm btn-default btn-delete-row" type="special" index="0">-</button>
																</div>
															</div>
														<?php
														}
														?>
													<?php
													}
													?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					@if (isset($product_category_feature))
					<div class="row">
						<div class="col-md-12">
							<div class="box box-solid">
								<div class="box-body">
									<div class="form-group">
										<div class="row">
											<div class="col-md-12">
												<label for="" style="margin-bottom: 24px;">Feature Highlight. <span class="loadingSpec"></span></label>
													<div class="category-feature" style="max-height: 300px;height: 300px;overflow-y: auto;">
														<div style="height: auto;">
															<?php $index = 0; ?>
															@foreach($product_category_feature as $feature)
															<?php

																$checked = false;
																foreach ($list_selected_feature as $value) {
																	if ((int)$value === (int)$feature->id) {
																		$checked = true;
																		break;
																	}
																}

															?>
															<div class="category-feature-item" style="padding: 12px;">
																<input type="checkbox" name="features[{{$feature->id}}]" <?php echo $checked ? "checked" : "" ?> />
																<img class="images" src="{{ url($feature->img) }}" style="width: 125px;"></img>
															</div>
															<?php $index++; ?>
															@endforeach
														</div>
													</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif

				</div>

			</div><!-- /.row -->
			<br>
			<div class="form-group">
				<center>
					<button class="btn btn-primary btn-submit"><i class="fa fa-plus"></i>{{ ($statusAction == 'insert' ? 'Add New' : 'Edit' ) }} Product</button>
					<?php
					if ($statusAction != 'insert') {
					?>
						<button class="btn btn-primary btn-duplicate"><i class="fa fa-clone"></i>Duplicate Data Product</button>
					<?php
					}
					?>
				</center>
			</div>
		</form>
	</section><!-- /.content -->
</div>

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script type="text/javascript">

	var index_content = 999;

	$(document).on('click', '.btn-del-img-prod', function(e) {
		e.preventDefault();

		swal({
				title: "Konfirmasi",
				text: "Apakah yakin menghapus product image ini?",
				icon: "info",
				buttons: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					let url = $(this).attr('href');
					$.post(url, { _token: $("[name='_token']").val(), product_id: $("[name='product_id']").val() }, function(res) {
						res = JSON.parse(res);
						swal((res.success ? 'Berhasil' : 'Gagal'), res.message, (res.success ? 'success' : 'error')).then(() => {
							$("#img_preview").attr("src", "");
						});
					});
				}
			});
	});

	$(document).on('click', '.btn-add-row-content', function(e) {
		e.preventDefault();

		$('.body_content').append('<div class="row_content row_content_' + index_content + '">' +
			'<div class="row">' +
			'<div class="col-md-12">' +
			'<label for="">Banner</label>' +
			'<br>' +
			'<img id="img_preview_banner_' + index_content + '" style="height:50%;width:100%">' +
			'<input type="file" name="banner[]" index="' + index_content + '" id="banner_prev_' + index_content + '" class="banner_prev form-control">' +
			'</div>' +
			'</div>' +
			'<br>' +
			'<div class="row">' +
			'<div class="col-md-12">' +
			'<label for="">Content Title</label>' +
			'<input type="text" name="content_title[]" placeholder="Content Title" class="form-control">' +
			'</div>' +
			'</div>' +
			'<br>' +
			'<div class="row">' +
			'<div class="col-md-12">' +
			'<label for="">Content Description</label>' +
			'<textarea name="content_description[]" cols="10" rows="8" class="form-control"></textarea>' +
			'</div>' +
			'</div>' +
			'<div class="col-md-12" style="margin-top: 12px; margin-bottom: 24px; padding-right: 0px;">' +
			'<button class="btn btn-danger pull-right btn-remove-row-content" index="' + index_content + '">Remove Row</button>' +
			'</div>' +
			'</div>');

		index_content++;

	});

	$(document).on('click', '.btn-remove-row-content', function(e) {
		e.preventDefault();
		let idx = $(this).attr('index');
		$('.body_content .row_content_' + idx).remove();
	});

	$(document).on('click', '.btn-duplicate', function(e) {
		e.preventDefault();

		swal({
				title: "Confirmation",
				text: "Are you sure to duplicate this data?",
				icon: "info",
				buttons: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$('.fdata').attr('action', '{{ url("artmin/product/add-product-process") }}');
					$('.fdata').submit();
				}
			});

	});


	$(document).on('change', '[name="icon_id"]', function(e) {
		e.preventDefault();
		let icon = $('option:selected', this).attr('url');
		if (icon != '-') {
			$('#preview_icon').attr('src', icon);
		} else {
			$('#preview_icon').attr('src', '');
		}
	});

	$(document).on('click', '.btn-submit', function(e) {
		e.preventDefault();

		swal({
				title: "Confirmation",
				text: "Are you sure to {{ ($statusAction == 'insert' ? 'add' : 'edit') }} this data?",
				icon: "info",
				buttons: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$('.fdata').submit();
				}
			});

	});

	$('#customer_type').on('change', function() {
		var type =
			$('#customer_type').val();
		$('#form-exist-customer').addClass('hide');
		$('#form-new-customer').addClass('hide');
		if (type == 1) {
			$('#form-new-customer').removeClass('hide');
		} else if (type == 2) {
			$('#form-exist-customer').removeClass('hide');
		}
	});
	var indexWarranty = 1;
	$(document).on('click', '.btn-add-row-warranty', function(e) {
		e.preventDefault();
		console.log('clciked');
		$('.formWarranty').append('<div class="row r_' + indexWarranty + '">' + '<br> <div class="col-md-5">' +
			'<input type="hidden" name="warranty_id[]">' +
			'<input type="text" name="warranty_title[]" class="form-control" placeholder="Warranty Title">' +
			'</div>' +
			'<div class="col-md-5">' +
			'<input type="text" name="warranty_value[]" class="form-control" placeholder="Warranty Value">' +
			'</div>' +
			'<div class="col-md-2" style="display: flex;">' +
			'<button class="btn btn-sm btn-primary btn-add-row-warranty" index="' + indexWarranty + '">+</button>' +
			'<button class="btn btn-sm btn-default btn-remove-row-warranty" index="' + indexWarranty + '">-</button>' +
			'</div>' +
			'</div>');
		indexWarranty++;
	});


	$(document).on('click', '.btn-remove-row-warranty', function(e) {
		e.preventDefault();
		let idx = $(this).attr('index');

		$('.formWarranty .r_' + idx).remove();
	});

	$(document).on('change', '[name="refDataSpec"]', function(e) {
		e.preventDefault();

		let id = $(this).val();
		let url = '{{ url("products/refspec") }}' + '/' + id;

		$('.loadingSpec').text('(Loading...)');

		if (id != '-') {
			$.get(url, function(data) {
				let retData = JSON.parse(data);
				if (retData.status) {

					if (retData.dataWar.length > 0) {
						$('.formWarranty .row').remove();
						for (let i = 0; i < retData.dataWar.length; i++) {
							const element = retData.dataWar[i];
							$('.formWarranty').append('<div class="row r_' + i + '">' + '<br> <div class="col-md-5">' +
								'<input type="text" name="warranty_title[]" value="' + element.warranty_title + '" class="form-control" placeholder="Warranty Title"></div>' +
								'<div class="col-md-5">' +
								'<input type="text" name="warranty_value[]" value="' + element.warranty_year + '" class="form-control" placeholder="Warranty Value">' +
								'</div>' +
								'<div class="col-md-2" style="display: flex;">' +
								'<button class="btn btn-sm btn-primary btn-add-row-warranty" index="' + i + '">+</button>' +
								'<button class="btn btn-sm btn-default btn-remove-row-warranty" index="' + i + '">-</button>' +
								'</div>' +
								'</div>');
						}
					}

					if (retData.dataSpec.length > 0) {
						$('.generalSpec .row').remove();
						$('.specialSpec .row').remove();
						for (let i = 1; i < retData.dataSpec.length; i++) {
							let element = retData.dataSpec[i];
							if (element.spec_group == 'General Spec') {
								$('.generalSpec').append('<div class="row rg_' + i + '">' +
									'<div class="col-md-5">' +
									'<input type="text" name="general_spec_title[]" value="' + element.spec_title + '" class="form-control" placeholder="Spec. Title">' +
									'</div>' +
									'<div class="col-md-5">' +
									'<input type="text" name="general_spec_value[]" value="' + element.spec_value + '" class="form-control" placeholder="Spec. Value">' +
									'</div>' +
									'<div class="col-md-2" style="display: flex;">' +
									'<button class="btn btn-sm btn-primary btn-add-row" type="general" index="' + i + '">+</button>' +
									'<button class="btn btn-sm btn-default btn-delete-row" type="general" index="' + i + '">-</button>' +
									'</div>' +
									'</div>');
							}
							if (element.spec_group == 'Special Spec') {
								$('.specialSpec').append('<div class="row rs_' + i + '">' +
									'<div class="col-md-5">' +
									'<input type="text" name="special_spec_title[]" value="' + element.spec_title + '" class="form-control" placeholder="Spec. Title">' +
									'</div>' +
									'<div class="col-md-5">' +
									'<input type="text" name="special_spec_value[]" value="' + element.spec_value + '" class="form-control" placeholder="Spec. Value">' +
									'</div>' +
									'<div class="col-md-2" style="display: flex;">' +
									'<button class="btn btn-sm btn-primary btn-add-row" type="special" index="' + i + '">+</button>' +
									'<button class="btn btn-sm btn-default btn-delete-row" type="special" index="' + i + '">-</button>' +
									'</div>' +
									'</div>');
							}
						}
						$('.loadingSpec').text('');
					} else {
						$('.loadingSpec').text('');
					}
				} else {
					$('.loadingSpec').text('');
				}
			});

		} else {
			$('.loadingSpec').text('');
		}
	});

	var idxPub = 99;
	var idxSpe = 99;
	$(document).on('click', '.btn-add-row', function(e) {
		e.preventDefault();

		let index = $(this).attr('index');
		let type = $(this).attr('type');
		// console.log(type);
		if (type == 'general') {
			$('.generalSpec').append('<div class="row rg_' + idxPub + '">' +
				'<div class="col-md-5">' +
				'<input type="text" name="general_spec_title[]" value="" class="form-control" placeholder="Spec. Title">' +
				'</div>' +
				'<div class="col-md-5">' +
				'<input type="text" name="general_spec_value[]" value="" class="form-control" placeholder="Spec. Value">' +
				'</div>' +
				'<div class="col-md-2" style="display: flex;">' +
				'<button class="btn btn-sm btn-primary btn-add-row" type="general" index="' + idxPub + '">+</button>' +
				'<button class="btn btn-sm btn-default btn-delete-row" type="general" index="' + idxPub + '">-</button>' +
				'</div>' +
				'</div>');

			idxPub++;
		} else {
			$('.specialSpec').append('<div class="row rs_' + idxSpe + '">' +
				'<div class="col-md-5">' +
				'<input type="text" name="special_spec_title[]" value="" class="form-control" placeholder="Spec. Title">' +
				'</div>' +
				'<div class="col-md-5">' +
				'<input type="text" name="special_spec_value[]" value="" class="form-control" placeholder="Spec. Value">' +
				'</div>' +
				'<div class="col-md-2" style="display: flex;">' +
				'<button class="btn btn-sm btn-primary btn-add-row" type="special" index="' + idxSpe + '">+</button>' +
				'<button class="btn btn-sm btn-default btn-delete-row" type="special" index="' + idxSpe + '">-</button>' +
				'</div>' +
				'</div>');File

			idxSpe++;
		}
	});

	$(document).on('click', '.btn-delete-row', function(e) {
		e.preventDefault();

		let index = $(this).attr('index');
		let type = $(this).attr('type');

		if (type == 'general') {
			$('.generalSpec .rg_' + index).remove();
		} else {
			$('.specialSpec .rs_' + index).remove();
		}
	});

	function onUploadThumbnail(fileId) {
		$(`#file_thumbnail_${fileId}`).click();
	}

	function showFileDescEdit(fileId) {
		$(`#file-description-view-${fileId}`).addClass("hidden");
		$(`#file-description-edit-${fileId}`).removeClass("hidden");
		$(`#description-files-input-${fileId}`).focus();
	}

	function showFileDescView(fileId) {
		let isnew = $(`#form-product-file-${fileId}`).attr("isnew");
		if (isnew === undefined) {
			$(`#file-description-view-${fileId}`).removeClass("hidden");
			$(`#file-description-edit-${fileId}`).addClass("hidden");
		} else {
			$(`[file-id=${fileId}]`).remove();
		}
	}

	function onChangeFileDescription(input, fileId) {
		$(`#description-files-${fileId}`).html(input.value);
	}

	function onChangeFile(input) {
		if (input.files && input.files[0]) {

			let fileId = $(input).attr("file-id");
			var reader = new FileReader();
			const mimeType = input.files[0].type;

			reader.onload = function(e) {
				if (mimeType.includes("image")) {
					$('#view-files-'+fileId).attr("src", e.target.result);
					$('#view-files-'+fileId).removeClass("hidden");
					$(input).addClass("hidden");
				}
			}
			reader.onloadend = function(e) {
				$('#file-description-edit-'+fileId).removeClass("hidden");
				$("#description-files-input-"+fileId).focus();
				if (mimeType.includes("video")) {
					$(`#btn-upload-thumbnail-${fileId}`).removeClass("hidden");
				}
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	function onChangeFileThumbnail(input) {
		if (input.files && input.files[0]) {

			let fileId = $(input).attr("file-id");
			var reader = new FileReader();
			const mimeType = input.files[0].type;

			if (!mimeType.includes("image")) {
				swal({
					title: "Perhatian",
					text: "Hanya boleh upload file image!",
					icon: "error",
					buttons: true,
				}).then((willDelete) => {
					$(input).click();
				});
				return;
			}
			reader.onload = function(e) {
				$('#view-files-'+fileId).attr("src", e.target.result);
				$('#view-files-'+fileId).removeClass("hidden");
			}
			reader.onloadend = function(e) {
				$('#file-description-edit-'+fileId).removeClass("hidden");
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	function updateProductFile (fileId) {
		$(`#form-product-file-${fileId}`).submit();
	}

	function removeRowProductFile (fileId) {
		let isnew = $(`#form-product-file-${fileId}`).attr("isnew");
		if (isnew === undefined) {
			$.ajax({
				type: "DELETE",
				url: `{{ url("/artmin/product/files") }}/${fileId}`,
				data: { "_token": "{{ csrf_token() }}" },
				timeout: 600000,
				success: function (data) {
					swal(data.success ? 'Berhasil' : 'Gagal', `File ${data.success ? 'berhasil' : 'gagal'} dihapus`, data.success ? 'success' : 'error');
					$(`[file-id=${fileId}]`).remove();
				},
				error: function (e) {
					swal('Gagal', 'File gagal dihapus', 'error');
				}
			});
		} else {
			$(`[file-id=${fileId}]`).remove();
		}
	}

	$(document).ready(function(e) {

		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function(e) {
					$('#img_preview').attr('src', e.target.result);
				}

				reader.readAsDataURL(input.files[0]); // convert to base64 string
			}
		}

		$("#imgInp").change(function() {
			readURL(this);
			console.log('change');
		});

		function readURL_content(input, idx, pf) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function(e) {
					$('#img_preview_banner_'+ pf +'_' + idx).attr('src', e.target.result);
				}

				reader.readAsDataURL(input.files[0]); // convert to base64 string
			}
		}

		const productId = $('[name="product_id"]').val();
		const productFileUrl = `{{url("artmin/product/files/")}}/${productId}`;
		$.get(productFileUrl, function(data) {
			$("#product_files_container").empty();
			let i = 0;
			for (const product_file of data.data) {
				$("#product_files_container").append(renderRowProductFile(product_file));
				i++;
			}
		});

		$(document).on('change', '.banner_prev_web', function() {
			let idx = $(this).attr('index');
			readURL_content(this, idx,'web');
			console.log('changed');
		});

		$(document).on('change', '.banner_prev_mobile', function() {
			let idx = $(this).attr('index');
			readURL_content(this, idx,'mobile');
			console.log('changed');
		});


		$(document).on('submit', '.form-files', function(e) {
			e.preventDefault();

			let url = $(this).attr('action');
			let fileId = $(this).attr('file-id');

			let isnew = $(`#form-product-file-${fileId}`).attr("isnew");

			var data = new FormData(this);
			// If you want to add an extra field for the FormData
			// data.append("CustomField", "This is some extra data, testing");

			// disabled the submit button
			$("#btn-save-product-file-"+fileId).prop("disabled", true);

			$.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
				url: url,
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 600000,
				success: function (data) {
					swal(data.success ? 'Berhasil' : 'Gagal', `File ${data.success ? 'berhasil' : 'gagal'} diupload`, data.success ? 'success' : 'error');
					if (data.success) {
						if (data.data) {
							// $(`form[id='form-product-file-${fileId}'] input[name='id']`).val(data.data.id);
							$(`#form-product-file-${fileId}`).remove();
							$("#product_files_container").append(renderRowProductFile(data.data));
						}
						$("#file-description-edit-"+fileId).addClass("hidden");
						$("#file-description-view-"+fileId).removeClass("hidden");
					}
					$("#btn-save-product-file-"+fileId).prop("disabled", false);
				},
				error: function (e) {
					// swal('Gagal', 'File gagal diupload', 'error');
					$("#btn-save-product-file-"+fileId).prop("disabled", false);
				}
			});
		});

		function renderRowProductFile (product_file, isnew=false) {
			let { id, product_id, mime_type, file_url, description, file_url_thumbnail } = product_file;
			
			let display_image_url = file_url;
			if (!(file_url || "").includes("http")) {
				display_image_url = `{{ url('${display_image_url}') }}`;
			}

			if ((mime_type || "").includes("video")) {
				if ((file_url_thumbnail || "").includes("http")) {
					display_image_url = file_url_thumbnail;
				} else {
					display_image_url = `{{ url('${file_url_thumbnail}') }}`;
				}
			}

			let contentPreview = `<img class="preview_image ${display_image_url === "" ? "hidden" : ""}" id="view-files-${id}" src="${display_image_url}" style="width:100%;border-top-left-radius: 6px;border-top-right-radius: 6px;" alt="" />`;

			if ((mime_type || "").includes("video")) {
			// 	let thumbnail = "";
			// 	if (mime_type === 'video/youtube') {
			// 		thumbnail = file_url.replaceAll("youtu.be","img.youtube.com/vi") + "/hqdefault.jpg";
			// 		file_url = file_url.replaceAll("youtu.be", "www.youtube.com/embed") + "?autoplay=1";
			// 	}
			// 	contentPreview = `<div class="slider-card yt-card">
			// 		<div style="position: relative;">
			// 			<img src="{{ url('') }}/assets/img/youtube-play.png" video-url="${file_url}" class="c-pointer hover-scale play-video" style="padding: 0px; text-align: center; height: 50px; width: 50px; border-radius: 8px; position: fixed; left: calc(50% - 25px); top: calc(50% - 45px);">
			// 			<img class="card-img-top" src="${thumbnail}">
			// 		</div>
			// 		<div class="card-body text-dark" style="height: 45px;"><h5 class="font-bold">${description}</h5></div>
			// 	</div>`;
			} else {
				if (file_url !== "") {

				}
			}

			return `
			<form id="form-product-file-${id}" action="{{ url('artmin/product/files/${product_id}') }}" method="post" enctype="multipart/form-data" class="form-files text-center" style="flex: 1;" file-id="${id}" ${isnew ? "isnew" : ""}>
				{{ csrf_field() }}
				<div id="preview-file-section-${id}">
				${contentPreview}
				</div>
				<input id="${id}" value="${!isnew ? id : ""}" name="id" class="hidden"></input>
				<input type="file" id="product_file_${id}" name="product_file" onchange="onChangeFile(this)" class="form-control ${file_url !== "" ? "hidden" : ""} files" file-id=${id} accept="image/*" style="height: 43px; width: 100%;"></input>
				<input type="file" id="file_thumbnail_${id}" accept="image/*" name="product_file_thumbnail" onchange="onChangeFileThumbnail(this)" class="form-control hidden files" file-id=${id} accept="image/*" style="height: 43px; width: 100%;"></input>
				<div class="${file_url === "" ? "hidden" : ""}" id="file-description-view-${id}" style="padding: 14px; border: 1px solid #dee2e6; border-bottom-left-radius: 6px; border-bottom-right-radius: 6px; display: flex; justify-content: space-between; flex-direction: row; gap: 4px; align-items: center;">
					<span id="description-files-${id}" onclick="showFileDescEdit(${id})" style="font-size: 16px; ">${description}</span>
					<button type="button" class="btn btn-sm btn-danger" onclick="removeRowProductFile(${id})"><i class="fa fa-trash mr-0"></i></button>
				</div>
				<div class="${file_url !== "" ? "hidden" : ""}" id="file-description-edit-${id}" style="padding: 14px; border: 1px solid #dee2e6; border-bottom-left-radius: 6px; border-bottom-right-radius: 6px; display: flex; justify-content: space-between; flex-direction: column; gap: 6px; align-items: end;">
					<input id="description-files-input-${id}" onchange="onChangeFileDescription(this, ${id})" name="description" class="form-control" style="font-size: 16px; " value="${description}"></input>
					<div style="display: flex; justify-content: end; flex-direction: row; gap: 4px;">
						<button type="button" class="btn btn-sm btn-warning" onclick="showFileDescView(${id})"><i class="fa fa-undo mr-0"></i></button>
						<button id="btn-upload-thumbnail-${id}" type="button" class="btn btn-sm btn-info hidden" onclick="onUploadThumbnail(${id})"><i class="fa fa-image mr-0">&nbsp;Thumbnail</i></button>
						<button type="button" id="btn-save-product-file-${id}" onclick="updateProductFile(${id})" form="form-product-file-${id}" class="btn btn-sm btn-success"><i class="fa fa-save mr-0"></i></button>
					</div>
				</div>
			</form>`;
		}

		$('#btn-add-files').on('click', function (e) {
			let last = $("#product_files_container").children().last();
			let fileId = $('[name="product_id"]').val();
			let id = 0;
			if (last && last.length > 0) {
				id = Number(last.attr("file-id"))+1;
			}
			$("#product_files_container").append(renderRowProductFile({ id: id, product_id: fileId, file_url: "", description: "", file_url_thumbnail: "" }, true));

			setTimeout(() => {
				$("#product_files_container").animate({
					scrollTop: $(`form[id='form-product-file-${id}']`).offset().top
				}, 2000);
			}, 200);

		});

		$(document).on('click', '.play-video', function(e) {
			e.preventDefault();
			let videoUrl = $(this).attr('video-url');
			$(e.target).parent().parent().prepend(`<iframe class="card-img-top" src="${videoUrl}" allow="autoplay" frameborder="0"></iframe>`);
			$(e.target).parent().remove();
		});

	});
</script>

@endsection