<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Auth;
use App\Models\Module;
use App\Models\RolesPermissions;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class SidebarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //$response = $this->getuserPermissions();

        // echo '<pre>';
        // print_r($response);die;
        //$roledata = $response['roledata'];
        //$action = $response['action'];
       // $controller = $response['controller'];
       // return view('layouts.sidebar', compact('roledata','action','controller'));

    //    $id = '';
       View::composer(['layouts.sidebar'], function($view){
            if (Auth::check()){
                $id = Auth::id();
                $user = User::find($id);
                //====================================================================
                $rolepermis = RolesPermissions::select('roles_permissions.id','roles_permissions.role_id','roles_permissions.mid','roles_permissions.navigationshow','modules.name','modules.action','modules.controller','modules.pid')
                    ->join('modules', 'modules.id', '=', 'roles_permissions.mid')
                    ->where('roles_permissions.role_id',$user->role_id)
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
                    
                    $view->with('response',$response);
            }
        });
       
        //View::share('response', $response);
    }

    public function getuserPermissions()
    {

        // echo '<pre>';
        // //die('success');
        // print_r(Auth::user());die;
        $rolepermis = RolesPermissions::select('roles_permissions.id','roles_permissions.role_id','roles_permissions.mid','roles_permissions.navigationshow','modules.name','modules.action','modules.controller','modules.pid')
                    ->join('modules', 'modules.id', '=', 'roles_permissions.mid')
                    ->where('roles_permissions.role_id',1)
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
