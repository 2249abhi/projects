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
class StateWiseReportsController extends AppController
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
        $this->loadModel('PrimaryActivities');       
        $this->loadMOdel('CooperativeRegistrations');      

        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        $district_codes = [];
        $dairies = [];
        $pacs = [];
        $fisheries = [];                  
        $condtion_district=[];
        $condtion_districtA=[];
        $condtion_location=[];
        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
        
        $this->set('sectors',$sectors);

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $s_state = trim($this->request->query['state']);
           
            $this->set('s_state', $s_state);
            $condtion_district['state_code'] = $this->request->query['state'];
             $condtion_districtA['state_code'] = $this->request->query['state'];
        } 
        
        if (isset($this->request->query['sector']) && $this->request->query['sector'] !='') {
            $s_primary_activity = trim($this->request->query['sector']);
            if($s_primary_activity==1)
            {
                $condtion_district[] = "sector_of_operation IN(1,59,20,22)";
            }else{
                $condtion_district[] = "sector_of_operation = '" . $s_primary_activity . "'";
            }
            $this->set('s_primary_activity', $s_primary_activity);
         
        }
        
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }     

        if (isset($this->request->query['sector']) && $this->request->query['sector'] !='') {
            $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
            $query->hydrate(false);
            $stateOption = $query->toArray();
            $this->set('sOption',$stateOption);
          
                $state_code= $this->States->find('all')->where(['flag'=>1,$condtion_districtA])->order(['name'=>'ASC'])->select(['state_code'])->group(['state_code']);            
          
              //  $dairies = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);            
               // $dairies = $dairies->select(['state_code','count' => $dairies->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [9,37], 'is_draft'=>0, 'is_approved !='=>2, 'status'=>1, $condtion_district])->group(['state_code'])->toArray();

                $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);            
                $pacs = $pacs->select(['state_code','count' => $pacs->func()->count('sector_of_operation')])->where([$condtion_district,'is_draft'=>0, 'is_approved !='=>2, 'status'=>1])->group(['state_code'])->toArray();           

                //Fisheries
              //  $fisheries = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);
                
              //  $fisheries = $fisheries->select(['state_code','count' => $fisheries->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [10,43],'is_draft'=>0, 'is_approved !='=>2, 'status'=>1, $condtion_district,$condtion_location])->group(['state_code'])->toArray();


                $this->paginate = ['limit' => 20];
                $district_codes = $this->paginate($state_code);
                
                
                if(!empty($this->request->query['export_excel']))
                { 
        
                 $i=1;
                 $fileName = "State_Wise_Distribution_number_of_PACS/LAMPS/FSS_Dairy_And_Fishery".date("d-m-y:h:s").".xls";
                 $data = array(); 
               
                 $ExportResultData= $this->States->find('all')->where(['flag'=>1,$condtion_districtA])->order(['name'=>'ASC'])->select(['state_code','name'])->group(['state_code'])->toarray();  
               
                 $headerRow = array("S.No", "State-Name", "No Of Society");
                 $astatus = ['1'=>'Accepted','0'=>'Pending'];
                 foreach($ExportResultData As $rows)
                 {        
                    $total=$pacs[$rows['state_code']] + $dairies[$rows['state_code']] + $fisheries[$rows['state_code']];
                 //   $pacs=
                 $data[] = [$i, $rows['name'], $pacs[$rows['state_code']]?? 0]; 
                 $i++;
                 }
        
                 $this->exportInExcelNew($fileName, $headerRow, $data);           
        
             }
            }
           
            
       
        $this->set(compact('district_codes','dairies','pacs','fisheries','state_code'));
        
    }
     public function society()
     {
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');      
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {
            $societyName = trim($this->request->query['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "cooperative_society_name like '%" . $societyName . "%'";
        }

        if (!empty($this->request->query['registration_number'])) {
            $registrationNumber = trim($this->request->query['registration_number']);
            $this->set('registrationNumber', $registrationNumber);
            $search_condition[] = "registration_number like '%" . $registrationNumber . "%'";
        }

        if (!empty($this->request->query['cooperative_id'])) {
            $cooperativeId = trim($this->request->query['cooperative_id']);
            $this->set('cooperativeId', $cooperativeId);
            $search_condition[] = "cooperative_id like '%" . $cooperativeId . "%'";
        }

        if (!empty($this->request->query['reference_year'])) {
            $referenceYear = trim($this->request->query['reference_year']);
            $this->set('referenceYear', $referenceYear);
            $search_condition[] = "reference_year like '%" . $referenceYear . "%'";
        }

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $state = trim($this->request->query['state']);
            $this->set('state', $state);
            $search_condition[] = "state_code = '" . $state . "'";
        }
   


        if (isset($this->request->query['sociaty']) && $this->request->query['sociaty'] !='') {
            $s_primary_activity = trim($this->request->query['sociaty']);
            if($s_primary_activity==1)
            {
                $search_condition[] = "sector_of_operation IN(1,59,20,22)";
            }else{
                $search_condition[] = "sector_of_operation = '" . $s_primary_activity . "'";
            }
            $this->set('s_primary_activity', $s_primary_activity);
         
        }

      

        //=============================Urban=====================================

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "district_code = '" . $s_district . "'";
        }

     


        $search_condition3='';
       


        if (isset($this->request->query['is_approved']) && $this->request->query['is_approved'] !='') {
            $is_approved = trim($this->request->query['is_approved']);
            $this->set('is_approved', $is_approved);
            $search_condition[] = "is_approved = '" . $is_approved . "'";
        }else{
            $search_condition[] = "is_approved  != 2";
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

        $this->loadModel('CooperativeRegistrations');


        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => [$searchString,$search_condition2,$search_condition3]
        ])->where(['is_draft'=>0,'status'=>'1']);
        $this->paginate = ['limit' => 25];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        $this->set(compact('CooperativeRegistrations'));       

        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);

        $arr_location = [
            '1'=> 'Urban',
            '2'=> 'Rural'
        ];
        $this->set('arr_location',$arr_location);

        $this->loadModel('SectorOperations');

        $sectorOperations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();

        $this->set('sectorOperations', $sectorOperations);

        $primary_activities = [];

       



       //======================urban================================================

       if(!empty($this->request->query['state']) )
       {
           $this->loadMOdel('UrbanLocalBodies');

           $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
   
           $this->set('local_categories',$local_categories);
       }
    

      

       $this->set('localbodies',$localbodies); 

         
        

       //======================urban==============================================



       $districts = [];

       if(!empty($this->request->query['state']))
       {
           $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
       }else if($this->request->session()->read('Auth.User.role_id') == 11)
       {
        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->session()->read('Auth.User.state_code')])->order(['name'=>'ASC'])->toArray();
       }

       $this->set('districts',$districts);

       $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

        $this->set('arr_districts',$arr_districts);

       $blocks = [];
       if(!empty($this->request->query['district']) && $this->request->query['location'] == 2)
       {
           $this->loadMOdel('Blocks');

           $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$this->request->query['district']])->order(['name'=>'ASC'])->toArray();
       }

       $this->set('blocks',$blocks);

       $panchayats = [];

       if(!empty($this->request->query['block']) && $this->request->query['location'] == 2)
       {
           $this->loadMOdel('DistrictsBlocksGpVillages');

           $panchayats = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$this->request->query['block']])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  

           $this->set('panchayats',$panchayats); 
       }

       $villages = [];

       if(!empty($this->request->query['panchayat']) && $this->request->query['location'] == 2)
       {
           $this->loadMOdel('DistrictsBlocksGpVillages');

           $villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$this->request->query['panchayat']])->order(['village_name'=>'ASC'])->toArray();

           $this->set('villages',$villages);  
       }

       $this->loadModel('SectorOperations');

        // if($this->request->session()->read('Auth.User.role_id') == 11)
        // {
        //      $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1, 'id NOT IN'=>['20','22']])->toArray();
        // }else
        // {
             $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
        //}

      
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);

             
        if(!empty($this->request->query['export_excel']))
        { 

         $i=1;
       //  mysql_set_charset("utf8");
         $queryExport = $this->CooperativeRegistrations->find('all', [
         'order' => ['created' => 'ASC'],  
        // 'contain'=>['AreaOfOperationLevel','CooperativeRegistrationPacs'],        
        'conditions' => [$searchString,$search_condition2,$search_condition3]          
         ])->where(['is_draft'=>0,'status'=>1]);

         $queryExport->hydrate(false);
         $ExportResultData = $queryExport->toArray();
         $fileName = "all_registration".date("d-m-y:h:s").".xls";
         $data = array(); 
           // Start PresentFunctionalStatus for dropdown
       $this->loadModel('PresentFunctionalStatus');
       $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

       
         $headerRow = array("S.No", "Cooperative Society Name", "Location", "State", "District-Urban Local Body", "Sector", "Registration Number","Cooperative ID","Reference Year","Date of Registration","Status","Functional-Status");
         $astatus = ['1'=>'Accepted','0'=>'Pending'];
         foreach($ExportResultData As $rows){
            $dist_urbn='';
          $refrence_year =  date('Y',strtotime($rows['date_registration']));
         $location_array = [1=>'Urban',2=>'Rural'];
          $location=$location_array[$rows['location_of_head_quarter']];
            $state_name = $stateOption[$rows['state_code']];

            if($rows['location_of_head_quarter'] == 2)
            {
                $dist_urbn = $arr_districts[$rows['district_code']];
            }
            else if($rows['location_of_head_quarter'] == 1)
            {
                $dist_urbn = $arr_localbodies[$rows['urban_local_body_code']] ;
            }

           $coperative_id = str_pad($rows['sector_of_operation'], 2, "0", STR_PAD_LEFT) . str_pad($rows['cooperative_id_num'], 7, "0", STR_PAD_LEFT);

           $registration_number="'".trim($rows['registration_number'])."'";

           $function_status= $presentFunctionalStatus[$rows['functional_status']];


         $data[] = [$i, $rows['cooperative_society_name'], $location,  $state_name, $dist_urbn , $sectors[$rows['sector_of_operation']],  $registration_number, $coperative_id, $rows['reference_year'], $rows['date_registration'],$astatus[$rows['is_approved']], $function_status]; 
         $i++;
         }

         $this->exportInExcelNew($fileName, $headerRow, $data);           

     }
         #################export Excel################
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
