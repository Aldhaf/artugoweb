<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use DB;
use Session;
use App\Products;
use App\Products_serial;
use App\Reg_warranty;

class QRCodeController extends Controller
{
    public function index(){

        return view('web.warranty');
    }

    public function scan($code){

        return view('web.qr-view', [
            "code" => $code
        ]);
    }


}
