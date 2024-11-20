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
                    <?php if(Request::segment(3) == "") echo "On Going Service"; else echo "Service History"; ?>
                </h1>
                <div class="member-content">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ url('member/service') }}" class="btn btn-tabs-dark <?php if(Request::segment(3) == "") echo "active"; ?>" >On Going Service</a>
                            <a href="{{ url('member/service/history') }}" class="btn btn-tabs-dark <?php if(Request::segment(3) == "history") echo "active"; ?>">Service History</a>
                        </div>
                        <?php
                        if(count($service_list) == 0){
                            echo "<div class='col-12' style='padding-top: 20px;'>There's no service item.</div>";
                        }
                        ?>
                        <?php foreach ($service_list as $service): ?>
                            <?php
                            $det = DB::table('ms_products')->where('product_id', $service->product_id)->first();
                            $prod = DB::table('reg_warranty')->where('warranty_id', $service->warranty_id)->first();
                            ?>

                            <div class="col-12">
                                <div class="warranty-item">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <a href="{{ url('member/service/' . $service->service_no) }}" class="warranty-item-img">
                                                <div style="margin-bottom: 5px; text-align: left; padding-left: 20px;">
                                                    <?php
                                                    if ($service->service_type == 0) {
                                                    ?>
                                                        <span class="btn btn-xs btn-white btn-solid">Installation</span>
                                                    <?php
                                                    }
                                                    else{
                                                    ?>
                                                        <span class="btn btn-xs btn-white btn-solid">Service</span>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <img src="{{ $det->product_image??'' }}">
                                            </a>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="warranty-prod-summary">
                                                <a href="{{ url('member/service/' . $service->service_no) }}" style="display: block; width: 100%;">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="warranty-prod-item warranty-prod-name">
                                                                {{ $det->product_name??'' }}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="warranty-prod-item">
                                                                <strong>Status:</strong>
                                                                <div class="prod-info">
                                                                    <?php
                                                                    if($service->status == 0){
                                                                        echo "On Progress";
                                                                    }
                                                                    else{
                                                                        echo "Completed";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="warranty-prod-item">
                                                                <strong>Requested At:</strong>
                                                                <div class="prod-info"><?= date("d-m-Y", strtotime($service->created_at)) ?></div>
                                                            </div>
                                                            <?php if ($service->status == 1): ?>
                                                                <div class="warranty-prod-item">
                                                                    <strong>Completed At:</strong>
                                                                    <div class="prod-info"><?= date("d-m-Y", strtotime($service->updated_at)) ?></div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="warranty-prod-item">
                                                                <strong>Warranty No:</strong>
                                                                <div class="prod-info">{{ $prod->warranty_no }}</div>
                                                            </div>
                                                            <div class="warranty-prod-item">
                                                                <strong>Serial No:</strong>
                                                                <div class="prod-info">{{ $prod->serial_no }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
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
