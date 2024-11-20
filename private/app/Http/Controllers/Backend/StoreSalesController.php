<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Query\JoinClause;

use Auth;
use Excel;
use DB;
use Hash;

use App\Products;
use App\Products_serial;
use App\Products_warranty;
use App\Reg_warranty;
use App\Reg_warranty_details;
use App\Exports\Excel\StoreSales;
use Yajra\DataTables\Facades\DataTables;

use EmailHelper;

class StoreSalesController extends Controller
{
    public function listSales(Request $request)
    {
        $data['sales'] = [];
        $start_date = ''; // date('Y-m-01');
        $end_date = ''; // date('Y-m-d');

        $sales_date_filter = $request->input('sales_date_filter');

        $store_id_filter = $request->get('store_id_filter') ?? '';
        $status_filter = $request->get('status_filter') ?? '';

        $data['period'] = '';

        if (isset($sales_date_filter)) {
            $expd = explode(' ', $sales_date_filter);
            if (!empty($expd[0]) && !empty($expd[2])) {
                $start_date = $expd[0];
                $end_date = $expd[2];
                $data['period'] = $start_date . ' - ' . $end_date;
            }
        }

        $data['store_id_filter'] = $store_id_filter;
        $data['status_filter'] = $status_filter;

        $keywords = $request->get('keywords') ?? '';
        $data['keywords'] = $keywords;

        if ($request->ajax()) {
            $sales_qb = DB::table('ms_store_sales')
                ->select(
                    'ms_store_sales.*',
                    'users.name as apc_name',
                    'store_location_regional.regional_name',
                    'store_location.nama_toko'
                )
                ->join('users', 'users.id', '=', 'ms_store_sales.users_id')
                ->join('store_location', 'store_location.id', '=', 'ms_store_sales.store_id')
                ->join('store_location_regional', 'store_location_regional.id', '=', 'store_location.regional_id')
                ->whereRaw('(ms_store_sales.status="' . $status_filter . '" OR "' . $status_filter . '" = "")');

            if ($start_date && $end_date) {
                $sales_qb->whereBetween('ms_store_sales.sales_date', [date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            }

            if ($keywords) {
                $sales_qb->whereRaw("(users.name LIKE '%" . $keywords . "%' OR users.name LIKE '%" . $keywords . "%' OR ms_store_sales.sales_no LIKE '%" . $keywords . "%' OR ms_store_sales.customer_nama LIKE '%" . $keywords . "%' OR ms_store_sales.customer_telp LIKE '%" . $keywords . "%')");
            }

            // if (Auth::user()->roles == '5') {
            //     $sales_qb->join('users', 'users.id', '=', 'ms_store_sales.users_id')
            //         ->where('ms_store_sales.users_id', Auth::user()->id);
            // } else {

                $sales_qb->join('ms_store_location_users', function($join) use($store_id_filter) {
                    // $join->on('ms_store_location_users.store_id', '=', 'ms_store_sales.store_id');
                    // $join->on('ms_store_location_users.users_id', '=', DB::raw(Auth::user()->id)); 
                    $join->on([
                        'ms_store_location_users.store_id' => 'ms_store_sales.store_id',
                        'ms_store_location_users.users_id' => DB::raw(Auth::user()->id)
                    ])->where('ms_store_location_users.status', 1);
                    if ($store_id_filter !== '') {
                        $join->where('ms_store_sales.store_id', $store_id_filter);
                    }
                });

                if (Auth::user()->roles == '5') {
                    $sales_qb->where('ms_store_sales.users_id', Auth::user()->id);
                }

                // ->where('ms_store_sales.store_id', $store_id_filter);

                // if ($store_id_filter != "") {
                //     $sales_qb->join('store_location', 'store_location.id', '=', 'users.store_id')
                //     ->where('ms_store_sales.users_id', Auth::user()->id);
                // }

                // $sales_qb->join('users', function($join){
                //     $join->on('users.store_id', '=', 'ms_store_sales.store_id');
                //     $join->on('users.id', '=', DB::raw(Auth::user()->id)); 
                // });

            // }

            return DataTables::of($sales_qb)->toJson();
        }

        // if (Auth::user()->roles == '5') {
        //     $data['store_location'] = DB::table('store_location')
        //         ->select('store_location.id', 'store_location.nama_toko')
        //         ->leftJoin('users', 'users.store_id', '=', 'store_location.id')
        //         ->where('users.id', Auth::user()->id)
        //         ->get();
        // } else if (Auth::user()->roles == '8') {
        $data['store_location'] = DB::table('ms_store_location_users')
            ->select('store_location.id', 'store_location.nama_toko')
            ->leftJoin('store_location', 'store_location.id', '=', 'ms_store_location_users.store_id')
            ->where('ms_store_location_users.users_id', Auth::user()->id)
            ->where('ms_store_location_users.status', 1)
            ->orderBy('store_location.idx', 'ASC')
            ->get();
        // }

        return view('backend.store_sales.list_sales', $data);
    }

    public function reportSales()
    {
        // $data['product'] = DB::table('ms_products')->where('status', '1')->get();
        $data['city'] = DB::table('ms_loc_city')->orderBy('province_id')->get();
        $data['product'] = DB::table('ms_products')->get();
        $data['statusAction'] = 'insert';
        $data['store_location_regional'] = DB::table('store_location_regional')
            ->select('store_location_regional.id', 'store_location_regional.regional_name')
            ->join('store_location', 'store_location.regional_id', '=', 'store_location_regional.id')
            ->join('ms_store_location_users', 'ms_store_location_users.store_id', '=', 'store_location.id')
            ->where('ms_store_location_users.users_id', Auth::user()->id)
            ->orderBy('store_location_regional.idx', 'ASC')
            ->groupBy('store_location_regional.id', 'store_location_regional.regional_name')
            ->get();

        $store_user = DB::table('ms_store_location_users')
            ->select(['store_location.id', 'store_location.nama_toko'])
            ->join('store_location', 'store_location.id', '=', 'ms_store_location_users.store_id')
            ->where('ms_store_location_users.users_id', Auth::user()->id)
            ->get();

        if (count($store_user) == 1) {
            $data['default_store_id'] = $store_user[0]->id;
            $data['default_store_name'] = $store_user[0]->nama_toko;
        }

        return view('backend.store_sales.report_sales', $data);
    }

    public function edit_sales($id)
    {
        $data['product'] = DB::table('ms_products')->get();
        $data['city'] = DB::table('ms_loc_city')->orderBy('province_id')->get();
        $data['sales'] = DB::table('ms_store_sales')->where('sales_id', $id)->first();
        $data['sales_product'] = DB::table('ms_store_sales_product')->where('sales_id', $id)->get();
        $data['statusAction'] = 'update';
        $data['store_location_regional'] = DB::table('store_location_regional')
            ->select('store_location_regional.id', 'store_location_regional.regional_name')
            ->join('store_location', 'store_location.regional_id', '=', 'store_location_regional.id')
            ->join('ms_store_location_users', 'ms_store_location_users.store_id', '=', 'store_location.id')
            ->where('ms_store_location_users.users_id', Auth::user()->id)
            ->orderBy('store_location_regional.idx', 'ASC')
            ->groupBy('store_location_regional.id', 'store_location_regional.regional_name')
            ->get();

        $store_user = DB::table('ms_store_location_users')
            ->select(['store_location.id', 'store_location.nama_toko'])
            ->join('store_location', 'store_location.id', '=', 'ms_store_location_users.store_id')
            ->where('ms_store_location_users.users_id', Auth::user()->id)
            ->get();

        if (count($store_user) == 1) {
            $data['default_store_id'] = $store_user[0]->id;
            $data['default_store_name'] = $store_user[0]->nama_toko;
        }

        return view('backend.store_sales.report_sales', $data);
    }

    public function report_sales_process(Request $request)
    {

        $rules = array(
            'sales_id' => 'required',
            'sales_date' => 'required',
            'customer_nama' => 'required',
            'customer_telp' => 'required',
            'customer_alamat' => 'required',
            // 'customer_email' => 'required',
            'customer_city' => 'required',
            'purchase_location' => 'required',
            'foto_struk' => 'required',
        );

        $rules_message = array(
            'sales_id.required' => 'No Penjualan harus diisi!',
            'sales_date.required' => 'Tanggal Penjualan harus diisi!',
            'customer_nama.required' => 'Nama Customer harus diisi!',
            'customer_telp.required' => 'No Telp Customer harus diisi!',
            'customer_alamat.required' => 'Alamat Customer harus diisi!',
            // 'customer_email.required' => 'Alamat Email Customer harus diisi!',
            'customer_city.required' => 'Customer City harus diisi!',
            'purchase_location.required' => 'Purchase Location harus diisi!',
            'foto_struk.required' => 'Foto Struk harus diupload!'
        );

        $rules['product_id.*'] = 'required';
        $rules_message['product_id.*.required'] = 'Product harus dipilih!';
        $rules['serialno.*'] = 'required';
        $rules_message['serialno.*'] = 'Serial Number harus diisi!';
        $rules['qty.*'] = 'required';
        $rules_message['qty.*.required'] = 'Qty harus diisi!';
        $rules['harga.*'] = 'required|min:1';
        $rules_message['harga.*.required'] = 'Harga harus diisi tidak boleh nol!';
        $rules['flag_categ_b.*'] = 'required';
        $rules_message['flag_categ_b.*.required'] = 'Kategori B Pilih Ya atau Tidak!';
        $rules['stock_type.*'] = 'required';
        $rules_message['stock_type.*.required'] = 'Stock Type harus dipilih!';

        $validator = Validator::make($request->all(), $rules, $rules_message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // $data_users = DB::table('users')->where('id', Auth::user()->id)->first();

        $file = $request->file('foto_struk');

        if (!empty($file)) {
            $upload_loc = 'uploads/struk/';
            $file_name = date("YmdHis-") . str_replace('/', '-', $request->input('sales_id')) . "." . $file->getClientOriginalExtension();
            $file->move($upload_loc, $file_name);

            $ms_store_sales = [
                'sales_id' => null,
                'store_id' => $request->input('store_id'),
                'sales_no' => $request->input('sales_id'),
                'sales_date' => date('Y-m-d', strtotime($request->input('sales_date'))),

                // 'warranty_no' => $request->input('warranty_no'),

                'customer_nama' => $request->input('customer_nama'),
                'customer_telp' => $request->input('customer_telp'),
                'customer_alamat' => $request->input('customer_alamat'),

                'sales_foto_struk' => url($upload_loc . $file_name),
                'users_id' => Auth::user()->id,

                'customer_email' => $request->input('customer_email'),
                'customer_city' => $request->input('customer_city'),
                'purchase_location' => $request->input('purchase_location'),
                'status' => 0,

                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $sales_id = DB::table('ms_store_sales')->insertGetId($ms_store_sales);

            if (!empty($request->input('product_id'))) {
                foreach ($request->input('product_id') as $key => $value) {
                    $ms_store_sales_product = [
                        'detail_sales_id' => null,
                        'sales_id' => $sales_id,
                        'store_id' => $request->input('store_id'),
                        'product' => $request->input('product_id')[$key],
                        'serialno' => $request->input('serialno')[$key],
                        'qty' => $request->input('qty')[$key],
                        'harga' => str_replace(',', '', $request->input('harga')[$key]),
                        'flag_categ_b' => $request->input('flag_categ_b')[$key],
                        'stock_type' => $request->input('stock_type')[$key],
                        'users_id' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    DB::table('ms_store_sales_product')->insert($ms_store_sales_product);
                }
            }
            return redirect('artmin/storesales/edit/' . $sales_id);
        }
    }

    public function edit_sales_process(Request $request)
    {

        $rules = array(
            'sales_id' => 'required',
            'sales_date' => 'required',
            'customer_nama' => 'required',
            'customer_telp' => 'required',
            'customer_alamat' => 'required',
            // 'customer_email' => 'required',
            'customer_city' => 'required',
            'purchase_location' => 'required'
        );

        $rules_message = array(
            'sales_id.required' => 'No Penjualan harus diisi!',
            'sales_date.required' => 'Tanggal Penjualan harus diisi!',
            'customer_nama.required' => 'Nama Customer harus diisi!',
            'customer_telp.required' => 'No Telp Customer harus diisi!',
            'customer_alamat.required' => 'Alamat Customer harus diisi!',
            // 'customer_email.required' => 'Alamat Email Customer harus diisi!',
            'customer_city.required' => 'Customer City harus diisi!',
            'purchase_location.required' => 'Purchase Location harus diisi!'
        );

        $rules['product_id.*'] = 'required';
        $rules_message['product_id.*.required'] = 'Product harus dipilih!';
        $rules['serialno.*'] = 'required';
        $rules_message['serialno.*'] = 'Serial Number harus diisi!';
        $rules['qty.*'] = 'required';
        $rules_message['qty.*.required'] = 'Qty harus diisi!';
        $rules['harga.*'] = 'required|min:1';
        $rules_message['harga.*.required'] = 'Harga harus diisi tidak boleh nol!';
        $rules['flag_categ_b.*'] = 'required';
        $rules_message['flag_categ_b.*.required'] = 'Kategori B Pilih Ya atau Tidak!';
        $rules['stock_type.*'] = 'required';
        $rules_message['stock_type.*.required'] = 'Stock Type harus dipilih!';

        $validator = Validator::make($request->all(), $rules, $rules_message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // $data_users = DB::table('users')->where('id', Auth::user()->id)->first();

        $file = $request->file('foto_struk');

        if (!empty($file)) {
            $upload_loc = 'uploads/struk/';
            $file_name = date("YmdHis-") . str_replace('/', '-', $request->input('sales_id')) . "." . $file->getClientOriginalExtension();
            $file->move($upload_loc, $file_name);

            $ms_store_sales = [
                'sales_no' => $request->input('sales_id'),
                'sales_date' => date('Y-m-d', strtotime($request->input('sales_date'))),
                'sales_foto_struk' => url($upload_loc . $file_name),

                // 'warranty_no' => $request->input('warranty_no'),

                'customer_nama' => $request->input('customer_nama'),
                'customer_telp' => $request->input('customer_telp'),
                'customer_alamat' => $request->input('customer_alamat'),

                'customer_email' => $request->input('customer_email'),
                'customer_city' => $request->input('customer_city'),
                'purchase_location' => $request->input('purchase_location'),

                'updated_at' => date('Y-m-d H:i:s')
            ];
        } else {
            $ms_store_sales = [
                'sales_no' => $request->input('sales_id'),
                'sales_date' => date('Y-m-d', strtotime($request->input('sales_date'))),

                // 'warranty_no' => $request->input('warranty_no'),

                'customer_nama' => $request->input('customer_nama'),
                'customer_telp' => $request->input('customer_telp'),
                'customer_alamat' => $request->input('customer_alamat'),

                'customer_email' => $request->input('customer_email'),
                'customer_city' => $request->input('customer_city'),
                'purchase_location' => $request->input('purchase_location'),

                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        DB::table('ms_store_sales')->where('sales_id', $request->input('sales_unique_id'))->update($ms_store_sales);

        $sales_id = $request->input('sales_unique_id');
        DB::table('ms_store_sales_product')->where('sales_id', $sales_id)->delete();
        if (!empty($request->input('product_id'))) {
            foreach ($request->input('product_id') as $key => $value) {
                $ms_store_sales_product = [
                    'detail_sales_id' => null,
                    'sales_id' => $sales_id,
                    'store_id' => $request->input('store_id'),
                    'product' => $request->input('product_id')[$key],
                    'serialno' => $request->input('serialno')[$key],
                    'qty' => $request->input('qty')[$key],
                    'harga' => str_replace(',', '', $request->input('harga')[$key]),
                    'users_id' => Auth::user()->id,
                    'flag_categ_b' => $request->input('flag_categ_b')[$key],
                    'stock_type' => $request->input('stock_type')[$key],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                DB::table('ms_store_sales_product')->insert($ms_store_sales_product);
            }
        }
        
        return redirect('artmin/storesales/edit/' . $sales_id);
    }

    public function detail_products($sales_id)
    {
        $data['sales'] = DB::table('ms_store_sales')->where('sales_id', $sales_id)->first();
        $data['product'] = DB::table('ms_store_sales_product')
            ->select(
                'ms_store_sales_product.*',
                'ms_products.product_name_odoo as product_name'
            )
            ->where('ms_store_sales_product.sales_id', $sales_id)
            ->leftJoin('ms_products', 'ms_store_sales_product.product', '=', 'ms_products.product_id')
            ->get();
        return view('backend.store_sales.detail_products', $data);
    }

    public function delete_sales_process(Request $request)
    {
        DB::table('ms_store_sales')->where('sales_id', $request->input('sales_id'))->delete();
        DB::table('ms_store_sales_product')->where('sales_id', $request->input('sales_id'))->delete();
    }

    public function export_data($from, $to)
    {
        $export = new StoreSales;
        $export->setDateFrom(date('Y-m-d', strtotime($from)));
        $export->setDateTo(date('Y-m-d', strtotime($to)));

        return Excel::download($export, 'artugo_digital_store_sales_' . date('Ymd') . '.xlsx');
    }

    public function add_form_region()
    {
        $data['statusAction'] = 'insert';
        return view('backend.storelocation.form_region', $data);
    }
    public function edit_form_region($region_id)
    {
        $data['statusAction'] = 'update';
        $data['region'] = DB::table('store_location_regional')->where('id', $region_id)->first();
        return view('backend.storelocation.form_region', $data);
    }

    public function process_add(Request $request)
    {
        $index = DB::table('store_location_regional')->orderBy('idx', 'desc')->first();
        $data = [
            'id' => null,
            'regional_name' => $request->input('nama_region'),
            'idx' => (!empty($index) ? $index->idx + 1 : 1)
        ];
        DB::table('store_location_regional')->insert($data);
    }

    public function process_edit(Request $request)
    {
        $data = [
            'regional_name' => $request->input('nama_region'),
        ];
        DB::table('store_location_regional')->where('id', $request->input('region_id'))->update($data);
    }

    public function debug()
    {
        $store_sales =  DB::table('ms_store_sales_product')
            ->select(
                'ms_store_sales.sales_id',
                'ms_store_sales.sales_no',
                'ms_store_sales.sales_date',

                // 'ms_store_sales.customer_nama',
                // 'ms_store_sales.customer_alamat',
                // 'ms_store_sales.customer_telp',

                // 'store_location.nama_toko',
                // 'store_location_regional.regional_name',
                // 'ms_products.product_name_odoo',
                // 'users.name as karyawanName',

                'ms_store_sales_product.qty',
                'ms_store_sales_product.harga',
            )
            ->leftJoin('ms_store_sales', 'ms_store_sales.sales_id', '=', 'ms_store_sales_product.sales_id')
            // ->leftJoin('store_location', 'store_location.id', '=', 'ms_store_sales_product.store_id')
            // ->leftJoin('ms_products', 'ms_products.product_id', '=', 'ms_store_sales_product.product')
            // ->leftJoin('users', 'users.id', '=', 'ms_store_sales_product.users_id')
            // ->leftJoin('store_location_regional', 'store_location.regional_id', '=', 'store_location_regional.id')
            ->whereBetween('ms_store_sales.sales_date', ['2021-01-01', '2021-07-31'])
            ->where('ms_store_sales_product.users_id', '101')
            ->orderBy('ms_store_sales.sales_date')
            ->get();

        foreach ($store_sales as $key => $value) {
            print_r($value);

            echo "<br><hr><br>";
        }
    }

    public function approve_sales_confirm(Request $request)
    {
        $sales = DB::table('ms_store_sales')->select(['sales_id', 'customer_email', 'customer_telp'])->where('sales_id', $request->input('sales_id'))->first();
        if (!$sales) {
            return ['status' => false, 'message' => 'Data Penjualan ' . $request->input('sales_no') . ' tidak valid!'];
        }
        $member = DB::table('ms_members')
            ->select(['id', 'name', 'email', 'phone', 'address', 'city', 'city_id'])
            // ->where('email', $sales->customer_email)
            ->where('phone', $sales->customer_telp)
            ->where('status', 1)
            ->first();
        if ($member) {
            return ['message' => 'Nomor dan email sudah terdaftar sebagai member ' . $member->name . ' mohon periksa kembali data customer. Jika benar silahkan lanjutkan!'];
        }

        // $serialnoNull = DB::table('ms_store_sales_product')
        //     ->select("serialno")
        //     ->where('sales_id', $sales->sales_id)
        //     ->whereNull('serialno')
        //     ->count();

        // if ($serialnoNull > 0) {
        //     return ['success' => false, 'message' => 'Serial No tidak boleh kosong!'];
        // }

        // $serialno = DB::table('ms_store_sales_product')
        //     ->select(['ms_store_sales_product.serialno'])
        //     ->leftJoin('ms_products_serial', DB::raw("REPLACE(ms_products_serial.serial_no, ' ', '')"), '=', 'ms_store_sales_product.serialno')
        //     ->where('ms_store_sales_product.sales_id', $sales->sales_id)
        //     ->whereNull('ms_products_serial.serial_no')
        //     ->get();

        // if(count($serialno) > 0) {
        //     $serialno_notregistered = count($serialno) > 1 ? '( ' . collect($serialno)->pluck('serialno')->join(', ') . ' )' : $serialno[0]->serialno;
        //     return ['success' => false, 'message' => 'Serial No ' . $serialno_notregistered . ' belum di daftarkan di Product Serial Number!'];
        // }
        
        return ['message' => ''];
    }

    public function approve_sales_process(Request $request)
    {

        $current_time = date("Y-m-d H:i:s");
        $autoRegisterMemberWarranty = false;

        $sales = DB::table('ms_store_sales')->where('sales_id', $request->input('sales_id'))->first();
        if (!$sales) {
            return ['success' => false, 'message' => 'Data Penjualan ' . $request->input('sales_no') . ' tidak valid!'];
        }

        $reg_warranty = DB::table('ms_store_sales_product')
            ->select(['reg_warranty.warranty_id', 'reg_warranty.serial_no'])
            ->join('reg_warranty', function (JoinClause $join) {
                $join->on(['ms_store_sales_product.serialno' => 'reg_warranty.serial_no', 'ms_store_sales_product.product' => 'reg_warranty.product_id'])
                ->where('reg_warranty.stock_type', 'stkavailable');
            })
            ->where('ms_store_sales_product.sales_id', $sales->sales_id)
            ->first();
        if ($reg_warranty) {
            return ['success' => false, 'message' => 'Product dengan serial number ' . $reg_warranty->serial_no . ' sudah terpakai di Warranty!'];
        }

        $sales_products = DB::table('ms_store_sales_product')->where('sales_id', $sales->sales_id)->get();
        if(count($sales_products) == 0) {
            return ['success' => false, 'message' => 'Tidak ada product yang akan di input Warranty!'];
        }

        $serialnoNull = DB::table('ms_store_sales_product')
            ->select("serialno")
            ->where('sales_id', $sales->sales_id)
            ->whereNull('serialno')
            ->count();

        if ($serialnoNull > 0) {
            return ['success' => false, 'message' => 'Serial No tidak boleh kosong!'];
        }

        $serialno = DB::table('ms_store_sales_product')
            ->select(['ms_store_sales_product.serialno'])
            // ->leftJoin('ms_products_serial', DB::raw("REPLACE(ms_products_serial.serial_no, ' ', '')"), '=', 'ms_store_sales_product.serialno')
            ->leftJoin('ms_products_serial', function($join) {
                $join->on([
                    'ms_products_serial.product_id' => 'ms_store_sales_product.product',
                    'ms_store_sales_product.serialno' => DB::raw("REPLACE(ms_products_serial.serial_no, ' ', '')")
                ]);
            })
            ->where('ms_store_sales_product.sales_id', $sales->sales_id)
            ->whereNull('ms_products_serial.serial_no')
            ->get();

        if(count($serialno) > 0) {
            $serialno_notregistered = count($serialno) > 1 ? '( ' . collect($serialno)->pluck('serialno')->join(', ') . ' )' : $serialno[0]->serialno;
            return ['success' => false, 'message' => 'Serial No ' . $serialno_notregistered . ' belum di daftarkan di Product Serial Number!'];
        }

        // status 1 konfirmasi dari APC, status 2 approved branch manager, status 3 batal/Rejected
        if ($sales->status === 0 && Auth::user()->roles == '5') {
            $update_sales = [
                'status' => 1
            ];
        } else if ($sales->status === 1 && Auth::user()->roles == '8') {

            if ($autoRegisterMemberWarranty) {
                $city = DB::table('ms_loc_city')->where('city_id', $sales->customer_city)->first();

                $member_id = 0;
                $member = DB::table('ms_members')
                    ->select(['id', 'name', 'email', 'phone', 'address', 'city', 'city_id'])
                    // ->where('email', $sales->customer_email)
                    ->where('phone', $sales->customer_telp)
                    ->where('status', 1)
                    ->first();
                if ($member) {
                    $member_id = $member->id;
                } else {
                    $new_member['name'] = $sales->customer_nama;
                    $new_member['phone'] = $sales->customer_telp;
                    $new_member['address'] = $sales->customer_alamat;
                    $new_member['city_id'] = $sales->customer_city;
                    $new_member['city'] = $city->city_name;
                    $new_member['email'] = $sales->customer_email;
                    $new_member['password'] = Hash::make($sales->customer_telp);
                    $new_member['status'] = 1;
                    $new_member['created_at'] = $current_time;
                    $add_member = DB::table('ms_members')->insertGetId($new_member);
                    $member_id = $add_member;
                }

                foreach ($sales_products as $prod) {

                    $period = date("Ym");
                    $check_warranty = Reg_warranty::where("warranty_no", "LIKE", $period . "%")->orderBy('warranty_id', 'desc')->first();
                    $warranty_no = '';
                    if (!isset($check_warranty->warranty_id)) {
                        $warranty_no = $period . str_pad(1, 4, "0", STR_PAD_LEFT);
                    } else {
                        $last_warranty_no = $check_warranty->warranty_no;
                        $last_warranty_no = substr($last_warranty_no, -4, 4);
                        $last_warranty_no = (int)$last_warranty_no;
                        $warranty_no = $period . str_pad($last_warranty_no + 1, 4, "0", STR_PAD_LEFT);
                    }

                    $check_serial = Products_serial::where(DB::raw("REPLACE(serial_no, ' ', '')"), 'LIKE', $prod->serialno)->where("product_id", $prod->product)->first();
                    $is_unique_number = false;
                    $check_special_voucher = DB::table('unique_number')
                        ->where('unique_number', ltrim($check_serial['numbering'], '0'))
                        ->where('status', '1')
                        ->whereDate('date_from', '<=', date('Y-m-d'))
                        ->whereDate('date_to', '>=', date('Y-m-d'))
                        ->first();
                    if (!empty($check_special_voucher)) {
                        $check_special_voucher_product = DB::table('unique_number_products')->where('unique_number', $check_special_voucher->id)->where('products_id', $prod->product)->first();
                        if (!empty($check_special_voucher_product)) {
                            $is_unique_number = true;
                        }
                    }

                    $warranty = [
                        'warranty_no' => $warranty_no,
                        'purchase_date' => $sales->sales_date,
                        'purchase_loc' => $sales->purchase_location,
                        'files' => $sales->sales_foto_struk,
                        'member_id' => $member_id,
                        'reg_name' => $sales->customer_nama,
                        'reg_address' => $sales->customer_alamat,
                        'reg_phone' => $sales->customer_telp,
                        'reg_email' => $sales->customer_email,
                        'reg_city_id' => $sales->customer_city,
                        'reg_city' => $city->city_name,
                        'spg_ref' => $sales->users_id,
                        'created_by' => Auth::user()->id,
                        'reg_type' => Auth::user()->roles,
                        'verified' => 0,
                        'status' => 1,
                        'install_status' => 0,
                        'stock_type' => $prod->stock_type ?? 'stkavailable',
                        'created_at' => $current_time,
                        'updated_at' => $current_time,
                        'product_id' => $prod->product,
                        'serial_no' => $prod->serialno,
                    ];

                    $insert_warranty = Reg_warranty::insertGetId($warranty);

                    if ($insert_warranty) {
                        // Insert Warranty Details
                        $list_warranty = Products_warranty::where('product_id', $prod->product)->get();
                        foreach ($list_warranty as $lw) {
                            $details['warranty_reg_id'] = $insert_warranty;
                            $details['warranty_type'] = $lw->warranty_title;
                            $details['warranty_start'] = date("Y-m-d 00:00:00", strtotime($sales->sales_date));
                            $warranty_year = "+1 year";
                            if ($lw->warranty_year == 1) {
                                $warranty_year = "+ 1 year";
                            } else if ($lw->warranty_year > 1) {
                                $warranty_year = "+ " . $lw->warranty_year . " years";
                            }

                            $details['warranty_end'] = date("Y-m-d 23:59:59", strtotime($sales->sales_date . " " . $warranty_year));
                            $details['warranty_period'] = $lw->warranty_year;
                            $details['created_at'] = $current_time;
                            $details['created_by'] = Auth::user()->id;

                            $insert_detail = Reg_warranty_details::insert($details);
                        }

                        $update_serial_data['status'] = 1;
                        $update_serial = Products_serial::where('id', $check_serial->id)->update($update_serial_data);
                    }

                    if ($sales->customer_email != '') {
                        $data['to'] = $sales->customer_email;
                        $data['name'] = $sales->customer_nama;
                        $data['warranty'] = Reg_warranty::where('warranty_id', $insert_warranty)->first();
                        $data['warranty_list'] = Reg_warranty_details::where('warranty_reg_id', $insert_warranty)->get();
                        $data['product'] = Products::where('product_id', $prod->product)->first();

                        EmailHelper::warranty_registration($data);

                        if ($is_unique_number) {

                            $special_voucher = [
                                'warranty_id' => $insert_warranty,
                                'unique_number_id' => $check_special_voucher->id,
                                'status' => 1,
                                'created_at' => $current_time,
                                'updated_at' => $current_time,
                            ];
                            DB::table('special_voucher')->insert($special_voucher);

                            $mailSpecialNumber['to'] = $sales->customer_email;
                            $mailSpecialNumber['name'] = $sales->customer_nama;
                            $mailSpecialNumber['warranty_no'] = $warranty_no;
                            $mailSpecialNumber['serial_number'] = str_replace(" ", "", $prod->serialno);
                            $mailSpecialNumber['cashback'] = $check_special_voucher->cashback;
                            EmailHelper::special_voucher($mailSpecialNumber);
                        }
                    }

                }
            }

            $update_sales = [
                'status' => 2,
                'approved_by' => Auth::user()->id,
                'approved_at' => $current_time
            ];
        }

        if (isset($update_sales)) {
            DB::table('ms_store_sales')->where('sales_id', $sales->sales_id)->update($update_sales);
        }

        return ['success' => true, 'message' => ''];

    }

    public function dashboard(Request $request)
    {
        $regional_id_filter = $request->get('regional_id') ?? '';
        $store_id_filter = $request->get('store_id') ?? '';
        $status_filter = $request->get('status') ?? '';

        $sales_qb = DB::table('ms_store_sales')
            ->select(
                DB::raw('MONTH(ms_store_sales.sales_date) AS month'),
                DB::raw('COUNT(MONTH(ms_store_sales.sales_date)) AS total')
            )
            ->join('users', 'users.id', '=', 'ms_store_sales.users_id')
            ->join('store_location', 'store_location.id', '=', 'ms_store_sales.store_id')
            ->join('store_location_regional', 'store_location_regional.id', '=', 'store_location.regional_id')
            ->join('ms_store_location_users', function($join) use($regional_id_filter, $store_id_filter) {
            $join->on([
                'ms_store_location_users.store_id' => 'ms_store_sales.store_id',
                'ms_store_location_users.users_id' => DB::raw(Auth::user()->id)
            ])->where('ms_store_location_users.status', 1);

            if ($regional_id_filter !== '') {
                $join->where('store_location.regional_id', $regional_id_filter);
            }

            if ($store_id_filter !== '') {
                $join->where('ms_store_sales.store_id', $store_id_filter);
            }
        });

        if (Auth::user()->roles == '5') {
            $sales_qb->where('ms_store_sales.users_id', Auth::user()->id);
        }

        $sales_qb
            ->whereRaw('(ms_store_sales.status="' . $status_filter . '" OR "' . $status_filter . '" = "")')
            ->whereRaw(DB::raw('YEAR(ms_store_sales.sales_date) = ' . ($request->input('year') ?? date('Y'))))
            ->groupBy(DB::raw('MONTH(ms_store_sales.sales_date)'))
            ->orderBy(DB::raw('MONTH(ms_store_sales.sales_date)'));

        return $sales_qb->get();
    }    

}
