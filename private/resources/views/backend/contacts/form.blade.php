@extends('backend.layouts.backend-app')
@section('title', 'Contacts')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Contacts
      <small>{{$statusAction == 'insert' ? 'New' : 'Edit'}}</small>
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
              <form class="fdata" method="post" action="{{url('artmin/contacts')}}">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $contact->id }}">
                <div class="row">
                  <div class="col-md-12">
                    <label for="">Name</label>
                    <input type="text" class="form-control" placeholder="Name" value="{{ $contact->name }}" name="name">
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <label for="">Code</label>
                    <input type="text" class="form-control" placeholder="Code" value="{{ $contact->code }}" name="code">
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <label for="">Phone</label>
                    <input type="text" class="form-control" placeholder="Phone" value="{{ $contact->phone }}" name="phone">
                  </div>
                </div>
                <br>
                <center>
                  <button type="submit" class="btn btn-primary">Simpan</button>
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
    location.href = '{{ url("artmin/contacts") }}';
  });

  // $(document).on('submit', '.fdata', function(e) {
  //   e.preventDefault();
  //   swal({
  //       title: "Konfirmasi",
  //       text: "Apakah data yang anda masukan telah sesuai?",
  //       icon: "info",
  //       buttons: true,
  //     })
  //     .then((confirm) => {
  //       if (confirm) {
  //         let url = '{{ url("artmin/contacts") }}';
  //         let data = $(this).serializeArray();

  //         $.post(url, data)
  //         .success((r) => {
  //           if(r.success) {
  //             swal("Berhasil", r.message, "success").then((confirm) => location.reload());
  //           } else {
  //             swal("Gagal", r.message, "error");
  //           }
  //         }).error((e) => swal('Gagal', e.responseJSON.message, 'error'));

  //         // , function(e) {
  //         //   swal('Berhasil', 'Data Telah Diperbaharui', 'success');
  //         // });
  //       }
  //     });
  // });
</script>


@endsection