@extends('backend.layouts.backend-app')
@section('title', 'Store Sales')
<style>
  .img-responsive {
    width: 30%;
  }

  @media only screen and (max-width: 768px) {
    .img-responsive {
      width: 100%;
    }
  }
</style>
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Store Sales
      <small>Data</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->

    <div class="row">
      <?php if ($sales->approved === 1) { ?>
        <div class="col-md-12 text-right mb-4">
          <button class="btn btn-xs btn-success"><i class="fa fa-check"></i> Approved</button>
        </div>
      <?php } ?>
      <div class="col-sm-12">
          <!-- <div class="box-header">
			          <h3 class="box-title">Products</h3>
			        </div> -->
        <div class="box box-solid">
          <div class="box-body">

            <div class="col-md-6">
              <div class="row">
                <div class="col-6 col-md-4">
                  <b>No Penjualan</b>
                </div>
                <div class="col-6 col-md-8">
                  : {{ $sales->sales_no }}
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-md-4">
                  <b>Tanggal Penjualan</b>
                </div>
                <div class="col-6 col-md-8">
                  : {{ date('d M Y',strtotime($sales->sales_date)) }}
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-md-4">
                  <b>Purchase Location</b>
                </div>
                <div class="col-6 col-md-8">
                  : {{ $sales->purchase_location }}
                </div>
              </div>
              <br>

              <div class="row">
                <div class="col-6 col-md-4">
                  <b>Nama Customer</b>
                </div>
                <div class="col-6 col-md-8">
                  : {{ $sales->customer_nama }}
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-md-4">
                  <b>No Telp Customer</b>
                </div>
                <div class="col-6 col-md-8">
                  : {{ $sales->customer_telp }}
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-md-4">
                  <b>Email Customer</b>
                </div>
                <div class="col-6 col-md-8">
                  : {{ $sales->customer_email }}
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-md-4">
                  <b>Alamat Customer</b>
                </div>
                <div class="col-6 col-md-8">
                  : {{ $sales->customer_alamat }}
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-md-4">
                  <b>Customer City</b>
                </div>
                <div class="col-6 col-md-8">
                  : {{ $sales->customer_city }}
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="">Foto Struk</label>
              <br>
              <img src="{{ $sales->sales_foto_struk }}" class="img-responsive" style="border-radius:10px;border:10px solid #ebebeb">
            </div>

          </div>

        </div>

        <div class="box box-solid">
          <div class="box-body">
            <div class="col-sm-12 table-container">
              <table class="table table-bordered" style="width: 100%;">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Serial No</th>
                    <th>Category B</th>
                    <th>QTY</th>
                    <th>Harga</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($product as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->product_name??'' }}</td>
                    <td>{{ $val->serialno }}</td>
                    <td>{{ ($val->flag_categ_b == '1' ? 'Ya' :'Tidak') }}</td>
                    <td>{{ $val->qty }}</td>
                    <td>{{ number_format($val->harga) }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /.row -->
  </section><!-- /.content -->
</div>

<!-- <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->
<script>
  $('.table').DataTable({
    "sScrollX": '100%'
  });
</script>


@endsection