@extends('backend.layouts.backend-app')
@section('title', 'Browse Warranty')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Browse Warranty
      <small>New</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <div class="col-md-12">
        <div class="box box-solid">
          <!-- <div class="box-header">
			          <h3 class="box-title">Products</h3>
			        </div> -->
          <div class="box-body">

            <table class="table data-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Warranty No.</th>
                  <th>Product</th>
                  <th>Serial Number</th>
                  <th>Member Name</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($warranty as $key => $val)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $val->warranty_no }}</td>
                  <td>{{ $val->product_name??'' }}</td>
                  <td>{{ $val->serial_no }}</td>
                  <td>{{ $val->reg_name }}</td>
                  <td>{{ $val->reg_phone }}</td>
                  <td>{{ $val->reg_email }}</td>
                  <td>
                    <a href="{{ url('artmin/installation/request/add-service-request/'.$val->warranty_id) }}"><button class="btn btn-primary">Create Installation Request</button></a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>


          </div>
        </div>
      </div>



    </div>



  </section><!-- /.content -->
</div>

<script type="text/javascript">
  $(document).on('submit', '.fdata', function(e) {
    e.preventDefault();

    var r = confirm("Are You Sure To Submit This Data?");
    if (r) {
      let url = $(this).attr('action');
      let data = $(this).serializeArray();

      $.post(url, data, function(retData) {
        location.href = '{{ url("artmin/product/serialnumber") }}';
      })
    }

  });

  function pad_with_zeroes(number, length) {
    var my_string = '' + number;
    while (my_string.length < length) {
      my_string = '0' + my_string;
    }
    return my_string;
  }


  $(document).on('click', '.btn-preview', function(e) {
    e.preventDefault();

    let prefix = $('[name="prefix"]').val();

    let start = $('[name="start"]').val();
    let end = $('[name="end"]').val();

    let postfix = $('[name="postfix"]').val();

    $('.tpreview tbody tr').remove();

    for (let i = start; i <= end; i++) {
      let sn = prefix + pad_with_zeroes(i, 4) + postfix;

      $('.tpreview tbody').append('<tr>' +
        '<td>' + prefix + '</td>' +
        '<td>' + pad_with_zeroes(i, 4) + '</td>' +
        '<td>' + postfix + '</td>' +
        '<td>' + sn + '</td>' +
        '</tr>');
    }

  });
</script>

@endsection