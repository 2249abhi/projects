<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
 * Modules Controller
 *
 * @property \App\Model\Table\ModulesTable $Modules
 */
class ModulesController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    /*
     *  For Module List
     */ 
    public function index(){
        $this->set('title', 'Modules list');
        $modulesTable = TableRegistry::get('modules');
        $moduleResult = $modulesTable->find('list',['keyField'=>'id','valueField'=>'name','conditions'=>['pid'=>0],'order'=>'name ASC'])->hydrate(false)->toArray();

        $this->set('module_result',$moduleResult);

        $search_condition = array();
        if(isset($this->request->query['page_length'])){
            $page_length = $this->request->query['page_length'];
        } else {
            $page_length = 10;
        }

        if(!empty($this->request->query['page'])){
            $this->set('page', $this->request->query['page']);
            $page = $this->request->query['page'];
        }else{
            $page = 1;
        }

        if(!empty($this->request->query['pid'])){
            $selectedmod = $this->request->query['pid'];
            $this->set('selectedmod', $selectedmod);
            $search_condition[] = "modules.pid = '" . $selectedmod . "'";
        }else{
            $search_condition[] = "modules.pid = 0";
        }

        if(!empty($this->request->query['sort'])){
            $this->set('sort', $this->request->query['sort']);
            $order = $this->request->query['sort'];
        }
        if(!empty($this->request->query['direction'])){
            $this->set('direction', $this->request->query['direction']);
            $order_dir = $order.' '.$this->request->query['direction'];
            $order_dir = "$order_dir";
        }
        if(empty($order)){
            $order = 'modules.depth asc';
            $order_dir = 'modules.depth asc';
        }

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }

        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'order'=>[$order],
                'limit' => $page_length
            ];
        } else {
            $numUsers = $modulesTable->find('all')->count();
            $this->paginate = [
                'order'=>[$order],
                'limit' => $numUsers
            ];
        }
        
        if(!empty($this->request->query['export_excel'])) { 
            if(!empty($numUsers)){
                $queryExport = $modulesTable->find('all', [
                    'conditions'=>[$searchString],
                    'limit' =>$numUsers,
                    'page'=> $page,
                    'order'=>[$order_dir]
                ]);
            }else{              
                $queryExport = $modulesTable->find('all', [
                    'conditions'=>[$searchString],
                    'limit' =>$page_length,
                    'page'=> $page,
                    'order'=>[$order_dir],
                ]);
            }
            
            $queryExport->hydrate(false);
            $ExportResultData = $queryExport->toArray();
            $fileName = "Modules_list".date("d-m-y:h:s").".xls";
            $data = array(); $i=1;
            if(@$this->request->query['pid'] ==0){
                $headerRow = array("S.No", "Name", "Position");
                foreach($ExportResultData As $rows){
                    $data[] = [$i, $rows['name'], $rows['depth']];
                    $i++;
                }
            }else{
                $headerRow = array("S.No", "Name", "Controller", "Action", "Position");
                foreach($ExportResultData As $rows){
                    $data[] = [$i, $rows['name'], $rows['controller'], $rows['action'], $rows['depth']];
                    $i++;
                }
            }
            $this->exportInExcel($fileName, $headerRow, $data);
        }
        
        $query = $modulesTable->find('all', ['conditions'=>[$searchString],'order'=>[$order_dir]]);

        $dsdetails = $this->paginate($query);
        $this->set('selectedLen',$page_length);
        $this->paginate = ['limit' => $page_length];
        $this->set(compact('dsdetails'));
        $this->set('_serialize', ['dsdetails']);
    }
    
    public function addModule(){        
        $this->__module();
    }

    public function editModule($id = null) {
         $this->__module($id);
    }

    private function __module($id=NULL){
        $this->set('title', 'Add/Edit Modules');
        $modulesTable = TableRegistry::get('modules');
        if($id){
            $modulesData = $modulesTable->get($id);
        }else{
            $modulesData = $modulesTable->newEntity();
        }
        $moduleResult = $modulesTable->find('list', ['keyField'=>'id', 'valueField'=>'name','conditions'=>['pid'=>0]])->hydrate(false)->toArray();
        
        $this->set(compact('modulesData','moduleResult'));
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            
            $query = $modulesTable->find('all',['conditions'=>['action'=> trim(@$this->request->data['action']),'controller'=> trim(@$this->request->data['controller'])]])->hydrate(false)->first();

           if(!empty($query) && $query['id'] != $id) {
                $this->Flash->error(__('Record already exist'));
            }else{
                if($this->request->data['depth']){
                    $depth = $this->request->data['depth'];
                } else {
                    if($this->request->data['pid'] == 0){
                        $depth = $modulesTable->find('all', ['conditions'=>['pid'=>0], 'fields'=>['depth'=>'MAX(depth)']])->hydrate(false)->first();
                    } else {
                        $depth = $modulesTable->find('all', ['conditions'=>['pid'=>$this->request->data['pid']], 'fields'=>['depth'=>'MAX(depth)']])->hydrate(false)->first();
                    }
                    $depth = $depth['depth']+1;
                }

                if(@$this->request->data['icon']){
                    $icon = explode(' ', $this->request->data['icon']);
                    $icon_fa = explode('-', @$icon[1]);
                    if($icon[0] != 'fa' || $icon_fa[0] != 'fa' || $icon_fa[1] == ''){
                        $this->Flash->error(__("Font Awesome icon is not correct"));
                        return $this->redirect(['action' => "editModule/$id"]);
                    }
                }

                $module_arr = [
                    $modulesData->name = $this->request->data['name'],
                    $modulesData->description = @$this->request->data['description'],
                    $modulesData->pid = $this->request->data['pid'],
                    $modulesData->cid = $this->request->data['cid'],
                    $modulesData->controller = @$this->request->data['controller'],
                    $modulesData->action = @$this->request->data['action'],
                    $modulesData->icon = @$this->request->data['icon'],
                    $modulesData->depth = $depth,
                    $modulesData->created = date('Y-m-d H:i:s'),
                ];
                $modulesData = $modulesTable->patchEntity($modulesData, $module_arr, ['validate' => false]);
                
                if ($modulesTable->save($modulesData)) {
                    if($id){
                        $this->Flash->success(__("The Module has been updated successfully"));
                    }else{
                        $this->Flash->success(__("The Module has been added successfully"));
                    }
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->success(__("The Role could not be updated. Please, try again"));
                }
            }
        }
        $this->set('_serialize', ['role']);
    }

    public function getChildModule()
    {
        $this->viewBuilder()->layout('ajax');
        $pid = $_POST['pid'];
        $modulesTable = TableRegistry::get('modules');
        $moduleResult = $modulesTable->find('list', ['keyField'=>'id', 'valueField'=>'name','conditions'=>['pid'=>$pid]])->hydrate(false)->toArray();
        $html='';
        $html .="<option>--Child Module--</option>" ;
        foreach( $moduleResult as $key=>$each ){
            $html .="<option value='".$key."'>".$each."</option>" ;
        }
        $response=array();
        if($moduleResult){
            $response['status']='true';
            $response['data']=$html;
        }else{
            $response['status']='false';
        }
        echo json_encode($response);
        exit;
    }

}
    
   
