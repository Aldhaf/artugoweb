@extends('backend.layouts.backend-app')
@section('title', ( $statusAction == 'insert' ? 'Add New' : 'Edit' ).' User')
@section('content')
<div>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			User
			<small>{{ $statusAction == 'insert' ? 'New' : 'Edit' }}</small>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-6">
				<div class="box box-solid">
					<!-- <div class="box-header">
					<h3 class="box-title">Products</h3>
					</div> -->
					<form class="fdata m-0 p-0" action="{{ ($statusAction == 'insert' ? url('artmin/user/add-user-process') : url('artmin/user/edit-user-process')) }}" method="post" enctype="multipart/form-data">
						<div class="box-body">
							@include('backend.layouts.alert')
							<div class="col-md-12">
								{{ csrf_field() }}
								<input type="hidden" name="userid" value="{{ ($user->id ?? null) }}">
								<div class="form-group">
									<label>Roles</label>
									<select class="form-control select2" name="roles">
										<option value="">Select Roles</option>
										@foreach($roles as $val)
										<option value="{{ $val->id }}">{{ $val->title }}</option>
										@endforeach
									</select>
									@if ($errors->has('roles'))
									<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('roles') }}</label>
									@endif
								</div>
								<?php /*
								@if ($user !== null)
								<div class="form-group">
									<label>Store</label>
									<input type="text" class="form-control" value="{{ $user->nama_toko }}" placeholder="Nama Toko" readonly>
								</div>
								@endif
								*/ ?>
								<div class="form-group">
									<label>Name</label>
									<input type="text" class="form-control" name="name" value="{{ ($user->name ?? null) }}" placeholder="Name">
									@if ($errors->has('name'))
									<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('name') }}</label>
									@endif
								</div>
								<div class="form-group">
									<label>Email</label>
									<input type="text" class="form-control" name="email" value="{{ ($user->email ?? null) }}" placeholder="Email">
									@if ($errors->has('email'))
									<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('email') }}</label>
									@endif
								</div>
								<div class="form-group">
									<label>Phone Number</label>
									<input type="number" class="form-control" name="phoneNumber" value="{{ ($user->phoneNumber ?? null) }}" placeholder="Phone Number">
									@if ($errors->has('phoneNumber'))
									<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('phoneNumber') }}</label>
									@endif
								</div>
								<div class="form-group">
									<label>Username</label>
									<input type="text" class="form-control" name="username" value="{{ ($user->username ?? null) }}" placeholder="Username">
									@if ($errors->has('username'))
									<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('username') }}</label>
									@endif
								</div>
								<?php
								if ($statusAction == 'insert') {
								?>
									<div class="form-group">
										<label>Password</label>
										<input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Password">
										@if ($errors->has('password'))
										<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('password') }}</label>
										@endif
									</div>
								<?php
								}
								?>
								<div class="form-group">
									<label for="">Join Date</label>
									<input type="text" autocomplete="off" class="form-control datepicker" placeholder="dd-mm-yyyy" value="{{ isset($user->join_date) ? date('d-m-Y',strtotime($user->join_date)) : '' }}" name="join_date">
								</div>

							</div>
						</div>
						<div class="box-footer">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group text-center">
										<button id="btn-save" type="submit" class="btn btn-primary"><i class="fa <?php echo $statusAction == 'insert' ? 'fa-plus' : 'fa-edit'; ?>"></i> {{( $statusAction == 'insert' ? 'Add New' : 'Edit' )}} Account</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			@if($statusAction != 'insert')
			<div class="col-md-6">
				<div class="box box-solid">
					<div class="box-header d-flex justify-content-between align-items-center">
						<h3 class="box-title" style="flex: 1;">Service Center</h3>
						<button id="btn-add-sc" type="submit" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></button>
					</div>
					<div class="box-body">
						<div id="content_sc_users">
							@foreach($ms_service_center_users as $idx => $ms_sc_users)
							<div class="form-group d-flex justify-content-start gap-2">
								<select class="form-control select2" name="ms_service_center_users[{{$idx}}][sc_id]" disabled>
									<option value="">Select Service Center</option>
									@foreach($ms_service_center as $val)
									<option value="{{ $val->sc_id }}" <?php echo $val->sc_id == $ms_sc_users->sc_id ? 'selected' : ''; ?> >{{ $val->sc_location }}</option>
									@endforeach
								</select>
								<button type="submit" data-id="{{ $ms_sc_users->id }}" class="btn btn-sm btn-danger ml-2 btn-del-sc"><i class="fa fa-trash"></i></button>
							</div>
							@endforeach
						</div>
					</div>
				</div>

				<div class="box box-solid">
					<div class="box-header d-flex justify-content-between align-items-center">
						<h3 class="box-title" style="flex: 1;">Branch Store</h3>
						<button id="btn-add-store" type="submit" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></button>
						<?php /*  {{$user->roles != '8' ? 'hidden' : ''}} */ ?>
					</div>
					<div class="box-body">
						<div id="content_sc_users">
							@foreach($ms_store_location_users as $idx => $ms_store_user)
							<div class="form-group d-flex justify-content-start gap-2">
								<select class="form-control select2" name="ms_store_location_users[{{$idx}}][store_id]" disabled>
									<option value="">Select Branch Store</option>
									@foreach($store_location as $val)
									<option value="{{ $val->id }}" <?php echo $val->id == $ms_store_user->store_id ? 'selected' : ''; ?> >{{ $val->nama_toko }}</option>
									@endforeach
								</select>
								<button type="submit" data-id="{{ $ms_store_user->id }}" class="btn btn-sm btn-danger ml-2 btn-del-store"><i class="fa fa-trash"></i></button>
							</div>
							@endforeach
						</div>
					</div>
				</div>

			</div>
			@endif
		</div><!-- /.row -->
	</section><!-- /.content -->
