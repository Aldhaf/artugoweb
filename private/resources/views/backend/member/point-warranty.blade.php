@extends('backend.layouts.backend-app')
@section('title', 'Member')
@section('content')
<style>
  .gap-2 {
    gap: 8px;
  }

  .member-content-title {
      font-size: 25px;
      font-weight: bold;
      margin-bottom: 20px;
  }

  .member-content {
      /* background: #222629; */
      /* padding: 30px 30px; */
      border-radius: 8px;
      /* min-height: 500px; */
      /* margin-bottom: 100px; */
  }

  .warranty-item {
      display: inline-block;
      position: relative;
      /* background: #30353a; */
      /* color: #fff; */
      border-radius: 8px;
      box-shadow: 0px 0px 5px 1px rgba(0, 0, 0, 0.03);
      margin: 15px 0px;
      width: 100%;
      -webkit-transition: all 200ms ease;
      -moz-transition: all 200ms ease;
      -ms-transition: all 200ms ease;
      -o-transition: all 200ms ease;
      transition: all 200ms ease;
  }

  .warranty-item:hover {
      box-shadow: 0px 0px 20px 2px rgba(0, 0, 0, 0.2);
      /* color: #fff; */
      -webkit-transition: all 200ms ease;
      -moz-transition: all 200ms ease;
      -ms-transition: all 200ms ease;
      -o-transition: all 200ms ease;
      transition: all 200ms ease;
  }

  .warranty-item .warranty-item-img img {
      max-width: 90%;
  }

  .warranty-item-img {
      text-align: center;
      display: block;
      padding: 20px 10px 10px 10px;
  }

  .warranty-item-info {
      padding: 20px 30px;
  }

  .warranty-item-info .info {
      font-size: 15px;
      margin-bottom: 15px;
      color: #c3c3c3;
  }

  /* .info-group {
      //margin-bottom: 15px;
  } */

  .warranty-prod-summary {
      padding: 40px 20px 20px 20px;
  }

  .warranty-prod-item {
      margin-bottom: 10px;
  }

  .warranty-prod-item .prod-info {
      font-size: 14px;
  }

  .warranty-prod-name {
      font-size: 18px;
      font-weight: bold;
  }

  .warranty-info-container {
      background: #222629;
      padding: 20px 40px;
      border-radius: 8px;
      margin-bottom: 30px;
  }

  .warranty-info-detail {
      padding: 20px 20px;
      color: #fff;
  }

  .warranty-info-detail .table {
      color: #fff;
  }

  .warranty-info-detail h1 {
      font-size: 25px;
      font-weight: bold;
      margin-bottom: 20px;
  }

  .warranty-info-detail h4 {
      font-size: 15px;
      font-weight: bold;
  }

  .warranty-help {
      padding: 30px 20px;
  }

  .member-content-view {
      display: flex;
      flex-direction: column;
      gap: 24px;
  }
  .uncomplete-warning {
      gap: 4px;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
  }

  .rounded-3 {
    border-radius: .3rem !important;
  }
</style>

<div class="row">
  <div class="col-sm-12">
    <div class="box box-solid" style="height: 620px; max-height: 620px; overflow-y: auto;">
      <div class="box-body">
        <h1 class="member-content-title">
          Details Point
        </h1>
        <div class="member-content">
          <div class="row">
            <div class="col-12">
              <a href="{{ url('/artmin/member/point/' . $warranty->member_id) }}" class="btn btn-md btn-primary"> Back to Summary Point</a>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="warranty-item-img">
                  <div style="margin-bottom: 5px; text-align: center; padding-left: 20px;">
                    <img src="{{ $product->product_image??'' }}">
                  </div>
                </div>
              </div>
              <div class="col-sm-8">
                <div class="warranty-prod-summary">
                  <div class="row">
                    <div class="col-12">
                      <div class="warranty-prod-item warranty-prod-name">
                        {{ $product->product_name??'' }}
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="warranty-prod-item">
                        <strong>Warranty No:</strong>
                        <div class="prod-info">{{ $product->warranty_no }}</div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="warranty-prod-item">
                        <strong>Serial No:</strong>
                        <div class="prod-info">{{ $product->serial_no }}</div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="warranty-prod-item">
                        <strong>Balance Point:</strong>
                        <div class="prod-info">{{ number_format($product->balance_point, 2) }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 mb-1">
              <div class="warranty-item mb-0 rounded-0">
                <div class="row py-2 pl-4">
                  <div class="col-md-3">
                    <strong>Transaction Date</strong>
                  </div>
                  <div class="col-md-5">
                    <strong>Description</strong>
                  </div>
                  <div class="col-md-3 text-right pr-0">
                    <strong>Point</strong>
                  </div>
                </div>
              </div>
            </div>
            <?php foreach ($points as $point): ?>
            <div class="col-12 m-0">
              <div class="warranty-item m-0 bg-transparent rounded-0">
                <div class="row py-2 pl-4">
                  <div class="col-md-3">
                    <div class="prod-info"><?= date("d-m-Y", strtotime($point->trx_date)) ?></div>
                  </div>
                  <div class="col-md-5">
                    <div class="prod-info">{{$point->description}}</div>
                  </div>
                  <div class="col-md-3 text-right pr-0">
                    <div class="prod-info">{{ number_format($point->point, 2) }}</div>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection