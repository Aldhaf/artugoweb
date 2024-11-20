<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\Excel\QuizData;
use Auth;
use Excel;
use DB;

class QuizController extends Controller
{

    private function has_answered($quiz_id, $apc_id) {
        $count = DB::table('quiz_answer')
        ->select("quiz_answer_detail.id")
        ->where('quiz_id', $quiz_id)
        ->where('apc_id', $apc_id)
        ->whereNull('deleted_at')
        ->join('quiz_answer_detail', 'quiz_answer.id', '=', 'quiz_answer_detail.quiz_answer_id')
        ->count();
        return $count > 0;
    }

    public function listQuiz()
    {
        if (in_array(Auth::user()->roles, ['1','2'])) {
            $data['quiz'] = DB::table('quiz')
                ->select(
                    'quiz.*',
                    DB::raw('count(quiz_answer.id) as jumlah_peserta'),
                )
                ->leftJoin('quiz_answer', 'quiz_answer.quiz_id', '=', 'quiz.id')
                ->groupBy('quiz.id')
                ->get();

            return view('backend.quiz.listQuiz', $data);
        } else {

            $data['quiz'] = DB::table('quiz')
                ->select(
                    'quiz.*',
                    'quiz_answer.id as quiz_answer_id',
                    'quiz_answer.count_true as quiz_answer_count_true',
                    'quiz_answer.count_false as quiz_answer_count_false',
                    'quiz_answer.score as quiz_answer_score',
                    'quiz_answer.duration as quiz_answer_duration',
                    DB::raw('(SELECT COUNT(qad.id) FROM quiz_answer_detail qad WHERE qad.quiz_answer_id = quiz_answer.id) AS answer_count')
                )
                ->leftJoin('quiz_answer', function ($join) {
                    $join->on('quiz_answer.quiz_id', '=', 'quiz.id')
                    ->where('quiz_answer.apc_id', Auth::user()->id)
                    ->whereNull('quiz_answer.deleted_at');
                })
                ->where('quiz.status','1')
                ->orderBy('quiz.id', 'asc')
                // ->leftJoin('quiz_answer', 'quiz_answer.quiz_id', '=', 'quiz.id')
                // ->where('quiz_answer.apc_id', Auth::user()->id)
                ->get();

            return view('backend.quiz.APC.listQuiz', $data);
        }
    }

    public function save_module_quiz(Request $request)
    {
        $data = [
            'name' => $request->input('subject'),
            'status' => '1',
            'score_true' => $request->input('score_true'),
            'score_false' => $request->input('score_false'),
            'time_limit' => $request->input('time_limit'),
            'created_by' => Auth::user()->id,
            'created_at' => date('Y-m-d H:i:s')
        ];
        DB::table('quiz')->insert($data);
    }

