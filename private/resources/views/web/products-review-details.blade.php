@extends('web.layouts.app')
@section('title', $product->product_name_odoo)

@section('content')
<div class="content">
  <div class="product-page">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <div class="row" style="display: flex; justify-content: center;">
          <a href="javascript:void(0);" class="prod-detail-tabs active" style="width: auto; font-weight: 800; font-size: 22px; border-radius: 15px; padding-top: 3px; padding-bottom: 3px; margin-right: 0px;">
              <img src="{{ url('assets/img/artugo-arrow-w.png')}}" class="prod-tabs-accent">
              Product Review
            </a>
          </div>
          <div class="row" style="margin-top: 30px;">
            <div class="col-md-12">
              <?php
              $number = 0;
              foreach ($product_rating as $key => $value) {
                $number += $value->star;
              }
              $rating = (count($product_rating) > 0 ? round($number / count($product_rating), 1) : 0);
              ?>
              <center>
                <h1>{{ $rating }}/5</h1>
                <span>({{ count($product_rating) }}) Review</span>
              </center>
              <br>
              <hr>
              <table style="width:100%">
                <?php
                function calcPercentageRating($starIndex, $product, $jumlah_rating)
                {
                  $sum = DB::table('rating')->where('productID', $product->product_id)->where('star', $starIndex)->count();
                  return  '(' . $sum . ') ' . ($sum > 0 ? ($sum / $jumlah_rating) * 100 : 0) . '%';
                }
                ?>
                @for($a=5; $a >= 1; $a--)
                <tr>
                  <td>{{ $a }} Star</td>
                  <td>
                    <center>
                      @for($i=1;$i <= 5; $i++ ) <i style="color:{{ ($i <= $a ? 'gold' : 'gray') }}; cursor:pointer" data-star="{{ $i }}" class="rating-star rating-star-{{ $i }} fa fa-star"></i>
                        @endfor
                    </center>
                  </td>
                  <td>{{ calcPercentageRating($a, $product, count($product_rating)) }}</td>
                </tr>
                @endfor
              </table>
              <hr>
              <center>
                <a href="{{ url('products/'. $product->slug . '#writereview') }}" class="btn btn-sm active border shadow-sm mb-4" style="width:100%;border-radius: 15px;">
                  Write Review
                </a>
              </center>
            </div>
          </div>
        </div>
        <div class="col-md-8">

          @if(count($product_rating) > 0)
          @foreach($product_rating as $value)
          <div class="card card-body" style="margin-bottom: 20px;">
            <div class="row">
              <div class="col-md-12">
                <b>{{ $value->memberName }}</b>
                <br>
                @for($i=1;$i <= 5; $i++ ) <i style="color:{{ ($i <= $value->star ? 'gold' : 'gray') }}; cursor:pointer" data-star="{{ $i }}" class="rating-star rating-star-{{ $i }} fa fa-star"></i>
                  @endfor
                  <br>
                  <span style="font-size: 14px">Reviewed on {{ date('M d, Y',strtotime($value->created_at)) }}</span>
                  <hr>
                  <?php echo nl2br($value->review) ?>
              </div>
            </div>
          </div>
          @endforeach
          @else
          There are no reviews for this product yet
          @endif

        </div>
      </div>
    </div>
  </div>


</div>

@push('js')
<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>


@endpush

@endsection