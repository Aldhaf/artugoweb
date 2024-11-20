<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class StoreController extends Controller
{
    public function sales_report(Request $request)
    {
        $date_from = $_GET['date_from'];
        $date_to = $_GET['date_to'];
        $operating_unit = $_GET['operating_unit'];

        $sql = "SELECT 
            slr.regional_name,
            us.name,
            sl.nama_toko,
            customer_nama,
            customer_telp,
            customer_alamat,
            sales_date,
            sales_no,
            qty,
            harga,
            product_code,
            default_code,
            warranty_no,
            serialno,
            sl.discount_area,
            mc.name AS category,
            st.created_at,
            st.updated_at
        FROM ms_store_sales st 
        LEFT JOIN ms_store_sales_product stp ON st.sales_id=stp.sales_id
        LEFT JOIN store_location sl ON sl.id=st.store_id
        LEFT JOIN store_location_regional slr ON slr.id=sl.regional_id
        LEFT JOIN users us ON us.id=st.users_id
        LEFT JOIN ms_products mp ON mp.product_id=stp.product
        LEFT JOIN ms_categories mc ON mc.category_id=mp.category
        WHERE sales_date BETWEEN '$date_from' AND '$date_to'
        AND slr.link_regional_name = '$operating_unit'
        ORDER BY us.name, sl.nama_toko, sales_date";
        $data = DB::select($sql);

        if (count($data) > 0) {
            return json_encode($data);
        } else {
            return json_encode(array("message" => "0 results"));
        }
    }
}