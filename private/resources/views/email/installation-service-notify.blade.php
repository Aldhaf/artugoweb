<?php
    $style_table = 'font-family: Arial, Helvetica, sans-serif; border-collapse: collapse; width: 100%; width: max-content;';
    $style_th = 'padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #000; color: white; border: 1px solid #ddd; padding: 8px;';
    $style_td = 'padding-top: 12px; padding-bottom: 12px; text-align: left; border: 1px solid #ddd; padding: 8px; vertical-align: top;';
    $maxwidth = 'unset';
?>
@extends('email.letterhead')
@section('content')
<p>Hi there,</p>
<p>Here We reminder You, jobs more than 7 days.</p>
@foreach($data as $index_grp => $service)
@if(count($service['data']) > 0)
<div style="width: 100%; overflow-x: auto; {{$index_grp > 0 ? 'margin-top: 50px;' : ''}}">
    <h2 style="margin-top: 0px; ">{{$service['title']}}</h2>
    <table style="{{$style_table}}">
        <thead>
            <tr>
                <th style="{{$style_th}}" width="40">No.</th>
                <th style="{{$style_th}}" width="130">Service No</th>
                <th style="{{$style_th}}" width="120"><center>Request Date</center></th>
                <th style="{{$style_th}}" width="90"><center>Lead Time</center></th>
                <th style="{{$style_th}}" width="150">Member Name</th>
                <th style="{{$style_th}}" width="130">Member Phone</th>
                <th style="{{$style_th}}" width="250">Product</th>
                <th style="{{$style_th}}" width="110">Serial No</th>
                <th style="{{$style_th}}" width="150">Service Center</th>
                <th style="{{$style_th}}" width="150">Technician</th>
                <th style="{{$style_th}}" width="150">Symptom</th>
                <th style="{{$style_th}}" width="150">Defect</th>
                <th style="{{$style_th}}" width="150">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($service['data'] as $index_row => $row)
            <tr <?php echo fmod($index_row, 2) != 0 ? 'style="background-color: #f2f2f2;"' : '' ?> style="cursor: pointer;" onclick="alert('xxx')">
                <?php
                    $latest_progress = DB::table('reg_service_progress')
                        ->select(
                        'ms_problems_symptom.symptom AS symptom_name',
                        'ms_problems_defect.defect AS defect_name',
                        'ms_problems_action.action AS action_name'
                        )
                        ->leftJoin('ms_problems_symptom', 'ms_problems_symptom.id', 'reg_service_progress.symptom')
                        ->leftJoin('ms_problems_defect', 'ms_problems_defect.id', 'reg_service_progress.defect')
                        ->leftJoin('ms_problems_action', 'ms_problems_action.id', 'reg_service_progress.action')
                        ->where('reg_service_progress.service_id', $row->service_id)
                        ->orderBy('reg_service_progress.created_at', 'desc')
                        ->first();
                ?>
                <td style="{{$style_td}}">{{$index_row + 1}}</td>
                <td style="{{$style_td}}">
                    <a style="text-decoration: none; font-weight: bold;" href="{{url('/artmin/' . ($row->service_type == 0 ? 'installation' : 'service') . '/request-details/' . $row->service_id)}}" target="_blank" rel="noreferrer noopener">{{$row->service_no}}</a>
                </td>
                <td style="{{$style_td}}"><center><span>{{date('d-m-Y', strtotime($row->created_at)) }}</span></center></td>
                <td style="{{$style_td}}"><center><span>{{$row->lead_time}}</span></center></td>
                <td style="{{$style_td}}"><span>{{$row->contact_name}}</span></td>
                <td style="{{$style_td}}"><span>{{$row->reg_phone}}</span></td>
                <td style="{{$style_td}}"><span>{{$row->product_name_odoo}}</span></td>
                <td style="{{$style_td}}"><span>{{strtoupper($row->serial_no)}}</span></td>
                <td style="{{$style_td}}"><span>{{$row->sc_location}}</span></td>
                <td style="{{$style_td}}"><span>{{$row->technician_name}}</span></td>
                <td style="{{$style_td}}"><span>{{$latest_progress->symptom_name}}</span></td>
                <td style="{{$style_td}}"><span>{{$latest_progress->defect_name}}</span></td>
                <td style="{{$style_td}}"><span>{{$latest_progress->action_name}}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endforeach

@endsection