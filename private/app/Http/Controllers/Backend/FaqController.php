<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{

    public function answerPageList (Request $request) {
        if ($request->ajax()) {
            $qb = DB::table('ms_answer');
            return DataTables::of($qb)->toJson();
        }

        return view('backend.faq.answer', [
            'list_answer' => []
        ]);
    }

    public function answerJSON(Request $request)
    {
        $qb = DB::table('ms_answer')
            ->select('id', 'description');
        $keywords = $request->get('q') ?? '';
        if ($keywords) {
            $qb->whereRaw("description LIKE '%" . $keywords . "%'");
        }
        return $qb->get();
    }

    public function saveAnswer (Request $request) {

        $rules = array(
            'description' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'description' => $request->input('description')
        ];

        if ($request->input('id') == '0') {
            DB::table('ms_answer')->insert($data);
        } else {
            DB::table('ms_answer')
            ->where('id', $request->input('id'))
            ->update($data);
        }
        return redirect('artmin/faq/answer');
    }

    public function deleteAnswer (Request $request, $id) {
        DB::table('ms_answer')->where('id', $id)->delete();
    }

    ////////////

    public function questionAnswerPageList (Request $request) {
        if ($request->ajax()) {
            $qb = DB::table('ms_faq')
            ->select([
                'ms_faq.id', 'ms_faq.category_id', 'mc.name as category_name', 'ms_faq.subcategory_id', 'mcSub.name as subcategory_name', 'ms_faq.question', 'ms_faq.keywords'
            ])
            ->join('ms_categories as mc', 'mc.category_id', '=', 'ms_faq.category_id' )
            ->join('ms_categories as mcSub', 'mcSub.category_id', '=', 'ms_faq.subcategory_id' );
            return DataTables::of($qb)->toJson();
        }

        return view('backend.faq.question-answer', [
            'list_answer' => []
        ]);
    }

    public function saveQuestionAnswer (Request $request) {

        $faq_id = $request->input('id');

        $rules = array(
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'question' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'category_id' => $request->input('category_id'),
            'subcategory_id' => $request->input('subcategory_id'),
            'question' => $request->input('question'),
            'keywords' => $request->input('keywords')
        ];

        if ($faq_id == '0') {
            $faq_id = DB::table('ms_faq')->insertGetId($data);
            foreach ($request->input('answer_id') as $answer) {
                DB::table('ms_faq_answer')->insert([
                    'faq_id' => $faq_id,
                    'answer_id' => $answer
                ]);
            }
        } else {

            DB::table('ms_faq')->where('id', $faq_id)->update($data);

            foreach ($request->input('answer_id') as $answer) {
                $exist = DB::table('ms_faq_answer')->select(['id'])->where('faq_id', $faq_id)->where('answer_id', $answer)->first();
                if (!$exist) {
                    DB::table('ms_faq_answer')->insert([
                        'faq_id' => $faq_id,
                        'answer_id' => $answer
                    ]);
                }
            }

        }

        return redirect('artmin/faq/question-answer');
    }

    public function deleteQuestionAnswer (Request $request, $id) {
        DB::table('ms_faq')->where('id', $id)->delete();
        DB::table('ms_faq_answer')->where('faq_id', $id)->delete();
    }

    //////////

    public function faqAnswerJSON(Request $request)
    {
        $qb = DB::table('ms_faq_answer as mfa')
            ->select('mfa.id', 'mfa.answer_id', 'ma.description')
            ->leftJoin('ms_answer as ma', 'ma.id', '=', 'mfa.answer_id')
            ->where('faq_id', $request->get('faq_id'));

        $keywords = $request->get('q') ?? '';
        if ($keywords) {
            $qb->whereRaw("ma.description LIKE '%" . $keywords . "%'");
        }
        return $qb->get();
    }

    public function deleteFaqAnswer (Request $request, $id) {
        DB::table('ms_faq_answer')->where('id', $id)->delete();
    }
}
