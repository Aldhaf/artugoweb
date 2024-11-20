<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

use DB;
use PDF;
use Auth;
use Session;
use Hash;
use Excel;

use App\Products;
use App\Products_serial;
use App\Reg_service;
use App\Reg_service_progress;
use App\Reg_warranty;
use App\Exports\Excel\ServiceInstallation;
use App\Http\Controllers\Controller;

use Yajra\DataTables\Facades\DataTables;

class InstallationController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function index()
    {
    }

    public function request(Request $request)
    {
        // $service = Reg_service::select(
        //     'reg_service.*',
        //     'ms_products.product_name_odoo',
        //     'reg_warranty.serial_no'
        // )
        //     ->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_service.product_id')
        //     ->leftJoin('reg_warranty', 'reg_warranty.warranty_id', '=', 'reg_service.warranty_id')
        //     ->where('reg_service.service_type', 0)
        //     ->where('reg_service.isDeleted', '0')
        //     ->orderBy('reg_service.created_at', 'desc')
        //     ->orderBy('reg_service.status', 'asc')
        //     ->get();

        // $service = Reg_service::where('service_type', 0)->orderBy('created_at', 'desc')->orderBy('status', 'asc')->get();

        if ($request->ajax()) {
            
            $svc_qb = Reg_service::select(
                'reg_service.*',
                'ms_products.product_name_odoo',
                'reg_warranty.serial_no'
            )
            ->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_service.product_id')
            ->leftJoin('reg_warranty', 'reg_warranty.warranty_id', '=', 'reg_service.warranty_id');

            $is_ho = in_array(Auth::user()->roles, ['1', '2', '3']); // Super Administrator, Administrator, Customer Care
            if (!$is_ho) {
                $svc_qb->leftJoin('ms_service_center', 'ms_service_center.sc_id', '=', 'reg_service.sc_id');
                $svc_qb->join('ms_service_center_users', 'ms_service_center_users.sc_id', '=', 'ms_service_center.sc_id')
                    ->where('ms_service_center_users.status', 1)
                    ->where('ms_service_center_users.users_id', Auth::user()->id);
            }
            $svc_qb->where('reg_service.service_type', 0)->where('reg_service.isDeleted', '0');

            return DataTables::of($svc_qb)->toJson();
        }

        return view('backend.installation.request', [
            // "service" => $service
        ]);
    }

    public function request_details($service_id)
    {
        $service = Reg_service::where('service_id', $service_id)->first();
        // $warranty = Reg_warranty::where('warranty_id', $service->warranty_id)->first();
        $warranty = Reg_warranty::select('reg_warranty.*', 'ms_categories.problem_initial_id as problem_initial_id',)
            ->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_warranty.product_id')
            ->leftJoin('ms_categories', 'ms_categories.category_id', '=', 'ms_products.category')
            ->where('warranty_id', $service->warranty_id)
            ->first();
        $product = Products::where('product_id', $service->product_id)->first();
        $progress = DB::table('reg_service_progress')->where('service_id', $service_id)->orderBy('id', 'desc')->get();

        if ($service->service_type == 1) {
            return redirect('artmin/service/request-details/' . $service->service_id);
        }

        $city = DB::table('ms_loc_city')->orderBy('province_id')->get();

        $service_center = DB::table('ms_service_center')
        ->select('ms_service_center.*', 'store_location_regional.regional_name')
        ->leftJoin('store_location_regional', 'store_location_regional.id', '=', 'ms_service_center.region_id')
        ->join('ms_loc_city', 'ms_loc_city.sc_id', '=', 'ms_service_center.sc_id')
        ->where('ms_loc_city.city_id', $service->service_city_id)
        ->get();

        return view('backend.installation.request-details', [
            "city" => $city,
            "service_center" => $service_center,
            "service" => $service,
            "warranty" => $warranty,
            "product" => $product,
            "progress" => $progress
        ]);
    }

    public function request_update_city($service_id, Request $request)
    {
        $service = Reg_service::where('service_id', $service_id)->select("service_id")->first();
        if ($service) {
            $update_service['updated_at'] = date('Y-m-d H:i:s');
            $update_service['updated_by'] = Auth::user()->id;
            $update_service['service_city_id'] = Input::get('service_city_id');
            $update_service['service_city'] = Input::get('service_city');
            DB::table('reg_service')->where('service_id', $service_id)->update($update_service);
        }
        return redirect('artmin/installation/request-details/' . $service_id);
    }

    public function request_update($service_id, Request $request)
    {
        $progress['service_id'] = $service_id;
        $progress['status'] = Input::get('status');
        $progress['update_time'] = date('Y-m-d');
        $progress['notes'] = Input::get('notes');
        $progress['pic'] = Input::get('pic');
        $progress['created_at'] = date('Y-m-d H:i:s');
        $progress['created_by'] = 1;

        DB::table('reg_service_progress')->insert($progress);

        if (Input::get('status') == 1) {
            $service['status'] = 1;
            DB::table('reg_service')->where('service_id', $service_id)->update($service);
        }

        return redirect('artmin/installation/request-details/' . $service_id);
    }

    public function set_schedule($service_id, Request $request)
    {
        $check = Reg_service::where('service_id', $service_id)->first();
        if ($check->visit_date == '') {
            $city = DB::table('ms_loc_city')->where('city_id', Input::get('service_city_id'))->first();
            $schedule['service_city_id'] = $city->city_id;
            $schedule['service_city'] = $city->city_name;
            $schedule['sc_id'] = $city->sc_id;
            // $schedule['sc_id'] = Input::get('sc_id');
            $schedule['visit_date'] = date('Y-m-d', strtotime(Input::get('visit_date')));
            $schedule['visit_time'] = Input::get('visit_time');
            $schedule['visit_technician'] = Input::get('technician');

            $set_schedule = Reg_service::where('service_id', $service_id)->update($schedule);

            $time = DB::table('ms_service_time')->where('id', $schedule['visit_time'])->first();
            $technician = DB::table('ms_technician')->where('id', $schedule['visit_technician'])->first();

            $progress['service_id'] = $service_id;
            $progress['status'] = 14;
            $progress['info'] = "Instalasi telah dijadwalkan. Teknisi akan datang pada tanggal " . date('d M Y', strtotime($schedule['visit_date'])) . " jam " . $time->time;
            $progress['pic'] = $technician->technician_id . ' - ' . $technician->name;
            $progress['update_time'] = date('Y-m-d');
            $progress['notes'] = Input::get('notes'); // jadi defect
            $progress['taken'] = Input::get('taken');
            $progress['visit_date'] = $schedule['visit_date'];
            $progress['visit_time'] = $schedule['visit_time'];
            $progress['created_at'] = date('Y-m-d H:i:s');
            $progress['created_by'] = Auth::user()->id;

            $update_progress = Reg_service_progress::where('service_id', $service_id)->insert($progress);

            return redirect('artmin/installation/request-details/' . $service_id)->with('success', 'Set Schedule success.');
        } else if ($check->visit_date != '') {
            $city = DB::table('ms_loc_city')->where('city_id', Input::get('service_city_id'))->first();
            $schedule['service_city_id'] = $city->city_id;
            $schedule['service_city'] = $city->city_name;
            $schedule['sc_id'] = $city->sc_id;
            // $schedule['sc_id'] = Input::get('sc_id');
            $schedule['visit_date'] = date('Y-m-d', strtotime(Input::get('visit_date')));
            $schedule['visit_time'] = Input::get('visit_time');
            $schedule['visit_technician'] = Input::get('technician');

            $set_schedule = Reg_service::where('service_id', $service_id)->update($schedule);

            $time = DB::table('ms_service_time')->where('id', $schedule['visit_time'])->first();
            $technician = DB::table('ms_technician')->where('id', $schedule['visit_technician'])->first();

            $progress['service_id'] = $service_id;
            $progress['status'] = 15;
            $progress['info'] = "Instalasi telah dijadwalkan ulang. Teknisi akan datang pada tanggal " . date('d M Y', strtotime($schedule['visit_date'])) . " jam " . $time->time;
            $progress['pic'] = $technician->technician_id . ' - ' . $technician->name;
            $progress['update_time'] = date('Y-m-d');
            $progress['notes'] = Input::get('notes');
            $progress['taken'] = Input::get('taken');
            $progress['visit_date'] = $schedule['visit_date'];
            $progress['visit_time'] = $schedule['visit_time'];
            $progress['created_at'] = date('Y-m-d H:i:s');
            $progress['created_by'] = Auth::user()->id;

            $update_progress = Reg_service_progress::where('service_id', $service_id)->insert($progress);

            return redirect('artmin/installation/request-details/' . $service_id)->with('success', 'Reschedule success.');
        } else {
            return redirect('artmin/installation/request-details/' . $service_id);
        }
    }

    public function update_processing($service_id)
    {
        if (isset($service_id)) {

            $service = Reg_service::where('service_id', $service_id)->first();
            $technician = DB::table('ms_technician')->where('id', $service->visit_technician)->first();

            $progress['service_id'] = $service_id;
            $progress['status'] = 16;
            $progress['update_time'] = date('Y-m-d');
            $progress['info'] = "Dalam proses instalasi oleh " . $technician->technician_id . " - " . $technician->name;
            $progress['created_at'] = date('Y-m-d H:i:s');
            $progress['created_by'] = Auth::user()->id;

            $update_progress = Reg_service_progress::where('service_id', $service_id)->insert($progress);

            return redirect('artmin/installation/request-details/' . $service_id);
        } else {
            return redirect('artmin/installation/request');
        }
    }

    public function update_complete($service_id)
    {
        if (isset($service_id)) {

            $check_service = Reg_service::where('service_id', $service_id)->first();

            $service['status'] = 1;
            $service['updated_at'] = date('Y-m-d H:i:s');
            $service['updated_by'] = Auth::user()->id;

            $update_service = Reg_service::where('service_id', $service_id)->update($service);

            $progress['service_id'] = $service_id;
            $progress['status'] = 13;
            $progress['update_time'] = date('Y-m-d');
            $progress['info'] = "Proses instalasi selesai pada tanggal " . date('d M Y') . ".";
            $progress['created_at'] = date('Y-m-d H:i:s');
            $progress['created_by'] = Auth::user()->id;

            Reg_service_progress::where('service_id', $service_id)->insert($progress);

            $warranty['install_status'] = 1;
            $update_warranty = Reg_warranty::where('warranty_id', $check_service->warranty_id)->update($warranty);

            return redirect('artmin/installation/request-details/' . $service_id);
        } else {
            return redirect('artmin/installation/request');
        }
    }

    public function update_cancel($service_id)
    {
        if (isset($service_id)) {

            $service['status'] = 2;
            $service['updated_at'] = date('Y-m-d H:i:s');
            $service['updated_by'] = Auth::user()->id;

            $update_service = Reg_service::where('service_id', $service_id)->update($service);

            $progress['service_id'] = $service_id;
            $progress['status'] = 21;
            $progress['update_time'] = date('Y-m-d');
            $progress['info'] = "Proses instalasi dibatalkan tanggal " . date('d M Y') . ".";
            $progress['created_at'] = date('Y-m-d H:i:s');
            $progress['created_by'] = Auth::user()->id;

            Reg_service_progress::where('service_id', $service_id)->insert($progress);


            return redirect('artmin/installation/request-details/' . $service_id);
        } else {
            return redirect('artmin/installation/request');
        }
    }

    public function update_uncomplete($service_id)
    {
        if (isset($service_id)) {

            $service['status'] = 0;
            $service['updated_at'] = date('Y-m-d H:i:s');
            $service['updated_by'] = Auth::user()->id;

            $update_service = Reg_service::where('service_id', $service_id)->update($service);

            return redirect('artmin/service/request-details/' . $service_id);
        } else {
            return redirect('artmin/service/request');
        }
    }

    public function export_installation_request_excel($from, $to)
    {
        $export = new ServiceInstallation;
        $export->setDateFrom(date('Y-m-d', strtotime($from)));
        $export->setDateTo(date('Y-m-d', strtotime($to)));

        // print_r($to);

        return Excel::download($export, 'artugo_digital_warranty_request_installation_' . date('Ymd', strtotime($from)) . '_' . date('Ymd', strtotime($to)) . '.xlsx');
    }

    public function export_installation_request_pdf($from, $to)
    {
        $installation_request = Reg_service::where('service_type', 0)
        ->whereBetween('reg_service.created_at', [date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))])
        ->where('isDeleted', '0')
        ->orderBy('created_at', 'desc')
        ->orderBy('status', 'asc')
        ->get();

        $pdf = PDF::loadview('backend.installation.export-request-installation-pdf', ['service' => $installation_request])->setPaper('a4', 'landscape');
        return $pdf->stream('artugo-digital-warranty-request-installation' . date('Ymd'));
    }

    public function delete(Request $request)
    {
        DB::table('reg_service')->where('service_id', $request->input('service_id'))->update([
            'isDeleted' => '1',
            'delete_reason' => $request->input('reason'),
            'deleted_by' => Auth::user()->id,
        ]);
        // DB::table('reg_service')->where('service_id', $request->input('service_id'))->delete();
    }

    public function request_update_revisi_process()
    {
        $data = [
            // 'status' => Input::get('revisi_status'),
            // 'info' => Input::get('revisi_info'),
            'notes' => Input::get('revisi_notes'), // defect
            'taken' => Input::get('revisi_taken'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::user()->id,
        ];

        $check = DB::table('reg_service_progress')->select('service_id', 'status')->where('id', Input::get('reg_service_progress_id'))->first();
        $status = DB::table('ms_service_status')->select('service_status')->whereRaw("id = " . $check->status . " AND service_status IN ('Installation Scheduled', 'Installation Rescheduled')", )->first();
        if ($status && $check) {
            $city = DB::table('ms_loc_city')->where('city_id', Input::get('revisi_service_city_id'))->first();

            $service['service_city_id'] = $city->city_id;
            $service['service_city'] = $city->city_name;
            $service['sc_id'] = $city->sc_id;
            $service['visit_technician'] = Input::get('revisi_technician');

            $update_service = Reg_service::where('service_id', $check->service_id)->update($service);

            $technician = DB::table('ms_technician')->where('id', $service['visit_technician'])->first();
            $data['pic'] = $technician->technician_id . ' - ' . $technician->name;
        }

        // print_r(Input::get());
        DB::table('reg_service_progress')->where('id', Input::get('reg_service_progress_id'))->update($data);
    }

    public function browse_warranty()
    {
        $data['warranty'] = Reg_warranty::select('reg_warranty.*', 'ms_products.product_name_odoo as product_name')->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_warranty.product_id')->where('verified', '1')->get();

        return view('backend.installation.browse-warranty', $data);
    }


    public function add_installation_request($warranty_id)
    {
        $data['warranty'] = Reg_warranty::select('reg_warranty.*', 'ms_products.product_name_odoo as product_name', 'ms_products.category as product_category', 'ms_categories.problem_initial_id as problem_initial_id', 'ms_products.product_image', 'ms_categories.name as category_name',)
            ->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_warranty.product_id')
            ->leftJoin('ms_categories', 'ms_categories.category_id', '=', 'ms_products.category')
            ->where('verified', '1')
            ->where('warranty_id', $warranty_id)
            ->first();


        $data['service_time'] = DB::table('ms_service_time')->orderBy('ordering')->get();

        $data['city'] = DB::table('ms_loc_city')->get();

        return view('backend.installation.add-installation-request', $data);
    }

    public function add_installation_request_process(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prefered_date' => 'required',
            'prefered_time' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $warranty = Reg_warranty::where('warranty_no', $request->input('warranty_no'))->first();
            if (!isset($warranty->warranty_id)) {
                return redirect('member/dashboard');
            } else {

                $period = date("Ym");

                $check_service = Reg_service::where("service_no", "LIKE", 'ARN' . $period . "%")->orderBy('service_id', 'desc')->first();


                if (!isset($check_service->service_id)) {
                    $service_no = "ARN" . $period . str_pad(1, 4, "0", STR_PAD_LEFT);
                } else {
                    $last_service_no = $check_service->service_no;
                    $last_service_no = substr($last_service_no, -4, 4);
                    $last_service_no = (int)$last_service_no;
                    $service_no = "ARN" . $period . str_pad($last_service_no + 1, 4, "0", STR_PAD_LEFT);
                }



                $city = DB::table('ms_loc_city')->where('city_id', Input::get('city'))->first();


                $service['service_no'] = $service_no;
                $service['service_type'] = 0;
                $service['warranty_id'] = $warranty->warranty_id;
                $service['product_id'] = $warranty->product_id;
                $service['member_id'] = Session::get('member_id');
                $service['prefered_date'] = date('Y-m-d', strtotime(Input::get('prefered_date')));
                $service['prefered_time'] = Input::get('prefered_time');
                $service['contact_name'] = Input::get('name');
                $service['contact_phone'] = Input::get('phone');
                $service['service_address'] = Input::get('address');
                $service['service_city_id'] = Input::get('city');
                $service['service_city'] = $city->city_name;
                $service['sc_id'] = $city->sc_id;
                $service['status'] = 0;
                $service['created_at'] = date("Y-m-d H:i:s");
                $service['created_by'] = Session::get('member_id');

                $insert_request = Reg_service::insertGetId($service);

                if ($insert_request) {
                    $progress['service_id'] = $insert_request;
                    $progress['status'] = 12;
                    $progress['update_time'] = date("Y-m-d H:i:s");
                    $progress['info'] = "Permintaan instalasi diterima.";
                    $progress['created_at'] = date('Y-m-d H:i:s');
                    $progress['created_by'] = Session::get('member_id');

                    $insert_progress = Reg_service_progress::insert($progress);
                }

                // return redirect('member/service');
            }
        }
    }

    public function get_attachments_by_progress(Request $request, $progress_id)
    {
        $attachments = DB::table('reg_service_progress_attachment')
        ->select("*")
        ->addSelect(DB::raw("CONCAT('" . url("/") . "/', path_file) AS url" ))
        ->where('progress_id', $progress_id)->get();
        return [
            'success' => true,
            'data' => $attachments
        ];
    }

    public function upload_progress_attachments(Request $request, $progress_id)
    {
        $file = $request->file("file_attachment");
        if (isset($file)) {
            $upload_loc = 'sys_uploads/installation-progress/' . $progress_id;
            if(!Storage::exists($upload_loc)){
                Storage::makeDirectory($upload_loc);
            }

            // $filename = $file->getClientOriginalName();
            $fileext = $file->getClientOriginalExtension();
            $filename = date("YmdHis.") . $fileext;

            $attachment['progress_id'] = $progress_id;
            $attachment['path_file'] = $upload_loc . '/' . $filename;
            $attachment['file_ext'] = $fileext;
            $attachment['description'] = $request->input('description');
            $attachment['created_at'] = date('Y-m-d H:i:s');
            $attachment['created_by'] = Auth::user()->id;
            $created = DB::table('reg_service_progress_attachment')->insert($attachment);

            $file->move($upload_loc, $filename);

            return [
                'success' => true,
                'message' => ''
            ];
        }

        return [
            'success' => false,
            'message' => 'Mohon upload file!'
        ];
    }

    public function delete_progress_attachments(Request $request, $attachment_id)
    {
        $attachment = DB::table('reg_service_progress_attachment')->select("path_file")->where('id', $attachment_id)->first();
        if (isset($attachment)) {
            $deleted = DB::table('reg_service_progress_attachment')->where('id', $attachment_id)->delete();
            unlink($attachment->path_file);
            return [
                'success' => true,
                'message' => ''
            ];
        }
        return [
            'success' => false,
            'message' => 'Tidak ada attachment!'
        ];
    }
}
