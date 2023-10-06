<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * SecondaryActivities Controller
 *
 * @property \App\Model\Table\SecondaryActivitiesTable $SecondaryActivities
 *
 * @method \App\Model\Entity\SecondaryActivities[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FederationController extends AppController
{
    
    public function initialize()
    {
        parent::initialize();

        $this->loadMOdel('Federation');
        $this->loadMOdel('States');
        $this->loadMOdel('Districts');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {


        $search_condition = array();

       // $data = $this->request->getData();

        if(isset($this->request->query['page_length'])){
            $page_length = $this->request->query['page_length'];
        } else {
            $page_length = 20;
        }

        if(!empty($this->request->query['page'])){
            $this->set('page', $this->request->query['page']);
            $page = $this->request->query['page'];
        }else{
            $page = 1;
        }

         if(!empty($this->request->query['federation_name'])) {
            $s_name = trim($this->request->query['federation_name']);
            $this->set('s_name', $s_name);
            $search_condition[] = "federation_name like '%" . $s_name . "%'";
        }

        if (!empty($this->request->query['president_name'])) {
            $president_name = trim($this->request->query['president_name']);
            $this->set('president_name', $president_name);
            $search_condition[] = "president_name like '%" . $president_name . "%'";
        }

        if (!empty($this->request->query['registration_number'])) {
            $registration_number = trim($this->request->query['registration_number']);
            $this->set('registration_number', $registration_number);
            $search_condition[] = "registration_number like '%" . $registration_number . "%'";
        }

        if (!empty($this->request->query['state_code']) ) {
            $state_code = trim($this->request->query['state_code']);
            $this->set('state_code', $state_code);
            $search_condition[] = "state_code = '" . $state_code . "'";

            $districtlist=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();

            $this->set(compact('districtlist'));
        }

        if (!empty($this->request->query['district_code'])) {
            $district_code = trim($this->request->query['district_code']);

            $districtlist=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
            $this->set(compact('districtlist'));

            $this->set('district_code', $district_code);
            $search_condition[] = "district_code  = '" . $district_code . "'";;
        }

        if ($this->request->query['status'] !='' ) {
            $s_status = trim($this->request->query['status']);
            $this->set('s_status', $s_status);
            $search_condition[] = "status = '" . $s_status . "'";
        }

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }
        

        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }

        $auditcategories = $this->Federation->find('all', [
            'order' => ['created_at'=>'DESC'],
            'conditions' => [$searchString]
        ]);

      // print_r($auditcategories);
        
        //->find('All')->order(['sector_of_operation'=>'ASC'])->order(['orderseq'=>'ASC']);

        $this->paginate = ['limit' => 20];

        $auditcategories = $this->paginate($auditcategories);

        $status = ['1'=>'Active','0'=>'Inactive'];

        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
        $this->set(compact('auditcategories','status','districts','states'));
    }

    /**
     * View method
     *
     * @param string|null $id SecondaryActivities id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        /*$designation = $this->Designations->get($id, [
            'contain' => ['Departments']
        ]);*/
        $acategories = $this->Federation->get($id);



        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();

        $this->set(compact('districts','states'));

        $this->set('acategories', $acategories);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $acategories = $this->Federation->newEntity();

        if ($this->request->is('post')) {

            $data=$this->request->getData();

            $data['created_at']=date('Y-m-d H:i:s');

            $acategories = $this->Federation->patchEntity($acategories,  $data);

            if ($this->Federation->save($acategories)) {
                $this->Flash->success(__('The Federation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Federation could not be saved. Please, try again.'));
        }
        
        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);

        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);


        $this->set(compact('acategories','states','districts'));
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
        $acategories = $this->Federation->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data=$this->request->getData();
            $data['updated_at'] = date('Y-m-d H:i:s');

            $acategories = $this->Federation->patchEntity($acategories, $data);
            if ($this->Federation->save($acategories)) {
                $this->Flash->success(__('The Federation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Federation could not be saved. Please, try again.'));
        }

        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$acategories['state_code']])->toarray();
        
        $this->set(compact('acategories','states','districts'));
    }

    /**
     * Delete method
     *
     * @param string|null $id SecondaryActivities id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $acategories = $this->Federation->get($id);
        if ($this->Federation->delete($acategories)) {
            $this->Flash->success(__('The Federation has been deleted.'));
        } else {
            $this->Flash->error(__('The Federation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getDistricts(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
           $state_code=$this->request->data('state_code');  
           // $multiple=$this->request->data('multiple');    
            $this->loadMOdel('Districts');
            $Districts=$this->Districts->find('list',['keyFields'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']);            
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
