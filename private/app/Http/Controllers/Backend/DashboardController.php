<?php

namespace App\Http\Controllers\Backend;

use App\User;
use App\Products;
use App\Http\Controllers\Controller;
use App\Reg_service;
use App\Exports\Excel\DashboardData;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

use Hash;
use Auth;
use Excel;
use DB;

class DashboardController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect('artmin/login');
        }

        $is_ho = in_array(Auth::user()->roles, ['1', '2', '3']); // Super Administrator, Administrator, Customer Care
        $data['is_ho'] = $is_ho;

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
            // ->leftJoin('ms_service_center', 'ms_service_center.sc_id', '=', 'ms_loc_city.sc_id')
            ->leftJoin('ms_service_center', 'ms_service_center.sc_id', '=', 'reg_service.sc_id')
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

        $sql = "SELECT CASE WHEN rs.service_type = 0 THEN 'Install' ELSE 'Service' END AS 'Item',";
        if (!$is_ho) {
            $sql .= "
            SUM(CASE WHEN DATEDIFF(NOW(), rs.created_at) <= 3 THEN 1 ELSE 0 END) AS '<= 3d',
            SUM(CASE WHEN DATEDIFF(NOW(), rs.created_at) > 3 THEN 1 ELSE 0 END) AS '> 3d',
            COUNT(rs.service_type) AS 'Total'";
        } else {
            $sql .= "
            SUM(CASE WHEN msc.sc_code='HO' AND DATEDIFF(NOW(), rs.created_at) <= 3 THEN 1 ELSE 0 END) AS HO_LTE3,
            SUM(CASE WHEN msc.sc_code='HO' AND DATEDIFF(NOW(), rs.created_at) > 3 THEN 1 ELSE 0 END) AS HO_GT3,
            SUM(CASE WHEN msc.sc_code='SBY' AND DATEDIFF(NOW(), rs.created_at) <= 3 THEN 1 ELSE 0 END) AS SBY_LTE3,
            SUM(CASE WHEN msc.sc_code='SBY' AND DATEDIFF(NOW(), rs.created_at) > 3 THEN 1 ELSE 0 END) AS SBY_GT3,
            SUM(CASE WHEN msc.sc_code='KDR' AND DATEDIFF(NOW(), rs.created_at) <= 3 THEN 1 ELSE 0 END) AS KDR_LTE3,
            SUM(CASE WHEN msc.sc_code='KDR' AND DATEDIFF(NOW(), rs.created_at) > 3 THEN 1 ELSE 0 END) AS KDR_GT3,
            SUM(CASE WHEN msc.sc_code='SMR' AND DATEDIFF(NOW(), rs.created_at) <= 3 THEN 1 ELSE 0 END) AS SMR_LTE3,
            SUM(CASE WHEN msc.sc_code='SMR' AND DATEDIFF(NOW(), rs.created_at) > 3 THEN 1 ELSE 0 END) AS SMR_GT3,
            SUM(CASE WHEN msc.sc_code='MKS' AND DATEDIFF(NOW(), rs.created_at) <= 3 THEN 1 ELSE 0 END) AS MKS_LTE3,
            SUM(CASE WHEN msc.sc_code='MKS' AND DATEDIFF(NOW(), rs.created_at) > 3 THEN 1 ELSE 0 END) AS MKS_GT3,
            SUM(CASE WHEN msc.sc_code='BDG' AND DATEDIFF(NOW(), rs.created_at) <= 3 THEN 1 ELSE 0 END) AS BDG_LTE3,
            SUM(CASE WHEN msc.sc_code='BDG' AND DATEDIFF(NOW(), rs.created_at) > 3 THEN 1 ELSE 0 END) AS BDG_GT3,
            SUM(CASE WHEN msc.sc_code='BJM' AND DATEDIFF(NOW(), rs.created_at) <= 3 THEN 1 ELSE 0 END) AS BJM_LTE3,
            SUM(CASE WHEN msc.sc_code='BJM' AND DATEDIFF(NOW(), rs.created_at) > 3 THEN 1 ELSE 0 END) AS BJM_GT3,
            SUM(CASE WHEN msc.sc_code='DPS' AND DATEDIFF(NOW(), rs.created_at) <= 3 THEN 1 ELSE 0 END) AS DPS_LTE3,
            SUM(CASE WHEN msc.sc_code='DPS' AND DATEDIFF(NOW(), rs.created_at) > 3 THEN 1 ELSE 0 END) AS DPS_GT3,
            SUM(CASE WHEN msc.sc_code='YGY' AND DATEDIFF(NOW(), rs.created_at) <= 3 THEN 1 ELSE 0 END) AS YGY_LTE3,
            SUM(CASE WHEN msc.sc_code='YGY' AND DATEDIFF(NOW(), rs.created_at) > 3 THEN 1 ELSE 0 END) AS YGY_GT3,
            SUM(CASE WHEN msc.sc_code='SLO' AND DATEDIFF(NOW(), rs.created_at) <= 3 THEN 1 ELSE 0 END) AS SLO_LTE3,
            SUM(CASE WHEN msc.sc_code='SLO' AND DATEDIFF(NOW(), rs.created_at) > 3 THEN 1 ELSE 0 END) AS SLO_GT3,
            SUM(CASE WHEN msc.sc_code='PLB' AND DATEDIFF(NOW(), rs.created_at) <= 3 THEN 1 ELSE 0 END) AS PLB_LTE3,
            SUM(CASE WHEN msc.sc_code='PLB' AND DATEDIFF(NOW(), rs.created_at) > 3 THEN 1 ELSE 0 END) AS PLB_GT3,
            SUM(CASE WHEN msc.sc_code='PWK' AND DATEDIFF(NOW(), rs.created_at) <= 3 THEN 1 ELSE 0 END) AS PWK_LTE3,
            SUM(CASE WHEN msc.sc_code='PWK' AND DATEDIFF(NOW(), rs.created_at) > 3 THEN 1 ELSE 0 END) AS PWK_GT3"
            ;
        }

        $sql .= " FROM reg_service rs LEFT JOIN ms_service_center msc ON msc.sc_id = rs.sc_id";
        $sql .= $is_ho ? "" : " INNER JOIN ms_service_center_users mscu ON mscu.sc_id = msc.sc_id";
        $sql .= " WHERE rs.status = 0";
        $sql .= $is_ho ? "" : " AND mscu.users_id = " . Auth::user()->id;
        $sql .= " GROUP BY rs.service_type;";
        $data['svc_data'] = DB::select($sql) ?? [];

        $data['januari'] = $is_ho ? $this->get_reg_monthly('01') : 0;
        $data['februari'] = $is_ho ? $this->get_reg_monthly('02') : 0;
        $data['maret'] = $is_ho ? $this->get_reg_monthly('03') : 0;
        $data['april'] = $is_ho ? $this->get_reg_monthly('04') : 0;
        $data['mei'] = $is_ho ? $this->get_reg_monthly('05') : 0;
        $data['juni'] = $is_ho ? $this->get_reg_monthly('06') : 0;
        $data['juli'] = $is_ho ? $this->get_reg_monthly('07') : 0;
        $data['agustus'] = $is_ho ? $this->get_reg_monthly('08') : 0;
        $data['september'] = $is_ho ? $this->get_reg_monthly('09') : 0;
        $data['oktober'] = $is_ho ? $this->get_reg_monthly('10') : 0;
        $data['november'] = $is_ho ? $this->get_reg_monthly('11') : 0;
        $data['desember'] = $is_ho ? $this->get_reg_monthly('12') : 0;

        if ($is_ho) {
            $data['top_product'] = DB::table('reg_warranty')
                ->select(
                    'ms_products.product_name_odoo as product_name',
                    DB::raw("count('reg_warranty.product_id') as sales")
                )
                ->leftJoin('ms_products', 'ms_products.product_id', '=', 'reg_warranty.product_id')
                ->groupBy('reg_warranty.product_id')
                ->orderBy('sales', 'desc')
                ->limit(4)
                ->get();

            $data['top_customer'] = DB::table('reg_warranty')
                ->select(
                    'ms_members.name as customer_name',
                    DB::raw("count('reg_warranty.member_id') as sales")
                )
                ->leftJoin('ms_members', 'ms_members.id', '=', 'reg_warranty.member_id')
                ->groupBy('reg_warranty.member_id')
                ->orderBy('sales', 'desc')
                ->limit(4)
                ->get();

            $data['point_summary'] = DB::select("
            SELECT
                mp.waiting,mp.waiting_point,mp.rejected,mpa.approved_point,mpa.used_point
            FROM
            (
                SELECT
                1 AS id,
                SUM(CASE WHEN status='waiting' THEN 1 ELSE 0 END) AS waiting,
                SUM(CASE WHEN status='waiting' THEN value ELSE 0 END) AS waiting_point,
                SUM(CASE WHEN status='rejected' THEN 1 ELSE 0 END) AS rejected
                FROM member_point
            ) AS mp LEFT JOIN
            (
                SELECT
                1 AS id,
                SUM(CASE WHEN `type`='in' THEN value ELSE 0 END) AS approved_point,
                SUM(CASE WHEN `type`='out' THEN value*-1 ELSE 0 END) AS used_point
                FROM member_point_adjustment
            ) AS mpa ON mp.id=mpa.id;");
        }

        return view('backend.dashboard', $data);
    }

    private function get_reg_monthly($month)
    {
        $data = DB::table('reg_warranty')->where('created_at', 'like', date('Y-') . $month . '%')->count();

        return $data;
    }

    public function fix_slug()
    {
        $data = DB::table('ms_products')->whereNull('slug')->get();

        foreach ($data as $key => $value) {
            $slug = strtolower(str_replace(' ', '-', $value->product_code));
            print_r($slug);

            DB::table('ms_products')->where('product_id', $value->product_id)->update([
                'slug' => $slug
            ]);

            echo "<br><hr><br>";
        }
    }

    public function export()
    {
        $export = new DashboardData;
        // $export->set_is_ho(in_array(Auth::user()->roles, ['1', '2', '3']));
        return Excel::download($export, 'artugo_dashboard_service_progress ' . date('Y-m-d') . '.xlsx');

    }

    /**
     * service_type        'service' | 'installation'
     * period_type         'last7' | 'morethan7' | 'last3' | 'morethan3'
     * status_type         'new' | 'schedule' | 'progress' | 'completed' | 'canceled'
     * 
    */
    public function get_service_count(Request $request)
    {

        $service_type = $request->query('service_type');
        $period_type = $request->query('period_type');
        $status_type = $request->query('status_type');
        $resdata_type = $request->query('resdata_type');

        $status_new = $service_type == 'service' ? 2 : 12;
        $status_completed = $service_type == 'service' ? 1 : 13;
        $status_progress = $service_type == 'service' ? 5 : 16;
        $status_sch = $service_type == 'service' ? 3 : 14;
        $status_rsch = $service_type == 'service' ? 4 : 15;

        $QB = DB::table('reg_service')
        ->where('service_type', $service_type == 'service' ? 1 : 0)
        ->orderBy('created_at', 'DESC');

        $SBQ_Progress = '(SELECT status FROM reg_service_progress WHERE service_id = reg_service.service_id ORDER BY created_at DESC LIMIT 1)';

        if (($period_type == 'morethan7' || $period_type == 'morethan3') && $status_type == 'new') {

            $day = '3';
            if ($period_type == 'morethan7') {
                $day = '7';
            }

            $QB->whereRaw('DATEDIFF(NOW(), created_at) > ' . $day)
            ->where('status', 0)
            ->whereRaw("IFNULL(visit_technician, '') = ''")
            ->whereRaw($SBQ_Progress . ' = ' . $status_new);

        } else if (($period_type == 'morethan7' || $period_type == 'morethan3') && $status_type == 'schedule') {

            $day = '3';
            if ($period_type == 'morethan7') {
                $day = '7';
            }

            $QB->whereRaw('DATEDIFF(NOW(), created_at) > ' . $day)
            ->where('status', 0)
            ->whereRaw("IFNULL(visit_technician, '') != ''")
            ->whereRaw($SBQ_Progress . ' IN (' . $status_sch . ',' . $status_rsch . ')');

        } else if (($period_type == 'morethan7' || $period_type == 'morethan3') && $status_type == 'progress') {

            $day = '3';
            if ($period_type == 'morethan7') {
                $day = '7';
            }

            $QB->whereRaw('DATEDIFF(NOW(), created_at) > ' . $day)
            ->where('status', 0)
            ->whereRaw("IFNULL(visit_technician, '') != ''")
            ->whereRaw($SBQ_Progress . ' = ' . $status_progress);

        // } else if ($period_type == 'morethan7' && $status_type == 'completed') {

            // $QB->whereRaw('DATEDIFF(NOW(), created_at) > 7 ')
            // ->where('status', 1)
            // ->whereRaw($SBQ_Progress . ' = ' . $status_completed);

        } else if (($period_type == 'last7' || $period_type == 'last3') && $status_type == 'new') {

            $day = '3';
            if ($period_type == 'last7') {
                $day = '7';
            }

            $QB->whereRaw('DATEDIFF(NOW(), created_at) BETWEEN 0 AND ' . $day)
            ->where('status', 0)
            ->whereRaw("IFNULL(visit_technician, '') = ''")
            ->whereRaw($SBQ_Progress . ' = ' . $status_new);

        } else if (($period_type == 'last7' || $period_type == 'last3') && $status_type == 'schedule') {

            $day = '3';
            if ($period_type == 'last7') {
                $day = '7';
            }

            $QB->whereRaw('DATEDIFF(NOW(), created_at) BETWEEN 0 AND ' . $day)
            ->where('status', 0)
            ->whereRaw("IFNULL(visit_technician, '') != ''")
            ->whereRaw($SBQ_Progress . ' IN (' . $status_sch . ',' . $status_rsch . ')');

        } else if (($period_type == 'last7' || $period_type == 'last3') && $status_type == 'progress') {

            $day = '3';
            if ($period_type == 'last7') {
                $day = '7';
            }

            $QB->whereRaw('DATEDIFF(NOW(), created_at) BETWEEN 0 AND ' . $day)
            ->where('status', 0)
            ->whereRaw("IFNULL(visit_technician, '') != ''")
            ->whereRaw($SBQ_Progress . ' = ' . $status_progress);

        } else if (($period_type == 'last7' || $period_type == 'last3') && $status_type == 'completed') {

            $day = '3';
            if ($period_type == 'last7') {
                $day = '7';
            }

            $QB->whereRaw('DATEDIFF(NOW(), created_at) BETWEEN 0 AND ' . $day)
            ->where('status', 1);

        }

        // DB::enableQueryLog();
        if ($resdata_type == 'rows') {
            $data = $QB->get();
        } else {
            $data = $QB->count();
        }
        // dd(DB::getQueryLog());

        return json_encode($data);
    }

    public function slideshow(Request $request)
    {

        if ($request->ajax()) {
            $qb = DB::table('ms_slideshow');
            return DataTables::of($qb)->toJson();
        }

        return view('backend.slideshow', []);
    }

    public function create_update_slideshow(Request $request)
    {
        $url_image = '';
        if (!empty($request->file('image'))) {
            $file = $request->file('image');
            $upload_loc = 'uploads/slides/';
            $image = Uuid::uuid4()->toString() .  "." . $file->getClientOriginalExtension();
            $file->move($upload_loc, $image);
            $url_image = url($upload_loc . $image);
        } else {
            $url_image = $request->input('image_old');
        }

        $url_image_mobile = '';
        if (!empty($request->file('image_mobile'))) {
            $file = $request->file('image_mobile');
            $upload_loc = 'uploads/slides/';
            $image_mobile = Uuid::uuid4()->toString() .  "." . $file->getClientOriginalExtension();
            $file->move($upload_loc, $image_mobile);
            $url_image_mobile = url($upload_loc . $image_mobile);
        } else {
            $url_image_mobile = $request->input('image_mobile_old');
        }

        // $data['display'] = $request->input('display');
        $data['type'] = $request->input('type');
        $data['title'] = $request->input('title');
        $data['sub_title'] = $request->input('sub_title');
        $data['image'] = $url_image;
        $data['image_mobile'] = $url_image_mobile;
        $data['url'] = $request->input('url') ?? "";
        $data['btn_text'] = $request->input('btn_text');
        $data['ordering'] = $request->input('ordering');
        $data['content'] = $request->input('content');
        
        if ($request->input('id')) {
            $data['updated_by'] = Auth::user()->id;
            $data['updated_at'] = date('Y-m-d H:i:s');
            DB::table('ms_slideshow')->where('id', $request->input('id'))->update($data);
        } else {
            $data['created_by'] = Auth::user()->id;
            $data['created_at'] = date('Y-m-d H:i:s');

            $rules = array(
                'image' => 'required',
                'image_mobile' => 'required'
            );
            $validator = Validator::make($request->all(), $rules);
            if (empty($url_image) && empty($url_image_mobile)) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::table('ms_slideshow')->insert($data);
        }

        return redirect('artmin/slide-show');
    }

    public function show_hide_slideshow(Request $request)
    {
        $data['display'] = $request->input('display');
        DB::table('ms_slideshow')->where('id', $request->input('id'))->update($data);
        return [
            'success' => true,
            'data' => $data
        ];
    }

}
