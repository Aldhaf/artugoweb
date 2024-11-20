@extends('web.layouts.app')
@section('title', 'Service Tracking')

@section('content')


<style media="screen">
/* Import */
/* Variables */
/* Base */
/* Timeline */
.timeline {
    border-left: 4px solid #004ffc;
    border-bottom-right-radius: 4px;
    border-top-right-radius: 4px;
    background: rgba(0, 0, 0, 0.25);
    color: rgba(255, 255, 255, 0.8);
    font-family: "Chivo", sans-serif;
    margin: 20px auto;
    letter-spacing: 0.5px;
    position: relative;
    line-height: 1.4em;
    font-size: 1.03em;
    padding: 20px 50px;
    list-style: none;
    text-align: left;
    font-weight: 100;
    max-width: 60%;
}
.timeline h2,
.timeline h3 {
    font-weight: 600;
    font-size: 15px;
}
.timeline .event {
    border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
    padding-bottom: 15px;
    margin-bottom: 15px;
    position: relative;
}
.timeline .event:last-of-type {
    padding-bottom: 0;
    margin-bottom: 0;
    border: none;
}
.timeline .event:before,
.timeline .event:after {
    position: absolute;
    display: block;
    top: 0;
}
.timeline .event:before {
    left: -217.5px;
    color: rgba(255, 255, 255, 0.8);
    content: attr(data-date);
    text-align: right;
    font-weight: 100;
    font-size: 1em;
    min-width: 120px;
}
.timeline .event:after {
    box-shadow: 0 0 0 4px #004ffc;
    left: -57.85px;
    background: #313534;
    border-radius: 50%;
    height: 11px;
    width: 11px;
    content: "";
    top: 5px;
}

.timeline-group .date{
    font-size: 18px;
    margin-left: 10px;
    font-weight: bold;
}

.timeline p{
    margin-bottom: 0px;
}


</style>


<div class="content content-dark content-small">
    <div class="container" style="padding-top: 100px;">
        <div class="row">
            <div class="col-sm-3">
                @include('web.layouts.member-sidebar')
            </div>
            <div class="col-sm-9">
                <h1 class="member-content-title">Service No.: </h1>
                <div class="member-content">
                    <?php foreach ($progress as $prog): ?>
                        <?php $progress_detail = DB::table('reg_service_progress')->select('status', 'update_time', 'notes', 'created_at')->where('update_time', $prog->update_time)->orderBy('created_at', 'desc')->get(); ?>
                        <div class="timeline-group">
                            <div class="date">
                                <?= date('d-m-Y', strtotime($prog->update_time)) ?>
                            </div>
                            <ul class="timeline">
                                <?php foreach ($progress_detail as $det): ?>
                                    <?php $status = DB::table('ms_service_status')->where("id", $det->status)->first(); ?>
                                    <li class="event" data-date="<?= date("H:i", strtotime($det->created_at)) ?>">
                                        <h3><?= $status->service_status ?></h3>
                                        <p><?= $det->notes ?></p>
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


    @endsection
