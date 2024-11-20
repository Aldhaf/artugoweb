<?php

namespace App\Http\Controllers\Backend\Problems;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Products_category;
use App\ProblemInitial;
use Auth;
use DB;

class ProblemInitialController extends Controller
{
    //
    public function index()
    {
        $data['problems_initial'] = ProblemInitial::all();

        return view('backend.problems.initial.list', $data);
    }

    public function add_problem_initial()
    {
        $data['category'] = Products_category::where('parent_id', '!=' , 0)->get();

        return view('backend.problems.initial.add_initial', $data);
    }

    public function add_problem_initial_process(Request $request)
    {
        $rules = array(
            'initial' => 'required|max:2',
        );

        $rules_message = array(
            'initial.required' => 'Inisial Harus diisi',
            'initial.max' => 'Maksimal 2 huruf',
        );

        $validator = Validator::make($request->all(), $rules, $rules_message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $data = [
                'problem_initial_id' => null,
                'initial' => $request->input('initial'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id,
            ];
            // print_r($data);
            ProblemInitial::insert($data);

            return redirect('artmin/problem-initial');
        }
    }

    public function edit_problem_initial($problems_initial_id)
    {
        $data['problems_initial'] = ProblemInitial::where('problem_initial_id', $problems_initial_id)->first();
        $data['servicecenter'] = DB::table('ms_service_center')->get();

        if (!empty($data['problems_initial'])) {
            return view('backend.problems.initial.edit_initial', $data);
        } else {
            return redirect('artmin/problem-initial');
        }
    }

    public function edit_problem_initial_process(Request $request)
    {
        $rules = array(
            'initial' => 'required|max:2',
        );

        $rules_message = array(
            'initial.required' => 'Inisial Harus diisi',
            'initial.max' => 'Maksimal 2 huruf',
        );

        $validator = Validator::make($request->all(), $rules, $rules_message);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = [
                'initial' => $request->input('initial'),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id,
            ];

            ProblemInitial::where('problem_initial_id', $request->input('initial_id'))->update($data);

            return redirect('artmin/problem-initial');
        }
    }

    public function delete_problem_initial(Request $request)
    {
        $rules = array(
            'initial_id' => 'required',
        );
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            ProblemInitial::where('problem_initial_id', $request->input('initial_id'))->delete();
        }
    }

    public function status_problem_initial_process(Request $request)
    {
        $currentStatus = ProblemInitial::where('problem_initial_id',$request->input('initial_id'))->first()->need_installation; 
        ProblemInitial::where('problem_initial_id',$request->input('initial_id'))->update([
            'need_installation' => ($currentStatus == '1' ? '0' : '1')
        ]);
    }
}
