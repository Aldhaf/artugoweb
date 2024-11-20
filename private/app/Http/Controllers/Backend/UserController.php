<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

use App\User;
use DB;
use Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data['user'] = []; // User::select('users.*', 'users_roles.title as roles_title')->join('users_roles', 'users_roles.id', '=', 'users.roles')->get();

        if ($request->ajax()) {
            $user_qb = User::select('users.*', 'users_roles.title as roles_title')->join('users_roles', 'users_roles.id', '=', 'users.roles')->get();
            return DataTables::of($user_qb)->toJson();
        }
        return view('backend/user/list', $data);
    }

    public function add_user()
    {
        $data['roles'] = DB::table('users_roles')->where('status', '1')->get();
        $data['statusAction'] = 'insert';
        $data['ms_service_center'] = [];
        // DB::table('ms_service_center')
        //     ->select('sc_id', 'sc_location')
        //     ->orderBy('sc_location', 'ASC')
        //     ->get();
        $data['ms_service_center_users'] = [];

        $data['store_location_regional'] = [];
        $data['store_location'] = [];
        $data['ms_store_location_users'] = [];
        $data['user'] = null;


        return view('backend/user/add-user', $data);
    }

    public function edit_user($userID)
    {
        
        $data['roles'] = DB::table('users_roles')->where('status', '1')->get();
        $data['user'] = DB::table('users')->where('id', $userID)->first();
        // $data['user'] = DB::table('users')
        //     ->select("users.*", "store_location.nama_toko")
        //     ->leftJoin('store_location', 'store_location.id', '=', 'users.store_id')
        //     ->where('users.id', $userID)->first();
        $data['statusAction'] = 'update';
        $data['ms_service_center'] = DB::table('ms_service_center')
            ->select('sc_id', 'sc_location')
            ->orderBy('sc_location', 'ASC')
            ->get();
        $data['ms_service_center_users'] = DB::table('ms_service_center_users')
            ->where('users_id', $userID)
            ->where('status', 1)
            ->orderBy('id', 'ASC')
            ->get();

        $data['store_location_regional'] = DB::table('store_location_regional')
            ->select('id', 'regional_name')
            ->orderBy('idx', 'ASC')
            ->get();
        $data['store_location'] = DB::table('store_location')
            ->select('store_location.id', 'store_location.nama_toko')
            ->join('ms_store_location_users', 'ms_store_location_users.store_id', 'store_location.id')
            ->where('ms_store_location_users.users_id', $userID)
            ->where('ms_store_location_users.status', 1)
            ->orderBy('regional_id', 'ASC')
            ->orderBy('nama_toko', 'ASC')
            ->get();
        $data['ms_store_location_users'] = DB::table('ms_store_location_users')
            ->where('users_id', $userID)
            ->where('status', 1)
            ->orderBy('id', 'ASC')
            ->get();

        return view('backend/user/add-user', $data);
        // print_r($data['user']);
    }

    public function add_user_process(Request $request)
    {
        $data = [
            'id' => null,
            'roles' => $request->input('roles'),
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'phoneNumber' => $request->input('phoneNumber'),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'password' => Hash::make($request->input('password')),
            'remember_token' => null,
            'join_date' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        if ($request->input('join_date') != 'null' && $request->input('join_date') != '') {
            $data['join_date'] = date('Y-m-d', strtotime($request->input('join_date')));
        }
        
        // $ms_sc_users = $request->input('ms_service_center_users');
        $id = User::insertGetId($data);

        return json_encode(array('status' => true, 'message' => 'Data Has Been Saved', 'data_id' => $id ));
    }

    public function edit_user_process(Request $request)
    {
        $data = [
            'roles' => $request->input('roles'),
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'phoneNumber' => $request->input('phoneNumber'),
            'updated_at' => date('Y-m-d H:i:s'),
            'join_date' => null
        ];
        if ($request->input('join_date') != 'null' && $request->input('join_date') != '') {
            $data['join_date'] = date('Y-m-d', strtotime($request->input('join_date')));
        }

        User::where('id', $request->input('userid'))->update($data);

        // print_r($request->input('ms_service_center_users'));
    }

    public function del_user_sc_process(Request $request, $sc_user_id)
    {
        DB::table('ms_service_center_users')->where('id', $sc_user_id)->update(['status' => 0, 'updated_at' => date('Y-m-d H:i:s'), 'deleted_by' => Auth::user()->id]);
    }

    public function add_user_sc_process(Request $request)
    {
        $data = [
            'users_id' => $request->input('userid'),
            'sc_id' => $request->input('sc_id'),
            'status' => 1,
            'created_by' => Auth::user()->id,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        DB::table('ms_service_center_users')->insert($data);
    }

    public function del_user_store_process(Request $request, $store_user_id)
    {
        DB::table('ms_store_location_users')->where('id', $store_user_id)->update(['status' => 0, 'updated_at' => date('Y-m-d H:i:s'), 'deleted_by' => Auth::user()->id]);
    }

    public function add_user_store_process(Request $request)
    {

        $exist = DB::table('ms_store_location_users')->where([
            'users_id' => $request->input('userid'),
            'store_id' => $request->input('store_id')
        ])->first();

        if ($exist == null) {
            $data = [
                'users_id' => $request->input('userid'),
                'store_id' => $request->input('store_id'),
                'status' => 1,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            DB::table('ms_store_location_users')->insert($data);
        } else {
            DB::table('ms_store_location_users')->where([
                'users_id' => $request->input('userid'),
                'store_id' => $request->input('store_id')
            ])->update([
                'status' => 1,
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public function reset_password()
    {
        return view('backend/user/reset-password');
    }

    public function reset_password_process(Request $request)
    {
        $user = User::where('id', $request->input('users_id'))->first();
        if (Hash::check($request->input('current_password'), $user->password)) {
            if ($request->input('new_password') == $request->input('confirmation_new_password')) {
                $password = Hash::make($request->input('new_password'));
                $data = [
                    'password' => $password
                ];
                User::where('id', $request->input('users_id'))->update($data);
                return json_encode(array('status' => true));
            } else {
                return json_encode(array('status' => false, 'message' => 'New Password and Confirmation New Password is Not Match'));
            }
        } else {
            return json_encode(array('status' => false, 'message' => 'Current Password is Not Match'));
        }
    }

    public function reset_password_admin_process(Request $request)
    {
        $password = Hash::make($request->input('password'));
        $data = [
            'password' => $password
        ];
        User::where('id', $request->input('users_id'))->update($data);
    }

    public function delete_user(Request $request)
    {
        User::where('id', $request->input('user_id'))->delete();
    }

    public function changeStatus(Request $request, $id)
    {
        $data['status'] = $request->input('active');
        DB::table('users')->where('id', $id)->update($data);
    }

}
