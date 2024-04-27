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
class AllIndiaReportsController extends AppController
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

       // if(!empty($this->request->query['state']))
       if(1==1)
       {
           
            $condtion_district=[];
            $condtion_districtA=[];
            $condtion_location=[];
            if(!empty($this->request->query['district_code']))
            {
                $condtion_district['district_code'] = $this->request->query['district_code'];
                $condtion_districtB['district_code'] = $this->request->query['district_code'];
            }
            if(!empty($this->request->query['state']))
            {
                $condtion_district['state_code'] = $this->request->query['state'];
                $condtion_districtA['state_code'] = $this->request->query['state'];
                $condtion_districtB['state_code'] = $this->request->query['state'];
            }
            if(!empty($this->request->query['location']))
            {
                $condtion_location['location_of_head_quarter'] = $this->request->query['location'];
            }

            //print_r($condtion_district);

            if(!empty($this->request->query['state']) && $s_location ==2)
            {
                $state_code= $this->DistrictsBlocksGpVillages->find('all')->where(['status'=>1,$condtion_districtB])->order(['district_name'=>'ASC'])->select(['district_code','state_code'])->group(['district_code']); 

                $state_code1= $this->DistrictsBlocksGpVillages->find('all')->where(['status'=>1,$condtion_districtB])->order(['district_name'=>'ASC'])->select(['district_code','state_code'])->group(['district_code'])->toarray();;  

             // echo "<pre>";
             // print_r($state_code1);
              // die;

               
              //  $state_code= $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1, $condtion_districtB])->order(['name'=>'ASC']);
           
         
            } else if(!empty($this->request->query['state']) && ($s_location ==1))
            {
                                
                $this->loadMOdel('UrbanLocalBodiesWards');
                $state_code= $this->UrbanLocalBodiesWards->find('all')->where(['status'=>1,$condtion_districtA])->order(['state_name'=>'ASC'])->select(['state_code'])->group(['state_code']);
            }
            else{
                $state_code= $this->States->find('all')->where(['flag'=>1,$condtion_districtA])->order(['name'=>'ASC'])->select(['state_code'])->group(['state_code']);
               // $state_code= $this->States->find('all')->where(['flag'=>1,$condtion_districtA])->order(['name'=>'ASC'])->select(['state_code']);
            }


            //Panchayats         
            if(!empty($this->request->query['state']) && $s_location !=1)
            {
                $panchayats = $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['district_code IS NOT NULL', $condtion_district],'keyField'=>'district_code','valueField'=>'count']);
            
                $panchayats = $panchayats->select(['district_code','count' => $panchayats->func()->count('DISTINCT  gram_panchayat_code')])->where([$condtion_district])->group(['district_code'])->toArray();
            }else{
                $panchayats = $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['district_code IS NOT NULL', $condtion_district],'keyField'=>'state_code','valueField'=>'count']);
            
                $panchayats = $panchayats->select(['state_code','count' => $panchayats->func()->count('DISTINCT  gram_panchayat_code')])->where([$condtion_district])->group(['state_code'])->toArray();
            }
          

            if($this->request->query['location']==1)
            {
                $this->loadMOdel('UrbanLocalBodies');
                $urban_bodys = $this->UrbanLocalBodies->find('list',['conditions' => [$condtion_district],'keyField'=>'state_code','valueField'=>'count']);
                
                $urban_bodys = $urban_bodys->select(['state_code','count' => $urban_bodys->func()->count('DISTINCT  localbody_type_code')])->where([$condtion_district])->group(['state_code'])->toArray();


                
                $this->loadMOdel('UrbanLocalBodiesWards');

                $urban_bodys_wards = $this->UrbanLocalBodiesWards->find('list',['conditions' => [$condtion_district],'keyField'=>'state_code','valueField'=>'count']);
                
                $urban_bodys_wards = $urban_bodys_wards->select(['state_code','count' => $urban_bodys_wards->func()->count('DISTINCT ward_code')])->where([$condtion_district])->group(['state_code'])->toArray();
            }

           else if(!empty($this->request->query['state']) && $s_location ==2)
            {
                $villages = $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['district_code IS NOT NULL' , $condtion_district],'keyField'=>'district_code','valueField'=>'count']);
            
                $villages = $villages->select(['district_code','count' => $villages->func()->count('DISTINCT village_code')])->where([$condtion_district])->group(['district_code'])->toArray();

            }else{
              //Villages
                $villages = $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['district_code IS NOT NULL' , $condtion_district],'keyField'=>'state_code','valueField'=>'count']);
                
                $villages = $villages->select(['state_code','count' => $villages->func()->count('DISTINCT village_code')])->where([$condtion_district])->group(['state_code'])->toArray();
            }

            //Dairies
            //$dairies = $this->CooperativeRegistrations->find('list',['conditions' => ['district_code IS NOT NULL'],'keyField'=>'state_code','valueField'=>'count']);

            if(!empty($this->request->query['state']) && $s_location ==2)
            {
                $dairies = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);            
                $dairies = $dairies->select(['district_code','count' => $dairies->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [9,37], 'is_draft'=>0, 'status'=>1, $condtion_district ,$condtion_location])->group(['district_code'])->toArray();
                 //Dairies
                 $dairies_today = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);
                
                 $dairies_today = $dairies_today->select(['district_code','count' => $dairies_today->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [9,37],'is_draft'=>0, 'status'=>1, 'DATE(created)' => date('Y-m-d'), $condtion_district,$condtion_location])->group(['district_code'])->toArray();
            }else{
                $dairies = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);            
                $dairies = $dairies->select(['state_code','count' => $dairies->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [9,37], 'is_draft'=>0, 'status'=>1, $condtion_district ,$condtion_location])->group(['state_code'])->toArray();
                 //Dairies
                 $dairies_today = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);
                
                 $dairies_today = $dairies_today->select(['state_code','count' => $dairies_today->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [9,37],'is_draft'=>0, 'status'=>1, 'DATE(created)' => date('Y-m-d'), $condtion_district,$condtion_location])->group(['state_code'])->toArray();
            }
       
             $this->loadModel('DistrictNodalEntries');
           //  echo $this->request->query['state'];
            $total_district_nodal = $this->DistrictNodalEntries->find('all',['conditions' => [$condtion_district]])->toarray();           
           
            $district_nodal_tatal =[];
            $district_nodal_tatal_without_state =[];          

            foreach($total_district_nodal as $key=>$value)
            {
                  
                if(!empty($this->request->query['state']) && $s_location ==2)
                {
                    $district_nodal_tatal ['pacs'][$value['district_code']][]           =   $value['pacs_count'];
                    $district_nodal_tatal['dairy'] [$value['district_code']] []         =   $value['dairy_count'];
                    $district_nodal_tatal['fisfhery'][$value['district_code']] []       =   $value['fishery_count'];
                }else
                {
                    $district_nodal_tatal ['pacs'][$value['state_code']][]           =   $value['pacs_count'];
                    $district_nodal_tatal['dairy'] [$value['state_code']] []         =   $value['dairy_count'];
                    $district_nodal_tatal['fisfhery'][$value['state_code']] []       =   $value['fishery_count'];
                }

              

                 $district_nodal_tatal_without_state['pacs'][]           =   $value['pacs_count'];
                 $district_nodal_tatal_without_state['dairy'][]         =   $value['dairy_count'];
                 $district_nodal_tatal_without_state['fisfhery'][]       =   $value['fishery_count'];
            }  
            //Pacs
            
            if(!empty($this->request->query['state']) && $s_location ==2)
            {

                $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);            
                $pacs = $pacs->select(['district_code','count' => $pacs->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [1,59,20,22],$condtion_district,'is_draft'=>0, 'status'=>1, $condtion_location])->group(['district_code'])->toArray();          
    
                $pacs_today = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);
            
                $pacs_today = $pacs_today->select(['district_code','count' => $pacs_today->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [1,59,20,22],'is_draft'=>0, 'status'=>1, 'DATE(created)' => date('Y-m-d'), $condtion_district,$condtion_location])->group(['district_code'])->toArray();
    
              //  print_r($pacs_today);
    
                //Fisheries
                $fisheries = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);
                
                $fisheries = $fisheries->select(['district_code','count' => $fisheries->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [10,43],'is_draft'=>0, 'status'=>1, $condtion_district,$condtion_location])->group(['district_code'])->toArray();
    
                $fisheries_today = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);
                
                $fisheries_today = $fisheries_today->select(['district_code','count' => $fisheries_today->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [10,43],'is_draft'=>0,'status'=>1, 'DATE(created)' => date('Y-m-d'), $condtion_district,$condtion_location])->group(['district_code'])->toArray();

            }else
            {
            $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);            
            $pacs = $pacs->select(['state_code','count' => $pacs->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [1,59,20,22],$condtion_district,'is_draft'=>0, 'status'=>1, $condtion_location])->group(['state_code'])->toArray();          

            $pacs_today = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);
        
            $pacs_today = $pacs_today->select(['state_code','count' => $pacs_today->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [1,59,20,22],'is_draft'=>0, 'status'=>1, 'DATE(created)' => date('Y-m-d'), $condtion_district,$condtion_location])->group(['state_code'])->toArray();

          //  print_r($pacs_today);

            //Fisheries
            $fisheries = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);
            
            $fisheries = $fisheries->select(['state_code','count' => $fisheries->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [10,43],'is_draft'=>0, 'status'=>1, $condtion_district,$condtion_location])->group(['state_code'])->toArray();

            $fisheries_today = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);
            
            $fisheries_today = $fisheries_today->select(['state_code','count' => $fisheries_today->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [10,43],'is_draft'=>0,'status'=>1, 'DATE(created)' => date('Y-m-d'), $condtion_district,$condtion_location])->group(['state_code'])->toArray();
            }

                //print_r($state_code);

            $this->paginate = ['limit' => 20];
           $district_codes = $this->paginate($state_code);

        }
                     

        //$arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
            
        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

    
        $this->set(compact('district_codes','arr_districts','panchayats','villages','dairies','pacs','fisheries','district_nodal_tatal','state_code','district_nodal_tatal_without_state','pacs_today','dairies_today','fisheries_today','urban_bodys','urban_bodys_wards'));
        
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
