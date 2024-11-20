<?php

namespace App\Exports\Excel;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class StoreSales implements FromView, ShouldAutoSize
{

  private $dateFrom;
  private $dateTo;

  public function setDateFrom(string $dateFrom)
  {
    $this->dateFrom = $dateFrom;
  }

  public function setDateTo(string $dateTo)
  {
    $this->dateTo = $dateTo;
  }


  public function view(): View
  {
    $store_sales =  DB::table('ms_store_sales_product')
      ->select(
        'ms_store_sales.sales_id',
        'ms_store_sales.sales_no',
        'ms_store_sales.sales_date',

        'ms_store_sales.customer_nama',
        'ms_store_sales.customer_alamat',
        'ms_store_sales.customer_telp',

        'store_location.nama_toko',
        'store_location_regional.regional_name',
        'ms_products.product_code',
        'ms_products.product_name_odoo',
        'users.name as karyawanName',
        'ms_store_sales_product.qty',
        'ms_store_sales_product.flag_categ_b',
        'ms_store_sales_product.harga',
      )
      ->leftJoin('ms_store_sales', 'ms_store_sales.sales_id', '=', 'ms_store_sales_product.sales_id')
      ->leftJoin('store_location', 'store_location.id', '=', 'ms_store_sales_product.store_id')
      ->leftJoin('ms_products', 'ms_products.product_id', '=', 'ms_store_sales_product.product')
      ->leftJoin('users', 'users.id', '=', 'ms_store_sales_product.users_id')
      ->leftJoin('store_location_regional', 'store_location.regional_id', '=', 'store_location_regional.id')
      ->whereBetween('ms_store_sales.sales_date', [$this->dateFrom, $this->dateTo])
      ->orderBy('ms_store_sales.sales_date')
      ->get();

    return view('backend.store_sales.export_store_sales', [
      'store_sales' => $store_sales
    ]);
  }
}
