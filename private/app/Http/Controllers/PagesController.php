<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use DB;
use Session;
use App\Data;
use App\Pages;
use App\Products;
use App\Products_category;
use App\Store_location;

class PagesController extends Controller
{
    public function index()
    {

        return view('web.warranty');
    }

    public function about()
    {

        $data['about'] = Data::where('code', 'about-content')->first();
        $data['tagline'] = Data::where('code', 'about-tagline')->first();
        $data['vision'] = Data::where('code', 'about-vision')->first();
        $data['mission'] = Data::where('code', 'about-mission')->first();

        return view('web.about', $data);
    }

    public function contact()
    {

        return view('web.contact');
    }

    public function view($slug)
    {
        $data['content'] = Pages::where('slug', $slug)->first();

        if (!isset($data['content']->title)) {
            return redirect('/');
        } else {
            return view('web.pages-view', $data);
        }
    }

    public function brochure()
    {
        $data['parent_brochure'] = Products_category::where('ordering_parent', '!=', '0')->where('active', '1')->orderBy('ordering_parent')->get();
        $data['brochure'] = Products_category::where('brochure', '!=', NULL)->orderBy('ordering')->get();
        return view('web.pages-brochure', $data);
    }

    public function store_location($region_name = null)
    {
        $data['store_location_regional'] = DB::table('store_location_regional')->get();


        if (!empty($region_name)) {
            $regName = str_replace('-', ' ', $region_name);

            if ($regName == '-') {
                return redirect('store-location');
            } else {
                $region = DB::table('store_location_regional')->orWhere('regional_name', $regName)->first();
                if (!empty($region)) {
                    $data['selectedRegion'] = $region->id;
                    $data['store'] = DB::table('store_location')
                        ->where('regional_id', $region->id)
                        ->whereNotNull('latitude')
                        ->whereNotNull('longitude')
                        ->get();
                } else {
                    return redirect('store-location');
                }
            }
        } else {
            $data['store'] = DB::table('store_location')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();
        }

        return view('web.pages-store-location', $data);
    }

    public function load_store_location(Request $request)
    {
        if ($request->has('q')) {
            $cari = $request->q;
            $results = Store_location::where('nama_toko', 'LIKE', "%$cari%")->orWhere('alamat_toko', 'LIKE', "%$cari%")->orderBy('nama_toko', 'ASC')->get();
            echo $results;
        }
    }

    public function load_store_location_filter(Request $request)
    {
        if ($request->has('q')) {
            $cari = $request->q;
            $results = Store_location::where('nama_toko', 'LIKE', "%$cari%")->orWhere('alamat_toko', 'LIKE', "%$cari%")->orderBy('nama_toko', 'ASC')->get();
            echo $results;
        }
    }

    public function tradein()
    {
        return view('web.tradein');
    }

    public function career()
    {
        return view('web.career');
    }
}
