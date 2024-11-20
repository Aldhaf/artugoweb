@extends('web.layouts.app')
@section('title', $content->title)

@section('content')


<div class="content content-dark">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>{{ $content->title }}</h1>
                <div class="page-content">
                    <?= $content->content ?>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
