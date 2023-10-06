<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * CooperativeRegistrations Controller
 *
 * @property \App\Model\Table\CooperativeRegistrationsTable $CooperativeRegistrations
 *
 * @method \App\Model\Entity\State[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ComputerizeSchemeController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
        $user_all=$this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
        $this->set('user_all', $user_all);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','getdccb','getfederationlevel','approval','bulkapproval','getUrbanRural','generateUniqueNumber']);
    }
    public function index()
    {
       $this->loadModel('CooperativeRegistrations');
       $search_condition = array();
       $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
       $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
       
       if (!empty($this->request->query['district_code'])) {
        $district_code = trim($this->request->query['district_code']);
        $this->set('district_code', $district_code);
        $search_condition[] = "district_code like '%" . $district_code . "%'";
      }
      if (!empty($this->request->query['cooperative_society_name'])) {
        $cooperative_society_name = trim($this->request->query['cooperative_society_name']);
        $this->set('cooperative_society_name', $cooperative_society_name);
        $search_condition[] = "cooperative_society_name like '%" . $cooperative_society_name . "%'";
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

        $state_code= $this->request->session()->read('Auth.User.state_code');
        
    if(!empty($this->request->query))
    {

        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            // 'order' => ['cooperative_id_num' => 'DESC'],
            'conditions' => [$searchString]
        ])->where(['is_draft'=>0,'is_approved'=>1,'status'=>1,'state_code'=> $state_code,'sector_of_operation IN' => ['1','20','22']]);

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        $this->set(compact('CooperativeRegistrations'));
    }
        $this->loadModel('PrimaryActivities');
        $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $this->loadModel('Districts');
        $all_districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']);
        $this->set('all_districts',$all_districts);
        
       $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       $this->set('arr_districts',$arr_districts);
        $option_html=$this->CooperativeRegistrations->find('list',['keyField'=>'cooperative_society_name','valueField'=>'cooperative_society_name'])->where(['status'=>1,'district_code'=>$this->request->query['district_code'],'sector_of_operation IN' => ['1','20','22'],'is_draft'=>0,'is_approved'=>1]);
        // $society2=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'district_code'=>$district_code,'sector_of_operation IN' => ['1','20','22'],'is_draft'=>0,'is_approved'=>1])->toArray();
        // foreach($society2 as $key=>$value){
        //     $option_html.='<option>'.$value['cooperative_society_name'].'</option>';
        // }
        $this->set(compact('PrimaryActivities','option_html'));
    }
    // public function getSociety()
    // {
    //     $this->viewBuilder()->setLayout('ajax');
    //     if($this->request->is('ajax')){
    //         $district_code=$this->request->data('district_code');    
    //         $primary_activity=$this->request->data('primary_activity');    
    //         $this->loadModel('CooperativeRegistrations');

    //         $society=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'district_code'=>$district_code,'sector_of_operation'=>$primary_activity])->order(['cooperative_society_name'=>'ASC'])->toArray();
            
    //         $option_html='';
    //         if(count($society)>0){
    //             foreach($society as $key=>$value){

    //                 $option_html.='<option cs="'.$value['under_computerised_scheme'].'" s_name="'.$value['cooperative_society_name'].'" reg_no="'.$value['registration_number'].'"  reg_date="'.$value['date_registration'].'" value="'.$value['id'].'">'.$value['cooperative_society_name'].'</option>';
    //             }
    //         }
    //         echo $option_html;
    //     }
    //     exit;

    // }
    public function bulkapproval()
    {
        if($this->request->is('post')){
            $data=$this->request->getData();
            $this->loadMOdel('CooperativeRegistrations');  
            $this->loadMOdel('CooperativeRegistrationsRemarks');
            
            // echo '<pre>';
            // print_r($r_data);
            // print_r($data);
            // die;

            $remarks_data = [];
            $remarks_data['under_computerised_scheme'] = $data['under_computerised_scheme'];
          

            

            foreach($data['arr_accept'] as $key => $id)
            {
                $CooperativeRegistration = $this->CooperativeRegistrations->get($id);

                $CooperativeRegistration = $this->CooperativeRegistrations->patchEntity($CooperativeRegistration, $data);
                $this->CooperativeRegistrations->save($CooperativeRegistration);

               
            }

            $this->Flash->success(__('Request has been accepted.'));

           
            
        }

    }
    public function getSociety()
    {
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $district_code=$this->request->query('district_code');    
            $primary_activity=$this->request->query('primary_activity');    
            $this->loadModel('CooperativeRegistrations');

           //$society=$this->CooperativeRegistrations->find('list',['keyField'=>'id','valueField'=>'cooperative_society_name'])->where(['status'=>1,'district_code'=>$district_code,'sector_of_operation IN' => ['1','20','22'],'is_draft'=>0,'is_approved'=>1])->toArray();
            $society=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'district_code'=>$district_code,'sector_of_operation IN' => ['1','20','22'],'is_draft'=>0,'is_approved'=>1])->toArray();
            $option_html='<option value="">Select</option>';
           //$option_html='';
            if(count($society)>0){
                foreach($society as $key=>$value){

                    //$option_html.='<option value="'.$key.'">'.$value.'</option>';
                   $option_html.='<option>'.$value['cooperative_society_name'].'</option>';
                }
            }
            echo $option_html;
        }
        exit;

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