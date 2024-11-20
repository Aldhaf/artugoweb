<?php

namespace App\Http\Controllers\Backend\Promotion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

use EmailHelper;
use App\Helpers\APIodoo;

class TradeInController extends Controller
{
    public function list()
    {
        $data['tradein'] = DB::table('tradein')->get();
        return view('backend.promotion.tradein.list', $data);
    }

    public function settings()
    {
        $data['periode'] = DB::table('tradein_periode')->get();
        return view('backend.promotion.tradein.settings', $data);
    }

    public function add_period()
    {
        $data['statusAction'] = 'insert';
        $data['products'] = DB::table('ms_products')->where('status', '1')->get();
        return view('backend.promotion.tradein.form-period', $data);
    }

    public function add_period_process(Request $request)
    {
        $tradein_periode_data = [
            'startDate' => date('Y-m-d', strtotime($request->input('startDate'))),
            'endDate' => date('Y-m-d', strtotime($request->input('endDate'))),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $trade_periode = DB::table('tradein_periode')->insertGetId($tradein_periode_data);

        if (!empty($request->input('products_id'))) {
            foreach ($request->input('products_id') as $key => $value) {
                $tradein_periode_product_data = [
                    'tradein_periode' => $trade_periode,
                    'products_id' => $value,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                DB::table('tradein_periode_product')->insert($tradein_periode_product_data);
            }
        }
    }

    public function get_data_verification($trade_id)
    {
        $trade = DB::table('tradein')->where('trade_id', $trade_id)->first();

        $reg_warranty = DB::table('reg_warranty')->select('reg_warranty.*', 'ms_products.product_name_odoo')->where('reg_warranty.warranty_id', $trade->warranty_id)->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_warranty.product_id')->first();

        $member = DB::table('ms_members')->where('id', $reg_warranty->member_id)->first();

        $data = [
            'tradein' => $trade,
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
            $data['tradein'] = DB::table('tradein')->where('trade_id', $request->input('trade_id'))->first();
            $data['warranty'] = DB::table('reg_warranty')->select('reg_warranty.*', 'ms_products.product_name_odoo as product_name')->where('reg_warranty.warranty_no', $data['tradein']->warranty_no)->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_warranty.product_id')->first();
            $data['tradein_periode'] = DB::table('tradein_periode_product')->where('tradein_periode', $data['tradein']->trade_periode)->where('products_id', $data['warranty']->product_id)->first();
            $data['name'] = $data['warranty']->reg_name;
            $data['to'] = $val_mail;
            EmailHelper::tradein_confirmation($data);
        }


        $data = [
            'verified' => 1
        ];
        DB::table('tradein')->where('trade_id', $request->input('trade_id'))->update($data);

        $trade_in = DB::table('tradein')->where('trade_id', $request->input('trade_id'))->first();
        $reg_warranty = DB::table('reg_warranty')
            ->select(
                'reg_warranty.warranty_no',
                'tradein_periode_product.nominal'
            )
            ->leftJoin('tradein_periode_product', 'tradein_periode_product.products_id', '=', 'reg_warranty.product_id')
            ->where('reg_warranty.warranty_no', $trade_in->warranty_no)
            ->where('tradein_periode_product.tradein_periode', $trade_in->trade_periode)
            ->first();

        APIodoo::create_vendor_bill('Trade In', $trade_in->warranty_no, $trade_in->no_rekening, $trade_in->atas_nama_rekening, $trade_in->nama_bank, $reg_warranty->nominal);
    }

    public function verified_transfer(Request $request)
    {
        $file = $request->file('bukti_transfer');
        $upload_loc = 'uploads/promotion/tradein/transfer/';
        $file_name = date("YmdHis-") . $request->input('trade_id') . "." . $file->getClientOriginalExtension();
        $file->move($upload_loc, $file_name);

        $data = [
            'verified' => 2,
            'transfer_finance' => url($upload_loc, $file_name)
        ];
        DB::table('tradein')->where('trade_id', $request->input('trade_id'))->update($data);

        return redirect('artmin/promotion/tradein');
    }
}
