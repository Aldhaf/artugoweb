<?php

namespace App\Http\Controllers\Backend;

use DB;
use Auth;

use App\Products;
use App\Products_serial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SerialNumberController extends Controller
{
    public function index()
    {
        $data['products'] = DB::table('ms_products')->get();

        $data_sn = null;

        if (!empty($_GET['product'])) {
            if ($_GET['product'] == '-') {
                $data_sn = Products_serial::select(
                    'ms_products_serial.id',
                    'ms_products_serial.serial_no',
                    'ms_products_serial.craeted_at',
                    'ms_products_serial.status',
                    'ms_products.product_name'
                )
                    ->join('ms_products', 'ms_products.product_id', '=', 'ms_products_serial.product_id');
            } else {
                $data_sn = Products_serial::select(
                    'ms_products_serial.id',
                    'ms_products_serial.serial_no',
                    'ms_products_serial.craeted_at',
                    'ms_products_serial.status',
                    'ms_products.product_name'
                )
                    ->join('ms_products', 'ms_products.product_id', '=', 'ms_products_serial.product_id')
                    ->where('ms_products_serial.product_id', $_GET['product']);
            }
        }

        $data['serialnumber'] = (!empty($data_sn) ? $data_sn->get() : null);

        return view('backend.serialnumber.list', $data);
    }

    public function add_serialnumber()
    {
        $data['products'] = Products::get();
        $data['city'] = DB::table('ms_loc_city')->orderBy('province_id')->get();
        $data['customer'] = DB::table('ms_members')->where('status', 1)->get();

        return view('backend.serialnumber.add-serialnumber', $data);
    }

    public function add_serialnumber_process(Request $request)
    {
        $prefix = $request->input('prefix');
        $start = $request->input('start');
        $end = $request->input('end');
        $postfix = $request->input('postfix');

        for ($i = $start; $i <= $end; $i++) {

            $sn = $prefix . sprintf("%04d", $i) . $postfix;

            $checkSN = DB::table('ms_products_serial')->where('serial_no', $sn)->first();

            if (empty($checkSN)) {
                $data = [
                    'id' => null,
                    'product_id' => $request->input('product_id'),
                    'serial_no' => $sn,
                    'batch' => null,
                    'prefix' => $prefix,
                    'numbering' => sprintf("%04d", $i),
                    'postfix' => $postfix,
                    'status' => 0,
                    'activated_at' => null,
                    'craeted_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ];
                DB::table('ms_products_serial')->insert($data);
            }
        }
    }

    public function reactivate(Request $request)
    {
        $data = [
            'status' => 0
        ];
        DB::table('ms_products_serial')->where('id', $request->input('serial_id'))->update($data);
    }
}
