@extends('backend.layouts.backend-app')
@section('title', 'Store Location')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Store Location
      <small>{{ $region->regional_name }} | Data</small>
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
              <a href="{{ url('artmin/storelocation/add/'.$region->id) }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Store Location</a>
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th width="50">#</th>
                    <th>Store</th>
                    <th>Discount Area</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($store as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->nama_toko }}</td>
                    <td>{{ $val->discount_area }}</td>
                    <td>{{ $val->latitude }}</td>
                    <td>{{ $val->longitude }}</td>
                    <td>
                      <a href="{{ url('artmin/storelocation/edit/'.$val->regional_id.'/'.$val->id) }}"><button class="btn btn-primary">Edit</button></a>
                      <a href="{{ url('artmin/storelocation/assignpromotor/'.$val->regional_id.'/'.$val->id) }}"><button class="btn btn-primary">Promotor</button></a>
                      <a href="{{ url('artmin/storelocation/salesreport/'.$val->regional_id.'/'.$val->id) }}"><button class="btn btn-primary">Sales Report</button></a>
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



@endsection