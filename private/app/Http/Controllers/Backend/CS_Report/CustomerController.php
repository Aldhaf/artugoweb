<?php

namespace App\Http\Controllers\Backend\CS_Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\Excel\CS_Report\CustomerProducts;
use DB;
use Excel;
use App\Reg_warranty;

class CustomerController extends Controller
{
    public function customer_products()
    {
        $fetchData = DB::table('ms_members')
            ->select(
                'ms_members.id',
                'ms_members.name',
                'ms_members.phone',
                'ms_members.email',
                DB::raw('COUNT(reg_warranty.product_id) as registered_products')
            )
            ->join('reg_warranty', 'reg_warranty.member_id', '=', 'ms_members.id')
            ->where('reg_warranty.status', '1')
            // ->where('reg_warranty.verified', '1')
            ->groupBy('ms_members.id')
            ->orderBy('registered_products', 'desc');

        if (isset($_GET['purchase_date_filter'])) {
            $expd = explode(' ', $_GET['purchase_date_filter']);
            if (!empty($expd[0]) && !empty($expd[2])) {
                $fetchData->whereBetween('reg_warranty.purchase_date', [$expd[0], $expd[2]]);
            }
        }

        $limit = null;
        if (!empty($_GET['limit'])) {
            if ($_GET['limit'] != '-') {
                $limit = $_GET['limit'];
                $fetchData->limit($_GET['limit']);
            }
        }


        $data['customer_products'] = $fetchData->get();
        $data['period'] = (!empty($_GET['purchase_date_filter']) ? $_GET['purchase_date_filter'] : date('Y-m-01') . ' - ' . date('Y-m-d'));
        $data['limit'] = $limit;

        return view('backend.cs_report.customer_products.listData', $data);
    }

    public function customer_products_export($from, $to)
    {
        $export = new CustomerProducts;
        $export->setDateFrom(date('Y-m-d', strtotime($from)));
        $export->setDateTo(date('Y-m-d', strtotime($to)));

        return Excel::download($export, 'CSReport_CustomerProduct_' . date('Ymd', strtotime($from)) . '-' . date('Ymd', strtotime($from)) . '.xlsx');
    }

    public function customer_products_detail($member_id)
    {
        $fetchData = Reg_warranty::select(
            'reg_warranty.*',
            'ms_products.product_name_odoo as product_name',
        )
            ->join('ms_products', 'reg_warranty.product_id', '=', 'ms_products.product_id')
            ->where('reg_warranty.member_id', $member_id)
            ->where('reg_warranty.status', '1')
            // ->where('reg_warranty.verified', '1')
            ->orderBy('reg_warranty.created_at', 'desc');


        if (isset($_GET['purchase_date_filter'])) {
            $expd = explode(' ', $_GET['purchase_date_filter']);
            if (!empty($expd[0]) && !empty($expd[2])) {
                $fetchData->whereBetween('reg_warranty.purchase_date', [$expd[0], $expd[2]]);
            }
        }


        $data['customer_products'] = $fetchData->get();
        $data['period'] = (!empty($_GET['purchase_date_filter']) ? $_GET['purchase_date_filter'] : date('Y-m-01') . ' - ' . date('Y-m-d'));


        return view('backend.cs_report.customer_products.detailData', $data);
    }
}
