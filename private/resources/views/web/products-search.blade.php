<?php use App\Http\Middleware\XSS; ?>
@extends('web.layouts.app')
@if(isset($category))
@section('title', $category->name)
@else
@section('title', 'Products')
@endif
@section('content')
<div class="content content-dark">
    <div class="product-banner d-none">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="product-banner-slider">
                        <img src="{{ url('uploads/slides/slides-the-whale.jpg') }}">
                        <img src="{{ url('uploads/slides/slides-matador-series.jpg') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="product-page">
        <div class="product-list">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="product-page-title">
                            Search for: "{{ isset($keywords) ? $keywords : '' }}"
                        </h1>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
                <div class="row">
                    <?php
                    function name_product($name)
                    {
                        $nameRet = str_replace('ARTUGO', '', $name);
                        $arr = explode(' ', $nameRet);
                        $index_num = 0;
                        foreach ($arr as $key => $value) {
                            if (is_numeric($value)) {
                                $index_num = $key;
                            }
                        }
                        $prod_name = '';
                        for ($i = 0; $i < $index_num - 1; $i++) {
                            $prod_name .= ' ' . $arr[$i];
                        }
                        $prod_code = '';
                        for ($i = $index_num - 1; $i < count($arr); $i++) {
                            $prod_code .= ' ' . $arr[$i];
                        }

                        return 'ARTUGO <br>' . $prod_name . '<br>' . $prod_code;
                    }

                    foreach ($products as $prod) : ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="{{ url('products/' . $prod->slug) }}" class="product-card">
                                <?php
                                if (!empty($prod->icon_url)) {
                                ?>
                                    <img src="{{ $prod->icon_url }}" style="position: absolute; height:50px;width:50px; margin-left: 10px;margin-top:10px" data-toggle="tooltip" data-placement="top" title="{{ $prod->icon_title }}">
                                <?php
                                }
                                ?>
                                <div class="product-image">
                                    <img src="<?= $prod->product_image ?>" class="product-image-img">
                                </div>
                                <div class="product-name">
                                    <?= name_product($prod->product_name_odoo) ?>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                    <div class="col-12">
                        <div class="product-pagination">
                            {{ $products->setPath(isset($keywords) ? '?keywords='. $keywords : '')->render() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')


<script>
    $('.product-banner-slider').slick({
        dots: false,
        infinite: true,
        speed: 300,
        fade: false,
        cssEase: 'linear',
        arrows: true,
        prevArrow: '<button class="slider-prev"><i class="fi-xnllxl-chevron"></i></button>',
        nextArrow: '<button class="slider-next"><i class="fi-xnlrxl-chevron"></i></button>',
        autoplay: false,
        autoplaySpeed: 6000,
        slidesToShow: 2,
        responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            },
            {
                breakpoint: 1100,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
</script>
@endpush

@endsection
