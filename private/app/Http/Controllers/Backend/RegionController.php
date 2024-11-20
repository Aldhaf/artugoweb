<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Excel;
use DB;

use App\Exports\Excel\StoreSales;

class RegionController extends Controller
{

    public function add_form_region(Request $request)
    {
        $data['statusAction'] = 'insert';
        $data['redirect_to'] = $request->query('redirect_to') ?? "storelocation";
        return view('backend.region.form_region', $data);
    }

    public function edit_form_region(Request $request, $region_id)
    {
        $data['statusAction'] = 'update';
        $data['redirect_to'] = $request->query('redirect_to') ?? "storelocation";
        $data['region'] = DB::table('store_location_regional')->where('id', $region_id)->first();
        return view('backend.region.form_region', $data);
    }

    public function process_add(Request $request)
    {
        $redirect_to = $request->query('redirect_to') ?? "storelocation";

        $index = DB::table('store_location_regional')->orderBy('idx', 'desc')->first();
        $data = [
            'id' => null,
            'regional_name' => $request->input('nama_region'),
            'idx' => (!empty($index) ? $index->idx + 1 : 1)
        ];
        DB::table('store_'.$redirect_to.'_regional')->insert($data);
    }

    public function process_edit(Request $request)
    {
        $redirect_to = $request->query('redirect_to') ?? "storelocation";

        $data = [
            'regional_name' => $request->input('nama_region'),
        ];
        DB::table('store_'.$redirect_to.'_regional')->where('id', $request->input('region_id'))->update($data);
    }

    public function all_json(Request $request)
    {
        $keywords = $request->get('q');
        $qb = DB::table('store_location_regional')
            ->select('store_location_regional.*')
            ->where('regional_name', 'LIKE', '%' . $keywords . '%');

        $users_id = $request->get('users_id') ?? "";

        if ($users_id !== "") {
            $qb->join('store_location', 'store_location.regional_id', '=', 'store_location_regional.id')
            ->join('ms_store_location_users', function($join) use($users_id) {
                $join->on([
                    'ms_store_location_users.store_id' => 'store_location.id',
                    'ms_store_location_users.users_id' => DB::raw($users_id)
                ])->where('ms_store_location_users.status', 1);
            });
        }

        return $qb->orderBy('idx')->limit(20)->get();
    }

}
