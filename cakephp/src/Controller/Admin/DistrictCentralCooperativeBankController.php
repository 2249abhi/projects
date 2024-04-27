<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\I18n\Time;

/**
 * SecondaryActivities Controller
 *
 * @property \App\Model\Table\DairiesTable $SecondaryActivities
 *
 * @method \App\Model\Entity\SecondaryActivities[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DistrictCentralCooperativeBankController extends AppController
{
    
    public function initialize()
    {
        parent::initialize();

        $this->loadMOdel('DistrictCentralCooperativeBank');
        $this->loadMOdel('States');
        $this->loadMOdel('Districts');

        $this->Auth->allow(['import']);
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

        $data = $this->request->getData();

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

         if(!empty($this->request->query['dccb_name'])) {
            $dccb_name = trim($this->request->query['dccb_name']);

            echo $dccb_name;
            $this->set('dccb_name', $dccb_name);
            $search_condition[] = "dccb_name like '%" . $dccb_name . "%'";
        }

       
        if (!empty($this->request->query['branch_code'])) {
            $branch_code = $this->request->query['branch_code'];
            $this->set('branch_code', $branch_code);
            $search_condition[] = "branch_code like '%" . $branch_code . "%'";
        }

        if (!empty($this->request->query['state_code']) ) {
            $state_code = $this->request->query['state_code'];
            $this->set('state_code', $state_code);
            $search_condition[] = "state_code = '" . $state_code . "'";

            $districtlist=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();

            $this->set(compact('districtlist'));
        }

        if (!empty($this->request->query['district_code'])) {
            $district_code = trim(!empty($this->request->query['district_code']));

            $districtlist=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
            $this->set(compact('districtlist'));

            $this->set('district_code', $district_code);
            $search_condition[] = "district_code = '" . $district_code . "'";
        }

        if ($this->request->query['status']!='') {
            $s_status = trim($this->request->query['status']);
            $this->set('s_status', $s_status);
            $search_condition[] = "status = '" . $s_status . "'";
        }
		if ($this->request->query['entity_type']!='') {
            $entity_type = trim($this->request->query['entity_type']);
            $this->set('entity_type', $entity_type);
            $search_condition[] = "entity_type = '" . $entity_type . "'";
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

      
        
        $auditcategories = $this->DistrictCentralCooperativeBank->find('all', [
            'order' => ['created_at'=>'DESC'],
            'conditions' => [$searchString]
        ]);
        
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
        $acategories = $this->DistrictCentralCooperativeBank->get($id);



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
        $acategories = $this->DistrictCentralCooperativeBank->newEntity();

        if ($this->request->is('post')) {

            $data=$this->request->getData();        
            $data['created_at']= date('Y-m-d H:i:s');    

            $acategories = $this->DistrictCentralCooperativeBank->patchEntity($acategories,  $data);

            if ($this->DistrictCentralCooperativeBank->save($acategories)) {
                $this->Flash->success(__('The District Central Cooperative Bank has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The District Central Cooperative Bank could not be saved. Please, try again.'));
        }
        
        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);

        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
		$this->loadMOdel('PrimaryActivities');
		$pactivities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $this->set(compact('acategories','states','districts','pactivities'));
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
        $acategories = $this->DistrictCentralCooperativeBank->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data=$this->request->getData();
            $data['updated_at'] = date('Y-m-d H:i:s');
          
            $acategories = $this->DistrictCentralCooperativeBank->patchEntity($acategories, $data);
            if ($this->DistrictCentralCooperativeBank->save($acategories)) {
                $this->Flash->success(__('The District Central Cooperative Bank has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The District Central Cooperative Bank could not be saved. Please, try again.'));
        }

        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$acategories['state_code']])->toarray();
        $this->loadMOdel('PrimaryActivities');
		$pactivities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set(compact('acategories','states','districts','pactivities'));
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
        $acategories = $this->DistrictCentralCooperativeBank->get($id);
        if ($this->DistrictCentralCooperativeBank->delete($acategories)) {
            $this->Flash->success(__('The District Central Cooperative Bank has been deleted.'));
        } else {
            $this->Flash->error(__('The District Central Cooperative Bank could not be deleted. Please, try again.'));
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


	public function import() {
   
            

        if($this->request->is('post')){
          
            $data       = $this->request->getData();
            if($data['csv']){

           
                $data = $this->request->data['csv'];
                $file = $data['tmp_name'];
                $handle = fopen($file, "r");
               
                print_r($handle);
                die;

                
                while (($row = fgetcsv($handle, 10000, ",")) !== FALSE) {
                if($row[0] == 'id') {
                continue;
                }

                // $Applicants = $this->Applicants->get($row[0]);
                // $columns = [
                // 'written_mark' => $row[1],
                // 'written_comments' => $row[2],
                // 'viva_mark' => $row[3],
                // 'viva_comments' => $row[4]
                // ];
                // $Applicant = $this->Applicants->patchEntity($Applicants, $columns);
                // $this->Applicants->save($Applicant);
                }
            }
        }
      //  $this->render(FALSE);

		
	}

}
