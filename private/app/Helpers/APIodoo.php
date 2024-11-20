<?php

namespace App\Helpers;

class APIodoo
{
  private static function authenticate()
  {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => env('BASE_URL_ODOO') . '/web/session/authenticate',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HEADER => 1,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '{"jsonrpc":"2.0","params":{"db":"' . env('DB_ARTUGO_ODOO') . '","login":"' . env('DB_ARTUGO_USER') . '","password":"' . env('DB_ARTUGO_PASSWORD') . '"}}',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);

    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
    $cookies = array();
    foreach ($matches[1] as $item) {
      parse_str($item, $cookie);
      $cookies = array_merge($cookies, $cookie);
    }

    curl_close($curl);

    return $cookies['session_id'];
  }


  public static function create_vendor_bill($type, $ref, $ba, $ban, $bp, $pu, $dc, $ca)
  {
    $session_id = self::authenticate();
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => env('BASE_URL_ODOO') . '/create_vendor_bill',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_VERBOSE => true,
      CURLOPT_POSTFIELDS => '{
            "jsonrpc":"2.0",
            "params":
        {
            "reference" : "[' . $type . '] ' . $ref . '",
            "bank_account_number" : "' . $ba . ' a\n ' . $ban . '",
            "bank_partner_name" : "' . $bp . '",
            "default_code" : "' . $dc . '",
            "date" : "' . $ca . '",
            "lines" : [
                {
                    "price_unit" : ' . $pu . '
                }
            ]
        }
        }
        ',
      CURLOPT_HTTPHEADER => array(
        'session_id: ' . $session_id,
        'Content-Type: application/json',
        'Cookie: session_id=' . $session_id
      ),
      CURLOPT_COOKIE => 'session_id=' . $session_id,
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
  }


  public static function get_partner()
  {
    $session_id = self::authenticate();
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => env('BASE_URL_ODOO') . '/get_partner',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_VERBOSE => true,
      CURLOPT_POSTFIELDS => '{"jsonrpc":"2.0","params":{"db":"artugolive","login":"admin","password":"joko@123"}}',
      CURLOPT_HTTPHEADER => array(
        'session_id: ' . $session_id,
        'Content-Type: application/json',
        'Cookie: session_id=' . $session_id
      ),
      CURLOPT_COOKIE => 'session_id=' . $session_id,
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
  }

  public static function create_so($post_data)
  {

    $session_id = self::authenticate();

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => env('BASE_URL_ODOO') . '/create_sale_order',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_VERBOSE => true,
      CURLOPT_POSTFIELDS => '{
            "jsonrpc":"2.0",
            "params": ' . json_encode($post_data) . '
        }
      ',
      CURLOPT_HTTPHEADER => array(
        'session_id: ' . $session_id,
        'Content-Type: application/json',
        'Cookie: session_id=' . $session_id
      ),
      CURLOPT_COOKIE => 'session_id=' . $session_id,
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return json_decode(json_decode($response)->result);
    // return array('id' => 1111,'number' => 'XXXXXX001');
  }

}