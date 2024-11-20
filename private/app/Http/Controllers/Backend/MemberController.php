<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\Excel\MemberPoint;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

use Auth;
use Excel;
use DB;

class MemberController extends Controller
{
    public function index(Request $request)
    {

        // DB::enableQueryLog();

        $member_qb = DB::table('ms_members')
            ->select(
                'ms_members.*',
                DB::raw('FORMAT(ms_members.balance_point,2) AS balance_point_format')
                // DB::raw("(SELECT FORMAT(SUM(mpa.value),2) AS total FROM member_point_adjustment mpa WHERE mpa.member_id=ms_members.id) AS balance_point")
            )
            ->whereNull('merge_to')
            ->orderBy('ms_members.created_at', 'desc');

        if (isset($_GET['name']) && !empty($_GET['name'])) {
            $member_qb->where('name', 'like', '%' . $_GET['name'] . '%');
        }
        if (isset($_GET['phone']) && !empty($_GET['phone'])) {
            $member_qb->where('phone', 'like', '%' . $_GET['phone'] . '%');
        }
        if (isset($_GET['email']) && !empty($_GET['email'])) {
            $member_qb->where('email', 'like', '%' . $_GET['email'] . '%');
        }

        if ($request->ajax()) {
            return DataTables::of($member_qb)->toJson();
        } else {
            $data['member'] = []; //$member_qb->limit(300)->get();
            return view('backend.member.list', $data);
        }
    }

    public function points(Request $request)
    {

        // DB::enableQueryLog();

        $member_qb = DB::table('ms_members')
            ->select(
                // 'ms_members.id',
                'member_point.member_id',
                'member_point.id',
                'member_point.expired_at',
                'ms_members.name',
                'ms_members.email',
                'ms_members.phone',
                'ms_members.address',
                'ms_members.city_id',
                'ms_members.city',
                'ms_members.birth_date',
                'ms_members.profile_image',
                'ms_members.ktp',
                'ms_members.gender',
                'ms_members.title_gender',
                'reg_warranty.warranty_id',
                'reg_warranty.warranty_no',
                'reg_warranty.serial_no',
                'reg_warranty.testimony',
                'reg_warranty.purchase_date',
                'member_point.status',
                DB::raw('FORMAT(member_point.value, 2) AS claimed_point'),
                'pd.product_name_odoo',
                'rating.star',
                'rating.review'
                // DB::raw("(SELECT FORMAT(SUM(mpa.value),2) AS total FROM member_point_adjustment mpa WHERE mpa.member_id=ms_members.id AND mpa.warranty_id=reg_warranty.warranty_id) AS balance_point")
            )
            ->join('member_point', 'member_point.member_id', '=', 'ms_members.id')
            ->join('reg_warranty', 'reg_warranty.warranty_id', '=', 'member_point.warranty_id')
            ->join('ms_products AS pd', 'pd.product_id', '=', 'reg_warranty.product_id')
            ->leftJoin('rating', function ($join) {
                $join->on('rating.productID', '=', 'pd.product_id');
                $join->on('rating.memberID', '=', 'reg_warranty.member_id');
            })
            ->whereNull('merge_to');

        if (isset($_GET['name']) && !empty($_GET['name'])) {
            $member_qb->where('name', 'like', '%' . $_GET['name'] . '%');
        }
        if (isset($_GET['phone']) && !empty($_GET['phone'])) {
            $member_qb->where('phone', 'like', '%' . $_GET['phone'] . '%');
        }
        if (isset($_GET['email']) && !empty($_GET['email'])) {
            $member_qb->where('email', 'like', '%' . $_GET['email'] . '%');
        }

        $member_qb->orderBy('member_point.created_at', 'desc');
        if ($request->ajax()) {
            return DataTables::of($member_qb)->toJson();
        } else {
            $data['member'] = []; //$member_qb->limit(300)->get();
            return view('backend.member.point-list', $data);
        }
    }

