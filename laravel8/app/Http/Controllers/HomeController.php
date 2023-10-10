<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Models\Module;
use App\Models\RolesPermissions;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $response = $this->getuserPermissions();

        // echo '<pre>';
        // print_r($response);die;
        $roledata = $response['roledata'];
        $action = $response['action'];
        $controller = $response['controller'];
        return view('home', compact('roledata','action','controller'));
        //return view('home');
    }

    public function getuserPermissions()
    {

        $rolepermis = RolesPermissions::select('roles_permissions.id','roles_permissions.role_id','roles_permissions.mid','roles_permissions.navigationshow','modules.name','modules.action','modules.controller','modules.pid')
                    ->join('modules', 'modules.id', '=', 'roles_permissions.mid')
                    ->where('roles_permissions.role_id',Auth::user()->role_id)
                    ->orderBy('modules.depth','ASC')
                    //->where('roles_permissions.mid',4)
                    ->get()->toArray();

        $action=array();
        $controller=array();
        $roledata=array();  $i=0;
        foreach ($rolepermis as $permission) {

            $permission['child'] = [
                'id' => $permission['mid'],
                'name' => $permission['name'],
                'action' => $permission['action'],
                'controller' => $permission['controller'],
                'pid' => $permission['pid']
            ];

            //unset($permission['name'],$permission['action'],$permission['controller']);
            
            $moduleP = Module::select('id','name','action','controller','icon')->where('id',$permission['pid'])->first();
            if(isset($moduleP) && !empty($moduleP))
            {
                $roledata[$moduleP['id']]['ParentModule']=$moduleP;
            
                if(empty($permission['navigationshow'])){
                $roledata[$moduleP['id']]['ChildModule'][]=$permission['child'];
                }
                if(empty($roledata[$moduleP['id']]['ChildModule'])){
                    unset($roledata[$moduleP['id']]);
                }
                
                $action[]=str_replace("-","",$permission['child']['action']);
                $controller[]=str_replace("-","",$permission['child']['controller']);
            }
            
         }
         ksort($roledata);
         $response=array();
         array_push($controller,"dashboard","users");
         array_push($action,"logout");
         $response['roledata']=$roledata;
         $response['action']=array_unique($action);
         $response['controller']=array_unique($controller);
         return $response;
    }
}