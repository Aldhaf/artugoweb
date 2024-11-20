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
              <a href="{{ url('artmin/problem-initial/add-problem-initial') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Symptom Problem</a>
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Initial Name</th>
                    <th>Need Installation</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($problems_initial as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->initial }}</td>
                    <td>
                      <button class="btn btn-primary btn-status" data-id="{{ $val->problem_initial_id }}" data-curstatus="{{ $val->need_installation }}"><span class="statusLabel statusLabel_{{ $val->problem_initial_id }}">{{ ($val->need_installation == '1' ? 'Yes' : 'No') }}</span></button>
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

    swal({
        title: "Confirmation",
        text: "Are you sure to change this status installation information?",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/problem-initial/status") }}';
          let data_id = $(this).attr('data-id');
          let data_curStatus = $(this).attr('data-curstatus');

          let data = {
            "_token": "{{ csrf_token() }}",
            "initial_id": data_id
          };

          console.log(data);

          $.post(url, data, function(r) {
            swal('Success', 'Status data has been changed', 'success').then((confirm) => location.reload());
            // $('.statusLabel_' + data_id).text((data_curStatus == '1' ? 'No' : 'Yes'));
            // $(this).attr('curstatus', (data_curStatus == '1' ? '0' : '1'));
          });
        }
      });
  });

  $(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();

    let initial_id = $(this).attr('initial_id');
    let initial_name = $(this).attr('initial_name');

    let r = confirm('Are you sure to delete data Initial Problem ' + initial_name);

    if (r) {
      let data = {
        "_token": "{{ csrf_token() }}",
        "initial_id": initial_id
      };

      let url = '{{ url("artmin/problem-initial/delete-problem-initial") }}';

      $.post(url, data, function(data) {
        location.reload();
        // console.log(data);
      });

    }

  });
</script>


@endsection