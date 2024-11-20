@extends('backend.layouts.backend-app')
@section('title', 'Symptom Problem')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Symptom Problem
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
              <a href="{{ url('artmin/problem-symptom/add-problem-symptom') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Symptom Problem</a>
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Initial Category</th>
                    <th>Type</th>
                    <th>Symptom</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($problems_symptom as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->initial }}</td>
                    <td>{{ ($val->service_type == '1' ? 'Service Request' : 'Installation Request') }}</td>
                    <td>{{ $val->symptom }}</td>
                    <td>
                      <a title="Edit" href="{{ url('artmin/problem-symptom/edit-problem-symptom/'.$val->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
                      <button title="Delete" class="btn btn-danger btn-xs btn-delete" symptom_id="{{ $val->id }}" symptom_name="{{ $val->symptom }}"><i class="fa fa-trash"></i></button>
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

    let symptom_id = $(this).attr('symptom_id');
    let symptom_name = $(this).attr('symptom_name');

    let r = confirm('Are you sure to delete data Symptom Problem ' + symptom_name);

    if (r) {
      let data = {
        "_token": "{{ csrf_token() }}",
        "symptom_id": symptom_id
      };
      
      let url = '{{ url("artmin/problem-symptom/delete-problem-symptom") }}';

      $.post(url,data,function(data){
        location.reload();
        // console.log(data);
      });

    }

  });
</script>


@endsection