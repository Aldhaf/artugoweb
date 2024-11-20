@extends('web.layouts.app')
@section('title', 'Member Dashboard')

@section('content')

<style>
    .review-option-capsul {
        padding-left: 6px;
        padding-right: 6px;
        border-radius: .3rem !important;
        border: 1px solid white;
        width: fit-content;
        font-weight: bold;
    }
    .review-option-capsul.selected {
        background: rgb(40, 167, 69);
    }

    @media (max-width: 481px) {
        .btn-regnew-warranty {
            width: 100%;
        }
        .poin-capsul {
            display: flex;
        }
    }
</style>

<?php
function calcTime($deadLine)
{
    $timeRemaining = $deadLine - $_SERVER['REQUEST_TIME'];
    if ($timeRemaining < 0) {
        $timeRemaining = abs($timeRemaining);
        $end = "ago.";
    } else if (!$timeRemaining) return 0;
    else $end = "remaining.";
    $timeRemaining = $timeRemaining / (60 * 60 * 24 * 365);
    $yrs = floor($timeRemaining);
    $timeRemaining = (($timeRemaining - $yrs) * 365);
    $days = floor($timeRemaining);
    $timeRemaining = (($timeRemaining - $days) * 24);
    $hrs = floor($timeRemaining);
    $timeRemaining = (($timeRemaining - $hrs) * 60);
    $min = floor($timeRemaining);
    $timeRemaining = (($timeRemaining - $min) * 60);
    $sec = floor($timeRemaining);
    $str = '';
    if ($yrs) $str = $str . $yrs . " years ";
    if ($days) $str = $str . $days . " days ";
    $str = $str . $end;
    return $str;
}
?>

