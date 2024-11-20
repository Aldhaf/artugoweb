<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use DB;
use APIodoo;

class ServiceCenterController extends Controller
{

    public function indexJSON(Request $request)
    {
        $service_city_id = $request->query('service_city_id');
        $whereRaw = "";

        if ($service_city_id) {
            $whereRaw = "ms_loc_city.city_id = " . $service_city_id;
        }

        $data = DB::table('ms_service_center')
            ->select('ms_service_center.*', 'store_location_regional.regional_name')
            ->leftJoin('store_location_regional', 'store_location_regional.id', '=', 'ms_service_center.region_id')
            ->join('ms_loc_city', 'ms_loc_city.sc_id', '=', 'ms_service_center.sc_id')
            ->whereRaw($whereRaw)
            ->get();

        return $data;
    }

    public function list_region()
    {
        $data['region'] = DB::table('store_location_regional')->get();
        return view('backend.servicecenter.list_region', $data);
    }

    public function list_service_center($region_id)
    {
        $data['region'] = DB::table('store_location_regional')->where('id', $region_id)->first();
        $data['service_center'] = DB::table('ms_service_center')->where('region_id', $region_id)->get();
        return view('backend.servicecenter.list_service_center', $data);
    }

    public function add_service_center($region_id)
    {
        $data['statusAction'] = 'insert';
        $data['region_id'] = $region_id;
        return view('backend.servicecenter.form_service_center', $data);
    }

    public function edit_service_center($region_id, $sc_id)
    {
        $data['statusAction'] = 'update';
        $data['service_center'] = DB::table('ms_service_center')->where('sc_id', $sc_id)->first();
        $data['region_id'] = $region_id;

        return view('backend.servicecenter.form_service_center', $data);
    }

    public function add_service_center_process(Request $request)
    {
        $data = [
            'sc_id' => null,
            'sc_code' => $request->input('sc_code'),
            'region_id' => $request->input('region_id'),
            'sc_location' => $request->input('sc_location'),
            'sc_address' => $request->input('sc_address'),
            'sc_phone' => $request->input('sc_phone'),
        ];
        DB::table('ms_service_center')->insert($data);
        // return redirect('artmin/servicecenter/'.$request->input('region_id'));
    }

    public function edit_service_center_process(Request $request)
    {
        $data = [
            'sc_code' => $request->input('sc_code'),
            'sc_location' => $request->input('sc_location'),
            'sc_address' => $request->input('sc_address'),
            'sc_phone' => $request->input('sc_phone'),
        ];
        DB::table('ms_service_center')->where('region_id', $request->input('region_id'))->where('sc_id', $request->input('sc_id'))->update($data);
    }

    public function delete_service_center_process($region_id, $sc_id)
    {
        $check_used = DB::table('ms_loc_city')->select('city_id')->where('sc_id', $sc_id)->first();
        if (!$check_used) {
            DB::table('ms_service_center')->where('region_id', $region_id)->where('sc_id', $sc_id)->delete();
            return [
                'success' => true,
                'message' => 'Deleted...'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Data tidak dapat dihapus karena sudah digunakan di City Location!'
            ];
        }
    }

}
