<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * CooperativeSocietyTypes Controller
 *
 * @property \App\Model\Table\CooperativeSocietyTypesTable $CooperativeSocietyTypes
 *
 * @method \App\Model\Entity\CooperativeSocietyTypes[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DistrictNodalEntriesAdminController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadMOdel('DistrictNodalEntries');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['getDistricts']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->loadModel('Districts');
        $this->loadModel('States');
          //$state_code = $this->request->session()->read('Auth.User.state_code');

          //  $role_id= $this->request->session()->read('Auth.User.role_id');

        if (isset($this->request->query['state_code']) && $this->request->query['state_code'] !='') {
            $state_code = trim($this->request->query['state_code']);
            $this->set('state_code', $state_code);
            $search_condition[] = "state_code ='" . $state_code . "'";
        }

        if (isset($this->request->query['district_nodal_name']) && $this->request->query['district_nodal_name'] !='') {
            $district_nodal_name = trim($this->request->query['district_nodal_name']);
            $this->set('district_nodal_name', $district_nodal_name);
         
            $search_condition[] = "district_nodal_name like '%" . $district_nodal_name . "%'";
        }

        
        if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
            $district_code = trim($this->request->query['district_code']);
            $this->set('district_code', $district_code);
            $search_condition[] = "district_code = '" . $district_code . "'";
        }
		
		$state_code = $this->request->session()->read('Auth.User.state_code');
		if($state_code!=''){
			 $search_condition[] = "state_code = '" . $state_code . "'";
		 }
		 
        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }        
		 
		 
           $nentries = $this->DistrictNodalEntries->find('all', [
                'order' => ['district_nodal_name'=>'ASC'],
                'conditions' => [$searchString]
            ]);       
        
        //->find('All')->order(['sector_of_operation'=>'ASC'])->order(['orderseq'=>'ASC']);

        $this->paginate = ['limit' => 20];

        $nentries = $this->paginate($nentries);

       // $state_code = $this->request->session()->read('Auth.User.state_code');

        
            if($state_code!='')
            {
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
            }
            else{
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
            }
        

         $state=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();


        

        $this->set(compact('nentries','districts','state'));
    }

    /**
     * View method
     *
     * @param string|null $id CooperativeSocietyTypes id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->loadModel('Districts');
        $this->loadModel('States');
        /*$designation = $this->Designations->get($id, [
            'contain' => ['Departments']
        ]);*/
        $nentry = $this->DistrictNodalEntries->get($id);

        $district = $this->Districts->find('all')->where(['id' => $nentry->district_code])->first();

        $state = $this->States->find('all')->where(['id' => $nentry->state_code])->first();
        $state_name = $state->name;
        $district_name = $district->name;


        $this->set(compact('nentry','district_name','state_name'));

    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('Districts');
        $this->loadModel('States');

        $nentry = $this->DistrictNodalEntries->newEntity();
       // $state_code = $this->request->session()->read('Auth.User.state_code');
       // $district_code = $this->request->session()->read('Auth.User.district_code');
        if ($this->request->is('post')) {

            $data = $this->request->getData();

            $exists = $this->DistrictNodalEntries->exists(['district_code' => $data['district_code']]);
            if ($exists == true) {
                $this->Flash->error(__('Nodal Entry Already exist.'));
                return $this->redirect($this->referer());
            }
                        
            $data['created_at'] = date("Y-m-d H:i:s");          
           // $data['state_code'] = $state_code;
            $data['created_by'] = $this->request->session()->read('Auth.User.id');

            $nentry = $this->DistrictNodalEntries->patchEntity($nentry, $data);



            if ($this->DistrictNodalEntries->save($nentry)) {
                $this->Flash->success(__('The Nodal Entry has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Nodal Entry could not be saved. Please, try again.'));
        }

            if($district_code!='')
            {
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code,'district_code'=>$district_code]);
            }else{
                 $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code]);
            }

            $state=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);


        $uname = $this->request->session()->read('Auth.User.name');
        $this->set(compact('nentry','districts','uname','district_code','state'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Designation id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('Districts');
        $this->loadModel('States');

       // $state_code = $this->request->session()->read('Auth.User.state_code');
       // $district_code = $this->request->session()->read('Auth.User.district_code');

       $editdata= $this->DistrictNodalEntries->get($id);
        if($editdata['state_code'] !='')
        {
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$editdata['state_code']]);
        }

        $nentry = $this->DistrictNodalEntries->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();

            $exists = $this->DistrictNodalEntries->exists(['district_code' => $data['district_code'],'id !='=>$id]);
            if ($exists == true) {
                $this->Flash->error(__('Nodal Entry Already exist.'));
                return $this->redirect($this->referer());
            }
            $data['updated_by']=$this->request->session()->read('Auth.User.id');

            $nentry = $this->DistrictNodalEntries->patchEntity($nentry, $data);
            if ($this->DistrictNodalEntries->save($nentry)) {
                $this->Flash->success(__('The Nodal Entry has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Nodal Entry could not be saved. Please, try again.'));
        }


       

        $state=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
        
        $this->set(compact('nentry','districts','district_code','state'));
    }

    /**
     * Delete method
     *
     * @param string|null $id CooperativeSocietyTypes id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $nentry = $this->DistrictNodalEntries->get($id);
        if ($this->DistrictNodalEntries->delete($nentry)) {
            $this->Flash->success(__('The Nodal Entry has been deleted.'));
        } else {
            $this->Flash->error(__('The Nodal Entry could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    
    public function getDistricsNodal()
    {

        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $district_code=$this->request->data('district_code');    
            $this->loadMOdel('Districts');            
            $this->loadMOdel('Users');

         

        $users=$this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'district_code'=>$district_code,'role_id'=>'8'])->order(['name'=>'ASC'])->toArray();

      

          //  $Districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']);
            $option_html='<option value="">Select</option>';
            if(count($users) > 0 ){
                foreach($users as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.$value.'</option>';
                }
            }
            echo $option_html;
        }
        exit;

    }
    public function getDistricts(){
      
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
             $state_code=$this->request->query('state_code');           
          
            $this->loadMOdel('Districts');
            $Districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']);
            $option_html='<option value="">Select</option>';
            if($Districts->count()>0){
                foreach($Districts as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.$value.'</option>';
                }
            }
            echo $option_html;
        }
        exit;
    }

    
}
