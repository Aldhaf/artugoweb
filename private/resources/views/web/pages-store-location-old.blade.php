@extends('web.layouts.app')
@section('title', 'Store Location')

@section('content')



<div class="content content-dark" style="padding-top: 0px;">
  <div style="background: url('{{ url('assets/img/bg-slider.png') }}') no-repeat bottom right; background-size: cover; padding-top: 80px;">
    <div class="container">
      <div class="row">
        <?php foreach ($store_location_regional as $row) : ?>
          <div class="col-sm-12">
            <a class="brochure-items">
              <div class="brochure-title">
                <h3>{{ $row->regional_name }}</h3>
                <?php
                $store_location = DB::table('store_location')->where('regional_id', $row->id)->orderBy('idx','asc')->get();

                if (!empty($store_location)) {
                  foreach ($store_location as $key => $value) {
                    ?>
                    <p>
                      {{ $value->nama_toko }} <br>
                      <span style="font-size:12px">({{ strtoupper($value->alamat_toko) }})</span>
                    </p>
                    <?php
                  }
                }
                ?>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

@endsection