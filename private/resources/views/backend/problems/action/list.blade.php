@extends('backend.layouts.backend-app')
@section('title', 'Action Problem')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Action Problem
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
              <a href="{{ url('artmin/problem-action/add-problem-action') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Action Problem</a>
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Initial Category</th>
                    <th>Type</th>
                    <th>Action</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($problems_action as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->initial }}</td>
                    <td>{{ ($val->service_type == '1' ? 'Service Request' : 'Installation Request') }}</td>
                    <td>{{ $val->action }}</td>
                    <td>
                      <a title="Edit" href="{{ url('artmin/problem-action/edit-problem-action/'.$val->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
                      <button title="Delete" class="btn btn-danger btn-xs btn-delete" action_id="{{ $val->id }}" action_name="{{ $val->action }}"><i class="fa fa-trash"></i></button>
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

    let action_id = $(this).attr('action_id');
    let action_name = $(this).attr('action_name');

    let r = confirm('Are you sure to delete data Action Problem ' + action_name);

    if (r) {
      let data = {
        "_token": "{{ csrf_token() }}",
        "action_id": action_id
      };
      
      let url = '{{ url("artmin/problem-action/delete-problem-action") }}';

      $.post(url,data,function(data){
        location.reload();
        // console.log(data);
      });

    }

  });
</script>


@endsection