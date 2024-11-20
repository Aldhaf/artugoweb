<?php

namespace App\Http\Controllers;

use DB;
use App\Posts;
use App\Posts_category;

class ArticleController extends Controller
{
    public function index(){
        $data['article'] = Posts::orderBy('order_features')->orderBy('id')->where('status', 1)->where('category', '!=' , 5)->where('reader', 'customer')->paginate(12);
        return view('web.article-list', $data);
    }

    public function category($slug){

        $category = Posts_category::where('slug', $slug)->first();
        if(!isset($category->id)){
            return redirect('article');
        }
        else{
            $data['article'] = Posts::orderBy('order_features')->orderBy('id')->where('category', $category->id)->where('category', '!=' , 5)->where('status', 1)->where('reader', 'customer')->paginate(12);
            $data['category'] = $category;

            return view('web.article-list', $data);
        }
    }

    public function read($slug){

        $data['article'] = Posts::where('slug', $slug)->where('reader', 'customer')->first();
        if(!isset($data['article']->id)){
            return redirect('article');
        }
        else {
            return view('web.article-read', $data);
        }
    }

}
