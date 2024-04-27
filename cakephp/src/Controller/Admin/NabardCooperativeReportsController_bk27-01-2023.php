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
class NabardCooperativeReportsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','testing']);
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

           // $this->testing(1,3,5);
        
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

           $state_code= $this->States->find('all')->where(['flag'=>1,$condtion_districtA])->order(['name'=>'ASC'])->select(['state_code']);
        
            $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);            
            $pacs = $pacs->select(['state_code','count' => $pacs->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [1,59,20,22],$condtion_district,'is_draft'=>0,$condtion_location])->group(['state_code'])->toArray();

            $registrationdata = $this->CooperativeRegistrations->find('all')->where(['is_draft'=>0,$condtion_location])->toArray();
            
            $registration_data=[];
            foreach($registrationdata as $key=>$value)
            {
                if($value['sector_of_operation']==1 || $value['sector_of_operation']==59)
                {
                    $registration_data['pacs'][$value['state_code']][]= 1;

                }else if($value['sector_of_operation']==20)
                {
                    $registration_data['fss'][$value['state_code']][]= 1;
                }
                else if($value['sector_of_operation']==22)
                {
                    $registration_data['lamps'][$value['state_code']][]= 1;
                }
                else if($value['sector_of_operation']==9)
                {
                    $registration_data['dairy'][$value['state_code']][]= 1;
                }
                else if($value['sector_of_operation']==10)
                {
                    $registration_data['fishary'][$value['state_code']][]= 1;
                }
            }
           // echo "<pre>";
           // print_r($registration_data);
            
            

            $this->paginate = ['limit' => 36];
           $district_codes = $this->paginate($state_code);

    
                     

        //$arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
            
        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

    
        $this->set(compact('district_codes','arr_districts','panchayats','villages','dairies','pacs','fisheries','district_nodal_tatal','state_code','district_nodal_tatal_without_state','pacs_today','dairies_today','fisheries_today','urban_bodys','registration_data'));
        
    }

    public function ncdList()
    {

        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadMOdel('Blocks');
        $this->loadModel('SectorOperations');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('DistrictsBlocksGpVillages');
       $this->loadMOdel('DistrictCentralCooperativeBank');
       $this->loadMOdel('UrbanLocalBodiesWards');             
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


        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='') {
            $s_sector_operation = trim($this->request->query['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "sector_of_operation_type = '" . $s_sector_operation . "'";
        }else{
            $s_sector_operation =1;
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "sector_of_operation_type = '" . $s_sector_operation . "'";
        }

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);
            $search_condition[] = "sector_of_operation = '" . $s_primary_activity . "'";
        }

        if (isset($this->request->query['secondary_activity']) && $this->request->query['secondary_activity'] !='') {
            $s_secondary_activity = trim($this->request->query['secondary_activity']);
            $this->set('s_secondary_activity', $s_secondary_activity);
            $search_condition[] = "secondary_activity = '" . $s_secondary_activity . "'";
        }

      
        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            $user_id=[];
            $s_district = $this->request->session()->read('Auth.User.district_code');
            $id=$this->request->session()->read('Auth.User.id');
            $idsarray = $this->Users->find('all')->order(['id'=>'ASC'])->where(['status'=>1,'created_by'=>$id])->toArray(); 
            foreach($idsarray as $key=>$value)
            {
                $user_id[]=$value['id'];
            }

            $this->set('s_district', $s_district);
            $search_condition3['created_by IN'] = $user_id;

           // $search_condition[] = "district_code = '" . $s_district . "'";
        }
        else{
            if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
                $s_district = trim($this->request->query['district']);
                $this->set('s_district', $s_district);
                $search_condition[] = "district_code = '" . $s_district . "'";
            }
        }

        if (isset($this->request->query['block']) && $this->request->query['block'] !='') {
            $s_block = trim($this->request->query['block']);
            $this->set('s_block', $s_block);
            $search_condition[] = "block_code = '" . $s_block . "'";
        }

        if (isset($this->request->query['panchayat']) && $this->request->query['panchayat'] !='') {
            $s_panchayat = trim($this->request->query['panchayat']);
            $this->set('s_panchayat', $s_panchayat);
            $search_condition[] = "gram_panchayat_code = '" . $s_panchayat . "'";
        }

        if (isset($this->request->query['village']) && $this->request->query['village'] !='') {
            $s_village = trim($this->request->query['village']);
            $this->set('s_village', $s_village);
            $search_condition[] = "village_code = '" . $s_village . "'";
        }  
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
        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();    
    
        $this->set('sectors',$sectors); 
        $banckarray= $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC'])->toArray();

        $villagesarray = $this->DistrictsBlocksGpVillages->find('all')->where(['status'=>1])->order(['village_name'=>'ASC'])->toArray();
        $dgbgV=[];
        foreach($villagesarray as $key=>$value)
        {
                $dgbgV['block'][$value['block_code']]               =   $value['block_name'];
                $dgbgV['panchayat'][$value['gram_panchayat_code']]  =   $value['gram_panchayat_name'];
                $dgbgV['village'][$value['village_code']]           =   $value['village_name'];
        }
        $this->set('dgbgV',$dgbgV);  


        $this->set('banckarray',$banckarray);
        $arr_location = [
            '1'=> 'Urban',
            '2'=> 'Rural'
        ];
        $this->set('arr_location',$arr_location);


        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')],'order' => ['name' => 'ASC']]);
        }else
        {       
             $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        }
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);


        $districts = [];
        if(!empty($this->request->query['state']))
        {
            $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
        }
 
        $this->set('districts',$districts);
 
        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            
            $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$this->request->session()->read('Auth.User.district_code')])->order(['name'=>'ASC'])->toArray();
        }else
        {
          $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        }
     
 
         $this->set('districts',$districts);


         
       $blocks = [];   
       if($this->request->session()->read('Auth.User.role_id') == 8)
       {
        $blocks = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$this->request->session()->read('Auth.User.district_code')])->distinct()->order(['block_name'=>'ASC'])->toArray();
       }
       else{   
        if(!empty($this->request->query['district']))
        {
            
            $blocks = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$this->request->query['district']])->distinct()->order(['block_name'=>'ASC'])->toArray();
        }
        }

       $this->set('blocks',$blocks);

       // Start PresentFunctionalStatus for dropdown
       $this->loadModel('PresentFunctionalStatus');
       $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();


          #################export Excel################
          if(!empty($this->request->query['export_excel']))
          { 
            $this->loadMOdel('Designations');   
            $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();

             $i=1;
        
             $queryExport = $this->CooperativeRegistrations->find('all', [
             'order' => ['cooperative_society_name' => 'ASC'],  
             'contain'=>['AreaOfOperationLevel','CooperativeRegistrationPacs'],        
             'conditions' => [$searchString,$search_condition2,$search_condition3]            
             ])->where(['is_draft'=>0,'status'=>1]);

             $queryExport->hydrate(false);
             $ExportResultData = $queryExport->toArray();
             $fileName = "NCD".date("d-m-y:h:s").".xls";
             $data = array(); 
           
             $headerRow = array("S.No", "Name of the PACS/LAPS/FSS/Others(M)", "Registration Number of PACS", "Year of Registration", "Category-PACS/LAMPS/FSS/Other(M)", "State", "District", "Block", "Panchayat", "Village","Name of the Bank to which the PACS is Affilited","Total No.of members","No Of Villages Covered By Pacs","Office  Building","status","ContactName","Designation",'Mobile','Functional_Status');

             $buildingTypes = ['1'=>'Own Building','2'=>'Rented Building','3'=>'Rent Free Building', '4'=> 'Leased Building','5'=>'Building Provided by the Government']; 

             $astatus = ['1'=>'Accepted','2'=>'Returned','0'=>'Pending'];
             foreach($ExportResultData As $rows){

              $refrence_year=  date('Y',strtotime($rows['date_registration']));

              $registration_number="'". $this->strClean($rows['registration_number'])."'";

              $function_status=  $this->strClean($presentFunctionalStatus[$rows['functional_status']]);

           //   $this->strClean();

             $data[] = [$i,  $this->strClean(str_replace('  ', ' ', $rows['cooperative_society_name'])), $registration_number,  $this->strClean($refrence_year), $sectors[$rows['sector_of_operation']],  $stateOption[$rows['state_code']] , $districts[$rows['district_code']], $dgbgV['block'][$rows['block_code']] , $dgbgV['panchayat'][$rows['gram_panchayat_code']], $dgbgV['village'][$rows['village_code']], $this->strClean($banckarray[trim($rows['affiliated_union_federation_name'])]) ,$rows['members_of_society'], count($rows['area_of_operation_level']),$buildingTypes[$rows['cooperative_registration_pacs'][0]['building_type']], $astatus[$rows['is_approved']], trim($rows['contact_person']),  $this->strClean($designations[$rows['designation']]),$rows['mobile'], $function_status]; 
             $i++;
             }

             $this->exportInExcelNew($fileName, $headerRow, $data);           

         }
             #################export Excel################

       //  print_r($search_condition3);

        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['cooperative_society_name' => 'ASC'],
            'contain'=>['AreaOfOperationLevel','CooperativeRegistrationPacs'],
            'conditions' => [$searchString,$search_condition2,$search_condition3]            
        ])->where(['is_draft'=>0,'status'=>1]);

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

        $this->set(compact('CooperativeRegistrations'));   

      

       

        $sectorOperations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1,'id'=>1])->toArray();

        $this->set('sectorOperations', $sectorOperations);

        $primary_activities = [];

        if(!empty($this->request->query['sector_operation']))
        {
            //for credit
            $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$this->request->query['sector_operation'],'status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        }

        $this->set('primary_activities',$primary_activities);       

      


       $panchayats = [];
      
       if(!empty($this->request->query['block']) )
       {
          
           $panchayats = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$this->request->query['block']])->distinct()->order(['gram_panchayat_name'=>'ASC'])->toArray();  
           $this->set('panchayats',$panchayats); 
       }
       
       
     //  $panchayatsarray = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  

           $this->set('panchayatsarray',$panchayatsarray);

       $villages = [];

       if(!empty($this->request->query['panchayat']))
       {
          
           $villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$this->request->query['panchayat']])->distinct()->order(['village_name'=>'ASC'])->toArray();

           $this->set('villages',$villages);  
       }

        //   $villagesarray = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1])->order(['village_name'=>'ASC'])->toArray();
        
    }

    public function ncdList1()
    {

        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadMOdel('Blocks');
        $this->loadModel('SectorOperations');
        $this->loadModel('Nabrad');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('DistrictsBlocksGpVillages');
       $this->loadMOdel('DistrictCentralCooperativeBank');
       $this->loadMOdel('UrbanLocalBodiesWards');             
       set_time_limit(0);
       ini_set('memory_limit', '-1');
       $asd=$this->Nabrad->find('all')->order(['id'=>'ASC'])->where(['status'=>1,'id'=>1])->toArray();

      // print_r($asd);

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


        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='') {
            $s_sector_operation = trim($this->request->query['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "sector_of_operation_type = '" . $s_sector_operation . "'";
        }else{
            $s_sector_operation =1;
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "sector_of_operation_type = '" . $s_sector_operation . "'";
        }

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);
            $search_condition[] = "sector_of_operation = '" . $s_primary_activity . "'";
        }

        if (isset($this->request->query['secondary_activity']) && $this->request->query['secondary_activity'] !='') {
            $s_secondary_activity = trim($this->request->query['secondary_activity']);
            $this->set('s_secondary_activity', $s_secondary_activity);
            $search_condition[] = "secondary_activity = '" . $s_secondary_activity . "'";
        }

      
        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            $user_id=[];
            $s_district = $this->request->session()->read('Auth.User.district_code');
            $id=$this->request->session()->read('Auth.User.id');
            $idsarray = $this->Users->find('all')->order(['id'=>'ASC'])->where(['status'=>1,'created_by'=>$id])->toArray(); 
            foreach($idsarray as $key=>$value)
            {
                $user_id[]=$value['id'];
            }

            $this->set('s_district', $s_district);
            $search_condition3['created_by IN'] = $user_id;

           // $search_condition[] = "district_code = '" . $s_district . "'";
        }
        else{
            if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
                $s_district = trim($this->request->query['district']);
                $this->set('s_district', $s_district);
                $search_condition[] = "district_code = '" . $s_district . "'";
            }
        }

        if (isset($this->request->query['block']) && $this->request->query['block'] !='') {
            $s_block = trim($this->request->query['block']);
            $this->set('s_block', $s_block);
            $search_condition[] = "block_code = '" . $s_block . "'";
        }

        if (isset($this->request->query['panchayat']) && $this->request->query['panchayat'] !='') {
            $s_panchayat = trim($this->request->query['panchayat']);
            $this->set('s_panchayat', $s_panchayat);
            $search_condition[] = "gram_panchayat_code = '" . $s_panchayat . "'";
        }

        if (isset($this->request->query['village']) && $this->request->query['village'] !='') {
            $s_village = trim($this->request->query['village']);
            $this->set('s_village', $s_village);
            $search_condition[] = "village_code = '" . $s_village . "'";
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
        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();    
    
        $this->set('sectors',$sectors); 
        $banckarray= $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC'])->toArray();

        $villagesarray = $this->DistrictsBlocksGpVillages->find('all')->where(['status'=>1])->order(['village_name'=>'ASC'])->toArray();
        $dgbgV=[];
        foreach($villagesarray as $key=>$value)
        {
                $dgbgV['block'][$value['block_code']]               =   $value['block_name'];
                $dgbgV['panchayat'][$value['gram_panchayat_code']]  =   $value['gram_panchayat_name'];
                $dgbgV['village'][$value['village_code']]           =   $value['village_name'];
        }
        $this->set('dgbgV',$dgbgV);  

        $this->set('banckarray',$banckarray);
          #################export Excel################
          if(!empty($this->request->query['export_excel']))
          { 

             $i=1;
        
             $queryExport = $this->CooperativeRegistrations->find('all', [
             'order' => ['cooperative_society_name' => 'ASC'],  
             'contain'=>['AreaOfOperationLevel','CooperativeRegistrationPacs'],        
             'conditions' => [$searchString,$search_condition2,$search_condition3]            
             ])->where(['is_draft'=>0,'status'=>1]);

             $queryExport->hydrate(false);
             $ExportResultData = $queryExport->toArray();
             $fileName = "NCD".date("d-m-y:h:s").".xls";
             $data = array(); 
           
             $headerRow = array("S.No", "Name of the PACS/LAPS/FSS/Others(M)", "Registration Number of PACS", "Year of Registration", "Category-PACS/LAMPS/FSS/Other(M)", "Panchayat", "Village","Name of the Bank to which the PACS is Affilited","Total No.of members","No Of Villages Covered By Pacs","Office  Building");

             $buildingTypes = ['1'=>'Own Building','2'=>'Rented Building','3'=>'Rent Free Building', '4'=> 'Leased Building','5'=>'Building Provided by the Government']; 
             foreach($ExportResultData As $rows){

              $refrence_year=  date('Y',strtotime($rows['date_registration']));

             $data[] = [$i, $rows['cooperative_society_name'],$rows['registration_number'],$refrence_year,$sectors[$rows['sector_of_operation']], $dgbgV['panchayat'][$rows['gram_panchayat_code']], $dgbgV['village'][$rows['village_code']], $banckarray[$rows['affiliated_union_federation_name']],$rows['members_of_society'], count($rows['area_of_operation_level']),$buildingTypes[$rows['cooperative_registration_pacs'][0]['building_type']]]; 
             $i++;
             }

             $this->exportInExcelNew($fileName, $headerRow, $data);           

         }
             #################export Excel################



        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['cooperative_society_name' => 'ASC'],
            'contain'=>['AreaOfOperationLevel','CooperativeRegistrationPacs'],
            'conditions' => [$searchString,$search_condition2,$search_condition3]            
        ])->where(['is_draft'=>0,'status'=>1]);

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

        $this->set(compact('CooperativeRegistrations'));
        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')],'order' => ['name' => 'ASC']]);
        }else
        {       
             $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        }
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);

        $arr_location = [
            '1'=> 'Urban',
            '2'=> 'Rural'
        ];
        $this->set('arr_location',$arr_location);

       

        $sectorOperations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1,'id'=>1])->toArray();

        $this->set('sectorOperations', $sectorOperations);

        $primary_activities = [];

        if(!empty($this->request->query['sector_operation']))
        {
            //for credit
            $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$this->request->query['sector_operation'],'status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        }

        $this->set('primary_activities',$primary_activities);


        

       $districts = [];
       if(!empty($this->request->query['state']))
       {
           $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
       }

       $this->set('districts',$districts);

       if($this->request->session()->read('Auth.User.role_id') == 8)
       {
           
           $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$this->request->session()->read('Auth.User.district_code')])->order(['name'=>'ASC'])->toArray();
       }else
       {
         $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       }
    

        $this->set('districts',$districts);

       $blocks = [];   
       if($this->request->session()->read('Auth.User.role_id') == 8)
       {
        $blocks = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$this->request->session()->read('Auth.User.district_code')])->distinct()->order(['block_name'=>'ASC'])->toArray();
       }
       else{   
        if(!empty($this->request->query['district']))
        {
            
            $blocks = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$this->request->query['district']])->distinct()->order(['block_name'=>'ASC'])->toArray();
        }
        }

       $this->set('blocks',$blocks);

       $panchayats = [];
      
       if(!empty($this->request->query['block']) )
       {
          
           $panchayats = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$this->request->query['block']])->distinct()->order(['gram_panchayat_name'=>'ASC'])->toArray();  
           $this->set('panchayats',$panchayats); 
       }
       
       
     //  $panchayatsarray = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  

           $this->set('panchayatsarray',$panchayatsarray);

       $villages = [];

       if(!empty($this->request->query['panchayat']))
       {
          
           $villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$this->request->query['panchayat']])->distinct()->order(['village_name'=>'ASC'])->toArray();

           $this->set('villages',$villages);  
       }

        //   $villagesarray = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1])->order(['village_name'=>'ASC'])->toArray();

        

        
    }



    public function nabardList()
    {
        //nabard_pacs_data
        $this->loadMOdel('NabardPacsData');
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadMOdel('Blocks');
        $this->loadModel('SectorOperations');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('DistrictsBlocksGpVillages');
       $this->loadMOdel('DistrictCentralCooperativeBank');
       $this->loadMOdel('UrbanLocalBodiesWards');             
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

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $location = trim($this->request->query['location']);
            $this->set('location', $location);
            $search_condition[] = "location_of_head_quarter = '" . $location . "'";
        }

        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='') {
            $s_sector_operation = trim($this->request->query['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "sector_of_operation_type = '" . $s_sector_operation . "'";
        }else{
            $s_sector_operation =1;
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "sector_of_operation_type = '" . $s_sector_operation . "'";
        }

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);
            $search_condition[] = "sector_of_operation = '" . $s_primary_activity . "'";
        }

        if (isset($this->request->query['secondary_activity']) && $this->request->query['secondary_activity'] !='') {
            $s_secondary_activity = trim($this->request->query['secondary_activity']);
            $this->set('s_secondary_activity', $s_secondary_activity);
            $search_condition[] = "secondary_activity = '" . $s_secondary_activity . "'";
        }
   

      
        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            $s_district = $this->request->session()->read('Auth.User.district_code');
            $this->set('s_district', $s_district);
            $search_condition[] = "district_code = '" . $s_district . "'";
        }else{
            if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
                $s_district = trim($this->request->query['district']);
                $this->set('s_district', $s_district);
                $search_condition[] = "district_code = '" . $s_district . "'";
            }
        }
      

        if (isset($this->request->query['block']) && $this->request->query['block'] !='') {
            $s_block = trim($this->request->query['block']);
            $this->set('s_block', $s_block);
           // $search_condition[] = "block_code = '" . $s_block . "'";
        }

        if (isset($this->request->query['panchayat']) && $this->request->query['panchayat'] !='') {
            $s_panchayat = trim($this->request->query['panchayat']);
            $this->set('s_panchayat', $s_panchayat);
            $search_condition[] = "gram_panchayat_code = '" . $s_panchayat . "'";
        }

        if (isset($this->request->query['village']) && $this->request->query['village'] !='') {
            $s_village = trim($this->request->query['village']);
            $this->set('s_village', $s_village);
            $search_condition[] = "village_code = '" . $s_village . "'";
        }       

        $search_condition3='';
        

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

        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            $stateOption =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1, 'state_code'=>$this->request->session()->read('Auth.User.state_code')],'order' => ['name' => 'ASC']])->toArray();   
        }else
        {
            $stateOption =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']])->toArray();   
        }
         $this->set('sOption',$stateOption);

       

      


            
        $districts = [];
        if(!empty($this->request->query['state']))
        {
            $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
        }
        
       $this->set('districts',$districts);
       if($this->request->session()->read('Auth.User.role_id') == 8)
       {
           
           $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$this->request->session()->read('Auth.User.district_code')])->order(['name'=>'ASC'])->toArray();
       }else
       {
         $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       }
       $this->set('districts',$districts);


       $blocks = [];    
       
       if($this->request->session()->read('Auth.User.role_id') == 8)
       {
        $blocks = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$this->request->session()->read('Auth.User.district_code')])->distinct()->order(['block_name'=>'ASC'])->toArray();
       }
       else
       {
       if(!empty($this->request->query['district']))
       {
         
           $blocks = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$this->request->query['district']])->distinct()->order(['block_name'=>'ASC'])->toArray();
       }
        }

       $this->set('blocks',$blocks);
       $panchayats = [];
      
       if(!empty($this->request->query['block']) )
       {
          
           $panchayats = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$this->request->query['block']])->distinct()->order(['gram_panchayat_name'=>'ASC'])->toArray();  
           $this->set('panchayats',$panchayats); 
       }
       if(!empty($this->request->query['panchayat']))
       {
          
           $villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$this->request->query['panchayat']])->distinct()->order(['village_name'=>'ASC'])->toArray();

           $this->set('villages',$villages);  
       }

        #################export Excel################
            if(!empty($this->request->query['export_excel']))
             { 

                $i=1;

                $queryExport = $this->NabardPacsData->find('all', [
                'order' => ['cooperative_society_name' => 'ASC'],          
                'conditions' => [$searchString,$search_condition2,$search_condition3]            
                ]);

                $queryExport->hydrate(false);
                $ExportResultData = $queryExport->toArray();
                $fileName = "Nabard_Pac_Data".date("d-m-y:h:s").".xls";
                $data = array(); 

                $headerRow = array("S.No", "Name of the PACS/LAPS/FSS/Others(M)", "Registration Number of PACS", "Year of Registration", "Category-PACS/LAMPS/FSS/Other(M)", "Panchayat", "Village","Name of the Bank to which the PACS is Affilited","Total No.of members","No Of Villages Covered By Pacs","Office  Building");

                foreach($ExportResultData As $rows)
                {
                    $data[] = [$i, trim($rows['cooperative_society_name']), trim($rows['registration_number']), $rows['reference_year'], $rows['cooperative_society_type'], trim($rows['gram_panchayat_name']), trim($rows['village_name']), trim($rows['affiliated_union_federation_name']),$rows['number_of_member'], $rows['no_village_covered'], $rows['building_type']]; 
                    $i++;
                }

                $this->exportInExcelNew($fileName, $headerRow, $data);           

            }
                #################export Excel################

        $registrationQuery = $this->NabardPacsData->find('all', [
            'order' => ['cooperative_society_name' => 'ASC'],          
            'conditions' => [$searchString,$search_condition2,$search_condition3]            
        ]);

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        $this->set(compact('CooperativeRegistrations'));

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

    public function getBlocks(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $district_code=$this->request->query('district_code');    
            //$this->loadMOdel('Blocks');
            //$Blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$district_code])->order(['name'=>'ASC']);
			$this->loadMOdel('DistrictsBlocksGpVillages');
            $Blocks=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$district_code])->group(['block_code']);
			
            $option_html='<option value="">Select</option>';
            if($Blocks->count()>0){
                foreach($Blocks as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.$value.'</option>';
                }
            }
            echo $option_html;
        }
        exit;
    }
    public function getGp(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
             $block_code=$this->request->query('block_code');    
            $this->loadMOdel('DistrictsBlocksGpVillages');
            $DistrictsBlocksGpVillages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);
            $option_html='<option value="">Select</option>';
            if($DistrictsBlocksGpVillages->count()==0){
                $option_html.='<option value="0">Gram Panchayat</option>';
            }
            if($DistrictsBlocksGpVillages->count()>0){
                foreach($DistrictsBlocksGpVillages as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.$value.'</option>';
                }
            }
            echo $option_html;
        }
        exit;
    }
    public function getVillages(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $gp_code=$this->request->query('gp_code');    
            $this->loadMOdel('DistrictsBlocksGpVillages');
            $DistrictsBlocksGpVillages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$gp_code])->group(['village_code'])->order(['village_name'=>'ASC']);
            
            $option_html='<option value="">Select</option>';
            if($DistrictsBlocksGpVillages->count()==0){
                $option_html.='<option value="0">Village</option>';
            }
            if($DistrictsBlocksGpVillages->count()>0){
                foreach($DistrictsBlocksGpVillages as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.$value.'</option>';
                }
            }
            echo $option_html;
        }
        exit;
    }  
    
    
   

 
}
