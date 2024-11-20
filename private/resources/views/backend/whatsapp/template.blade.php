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
    <div class="row">
      <div class="col-sm-12">
        <div class="box box-solid">
          <div class="box-body">

            <div class="form-group">
              <div class="row">
                <div class="col-md-6">
                <a href="{{url('artmin/whatsapp/wa-msg-template/new')}}" class="btn btn-primary btn-add"><i class="fa fa-plus"></i> New Template</a>
                </div>
                <div class="col-md-6 pull-right">
                </div>
              </div>
            </div>

            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Content</th>
                    <th><center>Action</center></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($template as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->description }}</td>
                    <td>{{ $val->content }}</td>
                    <td>
                      <center>
                        <a href="{{ url('artmin/whatsapp/wa-msg-template/'.$val->id) }}">
                          <button class="btn btn-sm btn-primary">Edit</button>
                        </a>
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

    </div><!-- /.row -->
  </section><!-- /.content -->
</div>

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>
  var initFilter = false;

  if(initFilter){
    $('.section-filter').show();
  }else{
    $('.section-filter').hide();
  }

  $(document).on('click', '.btn-toggle-filter', function(e) {
    e.preventDefault();
    initFil();
  });

  function initFil() {
    if (initFilter) {
      initFilter = false;
      $('.section-filter').hide();
    } else {
      initFilter = true;
      $('.section-filter').show();
    }
  }

  $(document).on('click', '.btn-marge', function(e) {
    e.preventDefault();

    let id = $(this).attr('id');

    $('[name="merge_account_id_old"]').val(id);
    $('.m_marge_account').modal('show');
  });

  $(document).on('submit', '.f_marge', function(e) {
    e.preventDefault();

    swal({
        title: "Konfirmasi",
        text: "Data akun akan dihapus dan data registrasi warranty akan otomatis digabungkan ke akun yang dipilih",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/member/marge") }}';
          let data = $(this).serializeArray();
          $.post(url, data, function(retDat) {
            swal('Berhasil', 'Account Telah Digabungkan', 'success').then((confirm) => location.reload());
          });
        }
      });

  });

  $(document).on('click', '.btn-submit-marge', function(e) {
    e.preventDefault();
    if($('[name="merge_account_id"]').val() == '-'){
      swal('Invalid','Anda Belum Memilih Target Member','warning');
    }else{
      $('.f_marge').submit();
    }
  });


  function makeid(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  }

  $(document).on('click','.btn-status',function(e){
    e.preventDefault();

    const curStatus = $(this).attr("data-status");
    const memberID = $(this).attr("data-id");
    const url = '{{ url("artmin/member/change-status") }}/' + memberID;

    swal({
        title: "Confirmation",
        text: `Are you sure to change status to ${curStatus === "1" ? "Not Active" : "Active"}?`,
        icon: "info",
        buttons: true
      })
      .then((ok) => {
        if (ok) {
          const data = {
            "_token": "{{ csrf_token() }}",
            category_id: memberID,
            active: curStatus === "1" ? "0" : "1"
          };

          $.post(url, data)
          .success((r) => swal('Success', 'Status has been changed.', 'success').then((confirm) => location.reload()))
          .error((e) => swal('Error', 'Failed to update status!.', 'error'));
        }
      });
  });

  $(document).on('click', '.btn-reset', function(e) {
    e.preventDefault();

    let password = makeid(4);
    $('.passwd').text(password);

    let member_id = $(this).attr('id');
    $('.btn-reset-process').attr('id', member_id);
    $('.mreset').modal('show');
  });

  $(document).on('click', '.btn-reset-process', function(e) {
    e.preventDefault();

    swal({
        title: "Confirmation",
        text: "Are you sure to reset password for this account?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/member/reset-password") }}';
          let data = {
            "_token": "{{ csrf_token() }}",
            "member_id": $(this).attr('id'),
            "password": $('[name="passwordbaru"]').val()
          }

          $.post(url, data, function(r) {
            swal('Success', 'Password Has Been Reset', 'success');
          });
        }
      });
  });

  $(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();

    let user_id = $(this).attr('user_id');
    let user_name = $(this).attr('user_name');

    let r = confirm('Are you sure to delete data user ' + user_name);

    if (r) {
      let data = {
        "_token": "{{ csrf_token() }}",
        "user_id": user_id
      };

      let url = '{{ url("artmin/user/delete-user") }}';

      $.post(url, data, function(data) {
        location.reload();
        // console.log(data);
      });

    }

  });
</script>


@endsection