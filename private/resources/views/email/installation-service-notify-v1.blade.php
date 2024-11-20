<?php
    $style_table = 'font-family: Arial, Helvetica, sans-serif; border-collapse: collapse; width: 100%;';
    $style_th = 'padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #000; color: white; border: 1px solid #ddd; padding: 8px;';
    $style_td = 'padding-top: 12px; padding-bottom: 12px; text-align: left; border: 1px solid #ddd; padding: 8px;';
    $maxwidth = 'unset';
?>
@extends('email.letterhead')
@section('content')
<p>Hi there,</p>
<p>Here We reminder You, jobs more than 7 days.</p>
@foreach($data as $index_grp => $service)
@if(count($service['data']) > 0)
<div style="width: 100%; {{$index_grp > 0 ? 'margin-top: 50px;' : ''}}">
    <h2 style="margin-top: 0px; ">{{$service['title']}}</h2>
    <table style="{{$style_table}}">
        <thead>
            <tr>
                <th style="{{$style_th}}" width="40">No.</th>
                <th style="{{$style_th}}">Summary Information</th>
                <!-- <th style="{{$style_th}}" width="60"></th> -->
            </tr>
        </thead>
        <tbody>
            @foreach ($service['data'] as $index_row => $row)
            <tr <?php echo fmod($index_row, 2) != 0 ? 'style="background-color: #f2f2f2;"' : '' ?>>
                <td style="{{$style_td}} vertical-align: top;">{{$index_row + 1}}</td>
                <td style="{{$style_td}}">
                    <div style="display: flex;">
                        <span style="width: 120px; font-size: 0.9rem; font-weight: bold;">Number&nbsp;</span>
                        <span style="font-weight: bold;">:&nbsp;&nbsp;</span>
                        <a style="text-decoration: none; font-weight: bold;" href="{{url('/artmin/' . ($row->service_type == 0 ? 'installation' : 'service') . '/request-details/' . $row->service_id)}}" target="_blank" rel="noreferrer noopener">{{$row->service_no}}</a>
                    </div>
                    <div style="display: flex;">
                        <span style="width: 120px; font-size: 0.9rem; font-weight: bold;">Date&nbsp;</span>
                        <span style="font-weight: bold;">:&nbsp;&nbsp;</span>
                        <span>{{date('d-m-Y', strtotime($row->created_at)) }}</span>
                    </div>
                    <div style="display: flex;">
                        <span style="width: 120px; font-size: 0.9rem; font-weight: bold;">Member&nbsp;</span>
                        <span style="font-weight: bold;">:&nbsp;&nbsp;</span>
                        <span>{{$row->contact_name}}</span>
                    </div>
                    <div style="display: flex;">
                        <span style="width: 120px; font-size: 0.9rem; font-weight: bold;">Product&nbsp;</span>
                        <span style="font-weight: bold;">:&nbsp;&nbsp;</span>
                        <span>{{$row->product_name_odoo}}</span>
                    </div>
                    <div style="display: flex;">
                        <span style="width: 120px; font-size: 0.9rem; font-weight: bold;">Serial No&nbsp;</span>
                        <span style="font-weight: bold;">:&nbsp;&nbsp;</span>
                        <span>{{strtoupper($row->serial_no)}}</span>
                    </div>
                    <div style="display: flex;">
                        <span style="width: 120px; font-size: 0.9rem; font-weight: bold;">Service Center&nbsp;</span>
                        <span style="font-weight: bold;">:&nbsp;&nbsp;</span>
                        <span>{{$row->sc_location}}</span>
                    </div>
                    <div style="display: flex;">
                        <span style="width: 120px; font-size: 0.9rem; font-weight: bold;">Technician&nbsp;</span>
                        <span style="font-weight: bold;">:&nbsp;&nbsp;</span>
                        <span>{{$row->technician_name}}</span>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endforeach

@endsection