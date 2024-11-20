<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class SettingsController extends Controller
{
    public function footer()
    {
        $data['subject'] = DB::table('footer_subject')->get();
        return view('backend.settings.config_footer.index', $data);
    }

    public function footerAdd(Request $request)
    {
        $data = [
            'footerSubjectID' => $request->input('subjectID'),
            'name' => $request->input('name'),
            'href' => $request->input('link'),
            'status' => '1'
        ];
        DB::table('footer_content')->insert($data);
    }

    public function footerUpdate(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'href' => $request->input('link')
        ];
        DB::table('footer_content')->where('id', $request->input('footerContentID'))->update($data);
    }

    public function footerStatus(Request $request)
    {
        $current = DB::table('footer_content')->where('id', $request->input('id'))->first()->status;
        DB::table('footer_content')->where('id', $request->input('id'))->update([
            'status' => ($current == '1' ? '0' : '1')
        ]);
    }

    public function footerDelete(Request $request)
    {
        DB::table('footer_content')->where('id', $request->input('id'))->delete();
    }
}