<div class="content content-dark content-small">
    <div class="container" style="padding-top: 100px;">
        <div class="row">
            <div class="col-sm-3 mb-4">
                @include('web.layouts.member-sidebar')
            </div>
            <div class="col-sm-9">
                <h1 class="member-content-title">Hi, {{ Session::get('member_name') }}, berikut daftar produk Anda</h1>
                <div class="member-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <a href="{{ url('warranty/registration') }}" class="btn btn-white btn-regnew-warranty">Register New Product</a>
                        </div>
                        <?php foreach ($products as $prod) : ?>
                            <?php $det = DB::table('ms_products')->where('product_id', $prod->product_id)->first(); ?>

                            <div class="col-12">
                                <div class="warranty-item" target="_blank">
                                    <div class="row">
                                        <div class="col-sm-4 text-center">
                                            <a href="{{ url('member/warranty/' . $prod->warranty_no) }}" target="_blank" class="warranty-item-img">
                                                <img src="{{ $det->product_image??'' }}">
                                            </a>
                                            <a href="{{ url('member/warranty/' . $prod->warranty_no) }}" target="_blank" class="btn btn-white btn-xs text-white">Warranty Detail</a>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="warranty-prod-summary">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="warranty-prod-item warranty-prod-name">
                                                            {{ $det->product_name??'' }}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <div class="warranty-prod-item">
                                                            <strong>Warranty No:</strong>
                                                            <div class="prod-info">{{ $prod->warranty_no }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <div class="warranty-prod-item">
                                                            <strong>Serial No:</strong>
                                                            <div class="prod-info">{{ $prod->serial_no }}</div>
                                                        </div>
                                                    </div>

                                                    <?php $member_point = DB::table('member_point')
                                                        ->select('id', 'status', 'value', 'expired_at')
                                                        ->where('member_id', $prod->member_id)
                                                        ->where('warranty_id', $prod->warranty_id)
                                                        ->where('type', 'first')
                                                        ->where('status', '!=', 'rejected')
                                                        ->first();
                                                    ?>

                                                    @if($prod->verified == '1')
                                                    <div class="col-sm-12">
                                                        <div class="warranty-prod-item">
                                                            <strong>Active Warranty:</strong>
                                                            <div class="prod-info">
                                                                <?php
                                                                $warranty_list = DB::table('reg_warranty_details')->where('warranty_reg_id', $prod->warranty_id)->get();
                                                                foreach ($warranty_list as $wl) {
                                                                ?>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            {{ $wl->warranty_type }}
                                                                            <br>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <?php
                                                                            if ($wl->warranty_period > 0) {
                                                                            ?>
                                                                                <small>{{ calcTime(strtotime($wl->warranty_end)) }}</small><br>
                                                                                <small>(Expired: {{ date('d M Y', strtotime($wl->warranty_end)) }})</small>
                                                                            <?php
                                                                            } else {
                                                                            ?>
                                                                                <small>Lifetime Warranty</small>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>

                                                @if (!$member_point || ($member_point && $member_point->status !== 'approved'))
                                                    @if($prod->verified != '1')
                                                    <hr />
                                                    @endif
                                                <div class="row">
                                                    <div class="form-group col-sm-12">
                                                        <div class="col-md-12 d-flex flex-column mb-2 my-3 p-0">
                                                            <strong>Review of Product</strong>
                                                            <textarea class="w-100" id="testimony-{{$prod->warranty_id}}">{{$prod->review}}</textarea>
                                                        </div>
                                                        <?php /*<div class="d-flex gap-2">
                                                            <a class="review-option-capsul selected" data-id="{{$prod->warranty_id}}" href="">Harga Affordable</a>
                                                            <a class="review-option-capsul selected" data-id="{{$prod->warranty_id}}" href="">Kualitas diatas expektasi</a>
                                                        </div>*/
                                                        ?>
                                                        <div class="d-flex flex-row justify-content-between">
                                                            <div>
                                                                <b>Give a Rating</b>
                                                                <input type="hidden" id="star-{{$prod->warranty_id}}" name="star" value="{{ ($prod->star ?? '0') }}">
                                                                <div>
                                                                    @if(empty($prod->star))
                                                                    <i style="color:gray; cursor:pointer" data-star="1" warranty-id="{{$prod->warranty_id}}" class="rating-pd{{$prod->warranty_id}} rating-star rating-star-1 fa fa-star"></i>
                                                                    <i style="color:gray; cursor:pointer" data-star="2" warranty-id="{{$prod->warranty_id}}" class="rating-pd{{$prod->warranty_id}} rating-star rating-star-2 fa fa-star"></i>
                                                                    <i style="color:gray; cursor:pointer" data-star="3" warranty-id="{{$prod->warranty_id}}" class="rating-pd{{$prod->warranty_id}} rating-star rating-star-3 fa fa-star"></i>
                                                                    <i style="color:gray; cursor:pointer" data-star="4" warranty-id="{{$prod->warranty_id}}" class="rating-pd{{$prod->warranty_id}} rating-star rating-star-4 fa fa-star"></i>
                                                                    <i style="color:gray; cursor:pointer" data-star="5" warranty-id="{{$prod->warranty_id}}" class="rating-pd{{$prod->warranty_id}} rating-star rating-star-5 fa fa-star"></i>
                                                                    @else
                                                                        @for($i=1;$i <= 5; $i++ )
                                                                        <i style="color:{{ ($i <= $prod->star ? 'gold' : 'gray') }}; cursor:pointer" data-star="{{ $i }}" warranty-id="{{$prod->warranty_id}}" class="rating-pd{{$prod->warranty_id}} rating-star rating-star-{{ $i }} fa fa-star"></i>
                                                                        @endfor
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="mt-1 d-flex btn-white rounded-3 p-2" style="height: fit-content;">
                                                                <a class="text-white m-0 btn-submit-testimony" data-id="{{$prod->warranty_id}}" prod-id="{{$prod->product_id}}" href="">
                                                                    Submit Review
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                @endif
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group col-sm-12 p-0">
                                                            @if ($member_point && $member_point->status == 'approved')
                                                            <a href="{{ url('member/point/detail/' . $prod->warranty_id) }}">
                                                            <div class="d-flex justify-content-center p-0 gap-2">
                                                                <div style="flex: 1;">
                                                                    <strong>Point Earned</strong>
                                                                    <div class="prod-info mt-1">
                                                                        <strong class="bg-success text-white rounded-3 px-2 py-1 poin-capsul">{{ number_format($member_point->value, 2) }}</strong>
                                                                    </div>
                                                                </div>
                                                                <div style="flex: 1;">
                                                                    <strong>Point Expired</strong>
                                                                    <div class="prod-info mt-1">
                                                                        <strong class="bg-danger text-white rounded-3 px-2 py-1 poin-capsul">{{ date("d-M-Y", strtotime($member_point->expired_at)) }}</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            </a>
                                                            @elseif ($member_point && $member_point->status == 'waiting')
                                                            <div class="prod-info font-weight-bold warranty-prod-item bg-warning text-white rounded-3 px-2" style="width: fit-content;">Waiting Approval Point</div>
                                                            @elseif ($member_point && $member_point->status == 'rejected')
                                                            <div class="prod-info font-weight-bold warranty-prod-item bg-danger text-white rounded-3 px-2" style="width: fit-content;">Rejected Point</div>
                                                            @elseif ($prod->verified === 1 && !$member_point && $complete_profile && !empty($prod->review ?? '') && $prod->base_point > 0 && $prod->purchase_date >= date('Y-m-d', strtotime('2020-07-01')))
                                                            <a class="prod-info font-weight-bold warranty-prod-item bg-danger text-white rounded-3 px-2 py-1 btn-claim-point" data-id="{{$prod->warranty_id}}" href="" style="width: fit-content;">
                                                                Claim {{number_format($prod->base_point, 2)}} Point Now
                                                            </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <?php
                                                        if ($prod->verified == '1') {
                                                            $check_service = DB::table('reg_service')->where('warranty_id', $prod->warranty_id)->where('status', 0)->orderBy('created_at', 'desc')->first();
                                                            if (isset($check_service->service_id)) {
                                                        ?>
                                                                <div style="margin-bottom: 20px;">
                                                                    <?php
                                                                    if ($check_service->service_type == 0) {
                                                                    ?>
                                                                        <div class="form-group"><strong><i class="fa fa-tools"></i> Installation Process</strong></div>
                                                                        <a href="{{ url('member/service/' . $check_service->service_no) }}" class="btn btn-white btn-solid btn-xs"><i class="fa fa-search"></i> More Info</a>
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <div class="form-group"><strong><i class="fa fa-tools"></i> Under Service</strong></div>
                                                                        <a href="{{ url('member/service/' . $check_service->service_no) }}" class="btn btn-white btn-solid btn-xs"><i class="fa fa-search"></i> Track Service</a>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class="form-group col-sm-12 p-0" style="margin-top: 10px;">
                                                                    <?php
                                                                    $installation = false;
                                                                    $reg_war = DB::table('reg_warranty')->where('warranty_no',  $prod->warranty_no)->first();

                                                                    if (!empty($reg_war)) {
                                                                        $product = DB::table('ms_products')->where('product_id', $reg_war->product_id)->first();
                                                                        if (!empty($product)) {
                                                                            $initial = explode(' ', $product->product_code)[0];
                                                                            $check_installation = DB::table('ms_problems_initial')->where('initial', $initial)->first();
                                                                            if (!empty($check_installation)) {
                                                                                if ($check_installation->need_installation == '1') {
                                                                                    $installation = true;
                                                                                }
                                                                            }
                                                                        }
                                                                    } ?>
                                                                    <div class="d-flex justify-content-center p-0 gap-2">
                                                                        @if ($installation)
                                                                            <a href="{{ url('request/installation/' . $prod->warranty_no) }}" class="btn btn-white btn-sm m-0" style="flex: 1;">Request Installation</a>
                                                                        @endif
                                                                        <a href="{{ url('member/service/request/' . $prod->warranty_no) }}" class="btn btn-white btn-sm m-0" style="flex: 1;">Request Support</a>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            }
                                                        } elseif ($prod->verified == '2') {
                                                            ?>
                                                            <strong>Status :</strong>
                                                            <div class="prod-info"><b style="color:red"><i class="fa fa-times"></i> REJECTED</b></div>
                                                            <small>Warranty Rejected By Customer Care Artugo</small>
                                                            <hr>
                                                        <?php
                                                        } elseif ($prod->verified == '0') {
                                                        ?>
                                                            <strong>Status :</strong>
                                                            <div class="prod-info"><b style="color:orange"><i class="fa fa-clock"></i> PENDING</b></div>
                                                            <small>Waiting For Confirmation Data By Customer Care Artugo</small>
                                                            <hr>
                                                        <?php
                                                        }
                                                        ?>

                                                        <?php
                                                        $check = DB::table('tradein')->where('warranty_id', $prod->warranty_id)->first();
                                                        if (empty($check)) {
                                                            $date = date('Y-m-d', strtotime($prod->purchase_date));
                                                            $periode_trade_in = DB::table('tradein_periode')->whereDate('startDate', '<=', $date)->whereDate('endDate', '>=', $date)->first();
                                                            if (!empty($periode_trade_in)) {
                                                                $trade_in_barang = DB::table('tradein_periode_product')->where('tradein_periode', $periode_trade_in->id)->where('products_id', $prod->product_id)->first();
                                                                if (!empty($trade_in_barang)) {
                                                        ?>
                                                                    <a href="{{ url('member/tradein/' . $prod->warranty_no) }}" class="btn btn-white btn-xs">Trade In</a>
                                                                    <?php
                                                                }
                                                            }
                                                        }

                                                        $check = DB::table('cashback')->where('warranty_id', $prod->warranty_id)->first();
                                                        if (empty($check)) {
                                                            $date = date('Y-m-d', strtotime($prod->purchase_date));
                                                            $periode_cashback = DB::table('cashback_periode')
                                                                ->select(
                                                                    'cashback_periode.*',
                                                                )
                                                                ->join('cashback_periode_product', 'cashback_periode_product.cashback_periode', '=', 'cashback_periode.id')
                                                                ->whereDate('cashback_periode.startDate', '<=', $date)->whereDate('cashback_periode.endDate', '>=', $date)
                                                                ->groupBy('cashback_periode.id')
                                                                ->where('cashback_periode_product.products_id', $prod->product_id)
                                                                ->orderBy('cashback_periode.nominal', 'desc')
                                                                ->get();

                                                            if (!empty($periode_cashback)) {
                                                                foreach ($periode_cashback as $k_pc => $v_pc) {
                                                                    if ($v_pc->type_cashback == '1') {
                                                                    ?>
                                                                        <a href="{{ url('member/cashback/' . $prod->warranty_no) }}" class="btn btn-sm btn-white">Cashback</a>
                                                                        <?php
                                                                        break;
                                                                    } elseif ($v_pc->type_cashback == '2') {
                                                                        $product_combine = DB::table('cashback_combine')->where('cashback_periode', $v_pc->id)->get();
                                                                        $check_combine = DB::table('reg_warranty')->where('member_id', $prod->member_id)->where('purchase_date', $prod->purchase_date)->get();
                                                                        if (!empty($product_combine) && !empty($check_combine)) {
                                                                            foreach ($product_combine as $k_p_com => $v_p_com) {
                                                                                foreach ($check_combine as $k_cc => $v_cc) {
                                                                                    if ($v_p_com->product_id == $v_cc->product_id) {
                                                                        ?>
                                                                                        <a href="{{ url('member/cashback/' . $prod->warranty_no) }}" class="btn btn-sm btn-white">Cashback</a>
                                                                <?php
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }

                                                        if (!empty($prod->unique_number)) {
                                                            $check = DB::table('reg_warranty')->where('special_voucher_from', $prod->warranty_id)->first();
                                                            if (empty($check)) {
                                                                ?>
                                                                <a href="{{ url('member/specialvoucher/' . $prod->warranty_no) }}" class="btn btn-white btn-xs">Special Voucher</a>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">

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

@push("js")
<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script>
    $(document).on("click", ".btn-claim-point", function(e) {
        e.preventDefault();
        let warranty_id = $(e.target).attr("data-id");
        $.post(`{{ url("member/point/claim") }}/${warranty_id}`, {
			"_token": "{{ csrf_token() }}"
        }, function(resData) {
            if (resData.success) {
                swal('Berhasil', 'Claim Point requested!', 'success').then((confirm) => location.reload());
            } else if (!resData.success && resData.message) {
                swal('Gagal', resData.message, 'error');
            }
        });
    });

    $(document).on("click", ".btn-submit-testimony", function(e) {
        e.preventDefault();
        let warranty_id = $(e.target).attr("data-id");
        let product_id = $(e.target).attr("prod-id");
        $.post(`{{ url("member/testimony") }}`, {
			"_token": "{{ csrf_token() }}",
            "warranty_id": warranty_id,
            "product_id": product_id,
            "testimony": $(`#testimony-${warranty_id}`).val(),
            "star": $(`#star-${warranty_id}`).val(),
        }, function(resData) {
            if (resData.success) {
                swal('Berhasil', resData.message, 'success').then((confirm) => location.reload());
            } else if (!resData.success && resData.message) {
                swal('Gagal', resData.message, 'error');
            }
        });
    });

    $(document).on('click', '.rating-star', function(e) {
        e.preventDefault();
        let star = $(this).attr('data-star');
        let warranty_id = $(this).attr('warranty-id');

        $(`.rating-pd${warranty_id}`).css('color', 'gray');
        for (let i = 1; i <= star; i++) {
            $(`.rating-pd${warranty_id}.rating-star-${i}`).css('color', 'gold');
        }
        $(`#star-${warranty_id}`).val(star);
    });

</script>
@endpush

@endsection