<?php

namespace App\Exports\Excel;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class MemberPoint implements FromView, ShouldAutoSize
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
    $member_point = DB::table('ms_members')
      ->select(
          'ms_members.name',
          'ms_members.email',
          'ms_members.phone',
          'ms_members.address',
          'ms_members.city',
          'pd.product_name_odoo',
          'reg_warranty.warranty_no',
          'reg_warranty.serial_no',
          'reg_warranty.purchase_date',
          'member_point.expired_at',
          'member_point.status',
          'member_point.value',
          'member_point.used',
          'member_point.balance'
      )
      ->join('member_point', 'member_point.member_id', '=', 'ms_members.id')
      ->join('reg_warranty', 'reg_warranty.warranty_id', '=', 'member_point.warranty_id')
      ->join('ms_products AS pd', 'pd.product_id', '=', 'reg_warranty.product_id')
      ->whereNull('merge_to')
      ->whereBetween('member_point.created_at', [$this->dateFrom, $this->dateTo])
      ->orderBy('member_point.created_at')
      ->get();

    return view('backend.member.export_member_point', [
      'member_point' => $member_point
    ]);
  }
}
