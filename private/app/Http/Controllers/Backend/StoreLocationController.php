<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Query\JoinClause;

use Auth;
use DB;
use APIodoo;

class StoreLocationController extends Controller
{
    public function list_region()
    {
        $data['region'] = DB::table('store_location_regional')->get();

        $data['period'] = date('Y-m-01') . ' - ' . date('Y-m-d');
        return view('backend.storelocation.list_region', $data);
    }

    public function list_store_location($region_id)
    {
        $data['region'] = DB::table('store_location_regional')->where('id', $region_id)->first();
        $data['store'] = DB::table('store_location')->where('regional_id', $region_id)->get();

        return view('backend.storelocation.list_store', $data);
    }

    public function add_store_location($region_id)
    {
        $data['statusAction'] = 'insert';
        $data['regional_id'] = $region_id;
        $data['data_partner'] = json_decode(APIodoo::get_partner())->result->response;
        
        return view('backend.storelocation.form_store', $data);
    }

    public function edit_store_location($region_id, $store_id)
    {
        $data['statusAction'] = 'update';
        $data['store'] = DB::table('store_location')->where('id', $store_id)->first();
        $data['regional_id'] = $region_id;
        $data['data_partner'] = json_decode(APIodoo::get_partner())->result->response;

        return view('backend.storelocation.form_store', $data);
    }

    public function add_store_location_process(Request $request)
    {
        $data = [
            'id' => null,
            'regional_id' => $request->input('regional_id'),
            'partner_id' => $request->input('partner_id'),
            'nama_toko' => $request->input('nama_toko'),
            'alamat_toko' => $request->input('alamat_toko'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'discount_area' => $request->input('discount_area'),
        ];
        DB::table('store_location')->insert($data);
    }

    public function edit_store_location_process(Request $request)
    {
        $data = [
            'partner_id' => $request->input('partner_id'),
            'nama_toko' => $request->input('nama_toko'),
            'alamat_toko' => $request->input('alamat_toko'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'discount_area' => $request->input('discount_area'),
        ];
        DB::table('store_location')->where('id', $request->input('store_id'))->update($data);
    }

    public function assign_promotor($region_id, $store_id)
    {
        // $data['spg'] = DB::table('users')->where('roles', '5')->where('store_id', null)->get();

        $data['spg'] = DB::table('users')
            ->select('users.*')
            ->leftJoin('ms_store_location_users', function (JoinClause $join) use($store_id) {
                $join->on(['ms_store_location_users.users_id' => 'users.id'])
                    ->where('ms_store_location_users.status', 1)
                    ->where('ms_store_location_users.store_id', $store_id);
            })
            ->where('ms_store_location_users.store_id', null)
            ->get();

        // $data['inchargespg'] = DB::table('users')->where('store_id', $store_id)->get();

        $data['inchargespg'] = DB::table('users')
            ->select('users.*')
            ->join('ms_store_location_users', 'ms_store_location_users.users_id', '=', 'users.id')
            ->where('ms_store_location_users.status', 1)
            ->where('ms_store_location_users.store_id', $store_id)->get();

        $data['store'] = DB::table('store_location')->where('id', $store_id)->first();
        $data['region'] = DB::table('store_location_regional')->where('id', $region_id)->first();
        return view('backend.storelocation.assign_promotor', $data);
    }

    public function assign_promotor_process(Request $request)
    {
        // $data = [
        //     'store_id' => $request->input('store_id')
        // ];
        // DB::table('users')->where('id', $request->input('users_id'))->update($data);

        $store_id = $request->input('store_id');
        $users_id = $request->input('users_id');

        $store_users = DB::table('ms_store_location_users')->where([
            'store_id' => $store_id,
            'users_id' => $users_id
        ])->first();

        if ($store_users == null) {
            DB::table('ms_store_location_users')->insert([
                'users_id' => $users_id,
                'store_id' => $store_id,
                'status' => 1,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            DB::table('ms_store_location_users')
                ->where([
                    'store_id' => $store_id,
                    'users_id' => $users_id
                ])->update(['status' => 1, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => Auth::user()->id]);
        }
    }

    public function remove_promotor_process(Request $request)
    {
        // $data = [
        //     'store_id' => null
        // ];
        // DB::table('users')->where('id', $request->input('users_id'))->update($data);

        $store_id = $request->input('store_id');
        $users_id = $request->input('users_id');

        DB::table('ms_store_location_users')->where([
            'store_id' => $store_id,
            'users_id' => $users_id
        ])->update(['status' => 0, 'updated_at' => date('Y-m-d H:i:s'), 'deleted_by' => Auth::user()->id]);
    }

    public function sales_report($region_id, $store_id)
    {
        // $sales = DB::table('ms_store_sales')
        //     ->select(
        //         'ms_store_sales.*',
        //         'users.name as apcName'
        //     )
        //     ->leftJoin('users', 'users.id', '=', 'ms_store_sales.users_id')
        //     ->where('ms_store_sales.store_id', $store_id)
        //     ->orderBy('ms_store_sales.created_at', 'desc');

        // if (Auth::user()->roles == '5') {
        //     $data['sales'] = $sales->where('ms_store_sales.users_id',Auth::user()->id)->get();
        // } else {
        //     $data['sales'] = $sales->get();
        // }

        // return view('backend.store_sales.list_sales', $data);
        return redirect('artmin/storesales?store_id_filter=' . $store_id . '&status_filter=&keywords=');
    }

    public function all_json(Request $request)
    {
        $regional_id = $request->get('regional_id');
        $keywords = $request->get('q');
        $qb = DB::table('store_location')->where('regional_id', $regional_id)->where('nama_toko', 'LIKE', '%' . $keywords . '%');

        $users_id = $request->get('users_id') ?? "";

        if ($users_id !== "") {
            $qb->join('ms_store_location_users', function($join) use($users_id) {
                $join->on([
                    'ms_store_location_users.store_id' => 'store_location.id',
                    'ms_store_location_users.users_id' => DB::raw($users_id)
                ])->where('ms_store_location_users.status', 1);
            });
        }

        $locations = $qb->orderBy('idx')->limit(20)->get();
        return $locations;
    }

}
