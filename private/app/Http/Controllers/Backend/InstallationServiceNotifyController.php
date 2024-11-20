<?php

namespace App\Http\Controllers\Backend;

use DB;
use App\Reg_service;
use App\Mail\InstallationServiceMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class InstallationServiceNotifyController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }

    public function index() {

        $service_type = array(
            array('code' => 0, 'title' => 'Installation'),
            array('code' => 1, 'title' => 'Service')
        );

        $morethanDay = 3;
        $have_morethanDay = false;
        $by_sc_group = null;

        $data = [];
        foreach($service_type as $type) {

            $rows = Reg_service::select(
                'reg_service.service_id',
                'reg_service.service_type',
                'reg_service.service_no',
                'reg_service.contact_name',
                'reg_service.created_at',
                'ms_products.product_name_odoo',
                'reg_warranty.serial_no',
                'reg_warranty.reg_phone',
                'ms_service_center.sc_location',
                'reg_service.sc_id',
                'ms_technician.name AS technician_name',
                DB::raw('DATEDIFF(NOW(), reg_service.created_at) AS lead_time')
            )
            ->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_service.product_id')
            ->leftJoin('reg_warranty', 'reg_warranty.warranty_id', '=', 'reg_service.warranty_id')
            ->leftJoin('ms_service_center', 'ms_service_center.sc_id', '=', 'reg_service.sc_id')
            ->leftJoin('ms_technician', 'ms_technician.id', '=', 'reg_service.visit_technician')
            ->where('reg_service.service_type', $type['code'])
            ->where('reg_service.status', 0)
            ->whereRaw('DATEDIFF(NOW(), reg_service.created_at) > ' . $morethanDay)
            ->orderBy('reg_service.created_at', 'DESC')
            ->get();

            foreach($rows as $i => $row) {
                if (!isset($by_sc_group[$row->sc_id])) {
                    $data_rows = [];
                    $by_sc_group[$row->sc_id] = [];

                    array_push($data_rows, $row);
                    array_push($by_sc_group[$row->sc_id], array(
                        'title' => $type['title'],
                        'data' => $data_rows
                    ));
                } else {
                    $index_data_group = -1;
                    $current = array_filter($by_sc_group[$row->sc_id], function ($fnd) use ($type) {
                        return $fnd['title'] == $type['title'];
                    });
                    if (count($current) > 0) {
                        $index_data_group = array_keys($current)[0];
                    }

                    if ($index_data_group < 0) {
                        array_push($by_sc_group[$row->sc_id], array(
                            'title' => $type['title'],
                            'data' => $data_rows
                        ));
                    } else {
                        array_push($by_sc_group[$row->sc_id][$index_data_group]['data'], $row);
                    }

                }
            }

            if (count($rows) > 0) {
                $have_morethanDay = true;
                array_push($data, array(
                    'title' => $type['title'],
                    'data' => $rows
                ));
            }
        }

        // $super_admin = DB::table('users')
        // ->select('users.email', 'users.name')
        // ->leftJoin('users_roles', 'users.roles', '=', 'users_roles.id')
        // ->where('users_roles.title', 'Super Administrator')
        // ->where('users.email', '!=', 'robert.widjaja@artugo.co.id')
        // ->where('users.status', 1)
        // ->get()
        // ->toArray();

        // $email_su_adm = implode(',', array_map(function ($obj) {
        //     return $obj->email;
        // }, $super_admin));

        // $email_su_adm = array_map(function ($obj) {
        //     return array( 'name' => $obj->name, 'address' => $obj->email );
        // }, $super_admin);

        // if ($have_morethanDay && $by_sc_group != null) {
        //     Mail::send(new InstallationServiceMail($data, $email_su_adm));
        // }

        foreach(array_keys($by_sc_group ?? []) as $index => $sc_id) {

            $bm = DB::table('users')
            ->select('users.email', 'users.name')
            ->leftJoin('users_roles', 'users.roles', '=', 'users_roles.id')
            ->leftJoin('ms_service_center_users', 'ms_service_center_users.users_id', 'users.id')
            ->where('ms_service_center_users.status', 1)
            ->whereIn('users_roles.title', ['Branch Manager','Branch Admin']) 
            ->where('users.status', 1)
            ->where('ms_service_center_users.sc_id', $sc_id)
            ->where('ms_service_center_users.status', 1)
            ->get()
            ->toArray();

            $email_bm = array_map(function ($obj) {
                return array( 'name' => $obj->name, 'address' => $obj->email );
            }, $bm);

            if ($by_sc_group[$sc_id] && count($email_bm) > 0) {
                Mail::send(new InstallationServiceMail($by_sc_group[$sc_id], $email_bm));
                // return view('email.installation-service-notify', ['data' => $by_sc_group[$sc_id]]);
            }
        }

        return json_encode(array('success' => true, 'message' => $have_morethanDay ? 'Notification More Than ' . $morethanDay . ' Days Sent...' : 'Does Not Have More Than ' . $morethanDay . ' Days Data!'));
        // return view('email.installation-service-notify', ['data' => $data]);

    }

}
