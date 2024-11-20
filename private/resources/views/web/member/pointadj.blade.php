@extends('web.layouts.app')
@section('title', 'Service')

@section('content')

<div class="content content-dark content-small">
    <div class="container" style="padding-top: 100px;">
        <div class="row">
            <div class="col-sm-3">
                @include('web.layouts.member-sidebar')
            </div>
            <div class="col-sm-9">
                <h1 class="member-content-title">
                    Point
                </h1>
                <div class="member-content">
                    <div class="row">
                        <?php /*
                        <div class="col-12">
                            <a href="{{ url('member/point') }}" class="btn btn-tabs-dark" >Summary Points</a>
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
                                        <div class="col-sm-6">
                                            <div class="warranty-prod-item">
                                                <strong>Warranty No:</strong>
                                                <div class="prod-info">{{ $product->warranty_no }}</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="warranty-prod-item">
                                                <strong>Serial No:</strong>
                                                <div class="prod-info">{{ $product->serial_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        */ ?>
                        <div class="col-12 mb-1">
                            <div class="row">
                                <div class="col-4">
                                    <div class="warranty-prod-item">
                                        <strong>Total Point :</strong>
                                        <div class="prod-info">{{number_format($total_points_in)}}</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="warranty-prod-item">
                                        <strong>Point Used :</strong>
                                        <div class="prod-info">{{number_format($total_points_out * -1)}}</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="warranty-prod-item">
                                        <strong>Balance :</strong>
                                        <div class="prod-info">{{number_format($total_points_in + $total_points_out)}}</div>
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
