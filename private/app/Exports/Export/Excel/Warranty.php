<?php

namespace App\Exports\Export\Excel;

use App\Reg_warranty;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;

class Warranty implements FromView, ShouldAutoSize
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
        $warranty =  Reg_warranty::select(
            'reg_warranty.*',
            'ms_products.product_name_odoo as product_name',
            'users.name as spg_name'
        )
            ->leftJoin('users', 'users.id', '=', 'reg_warranty.spg_ref')
            ->join('ms_products', 'reg_warranty.product_id', '=', 'ms_products.product_id')
            ->orderBy('reg_warranty.created_at', 'desc')
            // ->where('spg_ref', '12')
            // ->orWhere('spg_ref', '13')
            // ->orWhere('spg_ref', '11')
            // ->orWhere('spg_ref', '14')
            // ->orWhere('spg_ref', '22')
            // ->orWhere('spg_ref', '29')
            // ->orWhere('spg_ref', '15')
            // ->orWhere('spg_ref', '27')
            // ->orWhere('spg_ref', '37')
            // ->orWhere('spg_ref', '28')
            // ->orWhere('spg_ref', '28')
            // ->orWhere('spg_ref', '50')
            // ->orWhere('spg_ref', '83')
            ->whereBetween('reg_warranty.purchase_date', [$this->dateFrom, $this->dateTo])
            ->get();

        return view('backend.warranty.export-excel', [
            'warranty' => $warranty
        ]);
    }
}
