<?php

namespace App\Http\Controllers\Backend\Problems;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\ProblemDefect;
use App\ProblemInitial;
use Auth;

class ProblemDefectController extends Controller
{
    //
    public function index()
    {
        $data['problems_defect'] = ProblemDefect::select('ms_problems_defect.*', 'ms_problems_initial.initial')->join('ms_problems_initial', 'ms_problems_defect.problem_initial_id', '=', 'ms_problems_initial.problem_initial_id')->get();    

        
        return view('backend/problems/defect/list', $data);
    }

    public function add_problem_defect()
    {
        $data['problems_initial'] = ProblemInitial::all();

        return view('backend.problems.defect.add_defect', $data);
    }

    public function add_problem_defect_process(Request $request)
    {
        $rules = array(
            'defect' => 'required',
            'problem_initial_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = [
                'id' => null,
                'defect' => $request->input('defect'),
                'problem_initial_id' => $request->input('problem_initial_id'),
                'service_type' => $request->input('type'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id,
            ];
            // print_r($data);
            ProblemDefect::insert($data);

            return redirect('artmin/problem-defect');
        }
    }

    public function edit_problem_defect($defect_id)
    {
        $data['problem_defect'] = ProblemDefect::where('id', $defect_id)->first();
        $data['problems_initial'] = ProblemInitial::all();

        if (!empty($data['problem_defect'])) {
            return view('backend.problems.defect.edit_defect', $data);
        } else {
            return redirect('artmin/problem-defect');
        }
    }

    public function edit_problem_defect_process(Request $request)
    {
        $rules = array(
            'defect' => 'required',
            'problem_initial_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = [
                'problem_initial_id' => $request->input('problem_initial_id'),
                'defect' => $request->input('defect'),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id,
            ];
            
            ProblemDefect::where('id',$request->input('defect_id'))->update($data);

            return redirect('artmin/problem-defect');
        }
    }

    public function delete_problem_defect(Request $request)
    {
        $rules = array(
            'defect_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            ProblemDefect::where('id', $request->input('defect_id'))->delete();
        }
    }
}
