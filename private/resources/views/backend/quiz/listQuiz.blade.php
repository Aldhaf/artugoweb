@extends('backend.layouts.backend-app')
@section('title', 'Quiz Question')
@section('content')

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Module Quiz
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
              <div class="row">
                <div class="col-md-6">
                  <button class="btn btn-primary btn-add"><i class="fa fa-plus"></i> Add Quiz</button>
                </div>
                <div class="col-md-6 pull-right">
                  <!-- <a href="{{ url('artmin/quiz/settings') }}" class="btn btn-primary pull-right"><i class="fa fa-gear"></i> Settings</a> -->
                </div>
              </div>
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table-ns">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Participants</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($quiz as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->name }}</td>
                    <td>{{ $val->jumlah_peserta }}</td>
                    <!-- <td>{{ ($val->status == '1' ? 'Active' : 'Non-Active') }}</td> -->
                    <td>
                      <center>
                        @if($val->status == '1')
                        <button data-id="{{ $val->id }}" class="btn btn-status btn-sm btn-success">Active</button>
                        @else
                        <button data-id="{{ $val->id }}" class="btn btn-status btn-sm btn-default">Non-Active</button>
                        @endif
                      </center>
                    </td>
                    <td>
                      <a href="{{ url('artmin/quiz/question/'.$val->id) }}">
                        <button class="btn btn-sm btn-primary">Question</button>
                      </a>
                      <a href="{{ url('artmin/quiz/result/'.$val->id) }}">
                        <button class="btn btn-sm btn-success">Result</button>
                      </a>
                      <button class="btn btn-sm btn-default">Edit</button>
                      <button class="btn btn-sm btn-danger">Delete</button>
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

<div class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Module Quiz</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="fdata">
          {{ csrf_field() }}

          <div class="row">
            <div class="col-md-12">
              <label for="">Subject</label>
              <input type="text" class="form-control" placeholder="Subject" name="subject">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6">
              <label for="">Score Point Benar</label>
              <input type="number" class="form-control" placeholder="Score Point Benar" name="score_true">
            </div>
            <div class="col-md-6">
              <label for="">Score Point Salah</label>
              <input type="number" class="form-control" placeholder="Score Point Salah" name="score_false">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label for="">Time Limit</label>
              <input type="number" class="form-control" placeholder="Time Limit" name="time_limit">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-submit">Submit Data</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>
  $(document).on('click', '.btn-add', function(e) {
    e.preventDefault();

    $('.modal').modal('show');
  });

  $(document).on('click', '.btn-submit', function(e) {
    e.preventDefault();

    $('.fdata').submit();
  });

  $(document).on('submit', '.fdata', function(e) {
    e.preventDefault();

    swal({
        title: "Submit Data",
        text: "Are you sure to submit this data?",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/quiz/module_quiz/submit") }}';
          let data = $(this).serializeArray();

          $.post(url, data, function(e) {
            swal('Success', 'Data has been saved', 'success').then((confirm) => location.reload());
          });
        }
      });
  });

  $(document).on('click', '.btn-status', function(e) {
    e.preventDefault();

    swal({
        title: "Confirmation",
        text: "Are you sure to change status this quiz?",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/quiz/module_quiz/status") }}';
          let data = {
            "_token": "{{ csrf_token() }}",
            "id": $(this).attr('data-id')
          };

          $.post(url, data, function(e) {
            swal('Success', 'Data has been saved', 'success').then((confirm) => location.reload());
          });
        }
      });


  });
</script>


@endsection