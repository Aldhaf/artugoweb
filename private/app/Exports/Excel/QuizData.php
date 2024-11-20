<?php

namespace App\Exports\Excel;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\{
    BeforeExport,
    AfterSheet
};
use Illuminate\Contracts\View\View;
use DB;

class QuizData implements FromView, ShouldAutoSize, WithEvents
{
    private $filter;
    private $title;

    public function setFilter($filter) {
        $this->filter = json_decode(json_encode($filter));
    }

    public function view(): View
    {

        $whereRaw = 'DATE_FORMAT(quiz_answer.created_at, "%Y-%m-%d") >= "' . $this->filter->from_date . '" AND DATE_FORMAT(quiz_answer.created_at, "%Y-%m-%d") <= "' . $this->filter->to_date . '"';

        if ($this->filter->status == 'completed') {
            $whereRaw .= 'AND deleted_at IS NULL';
        } else if ($this->filter->status == 'retry') {
            $whereRaw .= 'AND deleted_at IS NOT NULL';
        }

        $data['quiz'] = DB::table('quiz')->where('id', $this->filter->quiz_id)->first();
        
        $title = $data['quiz']->name;

        $data['quiz_answer'] = DB::table('quiz_answer')
            ->select(
                'quiz_answer.*',
                'users.name as apcName'
            )
            ->leftJoin('users', 'users.id', '=', 'quiz_answer.apc_id')
            ->where('quiz_answer.quiz_id', $this->filter->quiz_id)
            ->whereRaw($whereRaw)
            ->get();

        return view('backend.quiz.quizresult_export_excel', $data);
    }

    public function registerEvents(): array
    {

        $stylesArray = [
            'title' => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['vertical' => 'center', 'horizontal' => 'center']
            ],
            'col_head' => [
                'font' => ['bold' => true]
            ],
        ];

        return [
            AfterSheet::class => function(AfterSheet $event) use ($stylesArray) {
                $event->sheet->getStyle('A1')->applyFromArray($stylesArray['title']);
                $event->sheet->getStyle('A3:H3')->applyFromArray($stylesArray['col_head']);
            }
        ];
    }

}
