<?php

namespace App\Exports\Excel;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Reg_service;
use DB;
use Auth;

class ServiceInstallation implements FromView, ShouldAutoSize
{

    private $dateFrom;
    private $dateTo;

    public function setDateFrom(string $dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }

    public function setDateTo(string $dateTo)
    {
        $this->dateTo = date('Y-m-d', strtotime($dateTo . ' + 1 day'));
    }

    public function view(): View
    {
        // $installation_request =  DB::table('vRegService')
        //     ->where('service_type', 0)
        //     ->orderBy('created_at', 'desc')
        //     ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
        //     ->get();

        // return view('backend.installation.export-request-installation-excel', [
        //     'service' => $installation_request
        // ]);

        $svc_qb = Reg_service::select(
            'reg_service.*',
            'ms_products.product_name_odoo',
            'ms_loc_city.city_name',
            'ms_loc_province.province_name',
            'ms_members.name as userName',
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
            ->leftJoin('ms_technician', 'ms_technician.id', '=', 'reg_service.visit_technician')
            ->leftJoin('ms_members', 'ms_members.id', '=', 'reg_warranty.member_id');

        $is_ho = in_array(Auth::user()->roles, ['1', '2', '3']); // Super Administrator, Administrator, Customer Care
        if (!$is_ho) {
            $svc_qb->join('ms_service_center_users', 'ms_service_center_users.sc_id', '=', 'ms_service_center.sc_id')
                ->where('ms_service_center_users.status', 1)
                ->where('ms_service_center_users.users_id', Auth::user()->id);
        }

        $service_request = $svc_qb
            ->where('reg_service.service_type', 0)
            ->where('reg_service.isDeleted', '0')
            ->orderBy('reg_service.created_at', 'desc')
            ->whereBetween('reg_service.created_at', [$this->dateFrom, $this->dateTo])
            ->get();

        return view('backend.service.export-request-service-excel', [
            'service_request' => $service_request
        ]);
    }
}
