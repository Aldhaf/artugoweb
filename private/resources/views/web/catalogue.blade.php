@extends('web.layouts.catalogue')
@if(isset($category->title))
    @section('title', $category->title)
@else
    @section('title', 'Catalogue')
@endif

@section('content')

<div class="navigation">
    <div class="nav-content d-flex h-100">
        <div class="logo-container">
            <a href="/" class="logo">
                <img src="assets/img/artugo-logo-white.png">
            </a>
        </div>
        <!-- <div class="form-control form-dark input-find-container">
            <input class="top-text-find" value="" placeholder="Find"></input> -->
            <button class="btn-find btn-toggle-find btn btn-primary d-none"><i class="icofont-ui-search"></i></button>
        <!-- </div> -->
    </div>
</div>

<div class="container-catalogue">

    <div class="flip-book" id="demoBookExample">

        @foreach($catalogue_list as $key => $val)
        <div class="page {{ $val->type !== 'page' ? 'page-cover page-' . $val->type : '' }}" {{ $val->type !== "page" ? 'data-density=hard' : '' }}>
            <div class="page-content">
                <div class="page-image" style="background-image: url({{$val->image}})"></div>
            </div>
        </div>
        @endforeach

        <!--
        <div class="page page-cover page-cover-top" data-density="hard">
            <div class="page-content">
                <div class="page-image" style="background-image: url('assets/catalogues/CATALOG_ARTUGO_2021:2022.jpg)"></div>
            </div>
        </div>
        <div class="page">
            <div class="page-content">
                <div class="page-image" style="background-image: url('assets/catalogues/CATALOG_ARTUGO_2021:202283.jpg)"></div>
                <div class="middle-fold"></div>
            </div>
        </div>
        <div class="page">
            <div class="page-content">
                <div class="page-image" style="background-image: url('assets/catalogues/CATALOG_ARTUGO_2021:20223.jpg)"></div>
                <div class="middle-fold"></div>
            </div>
        </div>
        <div class="page">
            <div class="page-content">
                <div class="page-image" style="background-image: url('assets/catalogues/CATALOG_ARTUGO_2021:20224.jpg)"></div>
                <div class="middle-fold"></div>
            </div>
        </div>
        <div class="page">
            <div class="page-content">
                <div class="page-image" style="background-image: url('assets/catalogues/CATALOG_ARTUGO_2021:20225.jpg)"></div>
                <div class="middle-fold"></div>
            </div>
        </div>
        <div class="page">
            <div class="page-content">
                <div class="page-image" style="background-image: url('assets/catalogues/CATALOG_ARTUGO_2021:20226.jpg)"></div>
                <div class="middle-fold"></div>
            </div>
        </div>
        <div class="page">
            <div class="page-content">
                <div class="page-image" style="background-image: url('assets/catalogues/CATALOG_ARTUGO_2021:20227.jpg)"></div>
                <div class="middle-fold"></div>
            </div>
        </div>
        <div class="page">
            <div class="page-content">
                <div class="page-image" style="background-image: url('assets/catalogues/CATALOG_ARTUGO_2021:20228.jpg)"></div>
                <div class="middle-fold"></div>
            </div>
        </div>
        <div class="page">
            <div class="page-content">
                <div class="page-image" style="background-image: url('assets/catalogues/CATALOG_ARTUGO_2021:202283.jpg)"></div>
                <div class="middle-fold"></div>
            </div>
        </div>
        <div class="page page-cover page-cover-bottom" data-density="hard">
            <div class="page-content">
                <div class="page-image" style="background-image: url('assets/catalogues/CATALOG_ARTUGO_2021:202284.jpg)"></div>
            </div>
        </div>
        -->
    </div>
</div>

<div class="zoom-container">
    <button class="btn-zoom btn-zoom-in btn btn-primary me-0"><i class="icofont-ui-zoom-in"></i></i></button>
    <input id="input-zoom" type="range" orient="vertical" min="1" max="10" value="0" onchange="showVal(this.value)"/>
    <button class="btn-zoom btn-zoom-out btn btn-primary me-0"><i class="icofont-ui-zoom-out"></i></button>
    <button class="btn-full-screen btn btn-primary mt-5"><i class="icofont-maximize"></i></button>
    <button class="btn-toggle-find-mobile btn btn-primary d-none"><i class="icofont-ui-search"></i></button>
</div>

<div class="find-result-container d-none">
    <div class="find-result-container-header">
        <!-- <button class="btn-close-find-result btn btn-primary"><i class="icofont-close"></i></button> -->
        <div class="form-control form-dark input-find-container">
            <input class="top-text-find" value="" placeholder="Find"></input>
            <button id="btn-find-catalogue" class="btn-find btn btn-primary"><i class="icofont-ui-search"></i></button>
        </div>
    </div>
    <div class="find-results">
        <!-- <div class="find-result-pin me-0"><i class="icofont-rounded-right"></i></div> -->
    </div>
    <div class="find-result-container-footer">
        <div class="find-result-pin me-0"><i class="icofont-rounded-right"></i></div>
    </div>
</div>

<div class="bottom-bar">
    <div class="page-navigator">
        <!-- <button type="button" class="btn-prev btn btn-primary me-0" style="width: 80px;">Prev</button> -->
        <button class="btn-first btn btn-primary me-0"><i class="icofont-rounded-double-left"></i></button>
        <button class="btn-prev btn btn-primary me-0"><i class="icofont-rounded-left"></i></button>
        <div style="background: white; padding: 0; margin: 0; height: 35px; width: 100px; color: black; display: flex; flex-direction: row; justify-content: center; align-items: center; font-weight: bolder;">
            <span class="page-current">1</span>&nbsp;/&nbsp;<span class="page-total">5</span>
        </div>
        <!-- <button type="button" class="btn-next btn btn-primary me-0" style="width: 80px;">Next</button> -->
        <button class="btn-next btn btn-primary me-0"><i class="icofont-rounded-right"></i></button>
        <button class="btn-end btn btn-primary me-0"><i class="icofont-rounded-double-right"></i></button>
    </div>
</div>
<div class="alert alert-danger mt-3 mb-3 d-none" draggable="true">
    State: <i class="page-state">read</i>, orientation: <i class="page-orientation">landscape</i>
</div>

@endsection
