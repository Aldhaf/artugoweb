@extends('web.layouts.app')

@section('title', 'Gallery')


@section('content')

<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="article-page-title">
                    <?= $gallery->title ?>
                </h1>
            </div>
            <?php foreach ($gallery_images as $row): ?>
                <div class="col-6 col-sm-4">
                    <a href="{{ $row->images }}" class="gallery-cards" data-fancybox="gallery">
                        <div class="gallery-img" style="background: url('{{ $row->images }}') no-repeat center center; background-size: cover;">
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

@push('js')
<script type="text/javascript">
    $('[data-fancybox="gallery"]').fancybox({

    });
</script>
@endpush

@endsection
