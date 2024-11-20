<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Products;
use App\Products_category;
use App\Products_spec;
use DB;
use Auth;
use Excel;
use App\Exports\Excel\ProductExport;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $data['product'] = Products::select(
            'ms_products.*',
            'ms_categories.name as category_name',
            'ms_products_warranty.warranty_title',
            'ms_products_warranty.warranty_year'
        )
            ->leftJoin('ms_categories', 'ms_categories.category_id', '=', 'ms_products.category')
            ->leftJoin('ms_products_warranty', 'ms_products_warranty.product_id', '=', 'ms_products.product_id')
            ->groupBy('ms_products.product_id')
            ->get();

        return view('backend.product.list', $data);
    }


    public function add_product()
    {
        $data['statusAction'] = 'insert';
        $data['category'] = Products_category::get();
        $data['all_product'] = Products::get();
        $data['icon'] = DB::table('ms_products_icon')->get();
        $data['product'] = json_decode(json_encode(array( 'product_image' => '' )));

        return view('backend.product.form_product', $data);
    }

    public function add_product_process(Request $request)
    {
        $latestOrder = DB::table('ms_products')->orderBy('ordering', 'desc')->first()->ordering;


        $foto = null;
        if (!empty($request->file('product_image'))) {
            $file = $request->file('product_image');
            $tujuan_upload = 'uploads/products';
            $foto = strtoupper(str_replace(' ', '', $request->input('product_code'))) . '.' . $file->getClientOriginalExtension();
            $file->move($tujuan_upload, $foto);
            $ms_product = [
                'product_id' => null,
                'category' => $request->input('category_model'),
                'product_code' => $request->input('product_code'),
                'product_name' => $request->input('product_name'),
                'default_code' => $request->input('default_code'),
                'price' => $request->input('price'),
                'base_point' => $request->input('base_point'),
                'product_name_odoo' => $request->input('product_name'),
                'slug' => $request->input('product_slug'),
                'icon_id' => $request->input('icon_id'),
                'product_desc' => $request->input('product_desc'),
                'product_image' => url($tujuan_upload . '/' . $foto),
                'meta_desc' => null,
                'meta_tags' => null,
                'ordering' => $latestOrder,
                'status' => 0,
                'featured' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ];
        } else {
            $product_image = null;
            if (!empty($request->input('product_id'))) {
                $product_data = DB::table('ms_products')->where('product_id', $request->input('product_id'))->first();
                if (!empty($product_data)) {
                    $product_image = $product_data->product_image;
                }
            }

            $ms_product = [
                'product_id' => null,
                'category' => $request->input('category_model'),
                'product_code' => $request->input('product_code'),
                'product_name' => $request->input('product_name'),
                'price' => $request->input('price'),
                'base_point' => $request->input('base_point'),
                'default_code' => $request->input('default_code'),
                'product_name_odoo' => $request->input('product_name'),
                'slug' => $request->input('product_slug'),
                'icon_id' => $request->input('icon_id'),
                'product_desc' => $request->input('product_desc'),
                'product_image' => $product_image,
                'meta_desc' => null,
                'meta_tags' => null,
                'ordering' => $latestOrder,
                'status' => 0,
                'featured' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ];
        }



        Products::insert($ms_product);

        $product_id = Products::orderBy('product_id', 'desc')->first()->product_id;

        $ms_product_category = [
            'id' => null,
            'product_id' => $product_id,
            'category_id' => $request->input('category_model'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::user()->id,
        ];

        DB::table('ms_products_category')->insert($ms_product_category);

        if (!empty($request->input('warranty_title'))) {
            foreach ($request->input('warranty_title') as $key => $value) {
                $ms_products_warranty = [
                    'id' => null,
                    'product_id' => $product_id,
                    'warranty_title' => $request->input('warranty_title')[$key],
                    'warranty_value' => $request->input('warranty_value')[$key] . ' Year',
                    'warranty_year' => $request->input('warranty_value')[$key],
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ];

                DB::table('ms_products_warranty')->insert($ms_products_warranty);
            }
        }

        if (!empty($request->input('general_spec_title'))) {
            foreach ($request->input('general_spec_title') as $key => $value) {
                $ms_product_spec = [
                    'spec_id' => null,
                    'product_id' => $product_id,
                    'spec_group' => 'General Spec',
                    'group_ordering' => $key + 1,
                    'spec_title' => $request->input('general_spec_title')[$key],
                    'spec_value' => $request->input('general_spec_value')[$key],
                    'spec_ordering' => $key + 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ];

                DB::table('ms_products_spec')->insert($ms_product_spec);
            }
        }

        if (!empty($request->input('special_spec_title'))) {
            foreach ($request->input('special_spec_title') as $key => $value) {
                $ms_product_spec = [
                    'spec_id' => null,
                    'product_id' => $product_id,
                    'spec_group' => 'Special Spec',
                    'group_ordering' => $key + 1,
                    'spec_title' => $request->input('special_spec_title')[$key],
                    'spec_value' => $request->input('special_spec_value')[$key],
                    'spec_ordering' => $key + 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ];

                DB::table('ms_products_spec')->insert($ms_product_spec);
            }
        }

        if (!empty($request->file('banner'))) {
            foreach ($request->file('banner') as $key => $value) {

                $file = $request->file('banner')[$key];
                $tujuan_upload = 'uploads/products/banner';
                $foto = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();
                $file->move($tujuan_upload, $foto);

                $ms_products_detail_content = [
                    'product_id' => $product_id,
                    'banner' => $foto,
                    'content_title' => $request->input('content_title')[$key],
                    'content_description' => $request->input('content_description')[$key],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id
                ];
                DB::table('ms_products_detail_content')->insert($ms_products_detail_content);
            }
        }

        // return redirect('artmin/product/list');
        return redirect('artmin/product/edit-product/' . $product_id);
    }

    public function edit_product($product_id)
    {
        $product = Products::where('product_id', $product_id)->first();
        $data['product'] = $product;
        $data['statusAction'] = 'update';
        $data['specification_general'] = DB::table('ms_products_spec')->where('product_id', $product_id)->where('spec_group', 'General Spec')->get();
        $data['specification_special'] = DB::table('ms_products_spec')->where('product_id', $product_id)->where('spec_group', 'Special Spec')->get();
        $data['detail_content'] = DB::table('ms_products_detail_content')->where('product_id', $product_id)->get();
        $data['category'] = Products_category::get();
        $data['all_product'] = Products::get();
        $data['icon'] = DB::table('ms_products_icon')->get();
        $data['list_selected_feature'] = $product->features ? explode(",", $product->features) : [];

        $data['product_category_feature'] = [];
        $category_feature = DB::table('ms_categories AS mc')
        ->select(DB::raw('IFNULL(IFNULL(topparent.category_id, parent.category_id), mc.category_id) AS cat_id_feature'))
        ->leftJoin('ms_categories AS parent', function ($join) {
            $join->on('parent.category_id', '=', 'mc.parent_id');
            $join->where(DB::raw('IFNULL(parent.parent_id, 0)'), '<>', 0);
        })
        ->leftJoin('ms_categories AS topparent', function ($join) {
            $join->on('topparent.category_id', '=', 'parent.parent_id');
            $join->where(DB::raw('IFNULL(topparent.parent_id, 0)'), '<>', 0);
        })
        ->whereRaw('IFNULL(mc.parent_id, 0) <> 0')
        ->where('mc.category_id', $product->category)
        ->first();

        if (isset($category_feature)) {
            $data['product_category_feature'] = DB::table('ms_categories_feature')->where('categoryID', $category_feature->cat_id_feature)->where('status', '1')->get();
        }

        return view('backend.product.form_product', $data);
    }

    public function edit_product_process(Request $request)
    {

        $rules = array(
            'category_model' => 'required',
            'product_code' => 'required',
            'product_name' => 'required',
            // 'product_slug' => 'required',
            // 'product_desc' => 'required',
            // 'ordering' => 'required',
        );
        $rules_message = array(
            'category_model.required' => 'Kategori Produk Harus dipilih',
            'product_code.required' => 'Kode produk harus diisi',
            'product_name.required' => 'Nama produk harus diisi',
            // 'product_slug.required' => 'Slug produk harus diisi',
            // 'product_desc.required' => 'Deskripsi produk harus diisi',
            // 'ordering.required' => 'Urutan Order harus diisi',
        );
        $validator = Validator::make($request->all(), $rules, $rules_message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $product_id = $request->input('product_id');

            $features_selected = "";
            if (!empty($request->input('features'))) {
                foreach ($request->input('features') as $key => $value) {
                    if ($value) {
                        $features_selected = $features_selected . ($features_selected === "" ? "" : ",") . (string)$key;
                    }
                }
            }

            $foto = null;
            if (!empty($request->file('product_image'))) {
                $file = $request->file('product_image');
                $tujuan_upload = 'uploads/products';
                $foto = strtoupper(str_replace(' ', '', $request->input('product_code') . '_' . date('ymdHis') )) . '.' . $file->getClientOriginalExtension();
                $file->move($tujuan_upload, $foto);

                $product = Products::where('product_id', $product_id)->select('product_image')->first();
                if($product->product_image && file_exists($tujuan_upload . '/' . basename($product->product_image))) {
                    unlink($tujuan_upload . '/' . basename($product->product_image));
                }

                $ms_product = [
                    'category' => $request->input('category_model'),
                    'product_code' => $request->input('product_code'),
                    'product_name' => $request->input('product_name'),
                    'default_code' => $request->input('default_code'),
                    'price' => $request->input('price'),
                    'base_point' => $request->input('base_point'),
                    'product_name_odoo' => $request->input('product_name'),
                    'slug' => $request->input('product_slug'),
                    'icon_id' => $request->input('icon_id'),
                    'product_desc' => $request->input('product_desc'),
                    'product_image' => url($tujuan_upload . '/' . $foto),
                    'features' => $features_selected,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ];
            } else {
                $ms_product = [
                    'category' => $request->input('category_model'),
                    'product_code' => $request->input('product_code'),
                    'product_name' => $request->input('product_name'),
                    'default_code' => $request->input('default_code'),
                    'price' => $request->input('price'),
                    'base_point' => $request->input('base_point'),
                    'product_name_odoo' => $request->input('product_name'),
                    'slug' => $request->input('product_slug'),
                    'icon_id' => $request->input('icon_id'),
                    'product_desc' => $request->input('product_desc'),
                    'features' => $features_selected,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ];
            }

            Products::where('product_id', $product_id)->update($ms_product);

            if (!empty($request->input('warranty_title'))) {
                DB::table('ms_products_warranty')->where('product_id', $product_id)->delete();
                foreach ($request->input('warranty_title') as $key => $value) {
                    $ms_products_warranty = [
                        'id' => null,
                        'product_id' => $product_id,
                        'warranty_title' => $request->input('warranty_title')[$key],
                        'warranty_value' => $request->input('warranty_value')[$key] . ' Year',
                        'warranty_year' => $request->input('warranty_value')[$key],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->id,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_by' => Auth::user()->id
                    ];

                    DB::table('ms_products_warranty')->insert($ms_products_warranty);
                }
            }

            if (!empty($request->input('general_spec_title'))) {
                DB::table('ms_products_spec')->where('product_id', $product_id)->where('spec_group', 'General Spec')->delete();
                foreach ($request->input('general_spec_title') as $key => $value) {
                    $ms_product_spec = [
                        'spec_id' => null,
                        'product_id' => $product_id,
                        'spec_group' => 'General Spec',
                        'group_ordering' => $key + 1,
                        'spec_title' => $request->input('general_spec_title')[$key],
                        'spec_value' => $request->input('general_spec_value')[$key],
                        'spec_ordering' => $key + 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->id,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_by' => Auth::user()->id
                    ];

                    DB::table('ms_products_spec')->insert($ms_product_spec);
                }
            }

            if (!empty($request->input('special_spec_title'))) {
                DB::table('ms_products_spec')->where('product_id', $product_id)->where('spec_group', 'Special Spec')->delete();
                foreach ($request->input('special_spec_title') as $key => $value) {
                    $ms_product_spec = [
                        'spec_id' => null,
                        'product_id' => $product_id,
                        'spec_group' => 'Special Spec',
                        'group_ordering' => $key + 1,
                        'spec_title' => $request->input('special_spec_title')[$key],
                        'spec_value' => $request->input('special_spec_value')[$key],
                        'spec_ordering' => $key + 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->id,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_by' => Auth::user()->id
                    ];

                    DB::table('ms_products_spec')->insert($ms_product_spec);
                }
            }

            if (!empty($request->input('content_title'))) {
                DB::table('ms_products_detail_content')->where('product_id', $product_id)->delete();
                foreach ($request->input('content_title') as $key => $value) {

                    $foto = null;
                    if (!empty($request->file('banner')[$key])) {
                        $file = $request->file('banner')[$key];
                        $tujuan_upload = 'uploads/products/banner';
                        $foto = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();
                        $file->move($tujuan_upload, $foto);
                    } elseif (!empty($request->input('current_banner')[$key])) {
                        $foto = $request->input('current_banner')[$key];
                    }

                    $foto_mobile = null;
                    if (!empty($request->file('banner_mobile')[$key])) {
                        $file = $request->file('banner_mobile')[$key];
                        $tujuan_upload = 'uploads/products/banner';
                        $foto_mobile = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();
                        $file->move($tujuan_upload, $foto_mobile);
                    } elseif (!empty($request->input('current_banner_mobile')[$key])) {
                        $foto_mobile = $request->input('current_banner_mobile')[$key];
                    }


                    $ms_products_detail_content = [
                        'product_id' => $product_id,
                        'banner' => $foto,
                        'banner_mobile' => $foto_mobile,
                        'content_title' => $request->input('content_title')[$key],
                        'content_description' => $request->input('content_description')[$key],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->id
                    ];
                    DB::table('ms_products_detail_content')->insert($ms_products_detail_content);
                }
            }

            return redirect('artmin/product/edit-product/' . $product_id);
        }
    }

    public function delete_product_image_process(Request $request)
    {
        $product_id = $request->input('product_id');
        $product = Products::where('product_id', $product_id)->select('product_image')->first();

        $tujuan_upload = 'uploads/products';
        if(file_exists($tujuan_upload . '/' . basename($product->product_image))) {
            unlink($tujuan_upload . '/' . basename($product->product_image));
        }
        Products::where('product_id', $product_id)->update([
            'product_image' => '',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::user()->id
        ]);
        return json_encode([
            'status' => 200,
            'success' => true,
            'message' => 'Image berhasil dihapus!'
        ]);
    }

    public function delete_product_process(Request $request)
    {
        DB::table('ms_products')->where('product_id', $request->input('product_id'))->delete();
        DB::table('ms_products_category')->where('product_id', $request->input('product_id'))->delete();
        DB::table('ms_products_gallery')->where('product_id', $request->input('product_id'))->delete();
        DB::table('ms_products_serial')->where('product_id', $request->input('product_id'))->delete();
        DB::table('ms_products_spec')->where('product_id', $request->input('product_id'))->delete();
        DB::table('ms_products_warranty')->where('product_id', $request->input('product_id'))->delete();
    }

    public function status_product_process(Request $request)
    {
        $status = DB::table('ms_products')->where('product_id', $request->input('product_id'))->first();
        DB::table('ms_products')->where('product_id', $request->input('product_id'))->update([
            'status' => ($status->status == '1' ? '0' : '1')
        ]);
    }

    public function display_product_process(Request $request)
    {
        $display = DB::table('ms_products')->where('product_id', $request->input('product_id'))->first("display");
        DB::table('ms_products')->where('product_id', $request->input('product_id'))->update([
            'display' => ($display->display == '1' ? '0' : '1')
        ]);
    }

    public function refspec($product_id)
    {
        $refspec = Products_spec::where('product_id', $product_id)->get();
        $refwar = DB::table('ms_products_warranty')->where('product_id', $product_id)->get();

        $return = [
            'status' => true,
            'dataSpec' => $refspec,
            'dataWar' => $refwar
        ];

        return json_encode($return);
    }

    public function warranty()
    {
        $data['warranty'] = DB::table('ms_warranty')->select('ms_warranty.*', 'ms_categories.name as category_name')->join('ms_categories', 'ms_categories.category_id', '=', 'ms_warranty.category_id')->get();

        return view('backend/product/warranty/list', $data);
    }

    public function exportData()
    {
        $export = new ProductExport;

        return Excel::download($export, 'artugo_master_product_' . date('Y-m-d') . '.xlsx');
    }

    public function files($product_id)
    {
        $product_files = DB::table('ms_products_files')->where('product_id', $product_id)->get();
        $return = [
            'status' => true,
            'data' => $product_files
        ];
        return $return;
    }

    public function postProductFile(Request $request, $product_id)
    {
        $file_id = $request->input('id');
        $description = $request->input('description');

        $product = DB::table('ms_products')->where('product_id', $product_id)->first('default_code');
        if (!$product) {
            return [
                'success' => false,
                'message' => 'Master Product tidak ditemukan!'
            ];
        }
        
        $files = [];
        $mime_type = '';

        foreach (['product_file', 'product_file_thumbnail'] as $key) {

            if (!empty($request->file($key))) {

                $file = $request->file($key);
                $mime_type = $file->getClientMimeType();
                $tujuan_upload = 'uploads/products/' . $product->default_code;

                if(!Storage::exists($tujuan_upload)){
                    Storage::makeDirectory($tujuan_upload);
                }

                $fileext = $file->getClientOriginalExtension();
                $filename = date("YmdHis.") . $fileext;
                $files[$key] = [
                    'path_file' => $tujuan_upload . '/' . $filename,
                    'filename' => $filename,
                    'mime_type' => $mime_type
                ];
                $file->move($tujuan_upload, $filename);

                // return [
                //     'success' => false,
                //     'path_file' => $files[$key]['path_file'],
                //     'fileext' => $fileext,
                //     'filename' => $files[$key]['filename'],
                //     'mime_type' => $files[$key]['mime_type']
                // ];
            }
        }

        if (!empty($file_id)) {
            DB::table('ms_products_files')->where('id', $file_id)->update(array(
                'description' => $description
            ));
        } else {
            $file_id = DB::table('ms_products_files')->insertGetId(array(
                'product_id' => $product_id,
                'description' => $description,
                'path_file' => $files['product_file']['path_file'] ?? '',
                'mime_type' => $files['product_file']['mime_type'] ?? '',
                'file_url' => $files['product_file']['path_file'] ?? '',
                'path_file_thumbnail' => $files['product_file_thumbnail']['path_file'] ?? '',
                'file_url_thumbnail' => $files['product_file_thumbnail']['path_file'] ?? ''
            ));
        }

        $return = [
            'success' => true,
            'data' => DB::table('ms_products_files')->where('id', $file_id)->first()
        ];
        return $return;
    }

    public function deleteFile($file_id)
    {
        $file = DB::table('ms_products_files')->select('path_file', 'path_file_thumbnail')->where('id', $file_id)->first();
        if (!$file) {
            return [
                'status' => false,
                'message' => 'Tidak ada data!'
            ];
        }
        
        foreach (['path_file', 'path_file_thumbnail'] as $key) {
            if ($file->$key && file_exists('./' . $file->$key)) {
                unlink('./' . $file->$key);
            }
        }

        DB::table('ms_products_files')->where('id', $file_id)->delete();
        return [ 'success' => true ];
    }
}
