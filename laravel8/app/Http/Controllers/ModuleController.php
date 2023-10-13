<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        //  $this->middleware('permission:module-list|module-create|module-edit|module-delete', ['only' => ['index','show']]);
        //  $this->middleware('permission:module-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:module-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:module-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pid = $request->input('pid');

        //return pid;
            if(isset($pid) && !empty($pid))
            {
                $modules = Module::where('pid',$request->input('pid'))->paginate(10);
               
            } else {
                $modules = Module::where('pid',0)->paginate(10);
            }

            return view('modules.index',compact('modules'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $modules = Module::where('pid',0)->pluck('name', 'id')->toArray();
        array_unshift($modules, [0 => 'Root']);
        return view('modules.create',compact('modules'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'pid' => 'required',
            'name' => 'required',
            'detail' => 'required',
            'action' => 'required',
            'depth' => 'required',
        ]);
        $input = $request->all();

        echo '<pre>';
        print_r($input);die;
        Module::create([
            'pid'       => $input['pid'],
            'name'      => $input['name'],
            'action'    => $input['action'],
            'depth'     => $input['depth'],
            'description'    => $input['detail'],
            'controller' => '',
        ]);

        // echo '<pre>';
        // print_r($q);die;
    
        return redirect()->route('modules.index')->with('success','Module created successfully.');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module)
    {
        return view('modules.show',compact('module'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function edit(Module $module)
    {
        return view('modules.edit',compact('module'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $module)
    {
         request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
    
        $module->update($request->all());
    
        return redirect()->route('modules.index')
                        ->with('success','Module updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        $module->delete();
    
        return redirect()->route('modules.index')
                        ->with('success','Module deleted successfully');
    }

}
