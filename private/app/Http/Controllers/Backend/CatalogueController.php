<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CatalogueController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function index()
    {
        $data['catalogue_list'] = DB::table('ms_catalogue')->orderBy('ordering', 'asc');
        return view('backend.catalogue', $data);
    }

}
