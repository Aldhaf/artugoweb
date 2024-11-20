@extends('backend.layouts.backend-app')
@section('title', 'Detail Cashback')
@section('content')

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Detail Cashback
      <small>Settings</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-solid">

          <div class="box-header">
            <h3 class="box-title">Cashback information</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-3">
                    <b>Period</b>
                  </div>
                  <div class="col-md-9">
                    : {{ date('d M Y',strtotime($periode->startDate)) . ' - ' . date('d M Y',strtotime($periode->endDate)) }}
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <b>Status</b>
                  </div>
                  <div class="col-md-9">
                    : {{ ($periode->status == '1' ? 'Active' : 'Non-Active') }}
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <b>Type</b>
                  </div>
                  <div class="col-md-9">
                    : {{ ($periode->type_cashback == '1' ? 'Regular' : 'Combine') }}
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <b>Nominal</b>
                  </div>
                  <div class="col-md-9">
                    : Rp {{ number_format($periode->nominal) }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br>
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <div class="col-sm-6">
        <div class="box box-solid">
          <div class="box-header">
            <h3 class="box-title">Products</h3>
          </div>
          <div class="box-body">
            <div class="col-sm-12 table-container">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Product Name</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($product as $val)
                  <tr>
                    <td>{{ $val->product_name_odoo }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <?php
      if ($periode->type_cashback == '2') {
      ?>
        <div class="col-sm-6">
          <div class="box box-solid">
            <div class="box-header">
              <h3 class="box-title">Products Combine</h3>
            </div>
            <div class="box-body">
              <div class="col-sm-12 table-container">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Product Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($product_combine as $val)
                    <tr>
                      <td>{{ $val->product_name_odoo }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      <?php
      }
      ?>

    </div><!-- /.row -->
  </section><!-- /.content -->
</div>



<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

@endsection