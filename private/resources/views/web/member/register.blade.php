@extends('web.layouts.app')
@section('title', 'Member Registration')

@section('content')

<div class="content content-dark content-small">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 offset-sm-3">
                <div class="member-login-container">
                    <img src="{{ url('assets/img/artugo-arrow-w.png') }}" class="login-box-accent">
                    <div class="login-form">
                        <h2><b>Member</b> Register</h2>
                        <div class="form-group">
                            Sudah menjadi member? <a href="{{ url('member/login') }}"> <b>Login di sini</b></a>
                        </div>
                        @if (Session::has('error'))
                            <label class="control-label input-error" for="inputError"><i class="fa fa-times-circle-o"></i> {{ Session::get('error') }}</label>
                        @endif
                        <form action="" method="post" style="text-align: left;">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control form-dark" name="name" placeholder="Nama Anda" value="{{ old('name') }}">
                                @if ($errors->has('name'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-times-circle-o"></i> {{ $errors->first('name') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" class="form-control form-dark" name="phone" placeholder="Nomor Telepon" value="{{ old('phone') }}">
                                @if ($errors->has('phone'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-times-circle-o"></i> {{ $errors->first('phone') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control form-dark" name="email" placeholder="Email" value="{{ old('email') }}">
                                @if ($errors->has('email'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-times-circle-o"></i> {{ $errors->first('email') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control form-dark" name="password" placeholder="**********" value="">
                                @if ($errors->has('password'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-times-circle-o"></i> {{ $errors->first('password') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control form-dark" name="password_confirmation" placeholder="**********" value="">
                                @if ($errors->has('password_confirmation'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-times-circle-o"></i> {{ $errors->first('password_confirmation') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <button class="btn btn-solid btn-white btn-block">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
