<?php

namespace App\Http\Controllers\Backend\Promotion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

use EmailHelper;
use APIodoo;


class CashbackController extends Controller
{
    public function list()
    {
        $data['cashback'] = DB::table('cashback')
            ->select(
                'cashback.*',
                'cashback_periode.nominal',
                'cashback_periode.nominal_extra'
            )
            ->leftJoin('cashback_periode', 'cashback.cashback_periode', '=', 'cashback_periode.id')
            ->orderBy('cashback.created_at', 'desc')
            ->get();
        return view('backend.promotion.cashback.list', $data);
    }

    public function settings()
    {
        $data['periode'] = DB::table('cashback_periode')->get();
        return view('backend.promotion.cashback.settings', $data);
    }

    public function settings_detail($period_id)
    {
        $data['periode'] = DB::table('cashback_periode')->where('id', $period_id)->first();
        if (!empty($data['periode'])) {
            $data['product'] = DB::table('cashback_periode_product')
            ->select(
                'ms_products.product_name_odoo'
            )
            ->join('ms_products','ms_products.product_id','=','cashback_periode_product.products_id')
            ->where('cashback_periode_product.cashback_periode', $data['periode']->id)
            ->get();

            $data['product_combine'] = DB::table('cashback_combine')
            ->select(
                'ms_products.product_name_odoo'
            )
            ->join('ms_products','ms_products.product_id','=','cashback_combine.product_id')
            ->where('cashback_combine.cashback_periode', $data['periode']->id)
            ->get();
            
            return view('backend.promotion.cashback.detail', $data);
        } else {
            return redirect('artmin/promotion/cashback/settings');
        }
    }

    public function add_period()
    {
        $data['statusAction'] = 'insert';
        $data['products'] = DB::table('ms_products')->where('status', '1')->get();
        return view('backend.promotion.cashback.form-period', $data);
    }

