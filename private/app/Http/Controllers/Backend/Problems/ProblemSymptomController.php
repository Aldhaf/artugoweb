<?php

namespace App\Http\Controllers\Backend\Problems;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\ProblemSymptom;
use App\ProblemInitial;
use Auth;

class ProblemSymptomController extends Controller
{
    //
    public function index()
    {
        $data['problems_symptom'] = ProblemSymptom::select('ms_problems_symptom.*', 'ms_problems_initial.initial')->join('ms_problems_initial', 'ms_problems_symptom.problem_initial_id', '=', 'ms_problems_initial.problem_initial_id')->get();    

        
        return view('backend/problems/symptom/list', $data);
    }

    public function add_problem_symptom()
    {
        $data['problems_initial'] = ProblemInitial::all();

        return view('backend.problems.symptom.add_symptom', $data);
    }

    public function add_problem_symptom_process(Request $request)
    {
        $rules = array(
            'symptom' => 'required',
            'problem_initial_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = [
                'id' => null,
                'symptom' => $request->input('symptom'),
                'service_type' => $request->input('type'),
                'problem_initial_id' => $request->input('problem_initial_id'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id,
            ];
            // print_r($data);
            ProblemSymptom::insert($data);

            return redirect('artmin/problem-symptom');
        }
    }

    public function edit_problem_symptom($symptom_id)
    {
        $data['problem_symptom'] = ProblemSymptom::where('id', $symptom_id)->first();
        $data['problems_initial'] = ProblemInitial::all();

        if (!empty($data['problem_symptom'])) {
            return view('backend.problems.symptom.edit_symptom', $data);
        } else {
            return redirect('artmin/problem-symptom');
        }
    }

    public function edit_problem_symptom_process(Request $request)
    {
        $rules = array(
            'symptom' => 'required',
            'problem_initial_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = [
                'problem_initial_id' => $request->input('problem_initial_id'),
                'symptom' => $request->input('symptom'),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id,
            ];
            
            ProblemSymptom::where('id',$request->input('symptom_id'))->update($data);

            return redirect('artmin/problem-symptom');
        }
    }

    public function delete_problem_symptom(Request $request)
    {
        $rules = array(
            'symptom_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            ProblemSymptom::where('id', $request->input('symptom_id'))->delete();
        }
    }
}
