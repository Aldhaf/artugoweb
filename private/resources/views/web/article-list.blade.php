@extends('web.layouts.app')
@if(isset($category->title))
    @section('title', $category->title)
@else
    @section('title', 'Article')
@endif

@section('content')

<div class="content">
    <div class="article-header-img"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="article-page-title">
                    <?php
                    if (!isset($category->title)) {
                        echo "Articles";
                    }
                    else{
                        echo $category->title;
                    }
                    ?>
                </h1>
            </div>
            <?php if (count($article) == 0): ?>
                <div class="col-sm-12">
                    <h5>Sorry, there's no article data</h5>
                </div>
            <?php endif; ?>
            <?php foreach ($article as $row): ?>
                <div class="col-6 col-sm-4">
                    <a href="{{ url('article/read/'.$row->slug) }}" class="article-cards">
                        <div class="article-img" style="background: url('{{ $row->image }}') no-repeat center center; background-size: cover;">
                        </div>
                        <div class="article-meta">
                            <h2>{{ $row->title }}</h2>
                            <div class="article-excerpt">
                                <?php
                                if($row->meta_desc != ''){
                                    echo substr($row->meta_desc, 0, 100) . '...';
                                }
                                else{
                                    echo substr(strip_tags($row->content), 0, 100) . '...';
                                }
                                ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>

            <div class="col-12">
                <div class="article-pagination">
                    {{ $article->render() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
