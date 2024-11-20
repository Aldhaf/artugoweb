<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

use Auth;
use DB;
use App\Technician;

class TechnicianController extends Controller
{
    public function index()
    {
        $data['technician'] = Technician::select('ms_technician.*', 'ms_service_center.sc_location', 'ms_technician_type.description AS technician_type')
        ->join('ms_service_center', 'ms_technician.sc_id', '=', 'ms_service_center.sc_id')
        ->leftJoin('ms_technician_type', 'ms_technician.technician_type_id', '=', 'ms_technician_type.id')
        ->get();

        return view('backend.technician.list', $data);
    }

    public function indexJSON(Request $request)
    {
        $qb = Technician::select('id', 'sc_id', 'technician_id', 'name')->where('status', 1);

        $sc_id = $request->query('sc_id');
        if ($sc_id) {
            $whereRaw = "sc_id = " . $sc_id;
            $qb->whereRaw($whereRaw);
        }

        $keywords = $request->get('q') ?? '';
        if ($keywords) {
            $qb->whereRaw("(technician_id LIKE '%" . $keywords . "%' OR name LIKE '%" . $keywords . "%')");
        }

        $data = $qb->get();

        return $data;
    }

    public function add_technician()
    {
        $data['servicecenter'] = DB::table('ms_service_center')->get();
        $data['technician_type'] = DB::table('ms_technician_type')->get();

        return view('backend.technician.add_technician', $data);
    }

    public function add_technician_process(Request $request)
    {
        $rules = array(
            'technician_type_id' => 'required',
            'sc_id' => 'required',
            'technician' => 'required',
            'phone' => 'required',
            'cover_area' => 'required',
            'email' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $check_id = Technician::orderBy('technician_id', 'desc')->first();

            $technician_id = '';

            if (!empty($check_id)) {
                $last_technician_id = $check_id->technician_id;
                $last_technician_id = substr($last_technician_id, -3, 3);
                $last_technician_id = (int)$last_technician_id;
                $technician_id = 'TC' . str_pad($last_technician_id + 1, 3, "0", STR_PAD_LEFT);
            } else {
                $technician_id = 'TC001';
            }


            $data = [
                'id' => null,
                'technician_type_id' => $request->input('technician_type_id'),
                'technician_id' => $technician_id,
                'sc_id' => $request->input('sc_id'),
                'name' => $request->input('technician'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'join_date' => $request->input('join_date'),
                'status' => ($request->input('status') == 'on' ? 1 : 0),
                'odoo_user_id' => $request->input('odoo_user_id'),
                'cover_area' => $request->input('cover_area'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id,
            ];
            // print_r($data);
            Technician::insert($data);

            return redirect('artmin/technician');
        }
    }

    public function edit_technician($technician_id)
    {
        $data['technician'] = Technician::where('technician_id', $technician_id)->first();
        $data['servicecenter'] = DB::table('ms_service_center')->get();
        $data['technician_type'] = DB::table('ms_technician_type')->get();

        if (!empty($data['technician'])) {
            return view('backend.technician.edit_technician', $data);
        } else {
            return redirect('artmin/technician');
        }
    }

    public function edit_technician_process(Request $request)
    {
        $rules = array(
            'technician_type_id' => 'required',
            'sc_id' => 'required',
            'technician' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'cover_area' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = [
                'sc_id' => $request->input('sc_id'),
                'technician_type_id' => $request->input('technician_type_id'),
                'name' => $request->input('technician'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'join_date' => $request->input('join_date'),
                'status' => ($request->input('status') == 'on' ? 1 : 0),
                'odoo_user_id' => $request->input('odoo_user_id'),
                'cover_area' => $request->input('cover_area'),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id,
            ];

            Technician::where('technician_id', $request->input('technician_id'))->update($data);

            return redirect('artmin/technician/edit-technician/' . $request->input('technician_id'));
        }
    }

    public function delete_technician(Request $request)
    {
        $rules = array(
            'technician_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            Technician::where('technician_id', $request->input('technician_id'))->delete();
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $data['status'] = $request->input('active');
        DB::table('ms_technician')->where('id', $id)->update($data);
    }

}
