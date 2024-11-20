@extends('backend.layouts.backend-app')
@section('title', 'Taken Problem')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Taken Problem
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
              <a href="{{ url('artmin/problem-taken/add-problem-taken') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Taken Problem</a>
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Initial Category</th>
                    <th>Type</th>
                    <th>Taken</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($problems_taken as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->initial }}</td>
                    <td>{{ ($val->service_type == '1' ? 'Service Request' : 'Installation Request') }}</td>
                    <td>{{ $val->taken }}</td>
                    <td>
                      <a title="Edit" href="{{ url('artmin/problem-taken/edit-problem-taken/'.$val->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
                      <button title="Delete" class="btn btn-danger btn-xs btn-delete" taken_id="{{ $val->id }}" taken_name="{{ $val->taken }}"><i class="fa fa-trash"></i></button>
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



<script>
  $(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();

    let taken_id = $(this).attr('taken_id');
    let taken_name = $(this).attr('taken_name');

    let r = confirm('Are you sure to delete data Taken Problem ' + taken_name);

    if (r) {
      let data = {
        "_token": "{{ csrf_token() }}",
        "taken_id": taken_id
      };
      
      let url = '{{ url("artmin/problem-taken/delete-problem-taken") }}';

      $.post(url,data,function(data){
        location.reload();
        // console.log(data);
      });

    }

  });
</script>


@endsection