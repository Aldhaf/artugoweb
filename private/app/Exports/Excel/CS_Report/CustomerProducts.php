<?php

namespace App\Exports\Excel\CS_Report;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class CustomerProducts implements FromCollection, WithHeadings, ShouldAutoSize
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


    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $fetchData = DB::table('ms_members')
            ->select(
                'ms_members.name',
                'ms_members.phone',
                'ms_members.email',
                DB::raw('COUNT(reg_warranty.product_id) as registered_products')
            )
            ->join('reg_warranty', 'reg_warranty.member_id', '=', 'ms_members.id')
            ->groupBy('ms_members.id')
            ->orderBy('registered_products', 'desc')
            ->whereBetween('reg_warranty.purchase_date', [$this->dateFrom, $this->dateTo])
            ->get();

        return $fetchData;
    }
    
    public function headings(): array
    {
        return ["Customer", "Phone", "Email", "Registered Products"];
    }
}
