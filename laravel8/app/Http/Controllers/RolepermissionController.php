<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Module;

class RolepermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $modules = Module::where('pid',0)->pluck('name', 'id')->toArray();

    }

}
