<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use DB;
use Hash;
use Session;
use App\Members;
use App\Reg_warranty;
use App\Reg_warranty_details;
use App\Reg_service;
use App\Reg_service_progress;
use App\Products;
use App\Products_spec;

use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class MemberController extends Controller
{
    public function index()
    {

        return view('web.home');
    }

    public function login()
    {
        return view('web.member.login');
    }

    public function login_process(Request $request)
    {

        $email = str_replace(" ", "", Input::get('email'));
        $get_member = Members::where("status", 1)->where(function($query) use ($email){
            $query->orWhere('email', $email);
            $query->orWhere('phone', $email);
        })->first();

        if (isset($get_member->id)) {
            $password = Hash::check(Input::get('password'), $get_member->password);
            if ($password) {
                Session::put("member_id", $get_member->id);
                Session::put("member_email", $get_member->email);
                Session::put("member_name", $get_member->name);
                Session::put("member_phone", $get_member->phone);
                Session::put("member_city_id", $get_member->city_id);
                Session::put("member_city", $get_member->city);
                Session::put("member_address", $get_member->address);

                if (Input::get('redirect') != "") {
                    return redirect(Input::get('redirect'));
                } else {
                    return redirect('member/dashboard');
                }
            } else {
                return redirect()->back()->withInput()->with('error', 'Wrong Email/Phone Number or Password. Please try again.');
            }
        } else {
            return redirect()->back()->withInput()->with('error', 'Wrong Email/Phone Number or Password. Please try again.');
        }
    }

    public function register()
    {
        return view('web.member.register');
    }

    public function register_process(Request $request)
    {

        $rules['name'] = "required";
        $rules['phone'] = "required";
        $rules['password'] = "required|min:6|confirmed";

        if (Input::get('email') != "") {
            $rules['email'] = "email";
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $check = Members::where('phone', str_replace(" ", "", Input::get('phone')))->first();
            if (!empty($check)) {
                return redirect()->back()->withInput()->with('error', 'Akun Dengan No Telp ' . str_replace(" ", "", Input::get('phone')) . ' Sudah Terdaftar.');
            } else {
                $member['name'] = Input::get('name');
                $member['email'] = Input::get('email');
                $member['phone'] = str_replace(" ", "", Input::get('phone'));
                $member['password'] = Hash::make(Input::get('password'));
                $member['status'] = 1;
                $member['created_at'] = date("Y-m-d H:i:s");

                $create = Members::insertGetId($member);
                if ($create) {
                    Session::put("member_id", $create);
                    Session::put("member_email", $member['email']);
                    Session::put("member_name", $member['name']);
                    Session::put("member_phone", $member['phone']);

                    return redirect('member/dashboard')->with("success", "Thank you. Your registrasion is successfull.");
                }
            }
        }
    }

    public function dashboard()
    {
        if (!Session::has('member_id')) {
            return redirect('member/login');
        } else {

            $products = Reg_warranty::select('reg_warranty.*','pd.base_point','rating.star', 'rating.review')
                ->join('ms_products AS pd', 'pd.product_id', '=', 'reg_warranty.product_id')
                ->leftJoin('rating', function ($join) {
                    $join->on('rating.productID', '=', 'pd.product_id');
                    $join->on('rating.memberID', '=', 'reg_warranty.member_id');
                })
                ->where("reg_warranty.member_id", Session::get('member_id'))
                ->where('reg_warranty.status', '1')
                ->orderBy('reg_warranty.created_at', 'desc')->get();

            $complete_profile = true;
            $member = DB::table("ms_members")
                ->select("id", "name", "gender", "birth_date", "profile_image", "email", "phone", "address", "city_id", "city", "title_gender", "ktp", "testimony")
                ->where("id", Session::get('member_id'))
                ->first();

            $validator = Validator::make((array) $member, [
                'name' => 'required',
                'gender' => 'required',
                'birth_date' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'city_id' => 'required',
                // 'city' => 'required',
                'title_gender' => 'required',
                'ktp' => 'required',
            ]);

            if ($validator->fails()) {
                $complete_profile = false;
            }

            return view('web.member.dashboard', [
                "products" => $products,
                "complete_profile" => $complete_profile
            ]);
        }
    }

    public function warranty($warranty_no)
    {
        if (!Session::has('member_id')) {
            return redirect('member/login');
        } else {
            $warranty = Reg_warranty::where("warranty_no", $warranty_no)->first();
            $warranty_list = Reg_warranty_details::where("warranty_reg_id", $warranty->warranty_id)->get();
            $product = Products::where("product_id", $warranty->product_id)->first();
            $product_spec = Products_spec::where("product_id", $warranty->product_id)->where("spec_group", "Specifications")->get();

            return view('web.member.warranty', [
                "warranty" => $warranty,
                "warranty_list" => $warranty_list,
                "product" => $product,
                "product_spec" => $product_spec,
            ]);
        }
    }

    public function profile()
    {
        if (!Session::has('member_id')) {
            return redirect('member/login');
        } else {
            $complete_profile = true;
            $sum_point = DB::table('member_point_adjustment')
                ->select(DB::raw("SUM(value) AS total"))
                ->where('member_id', Session::get('member_id'))
                ->first();
            $data["total_points"] = 0;
            if ($sum_point != null) {
                $data["total_points"] = $sum_point->total;
            }
            $member = DB::table("ms_members")
                ->select("id", "name", "gender", "birth_date", "profile_image", "email", "phone", "address", "city_id", "city", "title_gender", "ktp", "testimony")
                ->where("id", Session::get('member_id'))->first();
            $data["member"] = $member;

            $validator = Validator::make((array) $member, [
                'name' => 'required',
                'gender' => 'required',
                'birth_date' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'city_id' => 'required',
                // 'city' => 'required',
                'title_gender' => 'required',
                'ktp' => 'required',
            ]);

            if ($validator->fails()) {
                $complete_profile = false;
            }

            $data['complete_profile'] = $complete_profile;

            return view('web.member.profile', $data);
        }
    }

    public function update_profile(Request $request)
    {
        if (!Session::has('member_id')) {
            return redirect('member/login');
        } else {

            $city_id = Input::get('city');
            $city_name = '';

            if (!empty($city_id)) {
                $city = DB::table('ms_loc_city')->select(DB::raw("CONCAT(ms_loc_province.province_name,' - ', ms_loc_city.city_name) AS city_name"))
                    ->join('ms_loc_province', 'ms_loc_province.province_id', '=', 'ms_loc_city.province_id')
                    ->where('city_id', $city_id)->first();
                if ($city) {
                    $city_name = $city->city_name;
                }
            }

            $data = [
                'name' => Input::get('name'),
                'gender' => Input::get('gender'),
                'title_gender' => Input::get('title_gender'),
                'birth_date' => date('Y-m-d', strtotime(Input::get('birth_date'))),
                'phone' => Input::get('phone'),
                'email' => Input::get('email'),
                'address' => Input::get('address'),
                'city_id' => Input::get('city'),
                'city' => $city_name,
                'profile_image' => Input::get('profile_image'),
                'ktp' => Input::get('ktp'),
                'testimony' => Input::get('testimony'),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Session::get('member_id')
            ];
            DB::table('ms_members')->where('id', Input::get('id'))->update($data);
            return redirect('member/profile');
        }
    }

    public function claim_point($warranty_id)
    {
        $data_warranty = DB::table('reg_warranty')->where('warranty_id', $warranty_id)->first();
        $product = DB::table("ms_products")
            ->select('base_point', 'product_name')
            ->where('product_id', $data_warranty->product_id)
            ->first();

        if (!$product) {
            return [
                'success' => false,
                'message' => 'Data Product tidak ditemukan!'
            ];
        }

        $member_point_exist = DB::table('member_point')
            ->select('id')
            ->where('member_id', $data_warranty->member_id)
            ->where('warranty_id', $data_warranty->warranty_id)
            ->where('type', 'first')
            ->where('status', '!=', 'rejected')
            ->first();
        if ($member_point_exist != null) {
            return [
                'success' => false,
                'message' => 'Member Point already exist!'
            ];
        }

        if (($product->base_point ?? 0) == 0) {
            return [
                'success' => false,
                'message' => 'Base Point product "' . $product->product_name . '" tidak boleh 0...!'
            ];
        }

        $expired_point = date('Y-m-d', strtotime('+2 years -1 days', strtotime($data_warranty->purchase_date)));
        if ($data_warranty->purchase_date >= date('Y-m-d', strtotime('2020-07-01')) && $expired_point <= date('Y-m-d', strtotime('2024-12-31'))) {
            $expired_point = date('Y-m-d', strtotime('2024-12-31'));
        }

        $point_description = 'Request Claim Point Warranty ' . $data_warranty->warranty_no . ' expired at ' . date("d-M-Y", strtotime($expired_point));
        $member_point = [
            'member_id' => $data_warranty->member_id,
            'warranty_id' => $data_warranty->warranty_id,
            'description' => $point_description,
            'expired_at' => $expired_point,
            'value' => $product->base_point,
            'type' => 'first',
            'used' => 0,
            'status' => 'waiting',
            'balance' => $product->base_point,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Session::get('member_id'),
        ];
        $member_point_id = DB::table('member_point')->insertGetId($member_point);

        return [
            'success' => true
        ];
    }

    public function service(Request $request)
    {
        if (!Session::has('member_id')) {
            return redirect('member/login');
        } else {

            if ($request->segment(3) == "history") {
                $service_list = Reg_service::where('member_id', Session::get('member_id'))->where('status', 1)->orderBy('created_at', 'desc')->get();
            } else {
                $service_list = Reg_service::where('member_id', Session::get('member_id'))->where('status', 0)->orderBy('created_at', 'desc')->get();
            }

            return view('web.member.services', [
                "service_list" => $service_list
            ]);
        }
    }

    public function point(Request $request)
    {

        if (!Session::has('member_id')) {
            return redirect('member/login');
        }

        $data = [];
        $history = false;

        $sum_point_in = DB::table('member_point_adjustment')
            ->select(DB::raw("SUM(value) AS total"))
            ->where('member_id', Session::get('member_id'))
            ->where('type', 'in')
            ->first();

        $sum_point_out = DB::table('member_point_adjustment')
            ->select(DB::raw("SUM(value) AS total"))
            ->where('member_id', Session::get('member_id'))
            ->where('type', 'out')
            ->first();

        $data["total_points_in"] = $sum_point_in->total ?? 0;
        $data["total_points_out"] = $sum_point_out->total ?? 0;

        // if ($request->segment(3) == '') {
        //     $section = 'point';

        //     $data['points'] = DB::table('member_point AS mp')
        //         ->select('mp.id', 'mp.member_id', 'mp.warranty_id', 'rw.warranty_no', 'rw.serial_no', 'pd.product_id', 'pd.product_name', 'pd.product_image',
        //             DB::raw('IF(DATEDIFF(mp.expired_at, NOW()) < 0, "expired", "ready" ) AS status'), 'mp.expired_at', 'mp.value AS point', 'mp.created_at'
        //         )
        //         ->join('reg_warranty AS rw', 'rw.warranty_id', '=', 'mp.warranty_id')
        //         ->join('ms_products AS pd', 'pd.product_id', '=', 'rw.product_id')
        //         ->where('mp.member_id', Session::get('member_id'))
        //         ->orderBy('mp.expired_at', 'DESC')
        //         ->get();
        // } else if ($request->segment(3) == 'detail') {

            $section = 'pointadj';

            $warranty_id = $request->segment(4) ?? '';
            $pointsQB = DB::table('member_point_adjustment AS mpa')
                ->select('mpa.id', 'mpa.member_id', 'mpa.warranty_id', 'mpa.description','mpa.trx_date', 'mpa.type', 'mpa.value AS point')
                // ->leftJoin('member_point AS mp', 'mp.id', '=', 'mpa.point_id')
                ->where('mpa.member_id', Session::get('member_id'));

            // if ($warranty_id != '') {
            //     $pointsQB->where('mpa.warranty_id', $warranty_id);
            // }

            $data['points'] = $pointsQB->orderBy('mpa.trx_date', 'DESC')->get();

            // $productQB = DB::table('reg_warranty AS rw')
            //     ->select('rw.warranty_no', 'rw.serial_no', 'pd.product_id', 'pd.product_name', 'pd.product_image')
            //     ->join('ms_products AS pd', 'pd.product_id', '=', 'rw.product_id')
            //     ->where('rw.member_id', Session::get('member_id'));

            // if ($warranty_id != '') {
            //     $productQB->where('rw.warranty_id', $warranty_id);
            // }

            // $data['product'] = $productQB->first();
        // }

        return view('web.member.' . $section, $data);
    }

    public function service_request($warranty_id)
    {
        if (!Session::has('member_id')) {
            return redirect('member/login');
        } else {
            $warranty = Reg_warranty::where('warranty_no', $warranty_id)->where('member_id', Session::get('member_id'))->first();
            if (!isset($warranty->warranty_id)) {
                return redirect('member/dashboard');
            } else {
                $product = Products::where('product_id', $warranty->product_id)->first();
                $service_time = DB::table('ms_service_time')->orderBy('ordering')->get();

                return view('web.member.services-request', [
                    "warranty" => $warranty,
                    "product" => $product,
                    "service_time" => $service_time
                ]);
            }
        }
    }

    public function tradein($warranty_no)
    {
        if (!Session::has('member_id')) {
            return redirect('member/login');
        } else {
            $warranty = Reg_warranty::where('warranty_no', $warranty_no)->where('member_id', Session::get('member_id'))->first();
            if (!isset($warranty->warranty_id)) {
                return redirect('member/dashboard');
            } else {
                $product = Products::where('product_id', $warranty->product_id)->first();
                $service_time = DB::table('ms_service_time')->orderBy('ordering')->get();

                return view('web.member.tradein', [
                    "warranty" => $warranty,
                    "product" => $product,
                    "service_time" => $service_time
                ]);
            }
        }
    }

    public function tradein_process(Request $request)
    {
        $file = Input::file('foto_ktp');
        $upload_loc_ktp = 'sys_uploads/tradein/foto_ktp/';
        $foto_ktp = date("YmdHis-") . Input::get('serial_no') . "." . $file->getClientOriginalExtension();
        $file->move($upload_loc_ktp, $foto_ktp);

        $file = Input::file('foto_barang');
        $upload_loc = 'sys_uploads/tradein/foto_barang/';
        $foto_barang = date("YmdHis-") . Input::get('serial_no') . "." . $file->getClientOriginalExtension();
        $file->move($upload_loc, $foto_barang);


        $date = date('Y-m-d');
        $periode_trade_in = DB::table('tradein_periode')->whereDate('startDate', '<=', $date)->whereDate('endDate', '>=', $date)->first();


        $tradein['warranty_id'] = Input::get('warranty_id');
        $tradein['warranty_no'] = Input::get('warranty_no');
        $tradein['trade_periode'] = $periode_trade_in->id;
        $tradein['ktp'] = Input::get('ktp');
        $tradein['nama_bank'] = Input::get('nama_bank');
        $tradein['no_rekening'] = Input::get('no_rekening');
        $tradein['atas_nama_rekening'] = Input::get('atas_nama_rekening');
        $tradein['foto_ktp'] = url($upload_loc_ktp . $foto_ktp);
        $tradein['foto_barang'] = url($upload_loc . $foto_barang);
        $tradein['created_at'] = date('Y-m-d H:i:s');
        $tradein['updated_at'] = date('Y-m-d H:i:s');

        DB::table('tradein')->insert($tradein);

        return redirect('member/tradein-success?warranty=' . Input::get('warranty_no'));
    }

    public function tradein_success()
    {
        return view('web.member.tradein_success');
    }


    public function cashback($warranty_no)
    {
        if (!Session::has('member_id')) {
            return redirect('member/login');
        } else {
            $warranty = Reg_warranty::where('warranty_no', $warranty_no)->where('member_id', Session::get('member_id'))->first();
            if (!isset($warranty->warranty_id)) {
                return redirect('member/dashboard');
            } else {
                $product = Products::where('product_id', $warranty->product_id)->first();
                $service_time = DB::table('ms_service_time')->orderBy('ordering')->get();

                return view('web.member.cashback', [
                    "warranty" => $warranty,
                    "product" => $product,
                    "service_time" => $service_time
                ]);
            }
        }
    }

    public function cashback_process(Request $request)
    {
        $file = Input::file('foto_ktp');
        $upload_loc_ktp = 'sys_uploads/cashback/foto_ktp/';
        $foto_ktp = date("YmdHis-") . Input::get('serial_no') . "." . $file->getClientOriginalExtension();
        $file->move($upload_loc_ktp, $foto_ktp);

        $file = Input::file('foto_barang');
        $upload_loc = 'sys_uploads/cashback/foto_barang/';
        $foto_barang = date("YmdHis-") . Input::get('serial_no') . "." . $file->getClientOriginalExtension();
        $file->move($upload_loc, $foto_barang);

        $foto_sertifikat_vaksin = null;
        // if (!empty(Input::file('foto_sertifikat_vaksin'))) {
        //     $file = Input::file('foto_sertifikat_vaksin');
        //     $upload_loc = 'sys_uploads/cashback/foto_sertifikat_vaksin/';
        //     $foto_sertifikat_vaksin = date("YmdHis-") . Input::get('serial_no') . "." . $file->getClientOriginalExtension();
        //     $file->move($upload_loc, $foto_sertifikat_vaksin);
        //     $foto_sertifikat_vaksin = url($upload_loc . $foto_sertifikat_vaksin);
        // }

        $prod = DB::table('reg_warranty')->where('warranty_id', Input::get('warranty_id'))->first();

        $date = date('Y-m-d', strtotime($prod->purchase_date));
        // $periode_cashback = DB::table('cashback_periode')->whereDate('startDate', '<=', $date)->whereDate('endDate', '>=', $date)->first();

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

        $cashback_periode_id = null;

        if (!empty($periode_cashback)) {
            foreach ($periode_cashback as $k_pc => $v_pc) {
                if ($v_pc->type_cashback == '1') {
                    $cashback_periode_id = $v_pc->id;
                    break;
                } elseif ($v_pc->type_cashback == '2') {
                    $product_combine = DB::table('cashback_combine')->where('cashback_periode', $v_pc->id)->get();
                    $check_combine = DB::table('reg_warranty')->where('member_id', $prod->member_id)->where('purchase_date', $prod->purchase_date)->get();
                    if (!empty($product_combine) && !empty($check_combine)) {
                        foreach ($product_combine as $k_p_com => $v_p_com) {
                            foreach ($check_combine as $k_cc => $v_cc) {
                                if ($v_p_com->product_id == $v_cc->product_id) {
                                    $cashback_periode_id = $v_pc->id;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

        $cashback['warranty_id'] = Input::get('warranty_id');
        $cashback['warranty_no'] = Input::get('warranty_no');
        $cashback['cashback_periode'] = $cashback_periode_id;
        $cashback['ktp'] = Input::get('ktp');
        $cashback['nama_bank'] = Input::get('nama_bank');
        $cashback['no_rekening'] = Input::get('no_rekening');
        $cashback['atas_nama_rekening'] = Input::get('atas_nama_rekening');
        $cashback['kota_tempat_rekening_dibuka'] = Input::get('kota_tempat_rekening_dibuka');
        $cashback['foto_ktp'] = url($upload_loc_ktp . $foto_ktp);
        $cashback['foto_barang'] = url($upload_loc . $foto_barang);
        $cashback['foto_sertifikat_vaksin'] = $foto_sertifikat_vaksin;
        $cashback['created_at'] = date('Y-m-d H:i:s');
        $cashback['updated_at'] = date('Y-m-d H:i:s');

        DB::table('cashback')->insert($cashback);

        return redirect('member/cashback-success?warranty=' . Input::get('warranty_no'));
    }

    public function cashback_success()
    {
        return view('web.member.cashback_success');
    }



    public function specialvoucher($warranty_no)
    {
        if (!Session::has('member_id')) {
            return redirect('member/login');
        } else {
            $warranty = Reg_warranty::where('warranty_no', $warranty_no)->where('member_id', Session::get('member_id'))->first();
            if (!isset($warranty->warranty_id)) {
                return redirect('member/dashboard');
            } else {
                $products = Products::where("status", 1)->where('display', '1')->get();
                $product = Products::where('product_id', $warranty->product_id)->first();
                $service_time = DB::table('ms_service_time')->orderBy('ordering')->get();

                return view('web.member.specialvoucher', [
                    "warranty" => $warranty,
                    "product" => $product,
                    "products" => $products,
                    "service_time" => $service_time
                ]);
            }
        }
    }

    public function service_request_process($warranty_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'problem_category' => 'required',
            'problem' => 'required',
            'prefered_date' => 'required',
            'prefered_time' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $warranty_id = Input::get('warranty_id');
            $warranty = Reg_warranty::where('warranty_id', $warranty_id)->first();
            if (!isset($warranty->warranty_id)) {
                return redirect('member/dashboard');
            } else {

                $period = date("Ym");

                $check_service = Reg_service::where("service_no", "LIKE", "ARN" . $period . "%")->orderBy('service_id', 'desc')->first();

                if (!isset($check_service->service_id)) {
                    $service_no = "ARN" . $period . str_pad(1, 4, "0", STR_PAD_LEFT);
                } else {
                    $last_service_no = $check_service->service_no;
                    $last_service_no = substr($last_service_no, -4, 4);
                    $last_service_no = (int)$last_service_no;
                    $service_no = "ARN" . $period . str_pad($last_service_no + 1, 4, "0", STR_PAD_LEFT);
                }

                $city = DB::table('ms_loc_city')->where('city_id', Input::get('city'))->first();

                $service['svc_type'] = 'enduser';
                $service['service_no'] = $service_no;
                $service['service_type'] = 1;
                $service['warranty_id'] = $warranty_id;
                $service['product_id'] = $warranty->product_id;
                $service['member_id'] = Session::get('member_id');
                $service['problem_category'] = Input::get('problem_category');
                $service['prefered_date'] = date('Y-m-d', strtotime(Input::get('prefered_date')));
                $service['prefered_time'] = Input::get('prefered_time');
                $service['problem_notes'] = Input::get('problem');
                $service['contact_name'] = Input::get('name');
                $service['contact_phone'] = Input::get('phone');
                $service['service_address'] = Input::get('address');
                $service['service_city_id'] = Input::get('city');
                $service['service_city'] = $city->city_name;
                $service['sc_id'] = $city->sc_id;
                $service['status'] = 0;
                $service['created_at'] = date("Y-m-d H:i:s");
                $service['created_by'] = Session::get('member_id');

                $insert_request = Reg_service::insertGetId($service);

                if ($insert_request) {
                    $progress['service_id'] = $insert_request;
                    $progress['status'] = 2;
                    $progress['update_time'] = date("Y-m-d H:i:s");
                    $progress['info'] = "Permintaan servis diterima.";
                    $progress['created_at'] = date('Y-m-d H:i:s');
                    $progress['created_by'] = Session::get('member_id');

                    $insert_progress = Reg_service_progress::insert($progress);
                }
                return redirect('member/service');
            }
        }
    }

    public function service_details($service_no)
    {
        $service = Reg_service::where('service_no', $service_no)->first();
        $product = Products::where('product_id', $service->product_id)->first();
        $warranty = Reg_warranty::where('warranty_id', $service->warranty_id)->first();

        if (!isset($service->service_id)) {
            return redirect('member/service');
        } else {
            $progress = Reg_service_progress::select('update_time')->where('service_id', $service->service_id)->orderBy('created_at', 'desc')->orderBy('status', 'asc')->groupBy('update_time')->get();

            return view('web.member.services-details', [
                "service" => $service,
                "progress" => $progress,
                "product" => $product,
                "warranty" => $warranty,
            ]);
        }
    }

    public function logout()
    {
        Session::forget("member_id");
        Session::forget("member_email");
        Session::forget("member_name");
        Session::forget("member_phone");
        Session::forget("member_city_id");
        Session::forget("member_city");
        Session::forget("member_address");

        return redirect('member/login');
    }

    public function reset_password($reset_token="")
    {

        $data["reset_token"] = $reset_token;
        $data["email"] = "";

        if (!Session::has("success_reset") && isset($reset_token) && $reset_token !== "") {
            $reset = DB::table("reset_member_password")->where("reset_token", $reset_token)->first();
            if (!$reset) {
                $data["error"] ="Token tidak ditemukan.";
            } else if ($reset->status !== "active") {
                $data["error"] ="Token kadaluarsa atau sudah digunakan.";
            } else {
                $data["email"] = $reset->email;
            }
        }

        return view("web.member.reset-password", $data);
    }

    public function reset_password_process(Request $request)
    {

        $email = str_replace(" ", "", Input::get('email'));
        $new_password = str_replace(" ", "", Input::get('new_password'));
        $reset_token = str_replace(" ", "", Input::get('reset_token'));

        if ($reset_token === "") {
            $rules['email'] = "required";
        } else {
            $rules['new_password'] = "required|min:6|confirmed";
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $get_member = Members::where("status", 1)->where("email", $email)->first(["id", "name"]);

            if (!isset($get_member->id)) {
                return redirect()->back()->withInput()->with('error', 'Email belum terdaftar sebagai member.');
            }

            if ($reset_token === "") {

                $reset_token = strtoupper(base64_encode($email . time()));
                $data = [
                    "email" => $email,
                    "name" => $get_member->name,
                    "reset_url" => url('member/reset-password/' . $reset_token)
                ];
                Mail::to($email)->send(new ResetPasswordMail($data));

                $reset["member_id"] = $get_member->id;
                $reset["reset_token"] = $reset_token;
                $reset["email"] = $email;
                $reset["status"] = "active";

                DB::table("reset_member_password")->insert($reset);

                return view("web.member.reset-password", [
                    "email" => $email,
                    "reset_token" => "",
                    "sent_email" => true
                ]);
   
            } else {

                $reset = DB::table("reset_member_password")
                ->where("reset_token", $reset_token)
                ->where("status", "active")
                ->first();

                $member["password"] = Hash::make($new_password);
                DB::table("ms_members")->where("id", $reset->member_id)->update($member);
                DB::table("reset_member_password")->where("id", $reset->id)->update(["status" => "used"]);

                return redirect()->back()->with("success_reset", "true");

                // return view("web.member.reset-password", [
                //     "reset_token" => $reset_token,
                //     "email" => $email,
                //     "hasreset" => true
                // ]);

            }

        }

    }

    public function submit_testimony(Request $request)
    {
        if (Input::get('testimony') == "") {
            return [
                'success' => false,
                'message' => 'Testimony harus diisi!'
            ];
        }

        if (Input::get('warranty_id') != "") {

            DB::table("reg_warranty")
                ->where("warranty_id", Input::get('warranty_id'))
                ->update([ "testimony" => Input::get('testimony') ]);

            $prev_rating = DB::table("rating")
                ->select('id')
                ->where('productID', Input::get('product_id'))
                ->where('memberID', Session::get('member_id'))
                ->first();

            if ($prev_rating == null) {
                DB::table("rating")->insert([
                    'productID' => Input::get('product_id'),
                    'star' => Input::get('star'),
                    'review' => Input::get('testimony'),
                    'memberID' => Session::get('member_id'),
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            } else {
                DB::table("rating")
                ->where('productID', Input::get('product_id'))
                ->where('memberID', Session::get('member_id'))
                ->update([
                    'star' => Input::get('star'),
                    'review' => Input::get('testimony'),
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
            }
        } else {
            DB::table("ms_members")->where("id", Input::get('member_id'))->update([ 'testimony' => Input::get('testimony') ]);
        }

        return [
            'success' => true,
            'message' => 'Testimony berhasil disimpan.'
        ];
    }

}
