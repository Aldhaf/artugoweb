@extends('web.layouts.app')
@section('title', 'Warranty Registration')

@section('content')

<div class="content content-dark" style="padding-top: 0px;">
    <div style="background: url('{{ url('assets/img/bg-slider.png') }}') no-repeat bottom right; background-size: cover; padding-top: 80px;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="warranty-content">
                        <h1 style="margin-bottom: 20px;">Digital Warranty <br>Registration</h1>


                        <div class="form-group">
                            Anda Mendaftarkan Produk Sebagai <i>Artugo Product Consultant (APC)</i><br>
                            <b>{{Auth::user()->name}}</b>
                        </div>


                        <?php
                        // $product_id = "";
                        // $serial_no = "";
                        // if (isset($_GET['code'])) {
                        //     $product_id = $_GET['code'];
                        // }

                        // if (isset($_GET['serial'])) {
                        //     $serial_no = $_GET['serial'];
                        // }
                        ?>

                        <form class="frm-warranty" action="{{ url('warranty/registration-spg') }}" autocomplete="off" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="has_confirm_phone_duplicate" value="{{old('has_confirm_phone_duplicate', 0)}}" />
                            <div class="form-group">
                                <label>Product Model</label>
                                <?php /*if (isset($product)) : ?>
                                    <select class="form-control select2" readonly="" name="product_id">
                                        <option value="{{ $product->product_id }}" selected>{{ $product->product_name_odoo }}</option>
                                    </select>
                                    @if ($errors->has('product_id'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('product_id') }}</label>
                                    @endif
                                <?php else : */ ?>
                                    <select class="form-control" name="product_id"></select>
                                    <div id="status-product">
                                    </div>
                                    @if ($errors->has('product_id'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('product_id') }}</label>
                                    @endif
                                <?php //endif; ?>
                            </div>
                            <div class="form-group">
                                <label>
                                    Serial Number
                                    <span class="help-box">
                                        <span class="help"><i class="fa fa-question"></i></span>
                                        <span class="help-content">
                                            Anda dapat menemukan nomor serial produk anda pada bagian kartu garansi atau pada label yang terletak di belakang produk anda.
                                        </span>
                                    </span>
                                </label>
                                <input type="text" class="form-control form-dark" id="serial_no" name="serial_no" placeholder="Nomor serial produk anda" value="{{ old('serial_no', $serial_no) }}" <?php if (isset($_GET['serial'])) echo "readonly=''"; ?>>
                                @if ($errors->has('serial_no'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('serial_no') }}</label>
                                @endif
                                <div id="status-serial">
                                </div>
                                @if (Session::get('error_serial') != "")
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ Session::get('error_serial') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>
                                    Purchase Date
                                    <span class="help-box">
                                        <span class="help"><i class="fa fa-question"></i></span>
                                        <span class="help-content">
                                            Tanggal pembelian yang tertera pada nota pembelian produk anda.
                                        </span>
                                    </span>
                                </label>
                                <input type="text" class="form-control form-dark datepicker-today" name="purchase_date" readonly="true" style="cursor: pointer;" placeholder="DD-MM-YYYY" value="{{ old('purchase_date') }}">
                                @if ($errors->has('purchase_date'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('purchase_date') }}</label>
                                @endif
                            </div>
                            <hr>
                            <div class="form-group container_store">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <label>
                                        Purchase Location
                                        <span class="help-box">
                                            <span class="help"><i class="fa fa-question"></i></span>
                                            <span class="help-content">
                                                Nama toko pembelian produk ARTUGO.
                                            </span>
                                        </span>
                                    </label>
                                    <label style="cursor: pointer;">
                                        <input type="checkbox" name="online_store" {{old('online_store', '')=='on' ? 'checked' : ''}}> Online Store
                                    </label>
                                </div>
                                <!-- <input type="text" class="form-control form-dark" name="purchase_loc" placeholder="Toko Pembelian Produk" value="{{ old('purchase_loc') }}"> -->
                                <input type="hidden" name="purchase_loc_id" value="{{old('purchase_loc_id', null)}}">
								<input readonly type="text" class="form-control" style="cursor: pointer;" name="purchase_loc" value="{{old('purchase_loc', '')}}" placeholder="Store Name / Location">

                                <select class="form-control hidden" name="online_store_name">
                                    <option value="" selected>-- Select Online Store --</option>
                                    <option value="Shopee" <?php if (old('online_store_name', '') == 'Shopee') echo "selected"; ?>>Shopee</option>
                                    <option value="Tokopedia" <?php if (old('online_store_name', '') == 'Tokopedia') echo "selected"; ?>>Tokopedia</option>
                                    <option value="Lainnya" <?php if (old('online_store_name', '') == 'Lainnya') echo "selected"; ?>>Lainnya</option>
                                </select>

                                @if ($errors->has('purchase_loc'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('purchase_loc') }}</label>
                                @endif
                            </div>
                            <div class="container_store_select hidden" style="display: flex; width: 100%; flex-direction: column;">
                                <div class="form-group">
                                    <label style="width: 100%;">Regional</label>
                                    <select style="width: 100%;" class="form-control" name="picker_regional_id"></select>
                                </div>
                                <div class="form-group">
                                    <label style="width: 100%;">Store</label>
                                    <select style="width: 100%;" class="form-control" name="picker_store_id"></select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label>
                                    Purchase Invoice
                                    <span class="help-box">
                                        <span class="help"><i class="fa fa-question"></i></span>
                                        <span class="help-content">
                                            Foto / scan bukti nota pembelian dari toko
                                        </span>
                                    </span>
                                </label>
                                <input type="file" class="form-control form-dark" name="purchase_invoice" value="{{ old('purchase_invoice') }}" accept="image/*" style="height: 43px;">
                                @if ($errors->has('purchase_invoice'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('purchase_invoice') }}</label>
                                @endif
                            </div>
                            <!-- <div class="form-group">
                                <label>Purchase Invoice</label>
                                <input type="text" class="form-control form-dark" name="purchase_invoice" placeholder="">
                            </div> -->
                            <hr>
                            <div class="form-group">
                                <label>Account Customer</label>
                                <select name="type_reg_cus" class="form-control form-dark">
                                    <option value="new" <?php if (old('type_reg_cus') == 'new') echo "selected"; ?>>Create New Account Customer</option>
									<option value="exist" <?php if (old('type_reg_cus') == 'exist') echo "selected"; ?>>Exist Account Customer</option>
                                </select>
                            </div>

                            <hr>
                            <div class="form-group form-member">
                                <label>Member</label>
                                <select class="form-control" name="member_id"></select>
                                <?php /*
                                <select class="form-control select2" name="member_id">
                                    <option value="">Select Member</option>
                                    @foreach($member as $val)
                                    <option value="{{ $val->id }}" data="{{ json_encode($val) }}">{{ $val->name . ' | ' . $val->phone }}</option>
                                    @endforeach
                                </select>
                                */ ?>
                                @if ($errors->has('member_id'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('member_id') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" class="form-control form-dark" name="name" placeholder="Nama Anda" value="{{ old('name', Session::get('member_name')) }}">
                                @if ($errors->has('name'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('name') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" class="form-control form-dark" name="phone" placeholder="Nomor Telepon" value="{{ old('phone', Session::get('member_phone')) }}">
                                @if ($errors->has('phone'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('phone') }}</label>
                                @endif
                                @if (Session::get('error_phone') != "")
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ Session::get('error_phone') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea type="text" class="form-control form-dark" name="address" placeholder="Alamat">{{ old('address', Session::get('member_address')) }}</textarea>
                                @if ($errors->has('address'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('address') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <?php $city = DB::table('ms_loc_city')->orderBy('province_id')->get(); ?>
                                <select class="form-control select2" name="city">
                                    <option value="">Select City</option>
                                    <?php foreach ($city as $cit) : ?>
                                        <?php $province = DB::table('ms_loc_province')->where('province_id', $cit->province_id)->first(); ?>
                                        <option value="<?= $cit->city_id ?>" <?php if (old('city', Session::get('member_city_id')) == $cit->city_id) echo "selected"; ?>><?= $province->province_name ?> - <?= $cit->city_name ?></option>
                                    <?php endforeach; ?>
                                </select>
                                @if ($errors->has('city'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('city') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control form-dark" name="email" placeholder="Email" value="{{ old('email', Session::get('member_email')) }}" <?php if (Session::has('member_id')) echo "readonly"; ?>>
                                @if ($errors->has('email'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('email') }}</label>
                                @endif
                                @if (Session::get('error_email') != "")
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ Session::get('error_email') }}</label>
                                @endif
                            </div>
                            <div class="form-group form-password">
                                <label>Password</label>
                                <input type="password" class="form-control form-dark" name="password" placeholder="Password">
                                @if ($errors->has('password'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('password') }}</label>
                                @endif
                            </div>
                            <div class="form-group form-password-confirmation">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control form-dark" name="password_confirmation" placeholder="Konfirmasi Password">
                                @if ($errors->has('password_confirmation'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('password_confirmation') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label style="cursor: pointer;">
                                    <input type="checkbox" name="terms_check"> Saya menyetujui <a href="{{ url('pages/terms-and-condition') }}" target="_blank"><b>Syarat dan Ketentuan</b></a> ARTUGO.
                                </label>
                                @if ($errors->has('terms_check'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('terms_check') }}</label>
                                @endif
                            </div>
                            <input type="hidden" name="product_image" id="product_image" value="{{ old('product_image', $product_image)}}">

                            <div class="form-group">
                                <button class="btn btn-white">Daftarkan Produk</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-6">

                    <div class="warranty-product-image">
                        <div class="loading">
                            <div class="loader"></div>
                        </div>
                        <div id="warranty-product-image">
                            <?php if (old('product_image') != "") : ?>
                                <img src="{{ old('product_image') }}">
                            <?php else : ?>
                                <?php if ($product_image != "") : ?>
                                    <img src="{{ $product_image }}">
                                <?php else : ?>
                                    <img src="{{ url('assets/img/artugo-digital-warranty.png') }}">
                                <?php endif; ?>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

</div>

@push('js')
<script>
    var messageDuplicatePhone = "{{$errors->has('duplicate_phone') ? $errors->first('duplicate_phone') : '' }}";
    // var type_account = 'new';
    var type_account = "{{old('type_reg_cus','new')}}";

    var customerId = "{{old('member_id', '')}}";
	var customerName = "{{old('name', '')}}";

    var productId = "{{old('product_id', $product_id)}}";
	var productName = "{{old('product_name', $product_name)}}";

    var defaultCode = "{{isset($product) ? $product->product_id : ''}}";
    var onlineStore = "{{old('online_store','')}}";
    var onlineStoreName = "{{old('online_store_name','')}}";

    function actionChangeType(type) {
        if (type == 'new') {
            $('.form-password').attr('hidden', false);
            $('.form-password-confirmation').attr('hidden', false);
            $('.form-member').addClass('d-none');

            $('[name="name"]').attr('readonly', false);
            $('[name="phone"]').attr('readonly', false);
            // $('[name="city"]').attr('readonly', false);
            $('[name="email"]').attr('readonly', false);

        } else {
            $('.form-password').attr('hidden', true);
            $('.form-password-confirmation').attr('hidden', true);
            $('.form-member').removeClass('d-none');

            $('[name="name"]').attr('readonly', true);
            $('[name="phone"]').attr('readonly', true);
            // $('[name="city"]').attr('readonly', true);
            $('[name="email"]').attr('readonly', true);
        }
    }

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

    function initSelectSeachMember () {
        if (!$("[name='member_id']").hasClass("select2")) {
            $("[name='member_id']").addClass("select2");
        }
        $("[name='member_id']").select2({
            placeholder: "Search Member...",
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
            cache: false,
            // templateResult: formatResult,
            // templateSelection: formatSelection,
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
            // $('#btn-select-store').addClass("hidden");
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

    $("[name='purchase_loc']").on("click", function () {
      $(".m-select-store").modal("show");
      $('[name="picker_regional_id"]').select2("val","");
	  $('[name="picker_store_id"]').select2("val","");

      if($(".container_store_select").hasClass("hidden")) {
        $(".container_store_select").removeClass("hidden");
      } else {
        $(".container_store_select").addClass("hidden");
      }
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

	$(document).on("click", "#btn-select-store", function (e) {
		const selectedStore = $('[name="picker_store_id"]').select2("data");
		$("[name='purchase_loc_id']").val(selectedStore[0].id);
		$("[name='purchase_loc']").val(selectedStore[0].text);
		$(".m-select-store").modal("hide");
	});

    $(document).on("click",".close-mconfirm", function(e) {
		$(".mconfirm").modal("hide");
    });

    function onChangeOnlineStore(checked) {
        $('select[name="online_store_name"]').val("");
        $('input[name="purchase_loc"]').val("");
        if (checked) {
            $('select[name="online_store_name"]').removeClass("hidden");
            $('input[name="purchase_loc"]').addClass("hidden");
        } else {
            $('select[name="online_store_name"]').addClass("hidden");
            $('input[name="purchase_loc"]').removeClass("hidden");
        }
        $('input[name="purchase_loc"]').val("");
    }

    function initSelectOnlineStore() {
        $('[name="online_store"]').on("change", function(e) {
            onChangeOnlineStore(e.currentTarget.checked);
        });
        $('[name="online_store_name"]').on("change", function(e) {
            $('input[name="purchase_loc"]').val(e.currentTarget.value);
        });
        onChangeOnlineStore(onlineStore=="on");
        if (onlineStore=="on") {
            $('select[name="online_store_name"]').val(onlineStoreName);
            $('input[name="purchase_loc"]').val(onlineStoreName);
        }
    }

    $(document).ready(function () {

        if (messageDuplicatePhone) {
			$(".mconfirm").modal("show");
		}

        // if (!defaultCode) {
            setTimeout(() => {
                initSelectSeachProduct();
                initSelectSeachMember();
                initSelectSeachRegion();
			    initSelectSeachStore();
                actionChangeType(type_account);

                if (productId && productName) {
                    let $newOption = $("<option selected='selected'></option>").val(productId).text(productName);
                    $("[name='product_id']").append($newOption).trigger('change');
                }

                if (customerId && customerName) {
                    let $newOption = $("<option selected='selected'></option>").val(customerId).text(customerName);
                    $("[name='member_id']").append($newOption).trigger('change');
                }

                check_serial();
    
            }, 1000);
        // }

		$('[name="picker_regional_id"]').on("change", function () {
			initSelectSeachStore(this.selectedOptions[0].value);
		});

		$('[name="picker_store_id"]').on("change", function () {
            const selectedStore = $(this).select2("data");
            $("[name='purchase_loc_id']").val(selectedStore[0].id);
            $("[name='purchase_loc']").val(selectedStore[0].text);
            $(".container_store_select").addClass("hidden");
		});

        initSelectOnlineStore();

    });

    $(document).on('change', '[name="type_reg_cus"]', function(e) {
        e.preventDefault();
        actionChangeType($(this).val());
    });

    // $(document).on('change', '[name="member_id"]', function(e) {
    //     // e.preventDefault();
    //     // let data = JSON.parse($('option:selected', this).attr('data'));
    //     if ($('[name="member_id"]').select2('data').length > 0) {
    //         var data = $('[name="member_id"]').select2('data')[0];
    //         if (data) {
    //             $('[name="name"]').val(data.name);
    //             $('[name="phone"]').val(data.phone);
    //             $('[name="address"]').val(data.address);
    //             $('[name="city"]').val(data.city_id).trigger('change');
    //             $('[name="email"]').val(data.email);
    //         }
    //     } else {
    //         $('[name="name"]').val("");
    //         $('[name="phone"]').val("");
    //         $('[name="address"]').val("");
    //         $('[name="city"]').val("").trigger('change');
    //         $('[name="email"]').val("");
    //     }
    // });

    $('[name="member_id"]').on('select2:select', function (e) {
        var data = e.params.data;
        $('[name="name"]').val(data.name);
        $('[name="phone"]').val(data.phone);
        $('[name="address"]').val(data.address);
        $('[name="city"]').val(data.city_id).trigger('change');
        $('[name="email"]').val(data.email);
    });

    $('[name="product_id"]').on("change", function() {
        var product_id = $('[name="product_id"]').val();
        $('.loading').show(200);
        $('#warranty-product-image').html('');
        $.ajax({
            url: '{{ url("warranty/get-product")}}',
            method: 'GET',
            data: 'product_id=' + product_id,
            success: function(image) {
                if (image != 0) {
                    $('#warranty-product-image').html('<img src="' + image + '">');
                    $('#product_image').val(image);
                    $('.loading').hide(200);
                    check_serial();
                }
            }
        })
    });

    $('#serial_no').blur(function() {
        check_serial();
    });

    function check_serial() {
        var serial_no = $('#serial_no').val();
        var product_id = $('[name="product_id"]').val();
        $('#status-product').html('')
        if (product_id == '') {
            $('#status-product').html('<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> Silahkan pilih Produk.</label>')
        } else {
            if (serial_no != "") {
                $.ajax({
                    url: "{{ url('warranty/check-serial')}}",
                    method: 'GET',
                    data: 'serial_no=' + serial_no + '&product_id=' + product_id,
                    success: function(e) {
                        if (e == 'false') {
                            $('#status-serial').html('<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> Nomor Serial tidak ditemukan. <br>Mohon periksa kembali Nomor Serial atau Model Produk anda, atau hubungi kami di <b>care@artugo.co.id</b> untuk bantuan lebih lanjut.</label>')
                        } else {
                            $('#status-serial').html('<label class="control-label input-success" for="inputError"><i class="fa fa-check-circle"></i> Nomor Serial valid.</label>')
                        }
                    }
                });
            }
        }
    }
</script>
@endpush

@endsection