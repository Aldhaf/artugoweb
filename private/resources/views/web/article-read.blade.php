@extends('web.layouts.app')
@section('title', $article->title)
@section('meta-description', $article->meta_desc)
@section('meta-tags', $article->meta_tags)


@section('content')

<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <center>
                    <img src="{{ $article->image }}">
                </center>
                <div class="article-read-title">
                    <h1>{{ $article->title }}</h1>
                    <div class="article-read-date">
                        {{ date('d M Y', strtotime($article->created_at)) }}
                    </div>
                </div>
                <div class="article-content">
                    <?= $article->content ?>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection