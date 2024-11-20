@extends('web.layouts.app')
@section('title', 'Distributor')

@section('content')


<style media="screen">
    #warranty-service-section{
        display: none;
    }
</style>


<div class="content content-dark" style="padding-top: 0px;">
    <div style="background: url('{{ url('assets/img/bg-slider.png') }}') no-repeat bottom right; background-size: cover; padding-top: 80px;">
        <div class="container">
            <div class="row">
                <!-- <div class="col-sm-6">
                    <div class="warranty-image">
                        <img src="{{ url('assets/img/artugo-digital-warranty.png') }}">
                    </div>
                </div> -->
                <div class="col-md-8 offset-md-2">
                    <div class="service-content text-center">
                        <h1>Artugo Distributor</h1>
                        <div class="service-text">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
