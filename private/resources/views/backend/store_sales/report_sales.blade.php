@extends('backend.layouts.backend-app')
@section('title', 'Add New Report Sales')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Report Sales
      <small>{{ ($statusAction == 'insert' ? 'Buat Laporan Data' : 'Update Laporan Data') }}</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <form autocomplete="off" class="fdata" action="{{ ($statusAction == 'insert' ? url('artmin/storesales/report-sales-process') : url('artmin/storesales/edit-sales-process')) }}" enctype="multipart/form-data" method="post">
      <div class="row">
        <?php if ($statusAction == 'update' && $sales->status > 0) { ?>
          <div class="col-md-12 text-right mb-4">
            @if ($sales->status == 1)
              <button class="btn btn-xs btn-success"><i class="fa fa-hourglass"></i> Request Approve</button>
            @elseif ($sales->status == 2)
              <button class="btn btn-xs btn-success"><i class="fa fa-check"></i> Approved</button>
            @endif
          </div>
        <?php } ?>
        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-body">
              @include('backend.layouts.alert')
              {{ csrf_field() }}
              <input type="hidden" name="sales_unique_id" value="{{ ($statusAction == 'update' ? $sales->sales_id : null) }}">
              <div class="row">
                <div class="col-12 col-md-4">
                  <div class="form-group">
                    <label>No Penjualan</label>
                    <input type="text" class="form-control" name="sales_id" value="{{ old('sales_id', ($statusAction == 'update' ? $sales->sales_no : '')) }}" placeholder="No Penjualan" required="true">
                    @if ($errors->has('sales_id'))
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('sales_id') }}</label>
                    @endif
                  </div>
                </div>

                <div class="col-12 col-md-4">
                  <div class="form-group">
                    <label>Tanggal Penjualan</label>
                    <input type="text" class="form-control datepicker" name="sales_date" value="{{ old('sales_date', ($statusAction == 'update' ? date('d-m-Y', strtotime($sales->sales_date)) : date('d-m-Y')) ) }}" placeholder="Tanggal Penjualan" required="true">
                    @if ($errors->has('sales_date'))
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('sales_date') }}</label>
                    @endif
                  </div>
                </div>

                <div class="col-12 col-md-4">
                  <div class="form-group">
                    <label>Purchase Location</label>
                    <input type="hidden" name="store_id" value="{{ ($statusAction == 'update' ? $sales->store_id : (isset($default_store_id) ? $default_store_id : '')) }}">
                    <input readonly type="text" class="form-control" name="purchase_location" value="{{ old('purchase_location', $statusAction == 'update' ? $sales->purchase_location : (isset($default_store_name) ? $default_store_name : '')) }}" placeholder="Store Name / Location">
                    @if ($errors->has('purchase_location'))
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('purchase_location') }}</label>
                    @endif
                  </div>
                </div>

              </div>

              <div class="row">
                <div class="col-12 col-md-4">
                  <div class="form-group">
                    <label for="">Nama Customer</label>
                    <input type="text" class="form-control" placeholder="Nama Customer" value="{{ old('customer_nama', ($statusAction == 'update' ? $sales->customer_nama : '')) }}" name="customer_nama" required="true">
                    @if ($errors->has('customer_nama'))
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('customer_nama') }}</label>
                    @endif
                  </div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="form-group">
                    <label for="">No Telp Customer</label>
                    <input type="text" class="form-control" placeholder="No Telp Customer" value="{{ old('customer_telp', ($statusAction == 'update' ? $sales->customer_telp : null)) }}" name="customer_telp">
                    @if ($errors->has('customer_telp'))
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('customer_telp') }}</label>
                    @endif
                  </div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" placeholder="Email" value="{{ old('customer_email', ($statusAction == 'update' ? $sales->customer_email : null)) }}" name="customer_email">
                    @if ($errors->has('customer_email'))
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('customer_email') }}</label>
                    @endif
                  </div>
                </div>
  
              </div>

              <div class="row">
                <div class="col-12 col-md-4">
                  <div class="form-group">
                    <label for="">Alamat Customer</label>
                    <input type="text" class="form-control" placeholder="Alamat Customer" value="{{ old('customer_alamat', ($statusAction == 'update' ? $sales->customer_alamat : null)) }}" name="customer_alamat">
                    @if ($errors->has('customer_alamat'))
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('customer_alamat') }}</label>
                    @endif
                  </div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="form-group">
                    <label>Customer City</label>
                    <select class="form-control select2" name="customer_city">
                      <option value="">Select City</option>
                      <?php foreach ($city as $cit) : ?>
                        <?php $province = DB::table('ms_loc_province')->where('province_id', $cit->province_id)->first(); ?>
                        <option value="<?= $cit->city_id ?>" data-name="<?= $cit->city_name ?>" <?php if (old('customer_city', $statusAction == 'update' ? $sales->customer_city : null) == $cit->city_id) echo "selected"; ?>><?= $province->province_name ?> - <?= $cit->city_name ?></option>
                      <?php endforeach; ?>
                    </select>
                    @if ($errors->has('customer_city'))
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('customer_city') }}</label>
                    @endif
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Foto Struk</label>
                    <input type="file" id="fileStruk" name="foto_struk" class="form-control" required="true">
                    <br>
                    @if ($errors->has('foto_struk') && ($statusAction == 'update' ? old('sales_foto_struk', $sales->sales_foto_struk) : '') == '')
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('foto_struk') }}</label>
                    @endif
                    <img id="blah" src="{{ ($statusAction == 'update' ? $sales->sales_foto_struk : null) }}" style="width:60%" />
                  </div>
                </div>

              </div>

            </div>
          </div>
        </div>
      </div>

      <div class="row_product">
        <?php

        $hasOld = old('product_id') ? true : false;
        $countProduct = $hasOld ? count(old('product_id')) : ($statusAction === 'insert' ? 1 : count($sales_product));
        // if ($statusAction == 'insert') {

        for ($index = 0; $index < $countProduct; $index++) {
            $sales_id = $hasOld ? old('sales_id')[$index] : ($statusAction === 'insert' ? 0 : $sales_product[$index]->sales_id);
            $product_id = $hasOld ? old('product_id')[$index] : ($statusAction === 'insert' ? null : $sales_product[$index]->product);
            $serialno = $hasOld ? old('serialno')[$index] : ($statusAction === 'insert' ? '' : $sales_product[$index]->serialno);
            $qty = $hasOld ? old('qty')[$index] : ($statusAction === 'insert' ? 0 : $sales_product[$index]->qty);
            $harga = $hasOld ? old('harga')[$index] : ($statusAction === 'insert' ? 0 : $sales_product[$index]->harga);
            $flag_categ_b = $hasOld ? old('flag_categ_b')[$index] : ($statusAction === 'insert' ? 0 : $sales_product[$index]->flag_categ_b);
            $stock_type = $hasOld ? old('stock_type')[$index] : ($statusAction === 'insert' ? 'stkavailable' : $sales_product[$index]->stock_type);
        ?>
          <div class="row row_{{$index}}">
            <div class="col-md-12">
              <div class="box box-solid">
                <div class="row">
                  <div class="col-md-12 text-right">
                    <button class="btn btn-xs btn-danger btn-hapus-kolom" index="{{$index}}"><i class="fa fa-trash"></i> Hapus</button>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-md-4">
                    <label for="">Product</label>
                    <select name="product_id[]" class="form-control select2">
                      <option value="">[Pilih Produk]</option>
                      @foreach($product as $val)
                      <option {{ ($val->product_id == $product_id ? 'selected="true"' : null) }} value="{{ $val->product_id }}">{{ $val->product_name_odoo }}</option>
                      @endforeach
                      </select>
                      @if ($errors->has('product_id.' . $index))
                      <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('product_id.' . $index) }}</label>
                      @endif
                  </div>
                  <div class="col-12 col-md-4">
                    <label for="">Serial Number</label>
                    <input type="text" class="form-control" name="serialno[]" placeholder="Serial Number" value="{{$serialno}}">
                    @if ($errors->has('serialno.' . $index))
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('serialno.' . $index) }}</label>
                    @endif
                  </div>
                  <div class="col-12 col-md-4">
                    <div class="form-group hidden">
                      <label for="">Stock Type</label>
                      <select name="stock_type[]" class="form-control select2">
                        <option value="stkavailable" {{ ($stock_type ?? 'stkavailable') == 'stkavailable' ? 'selected' : '' }}>Stock Available</option>
                        <option value="stkdisplay" {{ $stock_type == 'stkdisplay' ? 'selected' : '' }}>Stock Display</option>
                        <option value="stkservice" {{ $stock_type == 'stkservice' ? 'selected' : '' }}>Stock Service</option>
                      </select>
                      @if ($errors->has('stock_type.' . $index))
                      <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('stock_type.' . $index) }}</label>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-md-4">
                    <label for="">Qty</label>
                    <input type="number" class="form-control" placeholder="Qty" name="qty[]" value="{{$qty}}">
                    @if ($errors->has('qty.' . $index))
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('qty.' . $index) }}</label>
                    @endif
                  </div>
                  <div class="col-12 col-md-4">
                    <label for="">Harga</label>
                    <input type="text" class="form-control price" placeholder="Harga" name="harga[]" value="{{$harga}}">
                    @if ($errors->has('harga.' . $index))
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('harga.' . $index) }}</label>
                    @endif
                  </div>
                  <div class="col-12 col-md-4">
                    <div class="form-group">
                      <label for="">Kategori B</label>
                      <!-- <input type="checkbox" name="flag_categ_b[]"> -->
                      <select name="flag_categ_b[]" class="form-control">
                        <option {{$flag_categ_b == '0' ? 'selected' : ''}} value="0">Tidak</option>
                        <option {{$flag_categ_b == '1' ? 'selected' : ''}} value="1">Ya</option>
                      </select>
                      @if ($errors->has('flag_categ_b.' . $index))
                      <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('flag_categ_b.' . $index) }}</label>
                      @endif
                    </div>
                  </div>                  
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>

      <?php if ($statusAction == 'insert' || ($statusAction == 'update' && $sales->status === 0)) { ?>
      <div class="row">
        <div class="col-md-12">
          <center>
            <button class="btn btn-warning btn-tambah-kolom-produk"><i class="fa fa-plus"></i> Add Product</button>
          </center>
        </div>
      </div>
      <?php } ?>

      <hr>
      <div class="form-group">
        <center>
          <?php if ($statusAction == 'insert' || ($statusAction == 'update' && $sales->status < 2)) { ?>
          <button class="btn btn-primary btn-submit"><i class="fa fa-save"></i> Save</button>
          <?php } ?>
          <?php if ($statusAction == 'update' && ((Auth::user()->roles === 5 && $sales->status === 0) || (Auth::user()->roles === 8 && $sales->status === 1)) ) { ?>
          <button class="btn btn-success btn-approve" sales_id="{{ $sales->sales_id }}" sales_no="{{ $sales->sales_no }}"><i class="fa fa-{{Auth::user()->roles === 5 ? 'paper-plane' : 'check-circle'}}"></i> {{ Auth::user()->roles === 5 ? 'Request Approve' : 'Approve' }}</button>
          <?php } ?>
        </center>
      </div>

      <div class="modal m-add-store" role="dialog">
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
                    <select class="form-control select2" name="ms_store_users[regional_id]">
                      <option value="">Select Regional</option>
                      @foreach($store_location_regional as $val)
                      <option value="{{ $val->id }}">{{ $val->regional_name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group" id="container_store_select">
                    <label>Store</label>
                    <select class="form-control" name="ms_store_users[store_id]"></select>
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

  <?php /* if ($statusAction == 'update') { ?>
    $product_id = 1;
    foreach ($sales_product as $key => $value) {
      $product_id = $hasOld ? old('product_id')[$key] : $value->product;
      $serialno = $hasOld ? old('serialno')[$key] : $value->serialno;
      $qty = $hasOld ? old('qty')[$key] : $value->qty;
      $harga = $hasOld ? old('harga')[$key] : $value->harga;
      $flag_categ_b = $hasOld ? old('flag_categ_b')[$key] : $value->flag_categ_b;
      $stock_type = $hasOld ? old('stock_type')[$key] : $value->stock_type;
    ?>
  <div class="row row_{{ $key }}">
    <div class="col-md-12">
      <input type="hidden" name="detail_sales_id[]" value="{{ $value->detail_sales_id }}">
      <div class="box box-solid">
        <div class="row">
          <div class="col-md-12 text-right">
            <button class="btn btn-xs btn-danger btn-hapus-kolom" index="{{ $key }}"><i class="fa fa-trash"></i> Hapus</button>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-md-4">
            <label for="">Product</label>
            <select name="product_id[]" class="form-control select2">
              <option value="">[Pilih Produk]</option>
              @foreach($product as $val)
              <option {{ ($val->product_id == $product_id ? 'selected="true"' : null) }} value="{{ $val->product_id }}">{{ $val->product_name_odoo }}</option>
              @endforeach
            </select>
            @if ($errors->has('product_id.' . $key))
            <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('product_id.' . $key) }}</label>
            @endif
          </div>
          <div class="col-12 col-md-4">
            <label for="">Serial Number</label>
            <input type="text" class="form-control" name="serialno[]" value="{{ $serialno }}" placeholder="Serial Number">
            @if ($errors->has('serialno.' . $key))
            <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('serialno.' . $key) }}</label>
            @endif
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="">Stock Type</label>
              <select name="stock_type[]" class="form-control select2">
                <option value=""></option>
                <option value="stkavailable" {{ $stock_type == 'stkavailable' ? 'selected' : '' }}>Stock Available</option>
                <option value="stkdisplay" {{ $stock_type == 'stkdisplay' ? 'selected' : '' }}>Stock Display</option>
                <option value="stkservice" {{ $stock_type == 'stkservice' ? 'selected' : '' }}>Stock Service</option>
              </select>
              @if ($errors->has('stock_type.' . $key))
              <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('stock_type.' . $key) }}</label>
              @endif
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-md-4">
            <label for="">Qty</label>
            <input type="text" class="form-control" value="{{ $qty }}" placeholder="Qty" name="qty[]">
            @if ($errors->has('qty.' . $key))
            <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('qty.' . $key) }}</label>
            @endif
          </div>
          <div class="col-12 col-md-4">
            <label for="">Harga</label>
            <input type="text" class="form-control price" value="{{ $harga }}" placeholder="Harga" name="harga[]">
            @if ($errors->has('harga.' . $key))
            <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('harga.' . $key) }}</label>
            @endif
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="">Kategori B</label>
              <select name="flag_categ_b[]" class="form-control">
                <option {{ ($flag_categ_b == '0' ? 'selected' : null) }} value="0">Tidak</option>
                <option {{ ($flag_categ_b == '1' ? 'selected' : null) }} value="1">Ya</option>
              </select>
              @if ($errors->has('flag_categ_b.' . $key))
              <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('flag_categ_b.' . $key) }}</label>
              @endif
            </div>
          </div>
          <br>
          <!-- <center>
            <button class="btn btn-hapus-kolom ml-4" index="{{ $key }}">Hapus Kolom</button>
          </center> -->
        </div>
      </div>
    </div>
  </div>
<?php } */ ?>

    </form>
  </section><!-- /.content -->
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script type="text/javascript">
  var index_kolom = 99;

  $(document).on('click', '.btn-tambah-kolom-produk', function(e) {
    e.preventDefault();

    $('.row_product').append('<div class="row row_' + index_kolom + '">' +
      '<div class="col-md-12">' +
      '<div class="box box-solid">' +
      '<div class="row">' +
      '<div class="col-md-12 text-right">' +
      '<button class="btn btn-xs btn-danger btn-hapus-kolom" index="' + index_kolom + '"><i class="fa fa-trash"></i> Hapus</button>' +
      '</div>' +
      '</div>' +
      '<div class="row">' +
      '<div class="col-12 col-md-4">' +
      '<label for="">Product</label>' +
      '<select name="product_id[]" class="form-control select2">' +
      '<option value="">[Pilih Produk]</option>' +
      <?php
      foreach ($product as $key => $val) {
      ?> '<option value="{{ $val->product_id }}">{{ $val->product_name_odoo }}</option>' +
      <?php
      }
      ?> '</select>' +
      '</div>' +
      '<div class="col-12 col-md-4">'+
      '<label for="">Serial Number</label>'+
      '<input type="text" class="form-control" name="serialno[]" placeholder="Serial Number">'+
      '</div>'+
      '<div class="col-12 col-md-4">' +
      '<div class="form-group hidden">' +
      '<label for="">Stock Type</label>' +
      '<select name="stock_type[]" class="form-control select2">' +
      '<option value="stkavailable" selected>Stock Available</option>' +
      '<option value="stkdisplay">Stock Display</option>' +
      '<option value="stkservice">Stock Service</option>' +
      '</select>' +
      '</div>' +
      '</div>' +
      '</div>' +
      '<div class="row">' +
      '<div class="col-12 col-md-4">' +
      '<label for="">Qty</label>' +
      '<input type="text" class="form-control" placeholder="Qty" name="qty[]">' +
      '</div>' +
      '<div class="col-12 col-md-4">' +
      '<label for="">Harga</label>' +
      '<input type="text" class="form-control price" placeholder="Harga" name="harga[]">' +
      '</div>' +
      '<div class="col-12 col-md-4">' +
      '<div class="form-group">' +
      '<label for="">Kategori B</label>' +
      // '<input type="checkbox" name="flag_categ_b[]">'+
      '<select name="flag_categ_b[]" class="form-control">' +
      '<option value="0">Tidak</option>' +
      '<option value="1">Ya</option>' +
      '</select>' +
      '</div>' +
      '</div>' +
      // '<br>' +
      // '<center>' +
      // '<button class="btn btn-hapus-kolom ml-4" index="' + index_kolom + '">Hapus Kolom</button>' +
      // '</center>' +
      '</div>' +
      '</div>' +
      '</div>' +
      '</div>');

    index_kolom++;
    $('.select2').select2();
  });

  $(document).on('click', '.btn-hapus-kolom', function(e) {
    e.preventDefault();

    let idx = $(this).attr('index');
    $('.row_product .row_' + idx).remove();
  });


  $(document).on('keyup', '.price', function(e) {
    $(this).val(numeral($(this).val()).format('0,0'));
  });


  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function(e) {
        $('#blah').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
  }

  $("#fileStruk").change(function() {
    readURL(this);
    console.log('changed');
  });


  $(document).on('click', '.btn-submit', function(e) {
    e.preventDefault();

    <?php
    if ($statusAction == 'insert') {
    ?>
      // if (document.getElementById("fileStruk").files.length == 0) {
      //   return swal('Foto Struk Belum Dipilih');
      // }
      swal({
        title: "Confirmation",
        text: "Are you sure to submit this data?",
        icon: "info",
        buttons: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $('.fdata').submit();
        }
      });
    <?php
    } else {
    ?>
      swal({
          title: "Confirmation",
          text: "Are you sure to submit this data?",
          icon: "info",
          buttons: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            $('.fdata').submit();
          }
        });

    <?php } ?>

  });

	function approveProcess (sales_id, sales_no) {
    
    const role = {{Auth::user()->roles}};
		const confirmMessage = role === 8 ? "Setelah di Approve data akan di registrasikan ke Warranty." : "Setelah di Approve data tidak bisa di ubah.";

		swal({
			title: "Konfirmasi",
			text: confirmMessage,
			icon: "info",
			buttons: true,
			dangerMode: true,
		})
		.then((willApprove) => {
			if (willApprove) {
				let url = '{{ url("artmin/storesales/approve") }}';
				let data = {
					"_token": "{{ csrf_token() }}",
					"sales_id": sales_id
				}
				$.post(url, data, function(res) {
					if (res.success) {
						swal('Berhasil', `Data penjualan ${sales_no} ${role === 8 ? "telah di Approve" : "menunggu di Approve!"}`, 'success').then((confirm) => location.reload());
					} else {
						const message = res.message || `Gagal ${role === 8 ? "Approve" : "Request Approve"} penjualan ${sales_no}!`;
						swal('Gagal', message, 'error');
					}
				});
			}
		});
	}

	$(document).on('click', '.btn-approve', function(e) {
		e.preventDefault();

		let sales_id = $(this).attr('sales_id');
		let sales_no = $(this).attr('sales_no');

		let url = '{{ url("artmin/storesales/approve-confirm") }}';
		let data = {
			"_token": "{{ csrf_token() }}",
			"sales_id": sales_id
		}
		$.post(url, data, function(res) {
			if (res.message) {
				swal({
					title: "Informasi",
					text: res.message,
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willApprove) => {
					if (willApprove) {
						approveProcess(sales_id, sales_no);
					}
				});
			} else {
				approveProcess(sales_id, sales_no);
			}
		});
	});

	function initSelectSeachBranchStore (regional_id="") {

    if (regional_id === "") {
      $("#container_store_select").addClass("hidden");
      $('#btn-select-store').addClass("hidden");
      return;
    }

    $("#container_store_select").removeClass("hidden");

    if (!$("[name='ms_store_users[store_id]']").hasClass("select2")) {
        $("[name='ms_store_users[store_id]']").addClass("select2");
    }

    $("[name='ms_store_users[store_id]']").select2({
      placeholder: "Search Branch Store...",
      ajax: {
        url: '{{url("/artmin/storelocation-json")}}' + "?users_id={{Auth::user()->id}}" + (regional_id ? `&regional_id=${regional_id}` : ""),
        dataType: "json",
        delay: 250,
        processResults: function(data) {
            return {
                results: $.map(data, function(item) {
                    return {
                        text: item.nama_toko,
                        id: item.store_id
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

  $(document).on("click", "#btn-select-store", function (e) {
    const selectedStore = $('[name="ms_store_users[store_id]"]').select2("data");
    $("[name='store_id']").val(selectedStore[0].id);
    $("[name='purchase_location']").val(selectedStore[0].text);
    $(".m-add-store").modal("hide");
  });

  $(document).on("ready", function () {
	
    setTimeout(() => {
      initSelectSeachBranchStore();
    }, 1000);

    $("[name='purchase_location']").on("click", function () {
      $(".m-add-store").modal("show");
      $('[name="ms_store_users[regional_id]"]').select2("val","");
      // $("#container_store_select").addClass("hidden");
    });

    $('[name="ms_store_users[regional_id]"]').on("change", function () {
			initSelectSeachBranchStore(this.selectedOptions[0].value);
		});

    $('[name="ms_store_users[store_id]"]').on("change", function () {
			const val = this.selectedOptions[0].value;
      if (val) {
        $('#btn-select-store').removeClass("hidden");
      } else {
        $('#btn-select-store').addClass("hidden");
      }
		});

  });

</script>

@endsection