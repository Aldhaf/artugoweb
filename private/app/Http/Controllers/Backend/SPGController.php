<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

use DB;
use Session;
use Hash;
use App\Products;
use App\Reg_warranty;
use App\Reg_warranty_details;
use App\Products_warranty;
use App\Products_serial;
use Auth;

use EmailHelper;

class SPGController extends Controller
{
    public function register_product_customer()
    {
        // $data['member'] = DB::table('reg_warranty')
        //     ->select(
        //         'reg_warranty.member_id',
        //         'ms_members.name',
        //         'ms_members.email',
        //         'ms_members.phone'
        //     )
        //     ->join('ms_members', 'ms_members.id', '=', 'reg_warranty.member_id')
        //     ->groupBy('reg_warranty.member_id')
        //     ->distinct('reg_warranty.member_id')
        //     ->where('reg_warranty.spg_ref', Auth::user()->id)
        //     ->get();

        $data['warranty'] = Reg_warranty::select(
            'reg_warranty.*',
            'ms_products.product_name_odoo as product_name',
            // 'users.reg_name as spg_name'
        )
            ->join('ms_products', 'reg_warranty.product_id', '=', 'ms_products.product_id')
            // ->rightJoin('users','users.id','=','reg_warranty.spg_ref')
            ->where('reg_warranty.spg_ref', Auth::user()->id)
            ->orderBy('reg_warranty.warranty_id')
            ->get();


        return view('backend.spg.register_product_customer.list', $data);
    }

    public function list_product_customer($member_id)
    {
        $data['member'] = DB::table('ms_members')->where('id', $member_id)->first();
        $data['reg_warranty'] = Reg_warranty::select(
            'reg_warranty.*',
            'ms_products.product_name'
        )
            ->join('ms_products', 'ms_products.product_id', '=', 'reg_warranty.product_id')
            ->where('reg_warranty.member_id', $member_id)->where('reg_warranty.spg_ref', Auth::user()->id)->get();

        return view('backend.spg.register_product_customer.detail', $data);
    }

    public function add_product_customer()
    {
        $data['products'] = Products::where('status', '1')->get();
        $data['city'] = DB::table('ms_loc_city')->get();
        $data['member'] = DB::table('ms_members')->where('status', '1')->get();

        return view('backend.spg.register_product_customer.form', $data);
    }

    public function get_data_customer($member_id)
    {
        $data = DB::table('ms_members')->where('id', $member_id)->first();

        $retData = [
            'status' => true,
            'data' => $data
        ];

        return json_encode($retData);
    }

    public function check_serial_number($product_id, $sn)
    {
        $data = Products_serial::where(DB::raw("REPLACE(serial_no, ' ', '')"), 'LIKE', $sn)->where("product_id", $product_id)->first();

        if (!empty($data)) {
            $retData = [
                'status' => true,
                'data' => $data
            ];
        } else {
            $retData = [
                'status' => false,
                'prod_id' => $product_id,
                'sn' => $sn,
                'data' => $data
            ];
        }

        return json_encode($retData);
    }

