@extends('email.letterhead')
@section('content')
<h1>Reset Password</h1>
<p>Hi {{ $data['name'] }}, Silahkan klik link dibawah ini untuk mengganti password.</p>
<a href="{{$data['reset_url']}}" target="_blank" rel="noreferrer noopener">Klik Disini</a>
@endsection