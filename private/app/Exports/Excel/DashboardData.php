<?php

namespace App\Exports\Excel;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Reg_service;
use Auth;

class DashboardData implements FromView, ShouldAutoSize
{

    private $is_ho;

    public function set_is_ho($val) {
        $this->is_ho = $val;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {

        $is_ho = in_array(Auth::user()->roles, ['1', '2', '3']); // Super Administrator, Administrator, Customer Care

        $svc_qb = Reg_service::select(
            'reg_service.*',
            'ms_products.product_name_odoo',
            'ms_loc_city.city_name',
            'ms_loc_province.province_name',
            'reg_warranty.serial_no',
            'reg_warranty.purchase_date',
            'ms_service_center.sc_location',
            'ms_technician.name as technicianName',
            'store_location_regional.regional_name as branchName'
        )
            ->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_service.product_id')
            ->leftJoin('ms_loc_city', 'ms_loc_city.city_id', '=', 'reg_service.service_city_id')
            ->leftJoin('ms_loc_province', 'ms_loc_province.province_id', '=', 'ms_loc_city.province_id')
            ->leftJoin('reg_warranty', 'reg_warranty.warranty_id', '=', 'reg_service.warranty_id')
            ->leftJoin('ms_service_center', 'ms_service_center.sc_id', '=', 'ms_loc_city.sc_id')
            ->leftJoin('store_location_regional', 'ms_service_center.region_id', '=', 'store_location_regional.id')
            ->leftJoin('ms_technician', 'ms_technician.id', '=', 'reg_service.visit_technician');

        if (!$is_ho) {
            $svc_qb->join('ms_service_center_users', 'ms_service_center_users.sc_id', '=', 'ms_service_center.sc_id')
                ->where('ms_service_center_users.status', 1)
                ->where('ms_service_center_users.users_id', Auth::user()->id);
        }

        $data['service_request'] = $svc_qb
            ->where('reg_service.status', 0)
            ->orderBy('reg_service.created_at', 'desc')
            ->get();

        return view('backend.dashboard_export_excel',$data);
    }
}
