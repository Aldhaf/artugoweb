@extends('web.layouts.app')
@section('title', 'Warranty Information')

@section('content')



<div class="content content-dark content-small">
    <div class="container" style="padding-top: 100px;">
        <div class="row">
            <div class="col-sm-12" style="padding-bottom: 100px;">
                <h1 class="member-content-title">Warranty Information</h1>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="warranty-info-container">

                            <div class="warranty-info-detail">
                                <h1>{{ $product->product_name??'' }}</h1>
                            </div>
                            <div class="warranty-detail-item">
                                <img src="{{ $product->product_image??'' }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="warranty-info-container">
                            <div class="warranty-detail-item">
                                <div class="warranty-info-detail" style="padding-right: 0px; padding-left: 0px;">
                                    <h4>Warranty Details</h4>
                                    <table class="table table-bordered">
                                        <?php foreach ($warranty_list as $wl) : ?>
                                            @if ($wl->warranty_type)
                                            <tr>
                                                <td><b><?= $wl->warranty_type ?></b></td>
                                                <td>
                                                    <?php
                                                    if ($wl->warranty_period > 0) {
                                                        echo date("d M Y", strtotime($wl->warranty_end));
                                                        $now = time(); // or your date as well
                                                        $your_date = strtotime($wl->warranty_end);
                                                        $datediff = $your_date - $now;

                                                        $diff = round($datediff / (60 * 60 * 24));
                                                        echo "(" . number_format($diff, 0, ',', '.') . " Days left)";
                                                    } else {
                                                        echo "Lifetime Warranty";
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            @endif
                                        <?php endforeach; ?>

                                        <tr>
                                            <td><b>Serial No.</b></td>
                                            <td>{{ $warranty->serial_no }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Warranty No.</b></td>
                                            <td>{{ $warranty->warranty_no }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Registered At</b></td>
                                            <td>{{ date("d M Y", strtotime($warranty->created_at)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Purchase Date</b></td>
                                            <td>{{ date("d M Y", strtotime($warranty->purchase_date)) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="warranty-info-container">
                            <div class="warranty-detail-item">
                                <div class="warranty-info-detail" style="padding-right: 0px; padding-left: 0px;">
                                    <h4>Product Registration Information</h4>
                                    <table class="table">
                                        <tr>
                                            <td><b>Name</b></td>
                                            <td>: {{ $warranty->reg_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Phone</b></td>
                                            <td>: {{ $warranty->reg_phone }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Email</b></td>
                                            <td>: {{ $warranty->reg_email }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Address</b></td>
                                            <td>: {{ $warranty->reg_address }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>City</b></td>
                                            <td>: {{ $warranty->reg_city }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection