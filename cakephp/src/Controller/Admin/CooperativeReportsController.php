<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
/**
 * CooperativeRegistrations Controller
 *
 * @property \App\Model\Table\CooperativeRegistrationsTable $CooperativeRegistrations
 *
 * @method \App\Model\Entity\State[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CooperativeReportsController extends AppController
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
        $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','getdccb','getfederationlevel','approval','bulkapproval','getUrbanRural','functionaldistrictwisesocietycount']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        ################################
        // this function use for all registration  list (pendding + approved)
        #################################

        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $this->Flash->error(__('You are not authorized.'));
            return $this->redirect(['action' => 'dataEntryPending']);    
            
        }

     // $get_state_id = $this->request->session()->read('Auth.User.state_code');

        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
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

        if (isset($this->request->query['is_multi_state']) && $this->request->query['is_multi_state'] !='') {
            $is_multi_state_data = trim($this->request->query['is_multi_state']);
            $this->set('is_multi_state_data', $is_multi_state_data);
            $search_condition[] = "is_multi_state = '" . $is_multi_state_data . "'";
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
        }

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);
            $search_condition[] = "sector_of_operation = '" . $s_primary_activity . "'";
        }

        if (isset($this->request->query['functional_status']) && $this->request->query['functional_status'] !='') {
            $s_functional_status = trim($this->request->query['functional_status']);
            $this->set('s_functional_status', $s_functional_status);
            $search_condition[] = "functional_status = '" . $s_functional_status . "'";
        }

        if (isset($this->request->query['secondary_activity']) && $this->request->query['secondary_activity'] !='') {
            $s_secondary_activity = trim($this->request->query['secondary_activity']);
            $this->set('s_secondary_activity', $s_secondary_activity);
            $search_condition[] = "secondary_activity = '" . $s_secondary_activity . "'";
        }

         //=============================Urban=====================================

         if (isset($this->request->query['local_category']) && $this->request->query['local_category'] !='') {
            $s_local_category = trim($this->request->query['local_category']);
            $this->set('s_local_category', $s_local_category);
            $search_condition[] = "urban_local_body_type_code = '" . $s_local_category . "'";
        }

        if (isset($this->request->query['local_body']) && $this->request->query['local_body'] !='') {
            $s_local_body = trim($this->request->query['local_body']);
            $this->set('s_local_body', $s_local_body);
            $search_condition[] = "urban_local_body_code = '" . $s_local_body . "'";
        }

        if (isset($this->request->query['ward']) && $this->request->query['ward'] !='') {
            $s_ward = trim($this->request->query['ward']);
            $this->set('s_ward', $s_ward);
            $search_condition[] = "locality_ward_code = '" . $s_ward . "'";
        }

        //=============================Urban=====================================

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "operational_district_code = '" . $s_district . "'";
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

        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            $nodal_data_entry_ids = [];
            $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();
            
            if(!empty($nodal_data_entry_ids)){
                $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
                $nodal_data_entry_ids=implode(",",$nodal_data_entry_ids);
                
                $search_condition2 = "created_by IN (" . $nodal_data_entry_ids . ")";
            } else{
                $search_condition2 = "created_by IN (0)";
            }
        }

        $search_condition3='';
         if($this->request->session()->read('Auth.User.role_id') == 11 ||  $this->request->session()->read('Auth.User.role_id')==56 )
        {
           $state= $this->request->session()->read('Auth.User.state_code');

          
             $search_condition3  = "state_code = '" . $state . "'";

             ##################### for use primary activity accroding show record created by ravindra as par anayat sir #######################

             $this->loadModel('UserPrimary');
            $primary_is_array= $this->UserPrimary->find('list',['keyField'=>'primary_activity_id','valueField'=>'user_id'])->where(['user_id'=>$this->request->session()->read('Auth.User.id')])->toArray(); 
            $search_condition4='';
            if(!empty($primary_is_array))
            {
                $p_ids  =   array_keys($primary_is_array);           
                $pp     =   implode(",",$p_ids);
                $search_condition4 = "sector_of_operation IN (" . $pp . ")";
            }
            $this->set('primary_is_array', $primary_is_array);
            #######################end #####################################

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

        $this->loadModel('CooperativeRegistrations');


        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => [$searchString,$search_condition2,$search_condition3,$search_condition4]
        ])->select(['location_of_head_quarter','state_code','district_code','urban_local_body_code','sector_of_operation','cooperative_id_num','block_code','gram_panchayat_code','functional_status','cooperative_society_name','registration_number','is_coastal','water_body_type_id','reference_year','date_registration','is_approved','id'])->where(['is_draft'=>0,'status'=>'1']);
        $this->paginate = ['limit' => 25];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        $this->set(compact('CooperativeRegistrations'));       

    //     if($this->request->session()->read('Auth.User.role_id') == 56)
    //     {
    //     $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']])->where(['state_code'=>$get_state_id]);
    //     $query->hydrate(false);
    //     $stateOption = $query->toArray();
    //     $this->set('sOption',$stateOption);
	// } else {
	
        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);

        $this->loadModel('DistrictCentralCooperativeBank');
        $SecondaryActivities = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>''])->order(['name'=>'ASC'])->toArray();

     //   print_r($SecondaryActivities);
       // die;
	//}

        $arr_location = [
            '1'=> 'Urban',
            '2'=> 'Rural'
        ];
        $this->set('arr_location',$arr_location);

        $this->loadModel('SectorOperations');

        $sectorOperations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();

        $this->set('sectorOperations', $sectorOperations);

        $primary_activities = [];

        if(!empty($this->request->query['sector_operation']))
        {
            //for credit
            $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$this->request->query['sector_operation'],'status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        }

        $this->set('primary_activities',$primary_activities);


        // Start SecondaryActivities for dropdown
        $this->loadModel('SecondaryActivities');

        $secondary_activities = $this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $this->set('secondary_activities',$secondary_activities);

       //======================urban================================================

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 1)
       {
           $this->loadMOdel('UrbanLocalBodies');

           $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
   
           $this->set('local_categories',$local_categories);
       }
      if($this->request->session()->read('Auth.User.role_id') == 11)
       {
            $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();   
            $this->set('local_categories',$local_categories);
       }
        
       if(!empty($this->request->query['local_category']) && $this->request->query['location'] == 1)
       {
           

           $localbodies = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$this->request->query['local_category'],'state_code'=>$state])->order(['local_body_name'=>'ASC'])->toArray();
          
       }
    

       $this->set('localbodies',$localbodies); 

       if(!empty($this->request->query['local_body']) && $this->request->query['location'] == 1)
       {
           $this->loadMOdel('UrbanLocalBodiesWards');

           $wards = $this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$this->request->query['local_body'],'state_code'=>$this->request->query['state']])->order(['ward_name'=>'ASC'])->toArray();
       }
       
       $this->set('wards',$wards);      
        

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

       // if(!empty($this->request->query['block']) && $this->request->query['location'] == 2)
       // {
       //     $this->loadMOdel('DistrictsBlocksGpVillages');

       //     $panchayats = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$this->request->query['block']])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  

       //     $this->set('panchayats',$panchayats); 
       // }



       $villages = [];

       if(!empty($this->request->query['panchayat']) && $this->request->query['location'] == 2)
       {
           $this->loadMOdel('DistrictsBlocksGpVillages');

           $villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$this->request->query['panchayat']])->order(['village_name'=>'ASC'])->toArray();

           $this->set('villages',$villages);  
       }

       $this->loadModel('SectorOperations');

        
             $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
           
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);
        
        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('presentFunctionalStatus',$presentFunctionalStatus);  
             
        if(!empty($this->request->query['export_excel']))
        { 

                $this->loadMOdel('DistrictsBlocksGpVillages');

           $panchayats = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray(); 

           $bloack = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1])->group(['block_code'])->order(['block_code'=>'ASC'])->toArray(); 
         $i=1;
       //  mysql_set_charset("utf8");

       
       
            $this->loadModel('CooperativeRegistrationPacs');
            $this->loadModel('CooperativeRegistrationsLands');

            $queryExport = $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'ASC'],  
            'contain'=>['CooperativeRegistrationPacs','CooperativeRegistrationsLands'],        
            'conditions' => [$searchString,$search_condition2,$search_condition3,$search_condition4]          
            ])->select(['CooperativeRegistrations.id','CooperativeRegistrations.location_of_head_quarter','CooperativeRegistrations.state_code','CooperativeRegistrations.district_code','CooperativeRegistrations.urban_local_body_code','CooperativeRegistrations.sector_of_operation','CooperativeRegistrations.cooperative_id_num','CooperativeRegistrations.block_code','CooperativeRegistrations.gram_panchayat_code','CooperativeRegistrations.functional_status','CooperativeRegistrations.cooperative_society_name','CooperativeRegistrations.registration_number','CooperativeRegistrations.is_coastal','CooperativeRegistrations.water_body_type_id','CooperativeRegistrations.is_affiliated_union_federation','CooperativeRegistrations.affiliated_union_federation_level','CooperativeRegistrations.affiliated_union_federation_name',
            'CooperativeRegistrations.financial_audit','CooperativeRegistrations.is_dividend_paid','CooperativeRegistrations.audit_complete_year','CooperativeRegistrations.category_audit','CooperativeRegistrations.is_profit_making','CooperativeRegistrations.annual_turnover','CooperativeRegistrations.dividend_rate','CooperativeRegistrations.contact_person','CooperativeRegistrations.mobile','CooperativeRegistrations.designation','CooperativeRegistrations.landline','CooperativeRegistrations.email','CooperativeRegistrations.reference_year','CooperativeRegistrations.date_registration','CooperativeRegistrations.is_approved'])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.status'=>1]);

            $queryExport->hydrate(false);
            $ExportResultData = $queryExport->toArray();
            $fileName = "all_registration".date("d-m-y:h:s").".xls";
            $data = array(); 
            // Start PresentFunctionalStatus for dropdown
                $this->loadModel('PresentFunctionalStatus');
                $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
                $this->loadMOdel('WaterBodyTypes');
                $water_body_typearray = $this->WaterBodyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();
                $this->loadModel('Designations');
                $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();
                
                    $headerRow = array("S.No", "Cooperative Society Name","contact person","Contact Number","Address", "Location", "State", "District-Urban Local Body","Block Name","Gram Panchayat","Water Body","Type of water body", "Sector", "Registration Number","Cooperative ID","Reference Year","Date of Registration","Status","No. of Member","Has-Land"
                    ,"Land Owned","Land Leased","Land Alloted","Land Total","Total-Outstanstanding loan extended","Revenue","Fertilizer Distribution","Fair Price Shop License","Procurement of Foodgrains","Dry Storage","Dry-storage capacity","Cold Storage","Cold-storage capacity","Union/Federation","Level of Affiliated Union/Federation","Name of Union/federation","Functional Status");
                    $astatus = ['1'=>'Accepted','0'=>'Pending'];
         
         foreach($ExportResultData As $rows){
                foreach($rows['cooperative_registration_pacs'] as $key=>$value1)
                {
                    $land = $value1['is_socitey_has_land'];
                    $loan = $value1['pack_total_outstanding_loan'];
                    $revenue = $value1['pack_revenue_non_credit'];
                    $fertilizer = $value1['fertilizer_distribution'];
                    $fair = $value1['fair_price'];
                    $grain = $value1['is_foodgrains'];
                    $agri = $value1['agricultural_implements'];
                    $storage = $value1['dry_storage'];
                    $capicity = $value1['dry_storage_capicity'];
                    $cold = $value1['cold_storage'];
                    $cold_capicity = $value1['cold_storage_capicity'];
                   
                }
                foreach($rows['cooperative_registrations_lands'] as $key=>$value2)
                {
                    $own = $value2['land_owned'];
                    $leas = $value2['land_leased'];
                    $allot = $value2['land_allotted'];
                    $total = $value2['land_total'];

                }
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

           $bloack_name     =   $bloack[$rows['block_code']];
           $panchayat_name =   $panchayats[$rows['gram_panchayat_code']];
          // $registration_number="'".trim($rows['registration_number'])."'";

           $function_status= $presentFunctionalStatus[$rows['functional_status']];
           $cooperative_society_name= str_replace('	','',$this->strClean(trim($rows['cooperative_society_name'])));
           $contact_person= str_replace('','',$this->strClean(trim($rows['contact_person'])));

           $registration_number= str_replace('	','',$this->strClean(trim($rows['registration_number'])));
           $mobile= str_replace('	','',$this->strClean(trim($rows['mobile'])));
       
           $address= "State- ".$state_name. " District- ". $arr_districts[$rows['operational_district_code']]." Block- ".  $bloack_name." Panchayat_name- ".  $panchayat_name. " Pin Code -".$rows['pincode'];
          
           
           if($rows['is_coastal']==1)
            {
                $waterbody='YES';
            }
            else
            {
                $waterbody='NO';
            }
            $water_body_type = $water_body_typearray[$rows['water_body_type_id']];

            if($rows['is_affiliated_union_federation']==1)
            {
                $union='YES';
            }
            else
            {
                $union='NO';
            }
            $levelOfUnions = ['1'=>'DCCB','2'=>'STCB','3'=>'Other']; 
           
            $option =  ['1'=>'YES','0'=>'NO'];
            $categoryAudit = ['1'=>'A: Score more than 70','2'=>'B: Score between 50 to 70','3'=>'C: Score between 35 and 50', '4'=> 'D: Score less than 35','5'=>'Audit has not given any Score'];                                                                                                                                                      
            $buildingTypes = ['1'=>'Own Building','2'=>'Rented Building','3'=>'Rent Free Building', '4'=> 'Leased Building','5'=>'Building Provided by the Government'];
        
            $data[] = [$i, $cooperative_society_name,  $contact_person,$mobile,$address ,$location,  $state_name, $dist_urbn ,$bloack_name,$panchayat_name,$waterbody,  $water_body_type, $sectors[$rows['sector_of_operation']],$registration_number, $coperative_id,
            $rows['reference_year'], $rows['date_registration'],$astatus[$rows['is_approved']],$rows['members_of_society'],$option[$land],$own,$leas,$allot,$total,$loan,$revenue,$option[$fertilizer],$option[$fair],$option[$grain],$option[$storage],$capicity,$option[$cold],$cold_capicity,$union,$levelOfUnions[$rows['affiliated_union_federation_level']],$SecondaryActivities[$rows['affiliated_union_federation_name']],$function_status];
         $i++;
         }

         $this->exportInExcelNew($fileName, $headerRow, $data);           

     }
         #################export Excel################




    }

    public function allregistrationlevel()
    {

        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $this->Flash->error(__('You are not authorized.'));
            return $this->redirect(['action' => 'dataEntryPending']);    
            
        }

     // $get_state_id = $this->request->session()->read('Auth.User.state_code');

        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
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

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $location = trim($this->request->query['location']);
            $this->set('location', $location);
            $search_condition[] = "location_of_head_quarter = '" . $location . "'";
        }

        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='') {
            $s_sector_operation = trim($this->request->query['sector_operation']);
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

         //=============================Urban=====================================

         if (isset($this->request->query['local_category']) && $this->request->query['local_category'] !='') {
            $s_local_category = trim($this->request->query['local_category']);
            $this->set('s_local_category', $s_local_category);
            $search_condition[] = "urban_local_body_type_code = '" . $s_local_category . "'";
        }

        if (isset($this->request->query['local_body']) && $this->request->query['local_body'] !='') {
            $s_local_body = trim($this->request->query['local_body']);
            $this->set('s_local_body', $s_local_body);
            $search_condition[] = "urban_local_body_code = '" . $s_local_body . "'";
        }

        if (isset($this->request->query['ward']) && $this->request->query['ward'] !='') {
            $s_ward = trim($this->request->query['ward']);
            $this->set('s_ward', $s_ward);
            $search_condition[] = "locality_ward_code = '" . $s_ward . "'";
        }

        //=============================Urban=====================================

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "operational_district_code = '" . $s_district . "'";
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

        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            $nodal_data_entry_ids = [];
            $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();
            
            if(!empty($nodal_data_entry_ids)){
                $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
                $nodal_data_entry_ids=implode(",",$nodal_data_entry_ids);
                
                $search_condition2 = "created_by IN (" . $nodal_data_entry_ids . ")";
            } else{
                $search_condition2 = "created_by IN (0)";
            }
        }

        $search_condition3='';
         if($this->request->session()->read('Auth.User.role_id') == 11 ||  $this->request->session()->read('Auth.User.role_id')==56 )
        {
           $state= $this->request->session()->read('Auth.User.state_code');

          
             $search_condition3  = "state_code = '" . $state . "'";

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

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
       // $this->loadMOdel('DistrictsBlocksGpVillages');

       // $panchayats = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray(); 
        $this->loadModel('CooperativeRegistrations');
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();
        $this->set('designations',$designations);

        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => [$searchString,$search_condition2,$search_condition3]
        ])->select(['location_of_head_quarter','state_code','district_code','urban_local_body_code','sector_of_operation','cooperative_id_num','block_code','gram_panchayat_code','functional_status','cooperative_society_name','registration_number','is_coastal','water_body_type_id','reference_year','date_registration','is_approved','id','mobile','operational_district_code','pincode','contact_person','designation'])->where(['is_draft'=>0,'status'=>'1']);
        $this->paginate = ['maxLimit' => 10,'limit' =>10];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        $this->set(compact('CooperativeRegistrations'));  
        }     

    //     if($this->request->session()->read('Auth.User.role_id') == 56)
    //     {
    //     $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']])->where(['state_code'=>$get_state_id]);
    //     $query->hydrate(false);
    //     $stateOption = $query->toArray();
    //     $this->set('sOption',$stateOption);
	// } else {
	
        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);
	//}

        $arr_location = [
            '1'=> 'Urban',
            '2'=> 'Rural'
        ];
        $this->set('arr_location',$arr_location);

        $this->loadModel('SectorOperations');

        $sectorOperations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();

        $this->set('sectorOperations', $sectorOperations);

        $primary_activities = [];

        if(!empty($this->request->query['sector_operation']))
        {
            //for credit
            $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$this->request->query['sector_operation'],'status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        }

        $this->set('primary_activities',$primary_activities);


        // Start SecondaryActivities for dropdown
        $this->loadModel('SecondaryActivities');

        $secondary_activities = $this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $this->set('secondary_activities',$secondary_activities);

       //======================urban================================================

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 1)
       {
           $this->loadMOdel('UrbanLocalBodies');

           $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
   
           $this->set('local_categories',$local_categories);
       }
      if($this->request->session()->read('Auth.User.role_id') == 11)
       {
            $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();   
            $this->set('local_categories',$local_categories);
       }
        
       if(!empty($this->request->query['local_category']) && $this->request->query['location'] == 1)
       {
           

           $localbodies = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$this->request->query['local_category'],'state_code'=>$state])->order(['local_body_name'=>'ASC'])->toArray();
          
       }
    

       $this->set('localbodies',$localbodies); 

       if(!empty($this->request->query['local_body']) && $this->request->query['location'] == 1)
       {
           $this->loadMOdel('UrbanLocalBodiesWards');

           $wards = $this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$this->request->query['local_body'],'state_code'=>$this->request->query['state']])->order(['ward_name'=>'ASC'])->toArray();
       }
       
       $this->set('wards',$wards);      
        

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

       // if(!empty($this->request->query['block']) && $this->request->query['location'] == 2)
       // {
       //     $this->loadMOdel('DistrictsBlocksGpVillages');

       //     $panchayats = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$this->request->query['block']])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  

       //     $this->set('panchayats',$panchayats); 
       // }



       $villages = [];

      

       $this->loadModel('SectorOperations');

        
             $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
           
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);

             
        if(!empty($this->request->query['export_excel']))
        { 

           //$this->CreatePdfDemo("testtt","testt");
              
           $this->loadMOdel('DistrictsBlocksGpVillages');
           $allvillage = $this->DistrictsBlocksGpVillages->find('all')->where(['status'=>1,'state_code'=>$this->request->query['state']])->group(['village_code'])->order(['block_code'=>'ASC'])->toArray(); 

           $bloackarray =   array();
           $gparray     =   array();
           $villarray   =   array();
            foreach($allvillage as $keyv=>$valuev)
            {
                $bloackarray[$valuev['block_code']]         =   $valuev['block_name'];
                $gparray[$valuev['gram_panchayat_code']]    =   $valuev['gram_panchayat_name'];
                $villarray[$valuev['village_code']]           =   $valuev['village_name'];
            }


         $i=1;
       //  mysql_set_charset("utf8");


          //  $this->CreatePdfDemo("name","testpdf");
          //  die;

            $this->loadModel('CooperativeRegistrationPacs');
            $this->loadModel('CooperativeRegistrationsLands');

            $queryExport = $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'ASC'],  
           // 'contain'=>['CooperativeRegistrationPacs','CooperativeRegistrationsLands'],        
            'conditions' => [$searchString,$search_condition2,$search_condition3]          
            ])->select(['location_of_head_quarter','state_code','district_code','urban_local_body_code','sector_of_operation','cooperative_id_num','block_code','gram_panchayat_code','village_code','functional_status','cooperative_society_name','registration_number','is_coastal','water_body_type_id','reference_year','date_registration','is_approved','id','mobile','operational_district_code','pincode','contact_person','designation'])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.status'=>1]);

            $queryExport->hydrate(false);
            $ExportResultData = $queryExport->toArray();
            $fileName = "all_registration".date("d-m-y:h:s").".xls";
            $data = array(); 
            // Start PresentFunctionalStatus for dropdown
              
                $this->loadModel('Designations');
                $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();
                
                    $headerRow = array("S.No", "Cooperative Society Name","Contact person","Desination","Contact Number","Address");
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

         //  $coperative_id = str_pad($rows['sector_of_operation'], 2, "0", STR_PAD_LEFT) . str_pad($rows['cooperative_id_num'], 7, "0", STR_PAD_LEFT);

           $bloack_name     =   $bloackarray[$rows['block_code']];
           $panchayat_name  =   $gparray[$rows['gram_panchayat_code']];
           $village_name    =  $villarray[$rows['village_code']]; 

          
           $cooperative_society_name= str_replace('	','',$this->strClean(trim($rows['cooperative_society_name'])));
           $contact_person= str_replace('','',$this->strClean(trim($rows['contact_person'])));

           $mobile= str_replace('	','',$this->strClean(trim($rows['mobile'])));
           $designationsnanme= $designations[$rows['designation']];
       
           $address= $state_name. ", ". $arr_districts[$rows['operational_district_code']].",  ".  $bloack_name." , ".  $panchayat_name. " , ".  $village_name."  Pin Code -".$rows['pincode']."Phone Number- ". $mobile;   
           
           $address= $village_name. ", ".$panchayat_name.",  ".  $bloack_name." , ".  $arr_districts[$rows['operational_district_code']]. " , ".  $state_name." Pin Code -".$rows['pincode']." Phone Number- ". $mobile;  
                                                                                                                                                                                                                                                                                                                                                 
         
        
            $data[] = [$i, $cooperative_society_name, $contact_person, $designationsnanme,$mobile,$address];
         $i++;
         }

         $this->exportInExcelNew($fileName, $headerRow, $data);           

     }
         #################export Excel################




    }
    
    public function view($id = null)
    {
        $this->loadModel('CooperativeRegistrations');
        $CooperativeRegistration = $this->CooperativeRegistrations->get($id, [
            'contain' => ['CRMS','CooperativeSocietyTypes','AreaOfOperations','AreaOfOperationLevelUrban','CooperativeRegistrationsContactNumbers','CooperativeRegistrationsEmails','CooperativeRegistrationPacs'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationDairy'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationFishery'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationsLands']
        ]);

        //if data entry user
        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $this->loadMOdel('Users');
            $created_by = $CooperativeRegistration['created_by'];
            $cUser = $this->Users->find('all')->where(['id' => $created_by])->first();

            if(empty($created_by) || $cUser['id'] != $created_by)
            {
                $this->Flash->error(__('You are not authorized.'));
                return $this->redirect(['action' => 'dataEntryPending']);    
            }
        }

        // Start States for display name
            $this->loadModel('States');
            $states=$this->States->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
            
            $this->set('states', $states);
        // End States for display name

        $this->set('CooperativeRegistration', $CooperativeRegistration);

        $operationSector = [
            '1' => 'Credit',
            '2' => 'Non Credit',
        ];
        

        $this->set('operationSector', $operationSector);

        $this->loadModel('SecondaryActivities');
        $secondaryActivities=$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();
            
        $this->set('secondaryActivities', $secondaryActivities);

        // Start PrimaryActivities for dropdown
        $this->loadModel('PrimaryActivities');
        //for credit
        $creditPrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

        //for non credit
        $nonCreditPrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>2,'status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        // End PrimaryActivities for dropdown



        // Start PresentFunctionalStatus for dropdown
        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

        $locationOfHeadquarter = [
            '1' => 'Urban',
            '2' => 'Rural',
        ];

        $blockName = '';

        if(!empty($CooperativeRegistration->block_code))
        {
            $this->loadModel('Blocks');
            
            $block = $this->Blocks->find('all')->where(['block_code'=>$CooperativeRegistration->block_code,'flag'=>1])->first();
            $blockName = $block['name'];
        }

        $districtName = '';

        if(!empty($CooperativeRegistration->district_code))
        {
            $this->loadModel('Districts');
            
            $district = $this->Districts->find('all')->where(['district_code'=>$CooperativeRegistration->district_code,'flag'=>1])->first();
            $districtName = $district['name'];
        }

        $panchayatName = '';
        $this->loadMOdel('DistrictsBlocksGpVillages');

        if(!empty($CooperativeRegistration->gram_panchayat_code))
        {
            $gpc = $this->DistrictsBlocksGpVillages->find('all')->where(['status'=>1,'gram_panchayat_code'=>$CooperativeRegistration->gram_panchayat_code])->first();  
            $panchayatName = $gpc['gram_panchayat_name'];
        }


        $villageName = '';

        if(!empty($CooperativeRegistration->village_code))
        {
            $dbgv = $this->DistrictsBlocksGpVillages->find('all')->where(['status'=>1,'village_code'=>$CooperativeRegistration->village_code])->first(); 

            $villageName = $dbgv['village_name'];
        }

        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$CooperativeRegistration->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies for dropdown

        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$CooperativeRegistration->urban_local_body_type_code,'state_code'=>$CooperativeRegistration->state_code])->order(['local_body_name'=>'ASC'])->toArray();
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown
        

        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$CooperativeRegistration->urban_local_body_code])->order(['ward_name'=>'ASC'])->toArray();

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown

        $this->loadModel('SectorOperations');

       $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sector_operations',$sector_operations); 

        // Start registration_authorities for display name
            $this->loadModel('RegistrationAuthorities');
            $regi_authorities=$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->order(['authority_name'=>'ASC'])->where(['status'=>1])->toArray();
            
            $this->set('regi_authorities', $regi_authorities);
        // End States for display name

             ####### degination dropdown#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();
       $this->set('designations',$designations);
        ###################end ############


        // Start CooperativeSocietyTypes for dropdown
            $this->loadModel('CooperativeSocietyTypes');
            $CooperativeSocietyTypes=$this->CooperativeSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray();
              $this->set('CooperativeSocietyTypes',$CooperativeSocietyTypes);
        // End CooperativeSocietyTypes for dropdown


               // Start area_of_operation_level for dropdown
            $this->loadModel('AreaOfOperationLevel');
            $area_operation_level=$this->AreaOfOperationLevel->find('all')->where(['cooperative_registrations_id'=>$id])->order(['id'=>'ASC'])->toArray();
              $this->set('area_operation_level',$area_operation_level);
        // End area_of_operation_level for dropdown

         $this->loadModel('Districts');
            $districtarry = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->toarray();
            $this->set('districtarry',$districtarry);

         // Start Blocks for dropdown
         $this->loadModel('Blocks');            
            $blocklist = $this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1])->toarray();
            $this->set('blocklist',$blocklist);


            // Start Gram Panchayats for dropdown
            $statelevelarray=[];
            foreach($area_operation_level as $key=>$value)
            {
                 $statelevelarray[]=$value['state_code'];
            }

            //for urban data
          $this->loadModel('AreaOfOperationLevelUrban');
          $area_operation_level_urban_row = $this->AreaOfOperationLevelUrban->find('all')->where(['cooperative_registrations_id'=>$id])->order(['id'=>'ASC'])->group(['row_id'])->toArray();

          $this->set('area_operation_level_urban_row',$area_operation_level_urban_row);



          $this->loadMOdel('UrbanLocalBodies');
              $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$CooperativeRegistration->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  
              $this->set('localbody_types',$localbody_types);  
              // end urban_local_bodies type for dropdown


              // Start urban_local_bodies for dropdown
              $this->loadMOdel('UrbanLocalBodies');
              $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'state_code'=>$CooperativeRegistration->state_code])->order(['local_body_name'=>'ASC'])->toArray();
                  
              $this->set('localbodies',$localbodies);


              $this->loadMOdel('UrbanLocalBodiesWards');
              $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1])->order(['ward_name'=>'ASC'])->toArray();

      $this->set('localbodieswards',$localbodieswards);  

            $area_of_operation_id_urban = '';
          if(isset($area_operation_level_urban_row) && !empty($area_operation_level_urban_row))
          {
              //$area_operation_level_urban_categories[$urban_data['row_id']][] = $urban_data['locality_ward_code'];
              foreach($CooperativeRegistration->area_of_operation_level_urban as $urban_wards)
              {
                  $area_of_operation_id_urban = $urban_wards['area_of_operation_id'];
                  $area_operation_level_urban_wards[$urban_wards['row_id']][] = $urban_wards['locality_ward_code'];
              }
          }
          
          $this->set('area_operation_level_urban_wards',$area_operation_level_urban_wards);

              $area_operation_level_row=$this->AreaOfOperationLevel->find('all')->where(['cooperative_registrations_id'=>$id])->order(['id'=>'ASC'])->group(['row_id'])->toArray();
              $this->set('area_operation_level_row',$area_operation_level_row);

              $area_of_operation_id_rural = '';

           //   echo "<pre>";
           // print_r($area_operation_level_row);
              $area_operation_level_row_v_1=array();
            foreach($area_operation_level_row as $key => $value123)
            {
                $area_operation_level_row_v=$this->AreaOfOperationLevel->find('all')->where(['cooperative_registrations_id'=>$id,'row_id'=>$value123['row_id']])->order(['id'=>'ASC'])->toArray();

                foreach($area_operation_level_row_v as $key1=>$value1)
                {
                    $area_of_operation_id_rural = $value1['area_of_operation_id'];
                   
                    $area_operation_level_row_v_1[$value123['row_id']][]=$value1['village_code'] ;
                }

            }

             $this->set('area_operation_level_row_v_1',$area_operation_level_row_v_1);
         
            if(!empty($statelevelarray))
            {
                $this->loadMOdel('DistrictsBlocksGpVillages');
                $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'state_code IN'=>$statelevelarray])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toarray();  
                $this->set('gps',$gps); 

                
                $gpsv=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'state_code IN'=>$statelevelarray])->group(['village_code'])->order(['village_name'=>'ASC'])->toarray();  
                $this->set('gpsv',$gpsv); 
            }

            // Start water_body_types for dropdown
         $this->loadModel('WaterBodyTypes');            
            $water_body_type = $this->WaterBodyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();
            $this->set('water_body_type',$water_body_type);

                 // Start level_of_affiliated_union for dropdown
            $this->loadModel('LevelOfAffiliatedUnion');            
            $level_of_aff_union = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1])->toarray();
            $this->set('level_of_aff_union',$level_of_aff_union);

                 // Start Name of Affiliated Union/Federation (district_central_cooperative_ban) for dropdown
            $this->loadModel('DistrictCentralCooperativeBank');            
            $dist_central_bannk = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();
           // print_r($dist_central_bannk);
            $this->set('dist_central_bannk',$dist_central_bannk);

                 // Start audit_categories for dropdown
            $this->loadModel('AuditCategories');            
            $audit_categorey = $this->AuditCategories->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();
           // print_r($dist_central_bannk);
            $this->set('audit_categorey',$audit_categorey);

            $buildingTypes = ['1'=>'Own Building','2'=>'Rented Building','3'=>'Rent Free Building', '4'=> 'Leased Building','5'=>'Building Provided by the Government'];

               $this->set('buildingTypes',$buildingTypes);

               $availableLand = ['1'=>'Owned Land','2'=>'Leased Land','3'=>'Land Provided by the Government'];

                 $this->set('availableLand',$availableLand);

                 $this->loadModel('AreaOfOperations');
                $areaOfOperations=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC'])->toArray();


        $this->set(compact('creditPrimaryActivities','presentFunctionalStatus','locationOfHeadquarter','blockName','districtName','panchayatName','villageName','nonCreditPrimaryActivities','area_of_operation_id_rural','area_of_operation_id_urban','areaOfOperations'));
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

    
    public function landreport()
    {
        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $this->Flash->error(__('You are not authorized.'));
            return $this->redirect(['action' => 'dataEntryPending']);    
            
        }

      //  $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
      //  $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        //$this->loadMOdel('UrbanLocalBodies');
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

       

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $state = trim($this->request->query['state']);
            $this->set('state', $state);
            $search_condition[] = "state_code = '" . $state . "'";
        }

        if (isset($this->request->query['financial_audit']) && $this->request->query['financial_audit'] !='') {
            $financial_audit = trim($this->request->query['financial_audit']);
            $this->set('financial_audit', $financial_audit);
            $search_condition[] = "financial_audit = '" . $financial_audit . "'";
        }

        
        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $location = trim($this->request->query['location']);
            $this->set('location', $location);
            $search_condition[] = "location_of_head_quarter = '" . $location . "'";
        }

        if (isset($this->request->query['land']) && $this->request->query['land'] !='') {
            $land = trim($this->request->query['land']);
            $this->set('land', $land);
          
        }

       
        $search_condition[] = "CooperativeRegistrations.sector_of_operation IN (1,20,22)";
        

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "operational_district_code = '" . $s_district . "'";
        }
      

       
        $search_condition3='';
          if($this->request->session()->read('Auth.User.role_id') == 11)
        {
            $state= $this->request->session()->read('Auth.User.state_code');

          
             $search_condition3  = "state_code = '" . $state . "'";

        }


        if (isset($this->request->query['is_approved']) && $this->request->query['is_approved'] !='') {
            $is_approved = trim($this->request->query['is_approved']);
            $this->set('is_approved', $is_approved);
            $search_condition[] = "is_approved = '" . $is_approved . "'";
        }else{
            $search_condition[] = "is_approved  != 2";
        }
        $search_condition[] = "is_draft  = 0";
        $search_condition[] = "status  = 1";

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
        $this->loadModel('CooperativeRegistrationsLands');

       
        //if(!empty($this->request->query['district']))
        if(1==1)
        {
        
        if (isset($this->request->query['land']) && $this->request->query['land'] !='') {
            $land = trim($this->request->query['land']);
            $this->set('land', $land);
            if($land==1)
            {

                    $registrationQuery = $this->CooperativeRegistrations->find('all')->select(['CooperativeRegistrations.location_of_head_quarter','CooperativeRegistrations.state_code','CooperativeRegistrations.district_code','CooperativeRegistrations.category_audit','CooperativeRegistrations.operational_district_code','CooperativeRegistrations.sector_of_operation','CooperativeRegistrations.cooperative_id_num','CooperativeRegistrations.financial_audit','CooperativeRegistrations.cooperative_society_name','CooperativeRegistrations.registration_number','CooperativeRegistrations.is_coastal','CooperativeRegistrations.date_registration','CooperativeRegistrations.is_approved','CooperativeRegistrations.id','CooperativeRegistrationPacs.is_socitey_has_land','CooperativeRegistrationsLands.land_total','CooperativeRegistrations.audit_complete_year'])                  
                    ->order(['CooperativeRegistrations.cooperative_society_name' => 'ASC'])
                    ->join([
                    'CooperativeRegistrationPacs' => [
                    'table' => 'cooperative_registration_pacs',
                    'type' => 'INNER',
                    'conditions' => 'CooperativeRegistrationPacs.cooperative_registrations_id = CooperativeRegistrations.id'
                    ],
                    'CooperativeRegistrationsLands' => [
                    'table' => 'cooperative_registrations_lands',
                    'type' => 'INNER',
                    'conditions' => 'CooperativeRegistrationsLands.cooperative_registration_id = CooperativeRegistrations.id'
                    ]
                    ])->where(['CooperativeRegistrationPacs.is_socitey_has_land'=>1,'CooperativeRegistrationsLands.land_total <'=>1,$searchString]);


            }
           if($land==2)
            {
                               
                $registrationQuery = $this->CooperativeRegistrations->find('all')->select(['CooperativeRegistrations.location_of_head_quarter','CooperativeRegistrations.state_code','CooperativeRegistrations.district_code','CooperativeRegistrations.category_audit','CooperativeRegistrations.operational_district_code','CooperativeRegistrations.sector_of_operation','CooperativeRegistrations.cooperative_id_num','CooperativeRegistrations.financial_audit','CooperativeRegistrations.cooperative_society_name','CooperativeRegistrations.registration_number','CooperativeRegistrations.is_coastal','CooperativeRegistrations.water_body_type_id','CooperativeRegistrations.reference_year','CooperativeRegistrations.date_registration','CooperativeRegistrations.is_approved','CooperativeRegistrations.id','CooperativeRegistrationPacs.is_socitey_has_land','CooperativeRegistrationsLands.land_total','CooperativeRegistrations.audit_complete_year'])                  
                ->order(['CooperativeRegistrations.cooperative_society_name' => 'ASC'])
                ->join([
                'CooperativeRegistrationPacs' => [
                'table' => 'cooperative_registration_pacs',
                'type' => 'INNER',
                'conditions' => 'CooperativeRegistrationPacs.cooperative_registrations_id = CooperativeRegistrations.id'
                ],
                'CooperativeRegistrationsLands' => [
                'table' => 'cooperative_registrations_lands',
                'type' => 'INNER',
                'conditions' => 'CooperativeRegistrationsLands.cooperative_registration_id = CooperativeRegistrations.id'
                ]
                ])->where(['CooperativeRegistrationPacs.is_socitey_has_land'=>1,'CooperativeRegistrationsLands.land_total >='=>1,$searchString]);

            }
            if($land==3)
            {
                               
                $registrationQuery = $this->CooperativeRegistrations->find('all')->select(['CooperativeRegistrations.location_of_head_quarter','CooperativeRegistrations.state_code','CooperativeRegistrations.district_code','CooperativeRegistrations.category_audit','CooperativeRegistrations.operational_district_code','CooperativeRegistrations.sector_of_operation','CooperativeRegistrations.cooperative_id_num','CooperativeRegistrations.financial_audit','CooperativeRegistrations.cooperative_society_name','CooperativeRegistrations.registration_number','CooperativeRegistrations.is_coastal','CooperativeRegistrations.water_body_type_id','CooperativeRegistrations.reference_year','CooperativeRegistrations.date_registration','CooperativeRegistrations.is_approved','CooperativeRegistrations.id','CooperativeRegistrationPacs.is_socitey_has_land','CooperativeRegistrationsLands.land_total','CooperativeRegistrations.audit_complete_year'])                  
                ->order(['CooperativeRegistrations.cooperative_society_name' => 'ASC'])
                ->join([
                'CooperativeRegistrationPacs' => [
                'table' => 'cooperative_registration_pacs',
                'type' => 'INNER',
                'conditions' => 'CooperativeRegistrationPacs.cooperative_registrations_id = CooperativeRegistrations.id'
                ],
                'CooperativeRegistrationsLands' => [
                'table' => 'cooperative_registrations_lands',
                'type' => 'INNER',
                'conditions' => 'CooperativeRegistrationsLands.cooperative_registration_id = CooperativeRegistrations.id'
                ]
                ])->where(['CooperativeRegistrationPacs.is_socitey_has_land !='=>1, $searchString]);

            }
        }else{

          //  is_socitey_has_land
          $registrationQuery = $this->CooperativeRegistrations->find('all')->select(['CooperativeRegistrations.location_of_head_quarter','CooperativeRegistrations.state_code','CooperativeRegistrations.district_code','CooperativeRegistrations.category_audit','CooperativeRegistrations.operational_district_code','CooperativeRegistrations.sector_of_operation','CooperativeRegistrations.cooperative_id_num','CooperativeRegistrations.financial_audit','CooperativeRegistrations.cooperative_society_name','CooperativeRegistrations.registration_number','CooperativeRegistrations.is_coastal','CooperativeRegistrations.water_body_type_id','CooperativeRegistrations.is_approved','CooperativeRegistrations.id','CooperativeRegistrationPacs.is_socitey_has_land','CooperativeRegistrationsLands.land_total','CooperativeRegistrations.audit_complete_year'])                  
          ->order(['CooperativeRegistrations.cooperative_society_name' => 'ASC'])
          ->join([
          'CooperativeRegistrationPacs' => [
          'table' => 'cooperative_registration_pacs',
          'type' => 'INNER',
          'conditions' => 'CooperativeRegistrationPacs.cooperative_registrations_id = CooperativeRegistrations.id'
          ],
          'CooperativeRegistrationsLands' => [
          'table' => 'cooperative_registrations_lands',
          'type' => 'INNER',
          'conditions' => 'CooperativeRegistrationsLands.cooperative_registration_id = CooperativeRegistrations.id'
          ]
          ])->where(['CooperativeRegistrationPacs.is_socitey_has_land'=>1,$searchString]);
        }

       // print_r($registrationQuery);
       // die;
            
        $this->paginate = ['limit' =>20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        $this->set(compact('CooperativeRegistrations')); 
    }
            // Start audit_categories for dropdown
            $this->loadModel('AuditCategories');            
            $audit_categorey = $this->AuditCategories->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();
           // print_r($dist_central_bannk);
            $this->set('audit_categorey',$audit_categorey);

        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);       

        $this->loadModel('SectorOperations');
        $sectorOperations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();

        $this->set('sectorOperations', $sectorOperations);
        $primary_activities = [];

        if(!empty($this->request->query['sector_operation']))
        {
            //for credit
            $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$this->request->query['sector_operation'],'status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        }

        $this->set('primary_activities',$primary_activities);


        // Start SecondaryActivities for dropdown
        $this->loadModel('SecondaryActivities');


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

       $this->loadModel('SectorOperations');

       if($this->request->session()->read('Auth.User.role_id') == 66)
       {
           $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1,'id IN'=>['1','9','10']])->toArray();
           $replacements=['1'=>'Primary Agricultural Credit Society (PACS/FSS/LAMP)'];
           $sectors = array_replace($sectors, $replacements);
       }else
       {
           $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
       }   
        
    
       $this->set('sectors',$sectors);  

      
   
             
        if(!empty($this->request->query['export_excel']))
       { 

            if(!empty($this->request->query['district']))
            {
       
                if($land==2)
                {
                    {
                        $search_condition_land[] = "CooperativeRegistrationsLands.land_total >= 1";
                        $search_condition_land[] = "CooperativeRegistrationPacs.is_socitey_has_land = 1";
                    }
                }
                if($land==1)
                {
                    {
                        $search_condition_land[] = "CooperativeRegistrationsLands.land_total <  1";
                        $search_condition_land[] = "CooperativeRegistrationPacs.is_socitey_has_land = 1";
                    }
                }
                if($land==3)
                {
                    {
                       // $search_condition_land[] = "CooperativeRegistrationsLands.land_total <  1";
                        $search_condition_land[] = "CooperativeRegistrationPacs.is_socitey_has_land != 1";
                    }
                }
                if($land==0)
                {
                    {
                       // $search_condition_land[] = "CooperativeRegistrationsLands.land_total <  1";
                        $search_condition_land[] = "CooperativeRegistrationPacs.is_socitey_has_land = 1";
                    }
                }
            
                    if(!empty($search_condition_land)){
                        $searchString_land = implode(" AND ",$search_condition_land);
                    } else {
                        $searchString_land = '';
                    }
                
            $i=1;
            $queryExport = $this->CooperativeRegistrations->find('all')->select(['CooperativeRegistrations.location_of_head_quarter','CooperativeRegistrations.state_code','CooperativeRegistrations.district_code','CooperativeRegistrations.category_audit','CooperativeRegistrations.operational_district_code','CooperativeRegistrations.sector_of_operation','CooperativeRegistrations.cooperative_id_num','CooperativeRegistrations.financial_audit','CooperativeRegistrations.cooperative_society_name','CooperativeRegistrations.registration_number','CooperativeRegistrations.is_coastal','CooperativeRegistrations.date_registration','CooperativeRegistrations.is_approved','CooperativeRegistrations.id','CooperativeRegistrationPacs.is_socitey_has_land','CooperativeRegistrationsLands.land_total','CooperativeRegistrations.audit_complete_year'])                  
            ->order(['CooperativeRegistrations.cooperative_society_name' => 'ASC'])
            ->join([
            'CooperativeRegistrationPacs' => [
            'table' => 'cooperative_registration_pacs',
            'type' => 'INNER',
            'conditions' => 'CooperativeRegistrationPacs.cooperative_registrations_id = CooperativeRegistrations.id'
            ],
            'CooperativeRegistrationsLands' => [
            'table' => 'cooperative_registrations_lands',
            'type' => 'INNER',
            'conditions' => 'CooperativeRegistrationsLands.cooperative_registration_id = CooperativeRegistrations.id'
            ]
            ])->where([$searchString_land,$searchString]);


         $queryExport->hydrate(false);
         $ExportResultData = $queryExport->toArray();
         $fileName = "land_report".date("d-m-y:h:s").".xls";
         $data = array();    
         
        //  echo "<pre>";
        //  print_r($ExportResultData);
        // die;
  
      
        $headerRow = array("S.No", "District", "Cooperative Society Name",   "Sector", "Registration Number", "Land Total","Audit","Category Audit","Audit Year");
         $astatus = ['1'=>'Accepted','0'=>'Pending'];
         $categoryAudit = ['1'=>'A: Score more than 70','2'=>'B: Score between 50 to 70','3'=>'C: Score between 35 and 50', '4'=> 'D: Score less than 35','5'=>'Audit has not given any Score']; 
         $financial_audits = ['0'=>'No','1'=>'Yes'];
         foreach($ExportResultData As $rows){
              
        $dist_urbn='';
       
    
        $state_name = $stateOption[$rows['state_code']];

            $district_name = $arr_districts[$rows['operational_district_code']];         
           $cooperative_society_name= str_replace(' ','',$this->strClean(trim($rows['cooperative_society_name'])));  
           $registration_number= str_replace('  ','',$this->strClean(trim($rows['registration_number']))); 
           $land_total=$rows['CooperativeRegistrationsLands']['land_total'];
           $audit=$financial_audits[$rows['financial_audit']];
           $category_audit=$categoryAudit[$rows['category_audit']];
           $audit_complete_year=$rows['audit_complete_year'];
           
           
            $data[] = [$i,  $district_name, $cooperative_society_name,  $sectors[$rows['sector_of_operation']],$registration_number, $land_total,$audit,$category_audit,$audit_complete_year];
         $i++;
         }

         $this->exportInExcelNew($fileName, $headerRow, $data);           

     }
        }
         #################export Excel################

    }
    public function getGp(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
             $block_code=$this->request->data('block_code');    
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
            $DistrictsBlocksGpVillages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$gp_code])->order(['village_name'=>'ASC']);
            
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


public function getUrbanLocalBodies(){
    $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $state_code=$this->request->query('state_code');    
            
            $this->loadMOdel('UrbanLocalBodies');
            $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);
            
            $option_html='<option value="">Select</option>';
            if($localbody_types->count()>0){
                foreach($localbody_types as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.$value.'</option>';
                }
            }
            echo $option_html;
        }
        exit;
}
public function getUrbanLocalBody(){
    $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $urban_local_body_type_code=$this->request->query('urban_local_body_type_code');  
             $state_code=$this->request->query('state_code');       
            
            
            
            $this->loadMOdel('UrbanLocalBodies');
            $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$urban_local_body_type_code,'state_code'=>$state_code])->order(['local_body_name'=>'ASC']);
            
            $option_html='<option value="">Select</option>';
            if($localbodies->count()>0){
                foreach($localbodies as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.$value.'</option>';
                }
            }
            echo $option_html;
        }
        exit;
}

public function getLocalityWard(){
    $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $urban_local_body_code=$this->request->data('urban_local_body_code');    
            
            $this->loadMOdel('UrbanLocalBodiesWards');
            $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$urban_local_body_code])->order(['ward_name'=>'ASC']);
            
            $option_html='<option value="">Select</option>';
            if($localbodieswards->count()>0){
                foreach($localbodieswards as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.$value.'</option>';
                }
            }
            echo $option_html;
        }
        exit;
}

  
public function getPrimaryActivity(){
    $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            
            $sector_operation = $this->request->query('sector_operation');    

            $this->loadMOdel('PrimaryActivities');

            $pactivities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$sector_operation,'status'=>1])->order(['orderseq'=>'ASC']);
          
            $option_html='<option value="">Select</option>';

            if($pactivities->count()>0){
                foreach($pactivities as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.$value.'</option>';
                }
            }

            echo $option_html;
        }
        exit;
}

    public function NewAllRegistration()
    {

        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $this->Flash->error(__('You are not authorized.'));
            return $this->redirect(['action' => 'dataEntryPending']);    
            
        }

     // $get_state_id = $this->request->session()->read('Auth.User.state_code');

        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
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

        if (!empty($this->request->query['from_date_registration'])) {
            $from_date = trim($this->request->query['from_date_registration']);
               $from_date_array =explode("/", $from_date);       
           $from_date_var= $from_date_array[2].'-'.$from_date_array[1].'-'.$from_date_array[0];
            $this->set('from_date_registration', $from_date);      
            $search_condition[] = "created >= '" . $from_date_var . " 00:00:00'";
         
        }else
        {
            $from_date_var = date('Y-m-d',strtotime('2023-04-01'));
            $search_condition[] = "created >= '" . $from_date_var . " 00:00:00'";
        }
        if (!empty($this->request->query['to_date_registration'])) {
            $todate = trim($this->request->query['to_date_registration']);
            $this->set('to_date_registration', $todate);

            $to_date_array =explode("/", $todate);
            // print_r($from_date_array);
             $to_date_var= $to_date_array[2].'-'.$to_date_array[1].'-'.$to_date_array[0];

             $search_condition[] = "created <= '" . $to_date_var . " 23:59:59'";
            //$search_condition[] = "cooperative_society_name like '%" . $societyName . "%'";
        }else
        {
            $to_date_var = date('Y-m-d');
            $search_condition[] = "created <= '" . $to_date_var . " 23:59:59'";
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

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $location = trim($this->request->query['location']);
            $this->set('location', $location);
            $search_condition[] = "location_of_head_quarter = '" . $location . "'";
        }

        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='') {
            $s_sector_operation = trim($this->request->query['sector_operation']);
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

         //=============================Urban=====================================

         if (isset($this->request->query['local_category']) && $this->request->query['local_category'] !='') {
            $s_local_category = trim($this->request->query['local_category']);
            $this->set('s_local_category', $s_local_category);
            $search_condition[] = "urban_local_body_type_code = '" . $s_local_category . "'";
        }

        if (isset($this->request->query['local_body']) && $this->request->query['local_body'] !='') {
            $s_local_body = trim($this->request->query['local_body']);
            $this->set('s_local_body', $s_local_body);
            $search_condition[] = "urban_local_body_code = '" . $s_local_body . "'";
        }

        if (isset($this->request->query['ward']) && $this->request->query['ward'] !='') {
            $s_ward = trim($this->request->query['ward']);
            $this->set('s_ward', $s_ward);
            $search_condition[] = "locality_ward_code = '" . $s_ward . "'";
        }

        //=============================Urban=====================================

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "operational_district_code = '" . $s_district . "'";
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

        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            $nodal_data_entry_ids = [];
            $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();
            
            if(!empty($nodal_data_entry_ids)){
                $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
                $nodal_data_entry_ids=implode(",",$nodal_data_entry_ids);
                
                $search_condition2 = "created_by IN (" . $nodal_data_entry_ids . ")";
            } else{
                $search_condition2 = "created_by IN (0)";
            }
        }

        $search_condition3='';
         if($this->request->session()->read('Auth.User.role_id') == 11 ||  $this->request->session()->read('Auth.User.role_id')==56 )
        {
           $state= $this->request->session()->read('Auth.User.state_code');

          
             $search_condition3  = "state_code = '" . $state . "'";

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
       // print_r($search_condition);


        $this->loadModel('CooperativeRegistrations');


        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => [$searchString,$search_condition2,$search_condition3]
        ])->select(['location_of_head_quarter','state_code','district_code','urban_local_body_code','sector_of_operation','cooperative_id_num','block_code','gram_panchayat_code','functional_status','cooperative_society_name','registration_number','is_coastal','water_body_type_id','reference_year','date_registration','is_approved','id','contact_person','mobile'])->where(['is_draft'=>0,'status'=>'1']);
        $this->paginate = ['limit' => 25];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        $this->set(compact('CooperativeRegistrations'));       

	    $this->loadModel('PresentFunctionalStatus');
                $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('presentFunctionalStatus',$presentFunctionalStatus);

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

        if(!empty($this->request->query['sector_operation']))
        {
            //for credit
            $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$this->request->query['sector_operation'],'status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        }

        $this->set('primary_activities',$primary_activities);


        // Start SecondaryActivities for dropdown
        $this->loadModel('SecondaryActivities');

        $secondary_activities = $this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $this->set('secondary_activities',$secondary_activities);

       //======================urban================================================

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 1)
       {
           $this->loadMOdel('UrbanLocalBodies');

           $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
   
           $this->set('local_categories',$local_categories);
       }
      if($this->request->session()->read('Auth.User.role_id') == 11)
       {
            $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();   
            $this->set('local_categories',$local_categories);
       }
        
       if(!empty($this->request->query['local_category']) && $this->request->query['location'] == 1)
       {
           

           $localbodies = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$this->request->query['local_category'],'state_code'=>$state])->order(['local_body_name'=>'ASC'])->toArray();
          
       }
    

       $this->set('localbodies',$localbodies); 

       if(!empty($this->request->query['local_body']) && $this->request->query['location'] == 1)
       {
           $this->loadMOdel('UrbanLocalBodiesWards');

           $wards = $this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$this->request->query['local_body'],'state_code'=>$this->request->query['state']])->order(['ward_name'=>'ASC'])->toArray();
       }
       
       $this->set('wards',$wards);      
        

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


       $villages = [];

       if(!empty($this->request->query['panchayat']) && $this->request->query['location'] == 2)
       {
           $this->loadMOdel('DistrictsBlocksGpVillages');

           $villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$this->request->query['panchayat']])->order(['village_name'=>'ASC'])->toArray();

           $this->set('villages',$villages);  
       }

       $this->loadModel('SectorOperations');

        
             $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
           
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);

             
        if(!empty($this->request->query['export_excel']))
        { 

                $this->loadMOdel('DistrictsBlocksGpVillages');

          // $panchayats = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray(); 

          // $bloack = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1])->group(['block_code'])->order(['block_code'=>'ASC'])->toArray(); 
         $i=1;
       //  mysql_set_charset("utf8");
            $this->loadModel('CooperativeRegistrationPacs');
            $this->loadModel('CooperativeRegistrationsLands');

            $queryExport = $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'ASC'],  
           // 'contain'=>['CooperativeRegistrationPacs','CooperativeRegistrationsLands'],        
            'conditions' => [$searchString,$search_condition2,$search_condition3]          
            ])->select(['CooperativeRegistrations.id','CooperativeRegistrations.location_of_head_quarter','CooperativeRegistrations.state_code','CooperativeRegistrations.district_code','CooperativeRegistrations.urban_local_body_code','CooperativeRegistrations.sector_of_operation','CooperativeRegistrations.cooperative_id_num','CooperativeRegistrations.block_code','CooperativeRegistrations.gram_panchayat_code','CooperativeRegistrations.functional_status','CooperativeRegistrations.cooperative_society_name','CooperativeRegistrations.registration_number','CooperativeRegistrations.is_coastal','CooperativeRegistrations.water_body_type_id','CooperativeRegistrations.is_affiliated_union_federation','CooperativeRegistrations.affiliated_union_federation_level',
            'CooperativeRegistrations.financial_audit','CooperativeRegistrations.is_dividend_paid','CooperativeRegistrations.audit_complete_year','CooperativeRegistrations.category_audit','CooperativeRegistrations.is_profit_making','CooperativeRegistrations.annual_turnover','CooperativeRegistrations.dividend_rate','CooperativeRegistrations.contact_person','CooperativeRegistrations.mobile','CooperativeRegistrations.designation','CooperativeRegistrations.landline','CooperativeRegistrations.email','CooperativeRegistrations.reference_year','CooperativeRegistrations.date_registration','CooperativeRegistrations.is_approved'])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.status'=>1]);

            $queryExport->hydrate(false);
            $ExportResultData = $queryExport->toArray();
            $fileName = "all_registration".date("d-m-y:h:s").".xls";
            $data = array(); 
            // Start PresentFunctionalStatus for dropdown
              
                $this->loadMOdel('WaterBodyTypes');
            //    $water_body_typearray = $this->WaterBodyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();
                $this->loadModel('Designations');
                $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();
                
                    $headerRow = array("S.No", "Cooperative Society Name","Location", "State", "District-Urban Local Body","Sector","contact person","Contact Number","function_sateus","Cooperative ID","Date of Registration");
                    $astatus = ['1'=>'Accepted','0'=>'Pending'];
         
         foreach($ExportResultData As $rows){
                // foreach($rows['cooperative_registration_pacs'] as $key=>$value1)
                // {
                //     $land = $value1['is_socitey_has_land'];
                //     $loan = $value1['pack_total_outstanding_loan'];
                //     $revenue = $value1['pack_revenue_non_credit'];
                //     $fertilizer = $value1['fertilizer_distribution'];
                //     $fair = $value1['fair_price'];
                //     $grain = $value1['is_foodgrains'];
                //     $agri = $value1['agricultural_implements'];
                //     $storage = $value1['dry_storage'];
                //     $capicity = $value1['dry_storage_capicity'];
                //     $cold = $value1['cold_storage'];
                //     $cold_capicity = $value1['cold_storage_capicity'];
                   
                // }
                // foreach($rows['cooperative_registrations_lands'] as $key=>$value2)
                // {
                //     $own = $value2['land_owned'];
                //     $leas = $value2['land_leased'];
                //     $allot = $value2['land_allotted'];
                //     $total = $value2['land_total'];

                // }
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

           $bloack_name     =   $bloack[$rows['block_code']];
           $panchayat_name =   $panchayats[$rows['gram_panchayat_code']];
          // $registration_number="'".trim($rows['registration_number'])."'";

           $function_status= $presentFunctionalStatus[$rows['functional_status']];
           $cooperative_society_name= str_replace('	','',$this->strClean(trim($rows['cooperative_society_name'])));
           $contact_person= str_replace('','',$this->strClean(trim($rows['contact_person'])));

           $registration_number= str_replace('	','',$this->strClean(trim($rows['registration_number'])));
           $mobile= str_replace('	','',$this->strClean(trim($rows['mobile'])));
       
          // $address= "State- ".$state_name. " District- ". $arr_districts[$rows['operational_district_code']]." Block- ".  $bloack_name." Panchayat_name- ".  $panchayat_name. " Pin Code -".$rows['pincode'];
          
           
           if($rows['is_coastal']==1)
            {
                $waterbody='YES';
            }
            else
            {
                $waterbody='NO';
            }
            $water_body_type = $water_body_typearray[$rows['water_body_type_id']];

            if($rows['is_affiliated_union_federation']==1)
            {
                $union='YES';
            }
            else
            {
                $union='NO';
            }
            $levelOfUnions = ['1'=>'DCCB','2'=>'STCB','3'=>'Other']; 
           
            $option =  ['1'=>'YES','0'=>'NO'];
            $categoryAudit = ['1'=>'A: Score more than 70','2'=>'B: Score between 50 to 70','3'=>'C: Score between 35 and 50', '4'=> 'D: Score less than 35','5'=>'Audit has not given any Score'];                                                                                                                                                                                                                                                                                                                   
                       
            $buildingTypes = ['1'=>'Own Building','2'=>'Rented Building','3'=>'Rent Free Building', '4'=> 'Leased Building','5'=>'Building Provided by the Government'];
        
        //    $data[] = [$i, $cooperative_society_name,  $contact_person,$mobile,$address ,$location,  $state_name, $dist_urbn ,$bloack_name,$panchayat_name,$waterbody,  $water_body_type, $sectors[$rows['sector_of_operation']],$registration_number, $coperative_id,
            //$rows['reference_year'], $rows['date_registration'],$astatus[$rows['is_approved']],$rows['members_of_society'],$option[$land],$own,$leas,$allot,$total,$loan,$revenue,$option[$fertilizer],$option[$fair],$option[$grain],$option[$storage],$capicity,$option[$cold],$cold_capicity,$union,$levelOfUnions[$rows['affiliated_union_federation_level']],$rows['affiliated_union_federation_name'],$function_status];

            $data[] = [$i, $cooperative_society_name, $location, $state_name, $dist_urbn,  $sectors[$rows['sector_of_operation']],$contact_person,$mobile,$function_status, $coperative_id,$rows['date_registration']];

         $i++;
         }

         $this->exportInExcelNew($fileName, $headerRow, $data);           

     }
         #################export Excel################




    }

    public function NewAllRegistrationList()
    {

        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $this->Flash->error(__('You are not authorized.'));
            return $this->redirect(['action' => 'dataEntryPending']);    
            
        }

     // $get_state_id = $this->request->session()->read('Auth.User.state_code');

        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        // if (!empty($this->request->query['society_name'])) {
        //     $societyName = trim($this->request->query['society_name']);
        //     $this->set('societyName', $societyName);
        //     $search_condition[] = "cooperative_society_name like '%" . $societyName . "%'";
        // }

        // if (!empty($this->request->query['from_date_registration'])) {
        //     $from_date = trim($this->request->query['from_date_registration']);
        //        $from_date_array =explode("/", $from_date);       
        //    $from_date_var= $from_date_array[2].'-'.$from_date_array[1].'-'.$from_date_array[0];
        //     $this->set('from_date_registration', $from_date);      
        //     $search_condition[] = "created >= '" . $from_date_var . " 00:00:00'";
         
        // }else
        // {
        //     $from_date_var = date('Y-m-d',strtotime('2023-04-01'));
        //     $search_condition[] = "created >= '" . $from_date_var . " 00:00:00'";
        // }
        // if (!empty($this->request->query['to_date_registration'])) {
        //     $todate = trim($this->request->query['to_date_registration']);
        //     $this->set('to_date_registration', $todate);

        //     $to_date_array =explode("/", $todate);
        //     // print_r($from_date_array);
        //      $to_date_var= $to_date_array[2].'-'.$to_date_array[1].'-'.$to_date_array[0];

        //      $search_condition[] = "created <= '" . $to_date_var . " 23:59:59'";
        //     //$search_condition[] = "cooperative_society_name like '%" . $societyName . "%'";
        // }else
        // {
        //     $to_date_var = date('Y-m-d');
        //     $search_condition[] = "created <= '" . $to_date_var . " 23:59:59'";
        // }

        // if (!empty($this->request->query['registration_number'])) {
        //     $registrationNumber = trim($this->request->query['registration_number']);
        //     $this->set('registrationNumber', $registrationNumber);
        //     $search_condition[] = "registration_number like '%" . $registrationNumber . "%'";
        // }

        // if (!empty($this->request->query['cooperative_id'])) {
        //     $cooperativeId = trim($this->request->query['cooperative_id']);
        //     $this->set('cooperativeId', $cooperativeId);
        //     $search_condition[] = "cooperative_id like '%" . $cooperativeId . "%'";
        // }

        // if (!empty($this->request->query['reference_year'])) {
        //     $referenceYear = trim($this->request->query['reference_year']);
        //     $this->set('referenceYear', $referenceYear);
        //     $search_condition[] = "reference_year like '%" . $referenceYear . "%'";
        // }

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $state = trim($this->request->query['state']);
            $this->set('state', $state);
            $search_condition[] = "state_code = '" . $state . "'";
        }

        // if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
        //     $location = trim($this->request->query['location']);
        //     $this->set('location', $location);
        //     $search_condition[] = "location_of_head_quarter = '" . $location . "'";
        // }

        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='' || isset($this->request->query['sector-operation']) && $this->request->query['sector-operation'] !='') {
            if(isset($this->request->query['sector-operation']))
            {
                $this->request->query['sector_operation']=$this->request->query['sector-operation'];
            }

            $s_sector_operation = trim($this->request->query['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "sector_of_operation_type = '" . $s_sector_operation . "'";
        }
        if(isset($this->request->query['primary-activity']))
        {
            $this->request->query['primary_activity']=$this->request->query['primary-activity'];
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

         //=============================Urban=====================================

        //  if (isset($this->request->query['local_category']) && $this->request->query['local_category'] !='') {
        //     $s_local_category = trim($this->request->query['local_category']);
        //     $this->set('s_local_category', $s_local_category);
        //     $search_condition[] = "urban_local_body_type_code = '" . $s_local_category . "'";
        // }

        // if (isset($this->request->query['local_body']) && $this->request->query['local_body'] !='') {
        //     $s_local_body = trim($this->request->query['local_body']);
        //     $this->set('s_local_body', $s_local_body);
        //     $search_condition[] = "urban_local_body_code = '" . $s_local_body . "'";
        // }

        // if (isset($this->request->query['ward']) && $this->request->query['ward'] !='') {
        //     $s_ward = trim($this->request->query['ward']);
        //     $this->set('s_ward', $s_ward);
        //     $search_condition[] = "locality_ward_code = '" . $s_ward . "'";
        // }

        //=============================Urban=====================================

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "operational_district_code = '" . $s_district . "'";
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

        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            $nodal_data_entry_ids = [];
            $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();
            
            if(!empty($nodal_data_entry_ids)){
                $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
                $nodal_data_entry_ids=implode(",",$nodal_data_entry_ids);
                
                $search_condition2 = "created_by IN (" . $nodal_data_entry_ids . ")";
            } else{
                $search_condition2 = "created_by IN (0)";
            }
        }

        $search_condition3='';
         if($this->request->session()->read('Auth.User.role_id') == 11 ||  $this->request->session()->read('Auth.User.role_id')==56 )
        {
           $state= $this->request->session()->read('Auth.User.state_code');

          
             $search_condition3  = "state_code = '" . $state . "'";

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
       // print_r($search_condition);



        $this->loadModel('CooperativeRegistrations');


        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => [$searchString,$search_condition2,$search_condition3]
        ])->select(['location_of_head_quarter','state_code','district_code','urban_local_body_code','sector_of_operation','cooperative_id_num','block_code','gram_panchayat_code','functional_status','cooperative_society_name','registration_number','is_coastal','water_body_type_id','reference_year','date_registration','is_approved','id','contact_person','mobile'])->where(['is_draft'=>0,'status'=>'1']);
        $this->paginate = ['limit' => 25];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        $this->set(compact('CooperativeRegistrations'));       

	    $this->loadModel('PresentFunctionalStatus');
                $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('presentFunctionalStatus',$presentFunctionalStatus);

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

        if(!empty($this->request->query['sector_operation']))
        {
            //for credit
            $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$this->request->query['sector_operation'],'status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        }

        $this->set('primary_activities',$primary_activities);


        // Start SecondaryActivities for dropdown
        $this->loadModel('SecondaryActivities');

        $secondary_activities = $this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $this->set('secondary_activities',$secondary_activities);

       //======================urban================================================

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 1)
       {
           $this->loadMOdel('UrbanLocalBodies');

           $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
   
           $this->set('local_categories',$local_categories);
       }
      if($this->request->session()->read('Auth.User.role_id') == 11)
       {
            $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();   
            $this->set('local_categories',$local_categories);
       }
        
       if(!empty($this->request->query['local_category']) && $this->request->query['location'] == 1)
       {
           

           $localbodies = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$this->request->query['local_category'],'state_code'=>$state])->order(['local_body_name'=>'ASC'])->toArray();
          
       }
    

       $this->set('localbodies',$localbodies); 

       if(!empty($this->request->query['local_body']) && $this->request->query['location'] == 1)
       {
           $this->loadMOdel('UrbanLocalBodiesWards');

           $wards = $this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$this->request->query['local_body'],'state_code'=>$this->request->query['state']])->order(['ward_name'=>'ASC'])->toArray();
       }
       
       $this->set('wards',$wards);      
        

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


       $villages = [];

       if(!empty($this->request->query['panchayat']) && $this->request->query['location'] == 2)
       {
           $this->loadMOdel('DistrictsBlocksGpVillages');

           $villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$this->request->query['panchayat']])->order(['village_name'=>'ASC'])->toArray();

           $this->set('villages',$villages);  
       }

       $this->loadModel('SectorOperations');

        
        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
         $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);

             
        if(!empty($this->request->query['export_excel']))
        { 

                $this->loadMOdel('DistrictsBlocksGpVillages');

        
         $i=1;
       //  mysql_set_charset("utf8");
            $this->loadModel('CooperativeRegistrationPacs');
            $this->loadModel('CooperativeRegistrationsLands');

            $queryExport = $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'ASC'],  
           // 'contain'=>['CooperativeRegistrationPacs','CooperativeRegistrationsLands'],        
            'conditions' => [$searchString,$search_condition2,$search_condition3]          
            ])->select(['CooperativeRegistrations.id','CooperativeRegistrations.location_of_head_quarter','CooperativeRegistrations.state_code','CooperativeRegistrations.district_code','CooperativeRegistrations.urban_local_body_code','CooperativeRegistrations.sector_of_operation','CooperativeRegistrations.cooperative_id_num','CooperativeRegistrations.block_code','CooperativeRegistrations.gram_panchayat_code','CooperativeRegistrations.functional_status','CooperativeRegistrations.cooperative_society_name','CooperativeRegistrations.registration_number','CooperativeRegistrations.is_coastal','CooperativeRegistrations.water_body_type_id','CooperativeRegistrations.is_affiliated_union_federation','CooperativeRegistrations.affiliated_union_federation_level',
            'CooperativeRegistrations.financial_audit','CooperativeRegistrations.is_dividend_paid','CooperativeRegistrations.audit_complete_year','CooperativeRegistrations.category_audit','CooperativeRegistrations.is_profit_making','CooperativeRegistrations.annual_turnover','CooperativeRegistrations.dividend_rate','CooperativeRegistrations.contact_person','CooperativeRegistrations.mobile','CooperativeRegistrations.designation','CooperativeRegistrations.landline','CooperativeRegistrations.email','CooperativeRegistrations.reference_year','CooperativeRegistrations.date_registration','CooperativeRegistrations.is_approved'])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.status'=>1]);

            $queryExport->hydrate(false);
            $ExportResultData = $queryExport->toArray();
            $fileName = "all_registration".date("d-m-y:h:s").".xls";
            $data = array(); 
            // Start PresentFunctionalStatus for dropdown
              
                $this->loadMOdel('WaterBodyTypes');
            //    $water_body_typearray = $this->WaterBodyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();
                $this->loadModel('Designations');
                $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();
                
                    $headerRow = array("S.No", "Cooperative Society Name","Location", "State", "District-Urban Local Body","Sector","contact person","Contact Number","function_sateus","Cooperative ID","Date of Registration");
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

           $bloack_name     =   $bloack[$rows['block_code']];
           $panchayat_name =   $panchayats[$rows['gram_panchayat_code']];
          // $registration_number="'".trim($rows['registration_number'])."'";

           $function_status= $presentFunctionalStatus[$rows['functional_status']];
           $cooperative_society_name= str_replace('	','',$this->strClean(trim($rows['cooperative_society_name'])));
           $contact_person= str_replace('','',$this->strClean(trim($rows['contact_person'])));

           $registration_number= str_replace('	','',$this->strClean(trim($rows['registration_number'])));
           $mobile= str_replace('	','',$this->strClean(trim($rows['mobile'])));
       
          // $address= "State- ".$state_name. " District- ". $arr_districts[$rows['operational_district_code']]." Block- ".  $bloack_name." Panchayat_name- ".  $panchayat_name. " Pin Code -".$rows['pincode'];
          
           
           if($rows['is_coastal']==1)
            {
                $waterbody='YES';
            }
            else
            {
                $waterbody='NO';
            }
            $water_body_type = $water_body_typearray[$rows['water_body_type_id']];

            if($rows['is_affiliated_union_federation']==1)
            {
                $union='YES';
            }
            else
            {
                $union='NO';
            }
            $levelOfUnions = ['1'=>'DCCB','2'=>'STCB','3'=>'Other']; 
           
            $option =  ['1'=>'YES','0'=>'NO'];
            $categoryAudit = ['1'=>'A: Score more than 70','2'=>'B: Score between 50 to 70','3'=>'C: Score between 35 and 50', '4'=> 'D: Score less than 35','5'=>'Audit has not given any Score'];                                                                                                                                                                                                                                                                                                                   
                       
            $buildingTypes = ['1'=>'Own Building','2'=>'Rented Building','3'=>'Rent Free Building', '4'=> 'Leased Building','5'=>'Building Provided by the Government'];
        
        //    $data[] = [$i, $cooperative_society_name,  $contact_person,$mobile,$address ,$location,  $state_name, $dist_urbn ,$bloack_name,$panchayat_name,$waterbody,  $water_body_type, $sectors[$rows['sector_of_operation']],$registration_number, $coperative_id,
            //$rows['reference_year'], $rows['date_registration'],$astatus[$rows['is_approved']],$rows['members_of_society'],$option[$land],$own,$leas,$allot,$total,$loan,$revenue,$option[$fertilizer],$option[$fair],$option[$grain],$option[$storage],$capicity,$option[$cold],$cold_capicity,$union,$levelOfUnions[$rows['affiliated_union_federation_level']],$rows['affiliated_union_federation_name'],$function_status];

            $data[] = [$i, $cooperative_society_name, $location, $state_name, $dist_urbn,  $sectors[$rows['sector_of_operation']],$contact_person,$mobile,$function_status, $coperative_id,$rows['date_registration']];

         $i++;
         }

         $this->exportInExcelNew($fileName, $headerRow, $data);           

     }
         #################export Excel################




    }



function gpwisecgecksocity()
{

    $this->loadModel('CoverageAreas');
    $this->loadModel('DistrictsBlocksGpVillages');
    $this->loadModel('PanchayatWiseSocityCount');

    
    set_time_limit(0);
    ini_set('memory_limit', '-1');
 
    $condtion['state_code'] =19;

    $fishary_array = $this->CoverageAreas->find('list',['keyField'=>'panchayat_code','valueField'=>'id'])->where(['sector_of_operation'=>'10','is_draft'=>0, 'status'=>1,'is_approved !='=>2,$condtion])->group(['panchayat_code'])->order(['district_code'=>'ASC','block_code'=>'ASC','panchayat_code'=>'ASC','village_code'=>'ASC'])->toarray();
    $dairy_array = $this->CoverageAreas->find('list',['keyField'=>'panchayat_code','valueField'=>'id'])->where(['sector_of_operation'=>'9','is_draft'=>0, 'status'=>1,'is_approved !='=>2,$condtion])->group(['panchayat_code'])->order(['district_code'=>'ASC','block_code'=>'ASC','panchayat_code'=>'ASC','village_code'=>'ASC'])->toarray();
    $pck_array = $this->CoverageAreas->find('list',['keyField'=>'panchayat_code','valueField'=>'id'])->where(['sector_of_operation IN'=>['1','20','22'],'is_draft'=>0, 'status'=>1,'is_approved !='=>2,$condtion])->group(['panchayat_code'])->order(['district_code'=>'ASC','block_code'=>'ASC','panchayat_code'=>'ASC','village_code'=>'ASC'])->toarray();
    $panchayats = $this->DistrictsBlocksGpVillages->find('all')->where(['status'=>1,$condtion])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();

   $fileName = "all_registration_westbagal-".$value['name']."-".date("d-m-y:h:s").".xls";
    $headerRow = array("S.No", "State", "District","block","panchayat_code","PAC","Dairy","Fishary");
    $data = array();
    $i=1;
    foreach($panchayats as $key=>$value)
    {
        

        if($pck_array[$value['gram_panchayat_code']] !='')
        {
            $pack='YES';
        }else{
            $pack='NO';
        }
        
        if($dairy_array[$value['gram_panchayat_code']]!='')
        {
            $dairy='YES';
        }else{
            $dairy='NO';
        }
        if($fishary_array[$value['gram_panchayat_code']]!='')
        {
            $fishary='YES';
        }else{
            $fishary='NO';
        }


      //  $acategories = $this->PanchayatWiseSocityCount->newEntity(); 
      
        // $data['state_name']             = $value['name'];       
        // $data['district_name']          = $value['district_name'];    
        // $data['block_name']             = $value['block_name']; 
        // $data['gram_panchayat_name']    = $value['gram_panchayat_name']; 
        // $data['pack']                   = $pack;
        // $data['dairy']                  = $dairy;
        // $data['fishary']                = $fishary; 
        // $data['created_date']           = date('Y-m-d');

       // $acategories = $this->PanchayatWiseSocityCount->patchEntity($acategories,  $data);

      //  print_r($data);

      //  if ($this->PacsPanchayatReportDistrictWise->save($acategories)) {
       // echo "success";

      //  $this->Flash->success(__('The PacsPanchayatReport  has been saved.'));
      //  }



        $data[] = [$i, $value['state_name'], $value['district_name'], $value['block_name'], $value['gram_panchayat_name'], $pack,$dairy,$fishary];

        $i++;

    }

   // print_r($data);
  //  die;
   $this->exportInExcel($fileName, $headerRow, $data);
    die;



}

public function NewCountReport()
{

    $this->loadModel('States');
    $this->loadModel('Districts');
    $this->loadModel('SubDistricts');
    $this->loadModel('PrimaryActivities');
    $this->loadMOdel('UrbanLocalBodies');
    $this->loadMOdel('CooperativeRegistrations');
    $this->loadMOdel('StateWiseReport');
    $this->loadMOdel('NewDistrictNodalEntries');
   

    set_time_limit(0);
    ini_set('memory_limit', '-1');
    $search_condition = array();
    $condtion_districtA=[];
    $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
    $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

    if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
        $s_state = trim($this->request->query['state']);
        $condtion_districtA['state_code'] = $this->request->query['state'];
       
        $this->set('s_state', $s_state);
       
    } 
    if($this->request->session()->read('Auth.User.role_id') == 11 ||  $this->request->session()->read('Auth.User.role_id')==56 )
    {
        
       $s_state= $this->request->session()->read('Auth.User.state_code');
       $condtion_districtA['state_code'] = $s_state;

       $this->set('s_state', $s_state);

    }
    if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
        $d_district_code = trim($this->request->query['state']);
        $condtion_districtA['district_code'] = $this->request->query['state'];
       
        $this->set('d_district_code', $d_district_code);
       
    } 

      if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='' || isset($this->request->query['sector-operation']) && $this->request->query['sector-operation'] !='') {
            if(isset($this->request->query['sector-operation']))
            {
                $this->request->query['sector_operation']=$this->request->query['sector-operation'];
            }

            $s_sector_operation = trim($this->request->query['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "sector_of_operation_type = '" . $s_sector_operation . "'";
        }
        if(isset($this->request->query['primary-activity']))
        {
            $this->request->query['primary_activity']=$this->request->query['primary-activity'];
        }

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);


            $this->set('s_primary_activity', $s_primary_activity);
            if($s_primary_activity =='1' || $s_primary_activity =='22' || $s_primary_activity =='20')
            {
                $search_condition[] = "sector_of_operation IN('1','20','22')";
            }else{
                $search_condition[] = "sector_of_operation = '" . $s_primary_activity . "'";
            }
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

        $till_date='2022-12-01 00:00:00';

        if($s_state !='')
        {

            $state_code1= $this->Districts->find('all')->where(['flag'=>1,$condtion_districtA])->order(['name'=>'ASC'])->select(['state_code','name','district_code']);
            $tilldate = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);            
            $tilldate = $tilldate->select(['district_code','count' => $tilldate->func()->count('id')])->where(['is_draft'=>0, 'status'=>1, 'created >='=>$till_date, 'is_approved !='=>2, $condtion_districtA,$searchString])->group(['district_code'])->toArray();

            $todate2 = date('Y-m-d').' 23:59:59';
           

            // $todate_total = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);            
            // $todate_total = $todate_total->select(['district_code','count' => $todate_total->func()->count('id')])->where(['is_draft'=>0, 'status'=>1, 'is_approved !='=>2,'created >='=>$todate1,'created <='=>$todate2, $condtion_districtA,$searchString])->group(['district_code'])->toArray();

            // $total_target = $this->NewDistrictNodalEntries->find('list',['keyField'=>'district_code','valueField'=>'count']);            
            // $total_target = $total_target->select(['district_code','count' => $total_target->func()->sum('primary_activity_count')])->where([ '	primaryactivitity_id NOT IN' => ['105','1','20','22','9','10'], $condtion_districtA])->group(['district_code'])->toArray();


        }else
        {
            $state_code1= $this->States->find('all')->where(['flag'=>1,$condtion_districtA])->order(['name'=>'ASC'])->select(['state_code','name']);

            $tilldate = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);            
            $tilldate = $tilldate->select(['state_code','count' => $tilldate->func()->count('id')])->where(['is_draft'=>0, 'status'=>1,'created >='=>$till_date, 'is_approved !='=>2, $condtion_districtA,$searchString])->group(['state_code'])->toArray();

           
            // $todate2 = date('Y-m-d').' 23:59:59';
            // $todate_total = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);            
            // $todate_total = $todate_total->select(['state_code','count' => $todate_total->func()->count('id')])->where(['is_draft'=>0, 'status'=>1, 'is_approved !='=>2,'created >='=>$todate1,'created <='=>$todate2, $condtion_districtA,$searchString])->group(['state_code'])->toArray();

        
            // $total_target = $this->NewDistrictNodalEntries->find('list',['keyField'=>'state_code','valueField'=>'count']);            
            // $total_target = $total_target->select(['state_code','count' => $total_target->func()->sum('primary_activity_count')])->where([ '	primaryactivitity_id NOT IN' => ['105','1','20','22','9','10'], $condtion_districtA])->group(['state_code'])->toArray();

            

        }
       
        $this->paginate = ['limit' => 36];
        $state_code_array = $this->paginate($state_code1);

        if($this->request->session()->read('Auth.User.role_id') == 11 ||  $this->request->session()->read('Auth.User.role_id')==56 )
        {
            $sOption =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1 ,'state_code'=>$s_state],'order' => ['name' => 'ASC']])->toArray(); 
        }else
        {    
             $sOption =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']])->toArray();    
        }  

    //$arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        
    $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,$condtion_districtA])->order(['name'=>'ASC'])->toArray();
    //sum of total no. of pac
    $this->loadModel('PrimaryActivities');
    if($s_sector_operation !='')
    { 
        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1,'sector_of_operation'=>$s_sector_operation ,'id NOT IN'=>['20,22']])->toArray();
        $this->set('sectors',$sectors); 
    }
    else
    {
        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1,'id NOT IN'=>['20,22']])->toArray();
        $this->set('sectors',$sectors); 
    }
 
    $this->loadModel('SectorOperations');

    $sectorOperations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();

    $this->set('sectorOperations', $sectorOperations);
    

    $this->set(compact('todate_total','tilldate','sOption','arr_districts','state_code','state_code_array','s_state','total_target'));
     
    if($this->request->is('get')){
        if(!empty($this->request->query['export_excel'])) {
           
            $fileName = "Report Todate -".date("d-m-y:h:s").".xls";
            $headerRow = array("S.No",  "State",  "To Date", "Till Date", "Total Target");
                $data = array();
                $i=1;
                if($s_state !='')
                {
                    $stateOption =$this->Districts->find('all',['conditions'=>['flag'=>1,$condtion_districtA],'order' => ['name' => 'ASC']])->toArray();
                }else
                {
                    $stateOption =$this->States->find('all',['conditions'=>['flag'=>1,$condtion_districtA],'order' => ['name' => 'ASC']])->toArray();
                }
        
               
                if($s_state !='')
                {

                    foreach($stateOption As $rows){
                    
                        $data[]=array($i, $rows['name'],$todate_total[$rows['district_code']] ?? 0, $tilldate[$rows['district_code']] ?? 0, $total_target[$rows['district_code']] ?? 0);
                        $i++;
                        $total_doday += $todate_total[$rows['district_code']];
                        $total_til_date += $tilldate[$rows['district_code']];
                        $total_target_aa += $total_target[$rows['district_code']];
                    } 
                }else
                {
                    foreach($stateOption As $rows){
                    
                        $data[]=array($i, $rows['name'],$todate_total[$rows['state_code']] ?? 0, $tilldate[$rows['state_code']] ?? 0,$total_target[$rows['state_code']] ?? 0);
                        $i++;
                        $total_doday += $todate_total[$rows['state_code']];
                        $total_til_date += $tilldate[$rows['state_code']];

                        $total_target_aa += $total_target[$rows['state_code']];

                        
                    } 
                }
                
              
               
                        $total_target_aa += $total_target[$rows['state_code']];
                $data[]=array('','Total' ,$total_doday, $total_til_date,$total_target_aa);
                $this->exportInExcel($fileName, $headerRow, $data);
        } 
    }

}

        
public function turnoverReport()
{
}
       
public function numberOfmemberReport()
{
        ################# Created by ravindra as par anayat sir ###############
        // this function use for all registration 150 highest have number of Member
        #################################
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadModel('CooperativeRegistrations');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $search_condition       =   array();
        $page_length            =   !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page                   =   !empty($this->request->query['page']) ? $this->request->query['page'] : 1; 
        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $state              =   trim($this->request->query['state']);
            $this->set('state', $state);
            $search_condition[] =   "state_code = '" . $state . "'";
        }
        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='') {
            $s_sector_operation =   trim($this->request->query['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] =   "sector_of_operation_type = '" . $s_sector_operation . "'";
        }

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity =   trim($this->request->query['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);
            $search_condition[] =   "sector_of_operation = '" . $s_primary_activity . "'";
        }
        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district         =   trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] =   "operational_district_code = '" . $s_district . "'";
        }
        $search_condition3      =   '';
         if($this->request->session()->read('Auth.User.role_id') == 11 ||  $this->request->session()->read('Auth.User.role_id')==56 )
        {
           $state               =   $this->request->session()->read('Auth.User.state_code');
           $search_condition3   =   "state_code = '" . $state . "'";           
        }       
        $search_condition[]     =   "is_approved  != 2";
      

        if(!empty($search_condition)){
            $searchString       =   implode(" AND ",$search_condition);
        } else {
            $searchString       =   '';
        }
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate     =   ['limit' => $page_length,];
        }

        
        $registrationQuery          =   $this->CooperativeRegistrations->find('all', [
            'order' => ['members_of_society' => 'DESC'],
            'conditions' => [$searchString,$search_condition2,$search_condition3,$search_condition4]
        ])->select(['state_code','district_code','sector_of_operation','cooperative_society_name','registration_number','is_coastal','date_registration','is_approved','id'])->limit(['150'])->where(['is_draft'=>0,'status'=>'1']);
        $this->paginate             =   ['limit' => 25];
        $CooperativeRegistrations   =   $this->paginate($registrationQuery);
        $this->set(compact('CooperativeRegistrations'));
        $query                      =   $this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption                =   $query->toArray();
        $this->set('sOption',$stateOption);
        $this->loadModel('SectorOperations');
        $sectorOperations           =   $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
        $this->set('sectorOperations', $sectorOperations);
        $primary_activities = [];
        if(!empty($this->request->query['sector_operation']))
        {
            $primary_activities     =   $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$this->request->query['sector_operation'],'status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        }
        $this->set('primary_activities',$primary_activities);  
        $districts = [];
       if(!empty($this->request->query['state']))
       {
           $districts               =   $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
      
        }

       $this->set('districts',$districts);
       $arr_districts               =   $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       $this->set('arr_districts',$arr_districts);
       $this->loadModel('SectorOperations');        
       $sectors                     =   $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();       
       $this->set('sectors',$sectors);             
        if(!empty($this->request->query['export_excel']))
        {  
            $queryExport            =   $this->CooperativeRegistrations->find('all', [
            'order' => ['created' => 'ASC'],                
            'conditions' => [$searchString,$search_condition2,$search_condition3,$search_condition4]          
            ])->select(['CooperativeRegistrations.id','CooperativeRegistrations.sector_of_operation','CooperativeRegistrations.cooperative_society_name','CooperativeRegistrations.registration_number','CooperativeRegistrations.annual_turnover','CooperativeRegistrations.contact_person','CooperativeRegistrations.mobile','CooperativeRegistrations.designation','CooperativeRegistrations.landline','CooperativeRegistrations.email','CooperativeRegistrations.date_registration','CooperativeRegistrations.is_approved'])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.status'=>1]);

            $queryExport->hydrate(false);
            $ExportResultData       =   $queryExport->toArray();
            $fileName               =   "all_registration".date("d-m-y:h:s").".xls";
            $data                   =   array(); 
            // Start PresentFunctionalStatus for dropdown  
            $headerRow              =   array("S.No", "Cooperative Society Name","contact person","Contact Number", "State", "District", "Sector", "Registration Number","Date of Registration","Status","No. of Member");
            $astatus                =   ['1'=>'Accepted','0'=>'Pending']; 

             foreach($ExportResultData as $key=>$rows)
             {
       
            $dist_urbn              =   '';
            $refrence_year          =   date('Y',strtotime($rows['date_registration']));              
            $state_name             =   $stateOption[$rows['state_code']];           
             $dist_urbn             =   $arr_districts[$rows['district_code']];
        
           $cooperative_society_name=   str_replace('	','',$this->strClean(trim($rows['cooperative_society_name'])));
           $contact_person          =   str_replace('','',$this->strClean(trim($rows['contact_person'])));

           $registration_number     =   str_replace('	','',$this->strClean(trim($rows['registration_number'])));
           $mobile                  =   str_replace('	','',$this->strClean(trim($rows['mobile'])));
       
       
            $data[]                 =   [$i, $cooperative_society_name, $contact_person,$mobile,$state_name, $dist_urbn , $sectors[$rows['sector_of_operation']],$registration_number, $rows['date_registration'],$rows['members_of_society']];
         $i++;
         }

         $this->exportInExcelNew($fileName, $headerRow, $data); 
         }  
}

    public function districtwisesocietycountreport()
    {
        $this->loadMOdel('DistrictFunctionalReports');
        $this->loadMOdel('DistrictReports');
        $this->loadMOdel('Districts');
        $this->loadMOdel('States');

        $states=$this->States->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();

        
        $districts = [];
        if(!empty($this->request->query['state']))
        {
            $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
        }
        
        $search_condition = array();
        $flag = 0;
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 80;
        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $flag = 1;
            $state = trim($this->request->query['state']);
            $this->set('s_state', $state);
            $search_condition[] = "state_code = '" . $state . "'";
        }

        if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
            $flag = 1;
            $s_district = trim($this->request->query['district_code']);
            $this->set('s_district', $s_district);
            $search_condition[] = "district_code = '" . $s_district . "'";
        }

        $search_condition3 = '';

        if($this->request->session()->read('Auth.User.role_id') == 11)
        {
            $state= $this->request->session()->read('Auth.User.state_code');
            $this->set('s_state', $state);
             $search_condition3  = "state_code = '" . $state . "'";

             $states=$this->States->find('list',['keyField'=>'id','valueField'=>'name'])->where(['state_code'=>$state])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
             $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$state])->order(['name'=>'ASC'])->toArray();
             $flag = 1;
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

        if (isset($this->request->query['functional_status']) && $this->request->query['functional_status'] == 1) {
            
            $query = $this->DistrictFunctionalReports;
        } else {
            $query = $this->DistrictReports;
        }

        if($flag == 0)
        {
            $districts_data = $query->find('all', [
                'order' => ['state_code' => 'ASC'],
                //'conditions' => [$searchString]
            ])->select(['name'=>'state_code',
            'agriculture_and_allied_cooperative'=>'sum(agriculture_and_allied_cooperative)',
            'agro_Processing_industrial_cooperative'=>'sum(agro_Processing_industrial_cooperative)',
            'bee_farming_cooperative'=>'sum(bee_farming_cooperative)',
            'consumer_cooperative'=>'sum(consumer_cooperative)',
            'credit_thrift_society'=>'sum(credit_thrift_society)',
            'dairy_cooperative'=>'sum(dairy_cooperative)',
            'educational_and_training_cooperatives'=>'sum(educational_and_training_cooperatives)',
            'farmers_service_societies'=>'sum(farmers_service_societies)',
            'fishery_cooperative'=>'sum(fishery_cooperative)',
            'handicraft_cooperative'=>'sum(handicraft_cooperative)',
            'handloom_textile_weavers_cooperative'=>'sum(handloom_textile_weavers_cooperative)',
            'jute_and_coir_cooperative'=>'sum(jute_and_coir_cooperative)',
            'labour_cooperative'=>'sum(labour_cooperative)',
            'large_area_multipurpose_society'=>'sum(large_area_multipurpose_society)',
            'livestock_poultry_cooperative'=>'sum(livestock_poultry_cooperative)',
            'miscellaneous'=>'sum(miscellaneous)',
            'miscellaneous_credit_cooperative_society'=>'sum(miscellaneous_credit_cooperative_society)',
            'multipurpose_cooperative'=>'sum(multipurpose_cooperative)',
            'primary_agricultural_credit_society_pacs'=>'sum(primary_agricultural_credit_society_pacs)',
            'primary_housing_cooperative_society'=>'sum(primary_housing_cooperative_society)',
            'primary_marketing_cooperative_society'=>'sum(primary_marketing_cooperative_society)',
            'sericulture_cooperative'=>'sum(sericulture_cooperative)',
            'social_welfare_and_cultural_cooperative'=>'sum(social_welfare_and_cultural_cooperative)',
            'sugar_mills_cooperative'=>'sum(sugar_mills_cooperative)',
            'tourism_cooperative'=>'sum(tourism_cooperative)',
            'transport_cooperative'=>'sum(transport_cooperative)',
            'tribal_SC_ST_cooperative'=>'sum(tribal_SC_ST_cooperative)',
            'urban_cooperative_bank_ucb'=>'sum(urban_cooperative_bank_ucb)',
            'women_welfare_cooperative_society'=>'sum(women_welfare_cooperative_society)',
            'member_count'=>'sum(member_count)'
            ])->where(['status'=>'1'])->group(['state_code']);
            
        } else {
            $districts_data = $query->find('all', [
                'order' => ['name' => 'ASC'],
                'conditions' => [$searchString,$search_condition3]
            ])->where(['status'=>'1']);
        }

        
                
        if(!empty($this->request->query['export_excel'])) {
            
            $fileName = "Sectorwise Society_count-".date("d-m-y:h:s").".xls";
            $headerRow = array("S.No", "Name","Agriculture and Allied Cooperative","Processing / Industrial Cooperative","Bee Farming Cooperative","Consumer Cooperative","Credit & Thrift Society","Dairy Cooperative","Educational and Training Cooperatives","Fishery Cooperative","Handicraft and Weaver Cooperative","Handloom Textile  & Weavers Cooperative","Jute and Coir Cooperative","Labour Cooperative","Livestock & Poultry Cooperative","Miscellaneous","Miscellaneous Credit Cooperative Society","Multipurpose Cooperative","Primary Agricultural Credit Society (PACS)","Primary Housing Cooperative Society","Primary Marketing Cooperative Society","Sericulture Cooperative","Social Welfare and Cultural Cooperative","Sugar Mills Cooperative","Tourism Cooperative","Transport Cooperative","Tribal-SC/ST Cooperative","Urban Cooperative Bank (UCB)","Women Welfare Cooperative Society","Total no. Cooperatives","Total no. Members");
            $data = array();
            
            $districts_data = $districts_data
            ///->limit($page_length)
            ->toArray();
            // echo '<pre>';
            // print_r($registrationQuery);die;
            $agriculture_and_allied_cooperative = 0;
            $agro_Processing_industrial_cooperative = 0;
            $bee_farming_cooperative = 0;
            $bee_farming_cooperative = 0;
            $consumer_cooperative = 0;
            $credit_thrift_society = 0;
            $dairy_cooperative = 0;
            $educational_and_training_cooperatives = 0;
            $fishery_cooperative = 0;
            $handicraft_cooperative = 0;
            $handloom_textile_weavers_cooperative = 0;
            $jute_and_coir_cooperative = 0;
            $labour_cooperative = 0;
            $livestock_poultry_cooperative = 0;
            $miscellaneous = 0;
            $miscellaneous_credit_cooperative_society = 0;
            $multipurpose_cooperative = 0;
            $primary_agricultural_credit_society_pacs = 0;
            $primary_housing_cooperative_society = 0;
            $primary_marketing_cooperative_society = 0;
            $sericulture_cooperative = 0;
            $social_welfare_and_cultural_cooperative = 0;
            $sugar_mills_cooperative = 0;
            $tourism_cooperative = 0;
            $transport_cooperative = 0;
            $tribal_SC_ST_cooperative = 0;
            $urban_cooperative_bank_ucb = 0;
            $women_welfare_cooperative_society = 0;
            $all_total = 0;
            $all_member_count = 0;
            foreach($districts_data as $key => $rows){

                $agriculture_and_allied_cooperative +=  $rows['agriculture_and_allied_cooperative'];
                $agro_Processing_industrial_cooperative +=  $rows['agro_Processing_industrial_cooperative'];
                $bee_farming_cooperative +=  $rows['bee_farming_cooperative'];
                $consumer_cooperative +=  $rows['consumer_cooperative'];
                $credit_thrift_society +=  $rows['credit_thrift_society'];
                $dairy_cooperative +=  $rows['dairy_cooperative'];
                $educational_and_training_cooperatives +=  $rows['educational_and_training_cooperatives'];
                $fishery_cooperative +=  $rows['fishery_cooperative'];
                $handicraft_cooperative +=  $rows['handicraft_cooperative'];
                $handloom_textile_weavers_cooperative +=  $rows['handloom_textile_weavers_cooperative'];
                $jute_and_coir_cooperative +=  $rows['jute_and_coir_cooperative'];
                $labour_cooperative +=  $rows['labour_cooperative'];
                $livestock_poultry_cooperative +=  $rows['livestock_poultry_cooperative'];
                $miscellaneous +=  $rows['miscellaneous'];
                $miscellaneous_credit_cooperative_society +=  $rows['miscellaneous_credit_cooperative_society'];
                $multipurpose_cooperative +=  $rows['multipurpose_cooperative'];
                $primary_agricultural_credit_society_pacs +=  $rows['primary_agricultural_credit_society_pacs'];
                $primary_housing_cooperative_society +=  $rows['primary_housing_cooperative_society'];
                $primary_marketing_cooperative_society +=  $rows['primary_marketing_cooperative_society'];
                $sericulture_cooperative +=  $rows['sericulture_cooperative'];
                $social_welfare_and_cultural_cooperative +=  $rows['social_welfare_and_cultural_cooperative'];
                $sugar_mills_cooperative +=  $rows['sugar_mills_cooperative'];
                $tourism_cooperative +=  $rows['tourism_cooperative'];
                $transport_cooperative +=  $rows['transport_cooperative'];
                $tribal_SC_ST_cooperative +=  $rows['tribal_SC_ST_cooperative'];
                $urban_cooperative_bank_ucb +=  $rows['urban_cooperative_bank_ucb'];
                $women_welfare_cooperative_society +=  $rows['women_welfare_cooperative_society'];

                $total = 0;
                $total = ($rows['agriculture_and_allied_cooperative']+$rows['agro_Processing_industrial_cooperative']+$rows['bee_farming_cooperative']+$rows['consumer_cooperative']+$rows['credit_thrift_society']+$rows['dairy_cooperative']+$rows['educational_and_training_cooperatives']+$rows['fishery_cooperative']+$rows['handicraft_cooperative']+$rows['handloom_textile_weavers_cooperative']+$rows['jute_and_coir_cooperative']+$rows['labour_cooperative']+$rows['livestock_poultry_cooperative']+$rows['miscellaneous']+$rows['miscellaneous_credit_cooperative_society']+$rows['multipurpose_cooperative']+$rows['primary_agricultural_credit_society_pacs']+$rows['primary_housing_cooperative_society']+$rows['primary_marketing_cooperative_society']+$rows['sericulture_cooperative']+$rows['social_welfare_and_cultural_cooperative']+$rows['sugar_mills_cooperative']+$rows['tourism_cooperative']+$rows['transport_cooperative']+$rows['tribal_SC_ST_cooperative']+$rows['urban_cooperative_bank_ucb']+$rows['women_welfare_cooperative_society']);

                $all_total += $total;
                $all_member_count += $rows['member_count'];

                $data[]=array(($key+1), ($flag == 0 ? $states[$rows['name']] : $rows['name']), $rows['agriculture_and_allied_cooperative'], $rows['agro_Processing_industrial_cooperative'], $rows['bee_farming_cooperative'], $rows['consumer_cooperative'], $rows['credit_thrift_society'], $rows['dairy_cooperative'], $rows['educational_and_training_cooperatives'], $rows['fishery_cooperative'], $rows['handicraft_cooperative'], $rows['handloom_textile_weavers_cooperative'], $rows['jute_and_coir_cooperative'], $rows['labour_cooperative'], $rows['livestock_poultry_cooperative'], $rows['miscellaneous'], $rows['miscellaneous_credit_cooperative_society'], $rows['multipurpose_cooperative'], $rows['primary_agricultural_credit_society_pacs'], $rows['primary_housing_cooperative_society'], $rows['primary_marketing_cooperative_society'], $rows['sericulture_cooperative'], $rows['social_welfare_and_cultural_cooperative'], $rows['sugar_mills_cooperative'], $rows['tourism_cooperative'], $rows['transport_cooperative'], $rows['tribal_SC_ST_cooperative'], $rows['urban_cooperative_bank_ucb'], $rows['women_welfare_cooperative_society'],$total,$rows['member_count']);
            }

            $data[] = array('','Total',$agriculture_and_allied_cooperative, $agro_Processing_industrial_cooperative, $bee_farming_cooperative, $consumer_cooperative, $credit_thrift_society, $dairy_cooperative, $educational_and_training_cooperatives, $fishery_cooperative, $handicraft_cooperative, $handloom_textile_weavers_cooperative, $jute_and_coir_cooperative, $labour_cooperative, $livestock_poultry_cooperative, $miscellaneous, $miscellaneous_credit_cooperative_society, $multipurpose_cooperative, $primary_agricultural_credit_society_pacs, $primary_housing_cooperative_society, $primary_marketing_cooperative_society, $sericulture_cooperative, $social_welfare_and_cultural_cooperative, $sugar_mills_cooperative, $tourism_cooperative, $transport_cooperative, $tribal_SC_ST_cooperative, $urban_cooperative_bank_ucb, $women_welfare_cooperative_society,$all_total,$all_member_count);
            $this->exportInExcel($fileName, $headerRow, $data);
        }
        

        // echo '<pre>';
        // print_r($districts_data);die;
        //$this->paginate = ['limit' => 20];
        $districts_data = $this->paginate($districts_data);


        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

        $this->set(compact('states','districts','districts_data','flag','presentFunctionalStatus'));

    }

    public function totalsocietycountreport()
    {
        $this->loadMOdel('DistrictFunctionalReports');
        $this->loadMOdel('DistrictReports');
        $this->loadMOdel('Districts');
        $this->loadMOdel('States');

        $states=$this->States->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();

        
        $districts = [];
        
        
        $search_condition = array();
        $flag = 0;
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 80;

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $flag = 1;
            $state = trim($this->request->query['state']);
            $this->set('s_state', $state);
            $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC']);
            $search_condition[] = "state_code = '" . $state . "'";
        }

        if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
            $flag = 1;
            $s_district = trim($this->request->query['district_code']);
            $this->set('s_district', $s_district);
            $search_condition[] = "district_code = '" . $s_district . "'";
        }


        if($this->request->session()->read('Auth.User.role_id') == 11)
        {
            $state= $this->request->session()->read('Auth.User.state_code');
            $this->set('s_state', $state);
             $search_condition3  = "state_code = '" . $state . "'";

             $states=$this->States->find('list',['keyField'=>'id','valueField'=>'name'])->where(['state_code'=>$state])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
             $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$state])->order(['name'=>'ASC'])->toArray();
             $flag = 1;
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

        if (isset($this->request->query['functional_status']) && $this->request->query['functional_status'] == 1) {
            
            $query = $this->DistrictFunctionalReports;
        } else {
            $query = $this->DistrictReports;
        }

        
        if($flag == 0)
        {
            $districts_data = $query->find('all', [
                'order' => ['state_code' => 'ASC'],
                //'conditions' => [$searchString]
            ])->select(['name'=>'state_code',
            'agriculture_and_allied_cooperative'=>'sum(agriculture_and_allied_cooperative)',
            'agro_Processing_industrial_cooperative'=>'sum(agro_Processing_industrial_cooperative)',
            'bee_farming_cooperative'=>'sum(bee_farming_cooperative)',
            'consumer_cooperative'=>'sum(consumer_cooperative)',
            'credit_thrift_society'=>'sum(credit_thrift_society)',
            'dairy_cooperative'=>'sum(dairy_cooperative)',
            'educational_and_training_cooperatives'=>'sum(educational_and_training_cooperatives)',
            'farmers_service_societies'=>'sum(farmers_service_societies)',
            'fishery_cooperative'=>'sum(fishery_cooperative)',
            'handicraft_cooperative'=>'sum(handicraft_cooperative)',
            'handloom_textile_weavers_cooperative'=>'sum(handloom_textile_weavers_cooperative)',
            'jute_and_coir_cooperative'=>'sum(jute_and_coir_cooperative)',
            'labour_cooperative'=>'sum(labour_cooperative)',
            'large_area_multipurpose_society'=>'sum(large_area_multipurpose_society)',
            'livestock_poultry_cooperative'=>'sum(livestock_poultry_cooperative)',
            'miscellaneous'=>'sum(miscellaneous)',
            'miscellaneous_credit_cooperative_society'=>'sum(miscellaneous_credit_cooperative_society)',
            'multipurpose_cooperative'=>'sum(multipurpose_cooperative)',
            'primary_agricultural_credit_society_pacs'=>'sum(primary_agricultural_credit_society_pacs)',
            'primary_housing_cooperative_society'=>'sum(primary_housing_cooperative_society)',
            'primary_marketing_cooperative_society'=>'sum(primary_marketing_cooperative_society)',
            'sericulture_cooperative'=>'sum(sericulture_cooperative)',
            'social_welfare_and_cultural_cooperative'=>'sum(social_welfare_and_cultural_cooperative)',
            'sugar_mills_cooperative'=>'sum(sugar_mills_cooperative)',
            'tourism_cooperative'=>'sum(tourism_cooperative)',
            'transport_cooperative'=>'sum(transport_cooperative)',
            'tribal_SC_ST_cooperative'=>'sum(tribal_SC_ST_cooperative)',
            'urban_cooperative_bank_ucb'=>'sum(urban_cooperative_bank_ucb)',
            'women_welfare_cooperative_society'=>'sum(women_welfare_cooperative_society)',
            'member_count'=>'sum(member_count)'
            ])->where(['status'=>'1'])->group(['state_code']);
            
        } else {
            $districts_data = $query->find('all', [
                'order' => ['name' => 'ASC'],
                'conditions' => [$searchString,$search_condition3]
            ])->where(['status'=>'1']);
        }
                
        if(!empty($this->request->query['export_excel'])) {
            
            $fileName = "Statewise Society_count-".date("d-m-y:h:s").".xls";
            $headerRow = array("S.No", "Name","Total no. Cooperatives","Total no. Members");
            $data = array();
            
            $districts_data = $districts_data
            ///->limit($page_length)
            ->toArray();
            // echo '<pre>';
            // print_r($registrationQuery);die;
            $all_total = 0;
            $all_member_count = 0;
            foreach($districts_data as $key => $rows){

                $total = 0;
                $total = ($rows['agriculture_and_allied_cooperative']+$rows['agro_Processing_industrial_cooperative']+$rows['bee_farming_cooperative']+$rows['consumer_cooperative']+$rows['credit_thrift_society']+$rows['dairy_cooperative']+$rows['educational_and_training_cooperatives']+$rows['fishery_cooperative']+$rows['handicraft_cooperative']+$rows['handloom_textile_weavers_cooperative']+$rows['jute_and_coir_cooperative']+$rows['labour_cooperative']+$rows['livestock_poultry_cooperative']+$rows['miscellaneous']+$rows['miscellaneous_credit_cooperative_society']+$rows['multipurpose_cooperative']+$rows['primary_agricultural_credit_society_pacs']+$rows['primary_housing_cooperative_society']+$rows['primary_marketing_cooperative_society']+$rows['sericulture_cooperative']+$rows['social_welfare_and_cultural_cooperative']+$rows['sugar_mills_cooperative']+$rows['tourism_cooperative']+$rows['transport_cooperative']+$rows['tribal_SC_ST_cooperative']+$rows['urban_cooperative_bank_ucb']+$rows['women_welfare_cooperative_society']);

                $all_total += $total;
                $all_member_count += $rows['member_count'];

                $data[]=array(($key+1), ($flag == 0 ? $states[$rows['name']] : $rows['name']), $total,$rows['member_count']);
            }

            $data[] = array('','Total',$all_total,$all_member_count);
            $this->exportInExcel($fileName, $headerRow, $data);
        }
        

        // echo '<pre>';
        // print_r($districts_data);die;
        //$this->paginate = ['limit' => 20];
        $districts_data = $this->paginate($districts_data);

        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

        $this->set(compact('states','districts','districts_data','flag','presentFunctionalStatus'));

    }

    public function districtwisesocietycountreportFirst()
    {
        $this->loadMOdel('DistrictReports');
        $this->loadMOdel('Districts');
        $this->loadMOdel('States');

        $states=$this->States->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();

        $districts = [];
        if(!empty($this->request->query['state']))
        {
            $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
        }
        
        $search_condition = array();
        $flag = 0;
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 36;
        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $flag = 1;
            $state = trim($this->request->query['state']);
            $search_condition[] = "state_code = '" . $state . "'";
        }

        if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
            $flag = 1;
            $s_district = trim($this->request->query['district_code']);
            $search_condition[] = "district_code = '" . $s_district . "'";
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

        if($flag == 0)
        {
            $districts_data = $this->DistrictReports->find('all', [
                'order' => ['state_code' => 'ASC'],
                //'conditions' => [$searchString]
            ])->select(['name'=>'state_code',
            'agriculture_and_allied_cooperative'=>'sum(agriculture_and_allied_cooperative)',
            'agro_Processing_industrial_cooperative'=>'sum(agro_Processing_industrial_cooperative)',
            'bee_farming_cooperative'=>'sum(bee_farming_cooperative)',
            'consumer_cooperative'=>'sum(consumer_cooperative)',
            'credit_thrift_society'=>'sum(credit_thrift_society)',
            'dairy_cooperative'=>'sum(dairy_cooperative)',
            'educational_and_training_cooperatives'=>'sum(educational_and_training_cooperatives)',
            'farmers_service_societies'=>'sum(farmers_service_societies)',
            'fishery_cooperative'=>'sum(fishery_cooperative)',
            'handicraft_cooperative'=>'sum(handicraft_cooperative)',
            'handloom_textile_weavers_cooperative'=>'sum(handloom_textile_weavers_cooperative)',
            'jute_and_coir_cooperative'=>'sum(jute_and_coir_cooperative)',
            'labour_cooperative'=>'sum(labour_cooperative)',
            'large_area_multipurpose_society'=>'sum(large_area_multipurpose_society)',
            'livestock_poultry_cooperative'=>'sum(livestock_poultry_cooperative)',
            'miscellaneous'=>'sum(miscellaneous)',
            'miscellaneous_credit_cooperative_society'=>'sum(miscellaneous_credit_cooperative_society)',
            'multipurpose_cooperative'=>'sum(multipurpose_cooperative)',
            'primary_agricultural_credit_society_pacs'=>'sum(primary_agricultural_credit_society_pacs)',
            'primary_housing_cooperative_society'=>'sum(primary_housing_cooperative_society)',
            'primary_marketing_cooperative_society'=>'sum(primary_marketing_cooperative_society)',
            'sericulture_cooperative'=>'sum(sericulture_cooperative)',
            'social_welfare_and_cultural_cooperative'=>'sum(social_welfare_and_cultural_cooperative)',
            'sugar_mills_cooperative'=>'sum(sugar_mills_cooperative)',
            'tourism_cooperative'=>'sum(tourism_cooperative)',
            'transport_cooperative'=>'sum(transport_cooperative)',
            'tribal_SC_ST_cooperative'=>'sum(tribal_SC_ST_cooperative)',
            'urban_cooperative_bank_ucb'=>'sum(urban_cooperative_bank_ucb)',
            'women_welfare_cooperative_society'=>'sum(women_welfare_cooperative_society)'
            ])->where(['status'=>'1'])->group(['state_code']);
            
        } else {
            $districts_data = $this->DistrictReports->find('all', [
                'order' => ['name' => 'ASC'],
                'conditions' => [$searchString]
            ])->where(['status'=>'1']);
        }

        
            $arr_total = [];
                foreach ($districts_data as $key => $row_data) {

                    $arr_total['agriculture_and_allied_cooperative'] += $row_data->agriculture_and_allied_cooperative;
                    $arr_total['agro_Processing_industrial_cooperative'] += $row_data->agro_Processing_industrial_cooperative;
                    $arr_total['bee_farming_cooperative'] += $row_data->bee_farming_cooperative;
                    $arr_total['consumer_cooperative'] += $row_data->consumer_cooperative;
                    $arr_total['credit_thrift_society'] += $row_data->credit_thrift_society;
                    $arr_total['dairy_cooperative'] += $row_data->dairy_cooperative;
                    $arr_total['farmers_service_societies'] += $row_data->farmers_service_societies;
                    $arr_total['fishery_cooperative'] += $row_data->fishery_cooperative;
                    $arr_total['handicraft_cooperative'] += $row_data->handicraft_cooperative;
                    $arr_total['handloom_textile_weavers_cooperative'] += $row_data->handloom_textile_weavers_cooperative;
                    $arr_total['jute_and_coir_cooperative'] += $row_data->jute_and_coir_cooperative;
                    $arr_total['labour_cooperative'] += $row_data->labour_cooperative;
                    $arr_total['large_area_multipurpose_society'] += $row_data->large_area_multipurpose_society;
                    $arr_total['livestock_poultry_cooperative'] += $row_data->livestock_poultry_cooperative;
                    $arr_total['miscellaneous'] += $row_data->miscellaneous;
                    $arr_total['multipurpose_cooperative'] += $row_data->multipurpose_cooperative;
                    $arr_total['primary_agricultural_credit_society_pacs'] += $row_data->primary_agricultural_credit_society_pacs;
                    $arr_total['primary_housing_cooperative_society'] += $row_data->primary_housing_cooperative_society;
                    $arr_total['primary_marketing_cooperative_society'] += $row_data->primary_marketing_cooperative_society;
                    $arr_total['sericulture_cooperative'] += $row_data->sericulture_cooperative;
                    $arr_total['social_welfare_and_cultural_cooperative'] += $row_data->social_welfare_and_cultural_cooperative;
                    $arr_total['sugar_mills_cooperative'] += $row_data->sugar_mills_cooperative;
                    $arr_total['tourism_cooperative'] += $row_data->tourism_cooperative;
                    $arr_total['transport_cooperative'] += $row_data->transport_cooperative;
                    $arr_total['tribal_SC_ST_cooperative'] += $row_data->tribal_SC_ST_cooperative;
                    $arr_total['urban_cooperative_bank_ucb'] += $row_data->urban_cooperative_bank_ucb;
                    $arr_total['women_welfare_cooperative_society'] += $row_data->women_welfare_cooperative_society;
                }

        if(!empty($this->request->query['export_excel'])) {
            
            $fileName = "Sectorwise Society_count-".date("d-m-y:h:s").".xls";
            $headerRow = array("S.No", "Name","Agriculture and Allied Cooperative","Agro Processing / Industrial","Bee Farming Cooperative","Consumer Cooperative","Credit & Thrift Society","Dairy Cooperative","Educational and Training Cooperatives","Fishery Cooperative","Handicraft and Weaver Cooperative","Handloom Textile & Weavers Cooperative","Jute and Coir Cooperative","Labour Cooperative","Livestock & Poultry Cooperative","Miscellaneous","Miscellaneous Credit Cooperative Society","Multipurpose Cooperative","Primary Agricultural Credit Society (PACS)","Primary Housing Cooperative Society","Primary Marketing Cooperative Society","Sericulture Cooperative","Social Welfare and Cultural Cooperative","Sugar Mills Cooperative","Tourism Cooperative","Transport Cooperative","Tribal-SC/ST Cooperative","Urban Cooperative Bank (UCB)","Women Welfare Cooperative Society");
            $data = array();
            
            $districts_data = $districts_data
            ///->limit($page_length)
            ->toArray();
            // echo '<pre>';
            // print_r($registrationQuery);die;
            
            foreach($districts_data as $key => $rows){
                
                $data[]=array(($key+1), ($flag == 0 ? $states[$rows['name']] : $rows['name']), $rows['agriculture_and_allied_cooperative'], $rows['agro_Processing_industrial_cooperative'], $rows['bee_farming_cooperative'], $rows['consumer_cooperative'], $rows['credit_thrift_society'], $rows['dairy_cooperative'], $rows['educational_and_training_cooperatives'], $rows['fishery_cooperative'], $rows['handicraft_cooperative'], $rows['handloom_textile_weavers_cooperative'], $rows['jute_and_coir_cooperative'], $rows['labour_cooperative'], $rows['livestock_poultry_cooperative'], $rows['miscellaneous'], $rows['miscellaneous_credit_cooperative_society'], $rows['multipurpose_cooperative'], ($rows['primary_agricultural_credit_society_pacs']+ $rows['farmers_service_societies']+$rows['large_area_multipurpose_society']), $rows['primary_housing_cooperative_society'], $rows['primary_marketing_cooperative_society'], $rows['sericulture_cooperative'], $rows['social_welfare_and_cultural_cooperative'], $rows['sugar_mills_cooperative'], $rows['tourism_cooperative'], $rows['transport_cooperative'], $rows['tribal_SC_ST_cooperative'], $rows['urban_cooperative_bank_ucb'], $rows['women_welfare_cooperative_society']);
            }
            $this->exportInExcel($fileName, $headerRow, $data);
        }
        

        // echo '<pre>';
        // print_r($districts_data);die;
        //$this->paginate = ['limit' => 20];
        $districts_data = $this->paginate($districts_data);


        $this->set(compact('states','districts','districts_data','flag','arr_total'));

    }


    public function districtwisesocietycount()
    {
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('Districts');


        /*
        $registrationQuery = $this->CooperativeRegistrations->find('all',
                       
                       )->join([
                        'states' => [
                            'table' => 'states',
                            'type' => 'INNER',
                            'conditions' => 'states.state_code = CooperativeRegistrations.state_code'
                        ],
                        'primary_activities' => [
                            'table' => 'primary_activities',
                            'type' => 'INNER',
                            'alias' => 'pactivities',
                            'conditions' => 'pactivities.id = CooperativeRegistrations.sector_of_operation'
                        ],
                        'districts' => [
                            'table' => 'districts',
                            'type' => 'INNER',
                            'conditions' => 'districts.district_code = CooperativeRegistrations.district_code'
                        ]])
                        ->select(['states.name','cnt' => 'count(CooperativeRegistrations.id)'])
                        ->where(['is_draft'=>0,'CooperativeRegistrations.status'=>1,'is_approved !='=>2])
                        ->group(['CooperativeRegistrations.state_code'])
                        ->order(['states.state_code'=>'ASC'])
                        ->toArray();*/

                        

        
            $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state_code']])->order(['name'=>'ASC'])->toArray();

            $state_code = $this->request->query['state_code'];
            
            $condition = [
                77 => 'agriculture_and_allied_cooperative',
                31 => 'agro_Processing_industrial_cooperative',
                79 => 'bee_farming_cooperative',
                80 => 'consumer_cooperative',
                18 => 'credit_thrift_society',
                9  => 'dairy_cooperative',
                84 => 'educational_and_training_cooperatives',
                //20 => 'farmers_service_societies',
                10 => 'fishery_cooperative',
                14 => 'handicraft_cooperative',
                13 => 'handloom_textile_weavers_cooperative',
                90 => 'jute_and_coir_cooperative',
                51 => 'labour_cooperative',
                //22 => 'large_area_multipurpose_society',
                54 => 'livestock_poultry_cooperative',
                29 => 'miscellaneous',
                35 => 'miscellaneous_credit_cooperative_society',
                16 => 'multipurpose_cooperative',
                1  => 'primary_agricultural_credit_society_pacs',
                47 => 'primary_housing_cooperative_society',
                82 => 'primary_marketing_cooperative_society',
                96 => 'sericulture_cooperative',
                98 => 'social_welfare_and_cultural_cooperative',
                11 => 'sugar_mills_cooperative',
                99 => 'tourism_cooperative',
                68 => 'transport_cooperative',
                102 => 'tribal_SC_ST_cooperative',
                7  => 'urban_cooperative_bank_ucb',
                15 => 'women_welfare_cooperative_society'
        ];

            
            $insert_data = [];
            if(!empty($districts))
            {
                $i=0;
                
                $this->loadMOdel('DistrictReports');
                $this->loadMOdel('CooperativeRegistrations');
                

                $record = $this->DistrictReports->find()->where(['state_code' => $state_code])->count();
                if($record > 0)
                {
                    $this->DistrictReports->deleteAll(array('state_code' => $state_code));
                }
                
                foreach($districts as $district_code => $d_name)
                {
                    $insert_data[$i]['state_code'] = $state_code;
                    $insert_data[$i]['district_code'] = $district_code;
                    $insert_data[$i]['name'] = $d_name;
                    
                    foreach($condition as $sector => $sector_name)
                    {
                        if($sector == 1)
                        {
                            $sector = [1,20,22];
                        }

                        if($sector == 20)
                        {
                            $sector = 0;
                        }

                        if($sector == 22)
                        {
                            $sector = 0;
                        }

                        $search_condition = ['CooperativeRegistrations.sector_of_operation IN'=>$sector];
                    
                       $registrationQuery = $this->CooperativeRegistrations->find('all',
                       ['conditions' => [$search_condition]]
                       )->join([
                        'states' => [
                            'table' => 'states',
                            'type' => 'INNER',
                            'conditions' => 'states.state_code = CooperativeRegistrations.state_code'
                        ],
                        'primary_activities' => [
                            'table' => 'primary_activities',
                            'type' => 'INNER',
                            'alias' => 'pactivities',
                            'conditions' => 'pactivities.id = CooperativeRegistrations.sector_of_operation'
                        ],
                        'districts' => [
                            'table' => 'districts',
                            'type' => 'INNER',
                            'conditions' => 'districts.district_code = CooperativeRegistrations.district_code'
                        ]])
                        ->select(['states.name','cnt' => 'count(CooperativeRegistrations.id)'])
                        ->where(['is_draft'=>0,'CooperativeRegistrations.status'=>1,'is_approved !='=>2,'is_multi_state' => 0,'states.state_code'=>$this->request->query['state_code'],'districts.district_code'=>$district_code])
                        ->order(['states.state_code'=>'ASC'])
                        ->order(['districts.district_code'=>'ASC'])
                        ->toArray();

                        // echo 'district:'.$district_code.' sector:'.$sector.'<br/>';
                        // echo '<pre>';
                        // print_r($registrationQuery);die;


                        $insert_data[$i][$sector_name] = $registrationQuery[0]['cnt'];

                        // if($district_code == 5 && $sector == 20)
                        // {
                        //     echo $registrationQuery[0]['cnt'];die;
                        // }

                            // echo '=====<br/>';
                            // print_r();die;
                        
                    }

                    $member_data = $this->CooperativeRegistrations->find('all')->join([
                        'states' => [
                            'table' => 'states',
                            'type' => 'INNER',
                            'conditions' => 'states.state_code = CooperativeRegistrations.state_code'
                        ],
                        'primary_activities' => [
                            'table' => 'primary_activities',
                            'type' => 'INNER',
                            'alias' => 'pactivities',
                            'conditions' => 'pactivities.id = CooperativeRegistrations.sector_of_operation'
                        ]])
                        ->select(['states.name','CooperativeRegistrations.sector_of_operation','total' => 'sum(CooperativeRegistrations.members_of_society)'])
                        ->where(['is_draft'=>0,'CooperativeRegistrations.status'=>1,'is_multi_state' => 0,'is_approved !='=>2,'members_of_society*1 <'=>100000,'CooperativeRegistrations.state_code'=>$this->request->query['state_code'],'CooperativeRegistrations.district_code'=>$district_code])
                        //->group(['states.state_code'])
                        //->group(['CooperativeRegistrations.sector_of_operation'])
                        ->order(['states.name'=>'ASC'])
                        ->first();

                        // echo '<pre>';
                        // print_r($member_data->total);die;
                    
                    $insert_data[$i]['member_count'] = $member_data->total ?? 0;
                    $insert_data[$i]['status'] = 1;
                    $insert_data[$i]['created'] = date('Y-m-d H:i:s');

                    // echo '<pre>';
                    // print_r($insert_data);die;
                   
                    $i++;
                }

                
                
                // echo '<pre>';
                // print_r($insert_data);die;
                $DistrictReportsData = $this->DistrictReports->newEntities($insert_data);
            
                if($this->DistrictReports->saveMany($DistrictReportsData))
                {
                    echo 'Data inserted successfully';
                }

            } else {
                echo "Wrong State";
            }

        die;

    }

    public function functionaldistrictwisesocietycount()
    {
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('DistrictFunctionalReports');
        $this->loadMOdel('Districts');
        
            $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state_code']])->order(['name'=>'ASC'])->toArray();

            $state_code = $this->request->query['state_code'];
            
            $condition = [
                77 => 'agriculture_and_allied_cooperative',
                31 => 'agro_Processing_industrial_cooperative',
                79 => 'bee_farming_cooperative',
                80 => 'consumer_cooperative',
                18 => 'credit_thrift_society',
                9  => 'dairy_cooperative',
                84 => 'educational_and_training_cooperatives',
                //20 => 'farmers_service_societies',
                10 => 'fishery_cooperative',
                14 => 'handicraft_cooperative',
                13 => 'handloom_textile_weavers_cooperative',
                90 => 'jute_and_coir_cooperative',
                51 => 'labour_cooperative',
                //22 => 'large_area_multipurpose_society',
                54 => 'livestock_poultry_cooperative',
                29 => 'miscellaneous',
                35 => 'miscellaneous_credit_cooperative_society',
                16 => 'multipurpose_cooperative',
                1  => 'primary_agricultural_credit_society_pacs',
                47 => 'primary_housing_cooperative_society',
                82 => 'primary_marketing_cooperative_society',
                96 => 'sericulture_cooperative',
                98 => 'social_welfare_and_cultural_cooperative',
                11 => 'sugar_mills_cooperative',
                99 => 'tourism_cooperative',
                68 => 'transport_cooperative',
                102 => 'tribal_SC_ST_cooperative',
                7  => 'urban_cooperative_bank_ucb',
                15 => 'women_welfare_cooperative_society'
        ];

            
            $insert_data = [];
            if(!empty($districts))
            {
                $i=0;
                

                $record = $this->DistrictFunctionalReports->find()->where(['state_code' => $state_code])->count();
                if($record > 0)
                {
                    $this->DistrictFunctionalReports->deleteAll(array('state_code' => $state_code));
                }
                
                foreach($districts as $district_code => $d_name)
                {
                    $insert_data[$i]['state_code'] = $state_code;
                    $insert_data[$i]['district_code'] = $district_code;
                    $insert_data[$i]['name'] = $d_name;
                    
                    foreach($condition as $sector => $sector_name)
                    {
                        if($sector == 1)
                        {
                            $sector = [1,20,22];
                        }

                        if($sector == 20)
                        {
                            $sector = 0;
                        }

                        if($sector == 22)
                        {
                            $sector = 0;
                        }

                        $search_condition = ['CooperativeRegistrations.sector_of_operation IN'=>$sector];
                    
                       $registrationQuery = $this->CooperativeRegistrations->find('all',
                       ['conditions' => [$search_condition]]
                       )->join([
                        'states' => [
                            'table' => 'states',
                            'type' => 'INNER',
                            'conditions' => 'states.state_code = CooperativeRegistrations.state_code'
                        ],
                        'primary_activities' => [
                            'table' => 'primary_activities',
                            'type' => 'INNER',
                            'alias' => 'pactivities',
                            'conditions' => 'pactivities.id = CooperativeRegistrations.sector_of_operation'
                        ],
                        'districts' => [
                            'table' => 'districts',
                            'type' => 'INNER',
                            'conditions' => 'districts.district_code = CooperativeRegistrations.district_code'
                        ]])
                        ->select(['states.name','cnt' => 'count(CooperativeRegistrations.id)'])
                        ->where(['CooperativeRegistrations.functional_status'=>1,'is_draft'=>0,'is_multi_state' => 0,'CooperativeRegistrations.status'=>1,'is_approved !='=>2,'states.state_code'=>$this->request->query['state_code'],'districts.district_code'=>$district_code])
                        ->order(['states.state_code'=>'ASC'])
                        ->order(['districts.district_code'=>'ASC'])
                        ->toArray();

                        // echo 'district:'.$district_code.' sector:'.$sector.'<br/>';
                        // echo '<pre>';
                        // print_r($registrationQuery);die;


                        $insert_data[$i][$sector_name] = $registrationQuery[0]['cnt'];

                        // if($district_code == 5 && $sector == 20)
                        // {
                        //     echo $registrationQuery[0]['cnt'];die;
                        // }

                            // echo '=====<br/>';
                            // print_r();die;
                        
                    }

                    $member_data = $this->CooperativeRegistrations->find('all')->join([
                        'states' => [
                            'table' => 'states',
                            'type' => 'INNER',
                            'conditions' => 'states.state_code = CooperativeRegistrations.state_code'
                        ],
                        'primary_activities' => [
                            'table' => 'primary_activities',
                            'type' => 'INNER',
                            'alias' => 'pactivities',
                            'conditions' => 'pactivities.id = CooperativeRegistrations.sector_of_operation'
                        ]])
                        ->select(['states.name','CooperativeRegistrations.sector_of_operation','total' => 'sum(CooperativeRegistrations.members_of_society)'])
                        ->where(['CooperativeRegistrations.functional_status'=>1,'is_multi_state'=>'0','is_draft'=>0,'CooperativeRegistrations.status'=>1,'is_approved !='=>2,'members_of_society*1 <'=>100000,'CooperativeRegistrations.state_code'=>$this->request->query['state_code'],'CooperativeRegistrations.district_code'=>$district_code])
                        //->group(['states.state_code'])
                        //->group(['CooperativeRegistrations.sector_of_operation'])
                        ->order(['states.name'=>'ASC'])
                        ->first();

                        // echo '<pre>';
                        // print_r($member_data->total);die;
                    
                    $insert_data[$i]['member_count'] = $member_data->total ?? 0;
                    $insert_data[$i]['status'] = 1;
                    $insert_data[$i]['created'] = date('Y-m-d H:i:s');

                    // echo '<pre>';
                    // print_r($insert_data);die;
                   
                    $i++;
                }

                
                
                // echo '<pre>';
                // print_r($insert_data);die;
                $DistrictFunctionalReportsData = $this->DistrictFunctionalReports->newEntities($insert_data);
            
                if($this->DistrictFunctionalReports->saveMany($DistrictFunctionalReportsData))
                {
                    echo 'Data inserted successfully';
                }

            } else {
                echo "Wrong State";
            }

        die;

    }
}