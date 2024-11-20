<?php

namespace App\Http\Controllers\Backend\Promotion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use EmailHelper;

class SpecialVoucherController extends Controller
{
    public function list()
    {
        $data['special_voucher'] = DB::table('special_voucher')
            ->select(
                'special_voucher.*',
                // 'special_voucher.foto_ktp',
                // 'special_voucher.foto_barang',
                // 'special_voucher.bukti_transfer',
                'reg_warranty.*',
                // 'reg_warranty.created_at as warranty_created',
                // 'reg_warranty.warranty_id',
                // 'reg_warranty.warranty_no',
                // 'reg_warranty.serial_no',
                'ms_members.name as member_name',
                // 'reg_warranty.reg_name',
                // 'reg_warranty.reg_phone',
                // 'reg_warranty.reg_email'
            )
            ->leftJoin('reg_warranty', 'reg_warranty.warranty_id', '=', 'special_voucher.warranty_id')
            ->leftJoin('ms_members', 'ms_members.id', '=', 'reg_warranty.member_id')
            ->orderBy('special_voucher.id', 'desc')
            ->get();
        $data['unique_number'] = DB::table('unique_number')->get();
        return view('backend/promotion/specialvoucher/list', $data);
    }

    public function settings()
    {
        $data['unique_number'] = DB::table('unique_number')->get();
        return view('backend/promotion/specialvoucher/settings', $data);
    }

    public function add_unique_number()
    {
        $data['statusAction'] = 'insert';
        $data['products'] = DB::table('ms_products')->where('status', '1')->get();
        return view('backend/promotion/specialvoucher/form', $data);
    }

    public function saveData(Request $request)
    {
        $data = [
            'unique_number' => $request->input('unique_number'),
            'cashback' => $request->input('cashback'),
            'date_from' => date('Y-m-d', strtotime($request->input('period_from'))),
            'date_to' => date('Y-m-d', strtotime($request->input('period_to'))),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $unique_number_id = DB::table('unique_number')->insertGetId($data);

        if (!empty($request->input('products_id'))) {
            foreach ($request->input('products_id') as $key => $value) {
                $unique_number_products = [
                    'unique_number' => $unique_number_id,
                    'products_id' => $value,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                DB::table('unique_number_products')->insert($unique_number_products);
            }
        }
    }

    public function updateData(Request $request)
    {
        $unique_number_id = $request->input('unique_number_id');

        $data = [
            'unique_number' => $request->input('unique_number'),
            'cashback' => $request->input('cashback'),
            'date_from' => date('Y-m-d', strtotime($request->input('period_from'))),
            'date_to' => date('Y-m-d', strtotime($request->input('period_to'))),
        ];

        $update = DB::table('unique_number')->where('id', $unique_number_id)->update($data);

        if (!empty($request->input('products_id'))) {
            DB::table('unique_number_products')->where('unique_number', $unique_number_id)->delete();
            foreach ($request->input('products_id') as $key => $value) {
                if ($value != '-') {
                    $unique_number_products = [
                        'unique_number' => $unique_number_id,
                        'products_id' => $value,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    DB::table('unique_number_products')->insert($unique_number_products);
                }
            }
        }
    }

    public function edit_unique_number($id)
    {
        $data['statusAction'] = 'update';

        $data['unique_number'] = DB::table('unique_number')->where('id', $id)->first();
        $data['unique_number_products'] = DB::table('unique_number_products')->where('unique_number', $id)->get();

        $data['products'] = DB::table('ms_products')->where('status', '1')->get();
        return view('backend/promotion/specialvoucher/form', $data);
    }

    public function transfer_cashback(Request $request)
    {
        $file = $request->file('bukti_transfer');
        $upload_loc = 'uploads/promotion/specialvouhcer/transfer';
        $file_name = date("YmdHis-") . $request->input('special_voucher_id') . "." . $file->getClientOriginalExtension();
        $file->move($upload_loc, $file_name);

        $data = [
            // 'verified' => 2,
            'bukti_transfer' => url($upload_loc, $file_name)
        ];
        DB::table('special_voucher')->where('id', $request->input('special_voucher_id'))->update($data);


        $email = [
            // 'lambdasangkala45@gmail.com',
            'harry.dimas@artugo.co.id',
            'kimi@artugo.co.id',
            'leo.ariefyanto@artugo.co.id',
            'fransisca.sianipar@artugo.co.id'
        ];

        foreach ($email as $val_mail) {
            $data['special_voucher'] = DB::table('special_voucher')->where('id', $request->input('special_voucher_id'))->first();
            $data['warranty'] = DB::table('reg_warranty')->select('reg_warranty.*', 'ms_products.product_name_odoo as product_name')->where('reg_warranty.warranty_id', $data['special_voucher']->warranty_id)->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_warranty.product_id')->first();
            $data['unique_number'] = DB::table('unique_number')->where('id', $data['special_voucher']->unique_number_id)->first();
            $data['name'] = $data['warranty']->reg_name;
            $data['to'] = $val_mail;
            EmailHelper::special_voucher_confirmation_finance($data);
        }

        // print_r($data['warranty']);

        return redirect('artmin/promotion/specialvoucher');
    }
}
