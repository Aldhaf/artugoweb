<?php

namespace App\Http\Controllers\Backend\Problems;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\ProblemAction;
use App\ProblemInitial;
use Auth;

class ProblemActionController extends Controller
{
    public function index()
    {
        $data['problems_action'] = ProblemAction::select('ms_problems_action.*', 'ms_problems_initial.initial')->join('ms_problems_initial', 'ms_problems_action.problem_initial_id', '=', 'ms_problems_initial.problem_initial_id')->get();


        return view('backend/problems/action/list', $data);
    }

    public function add_problem_action()
    {
        $data['problems_initial'] = ProblemInitial::all();

        return view('backend.problems.action.add_action', $data);
    }

    public function add_problem_action_process(Request $request)
    {
        $rules = array(
            'action' => 'required',
            'problem_initial_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = [
                'id' => null,
                'action' => $request->input('action'),
                'service_type' => $request->input('type'),
                'problem_initial_id' => $request->input('problem_initial_id'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id,
            ];
            // print_r($data);
            ProblemAction::insert($data);

            return redirect('artmin/problem-action');
        }
    }

    public function edit_problem_action($action_id)
    {
        $data['problem_action'] = ProblemAction::where('id', $action_id)->first();
        $data['problems_initial'] = ProblemInitial::all();

        if (!empty($data['problem_action'])) {
            return view('backend.problems.action.edit_action', $data);
        } else {
            return redirect('artmin/problem-action');
        }
    }

    public function edit_problem_action_process(Request $request)
    {
        $rules = array(
            'action' => 'required',
            'problem_initial_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = [
                'problem_initial_id' => $request->input('problem_initial_id'),
                'action' => $request->input('action'),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id,
            ];

            ProblemAction::where('id', $request->input('action_id'))->update($data);

            return redirect('artmin/problem-action');
        }
    }

    public function delete_problem_action(Request $request)
    {
        $rules = array(
            'action_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            ProblemAction::where('id', $request->input('action_id'))->delete();
        }
    }
}
