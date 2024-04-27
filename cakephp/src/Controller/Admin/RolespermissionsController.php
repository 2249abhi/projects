<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
 * Rolespermissions Controller
 *
 * @property \App\Model\Table\RolespermissionsTable $Rolespermissions
 */
class RolespermissionsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
		$this->loadModel('Roles');
        $query =$this->Roles->find('list',['keyField'=>'id','valueField'=>'name','conditions'=>['status'=>1]]);
        $query->hydrate(false);
        $roleResult = $query->toArray();
        $this->set('role_result',$roleResult);
		
		$modulesTable = TableRegistry::get('modules');
        $query = $modulesTable->find('list',['keyField'=>'id','valueField'=>'name','conditions'=>['pid'=>0]]);
        $query->hydrate(false);
        $moduleResult = $query->toArray();
        $this->set('module_result',$moduleResult);

		if($this->request->is('post')){	
            if (!empty($this->request->data['chkid'])) {
    		    $rloeId = $this->request->data['roleId'];						
    		    foreach($this->request->data['chkid'] as $key=>$childModule){
    				if(empty($childModule)){
    					$rolepermis = $this->Rolespermissions->find('all',['conditions'=>['role_id'=>$rloeId,'mid'=>$key]])->hydrate(false)->first();
    					if($rolepermis){
        					$querydl = $this->Rolespermissions->query();
        					$querydl->delete()
        					->where(['id' => $rolepermis['id'],'role_id'=>$rloeId])
        					->execute();
    					}
    				}
    				$rolepermis = $this->Rolespermissions->find('all',['conditions'=>['role_id'=>$rloeId,'mid'=>$childModule]])->hydrate(false)->first();    				
    				if($rolepermis){
    					$querydl = $this->Rolespermissions->query();
    					$querydl->delete()
    					->where(['id' => $rolepermis['id'],'role_id'=>$rloeId])
    					->execute();
    				}    				
    				$moduleC = $modulesTable->find('all',['conditions' => ['id' => $childModule],'fields'=>['id','name','pid']])->hydrate(false)->first();
    				$moduleP = $modulesTable->find('all',['conditions' => ['id' => $moduleC['pid']],'fields'=>['id','name']])->hydrate(false)->first();
    				
    				$rolespermission = $this->Rolespermissions->newEntity();
    				$insertData = ['role_id'=>$rloeId, 'mid' => $childModule,'module'=>$moduleP['name'],'moduletask'=>$moduleC['name']];
    				$rolespermission = $this->Rolespermissions->patchEntity($rolespermission, $insertData);
    				
    				$this->Rolespermissions->save($rolespermission);    				
    				$rolepermisModel = TableRegistry::get('Rolespermissions');
    				if(empty($this->request->data['chkid_dshow'][$key])){
    					$query = $rolepermisModel->query();
    					$query->update()
    						->set(['navigationshow' => '0'])
    						->where(['mid' =>$key,'role_id'=>$rloeId])
    						->execute();
    				}else{
    					$query = $rolepermisModel->query();
    					$query->update()
    						->set(['navigationshow' => '1'])
    						->where(['mid' =>$key,'role_id'=>$rloeId])
    						->execute();
    				}	
    			}
    			$this->Flash->success(__('Permissions has been saved'));
            } else {
                $this->Flash->error(__('Select at least one module.'));
            }
		}
		
		$this->loadModel('Modules');
		if($this->request->query){
            if(isset($this->request->query['role'])){
                $this->set('selectedrole',$this->request->query['role']);
            }
            if($this->request->query['module']){
                $this->set('selectedmodule',$this->request->query['module']);
				$modrolpResult = $this->Modules->find('all',['fields'=>['id','name','pid','description'],'conditions'=>['id'=>$this->request->query['module'],'pid'=>0]])->hydrate(false)->toArray();
            }else{
                $modrolpResult = $this->Modules->find('all',['fields'=>['id','name','pid','description'],'conditions'=>['pid'=>0]])->hydrate(false)->toArray();
            }
		  
    		foreach($modrolpResult as $k=>$mod){
    			$child=$this->Modules->find('all',['fields'=>['id','name','description'],'conditions'=>['pid'=>$mod['id']]])->hydrate(false)->toArray();    			
    			foreach($child as $kq=>$v){
                    if($this->checkselected($this->request->query['role'],$v['id'])){
    				   $child[$kq]['selected']='true';
    				}else{
    				   $child[$kq]['selected']='false';
    				}
                    $child[$kq]['navigationshow']=$this->checkselectedDShow($this->request->query['role'],$v['id']);
    			}			
    			$modrolpResult[$k]['child']=$child;
    		}
    		$this->set(compact('modrolpResult'));
		}
    }
    
	function checkselected($rloeId,$childModule){
			$rolepermis = $this->Rolespermissions->find('all',['conditions'=>['role_id'=>$rloeId,'mid'=>$childModule]])->hydrate(false)->count();
			return $rolepermis;
	}
	
	function checkselectedDShow($rloeId,$childModule){
			$rolepermis = $this->Rolespermissions->find('all',['conditions'=>['role_id'=>$rloeId,'mid'=>$childModule]])->hydrate(false)->first();
			return $rolepermis['navigationshow'];
	}
	
    /**
     * View method
     *
     * @param string|null $id Rolespermission id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $rolespermission = $this->Rolespermissions->get($id, [
            'contain' => []
        ]);

        $this->set('rolespermission', $rolespermission);
        $this->set('_serialize', ['rolespermission']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $rolename = isset($this->request->data['rolename'])?$this->request->data['rolename']:'';
        //$modulename = isset($this->request->data['modulename'])?$this->request->data['modulename']:'';
        //$this->set('modulenameId',$modulename);
        $this->set('rolenameId',$rolename);
        
        $rolesTable = TableRegistry::get('user_roles');
        if(!empty($rolename)) {
            $query = $rolesTable->find('all',['conditions'=>['id'=>$rolename,'flag != '=>0]]);
            $query->hydrate(false);
            $rolesResult = $query->toArray();
            $this->set('roles',$rolesResult);
        }
               
        $this->set('roleslistv',$rolesTable->find('all',['conditions'=>['flag'=>'1']])->hydrate(false)->toArray());
        
        $modrolpTable = TableRegistry::get('modules');
        $modrolpQuery = $modrolpTable->find('all');//[
       
        $modrolpQuery->hydrate(false);
        $modrolpResult = $modrolpQuery->toArray();      //debug($modrolpResult);
        $ModRolData = [];
        $l = 0;
        foreach($modrolpResult as $modrolpResultVal) {
            //echo '<pre>'; print_r($modrolpResultVal);echo '</pre>';
            $modid = $modrolpResultVal['id'];
            $rolesPermDataQ = $this->Rolespermissions->find('all',['conditions'=>['mid'=>$modid]]);
            //$rolesPermDataQ = $this->Rolespermissions->find('all',['conditions'=>['mid'=>$modid,'role_id'=>$rolename]]);
            $rolesPermData = $rolesPermDataQ->hydrate(false)->toArray();
            $ModRolData[$l]['module'] = $modrolpResultVal;
            $ModRolData[$l]['module']['rolepermission'] = $rolesPermData;
            $l++;
        }
        //$this->set('modrolp',$ModRolData);
        $modrolp = $ModRolData;
        //debug($modrolp);
        
        //$rpquery = $this->Rolespermissions->find('all');
        $rpquery = $this->Rolespermissions->find('all',['conditions'=>['role_id'=>$rolename]]);
        $rpquery->hydrate(false);//debug($rpquery);debug($rolename);
        $savedPerm = $rpquery->toArray();   //debug($savedPerm);//die();
        //debug($this->request->data);
        //die();
        
        if($this->request->is('post')){
            //debug($this->request->data);
            $rolename1 = isset($this->request->data['rolenamehid'])?$this->request->data['rolenamehid']:'';
            
            //echo ' chkid is : ';
            //debug($this->request->data['chkid']);//die();
            if(!empty($this->request->data['chkid'])){
                $newArrtoPlay = isset($this->request->data['chkid'])?$this->request->data['chkid']:'';
                if(!empty($savedPerm)) {               
                    for($i = 0; $i < count($savedPerm); $i++) {
                        $v1tomatch = $savedPerm[$i];
                        for($j = 0; $j < count($newArrtoPlay); $j++) {
                            if($v1tomatch['role_id'].','.$v1tomatch['mid'] == $newArrtoPlay[$j]) {
                                //echo 'matched';echo '<br>';echo $v1tomatch['role_id'].','.$v1tomatch['mid']. ' is in '.$newArrtoPlay[$j];echo '<br>';
                                $newUpdateArr[$j] = $newArrtoPlay[$j];
                                $newUpdateArr1[$savedPerm[$i]['id']] = $newArrtoPlay[$j];
                                //$newUpdateArr2[$j]['id'] = $savedPerm[$i]['id'];$newUpdateArr2[$j]['role_id'] = $savedPerm[$i]['role_id'];
                                //$newUpdateArr2[$j]['mid'] = $savedPerm[$i]['mid'];$newUpdateArr2[$j]['module'] = $savedPerm[$i]['module'];
                                //$newUpdateArr2[$j]['moduletask'] = $savedPerm[$i]['moduletask'];
                            } 
                        }
                        if(!in_array($savedPerm[$i]['role_id'].','.$savedPerm[$i]['mid'],$newArrtoPlay)){
                            $newDeleteArr[$i] = $savedPerm[$i];
                        }
                    }
                    $newUpdateArr = array_values($newUpdateArr);
                    $newAddArr=array_diff($newArrtoPlay,$newUpdateArr);
                    //echo '<pre>';echo '$newDeleteArr array is : ';print_r($newDeleteArr);echo '</pre>';
                    //die();
                    
                    //die();
                    //////////// for adding, updating and deleting data
                    /*  commented bcz. because there is no use of updation
                        if(!empty($newUpdateArr1)){
                            $ikl = 0;
                            $sizeOfArray = sizeof($newUpdateArr1);
                            foreach ($newUpdateArr1 as $keyid => $value) {
                                $valueArr = explode(',',$value);
                                $vrole_id = $valueArr[0];
                                $vmid = $valueArr[1];
                                $modulesTable = TableRegistry::get('modules');
                                $moduleR = $modulesTable->find('all',['conditions' => ['id' => $vmid],'fields'=>['id','name','pid','description']])->hydrate(false)->toArray();
                                //echo '<pre>';echo '$moduleR table record is : ';print_r($moduleR);echo '</pre>';
                                if($moduleR[0]['pid'] == 0) {
                                    $modulename = $moduleR[0]['name'];
                                } else {
                                    $modulenamev = $modulesTable->find('all',['conditions' => ['id' => $moduleR[0]['pid']],'fields'=>['id','name','pid','description']])->hydrate(false)->toArray();
                                    $modulename = $modulenamev[0]['name'];
                                }
                                $moduleTaskname = $moduleR[0]['name'];     //echo $moduleTaskname; //die();
                                $queryt = $this->Rolespermissions->query();

                                if($ikl == $sizeOfArray-1) {
                                    $queryt->update()
                                    ->set(['role_id' => $vrole_id,'mid' => $vmid,'module' => $modulename,'moduletask' => $moduleTaskname])
                                    ->where(['id' => $keyid])
                                    ->execute();
                                    //$this->Flash->success(__('The rolespermission has been updated.'));
                                    //return $this->redirect($this->referer());
                                } else {
                                    $queryt->update()
                                    ->set(['role_id' => $vrole_id,'mid' => $vmid,'module' => $modulename,'moduletask' => $moduleTaskname])
                                    ->where(['id' => $keyid])
                                    ->execute();
                                }
                                $ikl++;
                            }
                        }
                    */

                    if(!empty($newDeleteArr)) {
                        $ikl = 0;
                        $sizeOfArray = sizeof($newDeleteArr);
                        foreach ($newDeleteArr as $keyd => $value) {
                            $idd = $value['id'];
                            $querydl = $this->Rolespermissions->query();

                            if($ikl == $sizeOfArray-1) {
                                $querydl->delete()
                                ->where(['id' => $idd,'role_id'=>$rolename])
                                ->execute();
                                //$this->Flash->success(__('Permissions has been saved'));
                                $this->set('message',__('Permissions has been saved'));
                                $this->set('rolenameId',$rolename1);
                                //return $this->redirect($this->referer());
                            } else { 
                                $querydl->delete()
                                ->where(['id' => $idd,'role_id'=>$rolename])
                                ->execute();
                            }
                            $ikl++;
                        }
                    }

                    if(!empty($newAddArr)) {
                        //$newAddArr = array_values($newAddArr);
                        $ikl = 0;
                        $sizeOfArray = sizeof($newAddArr);

                        foreach($newAddArr as $keyadd => $value) {
                            $insertData = [];
                            $valueAr = explode(',',$value);
                            $vrolid = $valueAr[0];
                            $vmodid = $valueAr[1];
                            /* for checking if element have to add already exist or not  */
                            $rolepermis = $this->Rolespermissions->find('all',
                                    ['conditions'=>['role_id'=>$vrolid,'mid'=>$vmodid]])->hydrate(false)->toArray();
                            
                            //if(empty($rolepermis)) { //}
                            /* */
                                $rolespermission = $this->Rolespermissions->newEntity();
                                $modulesTable = TableRegistry::get('modules');
                                $moduleR = $modulesTable->find('all',['conditions' => ['id' => $vmodid],'fields'=>['id','name','pid','description']])->hydrate(false)->toArray();
                                if($moduleR[0]['pid'] == 0) {
                                    $modulename = $moduleR[0]['name'];
                                } else {
                                    $modulenamev = $modulesTable->find('all',['conditions' => ['id' => $moduleR[0]['pid']],'fields'=>['id','name','pid','description']])->hydrate(false)->toArray();
                                    $modulename = $modulenamev[0]['name'];
                                }
                                $moduleTaskname = $moduleR[0]['name'];
                                $insertData = ['role_id'=>$vrolid, 'mid' => $vmodid,'module'=>$modulename,'moduletask'=>$moduleTaskname];
                                $rolespermission = $this->Rolespermissions->patchEntity($rolespermission, $insertData);

                                if($ikl == $sizeOfArray-1) {
                                    $this->Rolespermissions->save($rolespermission);
                                    //$this->Flash->success(__('Permissions has been saved'));
                                    $this->set('message',__('Permissions has been saved'));
                                    $this->set('rolenameId',$vrolid);
                                    //return $this->redirect($this->referer());
                                } else {
                                    $this->Rolespermissions->save($rolespermission);
                                }
                            //} else {
                               //echo '$rolepermis is not empty'; 
                            //}
                            $ikl++;
                        } //die();
                    }
                    
                    //////////////////////////////////////
                    $modrolpResult = $modrolpTable->find('all')->hydrate(false)->toArray();     
                    $ModRolData = [];
                    $l = 0;
                    foreach($modrolpResult as $modrolpResultVal) {
                        $modid = $modrolpResultVal['id'];
                        $rolesPermData = $this->Rolespermissions->find('all',['conditions'=>['mid'=>$modid]])->hydrate(false)->toArray();
                        $ModRolData[$l]['module'] = $modrolpResultVal;
                        $ModRolData[$l]['module']['rolepermission'] = $rolesPermData;
                        $l++;
                    }
                    $modrolp = $ModRolData;
                    //////////////////////////////////////
                    $this->set('message',__('Permissions has been saved'));
                } else if(!empty($savedPerm) && empty($newArrtoPlay)) {
                    //echo 'No new data is selected.';
                    $this->set('message',__('No new data is selected.'));
                } else if(empty($savedPerm) && empty($newArrtoPlay)) {
                    //echo 'No new data is selected.';
                    $this->set('message',__('No new data is selected.'));
                } else {
                    //echo 'There is no already saved record.';
                    $this->set('message',__('There is no already saved record.'));
                    $newAddArr = $newArrtoPlay;
                        
                    if(!empty($newAddArr)) {
                        $ikl = 0;
                        $sizeOfArray = sizeof($newAddArr);
                        foreach($newAddArr as $key => $value) {
                            $rolespermission = $this->Rolespermissions->newEntity();
                            $insertData = [];
                            $valueAr = explode(',',$value);
                            $vrolid = $valueAr[0];
                            $vmodid = $valueAr[1];
                            $modulesTable = TableRegistry::get('modules');
                            $moduleR = $modulesTable->find('all',['conditions' => ['id' => $vmodid],'fields'=>['id','name','pid','description']])->hydrate(false)->toArray();
                            if($moduleR[0]['pid'] == 0) {
                                $modulename = $moduleR[0]['name'];
                            } else {
                                $modulenamev = $modulesTable->find('all',['conditions' => ['id' => $moduleR[0]['pid']],'fields'=>['id','name','pid','description']])->hydrate(false)->toArray();
                                $modulename = $modulenamev[0]['name'];
                            }
                            $moduleTaskname = $moduleR[0]['name'];
                            $insertData = ['role_id'=>$vrolid, 'mid' => $vmodid,'module'=>$modulename,'moduletask'=>$moduleTaskname];
                            $rolespermission = $this->Rolespermissions->patchEntity($rolespermission, $insertData);
                            //$this->Rolespermissions->save($rolespermission);
                            if($ikl == $sizeOfArray-1) {
                                $this->Rolespermissions->save($rolespermission);
                                //$this->Flash->success(__('Permissions has been saved'));
                                $this->set('message',__('Permissions has been saved'));
                                $this->set('rolenameId',$vrolid);
                                //return $this->redirect($this->referer());
                            } else {
                                $this->Rolespermissions->save($rolespermission);
                            }
                            $ikl++;
                        }
                    }
                    //return $this->redirect(['action' => 'add']);
                    //////////////////////////////////////
                    $modrolpResult = $modrolpTable->find('all')->hydrate(false)->toArray(); 
                    $ModRolData = [];
                    $l = 0;
                    foreach($modrolpResult as $modrolpResultVal) {
                        $modid = $modrolpResultVal['id'];
                        $rolesPermData = $this->Rolespermissions->find('all',['conditions'=>['mid'=>$modid]])->hydrate(false)->toArray();
                        $ModRolData[$l]['module'] = $modrolpResultVal;
                        $ModRolData[$l]['module']['rolepermission'] = $rolesPermData;
                        $l++;
                    }
                    $modrolp = $ModRolData;
                    //////////////////////////////////////
                }
            } else {
                $querydlq = $this->Rolespermissions->query();
                $querydlq->delete()
                    ->where(['role_id'=>$rolename1])
                    ->execute();
                $modrolpResult = $modrolpTable->find('all')->hydrate(false)->toArray();     //debug($modrolpResult);
                $ModRolData = [];
                $l = 0;
                foreach($modrolpResult as $modrolpResultVal) {
                    $modid = $modrolpResultVal['id'];
                    $rolesPermData = $this->Rolespermissions->find('all',['conditions'=>['mid'=>$modid]])->hydrate(false)->toArray();
                    $ModRolData[$l]['module'] = $modrolpResultVal;
                    $ModRolData[$l]['module']['rolepermission'] = $rolesPermData;
                    $l++;
                }
                $modrolp = $ModRolData;
            }
        } else {
            $modrolpResult = $modrolpTable->find('all')->hydrate(false)->toArray();     //debug($modrolpResult);
            $ModRolData = [];
            $l = 0;
            foreach($modrolpResult as $modrolpResultVal) {
                $modid = $modrolpResultVal['id'];
                $rolesPermData = $this->Rolespermissions->find('all',['conditions'=>['mid'=>$modid]])->hydrate(false)->toArray();
                $ModRolData[$l]['module'] = $modrolpResultVal;
                $ModRolData[$l]['module']['rolepermission'] = $rolesPermData;
                $l++;
            }
            $modrolp = $ModRolData;
        }
        $this->set('rolenameId',$rolename);
        $this->set(compact('modrolp'));
        $this->set('_serialize', ['modrolp']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Rolespermission id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $rolespermission = $this->Rolespermissions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $rolespermission = $this->Rolespermissions->patchEntity($rolespermission, $this->request->data);
            if ($this->Rolespermissions->save($rolespermission)) {
                $this->Flash->success(__('The rolespermission has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The rolespermission could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('rolespermission'));
        $this->set('_serialize', ['rolespermission']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Rolespermission id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $rolespermission = $this->Rolespermissions->get($id);
        if ($this->Rolespermissions->delete($rolespermission)) {
            $this->Flash->success(__('The rolespermission has been deleted.'));
        } else {
            $this->Flash->error(__('The rolespermission could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
