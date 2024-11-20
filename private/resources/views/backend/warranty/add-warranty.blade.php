@extends('backend.layouts.backend-app')
@section('title', 'Add New Warranty')
@section('content')
<div>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Warranty
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
						<form class="frm-warranty" action="{{ url('artmin/warranty/add-warranty-process') }}" method="post" enctype="multipart/form-data">
							{{ csrf_field() }}
							<input type="hidden" name="has_confirm_phone_duplicate" value="{{old('has_confirm_phone_duplicate', 0)}}" />
							<div class="form-group hidden">
								<?php $stock_type = old('stock_type', 'stkavailable'); ?>
								<label for="">Stock Type</label>
								<select name="stock_type" class="form-control select2">
									<option value="stkavailable" {{ $stock_type == 'stkavailable' ? 'selected' : '' }}>Stock Available</option>
									<option value="stkdisplay" {{ $stock_type == 'stkdisplay' ? 'selected' : '' }}>Stock Display</option>
									<option value="stkservice" {{ $stock_type == 'stkservice' ? 'selected' : '' }}>Stock Service</option>
								</select>
								@if ($errors->has('stock_type'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('stock_type') }}</label>
								@endif
							</div>
							<div class="form-group">
								<label>Product Model</label>
								<select class="form-control" name="product_id"></select>
								@if ($errors->has('product_id'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('product_id') }}</label>
								@endif
							</div>
							<div class="form-group">
								<label>Serial Number <span class="error_message_serial_number"></span>
								</label>
								<input type="text" class="form-control" name="serial_no" value="{{ (isset($_GET['serial']) ? $_GET['serial'] : old('serial_no', null)) }}" placeholder="Serial Number">
								@if ($errors->has('serial_no'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('serial_no') }}</label>
								@endif
							</div>
							<div class="form-group">
								<label>Purchase Date</label>
								<input type="text" class="form-control datepicker" name="purchase_date" value="{{ old('purchase_date', date("d-m-Y")) }}" placeholder="dd-mm-yyyy">
								@if ($errors->has('purchase_date'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('purchase_date') }}</label>
								@endif
							</div>
							<div class="form-group">
								<div style="display: flex; justify-content: space-between; align-items: center;">
									<label>Purchase Location</label>
                                    <label style="cursor: pointer;">
                                        <input type="checkbox" name="online_store" {{old('online_store', '')=='on' ? 'checked' : ''}}> Online Store
                                    </label>
								</div>
								<input type="hidden" name="purchase_location_id" value="{{old('purchase_location_id', null)}}">
								<input readonly type="text" class="form-control" style="cursor: pointer;" name="purchase_location" value="{{old('purchase_location', '')}}" placeholder="Store Name / Location">

                                <select class="form-control hidden" name="online_store_name">
                                    <option value="" selected>-- Select Online Store --</option>
                                    <option value="Shopee" <?php if (old('online_store_name', '') == 'Shopee') echo "selected"; ?>>Shopee</option>
                                    <option value="Tokopedia" <?php if (old('online_store_name', '') == 'Tokopedia') echo "selected"; ?>>Tokopedia</option>
                                    <option value="Lainnya" <?php if (old('online_store_name', '') == 'Lainnya') echo "selected"; ?>>Lainnya</option>
                                </select>

								<!-- <input type="text" class="form-control" name="purchase_location" value="{{ old('purchase_location') }}" placeholder="Store Name / Location"> -->
								@if ($errors->has('purchase_location'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('purchase_location') }}</label>
								@endif
							</div>
							<div class="form-group">
								<label>Purchase Invoice</label>
								<input type="file" class="form-control" name="purchase_invoice" value="{{ old('purchase_invoice') }}">
								@if ($errors->has('purchase_invoice'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('purchase_invoice') }}</label>
								@endif
							</div>
							<div class="form-group">
								<label>Customer Type</label>
								<select class="form-control" name="customer_type" id="customer_type">
									<option value="1" <?php if (old('customer_type') == 1) echo "selected"; ?>>New Customer</option>
									<option value="2" <?php if (old('customer_type') == 2) echo "selected"; ?>>Existing Customer</option>
								</select>
								@if ($errors->has('customer_type'))
								<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('customer_type') }}</label>
								@endif
							</div>

							<div class="sub-form <?php if (old('customer_type') == 1) echo "hide";
								else if (old('customer_type') == "") echo "hide"; ?>" id="form-exist-customer">
								<div class="sub-form-title">
									Existing Customer
								</div>
								<div class="form-group">
									<label>Customer</label>
									<select class="form-control" name="customer_id"></select>
									<?php /*
									<select class="form-control select2" name="customer_id">
										<option value="">Select Customer</option>
										<?php foreach ($customer as $cust) : ?>
											<option value="<?= $cust->id ?>" <?php if (old('customer_id') == $cust->id) echo "selected"; ?>><?= $cust->name ?> - <?= $cust->phone ?></option>
										<?php endforeach; ?>
									</select>
									*/ ?>
									@if ($errors->has('customer_id'))
									<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('customer_id') }}</label>
									@endif
								</div>
							</div>

							<div class="sub-form <?php if (old('customer_type') == 2) echo "hide"; ?>" id="form-new-customer">
								<!-- <div class="sub-form-title">
									New Customer
								</div> -->
								<div class="form-group">
									<label>Full Name</label>
									<input type="text" class="form-control" name="new_cust_name" value="{{ old('new_cust_name') }}" placeholder="Customer Name">
									@if ($errors->has('new_cust_name'))
									<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('new_cust_name') }}</label>
									@endif
								</div>
								<div class="form-group">
									<label>Phone</label>
									<input type="text" class="form-control" name="new_cust_phone" value="{{ old('new_cust_phone') }}" placeholder="Phone Number">
									@if ($errors->has('new_cust_phone'))
									<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('new_cust_phone') }}</label>
									@endif
								</div>
								<div class="form-group">
									<label>Email</label>
									<input type="text" class="form-control" name="new_cust_email" value="{{ old('new_cust_email') }}" placeholder="Email">
									@if ($errors->has('new_cust_email'))
									<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('new_cust_email') }}</label>
									@endif
								</div>
								<div class="form-group">
									<label>Address</label>
									<textarea class="form-control" name="new_cust_address" placeholder="Address">{{old('new_cust_address')}}</textarea>
									@if ($errors->has('new_cust_address'))
									<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('new_cust_address') }}</label>
									@endif
								</div>
								<div class="form-group">
									<label>City</label>
									<select class="form-control select2" name="new_cust_city">
										<option value="">Select City</option>
										<?php foreach ($city as $cit) : ?>
											<?php $province = DB::table('ms_loc_province')->where('province_id', $cit->province_id)->first(); ?>
											<option value="<?= $cit->city_id ?>" data-name="<?= $cit->city_name ?>" <?php if (old('new_cust_city') == $cit->city_id) echo "selected"; ?>><?= $province->province_name ?> - <?= $cit->city_name ?></option>
										<?php endforeach; ?>
									</select>
									@if ($errors->has('new_cust_city'))
									<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('new_cust_city') }}</label>
									@endif
								</div>
								<div class="form-group password">
									<label>Password</label>
									<input type="password" class="form-control" name="new_cust_password" value="{{ old('new_cust_password', '') }}" placeholder="Password">
									@if ($errors->has('new_cust_password'))
									<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('new_cust_password') }}</label>
									@endif
								</div>
								<div class="form-group password">
									<label>Password Confirmation</label>
									<input type="password" class="form-control" name="new_cust_password_confirmation" value="{{ old('new_cust_password_confirmation', '') }}" placeholder="Customer Password Confirmation">
									@if ($errors->has('new_cust_password_confirmation'))
									<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('new_cust_password_confirmation') }}</label>
									@endif
								</div>
							</div>

							<div class="form-group">
								<button class="btn btn-primary"><i class="fa fa-plus"></i> Add New Warranty</button>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div><!-- /.row -->

		<div class="modal mconfirm" style="color:#000; z-index: 9999999999;" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<div style="display: flex; gap: 6px; align-items: center;">
							<i class="fa fa-exclamation-circle text-warning"></i><h5 class="modal-title">Konfirmasi</h5>
						</div>
						<button type="button" class="close close-mconfirm" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						@if ($errors->has('duplicate_phone'))
						<label class="control-label text-danger">{{ $errors->first('duplicate_phone')}}</label>
						@endif
						<label class="control-label">Apakah member tersebut sama dengan yang sedang anda daftarkan?</label>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-confirm-no">Tidak</button>
                    	<button type="button" class="btn btn-primary btn-confirm-yes">Ya</button>
                	</div>
				</div>
			</div>
		</div>

		<div class="modal m-select-store" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Select Branch Store</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Regional</label>
									<select class="form-control" name="picker_regional_id"></select>
								</div>
							</div>
						</div>
						<div class="row container_store_select">
							<div class="col-md-12">
								<div class="form-group">
									<label>Store</label>
									<select class="form-control" name="picker_store_id"></select>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button id="btn-select-store" class="btn btn-sm btn-success hidden">SELECT</button>
					</div>
				</div>
			</div>
		</div>

	</section><!-- /.content -->
