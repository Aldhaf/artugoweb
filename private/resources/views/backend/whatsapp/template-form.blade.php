@extends('backend.layouts.backend-app')
@section('title', 'Template Message')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Message
      <small>Template</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <div class="col-sm-12">
        <div class="box box-solid">
          <div class="box-body">
            <div class="col-sm-6">
              <form class="fdata">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $template->id }}">
                <div class="row">
                  <div class="col-md-12">
                    <label for="">Description</label>
                    <input type="text" class="form-control" placeholder="Description" value="{{ $template->description }}" name="description">
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <label for="">Content</label>
                    <textarea allow-ascii="true" name="content" rows="10" placeholder="Content" class="form-control">{{ $template->content }}</textarea>
                  </div>
                </div>
                <br>
                <center>
                  <button class="btn btn-primary">Simpan</button>
                  <button class="btn btn-back">Kembali</button>
                </center>
            </div>
            </form>
          </div>
        </div>
      </div>

    </div><!-- /.row -->
  </section><!-- /.content -->
</div>


<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>
  $(document).on('click', '.btn-back', function(e) {
    e.preventDefault();
    location.href = '{{ url("artmin/whatsapp/wa-msg-template") }}';
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
          let url = '{{ url("artmin/whatsapp/wa-msg-template/save_edit") }}';
          let data = $(this).serializeArray();

          $.post(url, data, function(e) {
            swal('Berhasil', 'Data Telah Diperbaharui', 'success');
          });
        }
      });
  });
</script>


@endsection