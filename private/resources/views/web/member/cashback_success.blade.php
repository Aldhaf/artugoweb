@extends('web.layouts.app')
@section('title', 'Cashback')

@section('content')



<div class="content content-dark" style="padding-top: 0px;">
  <div style="background: url('{{ url('assets/img/bg-slider.png') }}') no-repeat bottom right; background-size: cover; padding-top: 80px;">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 offset-sm-2">
          <div class="warranty-register-success">
            <h1><b>Terima Kasih</b></h1>
            Data Cashback Anda Sedang Kami Proses
            <div class="warranty-register-no">
              Dengan Nomor Garansi :<br>
              <div class='warranty-no'>
                <?php echo $_GET['warranty']; ?>
              </div>
            </div>

            <a href="{{ url('member/dashboard') }}" class="btn btn-white btn-sm" style="margin-bottom: 10px;">Lihat Produk Saya</a>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection