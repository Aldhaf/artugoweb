@extends('web.layouts.app')
@section('title', $product->product_name_odoo)

@section('content')

<link rel="stylesheet" href="{{ url('assets/css/pages/detail_product.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">


<div class="content">
    <div class="product-page">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="product-image-container">
                        @if($product->product_image)
                        <img src="{{ $product->product_image }}" alt="{{$product->product_name}}">
                        @endif
                        @foreach($product_images as $val)
                        <img src="{{ (str_contains($val->file_url, 'http') ? $val->file_url : url($val->file_url)) ?? '' }}" alt="{{$val->description}}" >
                        @endforeach
                    </div>
                    <!-- <div class="product-gallery">
                        <a href="javascript:void(0)"><img src="{{ $product->product_image??'' }}"></a>
                    </div> -->
                    <div class="category-feature m-0 p-0 mt-2">
                        <?php $index = 0; ?>
                        @foreach($product_features as $val)
                        <div class="category-feature-item <?php echo $index > 0 ? 'border-left-img' : '' ?>">
                            <!-- <div class="product-image" style="margin:10px"> -->
                                <img class="images" src="{{ url('/') .'/'. $val->img }}">
                            <!-- </div> -->
                        </div>
                        <?php
                            $index++;
                            if ($index === 1 || $index < count($product_features)) {
                        ?>
                        <!-- <div class="divider"></div> -->
                        <?php } ?>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product-summary">
                        <h1 class="product-name">
                            <!-- ARTUGO <br> -->
                            <?= str_replace("ARTUGO ", "", $product->product_name_odoo) ?>
                        </h1>
                        <div class="product-description">
                            <h3><b>Specification</b></h3>
                            <?= $product->product_desc ?>
                            <br>
                            <div class="px-3 d-flex gap-2">
                                <a href="{{ url('brochure') }}" class="btn col-6">Download Brochure</a>
                                <a href="{{ url('store-location') }}" class="btn col-6">Store Location</a>
                            </div>
                        </div>
                        <!-- <img src="{{ url('assets/img/arrow-pattern-product.png')}}" height="60px" style="margin-top: 50px;"> -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                </div>
            </div>
        </div>
    </div>
    <!-- <div class="product-details-section">
        @if(!empty($product_category_feature))
        <div class="home-product" style="padding: 100px 0px;">
            <div class="content-inner">
                <div class="col-md-12">
                    <center>
                        <h1>Dapatkan Fitur Terbaik Untuk Anda</h1> -->
                        <!-- <h5>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta veritatis quidem, praesentium tempore illo cum, sequi, possimus rem dolor ratione tempora eos id. Deleniti omnis excepturi quasi quod, officia odio.</h5> -->
                    <!-- </center>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="home-product-slider">
                                @foreach($product_category_feature as $val)
                                <div>
                                    <a href="#" class="product-card">
                                        <div class="product-image" style="margin:10px">
                                            <img src="{{ url('/') .'/'. $val->img }}">
                                        </div>
                                    </a>
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif -->

        <!-- <div class="home-product-slider">
        @foreach($product_content as $val_pc)
        <div class="banner-img slideshow-desktop">
            <a href="{{ url('service') }}">
                <img src="{{ url('uploads/products/banner/' . $val_pc->banner) }}" alt="" style="width: 100%;">
            </a>
        </div>
        <div class="banner-img slideshow-mobile">
            <a href="{{ url('service') }}">
                <img src="{{ url('uploads/products/banner/' . $val_pc->banner_mobile) }}" alt="" style="width: 100%;">
            </a>
        </div>
        <br>
        <center>
            <div style="padding:70px; padding-left:100px; padding-right:100px;">
                <h1 style="font-size:38px"><b>{{ $val_pc->content_title }}</b></h1>
                <br><br>
                <p style="font-size: 20px; color:#000;">
                    {{ $val_pc->content_description }}
                </p>
            </div>
        </center>
        @endforeach
        </div> -->
        <!-- 
        <div class="banner-img">
            <a href="{{ url('service') }}">
                <img src="{{ url('assets/img/Material_KV_5D COOLING_1439 x 469 5.png') }}" alt="" style="width: 100%;">
            </a>
        </div>
        <br>
        <center>
            <div class="container" style="padding:100px">
                <h3><b>5 Sisi Sistem Pendinginan</b></h3>
                <p>
                    Memperkenalkan teknologi Wide Range ART Compressor, salah satu komponen terpenting dari sebuah chest freezer. Seperti halnya fungsi jantung yang ada pada tubuh manusia, kompresor juga menjadi jantung bagi chest freezer, yang berfungsi untuk memompa refrigerant agar dapat menghasilkan dingin yang sempurna. Guna menghadirkan chest freezer berkualitas, ARTUGO mengadopsi kompresor berteknologi Wide Range Voltage, yang mampu bekerja normal dalam kondisi listrik yang tidak stabil, misalnya naik hingga 260 Volt dan turun hingga 160 Volt, seperti yang sering terjadi di Indonesia.
                </p>
            </div>
        </center>

        <div class="banner-img">
            <a href="{{ url('service') }}">
                <img src="{{ url('assets/img/banner-categ.png') }}" alt="" style="width: 100%;">
            </a>
        </div>
        <br>
        <center>
            <div class="container" style="padding:100px">
                <h3><b>5 Sisi Sistem Pendinginan</b></h3>
                <p>
                    Memperkenalkan teknologi Wide Range ART Compressor, salah satu komponen terpenting dari sebuah chest freezer. Seperti halnya fungsi jantung yang ada pada tubuh manusia, kompresor juga menjadi jantung bagi chest freezer, yang berfungsi untuk memompa refrigerant agar dapat menghasilkan dingin yang sempurna. Guna menghadirkan chest freezer berkualitas, ARTUGO mengadopsi kompresor berteknologi Wide Range Voltage, yang mampu bekerja normal dalam kondisi listrik yang tidak stabil, misalnya naik hingga 260 Volt dan turun hingga 160 Volt, seperti yang sering terjadi di Indonesia.
                </p>
            </div>
        </center> -->


    </div>

    <div class="product-details-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-sm-12xx text-center product-detail-label">
                    <!-- <a href="javascript:void(0);" class="prod-detail-tabs active"> -->
                        <!-- <img src="{{ url('assets/img/artugo-arrow-w.png')}}" class="prod-tabs-accent"> -->
                        Product Detail
                    <!-- </a> -->
                </div>
            </div>
            <div class="row" style="margin-top: 30px;">
                <?php foreach ($spec as $row) : ?>
                    <div class="col-lg-4 col-sm-6">
                    <?php $spec_details = DB::table('ms_products_spec')->where('product_id', $product->product_id)->where('spec_group', $row->spec_group)->orderBy('spec_ordering')->get(); ?>

                        <div class="product-spec">
                            <div class="spec-header">
                                <?= $row->spec_group ?>
                            </div>
                            <table>
                                <?php foreach ($spec_details as $det) : ?>
                                    <tr>
                                        <td width="100px;"><b><?= $det->spec_title ?></b></td>
                                        <td><?= $det->spec_value ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                    <?php endforeach; ?>

                <?php
                if (count($product_warranty) > 0) {
                ?>
                    <div class="col-sm-4">
                        <div class="product-spec">
                            <div class="spec-header">
                                Warranty
                            </div>
                            <table>
                                <?php foreach ($product_warranty as $warranty) : ?>
                                    <tr>
                                        <td width="100px;"><b><?= $warranty->warranty_title ?></b></td>
                                        <td><?= ($warranty->warranty_value == '0 Year' ? 'Lifetime' : $warranty->warranty_value) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>

            <div class="row" style="margin-top: -80px;" id="vid-content">
                <div id="youtube-section">
                    @foreach($product_videos as $val)
                    <?php
                        if (str_contains($val->mime_type, 'video')) {
                            if ($val->mime_type === 'video/youtube') {
                                $thumbnail = str_replace('youtu.be', 'img.youtube.com/vi', $val->file_url) . '/hqdefault.jpg';
                                $val->file_url = str_replace('youtu.be', 'www.youtube.com/embed', $val->file_url) . '?autoplay=1';
                            } else {
                                $thumbnail = url($val->file_url_thumbnail);
                                $val->file_url = url($val->file_url);
                            }
                        }
                    ?>
                    <div class="slider-card yt-card">
                        <div style="position: relative;">
                            <img src="{{ url('') }}/assets/img/youtube-play.png" video-url="{{$val->file_url}}" class="c-pointer hover-scale play-video" style="padding: 0px; text-align: center; height: 50px; width: 50px; border-radius: 8px; position: fixed; left: calc(50% - 25px); top: calc(50% - 45px);">
                            <img class="card-img-top" src="{{ $thumbnail }}">
                        </div>
                        <div class="card-body text-dark" style="height: 45px;"><h5 class="font-bold">{{$val->description}}</h5></div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="row" style="margin-top: 30px;" id="writereview">
                <div class="col-md-12 pb-5">
                    <div class="row" style="display: flex; justify-content: center;">
                        <a href="javascript:void(0);" class="prod-detail-tabs active" style="width: auto; font-weight: 800; font-size: 22px; border-radius: 15px; margin-right: 0px;">
                            <img src="{{ url('assets/img/artugo-arrow-w.png')}}" class="prod-tabs-accent">
                            Product Review
                        </a>
                        @if(!Session::has('member_id'))
                            <center class="mt-4">You must login first to submit review</center>
                        @endif
                    </div>
                    <div class="row" style="margin-top: 30px;">
                        <div class="col-md-12">
                            <?php
                            $number = 0;
                            foreach ($product_rating as $key => $value) {
                                $number += $value->star;
                            }
                            $rating = (count($product_rating) > 0 ? round($number / count($product_rating), 1) : 0);
                            ?>
                            @if(count($product_rating) > 0)
                            <center>
                                <h1>{{ $rating }}/5</h1>
                                <span>({{ count($product_rating) }}) Review</span>
                            </center>
                            <br>
                            <center>
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
                                    <table style="width:100%">
                                        <?php
                                        function calcPercentageRating($starIndex, $product, $jumlah_rating)
                                        {
                                        $sum = DB::table('rating')->where('productID', $product->product_id)->where('star', $starIndex)->count();
                                        return  '(' . $sum . ') ' . ($sum > 0 ? ($sum / $jumlah_rating) * 100 : 0) . '%';
                                        }
                                        ?>
                                        @for($a=5; $a >= 1; $a--)
                                        <tr>
                                        <td>{{ $a }} Star</td>
                                        <td>
                                            <center>
                                            @for($i=1;$i <= 5; $i++ ) <i style="color:{{ ($i <= $a ? 'gold' : 'gray') }}; cursor:pointer" data-star="{{ $i }}" class="rating-star rating-star-{{ $i }} fa fa-star"></i>
                                                @endfor
                                            </center>
                                        </td>
                                        <td>{{ calcPercentageRating($a, $product, count($product_rating)) }}</td>
                                        </tr>
                                        @endfor
                                    </table>
                                </div>
                            <center>
                            @else
                            <center>
                            There are no reviews for this product yet
                            </center>
                            <br>
                            @endif
                            <center>
                                <a href="" class="btn btn-sm active border shadow-sm mb-4 mr-0 btn-write-review" style="border-radius: 15px;">
                                {{ (!empty($my_product_rating) ? 'Update' : 'Submit') }} Review
                                </a>
                            </center>

                            @if(count($product_rating) > 0)
                            <div id="container-review" class="row d-none" style="margin-bottom: 20px;">
                                @foreach($product_rating as $value)
                                <div class="col-md-12 mt-5 d-flex flex-column align-items-center">
                                    <b>{{ $value->memberName }}</b>
                                    <div>
                                    @for($i=1;$i <= 5; $i++ ) <i style="color:{{ ($i <= $value->star ? 'gold' : 'gray') }}; cursor:pointer" data-star="{{ $i }}" class="rating-star rating-star-{{ $i }} fa fa-star"></i>
                                    @endfor
                                    </div>
                                    <span class="mb-4" style="font-size: 14px">Reviewed on {{ date('M d, Y',strtotime($value->created_at)) }}</span>
                                    <?php echo nl2br($value->review) ?>
                                </div>
                                @endforeach
                            </div>
                            <center>
                                <a href="" class="btn btn-sm active border shadow-sm mr-0 btn-detail-review mt-4" style="background: #FFB803; border-radius: 15px;">
                                    Detail Review
                                </a>
                                <!-- <button class="btn btn-sm" style="width:100%; border-radius:50px">Detail Review</button> -->
                            </center>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    @if(Session::has('member_id'))
                    <form class="fdata">
                        {{ csrf_field() }}
                        <input type="hidden" name="productID" value="{{ $product->product_id }}">
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-12">
                                <div class="modal mwtirereview" style="color:#000; padding-top: 100px;" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"><b>Write Review For This Product</b></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12 d-flex flex-column align-items-center">
                                                        <b>Give a Rating</b>
                                                        <input type="hidden" name="star" value="{{ ($my_product_rating->star ?? '0') }}">
                                                        <div>
                                                            @if(empty($my_product_rating))
                                                            <i style="color:gray; cursor:pointer" data-star="1" class="rating-star rating-star-1 fa fa-star"></i>
                                                            <i style="color:gray; cursor:pointer" data-star="2" class="rating-star rating-star-2 fa fa-star"></i>
                                                            <i style="color:gray; cursor:pointer" data-star="3" class="rating-star rating-star-3 fa fa-star"></i>
                                                            <i style="color:gray; cursor:pointer" data-star="4" class="rating-star rating-star-4 fa fa-star"></i>
                                                            <i style="color:gray; cursor:pointer" data-star="5" class="rating-star rating-star-5 fa fa-star"></i>
                                                            @else
                                                            @for($i=1;$i <= 5; $i++ ) <i style="color:{{ ($i <= $my_product_rating->star ? 'gold' : 'gray') }}; cursor:pointer" data-star="{{ $i }}" class="rating-star rating-star-{{ $i }} fa fa-star"></i>
                                                                @endfor
                                                                @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <!-- <b>Write Review For This Product</b> -->
                                                        <br>
                                                        <textarea name="review" placeholder="Write Review..." cols="30" rows="10" class="form-control">{{ ($my_product_rating->review ?? '') }}</textarea>
                                                    </div>
                                                </div>
                                                <button style="margin-top: 10px" class="btn btn-sm shadow-sm btn-submit-review">{{ (!empty($my_product_rating) ? 'Update' : 'Submit') }} Review</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div class="home-product">
        <div class="content-inner">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-10 offset-md-2">
                                <div class="home-product-content">
                                    <div class="home-product-subtitle">Produk</div>
                                    <div class="home-product-title">Sejenis</div>
                                    <p>
                                        Temukan varian produk lain yang bisa memenuhi kebutuhanmu.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="home-product-slider">
                            <?php foreach ($product_related as $rel) : ?>
                                <div>
                                    <a href="{{ url('products/' . $rel->slug) }}" class="product-card">
                                        <div class="product-image">
                                            <img src="{{ $rel->product_image??'' }}">
                                        </div>
                                        <div class="product-name">
                                            <?= $rel->product_name_odoo ?>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <img src="{{ url('assets/img/arrow-pattern-3.png') }}" class="home-product-arrow"> -->
    </div>


</div>

@push('js')
<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>

    var maxHeight = function(elems){
        return Math.max.apply(null, elems.map(function ()
        {
            return $(this).height();
        }).get());
    }

    function sameHeightProductSejenis() {
        var mh = maxHeight($(".home-product-slider .product-card .product-name"));
        $(".home-product-slider .product-card .product-name").height(mh);
    }

    $('.home-product-slider').slick({
        dots: false,
        infinite: true,
        speed: 300,
        fade: false,
        cssEase: 'linear',
        arrows: true,
        prevArrow: '<button class="slider-prev"><i class="fi-xnllxl-chevron"></i></button>',
        nextArrow: '<button class="slider-next"><i class="fi-xnlrxl-chevron"></i></button>',
        autoplay: false,
        autoplaySpeed: 4000,
        slidesToShow: 4,
        slidesToScroll: 4,
        initialSlide: 1,
        centerMode: false,
        adaptiveHeight: true,
        responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4,
                }
            },
            {
                breakpoint: 1100,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    sameHeightProductSejenis();

    $(window).resize(() => {
        sameHeightProductSejenis();
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
        infinite: false,
        dots: false,
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
                    slidesToScroll: slidesToShowFeature600
                }
            }
        ]
    });

    $(document).on('click', '.rating-star', function(e) {
        e.preventDefault();
        let star = $(this).attr('data-star');
        $('.rating-star').css('color', 'gray');
        for (let i = 1; i <= star; i++) {
            console.log(i);
            $('.rating-star-' + i).css('color', 'gold');
        }
        $('[name="star"]').val(star);
    });

    $(document).on('click', '.play-video', function(e) {
        e.preventDefault();
        let videoUrl = $(this).attr('video-url');
        $(e.target).parent().parent().prepend(`<iframe class="card-img-top" src="${videoUrl}" allow="autoplay" frameborder="0"></iframe>`);
        $(e.target).parent().remove();
    });

    $(document).on('submit', '.fdata', function(e) {
        e.preventDefault();

        swal({
                title: "Confirmation",
                text: "Are you sure to change this status installation information?",
                icon: "info",
                buttons: true
            })
            .then((willDelete) => {
                if (willDelete) {
                    let url = '{{ url("products/review/submit") }}';
                    let data = $(this).serializeArray();

                    $.post(url, data, function(r) {
                        swal('Success', 'Your review has been submited', 'success').then((confirm) => location.reload());
                    });
                }
            });
    });

    $(document).on('click', '.btn-submit-review', function(e) {
        e.preventDefault();
        $('.fdata').submit();
    });

    $(document).on('click', '.btn-write-review', function(e) {
        e.preventDefault();

        $('.mwtirereview').modal('show');

    });

    $(document).on('click', '.btn-detail-review', function(e) {
        e.preventDefault();
        const visible = $('#container-review').hasClass('d-none');
        if (!visible) {
            $('#container-review').addClass('d-none');
        } else {
            $('#container-review').removeClass('d-none');
        }
        e.target.innerHTML = visible ? "Sembunyikan" : "Detail Review";
    });

    $('#youtube-section').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: true,
        centerMode: true,
        arrows: true,
        prevArrow: '<button class="slider-prev text-muted" style="margin-left: -120px;"><i class="fi-xnllxl-chevron"></i></button>',
        nextArrow: '<button class="slider-next text-muted" style="margin-right: -120px;"><i class="fi-xnlrxl-chevron"></i></button>',
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }
        ]
    });

    $('.product-image-container').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false,
        centerMode: true,
        arrows: true,
        prevArrow: '<button class="slider-prev text-muted"><i class="fi-xnllxl-chevron"></i></button>',
        nextArrow: '<button class="slider-next text-muted"><i class="fi-xnlrxl-chevron"></i></button>'
    });

</script>
@endpush

@endsection
