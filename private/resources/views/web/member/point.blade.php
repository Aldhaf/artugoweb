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
                    Summary Point
                </h1>
                <div class="member-content">
                    <div class="row">
                        @if(count($points) == 0)
                            <div class='col-12' style='padding-top: 20px;'>There's no item.</div>
                        @endif
                        <?php foreach ($points as $point): ?>
                            <div class="col-12">
                                <div class="warranty-item">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="warranty-item-img">
                                                <div style="margin-bottom: 5px; text-align: center; padding-left: 20px;">
                                                    <img src="{{ $point->product_image??'' }}">
                                                    <!-- <span class="btn btn-xs btn-white btn-solid">Detail Point</span> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="warranty-prod-summary">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="warranty-prod-item warranty-prod-name">
                                                            {{ $point->product_name??'' }}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="warranty-prod-item">
                                                            <strong>Warranty No:</strong>
                                                            <div class="prod-info">{{ $point->warranty_no }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="warranty-prod-item">
                                                            <strong>Serial No:</strong>
                                                            <div class="prod-info">{{ $point->serial_no }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="warranty-prod-item">
                                                            <strong>Claim Date:</strong>
                                                            <div class="prod-info" style="width: fit-content;"><?= date("d-M-Y", strtotime($point->created_at)) ?></div>
                                                        </div>
                                                        <div class="warranty-prod-item">
                                                            <strong>Status:</strong>
                                                            @if($point->status == 'expired')
                                                                <div class="prod-info font-weight-bold warranty-prod-item bg-danger text-white rounded-3 px-2" style="width: fit-content;">Expired</div>
                                                            @else
                                                                <div class="prod-info font-weight-bold warranty-prod-item bg-success text-white rounded-3 px-2" style="width: fit-content;">Ready to Use</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div>
                                                            <strong>Points Earned:</strong>
                                                            <div style="display: flex; gap: 4px;">
                                                                <div class="prod-info font-weight-bold warranty-prod-item bg-white text-body rounded-3 px-2 pointer">{{ number_format($point->point, 2) }}</div>
                                                                <a href="{{ url('member/point/detail/' . $point->warranty_id) }}">
                                                                    <div class="prod-info font-weight-bold warranty-prod-item bg-primary text-white rounded-3 px-2 pointer">Detail</div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="warranty-prod-item">
                                                            <strong>Expired At:</strong>
                                                            <div class="prod-info font-weight-bold warranty-prod-item bg-danger text-white rounded-3 px-2" style="width: fit-content;"><?= date("d-M-Y", strtotime($point->expired_at)) ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
