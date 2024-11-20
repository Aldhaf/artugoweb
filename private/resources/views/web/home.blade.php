<?php use App\Http\Controllers\HomeController; ?>
@extends('web.layouts.app')
@section('title', 'Home')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ url('assets/css/pages/homepage.css') }}?t=<?php echo time(); ?>">

<div class="content content-dark">
    <div class="slideshow-container" style="background: url('{{ url('assets/img/bg-slider.png') }}') no-repeat bottom right; background-size: cover;">
        <!-- <img src="{{ url('assets/img/arrow-pattern-1.png')}}" class="slide-arrow-pattern"> -->
        <div class="home-slideshow">
            <?php 
                foreach ($slideshow as $slides) : ?>

                <?php
                if ($slides->type == 1) {
                ?>
                    <div>
                        <a href="{{ $slides->url }}" {{substr($slides->url,0,4) == 'http' ? 'target="_blank"' : ''}}>
                            <div class="slideshow-desktop">
                                <img src="{{ $slides->image }}" alt="{{ $slides->title }}">
                            </div>
                            <div class="slideshow-mobile">
                                <img src="{{ $slides->image_mobile }}" alt="{{ $slides->title }}">
                            </div>
                        </a>
                    </div>
                <?php
                } elseif ($slides->type == 2) {
                ?>
                    <div>
                        <div class="slideshow-desktop">
                            <img src="{{ $slides->image }}" alt="{{ $slides->title }}">
                            <div class="slideshow-button-area">
                                <a href="{{ url('article/read/cashin-your-old-appliances-and-experience-the-new-ultimate-product-and-service') }}" class="slideshow-button" style="margin-left:95px;">Pelajari
                                    Lebih Lanjut</a>
                                <a href="{{ url('warranty/registration') }}" class="slideshow-button">Registration</a>
                            </div>
                        </div>
                        <div class="slideshow-mobile">
                            <img src="{{ $slides->image_mobile }}" alt="{{ $slides->title }}">
                            <div class="slideshow-button-area">
                                <a href="{{ url('article/read/cashin-your-old-appliances-and-experience-the-new-ultimate-product-and-service') }}" class="slideshow-button">Pelajari
                                    Lebih Lanjut</a>

                                <a href="{{ url('warranty/registration') }}" class="slideshow-button">Registration</a>
                            </div>
                        </div>
                    </div>
                <?php
                } elseif ($slides->type == 3) {
                ?>
                    <div>
                        <div class="container slideshow-custom">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="slideshow-content">

                                        <div class="slideshow-header">
                                            <h1><small>{{ $slides->title }}</small></h1>
                                        </div>
                                        <?= $slides->content ?>
                                        <a href="{{ $slides->url }}" class="btn btn-white" {{substr($slides->url,0,4) == 'http' ? 'target="_blank"' : ''}}>{{ $slides->btn_text }}</a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <img src="{{ url('assets/img/artugo-digital-warranty.png') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>

            <?php endforeach; ?>
        </div>
    </div>

    <div class="section-product-highlight">
        <div class="content-ph">
            <br>
            <center>
                <h5><b>Products Highlight</b></h5>
            </center>

            <div class="tabs">

                <?php $parent_index = 0; ?>
                @foreach(HomeController::getCategoryParent() as $cat_parent)
                <input type="radio" id="tab{{$parent_index}}" name="tab-control" <?php echo ($parent_index == 0 ? "checked" : ""); ?> >
                <?php $parent_index++; ?>
                @endforeach

                <ul>
                    <?php $parent_index = 0; ?>
                    @foreach(HomeController::getCategoryParent() as $cat_parent)
                    <li title="{{$cat_parent->name}}"><label onclick="reInit({{$parent_index}})" for="tab{{$parent_index}}" role="button"><span>{{$cat_parent->name}}</span></label></li>
                    <?php $parent_index++; ?>
                    @endforeach
                </ul>

                <div class="slider">
                    <div class="indicator"></div>
                </div>
                <div class="content-block">
                    <?php $parent_index = 0; ?>
                    @foreach(HomeController::getCategoryParent() as $cat_parent)
                    <section>
                        <div class="row mx-0">
                            <div class="col-md-12">
                                <div class="home-product-slider tab-{{$parent_index}}">
                                    <?php $sub_index = 0; ?>
                                    @foreach(HomeController::getSubCategory($cat_parent->slug, "highlight_nav") as $val)
                                    <div>
                                        <center>
                                            <!-- <a href="{{ url('products/category/cooling#chest-freezer') }}"> -->
                                            <a href="{{ url($val->href) }}">
                                                <div class="img-prod-highlight-wrapper">
                                                    <img style="width: 80%;" src="{{ url($val->icon) }}" alt="">
                                                </div>
                                                <br>
                                                {{$val->name}}
                                            </a>
                                        </center>
                                    </div>
                                    <?php $sub_index++; ?>
                                    @endforeach
                                </div>
                                <div class="home-product-slider-arrow">
                                    <button class="arrow slider-prev"><i class="fi-xnllxl-chevron"></i></button>
                                    <button class="arrow slider-next"><i class="fi-xnlrxl-chevron"></i></button>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php $parent_index++; ?>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="banner-img">
        <img src="{{ url('assets/uploads/TECHNOLOGY.png') }}" alt="" style="width: 100%;">
    </div> -->

    <div class="banner-img">
        <a href="{{ url('service') }}">
            <img src="{{ url('assets/img/Service-Trifecta-Web_1440-x-422-pxl_new1.webp') }}" alt="Artugo, Digital Waranty, Service Tracking, 7 Days Solution" style="width: 100%;">
        </a>
    </div>


    <!-- <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 home-warranty-bg-1">
                <a class="home-warranty-items" href="{{ url('warranty') }}">
                    <div class="warranty-items-image">
                        <img src="{{ url('assets/img/icons-digital-warranty.png') }}">
                    </div>
                    <div class="warranty-items-content">
                        Kemudahan untuk mengakses informasi garansi produk yang anda miliki dengan sistem digital kami yang terintegrasi.
                    </div>
                </a>
            </div>
            <div class="col-md-4 home-warranty-bg-2">
                <a class="home-warranty-items" href="{{ url('service') }}">
                    <div class="warranty-items-image">
                        <img src="{{ url('assets/img/icons-service-tracking.png') }}">
                    </div>
                    <div class="warranty-items-content">
                        Data Produk setiap Pelanggan termonitor setiap saat mulai dari instalasi hingga status terkini.
                    </div>
                </a>
            </div>
            <div class="col-md-4 home-warranty-bg-1">
                <a class="home-warranty-items" href="{{ url('service') }}">
                    <div class="warranty-items-image">
                        <img src="{{ url('assets/img/icons-7days-solution.png') }}">
                    </div>
                    <div class="warranty-items-content">
                        Keluhan Pelanggan mendapatkan solusi selambat-lambatnya 7 hari kerja.
                    </div>
                </a>
            </div>
        </div>
    </div> -->
</div>

@push('js')
<script>
    $('.home-slideshow').slick({
        dots: false,
        infinite: true,
        speed: 800,
        fade: true,
        cssEase: 'linear',
        arrows: true,
        prevArrow: '<button class="slider-prev"><i class="fi-xnllxl-chevron"></i></button>',
        nextArrow: '<button class="slider-next"><i class="fi-xnlrxl-chevron"></i></button>',
        autoplay: true,
        autoplaySpeed: 6000,
    });

    var currentTab = 0;

    function getSlickProductConfig() {
        return {
            dots: false,
            infinite: true,
            speed: 300,
            fade: false,
            cssEase: 'linear',
            arrows: false,
            // prevArrow: '<button class="slider-prev" style="position: absolute; left: -20px; top: 20%;"><i class="fi-xnllxl-chevron"></i></button>',
            // nextArrow: '<button class="slider-next" style="position: absolute; right: -20px; top: 20%;"><i class="fi-xnlrxl-chevron"></i></button>',
            autoplay: false,
            autoplaySpeed: 6000,
            slidesToShow: 5,
            initialSlide: 0,
            centerMode: false,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 5,
                        slidesToScroll: 5,
                    }
                },
                {
                    breakpoint: 1100,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4
                    }
                },
                {
                    breakpoint: 800,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                }
            ]
        }
    }

    $('.home-product-slider.tab-'+currentTab).slick(getSlickProductConfig());
    $(".home-product-slider-arrow .slider-prev").click(() => $('.home-product-slider').slick("slickPrev"));
    $(".home-product-slider-arrow .slider-next").click(() => $('.home-product-slider').slick("slickNext"));

    function reInit(tabIndex) {
        $('.home-product-slider.tab-'+currentTab).slick('unslick');
        $('.home-product-slider.tab-'+currentTab).addClass('d-none');
        currentTab = tabIndex;
        $('.home-product-slider.tab-'+currentTab).removeClass('d-none');
        $('.home-product-slider.tab-'+currentTab).slick(getSlickProductConfig());
    }
</script>
@endpush