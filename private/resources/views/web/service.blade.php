@extends('web.layouts.app')
@section('title', 'Service')

@section('content')


<style media="screen">
    #about-service-section {
        display: none;
    }
</style>
<style>
    @media only screen and (min-width: 768px) {
        .mobile_only {
            display: none;
        }
    }

    @media only screen and (max-width: 768px) {
        .desktop_only {
            display: none;
        }
    }
</style>


<div class="content content-dark" style="padding-top: 0px;">
    <div class="desktop_only" style="background: url('{{ url('assets/img/about-banner.png') }}') no-repeat ; background-size: cover; padding-top: 80px;height:650px">
        <div class="float-right align-items-center" style="align-items: center;background-image:linear-gradient(to right, rgba(255,0,0,0) , rgba(255,255,255,1) 35%);height:570px;padding:50px;text-align: right; color:#000;width:50%">
            <div class="float-right align-items-center" style="align-items: center;width: 65%;">
                <h2><b>ARTUGO SERVICE</b></h2>
                <b>Digital Warranty, merupakan terobosan penaganan service di industry home appliances.</b>
                <br><br>
                <b>Jajaran sales, call center, dan Teknisi ARTUGO akan membantu setiap Pelanggan melakukan pendaftaran online dan memasuki gerbang awal menuju solusi atas setiap permasalahan service produk ARTUGO.</b>
            </div>
        </div>
    </div>

    <div class="mobile_only">
        <img src="{{ url('assets/img/about-banner.png') }}">
        <div style="background-color: #fff;color:#000;text-align: center;">
            <div style="padding:30px">
                <h2><b>ARTUGO SERVICE</b></h2>
                <b>Digital Warranty, merupakan terobosan penaganan service di industry home appliances.</b>
                <br><br>
                <b>Jajaran sales, call center, dan Teknisi ARTUGO akan membantu setiap Pelanggan melakukan pendaftaran online dan memasuki gerbang awal menuju solusi atas setiap permasalahan service produk ARTUGO.</b>
            </div>
            <hr>
        </div>


    </div>

    <div style="background-color: #fff;color:#000;text-align: center;">
        <div style="padding:30px">
            <h4>Digital Warranty</h4>
            <p>Kemudahan untuk mengakses informasi garansi produk yang anda miliki dengan sistem digital kami yang terintegrasi.</p>
        </div>
        <hr>
        <div style="padding:30px">
            <h4>Service Tracking</h4>
            <p>Data Produk setiap Pelanggan termonitor setiap hari mulai dari instalasi hingga status terkini.</p>
        </div>
        <hr>
        <div style="padding:30px">
            <h4>7 Days Solution</h4>
            <p>Keluhan Pelanggan mendapatkan solusi selambat-lambatnya 7 hari kerja.</p>
        </div>
    </div>

</div>

@endsection