<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * Roles Controller
 *
 * @property \App\Model\Table\RolesTable $Roles
 *
 * @method \App\Model\Entity\Role[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CertificatesController extends AppController
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
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
		$this->loadModel('Certificates');
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
        $data = $this->request->getQuery();
        $data = $this->Sanitize->clean($data);
        $data['title'] = $this->Sanitize->stripAll(isset($data['title'])?$data['title']:'');

        if (!empty($data['title'])) {
            $title = trim($data['title']);
            $this->set('title', $title);
            $search_condition[] = "title like '%" . $title . "%'";
        }
        if (isset($data['state_code']) && $data['state_code'] !='') {
            $state_code = trim($data['state_code']);
            $this->set('state_code', $state_code); 
            $search_condition[] = "state_code = '" . $state_code . "'";
        }
        if (isset($data['district_code']) && $data['district_code'] !='') {
            $district_code = trim($data['district_code']);
            $this->set('district_code', $district_code); 
            $search_condition[] = "district_code = '" . $district_code . "'";
        }
        // if (isset($data['status']) && $data['status'] !='') {
        //     $status = trim($data['status']);
        //     $this->set('status', $status); 
        //     $search_condition[] = "status = '" . $status . "'";
        // }

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


        $state_code = $this->request->session()->read('Auth.User.state_code');
        $district_code = $this->request->session()->read('Auth.User.district_code');

        $search_condition2='';
        //district nodal
         if($this->request->session()->read('Auth.User.role_id') == 8)
        {
             $search_condition2  = "Certificates.district_code = '" . $district_code . "'";
        }

        $search_condition3='';
        //state_nodal
         if($this->request->session()->read('Auth.User.role_id') == 11)
        {
           
             $search_condition3  = "Certificates.state_code = '" . $state_code . "'";
        }

        $certificates = $this->Certificates->find('all', [
            'order' => ['Certificates.id' => 'DESC'],
            //'contain' => ['Users'],
            'conditions' => [$searchString,$search_condition2,$search_condition3,'Certificates.status'=>1,'Certificates.type'=>1,'Certificates.title !='=>'']
        ]);//->toArray()
        
        // echo '<pre>';
        // print_r($certificates);die;

        $this->paginate = ['limit' => 20];
        $certificates = $this->paginate($certificates);

        // echo '<pre>';
        // print_r($certificates);die;
        $this->set('selectedLen', $page_length);
		
        $all_user = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['role_id IN'=>[7,8]])->toarray();


        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('states',$stateOption);

        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();


        $this->set(compact('certificates','pagCatList','all_user','districts'));
    }
    public function stateCertificateList()
    {
		$this->loadModel('Certificates');
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
        $data = $this->request->getQuery();
        $data = $this->Sanitize->clean($data);
        $data['title'] = $this->Sanitize->stripAll(isset($data['title'])?$data['title']:'');

        if (!empty($data['title'])) {
            $title = trim($data['title']);
            $this->set('title', $title);
            $search_condition[] = "title like '%" . $title . "%'";
        }
        // if (isset($data['status']) && $data['status'] !='') {
        //     $status = trim($data['status']);
        //     $this->set('status', $status); 
        //     $search_condition[] = "status = '" . $status . "'";
        // }

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


        $state_code = $this->request->session()->read('Auth.User.state_code');
       
        $district_code = $this->request->session()->read('Auth.User.district_code');

        $search_condition2='';
        //district nodal
         if($this->request->session()->read('Auth.User.role_id') == 8)
        {
             $search_condition2  = "Certificates.district_code = '" . $district_code . "'";
        }

        $search_condition3='';
        //state_nodal
         if($this->request->session()->read('Auth.User.role_id') == 11)
        {
           
             $search_condition3  = "Certificates.state_code = '" . $state_code . "'";
        }

        $certificates = $this->Certificates->find('all', [
            'order' => ['Certificates.id' => 'DESC'],
            //'contain' => ['Users'],
            'conditions' => [$searchString,$search_condition2,$search_condition3,'Certificates.status'=>1,'Certificates.type'=>1,'Certificates.title !='=>'']
        ]);//->toArray()
        
        // echo '<pre>';
        // print_r($certificates);die;

        $this->paginate = ['limit' => 20];
        $certificates = $this->paginate($certificates);

        // echo '<pre>';
        // print_r($certificates);die;
        $this->set('selectedLen', $page_length);
		
        $all_user = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['role_id IN'=>[7,8]])->toarray();


        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('states',$stateOption);
        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();


        $this->set(compact('certificates','pagCatList','all_user','districts'));
    }


    public function indexdistrict()
    {
		$this->loadModel('Certificates');
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
        $data = $this->request->getQuery();
        $data = $this->Sanitize->clean($data);
        $data['title'] = $this->Sanitize->stripAll(isset($data['title'])?$data['title']:'');

        if (!empty($data['title'])) {
            $title = trim($data['title']);
            $this->set('title', $title);
            $search_condition[] = "title like '%" . $title . "%'";
        }
        // if (isset($data['status']) && $data['status'] !='') {
        //     $status = trim($data['status']);
        //     $this->set('status', $status); 
        //     $search_condition[] = "status = '" . $status . "'";
        // }

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


        $state_code = $this->request->session()->read('Auth.User.state_code');
        //$district_code = $this->request->session()->read('Auth.User.district_code');

        $search_condition2='';
        //district nodal
        //  if($this->request->session()->read('Auth.User.role_id') == 8)
        // {
        //      $search_condition2  = "Certificates.district_code = '" . $district_code . "'";
        // }

        $search_condition3='';
        //state_nodal
         if($this->request->session()->read('Auth.User.role_id') == 11)
        {
           
             $search_condition3  = "Certificates.state_code = '" . $state_code . "'";
        }

        $certificates = $this->Certificates->find('all', [
            'order' => ['Certificates.id' => 'DESC'],
            //'contain' => ['Users'],
            'conditions' => [$searchString,$search_condition3,'Certificates.status'=>1,'Certificates.type'=>1,'Certificates.title !='=>'']
        ])->where(['district_code !='=>'NULL']);
        //->toArray();
        
        // echo '<pre>';
        // print_r($certificates);die;

        $this->paginate = ['limit' => 20];
        $certificates = $this->paginate($certificates);

        // echo '<pre>';
        // print_r($certificates);die;
        $this->set('selectedLen', $page_length);
		
        $all_user = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['role_id IN'=>[7,8]])->toarray();


        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('states',$stateOption);
        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();


        $this->set(compact('certificates','pagCatList','all_user','districts'));
    }

    /**
     * View method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $doc_type = $this->Videos->get($id, [
            
        ]);

        $this->set('doc_type', $doc_type);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->loadModel('Certificates');
        $doc_type = $this->Certificates->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if(empty($data['certificate_pdf']['name']))
            {
                $this->Flash->error(__('Sorry unable to submit'));
                $this->redirect($this->referer());
            }

            $state_code = $this->request->session()->read('Auth.User.state_code');
            $district_code = $this->request->session()->read('Auth.User.district_code');

            $ext = pathinfo($data['certificate_pdf']['name'], PATHINFO_EXTENSION);

            $data['certificate_pdf']['name'] = $state_code.'_'.$district_code.'_'.date('Y_m_d_H_i_s').'.'.$ext;

            //echo $data['certificate_pdf']['name'];die;

            if($data['certificate_pdf']['name']!=''){
                $fileName = $this->uploadImage('certificates', $data['certificate_pdf']);
                $data['title']  = $fileName['filename'];//echo "<pre>"; print_r($fileName); exit;
            }

            $data['created_by'] = $this->request->session()->read('Auth.User.id');
            $data['created'] = date('Y-m-d H:i:s');
            $data['state_code'] = $state_code;
            $data['district_code'] = $district_code;
            $data['type'] = 1;
            
			//echo "<pre>"; print_r($data); exit;
            $data = $this->Sanitize->clean($data);
            $doc_type = $this->Certificates->patchEntity($doc_type, $data);
            
            
            //echo "<pre>"; print_r($doc_type); exit;
            if ($this->Certificates->save($doc_type)) {
                $this->Flash->success(__('The Certificate has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Certificate could not be saved. Please, try again.'));
        }
        $this->set(compact('doc_type'));
    }

    public function stateAdd()
    {
		$this->loadModel('Certificates');
        $doc_type = $this->Certificates->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if(empty($data['certificate_pdf']['name']))
            {
                $this->Flash->error(__('Sorry unable to submit'));
                $this->redirect($this->referer());
            }

            $state_code = $this->request->session()->read('Auth.User.state_code');
            
            //$district_code = $this->request->session()->read('Auth.User.district_code');

            $ext = pathinfo($data['certificate_pdf']['name'], PATHINFO_EXTENSION);

            $data['certificate_pdf']['name'] = $state_code.'_'.date('Y_m_d_H_i_s').'.'.$ext;

            //echo $data['certificate_pdf']['name'];die;

            if($data['certificate_pdf']['name']!=''){
                $fileName = $this->uploadImage('certificates', $data['certificate_pdf']);
                $data['title']  = $fileName['filename'];//echo "<pre>"; print_r($fileName); exit;
            }

            $data['created_by'] = $this->request->session()->read('Auth.User.id');
            $data['created'] = date('Y-m-d H:i:s');
            $data['state_code'] = $state_code;
            $data['type'] = 1;
            //$data['district_code'] = $district_code;
            
			//echo "<pre>"; print_r($data); exit;
            $data = $this->Sanitize->clean($data);
            $doc_type = $this->Certificates->patchEntity($doc_type, $data);
            
            
            //echo "<pre>"; print_r($doc_type); exit;
            if ($this->Certificates->save($doc_type)) {
                $this->Flash->success(__('The Certificate has been saved.'));

                return $this->redirect(['action' => 'indexstate']);
            }
            $this->Flash->error(__('The Certificate could not be saved. Please, try again.'));
        }
        $this->set(compact('doc_type'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {   //echo $id;die;
        $doc_type = $this->Certificates->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data  = $this->request->getData();
            $data['updated_by'] = $this->request->session()->read('Auth.User.id');
            $data['updated'] = date('Y-m-d H:i:s');

            $data       = $this->Sanitize->clean($data);
            $doc_type = $this->Certificates->patchEntity($doc_type, $data);
            // if($data['blog_image']['name']!=''){
            //     $fileName = $this->uploadImage('blogs', $data['blog_image']);
            //     $doc_type->blog_image  = $fileName['filename'];
            // }
			$modi = date("d-m-y h:i:s");
			$doc_type->modified = $modi;//echo '<pre>'; print_r($doc_type);die;
            if ($this->Certificates->save($doc_type)) {
                $this->Flash->success(__('The Certificate has been saved.'));
                //echo 'save';die;
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Certificates could not be saved. Please, try again.'));
        }
        $this->set(compact('doc_type'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post']);
        $certificate = $this->Certificates->get($id);
        $data['status'] = 0;
        $data['updated_by'] = $this->request->session()->read('Auth.User.id');
        $data['updated'] = date('Y-m-d H:i:s');
        $certificate = $this->Certificates->patchEntity($certificate, $data);
        if ($this->Certificates->save($certificate)) {
            $this->Flash->success(__('The Certificate has been deleted.'));
        } else {
            $this->Flash->error(__('The Certificate could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function certificate()
    {
        
        $this->loadModel('Users');
       $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('DistrictNodalEntries');
        $this->loadModel('CooperativeRegistrations');
        
        $pacs_graph = [];
        $dairy_graph = [];
        $fishery_graph = [];
        $nodal_entry_id = '';
        $certificate_districts = [];

        $role_id =  $this->Auth->user('role_id');
        $state_code = $this->request->session()->read('Auth.User.state_code');
        $district_code = $this->request->session()->read('Auth.User.district_code');

        //echo $state_code;
        if($role_id == 8)
        {
            //dstrict
            $search_condition['state_code'] = $state_code;
            $s_state = $state_code;
            $this->set('s_state', $s_state);
            $search_condition['district_code'] = $district_code;
            $s_district = $district_code;
            $this->set('s_district', $s_district);

            $nodal_entry_id = $this->DistrictNodalEntries->find('all', [
                'order' => ['district_nodal_name'=>'ASC'],
                'conditions' => ['state_code'=>$state_code,'district_code'=>$district_code]
            ])->first()->id;


        } else {
            $search_condition['state_code'] = 0;
        }

        $all_district_nodal = $this->DistrictNodalEntries->find('all')->where($search_condition)->toarray();

        $district_nodal_tatal =[];
        $district_nodal_tatal_without_state =[];

        foreach($all_district_nodal as $key=>$value)
        {
                
                $district_nodal_total ['pacs'][$value['state_code']][]           =   $value['pacs_count'];
                $district_nodal_total['dairy'] [$value['state_code']] []         =   $value['dairy_count'];
                $district_nodal_total['fisfhery'][$value['state_code']] []       =   $value['fishery_count'];

                $district_nodal_total_without_state['pacs'][]           =   $value['pacs_count'];
                $district_nodal_total_without_state['dairy'][]         =   $value['dairy_count'];
                $district_nodal_total_without_state['fishery'][]       =   $value['fishery_count'];
        }

        //Pacs

        //====================================================================================================

        

        //====================================================================================================

        $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);            
        $pacs = $pacs->select(['state_code','count' => $pacs->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [1,59,20,22],'is_draft'=>0, $search_condition])->group(['state_code'])->toArray();


        //Dairies
        $dairies = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);            
        $dairies = $dairies->select(['state_code','count' => $dairies->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [9,37], 'is_draft'=>0, $search_condition])->group(['state_code'])->toArray();

        
        $fisheries = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);

        //fishery
        $fisheries = $fisheries->select(['state_code','count' => $fisheries->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [10,43],'is_draft'=>0, $search_condition])->group(['state_code'])->toArray();
     

        $pacs_data['total_record'] = array_sum($district_nodal_total_without_state['pacs']) ?? 0;

        $$nodal_data_entry_ids = [];

        
        //for district nodal 
        if($role_id == 8)
        {
            // if($this->request->session()->read('Auth.User.primary_act_code') == 1)
            // {
            //     $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code'),'created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();//,,'district_code'=>$this->request->session()->read('Auth.User.district_code')
            // } else {
            //     $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'created_by'=>$this->request->session()->read('Auth.User.id'),'state_code'=>$this->request->session()->read('Auth.User.state_code')])->toArray();//,'district_code'=>$this->request->session()->read('Auth.User.district_code')
            // }
            
            $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code'),'created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();

            $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
        }

        // echo '<pre>';
        // print_r($nodal_data_entry_ids);die;
        
        // if(!empty($nodal_data_entry_ids)){
        // {
        //     $certificate_districts = $this->$CooperativeRegistrations->select(['district_code'])->where(['created_by IN' => $nodal_data_entry_ids,'is_draft'=>0, 'status'=>1,'is_approved'=>1])->group(['district_code'])->toArray();
        // } else {
        //     $certificate_districts = $CooperativeRegistrations->select(['district_code'])->where(['created_by IN' => 0,'is_draft'=>0, 'status'=>1,'is_approved'=>1])->group(['district_code'])->toArray();
        // }
        
        // echo '<pre>';
        // print_r($certificate_districts);die;
        
        
        //for state nodal
        if($role_id == 11)
        {
            $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')])->toArray();

            $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
        }

        $pacs_data['total_data_entered'] = $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => ['is_draft'=>0,'is_approved'=>1,'status'=>1,'sector_of_operation IN' => [1,59,20,22],'state_code'=>$this->request->session()->read('Auth.User.state_code')]
        ]);

        if(!empty($nodal_data_entry_ids)){
			$pacs_data['total_data_entered'] = $pacs_data['total_data_entered']->where(['created_by IN'=>$nodal_data_entry_ids]);
        }else {
            $pacs_data['total_data_entered'] = $pacs_data['total_data_entered']->where(['created_by IN'=>0]);
        }

        $pacs_data['total_data_entered'] = $pacs_data['total_data_entered']->count();
        
        //,'created_by IN'=>$nodal_data_entry_ids
        //'operational_district_code'=>$this->request->session()->read('Auth.User.district_code')
        

        $dairy_data['total_record'] = array_sum($district_nodal_total_without_state['dairy']) ?? 0;
        //$dairy_graph['data_entered_today'] = array_sum($dairies_today) ?? 0;
        $dairy_data['total_data_entered'] = $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => ['is_draft'=>0,'is_approved'=>1,'status'=>1,'sector_of_operation IN' => [9,37],'state_code'=>$this->request->session()->read('Auth.User.state_code'),'operational_district_code'=>$this->request->session()->read('Auth.User.district_code')]
        ]);

        if(!empty($nodal_data_entry_ids)){
			$dairy_data['total_data_entered'] = $dairy_data['total_data_entered']->where(['created_by IN'=>$nodal_data_entry_ids]);
        }else {
            $dairy_data['total_data_entered'] = $dairy_data['total_data_entered']->where(['created_by IN'=>0]);
        }

        $dairy_data['total_data_entered'] = $dairy_data['total_data_entered']->count();

        

        $fishery_data['total_record'] = array_sum($district_nodal_total_without_state['fishery']) ?? 0;
        //$fishery_graph['data_entered_today'] = array_sum($fisheries_today) ?? 0;
        $fishery_data['total_data_entered'] = $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => ['is_draft'=>0,'is_approved'=>1,'status'=>1,'sector_of_operation IN' => [10,43],'state_code'=>$this->request->session()->read('Auth.User.state_code'),'operational_district_code'=>$this->request->session()->read('Auth.User.district_code')]
        ]);

        if(!empty($nodal_data_entry_ids)){
			$fishery_data['total_data_entered'] = $fishery_data['total_data_entered']->where(['created_by IN'=>$nodal_data_entry_ids]);
        }else {
            $fishery_data['total_data_entered'] = $fishery_data['total_data_entered']->where(['created_by IN'=>0]);
        }
        $fishery_data['total_data_entered'] = $fishery_data['total_data_entered']->count();

        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('states',$stateOption);

        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

        $this->set(compact('pacs_data','dairy_data','fishery_data','districts','nodal_entry_id'));

    }


    public function stateCertificate()
    {
        
        $this->loadModel('Users');
       $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('DistrictNodalEntries');
        $this->loadModel('CooperativeRegistrations');
        
        $pacs_graph = [];
        $dairy_graph = [];
        $fishery_graph = [];

        $role_id =  $this->Auth->user('role_id');
        $state_code = $this->request->session()->read('Auth.User.state_code');
        $district_code = $this->request->session()->read('Auth.User.district_code');

        //echo $state_code;
        if($role_id == 8)
        {
            //dstrict
            $search_condition['state_code'] = $state_code;
            $s_state = $state_code;
            $this->set('s_state', $s_state);
            $search_condition['district_code'] = $district_code;
            $s_district = $district_code;
            $this->set('s_district', $s_district);
        } else {
            //state
            $search_condition['state_code'] = $state_code;
            $s_state = $state_code;
            $this->set('s_state', $s_state);
        }

        $all_district_nodal = $this->DistrictNodalEntries->find('all')->where($search_condition)->toarray();

        $district_nodal_tatal =[];
        $district_nodal_tatal_without_state =[];

        foreach($all_district_nodal as $key=>$value)
        {
                
                $district_nodal_total ['pacs'][$value['state_code']][]           =   $value['pacs_count'];
                $district_nodal_total['dairy'] [$value['state_code']] []         =   $value['dairy_count'];
                $district_nodal_total['fisfhery'][$value['state_code']] []       =   $value['fishery_count'];

                $district_nodal_total_without_state['pacs'][]           =   $value['pacs_count'];
                $district_nodal_total_without_state['dairy'][]         =   $value['dairy_count'];
                $district_nodal_total_without_state['fishery'][]       =   $value['fishery_count'];
        }

        //Pacs

        //====================================================================================================

        

        //====================================================================================================

        $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);            
        $pacs = $pacs->select(['state_code','count' => $pacs->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [1,59,20,22],'is_draft'=>0, $search_condition])->group(['state_code'])->toArray();


        //Dairies
        $dairies = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);            
        $dairies = $dairies->select(['state_code','count' => $dairies->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [9,37], 'is_draft'=>0, $search_condition])->group(['state_code'])->toArray();

        
        $fisheries = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);

        //fishery
        $fisheries = $fisheries->select(['state_code','count' => $fisheries->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [10,43],'is_draft'=>0, $search_condition])->group(['state_code'])->toArray();
     

        $pacs_data['total_record'] = array_sum($district_nodal_total_without_state['pacs']) ?? 0;

        $$nodal_data_entry_ids = [];

        //for district nodal 
        if($role_id == 8)
        {
            if($this->request->session()->read('Auth.User.primary_act_code') == 1)
            {
                $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code'),'district_code'=>$this->request->session()->read('Auth.User.district_code')])->toArray();//,'created_by'=>$this->request->session()->read('Auth.User.id')
            } else {
                $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'created_by'=>$this->request->session()->read('Auth.User.id'),'state_code'=>$this->request->session()->read('Auth.User.state_code'),'district_code'=>$this->request->session()->read('Auth.User.district_code')])->toArray();//
            }
            

            $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
        }
        
        //for state nodal
        if($role_id == 11)
        {
            $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')])->toArray();

            $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
        }

        $pacs_data['total_data_entered'] = $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => ['is_draft'=>0,'is_approved'=>1,'status'=>1,'sector_of_operation IN' => [1,59,20,22],'created_by IN'=>$nodal_data_entry_ids]
        ])->count();

        

        $dairy_data['total_record'] = array_sum($district_nodal_total_without_state['dairy']) ?? 0;
        //$dairy_graph['data_entered_today'] = array_sum($dairies_today) ?? 0;
        $dairy_data['total_data_entered'] = $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => ['created_by IN'=>$nodal_data_entry_ids,'is_draft'=>0,'is_approved'=>1,'status'=>1,'sector_of_operation IN' => [9,37]]
        ])->count();

        

        $fishery_data['total_record'] = array_sum($district_nodal_total_without_state['fishery']) ?? 0;
        //$fishery_graph['data_entered_today'] = array_sum($fisheries_today) ?? 0;
        $fishery_data['total_data_entered'] = $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => ['created_by IN'=>$nodal_data_entry_ids,'is_draft'=>0,'is_approved'=>1,'status'=>1,'sector_of_operation IN' => [10,43]]
        ])->count();

        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('states',$stateOption);

        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

        $this->set(compact('pacs_data','dairy_data','fishery_data','districts'));

    }

    public function download($title)
    {
        $file_path = WWW_ROOT.'files'.DS.'certificates'.DS.$title;
        
        $this->response->file($file_path, array(
        'download' => true,
        'name' => $title,
        ));
        return $this->response;
    }


    public function stateCerticateList()
    {
        $this->loadModel('Certificates');
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
        $data = $this->request->getQuery();
        $data = $this->Sanitize->clean($data);
        $data['title'] = $this->Sanitize->stripAll(isset($data['title'])?$data['title']:'');

        if (!empty($data['title'])) {
            $title = trim($data['title']);
            $this->set('title', $title);
            $search_condition[] = "title like '%" . $title . "%'";
        }
        // if (isset($data['status']) && $data['status'] !='') {
        //     $status = trim($data['status']);
        //     $this->set('status', $status); 
        //     $search_condition[] = "status = '" . $status . "'";
        // }

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


        $state_code = $this->request->session()->read('Auth.User.state_code');
        $district_code = $this->request->session()->read('Auth.User.district_code');

        $search_condition2='';
        //district nodal
         if($this->request->session()->read('Auth.User.role_id') == 8)
        {
             $search_condition2  = "Certificates.district_code = '" . $district_code . "'";
        }

        $search_condition3='';
        //state_nodal
         if($this->request->session()->read('Auth.User.role_id') == 11)
        {
           
             $search_condition3  = "Certificates.state_code = '" . $state_code . "'";
        }

        $certificates = $this->Certificates->find('all', [
            'order' => ['Certificates.id' => 'DESC'],
            //'contain' => ['Users'],
            'conditions' => [$searchString,$search_condition2,$search_condition3,'Certificates.type'=>1,'Certificates.status'=>1]
        ]);//->toArray()
        
        // echo '<pre>';
        // print_r($certificates);die;

        $this->paginate = ['limit' => 20];
        $certificates = $this->paginate($certificates);

        // echo '<pre>';
        // print_r($certificates);die;
        $this->set('selectedLen', $page_length);
		
        $all_user = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['role_id IN'=>[7,8]])->toarray();


        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('states',$stateOption);

        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();


        $this->set(compact('certificates','pagCatList','all_user','districts'));
    }

    public function districtCerticateList()
    {

    }
    public function getDistricts(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $state_code=$this->request->data('state_code');    
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