</div>

<script type="text/javascript">


	var messageDuplicatePhone = "{{$errors->has('duplicate_phone') ? $errors->first('duplicate_phone') : '' }}";
	var customerId = "{{old('customer_id', '')}}";
	var customerName = "{{old('customer_name', '')}}";
	
	var productId = "{{old('product_id', $product_id)}}";
	var productName = "{{old('product_name', $product_name)}}";

	var onlineStore = "{{old('online_store','')}}";
    var onlineStoreName = "{{old('online_store_name','')}}";

	function check_serial_number() {
		let product_id = $('[name="product_id"]').val();

		if (product_id == '-') {
			$('.error_message_product_model').text('(Silahkan Pilih Produk)');
		} else {
			$('.error_message_product_model').text('');
			let serial_number = $('[name="serial_no"]').val();
			if (serial_number) {

				let url = '{{ url("artmin/registerproductcustomer/check_sn") }}' + '/' + product_id + '/' + serial_number;

				$.get(url, function(data) {
					let retData = JSON.parse(data);
					if (retData.status) {
						$('.error_message_serial_number').attr('style', 'color:green');
						$('.error_message_serial_number').text('(Serial No Valid)');
					} else {
						$('.error_message_serial_number').attr('style', 'color:red');
						$('.error_message_serial_number').text('(Serial No Tidak Valid)');
					}
				});

			}
		}

	};

	$(document).on('change', '[name="product_id"]', function(e) {
		e.preventDefault();
		check_serial_number();
	});

	$(document).on('change', '[name="serial_no"]', function(e) {
		e.preventDefault();
		check_serial_number();
	});

	$('#customer_type').on('change', function() {
		var type = $('#customer_type').val();
		var readonly = false;

		$('#form-exist-customer').addClass('hide');
		$(".password").addClass("hide");
		// $('#form-new-customer').addClass('hide');
		if (type == 1) {
			// $('#form-new-customer').removeClass('hide');
			$(".password").removeClass("hide");
		} else if (type == 2) {
			$('#form-exist-customer').removeClass('hide');
			readonly = true;
		}

		$('[name="new_cust_name"]').attr('readonly', readonly);
		$('[name="new_cust_phone"]').attr('readonly', readonly);
		$('[name="new_cust_email"]').attr('readonly', readonly);
		// $('[name="new_cust_address"]').attr('readonly', readonly);
		// $('[name="new_cust_city"]').attr('readonly', readonly);
	});

	$(document).on("click", ".btn-confirm-no", function(e) {
		$('[name="has_confirm_phone_duplicate"]').val(0);
		$(".mconfirm").modal("hide");
		$(".frm-warranty").submit();
    });

	$(document).on("click", ".btn-confirm-yes", function(e) {
		$('[name="has_confirm_phone_duplicate"]').val(1);
		$(".mconfirm").modal("hide");
		$(".frm-warranty").submit();
    });

	function initSelectSeachProduct () {
        if (!$('[name="product_id"]').hasClass("select2")) {
            $('[name="product_id"]').addClass("select2");
        }
        $('[name="product_id"]').select2({
            placeholder: "Search Product...",
            ajax: {
                url: '{{ url("/products/all_json")}}',
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.product_name,
                                id: item.product_id
                            }
                        })
                    };
                },
            },
            escapeMarkup: function(m) {
                return m;
            },
            language: {
                searching: function() {
                    return "Ketik Product Code, Product Name...";
                }
            },
            minimumInputLength: 3,
            cache: false,
            // templateResult: formatResult,
            // templateSelection: formatSelection,
        });
    }

	function initSelectSeachCustomer () {
        if (!$("[name='customer_id']").hasClass("select2")) {
            $("[name='customer_id']").addClass("select2");
        }
        $("[name='customer_id']").select2({
            placeholder: "Search Customer...",
            ajax: {
                url: '{{url("/artmin/member-json")}}',
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                ...item,
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
            },
            escapeMarkup: function(m) {
                return m;
            },
            language: {
                searching: function() {
                    return "Ketik nama pelanggan...";
                }
            },
            minimumInputLength: 3,
            cache: false
        });
    }

	function initSelectSeachRegion () {

		if (!$("[name='picker_regional_id']").hasClass("select2")) {
			$("[name='picker_regional_id']").addClass("select2");
		}

		$("[name='picker_regional_id']").select2({
			placeholder: "Search Regional Branch Store...",
			// dropdownParent: $("[name='picker_regional_id']").parent(),
			ajax: {
				url: '{{url("/artmin/storeregion-json")}}',
				dataType: "json",
				delay: 250,
				processResults: function(data) {
					return {
						results: $.map(data, function(item) {
							return {
								text: item.regional_name,
								id: item.id
							}
						})
					};
				},
			},
			escapeMarkup: function(m) {
				return m;
			},
			language: {
				searching: function() {
					return "Ketik nama kota...";
				}
			},
			cache: false,
		});
	}

	function initSelectSeachStore (regional_id="") {

		if (regional_id === "") {
			$(".container_store_select").addClass("hidden");
			$('#btn-select-store').addClass("hidden");
		} else {
			$(".container_store_select").removeClass("hidden");
		}

		if (!$("[name='picker_store_id']").hasClass("select2")) {
			$("[name='picker_store_id']").addClass("select2");
		}

		$("[name='picker_store_id']").select2({
			placeholder: "Search Branch Store...",
			// dropdownParent: $("[name='picker_store_id']").parent(),
			ajax: {
				url: '{{url("/artmin/storelocation-json")}}' + `?regional_id=${regional_id}`,
				dataType: "json",
				delay: 250,
				processResults: function(data) {
					return {
						results: $.map(data, function(item) {
							return {
								text: item.nama_toko,
								id: item.id
							}
						})
					};
				},
			},
			escapeMarkup: function(m) {
				return m;
			},
			language: {
				searching: function() {
					return "Ketik nama toko...";
				}
			},
			cache: false,
		});
	}

	$("[name='purchase_location']").on("click", function () {
      $(".m-select-store").modal("show");
      $('[name="picker_regional_id"]').select2("val","");
	  $('[name="picker_store_id"]').select2("val","");
    });

	$(document).on("click", "#btn-select-store", function (e) {
		const selectedStore = $('[name="picker_store_id"]').select2("data");
		$("[name='purchase_location_id']").val(selectedStore[0].id);
		$("[name='purchase_location']").val(selectedStore[0].text);
		$(".m-select-store").modal("hide");
	});

	// $(".m-select-store").on("shown.bs.modal", function () {
	// 	initSelectSeachRegion();
	// 	initSelectSeachStore();
	// });

	function onChangeOnlineStore(checked) {
        $('select[name="online_store_name"]').val("");
        $('input[name="purchase_location"]').val("");
        if (checked) {
            $('select[name="online_store_name"]').removeClass("hidden");
            $('input[name="purchase_location"]').addClass("hidden");
        } else {
            $('select[name="online_store_name"]').addClass("hidden");
            $('input[name="purchase_location"]').removeClass("hidden");
        }
        $('input[name="purchase_location"]').val("");
    }

    function initSelectOnlineStore() {
        $('[name="online_store"]').on("change", function(e) {
            onChangeOnlineStore(e.currentTarget.checked);
        });
        $('[name="online_store_name"]').on("change", function(e) {
            $('input[name="purchase_location"]').val(e.currentTarget.value);
        });
        onChangeOnlineStore(onlineStore=="on");
        if (onlineStore=="on") {
            $('select[name="online_store_name"]').val(onlineStoreName);
            $('input[name="purchase_location"]').val(onlineStoreName);
        }
    }

	$(document).ready(function() {
		if (messageDuplicatePhone) {
			$(".mconfirm").modal("show");
		}

		setTimeout(() => {

			initSelectSeachProduct();
			initSelectSeachCustomer();
			initSelectSeachRegion();
			initSelectSeachStore();

			if (productId && productName) {
				let $newOption = $("<option selected='selected'></option>").val(productId).text(productName);
				$("[name='product_id']").append($newOption).trigger('change');
			}

			if (customerId && customerName) {
				let $newOption = $("<option selected='selected'></option>").val(customerId).text(customerName);
				$("[name='customer_id']").append($newOption).trigger('change');
			}

		}, 1000);

		$('[name="picker_regional_id"]').on("change", function () {
			initSelectSeachStore(this.selectedOptions[0].value);
		});

		$('[name="picker_store_id"]').on("change", function () {
			const val = this.selectedOptions[0].value;
			if (val) {
				$('#btn-select-store').removeClass("hidden");
			} else {
				$('#btn-select-store').addClass("hidden");
			}
		});

		initSelectOnlineStore();

	});

	$('[name="customer_id"]').on('select2:select', function (e) {
        var data = e.params.data;
		$('[name="new_cust_name"]').val(data.name);
		$('[name="new_cust_phone"]').val(data.phone);
		$('[name="new_cust_email"]').val(data.email);
		$('[name="new_cust_address"]').val(data.address);
		$('[name="new_cust_city"]').val(data.city_id).trigger('change');
    });

</script>

@endsection