@extends('backend.layouts.backend-app')
@section('title', 'Add Product Customer')
@section('content')
<link rel="stylesheet" href="{{ url('assets/plugins/datepicker/datepicker3.css') }}" type="text/css" />
<script type="text/javascript" src="{{ url('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Product Customer
      <small>New</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <form autocomplete="off" class="fdata" action="{{ url('artmin/product/registerproductcustomer/add-registerproductcustomer-process') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="col-md-6">
          <div class="box box-solid">
            <div class="box-header">
              <h3 class="box-title">Products</h3>
            </div>
            <div class="box-body">
              @include('backend.layouts.alert')
              <div class="form-group">
                <label>Product Model <span style="color:red" class="error_message_product_model"></span></label>
                <select class="form-control select2" name="product_id">
                  <option value="-">Select Product Model</option>
                  <?php foreach ($products as $prod) : ?>
                    <option value="<?= $prod->product_id ?>" <?php if (old('product_id') == $prod->product_id) echo "selected"; ?>><?= $prod->product_name_odoo; ?></option>
                  <?php endforeach; ?>
                </select>
                @if ($errors->has('product_id'))
                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('product_id') }}</label>
                @endif
              </div>



              <div class="form-group">
                <label>
                  Serial Number
                  <span class="error_message_serial_number"></span>
                </label>
                <input type="text" class="form-control form-dark" id="serial_no" name="serial_no" placeholder="Nomor serial produk anda" value="">
                <div id="status-serial">

                </div>
              </div>
              <div class="form-group">
                <label>
                  Purchase Date
                </label>
                <input type="text" class="form-control form-dark datepicker-today" name="purchase_date" placeholder="DD-MM-YYYY" value="">
              </div>
              <div class="form-group">
                <label>
                  Purchase Location
                </label>
                <input type="text" class="form-control form-dark" name="purchase_loc" placeholder="Toko Pembelian Produk" value="">
              </div>
              <div class="form-group">
                <label>
                  Purchase Invoice
                </label>
                <input type="file" class="form-control form-dark" name="purchase_invoice" value="" accept="image/*" style="height: 43px;">
              </div>

            </div>
          </div>
        </div>

        <div class="col-md-6">

          <div class="box box-solid">
            <div class="box-header">
              <h3 class="box-title">Customer</h3>
            </div>
            <div class="box-body">
              <div class="form-group">
                <label>Account Customer</label>
                <select name="type_reg_cus" class="form-control">
                  <option value="new">Create New Account Customer</option>
                  <option value="exist">Exist Account Customer</option>
                </select>
              </div>

              <div class="new-customer">
                <hr>
                <div class="form-group">
                  <label>Full Name</label>
                  <input type="text" class="form-control form-dark" name="new_name" placeholder="Nama Anda" value="">
                </div>
                <div class="form-group">
                  <label>Phone Number</label>
                  <input type="text" class="form-control form-dark" name="new_phone" placeholder="Nomor Telepon" value="">
                </div>
                <div class="form-group">
                  <label>Address</label>
                  <textarea type="text" class="form-control form-dark" name="new_address" placeholder="Alamat"></textarea>
                </div>
                <div class="form-group">
                  <label for="">City</label>
                  <select name="new_city" class="form-control select2">
                    @foreach($city as $val)
                    <option value="{{ $val->city_id }}">{{ $val->city_name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="text" class="form-control form-dark" name="new_email" placeholder="Email" value="">
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control form-dark" name="password" placeholder="Password">
                </div>
                <div class="form-group">
                  <label>Confirm Password</label>
                  <input type="password" class="form-control form-dark" name="password_confirmation" placeholder="Konfirmasi Password">
                </div>
              </div>


              <div class="exist-customer" hidden="true">
                <hr>
                <div class="form-group">
                  <label for="">Customer</label>
                  <select name="member_id" class="form-control select2">
                    <option value="-">Select Customer</option>
                    @foreach($member as $val)
                    <option value="{{ $val->id }}">{{ $val->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>Full Name</label>
                  <input type="text" class="form-control form-dark" name="exist_name" placeholder="Nama Anda" value="">
                </div>
                <div class="form-group">
                  <label>Phone Number</label>
                  <input type="text" class="form-control form-dark" name="exist_phone" placeholder="Nomor Telepon" value="">
                </div>
                <div class="form-group">
                  <label>Address</label>
                  <textarea type="text" class="form-control form-dark" name="exist_address" placeholder="Alamat"></textarea>
                </div>
                <div class="form-group">
                  <label for="">City</label>
                  <select name="exist_city" class="form-control">
                    @foreach($city as $val)
                    <option value="{{ $val->city_id }}">{{ $val->city_name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="text" class="form-control form-dark" name="exist_email" placeholder="Email" value="">
                </div>
              </div>

            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <center>
                <button class="btn btn-primary"><i class="fa fa-plus"></i> Submit Data Product Customer</button>
              </center>
            </div>
          </div>
        </div>
      </form>
    </div><!-- /.row -->
  </section><!-- /.content -->
</div>

<script type="text/javascript">
  <?php
  if (!empty($_GET['code'])) {
    if (isset($_GET['code'])) {
      // $code = $_GET['code'];
      $code = str_replace(' ', '', $_GET['code']);
      $get_product = DB::table('ms_products')->where(DB::raw("REPLACE(product_code, ' ', '')"), 'LIKE', $code)->first();
    }
  ?>
    $('[name="product_id"]').val('{{ $get_product->product_id }}').trigger('change');
  <?php
  }
  ?>

  <?php
  if (!empty($_GET['serial'])) {
  ?>
    $('[name="serial_no"]').val("{{ str_replace(' ', '', $_GET['serial']) }}");
  <?php
  }
  ?>


  $('.datepicker-today').datepicker({
    format: 'dd-mm-yyyy',
    endDate: '0d',
    todayHighlight: true
  })

  $(document).on('change', '[name="type_reg_cus"]', function(e) {
    e.preventDefault();
    let val = $(this).val();

    if (val == 'new') {
      $('.new-customer').prop('hidden', false);
      $('.exist-customer').prop('hidden', true);
    } else {
      $('.new-customer').prop('hidden', true);
      $('.exist-customer').prop('hidden', false);
    }
  });

  $(document).on('change', '[name="member_id"]', function(e) {
    e.preventDefault();

    let member_id = $(this).val();
    let url = '{{ url("artmin/customer/") }}' + '/' + member_id;

    $.get(url, function(ret) {
      let retData = JSON.parse(ret);

      if (retData.status) {
        if (retData.data) {
          let data = retData.data;
          console.log(data);
          $('[name="exist_name"]').val(data.name);
          $('[name="exist_phone"]').val(data.phone);
          $('[name="exist_address"]').val(data.address);
          $('[name="exist_city"]').val(data.city_id);
          $('[name="exist_email"]').val(data.email);
        }
      }
    });
  });

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
</script>

@endsection