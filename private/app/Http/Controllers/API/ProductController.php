<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Products;

class ProductController extends Controller
{

    public function list_product()
    {
        $product = Products::where('status', '1')->get();

        $data = [
            'status' => 200,
            'data' => $product
        ];

        return response()->json($data, 200);
    }

    public function update_product(Request $request)
    {
        $check = DB::table('ms_products')->where('default_code', $request->input('default_code'))->first();

        if (!empty($check)) {
        } else {
        }

        $data = [
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function delete_product(Request $request)
    {
    }

    public function serial_check(Request $request)
    {
        $serial = $request->input('serial');
        $product_code = str_replace(' ', '', $request->input('product_code'));

        $data = DB::table('ms_products_serial')
            ->select(
                'ms_products_serial.*',
                // 'ms_products.default_code'
            )
            ->where(DB::raw("REPLACE(serial_no, ' ', '')"), 'LIKE', $serial)
            ->where(DB::raw("REPLACE(default_code, ' ', '')"), 'LIKE', $product_code)
            ->leftJoin('ms_products', 'ms_products.product_id', '=', 'ms_products_serial.product_id')
            ->first();

        if (!empty($data)) {
            $return = [
                'status' => true,
                'message' => 'Serial Number Ditemukan'
            ];
        } else {
            $return = [
                'status' => false,
                'message' => 'Serial Number Tidak Ditemukan'
            ];
        }

        return response()->json($return, 200);
    }

    public function serial_add(Request $request)
    {
        $product_code = str_replace(' ', '', $request->input('product_code'));

        $product = DB::table('ms_products')->where(DB::raw("REPLACE(default_code, ' ', '')"), 'LIKE', $product_code)->first();

        if (!empty($product)) {

            $prefix = $request->input('prefix');
            $lot_qty = $request->input('lot_qty');
            $qty_allowance = $request->input('qty_allowance');
            $postfix = $request->input('postfix');

            $total_qty = $lot_qty + $qty_allowance;

            for ($i = 1; $i <= $total_qty; $i++) {
                $sn = $prefix . sprintf("%04d", $i) . $postfix;
                $checkSN = DB::table('ms_products_serial')->where('serial_no', $sn)->first();

                if (empty($checkSN)) {
                    $data = [
                        'id' => null,
                        'product_id' => $product->product_id,
                        'serial_no' => $sn,
                        'batch' => null,
                        'prefix' => $prefix,
                        'numbering' => sprintf("%04d", $i),
                        'postfix' => $postfix,
                        'status' => 0,
                        'activated_at' => null,
                        'craeted_at' => date('Y-m-d H:i:s'),
                        'created_by' => '3',
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_by' => '3'
                    ];
                    DB::table('ms_products_serial')->insert($data);
                }
            }

            $return = [
                'status' => true,
                'message' => 'Serial Number Berhasil Ditambahkan'
            ];
        } else {
            $return = [
                'status' => false,
                'message' => 'Product Code Tidak Diketahui'
            ];
        }

        return response()->json($return, 200);
    }
}
