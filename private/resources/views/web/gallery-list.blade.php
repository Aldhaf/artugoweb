@extends('web.layouts.app')

@section('title', 'Gallery')


@section('content')

<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="article-page-title">
                    Gallery
                </h1>
            </div>
            <?php if (count($gallery) == 0): ?>
                <div class="col-sm-12">
                    <h5>Sorry, there's no gallery data</h5>
                </div>
            <?php endif; ?>
            <?php foreach ($gallery as $row): ?>
                <div class="col-6 col-sm-4">

                    <a href="{{ url('gallery/view/'.$row->slug) }}" class="gallery-items">
                        <div class="gallery-cards">
                            <div class="gallery-img" style="background: url('{{ $row->image }}') no-repeat center center; background-size: cover;">
                            </div>
                        </div>
                        <h2 class="gallery-title">
                            {{ $row->title }}
                        </h2>
                    </a>
                </div>
            <?php endforeach; ?>

            <div class="col-12">
                <div class="article-pagination">
                    {{ $gallery->render() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
