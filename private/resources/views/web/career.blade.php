@extends('web.layouts.app')
@section('title', 'Career')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
<style>
  .navigation {
    z-index: 1052;
  }
</style>
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.min.js"></script>
<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css">

<div class="content content-dark" style="padding-top: 0px;">
  <div style="background: url('{{ url('assets/img/bg-slider.png') }}') no-repeat bottom right; background-size: cover; padding-top: 90px;">
    <div class="container_dis" style="margin-left: 5%;margin-right:5%">
      <div class="row">
        <div class="col-sm-12 col-xs-12 col-md-12">
          <center>
            <h2>Artugo Vacancy</h2>
            <hr>
            <div class="row mb-4">
              <div class="col-md-6">
                <center>
                  <h4>Purchasing Staff<br/>Tanggerang - Banten</h4>
                  <img style="width: 80%;border:25px solid #25282C;border-radius:10px z-index:" src="{{ url('assets/img/Artugo-Vacancy-Purchasing-Staff.jpg') }}" alt="">
                </center>
              </div>
              <div class="col-md-6">
                <center>
                  <h4>ERP Developer<br/>Tanggerang - Banten</h4>
                  <img style="width: 80%;border:25px solid #25282C;border-radius:10px z-index:" src="{{ url('assets/img/Artugo-Vacancy-Ad-Odoo-Developer-Sept2024.jpg') }}" alt="">
                </center>
              </div>
            </div>
            <div class="row mb-4">
              <div class="col-md-6">
                <center>
                  <h4>Sales Promotion<br/>Jabodetabek</h4>
                  <img style="width: 80%;border:25px solid #25282C;border-radius:10px z-index:" src="{{ url('assets/img/Artugo-Vacancy-Ad-NEW-APC.jpg') }}" alt="">
                </center>
              </div>
              <div class="col-md-6">
                <center>
                  <h4>Sales Dealer Elektronik<br/>Jabodetabekar</h4>
                  <img style="width: 80%;border:25px solid #25282C;border-radius:10px z-index:" src="{{ url('assets/img/Artugo-Vacancy-Ad-Sales-Dealer-JabodeK.jpg') }}" alt="">
                </center>
              </div>
            </div>
          </center>
          <br>
        </div>
      </div>
    </div>
  </div>
</div>