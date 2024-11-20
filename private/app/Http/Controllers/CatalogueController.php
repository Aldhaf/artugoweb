<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

// use Illuminate\Support\Facades\Mail;
// use EmailHelper;

class CatalogueController extends Controller
{

    public function index(){
        $data['catalogue_list'] = DB::table('ms_catalogue')->where("display", 1)->orderBy('ordering', 'asc')->get();
        // dd($data['catalogue_list']);
        return view('web.catalogue', $data);
    }

    public function find(Request $request){
        $keyword = $request->query("keyword");
        // DB::enableQueryLog();
        $data = DB::table('ms_catalogue')->where("display", 1)->where("index_content", "LIKE", "%$keyword%")->orderBy('ordering', 'asc')->get();
        // dd(DB::getQueryLog());

        // Mail::send([], [], function ($message) {
        //     $message->to("roviriyadi@gmail.com")
        //     ->subject("Xxxxxxx")
        //     ->setBody(EmailHelper::wrap_body_html("<div>XXXXXXXXXXXXXXXXX</div>"), 'text/html');
        // });

        return $data;
    }

}
