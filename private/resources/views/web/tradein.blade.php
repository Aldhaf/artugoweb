@extends('web.layouts.app')
@section('title', 'Trade In')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>



<div class="content content-dark" style="padding-top: 0px;">
  <div style="background: url('{{ url('assets/img/bg-slider.png') }}') no-repeat bottom right; background-size: cover; padding-top: 80px;">
    <div class="container">
      <br>
      <div class="row">
        <div class="col-md-12">
          <h2>TRADE-IN FROM HOME</h2>
          <span>Cash-In your OLD appliances and experience the NEW ultimate product and service.</span>
          <hr>
          <p>
            Punya Water Dispenser atau Water Heater yang tidak terpakai atau rusak? <br> <br>
            Mau menggantinya dengan yang baru tapi masih ragu-ragu? <br> <br>
            Jangan bingung! <br> <br>
            Segera tukarkan Water Dispenser atau Water Heater lamamu sekarang.
            <br><br>
            Dapatkan cashback sebesar Rp126.000-, untuk Water Dispenser dan Rp260.000-, untuk Water Heater, setiap pembelian Water Dispenser ARTUGO AD 60 & 70 series, atau Water Heater ARTUGO HE 10/15/30 D, 10/15/30 E, 10/15/30 F, 15/30 RA, 15/30 RB & HE 20 dari ARTUGO.
            <br><br>
            Caranya:
            <br>
            1. Daftarkan produk melalui website <b><a href="{{ url('/') }}">www.artugo.co.id</a></b> atau scan QR code yang tertera pada produk. <br>
            2. Pilih bagian “Warranty” <br>
            3. Isi data diri <br>
            4. Setelah pengisian data diri berhasil akan muncul halaman baru berupa informasi bahwa garansi produk telah berhasil diproses. Dan nomor garansi produk sudah didapatkan. <br>
            5. Klik “Trade-in” <br>
            6. Isi kembali data diri yang dibutuhkan sebagai persyaratan, kemudian klik “Trade-in” <br>
            7. Tunggu verifikasi dari ARTUGO.
            <br><br>
            Jangan lewatkan juga kesempatan mendapatkan voucher cashback sebesar Rp2.600.000 dan Rp1.260.000 untuk yang mendaftarkan nomor seri produk dengan nomor 260 atau 126 pada Water Dispenser ARTUGO AD 60 & 70 series, atau Water Heater ARTUGO HE 10/15/30 D, 10/15/30 E, 10/15/30 F, 15/30 RA, 15/30 RB & HE 20.
            <br><br>
            Dengan syarat: <br>
            1. Mendaftarkan produk ke website www.artugo.co.id atau scan QR code yang tertera pada produk untuk pendaftaran secara otomatis. <br>
            2. Melakukan pembelanjaan produk ARTUGO apapun sebesar Rp2.600.000-, dan Rp1.260.000-, atau lebih, selama program berlangsung dari tanggal 15 September - 31 Desember 2020.
            <br><br>
            Jadi jangan lupa untuk selalu mendaftarkan produk ARTUGO milikmu agar tetap bisa menikmati beragam kemudahan dan keuntungannya.
            <br><br>
            Semua promo tersebut di atas berlaku dari 15 September - 31 Desember 2020 :)
            <br><br>
            ARTUGO #ArtYourGoal
            <br><br>
            Great Product, Excellent Service #BestPartnerforEveryone

          </p>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection