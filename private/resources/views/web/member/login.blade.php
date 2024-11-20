@extends('web.layouts.app')
@section('title', 'Member Login')

@section('content')



<div class="content content-dark content-small">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 offset-sm-3">
                <div class="member-login-container">
                    <img src="{{ url('assets/img/artugo-arrow-w.png') }}" class="login-box-accent">
                    <div class="login-form">
                        <h2><b>Member</b> Login</h2>
                        @if (Session::has('error'))
                            <label class="control-label input-error" for="inputError"><i class="fa fa-times-circle-o"></i> {{ Session::get('error') }}</label>
                        @endif
                        <form action="" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="text" class="form-control form-dark text-center" name="email" placeholder="Email / Phone Number" value="{{ old('email') }}">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-dark text-center" name="password" placeholder="**********" value="">
                            </div>

                            <div class="form-group">
                                <button class="btn btn-solid btn-white btn-block">Login</button>
                            </div>
                        </form>
                        <div class="form-group">
                            Belum menjadi member? <a href="{{ url('member/register') }}"> <b>Daftar Sekarang</b></a>
                        </div>
                        <div class="form-group">
                            Lupa Password?<a href="{{ url('member/reset-password') }}"> <b>Reset Password</b></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
