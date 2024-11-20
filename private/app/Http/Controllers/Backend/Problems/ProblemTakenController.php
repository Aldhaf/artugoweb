<?php

namespace App\Http\Controllers\Backend\Problems;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\ProblemTaken;
use App\ProblemInitial;
use Auth;

class ProblemTakenController extends Controller
{
    //
    public function index()
    {
        $data['problems_taken'] = ProblemTaken::select('ms_problems_taken.*', 'ms_problems_initial.initial')->join('ms_problems_initial', 'ms_problems_taken.problem_initial_id', '=', 'ms_problems_initial.problem_initial_id')->get();    

        
        return view('backend/problems/taken/list', $data);
    }

    public function add_problem_taken()
    {
        $data['problems_initial'] = ProblemInitial::all();

        return view('backend.problems.taken.add_taken', $data);
    }

    public function add_problem_taken_process(Request $request)
    {
        $rules = array(
            'taken' => 'required',
            'problem_initial_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = [
                'id' => null,
                'taken' => $request->input('taken'),
                'problem_initial_id' => $request->input('problem_initial_id'),
                'service_type' => $request->input('type'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id,
            ];
            // print_r($data);
            ProblemTaken::insert($data);

            return redirect('artmin/problem-taken');
        }
    }

    public function edit_problem_taken($taken_id)
    {
        $data['problem_taken'] = ProblemTaken::where('id', $taken_id)->first();
        $data['problems_initial'] = ProblemInitial::all();

        if (!empty($data['problem_taken'])) {
            return view('backend.problems.taken.edit_taken', $data);
        } else {
            return redirect('artmin/problem-taken');
        }
    }

    public function edit_problem_taken_process(Request $request)
    {
        $rules = array(
            'taken' => 'required',
            'problem_initial_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = [
                'problem_initial_id' => $request->input('problem_initial_id'),
                'taken' => $request->input('taken'),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id,
            ];
            
            ProblemTaken::where('id',$request->input('taken_id'))->update($data);

            return redirect('artmin/problem-taken');
        }
    }

    public function delete_problem_taken(Request $request)
    {
        $rules = array(
            'taken_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            ProblemTaken::where('id', $request->input('taken_id'))->delete();
        }
    }
}
