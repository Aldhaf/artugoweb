<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OdooController extends Controller
{
    public function test()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sys.artugo.co.id/web/session/authenticate",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"2.0\",\"params\":{\"db\":\"artugo_live_new\",\"login\":\"admin\",\"password\":\"joko@123\"}}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Cookie: session_id=4fd4de19256a05b5d23ff80668647456087ad1c0"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}
