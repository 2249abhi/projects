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
class CertificatesAdminController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }
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
            $districtlist=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
            $this->set(compact('districtlist'));
            $this->set('district_code', $district_code); 
            $search_condition[] = "district_code = '" . $district_code . "'";
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
            'conditions' => [$searchString,$search_condition2,$search_condition3,'Certificates.status'=>1,'Certificates.type'=>1,'Certificates.title !='=>'', 'Certificates.district_code !='=> 'NULL']
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
    public function adminStatewiseCertificate()
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

        // $certificates = $this->Certificates->find('all', [
        //     'order' => ['Certificates.id' => 'DESC'],
        //     'conditions' => [$searchString,$search_condition3,'Certificates.status'=>1,'Certificates.title !='=>'','Certificates.district_code'=> 'NULL']
        // ]);

        $certificates = $this->Certificates->find('all', [
            'order' => ['id' => 'ASC'],
            'conditions' => [$searchString,$search_condition3]
        ])->where(['status'=>1, 'title !='=>'', 'Certificates.type'=>1]);
        // $this->paginate = ['limit' => 25];
        // $state_codee = $this->paginate($registrationQuery);
        // $this->set(compact('state_codee'));
        
        //->toArray()
        
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

    public function getDistricts(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $state_code=$this->request->data('state_code');    
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