    public function add_product_customer_process(Request $request)
    {
        $data_serial_number = DB::table('ms_products_serial')->where('serial_no', $request->input('serial_no'))->first();

        if (!empty($data_serial_number)) {
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

            $file = $request->file('purchase_invoice');
            $upload_loc = 'sys_uploads/warranty_invoices/';
            $file_name = date("YmdHis-") . $request->input('serial_no') . "." . $file->getClientOriginalExtension();
            $file->move($upload_loc, $file_name);

            $member_id = null;
            $reg_name = null;
            $reg_phone = null;
            $reg_address = null;
            $reg_city_id = null;
            $reg_city = null;
            $reg_post_code = null;
            $reg_email = null;

            if ($request->input('type_reg_cus') == 'new') {

                $ms_members = [
                    'id' => null,
                    'name' => $request->input('new_name'),
                    'email' => $request->input('new_email'),
                    'password' => Hash::make($request->input('password')),
                    'phone' => $request->input('new_phone'),
                    'address' => $request->input('new_address'),
                    'city' => $request->input('new_city'),
                    'status' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                DB::table('ms_members')->insert($ms_members);

                $member_id = DB::table('ms_members')->orderBy('id', 'desc')->first()->id;

                $reg_name = $request->input('new_name');
                $reg_phone = $request->input('new_phone');
                $reg_address = $request->input('new_address');
                $reg_city_id = $request->input('new_city');
                $dataCity = DB::table('ms_loc_city')->where('city_id', $request->input('new_city'))->first();
                $reg_city = ($dataCity->name ?? null);
                $reg_post_code = ($dataCity->code ?? null);
                $reg_email = $request->input('new_email');
            } else {
                $member_id = $request->input('member_id');
                $reg_name = $request->input('exist_name');
                $reg_phone = $request->input('exist_phone');
                $reg_address = $request->input('exist_address');
                $reg_city_id = $request->input('exist_city');
                $dataCity = DB::table('ms_loc_city')->where('city_id', $request->input('exist_city'))->first();
                $reg_city = ($dataCity->name ?? null);
                $reg_post_code = ($dataCity->code ?? null);
                $reg_email = $request->input('exist_email');
            }


            $reg_warranty = [
                'warranty_id' => null,
                'warranty_no' => $warranty_no,
                'member_id' => $member_id,
                'product_id' => $request->input('product_id'),
                'install_status' => '0',
                'serial_no' => $request->input('serial_no'),
                'reg_name' => $reg_name,
                'reg_phone' => $reg_phone,
                'reg_address' => $reg_address,
                'reg_city_id' => $reg_city_id,
                'reg_city' => $reg_city,
                'reg_post_code' => $reg_post_code,
                'reg_email' => $reg_email,
                'purchase_date' => date('Y-m-d', strtotime($request->input('purchase_date'))),
                'purchase_loc' => $request->input('purchase_loc'),
                'files' => url($upload_loc . $file_name),
                'expired_date' => null,
                'spg_ref' => Auth::user()->id,
                'stock_type' => $request->input('stock_type') ?? 'stkavailable',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id,
                'updated_at' => null,
                'updated_by' => null,
            ];
            $insert_warranty = Reg_warranty::insertGetId($reg_warranty);

            if ($insert_warranty) {
                $list_warranty = Products_warranty::where('product_id', $request->input('product_id'))->get();

                foreach ($list_warranty as $lw) {
                    $details['warranty_reg_id'] = $insert_warranty;
                    $details['warranty_type'] = $lw->warranty_title;
                    $details['warranty_start'] = date("Y-m-d 00:00:00", strtotime($reg_warranty['purchase_date']));
                    $warranty_year = "+1 year";
                    if ($lw->warranty_year == 1) {
                        $warranty_year = "+ 1 year";
                    } else if ($lw->warranty_year > 1) {
                        $warranty_year = "+ " . $lw->warranty_year . " years";
                    }
                    $details['warranty_end'] = date("Y-m-d 23:59:59", strtotime($reg_warranty['purchase_date'] . " " . $warranty_year));
                    $details['warranty_period'] = $lw->warranty_year;
                    $details['created_at'] = date('Y-m-d H:i:s');
                    $details['created_by'] = Session::get('member_id');

                    $insert_detail = Reg_warranty_details::insert($details);
                }
            }


            $ms_products_serial = [
                'status' => '1'
            ];
            DB::table('ms_products_serial')->where('serial_no', $request->input('serial_no'))->update($ms_products_serial);

            $data['to'] = $reg_warranty['reg_email'];
            $data['name'] = $reg_warranty['reg_name'];
            $data['warranty'] = Reg_warranty::where('warranty_id', $insert_warranty)->first();
            $data['warranty_list'] = Reg_warranty_details::where('warranty_reg_id', $insert_warranty)->get();
            $data['product'] = Products::where('product_id', $reg_warranty['product_id'])->first();

            if (!empty($reg_email)) {
                EmailHelper::warranty_registration($data);
            }


            return redirect('artmin/registerproductcustomer');
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
        );
        // dd($request->all());
        if (Input::get('email') != '') {
            $rules['email'] = 'email';
        }

        $type_reg_cus = Input::get('type_reg_cus');
        $product_id = Input::get('product_id');
        $member_id = old('member_id', Input::get('member_id'));

        if ($type_reg_cus == 'new') {
            $rules['name'] = "required";
            $rules['phone'] = "required";
            $rules['address'] = "required";
            $rules['city'] = "required";
        } else if ($type_reg_cus == 'exist') {
            $rules['member_id'] = "required";
        }

        if ($type_reg_cus == 'new') {
            $exist_member = DB::table('ms_members')->select('id', 'phone', 'name', 'city_id', 'city', 'address', 'email')
                ->where('status', 1)
                ->where('phone', Input::get('phone'))
                ->whereNotNull('name')
                ->whereNotNull('phone')
                ->first();
            // dd(Input::get('new_cust_city')==$exist_member->city_id);
            if ($exist_member) { // && !$match_name) {
                $match_name = strtoupper(Input::get('name'))==strtoupper($exist_member->name);
                if (Input::get('has_confirm_phone_duplicate') == "0") {
                    
                    if ($match_name) {
                        $message = 'Nomor Telpon ' . $exist_member->phone . ' oleh customer dengan nama ' . Input::get('name') . ' sebelumnya telah terdaftar sebagai member.';
                    } else {
                        $message = 'Nomor Telpon ' . $exist_member->phone . ' sudah digunakan oleh member ' . $exist_member->name . ' (' . $exist_member->city . ').';
                    }
                    return redirect()->back()->withErrors(['duplicate_phone' => $message])->withInput();
                } else {
                    $type_reg_cus = 'exist';
                    $member_id = $exist_member->id . '';
                    $request->merge([
                        'type_reg_cus' => $type_reg_cus,
                        'member_id' => $member_id,
                        'name' => $exist_member->name,
                        'email' => $exist_member->email,
                        'address' => $exist_member->address,
                        'city' => $exist_member->city_id
                    ]);

                    // $input = $request->except([
                    //     'name',
                    // ]);
                }
            }
        } else {
            $exist_member = DB::table('ms_members')->select('id', 'phone', 'name', 'city_id', 'city', 'address', 'email')
                ->where('id', $member_id)->first();
            if ($exist_member) {
                $request->merge([
                    'name' => $exist_member->name,
                    'email' => $exist_member->email,
                    'address' => Input::get('address') ?? $exist_member->address,
                    'city' => Input::get('city') ?? $exist_member->city_id
                ]);
            }
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

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            // Check Serial Number
            $serial_no = Input::get('serial_no');
            $serial_no = str_replace(" ", "", $serial_no);

            // $get_serial = Products_serial::select(DB::raw("REPLACE(serial_no, ' ', '')"))->first();
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
                return redirect()->back()->with("error_serial", "Nomor Serial tidak ditemukan. Mohon periksa kembali nomor serial produk anda atau hubungi kami untuk mendapatkan bantuan lebih lanjut.")->withInput();
            } else {
                if ($check_serial->status == 1 && $check_serial->stock_type == 'stkavailable') {
                    return redirect()->back()->with("error_serial", "Nomor serial sudah terdaftar.")->withInput();
                } else {
                    $member_id = null;
                    // Check login account
                    if (Input::get('type_reg_cus') == 'new') {

                        $check_phone = DB::table('reg_warranty')->where('reg_phone', Input::get('phone'))->first();
                        $check_email = DB::table('reg_warranty')->where('reg_email', Input::get('email'))->first();

                        $city = DB::table('ms_loc_city')->where('city_id', Input::get('city'))->first();
                        if (!empty($check_phone->reg_phone)) {
                            return redirect()->back()->with("error_phone", "No Telp yang anda masukan telah terdaftar")->withInput();
                        }
                        if (!empty($check_email->reg_email)) {
                            return redirect()->back()->with("error_email", "Email yang anda masukan telah terdaftar")->withInput();
                        }

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
                    } else {
                        $member_id = Input::get('member_id');
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

                    $file = $request->file('purchase_invoice');
                    $upload_loc = 'sys_uploads/warranty_invoices/';
                    $file_name = date("YmdHis-") . Input::get('serial_no') . "." . $file->getClientOriginalExtension();
                    $file->move($upload_loc, $file_name);

                    $current_time = date("Y-m-d H:i:s");
                    $warranty['warranty_no'] = $warranty_no;
                    $warranty['member_id'] = $member_id;
                    $warranty['product_id'] = Input::get('product_id');
                    $warranty['serial_no'] = str_replace(" ", "", Input::get('serial_no'));
                    $warranty['reg_name'] = Input::get('name');
                    $warranty['reg_phone'] = Input::get('phone');

                    $city = DB::table('ms_loc_city')->where('city_id', Input::get('city'))->first();
                    $warranty['reg_address'] = Input::get('address');
                    $warranty['reg_city_id'] = Input::get('city');
                    $warranty['reg_city'] = $city->city_name;

                    $warranty['stock_type'] = Input::get('stock_type') ?? 'stkavailable';
                    $warranty['files'] = url($upload_loc . $file_name);
                    $warranty['reg_email'] = Input::get('email');
                    $warranty['purchase_date'] = date('Y-m-d', strtotime(Input::get('purchase_date')));
                    $warranty['online_store'] = Input::get('online_store') == "on" ? 1: 0;
                    $warranty['purchase_loc'] = Input::get($warranty['online_store']==1 ? 'online_store_name' : 'purchase_loc');
                    $warranty['spg_ref'] = Auth::user()->id;
                    $warranty['stock_type'] = Input::get('stock_type') ?? 'stkavailable';
                    $warranty['created_at'] = $current_time;
                    $warranty['created_by'] = 1;

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
                            $details['created_by'] = Session::get('member_id');

                            $insert_detail = Reg_warranty_details::insert($details);
                        }
                        $update_serial_data['status'] = 1;
                        $update_serial = Products_serial::where('id', $check_serial->id)->update($update_serial_data);

                        $data['to'] = $warranty['reg_email'];
                        $data['name'] = $warranty['reg_name'];
                        $data['warranty'] = Reg_warranty::where('warranty_id', $insert_warranty)->first();
                        $data['warranty_list'] = Reg_warranty_details::where('warranty_reg_id', $insert_warranty)->get();
                        $data['product'] = Products::where('product_id', $warranty['product_id'])->first();

                        if (Input::get('email') != '') {
                            EmailHelper::warranty_registration($data);
                        }

                        return redirect('warranty/registration-success?warranty=' . $warranty_no);
                    }
                }
            }
        }
    }
}
