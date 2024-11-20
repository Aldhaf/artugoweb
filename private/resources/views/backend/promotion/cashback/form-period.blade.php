@extends('backend.layouts.backend-app')
@section('title', 'Cashback')
@section('content')

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Cashback Period
      <small>{{ ($statusAction = 'insert' ? 'Add' : 'Edit') }} Period</small>
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

            <form autocomplete="off" action="" class="fdata">
              {{ csrf_field() }}
              <div class="row">
                <div class="col-md-4">
                  <label for="">Start Date</label>
                  <input type="text" class="form-control datepicker" placeholder="Start Date" name="startDate">
                </div>
                <div class="col-md-4">
                  <label for="">End Date</label>
                  <input type="text" class="form-control datepicker" placeholder="End Date" name="endDate">
                </div>
                <div class="col-md-4">
                  <label for="">Type Cashback</label>
                  <select name="type_cashback" class="form-control">
                    <option value="1">Regular</option>
                    <option value="2">Combine</option>
                  </select>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-6">
                  <label for="">Nominal Cashback</label>
                  <input type="number" class="form-control" name="nominal" placeholder="Nominal Cashback">
                </div>
                <div class="col-md-6">
                  <label for="">Nominal Extra Cashback</label>
                  <input type="number" class="form-control" name="nominal_extra" placeholder="Nominal Extra Cashback">
                </div>
              </div>
              <br>
              <table class="table tdata">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>#</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <select name="products_id[]" class="form-control select2">
                        <option value="-">[Pilih Product]</option>
                        @foreach($products as $val)
                        <option value="{{ $val->product_id }}">{{ $val->product_name_odoo }}</option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <center>
                        <button class="btn btn-primary btn-add-product">+</button>
                      </center>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div class="div_combine">
                <hr>
                <table class="table tdata_combine">
                  <thead>
                    <tr>
                      <th>Product Combine</th>
                      <th>#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <select name="products_id_combine[]" class="form-control select2">
                          <option value="-">[Pilih Product]</option>
                          @foreach($products as $val)
                          <option value="{{ $val->product_id }}">{{ $val->product_name_odoo }}</option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <center>
                          <button class="btn btn-primary btn-add-product-combine">+</button>
                        </center>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <br>

              <button class="btn btn-primary btn-submit">{{ ($statusAction = 'insert' ? 'Save' : 'Edit') }} Period</button>
              <button class="btn btn-back">Back</button>

            </form>

            <div class="col-sm-12 table-container">


            </div>
          </div>
        </div>
      </div>

    </div><!-- /.row -->
  </section><!-- /.content -->
</div>



<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>
  $('.div_combine').hide();

  var index = 99;

  $(document).on('click', '.btn-add-product', function(e) {
    e.preventDefault();

    $('.tdata tbody').append('<tr class="rowidx_' + index + '">' +
      '<td>' +
      '<select name="products_id[]" class="form-control select2">' +
      '<option value="-">[Pilih Product]</option>' +
      <?php
      foreach ($products as $key => $value) {
      ?> '<option value="{{ $value->product_id }}">{{ $value->product_name_odoo }}</option>' +
      <?php
      }
      ?> '</select>' +
      '</td>' +
      '<td>' +
      '<center>' +
      '<button class="btn btn-primary btn-add-product">+</button>&nbsp;' +
      '<button class="btn btn-remove-product" index="' + index + '">-</button>' +
      '</center>' +
      '</td>' +
      '</tr>');

    $('.select2').select2();
    index++;
  });

  $(document).on('click', '.btn-remove-product', function(e) {
    e.preventDefault();
    let idx = $(this).attr('index');

    $('.tdata tbody .rowidx_' + idx).remove();

  });

  $(document).on('click', '.btn-add-product-combine', function(e) {
    e.preventDefault();

    $('.tdata_combine tbody').append('<tr class="rowidx_' + index + '">' +
      '<td>' +
      '<select name="products_id_combine[]" class="form-control select2">' +
      '<option value="-">[Pilih Product]</option>' +
      <?php
      foreach ($products as $key => $value) {
      ?> '<option value="{{ $value->product_id }}">{{ $value->product_name_odoo }}</option>' +
      <?php
      }
      ?> '</select>' +
      '</td>' +
      '<td>' +
      '<center>' +
      '<button class="btn btn-primary btn-add-product-combine">+</button>&nbsp;' +
      '<button class="btn btn-remove-product-combine" index="' + index + '">-</button>' +
      '</center>' +
      '</td>' +
      '</tr>');

    $('.select2').select2();
    index++;
  });

  $(document).on('click', '.btn-remove-product-combine', function(e) {
    e.preventDefault();
    let idx = $(this).attr('index');

    $('.tdata-combine tbody .rowidx_' + idx).remove();

  });


  $(document).on('click', '.btn-back', function(e) {
    e.preventDefault();
    location.href = '{{ url("artmin/promotion/cashback/settings") }}';
  });

  $(document).on('click', '.btn-submit', function(e) {
    e.preventDefault();

    $('.fdata').submit();
  });

  $(document).on('submit', '.fdata', function(e) {
    e.preventDefault();
    swal({
        title: "Confirmation",
        text: "Are you sure to submit this data?",
        icon: "info",
        buttons: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/promotion/cashback/settings/add-period-process") }}';
          let data = $(this).serializeArray();

          $.post(url, data, function(e) {
            swal('Success', 'Data Has Been Saved', 'success').then((confirm) => location.reload());
          });
        }
      });
  });


  $(document).on('change', '[name="type_cashback"]', function(e) {
    e.preventDefault();
    let val = $(this).val();
    if (val == '1') {
      $('.div_combine').hide();
    } else {
      $('.div_combine').show();
    }
  });
</script>


@endsection