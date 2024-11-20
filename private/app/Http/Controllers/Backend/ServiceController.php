<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

// use Request;
use DB;
use Session;
use Hash;
use Auth;
use Excel;
use PDF;

use App\Products;
use App\Products_serial;
use App\Reg_service;
use App\Reg_service_progress;
use App\Reg_warranty;
use App\Http\Controllers\Controller;
use App\Exports\Excel\ServiceRequest;
use App\ProblemInitial;
use App\Helpers\APIodoo;

use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
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

    private function send_notification_whatsapp($contact_name, $contact_phone) {

        if (!DB::table('wa_messages')->where('id', 1)->first()) {
            DB::table('wa_messages')->insert(array(
                'id' => 1,
                'content' => 'Terimakasih atas kepercayaan Bapak/Ibu, telah menggunakan layanan Digital Warranty dari ARTUGO, layanan purna jual berbasis digital pertama di Indonesia untuk produk - produk rumah tangga dan elektronik.
Kami sangat senang dapat melayani, karena kepuasan Anda adalah pencapaian terbaik bagi ARTUGO.'
            ));
        }

        $queue = DB::table('wa_messages_queue')->insertGetId(array(
            'message_id' => 1
        ));

        if ($queue) {
            DB::table('wa_messages_recipients')->insert(array(
                'queue_id' => $queue,
                'name' => $contact_name,
                'number' => $contact_phone
            ));
        }
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
        //     ->where('reg_service.service_type', 1)
        //     ->where('reg_service.isDeleted','0')
        //     ->orderBy('reg_service.created_at', 'desc')
        //     ->orderBy('reg_service.status', 'asc')
        //     ->get();

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
            $svc_qb->where('reg_service.service_type', 1)->where('reg_service.isDeleted','0');
            // ->orderBy('reg_service.created_at', 'desc')
            // ->orderBy('reg_service.status', 'asc')
            // ->addOrder(function ($query) {
            //     $query->orderBy('reg_service.created_at', 'desc');
            //     $query->orderBy('reg_service.status', 'asc');
            // });

            return DataTables::of($svc_qb)->toJson();
        }

        return view('backend.service.request', [
            // "service" => $service
        ]);
    }

    public function request_details($service_id)
    {

        $service = Reg_service::select(
            'reg_service.*',
            'reg_warranty.purchase_date'
        )->where('reg_service.service_id', $service_id)
            ->leftJoin('reg_warranty', 'reg_service.warranty_id', '=', 'reg_warranty.warranty_id')
            ->groupBy('reg_service.service_id')
            ->first();
        // $warranty = Reg_warranty::where('warranty_id', $service->warranty_id)->first();
        $warranty = Reg_warranty::select('reg_warranty.*', 'ms_categories.problem_initial_id as problem_initial_id',)
            ->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_warranty.product_id')
            ->leftJoin('ms_categories', 'ms_categories.category_id', '=', 'ms_products.category')
            ->where('warranty_id', $service->warranty_id)
            ->first();
        $product = Products::where('product_id', $service->product_id)->first();
        $progress = DB::table('reg_service_progress')
            ->select(
                'reg_service_progress.*',
                'ms_problems_symptom.symptom as symptomName',
                'ms_problems_defect.defect as defectName',
                'ms_problems_action.action as actionName'
            )
            ->leftJoin('ms_problems_symptom', 'ms_problems_symptom.id', 'reg_service_progress.symptom')
            ->leftJoin('ms_problems_defect', 'ms_problems_defect.id', 'reg_service_progress.defect')
            ->leftJoin('ms_problems_action', 'ms_problems_action.id', 'reg_service_progress.action')
            ->where('reg_service_progress.service_id', $service_id)
            ->get();
        // $progress = Reg_service_progress::select('reg_service_progress.*', 'ms_problems_taken.taken as taken', 'ms_problems_taken.id as id_taken')->join('ms_problems_taken', 'reg_service_progress.taken', '=', 'ms_problems_taken.id')->get();

        if ($service->service_type == 0) {
            return redirect('artmin/installation/request-details/' . $service->service_id);
        }

        $city = DB::table('ms_loc_city')->orderBy('province_id')->get();

        $check_progres = DB::table('reg_service_progress')
            ->select("id")
            ->where('service_id', $service->service_id)
            ->whereRaw("IFNULL(symptom, 0) != 0 AND IFNULL(defect, 0) != 0 AND IFNULL(action, 0) != 0")
            ->orderBy('created_at', 'DESC')
            ->first();

        $allow_completed = $check_progres !== null;

        $initial = [];
        $ms_problems_initial = [];
        $ms_problems_symptom = [];
        $ms_problems_action = [];
        $ms_problems_defect = [];

        if (!empty($product) && !empty($product->product_code)) {
            // $temp_init = explode(' ', $product->product_code);
            // if (!empty($temp_init[0])) {
            $initial = substr($product->product_code, 0, 2);;

            $ms_problems_initial = DB::table('ms_problems_initial')->where('initial', $initial)->first();

            if (!empty($ms_problems_initial)) {
                $ms_problems_symptom = DB::table('ms_problems_symptom')->where('problem_initial_id', $ms_problems_initial->problem_initial_id)->get();
                $ms_problems_defect = DB::table('ms_problems_defect')->where('problem_initial_id', $ms_problems_initial->problem_initial_id)->get();
                $ms_problems_action = DB::table('ms_problems_action')->where('problem_initial_id', $ms_problems_initial->problem_initial_id)->get();
                // }
            }
        }

        $service_center = DB::table('ms_service_center')
            ->select('ms_service_center.*', 'store_location_regional.regional_name')
            ->leftJoin('store_location_regional', 'store_location_regional.id', '=', 'ms_service_center.region_id')
            ->join('ms_loc_city', 'ms_loc_city.sc_id', '=', 'ms_service_center.sc_id')
            ->where('ms_loc_city.city_id', $service->service_city_id)
            ->get();
        
            // dd($allow_completed);
        
        $odoo_pricelist = DB::table('ms_odoo_pricelist_group')->get();

        return view('backend.service.request-details', [
            "city" => $city,
            "allow_completed" => $allow_completed,
            "service_center" => $service_center,
            "service" => $service,
            "warranty" => $warranty,
            "product" => $product,
            "progress" => $progress,
            "ms_problems_defect" => $ms_problems_defect,
            "ms_problems_symptom" => $ms_problems_symptom,
            "ms_problems_action" => $ms_problems_action,
            'odoo_pricelist' => $odoo_pricelist
        ]);
    }

    public function request_update_city($service_id, Request $request)
    {
        $service = Reg_service::where('service_id', $service_id)->select("service_id")->first();
        if ($service) {
            $city = DB::table('ms_loc_city')->where('city_id', Input::get('service_city_id'))->first();
            $update_service['service_city_id'] = $city->city_id;
            $update_service['service_city'] = $city->city_name;
            $update_service['sc_id'] = $city->sc_id;
            $update_service['updated_at'] = date('Y-m-d H:i:s');
            $update_service['updated_by'] = Auth::user()->id;
            DB::table('reg_service')->where('service_id', $service_id)->update($update_service);
        }
        return redirect('artmin/service/request-details/' . $service_id);
    }

    public function request_update($service_id, Request $request)
    {

        $status = DB::table('ms_service_status')->where('id', Input::get('status'))->first();
        $service = Reg_service::where('service_id', $service_id)->first();

        $progress['service_id'] = $service_id;
        $progress['status'] = Input::get('status');
        $progress['update_time'] = date('Y-m-d');
        $progress['info'] = "Status service berubah menjadi : \"" . $status->service_status . "\"";
        $progress['notes'] = Input::get('notes');
        $progress['taken'] = Input::get('taken');
        $progress['symptom'] = Input::get('symptom');
        $progress['defect'] = Input::get('defect');
        $progress['action'] = Input::get('action');
        $progress['pic'] = $service->visit_pic;
        $progress['created_at'] = date('Y-m-d H:i:s');
        $progress['created_by'] = 1;

        DB::table('reg_service_progress')->insert($progress);

        if (Input::get('status') == 1 || Input::get('status') == 20) {
            $rules = array(
                'symptom' => 'required',
                'defect' => 'required',
                'action' => 'required',
                'notes' => 'required'
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $update_service['status'] = Input::get('status') == 1 ? 1 : 2;
            DB::table('reg_service')->where('service_id', $service_id)->update($update_service);
            if (Input::get('status') == 1) {
                $this->send_notification_whatsapp($service->contact_name, $service->contact_phone);
            }
        }
        // elseif (Input::get('status') == 20) {
        //     $update_service['status'] = 2;
        //     DB::table('reg_service')->where('service_id', $service_id)->update($update_service);
        // }

        return redirect('artmin/service/request-details/' . $service_id);
    }

    public function set_schedule($service_id, Request $request)
    {

        $progress_status = Input::get('progress_status');

        $check = Reg_service::where('service_id', $service_id)->first();
        // echo $check->visit_date;
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
            $progress['status'] = $progress_status == "7" ? 7 : 3;
            $progress['info'] = "Servis telah dijadwalkan. Teknisi akan datang pada tanggal " . date('d M Y', strtotime($schedule['visit_date'])) . " jam " . $time->time;
            $progress['pic'] = $technician->technician_id . ' - ' . $technician->name;
            $progress['update_time'] = date('Y-m-d');
            // $progress['notes'] = Input::get('notes'); // jadi defect
            // $progress['taken'] = Input::get('taken');
            $progress['symptom'] = Input::get('symptom');
            $progress['defect'] = Input::get('defect');
            $progress['action'] = Input::get('action');
            $progress['visit_date'] = $schedule['visit_date'];
            $progress['visit_time'] = $schedule['visit_time'];
            $progress['created_at'] = date('Y-m-d H:i:s');
            $progress['created_by'] = Auth::user()->id;
            // print_r(Input::get());

            // dd($progress);
            // return;

            $update_progress = Reg_service_progress::where('service_id', $service_id)->insert($progress);

            return redirect('artmin/service/request-details/' . $service_id)->with('success', 'Set Schedule success.');
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
            $progress['status'] = $progress_status == "7" ? 7 : 4;
            $progress['info'] = "Servis telah dijadwalkan ulang. Teknisi akan datang pada tanggal " . date('d M Y', strtotime($schedule['visit_date'])) . " jam " . $time->time;
            $progress['pic'] = $technician->technician_id . ' - ' . $technician->name;
            $progress['update_time'] = date('Y-m-d');
            // $progress['notes'] = Input::get('notes');
            // $progress['taken'] = Input::get('taken');
            $progress['symptom'] = Input::get('symptom');
            $progress['defect'] = Input::get('defect');
            $progress['action'] = Input::get('action');
            $progress['visit_date'] = $schedule['visit_date'];
            $progress['visit_time'] = $schedule['visit_time'];
            $progress['created_at'] = date('Y-m-d H:i:s');
            $progress['created_by'] = Auth::user()->id;

            // dd($progress);
            // return;

            $update_progress = Reg_service_progress::where('service_id', $service_id)->insert($progress);

            return redirect('artmin/service/request-details/' . $service_id)->with('success', 'Reschedule success.');
        } else {
            return redirect('artmin/service/request-details/' . $service_id);
        }
    }

    public function update_processing($service_id)
    {
        if (isset($service_id)) {

            $service = Reg_service::where('service_id', $service_id)->first();
            $technician = DB::table('ms_technician')->where('id', $service->visit_technician)->first();

            $progress['service_id'] = $service_id;
            $progress['status'] = 5;
            $progress['update_time'] = date('Y-m-d');
            $progress['info'] = "Servis dalam proses oleh " . $technician->technician_id . " - " . $technician->name;
            $progress['created_at'] = date('Y-m-d H:i:s');
            $progress['created_by'] = Auth::user()->id;

            $update_progress = Reg_service_progress::where('service_id', $service_id)->insert($progress);

            return redirect('artmin/service/request-details/' . $service_id);
        } else {
            return redirect('artmin/service/request');
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
            $progress['status'] = 1;
            $progress['update_time'] = date('Y-m-d');
            $progress['info'] = "Servis selesai pada tanggal " . date('d M Y') . ".";
            $progress['created_at'] = date('Y-m-d H:i:s');
            $progress['created_by'] = Auth::user()->id;

            $update_progress = Reg_service_progress::where('service_id', $service_id)->insert($progress);

            $warranty['install_status'] = 1;
            $update_warranty = Reg_warranty::where('warranty_id', $check_service->warranty_id)->update($warranty);

            $this->send_notification_whatsapp($check_service->contact_name, $check_service->contact_phone);

            return redirect('artmin/service/request-details/' . $service_id);
        } else {
            return redirect('artmin/service/request');
        }
    }

    public function services_completed_for_so() {
        $data = Reg_service::select('reg_service.service_no', DB::raw('SUM(member_point.balance) AS balance_point'))
            ->join('reg_warranty', 'reg_warranty.warranty_id', 'reg_service.warranty_id')
            ->join('member_point', 'member_point.member_id', 'reg_warranty.member_id')
            ->leftJoin('reg_service_so', 'reg_service_so.service_id', 'reg_service.service_id')
            ->where('reg_service.status', 1)
            ->where('reg_service.isDeleted', 0)
            ->where('reg_service_so.id', NULL)
            ->groupBy('reg_service.service_no')
            ->get();
        return json_encode(array($data));
    }

    public function submit_link_so_with_arn(Request $request) {
        
        $so_id = $request->input('so_id');
        $so_number = $request->input('so_number');
        $so_date = $request->input('so_date');
        $service_no = $request->input('arn_number');
        $used_point = floatval($request->input('point_deduction'));

        $prev_service_so = DB::table('reg_service_so')
            ->where('so_id', $so_id)
            ->first();

        if ($prev_service_so != null) {
            // Rollback prev used point
        } else {

            $member = DB::table('member_point')
                ->select(
                    'reg_service.service_id',
                    'reg_service.warranty_id',
                    'reg_warranty.member_id',
                    DB::raw('SUM(member_point.balance) AS balance_point')
                )
                ->join('reg_warranty', 'member_point.member_id', 'reg_warranty.member_id')
                ->join('reg_service', 'reg_service.warranty_id', 'reg_warranty.warranty_id')
                ->where('reg_service.service_no', $service_no)
                ->groupBy('reg_service.service_id', 'reg_warranty.member_id')
                ->first();

            $sum_point = DB::table('member_point_adjustment')
                ->select(
                    DB::raw("SUM(IF(`type`='in', value, 0)) AS total_point"),
                    DB::raw("SUM(IF(`type`='out', value*-1, 0)) AS used_point"),
                    DB::raw("IFNULL(SUM(value), 0) AS balance"),
                )
                ->where('member_id', $member->member_id)
                ->first();

            $balance_point = floatval($sum_point->balance);
            
            if ($balance_point < $used_point) {
                return [
                    'success' => false,
                    'message' => 'Balance Point ' . $balance_point . ' member tidak mencukupi!'
                ];
            }

            DB::table('reg_service_so')->insert([
                'service_id' => $member->service_id,
                'so_id' => $so_id,
                'so_number' => $so_number,
                'so_date' => $so_date,
                'deduct_point' => $used_point,
                'so_items' => json_encode($request->input('detail_items')),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            DB::table('member_point_adjustment')->insert([
                'member_id' => $member->member_id,
                'warranty_id' => $member->warranty_id,
                'point_id' => null,
                'trx_date' => $so_date,
                'description' => 'Deduction Point Service Number ' . $service_no . ', SO Number ' . $so_number . '.',
                'ref_number' => $service_no,
                'type' => 'out',
                'value' => $used_point * -1,
                'created_at' => $so_date,
                'created_by' => 0,
            ]);

            DB::table('ms_members')
                ->where('id', $member->member_id)
                ->update([
                    'total_point' => floatval($sum_point->total_point),
                    'used_point' => floatval($sum_point->used_point) + $used_point,
                    'balance_point' => floatval($sum_point->balance) - $used_point,
                ]);

        }

        return json_encode([
            'service_no' => $service_no,
            'used_point' => $used_point,
            'member' => $member
        ]);

    }


    public function update_cancel($service_id)
    {
        if (isset($service_id)) {

            $service['status'] = 2;
            $service['updated_at'] = date('Y-m-d H:i:s');
            $service['updated_by'] = Auth::user()->id;

            $update_service = Reg_service::where('service_id', $service_id)->update($service);

            $progress['service_id'] = $service_id;
            $progress['status'] = 20;
            $progress['update_time'] = date('Y-m-d');
            $progress['info'] = "Proses Service dibatalkan tanggal " . date('d M Y') . ".";
            $progress['created_at'] = date('Y-m-d H:i:s');
            $progress['created_by'] = Auth::user()->id;

            Reg_service_progress::where('service_id', $service_id)->insert($progress);


            return redirect('artmin/service/request-details/' . $service_id);
        } else {
            return redirect('artmin/service/request');
        }
    }

    // public function create_so($service_id, Request $request)
    // {
    //     if (isset($service_id)) {

    //         $service = Reg_service::where('service_id', $service_id)
    //         ->select("reg_service.service_no","ms_technician.odoo_user_id")
    //         ->join('ms_technician', 'ms_technician.id', '=', 'reg_service.visit_technician')
    //         ->whereNotNull("ms_technician.odoo_user_id")
    //         ->first();

    //         if (!$service) {
    //             return redirect('artmin/service/request-details/' . $service_id);
    //         }

    //         $default_codes = Input::get('default_code');
    //         $products_uom_qty = Input::get('default_code');

    //         for($count = 0; $count < collect($default_codes)->count(); $count++)
    //         {
    //             $data = array(
    //                 'default_code' => $default_codes[$count],
    //                 'product_uom_qty' => $products_uom_qty[$count]
    //             );
    //             $lines[] = $data; 
    //         }

    //         $post_data = array(
    //             'client_order_ref' => $service->service_no,
    //             'confirmation_date' => date('Y-m-d'),
    //             'pricelist_id' => Input::get('pricelist_id'),
    //             'user_id' => 11111,
    //             'lines' => [
    //                 array(
    //                     'default_code' => Input::get('default_code')[0],
    //                     'product_uom_qty' => Input::get('product_uom_qty')[0],
    //                 )
    //             ]
    //         );

    //         // return APIodoo::create_so($post_data);

    //         $res_create_so = APIodoo::create_so($post_data);
    //         Reg_service::where('service_id', $service_id)->update(array(
    //             'odoo_so_id' => $res_create_so->so_id,
    //             'odoo_so_number' => $res_create_so->so_no,
    //         ));

    //         // return json_encode($service);
    //         return redirect('artmin/service/request-details/' . $service_id);

    //     }
    // }

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

    public function export_service_request_excel($from, $to)
    {

        // $service_request =  Reg_service::select(
        //     'reg_service.*',
        //     'ms_products.product_name_odoo',
        //     'ms_loc_city.city_name',
        //     'ms_loc_province.province_name',
        //     'reg_warranty.serial_no',
        //     'reg_warranty.member_id',
        //     'reg_warranty.purchase_date',
        //     'ms_service_center.sc_location',
        //     'ms_technician.name as technicianName',
        //     'store_location_regional.regional_name as branchName'
        // )
        //     ->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_service.product_id')
        //     ->leftJoin('ms_loc_city', 'ms_loc_city.city_id', '=', 'reg_service.service_city_id')
        //     ->leftJoin('ms_loc_province', 'ms_loc_province.province_id', '=', 'ms_loc_city.province_id')
        //     ->leftJoin('reg_warranty', 'reg_warranty.warranty_id', '=', 'reg_service.warranty_id')
        //     ->leftJoin('ms_service_center', 'ms_service_center.sc_id', '=', 'ms_loc_city.sc_id')
        //     ->leftJoin('store_location_regional', 'ms_service_center.region_id', '=', 'store_location_regional.id')
        //     ->leftJoin('ms_technician', 'ms_technician.id', '=', 'reg_service.visit_technician')
        //     ->where('reg_service.service_type', 1)
        //     ->orderBy('reg_service.created_at', 'desc')
        //     ->whereBetween('reg_service.created_at', [$from, $to])
        //     ->get();

        //     print_r($service_request);

        $export = new ServiceRequest;
        $export->setDateFrom(date('Y-m-d', strtotime($from)));
        $export->setDateTo(date('Y-m-d', strtotime($to)));

        return Excel::download($export, 'artugo_digital_warranty_request_service_' . date('Ymd', strtotime($from)) . '_' . date('Ymd', strtotime($to)) . '.xlsx');
    }

    public function export_service_request_pdf($from, $to)
    {
        $service_request = DB::table('reg_service')
            ->where('service_type', '1')
            ->whereBetween('reg_service.created_at', [date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))])
            ->get();
        $pdf = PDF::loadview('backend.service.export-request-service-pdf', [
            'service_request' => $service_request
        ])->setPaper('a4', 'landscape');
        return $pdf->stream('artugo-digital-warranty-request-service' . date('Ymd'));
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

    public function browse_warranty()
    {
        $data['warranty'] = Reg_warranty::select('reg_warranty.*', 'ms_products.product_name_odoo as product_name')->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_warranty.product_id')->where('verified', '1')->get();

        return view('backend.service.browse-warranty', $data);
    }

    public function add_service_request($warranty_id)
    {
        $data['warranty'] = Reg_warranty::select('reg_warranty.*', 'ms_products.product_name_odoo as product_name', 'ms_products.category as product_category', 'ms_categories.problem_initial_id as problem_initial_id', 'ms_products.product_image', 'ms_categories.name as category_name',)
            ->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_warranty.product_id')
            ->leftJoin('ms_categories', 'ms_categories.category_id', '=', 'ms_products.category')
            ->where('verified', '1')
            ->where('warranty_id', $warranty_id)
            ->first();
        $data['city'] = DB::table('ms_loc_city')->get();

        return view('backend.service.add-service-request', $data);
    }


    public function add_service_request_process(Request $request)
    {

        $warranty_id = Input::get('warranty_id');
        $validator = Validator::make($request->all(), [
            'svc_type' => 'required',
            'problem_category' => 'required',
            'problem' => 'required',
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
            $warranty_id = Input::get('warranty_id');
            $warranty = Reg_warranty::where('warranty_id', $warranty_id)->first();
            if (!isset($warranty->warranty_id)) {
                return redirect('member/dashboard');
            } else {

                $period = date("Ym");

                $check_service = Reg_service::where("service_no", "LIKE", "ARN" . $period . "%")->orderBy('service_id', 'desc')->first();

                if (!isset($check_service->service_id)) {
                    $service_no = "ARN" . $period . str_pad(1, 4, "0", STR_PAD_LEFT);
                } else {
                    $last_service_no = $check_service->service_no;
                    $last_service_no = substr($last_service_no, -4, 4);
                    $last_service_no = (int) $last_service_no;
                    $service_no = "ARN" . $period . str_pad($last_service_no + 1, 4, "0", STR_PAD_LEFT);
                }

                $city = DB::table('ms_loc_city')->where('city_id', Input::get('city'))->first();

                $member_id = $warranty->member_id;

                $service['svc_type'] = Input::get('svc_type');
                $service['service_no'] = $service_no;
                $service['service_type'] = 1;
                $service['warranty_id'] = $warranty_id;
                $service['product_id'] = $warranty->product_id;
                $service['member_id'] = $member_id;
                $service['problem_category'] = Input::get('problem_category');
                $service['prefered_date'] = date('Y-m-d', strtotime(Input::get('prefered_date')));
                $service['prefered_time'] = Input::get('prefered_time');
                $service['problem_notes'] = Input::get('problem');
                $service['contact_name'] = Input::get('name');
                $service['contact_phone'] = Input::get('phone');
                $service['service_address'] = Input::get('address');
                $service['service_city_id'] = Input::get('city');
                $service['service_city'] = $city->city_name;
                $service['sc_id'] = $city->sc_id;
                $service['status'] = 0;
                $service['created_at'] = date("Y-m-d H:i:s");
                $service['created_by'] = $member_id;

                $insert_request = Reg_service::insertGetId($service);

                if ($insert_request) {
                    $progress['service_id'] = $insert_request;
                    $progress['status'] = 2;
                    $progress['update_time'] = date("Y-m-d H:i:s");
                    $progress['info'] = "Permintaan servis diterima.";
                    $progress['created_at'] = date('Y-m-d H:i:s');
                    $progress['created_by'] = $member_id;

                    $insert_progress = Reg_service_progress::insert($progress);
                }

                return redirect('artmin/service/request');
            }
        }
    }


    public function request_update_revisi_process()
    {
        // $progress['info'] = "Status service berubah menjadi : \"" . $status->service_status . "\"";
        // $progress['notes'] = Input::get('notes');
        // $progress['taken'] = Input::get('taken');
        // $progress['symptom'] = Input::get('symptom');
        // $progress['defect'] = Input::get('defect');
        // $progress['action'] = Input::get('action');

        $data = [
            // 'status' => Input::get('revisi_status'),
            'notes' => Input::get('revisi_notes') ?? '',
            'taken' => Input::get('revisi_taken') ?? '',
            'symptom' => Input::get('revisi_symptom') ?? '',
            'action' => Input::get('revisi_action') ?? '',
            'defect' => Input::get('revisi_defect') ?? '',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::user()->id,
        ];

        $check = DB::table('reg_service_progress')->select('service_id', 'status')->where('id', Input::get('reg_service_progress_id'))->first();
        $status = DB::table('ms_service_status')->select('service_status')->whereRaw("id = " . $check->status . " AND service_status IN ('Service Visit Scheduled', 'Service Visit Rescheduled')", )->first();
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
            $upload_loc = 'sys_uploads/service-progress/' . $progress_id;
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
