<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use App\User;
use App\Gallery;
use App\Gallery_images;
use App\Http\Controllers\Controller;
use Hash;
use Auth;


class GalleryController extends Controller{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function index(){
        $data['gallery'] = Gallery::orderBy('created_at', 'desc')->get();

        return view('backend.gallery.list', $data);
    }

    public function add(){

        return view('backend.gallery.add');
    }

    public function add_process(Request $request){
        $rules = array(
            'title' => 'required',
            'slug' => 'required',
            'image' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $gallery['title'] = $request->input('title');
            $gallery['slug'] = $request->input('slug');
            $gallery['image'] = $request->input('image');
            $gallery['created_at'] = date('Y-m-d H:i:s');
            $gallery['created_by'] = Auth::user()->id;

            $gallery_id = Gallery::insertGetId($gallery);

            foreach ($_POST['gallery_image'] as $gal) {
                $gallery_image['gallery_id'] = $gallery_id;
                $gallery_image['images'] = $gal;

                $insert_gallery_image = Gallery_images::insert($gallery_image);
            }

            return redirect('artmin/gallery')->with('success', 'Add New Gallery success.');
        }
    }

    public function edit($id)
    {
        $data['gallery'] = Gallery::where('id', $id)->first();
        $data['gallery_images'] = Gallery_images::where('gallery_id', $id)->get();

        return view('backend.gallery.edit', $data);
    }

    public function edit_process($id, Request $request)
    {
        $rules = array(
            'title' => 'required',
            'slug' => 'required',
            'image' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $gallery['title'] = $request->input('title');
            $gallery['slug'] = $request->input('slug');
            $gallery['image'] = $request->input('image');
            $gallery['updated_at'] = date('Y-m-d H:i:s');
            $gallery['updated_by'] = Auth::user()->id;

            $update_gallery = Gallery::where('id', $id)->update($gallery);

            $delete_images = Gallery_images::where("gallery_id", $id)->delete();

            foreach ($_POST['gallery_image'] as $gal) {
                $gallery_image['gallery_id'] = $id;
                $gallery_image['images'] = $gal;

                $insert_gallery_image = Gallery_images::insert($gallery_image);
            }

            return redirect('artmin/gallery')->with('success', 'Update Gallery success.');
        }
    }

    public function delete($id)
    {
        Gallery::where('id', $id)->delete();
        Gallery_images::where('gallery_id', $id)->delete();
        return redirect('artmin/gallery')->with('success', 'Delete Gallery success.');
    }
}
