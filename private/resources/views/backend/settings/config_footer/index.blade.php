@extends('backend.layouts.backend-app')
@section('title', 'Setting Footer')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Setting Footer
      <small>Data</small>
    </h1>
  </section>
  <section class="content">
    <div class="row">
      @foreach($subject as $value_subject)
      <?php
      $data_content = DB::table('footer_content')->where('footerSubjectID', $value_subject->id)->get();
      ?>
      <div class="col-sm-12">
        <div class="box box-solid">
          <div class="box-header">
            <h3 class="box-title">{{ $value_subject->name }}</h3>
          </div>
          <div class="box-body">
            <div class="form-group">
              <button class="btn btn-primary btn-add-content" subject-id="{{ $value_subject->id }}" subject-name="{{ $value_subject->name }}"><i class="fa fa-plus"></i> Add Content</button>
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Link</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($data_content as $val)
                  <tr>
                    <td>{{ $val->name }}</td>
                    <td>{{ $val->href }}</td>
                    <td>
                      <center>
                        <button class="btn btn-sm btn-status btn-primary" data-id="{{ $val->id }}">{{ ($val->status == '1' ? 'Active' : 'Non-Active') }}</button>
                      </center>
                    </td>
                    <td>
                      <center>
                        <button class="btn btn-sm btn-edit btn-primary" data="{{ json_encode($val) }}" subject-id="{{ $value_subject->id }}" subject-name="{{ $value_subject->name }}">Edit</button>
                        <button class="btn btn-sm btn-delete btn-danger" data-id="{{ $val->id }}">Delete</button>
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
      @endforeach

    </div><!-- /.row -->
  </section><!-- /.content -->
</div>


<div class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="fdata">
          {{ csrf_field() }}
          <input type="hidden" name="footerContentID">
          <input type="hidden" name="subjectID">
          <div class="row">
            <div class="col-md-6">
              <label for="">Name</label>
              <input type="text" class="form-control" placeholder="Name" name="name">
            </div>
            <div class="col-md-6">
              <label for="">Link Target</label>
              <input type="text" class="form-control" placeholder="Link Target" name="link">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-save">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script>
  var statusAction;

  $(document).on('click', '.btn-add-content', function(e) {
    e.preventDefault();
    let subjectID = $(this).attr('subject-id');
    let subjectName = $(this).attr('subject-name');
    statusAction = 'insert';
    $('[name="name"]').val('');
    $('[name="link"]').val('');

    $('.modal-title').text(subjectName);
    $('[name="subjectID"]').val(subjectID);

    $('.modal').modal('show');
  });

  $(document).on('click', '.btn-save', function(e) {
    $('.fdata').submit();
  });

  $(document).on('submit', '.fdata', function(e) {
    e.preventDefault();

    swal({
        title: "Konfirmasi",
        text: "Apakah data yang anda masukan telah sesuai?",
        icon: "info",
        buttons: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = (statusAction == 'insert' ? '{{ url("artmin/settings/footer/add") }}' : '{{ url("artmin/settings/footer/update") }}');
          let data = $(this).serializeArray();

          $.post(url, data, function(retData) {
            swal('Berhasil', 'Data Telah Disimpan', 'success').then((confirm) => location.reload());
          })
        }
      });
  });

  $(document).on('click', '.btn-status', function(e) {
    e.preventDefault();
    let id = $(this).attr('data-id');
    swal({
        title: "Confirmation",
        text: "Are you sure to change status this data",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/settings/footer/status") }}';
          let data = {
            "_token": "{{ csrf_token() }}",
            "id": id
          }
          $.post(url, data, function(r) {
            swal('Berhasil', 'Data Telah Disimpan', 'success').then((confirm) => location.reload());
          });
        }
      });
  });

  $(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();
    let id = $(this).attr('data-id');
    swal({
        title: "Confirmation",
        text: "Are you sure to delete this data",
        icon: "info",
        buttons: true,
        danger: true
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/settings/footer/delete") }}';
          let data = {
            "_token": "{{ csrf_token() }}",
            "id": id
          }
          $.post(url, data, function(r) {
            swal('Berhasil', 'Data Telah Dihapus', 'success').then((confirm) => location.reload());
          });
        }
      });
  });

  $(document).on('click', '.btn-edit', function(e) {
    e.preventDefault();

    let subjectID = $(this).attr('subject-id');
    let subjectName = $(this).attr('subject-name');
    statusAction = 'update';
    let data = JSON.parse($(this).attr('data'));
    console.log(data);
    $('[name="footerContentID"]').val(data.id);
    $('[name="name"]').val(data.name);
    $('[name="link"]').val(data.href);

    $('.modal-title').text(subjectName);
    $('[name="subjectID"]').val(subjectID);

    $('.modal').modal('show');

  });
</script>


@endsection