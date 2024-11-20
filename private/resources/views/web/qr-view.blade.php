@extends('web.layouts.app')
@section('title', 'Warranty')

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
                <div class="col-sm-12 text-center">
                    <h3 style="text-transform:uppercase;"><strong><?= $code ?></strong> </h3>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
