@extends('web.layouts.app')
@section('title', 'Warranty Registration Success')

@section('content')



<div class="content content-dark" style="padding-top: 0px;">
    <div style="background: url('{{ url('assets/img/bg-slider.png') }}') no-repeat bottom right; background-size: cover; padding-top: 80px;">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 offset-sm-2">
                    <div class="warranty-register-success">
                        <h1><b>Terima Kasih</b></h1>
                        Produk anda berhasil didaftarkan ke <b>Garansi Digital</b> kami.<br>
                        <div class="warranty-register-no">
                            Nomor Garansi anda adalah:<br>
                            <div class='warranty-no'>
                                <?php echo $_GET['warranty']; ?>
                            </div>
                        </div>
                        <?php
                        $installation = false;
                        $spg = false;

                        if (!empty(Auth::user())) {
                            if (Auth::user()->roles == '5') {
                                $spg = true;
                            }
                        }


                        $reg_war = DB::table('reg_warranty')->where('warranty_no', $_GET['warranty'])->first();
                        if (!empty($reg_war)) {
                            $product = DB::table('ms_products')->where('product_id', $reg_war->product_id)->first();
                            if(!empty($product)){
                                $initial = explode(' ', $product->product_code)[0];
                                $check_installation = DB::table('ms_problems_initial')->where('initial',$initial)->first();
                                if(!empty($check_installation)){
                                    if($check_installation->need_installation == '1'){
                                        $installation = true;
                                    }
                                }
                            }
                        }


                        if ($installation && !$spg) {
                        ?>
                            <div class="request-install-section">
                                <div style="margin-bottom: 20px;">
                                    Apakah anda membutuhkan bantuan instalasi?
                                </div>
                                <a href="{{ url('request/installation/' . $_GET['warranty']) }}" class="btn btn-white btn-solid">Request Instalasi</a>
                            </div>
                        <?php
                        }
                        ?>

                        <?php
                        if ($spg) {
                        ?>
                            <a href="{{ url('warranty/registration') }}" class="btn btn-sm btn-white" style="margin-bottom: 10px;">Registrasi Produk Lain</a> <a href="{{ url('artmin/registerproductcustomer') }}" class="btn btn-white btn-sm" style="margin-bottom: 10px;">Lihat Customer Saya</a>
                        <?php
                        } else {
                        ?>
                            <a href="{{ url('warranty/registration') }}" class="btn btn-sm btn-white" style="margin-bottom: 10px;">Registrasi Produk Lain</a> <a href="{{ url('member/dashboard') }}" class="btn btn-white btn-sm" style="margin-bottom: 10px;">Lihat Produk Saya</a>
                        <?php
                        }
                        ?>

                        <hr>
                        <?php
                        $prod = DB::table('reg_warranty')->where('warranty_no', $_GET['warranty'])->first();
                        if (!empty($prod)) {
                            $check = DB::table('tradein')->where('warranty_id', $prod->warranty_id)->first();
                            if (empty($check)) {
                                $date = date('Y-m-d', strtotime($prod->purchase_date));
                                $periode_trade_in = DB::table('tradein_periode')->whereDate('startDate', '<=', $date)->whereDate('endDate', '>=', $date)->first();
                                if (!empty($periode_trade_in)) {
                                    $trade_in_barang = DB::table('tradein_periode_product')->where('tradein_periode', $periode_trade_in->id)->where('products_id', $prod->product_id)->first();
                                    if (!empty($trade_in_barang)) {
                        ?>
                                        <a href="{{ url('member/tradein/' . $prod->warranty_no) }}" class="btn btn-sm btn-white">Trade In</a>
                        <?php
                                    }
                                }
                            }
                        }
                        ?>

                        <?php
                        if (!empty($prod)) {
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

                                // if (!empty($periode_cashback)) {
                                //     $trade_in_barang = DB::table('cashback_periode_product')->where('cashback_periode', $periode_cashback->id)->where('products_id', $prod->product_id)->first();
                                //     if (!empty($trade_in_barang)) {
                                //         if ($periode_cashback->type_cashback == '1') {
                                ?>
                                <!-- <a href="{{ url('member/cashback/' . $prod->warranty_no) }}" class="btn btn-sm btn-white">Cashback</a> -->
                        <?php
                                //         } elseif ($periode_cashback->type_cashback == '2') {
                                //             $check_combine = DB::table('reg_warranty')->where('member_id', $prod->member_id)->where('purchase_date', $prod->purchase_date)->get();
                                //             if(!empty($check_combine)){
                                //                 $product_combine = DB::table('cashback_combine')->where('cashback_periode',$periode_cashback->id)->get();
                                //                 foreach ($check_combine as $kc => $vc) {
                                //                     foreach ($product_combine as $kpc => $vpc) {
                                //                         if($)
                                //                     }
                                //                 }
                                //             }
                                //         }
                                //     }
                                // }


                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection