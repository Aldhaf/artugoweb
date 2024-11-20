<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class ProductHelper {
    public static function get_data($product_id){
        $product = DB::table('ms_products')->where('product_id', $product_id)->first();

        return $product;
    }
}
