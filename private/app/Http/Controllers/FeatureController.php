<?php

namespace App\Http\Controllers;

use DB;
use App\Posts;
use App\Posts_category;

class FeatureController extends Controller
{
    public function index(){
        return view('web.features');
    }

    public function category($slug){

        $category = Posts_category::where('slug', $slug)->first();
        if(!isset($category->id)){
            return redirect('features');
        }
        else{
            $data['features'] = Posts::orderBy('order_features', 'asc')->where('category', $category->id)->where('status', 1)->paginate(12);
            $data['category'] = $category;

            return view('web.features-list', $data);
        }
    }

    public function read($slug){

        $data['features'] = Posts::where('slug', $slug)->first();
        if(!isset($data['features']->id)){
            return redirect('features');
        }
        else {
            return view('web.features-read', $data);
        }
    }

}