</div>

@if($statusAction != 'insert')
<div class="modal m-add-sc" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Service Center</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
			<div class="form-group">
				<select class="form-control select2" name="ms_sc_users[sc_id]">
					<option value="">Select Service Center</option>
					@foreach($ms_service_center as $val)
					<option value="{{ $val->sc_id }}">{{ $val->sc_location }}</option>
					@endforeach
				</select>
			</div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
	  	<button id="btn-submit-add-sc" class="btn btn-sm btn-success">SUBMIT</button>
      </div>
    </div>
  </div>
</div>
@endif

@if($statusAction != 'insert')
<div class="modal m-add-store" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Branch Store</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
			<div class="col-md-12">
			<div class="form-group">
				<label>Regional</label>
				<select class="form-control select2" name="ms_store_users[regional_id]">
					<option value="">Select Regional</option>
					@foreach($store_location_regional as $val)
					<option value="{{ $val->id }}">{{ $val->regional_name }}</option>
					@endforeach
				</select>
			</div>
          </div>
          <div class="col-md-12" id="container_store_select">
			<div class="form-group">
				<label>Store</label>
				<select class="form-control" name="ms_store_users[store_id]">
					<?php /*<option value="">Select Branch Store</option>
					@foreach($store_location as $val)
					<option value="{{ $val->id }}">{{ $val->nama_toko }}</option>
					@endforeach*/ ?>
				</select>
			</div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
	  	<button id="btn-submit-add-store" class="btn btn-sm btn-success">SUBMIT</button>
      </div>
    </div>
  </div>
</div>
@endif


