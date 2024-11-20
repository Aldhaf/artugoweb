@extends('backend.layouts.backend-app')
@section('title', 'Special Voucher')
@section('content')

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Special Voucher
      <small>{{ ($statusAction == 'insert' ? 'Add' : 'Edit') }} Period</small>
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

            <form action="{{ ($statusAction == 'insert' ? url('artmin/promotion/specialvoucher/settings/save') : url('artmin/promotion/specialvoucher/settings/update') ) }}" class="fdata">
              {{ csrf_field() }}
              <input type="hidden" name="unique_number_id" value="{{ ($unique_number->id ?? null) }}">
              <div class="row">
                <div class="col-md-12">
                  <label for="">Unique Number</label>
                  <input type="text" class="form-control" name="unique_number" value="{{ ($unique_number->unique_number ?? null) }}" placeholder="Unique Number">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-4">
                  <label for="">Start Date</label>
                  <input type="text" class="form-control datepicker" placeholder="Start Date" value="{{ (!empty($unique_number) ? date('d-m-Y',strtotime($unique_number->date_from)) : null) }}" name="period_from">
                </div>
                <div class="col-md-4">
                  <label for="">End Date</label>
                  <input type="text" class="form-control datepicker" placeholder="End Date" value="{{ (!empty($unique_number) ? date('d-m-Y',strtotime($unique_number->date_to)) : null) }}" name="period_to">
                </div>
                <div class="col-md-4">
                  <label for="">Cashback</label>
                  <input type="text" class="form-control" name="cashback" value="{{ ($unique_number->cashback ?? null) }}" placeholder="Cashback">
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
                  <?php
                  if ($statusAction == 'insert') {
                  ?>
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
                    <?php
                  } else {
                    foreach ($unique_number_products as $key => $val_prod) {
                    ?>
                      <tr class="rowidx_{{ $key }}">
                        <td>
                          <select name="products_id[]" class="form-control select2">
                            @foreach($products as $val)
                            <option {{ ($val_prod->products_id == $val->product_id ? 'selected="true"' : null) }} value="{{ $val->product_id }}">{{ $val->product_name_odoo }}</option>
                            @endforeach
                          </select>
                        </td>
                        <td>
                          <center>
                            <button class="btn btn-primary btn-add-product">+</button>
                            <?php
                            if ($key > 0) {
                            ?>
                              <button class="btn btn-remove-product" index="{{ $key }}">-</button>
                            <?php
                            }
                            ?>
                          </center>
                        </td>
                      </tr>
                  <?php
                    }
                  }
                  ?>

                </tbody>
              </table>

              <br>

              <button class="btn btn-primary btn-submit">{{ ($statusAction == 'insert' ? 'Save' : 'Edit') }} Period</button>
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


  $(document).on('click', '.btn-back', function(e) {
    e.preventDefault();
    location.href = '{{ url("artmin/promotion/specialvoucher/settings") }}';
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
          let url = $(this).attr('action');
          let data = $(this).serializeArray();

          $.post(url, data, function(e) {
            swal('Success', 'Data Has Been Saved', 'success').then((confirm) => location.reload());
          });
        }
      });
  });
</script>


@endsection