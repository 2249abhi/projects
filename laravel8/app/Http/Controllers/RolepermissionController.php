<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Module;
use App\Models\Role;
use App\Models\RolesPermissions;

class RolepermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();

        $sub_modules = [];
        $permissions = [];
        $role_permissions = [];
        $menu = [];

        if(!empty($input))
        {
            request()->validate([
                'role' => 'required',
                'module' => 'required',
            ]);

            $c_module = Module::find($input['module']);
            $c_module_name = $c_module->name;
            $sub_modules = Module::where('pid',$input['module'])->pluck('name', 'id')->toArray();
            $permissions = RolesPermissions::where('role_id',$input['role'])->where('module',$c_module_name)->pluck('moduletask', 'mid')->toArray();

            $menu = RolesPermissions::where('role_id',$input['role'])->where('module',$c_module_name)->pluck('navigationshow', 'mid')->toArray();
            //echo '<pre>';
            // print_r($sub_modules);die;
            // echo '=========================';
            //print_r($permissions);die;

            $role_permissions = array_keys($permissions);


        }
        
        $emp = [''=>'Select Role'];
        $modules = Module::where('pid',0)->pluck('name', 'id')->toArray();

       // $modules = array_merge([''=>'Select Module'],$modules);

        //$roles = ['' => 'Select Role'];
        $roles = Role::pluck('name','id')->all();        

        return view('rolepermissions.index',compact('modules','roles','sub_modules','role_permissions','menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'name' => 'required',
        //     'permission' => 'required',
        // ]);

        $input = $request->all();
        $menu = [];

        if(!empty($input['menu']))
        {
            $menu = array_keys($input['menu']);
        }
        

        $data = [];

        $modules = Module::pluck('name', 'id')->toArray();

        $name = $modules[$input['module_id']];

        RolesPermissions::where('module',$name)->delete();

        foreach ($input['rolepermission'] as $permission) {
            $data = [
                'role_id'           =>  $input['role_id'],
                'mid'               =>  $permission,
                'navigationshow'    =>  (!empty($permission) && in_array($permission, $menu)) ? '1' : '0',
                'module'            =>  $modules[$input['module_id']],
                'moduletask'        =>  $modules[$permission]
            ]; 
            RolesPermissions::create($data);
        }

        // echo '<pre>';
        // print_r($data);die;
        return redirect()->route('rolepermissions.index',['role'=>$input['role_id'],'module'=>$input['module_id']])->with('success','Permission updated successfully');
       
    }

}
