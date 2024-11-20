@extends('web.layouts.app')
@section('title', 'Cashback')

@section('content')



<div class="content content-dark content-small">
  <div class="container" style="padding-top: 100px;">
    <div class="row">
      <div class="col-sm-3">
        @include('web.layouts.member-sidebar')
      </div>
      <div class="col-sm-9">
        <h1 class="member-content-title">Cashback</h1>
        <div class="member-content">
          <div class="warranty-item">
            <div class="row">
              <div class="col-sm-5">
                <div class=" warranty-item-img">
                  <img src="{{ $product->product_image??'' }}">
                </div>
              </div>
              <div class="col-sm-7" style="padding-top: 20px;">
                <div class="warranty-prod-summary">
                  <div class="warranty-prod-item warranty-prod-name">
                    {{ $product->product_name??'' }}
                  </div>
                  <div class="warranty-prod-item">
                    <strong>Warranty No:</strong> {{ $warranty->warranty_no }}
                  </div>
                  <div class="warranty-prod-item">
                    <strong>Serial No:</strong> {{ $warranty->serial_no }}
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="service-request-form">
            <form action="" method="post" enctype="multipart/form-data">
              {{ csrf_field() }}
              <input type="hidden" name="warranty_no" value="{{ $warranty->warranty_no }}">
              <input type="hidden" name="warranty_id" value="{{ $warranty->warranty_id }}">

              <div class="row">
                <div class="col-md-12">
                  <label for="">KTP</label>
                  <input type="text" name="ktp" placeholder="KTP" class="form-control form-dark" required="true">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <label for="">Nama Bank</label>
                  <input type="text" name="nama_bank" placeholder="Nama Bank" class="form-control form-dark" required="true">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <label for="">No Rekening</label>
                  <input type="text" name="no_rekening" placeholder="No Rekening" class="form-control form-dark" required="true">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-6">
                  <label for="">Atas Nama Rekening</label>
                  <input type="text" name="atas_nama_rekening" placeholder="Atas Nama Rekening" class="form-control form-dark" required="true">
                </div>
                <div class="col-md-6">
                  <label for="">Kota Tempat Rekening Dibuka</label>
                  <input type="text" name="kota_tempat_rekening_dibuka" placeholder="Kota Tempat Rekening Dibuka" class="form-control form-dark" required="true">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-6">
                  <label for="">Foto KTP</label>
                  <input type="file" name="foto_ktp" class="form-control form-dark" required="true">
                </div>

                <div class="col-md-6">
                  <label for="">Foto Barang</label>
                  <input type="file" name="foto_barang" class="form-control form-dark" required="true">
                </div>
              </div>
              <br>
              <b>Ketentuan</b> : <br>
              1. Konsumen wajib melakukan registrasi digital warranty dengan mengisi data diri serta meng-upload invoice pembelian, dan KTP. <br>
              2. Tim CS Artugo akan menghubungi konsumen untuk melakukan validasi data. <br>
              3. Cashback tidak berlaku jika data tidak valid atau tidak sesuai. <br>
              4. Cashback akan ditransfer langsung ke rekening konsumen, paling lama 5 hari kerja sejak data tervalidasi. <br>
              5. Batas waktu claim, maksimal satu minggu setelah periode promo berakhir. <br>
              6. Promo ini tidak berlaku untuk barang indent.
              <br>
              <br>

              <div class="form-group">
                <button class="btn btn-white">Submit Cashback</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('js')
<script>
  var date = new Date();
  date.setDate(date.getDate() + 1);

  $('#prefered_date').datepicker({
    daysOfWeekDisabled: [0],
    startDate: date,
    format: 'dd-mm-yyyy',
  })
</script>
@endpush


@endsection