@extends('backend.layouts.backend-app')
@section('title', 'Register Product Customer')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Register Product Customer
      <small>Data</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <div class="col-sm-12">
        <div class="box box-solid">
          <!-- <div class="box-header">
			          <h3 class="box-title">users</h3>
			        </div> -->
          <div class="box-body">
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Warranty No</th>
                    <th>Product</th>
                    <th>Purchase Date</th>
                    <th>Purchase Location</th>
                    <th>Registered Date</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($reg_warranty as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->warranty_no }}</td>
                    <td>{{ $val->product_name??'' }}</td>
                    <td>{{ date('d M Y',strtotime($val->purchase_date)) }}</td>
                    <td>{{ $val->purchase_loc }}</td>
                    <td>{{ $val->created_at }}</td>
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



<script>

</script>


@endsection