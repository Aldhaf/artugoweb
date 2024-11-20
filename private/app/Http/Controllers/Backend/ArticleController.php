<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use App\User;
use App\Products;
use App\Http\Controllers\Controller;
use Hash;
use Auth;
use App\Posts;
use App\Posts_category;


class ArticleController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function index_xxx(Request $request)
    {
        $reader = $request->segment(2) == 'article' ? 'customer' : 'employee';
        if (in_array(Auth::user()->roles, ['1','2'])) {
            $data['article'] = Posts::where('status', '!=', -1)->where('reader', $reader)->orderBy('created_at', 'desc')->get();
            return view('backend.article.list', $data);
        } else {
            $data['article'] = Posts::orderBy('order_features')->orderBy('id')->where('status', 1)->where('reader', 'employee')->paginate(12);
            return view('backend.article.APC.article-list', $data);
        }
    }

    public function index(Request $request)
    {
        $keywords = request()->has('keywords') ? request()->keywords : '';
        $reader = $request->segment(2) == 'article' ? 'customer' : 'employee';
        if (in_array(Auth::user()->roles, ['1','2'])) {
            $query = Posts::where('reader', $reader);
            if ($keywords != '') {
                $query->where(function($qb) use ($keywords) {
                    $qb->orWhere('title', 'LIKE', "%$keywords%")
                        ->orWhere('meta_tags', 'LIKE', "%$keywords%")
                        ->orWhere('meta_desc', 'LIKE', "%$keywords%")
                        ->orWhere('content', 'LIKE', "%$keywords%");
                });
            }

            $data['article'] = $query->orderBy('created_at', 'desc')->get();
            return $request->ajax() ? response()->json($data['article']) : view('backend.article.list', $data);
        } else {
            $data['article'] = 
            $query = Posts::where('status', 1)->where('reader', 'employee');
            if ($keywords != '') {
                $query->where(function($qb) use ($keywords) {
                    $qb->orWhere('title', 'LIKE', "%$keywords%")
                        ->orWhere('meta_tags', 'LIKE', "%$keywords%")
                        ->orWhere('meta_desc', 'LIKE', "%$keywords%")
                        ->orWhere('content', 'LIKE', "%$keywords%");
                });
            }

            $data['article'] = $query->orderBy('order_features')
                ->orderBy('id')
                ->paginate(12);
            return $request->ajax() ? response()->json($data['article']) : view('backend.article.APC.article-list', $data);
        }
    }
    
    public function read($slug, Request $request)
    {
        $reader = $request->segment(2) == 'article' ? 'customer' : 'employee';
        $data['article'] = Posts::where('slug', $slug)->where('reader', $reader)->first();
        if(!isset($data['article']->id)){
            return redirect('article');
        } else {
            return view('backend.article.APC.article-read', $data);
        }
    }

    public function add_post()
    {
        $data['category'] = Posts_category::get();

        return view('backend.article.new-post', $data);
    }

    public function add_post_process(Request $request)
    {
        $rules = array(
            'title' => 'required',
            'slug' => 'required',
            // 'reader' => 'required',
            'category' => 'required',
            'content' => 'required',
            'image' => 'required',
            'meta_desc' => 'required',
            'meta_tags' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $foto = null;
            if (!empty($request->file('image'))) {
                $file = $request->file('image');
                $tujuan_upload = 'uploads/posts';
                $foto = $request->input('slug') . '.' . $file->getClientOriginalExtension();
                $file->move($tujuan_upload, $foto);
                $foto = url($tujuan_upload . '/' . $foto);
            }

            $article['title'] = $request->input('title');
            $article['slug'] = $request->input('slug');
            // $article['reader'] = $request->input('reader');
            $article['reader'] = $request->segment(2) == 'article' ? 'customer' : 'employee';
            $article['category'] = $request->input('category');
            $article['content'] = str_replace("../../uploads", url('uploads'), $request->input('content'));
            $article['image'] = $request->input('image');
            $article['pdf'] = $request->input('pdf');
            $article['status'] = $request->input('status');
            $article['meta_desc'] = $request->input('meta_desc');
            $article['meta_tags'] = $request->input('meta_tags');
            $article['created_at'] = date('Y-m-d H:i:s');
            $article['created_by'] = Auth::user()->id;

            $insert_article = Posts::insert($article);

            return redirect('artmin/' . ($request->segment(2) == 'article' ? 'article' : 'product-knowledge'))->with('success', 'Add New Post success.');
        }
    }

    public function edit_post($id, Request $request)
    {
        $reader = $request->segment(2) == 'article' ? 'customer' : 'employee';
        $data['category'] = Posts_category::get();
        $data['article'] = Posts::where('id', $id)->where('reader', $reader)->first();

        return view('backend.article.edit-post', $data);
    }

    public function edit_post_process($id, Request $request)
    {
        $rules = array(
            'title' => 'required',
            'slug' => 'required',
            // 'reader' => 'required',
            'category' => 'required',
            'content' => 'required',
            'image' => 'required',
            'meta_desc' => 'required',
            'meta_tags' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $foto = null;
            if (!empty($request->file('image'))) {
                $file = $request->file('image');
                $tujuan_upload = 'uploads/posts';
                $foto = $request->input('slug') . '.' . $file->getClientOriginalExtension();
                $file->move($tujuan_upload, $foto);
                $foto = url($tujuan_upload . '/' . $foto);
            }

            $article['title'] = $request->input('title');
            $article['slug'] = $request->input('slug');
            $article['reader'] = $request->segment(2) == 'article' ? 'customer' : 'employee';
            $article['category'] = $request->input('category');
            $article['content'] = str_replace("../../uploads", url('uploads'), $request->input('content'));
            $article['image'] = $request->input('image');
            $article['pdf'] = $request->input('pdf');
            $article['status'] = $request->input('status');
            $article['meta_desc'] = $request->input('meta_desc');
            $article['meta_tags'] = $request->input('meta_tags');
            $article['updated_at'] = date('Y-m-d H:i:s');
            $article['updated_by'] = Auth::user()->id;

            $update_article = Posts::where('id', $id)->update($article);

            return redirect('artmin/' . ($request->segment(2) == 'article' ? 'article' : 'product-knowledge'))->with('success', 'Update Post success.');
        }
    }

    public function delete($id, Request $request)
    {
        $reader = $request->segment(2) == 'article' ? 'customer' : 'employee';
        Posts::where('id', $id)->where('reader', $reader)->delete();
        return redirect('artmin/article')->with('success', 'Delete Post success.');
    }
}