    public function listQuestion($quiz_id)
    {
        if (in_array(Auth::user()->roles, ['1','2'])) {
            $data['quiz'] = DB::table('quiz')->where('id', $quiz_id)->first();
            $data['quiz_question'] = DB::table('quiz_question')
                ->select(
                    'quiz_question.*',
                    'quiz_choice.option'
                )
                ->leftJoin('quiz_choice', 'quiz_choice.quiz_question_id', 'quiz_question.id')
                ->where('quiz_choice.isTrue', '1')
                ->groupBy('quiz_question.id')
                ->where('quiz_question.quiz_id', $quiz_id)
                ->get();

            return view('backend.quiz.listQuestion', $data);
        } else {

            // $quiz_answered_unfinished = DB::table('quiz_answer')
            // ->where('quiz_id', $quiz_id)
            // ->where('apc_id', Auth::user()->id)
            // ->where('count_true', 0)
            // ->where('count_false', 0)
            // ->where('score', 0)
            // ->where('duration', 0)
            // ->whereNotNull('created_at')
            // ->first();

            // dd($quiz_answered_unfinished);

            // if ($quiz_answered_unfinished == null) {

                if ($this->has_answered($quiz_id, Auth::user()->id)) {
                    return redirect('/artmin/quiz')->withErrors(['Sudah ada jawaban.'])->withInput();
                }

                DB::table('quiz_answer')
                ->where('quiz_id', $quiz_id)
                ->where('apc_id', Auth::user()->id)
                ->whereNull('deleted_at')
                ->update(['deleted_at' => date('Y-m-d H:i:s') ]);

                $answer = [
                    'quiz_id' => $quiz_id,
                    'apc_id' => Auth::user()->id,
                    'count_true' => 0,
                    'count_false' => 0,
                    'score' => 0,
                    'duration' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $answer_id = DB::table('quiz_answer')->insertGetId($answer);
                $quiz_answered_unfinished = DB::table('quiz_answer')->where('id', $answer_id)->first();

            // }


            $data['quiz_answered_unfinished'] = $quiz_answered_unfinished;
            $data['quiz'] = DB::table('quiz')->where('id', $quiz_id)->first();
            $data['quiz_question'] = DB::table('quiz_question')
                ->select(
                    'quiz_question.*',
                    'quiz_choice.option'
                )
                ->leftJoin('quiz_choice', 'quiz_choice.quiz_question_id', 'quiz_question.id')
                ->where('quiz_choice.isTrue', '1')
                ->groupBy('quiz_question.id')
                ->where('quiz_question.quiz_id', $quiz_id)
                ->get();

            return view('backend.quiz.APC.listQuestion', $data);
        }
    }

    public function insertQuestion($quiz_id)
    {
        if (in_array(Auth::user()->roles, ['1','2'])) {
            $data['quiz'] = DB::table('quiz')->where('id', $quiz_id)->first();
            $data['quiz_question'] = DB::table('quiz_question')->get();
            $data['statusAction'] = 'insert';

            return view('backend.quiz.formQuestion', $data);
        }
    }

    public function insertQuestion_save(Request $request)
    {
        $quiz = [
            'quiz_id' => $request->input('quiz_id'),
            'product_code' => $request->input('product_code'),
            'soal' => $request->input('quiz_question'),
            'tipe' => 'Pilihan Ganda',
            'created_by' => Auth::user()->id,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $quiz_question_id = DB::table('quiz_question')->insertGetId($quiz);

        if (!empty($request->input('option'))) {
            foreach ($request->input('option') as $key => $value) {
                $quiz_choice = [
                    'quiz_question_id' => $quiz_question_id,
                    'option' => $request->input('option')[$key],
                    'answer' => $request->input('option_answer')[$key],
                    'isTrue' => $request->input('iscorrect')[$key],
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                DB::table('quiz_choice')->insert($quiz_choice);
            }
        }
    }

    public function addQuestion()
    {
        if (in_array(Auth::user()->roles, ['1','2'])) {

            $data['statusAction'] = 'insert';
            $data['quiz_class'] = DB::table('quiz_class')->get();
            $data['category_product'] = DB::table('ms_categories')->whereNotNull('name')->get();

            return view('backend.quiz.formQuestion', $data);
        } else {
            return redirect('artmin/quiz');
        }
    }

    public function submit_answer(Request $request)
    {
        $data_quiz = DB::table('quiz')->where('id', $request->input('quiz_id'))->first();
        $question = DB::table('quiz_question')->where('quiz_id', $request->input('quiz_id'))->get();
        $count_true = 0;
        $count_false = 0;
        $score = 0;


        $answer = [
            'quiz_id' => $request->input('quiz_id'),
            'apc_id' => Auth::user()->id,
            'count_true' => $count_true,
            'count_false' => $count_false,
            'score' => $score,
            'created_at' => date('Y-m-d H:i:s')
        ];

        DB::table('quiz_answer')
        ->where('quiz_id', $request->input('quiz_id'))
        ->where('apc_id', Auth::user()->id)
        ->whereNull('deleted_at')
        ->update(['deleted_at' => date('Y-m-d H:i:s') ]);

        $answer_id = DB::table('quiz_answer')->insertGetId($answer);

        foreach ($question as $key => $value) {
            $choice_id = $request->input('answer_' . $value->id);
            if (!empty($choice_id)) {
                $check = DB::table('quiz_choice')->where('id', $choice_id)->first();

                if (!empty($check)) {
                    if ($check->isTrue == '1') {
                        $score += $data_quiz->score_true;
                        $count_true++;
                    } else {
                        $score += $data_quiz->score_false;
                        $count_false++;
                    }
                }

                $answer_detail = [
                    'quiz_answer_id' => $answer_id,
                    'choice_id' => $check->id,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                DB::table('quiz_answer_detail')->insert($answer_detail);
            }
        }

        $answer = [
            'count_true' => $count_true,
            'count_false' => $count_false,
            'score' => $score,
            'duration' => $request->input('duration'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        DB::table('quiz_answer')->where('id', $answer_id)->update($answer);
        // print_r($request->input());

        return redirect('artmin/quiz');
    }

    public function result($quiz_id)
    {
        $data['quiz'] = DB::table('quiz')->where('id', $quiz_id)->first();
        $data['quiz_answer'] = DB::table('quiz_answer')
            ->select(
                'quiz_answer.*',
                'users.name as apcName'
            )
            ->leftJoin('users', 'users.id', '=', 'quiz_answer.apc_id')
            ->where('quiz_answer.quiz_id', $quiz_id)
            ->get();

        return view('backend.quiz.result', $data);
    }

    public function statusQuiz(Request $request)
    {
        $current = DB::table('quiz')->where('id',$request->input('id'))->first();
        DB::table('quiz')->where('id',$request->input('id'))->update([
            'status' => ( $current->status == '1' ? '0' : '1' )
        ]);
    }

    public function result_export($from_date, $to_date, $quiz_id, $status='')
    {
        $export = new QuizData;
        $from_date = date('Y-m-d', strtotime($from_date));
        $to_date = date('Y-m-d', strtotime($to_date));
        $export->setFilter(array( 'from_date' => $from_date, 'to_date' => $to_date, 'quiz_id' => $quiz_id, 'status' => $status ));
        return Excel::download($export, 'artugo_quiz_' . $quiz_id . '_result_' . str_replace('-', '', $from_date) . '_' . str_replace('-', '', $to_date) . '_' . $status . '.xlsx');
    }
}
