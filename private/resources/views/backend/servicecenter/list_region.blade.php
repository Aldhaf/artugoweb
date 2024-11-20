@extends('backend.layouts.backend-app')
@section('title', 'Service Center')
@section('content')

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Service Center
      <small>Region</small>
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
              <a href="{{ url('artmin/region/add?redirect_to=servicecenter') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Region</a>
              <!-- <a href="{{ url('artmin/region/export-excel') }}"> -->
              <!-- <button style="margin-right: 10px;" class="btn btn-primary pull-right btn-export-excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Data Excel</button> -->
              <!-- </a> -->
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th width="50">#</th>
                    <th>Region</th>
                    <th width="120">Service Center</th>
                    <th width="200"><center>Action</center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  function count_servicecenter($region_id)
                  {
                    return DB::table('ms_service_center')->where('region_id', $region_id)->count();
                  }
                  ?>
                  @foreach($region as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->regional_name }}</td>
                    <td>{{ count_servicecenter($val->id) }}</td>
                    <td align="center">
                      <a href="{{ url('artmin/region/edit/'.$val->id.'?redirect_to=servicecenter') }}">
                        <button class="btn btn-default">Edit</button>
                      </a>
                      <a href="{{ url('artmin/servicecenter/'.$val->id) }}">
                        <button class="btn btn-default">List Service Center</button>
                      </a>
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