@extends('backend.layouts.backend-app')
@section('title', $article->title)
@section('meta-description', $article->meta_desc)
@section('content')

<style>
.content-wrapper {
    margin-top: 42px;
}
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
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
    @if(($article->pdf ?? "") != "")
    <div class="row">
        <div class="col-md-12">
            <embed id="preview_pdf" style="width:100%; height: <?php echo old('pdf', $article->pdf) == '' ? '0px' : '1020px'; ?>; border: 1px solid rgb(226 232 240/var(--tw-border-opacity));" src="<?= old('pdf', $article->pdf) ?>" type="application/pdf" />
        </div>
    </div>
    @endif
</div>

<script>
  $(document).ready(function() {
    setTimeout(() => $(".sidebar-toggle").click(), 500);
  });
</script>

@endsection