@extends('backend.layouts.backend-app')
@section('title', 'Member')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Member
      <small>Data</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12">
        <button class="btn btn-primary btn-toggle-filter">Filter/Search Member</button>
      </div>
    </div>
    <br>
    <div class="section-filter">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-body">
              <div class="col-md-12">
                <form action="" method="GET" class="fdata_filter">
                  <div class="row">
                    <div class="col-md-4">
                      <label for="">Fullname</label>
                      <input type="text" placeholder="Fullname" class="form-control" name="name" value="{{ (isset($_GET['name']) ? $_GET['name'] : null) }}">
                    </div>
                    <div class="col-md-4">
                      <label for="">Phone Number</label>
                      <input type="number" placeholder="Phone Number" class="form-control" name="phone" value="{{ (isset($_GET['phone']) ? $_GET['phone'] : null) }}">
                    </div>
                    <div class="col-md-4">
                      <label for="">Email</label>
                      <input type="email" placeholder="Email" class="form-control" name="email" value="{{ (isset($_GET['email']) ? $_GET['email'] : null) }}">
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                      <button class="btn btn-primary btn-submit-filter">Submit Filter</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="box box-solid">
          <div class="box-body">
            <!-- <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table"> -->
            <div class="col-sm-12 table-responsive">
              <table class="table data-table table-sm table-bordered table-hover member-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th><center>Status</center></th>
                    <th>Registered At</th>
                    <th>Balance Point</th>
                    <th>Action</th>
                  </tr>
                </thead>
<?php /*
                <tbody>
                  @foreach($member as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->name }}</td>
                    <td>{{ $val->phone }}</td>
                    <td>{{ $val->email }}</td>
                    <td>
                      <center>
                        <button data-id="{{ $val->id }}" class="btn {{$val->status == '1' ? 'btn-primary' : ''}} btn-status" data-status="{{$val->status}}">{{$val->status == '1' ? 'Active' : 'Not Active'}}</button>
                      </center>
                    </td>
                    <td>{{ $val->created_at }}</td>
                    <td>
                      <center>
                        <a href="{{ url('artmin/member/'.$val->id) }}">
                          <button class="btn btn-sm btn-primary">Edit</button>
                        </a>
                        <button class="btn btn-sm btn-primary btn-reset" id="{{ $val->id }}">Reset Password</button>
                        <button class="btn btn-sm btn-primary btn-marge" id="{{ $val->id }}">Merge Account</button>
                      </center>
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
          <!-- Temporary Password :
          <h3 class="passwd"></h3> -->
          <div class="row">
            <div class="col-md-12">
              <label for="">Password Baru</label>
              <input type="text" class="form-control" placeholder="Password Baru" name="passwordbaru">
            </div>
          </div>
        </center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-reset-process" id="">Reset Password</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal m_marge_account" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Merge Account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="f_marge">
          {{ csrf_field() }}
          <input type="hidden" name="merge_account_id_old">
          <div class="row">
            <div class="col-md-12">
              <label for="">Gabungkan Data Account Dengan</label>
              <select name="merge_account_id" id="merge_account_id" class="form-control select2">
                <?php /*
                <option value="-">[Pilih]</option>
                @foreach($member as $key => $val)
                <option value="{{ $val->id }}">{{ $val->name .' | ' . $val->phone . ' | '. $val->email }}</option>
                @endforeach
                */ ?>
              </select>
            </div>
          </div>
          <br>
          <p><i>*Data akun akan dihapus dan data registrasi warranty akan otomatis digabungkan ke akun yang dipilih</i></p>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-submit-marge">Marge</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="{{url('assets/backend/plugins/daterangepicker/moment.min.js')}}"></script>
<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{url('assets/backend/plugins/daterangepicker/daterangepicker.min.js?v=3.1')}}"></script>

<script>
  // $(document).on('click','.btn-submit-filter',function(e){
  //   e.preventDefault();
  //   $('.fdata_filter').submit();
  // });

  // $(document).on('submit','.fdata_filter',function(e){
  //   e.preventDefault();

  //   swal({
  //       title: "Konfirmasi",
  //       text: "",
  //       icon: "warning",
  //       buttons: true,
  //       dangerMode: true,
  //     })
  //     .then((willDelete) => {
  //       if (willDelete) {
  //         let url = '{{ url("artmin/member/filter") }}';
  //         let data = $(this).serializeArray();
  //         $.post(url, data, function(retDat) {

  //         });
  //       }
  //     });
  // });

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

	$(document).ready( function() {
		$('.data-table').DataTable().clear().destroy();
		$('.member-table').DataTable({
			paging: true,
			searching: true,
			processing: true,
			serverSide: true,

			retrieve: true,
			responsive: false,
			destroy: true,

			ajax: '{!! route('artmin.member.list') !!}', // memanggil route yang menampilkan data json
			columns: [
				{
					data: "id",
					name: "id",
					// orderable: false,
					// searchable: false,
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{
					data: 'name',
					name: 'name',
					searchable: true
				},
        {
					data: 'phone',
					name: 'phone',
					searchable: true
				},
				{
					data: 'email',
					name: 'email',
					searchable: true
				},
				{
					data: 'address',
					name: 'address',
					searchable: true
				},
				{
					data: 'status',
					name: 'status',
					searchable: false,
					render : function (data, type, row) {
						if(data === 0){
							return 'Inactive';
						} else if(data === 1) {
							return 'Active';
						}
					}
				},
				{
					data: 'created_at',
					name: 'created_at',
					render : function (data, type, row) {
						return moment(data).format('DD-MM-YYYY');
					}
				},
        {
					data: 'balance_point_format',
					name: 'balance_point_format',
          searchable: false,
					render : function (data, type, row) {
						return `<div class="prod-info font-weight-bold warranty-prod-item bg-success text-white rounded-3 px-2" style="width: fit-content;">${data||0}</div>`;
					}
				},
				{
					data: null,
					searchable: false,
					render : function (data, type, row) {
						const delBtn = `<button title="Delete" class="btn btn-danger btn-xs btn-delete" member_id="${row.id}"><i class="fa fa-trash"></i></button>`;
						return `
							<center>
							  <a href="{{ url('artmin/member/${row.id}') }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
                <button class="btn btn-sm btn-primary btn-reset" id="${row.id}">Reset Password</button>
                <button class="btn btn-sm btn-primary btn-marge" id="${row.id}">Merge Account</button>
							</center>
						`;
					}
				}
			],
			order: [[0, 'desc' ]]
		});

    setTimeout(() => {
			initSelectMember();
		}, 1000);

	});

  function initSelectMember () {
        if (!$("#merge_account_id").hasClass("select2")) {
            $("#merge_account_id").addClass("select2");
        }
        $("#merge_account_id").select2({
            placeholder: "Search Account Member...",
            ajax: {
                url: '{{ url("artmin/member-json") }}',
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: `${item.name} | ${item.phone} | ${item.email}`
                            }
                        })
                    };
                },
            },
            escapeMarkup: function(m) {
                return m;
            },
            language: {
                searching: function() {
                    return "Ketik untuk mencari...";
                }
            },
            minimumInputLength: 2,
            cache: true,
        });
    }

</script>


@endsection