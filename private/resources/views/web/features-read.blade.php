@extends('web.layouts.app')
@section('title', 'Features Information')

@section('content')



<div class="features-page pt-features">
    <div class="features-card pt-features">
        <div class="container-fluid ">
            <div class="row ">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="section-tittle mb-60 text-center wow fadeInUp" data-wow-duration="2s" data-wow-delay=".2s">
                        <h2><?= $features->title ?></h2>
                    </div>
                    <div class="single-features-card mb-card">
                        <div class="location-img ">
                            <img src="{{ $features->image }}" alt=" ">
                        </div>
                        <div class="location-details ">
                            <p><a href="product_details.html ">{{ $features->content }}</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="single-features-card mb-card">
                        <div class="location-img ">
                            <img src="{{ url('assets/img_features/macbook-juice.jpg')}}" alt=" ">
                        </div>
                        <div class="location-details ">
                            <p><a href="product_details.html ">Established fact that by the<br> readable content</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection