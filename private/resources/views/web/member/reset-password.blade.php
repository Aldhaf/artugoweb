@extends('web.layouts.app')
@section('title', 'Member Reset Password')
@section('content')

<div class="content content-dark content-small">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 offset-sm-3">
                <div class="member-login-container">
                    <img src="{{ url('assets/img/artugo-arrow-w.png') }}" class="login-box-accent">
                    <div class="login-form">
                        <h2><b>Member</b> Reset Password</h2>

                        @if (Session::has('error'))
                          <label class="control-label input-error" for="inputError"><i class="fa fa-times-circle-o"></i> {{ Session::get('error') }}</label>
                        @endif

                        @if (Session::has('success_reset'))
                          <label class="control-label input-success text-success h2"><i class="fa fa-check-circle"></i> Password berhasil di reset.</label>
                        @endif

                        @if (isset($sent_email))
                          <div>
                            <img style="height: 100px; width: 100px;" src="{{ url('assets/img/email_sent.svg') }}" alt="" />
                            <h2>Kami telah mengirimkan email ke alamat email <b>{{$email}}</b></h2>
                          </div>
                        @endif

                        @if (!isset($error) && !Session::has('success_reset') && !isset($sent_email))
                        <form class="fdata" action="{{ url('member/reset-password') }}" method="post" enctype="multipart/form-data">
                          {{ csrf_field() }}
                          <input type="hidden" name="reset_token" value="{{$reset_token}}">
                          @if ($reset_token !== "")
                            <input type="hidden" name="email" value="{{$email}}">
                          @endif

                          @if ($reset_token === "")
                          <div class="form-group">
                              <input type="text" class="form-control form-dark text-center" name="email" placeholder="Email" value="{{ old('email') }}">
                              @if ($errors->has('email'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-times-circle-o"></i> {{ $errors->first('email') }}</label>
                              @endif
                          </div>
                          @else <!-- ELSE reset_token === "" -->
                          <div class="form-group">
                            <input type="password" class="form-control form-dark text-center" name="new_password" value="{{ old('new_passsword') }}" placeholder="New Password">
                            @if ($errors->has('new_password'))
                              <label class="control-label input-error" for="inputError"><i class="fa fa-times-circle-o"></i> {{ $errors->first('new_password') }}</label>
                            @endif
                          </div>
                          <div class="form-group">
                            <input type="password" class="form-control form-dark text-center" name="new_password_confirmation" placeholder="Confirmation New Password">
                            @if ($errors->has('confirmation_new_passsword'))
                              <label class="control-label input-error" for="inputError"><i class="fa fa-times-circle-o"></i> {{ $errors->first('confirmation_new_passsword') }}</label>
                            @endif
                          </div>
                          @endif <!-- END reset_token === "" -->

                          <div class="form-group">
                            <button class="btn btn-solid btn-white btn-block"><i class="fa fa-key"></i> Reset Password</button>
                          </div>
                        </form>
                        @elseif (isset($error))
                          <label class="control-label input-error" for="inputError"><i class="fa fa-times-circle-o"></i> {{ $error }}</label>
                        @endif
                        <div class="form-group">
                            Kembali ke halaman <a href="{{ url('member/login') }}"> <b>Login</b></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- <script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
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
          let url = '{{ url("member/reset-password") }}';
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
-->

@endsection