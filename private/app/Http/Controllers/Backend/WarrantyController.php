<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Arr;
use Hash;
use Auth;
use DB;
use PDF;
use Excel;

use App\Members;
use App\Products;
use App\Products_serial;
use App\Products_warranty;
use App\Reg_warranty;
use App\Reg_warranty_details;

use EmailHelper;
use ProductHelper;

use App\Exports\Export\Excel\Warranty;

class WarrantyController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function index($phoneNumber = null)
    {
        // $data['purchase_period'] = date('Y-m-d');
        $fetchWarranty = Reg_warranty::select(
            'reg_warranty.*',
            'ms_products.product_code',
            'ms_products.product_name_odoo as product_name',
            'users.name as spg_name'
        )
            ->join('ms_products', 'reg_warranty.product_id', '=', 'ms_products.product_id')
            ->leftJoin('users', 'users.id', '=', 'reg_warranty.spg_ref')
            // ->where('reg_warranty.purchase_date', 'like', date('Y-m-') . '%')
            ->orderBy('reg_warranty.created_at', 'desc');


        if (!empty($phoneNumber)) {
            $fetchWarranty->where('reg_warranty.reg_phone', $phoneNumber)->get();
        }

        if (isset($_GET['purchase_date_filter'])) {
            $expd = explode(' ', $_GET['purchase_date_filter']);
            if (!empty($expd[0]) && !empty($expd[2])) {
                $fetchWarranty->whereBetween('reg_warranty.purchase_date', [$expd[0], $expd[2]]);
            }
        } else {
            $fetchWarranty->where('reg_warranty.purchase_date', 'like', date('Y-m-') . '%');
        }

        if (!empty($_GET['registered_date_filter'])) {
            // $fetchWarranty->where('')
            $expd = explode(' ', $_GET['registered_date_filter']);
            if (!empty($expd[0]) && !empty($expd[2])) {
                $fetchWarranty->whereBetween('reg_warranty.created_at', [$expd[0], $expd[2]]);
            }
        }

        if (!empty($_GET['product_id_filter'])) {
            if ($_GET['product_id_filter'] != '-') {
                $fetchWarranty->where('reg_warranty.product_id', $_GET['product_id_filter']);
            }
        }

        if (!empty($_GET['warranty_no_filter'])) {
            if ($_GET['warranty_no_filter'] != '-') {
                $fetchWarranty->where('reg_warranty.warranty_no', $_GET['warranty_no_filter']);
            }
        }

        if (!empty($_GET['serial_no_filter'])) {
            if ($_GET['serial_no_filter'] != '-') {
                $fetchWarranty->where(DB::raw("REPLACE(serial_no, ' ', '')"), 'LIKE', $_GET['serial_no_filter']);
            }
        }

        if (!empty($_GET['member_name_filter'])) {
            if ($_GET['member_name_filter'] != '-') {
                $fetchWarranty->where('reg_warranty.reg_name', 'like', '%' . $_GET['member_name_filter'] . '%');
            }
        }

        if (!empty($_GET['member_phone_filter'])) {
            if ($_GET['member_phone_filter'] != '-') {
                $fetchWarranty->where('reg_warranty.reg_phone', 'like', '%' . $_GET['member_phone_filter'] . '%');
            }
        }


        if (!empty($_GET['promotor_id'])) {
            if ($_GET['promotor_id'] != '-') {
                $fetchWarranty->where('reg_warranty.spg_ref', $_GET['promotor_id']);
            }
        }

        if (!empty($_GET['status_verify'])) {
            if ($_GET['status_verify'] != '-') {
                $sts = $_GET['status_verify'];

                if ($sts == '3') {
                    $sts = '0';
                }

                $fetchWarranty->where('reg_warranty.verified', $sts);
            }
        }

        if (!empty($_GET['status'])) {
            if ($_GET['status'] != '-') {
                $sts = $_GET['status'];

                if ($sts == '2') {
                    $sts = '0';
                }

                $fetchWarranty->where('reg_warranty.status', $sts);
            }
        }

        $data['warranty'] = $fetchWarranty->get();

        $data['products'] = Products::get();

        $data['period'] = (!empty($_GET['purchase_date_filter']) ? $_GET['purchase_date_filter'] : date('Y-m-01') . ' - ' . date('Y-m-d'));
        $data['registered'] = (!empty($_GET['registered_date_filter']) ? $_GET['registered_date_filter'] : null);

        $data['promotor'] = DB::table('users')->where('roles', '2')->orWhere('roles', '3')->orWhere('roles', '5')->get();

        return view('backend.warranty.list', $data);
    }

    public function indexJSON(Request $request)
    {
        $qb = DB::table('reg_warranty AS rw')
            ->select('rw.warranty_id', 'rw.warranty_no', 'rw.product_id', 'mp.product_name', DB::raw('FORMAT(mp.base_point, 2) AS base_point'))
            ->join('ms_products AS mp', 'mp.product_id', '=', 'rw.product_id');

        if ($request->get('member_id')){
            $qb->where("rw.member_id", $request->get('member_id'));
        }

        if ($request->get('warranty_id')){
            $qb->where("rw.warranty_id", $request->get('warranty_id'));
        }

        $keywords = $request->get('q') ?? '';
        if ($keywords) {
            $qb->whereRaw("rw.warranty_no LIKE '%" . $keywords . "%' OR mp.product_name LIKE '%" . $keywords . "%'");
        }

        $qb->where("rw.status", 1);

        return $qb->get();
    }

    public function details($warranty_id)
    {

        $data['warranty'] = Reg_warranty::where('warranty_id', $warranty_id)->first();
        $data['warranty_list'] = Reg_warranty_details::where('warranty_reg_id', $warranty_id)->get();
        $data['product'] = Products::where('product_id', $data['warranty']->product_id)->first();
        $data['product_warranty'] = Products_warranty::where('product_id', $data['warranty']->product_id)->get();

        return view('backend.warranty.details', $data);
    }

    public function add_warranty()
    {

        $data['product_id'] = null;
        $data['product_name'] = '';
        $data['products'] = []; //Products::get();
        $data['city'] = DB::table('ms_loc_city')->orderBy('province_id')->get();
        $data['customer'] = []; //DB::table('ms_members')->where('status', 1)->get();
        $product = null;

        if (isset($_GET['code'])) {
            $sch = Products::where(DB::raw("REPLACE(product_code, ' ', '')"), 'LIKE', $_GET['code'])->first();
            $product = (!empty($sch) ? $sch->product_id : null);
        }

        if (!$product && isset($_GET['serial'])) { // Input::get('serial')) {
            $serial_no = str_replace(" ", "", Input::get('serial'));
            $check_serial = Products_serial::select('product_id')->where(DB::raw("REPLACE(serial_no, ' ', '')"), $serial_no)->first();
            // if (!isset($check_serial->product_id)) {
            //     return redirect()->back()->with("error", "Serial number not found.");
            // }
            if ($check_serial) {
                $product = $check_serial->product_id;
            }
        }

        $data['selected_product'] = $product;

        if (isset($product)) {
            $data['product_id'] = $product;
            $prod = DB::table('ms_products')->select('product_name_odoo')->where('product_id', $product)->first();
            $data['product_name'] = $prod ? $prod->product_name_odoo : '';
        }

        return view('backend.warranty.add-warranty', $data);
    }

    public function add_warranty_process(Request $request)
    {
        $rules = array(
            'product_id' => 'required',
            'serial_no' => 'required',
            'purchase_date' => 'required',
            'purchase_location' => 'required',
            'purchase_invoice' => 'required',
            'stock_type' => 'required',
        );

        $customer_type = Input::get('customer_type');
        $customer_id = Input::get('customer_id');
        $product_id = Input::get('product_id');

        if ($customer_type == 1) {
            $rules['new_cust_name'] = "required";
            $rules['new_cust_phone'] = "required";
            $rules['new_cust_address'] = "required";
            $rules['new_cust_city'] = "required";
        } else if ($customer_type == 2) {
            $rules['customer_id'] = "required";
        }

        $validator = Validator::make($request->all(), $rules);

        if($product_id != '') {
            $exist_product = DB::table('ms_products')->select('product_name_odoo')->where('product_id', $product_id)->first();
            if ($exist_product) {
                $request->merge([
                    'product_name' => $exist_product->product_name_odoo
                ]);
            }
        }

        if ($customer_type == 1) {
            $exist_member = DB::table('ms_members')->select('id', 'phone', 'name', 'city_id', 'city')
                ->where('status', 1)
                ->where('phone', Input::get('new_cust_phone'))
                ->whereNotNull('name')
                ->whereNotNull('phone')
                ->first();
            // dd(Input::get('new_cust_city')==$exist_member->city_id);
            if ($exist_member) { // && !$match_name) {
                $match_name = strtoupper(Input::get('new_cust_name'))==strtoupper($exist_member->name);
                if (Input::get('has_confirm_phone_duplicate') == "0") {
                    
                    if ($match_name) {
                        $message = 'Nomor Telpon ' . $exist_member->phone . ' oleh customer dengan nama ' . Input::get('new_cust_name') . ' sebelumnya telah terdaftar sebagai member.';
                    } else {
                        $message = 'Nomor Telpon ' . $exist_member->phone . ' sudah digunakan oleh member ' . $exist_member->name . ' (' . $exist_member->city . ').';
                    }
                    return redirect()->back()->withErrors(['duplicate_phone' => $message])->withInput();
                } else {
                    $customer_type = "2";
                    $customer_id = $exist_member->id . '';
                    $request->merge([
                        'customer_type' => $customer_type,
                        'customer_id' => $customer_id,
                        'customer_name' => $exist_member->name
                    ]);

                    // $input = $request->except([
                    //     'new_cust_name',
                    // ]);
                }
            }
        } else {
            $exist_member = DB::table('ms_members')->select('name')->where('id', $customer_id)->first();
            if ($exist_member) {
                $request->merge([
                    'customer_name' => $exist_member->name
                ]);
            }
        }

        // $request->session()->save();

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            // Check Serial Number
            $serial_no = Input::get('serial_no');
            $serial_no = str_replace(" ", "", $serial_no);

            // $check_serial = Products_serial::where(DB::raw("REPLACE(serial_no, ' ', '')"), 'LIKE', $serial_no)->where("product_id", Input::get('product_id'))->first();
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
                return redirect()->back()->with("error", "Serial number not found.")->withInput();
            } else {
                if ($check_serial->status == 1 && $check_serial->stock_type == 'stkavailable') {
                    return redirect()->back()->with("error", "Serial Number is already registered.")->withInput();
                } else {
                    if ($customer_type == 1) {
                        $city = DB::table('ms_loc_city')->where('city_id', Input::get('new_cust_city'))->first();

                        $new_member['name'] = Input::get('new_cust_name');
                        $new_member['phone'] = Input::get('new_cust_phone');
                        $new_member['address'] = Input::get('new_cust_address');
                        $new_member['city_id'] = Input::get('new_cust_city');
                        $new_member['city'] = $city->city_name;
                        $new_member['email'] = Input::get('new_cust_email');
                        $new_member['password'] = Hash::make(Input::get('new_cust_password'));
                        $new_member['status'] = 1;
                        $new_member['created_at'] = date("Y-m-d H:i:s");

                        $add_member = DB::table('ms_members')->insertGetId($new_member);
                        $member_id = $add_member;
                    } else if ($customer_type == 2) {
                        $member_id = $customer_id;
                    }

                    $customer = Members::where('id', $member_id)->first();

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
                    $check_special_voucher = DB::table('unique_number')->where('unique_number', ltrim($check_serial->numbering, '0'))->where('status', '1')->whereDate('date_from', '<=', date('Y-m-d'))->whereDate('date_to', '>=', date('Y-m-d'))->first();
                    if (!empty($check_special_voucher)) {
                        $check_special_voucher_product = DB::table('unique_number_products')->where('unique_number', $check_special_voucher->id)->where('products_id', Input::get('product_id'))->first();
                        if (!empty($check_special_voucher_product)) {
                            $is_unique_number = true;
                        }
                    }

                    $file = $request->file('purchase_invoice');
                    $upload_loc = 'sys_uploads/warranty_invoices/';
                    $file_name = date("YmdHis-") . Input::get('serial_no') . "." . $file->getClientOriginalExtension();
                    $file->move($upload_loc, $file_name);

                    $current_time = date("Y-m-d H:i:s");

                    $warranty['warranty_no'] = $warranty_no;
                    $warranty['member_id'] = $member_id;
                    $warranty['product_id'] = Input::get('product_id');
                    $warranty['serial_no'] = str_replace(" ", "", Input::get('serial_no'));
                    $warranty['reg_name'] = $customer->name;
                    $warranty['reg_phone'] = $customer->phone;

                    /**
                     * Modif bisa ganti alamat dan kota dari form jadi tidak selalu alamat member
                     */
                    // $city = DB::table('ms_loc_city')->where('city_id', $customer->city_id)->first();
                    // $warranty['reg_address'] = $customer->address;
                    // $warranty['reg_city_id'] = $customer->city;
                    // $warranty['reg_city'] = ($city->city_name ?? null);
                    $city = DB::table('ms_loc_city')->where('city_id', Input::get('new_cust_city'))->first();
                    $warranty['reg_address'] = Input::get('new_cust_address');
                    $warranty['reg_city_id'] = Input::get('new_cust_city');
                    $warranty['reg_city'] = $city->city_name;

                    $warranty['files'] = url($upload_loc . $file_name);
                    $warranty['reg_email'] = $customer->email;
                    $warranty['purchase_date'] = date('Y-m-d', strtotime(Input::get('purchase_date')));
                    $warranty['purchase_location_id'] = Input::get('purchase_location_id');
                    $warranty['online_store'] = Input::get('online_store') == "on" ? 1: 0;
                    $warranty['purchase_loc'] = Input::get($warranty['online_store']==1 ? 'online_store_name' : 'purchase_location');
                    $warranty['spg_ref'] = Auth::user()->id;
                    $warranty['created_at'] = $current_time;

                    $warranty['unique_number'] = ($is_unique_number ? $check_special_voucher->id : null);
                    $warranty['stock_type'] = Input::get('stock_type') ?? 'stkavailable';

                    // if (Auth::user()->roles == 1) {
                    //     $warranty['reg_type'] = 1;
                    // } else if (Auth::user()->roles == 2) {
                    //     $warranty['reg_type'] = 2;
                    // } else if (Auth::user()->roles == 3) {
                    //     $warranty['reg_type'] = 3;
                    // }
                    $warranty['reg_type'] = Auth::user()->roles;

                    // $warranty['warranty_status'] = 1;
                    $warranty['created_by'] = Auth::user()->id;

                    $insert_warranty = Reg_warranty::insertGetId($warranty);

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
                            $details['created_by'] = Auth::user()->id;

                            $insert_detail = Reg_warranty_details::insert($details);
                        }

                        $update_serial_data['status'] = 1;
                        $update_serial = Products_serial::where('id', $check_serial->id)->update($update_serial_data);
                    }

                    if ($customer->email != '') {
                        $data['to'] = $customer->email;
                        $data['name'] = $customer->name;
                        $data['warranty'] = Reg_warranty::where('warranty_id', $insert_warranty)->first();
                        $data['warranty_list'] = Reg_warranty_details::where('warranty_reg_id', $insert_warranty)->get();
                        $data['product'] = Products::where('product_id', $warranty['product_id'])->first();

                        EmailHelper::warranty_registration($data);

                        if ($is_unique_number) {

                            $special_voucher = [
                                'warranty_id' => $insert_warranty,
                                'unique_number_id' => $check_special_voucher->id,
                                'status' => 1,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ];
                            DB::table('special_voucher')->insert($special_voucher);

                            $mailSpecialNumber['to'] = $customer->email;
                            $mailSpecialNumber['name'] = $customer->name;
                            $mailSpecialNumber['warranty_no'] = $warranty_no;
                            $mailSpecialNumber['serial_number'] = str_replace(" ", "", Input::get('serial_no'));
                            $mailSpecialNumber['cashback'] = $check_special_voucher->cashback;
                            EmailHelper::special_voucher($mailSpecialNumber);
                        }
                    }

                    return redirect('artmin/warranty')->with('success', 'New warranty registration success.');
                }
            }
        }
    }

    public function resend_warranty_email($warranty_id)
    {
        $data['warranty'] = Reg_warranty::where('warranty_id', $warranty_id)->first();
        $data['to'] = $data['warranty']->reg_email;
        $data['name'] = $data['warranty']->reg_name;
        $data['warranty_list'] = Reg_warranty_details::where('warranty_reg_id', $warranty_id)->get();
        $data['product'] = Products::where('product_id', $data['warranty']->product_id)->first();

        // EmailHelper::warranty_registration($data);
    }

    public function verified(Request $request)
    {

        $data_warranty = DB::table('reg_warranty')->where('warranty_id', $request->input('warranty_id'))->first();
        if ($request->input('status') == '2') {
            $update_status = [
                'status' => 0
            ];
            DB::table('ms_products_serial')->where('serial_no', $data_warranty->serial_no)->update($update_status);
        }

        // $data_warranty = DB::table('reg_warranty')->where('warranty_id', $request->input('warranty_id'))->first();

        if (!empty($data_warranty->unique_number)) {

            $un = DB::table('unique_number')->select('cashback')->where('id', $data_warranty->unique_number)->first();

            if (!empty($data_warranty->reg_phone)) {
                $str = $data_warranty->reg_phone;
                $pattern = '/08^ /';
                $format_indo = preg_replace($pattern, '628', $str);
                $phone_nuumber = preg_replace('/\D/', '', $format_indo);
                $content = "Pelanggan yang Terhormat.\r\nSELAMAT, Anda beruntung mendapatkan Extra Voucher dari ARTUGO.\r\nNomor Seri Produk Anda '" . str_replace(" ", "", $data_warranty->serial_no) . "' dan berhak atas Voucher Rp " . number_format($un->cashback) . " untuk Berbelanja Produk ARTUGO lainnya.\r\nInfo lengkap hubungi ARTUGO Care-line di Nomor WA 0877-8440-1818"; // 1500-602 atau 

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "http://117.103.66.104:10190/voda/wa/send",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => '{"user":"voda","password":"voda123","destination":"' . $phone_nuumber . '","content":"' . $content . '","sender":"WhatsApp","batch":"okok"}',
                    // CURLOPT_HTTPHEADER => array(
                    //     "Content-Type: application/json",
                    // ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }

            if (!empty($data_warranty->reg_email)) {
                $mailSpecialNumber['to'] = $data_warranty->reg_email;
                $mailSpecialNumber['name'] = $data_warranty->reg_name;
                $mailSpecialNumber['warranty_no'] = $data_warranty->warranty_no;
                $mailSpecialNumber['serial_number'] = str_replace(" ", "", $data_warranty->serial_no);
                $mailSpecialNumber['cashback'] = $un->cashback;
                EmailHelper::special_voucher($mailSpecialNumber);
            }
        }

        $data = [
            'verified' => $request->input('status'),
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => Auth::user()->id
        ];
        DB::table('reg_warranty')->where('warranty_id', $request->input('warranty_id'))->update($data);

    }

    public function export_pdf()
    {
        $warranty =  Reg_warranty::select('reg_warranty.*', 'ms_products.product_name_odoo as product_name')->join('ms_products', 'reg_warranty.product_id', '=', 'ms_products.product_id')->orderBy('reg_warranty.created_at', 'desc')->get();

        $pdf = PDF::loadview('backend.warranty.export-pdf', ['warranty' => $warranty])->setPaper('a4', 'landscape');
        return $pdf->stream('artugo-digital-warranty-' . date('Ymd'));
    }

    public function export_excel($from, $to)
    {
        $export = new Warranty;
        $export->setDateFrom(date('Y-m-d', strtotime($from)));
        $export->setDateTo(date('Y-m-d', strtotime($to)));

        return Excel::download($export, 'artugo_digital_warranty_' . date('Ymd') . '.xlsx');

        return Excel::download(new Warranty, 'artugo_digital_warranty_' . date('Ymd') . '.xlsx');
    }

    public function revision(Request $request)
    {
        $files = DB::table('reg_warranty')->where('warranty_id', $request->input('warranty_id'))->first()->files;

        if (!empty($request->file('purchase_invoice'))) {
            $file = $request->file('purchase_invoice');
            $upload_loc = 'sys_uploads/warranty_invoices/';
            $file_name = date("YmdHis-") . Input::get('serial_no') . "." . $file->getClientOriginalExtension();
            $file->move($upload_loc, $file_name);
            $files = url($upload_loc . $file_name);
        }

        $reg_warranty = [
            'reg_name' => $request->input('name'),
            'reg_phone' => $request->input('phone'),
            'reg_address' => $request->input('address'),
            'reg_email' => $request->input('email'),
            'nik_ktp' => $request->input('nik_ktp'),
            'purchase_date' => date('Y-m-d', strtotime($request->input('purchase_date'))),
            'purchase_loc' => $request->input('purchase_loc'),
            'files' => $files
        ];

        DB::table('reg_warranty')->where('warranty_id', $request->input('warranty_id'))->update($reg_warranty);

        $data_reg_war = DB::table('reg_warranty_details')->where('warranty_reg_id', $request->input('warranty_id'))->get();
        if (!empty($data_reg_war)) {
            foreach ($data_reg_war as $key => $value) {
                $start_war = date("Y-m-d 00:00:00", strtotime($request->input('purchase_date')));
                $warranty_year = "+1 year";
                if ($value->warranty_period == 1) {
                    $warranty_year = "+ 1 year";
                } else if ($value->warranty_period > 1) {
                    $warranty_year = "+ " . $value->warranty_period . " years";
                }
                $end_war = date("Y-m-d 23:59:59", strtotime($request->input('purchase_date') . " " . $warranty_year));

                $reg_details_warranty = [
                    'warranty_start' => $start_war,
                    'warranty_end' => $end_war
                ];

                DB::table('reg_warranty_details')->where('id', $value->id)->update($reg_details_warranty);
            }
        }

        // dd(Arr::query($request->query()));

        return redirect('artmin/warranty?' . Arr::query($request->query()));
    }

    public function assign_promotor(Request $request)
    {
        $data = [
            'spg_ref' => $request->input('users_id')
        ];
        DB::table('reg_warranty')->where('warranty_id', $request->input('warranty_id'))->update($data);
    }

    public function exchange(Request $request)
    {
        $cek = DB::table('ms_products_serial')->whereRaw("REPLACE(`serial_no`, ' ' ,'') LIKE ?", $request->input('serial_no_new'))->first();

        if (!empty($cek)) {
            if ($cek->status == '0') {
                $cekSN_old = DB::table('ms_products_serial')->whereRaw("REPLACE(`serial_no`, ' ' ,'') LIKE ?", $request->input('serial_no_old'))->first();

                if ($cek->product_id == $cekSN_old->product_id) {
                    DB::table('ms_products_serial')->whereRaw("REPLACE(`serial_no`, ' ' ,'') LIKE ?", $request->input('serial_no_old'))->update(['status' => 0]);
                    DB::table('ms_products_serial')->whereRaw("REPLACE(`serial_no`, ' ' ,'') LIKE ?", $request->input('serial_no_new'))->update(['status' => 1]);
                    DB::table('reg_warranty')->where('warranty_id', $request->input('exchange_warranty_id'))->update([
                        'serial_no' => $request->input('serial_no_new')
                    ]);
                    $return = [
                        'status' => true,
                    ];
                } else {
                    $return = [
                        'status' => false,
                        'message' => 'Serial Number Invalid Product'
                    ];
                }
            } else {
                $return = [
                    'status' => false,
                    'message' => 'Serial Number Has Been Redeemed'
                ];
            }
        } else {
            $return = [
                'status' => false,
                'message' => 'Serial Number Not Found'
            ];
        }


        return json_encode($return);
    }

    public function cancel(Request $request)
    {
        $warranty = DB::table('reg_warranty')->where('warranty_id', $request->input('warranty_id'))->first();

        // DB::table('ms_products_serial')->where('serial_no', $warranty->serial_no)->update(['status' => 0]);
        DB::table('ms_products_serial')->where(DB::raw("REPLACE(serial_no, ' ', '')"), 'LIKE', $warranty->serial_no)->update(['status' => 0]);

        DB::table('reg_warranty')->where('warranty_id', $request->input('warranty_id'))->update(['status' => 0]);
    }

    public function update_stocktype(Request $request)
    {
        DB::table('reg_warranty')->where('warranty_id', $request->input('warranty_id'))->update([ 'stock_type' => $request->input('stock_type') ]);
    }


    public function check_special_voucher()
    {

        // $reg_warranty = DB::table('reg_warranty')->where('product_id', '281')->get();

        // foreach ($reg_warranty as $key => $value) {

        //     $list_warranty = Products_warranty::where('product_id', $value->product_id)->get();
        //     foreach ($list_warranty as $lw) {
        //         $details['warranty_reg_id'] = $value->warranty_id;
        //         $details['warranty_type'] = $lw->warranty_title;
        //         $details['warranty_start'] = date("Y-m-d 00:00:00", strtotime($value->purchase_date));
        //         $warranty_year = "+1 year";
        //         if ($lw->warranty_year == 1) {
        //             $warranty_year = "+ 1 year";
        //         } else if ($lw->warranty_year > 1) {
        //             $warranty_year = "+ " . $lw->warranty_year . " years";
        //         }

        //         $details['warranty_end'] = date("Y-m-d 23:59:59", strtotime($value->purchase_date . " " . $warranty_year));
        //         $details['warranty_period'] = $lw->warranty_year;
        //         $details['created_at'] = date('Y-m-d H:i:s');
        //         $details['created_by'] = Auth::user()->id;

        //         print_r($details);

        //         echo "<br><hr><br>";
        //         $insert_detail = Reg_warranty_details::insert($details);
        //     }
        // }
        // =======================
        // ORIGINAL SCRIPT
        // =======================
        $reg_data_warranty = DB::table('reg_warranty')->whereDate('purchase_date', '>=', '2020-09-15')->get();
        foreach ($reg_data_warranty as $key => $value) {
            $check_serial = DB::table('ms_products_serial')->whereRaw("REPLACE(`serial_no`, ' ' ,'') LIKE ?", ['%' . str_replace(' ', '', $value->serial_no) . '%'])->first();
            // $check_serial = DB::table('ms_products_serial')->where('serial_no',$value->serial_no)->first();

            if (!empty($check_serial)) {
                $is_unique_number = false;
                $check_special_voucher = DB::table('unique_number')
                    ->where('unique_number', ltrim($check_serial->numbering, '0'))
                    ->where('status', '1')
                    ->whereDate('date_from', '<=', date('Y-m-d', strtotime($value->purchase_date)))
                    ->whereDate('date_to', '>=', date('Y-m-d', strtotime($value->purchase_date)))
                    ->first();
                if (!empty($check_special_voucher)) {
                    $check_special_voucher_product = DB::table('unique_number_products')->where('unique_number', $check_special_voucher->id)->where('products_id', $value->product_id)->first();
                    if (!empty($check_special_voucher_product)) {
                        $is_unique_number = true;

                        DB::table('reg_warranty')->where('warranty_id', $value->warranty_id)->update(['unique_number' => $check_special_voucher->id]);

                        echo "<br>" . $value->serial_no;

                        $customer = Members::where('id', $value->member_id)->first();

                        $special_voucher = [
                            'warranty_id' => $value->warranty_id,
                            'unique_number_id' => $check_special_voucher->id,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                        DB::table('special_voucher')->insert($special_voucher);

                        $mailSpecialNumber['to'] = $customer->email;
                        $mailSpecialNumber['name'] = $customer->name;
                        $mailSpecialNumber['warranty_no'] = $value->warranty_no;
                        $mailSpecialNumber['serial_number'] = str_replace(" ", "", $check_serial->serial_no);
                        $mailSpecialNumber['cashback'] = $check_special_voucher->cashback;
                        EmailHelper::special_voucher($mailSpecialNumber);
                    }
                }
            }
        }
    }

    public function sync_debug()
    {
        // 60,61,174,175,176,284,285,286,287,349
        $reg_warranty = DB::table('reg_warranty')->where('product_id', '87')->get();

        foreach ($reg_warranty as $key => $value) {
    //         $ms_products_warranty = DB::table('ms_products_warranty')->where('product_id', '285')->get();
            $ms_products_warranty = DB::table('ms_products_warranty')->where('product_id', '87')->get();

            DB::table('reg_warranty_details')->where('warranty_reg_id',$value->warranty_id)->delete();

            foreach ($ms_products_warranty as $keys => $values) {

                $details['warranty_reg_id'] = $value->warranty_id;
                $details['warranty_type'] = $values->warranty_title;
                $details['warranty_start'] = date("Y-m-d 00:00:00", strtotime($value->purchase_date));
                $warranty_year = "+1 year";
                if ($values->warranty_year == 1) {
                    $warranty_year = "+ 1 year";
                } else if ($values->warranty_year > 1) {
                    $warranty_year = "+ " . $values->warranty_year . " years";
                }

                $details['warranty_end'] = date("Y-m-d 23:59:59", strtotime($value->purchase_date . " " . $warranty_year));
                $details['warranty_period'] = $values->warranty_year;
                $details['created_at'] = date('Y-m-d H:i:s');

                DB::table('reg_warranty_details')->insert($details);
            }

            print_r($value);
            echo "<br><hr><br>";
        }
    }
}
