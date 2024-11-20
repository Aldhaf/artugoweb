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
                <div class="col-md-6">
                    <div class="warranty-content">
                        <h1>{{ $content->title }}</h1>
                        <div class="warranty-text">
                            <?= $content->value ?>
                        </div>
                        <a href="{{ url('warranty/registration') }}" class="btn btn-white">Daftarkan produk anda</a>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="warranty-image">
                        <img src="{{ $image->value }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container d-none">
        <div class="row">
            <div class="col-12">
                <div class="warranty-register-check">
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="{{ url('warranty/registration') }}" class="warranty-box">
                                <div style="padding: 65px 0px; display: inline-block;">
                                    <h1>Register Your Product</h1>
                                    <div class="warranty-box-content">
                                        Klik di sini untuk daftarkan produk baru anda.
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <div class="warranty-box warranty-box-2">
                                <form action="{{ url('warranty/check') }}" method="POST">
                                    <h1>Warranty Check</h1>
                                    <p>Periksa informasi garansi anda di sini.</p>
                                    <div class="form-group">
                                        <input type="text" class="form-control text-center form-dark" name="warranty_no" placeholder="Serial Number / Warranty Number">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-white">Check Product Warranty</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
