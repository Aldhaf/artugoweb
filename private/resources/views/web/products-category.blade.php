@extends('web.layouts.app')
@if(isset($category))
@section('title', $category->name . ( isset($sub_category) ? ' - ' . $sub_category->name : '' ) )
@else
@section('title', 'Products')
@endif
@section('content')
<style>
    .vertical-center {
        margin: auto;
        width: 60%;
        padding: 10px;
    }

    .product-name p {
        margin-top: -10px;
    }

    .wrap-spec {
        font-size: 14px;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }
</style>

<style>
    .div_c {
        height: 500px;
        line-height: 500px;
    }

    .span_c {
        display: inline-block;
        vertical-align: middle;
        line-height: normal;
    }
    .slide-text {
        padding: 34px 96px 34px 96px;
        background: #F7F7F7;
    }
    .slide-title {
        font-weight: 800;
        font-size: 48px;
        line-height: 56px;
        display: flex;
        align-items: center;
        text-align: center;
        letter-spacing: 0.03em;
        color: #000000;
        margin-bottom: 14px;
    }
    .slide-description {
        font-weight: 400;
        font-size: 24px;
        line-height: 28px;
        display: flex;
        align-items: center;
        text-align: center;
        letter-spacing: 0.03em;
        color: #000000;
    }
    .prodcat-slideshow {
        background: #F7F7F7;
    }
    /* .category-feature {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        flex-direction: row;
        background: #F7F7F7;
        padding-top: 34px;
        margin-bottom: 0px !important;
        padding-bottom: 72px;
    }
    .category-feature .slick-dots {
        padding-top: 0px;
        padding-bottom: 30px;
        bottom: 0px;
        background: #F7F7F7;
    }
    .category-feature .category-feature-item {
        width: 293px;
        /* height: 145px; */
        background: #FFFFFF;
        padding-top: 12px;
        padding-bottom: 12px;
    }
    .category-feature .divider {
        width: 120px;
    }
    .category-feature-item.border-left-img>img {
        border-left: 2px solid #000000 !important;
    } */
    .slideshow-mobile .slide-text {
        padding: 14px 26px 14px 26px;
    }
    .slideshow-mobile .slide-text .slide-title {
        font-weight: 400;
        font-size: 16px;
        line-height: 26px;
    }
    .slideshow-mobile .slide-text .slide-description {
        font-weight: 100;
        font-size: 14px;
        line-height: 26px;
    }
    .product-feature-next, .product-feature-prev {
        color: grey;
        padding: 0px !important;
    }

    .slick-dots>li:only-child {
        display: none;
    }

    @media screen and (max-width: 480px) {
        /* .category-feature {
            padding-top: 14px;
        }
        .category-feature-item.border-left-img>img {
            border-left: none !important;
        } */
        .home-product-arrow {
            display: none;
        }
        .prodcat-slideshow .slick-arrow i svg {
            width: 0.7em !important;
            height: 0.7em !important;
        }        
        .prodcat-slideshow .slideshow-item .slide-text {
            padding: 14px 26px 14px 26px;
        }
        .prodcat-slideshow .slideshow-item .slide-text .slide-title {
            font-weight: 400;
            font-size: 16px;
            line-height: 26px;
        }
        .prodcat-slideshow .slideshow-item .slide-text .slide-description {
            font-weight: 100;
            font-size: 14px;
            line-height: 26px;
        }
    }

</style>


