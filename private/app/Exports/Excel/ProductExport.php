<?php

namespace App\Exports\Excel;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class ProductExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function view(): View
    {
        $data['products'] = DB::table('ms_products')->get();

        return view('backend.product.export_excel',$data);
    }
}
