<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * CooperativeRegistrations Controller
 *
 * @property \App\Model\Table\CooperativeRegistrationsTable $CooperativeRegistrations
 *
 * @method \App\Model\Entity\State[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StateWiseRuralUrbanController extends AppController
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
        $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','getdccb','getfederationlevel','approval','bulkapproval','getUrbanRural']);
    }

  public function index()
  {
    $this->loadModel('States');
    $this->loadModel('Districts');
    $this->loadModel('SubDistricts');
    $this->loadModel('PrimaryActivities');
    $this->loadMOdel('UrbanLocalBodies');
    $this->loadMOdel('CooperativeRegistrations');
    $this->loadMOdel('DistrictsBlocksGpVillages');

    
    $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
    $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

    if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
        $s_state = trim($this->request->query['state']);

       
        $this->set('s_state', $s_state);
    }

    if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
        $d_district_code = trim($this->request->query['district_code']);
        $this->set('d_district_code', $d_district_code);
    }
         

    if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
        $s_location = trim($this->request->query['location']);
        $this->set('s_location', $s_location);
    }

   
    
    if ($page_length != 'all' && is_numeric($page_length)) {
        $this->paginate = [
            'limit' => $page_length,
        ];
    }
    

    
     $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
     $query->hydrate(false);
     $stateOption = $query->toArray();
     $this->set('sOption',$stateOption);


   if (isset($this->request->query['state']) && $this->request->query['state'] !='') 
   {
         $query =$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name','conditions'=>['state_code'=>$this->request->query['state']],'order' => ['name' => 'ASC']]);
 
         $query->hydrate(false);
         $dist_opt = $query->toArray();
         $this->set('dist_opt',$dist_opt);
    }

    $district_codes = [];
    $dairies = [];
    $pacs = [];
    $fisheries = [];

   

  
        //$district_codes = $this->CooperativeRegistrations->find('all',array('conditions' => array('district_code IS NOT NULL')))->select(['district_code'])->where(['state_code'=>$this->request->query['state']])->group(['district_code'])->extract('district_code');
        $condtion_district=[];
        $condtion_districtA=[];
        $condtion_location=[];
        if(!empty($this->request->query['district_code']))
        {
            $condtion_district['district_code'] = $this->request->query['district_code'];
        }
        if(!empty($this->request->query['state']))
        {
            $condtion_district['state_code'] = $this->request->query['state'];
            $condtion_districtA['state_code'] = $this->request->query['state'];
        }
        if(!empty($this->request->query['location']))
        {
            $condtion_location['location_of_head_quarter'] = $this->request->query['location'];
        }

        //print_r($condtion_district);

        $state_code= $this->States->find('all')->where(['flag'=>1,$condtion_districtA])->order(['name'=>'ASC'])->select(['state_code']);

    

        $this->loadModel('DistrictNodalEntries');

       //  echo $this->request->query['state'];
        $total_district_nodal = $this->DistrictNodalEntries->find('all',['conditions' => [$condtion_district]])->toarray();
       
       
        $district_nodal_tatal =[];
        $district_nodal_tatal_without_state =[];

       // echo "<pre>";
       // print_r($total_district_nodal);

        foreach($total_district_nodal as $key=>$value)
        {
              
             $district_nodal_tatal ['pacs'][$value['state_code']][]           =   $value['pacs_count'];
             $district_nodal_tatal['dairy'] [$value['state_code']] []         =   $value['dairy_count'];
             $district_nodal_tatal['fisfhery'][$value['state_code']] []       =   $value['fishery_count'];

             $district_nodal_tatal_without_state['pacs'][]           =   $value['pacs_count'];
             $district_nodal_tatal_without_state['dairy'][]         =   $value['dairy_count'];
             $district_nodal_tatal_without_state['fisfhery'][]       =   $value['fishery_count'];
        }
    
       
        //Pacs
       $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
       $pacs = $pacs->select(['state_code','count' => $pacs->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['1','20','22'],'is_draft'=>'0','location_of_head_quarter'=>'2','status'=>'1'])->group(['state_code'])->toarray();
       $this->paginate = ['limit' => 36];
       $district_codes = $this->paginate($state_code);
       
       $fpacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
       $fpacs = $fpacs->select(['state_code','count' => $fpacs->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['1','20','22'],'is_draft'=>'0','status'=>'1', 'location_of_head_quarter'=>'1'])->group(['state_code'])->toarray();
       

       //fisheries
       $fis = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);           
       $fis = $fis->select(['state_code','count' => $fis->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','location_of_head_quarter'=>'2','status'=>'1'])->group(['state_code'])->toarray();
       $ffis = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
       $ffis = $ffis->select(['state_code','count' => $ffis->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','status'=>'1', 'location_of_head_quarter'=>'1'])->group(['state_code'])->toarray();
      
       //dairy 
       $dai = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);           
       $dai = $dai->select(['state_code','count' => $dai->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['9','37'],'is_draft'=>'0','location_of_head_quarter'=>'2','status'=>'1'])->group(['state_code'])->toarray();
       $fdai = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
       $fdai = $fdai->select(['state_code','count' => $fdai->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['9','37'],'is_draft'=>'0','status'=>'1', 'location_of_head_quarter'=>'1'])->group(['state_code'])->toarray();
       
     
    //$arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        
    $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
    //sum of total no. of pac
    $total_quantity = $this->CooperativeRegistrations->find();
    $count_quantity =$total_quantity->select(['sum' => $total_quantity->func()->sum('CooperativeRegistrations.members_of_society')])->where(['CooperativeRegistrations.sector_of_operation IN' => ['1','20','22'],'CooperativeRegistrations.is_draft'=>'0','CooperativeRegistrations.status'=>'1'])->first();
    $sum_quantity = $count_quantity->sum;
    //sum of total no. of dairy
    $total_quantity2 = $this->CooperativeRegistrations->find();
    $count_quantity2 = $total_quantity2->select(['sum' => $total_quantity->func()->sum('CooperativeRegistrations.members_of_society')])->where(['CooperativeRegistrations.sector_of_operation IN' => ['10','43'],'CooperativeRegistrations.is_draft'=>'0','CooperativeRegistrations.status'=>'1'])->first();
    $sum_quantity2 = $count_quantity2->sum;
    //sum of total no. of fishery
    $total_quantity3 = $this->CooperativeRegistrations->find();
    $count_quantity3 =$total_quantity3->select(['sum' => $total_quantity->func()->sum('CooperativeRegistrations.members_of_society')])->where(['CooperativeRegistrations.sector_of_operation IN' => ['9','37'],'CooperativeRegistrations.is_draft'=>'0','CooperativeRegistrations.status'=>'1'])->first();
    $sum_quantity3 = $count_quantity3->sum;

    $total_quantity4 = $this->CooperativeRegistrations->find();
    $count_quantity4 =$total_quantity4->select(['sum' => $total_quantity->func()->sum('CooperativeRegistrations.members_of_society')])->where(['CooperativeRegistrations.sector_of_operation IN' => ['1','20','22'],'CooperativeRegistrations.is_draft'=>'0','CooperativeRegistrations.status'=>'1'])->first();
    $sum_quantity4 = $count_quantity4->sum;
    
    //count of total no. of pac entered
    $this->set('total_report', $this->CooperativeRegistrations->find()->where(['sector_of_operation IN' => [1,59,20,22],'is_draft'=>0])->count());
    //count of total no. of pac entered
    $this->set('total_report2', $this->CooperativeRegistrations->find()->where(['sector_of_operation IN' => [9,37],'is_draft'=>0])->count());
    //count of total no. of pac entered
    $this->set('total_report3', $this->CooperativeRegistrations->find()->where(['sector_of_operation IN' => [10,43],'is_draft'=>0])->count());
    // $this->set('total_pac', $this->CooperativeRegistrations->find()->where(['sector_of_operation IN' => [10,43],'is_draft'=>0])->count());

    $this->set(compact('dai','fdai','ldai','ndai','fis','ffis','lfis','nfis','fpacs','lpacs','npacs','district_codes','arr_districts','panchayats','villages','dairies','pacs','fisheries','district_nodal_tatal','state_code','district_nodal_tatal_without_state','pacs_today','dairies_today','fisheries_today','urban_bodys','urban_bodys_wards','sum_quantity','sum_quantity2','sum_quantity3','sum_quantity4'));
    
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