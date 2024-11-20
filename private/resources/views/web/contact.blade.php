@extends('web.layouts.app')
@section('title', 'Contact')

@section('content')


<style media="screen">
    #warranty-service-section {
        display: none;
    }
</style>


<div class="content content-dark" style="padding-top: 200px;">
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 col-sm-6 offset-sm-3">
                <div class="contact-item">
                    <h2>HEAD OFFICE</h2>
                    Jl. Gatot Subroto Km. 7,8 no. 88 Blok 3-5, Jatake, <br>
                    Kec. Jatiuwung Tangerang - Banten 15136, Indonesia <br>
                    +62 877 8440 1818
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <hr style="border-color: #fff; margin-bottom: 50px;">
                <div class="contact-item">
                    <h2>BRANCH OFFICE</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="contact-item-sm">
                    <h2>BANDUNG</h2>
                    Jl Gedebage No 11 Ruko Kav 4 - 5<br>
                    Babakan Penghulu - Cinambo Kota Bandung 40296<br>
                    Jawa Barat
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="contact-item-sm">
                    <h2>BANJARMASIN</h2>
                    Komplek Green Yakin No. 1<br>
                    Jalan A. Yani KM. 10 RW. 2, Kel. Sungai Lakum, Kec. Kertak Hanyar<br>
                    Kabupaten Banjar, Kalimatan Selatan - 70654
                </div>
            </div>
            <!-- <div class="col-md-3 col-sm-6">
                <div class="contact-item-sm">
                    <h2>PADANG</h2>
                    JL. HOS Cokroaminoto. No. 69<br>
                    Kota Padang, 25119<br>
                    Sumatera Barat
                </div>
            </div> -->
            <div class="col-md-3 col-sm-6">
                <div class="contact-item-sm">
                    <h2>KEDIRI</h2>
                    Jl. Brigjend Pol. IBH Pranoto No.89b<br>
                    Kel. Bangsal, Kec. Pesantren<br>Kota Kediri 64131<br>
                    Jawa Timur
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="contact-item-sm">
                    <h2>SOLO</h2>
                    Jl. Pesanggrahan Langenharjo No.32 <br>
                    Keluarahan/Dusun Langenharjo<br> Kec. Grogol, Kabupaten Sukoharjo<br>
                    Jawa Tengah
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="contact-item-sm">
                    <h2>YOGYAKARTA</h2>
                    Jl. Imogiri Barat KM 4, Randubelang RT 4<br>
                    Bangunharjo, Sewon<br> Kabupaten Bantul 55711<br>
                    Daerah Istimewa Yogyakarta
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="contact-item-sm">
                    <h2>SEMARANG</h2>
                    Jl. Mlatiharjo Raya No.14 <br>
                    Kel. Mlatibaru, Kec. Semarang Timur, Kota Semarang 50122<br>
                    Jawa Tengah
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="contact-item-sm">
                    <h2>MAKASSAR</h2>
                    Jl. Prof. Dr. Ir. Sutami <br>
                    Komp. PT Permata Alam Sulawesi <br>
                    (Pengisian Gas Elpiji)
                    Blok G No. 3 <br>
                    Kel. Parangloe Kec. Tamalanrea, <br>
                    Kota Makassar 90244<br>
                    Sulawesi Selatan
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="contact-item-sm">
                    <h2>PALEMBANG</h2>
                    Jl. Residen Abdul Rozak No. 191<br>
                    RT 010, Kecamatan Kalidoni Palembang<br>
                    Sumatera Selatan
                </div>
            </div>
            <!-- <div class="col-md-3 col-sm-6">
                <div class="contact-item-sm">
                    <h2>PEKANBARU</h2>
                    Komplek Pergudangan Siak 2 Jl. Arengka 2, Kota Pekanbaru 28293<br>
                    Riau
                </div>
            </div> -->
            <div class="col-md-3 col-sm-6">
                <div class="contact-item-sm">
                    <h2>SURABAYA</h2>
                    Jl. Margomulyo Angtropolis 46 G-9, RT.003/RW.001,<br>
                    Kelurahan Tambak Sarioso, Kecamatan Asemrowo,<br>
                    Kota Surabaya 60184 Jawa Timur
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="contact-item-sm">
                    <h2>BALI</h2>
                    Jl. Cokroaminoto No. 138<br>
                    Sedana Merta, Kelurahan Ubung, Kecamatan Denpasar Utara<br>
                    Denpasar 80116, Bali
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="contact-item-sm">
                    <h2>MEDAN</h2>
                    Jl. Karantina No. 82/10 D<br>
                    Kecamatan Medan Timur, Kelurahan Durian<br>
                    Sumatera Utara
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="contact-item-sm">
                    <h2>SAMARINDA</h2>
                    Jl. Ir. Sutami Blok L no 8C<br>
                    Kelurahan Karang asam Ulu<br>
                    Kecamatan Sungai Kunjang<br>
                    Samarinda
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="contact-item-sm">
                    <h2>PURWOKERTO</h2>
                    Jl. Ir. Sutami Blok L no 8C Dusun I Sokaraja Tengah,<br>
                    Sokaraja Tengah, Kec. Sokaraja<br>
                    Kabupaten Banyumas<br>
                    Jawa Tengah 53181
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-card">
                <center>
                    <a href="{{ url('store-location') }}" class="btn btn-white">Store Location</a>
                </center>
            </div>
        </div>
    </div>
</div>

@endsection