<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script type="text/javascript">

	<?php
	if ($statusAction == 'update') {
	?>
		$('[name="roles"]').val('{{ $user->roles }}').trigger('click');
	<?php
	}
	?>

	function initSelectSeachBranchStore (regional_id="") {

		if (regional_id === "") {
			$("#container_store_select").addClass("hidden");
			return;
		}

		$("#container_store_select").removeClass("hidden");

        if (!$("[name='ms_store_users[store_id]']").hasClass("select2")) {
            $("[name='ms_store_users[store_id]']").addClass("select2");
        }
        $("[name='ms_store_users[store_id]']").select2({
            placeholder: "Search Branch Store...",
            ajax: {
                url: '{{url("/artmin/storelocation-json")}}' + (regional_id ? `?regional_id=${regional_id}` : ""),
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.nama_toko,
                                id: item.id
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
                    return "Ketik nama toko...";
                }
            },
			cache: false,
            // minimumInputLength: 3,
            // templateResult: formatResult,
            // templateSelection: formatSelection,
        });
    }

	$(document).on("click", ".btn-del-store", function (e) {

		const storeUserId = $(e.target).closest("button").attr("data-id");
		if (!storeUserId) {
			return;
		}

		let url = `{{url('artmin/user/del-user-store-process/${storeUserId}')}}`;

		let data = {
			_token: $("[name='_token']").val()
		};

		swal({
			title: "Confirmation",
			text: "Are you sure to delete branch store?",
			icon: "info",
			buttons: true,
		}).then((confirm) => {
			if (confirm) {
				$.post(url, data, function(data) {
					swal('Success', 'Data Has Been Deleted', 'success').then((confirm) => location.reload());
					// console.log(data);
				});
			}
		});

	});

	$(document).on("click", ".btn-del-sc", function (e) {

		const scUserId = $(e.target).closest("button").attr("data-id");
		if (!scUserId) {
			return;
		}

		let url = `{{url('artmin/user/del-user-sc-process/${scUserId}')}}`;

		let data = {
			_token: $("[name='_token']").val()
		};

		swal({
			title: "Confirmation",
			text: "Are you sure to delete service center?",
			icon: "info",
			buttons: true,
		}).then((confirm) => {
			if (confirm) {
				$.post(url, data, function(data) {
					swal('Success', 'Data Has Been Deleted', 'success').then((confirm) => location.reload());
					// console.log(data);
				});
			}
		});

	});

	$(document).on("ready", function () {
	
		setTimeout(() => {
			initSelectSeachBranchStore();
		}, 1000);

		$("#btn-add-sc").on("click", function () {
			$(".m-add-sc").modal("show");
		});

		$("#btn-add-store").on("click", function () {
			$(".m-add-store").modal("show");
			$("#container_store_select").addClass("hidden");
		});

		// $('[name="roles"]').on("change", function () {
		// 	$("#btn-add-store").addClass("hidden");
		// 	if (this.selectedOptions[0].value === "8") {
		// 		$("#btn-add-store").removeClass("hidden");
		// 	}
		// });

		$('[name="ms_store_users[regional_id]"]').on("change", function () {
			initSelectSeachBranchStore(this.selectedOptions[0].value);
		});

		$("#btn-submit-add-sc").on("click", function () {

			let scId = $("[name='ms_sc_users[sc_id]']").val();
			if (scId === "") {
				swal({
					title: "Informasi",
					text: "Mohon Pilih Service Center?",
					icon: "info"
				});
				return;
			}

			let url = "{{url('artmin/user/add-user-sc-process')}}";
			let data = {
				_token: $("[name='_token']").val(),
				userid: $("[name='userid']").val(),
				sc_id: scId
			};

			swal({
					title: "Confirmation",
					text: "Are you sure to add service center?",
					icon: "info",
					buttons: true,
				})
				.then((confirm) => {
					if (confirm) {
						$.post(url, data, function(data) {
							swal('Success', 'Data Has Been Saved', 'success').then((confirm) => location.reload());
							// console.log(data);
						});
					}
				});
			
		});

		$("#btn-submit-add-store").on("click", function () {
			let storeId = $("[name='ms_store_users[store_id]']").val();
			if (storeId === "") {
				swal({
					title: "Informasi",
					text: "Mohon Pilih Branch Store?",
					icon: "info"
				});
				return;
			}

			let url = "{{url('artmin/user/add-user-store-process')}}";
			let data = {
				_token: $("[name='_token']").val(),
				userid: $("[name='userid']").val(),
				store_id: storeId
			};

			swal({
					title: "Confirmation",
					text: "Are you sure to add branch store?",
					icon: "info",
					buttons: true,
				})
				.then((confirm) => {
					if (confirm) {
						$.post(url, data, function(data) {
							swal('Success', 'Data Has Been Saved', 'success').then((confirm) => location.reload());
							// console.log(data);
						});
					}
				});
			
		});

	});

	$(document).on('submit', '.fdata', function(e) {
		e.preventDefault();
		let btn = $(this).find("button[type=submit]:focus" );
		if (btn.length === 0) {
			return;
		}

		btn = $(btn[0]);
		let btnId = btn.attr("id");

		if (btnId === "btn-save") {
			$("#StartDate").val("");

			let url = $(this).attr('action');
			let data = $(this).serializeArray();

			swal({
					title: "Confirmation",
					text: "Are you sure to submit this data?",
					icon: "info",
					buttons: true,
				})
				.then((willDelete) => {
					if (willDelete) {
						$.post(url, data, function(resData) {
							swal('Success', 'Data Has Been Saved', 'success').then(() => {
								if (resData) {
									resData = JSON.parse(resData);
									location.href = "{{url('/artmin/user/edit-user/')}}" + "/" + resData.data_id;
								} else {
									location.reload();
								}
							});
						});
					}
				});
		} else if (btnId === "btn-add-sc") {

			// let sc_users_length = $("#content_sc_users").children().length;
			// let select = $("[name='ms_sc_users[0][sc_id]']").clone();
			// // select.attr("name", `ms_sc_users[${sc_users_length}][sc_id]`);
			// // select.select2("destroy");
			// // select.select2();

			// let data = select.select2("data");
			// console.log("ZZZZ", data);

			// const div = $(document.createElement("div"));
			// div.addClass("form-group");
			// // div.append(select);
			// div.html(`
			// 	<select class="form-control select2" name="ms_service_center_users[${sc_users_length}][sc_id]">
			// 	</select>
			// `);
			// div.appendTo("#content_sc_users");
		}

	});
</script>

@endsection