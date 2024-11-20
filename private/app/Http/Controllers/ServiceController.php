<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use DB;
use App\Data;
use App\Products;
use App\Products_serial;
use App\Reg_warranty;

class ServiceController extends Controller
{
    public function index(){

        $data['content'] = Data::where('code', 'service-content')->first();

        return view('web.service', $data);
    }

    public function register(){

        $data['products'] = Products::where("status", 1)->get();

        return view('web.warranty-register', $data);
    }

    public function register_progress(Request $request){

        $validator = Validator::make($request->all(), [
            'product_code' => 'required',
            'serial_no' => 'required',
            'email' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'purchase_date' => 'required',
            'purchase_loc' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else{
            // Check Serial Number
            $serial_no = Input::get('serial_no');
            $serial_no = str_replace(" ", "", $serial_no);

            // $get_serial = Products_serial::select(DB::raw("REPLACE(serial_no, ' ', '')"))->first();
            $check_serial = Products_serial::where(DB::raw("REPLACE(serial_no, ' ', '')"), 'LIKE', $serial_no)->where("product_id", Input::get('product_code'))->first();
            if(!isset($check_serial->id)){
                return redirect()->back()->with("error_serial", "Nomor Serial tidak ditemukan. Mohon periksa kembali nomor serial produk anda atau hubungi kami untuk mendapatkan bantuan lebih lanjut.")->withInput();
            }
            else{
                if($check_serial->status == 1){
                    return redirect()->back()->with("error_serial", "Nomor serial sudah terdaftar.")->withInput();
                }
                else{
                    $period = date("Ym");

                    $check_warranty = Reg_warranty::where("warranty_no", "LIKE", $period."%")->orderBy('warranty_id', 'desc')->first();

                    if(!isset($check_warranty->warranty_id)){
                        $warranty_no = $period.str_pad(1, 4, "0", STR_PAD_LEFT);
                    }
                    else{
                        $last_warranty_no = $check_warranty->warranty_no;
                        $last_warranty_no = substr($last_warranty_no, -4, 4);
                        $last_warranty_no = (int)$last_warranty_no;
                        $warranty_no = $period.str_pad($last_warranty_no+1, 4, "0", STR_PAD_LEFT);
                    }

                    $warranty['warranty_no'] = $warranty_no;
                    $warranty['member_id'] = 2;
                    $warranty['product_id'] = Input::get('product_code');
                    $warranty['serial_no'] = str_replace(" ", "", Input::get('serial_no'));
                    $warranty['reg_name'] = Input::get('name');
                    $warranty['reg_phone'] = Input::get('phone');
                    $warranty['reg_address'] = Input::get('address');
                    $warranty['reg_email'] = Input::get('email');
                    $warranty['purchase_date'] = date('Y-m-d', strtotime(Input::get('purchase_date')));
                    $warranty['purchase_loc'] = Input::get('purchase_loc');
                    $warranty['created_at'] = date("Y-m-d H:i:s");
                    $warranty['created_by'] = 1;

                    $insert = Reg_warranty::insert($warranty);

                    if($insert){
                        return redirect('warranty/registration-success?warranty=' . $warranty_no);
                    }

                }
            }
        }
    }

    public function register_success(){
        return view('web.warranty-register-success');
    }

    public function get_product(){
        if(isset($_GET['product_code'])){
            $product_code = $_GET['product_code'];

            $product = Products::where("product_id", $product_code)->first();
            if(isset($product->product_id)){
                echo $product->product_image;
            }
        }
    }

    public function check_warranty($product_code, $serial_number){

    }
}
