<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Input;
use DB;
use Auth;

class CategoryController extends Controller
{
    public function listCategory()
    {
        $data['categories'] = DB::table('ms_categories')->get();
        return view('backend.categories.listCategory', $data);
    }

    public function indexJSON(Request $request)
    {
        $parent_id = $request->query('parent_id') ?? '0';

        $qb = DB::table('ms_categories')
            ->select('category_id', 'parent_id', 'name')
            ->where('active', 1)
            ->where(DB::raw('IFNULL(name, "")'), '!=', '')
            ->where(DB::raw('IFNULL(parent_id, 0)'), $parent_id);

        $keywords = $request->get('q') ?? '';
        if ($keywords) {
            $qb->whereRaw("name LIKE '%" . $keywords . "%'");
        }

        return $qb->get();
    }

    public function listCategoryFeatures($id)
    {
        $listFeature = DB::table('ms_categories_feature')->where('categoryID', $id)->get();

        return $listFeature;
    }

    public function changeStatus(Request $request, $category_id)
    {
        $data['active'] = $request->input('active');
        DB::table('ms_categories')->where('category_id', $category_id)->update($data);
        // return redirect('artmin/product/categories');
    }

    public function changeInstallation(Request $request, $category_id)
    {
        $data['need_installation'] = $request->input('need_installation');
        DB::table('ms_categories')->where('category_id', $category_id)->update($data);
        // return redirect('artmin/product/categories');
    }

    public function saveBrochure(Request $request)
    {

        $file = Input::file('brochure_file');
        if (!empty($file)) {
            $upload_loc_ktp = 'uploads/brochure/';
            $image = Uuid::uuid4()->toString() .  "." . $file->getClientOriginalExtension();
            $file->move($upload_loc_ktp, $image);

            $data['brochure'] = $image;
            DB::table('ms_categories')->where('category_id', $request->input('categoryID'))->update($data);
        } else {
            DB::table('ms_categories')->where('category_id', $request->input('categoryID'))->update(['brochure' => NULL]);
        }
        return redirect('artmin/product/categories');

    }

    public function saveConfiguration(Request $request)
    {
        $dataOriginal = DB::table('ms_categories')->where('category_id', $request->input('categoryID'))->first();
        $data['product_highlight_image'] = $dataOriginal->product_highlight_image;
        $data['product_highlight_image_mobile'] = $dataOriginal->product_highlight_image_mobile;

        $file = Input::file('product_highlight_image');
        if (!empty($file)) {
            $upload_loc_ktp = 'assets/uploads/product_highlight/';
            $image = Uuid::uuid4()->toString() .  "." . $file->getClientOriginalExtension();
            $file->move($upload_loc_ktp, $image);

            $data['product_highlight_image'] = $image;
        }
        
        $file = Input::file('product_highlight_image_mobile');
        if (!empty($file)) {
            $upload_loc_ktp = 'assets/uploads/product_highlight/';
            $image = Uuid::uuid4()->toString() .  "." . $file->getClientOriginalExtension();
            $file->move($upload_loc_ktp, $image);

            $data['product_highlight_image_mobile'] = $image;
        }

        $data['product_highlight_text_title'] = $request->input('product_highlight_text_title');
        $data['product_highlight_text_subtitle'] = $request->input('product_highlight_text_subtitle');
        $data['product_highlight_text_color'] = $request->input('product_highlight_text_color');
        $data['product_highlight_text_align'] = $request->input('product_highlight_text_align');
        DB::table('ms_categories')->where('category_id', $request->input('categoryID'))->update($data);


        return redirect('artmin/product/categories');
    }

    public function saveCategoryFeatures(Request $request)
    {
        DB::table('ms_categories_feature')->where('categoryID', $request->input('categoryID_feature'))->delete();
        if (!empty($request->input('idx_feature'))) {
            foreach ($request->input('idx_feature') as $key => $value) {
                $url_img = '';
                if (!empty($request->file('img_feature')[$key])) {
                    $file = $request->file('img_feature')[$key];
                    $upload_loc = 'assets/uploads/category_feature/';
                    $image = Uuid::uuid4()->toString() .  "." . $file->getClientOriginalExtension();
                    $file->move($upload_loc, $image);
                    $url_img = $upload_loc . $image;
                } else {
                    $url_img = $request->input('current_feature')[$key];
                }
                DB::table('ms_categories_feature')->insert([
                    'categoryID' => $request->input('categoryID_feature'),
                    'img' => $url_img,
                    'status' => 1,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        return redirect('artmin/product/categories');
    }

    public function listCategoryBanner($id)
    {
        $listBanner = DB::table('ms_categories_banner')->where('category_id', $id)->orderBy('ordering', 'asc')->get();
        return $listBanner;
    }

    public function saveCategoryBanners(Request $request)
    {
        if (!empty($request->input('idx_banner'))) {

            DB::table('ms_categories_banner')->where('category_id', $request->input('categoryID_banner'))->delete();

            foreach ($request->input('idx_banner') as $key => $value) {

                $url_img = '';
                if (!empty($request->file('img_banner')[$key])) {
                    $file = $request->file('img_banner')[$key];
                    $upload_loc = 'assets/uploads/category_banner/';
                    $image = Uuid::uuid4()->toString() .  "." . $file->getClientOriginalExtension();
                    $file->move($upload_loc, $image);
                    $url_img = $upload_loc . $image;
                } else {
                    $url_img = $request->input('current_banner')[$key];
                }

                DB::table('ms_categories_banner')->insert([
                    'category_id' => $request->input('categoryID_banner'),
                    'title' => $request->input('content_title')[$key],
                    'description' => $request->input('content_description')[$key],
                    'url' => $request->input('content_url')[$key],
                    'image' => $url_img,
                    'ordering' => $request->input('ordering')[$key] != "" ? $request->input('ordering')[$key] : $key,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        return redirect('artmin/product/categories');
    }
}
