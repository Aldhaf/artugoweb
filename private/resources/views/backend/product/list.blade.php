@extends('backend.layouts.backend-app')
@section('title', 'Product')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Product
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
			          <h3 class="box-title">Products</h3>
			        </div> -->
          <div class="box-body">
            <div class="form-group">
              <a href="{{ url('artmin/product/add-product') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Product</a>
              <a href="{{ url('artmin/product/export') }}" target="_blank">
                <button style="margin-right: 10px;" class="btn btn-primary pull-right btn-export-excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Data Excel</button>
              </a>
            </div>
            <div class="col-sm-12 table-container" style="margin-top: 10px;">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th style="width:10%">Image</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Warranty</th>
                    <th>Warranty Year</th>
                    <th>Publish</th>
                    <th>Visibility</th>
                    <th>Base Point</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($product as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td><img src="{{ $val->product_image??'' }}" style="width:100%"></td>
                    <td>{{ $val->product_name_odoo }}</td>
                    <td>{{ $val->category_name }}</td>
                    <td>{{ $val->warranty_title }}</td>
                    <td>{{ $val->warranty_year }} Year</td>
                    <td>
                      <button data-id="{{ $val->product_id }}" class="btn {{$val->status == '1' ? 'btn-primary' : 'btn-default'}} btn-status" data-status="{{$val->status}}">{{$val->status == '1' ? 'Published' : 'Draft'}}</button>
                    </td>
                    <td>
                      <button data-id="{{ $val->product_id }}" class="btn {{$val->display == '1' ? 'btn-primary' : 'btn-default'}} btn-display" data-display="{{$val->display}}">{{$val->display == '1' ? 'Display' : 'Hide'}}</button>
                    </td>
                    <td>{{ number_format($val->base_point, 2) }}</td>
                    <td>
                      <center>
                        <a title="Edit" href="{{ url('artmin/product/edit-product/'.$val->product_id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
                        <button title="Delete" class="btn btn-danger btn-xs btn-delete" product_id="{{ $val->product_id }}" product_name="{{ $val->name }}"><i class="fa fa-trash"></i></button>
                      </center>
                    </td>
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


<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>
  $(document).on('click', '.btn-status', function(e) {
    e.preventDefault();

    console.log($(this).attr('data-id'));

    swal({
        title: "Confirmation",
        text: "Are you sure change status this product?",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/product/status-product-process") }}';
          let data = {
            "_token": "{{ csrf_token() }}",
            "product_id": $(this).attr('data-id')
          }
          $.post(url, data, function(ret) {
            // console.log(ret);
            swal('Success', 'Status Data Product Has Been Changed', 'success').then((confirm) => location.reload());
          });
        }
      });
  });

  $(document).on('click', '.btn-display', function(e) {
    e.preventDefault();

    const curDisplay = $(this).attr("data-display");
    const productId = $(this).attr("data-id");

    swal({
        title: "Confirmation",
        text: `Are you sure to ${curDisplay === "1" ? "Hide" : "Display"} this product?`,
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/product/display-product-process") }}';
          let data = {
            "_token": "{{ csrf_token() }}",
            "product_id": productId
          }
          $.post(url, data, function(ret) {
            swal('Success', 'Display Data Product Has Been Changed', 'success').then((confirm) => location.reload());
          });
        }
      });
  });

  $(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();

    swal({
        title: "Confirmation",
        text: "Are you sure to delete this data product?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/product/delete-product-process") }}';
          let data = {
            "_token": "{{ csrf_token() }}",
            "product_id": $(this).attr('product_id')
          }
          $.post(url, data, function(e) {
            swal('Success', 'Data Product Has Been Deleted', 'success').then((confirm) => location.reload());
          });
        }
      });
  });
</script>

@endsection