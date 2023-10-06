<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * Roles Controller
 *
 * @property \App\Model\Table\UsersTable $Roles
 *
 * @method \App\Model\Entity\Role[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PaymentController extends AppController
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
        $this->loadMOdel('PrimaryActivities');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadModel('States');
        $this->loadModel('Users');
        $this->loadModel('Roles');
        $this->loadModel('Districts');
        $search_condition = array();

        if(!empty($this->request->query['name'])) {
            $name = trim($this->request->query['name']);
            $this->set('name', $name);
            $search_condition[] = "Users.name like '%" . $name. "%'";
        }
		 if(!empty($this->request->query['email'])) {
            $email = trim($this->request->query['email']);
            $this->set('email', $email);
            $search_condition[] = "Users.email like '%" . $email. "%'";
        }

        if($this->request->query['status'] != '') {
            $statusv = trim($this->request->query['status']);
            $this->set('statusv', $statusv);
            $search_condition[] = "Users.status=$statusv";

            //print_r($search_condition);
        }

        if(!empty($this->request->query['role_id'])) {
            $role_id = trim($this->request->query['role_id']);
            $this->set('role_id', $role_id);
            $search_condition[] = "Users.role_id=$role_id";
        }

        if(!empty($this->request->query['primary_act_code'])) {
            $primary_act_code = trim($this->request->query['primary_act_code']);
            $this->set('primary_act_code', $primary_act_code);
            $search_condition[] = "Users.primary_act_code=$primary_act_code";
        }

        if(!empty($this->request->query['state_code'])) {
            $state_code = trim($this->request->query['state_code']);
            $this->set('state_code', $state_code);
            $search_condition[] = "Users.state_code=$state_code";

            $districtlist=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();

             $this->set(compact('districtlist'));
        }



          if(!empty($this->request->query['district_code'])) {
            $district_code = trim($this->request->query['district_code']);

              $districtlist=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
               $this->set(compact('districtlist'));


            $this->set('district_code', $district_code);
            $search_condition[] = "Users.district_code=$district_code";


           //print_r($search_condition);
        }

        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
			$state= $this->request->session()->read('Auth.User.state_code');
            $district= $this->request->session()->read('Auth.User.district_code');
            //$search_condition3  = "Users.state_code = '" . $state . "' and Users.district_code = '" . $district . "'";
        }
		
		if($this->request->session()->read('Auth.User.role_id') == 11)
        {
			$state= $this->request->session()->read('Auth.User.state_code');
            $search_condition3  = "state_code = '" . $state . "'";
        }
		
        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }


        $users = $this->Users->find('all', [
            'order' => ['Users.name' => 'ASC'],
            'contain' => ['UserPayment','Roles'],
            'conditions' => [$searchString,'Users.status'=>1,'Users.state_code'=>$state,'Users.district_code'=>$this->request->session()->read('Auth.User.district_code')]
        ]);

        $state_name = $this->States->find('all')->where(['state_code'=>$this->request->session()->read('Auth.User.state_code'),'flag'=>1])->first()->name;

        $district_name = $this->Districts->find('all')->where(['state_code'=>$this->request->session()->read('Auth.User.state_code'),'district_code'=>$this->request->session()->read('Auth.User.district_code'),'flag'=>1])->first()->name;



        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
        $all_user = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['role_id IN'=>[7,8]])->toarray();
        
        $reg_cnt = $this->CooperativeRegistrations->find('list',['keyField'=>'created_by','valueField'=>'count']);            
        $reg_cnt = $reg_cnt->select(['created_by','count' => $reg_cnt->func()->count('id')])->where(['is_draft'=>0,'is_approved'=>1,'status'=>1])->group(['created_by'])->toArray();

        $this->set(compact('users','districts','states','all_user','reg_cnt','state_name','district_name'));
    }
    public function paymentlist()
    {
        $this->loadMOdel('PrimaryActivities');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadModel('States');
        $this->loadModel('Users');
        $this->loadModel('Roles');
        $this->loadModel('Districts');

        // $condtion=[];
        // $search_condition=[];
        // $condtiongp=[];
        // $page_length = !empty($this->request->data['page_length']) ? $this->request->data['page_length'] : 10;
        // $page = !empty($this->request->data['page']) ? $this->request->data['page'] : 1;
        $condtion=[];
        $search_condition=[];
        $condtiongp=[];
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $s_state = trim($this->request->query['state']);   
            
            $condtion['state_code'] = $this->request->query['state'];
            $condtiongp['state_code'] = $this->request->query['state'];
            

            $dist_opt = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$s_state])->order(['name'=>'ASC'])->toArray();
            $this->set('dist_opt', $dist_opt);
         
        }
        $this->set('s_state', $s_state);
        if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
            $district_code = trim($this->request->query['district_code']);
            $condtion['district_code'] = $this->request->query['district_code'];
            $condtiongp['district_code'] = $this->request->query['district_code'];
         //  $search_condition['area_of_operation_level.district_code']= $this->request->query['district_code'];
            $this->set('district_code', $district_code);
        }

        //  if (isset($this->request->data['state']) && $this->request->data['state'] !='') {
        //     $s_state = trim($this->request->data['state']);   
          
        //     $condtion['state_code'] = $this->request->data['state'];
        //     $condtiongp['state_code'] = $this->request->data['state'];
            
         
        //     $dist_opt = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$s_state])->order(['name'=>'ASC'])->toArray();
        //     $this->set('dist_opt', $dist_opt);
            
        // }
        // $this->set('s_state', $s_state);
        
        // if (isset($this->request->data['district_code']) && $this->request->data['district_code'] !='') {
        //     $district_code = trim($this->request->data['district_code']);
        //     $condtion['district_code'] = $this->request->data['district_code'];
        //     $condtiongp['district_code'] = $this->request->data['district_code'];
        //    $this->set('district_code', $district_code);
        //     $search_condition[] = "district_code like '%" .  $district_code . "%'";
        // }
      
        //$condtion['status']=0;
      
       

        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }
      
      
        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }


      $all_user = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['role_id IN'=>[7,8]])->toarray();
      $this->set('all_user',$all_user);
      $all_usern = $this->Users->find('list',['keyField'=>'id','valueField'=>'contact_no'])->where(['role_id IN'=>[7,8]])->toarray();
      $this->set('all_usern',$all_usern);
      $all_usern1 = $this->Users->find('list',['keyField'=>'id','valueField'=>'email'])->where(['role_id IN'=>[7,8]])->toarray();
      $this->set('all_usern1',$all_usern1);
      $all_usern2 = $this->Users->find('list',['keyField'=>'id','valueField'=>'state_code'])->where(['role_id IN'=>[7,8]])->toarray();
      $this->set('all_usern2',$all_usern2);
      $all_usern3 = $this->Users->find('list',['keyField'=>'id','valueField'=>'district_code'])->where(['role_id IN'=>[7,8]])->toarray();
      $this->set('all_usern3',$all_usern3);
      $all_usern4 =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
      $this->set('all_usern4',$all_usern4);
      $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
      $this->set('districts',$districts);
      $reg_cnt = $this->CooperativeRegistrations->find('list',['keyField'=>'created_by','valueField'=>'count']);            
      $reg_cnt = $reg_cnt->select(['created_by','count' => $reg_cnt->func()->count('id')])->where(['is_draft'=>0,'is_approved'=>1,'status'=>1])->group(['created_by'])->toArray();
      $this->set('reg_cnt',$reg_cnt);
         

        if($this->request->is('get'))
        {
         if(!empty($this->request->query['export_excel']))
         {
            $queryExport = $this->Users->find('all', [
                'order' => ['Users.name' => 'ASC'],
                'contain' => ['UserPayment','Roles'],
                'conditions' => [$condtion,'Users.status' => 1]]);
            
                // print_r($condtion); die;
              
                 $queryExport->hydrate(false);
                 $ExportResultData = $queryExport->toArray();
                 $fileName = "Payment_user".date("d-m-y:h:s").".xls";
                 

                 $headerRow = array("S.No", "State of Nodal Officer","District of Nodal Officer", "Name of Nodal Officer", "Contact No of Nodal Officer", "Email of Nodal Officer","Name of deo","Contact no. of deo","Email of deo","User Type","No of Society","A/C Holder Name","A/C Number","Bank Name","IFSC Code","Resource Type");
                 $data = array();
                 $i=1;
                 $user_types = [0=>'Normal User',1=>'DEO',2=>'Intern'];
              
               

                 foreach($ExportResultData As $rows){
                   
                      // print_r($ExportResultData);
                    //    $rows;
                    //    echo $all_usern4[$all_usern2[$rows['user_payment']['updated_by']]];
                
                $data[] = [$i, $all_usern4[$all_usern2[$rows['user_payment']['updated_by']]], $districts[$all_usern3[$rows['user_payment']['updated_by']]], $all_user[$rows['user_payment']['updated_by']], $all_usern[$rows['user_payment']['updated_by']], $all_usern1[$rows['user_payment']['updated_by']], $rows['name'], $rows['contact_no'], $rows['email'], $user_types[$rows['user_payment']['user_type']],  $reg_cnt[$rows['id']], $rows['user_payment']['account_holder_name'] ,$rows['user_payment']['account_number'],$rows['user_payment']['name_of_bank_and_branch'], $rows['user_payment']['ifsc_code'],$rows['user_payment']['remarks']];
                    $i++;
                }
                // die;
                $this->exportInExcelNew($fileName, $headerRow, $data);           

         }
        }
        
        if(!empty($this->request->query))
        {

        
            set_time_limit(0);
            ini_set('memory_limit', '2000000000000000000M');           

            $users = $this->Users->find('all', [
                'order' => ['Users.name' => 'ASC'],
                'contain' => ['UserPayment','Roles'],
                'conditions' => [$condtion,'Users.status' => 1]
                 ]);
            
  
                $this->paginate = ['limit' => 20];
                $users = $this->paginate($users); 

        }
        //  if(!empty($this->request->is('post')))
        //  {

        
            // $users = $this->Users->find('all', [
            //     'order' => ['Users.name' => 'ASC'],
            //     'contain' => ['UserPayment','Roles'],
            //     'conditions' => [$condtion,'Users.status' => 1]
            //      ]);
            
  
            //     $this->paginate = ['limit' => 20];
            //     $users = $this->paginate($users); 
              
        //  }   

        
            
      


      
       
       $sOption = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $this->set(compact('users','sOption','states','state_name','district_name'));
       
      
   
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
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');

        $user = $this->Users->get($id, [
            'contain' => ['UserPayment','Roles'],
        ]);

        $states = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();

        $this->set(compact('user','districts','states'));
        
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('Users');
        $this->loadModel('UserPayment');

        $userList = [];

        $state_code = $this->request->session()->read('Auth.User.state_code');
        $district_code= $this->request->session()->read('Auth.User.district_code');

        $rem_user_ids = $this->UserPayment->find('list',['keyField'=>'user_id','valueField'=>'account_holder_name'])->where(['status'=>1])->toArray();

        $rem_user_ids = array_keys($rem_user_ids);
        

        if(empty($rem_user_ids))
        {
            $userList=$this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'state_code'=>$state_code,'district_code'=>$district_code])->order(['name'=>'ASC'])->toArray();

        } else {

            $userList=$this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'state_code'=>$state_code,'district_code'=>$district_code,'id NOT IN'=>$rem_user_ids])->order(['name'=>'ASC'])->toArray();
        }

        //echo $this->request->session()->read('Auth.User.id').'='.$this->request->session()->read('Auth.User.name');die;
        

        if(!in_array($this->request->session()->read('Auth.User.id'),$rem_user_ids) )
        {
            $userList[$this->request->session()->read('Auth.User.id')] = $this->request->session()->read('Auth.User.name');
        }


        $UserPayment = $this->UserPayment->newEntity();

        if ($this->request->is(['patch', 'post', 'put'])) {

            
            $data = $this->request->getData();
            $data['updated_by'] = $this->request->session()->read('Auth.User.id');
            
            $userPaymentDetail = $this->UserPayment->patchEntity($UserPayment, $data);

            
            if ($UserPayment = $this->UserPayment->save($userPaymentDetail)) {
                $this->Flash->success(__('The user Bank detail has been added.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user Bank detail could not be saved. Please, try again.'));
        }

        $this->set(compact('userList','UserPayment'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('Users');
        $this->loadModel('UserPayment');

        $user = $this->Users->get($id, [
            'contain' => ['UserPayment','Roles']
        ]);

        $detail = $this->UserPayment->find('all')->where(['user_id' => $id])->first();

        if (empty($detail)) {
            $UserPayment = $this->UserPayment->newEntity();    
        } else {
            $UserPayment = $this->UserPayment->get($detail->id);
        }
       
        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();
            $data['user_id'] = $id;
            $data['updated_by'] = $this->request->session()->read('Auth.User.id');
            
            $userPaymentDetail = $this->UserPayment->patchEntity($UserPayment, $data);

            
            if ($UserPayment = $this->UserPayment->save($userPaymentDetail)) {
                $this->Flash->success(__('The user Bank detail has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user Bank detail could not be saved. Please, try again.'));
        }

        $this->set(compact('user','UserPayment'));
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
        $this->loadModel('Certificates');
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
        return $this->redirect(['action' => 'listDistrictPaymentCertificate']);
    }


    public function uploadDistrictPaymentCertificate()
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
                $fileName = $this->uploadPdf('certificates', $data['certificate_pdf']);
                $data['title']  = $fileName['filename'];//echo "<pre>"; print_r($fileName); exit;
            }

            $data['created_by'] = $this->request->session()->read('Auth.User.id');
            $data['created'] = date('Y-m-d H:i:s');
            $data['state_code'] = $state_code;
            $data['district_code'] = $district_code;
            $data['type'] = 2;

            
			//echo "<pre>"; print_r($data); exit;
            $data = $this->Sanitize->clean($data);
            $doc_type = $this->Certificates->patchEntity($doc_type, $data);
            
            
            //echo "<pre>"; print_r($doc_type); exit;
            if ($this->Certificates->save($doc_type)) {
                $this->Flash->success(__('The Certificate has been saved.'));

                return $this->redirect(['action' => 'listDistrictPaymentCertificate']);//listDistrictPaymentCertificate
            }
            $this->Flash->error(__('The Certificate could not be saved. Please, try again.'));
        }
        $this->set(compact('doc_type'));
    }


    public function listDistrictPaymentCertificate()
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
            'conditions' => [$searchString,$search_condition2,$search_condition3,'Certificates.status'=>1,'Certificates.title !='=>'','type'=>2]
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

    public function listAllPaymentCertificate()
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

            $districtlist=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
            $this->set(compact('districtlist'));
        }
       
        if(!empty($this->request->query['district_code'])) {
            $district_code = trim($this->request->query['district_code']);

              


            $this->set('district_code', $district_code);
            $search_condition[] = "district_code=$district_code";


           //print_r($search_condition);
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


        // $state_code = $this->request->session()->read('Auth.User.state_code');
        // $district_code = $this->request->session()->read('Auth.User.district_code');

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
            'conditions' => [$searchString,'Certificates.status'=>1,'Certificates.title !='=>'','type'=>2]
            //'conditions' => [$searchString,$search_condition2,$search_condition3,'Certificates.status'=>1,'Certificates.title !='=>'','type'=>2]
        ]);//->toArray()
        
        
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

    public function download($title)
    {
        $file_path = WWW_ROOT.'files'.DS.'certificates'.DS.$title;
        
        $this->response->file($file_path, array(
        'download' => true,
        'name' => $title,
        ));
        return $this->response;
    }

   

    /**
     * Delete method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deletepaymentdetail($id = null)
    {
        $this->loadModel('UserPayment');

        $detail = $this->UserPayment->find('all')->where(['user_id' => $id])->first();

        if($this->UserPayment->deleteAll(['user_id' => $id]))
        {
            $this->Flash->success(__('The User Bank detail has been deleted.'));
        } else {
            $this->Flash->error(__('The User Bank detail could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    public function getDistricts(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $state_code=$this->request->query('state_code');    
            $this->loadMOdel('Districts');
           //$districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

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
