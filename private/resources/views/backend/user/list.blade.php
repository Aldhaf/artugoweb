@extends('backend.layouts.backend-app')
@section('title', 'User')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      User
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
			          <h3 class="box-title">users</h3>
			        </div> -->
          <div class="box-body">
            <div class="form-group">
              <a href="{{ url('artmin/user/add-user') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New User</a>
            </div>
            <!-- <div class="col-sm-12 table-responsive">
              <table class="table table-bordered data-table"> -->
            <div class="col-sm-12 table-responsive">
							<table class="table data-table table-sm table-bordered table-hover user-table" style="width: 100%;">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Username Login</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Role</th>
                    <th><center>Join Date</center></th>
                    <th><center>Status</center></th>
                    <th>Action</th>
                  </tr>
                </thead>
                <?php /*
                <tbody>
                  @foreach($user as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->username }}</td>
                    <td>{{ $val->name }}</td>
                    <td>{{ $val->email }}</td>
                    <td>{{ $val->phoneNumber }}</td>
                    <td>{{ $val->roles_title }}</td>
                    <td width="150"><center>{{ isset($val->join_date) ? date('d-m-Y',strtotime($val->join_date)) : '' }}</center></td>
                    <td>
                      <center>
                        <button data-id="{{ $val->id }}" class="btn {{$val->status == '1' ? 'btn-primary' : ''}} btn-status" data-status="{{$val->status}}">{{$val->status == '1' ? 'Active' : 'Not Active'}}</button>
                      </center>
                    </td>
                    <td>
                      <a title="Edit" href="{{ url('artmin/user/edit-user/'.$val->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
                      <button title="Reset Password" class="btn btn-primary btn-xs btn-reset" id="{{ $val->id }}"><i class="fa fa-key"></i></button>
                      <button title="Delete" class="btn btn-danger btn-xs btn-delete" user_id="{{ $val->id }}" user_name="{{ $val->name }}"><i class="fa fa-trash"></i></button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                */ ?>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /.row -->
  </section><!-- /.content -->
</div>


<div class="modal mreset" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reset Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <center>
          Temporary Password :
          <h3 class="passwd"></h3>
        </center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-reset-process" id="">Reset Password</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>
  function makeid(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  }

  $(document).on('click', '.btn-reset', function(e) {
    e.preventDefault();

    let password = makeid(4);
    $('.passwd').text(password);

    let users_id = $(this).attr('id');
    $('.btn-reset-process').attr('id', users_id);

    $('.mreset').modal('show');
  });

  $(document).on('click','.btn-status',function(e){
    e.preventDefault();

    const curStatus = $(this).attr("data-status");
    const userID = $(this).attr("data-id");
    const url = '{{ url("artmin/user/change-status") }}/' + userID;

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
            id: userID,
            active: curStatus === "1" ? "0" : "1"
          };

          $.post(url, data)
          .success((r) => swal('Success', 'Status has been changed.', 'success').then((confirm) => location.reload()))
          .error((e) => swal('Error', 'Failed to update status!.', 'error'));
        }
      });
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
          let url = '{{ url("artmin/user/reset-password") }}';
          let data = {
            "_token": "{{ csrf_token() }}",
            "users_id": $(this).attr('id'),
            "password": $('.passwd').text()
          }

          console.log(data);
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

    // let r = confirm('Are you sure to delete data user ' + user_name);

    // if (r) {
    //   let data = {
    //     "_token": "{{ csrf_token() }}",
    //     "user_id": user_id
    //   };

    //   let url = '{{ url("artmin/user/delete-user") }}';

    //   $.post(url, data, function(data) {
    //     location.reload();
    //     // console.log(data);
    //   });

    // }

    swal({
        title: "Confirmation",
        text: "Are you sure to delete this account?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/user/delete-user") }}';
          let data = {
            "_token": "{{ csrf_token() }}",
            "user_id": user_id
          };
          // console.log(data);
          $.post(url, data, function(r) {
            swal('Success', 'Account Has Been Reset', 'success').then((confirm) => location.reload());
          });
        }
      });


  });

	$(document).ready( function() {

    $('.data-table').DataTable().clear().destroy();
    $('.user-table').DataTable({
      paging: true,
      info: false,
      searching: true,
      processing: true,
      serverSide: true,
      retrieve: true,
      responsive: false,
      destroy: true,

      ajax: '{!! route('artmin.user.list') !!}' + location.search, // memanggil route yang menampilkan data json
      columns: [
        {
          data: "id",
          name: "id",
          render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {
          data: 'name',
          name: 'name',
          searchable: true,
					render : function (data, type, row) {
						return `<p style="width: 150px;">${data}</p>`;
					}
        },
        {
          data: 'username',
          name: 'username',
          searchable: true
        },
        {
          data: 'email',
          name: 'email',
          searchable: true
        },
        {
          data: 'phoneNumber',
          name: 'phoneNumber',
          searchable: true
        },
        {
          data: 'roles_title',
          name: 'roles_title',
          searchable: true
        },
        {
          data: 'join_date',
          name: 'join_date',
          searchable: false,
					render : function (data, type, row) {
						return row.join_date ? `<p style="width: 100px;">${moment(data).format('DD-MMM-YYYY')}</p>` : "";
					}
        },
        {
          data: 'status',
          name: 'status',
          searchable: false,
          render : function (data, type, row) {
            return `<center><button data-id="${row.id}" class="btn ${row.status === 1 ? 'btn-primary' : ''} btn-status" data-status="${row.status}">${row.status == 1 ? 'Active' : 'Not Active'}</button></center>`;
          }
        },
        {
          data: null,
          searchable: false,
          render : function (data, type, row) {
            return `
              <center style="display: flex; gap: 4px;">
                <a title="Edit" href="{{ url('artmin/user/edit-user')}}/${row.id}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
                <button title="Reset Password" class="btn btn-primary btn-xs btn-reset" id="${row.id}"><i class="fa fa-key"></i></button>
                <button title="Delete" class="btn btn-danger btn-xs btn-delete" user_id="${row.id}" user_name="${row.name}"><i class="fa fa-trash"></i></button>
              </center>
            `;
          }
        }
      ],
      order: [[1, 'asc' ],[6, 'desc' ]],
      drawCallback: function (settings) {
    }
  });

});

</script>


@endsection