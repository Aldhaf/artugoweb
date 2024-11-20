@extends('web.layouts.app')
@section('title', 'Service')

@section('content')


<style media="screen">
    #warranty-service-section {
        display: none;
    }

    
</style>



<div class="features-page pt-features">
    <div class="features-card pt-features">
        <div class="container-fluid ">
            <div class="row ">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="section-tittle mb-60 text-center wow fadeInUp" data-wow-duration="2s"
                        data-wow-delay=".2s">
                        <h2>Features
                        </h2>
                        <hr class="hr-1 mb-card">
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="single-features-card mb-card">
                        <div class="location-img ">
                            <a href="{{ url('features/category/spirit-series') }}"><img src="{{ url('assets/img_features/Spirit-Series.jpg')}}" alt=" "></a>
                        </div>
                        {{-- <div class="location-details ">
                            <a href="{{ url('features/category/the-whale') }}" class="btn-purple">Lihat Fitur</a>
                        </div> --}}
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="single-features-card mb-card">
                        <div class="location-img ">
                            <a href="{{ url('features/category/the-whale') }}"><img src="{{ url('assets/img_features/THE-WHALE.jpg')}}" alt=" "></a>
                        </div>
                        <div class="location-details ">
                            {{-- <p><a href="#">The Whale</a>
                            </p> --}}
                            {{-- <a href="{{ url('features/category/the-whale') }}" class="btn-purple">Lihat Fitur</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection