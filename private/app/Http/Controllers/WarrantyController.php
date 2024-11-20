<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use DB;
use Session;
use Hash;
use Auth;
use App\Data;
use App\Products;
use App\Products_spec;
use App\Products_serial;
use App\Products_warranty;
use App\Reg_warranty;
use App\Reg_warranty_details;
use App\Reg_service;
use App\Reg_service_progress;

use EmailHelper;


class WarrantyController extends Controller
{
    public function index()
    {

        $data['content'] = Data::where('code', 'warranty-content')->first();
        $data['image'] = Data::where('code', 'warranty-image')->first();

        return view('web.warranty', $data);
    }

    public function register()
    {

        // $products = Products::get();

        $products = [];
        $product_image = "";
        $code = null;
        $serial_no = null;
        $get_product = null;
        $product = null;
        $selected_product = '';
        $product_name = '';

        if (isset($_GET['code'])) {
            // $code = $_GET['code'];
            $code = str_replace(' ', '', $_GET['code']);

            $q_serial = isset($_GET['serial']) ? $_GET['serial'] : '';
            $serial_no = str_replace(' ', '', $q_serial);
            $length_serial = strlen($serial_no);
            $prefix = strtoupper(substr($serial_no, 0, 4));
            $number = strtoupper(substr($serial_no, 4, 4));
            $postfix = strtoupper(substr($serial_no, 8, 6));

            if (strtoupper($code) == 'SV1102' && $prefix == 'ADAK' && $length_serial > 14) {
                // $serial_no = str_replace(' ', '', $serial_no);
                $prefix = strtoupper(substr($serial_no, 0, 4));
                $number = strtoupper(substr($serial_no, 8, 4));
                $postfix = strtoupper(substr($serial_no, 12, 6));
                $serial_no = $prefix . $number . $postfix;
            }

            if (strtoupper($code) == 'SV30') {
                if ($prefix == 'AGAG') {
                    $code = 'SV130';
                } elseif ($prefix == 'AGAH') {
                    $code = 'SV150';
                }
            }


            if ($prefix == 'ARAA' && $postfix == '261D21') {
                if ($number <= 275) {
                    $code = 'CF101A';
                }
            }

            if ($prefix == 'AFAB') {
                $code = 'CF101BW';
            } elseif ($prefix == 'AFAA') {
                $code = 'CF101AW';
            } else if ($prefix == 'AFAM') {
                $code = 'CF351AW';
            } else if ($prefix == 'AFAG' && $postfix == '008E23') {
                $code = 'CF101CW';
            } else if ($prefix == 'ADAU' && $postfix == '010J23') {
                $code = 'SV531';
            } else if ($prefix == 'AEDA') {
                $code = 'AD77';
            }

            $get_product = Products::where(DB::raw("REPLACE(product_code, ' ', '')"), 'LIKE', $code)->orderBy('product_code', 'ASC')->first();
            if ($get_product) {
                if (isset($get_product->product_image)) {
                    $product_image = $get_product->product_image;
                }
                $selected_product = $get_product->product_id;
                $products = Products::select('product_id', 'product_code', 'default_code', 'product_name', 'product_name_odoo')->where('product_id', $selected_product)->get();
            }

            // if (!empty(Auth::user())) {
            //     // if (!empty($code) && !empty($serial_no)) {
            //     if (!empty($code) || !empty($serial_no)) { 
            //         return redirect('artmin/warranty/add-warranty?code=' . $code . '&serial=' . $serial_no);
            //     }
            // }
        }

        $spg = false;

        if (!empty(Auth::user())) {
            if (Auth::user()->roles == '5') {
                $spg = true;
            }
        }

        $serial_no = str_replace(" ", "", Input::get('serial') ?? '');
        if ($serial_no) {
            $check_serial = Products_serial::select('product_id')->where(DB::raw("REPLACE(serial_no, ' ', '')"), $serial_no)->first();
            // if (!isset($check_serial->product_id)) {
            //     return redirect()->back()->with("error", "Serial number not found.");
            // }
            if ($check_serial) {
                $selected_product = old('product_id', $check_serial->product_id);
                $products = Products::select('product_id', 'product_code', 'default_code', 'product_name', 'product_name_odoo')->where('product_id', $selected_product)->get();
            }
        }

        $selected_product = !empty($selected_product) ? $selected_product : old('product_id');

        if (!empty($selected_product)) {
            $prod = DB::table('ms_products')->select('product_name_odoo')->where('product_id', $selected_product)->first();
            $product_name = $prod ? $prod->product_name_odoo : '';
        }

        if ($spg) {
            $member = []; //DB::table('ms_members')->where('status', '1')->get();
            return view('web.warranty-register-spg', [
                "products" => $products,
                'product_code' => $code,
                'serial_no' => str_replace(' ','',$serial_no),
                "product_image" => $product_image,
                'member' => $member,
                'product' => $get_product,
                'selected_product' => $selected_product,
                'product_id' => $selected_product,
                'product_name' => $product_name
            ]);
        } else {
            return view('web.warranty-register', [
                "products" => $products,
                'product_code' => $code,
                'serial_no' => str_replace(' ','',$serial_no),
                "product_image" => $product_image,
                'product' => $get_product,
                'selected_product' => $selected_product,
                'product_id' => $selected_product,
                'product_name' => $product_name
            ]);
        }
    }


    public function register_progress(Request $request)
    {

        $rules = array(
            'product_id' => 'required',
            'serial_no' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'purchase_date' => 'required',
            'purchase_loc' => 'required',
            'purchase_invoice' => 'required',
            'terms_check' => 'required',
            // 'stock_type' => 'required',
        );

        if (Input::get('email') != '') {
            $rules['email'] = 'email';
        }

        if (!Session::has('member_id')) {
            $type_reg_cus = 'new';
            $rules['password'] = "required|min:6|confirmed";
        } else {
            $type_reg_cus = 'exist';
            $member_id = Session::has('member_id');
        }

        $product_id = Input::get('product_id');

        if ($type_reg_cus == 'new') {
            $exist_member = DB::table('ms_members')->select('id', 'phone', 'name', 'city_id', 'city', 'address', 'email')
                ->where('status', 1)
                ->where('phone', Input::get('phone'))
                ->whereNotNull('name')
                ->whereNotNull('phone')
                ->first();

            if ($exist_member) {
                $match_name = strtoupper(Input::get('name'))==strtoupper($exist_member->name);
                if ($match_name) {
                    $message = 'Nomor Telpon anda ' . $exist_member->phone . ' telah terdaftar, silahkan login terlebih dahulu.';
                } else {
                    $message = 'Nomor Telpon ' . $exist_member->phone . ' telah digunakan oleh member lain.';
                }
                return redirect()->back()->withErrors(['duplicate_phone' => $message])->withInput();
            }
        } else {
            // Jika member sudah login cek nomor telpon jika beda dan nomor sudah di pakai member lain tidak bisa lanjut dan harus menghubungi admin
            $exist_member = DB::table('ms_members')->select('id', 'phone', 'name', 'city_id', 'city', 'address', 'email')
                ->where('id', $member_id)->first();
            if ($exist_member) {
                $request->merge([
                    'name' => $exist_member->name,
                    'address' => $exist_member->address,
                    'city' => $exist_member->city_id,
                    'email' => $exist_member->email,
                ]);
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            // Check Serial Number
            $serial_no = Input::get('serial_no');
            $serial_no = str_replace(" ", "", $serial_no);

            // $get_serial = Products_serial::select(DB::raw("REPLACE(serial_no, ' ', '')"))->first();
            $check_serial = Products_serial::select(['ms_products_serial.*', 'reg_warranty.stock_type'])
                ->leftJoin('reg_warranty', function ($join) {
                    $join->on(DB::raw("REPLACE(reg_warranty.serial_no, ' ', '')"), '=', DB::raw("REPLACE(ms_products_serial.serial_no, ' ', '')"));
                    $join->where('reg_warranty.status', 1);
                })
                ->where(DB::raw("REPLACE(ms_products_serial.serial_no, ' ', '')"), 'LIKE', $serial_no)
                ->where('ms_products_serial.product_id', Input::get('product_id'))
                ->orderBy('created_at', 'DESC')
                ->first();

            if (!isset($check_serial->id)) {
                return redirect()->back()->with("error_serial", "Nomor Serial tidak ditemukan. Mohon periksa kembali nomor serial produk anda atau hubungi kami untuk mendapatkan bantuan lebih lanjut.")->withInput();
            } else {
                if ($check_serial->status == 1 && $check_serial->stock_type == 'stkavailable') {
                    return redirect()->back()->with("error_serial", "Nomor serial sudah terdaftar.")->withInput();
                } else {
                    if (empty(Session::has('member_id'))) {
                        $check_phone = DB::table('reg_warranty')->where('reg_phone', Input::get('phone'))->first();
                        $check_email = DB::table('reg_warranty')->where('reg_email', Input::get('email'))->first();

                        if (!empty($check_phone->reg_phone)) {
                            return redirect()->back()->with("error_phone", "No Telp yang anda masukan telah terdaftar")->withInput();
                        }
                        if (!empty($check_email->reg_email)) {
                            return redirect()->back()->with("error_email", "Email yang anda masukan telah terdaftar")->withInput();
                        }
                    }


                    // Check login account
                    if (!Session::has('member_id')) {
                        $email = Input::get('email');
                        $check_email = DB::table('ms_members')->where("email", "LIKE", Input::get('email'))->first();
                        if (isset($check_email->member_id)) {
                            $member_id = $check_email->member_id;
                        } else {
                            $city = DB::table('ms_loc_city')->where('city_id', Input::get('city'))->first();

                            $member['name'] = Input::get('name');
                            $member['phone'] = Input::get('phone');
                            $member['address'] = Input::get('address');
                            $member['city_id'] = Input::get('city');
                            $member['city'] = $city->city_name;
                            $member['email'] = Input::get('email');
                            $member['password'] = Hash::make(Input::get('password'));
                            $member['status'] = 1;
                            $member['created_at'] = date("Y-m-d H:i:s");

                            $add_member = DB::table('ms_members')->insertGetId($member);
                            $member_id = $add_member;

                            Session::put("member_id", $add_member);
                            Session::put("member_email", $member['email']);
                            Session::put("member_name", $member['name']);
                            Session::put("member_phone", $member['phone']);
                        }
                    }

                    $period = date("Ym");

                    $check_warranty = Reg_warranty::where("warranty_no", "LIKE", $period . "%")->orderBy('warranty_id', 'desc')->first();

                    if (!isset($check_warranty->warranty_id)) {
                        $warranty_no = $period . str_pad(1, 4, "0", STR_PAD_LEFT);
                    } else {
                        $last_warranty_no = $check_warranty->warranty_no;
                        $last_warranty_no = substr($last_warranty_no, -4, 4);
                        $last_warranty_no = (int)$last_warranty_no;
                        $warranty_no = $period . str_pad($last_warranty_no + 1, 4, "0", STR_PAD_LEFT);
                    }

                    $is_unique_number = false;
                    $check_special_voucher = DB::table('unique_number')->where('unique_number', ltrim($check_serial->numbering, '0'))->where('status', '1')->whereDate('date_from', '<=', date('Y-m-d', strtotime(Input::get('purchase_date'))))->whereDate('date_to', '>=', date('Y-m-d', strtotime(Input::get('purchase_date'))))->first();
                    if (!empty($check_special_voucher)) {
                        $check_special_voucher_product = DB::table('unique_number_products')->where('unique_number', $check_special_voucher->id)->where('products_id', Input::get('product_id'))->first();
                        if (!empty($check_special_voucher_product)) {
                            $is_unique_number = true;
                        }
                    }

                    $special_voucher_from = null;
                    if (Session::has('member_id')) {
                        if (!empty($request->input('warranty_id_special_voucher'))) {
                            $special_voucher_from = $request->input('warranty_id_special_voucher');

                            $file = $request->file('foto_ktp');
                            $upload_loc_ktp = 'sys_uploads/special_voucher/foto_ktp/';
                            $foto_ktp = date("YmdHis-") . Input::get('serial_no') . "." . $file->getClientOriginalExtension();
                            $file->move($upload_loc_ktp, $foto_ktp);

                            $file = $request->file('foto_barang');
                            $upload_loc = 'sys_uploads/special_voucher/foto_barang/';
                            $foto_barang = date("YmdHis-") . Input::get('serial_no') . "." . $file->getClientOriginalExtension();
                            $file->move($upload_loc, $foto_barang);

                            $update_special_voucher = [
                                'ktp' => $request->input('ktp'),
                                'nama_bank' => $request->input('nama_bank'),
                                'no_rekening' => $request->input('no_rekening'),
                                'kota_tempat_rekening_dibuka' => $request->input('kota_tempat_rekening_dibuka'),
                                'atas_nama_rekening' => $request->input('atas_nama_rekening'),
                                'foto_ktp' => url($upload_loc_ktp . $foto_ktp),
                                'foto_barang' => url($upload_loc . $foto_barang)
                            ];
                            DB::table('special_voucher')->where('warranty_id', $special_voucher_from)->update($update_special_voucher);
                        }
                        // $data = DB::table('reg_warranty')
                        //     ->where('serial_no', $request->input('special_voucher'))
                        //     ->where('member_id', Session::get('member_id'))
                        //     ->where('special_voucher.status', '1')
                        //     ->join('special_voucher', 'special_voucher.warranty_id', '=', 'reg_warranty.warranty_id')
                        //     ->first();
                        // $special_voucher_from = (!empty($data) ? $data->warranty_id : null);
                    }


                    $file = $request->file('purchase_invoice');
                    $upload_loc = 'sys_uploads/warranty_invoices/';
                    $file_name = date("YmdHis-") . Input::get('serial_no') . "." . $file->getClientOriginalExtension();
                    $file->move($upload_loc, $file_name);

                    $current_time = date("Y-m-d H:i:s");
                    $city = DB::table('ms_loc_city')->where('city_id', Input::get('city'))->first();

                    $warranty['warranty_no'] = $warranty_no;
                    $warranty['member_id'] = Session::get('member_id');
                    $warranty['product_id'] = Input::get('product_id');
                    $warranty['serial_no'] = str_replace(" ", "", Input::get('serial_no'));
                    $warranty['reg_name'] = Input::get('name');
                    $warranty['reg_phone'] = Input::get('phone');
                    $warranty['reg_address'] = Input::get('address');
                    $warranty['reg_city_id'] = Input::get('city');
                    $warranty['reg_city'] = $city->city_name;
                    $warranty['files'] = url($upload_loc . $file_name);
                    $warranty['reg_email'] = Input::get('email');
                    $warranty['purchase_date'] = date('Y-m-d', strtotime(Input::get('purchase_date')));
                    $warranty['online_store'] = Input::get('online_store') == "on" ? 1: 0;
                    $warranty['purchase_loc'] = Input::get($warranty['online_store']==1 ? 'online_store_name' : 'purchase_loc');
                    $warranty['purchase_location_id'] = Input::get('purchase_loc_id');
                    $warranty['created_at'] = $current_time;
                    $warranty['created_by'] = 1;
                    $warranty['unique_number'] = ($is_unique_number ? $check_special_voucher->id : null);
                    $warranty['special_voucher_from'] = $special_voucher_from;
                    $warranty['stock_type'] = Input::get('stock_type') ?? 'stkavailable';

                    $insert_warranty = Reg_warranty::insertGetId($warranty);


                    if (!empty(Input::get('type'))) {
                        if (Input::get('type') == 'tradein') {
                            $file = $request->file('foto_ktp');
                            $upload_loc = 'sys_uploads/tradein/foto_ktp/';
                            $foto_ktp = date("YmdHis-") . Input::get('serial_no') . "." . $file->getClientOriginalExtension();
                            $file->move($upload_loc, $foto_ktp);

                            $file = $request->file('foto_barang');
                            $upload_loc = 'sys_uploads/tradein/foto_barang/';
                            $foto_barang = date("YmdHis-") . Input::get('serial_no') . "." . $file->getClientOriginalExtension();
                            $file->move($upload_loc, $foto_barang);


                            $tradein['warranty_id'] = $insert_warranty;
                            $tradein['warranty_no'] = $warranty_no;
                            $tradein['ktp'] = Input::get('ktp');
                            $tradein['nama_bank'] = Input::get('nama_bank');
                            $tradein['no_rekening'] = Input::get('no_rekening');
                            $tradein['kota_tempat_rekening_dibuka'] = Input::get('kota_tempat_rekening_dibuka');
                            $tradein['atas_nama_rekening'] = Input::get('atas_nama_rekening');
                            $tradein['foto_ktp'] = url($upload_loc . $foto_ktp);
                            $tradein['foto_barang'] = url($upload_loc . $foto_barang);
                            $tradein['created_at'] = date('Y-m-d H:i:s');
                            $tradein['updated_at'] = date('Y-m-d H:i:s');

                            DB::table('tradein')->insert($tradein);
                        }
                    }

                    if ($insert_warranty) {
                        // Insert Warranty Details
                        $list_warranty = Products_warranty::where('product_id', $warranty['product_id'])->get();
                        foreach ($list_warranty as $lw) {
                            $details['warranty_reg_id'] = $insert_warranty;
                            $details['warranty_type'] = $lw->warranty_title;
                            $details['warranty_start'] = date("Y-m-d 00:00:00", strtotime($warranty['purchase_date']));
                            $warranty_year = "+1 year";
                            if ($lw->warranty_year == 1) {
                                $warranty_year = "+ 1 year";
                            } else if ($lw->warranty_year > 1) {
                                $warranty_year = "+ " . $lw->warranty_year . " years";
                            }

                            $details['warranty_end'] = date("Y-m-d 23:59:59", strtotime($warranty['purchase_date'] . " " . $warranty_year));
                            $details['warranty_period'] = $lw->warranty_year;
                            $details['created_at'] = $current_time;
                            $details['created_by'] = Session::get('member_id');

                            $insert_detail = Reg_warranty_details::insert($details);
                        }
                        $update_serial_data['status'] = 1;
                        $update_serial = Products_serial::where('id', $check_serial->id)->update($update_serial_data);

                        if (Input::get('email') != '') {

                            $data['to'] = $warranty['reg_email'];
                            $data['name'] = $warranty['reg_name'];
                            $data['warranty'] = Reg_warranty::where('warranty_id', $insert_warranty)->first();
                            $data['warranty_list'] = Reg_warranty_details::where('warranty_reg_id', $insert_warranty)->get();
                            $data['product'] = Products::where('product_id', $warranty['product_id'])->first();

                            EmailHelper::warranty_registration($data);
                        }

                        if (!empty($check_special_voucher_product)) {
                            $special_voucher = [
                                'warranty_id' => $insert_warranty,
                                'unique_number_id' => $check_special_voucher->id,
                                'status' => 1,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ];
                            DB::table('special_voucher')->insert($special_voucher);

                            $mailSpecialNumber['to'] = $warranty['reg_email'];
                            $mailSpecialNumber['name'] = $warranty['reg_name'];
                            $mailSpecialNumber['warranty_no'] = $warranty_no;
                            $mailSpecialNumber['serial_number'] = str_replace(" ", "", Input::get('serial_no'));
                            $mailSpecialNumber['cashback'] = $check_special_voucher->cashback;
                            EmailHelper::special_voucher($mailSpecialNumber);
                        }

                        return redirect('warranty/registration-success?warranty=' . $warranty_no);
                    }

                    // if(!Se)

                    // print_r($check_phone);
                    // if (!empty($check_phone->reg_phone)) {
                    //     if (!Session::has('member_id')) {
                    //         return redirect()->back()->with("error_phone", "No Telp yang anda masukan telah terdaftar")->withInput();
                    //     }
                    // } else {
                    //     if (!empty($check_email->reg_email)) {
                    //         if (!Session::has('member_id')) {
                    //             return redirect()->back()->with("error_email", "Email yang anda masukan telah terdaftar")->withInput();
                    //         }
                    //     } else {

                    //     }
                    // }
                }
            }
        }
    }

    public function mail_test()
    {
        $data['to'] = 'lambdasangkala45@gmail.com';
        $data['name'] = 'Lambda Sangkala (testmail)';
        $data['warranty'] = Reg_warranty::where('warranty_id', '4')->first();
        $data['warranty_list'] = Reg_warranty_details::where('warranty_reg_id', '4')->get();
        $data['product'] = Products::where('product_id', '5')->first();

        $debug_mail = EmailHelper::warranty_registration($data);
        print_r($debug_mail);
    }

    public function register_success()
    {
        return view('web.warranty-register-success');
    }

    public function get_product()
    {
        if (isset($_GET['product_code'])) {
            $product_code = $_GET['product_code'];

            $product = Products::where("product_id", $product_code)->first();
            if (isset($product->product_id)) {
                echo $product->product_image;
            }
        }
    }

    public function check_warranty($product_code, $serial_number=null)
    {

        if (strtoupper(substr($product_code, 0, 8)) == 'AX6101SB') {
            $serial_number = str_replace('AX6101SB', '', $product_code);
            $product_code = strtoupper(substr($product_code, 0, 8));
        }

        // Tambah Filter stock_type untuk jaga2 jika toko tidak info admin bahwa stok display terjual dan pembeli scan untuk daftar garansi sendiri
        $check = Reg_warranty::where('serial_no', $serial_number)->where('status', 1)->whereNotIn('stock_type', ['stkdisplay', 'stkservice'])->orderBy('created_at', 'DESC')->first();
        if (isset($check->warranty_no)) {
            return redirect('warranty/info/' . $check->warranty_no);
        } else {
            return redirect('warranty/registration?code=' . $product_code . '&serial=' . $serial_number);
        }
    }

    public function warranty_info($warranty_no)
    {
        $warranty = Reg_warranty::where("warranty_no", $warranty_no)->first();
        $warranty_list = Reg_warranty_details::where("warranty_reg_id", $warranty->warranty_id)->get();
        $product = Products::where("product_id", $warranty->product_id)->first();
        $product_spec = Products_spec::where("product_id", $warranty->product_id)->where("spec_group", "Specifications")->get();

        return view('web.warranty-info', [
            "warranty" => $warranty,
            "warranty_list" => $warranty_list,
            "product" => $product,
            "product_spec" => $product_spec,
        ]);
    }

    public function email_layout()
    {
        return view('web.email-layout');
    }

    public function request_installation($warranty_no)
    {

        $data['warranty'] = Reg_warranty::where('warranty_no', $warranty_no)->first();
        $data['product'] = Products::where('product_id', $data['warranty']->product_id)->first();
        $data['service_time'] = DB::table('ms_service_time')->orderBy('ordering')->get();

        return view('web.warranty-request-installation', $data);
    }

    public function request_installation_process($warranty_no, Request $request)
    {
        $validator = Validator::make($request->all(), [
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
            $warranty = Reg_warranty::where('warranty_no', $warranty_no)->first();
            if (!isset($warranty->warranty_id)) {
                return redirect('member/dashboard');
            } else {

                $period = date("Ym");

                $check_service = Reg_service::where("service_no", "LIKE", 'ARN' . $period . "%")->orderBy('service_id', 'desc')->first();


                if (!isset($check_service->service_id)) {
                    $service_no = "ARN" . $period . str_pad(1, 4, "0", STR_PAD_LEFT);
                } else {
                    $last_service_no = $check_service->service_no;
                    $last_service_no = substr($last_service_no, -4, 4);
                    $last_service_no = (int)$last_service_no;
                    $service_no = "ARN" . $period . str_pad($last_service_no + 1, 4, "0", STR_PAD_LEFT);
                }



                $city = DB::table('ms_loc_city')->where('city_id', Input::get('city'))->first();


                $service['service_no'] = $service_no;
                $service['service_type'] = 0;
                $service['warranty_id'] = $warranty->warranty_id;
                $service['product_id'] = $warranty->product_id;
                $service['member_id'] = Session::get('member_id');
                $service['prefered_date'] = date('Y-m-d', strtotime(Input::get('prefered_date')));
                $service['prefered_time'] = Input::get('prefered_time');
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
                    $progress['status'] = 12;
                    $progress['update_time'] = date("Y-m-d H:i:s");
                    $progress['info'] = "Permintaan instalasi diterima.";
                    $progress['created_at'] = date('Y-m-d H:i:s');
                    $progress['created_by'] = Session::get('member_id');

                    $insert_progress = Reg_service_progress::insert($progress);
                }

                return redirect('member/service');
            }
        }
    }

    public function check_serial()
    {
        if (isset($_GET['serial_no']) && isset($_GET['product_id'])) {
            // Check Serial Number
            $product_id = $_GET['product_id'];
            $serial_no = $_GET['serial_no'];
            $serial_no = str_replace(" ", "", $serial_no);

            $check_serial = Products_serial::where(DB::raw("REPLACE(serial_no, ' ', '')"), 'LIKE', $serial_no)->where("product_id", $product_id)->first();
            if (isset($check_serial->id)) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            return 'false';
        }
    }

    public function check_kode_voucher(Request $request)
    {
        $data = null;
        if (Session::has('member_id')) {
            $data = DB::table('reg_warranty')
                ->where('serial_no', $request->input('kode_voucher'))
                ->where('member_id', Session::get('member_id'))
                ->where('special_voucher.status', '1')
                ->join('special_voucher', 'special_voucher.warranty_id', '=', 'reg_warranty.warranty_id')
                ->first();
        }
        $return = [
            'status' => true,
            'data' => $data
        ];

        return json_encode($return);
    }
}
