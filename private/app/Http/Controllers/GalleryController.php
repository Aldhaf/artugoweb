<?php

namespace App\Http\Controllers;

use DB;
use App\Gallery;
use App\Gallery_images;

class GalleryController extends Controller
{
    public function index(){
        $data['gallery'] = Gallery::orderBy('id', 'desc')->paginate(12);
        return view('web.gallery-list', $data);
    }

    public function view($slug){

        $gallery = Gallery::where('slug', $slug)->first();
        $data['gallery'] = $gallery;
        if(!isset($gallery->id)){
            return redirect('gallery');
        }
        else {
            $data['gallery_images'] = Gallery_images::where('gallery_id', $gallery->id)->get();
            return view('web.gallery-view', $data);
        }
    }

}
