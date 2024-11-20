@extends('web.layouts.app')
@section('title', 'Service Tracking')

@section('content')

<div class="content content-dark content-small">
    <div class="container" style="padding-top: 100px;">
        <div class="row">
            <div class="col-sm-3">
                @include('web.layouts.member-sidebar')
            </div>
            <div class="col-sm-9">
                <h1 class="member-content-title">Service Tracking</h1>
                <div class="member-content">
                    <div class="warranty-item" style="font-size: 16px; margin: 10px 0px;">
                        <div class="row">
                            <div class="col-sm-5">
                                <a href="{{ url('member/warranty/' . $warranty->warranty_no) }}" target="_blank" class="warranty-item-img">
                                    <img src="{{ $product->product_image??'' }}" class="img-responsive">
                                </a>
                            </div>
                            <div class="col-sm-7">
                                <div class="warranty-prod-summary" style="padding-top: 50px;">
                                    <a href="{{ url('member/warranty/' . $warranty->warranty_no) }}" target="_blank">
                                        <div class="warranty-prod-item warranty-prod-name">
                                            <?php
                                            if($service->service_type == 0) echo "Installation";
                                            else echo "Service Request";
                                            ?>
                                        </div>
                                        <div class="warranty-prod-item warranty-prod-name">
                                            {{ $product->product_name??'' }}
                                        </div>
                                        <div class="warranty-prod-item">
                                            <strong>Service No:</strong> {{ $service->service_no }}
                                        </div>
                                        <div class="warranty-prod-item">
                                            <strong>Serial No:</strong> {{ $warranty->serial_no }}
                                        </div>
                                        <div class="warranty-prod-item">
                                            <strong>Status:</strong>
                                            <?php
                                            if($service->status == 0) echo "On Progress";
                                            else echo "<i class='fa fa-check'></i> Completed";
                                            ?>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="warranty-item">
                        <div class="warranty-item-info">
                            <?php if ($service->service_type == 1): ?>
                                <div class="info-group">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <strong>Problem Category</strong>
                                            <div class="info">
                                                <?= $service->problem_category ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <strong>Problem Details</strong>
                                            <div class="info">
                                                <?= $service->problem_notes ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="info-group">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <strong>Visit Information</strong>
                                        <div class="info">
                                            <?= $service->contact_name ?><br>
                                            <?= $service->contact_phone ?><br>
                                            <?= $service->service_address ?><br>
                                            <?= $service->service_city ?><br>
                                        </div>
                                    </div>
                                    <div class="col-sm-7">
                                        <strong>Prefered Visit Time</strong>
                                        <div class="info">
                                            <?= date('d M Y', strtotime($service->prefered_date)) ?><br>
                                            <?php
                                            $time = DB::table('ms_service_time')->where('id', $service->prefered_time)->first();
                                            echo $time->time;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="warranty-item">
                        <div class="warranty-item-info">
                            <strong>Progress</strong>
                            <hr>
                            <?php foreach ($progress as $prog): ?>
                                <?php $progress_detail = DB::table('reg_service_progress')->where('service_id', $service->service_id)->where('update_time', $prog->update_time)->orderBy('created_at', 'desc')->get(); ?>
                                <div class="timeline-group">
                                    <div class="date">
                                        <?= date('l, d M Y', strtotime($prog->update_time)) ?>
                                    </div>
                                    <ul class="timeline">
                                        <?php foreach ($progress_detail as $det): ?>
                                            <?php $status = DB::table('ms_service_status')->where("id", $det->status)->first(); ?>
                                            <li class="event" data-date="<?= date("H:i", strtotime($det->created_at)) ?>">
                                                <h3><?= $status->service_status ?></h3>
                                                <p><?= $det->info ?></p>
                                                <?php if ($det->pic != ''): ?>
                                                    <p><b>PIC: <?= $det->pic ?></b></p>
                                                <?php endif; ?>
                                                <?php if ($det->notes != ''): ?>
                                                    <div class="timeline-notes">
                                                        <small>
                                                            <b>Notes</b><br>
                                                            <?= $det->notes ?>
                                                        </small>
                                                    </div>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


@endsection