<div class="content content-dark">
    <div class="slideshow-container">
        <!-- style="background: url('{{ url('assets/img/bg-slider.png') }}') no-repeat bottom right; background-size: cover;" -->
        <!-- <img src="{{ url('assets/img/arrow-pattern-1.png')}}" class="slide-arrow-pattern"> -->
        <div class="prodcat-slideshow">
            <?php $index = 0; ?>
            @if(count($product_category_banner) > 0)
            @foreach($product_category_banner as $val)
                <div class="slideshow-item">
                    <a href="{{ $val->url }}" {{substr($val->url,0,4) == 'http' ? 'target="_blank"' : ''}}>
                        <img class="w-100" src="{{ url($val->image) }}" />
                        <div class="w-100 d-flex flex-column align-items-center slide-text">
                            <strong class="slide-title">{{$val->title}}</strong>
                            <small class="slide-description">{{$val->description}}</small>
                        </div>
                    </a>
                </div>
                <?php
                    $index++;
                ?>
            @endforeach
            @else
                <div class="slideshow-item">
                    <img class="w-100" src="{{ url('/assets/img/banner-noimage.webp') }}" />
                    <div class="w-100 d-flex flex-column align-items-center slide-text">
                        <strong class="slide-title"></strong>
                        <small class="slide-description"></small>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if(count($product_category_feature) > 0)
    <div class="category-feature">
        <?php $index = 0; ?>
        @foreach($product_category_feature as $val)
            <div class="category-feature-item <?php echo $index > 0 ? 'border-left-img' : '' ?>">
                <!-- <div class="product-image" style="margin:10px"> -->
                    <img class="images" src="{{ url('/') .'/'. $val->img }}">
                <!-- </div> -->
            </div>
            <?php
                $index++;
                if ($index === 1 || $index < count($product_category_feature)) {
            ?>
            <!-- <div class="divider"></div> -->
            <?php } ?>
        @endforeach
    </div>
    @endif
    <div class="product-page">
        <div class="product-list">
            <div style="margin-left: 50px;margin-right: 50px;">
                <div class="row">
                    <?php
                    function name_product($name)
                    {
                        if (strlen($name ?? '') > 0) {
                            $nameRet = str_replace('ARTUGO', '', $name);
                            $arr = explode(' ', $nameRet);
                            $index_num = 0;
                            foreach ($arr as $key => $value) {
                                // if (is_numeric($value)) {
                                $index_num = $key;
                                // }
                            }
                            $prod_name = '';
                            for ($i = 0; $i < $index_num - 1; $i++) {
                                $prod_name .= ' ' . $arr[$i];
                            }
                            $prod_code = '';
                            for ($i = $index_num - 1; $i < count($arr); $i++) {
                                $prod_code .= ' ' . $arr[$i];
                            }

                            return  $prod_name . ' ' . $prod_code;
                        } else {
                            return '';
                        }
                    }

                    foreach ($prod_category as $kc => $cat): ?>
                        <?php
                        $products = DB::table('ms_products')
                            ->select(
                                'ms_products.*',
                                'ms_products_icon.icon as icon_url',
                                'ms_products_icon.title as icon_title',
                                'ms_categories.product_highlight_href',
                                'ms_categories.product_highlight_image',
                                'ms_categories.product_highlight_image_mobile'
                            )
                            ->leftJoin('ms_products_icon', 'ms_products.icon_id', '=', 'ms_products_icon.id')
                            ->join('ms_categories', 'ms_categories.category_id', '=', 'ms_products.category')
                            ->orderBy('ms_products.ordering')
                            // ->where('ms_products.product_image', '!=', '')
                            ->where('ms_categories.category_id', $cat->category_id)
                            ->where('ms_products.status', '1')
                            ->where('ms_products.display', '1')
                            ->get();

                        if (!empty($cat->name) && $cat->name != '' && !empty($products) && count($products) > 0) {
                        ?>
                        <!-- @if(!empty($cat->product_highlight_image)) -->
                        <!-- <div id="{{ $cat->product_highlight_href }}" class="product-category-header-img slideshow-desktop" style="{{ ($kc > 0 ? 'margin-top:50px' : null) }};background-image: url({{ url('assets/uploads/product_highlight/' . $cat->product_highlight_image) }});"> -->
                                <!-- <div class="row"> -->
                                    <!-- <div class="col-md-6"></div> -->
                                    <!-- <div class="col-md-12 div_c" style="text-align: {{ $cat->product_highlight_text_align }};"> -->
                                        <!-- <div class="" style="height:440px; background-color:#f0f;">
                                            <div class="vertical-center" style="text-align:right; display:block;">
                                                <h2 style="padding:8px; text-align:right; display:block; color:{{ $cat->product_highlight_text_color }}">test {{ $cat->product_highlight_text_title }}</h2>
                                                <p>Subtitle</p>
                                            </div>
                                        </div> -->
                                        <!-- <div class="span_c">
                                            <h1 style="padding:8px; display:block; color:{{ $cat->product_highlight_text_color }}">{{ $cat->product_highlight_text_title }}</h1>
                                            <h3 style="padding: 8px;">{{ $cat->product_highlight_text_subtitle }}</p>
                                        </div>
                                    </div>
                                </div> -->
                            <!-- </div> -->

                            <!-- <div id="{{ $cat->product_highlight_href }}" class="product-category-header-img slideshow-mobile" style="{{ ($kc > 0 ? 'margin-top:50px' : null) }};background-image: url({{ url('assets/uploads/product_highlight/' . $cat->product_highlight_image_mobile) }});">
                                <div class="row"> -->
                                    <!-- <div class="col-md-6"></div> -->
                                    <!-- <div class="col-md-12 div_c" style="text-align: {{ $cat->product_highlight_text_align }};"> -->
                                        <!-- <div class="" style="height:440px; background-color:#f0f;">
                                            <div class="vertical-center" style="text-align:right; display:block;">
                                                <h2 style="padding:8px; text-align:right; display:block; color:{{ $cat->product_highlight_text_color }}">test {{ $cat->product_highlight_text_title }}</h2>
                                                <p>Subtitle</p>
                                            </div>
                                        </div> -->
                                        <!-- <div class="span_c">
                                            <h1 style="padding:8px; display:block; color:{{ $cat->product_highlight_text_color }}">{{ $cat->product_highlight_text_title }}</h1>
                                            <h3 style="padding: 8px;">{{ $cat->product_highlight_text_subtitle }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif -->


                            <div id="{{ $cat->product_highlight_href }}" class="col-sm-12 product-category {{ $cat->product_highlight_href }} d-nonexx" style="margin-top: 20px;">
                                <center>
                                    <h4><b>{{ $cat->name }}</b></h4>
                                </center>
                                <hr style="border:2px solid #000; background-color:#000; border-radius:10px">

                            </div>
                            <div class="col-sm-12 product-category {{ $cat->product_highlight_href }} d-nonexx">
                                <div class="product-by-category-list row pb-5">
                                    <?php
                                    $count = 1;
                                    foreach ($products as $key => $prod) {
                                    ?>
                                        <div class="col-6 col-md-4 col-lg-3" style="margin-bottom: 0px;">
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
                                                <center>
                                                    
                                                    <h6 class="text-center" style="font-weight: bold;padding-bottom: 8px;"><?= name_product($prod->product_name_odoo) ?></h6>
                                                    <!-- <hr class="text-center" style="border:1px solid #000; background-color:#000; border-radius:10px;width:80%"> -->
                                                    <!-- <div class="product-name">
                                                        <br> -->
                                                        <?php
                                                    // $ps = explode('<p>', $prod->product_desc);
                                                    // if (!empty($ps)) {
                                                    //     for ($i = 1; $i <= 3; $i++) {
                                                    //         if (!empty($ps[$i])) {
                                                    //             echo "<p class='wrap-spec'>" . $ps[$i];
                                                    //         }
                                                    //     }
                                                    // }
                                                    ?>
                                                <!-- </div> -->
                                            </center>

                                                <!-- <center>
                                                    <button class="btn btn-warning" style="background-color: #FFB803;border:0px; padding:5px;width:35%;">Bandingkan</button>
                                                </center>
                                                <br> -->
                                            </a>
                                        </div>
                                    <?php
                                        if ($prod->br == '1') {
                                            echo '</div><div class="row">';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php
                        }


                        ?>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!-- <img src="{{ url('assets/img/arrow-pattern-3.png') }}" class="home-product-arrow"> -->
    </div>
</div>

@push('js')
<script>

    const fixVerticalArrows = () => {
        const h = ($(".prodcat-slideshow .slick-active").find("img").height()/2) - ($(".prodcat-slideshow .slick-arrow").height()/2);
        $(".slick-arrow").css("top",h+"px");
    }

    $('.prodcat-slideshow').slick({
        dots: false,
        infinite: true,
        speed: 800,
        fade: true,
        cssEase: 'linear',
        adaptiveHeight: true,
        autoplay: true,
        autoplaySpeed: 4000,
        arrows: true,
        prevArrow: '<button class="slider-prev product-banner-prev m-0 p-0"><i class="fi-xnllxl-chevron"></i></button>',
        nextArrow: '<button class="slider-next product-banner-next m-0 p-0"><i class="fi-xnlrxl-chevron"></i></button>'
    }).on('afterChange',(event) => {
        fixVerticalArrows();
    }).trigger('afterChange');

    $(window).resize(() => {
        fixVerticalArrows();
    });

    var slidesToShowFeature = 5;
    var slidesToShowFeature1024 = 4;
    var slidesToShowFeature600 = 3;
    var childElementsFeature = $('.category-feature').children().length;
    if( slidesToShowFeature > (childElementsFeature-1) ) {
        // slidesToShowFeature = (childElementsFeature-1);
        slidesToShowFeature = childElementsFeature;
    }

    if( slidesToShowFeature1024 > (childElementsFeature-1) ) {
        slidesToShowFeature1024 = childElementsFeature;
    }

    if( slidesToShowFeature600 > (childElementsFeature-1) ) {
        slidesToShowFeature600 = childElementsFeature;
    }

    $('.category-feature').slick({
        infinite: true,
        dots: true,
        slidesToShow: slidesToShowFeature,
        slidesToScroll: slidesToShowFeature,
        arrows: true,
        prevArrow: '<button class="slider-prev product-feature-prev"><i class="fi-xnllxl-chevron"></i></button>',
        nextArrow: '<button class="slider-next product-feature-next"><i class="fi-xnlrxl-chevron"></i></button>',
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: slidesToShowFeature1024,
                    slidesToScroll: slidesToShowFeature1024
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: slidesToShowFeature600,
                    slidesToScroll: slidesToShowFeature600
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: slidesToShowFeature600,
                    slidesToScroll: slidesToShowFeature600,
                    arrows: false
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });
</script>
@endpush

@endsection