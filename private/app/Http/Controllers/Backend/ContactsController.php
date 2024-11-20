<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables;

class ContactsController extends Controller
{
    public function index(Request $request)
    {
        $data['contacts'] = [];
        if ($request->ajax()) {
            $qb = DB::table('contacts');
            return DataTables::of($qb)->toJson();
        }
        return view('backend.contacts.list', $data);
    }

    public function detail($id)
    {

        $data['templates'] = [];

        if ($id !== 'new') {
            $data['statusAction'] = 'update';
            $data['contact'] = DB::table('contacts')
            ->select('contacts.*')
            // ->join('wa_messages', 'wa_messages.id', '=', 'contacts.message_id')
            ->where('contacts.id', $id)->first();
        } else {
            $data['statusAction'] = 'insert';
            $data['contact'] = json_decode(json_encode(array('id' => 0, 'name' => '', 'code' => '', 'phone' => '', 'status' => 'active' )));
            $data['recipients']= array();
        }

        return view('backend.contacts.form', $data);
    }

    public function detailPost(Request $request)
    {

        $data = $request->input();
        $id = $data['id'];

        unset($data['_token']);
        unset($data['id']);

        if (intval($id) === 0) {
            $id = DB::table('contacts')->insertGetId($data);
        } else {
            DB::table('contacts')->where('id', $id)->update($data);
        }

        return redirect('artmin/contacts/' . $id);
    }

    public function detailDelete(Request $request)
    {

        $id = $request->input('id');

        $contact = DB::table('contacts')->where('id', $id)->first();
        if (!$contact) {
            return ['success' => false, 'message' => 'Kontak tidak ditemukan.'];
        }

        DB::table('contacts')->where('id', $id)->delete();

        return ['success' => true, 'message' => 'Kontak telah dihapus.'];
    }

}
