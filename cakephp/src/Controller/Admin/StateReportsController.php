<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Error\Exceptions;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
/**
 * CooperativeRegistrations Controller
 *
 * @property \App\Model\Table\CooperativeRegistrationsTable $CooperativeRegistrations
 *
  * @method \App\Model\Entity\State[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StateReportsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $this->loadMOdel('DistrictNodalEntries');
        
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

     
         if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $district = trim($this->request->query['district']);
            $this->set('district', $district);
        }

        

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $s_location = trim($this->request->query['location']);
            $this->set('s_location', $s_location);
            $condtion_location['location_of_head_quarter'] = $this->request->query['location'];
        }
        $condtion_location['is_approved !='] = 2;
      
        
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }
        

        
        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);

      
            $query =$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name','conditions'=>['state_code'=> $this->request->session()->read('Auth.User.state_code')],'order' => ['name' => 'ASC']]);
     
            $query->hydrate(false);
            $dist_opt = $query->toArray();
            $this->set('dist_opt',$dist_opt);
     

        $district_codes = [];
        $dairies = [];
        $pacs = [];
        $fisheries = [];

       // if($this->request->session()->read('Auth.User.state_code') !='')
        //{
            //$district_codes = $this->CooperativeRegistrations->find('all',array('conditions' => array('district_code IS NOT NULL')))->select(['district_code'])->where(['state_code'=>$this->request->query['state']])->group(['district_code'])->extract('district_code');
            $condtion_district=[];
            if(!empty($this->request->query['district']))
            {
                $condtion_district['district_code'] = $this->request->query['district'];
            }

           // print_r($condtion_district);

           $district_codes= $this->Districts->find('all')->where(['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code') ,$condtion_district])->order(['name'=>'ASC'])->select(['district_code']);

          // print_r($district_codes);

            //Panchayats
            $panchayats = $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['district_code IS NOT NULL'],'keyField'=>'district_code','valueField'=>'count']);
            
            $panchayats = $panchayats->select(['district_code','count' => $panchayats->func()->count('gram_panchayat_code')])->where(['state_code'=>$this->request->session()->read('Auth.User.state_code') , $condtion_district])->group(['district_code'])->toArray();

            //Villages
            $villages = $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['district_code IS NOT NULL'],'keyField'=>'district_code','valueField'=>'count']);
            
            $villages = $villages->select(['district_code','count' => $villages->func()->count('village_code')])->where(['state_code'=>$this->request->session()->read('Auth.User.state_code') ,$condtion_district])->group(['district_code'])->toArray();

            
          //  echo $this->request->session()->read('Auth.User.state_code');
           // die;
           //  echo $this->request->query['state'];
           
            $total_district_nodal = $this->DistrictNodalEntries->find('all',['conditions' => ['state_code'=>$this->request->session()->read('Auth.User.state_code'),$condtion_district]])->toarray();
            $district_nodal_tatal=array();

            //print_r($total_district_nodal);

            foreach($total_district_nodal as $key=>$value)
            {

                 $district_nodal_tatal['pacs'][$value['state_code']][$value['district_code']]=$value['pacs_count'];
                 $district_nodal_tatal['dairy'][$value['state_code']][$value['district_code']]=$value['dairy_count'];
                 $district_nodal_tatal['fisfhery'][$value['state_code']][$value['district_code']]=$value['fishery_count'];
            }

            $state_code=$this->request->session()->read('Auth.User.state_code');

            //Dairies
           // $dairies = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);  
            
            $dairies = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']); 

            $dairies = $dairies->select(['district_code','count' => $dairies->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [9,37],'state_code'=>$this->request->session()->read('Auth.User.state_code'), 'is_draft'=>0, 'status'=>'1', $condtion_district , $condtion_location])->group(['district_code'])->toArray();

             //Dairies today 
            $dairies_today = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);
            
            $dairies_today = $dairies_today->select(['state_code','count' => $dairies_today->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [9,37],'is_draft'=>0, 'status'=>'1','DATE(created)' => date('Y-m-d'), $condtion_district , $condtion_location])->group(['state_code'])->toArray();


             $this->loadModel('DistrictNodalEntries');

            //Pacs
            $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);            
            $pacs = $pacs->select(['district_code','count' => $pacs->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [1,59,20,22],'state_code'=>$this->request->session()->read('Auth.User.state_code') , 'is_draft'=>0, 'status'=>'1', $condtion_district , $condtion_location])->group(['district_code'])->toArray();

            // echo '<pre>';
            // print_r($pacs);die;

              //Pacs today
              $pacs_today = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);            
              $pacs_today = $pacs_today->select(['district_code','count' => $pacs_today->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [1,59,20,22],'state_code'=>$this->request->session()->read('Auth.User.state_code') , 'is_draft'=>0, 'status'=>'1', 'DATE(created)' => date('Y-m-d'), $condtion_district , $condtion_location])->group(['district_code'])->toArray();

            //Fisheries
            $fisheries = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);
            
            $fisheries = $fisheries->select(['district_code','count' => $fisheries->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [10,43],'state_code'=>$this->request->session()->read('Auth.User.state_code'),'is_draft'=>0, 'status'=>'1', $condtion_district , $condtion_location])->group(['district_code'])->toArray();

                //Fisheries today 
                $fisheries_today = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);
            
                $fisheries_today = $fisheries_today->select(['district_code','count' => $fisheries_today->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [10,43],'state_code'=>$this->request->session()->read('Auth.User.state_code'),'is_draft'=>0, 'status'=>'1', 'DATE(created)' => date('Y-m-d'), $condtion_district , $condtion_location])->group(['district_code'])->toArray();

             $this->paginate = ['limit' => 20];
            $district_codes = $this->paginate($district_codes);

      //  }
              
       

        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

    
        $this->set(compact('district_codes','arr_districts','panchayats','villages','dairies','pacs','fisheries','district_nodal_tatal','state_code','pacs_today','dairies_today','fisheries_today'));
        
    }

    public function urbanReport()
    {
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $this->loadMOdel('DistrictNodalEntries');

        
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;        

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $s_location = trim($this->request->query['location']);

            $condtion_location['location_of_head_quarter'] = $this->request->query['location'];
            $this->set('s_location', $s_location);
        }else
        {
            $condtion_location['location_of_head_quarter'] = $s_location = 1;
          
        }       
        
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }

       
        
        
        $this->loadMOdel('UrbanLocalBodies');

       // $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')])->group('localbody_code')->order(['local_body_name'=>'ASC'])->toarray();  
        
        $localbodiesA=$this->UrbanLocalBodies->find('all')->where(['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')])->group('localbody_code')->order(['local_body_name'=>'ASC'])->toarray(); 
        foreach($localbodiesA as $key=>$value)
        {
            $localbodies[$value['localbody_code']]=$value['local_body_name']." (".$value['localbody_type_name'].")";
        }

      

        $this->set('localbodies', $localbodies);
     

        $urban_codes = [];
        $dairies = [];
        $pacs = [];
        $fisheries = [];

       
            $condtion_district=[];
            if(!empty($this->request->query['local_bodies']))
            {
                $condtion_district['localbody_code'] = $this->request->query['local_bodies'];
                $condtion_location['urban_local_body_code'] = $this->request->query['local_bodies'];
                $local_body = trim($this->request->query['local_bodies']);
                $this->set('local_body', $local_body);
            }

            $district_codes=$this->UrbanLocalBodies->find('list',['keyField'=>'id','valueField'=>'localbody_code'])->where(['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code'),$condtion_district])->group('localbody_code')->order(['local_body_name'=>'ASC']);
        
            $all_regidtration = $this->CooperativeRegistrations->find('all')->where(['state_code'=>$this->request->session()->read('Auth.User.state_code'), 'is_draft'=>0, 'status'=>'1', $condtion_location])->toArray(); 

            $pacA=[];
            $dairyA=[];
            $fishA=[];
            
            foreach($all_regidtration as $value)
            {
                if($value['sector_of_operation']==1 || $value['sector_of_operation']==59 || $value['sector_of_operation']==20 || $value['sector_of_operation']==22)
                {
                    $pacA[$value['urban_local_body_code']][]=$value['id'];
                }
                if($value['sector_of_operation']==9 || $value['sector_of_operation']==37)
                {
                    $dairyA[$value['urban_local_body_code']][]=$value['id'];
                }
                if($value['sector_of_operation']==10 || $value['sector_of_operation']==43)
                {
                    $fishA[$value['urban_local_body_code']][]=$value['id'];
                }
            }

            $this->set(compact('pacA','dairyA','fishA'));
            
            
            $dairies = $this->CooperativeRegistrations->find('all')->where(['sector_of_operation IN' => [9,37],'state_code'=>$this->request->session()->read('Auth.User.state_code'), 'is_draft'=>0, 'status'=>'1', $condtion_location])->toArray();           
            
            $this->loadModel('DistrictNodalEntries');          
           $state_code= $this->request->session()->read('Auth.User.state_code');
            //Pacs
            $pacs = $this->CooperativeRegistrations->find('all')->where(['sector_of_operation IN' => [1,59,20,22],'state_code'=>$state_code , 'is_draft'=>0, 'status'=>'1', $condtion_location])->toarray();     
            
          //  echo "<pre>";
            //print_r($pacs);
             
            //Fisheries
            $fisheries = $this->CooperativeRegistrations->find('all')->where(['sector_of_operation IN' => [10,43],'state_code'=>$this->request->session()->read('Auth.User.state_code'),'is_draft'=>0, 'status'=>'1', $condtion_location])->toArray();
             
             $this->paginate = ['limit' => 20];
            $district_codes = $this->paginate($district_codes);

      //  }
                  
        $this->set(compact('district_codes','arr_districts','panchayats','villages','dairies','pacs','fisheries','district_nodal_tatal','state_code','pacs_today','dairies_today','fisheries_today'));


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