    public function point_reject($point_id)
    {
        DB::table("member_point")
            ->where("id", $point_id)
            ->update([
                'status' => 'rejected',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);

        return [
            'success' => true
        ];
    }

    public function point_approve($point_id)
    {
        $member_point = DB::table("member_point")
            ->select("member_id", "warranty_id", "value", "expired_at")
            ->where("id", $point_id)
            ->first();

        $member = DB::table("ms_members")
            ->select("id", "name", "gender", "birth_date", "profile_image", "email", "phone", "address", "city_id", "city", "title_gender", "ktp")
            ->where("id", $member_point->member_id)->first();

        $validator = Validator::make((array) $member, [
            'name' => 'required',
            'gender' => 'required',
            'birth_date' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city_id' => 'required',
            'title_gender' => 'required',
            'ktp' => 'required'
        ]);
        $validator->setAttributeNames(array(
            'name' => 'Name',
            'gender' => 'Gender',
            'birth_date' => 'Birth Date',
            'email' => 'Email',
            'phone' => 'Phone Number',
            'address' => 'Address',
            'city_id' => 'City',
            'title_gender' => 'Gender',
            'ktp' => 'NIK / KTP',
        ));

        if ($validator->fails()) {
            $complete_profile = false;
            return [
                'success' => false,
                'message' => 'Data Profil Member tidak lengkap untuk claim poin...!',
                'messages' => $validator->messages()
            ];
        }

        $warranty = DB::table("reg_warranty")
            ->select("warranty_no", "testimony", "product_id")
            ->where("warranty_id", $member_point->warranty_id)
            ->first();
        
        // if ($warranty->testimony == null || empty($warranty->testimony)) {
        //     return [
        //         'success' => false,
        //         'message' => 'Belum ada Testimony untuk Product...!'
        //     ];
        // }

        $rating = DB::table("rating")
            ->select("star", "review")
            ->where("memberID", $member_point->member_id)
            ->where("productID", $warranty->product_id)
            ->first();
        
        if ($rating->review == null || empty($rating->review)) {
            return [
                'success' => false,
                'message' => 'Belum ada Product Review...!'
            ];
        }

        DB::table("member_point")
            ->where("id", $point_id)
            ->update([
                'status' => 'approved',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);

        $point_description = 'Approved Point Warranty ' . $warranty->warranty_no . ' expired at ' . date("d-M-Y", strtotime($member_point->expired_at));
        $member_point_adjustment = [
            'member_id' => $member_point->member_id,
            'warranty_id' => $member_point->warranty_id,
            'point_id' => $point_id,
            'trx_date' => date('Y-m-d H:i:s'),
            'description' => $point_description,
            'type' => 'in',
            'value' => $member_point->value,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::user()->id,
        ];
        DB::table('member_point_adjustment')->insert($member_point_adjustment);

        $sum_point = DB::table('member_point_adjustment')
            ->select(
                DB::raw("SUM(IF(`type`='in', value, 0)) AS total_point"),
                DB::raw("SUM(IF(`type`='out', value*-1, 0)) AS used_point"),
                DB::raw("IFNULL(SUM(value), 0) AS balance"),
            )
            ->where('member_id', $member_point->member_id)
            ->first();

        DB::table('ms_members')
            ->where('id', $member_point->member_id)
            ->update([
                'total_point' => floatval($sum_point->total_point),
                'used_point' => floatval($sum_point->used_point),
                'balance_point' => floatval($sum_point->balance)
            ]);

        return [
            'success' => true
        ];
    }

    public function point_adjustment(Request $request)
    {

        $balance_point = DB::table("member_point_adjustment")
            ->select(DB::raw("IFNULL(SUM(value),0) AS total"))
            // ->where("warranty_id", $request->input("warranty_id"))
            ->where('member_id', $request->member_id)
            ->first();

        if ($request->adj_type == 'out' && $request->value > $balance_point->total) {
            return [
                'success' => false,
                'message' => 'Sisa Point ' . number_format($balance_point->total, 2) . ' tidak mencukupi!'
            ];
        }

        $member_point_id = null;

        if ($request->adj_type == 'in' && $request->expired_at != null) {
            $firstExist = DB::table('member_point')->select("id")
                ->where('member_id', $request->member_id)
                ->where('warranty_id', $request->warranty_id)
                ->first();

            $member_point = [
                'member_id' => $request->member_id,
                'warranty_id' => $request->warranty_id,
                'description' => $request->description,
                // 'expired_at' => date('Y-m-d', strtotime($request->expired_at)),
                'expired_at' => date('Y-m-d', strtotime('+1 years -1 days', strtotime($request->trx_date))), // default 1th
                'value' => $request->value,
                'type' => $firstExist == null ? 'first' : 'additional',
                'used' => 0,
                'balance' => $request->value,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id,
            ];
            $member_point_id = DB::table('member_point')->insertGetId($member_point);
        }

        $member_point_adjustment = [
            'member_id' => $request->member_id,
            'warranty_id' => $request->adj_type == 'in' ? null : $request->warranty_id,
            'description' => $request->description,
            'point_id' => $member_point_id,
            'trx_date' => date('Y-m-d', strtotime($request->trx_date)),
            'type' => $request->adj_type,
            'value' => $request->value * ($request->adj_type == 'in' ? 1 : -1),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::user()->id,
        ];

        DB::table('member_point_adjustment')->insert($member_point_adjustment);

        // $balance_point = DB::table("member_point_adjustment")
        //     ->select(DB::raw("IFNULL(SUM(value),0) AS total"))
        //     ->where("warranty_id", $request->input("warranty_id"))
        //     ->first();

        // $total_point = DB::table("member_point_adjustment")
        //     ->select(DB::raw("IFNULL(SUM(value),0) AS total"))
        //     ->where("warranty_id", $request->input("warranty_id"))
        //     ->where("value", '>', 0)
        //     ->first();

        // $used_point = DB::table("member_point_adjustment")
        //     ->select(DB::raw("IFNULL(SUM(value),0) AS total"))
        //     ->where("warranty_id", $request->input("warranty_id"))
        //     ->where("value", '<', 0)
        //     ->first();

        // DB::table("reg_warranty")
        //     ->where('warranty_id', $request->input("warranty_id"))
        //     ->update([
        //         'balance_point' => $balance_point->total,
        //         'total_point' => $total_point->total,
        //         'used_point' => $used_point->total * -1
        //     ]);

        $sum_point = DB::table('member_point_adjustment')
            ->select(
                DB::raw("SUM(IF(`type`='in', value, 0)) AS total_point"),
                DB::raw("SUM(IF(`type`='out', value*-1, 0)) AS used_point"),
                DB::raw("IFNULL(SUM(value), 0) AS balance"),
            )
            ->where('member_id', $request->member_id)
            ->first();

        DB::table('ms_members')
            ->where('id', $request->member_id)
            ->update([
                'total_point' => floatval($sum_point->total_point),
                'used_point' => floatval($sum_point->used_point),
                'balance_point' => floatval($sum_point->balance),
            ]);

        return [
            'success' => true,
            'message' => 'Success Save Point!'
        ];
    }

    public function membersJSON(Request $request)
    {
        $qb = DB::table('ms_members')
            ->select('id', 'name', 'phone', 'email', 'address', 'city', 'email', 'city_id');
        $keywords = $request->get('q') ?? '';
        if ($keywords) {
            $qb->whereRaw("name LIKE '%" . $keywords . "%' OR phone LIKE '%" . $keywords . "%' OR email LIKE '%" . $keywords . "%'");
        }
        return $qb->get();
    }

    public function detail_points ($member_id)
    {
        // $sum_point = DB::table('member_point_adjustment')
        //     ->select(DB::raw("SUM(value) AS total"))
        //     ->where('member_id', $member_id)
        //     ->first();
        // $data["total_points"] = $sum_point->total;
        $member = DB::table("ms_members")
            ->select("id", "name", "gender", "birth_date", "profile_image", "email", "phone", "address", "city_id", "city", "title_gender", "ktp", "total_point", "used_point", "balance_point", )
            ->where("id", $member_id)
            ->first();
        $data["member"] = $member;
        $data["points"] = DB::table('member_point AS mp')
            ->select('mp.id', 'mp.member_id', 'mp.warranty_id', 'rw.warranty_no', 'rw.serial_no', 'pd.product_id', 'pd.product_name', 'pd.product_image',
                DB::raw('IF(DATEDIFF(mp.expired_at, NOW()) < 0, "expired", "ready" ) AS status'), 'mp.expired_at', DB::raw('mp.value AS point'),'mp.created_at'
            )
            ->join('reg_warranty AS rw', 'rw.warranty_id', '=', 'mp.warranty_id')
            ->join('ms_products AS pd', 'pd.product_id', '=', 'rw.product_id')
            ->where('mp.member_id', $member_id)
            ->orderBy('mp.expired_at', 'DESC')
            ->get();
        $data['points_adj'] = DB::table('member_point_adjustment')
            ->where('member_id', $member_id)
            ->orderBy('trx_date', 'DESC')
            ->get();

        return view('backend.member.point-detail', $data);
    }

    public function detail_warranty_points ($warranty_id)
    {
        $pointsQB = DB::table('member_point_adjustment AS mpa')
            ->select('mpa.id', 'mpa.member_id', 'mpa.warranty_id', 'mpa.description',
                'mpa.trx_date', 'mpa.type', DB::raw('mpa.value AS point')
            )
            ->leftJoin('member_point AS mp', 'mp.id', '=', 'mpa.point_id')
            ->where('mpa.warranty_id', $warranty_id);

        $data['points'] = $pointsQB->orderBy('mpa.trx_date', 'DESC')->get();

        $productQB = DB::table('reg_warranty AS rw')
            ->select('rw.warranty_no', 'rw.serial_no', 'pd.product_id', 'pd.product_name', 'pd.product_image', DB::raw('0 as balance_point') )
            ->join('ms_products AS pd', 'pd.product_id', '=', 'rw.product_id')
            ->where('rw.warranty_id', $warranty_id);

        $data['product'] = $productQB->first();
        $data['warranty'] = DB::table('reg_warranty')->select('member_id')->where('warranty_id', $warranty_id)->first();

        return view('backend.member.point-warranty', $data);
    }

    public function reset_password_process(Request $request)
    {
        $data = [
            'password' => Hash::make($request->input('password'))
        ];
        DB::table('ms_members')->where('id', $request->input('member_id'))->update($data);
    }

    public function marge_account(Request $request)
    {
        // Update RegWarranty
        DB::table('reg_warranty')->where('member_id', $request->input('merge_account_id_old'))->update([
            'member_id' => $request->input('merge_account_id')
        ]);

        DB::table('member_point')->where('member_id', $request->input('merge_account_id_old'))->update([
            'member_id' => $request->input('merge_account_id')
        ]);

        DB::table('member_point_adjustment')->where('member_id', $request->input('merge_account_id_old'))->update([
            'member_id' => $request->input('merge_account_id')
        ]);

        // Delete Account
        DB::table('ms_members')->where('id', $request->input('merge_account_id_old'))->update([
            'merge_to' => $request->input('merge_account_id'),
            'status' => '0'
        ]);

    }

    public function changeStatus(Request $request, $member_id)
    {
        $data['status'] = $request->input('active');
        DB::table('ms_members')->where('id', $member_id)->update($data);
    }

    public function edit_account($member_id)
    {
        $data['member'] = DB::table('ms_members')->where('id', $member_id)->first();
        if (!empty($data['member'])) {
            return view('backend.member.form', $data);
        } else {
            return redirect('artmin/member');
        }
    }

    public function edit_account_process(Request $request)
    {

        $city_id = $request->input('city');
        $city_name = '';

        if (!empty($city_id)) {
            $city = DB::table('ms_loc_city')->select(DB::raw("CONCAT(ms_loc_province.province_name,' - ', ms_loc_city.city_name) AS city_name"))
                ->join('ms_loc_province', 'ms_loc_province.province_id', '=', 'ms_loc_city.province_id')
                ->where('city_id', $city_id)->first();
            if ($city) {
                $city_name = $city->city_name;
            }
        }

        DB::table('ms_members')->where('id', $request->input('member_id'))->update([
            'name' => $request->input('fullname'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'gender' => $request->input('gender'),
            'title_gender' => $request->input('title_gender'),
            'birth_date' => date('Y-m-d', strtotime($request->input('birth_date'))),
            'city_id' => $request->input('city'),
            'city' => $city_name,
            'profile_image' => $request->input('profile_image'),
            'ktp' => $request->input('ktp'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::user()->id
        ]);
    }

    public function export($from, $to)
    {
        $export = new MemberPoint;
        $export->setDateFrom(date('Y-m-d', strtotime($from)));
        $export->setDateTo(date('Y-m-d', strtotime($to)));
        return Excel::download($export, 'artugo_member_points_' . date('Y-m-d') . '.xlsx');
    }

}
