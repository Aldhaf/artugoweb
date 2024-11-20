<?php

namespace App\Http\Controllers;

use App\User;
use App\Slideshow;
use App\Http\Controllers\Controller;
use DB;
use Hash;
use App\Members;

class HomeController extends Controller
{

    public static function getCategoryParent() {
        $category_parents = DB::table("ms_categories")
            ->select(DB::raw("name, slug, IFNULL(icon, '') AS icon, CONCAT('products/category/', slug) AS href"))
            ->where("active", 1)
            ->where("parent_id", 0)
            ->get();
        return $category_parents;
    }

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function index()
    {
        $data['slideshow'] = Slideshow::where("display", 1)->orderBy('ordering')->get();
        $data['category_parents'] = $this->getCategoryParent();

        return view('web.home', $data);
    }

    public static function getSubCategory($slug, $flag) {
        $where = "";
        if ($flag == "footer_nav") {
            $where = " AND mc.footer_nav = 1 AND sub_mc.footer_nav = 1";
        } else if ($flag == "highlight_nav") {
            $where = " AND mc.highlight_nav = 1 AND sub_mc.highlight_nav = 1";
        } else if ($flag == "mega_menu") {
        }

        $cool_product = DB::table("ms_categories AS mc")
        ->select(DB::raw("sub_mc.name, IFNULL(sub_mc.icon, '') AS icon, CONCAT('products/category/{$slug}/', IFNULL(sub_mc.slug, '')) AS href"))
        ->leftJoin("ms_categories AS sub_mc", "sub_mc.parent_id", "=", "mc.category_id")
        ->where("mc.slug", $slug)
        ->where("mc.active", 1)
        ->where("sub_mc.active", 1)
        ->whereRaw("IFNULL(sub_mc.name,'') <> '' " . $where)
        // ->where("mc.footer_nav", 1)
        // ->where("sub_mc.footer_nav", 1)
        ->orderBy("sub_mc.ordering")
        ->get();

        return $cool_product;
    }

    // public function fixmemberid()
    // {
    //     $data = DB::table('reg_warranty')->whereNull('member_id')->get();

    //     foreach ($data as $key => $value) {
    //         echo "<h2>WARRANTY : </h2>";
    //         print_r($value);
    //         echo "<h2>MEMBER : </h2>";
    //         $member = DB::table('ms_members')->where('phone', $value->reg_phone)->first();
    //         print_r($member);
    //         echo "<br>";
    //         // echo (!empty($member) ? 'FOUND' : 'NOT FOUND');
    //         if (!empty($member)) {
    //             echo "FOUND";
    //             DB::table('reg_warranty')->where('warranty_id', $value->warranty_id)->update([
    //                 'member_id' => $member->id
    //             ]);
    //         } else {
    //             $add_member['name'] = $value->reg_name;
    //             $add_member['email'] = $value->reg_email;
    //             $add_member['phone'] = str_replace(" ", "", $value->reg_phone);
    //             $add_member['password'] = Hash::make('123456');
    //             $add_member['status'] = 1;
    //             $add_member['created_at'] = date("Y-m-d H:i:s");

    //             $create = Members::insertGetId($add_member);
    //             if ($create) {
    //                 DB::table('reg_warranty')->where('warranty_id', $value->warranty_id)->update([
    //                     'member_id' => $create
    //                 ]);
    //             } else {
    //                 echo 'STATUS INSERT :NOK:';
    //             }
    //         }
    //         echo "<hr>";
    //     }
    // }
}
