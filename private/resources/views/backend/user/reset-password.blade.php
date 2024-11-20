@extends('backend.layouts.backend-app')
@section('title', 'Reset Password')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Reset Password
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
          <div class="box-body">
            @include('backend.layouts.alert')
            <form class="fdata" action="{{ url('artmin/user/add-user-process') }}" method="post" enctype="multipart/form-data">
              {{ csrf_field() }}
              <input type="hidden" name="users_id" value="{{ Auth::user()->id }}">
              <div class="form-group">
                <label>Current Password</label>
                <input type="password" class="form-control" name="current_password" value="{{ old('current_passsword') }}" placeholder="Current Password">
                @if ($errors->has('current_password'))
                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('current_password') }}</label>
                @endif
              </div>
              <div class="form-group">
                <label>New Password</label>
                <input type="password" class="form-control" name="new_password" value="{{ old('new_passsword') }}" placeholder="New Password">
                @if ($errors->has('new_password'))
                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('new_password') }}</label>
                @endif
              </div>
              <div class="form-group">
                <label>Confirmation New Password</label>
                <input type="password" class="form-control" name="confirmation_new_password" value="{{ old('confirmation_new_passsword') }}" placeholder="Confirmation New Password">
                @if ($errors->has('confirmation_new_password'))
                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('confirmation_new_password') }}</label>
                @endif
              </div>

              <div class="form-group">
                <button class="btn btn-primary"><i class="fa fa-key"></i> Reset Password</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div><!-- /.row -->
  </section><!-- /.content -->
</div>

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script type="text/javascript">
  $(document).on('submit', '.fdata', function(e) {
    e.preventDefault();

    swal({
        title: "Confirmation",
        text: "Are you sure to reset password?",
        icon: "info",
        buttons: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/reset-password-process") }}';
          let data = $(this).serializeArray();

          $.post(url, data, function(ret) {
            let retData = JSON.parse(ret);
            if (retData.status) {
              swal('Success', 'Password Telah Direset', 'success').then((confirm) => location.reload());
            } else {
              swal('Failed', retData.message, 'error');
            }
          });
        }
      });

  });
</script>

@endsection