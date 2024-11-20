@extends('web.layouts.app')
@section('title', '404 Not Found')



@section('content')

<div class="content content-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <div class="page-404">
                    <div class="title-404">
                        404
                    </div>
                    <div class="content-404">
                        Sorry, the page you are looking for is not found.
                    </div>
                    <a href="{{ url('') }}" class="btn btn-white">Back to Home Page</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
