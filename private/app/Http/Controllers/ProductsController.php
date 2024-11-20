<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Products;
use App\Products_spec;
use App\Products_category;
use App\Products_gallery;
use App\Products_warranty;
use App\Http\Controllers\Controller;
use DB;
use Session;


class ProductsController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function index()
    {

        $products = Products::select(
            'ms_products.*',
            'ms_products_icon.icon as icon_url',
            'ms_products_icon.title as icon_title',
        )
            ->leftJoin('ms_products_icon', 'ms_products.icon_id', '=', 'ms_products_icon.id')
            ->leftJoin('ms_categories', 'ms_products.category', '=', 'ms_categories.category_id')
            // ->orderBy('ms_categories.parent_id','asc')
            // ->orderBy('ms_products.category', 'desc')
            ->orderBy('ms_categories.ordering')
            ->orderBy('ms_products.ordering')
            ->where('ms_products.product_image', '!=', '')
            ->where('ms_products.status', '1')
            ->where('ms_products.display', '1')
            ->paginate(12);
        return view('web.products', [
            "products" => $products,
            "under_construction" => false
        ]);
    }

    public function all_json(Request $request)
    {
        $keywords = $request->get('q');

        $qb = Products::where('product_name', 'LIKE', '%' . $keywords . '%');
        $display = $request->get('display');
        if (isset($display)) {
            $qb->where('display', $display);
        }

        $products = $qb->orderBy('ordering')
        ->limit(20)
        ->get();
        return $products;
    }

    public function all_json_for_so(Request $request)
    {
        $keywords = $request->get('q') ?? '';

        $qb = Products::select('default_code', 'product_name_odoo', 'price')
        ->whereRaw("(product_name_odoo LIKE '%" . $keywords . "%' OR default_code LIKE '%" . $keywords . "%')")
        ->whereNotNull('default_code');
        $display = $request->get('display');
        if (isset($display)) {
            $qb->where('display', $display);
        }

        $products = $qb->orderBy('ordering')
        ->limit(20)
        ->get();
        return $products;
    }

    public function by_default_code($default_code)
    {
        $product = Products::select('default_code', 'product_name_odoo', 'price')
        ->where('default_code', $default_code)
        ->first();
        return $product;
    }

    public function category($slug, $slug_sub="")
    {

        // $category = Products_category::where("slug", "LIKE", $slug)->first();
        $category = Products_category::where("slug", $slug)->first();

        if (!isset($category->name)) {
            return redirect('products');
        } else {

            $sub_category = null;
            $prod_category = [];
            $product_category_banner = [];
            $product_category_feature = [];

            if ($slug_sub == "") {

                $prod_category = DB::table("ms_categories AS sub_mc")
                ->select("sub_mc.*")
                ->where("sub_mc.active", 1)
                ->where("sub_mc.parent_id", $category->category_id)
                ->whereRaw("IFNULL(sub_mc.name,'') <> ''")
                ->orderBy("sub_mc.ordering")
                ->get();

                $product_category_banner = DB::table('ms_categories_banner')->where('category_id', $category->category_id)->orderBy('ordering')->get();
                if (count($product_category_banner) == 0) {
                    $product_category_banner = DB::table('ms_categories_banner')
                    ->select("ms_categories_banner.*")
                    ->join('ms_categories', 'ms_categories.category_id', 'ms_categories_banner.category_id')
                    ->where('ms_categories.parent_id', $category->category_id)
                    ->orderBy(DB::raw('RAND()'))
                    ->limit(5)
                    ->get();
                }

            } else {

                $sub_category = DB::table("ms_categories AS mc")
                ->select("sub_mc.*")
                ->leftJoin("ms_categories AS sub_mc", "sub_mc.parent_id", "=", "mc.category_id")
                ->where("mc.category_id", $category->category_id)
                ->where("mc.active", 1)
                ->where("sub_mc.active", 1)
                ->whereRaw("(sub_mc.slug = '" . $slug_sub . "' OR '" . $slug_sub . "' = '')")
                ->whereRaw("IFNULL(sub_mc.name,'') <> ''")
                ->orderBy('sub_mc.ordering')
                ->first();

                if ($sub_category == null) {
                    return redirect("/products");
                }

                $sub_category_childs = DB::table('ms_categories')
                ->select("category_id")
                ->where('parent_id', $sub_category->category_id)
                ->whereRaw("IFNULL(name,'') <> ''")
                ->where('active', '1')->orderBy('ordering')->get();
    
                $prod_category = DB::table('ms_categories')
                ->where(count($sub_category_childs) == 0 ? 'category_id' : 'parent_id', $sub_category->category_id)
                ->where('active', '1')
                ->whereRaw("IFNULL(name,'') <> ''")
                ->orderBy('ordering')
                ->get();

                $product_category_banner = DB::table('ms_categories_banner')->where('category_id', $sub_category->category_id)->orderBy('ordering')->get();
                $product_category_feature = DB::table('ms_categories_feature')->where('categoryID', $sub_category->category_id)->where('status', '1')->get();

            }

            if (count($product_category_banner) == 0) {
                $product_category_banner = DB::table('ms_categories_banner')->where('category_id', $category->category_id)->orderBy('ordering')->get();
            }

            if (count($product_category_feature) == 0) {
                $product_category_feature = DB::table('ms_categories_feature')->where('categoryID', $category->category_id)->where('status', '1')->get();
            }

            return view('web.products-category', [
                "category" => $category,
                "sub_category" => $sub_category,
                "prod_category" => $prod_category,
                "product_category_feature" => $product_category_feature,
                "product_category_banner" => $product_category_banner
            ]);
        }
    }

    public function details($slug)
    {
        if ($slug != "") {
            $product = Products::where("slug", $slug)->first();
            $product_related = Products::where("category", $product->category)
            ->where("product_id", "!=", $product->product_id)
            ->where('status', '1')
            ->where('display', '1')
            ->whereNotNull('product_image')
            ->get();
            $product_category = Products_category::where("category_id", $product->category)->first();
            $product_spec = Products_spec::select('spec_group')->where("product_id", $product->product_id)->groupBy('spec_group')->orderBy('group_ordering')->get();
            $product_warranty = Products_warranty::where("product_id", $product->product_id)->whereNotNull('warranty_title')->get();
            $product_gallery = Products_gallery::where("product_id", $product->product_id)->get();
            $product_content = DB::table('ms_products_detail_content')->where('product_id', $product->product_id)->get();
            // $product_category_feature = DB::table('ms_categories_feature')->where('categoryID', $product->category)->where('status', '1')->get();
            $list_mcf_id = $product->features ? explode(",", $product->features) : [];
            $product_features = DB::table('ms_categories_feature')
            ->whereIn("id", $list_mcf_id)
            ->where("status", 1)->get();

            // $product_rating = DB::table('rating')->where('productID', $product->product_id)->get();
            $product_rating = DB::table('rating')
            ->select(
                'rating.*',
                'ms_members.name as memberName'
            )
            ->leftJoin('ms_members','ms_members.id','rating.memberID')
            ->where('rating.productID', $product->product_id)
            ->orderBy('rating.created_at','desc')
            ->get();

            $my_product_rating = DB::table('rating')->where('productID', $product->product_id)->where('memberID',Session::get('member_id'))->first();

            $product_files = DB::table('ms_products_files')
                ->where('product_id', $product->product_id)
                // ->where('mime_type', 'LIKE', 'image%')
                ->get();
            $product_files = json_decode(json_encode($product_files));

            $product_images = array_filter( $product_files, function($obj) {
                return str_contains($obj->mime_type, 'image');
            });

            $product_videos = array_filter( $product_files, function($obj) {
                return str_contains($obj->mime_type, 'video');
            });

            return view('web.products-details', [
                "product" => $product,
                "product_related" => $product_related,
                "product_warranty" => $product_warranty,
                "category" => $product_category,
                "spec" => $product_spec,
                "gallery" => $product_gallery,
                "product_content" => $product_content,
                "product_features" => $product_features,
                "product_images" => $product_images,
                "product_videos" => $product_videos,
                "product_rating" => $product_rating,
                "my_product_rating" => $my_product_rating
            ]);
        } else {
            return redirect('products');
        }
    }

    // public function reviewDetail($slug)
    // {
    //     $product = Products::where("slug", $slug)->first();

    //     if (empty($product)) {
    //         return redirect('products');
    //     } else {
    //         $product_rating = DB::table('rating')
    //         ->select(
    //             'rating.*',
    //             'ms_members.name as memberName'
    //         )
    //         ->leftJoin('ms_members','ms_members.id','rating.memberID')
    //         ->where('rating.productID', $product->product_id)
    //         ->orderBy('rating.created_at','desc')
    //         ->get();
    //         $my_product_rating = DB::table('rating')->where('productID', $product->product_id)->where('memberID',Session::get('member_id'))->first();
            
    //         return view('web.products-review-details', [
    //             "product" => $product,
    //             "product_rating" => $product_rating,
    //             "my_product_rating" => $my_product_rating
    //         ]);
    //     }
    // }


    public function warranty($category_id)
    {
        $data = DB::table('ms_warranty')->where('category_id', $category_id)->get();

        if (!empty($data)) {
            $return = [
                'status' => true,
                'data' => $data
            ];
        } else {
            $return = [
                'status' => false
            ];
        }
        return json_encode($return);
    }

    public function search(Request $request)
    {
        $keywords = $request->get('keywords');
        $products = Products::where('product_name', 'LIKE', '%' . $keywords . '%')
        ->where('status', '1')
        ->where('display', '1')
        ->whereNotNull('product_image')
        ->orderBy('ordering')
        ->paginate(12);
        // dd($products->withPath(""));
        return view('web.products-search', [
            "keywords" => $keywords,
            "products" => $products
        ]);
    }

    public function reviewDetail($slug)
    {

        $product = Products::where("slug", $slug)->first();

        if (empty($product)) {
            return redirect('products');
        } else {
            $product_rating = DB::table('rating')
            ->select(
                'rating.*',
                'ms_members.name as memberName'
            )
            ->leftJoin('ms_members','ms_members.id','rating.memberID')
            ->where('rating.productID', $product->product_id)
            ->orderBy('rating.created_at','desc')
            ->get();
            $my_product_rating = DB::table('rating')->where('productID', $product->product_id)->where('memberID',Session::get('member_id'))->first();
            
            return view('web.products-review-details', [
                "product" => $product,
                "product_rating" => $product_rating,
                "my_product_rating" => $my_product_rating
            ]);
        }
    }

    public function reviewSubmit()
    {
        $check = DB::table('rating')->where('productID', Input::get('productID'))->where('memberID', Session::get('member_id'))->first();
        
        if (empty($check)) {
            $data = [
                'productID' => Input::get('productID'),
                'star' => Input::get('star'),
                'review' => Input::get('review'),
                'memberID' => Session::get('member_id'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            DB::table('rating')->insert($data);
        }else{
            $data = [
                'star' => Input::get('star'),
                'review' => Input::get('review'),
                'memberID' => Session::get('member_id'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            DB::table('rating')->where('productID', Input::get('productID'))->where('memberID', Session::get('member_id'))->update($data);
        }
    }
}
