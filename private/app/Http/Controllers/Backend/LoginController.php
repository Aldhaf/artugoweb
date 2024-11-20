<?php

namespace App\Http\Controllers\Backend;

use App\User;
use App\Products;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Hash;
use Auth;

class LoginController extends Controller{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function index(){
        // echo Hash::make('password');
        return view('backend.login');
    }

    public function login_check(Request $request){
        $rules = array(
            'username' => 'required',
            'password' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Invalid Username or Password.');
        }
        else{
            $user_data = array(
                'username' => $request->get('username'),
                'password' => $request->get('password')
            );

            if(Auth::attempt($user_data)){
                return redirect()->intended('artmin');
            }
            else{
                return redirect()->back()->with('error', 'Invalid Username or Password.');
            }
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('artmin/login');
    }
}