    public function add_period_process(Request $request)
    {
        $cashback_periode_data = [
            'startDate' => date('Y-m-d', strtotime($request->input('startDate'))),
            'endDate' => date('Y-m-d', strtotime($request->input('endDate'))),
            'nominal' => $request->input('nominal'),
            'nominal_extra' => $request->input('nominal_extra'),
            'status' => 1,
            'type_cashback' => $request->input('type_cashback'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $cashback_periode = DB::table('cashback_periode')->insertGetId($cashback_periode_data);

        if (!empty($request->input('products_id'))) {
            foreach ($request->input('products_id') as $key => $value) {
                $cashback_periode_product_data = [
                    'cashback_periode' => $cashback_periode,
                    'products_id' => $value,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                DB::table('cashback_periode_product')->insert($cashback_periode_product_data);
            }
        }

        if ($request->input('type_cashback') == '2' && !empty($request->input('products_id_combine'))) {
            foreach ($request->input('products_id_combine') as $key => $value) {
                $cashback_periode_product_data = [
                    'cashback_periode' => $cashback_periode,
                    'product_id' => $value,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                DB::table('cashback_combine')->insert($cashback_periode_product_data);
            }
        }
    }

    public function get_data_verification($cashback_id)
    {
        $cashback = DB::table('cashback')->where('cashback_id', $cashback_id)->first();

        $reg_warranty = DB::table('reg_warranty')->select('reg_warranty.*', 'ms_products.product_name_odoo')->where('reg_warranty.warranty_id', $cashback->warranty_id)->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_warranty.product_id')->first();

        $member = DB::table('ms_members')->where('id', $reg_warranty->member_id)->first();

        $data = [
            'cashback' => $cashback,
            'reg_warranty' => $reg_warranty,
            'member' => $member
        ];

        return json_encode($data);
    }

    public function verified(Request $request)
    {

        $email = [
            'harry.dimas@artugo.co.id',
            'kimi@artugo.co.id',
            'leo.ariefyanto@artugo.co.id'
        ];

        // $email = [
        //     'hi@lambda.web.id',
        //     'lambdasangkala45@gmail.com'
        // ];

        foreach ($email as $val_mail) {
            $data['cashback'] = DB::table('cashback')->where('cashback_id', $request->input('cashback_id'))->first();
            $data['warranty'] = DB::table('reg_warranty')->select('reg_warranty.*', 'ms_products.product_name_odoo as product_name')->where('reg_warranty.warranty_no', $data['cashback']->warranty_no)->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_warranty.product_id')->first();
            $data['cashback_periode'] = DB::table('cashback_periode')->where('id', $data['cashback']->cashback_periode)->first();
            $data['name'] = $data['warranty']->reg_name;
            $data['to'] = $val_mail;
            EmailHelper::cashback_confirmation($data);
        }


        $data = [
            'verified' => 1
        ];
        DB::table('cashback')->where('cashback_id', $request->input('cashback_id'))->update($data);

        $cashback = DB::table('cashback')
            ->select(
                'cashback.*',
                'ms_products.default_code'
            )
            ->leftJoin('reg_warranty', 'reg_warranty.warranty_id', '=', 'cashback.warranty_id')
            ->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_warranty.product_id')
            ->where('cashback.cashback_id', $request->input('cashback_id'))
            ->first();
        $cashback_periode = DB::table('cashback_periode')->where('id', $cashback->cashback_periode)->first();

        $nominal = 0;
        if (!empty($cashback->foto_sertifikat_vaksin)) {
            $nominal = $cashback_periode->nominal + $cashback_periode->nominal_extra;
        } else {
            $nominal = $cashback_periode->nominal;
        }

        APIodoo::create_vendor_bill('Cashback', $cashback->warranty_no, $cashback->no_rekening, $cashback->atas_nama_rekening, $cashback->nama_bank, $nominal, $cashback->default_code, date('Y-m-d', strtotime($cashback->created_at)));
    }

    public function debug()
    {
        $cashback = DB::table('cashback')
            ->select(
                'cashback.*',
                'ms_products.default_code'
            )
            ->leftJoin('reg_warranty', 'reg_warranty.warranty_id', '=', 'cashback.warranty_id')
            ->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_warranty.product_id')
            ->where('cashback.cashback_id', '5')
            ->first();

        $cashback_periode = DB::table('cashback_periode')->where('id', $cashback->cashback_periode)->first();

        $nominal = 0;
        if (!empty($cashback->foto_sertifikat_vaksin)) {
            $nominal = $cashback_periode->nominal + $cashback_periode->nominal_extra;
        } else {
            $nominal = $cashback_periode->nominal;
        }

        $send = APIodoo::create_vendor_bill('Cashback', $cashback->warranty_no, $cashback->no_rekening, $cashback->atas_nama_rekening, $cashback->nama_bank, $nominal, $cashback->default_code, date('Y-m-d', strtotime($cashback->created_at)));

        print_r($send);
    }

    public function verified_transfer(Request $request)
    {
        $file = $request->file('bukti_transfer');
        $upload_loc = 'uploads/promotion/cashback/transfer/';
        $file_name = date("YmdHis-") . $request->input('cashback_id') . "." . $file->getClientOriginalExtension();
        $file->move($upload_loc, $file_name);

        $data = [
            'verified' => 2,
            'transfer_finance' => url($upload_loc, $file_name)
        ];
        DB::table('cashback')->where('cashback_id', $request->input('cashback_id'))->update($data);

        return redirect('artmin/promotion/cashback');
    }



    public function cashback_revisi(Request $request)
    {
        $current_data = DB::table('cashback')->where('cashback_id', $request->input('cashback_id'))->first();
        $foto_ktp = $current_data->foto_ktp;
        $foto_barang = $current_data->foto_barang;
        $foto_sertifikat_vaksin = $current_data->foto_sertifikat_vaksin;

        $file = Input::file('foto_ktp');
        if (!empty(Input::file('foto_ktp'))) {
            $upload_loc = 'sys_uploads/cashback/foto_ktp/';
            $foto_ktp = date("YmdHis-") . Input::get('serial_no') . "." . $file->getClientOriginalExtension();
            $file->move($upload_loc, $foto_ktp);
            $foto_ktp = url($upload_loc . $foto_ktp);
        }

        $file = Input::file('foto_barang');
        if (!empty(Input::file('foto_barang'))) {
            $upload_loc = 'sys_uploads/cashback/foto_barang/';
            $foto_barang = date("YmdHis-") . Input::get('serial_no') . "s." . $file->getClientOriginalExtension();
            $file->move($upload_loc, $foto_barang);
            $foto_barang = url($upload_loc . $foto_barang);
        }

        $file = Input::file('foto_sertifikat_vaksin');
        if (!empty(Input::file('foto_sertifikat_vaksin'))) {
            $upload_loc = 'sys_uploads/cashback/foto_sertifikat_vaksin/';
            $foto_sertifikat_vaksin = date("YmdHis-") . Input::get('serial_no') . "." . $file->getClientOriginalExtension();
            $file->move($upload_loc, $foto_sertifikat_vaksin);

            $foto_sertifikat_vaksin = url($upload_loc . $foto_sertifikat_vaksin);
        }

        $cashback['ktp'] = Input::get('ktp');
        $cashback['nama_bank'] = Input::get('nama_bank');
        $cashback['no_rekening'] = Input::get('no_rekening');
        $cashback['atas_nama_rekening'] = Input::get('atas_nama_rekening');
        $cashback['kota_tempat_rekening_dibuka'] = Input::get('kota_tempat_rekening_dibuka');
        $cashback['foto_ktp'] =  $foto_ktp;
        $cashback['foto_barang'] = $foto_barang;
        $cashback['foto_sertifikat_vaksin'] = $foto_sertifikat_vaksin;
        $cashback['updated_at'] = date('Y-m-d H:i:s');

        DB::table('cashback')->where('cashback_id', $request->input('cashback_id'))->update($cashback);

        return redirect('artmin/promotion/cashback');
    }

    public function resend($cashback_id)
    {
        $cashback = DB::table('cashback')->where('cashback_id', $cashback_id)->first();
        $cashback_periode = DB::table('cashback_periode')->where('id', $cashback->cashback_periode)->first();

        $nominal = 0;
        if (!empty($cashback->foto_sertifikat_vaksin)) {
            $nominal = $cashback_periode->nominal + $cashback_periode->nominal_extra;
        } else {
            $nominal = $cashback_periode->nominal;
        }

        $send = APIodoo::create_vendor_bill('Cashback', $cashback->warranty_no, $cashback->no_rekening, $cashback->atas_nama_rekening, $cashback->nama_bank, $nominal);
        print_r($send);
    }

    // public function debug_product()
    // {
    //     $product = DB::table('ms_products')->where('default_code','like','CD%')->get();

    //     foreach ($product as $key => $value) {
    //         $product_combine = [
    //             'cashback_periode' => '5',
    //             'product_id' => $value->product_id,
    //             'created_at' => date('Y-m-d H:i:s')
    //         ];

    //         DB::table('cashback_combine')->insert($product_combine);
    //     }
    // }
}
