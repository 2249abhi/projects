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
class CooperativeRegistrationsController extends AppController
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
        $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','getdccb','getfederationlevel','approval','bulkapproval','getUrbanRural','generateUniqueNumber','getBank','getRegistrationAuthority']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {

        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $this->Flash->error(__('You are not authorized.'));
            return $this->redirect(['action' => 'dataEntryPending']);    
            
        }

        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies'); 
		 $this->loadMOdel('Blocks');
        $loginUser = $this->Auth->user();

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
            $search_condition[] = "cooperative_id_num like '%" . (int)substr($cooperativeId, 2) . "%'";
            $this->set('cooperativeId', $cooperativeId);
        }

        if (!empty($this->request->query['reference_year'])) {
            $referenceYear = trim($this->request->query['reference_year']);
            $this->set('referenceYear', $referenceYear);
            $search_condition[] = "reference_year like '%" . $referenceYear . "%'";
        }

        if (!empty($this->request->query['functional_status'])) {
            $operational_status = trim($this->request->query['functional_status']);
            $this->set('operational_status', $operational_status);
            $search_condition[] = "functional_status = '" . $operational_status . "'";
        }

        if (isset($this->request->query['is_approved']) && $this->request->query['is_approved'] !='') {
            $is_approved_society = trim($this->request->query['is_approved']);
            $this->set('is_approved_society', $is_approved_society);
            $search_condition[] = "is_approved = '" . $is_approved_society . "'";
        }else{
            $search_condition[] = "is_approved  != 2";
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
            $search_condition[] = "district_code = '" . $s_district . "'";
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
         if($this->request->session()->read('Auth.User.role_id') == 11 || $this->request->session()->read('Auth.User.role_id') == 56)
        {
           $state = $this->request->session()->read('Auth.User.state_code');

          
             $search_condition3  = "state_code = '" . $state . "'";

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

     
        
            $registrationQuery = $this->CooperativeRegistrations->find('all', [
                'order' => ['cooperative_id_num' => 'DESC'],
                'conditions' => [$searchString,$search_condition2,$search_condition3]
            ])->where(['is_draft'=>0,'status'=>1,'is_multi_state'=>0]);

            
        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

        $this->set(compact('CooperativeRegistrations'));
        $all_users = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'role_id IN'=>[7,8]])->toArray();

        $this->set(compact('all_users'));

        $query = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        if($this->request->session()->read('Auth.User.role_id') == 11)
        {
            $query = $query->where(['state_code'=>$state]);
        }
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

           $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$this->request->query['state']])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
   
           $this->set('local_categories',$local_categories);
       }
        
       if(!empty($this->request->query['local_category']) && $this->request->query['location'] == 1)
       {
           

           $localbodies = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$this->request->query['local_category'],'state_code'=>$this->request->query['state']])->order(['local_body_name'=>'ASC'])->toArray();
          
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
       }else if($this->request->session()->read('Auth.User.role_id') == 11 && $this->request->session()->read('Auth.User.role_id') == 56)
       {
        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->session()->read('Auth.User.state_code')])->order(['name'=>'ASC'])->toArray();
       }

       $this->set('districts',$districts);

       $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

        $this->set('arr_districts',$arr_districts);  
		
		$arr_blocks = $this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

        $this->set('arr_blocks',$arr_blocks);

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

        if($this->request->is('get'))
        {
         if(!empty($this->request->query['export_excel']))
         {
           
            $queryExport =  $this->CooperativeRegistrations->find('all', [
                'order' => ['cooperative_id_num' => 'DESC'],
                'conditions' => [$searchString,$search_condition2,$search_condition3]
            ])->where(['is_draft'=>0,'status'=>1,'is_multi_state'=>0]);
     
              
                 $queryExport->hydrate(false);
                 $ExportResultData = $queryExport->toArray();
                 $fileName = "report".date("d-m-y:h:s").".xls";
                 
   
                 $headerRow = array("S.No", "cooperative_society_name","Location","State","District","Block/Urban_local_body","Sector","Registration Number","Functional Status","No of Member","Reference Year","Date of Registration","Status","Created By");
                 $data = array();
                 $i=1;
                
                
   
                  $state_name =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
                  $sOption = $state_name->toArray(); 
				  $district_name =$this->Districts->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
                  $dOption = $district_name->toArray(); 
				   $arr_localbodies_arr=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

                  //$this->set('arr_localbodies',$arr_localbodies);
               
                  $functionalStatus = [
                    '1'=> 'Functional',
                    '2'=> 'Non-Functional / Dormant',
                    '3'=> 'Under Liquidation'
                ];

                $status = [
                    '1'=> 'Accepted',
                    '2'=> 'Pending'
                ];
   
                 foreach($ExportResultData As $rows){
                   //echo "<pre>" ; print_r($arr_localbodies) ; exit ;
                      // print_r($ExportResultData);
                    //    $rows;
                    //    echo $all_usern4[$all_usern2[$rows['user_payment']['updated_by']]]; 
					
					if($rows['location_of_head_quarter'] == 2)
                                    {
                                        $disurbun =  $arr_blocks[$rows['block_code']];
                                    }

                                    if($rows['location_of_head_quarter']  == 1)
                                    { 
								        $disurbun =  $arr_localbodies[$rows['urban_local_body_code']];
                                    }
                //$disurbun =  $dOption[$rows['district_code']];
                $data[] = [$i, $rows['cooperative_society_name'],$arr_location[$rows['location_of_head_quarter']],$sOption[$rows['state_code']],$arr_districts[$rows['district_code']],$disurbun,$sectors[$rows['sector_of_operation']],$rows['registration_number'],$functionalStatus[$rows['functional_status']],$rows['members_of_society'],$rows['reference_year'],date('d/m/Y',strtotime($rows['date_registration'])),$status[$rows['is_approved']],$all_users[$rows['created_by']] ];
                    $i++;
                }
                // die;
                $this->exportInExcelNew($fileName, $headerRow, $data);           
   
         }
        }
        
    }

    public function adminPending()
    {

        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $this->Flash->error(__('You are not authorized.'));
            return $this->redirect(['action' => 'dataEntryPending']);    
            
        }

        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');


        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {
            $societyName = trim($this->request->query['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "CooperativeRegistrations.cooperative_society_name like '%" . $societyName . "%'";
        }

        if (!empty($this->request->query['registration_number'])) {
            $registrationNumber = trim($this->request->query['registration_number']);
            $this->set('registrationNumber', $registrationNumber);
            $search_condition[] = "CooperativeRegistrations.registration_number like '%" . $registrationNumber . "%'";
        }

        if (!empty($this->request->query['cooperative_id'])) {
            $cooperativeId = trim($this->request->query['cooperative_id']);
            $search_condition[] = "CooperativeRegistrations.cooperative_id_num like '%" . (int)substr($cooperativeId, 2) . "%'";
            $this->set('cooperativeId', $cooperativeId);
        }

        if (!empty($this->request->query['reference_year'])) {
            $referenceYear = trim($this->request->query['reference_year']);
            $this->set('referenceYear', $referenceYear);
            $search_condition[] = "CooperativeRegistrations.reference_year like '%" . $referenceYear . "%'";
        }

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $state = trim($this->request->query['state']);
            $this->set('state', $state);
            $search_condition[] = "CooperativeRegistrations.state_code = '" . $state . "'";
        }

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $location = trim($this->request->query['location']);
            $this->set('location', $location);
            $search_condition[] = "CooperativeRegistrations.location_of_head_quarter = '" . $location . "'";
        }

        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='') {
            $s_sector_operation = trim($this->request->query['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "CooperativeRegistrations.sector_of_operation_type = '" . $s_sector_operation . "'";
        }

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);
            $search_condition[] = "CooperativeRegistrations.sector_of_operation = '" . $s_primary_activity . "'";
        }

        if (isset($this->request->query['secondary_activity']) && $this->request->query['secondary_activity'] !='') {
            $s_secondary_activity = trim($this->request->query['secondary_activity']);
            $this->set('s_secondary_activity', $s_secondary_activity);
            $search_condition[] = "CooperativeRegistrations.secondary_activity = '" . $s_secondary_activity . "'";
        }

         //=============================Urban=====================================

         if (isset($this->request->query['local_category']) && $this->request->query['local_category'] !='') {
            $s_local_category = trim($this->request->query['local_category']);
            $this->set('s_local_category', $s_local_category);
            $search_condition[] = "CooperativeRegistrations.urban_local_body_type_code = '" . $s_local_category . "'";
        }

        if (isset($this->request->query['local_body']) && $this->request->query['local_body'] !='') {
            $s_local_body = trim($this->request->query['local_body']);
            $this->set('s_local_body', $s_local_body);
            $search_condition[] = "CooperativeRegistrations.urban_local_body_code = '" . $s_local_body . "'";
        }

        if (isset($this->request->query['ward']) && $this->request->query['ward'] !='') {
            $s_ward = trim($this->request->query['ward']);
            $this->set('s_ward', $s_ward);
            $search_condition[] = "CooperativeRegistrations.locality_ward_code = '" . $s_ward . "'";
        }

        //=============================Urban=====================================

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "CooperativeRegistrations.district_code = '" . $s_district . "'";
        }

        if (isset($this->request->query['block']) && $this->request->query['block'] !='') {
            $s_block = trim($this->request->query['block']);
            $this->set('s_block', $s_block);
            $search_condition[] = "CooperativeRegistrations.block_code = '" . $s_block . "'";
        }

        if (isset($this->request->query['panchayat']) && $this->request->query['panchayat'] !='') {
            $s_panchayat = trim($this->request->query['panchayat']);
            $this->set('s_panchayat', $s_panchayat);
            $search_condition[] = "CooperativeRegistrations.gram_panchayat_code = '" . $s_panchayat . "'";
        }

        if (isset($this->request->query['village']) && $this->request->query['village'] !='') {
            $s_village = trim($this->request->query['village']);
            $this->set('s_village', $s_village);
            $search_condition[] = "CooperativeRegistrations.village_code = '" . $s_village . "'";
        }

        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            $nodal_data_entry_ids = [];
            $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();
            
            if(!empty($nodal_data_entry_ids)){
                $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
                $nodal_data_entry_ids=implode(",",$nodal_data_entry_ids);
                
                $search_condition2 = "CooperativeRegistrations.created_by IN (" . $nodal_data_entry_ids . ")";
            } else{
                $search_condition2 = "CooperativeRegistrations.created_by IN (0)";
            }
        }

        $search_condition3='';
         if($this->request->session()->read('Auth.User.role_id') == 11)
        {
           $state= $this->request->session()->read('Auth.User.state_code');

          
             $search_condition3  = "CooperativeRegistrations.state_code = '" . $state . "'";

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

        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['CooperativeRegistrations.cooperative_id_num' => 'DESC'],
            'conditions' => [$searchString,$search_condition2,$search_condition3],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>0,'CooperativeRegistrations.status'=>1]);

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

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

        // if(!empty($this->request->query['sector_operation']))
        // {
        //     //for credit
        //     $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$this->request->query['sector_operation'],'status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        // }
       
        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('primary_activities',$primary_activities);


        // Start SecondaryActivities for dropdown
        $this->loadModel('SecondaryActivities');

        $secondary_activities = $this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $this->set('secondary_activities',$secondary_activities);

       //======================urban================================================

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 1)
       {
           $this->loadMOdel('UrbanLocalBodies');

           $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$this->request->query['state']])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
   
           $this->set('local_categories',$local_categories);
       }
        
       if(!empty($this->request->query['local_category']) && $this->request->query['location'] == 1)
       {
           

           $localbodies = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$this->request->query['local_category'],'state_code'=>$this->request->query['state']])->order(['local_body_name'=>'ASC'])->toArray();
          
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

        if($this->request->session()->read('Auth.User.role_id') == 11)
        {
             $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1, 'id NOT IN'=>['20','22']])->toArray();
        }else
        {
             $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
        }

      
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);
    }

    //all accepted list of cooperative registration for admin
    public function adminAccepted()
    {
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');


        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {
            $societyName = trim($this->request->query['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "CooperativeRegistrations.cooperative_society_name like '%" . $societyName . "%'";
        }

        if (!empty($this->request->query['registration_number'])) {
            $registrationNumber = trim($this->request->query['registration_number']);
            $this->set('registrationNumber', $registrationNumber);
            $search_condition[] = "CooperativeRegistrations.registration_number like '%" . $registrationNumber . "%'";
        }

        if (!empty($this->request->query['cooperative_id'])) {
            $cooperativeId = trim($this->request->query['cooperative_id']);
            $search_condition[] = "CooperativeRegistrations.cooperative_id_num like '%" . (int)substr($cooperativeId, 2) . "%'";
            $this->set('cooperativeId', $cooperativeId);
        }

        if (!empty($this->request->query['reference_year'])) {
            $referenceYear = trim($this->request->query['reference_year']);
            $this->set('CooperativeRegistrations.referenceYear', $referenceYear);
            $search_condition[] = "CooperativeRegistrations.reference_year like '%" . $referenceYear . "%'";
        }

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $state = trim($this->request->query['state']);
            $this->set('state', $state);
            $search_condition[] = "CooperativeRegistrations.state_code = '" . $state . "'";
        }

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $location = trim($this->request->query['location']);
            $this->set('location', $location);
            $search_condition[] = "CooperativeRegistrations.location_of_head_quarter = '" . $location . "'";
        }

        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='') {
            $s_sector_operation = trim($this->request->query['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "CooperativeRegistrations.sector_of_operation_type = '" . $s_sector_operation . "'";
        }

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);
            $search_condition[] = "CooperativeRegistrations.sector_of_operation = '" . $s_primary_activity . "'";
        }

        if (isset($this->request->query['secondary_activity']) && $this->request->query['secondary_activity'] !='') {
            $s_secondary_activity = trim($this->request->query['secondary_activity']);
            $this->set('s_secondary_activity', $s_secondary_activity);
            $search_condition[] = "CooperativeRegistrations.secondary_activity = '" . $s_secondary_activity . "'";
        }

         //=============================Urban=====================================

         if (isset($this->request->query['local_category']) && $this->request->query['local_category'] !='') {
            $s_local_category = trim($this->request->query['local_category']);
            $this->set('s_local_category', $s_local_category);
            $search_condition[] = "CooperativeRegistrations.urban_local_body_type_code = '" . $s_local_category . "'";
        }

        if (isset($this->request->query['local_body']) && $this->request->query['local_body'] !='') {
            $s_local_body = trim($this->request->query['local_body']);
            $this->set('s_local_body', $s_local_body);
            $search_condition[] = "CooperativeRegistrations.urban_local_body_code = '" . $s_local_body . "'";
        }

        if (isset($this->request->query['ward']) && $this->request->query['ward'] !='') {
            $s_ward = trim($this->request->query['ward']);
            $this->set('s_ward', $s_ward);
            $search_condition[] = "CooperativeRegistrations.locality_ward_code = '" . $s_ward . "'";
        }

        //=============================Urban=====================================

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "CooperativeRegistrations.district_code = '" . $s_district . "'";
        }

        if (isset($this->request->query['block']) && $this->request->query['block'] !='') {
            $s_block = trim($this->request->query['block']);
            $this->set('s_block', $s_block);
            $search_condition[] = "CooperativeRegistrations.block_code = '" . $s_block . "'";
        }

        if (isset($this->request->query['panchayat']) && $this->request->query['panchayat'] !='') {
            $s_panchayat = trim($this->request->query['panchayat']);
            $this->set('s_panchayat', $s_panchayat);
            $search_condition[] = "CooperativeRegistrations.gram_panchayat_code = '" . $s_panchayat . "'";
        }

        if (isset($this->request->query['village']) && $this->request->query['village'] !='') {
            $s_village = trim($this->request->query['village']);
            $this->set('s_village', $s_village);
            $search_condition[] = "CooperativeRegistrations.village_code = '" . $s_village . "'";
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

        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['CooperativeRegistrations.cooperative_id_num' => 'DESC'],
            'conditions' => [$searchString],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>1,'CooperativeRegistrations.status'=>1]);

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

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

        // if(!empty($this->request->query['sector_operation']))
        // {
        //     //for credit
        //     $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$this->request->query['sector_operation'],'status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        // }
       
        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('primary_activities',$primary_activities);


        // Start SecondaryActivities for dropdown
        $this->loadModel('SecondaryActivities');

        $secondary_activities = $this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $this->set('secondary_activities',$secondary_activities);

       //======================urban================================================

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 1)
       {
           $this->loadMOdel('UrbanLocalBodies');

           $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$this->request->query['state']])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
   
           $this->set('local_categories',$local_categories);
       }
        
       if(!empty($this->request->query['local_category']) && $this->request->query['location'] == 1)
       {
           

           $localbodies = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$this->request->query['local_category'],'state_code'=>$this->request->query['state']])->order(['local_body_name'=>'ASC'])->toArray();
          
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

        if($this->request->session()->read('Auth.User.role_id') == 11)
        {
             $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1, 'id NOT IN'=>['20','22']])->toArray();
        }else
        {
             $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
        }

      
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);

        $users = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();

        $this->set('users',$users);
    }

    //all rejected list of cooperative registration for admin
    public function adminRejected()
    {
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');


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
            $search_condition[] = "cooperative_id_num like '%" . (int)substr($cooperativeId, 2) . "%'";
            $this->set('cooperativeId', $cooperativeId);
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
            $search_condition[] = "district_code = '" . $s_district . "'";
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

        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['cooperative_id_num' => 'DESC'],
            'conditions' => [$searchString]
        ])->where(['is_draft'=>0,'is_approved'=>2,'status'=>1]);

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

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

        // if(!empty($this->request->query['sector_operation']))
        // {
        //     //for credit
        //     $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$this->request->query['sector_operation'],'status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        // }
       
        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('primary_activities',$primary_activities);


        // Start SecondaryActivities for dropdown
        $this->loadModel('SecondaryActivities');

        $secondary_activities = $this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $this->set('secondary_activities',$secondary_activities);

       //======================urban================================================

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 1)
       {
           $this->loadMOdel('UrbanLocalBodies');

           $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$this->request->query['state']])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
   
           $this->set('local_categories',$local_categories);
       }
        
       if(!empty($this->request->query['local_category']) && $this->request->query['location'] == 1)
       {
           

           $localbodies = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$this->request->query['local_category'],'state_code'=>$this->request->query['state']])->order(['local_body_name'=>'ASC'])->toArray();
          
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

        if($this->request->session()->read('Auth.User.role_id') == 11)
        {
             $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1, 'id NOT IN'=>['20','22']])->toArray();
        }else
        {
             $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
        }

      
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);

        $users = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();

        $this->set('users',$users);
    }

    /**
     * View method
     *
     * @param string|null $id State id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view_old($id = null)
    {
        $CooperativeRegistration = $this->CooperativeRegistrations->get($id, [
            'contain' => ['CRMS','CooperativeSocietyTypes','AreaOfOperations','CooperativeRegistrationsContactNumbers','CooperativeRegistrationsEmails']
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
        $creditPrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>1,'status'=>1])->order(['orderseq'=>'ASC'])->toArray();

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

        $this->set(compact('creditPrimaryActivities','presentFunctionalStatus','locationOfHeadquarter','blockName','districtName','panchayatName','villageName','nonCreditPrimaryActivities'));
    }

     public function view($id = null)
     {
        $CooperativeRegistration = $this->CooperativeRegistrations->get($id, [
            'contain' => ['CRMS','CooperativeSocietyTypes','AreaOfOperations','AreaOfOperationLevelUrban','CooperativeRegistrationsContactNumbers','CooperativeRegistrationsEmails','CooperativeRegistrationPacs'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationDairy'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationFishery'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationsCreditThrift'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationsLands','CooperativeRegistrationsHousing','CooperativeRegistrationsLabour','CooperativeRegistrationsTransport','CooperativeRegistrationsMarketing','CooperativeRegistrationsConsumer','CooperativeRegistrationsProcessing','CooperativeRegistrationsHandloom','CooperativeRegistrationsAgriculture','CooperativeRegistrationsTribal','CooperativeRegistrationsTourism','CooperativeRegistrationsHandicraft','CooperativeRegistrationsEducation','CooperativeRegistrationsJute','CooperativeRegistrationsLivestock','CooperativeRegistrationsSericulture','CooperativeRegistrationsWocoop','CooperativeRegistrationsBee','CooperativeRegistrationsUcb','SocietyImplementingSchemes','CooperativeRegistrationsMulti']
        ]);
    
        // ->toArray();   

        // echo "<pre>";
     // print_r($CooperativeRegistration);
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
            $dbgv = $this->DistrictsBlocksGpVillages->find('all')->where(['status'=>1,'state_code'=>$CooperativeRegistration->state_code,'village_code'=>$CooperativeRegistration->village_code])->first(); 

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

              $this->loadModel('MiscellaneousCooperativeSocieties');   
              $miscellaneoustypes =$this->MiscellaneousCooperativeSocieties->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
              $this->set('miscellaneoustypes',$miscellaneoustypes);
      


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
                //print_r($urban_wards);die;
                if($urban_wards['locality_ward_code'] == '-1')
                {
                    $all_wards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$urban_wards['local_body_code']])->order(['ward_name'=>'ASC'])->toArray();

                    $urban_wards['locality_ward_code'] = implode(', ',array_values($all_wards));                    
                }

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
              $area_operation_level_row_all_gp = array();
            foreach($area_operation_level_row as $key => $value123)
            {
                $area_operation_level_row_v=$this->AreaOfOperationLevel->find('all')->where(['cooperative_registrations_id'=>$id,'row_id'=>$value123['row_id']])->order(['id'=>'ASC'])->toArray();

                foreach($area_operation_level_row_v as $key1=>$value1)
                {
                    $area_of_operation_id_rural = $value1['area_of_operation_id'];
                   
                    $area_operation_level_row_v_1[$value123['row_id']][]=$value1['village_code'] ;

                    $area_operation_level_row_all_gp[$value123['row_id']][]=$value1['panchayat_code'];
                }

                $area_operation_level_row_all_gp[$value123['row_id']] = array_unique($area_operation_level_row_all_gp[$value123['row_id']]);
            }

            $this->set('area_operation_level_row_all_gp',$area_operation_level_row_all_gp);

             $this->set('area_operation_level_row_v_1',$area_operation_level_row_v_1);
         
            if(!empty($statelevelarray))
            {
                $this->loadMOdel('DistrictsBlocksGpVillages');
                $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'state_code IN'=>$statelevelarray])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toarray();  
                $this->set('gps',$gps); 

                
                $gpsv=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'state_code IN'=>$statelevelarray])->group(['village_code'])->order(['village_name'=>'ASC'])->toarray();  
                $this->set('gpsv',$gpsv); 

                $gp_code_name = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'state_code IN'=>$statelevelarray])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toarray();  

                $this->set('gp_code_name',$gp_code_name);
            }

            
         $this->loadModel('TypeSocialCulturalActivities');   
         $activitiestypes =$this->TypeSocialCulturalActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();
         $this->set('activitiestypes',$activitiestypes);
 
          
         $this->loadMOdel('TypeSocialCooperatives');
             $type_social=$this->TypeSocialCooperatives->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();
             $this->set('type_social', $type_social);
       

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
 
            $this->loadModel('SecondaryActivities');   
            $multitypes =$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();
            $this->set('multitypes',$multitypes);
            
            $this->loadModel('BeeBeehiveTypes');   
            $beetypes =$this->BeeBeehiveTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type'=>1])->toarray();
            $this->set('beetypes',$beetypes);
    
            $beehivetypes =$this->BeeBeehiveTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type'=>2])->toarray();
            $this->set('beehivetypes',$beehivetypes);

            $this->loadMOdel('ProductProduceBySociety');
            $type_product=$this->ProductProduceBySociety->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>79])->toarray();
            $this->set('type_product', $type_product);
          
             
                
            $this->loadModel('HousingSocietyTypes');  
            $societytypes =$this->HousingSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
            $this->set('societytypes',$societytypes);

            $this->loadModel('LabourSocietyTypes');
            $lsocietytypes =$this->LabourSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
            $this->set('lsocietytypes',$lsocietytypes);
        
            $this->loadModel('TransportSocietyTypes');
            $trsocietytypes =$this->TransportSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
            $this->set('trsocietytypes',$trsocietytypes);

            $this->loadModel('EducationSocietyTypes');   
            $educationsocietytypes =$this->EducationSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
            $this->set('educationsocietytypes',$educationsocietytypes);

            $this->loadModel('TypeWomenCooperatives');   
            $womensocietytypes =$this->TypeWomenCooperatives->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
            $this->set('womensocietytypes',$womensocietytypes);
    
            $this->loadModel('LevelAndDurationCourses');   
            $levelofcourses =$this->LevelAndDurationCourses->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type' => 1])->toArray();
            $this->set('levelofcourses',$levelofcourses);
            $durationofcourses =$this->LevelAndDurationCourses->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type' => 2])->toArray();
            $this->set('durationofcourses',$durationofcourses);
            $leveldurationofcourses =$this->LevelAndDurationCourses->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
            $this->set('leveldurationofcourses',$leveldurationofcourses);
            
            
            $this->loadMOdel('TypeRawMaterial');
            $type_raw=$this->TypeRawMaterial->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
            $this->set('type_raw', $type_raw);

            $this->loadMOdel('TypeRawMaterial');
            $type_jraw=$this->TypeRawMaterial->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>90])->toArray();
            $this->set('type_jraw', $type_jraw);

            $this->loadMOdel('ProductProduceBySociety');  
            $productproduce=$this->ProductProduceBySociety->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>90])->toArray();
            $this->set('productproduce', $productproduce);  

            $this->loadModel('LivestockSocietyTypes');   
            $livesocietytypes =$this->LivestockSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
            $this->set('livesocietytypes',$livesocietytypes);
            
            $this->loadMOdel('LivestockPrimaryActivity');
            $type_lpa=$this->LivestockPrimaryActivity->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
            $this->set('type_lpa', $type_lpa);

            $this->loadMOdel('ProductProduceBySociety');  
            $lproductproduce=$this->ProductProduceBySociety->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>54])->toArray();
            $this->set('lproductproduce', $lproductproduce);

            $this->loadModel('AreaOfOperations');
            $areaOfOperations=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC'])->toArray();
         
            $this->loadModel('SerticultureSilkwormTypes');   
            $sertisocietytypes =$this->SerticultureSilkwormTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
            $this->set('sertisocietytypes',$sertisocietytypes);

            $this->loadModel('CooperativeSocietyFacilities');
            $societyFacilities=$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();
            $this->set('societyFacilities', $societyFacilities);

            $this->loadModel('CooperativeSocietyBanks');
               
            $CooperativeRegistration['bank_type'] = explode(',',$CooperativeRegistration['bank_type']);
            $CooperativeRegistration->cooperative_society_bank_id = explode(',',$CooperativeRegistration->cooperative_society_bank_id);

        
            $b_type = array_merge($CooperativeRegistration['bank_type'],[0]);

            $arr_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type IN'=>$b_type])->order(['orderseq'=>'ASC'])->toArray();
                

        $this->set(compact('creditPrimaryActivities','presentFunctionalStatus','locationOfHeadquarter','blockName','districtName','panchayatName','villageName','nonCreditPrimaryActivities','area_of_operation_id_rural','area_of_operation_id_urban','areaOfOperations','arr_banks'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {   
        $this->loadModel('ServiceQuestions');

        $services = $this->ServiceQuestions->find('all')->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

        $CooperativeRegistration = $this->CooperativeRegistrations->newEntity();
        $years = [];
        $l_year = date('Y')-122;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
        }
        if($this->request->is('post')){

            if($this->request->session()->read('Auth.User.role_id') != 7)
            {
                return $this->redirect(['action' => 'add']);$this->Flash->error(__('You are not Authorize to add Cooperative Registration'));
            }
            $data=$this->request->getData();

            /*
            if(isset($data['cooperative_registrations_services']) && !empty($data['cooperative_registrations_services']))
            {
                $ser_ans = [];
                foreach ($data['cooperative_registrations_services'] as $q_id => $ans) {
                    $ser_ans[] = [
                        'cooperative_registration_id' => '52',
                        'service_question_id' => $q_id,
                        'value' => $ans,
                        'created' => date('Y-m-d H:i:s')
                    ];
                }

                $this->loadModel('CooperativeRegistrationsServices');

                $c_r_s  = $this->CooperativeRegistrationsServices->newEntity();
                $c_r_s  = $this->CooperativeRegistrationsServices->patchEntities($c_r_s, $ser_ans);
                $c_r_s =  $this->CooperativeRegistrationsServices->saveMany($c_r_s);  

                die('success');

            }*/

            
          
           
            if($data['affiliated_union_federation_level'] == '18' && !empty($data['affiliated_union_federation_name_for_other']) && $data['affiliated_union_federation_name'] == '-1')
            {
                $this->loadModel('LevelOfAffiliatedUnion');            
                $level_of_aff_union = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1])->toarray();
                $this->loadModel('States');
                $state_name=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
                $this->loadModel('DistrictCentralCooperativeBank');
                $DistrictCentralCooperativeBankData = $this->DistrictCentralCooperativeBank->newEntity();

                $arr_dccb_data = [
                    'name' => $data['affiliated_union_federation_name_for_other'],
                    'state_code' =>  $data['state_code'],
                    'state_name' => $state_name[$data['state_code']],
                    'status' => '1',
                    'level_of_affiliated_union_id' => $data['affiliated_union_federation_level'],
                    'entity_type' => $level_of_aff_union[$data['affiliated_union_federation_level']],
                    'dccb_flag' => '0',
                    'primary_activity_flag' => $data['sector_of_operation']
                  
                ];
                $DistrictCentralCooperativeBankData = $this->DistrictCentralCooperativeBank->patchEntity($DistrictCentralCooperativeBankData, $arr_dccb_data);
            
                if($this->DistrictCentralCooperativeBank->save($DistrictCentralCooperativeBankData)) {
                    $data['affiliated_union_federation_name'] = $DistrictCentralCooperativeBankData->id;
                }
            }
            elseif($data['affiliated_union_federation_level'] == '17' && !empty($data['affiliated_union_federation_name_for_other']) && $data['affiliated_union_federation_name'] == '-1')
            {
                $this->loadModel('LevelOfAffiliatedUnion');            
                $level_of_aff_union = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1])->toarray();
                $this->loadModel('States');
                $state_name=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
                $this->loadModel('Districts');
                $districts_name=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toarray(); 
                $this->loadModel('DistrictCentralCooperativeBank');
                $DistrictCentralCooperativeBankData = $this->DistrictCentralCooperativeBank->newEntity();

                $arr_dccb_data = [
                    'name' => $data['affiliated_union_federation_name_for_other'],
                    'state_code' =>  $data['state_code'],
                    'state_name' => $state_name[$data['state_code']],
                    'district_code' => $data['district_code'],
                    'district_name' => $districts_name[$data['district_code']],
                    'status' => '1',
                    'level_of_affiliated_union_id' => $data['affiliated_union_federation_level'],
                    'entity_type' => $level_of_aff_union[$data['affiliated_union_federation_level']],
                    'dccb_flag' => '0',
                    'primary_activity_flag' => $data['sector_of_operation']
                  
                ];
                $DistrictCentralCooperativeBankData = $this->DistrictCentralCooperativeBank->patchEntity($DistrictCentralCooperativeBankData, $arr_dccb_data);
            
                if($this->DistrictCentralCooperativeBank->save($DistrictCentralCooperativeBankData)) {
                    $data['affiliated_union_federation_name'] = $DistrictCentralCooperativeBankData->id;
                }
            }
            elseif(!empty($data['affiliated_union_federation_name_for_other']) && $data['affiliated_union_federation_name'] == '-1')
            {
                $this->loadModel('LevelOfAffiliatedUnion');            
                $level_of_aff_union = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1])->toarray();
                $this->loadModel('DistrictCentralCooperativeBank');
                $DistrictCentralCooperativeBankData = $this->DistrictCentralCooperativeBank->newEntity();

                $arr_dccb_data = [
                    'name' => $data['affiliated_union_federation_name_for_other'],
                    'status' => '1',
                    'level_of_affiliated_union_id' => $data['affiliated_union_federation_level'],
                    //'entity_type' => $level_of_aff_union[$data['affiliated_union_federation_level']],
                    'dccb_flag' => '0',
                    'primary_activity_flag' => $data['sector_of_operation']
                  
                ];

                // echo '<pre>';
                // print_r($arr_dccb_data);die;
                $DistrictCentralCooperativeBankData = $this->DistrictCentralCooperativeBank->patchEntity($DistrictCentralCooperativeBankData, $arr_dccb_data);
            
                if($this->DistrictCentralCooperativeBank->save($DistrictCentralCooperativeBankData)) {
                    $data['affiliated_union_federation_name'] = $DistrictCentralCooperativeBankData->id;
                }

            }
           


            //array of pacs, dairy & fishery
            $arr_pdf = [1,20,22,9,10];
            if(!in_array($data['sector_of_operation'],$arr_pdf))
            {
                unset($data['pacs']);
                unset($data['area']);
                unset($data['dairy']);
                unset($data['fishery']);
            }

            $data['is_approved'] = 0;
            $data['remark'] = '';
            $data['approved_by'] = '';
            $data['status'] = 1;
            $data['created'] = date('Y-m-d H:i:s');
            $data['updated'] = date('Y-m-d H:i:s');
			$data['operational_district_code'] = $data['district_code'] ?? 0;

            $data['cooperative_society_name'] = trim($data['cooperative_society_name']);
            if($this->request->session()->read('Auth.User.role_id') == 2)
            {
                $data['is_approved'] = 1;
                $data['approved_by'] = $this->request->session()->read('Auth.User.id');
            }

            if(!empty($data['bank_type']))
            {
                $data['bank_type'] = implode(',',$data['bank_type']);
            }

            if(!empty($data['cooperative_society_bank_id']))
            {
                $data['cooperative_society_bank_id'] = implode(',',$data['cooperative_society_bank_id']);
            }
            
            // echo '<pre>';
            //  print_r($data['sector']);die;
			//add his district based on user if urban
			
            /*
                //now district code is compulsary in urban/rural case
            if(($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8) && $data['location_of_head_quarter'] == 1)
            {
                $data['district_code'] = $this->request->session()->read('Auth.User.district_code');
            }*/
			
            if(!empty($data['landline']))
            {
                $std = $data['std'];
                $landline = $data['landline'];
                $data['landline'] = $std.'-'.$landline;
            } else {
                unset($data['std']);
            }
            $data['created_by'] = $this->request->session()->read('Auth.User.id');
           
             if($data['is_draft'] == 0)
           {
               $cooperative_id_num= $this->generateUniqueNumber(); 
               $data['cooperative_id_num'] = $cooperative_id_num;
            }

            // if($data['is_gov_benefit'] == 0)
            // {
            //     unset($data['cooperative_received_benefits']);
            // }

            // if($data['is_scheme_implemented'] == 0)
            // {
            //     unset($data['society_implementing_schemes']);
            // }

          

            if($data['location_of_head_quarter']==2 && !empty($data['state_code']) && !empty($data['district_code']) && !empty($data['block_code']) && !empty($data['gram_panchayat_code']) && !empty($data['village_code'])){

                $cooperative_id=(int)$data['state_code']+(int)$data['district_code']+(int)$data['block_code']+(int)$data['gram_panchayat_code']+(int)$data['village_code'];
                $MaxCooperativeId=$this->CooperativeRegistrations->find('all')->select(['id'=>'MAX(id)'])->first();
                $cooperative_id=$cooperative_id.($MaxCooperativeId->id+1);
                $data['cooperative_id']=$cooperative_id;
            }elseif($data['location_of_head_quarter']==1 && !empty($data['state_code']) && !empty($data['urban_local_body_type_code']) && !empty($data['urban_local_body_code'])){

                $cooperative_id=(int)$data['state_code']+(int)$data['urban_local_body_type_code']+(int)$data['urban_local_body_code']+(int)$data['locality_ward_code'];

                $MaxCooperativeId=$this->CooperativeRegistrations->find('all')->select(['id'=>'MAX(id)'])->first();
                $cooperative_id=$cooperative_id.($MaxCooperativeId->id+1);
                $data['cooperative_id']=$cooperative_id;

               

            }
            
			
            $multi_states=$data['multi_states'];

            if(empty($data['multi_states']))
            {
                $multi_states = [];
            }
            //unset($data['multi_states']);

            $crcns=$data['cooperative_registrations_contact_numbers'];
            //unset($data['cooperative_registrations_contact_numbers']);

            
            $cres=$data['cooperative_registrations_emails'];
            //unset($data['cooperative_registrations_emails']);

            if(count($multi_states)>1){
               $data['is_multi_state']=1; 
            }

            $data['reference_year']=date('Y');
            $data['date_registration'] = date("Y-m-d",strtotime(str_replace("/","-",$data['date_registration'])));

            if($data['sector_of_operation'] == '47')
            {
                $data['cooperative_registrations_housing']['facilities'] = implode(',',$data['cooperative_registrations_housing']['facilities']);
            }
            if($data['sector_of_operation'] == '80')
            {
                $data['cooperative_registrations_consumer']['facilities'] = implode(',',$data['cooperative_registrations_consumer']['facilities']);
            }
            if($data['sector_of_operation'] == '51')
            {
                $data['cooperative_registrations_labour']['facilities'] = implode(',',$data['cooperative_registrations_labour']['facilities']);
            }
            
            if($data['sector_of_operation'] == '82')
            {
                //marketing

                if(!empty($data['cooperative_registrations_marketing']['is_members_of']))
                {
                    $data['cooperative_registrations_marketing']['is_members_of'] = implode(',',$data['cooperative_registrations_marketing']['is_members_of']);
                }

                if(!empty($data['cooperative_registrations_marketing']['liecense_to_sell']))
                {
                    $data['cooperative_registrations_marketing']['liecense_to_sell'] = implode(',',$data['cooperative_registrations_marketing']['liecense_to_sell']);
                }

                if(!empty($data['cooperative_registrations_marketing']['sell_the_item']))
                {
                    $data['cooperative_registrations_marketing']['sell_the_item'] = implode(',',$data['cooperative_registrations_marketing']['sell_the_item']);
                }
                
            }

            if($data['sector_of_operation'] == '18')
            {
                $data['cooperative_registrations_credit_thrift']['facilities'] = implode(',',$data['cooperative_registrations_credit_thrift']['facilities']);
            }
            
            if($data['sector_of_operation'] == '96')
            {
                $data['cooperative_registrations_sericulture']['facilities'] = implode(',',$data['cooperative_registrations_sericulture']['facilities']);
                $data['cooperative_registrations_sericulture']['type_society'] = implode(',',$data['cooperative_registrations_sericulture']['type_society']);

            }
            if($data['sector_of_operation'] == '98')
            {
                $data['cooperative_registrations_social']['facilities'] = implode(',',$data['cooperative_registrations_social']['facilities']);
                $data['cooperative_registrations_social']['type_social_culture_activity'] = implode(',',$data['cooperative_registrations_social']['type_social_culture_activity']);

            }

            
            if($data['sector_of_operation'] == '102')
            {
                $data['cooperative_registrations_tribal']['facilities'] = implode(',',$data['cooperative_registrations_tribal']['facilities']);
            }
            if($data['sector_of_operation'] == '35')
            {
                $data['cooperative_registrations_cmiscellaneous']['facilities'] = implode(',',$data['cooperative_registrations_cmiscellaneous']['facilities']);
            }

            if($data['sector_of_operation'] == '99')
            {
                $data['cooperative_registrations_tourism']['facilities'] = implode(',',$data['cooperative_registrations_tourism']['facilities']);
            }

            if($data['sector_of_operation'] == '77') 
            {
                $data['cooperative_registrations_agriculture']['farming_mech'] = implode(',',$data['cooperative_registrations_agriculture']['farming_mech']);
                $data['cooperative_registrations_agriculture']['irrigation_means'] = implode(',',$data['cooperative_registrations_agriculture']['irrigation_means']);

            }

            if($data['sector_of_operation'] == '14') 
            {
                $data['cooperative_registrations_handicraft']['type_raw'] = implode(',',$data['cooperative_registrations_handicraft']['type_raw']);
                $data['cooperative_registrations_handicraft']['type_produce'] = implode(',',$data['cooperative_registrations_handicraft']['type_produce']);
                $data['cooperative_registrations_handicraft']['facilities'] = implode(',',$data['cooperative_registrations_handicraft']['facilities']);


            }
            if($data['sector_of_operation'] == '90') 
            {
                $data['cooperative_registrations_jute']['type_raw'] = implode(',',$data['cooperative_registrations_jute']['type_raw']);
                $data['cooperative_registrations_jute']['type_produce'] = implode(',',$data['cooperative_registrations_jute']['type_produce']);
                $data['cooperative_registrations_jute']['facilities'] = implode(',',$data['cooperative_registrations_jute']['facilities']);


            }
            if($data['sector_of_operation'] == '79') 
            {
                $data['cooperative_registrations_bee']['type_bee'] = implode(',',$data['cooperative_registrations_bee']['type_bee']);
                $data['cooperative_registrations_bee']['type_product'] = implode(',',$data['cooperative_registrations_bee']['type_product']);
                $data['cooperative_registrations_bee']['facilities'] = implode(',',$data['cooperative_registrations_bee']['facilities']);



            }
            if($data['sector_of_operation'] == '16') 
            {
                $data['cooperative_registrations_multi']['sec_activity'] = implode(',',$data['cooperative_registrations_multi']['sec_activity']);
                $data['cooperative_registrations_multi']['facilities'] = implode(',',$data['cooperative_registrations_multi']['facilities']);

                // echo '<pre>';
                // print_r($data['cooperative_registrations_multi']);die;

            }
            if($data['sector_of_operation'] == '54') 
            {
                $data['cooperative_registrations_livestock']['type_society'] = implode(',',$data['cooperative_registrations_livestock']['type_society']);
                $data['cooperative_registrations_livestock']['type_pa'] = implode(',',$data['cooperative_registrations_livestock']['type_pa']);
                $data['cooperative_registrations_livestock']['type_produce'] = implode(',',$data['cooperative_registrations_livestock']['type_produce']);
                $data['cooperative_registrations_livestock']['facilities'] = implode(',',$data['cooperative_registrations_livestock']['facilities']);
            }
            if($data['sector_of_operation'] == '15') 
            {
              
                $data['cooperative_registrations_wocoop']['facilities'] = implode(',',$data['cooperative_registrations_wocoop']['facilities']);
                
            }

         
            if($data['sector_of_operation'] == '84') 
            {
                $data['cooperative_registrations_education']['level_and_duration_of_course'] = implode(',',$data['cooperative_registrations_education']['level_and_duration_of_course']);
                $data['cooperative_registrations_education']['facilities']  = implode(',',$data['cooperative_registrations_education']['facilities']);
            }
           
            if($data['sector_of_operation'] == '11') 
            {
                $data['cooperative_registrations_sugar']['product_produced'] = implode(',',$data['cooperative_registrations_sugar']['product_produced']);
                if(!empty($data['cooperative_registrations_sugar']['crushing_period_start']))
                {
                    $data['cooperative_registrations_sugar']['crushing_period_start']= date("Y-m-d",strtotime(str_replace("/","-",$data['cooperative_registrations_sugar']['crushing_period_start'])));
                }

                if(!empty($data['cooperative_registrations_sugar']['crushing_period_end']))
                {
                    $data['cooperative_registrations_sugar']['crushing_period_end']= date("Y-m-d",strtotime(str_replace("/","-",$data['cooperative_registrations_sugar']['crushing_period_end'])));    
                }
            }

            // if($data['sector_of_operation'] == '7') 
            // {
            // $data['cooperative_registrations_ucb']['date_agm'] = date("Y-m-d",strtotime(str_replace("/","-",$data['cooperative_registrations_ucb']['date_agm'])));
            // $data['cooperative_registrations_ucb']['latest_annual_report'] = date("Y-m-d",strtotime(str_replace("/","-",$data['cooperative_registrations_ucb']['latest_annual_report'])));
            // $data['cooperative_registrations_ucb']['latest_gboard_elect'] = date("Y-m-d",strtotime(str_replace("/","-",$data['cooperative_registrations_ucb']['latest_gboard_elect'])));
            // $data['cooperative_registrations_ucb']['next_gboard_elect'] = date("Y-m-d",strtotime(str_replace("/","-",$data['cooperative_registrations_ucb']['next_gboard_elect'])));


            // }

            // echo '<pre>';
            // print_r($data);die;

          //  $CooperativeRegistration = $this->CooperativeRegistrations->patchEntity($CooperativeRegistration, $data,['associated' => ['SocietyImplementingSchemes','CooperativeReceivedBenefits','CooperativeRegistrationsMarketing','CooperativeRegistrationsHousing','CooperativeRegistrationsTransport','CooperativeRegistrationsCreditThrift','CooperativeRegistrationsConsumer','CooperativeRegistrationsLabour','CooperativeRegistrationsLands','CooperativeRegistrationsProcessing','CooperativeRegistrationsHandloom','CooperativeRegistrationsAgriculture','CooperativeRegistrationsTribal','CooperativeRegistrationsTourism','CooperativeRegistrationsHandicraft','CooperativeRegistrationsEducation','CooperativeRegistrationsJute','CooperativeRegistrationsLivestock','CooperativeRegistrationsSericulture','CooperativeRegistrationsWocoop','CooperativeRegistrationsBee','CooperativeRegistrationsMulti']]);
          $CooperativeRegistration = $this->CooperativeRegistrations->patchEntity($CooperativeRegistration, $data,['associated' => ['SocietyImplementingSchemes','CooperativeRegistrationsUcb','CooperativeReceivedBenefits','CooperativeRegistrationsMarketing','CooperativeRegistrationsHousing','CooperativeRegistrationsTransport','CooperativeRegistrationsCreditThrift','CooperativeRegistrationsConsumer','CooperativeRegistrationsLabour','CooperativeRegistrationsLands','CooperativeRegistrationsProcessing','CooperativeRegistrationsHandloom','CooperativeRegistrationsAgriculture','CooperativeRegistrationsTribal','CooperativeRegistrationsTourism','CooperativeRegistrationsHandicraft','CooperativeRegistrationsEducation','CooperativeRegistrationsJute','CooperativeRegistrationsLivestock','CooperativeRegistrationsSericulture','CooperativeRegistrationsWocoop','CooperativeRegistrationsBee','CooperativeRegistrationsMulti']]);
           
            if($this->CooperativeRegistrations->save($CooperativeRegistration)) {
                
              
                //Cooperative Registrations pacs data
             if($data['sector_of_operation'] == 1 || $data['sector_of_operation'] == 20 || $data['sector_of_operation'] == 22)
             {
                //save dynamic questions
                if(isset($data['cooperative_registrations_services']) && !empty($data['cooperative_registrations_services']))
                {
                    $ser_ans = [];
                    foreach ($data['cooperative_registrations_services'] as $q_id => $ans) {
                        $ser_ans[] = [
                            'cooperative_registration_id' => $CooperativeRegistration->id,
                            'service_question_id' => $q_id,
                            'value' => $ans,
                            'created' => date('Y-m-d H:i:s')
                        ];
                    }

                    $this->loadModel('CooperativeRegistrationsServices');

                    $c_r_s  = $this->CooperativeRegistrationsServices->newEntity();
                    $c_r_s  = $this->CooperativeRegistrationsServices->patchEntities($c_r_s, $ser_ans);
                    $c_r_s =  $this->CooperativeRegistrationsServices->saveMany($c_r_s);  

                }

                 $this->loadModel('CooperativeRegistrationPacs');
                 $data['pacs']['cooperative_registrations_id'] = $CooperativeRegistration->id;

                //  echo '<pre>';
                //  print_r($data['pacs']);die;
                 $CooperativeRegistrationPac = $this->CooperativeRegistrationPacs->newEntity();
                 
                 $CooperativeRegistrationPac = $this->CooperativeRegistrationPacs->patchEntity($CooperativeRegistrationPac, $data['pacs']);
                 $this->CooperativeRegistrationPacs->save($CooperativeRegistrationPac);     
             }

             //Cooperative Registrations Dairy data
             if($data['sector_of_operation'] == 9)
             {
                 $this->loadModel('CooperativeRegistrationDairy');
                 $data['dairy']['cooperative_registrations_id'] = $CooperativeRegistration->id;

                //  echo '<pre>';
                //  print_r($data['pacs']);die;
                 $dairy = $this->CooperativeRegistrationDairy->newEntity();
                 
                 $dairy = $this->CooperativeRegistrationDairy->patchEntity($dairy, $data['dairy']);
                 $this->CooperativeRegistrationDairy->save($dairy);     
             }

             //Cooperative Registrations Fishery data
             if($data['sector_of_operation'] == 10)
             {
                 $this->loadModel('CooperativeRegistrationFishery');
                 $data['fishery']['cooperative_registrations_id'] = $CooperativeRegistration->id;

                 $fishery = $this->CooperativeRegistrationFishery->newEntity();
                 
                 $fishery = $this->CooperativeRegistrationFishery->patchEntity($fishery, $data['fishery']);
                 $this->CooperativeRegistrationFishery->save($fishery);     
             }
                
             //save land
             if(isset($data['area']) && !empty($data['area']))
             {
                 $this->loadModel('CooperativeRegistrationsLands');
                 //$this->AreaOfOperationLevel->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                 
                 
                 $data['area']['cooperative_registration_id'] = $CooperativeRegistration->id;
                 $CooperativeRegistrationsLands = $this->CooperativeRegistrationsLands->newEntity();
                 
                 $CooperativeRegistrationsLands = $this->CooperativeRegistrationsLands->patchEntity($CooperativeRegistrationsLands, $data['area']);

                 $this->CooperativeRegistrationsLands->save($CooperativeRegistrationsLands);
             }

            //  if($data['sector_of_operation'] == 7)
            //  {
            //      $this->loadModel('SocietyImplementingSchemes');
            //     $data['society_implementing_schemes']['cooperative_registration_id'] = $CooperativeRegistration->id;
            //      $CooperativeRegistrationsSchemes = $this->SocietyImplementingSchemes->newEntity();
                 
            //      $CooperativeRegistrationsSchemes = $this->SocietyImplementingSchemes->patchEntity($CooperativeRegistrationsSchemes, $data['society_implementing_schemes']);

            //      $this->SocietyImplementingSchemes->save($CooperativeRegistrationsSchemes);
            //  }

             //Rural & both
             $arr_village_sector = [2,3];

             if(isset($data['sector']) && !empty($data['sector']) && in_array($data['operation_area_location'],$arr_village_sector))
             {
                 $this->loadModel('AreaOfOperationLevel');
                 $this->loadMOdel('DistrictsBlocksGpVillages');
                 //$this->AreaOfOperationLevel->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                 
                 foreach($data['sector'] as $s_key => $sector)
                 {
                     if($sector['panchayat_code'] == -1){

                         $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$sector['block_code']])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray(); 

                                 if(isset($gps) && !empty($gps))
                                 {
                                     foreach($gps as $gp_code => $gp_name)
                                     {
                                         $gp_villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$gp_code])->order(['village_name'=>'ASC'])->toArray();

                                         if(isset($gp_villages) && !empty($gp_villages))
                                         {
                                             foreach ($gp_villages as $gp_village_code => $gp_village_name) {

                                                 $gp_areaOfOperationLevel = $this->AreaOfOperationLevel->newEntity();
                                                 $bulk_gp_data = [
                                                     'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
                                                     'row_id'                        =>  $s_key,
                                                     'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
                                                     'state_code'                    =>  $data['state_code'],
                                                     'district_code'                 =>  $sector['district_code'],
                                                     'block_code'                    =>  $sector['block_code'],
                                                     'panchayat_code'                =>  $gp_code,
                                                     'village_code'                  =>  $gp_village_code,
                                                     'gp_village_all'                =>  1
                                                 ];

                                                 $gp_areaOfOperationLevel = $this->AreaOfOperationLevel->patchEntity($gp_areaOfOperationLevel, $bulk_gp_data);

                                                 $this->AreaOfOperationLevel->save($gp_areaOfOperationLevel);
                                             }
                                         }
                                     }
                                 }

                     } else if($sector['block_code'] == -1){
                        $block_areaOfOperationLevel = $this->AreaOfOperationLevel->newEntity();
                        $bulk_block_data = [
                            'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
                            'row_id'                        =>  $s_key,
                            'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
                            'state_code'                    =>  $data['state_code'],
                            'district_code'                 =>  $sector['district_code'],
                            'block_code'                    =>  '-1',
                            'panchayat_code'                =>  0,
                            'village_code'                  =>  0,
                            'gp_village_all'                =>  3
                        ];

                        $block_areaOfOperationLevel = $this->AreaOfOperationLevel->patchEntity($block_areaOfOperationLevel, $bulk_block_data);

                        $this->AreaOfOperationLevel->save($block_areaOfOperationLevel);

                     } else if($sector['village_code'][0] == -1) {

                         $all_villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$sector['panchayat_code']])->order(['village_name'=>'ASC'])->toArray();

                         if(isset($all_villages) && !empty($all_villages))
                         {
                             foreach ($all_villages as $all_village_code => $all_village_name) {

                                 $village_areaOfOperationLevel = $this->AreaOfOperationLevel->newEntity();
                                 $bulk_village_data = [
                                     'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
                                     'row_id'                        =>  $s_key,
                                     'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
                                     'state_code'                    =>  $data['state_code'],
                                     'district_code'                 =>  $sector['district_code'],
                                     'block_code'                    =>  $sector['block_code'],
                                     'panchayat_code'                =>  $sector['panchayat_code'],
                                     'village_code'                  =>  $all_village_code,
                                     'gp_village_all'                =>  2
                                 ];

                                 $village_areaOfOperationLevel = $this->AreaOfOperationLevel->patchEntity($village_areaOfOperationLevel, $bulk_village_data);

                                 $this->AreaOfOperationLevel->save($village_areaOfOperationLevel);
                             }
                         }
                         
                     } else {
                         $villages = $sector['village_code'];

                         foreach($villages as $v_key => $village_code)
                         {
                             if($village_code != '-1')
                             {
                                 $AreaOfOperationLevel = $this->AreaOfOperationLevel->newEntity();
                                 $area_data = [
                                     'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
                                     'row_id'                        =>  $s_key,
                                     'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
                                     'state_code'                    =>  $data['state_code'],
                                     'district_code'                 =>  $sector['district_code'],
                                     'block_code'                    =>  $sector['block_code'],
                                     'panchayat_code'                =>  $sector['panchayat_code'],
                                     'village_code'                  =>  $village_code,
                                     'gp_village_all'                =>  0
                                 ];
                                 $AreaOfOperationLevel = $this->AreaOfOperationLevel->patchEntity($AreaOfOperationLevel, $area_data);

                                 $this->AreaOfOperationLevel->save($AreaOfOperationLevel);
                             }
                             
                         }
                     }
                 }  

             }

                //Urban & both
                $arr_ward_sector = [1,3];

                if(isset($data['sector_urban']) && !empty($data['sector_urban'])   && in_array($data['operation_area_location'],$arr_ward_sector))
                {
                    $this->loadModel('AreaOfOperationLevelUrban');
                    //$this->AreaOfOperationLevel->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                    
                    foreach($data['sector_urban'] as $s_key => $sector)
                    {
                        $wards = $sector['locality_ward_code'];
                        foreach($wards as $v_key => $ward_code)
                        {
                           
                            $AreaOfOperationLevelUrban = $this->AreaOfOperationLevelUrban->newEntity();
                            $area_data = [
                                'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
                                'row_id'                        =>  $s_key,
                                'area_of_operation_id'          =>  $data['area_of_operation_id_urban'],
                                'state_code'                    =>  $data['state_code'],
                                'district_code'                 =>  $sector['district_code'],
                                'local_body_type_code'          =>  $sector['local_body_type_code'],
                                'local_body_code'               =>  $sector['local_body_code'],
                                'locality_ward_code'            =>  $ward_code
                            ];
                            $AreaOfOperationLevelUrban = $this->AreaOfOperationLevelUrban->patchEntity($AreaOfOperationLevelUrban, $area_data);

                            $this->AreaOfOperationLevelUrban->save($AreaOfOperationLevelUrban);
                        }
                    }  
                }
                

                if(!empty($multi_states)){
                    $this->loadModel('CooperativeRegistrationsMultiStates');
                    $this->CooperativeRegistrationsMultiStates->deleteAll(['cooperative_registration_id'=>$CooperativeRegistration->id]);
                    foreach($multi_states as $key=>$state_id){
                        $CRMS=$this->CooperativeRegistrationsMultiStates->newEntity();
                        $CRMS=$this->CooperativeRegistrationsMultiStates->patchEntity($CRMS,[]);
                        $CRMS->cooperative_registration_id=$CooperativeRegistration->id;
                        $CRMS->state_id=$state_id;
                        $this->CooperativeRegistrationsMultiStates->save($CRMS);
                    }
                }

               

                $this->Flash->success(__('The Cooperative data has been saved.'));

                if($this->request->session()->read('Auth.User.role_id') == 7)
                {
                    if($data['is_draft'] == 1)
                    {
                        return $this->redirect(['action' => 'draft']);    
                    } else {
                        return $this->redirect(['action' => 'dataEntryPending']);    
                    }
                    
                }
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
        }

        // Start States for dropdown
            $state_code= $this->request->session()->read('Auth.User.state_code');
            $this->loadModel('States');

            if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
            {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
            } else {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code]);
            }
            
        // End States for dropdown
        $this->set('states',$states);

        ####### degination dropdown#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        ###################end ############


        // Start States for dropdown
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        // End States for dropdown
        
        //sector district level
        // if($data['area_of_operation_id'] == 3)
        // {
        //     $sector_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$CooperativeRegistration->sector['state_code']])->order(['name'=>'ASC']);
        //     $this->set('sector_districts',$sector_districts);
        // }

        // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$CooperativeRegistration->district_code])->order(['name'=>'ASC']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown


        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$CooperativeRegistration->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown



        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$CooperativeRegistration->gram_panchayat_code])->order(['village_name'=>'ASC']); 
        $this->set('villages',$villages);  
        // end villages for dropdown
        



        // Start CooperativeSocietyTypes for dropdown
            $this->loadModel('CooperativeSocietyTypes');
            $CooperativeSocietyTypes=$this->CooperativeSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC']);
        // End CooperativeSocietyTypes for dropdown
    
        // Start areaOfOperations for dropdown
            $this->loadModel('AreaOfOperations');
            $areaOfOperationsUrban=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'urban_rural'=>1])->order(['orderseq'=>'ASC']);
            $areaOfOperationsRural=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'urban_rural'=>2])->order(['orderseq'=>'ASC']);
            //$areaOfOperations=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        // End areaOfOperations for dropdown
        

        // Start PrimaryActivities for dropdown
            $this->loadModel('PrimaryActivities');
            $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$CooperativeRegistration->sector_of_operation_type,'status'=>1])->order(['orderseq'=>'ASC']);

            
        // End PrimaryActivities for dropdown
            
        // Start SecondaryActivities for dropdown
            $this->loadModel('SecondaryActivities');
            $SecondaryActivities=$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'sector_of_operation'=>$CooperativeRegistration->sector_of_operation_type])->order(['orderseq'=>'ASC']);

        // End SecondaryActivities for dropdown
        
        // Start PresentFunctionalStatus for dropdown
            $this->loadModel('PresentFunctionalStatus');
            $PresentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        // End PresentFunctionalStatus for dropdown

        
        // Start urban_local_bodies type for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$CooperativeRegistration->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown


        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$CooperativeRegistration->urban_local_body_type_code,'state_code'=>$CooperativeRegistration->state_code])->order(['local_body_name'=>'ASC']);
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown


        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$CooperativeRegistration->urban_local_body_code])->order(['ward_name'=>'ASC']);

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown

        $this->loadModel('SectorOperations');

       $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sector_operations',$sector_operations); 

             //  Start water_body_type for dropdown
            $this->loadModel('WaterBodyTypes');
            $water_body_type=$this->WaterBodyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,])->order(['id'=>'ASC']);
            $this->set('water_body_type',$water_body_type);
        // End water_body_type for dropdown

        $this->loadModel('AgricultureSocietyTypes');   
        $agrisocietytypes =$this->AgricultureSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('agrisocietytypes',$agrisocietytypes);

        
        $this->loadModel('MiscellaneousCooperativeSocieties');   
        $miscellaneoustypes =$this->MiscellaneousCooperativeSocieties->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'type'=>2]);
        $this->set('miscellaneoustypes',$miscellaneoustypes);

        $cmiscellaneoustypes =$this->MiscellaneousCooperativeSocieties->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'type'=>1]);
        $this->set('cmiscellaneoustypes',$cmiscellaneoustypes);


        

              //  Start register_authorty dropdown registration_authorities
            $this->loadModel('RegistrationAuthorities');
            $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['orderseq'=>'ASC']);
            $this->set('register_authorities',$register_authorities);
        // End registration_authorities for dropdown

        $this->set(compact('CooperativeRegistration','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','years','state_code','services'));

    }

    /**
     * Edit method
     *
     * @param string|null $id State id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('Districts');
        $this->loadMOdel('Blocks');
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $this->loadMOdel('CooperativeRegistrationFishery');
        $this->loadModel('ServiceQuestions');
        $this->loadModel('CooperativeRegistrationsServices');
        
        $services = $this->ServiceQuestions->find('all')->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        
        // $CooperativeRegistration = $this->CooperativeRegistrations->get($id, [
        //     'contain' => ['CRMS','CooperativeRegistrationsContactNumbers','CooperativeRegistrationsEmails','CooperativeRegistrationsLands','CooperativeRegistrationPacs'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationDairy'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationFishery'=>['sort'=>['id'=>'desc']],'AreaOfOperationLevel','CooperativeRegistrationsHousing','CooperativeRegistrationsMarketing','CooperativeRegistrationsCreditThrift','CooperativeRegistrationsConsumer','CooperativeRegistrationsLabour','CooperativeRegistrationsTransport','CooperativeRegistrationsProcessing','CooperativeRegistrationsHandloom','CooperativeRegistrationsAgriculture','CooperativeRegistrationsTribal','CooperativeRegistrationsTourism','CooperativeRegistrationsHandicraft','CooperativeRegistrationsEducation','CooperativeRegistrationsJute','CooperativeRegistrationsLivestock','CooperativeRegistrationsSericulture','CooperativeRegistrationsWocoop','CooperativeRegistrationsBee','CooperativeRegistrationsMulti']
        // ]);
        $CooperativeRegistration = $this->CooperativeRegistrations->get($id, [
            'contain' => ['CRMS','CooperativeRegistrationsContactNumbers','CooperativeRegistrationsEmails','CooperativeRegistrationsLands','CooperativeRegistrationPacs'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationDairy'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationFishery'=>['sort'=>['id'=>'desc']],'AreaOfOperationLevel','CooperativeRegistrationsHousing','CooperativeRegistrationsMarketing','CooperativeRegistrationsCreditThrift','CooperativeRegistrationsConsumer','CooperativeRegistrationsLabour','CooperativeRegistrationsTransport','CooperativeRegistrationsProcessing','CooperativeRegistrationsHandloom','CooperativeRegistrationsAgriculture','CooperativeRegistrationsTribal','CooperativeRegistrationsTourism','CooperativeRegistrationsHandicraft','CooperativeRegistrationsEducation','CooperativeRegistrationsJute','CooperativeRegistrationsLivestock','CooperativeRegistrationsSericulture','CooperativeRegistrationsWocoop','CooperativeRegistrationsBee','CooperativeRegistrationsUcb','SocietyImplementingSchemes','CooperativeRegistrationsMulti','CooperativeRegistrationsSugar']
        ]);

        $cooperative_registrations_services = $this->CooperativeRegistrationsServices->find('list',['keyField'=>'service_question_id','valueField'=>'value'])->where(['cooperative_registration_id'=>$id,'value !='=>''])->toArray();

        $display_dep_questions = array_keys($cooperative_registrations_services);

        //echo $cooperative_registrations_services[18];die;

        //$CooperativeRegistration['cooperative_registrations_services'] = $service_data; 

        // $CooperativeRegistration['fishery'] = [];
        // $CooperativeRegistration['fishery'] = $CooperativeRegistration['cooperative_registration_fishery'][0];

        
        $this->loadModel('AreaOfOperationLevel');
        $this->loadModel('AreaOfOperationLevelUrban');

        $sectors = [];
        $sectorUrbans = [];
        $area_of_operation_id_urban = '';
        $area_of_operation_id_rural = '';

        if($CooperativeRegistration['operation_area_location'] == 1 || $CooperativeRegistration['operation_area_location'] == 3)
        {
            //sector urban
            
            $sectorurbans_data = $this->AreaOfOperationLevelUrban->find('all')->where(['cooperative_registrations_id'=>$id])->group(['row_id'])->order(['row_id'=>'ASC'])->toArray();

            foreach($sectorurbans_data as $key => $sector_data)
            {
                // echo '<pre>';
                // print_r($sector_data);die;
                $this->loadMOdel('UrbanLocalBodies');

                $localbody_types = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$sector_data['state_code']])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
                

                $this->loadMOdel('UrbanLocalBodiesWards');
                $sector_wards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$sector_data['local_body_code']])->order(['ward_name'=>'ASC'])->toArray();

                $sector_wards = array('-1'=>'Select All') + $sector_wards;

                $this->loadMOdel('UrbanLocalBodies');
                $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$sector_data['local_body_type_code'],'state_code'=>$sector_data['state_code']])->order(['local_body_name'=>'ASC'])->toArray();

                $wards_data = $this->AreaOfOperationLevelUrban->find()->where(['cooperative_registrations_id'=>$id,'row_id'=>$sector_data['row_id']])->extract('locality_ward_code')
                ->toArray();

                if(empty($wards_data))
                {
                    $wards_data = [
                        '0'=>'wards'
                    ];
                }

                

                $area_of_operation_id_urban = $sector_data['area_of_operation_id'];

                // if(empty($sector_panchayats))
                // {
                //     $sector_panchayats = [
                //         '0'=>'Gram Panchayat'
                //     ];
                // }

                $sectorUrbans[$key] = [
                    'districts'             =>  $sector_urban_districts,
                    'localbody_types'       =>  $localbody_types,
                    'localbodies'           =>  $localbodies,
                    'sector_wards'          =>  $sector_wards,
                    'district_code'         =>  $sector_data['district_code'],
                    'local_body_type_code'  =>  $sector_data['local_body_type_code'],
                    'local_body_code'       =>  $sector_data['local_body_code'],
                    'locality_ward_code'    =>  $wards_data,
                ];
            }
        }

        if($CooperativeRegistration['operation_area_location'] == 2 || $CooperativeRegistration['operation_area_location'] == 3)
        {
            //sector rural

            $sectors_data = $this->AreaOfOperationLevel->find('all')->where(['cooperative_registrations_id'=>$id])->group(['row_id'])->order(['row_id'=>'ASC'])->toArray();

            foreach($sectors_data as $key => $sector_data)
            {
                // echo '<pre>';
                // print_r($sector_data);die;
                $sector_blocks = $this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$sector_data['district_code']])->order(['name'=>'ASC'])->toArray();

                $sector_blocks = array('-1'=>'Select All') + $sector_blocks;

                $sector_panchayats = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$sector_data['block_code']])->order(['gram_panchayat_name'=>'ASC'])->toArray();  

                $sector_panchayats = array('-1'=>'Select All') + $sector_panchayats;

                $sector_villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$sector_data['panchayat_code']])->order(['village_name'=>'ASC'])->toArray(); 

                $sector_villages = array('-1'=>'Select All') + $sector_villages;

                $vilages_data = $this->AreaOfOperationLevel->find()->where(['cooperative_registrations_id'=>$id,'row_id'=>$sector_data['row_id']])->extract('village_code')
                ->toArray();


                if(empty($sector_villages))
                {
                    $sector_villages = [
                        '0'=>'villages'
                    ];
                }

                if(empty($sector_panchayats))
                {
                    $sector_panchayats = [
                        '0'=>'Gram Panchayat'
                    ];
                }


                if($sector_data['gp_village_all'] == 1)
                {
                    $sector_data['panchayat_code'] = -1;
                }

                if($sector_data['gp_village_all'] == 2)
                {
                    //$sector_data['panchayat_code'] = -1;
                    $vilages_data = [];
                    $vilages_data[] = -1;
                }

                $area_of_operation_id_rural = $sector_data['area_of_operation_id'];

                $sectors[$key] = [
                    'sector_blocks'     =>  $sector_blocks,
                    'sector_panchayats' =>  $sector_panchayats,
                    'sector_villages'   =>  $sector_villages,
                    'district_code'     =>  $sector_data['district_code'],
                    'block_code'        =>  $sector_data['block_code'],
                    'panchayat_code'    => $sector_data['panchayat_code'],
                    'village_code'      =>  $vilages_data,
                    'gp_village_all'    =>  $sector_data['gp_village_all']
                ];
            }
        }

        $years = [];
        $l_year = date('Y')-122;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
        }


        //for district nodal edit
        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            $this->request->session()->write('prev_url', $_SERVER['HTTP_REFERER']);

            //if edit by district nodal pending //or rejected
            if($CooperativeRegistration['is_approved'] == 0)// || $CooperativeRegistration['is_approved'] == 2
            {
                $this->Flash->error(__('You are not authorized to edit Pending'));// or Rejected
                //$this->redirect($this->referer());
                return $this->redirect(['action' => 'pending']);    
            }

            // //if user not created by him for CS
            // if($CooperativeRegistration['is_approved'] == 1)
            // {
                //$nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'created_by'=>$this->request->session()->read('Auth.User.id'),'state_code'=>$this->request->session()->read('Auth.User.state_code'),'district_code'=>$this->request->session()->read('Auth.User.district_code')])->toArray();

                $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();

                $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);

                if(empty($nodal_data_entry_ids) || !in_array($CooperativeRegistration['created_by'],$nodal_data_entry_ids)){

                    $this->Flash->error(__('You are not authorized to edit.'));
                    return $this->redirect($this->referer());
                    //return $this->redirect(['action' => 'pending']);   
                }
                
            //}

            
        }

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
        
        if ($this->request->is(['patch', 'post', 'put'])){
            $data=$this->request->getData();
            
            $valid_user_role = [2,7,8];
            if(!in_array($this->request->session()->read('Auth.User.role_id'),$valid_user_role))
            {
                $this->redirect($this->referer());
            }
            //create log for pacs, dairy, fishery
            $arr_valid_log = [1,20,22,9,10];
            if(in_array($CooperativeRegistration->sector_of_operation,$arr_valid_log))
            {
                $this->editLog($CooperativeRegistration);
            }

            if($this->request->session()->read('Auth.User.role_id') != 8)
            {
                $data['is_approved'] = 0;
                $data['remark'] = '';
                $data['approved_by'] = '';
            }
            $data['updated_by'] = $this->request->session()->read('Auth.User.id');
            $data['updated'] = date('Y-m-d H:i:s');
            $data['operational_district_code'] = $data['district_code'];//$this->request->session()->read('Auth.User.district_code');
            $data['cooperative_society_name'] = trim($data['cooperative_society_name']);

            if(!empty($data['affiliated_union_federation_name_for_other']) && $data['affiliated_union_federation_name'] == '-1')
            {
                $this->loadModel('DistrictCentralCooperativeBank');
                $DistrictCentralCooperativeBankData = $this->DistrictCentralCooperativeBank->newEntity();

                $arr_dccb_data = [
                    'name' => $data['affiliated_union_federation_name_for_other'],
                    'status' => '1',
                    'level_of_affiliated_union_id' => $data['affiliated_union_federation_level'],
                    'dccb_flag' => '0',
                    'primary_activity_flag' => $data['sector_of_operation']
                ];

                // echo '<pre>';
                // print_r($arr_dccb_data);die;
                $DistrictCentralCooperativeBankData = $this->DistrictCentralCooperativeBank->patchEntity($DistrictCentralCooperativeBankData, $arr_dccb_data);
            
                if($this->DistrictCentralCooperativeBank->save($DistrictCentralCooperativeBankData)) {
                    $data['affiliated_union_federation_name'] = $DistrictCentralCooperativeBankData->id;
                }

            }
			
            if(!empty($data['landline']))
            {
                $std = $data['std'];
                $landline = $data['landline'];
                $data['landline'] = $std.'-'.$landline;
            } else {
                unset($data['std']);
            }

            $data['bank_type'] = implode(',',$data['bank_type']);
            $data['cooperative_society_bank_id'] = implode(',',$data['cooperative_society_bank_id']);
			
            /*
			//add his district based on user if urban
			if(($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8) && $data['location_of_head_quarter'] == 1)
            {
                $data['district_code'] = $this->request->session()->read('Auth.User.district_code');
            }*/
			
            if($data['location_of_head_quarter']==2 && !empty($data['state_code']) && !empty($data['district_code']) && !empty($data['block_code']) && !empty($data['gram_panchayat_code']) && !empty($data['village_code'])){

                $cooperative_id=(int)$data['state_code']+(int)$data['district_code']+(int)$data['block_code']+(int)$data['gram_panchayat_code']+(int)$data['village_code'];
                $MaxCooperativeId=$this->CooperativeRegistrations->find('all')->select(['id'=>'MAX(id)'])->first();
                $cooperative_id=$cooperative_id.$id;
                $data['cooperative_id']=$cooperative_id;
            }elseif($data['location_of_head_quarter']==1 && !empty($data['state_code']) && !empty($data['urban_local_body_type_code']) && !empty($data['urban_local_body_code'])){

                $cooperative_id=(int)$data['state_code']+(int)$data['urban_local_body_type_code']+(int)$data['urban_local_body_code']+(int)$data['locality_ward_code'];
                
                $MaxCooperativeId=$this->CooperativeRegistrations->find('all')->select(['id'=>'MAX(id)'])->first();
                $cooperative_id=$cooperative_id.$id;
                $data['cooperative_id']=$cooperative_id;

            }

                if($data['is_draft'] == 0 && empty($CooperativeRegistration['cooperative_id_num']))
                {
                    $cooperative_id_num= $this->generateUniqueNumber($id); 
                    $data['cooperative_id_num'] = $cooperative_id_num;
                }

			
            $multi_states=$data['multi_states'];
            if(empty($data['multi_states']))
			{
				$multi_states = [];
			}
            $crcns=$data['cooperative_registrations_contact_numbers'];
            //unset($data['cooperative_registrations_contact_numbers']);

            
            $cres=$data['cooperative_registrations_emails'];
            //unset($data['cooperative_registrations_emails']);

            if(count($multi_states)>1){
               $data['is_multi_state']=1; 
            }
            

            $data['date_registration']= date("Y-m-d",strtotime(str_replace("/","-",$data['date_registration'])));

            if($data['sector_of_operation'] == '47')
            {
                $data['cooperative_registrations_housing']['facilities'] = implode(',',$data['cooperative_registrations_housing']['facilities']);
            }
            if($data['sector_of_operation'] == '51')
            {
                $data['cooperative_registrations_labour']['facilities'] = implode(',',$data['cooperative_registrations_labour']['facilities']);
            }
            if($data['sector_of_operation'] == '96')
            {
                $data['cooperative_registrations_sericulture']['facilities'] = implode(',',$data['cooperative_registrations_sericulture']['facilities']);
                $data['cooperative_registrations_sericulture']['type_society'] = implode(',',$data['cooperative_registrations_sericulture']['type_society']);

            }
           
            if($data['sector_of_operation'] == '80')
            {
                $data['cooperative_registrations_consumer']['facilities'] = implode(',',$data['cooperative_registrations_consumer']['facilities']);
            }
            if($data['sector_of_operation'] == '35')
            {
                $data['cooperative_registrations_cmiscellaneous']['facilities'] = implode(',',$data['cooperative_registrations_cmiscellaneous']['facilities']);
            }

            if($data['sector_of_operation'] == '77')
            {
                $data['cooperative_registrations_agriculture']['farming_mech'] = implode(',',$data['cooperative_registrations_agriculture']['farming_mech']);
                $data['cooperative_registrations_agriculture']['irrigation_means'] = implode(',',$data['cooperative_registrations_agriculture']['irrigation_means']);

            }
            if($data['sector_of_operation'] == '79') 
            {
                $data['cooperative_registrations_bee']['type_bee'] = implode(',',$data['cooperative_registrations_bee']['type_bee']);
                $data['cooperative_registrations_bee']['type_product'] = implode(',',$data['cooperative_registrations_bee']['type_product']);
                $data['cooperative_registrations_bee']['facilities'] = implode(',',$data['cooperative_registrations_bee']['facilities']);


            }
            if($data['sector_of_operation'] == '16') 
            {
                $data['cooperative_registrations_multi']['sec_activity'] = implode(',',$data['cooperative_registrations_multi']['sec_activity']);
                $data['cooperative_registrations_multi']['facilities'] = implode(',',$data['cooperative_registrations_multi']['facilities']);

            }
            
            if($data['sector_of_operation'] == '82')
            {
                //marketing

                if(!empty($data['cooperative_registrations_marketing']['liecense_to_sell']))
                {
                    $data['cooperative_registrations_marketing']['liecense_to_sell'] = implode(',',$data['cooperative_registrations_marketing']['liecense_to_sell']);
                } else {
                    $data['cooperative_registrations_marketing']['liecense_to_sell'] = '';
                }

                if(!empty($data['cooperative_registrations_marketing']['sell_the_item']))
                {
                    $data['cooperative_registrations_marketing']['sell_the_item'] = implode(',',$data['cooperative_registrations_marketing']['sell_the_item']);
                } else {
                    $data['cooperative_registrations_marketing']['sell_the_item'] = '';
                }
                
            }

            if($data['sector_of_operation'] == '18')
            {
                $data['cooperative_registrations_credit_thrift']['facilities'] = implode(',',$data['cooperative_registrations_credit_thrift']['facilities']);
            }

            // if($data['sector_of_operation'] == '15')
            // {
            //     $data['cooperative_registrations_women']['facilities'] = implode(',',$data['cooperative_registrations_women']['facilities']);
            //     $data['cooperative_registrations_women']['type_society'] = implode(',',$data['cooperative_registrations_women']['type_society']);

            // }
            if($data['sector_of_operation'] == '15') 
            {
              
                $data['cooperative_registrations_wocoop']['facilities'] = implode(',',$data['cooperative_registrations_wocoop']['facilities']);
                
            }


            if($data['sector_of_operation'] == '102')
            {
                $data['cooperative_registrations_tribal']['facilities'] = implode(',',$data['cooperative_registrations_tribal']['facilities']);
            }
            if($data['sector_of_operation'] == '99')
            {
                $data['cooperative_registrations_tourism']['facilities'] = implode(',',$data['cooperative_registrations_tourism']['facilities']);
            }
            if($data['sector_of_operation'] == '14')
            {
            $data['cooperative_registrations_handicraft']['type_raw'] = implode(',',$data['cooperative_registrations_handicraft']['type_raw']);
            $data['cooperative_registrations_handicraft']['type_produce'] = implode(',',$data['cooperative_registrations_handicraft']['type_produce']);
            $data['cooperative_registrations_handicraft']['facilities'] = implode(',',$data['cooperative_registrations_handicraft']['facilities']);
            }
            if($data['sector_of_operation'] == '90')
            {
            $data['cooperative_registrations_jute']['type_raw'] = implode(',',$data['cooperative_registrations_jute']['type_raw']);
            $data['cooperative_registrations_jute']['type_produce'] = implode(',',$data['cooperative_registrations_jute']['type_produce']);
            $data['cooperative_registrations_jute']['facilities'] = implode(',',$data['cooperative_registrations_jute']['facilities']);
            }
            if($data['sector_of_operation'] == '84') 
            {
                $data['cooperative_registrations_education']['level_and_duration_of_course'] = implode(',',$data['cooperative_registrations_education']['level_and_duration_of_course']);
                $data['cooperative_registrations_education']['facilities'] = implode(',',$data['cooperative_registrations_education']['facilities']);

            }
            if($data['sector_of_operation'] == '54') 
            {
                $data['cooperative_registrations_livestock']['type_society'] = implode(',',$data['cooperative_registrations_livestock']['type_society']);
                $data['cooperative_registrations_livestock']['type_pa'] = implode(',',$data['cooperative_registrations_livestock']['type_pa']);
                $data['cooperative_registrations_livestock']['type_produce'] = implode(',',$data['cooperative_registrations_livestock']['type_produce']);
                $data['cooperative_registrations_livestock']['facilities'] = implode(',',$data['cooperative_registrations_livestock']['facilities']);



            }   
            if($data['sector_of_operation'] == '98')
            {
                $data['cooperative_registrations_social']['facilities'] = implode(',',$data['cooperative_registrations_social']['facilities']);
                $data['cooperative_registrations_social']['type_social_culture_activity'] = implode(',',$data['cooperative_registrations_social']['type_social_culture_activity']);

            }

            if($data['sector_of_operation'] == '11') 
            { 
                $data['cooperative_registrations_sugar']['product_produced'] = implode(',',$data['cooperative_registrations_sugar']['product_produced']);

                if(!empty($data['cooperative_registrations_sugar']['crushing_period_start']))
                {
                    $data['cooperative_registrations_sugar']['crushing_period_start']= date("Y-m-d",strtotime(str_replace("/","-",$data['cooperative_registrations_sugar']['crushing_period_start'])));
                }

                if(!empty($data['cooperative_registrations_sugar']['crushing_period_end']))
                {
                    $data['cooperative_registrations_sugar']['crushing_period_end']= date("Y-m-d",strtotime(str_replace("/","-",$data['cooperative_registrations_sugar']['crushing_period_end'])));    
                }

            }

            if($data['sector_of_operation'] == '7') 
            {
                
                $this->loadModel('SocietyImplementingSchemes');
                $this->SocietyImplementingSchemes->deleteAll([       
                    'SocietyImplementingSchemes.cooperative_registration_id'=>$id,
                  ]);
                  
                  if($data['cooperative_registrations_ucb']['is_gov_scheme_implemented']==0){
                    unset($data['society_implementing_schemes']);
                  }

                //   echo "<pre>";
                //   print_r($data);die;

            }  
            // echo "<pre>";
            // print_r($data);die;


           // $CooperativeRegistration = $this->CooperativeRegistrations->patchEntity($CooperativeRegistration, $data,['associated' => ['CooperativeRegistrationsHousing','CooperativeRegistrationsLabour','CooperativeRegistrationsConsumer','CooperativeRegistrationsLands','CooperativeRegistrationsMarketing','CooperativeRegistrationsCreditThrift','CooperativeRegistrationsTransport','CooperativeRegistrationsProcessing','CooperativeRegistrationsHandloom','CooperativeRegistrationsAgriculture','CooperativeRegistrationsTribal','CooperativeRegistrationsTourism','CooperativeRegistrationsHandicraft','CooperativeRegistrationsEducation','CooperativeRegistrationsJute','CooperativeRegistrationsLivestock','CooperativeRegistrationsSericulture','CooperativeRegistrationsWocoop','CooperativeRegistrationsBee','CooperativeRegistrationsMulti']]);
           $CooperativeRegistration = $this->CooperativeRegistrations->patchEntity($CooperativeRegistration, $data,['associated' => ['CooperativeRegistrationsHousing','CooperativeRegistrationsLabour','CooperativeRegistrationsConsumer','CooperativeRegistrationsLands','CooperativeRegistrationsMarketing','CooperativeRegistrationsCreditThrift','CooperativeRegistrationsTransport','CooperativeRegistrationsProcessing','CooperativeRegistrationsHandloom','CooperativeRegistrationsAgriculture','CooperativeRegistrationsTribal','CooperativeRegistrationsTourism','CooperativeRegistrationsHandicraft','CooperativeRegistrationsEducation','CooperativeRegistrationsJute','CooperativeRegistrationsLivestock','CooperativeRegistrationsSericulture','CooperativeRegistrationsWocoop','CooperativeRegistrationsBee','CooperativeRegistrationsUcb','SocietyImplementingSchemes','CooperativeRegistrationsMulti','CooperativeRegistrationsSugar']]);
            
            if($this->CooperativeRegistrations->save($CooperativeRegistration)) {

                

                  //Cooperative Registrations pacs data
             if($data['sector_of_operation'] == 1 || $data['sector_of_operation'] == 20 || $data['sector_of_operation'] == 22)
             {
                //save dynamic questions
                if(isset($data['cooperative_registrations_services']) && !empty($data['cooperative_registrations_services']))
                {
                    $ser_ans = [];
                    foreach ($data['cooperative_registrations_services'] as $q_id => $ans) {
                        $ser_ans[] = [
                            'cooperative_registration_id' => $CooperativeRegistration->id,
                            'service_question_id' => $q_id,
                            'value' => $ans,
                            'created' => date('Y-m-d H:i:s')
                        ];
                    }

                    $this->loadModel('CooperativeRegistrationsServices');
                    $this->CooperativeRegistrationsServices->deleteAll(['cooperative_registration_id'=>$CooperativeRegistration->id]);

                    $c_r_s  = $this->CooperativeRegistrationsServices->newEntity();
                    $c_r_s  = $this->CooperativeRegistrationsServices->patchEntities($c_r_s, $ser_ans);
                    $c_r_s =  $this->CooperativeRegistrationsServices->saveMany($c_r_s);  

                }


                 $this->loadModel('CooperativeRegistrationPacs');
                 $data['pacs']['cooperative_registrations_id'] = $CooperativeRegistration->id;

                 $this->CooperativeRegistrationPacs->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                //  echo '<pre>';
                //  print_r($data['pacs']);die;
                 $CooperativeRegistrationPac = $this->CooperativeRegistrationPacs->newEntity();
                 
                 $CooperativeRegistrationPac = $this->CooperativeRegistrationPacs->patchEntity($CooperativeRegistrationPac, $data['pacs']);
                 $this->CooperativeRegistrationPacs->save($CooperativeRegistrationPac);     
             }

             //Cooperative Registrations Dairy data
             if($data['sector_of_operation'] == 9)
             {
                 $this->loadModel('CooperativeRegistrationDairy');
                 $data['dairy']['cooperative_registrations_id'] = $CooperativeRegistration->id;

                //  echo '<pre>';
                //  print_r($data['pacs']);die;
				$this->CooperativeRegistrationDairy->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                 $dairy = $this->CooperativeRegistrationDairy->newEntity();
                 
                 $dairy = $this->CooperativeRegistrationDairy->patchEntity($dairy, $data['dairy']);
                 $this->CooperativeRegistrationDairy->save($dairy);     
             }

             //Cooperative Registrations Fishery data
             if($data['sector_of_operation'] == 10)
             {
                 $this->loadModel('CooperativeRegistrationFishery');
                 $data['fishery']['cooperative_registrations_id'] = $CooperativeRegistration->id;

                 $this->CooperativeRegistrationFishery->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                 $fishery = $this->CooperativeRegistrationFishery->newEntity();
                 
                 $fishery = $this->CooperativeRegistrationFishery->patchEntity($fishery, $data['fishery']);
                 $this->CooperativeRegistrationFishery->save($fishery);     
             }
                
            $this->loadModel('CooperativeRegistrationsLands');

            if($CooperativeRegistration->sector_of_operation == 1)
            {
                $this->CooperativeRegistrationsLands->deleteAll(['cooperative_registration_id'=>$CooperativeRegistration->id]);
            }
             
             //save land
             if(isset($data['area']) && !empty($data['area']))
             {
                $data['area']['cooperative_registration_id'] = $CooperativeRegistration->id;
                
                 $CooperativeRegistrationsLands = $this->CooperativeRegistrationsLands->newEntity();                 
                 $CooperativeRegistrationsLands = $this->CooperativeRegistrationsLands->patchEntity($CooperativeRegistrationsLands, $data['area']);

                 $this->CooperativeRegistrationsLands->save($CooperativeRegistrationsLands);
             }

             $this->loadModel('AreaOfOperationLevelUrban');
             $this->AreaOfOperationLevelUrban->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);

              //Urban & both
            $arr_ward_sector = [1,3];
             
             if(isset($data['sector_urban']) && !empty($data['sector_urban']) && in_array($data['operation_area_location'],$arr_ward_sector))
                {
                    
                    //$this->AreaOfOperationLevel->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                    
                    foreach($data['sector_urban'] as $s_key => $sector)
                    {
                        $wards = $sector['locality_ward_code'];
                        foreach($wards as $v_key => $ward_code)
                        {
                           
                            $AreaOfOperationLevelUrban = $this->AreaOfOperationLevelUrban->newEntity();
                            $area_data = [
                                'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
                                'row_id'                        =>  $s_key,
                                'area_of_operation_id'          =>  $data['area_of_operation_id_urban'],
                                'state_code'                    =>  $data['state_code'],
                                'district_code'                 =>  $sector['district_code'],
                                'local_body_type_code'          =>  $sector['local_body_type_code'],
                                'local_body_code'               =>  $sector['local_body_code'],
                                'locality_ward_code'            =>  $ward_code
                            ];
                            $AreaOfOperationLevelUrban = $this->AreaOfOperationLevelUrban->patchEntity($AreaOfOperationLevelUrban, $area_data);

                            $this->AreaOfOperationLevelUrban->save($AreaOfOperationLevelUrban);
                        }
                    }  
                }

                $this->loadModel('AreaOfOperationLevel');
                $this->AreaOfOperationLevel->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);

                 //Rural & both
                 $arr_village_sector = [2,3];


                 if(isset($data['sector']) && !empty($data['sector']) && in_array($data['operation_area_location'],$arr_village_sector))
                 {
                     $this->loadModel('AreaOfOperationLevel');
                     $this->loadMOdel('DistrictsBlocksGpVillages');
                     //$this->AreaOfOperationLevel->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                     
                     foreach($data['sector'] as $s_key => $sector)
                     {
                         if($sector['panchayat_code'] == -1){
 
                             $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$sector['block_code']])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray(); 
 
                                     if(isset($gps) && !empty($gps))
                                     {
                                         foreach($gps as $gp_code => $gp_name)
                                         {
                                             $gp_villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$gp_code])->order(['village_name'=>'ASC'])->toArray();
 
                                             if(isset($gp_villages) && !empty($gp_villages))
                                             {
                                                 foreach ($gp_villages as $gp_village_code => $gp_village_name) {
 
                                                     $gp_areaOfOperationLevel = $this->AreaOfOperationLevel->newEntity();
                                                     $bulk_gp_data = [
                                                         'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
                                                         'row_id'                        =>  $s_key,
                                                         'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
                                                         'state_code'                    =>  $data['state_code'],
                                                         'district_code'                 =>  $sector['district_code'],
                                                         'block_code'                    =>  $sector['block_code'],
                                                         'panchayat_code'                =>  $gp_code,
                                                         'village_code'                  =>  $gp_village_code,
                                                         'gp_village_all'                =>  1
                                                     ];
 
                                                     $gp_areaOfOperationLevel = $this->AreaOfOperationLevel->patchEntity($gp_areaOfOperationLevel, $bulk_gp_data);
 
                                                     $this->AreaOfOperationLevel->save($gp_areaOfOperationLevel);
                                                 }
                                             }
                                         }
                                     }
 
                         } else if($sector['block_code'] == -1){

                            $block_areaOfOperationLevel = $this->AreaOfOperationLevel->newEntity();
                            $bulk_block_data = [
                                'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
                                'row_id'                        =>  $s_key,
                                'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
                                'state_code'                    =>  $data['state_code'],
                                'district_code'                 =>  $sector['district_code'],
                                'block_code'                    =>  '-1',
                                'panchayat_code'                =>  0,
                                'village_code'                  =>  0,
                                'gp_village_all'                =>  3
                            ];
    
                            $block_areaOfOperationLevel = $this->AreaOfOperationLevel->patchEntity($block_areaOfOperationLevel, $bulk_block_data);
    
                            $this->AreaOfOperationLevel->save($block_areaOfOperationLevel);

                         } else if($sector['village_code'][0] == -1) {
 
                             $all_villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$sector['panchayat_code']])->order(['village_name'=>'ASC'])->toArray();
 
                             if(isset($all_villages) && !empty($all_villages))
                             {
                                 foreach ($all_villages as $all_village_code => $all_village_name) {
 
                                     $village_areaOfOperationLevel = $this->AreaOfOperationLevel->newEntity();
                                     $bulk_village_data = [
                                         'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
                                         'row_id'                        =>  $s_key,
                                         'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
                                         'state_code'                    =>  $data['state_code'],
                                         'district_code'                 =>  $sector['district_code'],
                                         'block_code'                    =>  $sector['block_code'],
                                         'panchayat_code'                =>  $sector['panchayat_code'],
                                         'village_code'                  =>  $all_village_code,
                                         'gp_village_all'                =>  2
                                     ];
 
                                     $village_areaOfOperationLevel = $this->AreaOfOperationLevel->patchEntity($village_areaOfOperationLevel, $bulk_village_data);
 
                                     $this->AreaOfOperationLevel->save($village_areaOfOperationLevel);
                                 }
                             }
                             
                         } else {
                             $villages = $sector['village_code'];
 
                             foreach($villages as $v_key => $village_code)
                             {
                                 if($village_code != '-1')
                                 {
                                     $AreaOfOperationLevel = $this->AreaOfOperationLevel->newEntity();
                                     $area_data = [
                                         'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
                                         'row_id'                        =>  $s_key,
                                         'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
                                         'state_code'                    =>  $data['state_code'],
                                         'district_code'                 =>  $sector['district_code'],
                                         'block_code'                    =>  $sector['block_code'],
                                         'panchayat_code'                =>  $sector['panchayat_code'],
                                         'village_code'                  =>  $village_code,
                                         'gp_village_all'                =>  0
                                     ];
                                     $AreaOfOperationLevel = $this->AreaOfOperationLevel->patchEntity($AreaOfOperationLevel, $area_data);
 
                                     $this->AreaOfOperationLevel->save($AreaOfOperationLevel);
                                 }
                                 
                             }
                         }
                     }  
 
                 }


                $this->loadModel('CooperativeRegistrationsMultiStates');
                    
                $this->CooperativeRegistrationsMultiStates->deleteAll(['cooperative_registration_id'=>$id]);
                if(!empty($multi_states)){
                    
                    foreach($multi_states as $key=>$state_id){
                        $CRMS=$this->CooperativeRegistrationsMultiStates->newEntity();
                        $CRMS=$this->CooperativeRegistrationsMultiStates->patchEntity($CRMS,[]);
                        $CRMS->cooperative_registration_id=$id;
                        $CRMS->state_id=$state_id;
                        $this->CooperativeRegistrationsMultiStates->save($CRMS);
                    }
                }
                /*if(!empty($crcns)){
                $this->loadModel('CooperativeRegistrationsContactNumbers');
                $this->CooperativeRegistrationsContactNumbers->deleteAll(['cooperative_registration_id'=>$CooperativeRegistration->id]);
                foreach($crcns as $crcnkey=>$crcnData){
                    if(!empty($crcnData['contact_number'])){
                        $crcn=$this->CooperativeRegistrationsContactNumbers->newEntity();
                        $crcn=$this->CooperativeRegistrationsContactNumbers->patchEntity($crcn,[]);
                        $crcn->cooperative_registration_id=$id;
                        $crcn->contact_number=$crcnData['contact_number'];
                        $this->CooperativeRegistrationsContactNumbers->save($crcn); 
                    }
                }
             }
            
            if(!empty($cres)){
                $this->loadModel('CooperativeRegistrationsEmails');
                $this->CooperativeRegistrationsEmails->deleteAll(['cooperative_registration_id'=>$CooperativeRegistration->id]);
                foreach($cres as $crekey=>$creData){
                    if(!empty($creData['email'])){
                        $cre=$this->CooperativeRegistrationsEmails->newEntity();
                        $cre=$this->CooperativeRegistrationsEmails->patchEntity($cre,[]);
                        $cre->cooperative_registration_id=$id;
                        $cre->email=$creData['email'];
                        $this->CooperativeRegistrationsEmails->save($cre);  
                    }
                }
             }*/

                $this->Flash->success(__('The Cooperative data has been saved.'));

                if($this->request->session()->read('Auth.User.role_id') == 7)
                {
                    if($data['is_draft'] == 1)
                    {
                        return $this->redirect(['action' => 'draft']);    
                    } else {
                        return $this->redirect(['action' => 'dataEntryPending']);    
                    }
                    
                }

                if($this->request->session()->read('Auth.User.role_id') == 8)
                {
                    if(!empty($this->request->session()->read('prev_corr_url')))
                    {
                        return $this->redirect($this->request->session()->read('prev_corr_url')); 
                    }

                    if($CooperativeRegistration['is_approved'] == 2)
                    {
                        return $this->redirect(['action' => 'rejected']); 
                    }

                    if($CooperativeRegistration['is_approved'] == 1)
                    {
                        return $this->redirect(['action' => 'accepted']); 
                    }
                    
                    
                    //$this->redirect($this->referer());
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Cooperative data could not be saved. Please, try again.'));
        }
        // Start States for dropdown
          //  $this->loadModel('States');
             $state_code= $this->request->session()->read('Auth.User.state_code');
            $this->loadModel('States');
            if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
            {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
            } else {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code]);
            }
            $this->set('states',$states);
        // End States for dropdown


        // Start States for dropdown
            $this->loadModel('Districts');
            if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
            {
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$CooperativeRegistration->state_code])->order(['name'=>'ASC']);
            } else {
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']); 
            }
               
            //$districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$CooperativeRegistration->state_code])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        // End States for dropdown
        

        // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            //$blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$CooperativeRegistration->district_code])->order(['name'=>'ASC']);
			$blocks=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$CooperativeRegistration->district_code])->group(['block_code']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown


        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$CooperativeRegistration->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown


        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$CooperativeRegistration->gram_panchayat_code])->order(['village_name'=>'ASC']); 
        $this->set('villages',$villages);  
        // end villages for dropdown
        
        $this->loadModel('MiscellaneousCooperativeSocieties');   
        $miscellaneoustypes =$this->MiscellaneousCooperativeSocieties->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'type'=>2]);
        $this->set('miscellaneoustypes',$miscellaneoustypes);

        $cmiscellaneoustypes =$this->MiscellaneousCooperativeSocieties->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'type'=>1]);
        $this->set('cmiscellaneoustypes',$cmiscellaneoustypes);



        // Start CooperativeSocietyTypes for dropdown
            $this->loadModel('CooperativeSocietyTypes');
            $CooperativeSocietyTypes=$this->CooperativeSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC']);
        // End CooperativeSocietyTypes for dropdown
    
        // Start areaOfOperations for dropdown
            $this->loadModel('AreaOfOperations');
            $o_a_location = 2;
            if(!empty($CooperativeRegistration['operation_area_location']))
            {
                $o_a_location = $CooperativeRegistration['operation_area_location'];
            }
            $areaOfOperationsUrban=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'urban_rural'=>1])->order(['name'=>'ASC']);
            $areaOfOperationsRural=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'urban_rural'=>2])->order(['name'=>'ASC']);
        // End areaOfOperations for dropdown
        

        // Start PrimaryActivities for dropdown
            $this->loadModel('PrimaryActivities');

            $pActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$CooperativeRegistration->sector_of_operation_type,'status'=>1])->order(['orderseq'=>'ASC']);
            $this->set('pActivities',$pActivities); 
            
            // $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$CooperativeRegistration->sector_of_operation_type,'status'=>1])->order(['orderseq'=>'ASC']);

            $pactivities_data = $this->PrimaryActivities->find('all')->where(['sector_of_operation'=>$CooperativeRegistration->sector_of_operation_type,'status'=>1])->order(['orderseq'=>'ASC'])->toArray();
            
            $PrimaryActivities = [];
           
                                // $PrimaryActivities = array(
                                //     1 => 'One', 
                                //     2 => array('text' => 'Two', 'value' => 5,  'f_name' => 'extra'), 
                                           //     3 => 'Three');

                if(count($pactivities_data)>0){
                    
                    foreach($pactivities_data as $key=>$value){

                    
                        $PrimaryActivities[] = array('text'=>$value['name'],'value'=>$value['id'],'function'=>$value['function_name']); 
                    }
                }

        // End PrimaryActivities for dropdown
            
        // Start SecondaryActivities for dropdown
            $this->loadModel('SecondaryActivities');
            $SecondaryActivities=$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'sector_of_operation'=>$CooperativeRegistration->sector_of_operation_type])->order(['orderseq'=>'ASC']);

        // End SecondaryActivities for dropdown
        
        // Start PresentFunctionalStatus for dropdown
            $this->loadModel('PresentFunctionalStatus');
            $PresentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        // End PresentFunctionalStatus for dropdown

        
        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$CooperativeRegistration->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies for dropdown
    

        // Start urban_local_bodies for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$CooperativeRegistration->urban_local_body_type_code,'state_code'=>$CooperativeRegistration->state_code])->order(['local_body_name'=>'ASC']);
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown

        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$CooperativeRegistration->urban_local_body_code])->order(['ward_name'=>'ASC']);

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown

        $this->loadModel('SectorOperations');

       $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sector_operations',$sector_operations); 

       $this->loadModel('RegistrationAuthorities');

       $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['orderseq'=>'ASC']);

       $this->loadModel('Designations');
       $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();

        // Start water_body_types for dropdown
        $this->loadModel('WaterBodyTypes');            
           $water_body_type = $this->WaterBodyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();

       $this->loadMOdel('LevelOfAffiliatedUnion'); 


              // $federationlevel = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1,'primary_activity_id'=>$CooperativeRegistration->sector_of_operation])->order(['id'=>'ASC'])->toArray();

                $primary_id=$CooperativeRegistration->sector_of_operation;
                    if($CooperativeRegistration->sector_of_operation==22 || $CooperativeRegistration->sector_of_operation==20)
                    {
                          $primary_id=1;

                    }

               $federationlevel = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1,'primary_activity_id'=>$primary_id])->order(['orderseq'=>'ASC'])->toArray();


           


             $this->loadModel('DistrictCentralCooperativeBank');            
            $dist_central_bannk = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();

            // if($CooperativeRegistration->affiliated_union_federation_level == 3)
            // {

            // }else
            // {
            // if($CooperativeRegistration->affiliated_union_federation_level == '1')
            // {
            //     $dccb = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','state_code'=>$CooperativeRegistration->state_code,'dccb_flag'=>$CooperativeRegistration->affiliated_union_federation_level, 'primary_activity_flag'=>$CooperativeRegistration->sector_of_operation])->order(['name'=>'ASC'])->toArray();
            // }else if($CooperativeRegistration->affiliated_union_federation_level=='2')
            // {
            //     $dccb = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','state_code'=>$CooperativeRegistration->state_code, 'dccb_flag'=>$CooperativeRegistration->affiliated_union_federation_level, 'primary_activity_flag'=>$CooperativeRegistration->sector_of_operation])->order(['name'=>'ASC'])->toArray();
            // }
            // else if($CooperativeRegistration->affiliated_union_federation_level !='2' || $CooperativeRegistration->affiliated_union_federation_level !='1' || $CooperativeRegistration->affiliated_union_federation_level !='3')
            // {

            //      $federationlevel_enty = $this->LevelOfAffiliatedUnion->find('all')->where(['status'=>1,'id'=>$CooperativeRegistration->affiliated_union_federation_level])->order(['id'=>'ASC'])->first();

            //     $dccb = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','state_code'=>$CooperativeRegistration->state_code,'primary_activity_flag'=>$CooperativeRegistration->sector_of_operation,'entity_type'=>$federationlevel_enty['name_of_affiliated_union']])->order(['name'=>'ASC'])->toArray();
            // }

            if($a_union_level == 1)
                {
                    //national level
                    $dccb = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','dccb_flag'=>3])->order(['name'=>'ASC'])->toArray();
                } else {
                    $dccb = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','primary_activity_flag'=>$CooperativeRegistration->sector_of_operation,'level_of_affiliated_union_id'=>$CooperativeRegistration->affiliated_union_federation_level])->order(['name'=>'ASC'])->toArray();
                }


                if(empty($dccb))
                {

                    $dccb = ['0'=>'To Be Provided'];
                }

                $dccb['-1'] = 'Other';

            $this->set('dccb',$dccb);
        //}

        $curr_society = '';
        //array of pacs, dairy & fishery
        $arr_pdf = [1,20,22,9,10];
        if(!in_array($data['sector_of_operation'],$arr_pdf))
        {
            $curr_society = $this->PrimaryActivities->find('all')->where(['id' =>$CooperativeRegistration['sector_of_operation']])->first()->function_name ?? '';

            if(!empty($curr_society))
            {
                $curr_society = str_replace("-","_",$curr_society);
                $curr_society = $curr_society.'.ctp'; 
            }
        }


        
        $this->loadModel('SecondaryActivities');   
        $multitypes =$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        $this->set('multitypes',$multitypes);

        $this->loadModel('BeeBeehiveTypes');   
        $beetypes =$this->BeeBeehiveTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type'=>1]);
        $this->set('beetypes',$beetypes);

        $beehivetypes =$this->BeeBeehiveTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type'=>2]);
        $this->set('beehivetypes',$beehivetypes);
         
        $this->loadMOdel('ProductProduceBySociety');
        $type_product=$this->ProductProduceBySociety->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>79]);
        $this->set('type_product', $type_product);

        
        $this->loadModel('TypeSocialCulturalActivities');   
        $activitiestypes =$this->TypeSocialCulturalActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        $this->set('activitiestypes',$activitiestypes);

         
        $this->loadMOdel('TypeSocialCooperatives');
            $type_social=$this->TypeSocialCooperatives->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
            $this->set('type_social', $type_social);
      

        $this->loadModel('HousingSocietyTypes');
        $societytypes =$this->HousingSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('societytypes',$societytypes);

        $this->loadModel('SerticultureSilkwormTypes');   
        $sertisocietytypes =$this->SerticultureSilkwormTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('sertisocietytypes',$sertisocietytypes);


        $this->loadModel('AgricultureSocietyTypes');   
        $agrisocietytypes =$this->AgricultureSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('agrisocietytypes',$agrisocietytypes);

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,$CooperativeRegistration['sector_of_operation']]])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('facilities',$facilities);

        $this->loadMOdel('TypeRawMaterial');
        $type_raw=$this->TypeRawMaterial->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        $this->set('type_raw', $type_raw);

        $this->loadMOdel('TypeRawMaterial');
        $type_jraw=$this->TypeRawMaterial->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>90]);
        $this->set('type_jraw', $type_jraw);

        $this->loadMOdel('ProductProduceBySociety');  
        $productproduce=$this->ProductProduceBySociety->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>90])->toArray();
        $this->set('productproduce', $productproduce);  

        $this->loadModel('LivestockSocietyTypes');   
        $livesocietytypes =$this->LivestockSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('livesocietytypes',$livesocietytypes);

        $this->loadModel('TypeWomenCooperatives');   
        $womensocietytypes =$this->TypeWomenCooperatives->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        $this->set('womensocietytypes',$womensocietytypes);


        // $this->loadModel('TypeWomenCooperatives');   
        // $womensocietytypes =$this->TypeWomenCooperatives->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        // $this->set('womensocietytypes',$womensocietytypes);
        
        $this->loadMOdel('LivestockPrimaryActivity');
        $type_lpa=$this->LivestockPrimaryActivity->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        $this->set('type_lpa', $type_lpa);

        $this->loadMOdel('ProductProduceBySociety');  
        $lproductproduce=$this->ProductProduceBySociety->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>54])->toArray();
        $this->set('lproductproduce', $lproductproduce);

        

          
        $this->loadModel('EducationSocietyTypes');   
        $educationsocietytypes =$this->EducationSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('educationsocietytypes',$educationsocietytypes);

        $this->loadModel('LevelAndDurationCourses');   
        $levelofcourses =$this->LevelAndDurationCourses->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type' => 1])->toArray();
        $this->set('levelofcourses',$levelofcourses);
        $durationofcourses =$this->LevelAndDurationCourses->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type' => 2])->toArray();
        $this->set('durationofcourses',$durationofcourses);
        $leveldurationofcourses =$this->LevelAndDurationCourses->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
        $this->set('leveldurationofcourses',$leveldurationofcourses);

        $this->loadMOdel('CooperativeSocietyBanks');
        $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
        $this->set('list_banks', $list_banks);

        $this->loadModel('TribalSocietyTypes');   
        $tribalsocietytypes =$this->TribalSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('tribalsocietytypes',$tribalsocietytypes);

        $this->loadModel('TourismSocietyTypes');   
        $tourismsocietytypes =$this->TourismSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('tourismsocietytypes',$tourismsocietytypes);

        $this->loadModel('LabourSocietyTypes');
        $lsocietytypes =$this->LabourSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('lsocietytypes',$lsocietytypes);

        $this->loadModel('TransportSocietyTypes');
        $trsocietytypes =$this->TransportSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('trsocietytypes',$trsocietytypes);


        $CooperativeRegistration['bank_type'] = explode(',',$CooperativeRegistration['bank_type']);

        
        $b_type = array_merge($CooperativeRegistration['bank_type'],[0]);        

        $this->loadModel('CooperativeSocietyBanks');

        // echo '<pre>';
        // print_r($CooperativeRegistrationsServices);die;

        $arr_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type IN'=>$b_type])->order(['orderseq'=>'ASC']);
        
        $this->set(compact('CooperativeRegistration','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','pActivities','years','register_authorities','sectors','designations','water_body_type','federationlevel','sectorUrbans','area_of_operation_id_urban','area_of_operation_id_rural','curr_society','societytypes','arr_banks','services','display_dep_questions','cooperative_registrations_services'));
    }

    /**
     * Delete method
     *
     * @param string|null $id State id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $CooperativeRegistration = $this->CooperativeRegistrations->get($id);

        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $this->loadMOdel('Users');
            // echo '<pre>';
            // print_r($CooperativeRegistration);die;   
            $created_by = $CooperativeRegistration['created_by'];
            $cUser = $this->Users->find('all')->where(['id' => $created_by])->first();

            if(empty($created_by) || $cUser['id'] != $created_by)
            {
                $this->Flash->error(__('You are not authorized.'));
                return $this->redirect(['action' => 'dataEntryPending']);    
            }
        }


        $data['status'] = 0;
        $data['updated_by'] = $this->request->session()->read('Auth.User.id');
        $data['updated'] = date('Y-m-d H:i:s');
        $CooperativeRegistration = $this->CooperativeRegistrations->patchEntity($CooperativeRegistration, $data);

        if($this->CooperativeRegistrations->save($CooperativeRegistration)) {
            $this->Flash->success(__('The Cooperative Registration has been deleted.'));
        }
        $this->redirect($this->referer());

        /*
        if ($this->CooperativeRegistrations->delete($CooperativeRegistration)) {
            $this->loadModel('CooperativeRegistrationsMultiStates');
            $this->CooperativeRegistrationsMultiStates->deleteAll(['cooperative_registration_id'=>$id]);

            $this->loadModel('CooperativeRegistrationsContactNumbers');
            $this->CooperativeRegistrationsContactNumbers->deleteAll(['cooperative_registration_id'=>$id]);

            $this->loadModel('CooperativeRegistrationsEmails');
            $this->CooperativeRegistrationsEmails->deleteAll(['cooperative_registration_id'=>$id]);
            
            $this->Flash->success(__('The Cooperative Registration has been deleted.'));
        } else {
            $this->Flash->error(__('The Cooperative Registration could not be deleted. Please, try again.'));
        }*/

        //return $this->redirect(['action' => 'index']);
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
    public function getBlocks(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $district_code=$this->request->data('district_code');   
            $sector=$this->request->data('sector'); 
            //$this->loadMOdel('Blocks');
            //$Blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$district_code])->order(['name'=>'ASC']);
			$this->loadMOdel('DistrictsBlocksGpVillages');
            $Blocks=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$district_code])->group(['block_code']);
			
            $option_html='<option value="">Select</option>';
            if(isset($sector) && !empty($sector) && !(in_array($sector,[1,20,22,9,10])))
            {
                $option_html.='<option value="-1">Select All</option>';
            }
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
            $gp_code=$this->request->data('gp_code');    
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
            $state_code=$this->request->data('state_code');    
            
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
            $urban_local_body_type_code=$this->request->data('urban_local_body_type_code');  
            $state_code=$this->request->data('state_code');    
            
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




public function AddRowContactNumber(){

  $this->viewBuilder()->setLayout('ajax');


   $hr2=$this->request->data['hr2'];
   $this->set('hr2',$hr2);

}
public function AddRowEmail(){

  $this->viewBuilder()->setLayout('ajax');


   $hr2=$this->request->data['hr2'];
   $this->set('hr2',$hr2);

}
    
    public function dataEntryPending()
    {
        $this->loadModel('States');

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {
            $societyName = trim($this->request->query['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "CooperativeRegistrations.cooperative_society_name like '%" . $societyName . "%'";
        }

        if (!empty($this->request->query['registration_number'])) {
            $registrationNumber = trim($this->request->query['registration_number']);
            $this->set('registrationNumber', $registrationNumber);
            $search_condition[] = "CooperativeRegistrations.registration_number like '%" . $registrationNumber . "%'";
        }

        if (!empty($this->request->query['cooperative_id'])) {
            $cooperativeId = trim($this->request->query['cooperative_id']);
            $search_condition[] = "CooperativeRegistrations.cooperative_id_num like '%" . (int)substr($cooperativeId, 2) . "%'";
            $this->set('cooperativeId', $cooperativeId);
        }

        if (!empty($this->request->query['reference_year'])) {
            $referenceYear = trim($this->request->query['reference_year']);
            $this->set('referenceYear', $referenceYear);
            $search_condition[] = "CooperativeRegistrations.reference_year like '%" . $referenceYear . "%'";
        }

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $state = trim($this->request->query['state']);
            $this->set('state', $state);
            $search_condition[] = "CooperativeRegistrations.state_code = '" . $state . "'";
        }

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $location = trim($this->request->query['location']);
            $this->set('location', $location);
            $search_condition[] = "CooperativeRegistrations.location_of_head_quarter = '" . $location . "'";
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
        
        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['CooperativeRegistrations.cooperative_id_num' => 'DESC'],
            'conditions' => [$searchString,'CooperativeRegistrations.created_by'=>$this->request->session()->read('Auth.User.id')],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>0,'CooperativeRegistrations.status'=>1]);

        $this->paginate = ['limit' => $page_length];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

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
    }


    public function draft()
    {
        $this->loadModel('PrimaryActivities');
        $this->loadModel('SecondaryActivities');
        $this->loadModel('SectorOperations');
        $this->loadModel('States');

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {
            $societyName = trim($this->request->query['society_name']);
            $search_condition[] = "CooperativeRegistrations.cooperative_society_name like '%" . $societyName . "%'";
        }

        if (!empty($this->request->query['registration_number'])) {
            $registrationNumber = trim($this->request->query['registration_number']);
            $search_condition[] = "CooperativeRegistrations.registration_number like '%" . $registrationNumber . "%'";
        }

        if (!empty($this->request->query['cooperative_id'])) {
            $cooperativeId = trim($this->request->query['cooperative_id']);
            $search_condition[] = "CooperativeRegistrations.cooperative_id_num like '%" . (int)substr($cooperativeId, 2) . "%'";
        }

        if (!empty($this->request->query['reference_year'])) {
            $referenceYear = trim($this->request->query['reference_year']);
            $search_condition[] = "CooperativeRegistrations.reference_year like '%" . $referenceYear . "%'";
        }

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $state = trim($this->request->query['state']);
            $search_condition[] = "CooperativeRegistrations.state_code = '" . $state . "'";
        }

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $location = trim($this->request->query['location']);
            $search_condition[] = "CooperativeRegistrations.location_of_head_quarter = '" . $location . "'";
        }

        if (!empty($this->request->query['primary_activity'])) {
            $primaryActivity = trim($this->request->query['primary_activity']);
            $search_condition[] = "CooperativeRegistrations.sector_of_operation like '%" . $primaryActivity . "%'";
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
        
        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'conditions' => [$searchString,'CooperativeRegistrations.created_by'=>$this->request->session()->read('Auth.User.id')],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrations.is_draft'=>1,'CooperativeRegistrations.status'=>1])->order(['CooperativeRegistrations.id' => 'DESC']);

        $this->paginate = ['limit' => $page_length];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

        $this->set(compact('CooperativeRegistrations'));

        
        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();

        $this->set('states',$stateOption);

        $arr_location = [
            '1'=> 'Urban',
            '2'=> 'Rural'
        ];
        $this->set('arr_location',$arr_location);
        

        $sectorOperations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();

        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

        $this->set(compact('sectorOperations','primary_activities'));
    }

    
    
    public function getPrimaryActivity(){
        $this->viewBuilder()->setLayout('ajax');
            if($this->request->is('ajax')){
                
                $sector_operation = $this->request->data('sector_operation');    

                $this->loadMOdel('PrimaryActivities');

                $pactivities = $this->PrimaryActivities->find('all')->where(['sector_of_operation'=>$sector_operation,'status'=>1])->order(['name'=>'ASC']);
              
                $option_html='<option value="">Select</option>';

                if($pactivities->count()>0){
                    foreach($pactivities as $value){
                        $option_html.='<option function="'.$value->function_name.'" value="'.$value->id.'">'.$value->name.'</option>';
                    }
                }

                echo $option_html;
            }
            exit;
    }
    public function getSecondaryActivity(){
        $this->viewBuilder()->setLayout('ajax');
            if($this->request->is('ajax')){
                
                $sector_operation = $this->request->data('sector_operation');    

                $this->loadMOdel('SecondaryActivities');

                $SecondaryActivities = $this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
              
                $option_html='<option value="">Select</option>';

                if($SecondaryActivities->count()>0){
                    foreach($SecondaryActivities as $key=>$value){
                        $option_html.='<option value="'.$key.'">'.$value.'</option>';
                    }
                }

                echo $option_html;
            }
            exit;
    }

   public function getdccb()
    {
         $this->viewBuilder()->setLayout('ajax');
            if($this->request->is('ajax')){
                
                $dccb                   = $this->request->data('union_level');  
                $state_code             = $this->request->data('state');  
                $primary_activity_id    = $this->request->data('primary_activity');  
                $entity_type            = $this->request->data('entity_type'); 
                

                $this->loadMOdel('DistrictCentralCooperativeBank');
                $this->loadMOdel('LevelOfAffiliatedUnion');

                $a_union_level = $this->LevelOfAffiliatedUnion->find('all')->where(['id'=>$dccb])->first()->affiliated_level;

                if($a_union_level == 1)
                {
                    //national level
                    $SecondaryActivities = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','dccb_flag'=>3])->order(['name'=>'ASC'])->toArray();
                } else {
                    $SecondaryActivities = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','primary_activity_flag'=>$primary_activity_id,'level_of_affiliated_union_id'=>$entity_type])->order(['name'=>'ASC'])->toArray();
                }
                
                //,'state_code'=>$state_code
                    
              
                $option_html='<option value="">Select</option>';
                
                if(count($SecondaryActivities)==0){
                    
                    $option_html.='<option value="0">To Be Provided</option>';
                }
                if(count($SecondaryActivities)>0){
                    foreach($SecondaryActivities as $key=>$value){
                        $option_html.='<option value="'.$key.'">'.$value.'</option>';
                    }
                }
                $option_html.='<option value="-1">Other</option>';

                echo $option_html;
            }
            exit;
    }

    public function getfederationlevel()
    {
            $this->viewBuilder()->setLayout('ajax');
            if($this->request->is('ajax')){                
                $primary_activity_id = $this->request->data('primary_activity'); 


                $this->loadMOdel('LevelOfAffiliatedUnion');          
                $SecondaryActivities = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1,'primary_activity_id'=>$primary_activity_id])->order(['orderseq'=>'ASC'])->toArray();
                          
                $option_html='<option value="">Select</option>';
                if(count($SecondaryActivities)>0){
                    foreach($SecondaryActivities as $key=>$value){
                        $option_html.='<option value="'.$key.'">'.$value.'</option>';
                    }
                
                    }

                echo $option_html;
            }
            exit;

    }
    public function ruralVillageAddRow()
    {
        $this->viewBuilder()->setLayout('ajax');
        $hr2 = $this->request->data('hr2'); 
        $this->loadMOdel('Districts');
        $state_code = $this->request->data('state_code'); 
        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$state_code])->order(['name'=>'ASC'])->toArray();  
       $this->set('hr2',$hr2);
       $this->set('districts',$districts);
        
    }

    public function GovSchemeAddRow()
    {
        $this->viewBuilder()->setLayout('ajax');
        $gs3 = $this->request->data('gs3'); 
        $this->loadMOdel('Districts');
        $state_code = $this->request->data('state_code'); 
        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$state_code])->order(['name'=>'ASC'])->toArray();  
       $this->set('gs2',$gs3);
       $this->set('districts',$districts);
        
    }

    public function urbanWardAddRow()
    {
        $this->viewBuilder()->setLayout('ajax');
        $uw2 = $this->request->data('uw2'); 


        $this->loadMOdel('UrbanLocalBodies');
        $state_code = $this->request->data('state_code');

        $this->loadMOdel('Districts');
        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$state_code])->order(['name'=>'ASC'])->toArray();  

        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);

         
        $this->set('districts',$districts);
       $this->set('uw2',$uw2);
       $this->set('localbody_types',$localbody_types);
        
    }

    //for district nodal list
    public function pending()
    {

        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            //$this->Flash->error(__('You are not authorized.'));
            return $this->redirect(['action' => 'dataEntryPending']);    
            
        }
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');


        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {
            $societyName = trim($this->request->query['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "CooperativeRegistrations.cooperative_society_name like '%" . $societyName . "%'";
        }

        if (!empty($this->request->query['registration_number'])) {
            $registrationNumber = trim($this->request->query['registration_number']);
            $this->set('registrationNumber', $registrationNumber);
            $search_condition[] = "CooperativeRegistrations.registration_number like '%" . $registrationNumber . "%'";
        }

        if (!empty($this->request->query['cooperative_id'])) {
            $cooperativeId = trim($this->request->query['cooperative_id']);
            $search_condition[] = "CooperativeRegistrations.cooperative_id_num like '%" . (int)substr($cooperativeId, 2) . "%'";
            $this->set('cooperativeId', $cooperativeId);
        }

        if (!empty($this->request->query['reference_year'])) {
            $referenceYear = trim($this->request->query['reference_year']);
            $this->set('referenceYear', $referenceYear);
            $search_condition[] = "CooperativeRegistrations.reference_year like '%" . $referenceYear . "%'";
        }

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $state = trim($this->request->query['state']);
            $this->set('state', $state);
            $search_condition[] = "CooperativeRegistrations.state_code = '" . $state . "'";
        }

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $location = trim($this->request->query['location']);
            $this->set('location', $location);
            $search_condition[] = "CooperativeRegistrations.location_of_head_quarter = '" . $location . "'";
        }

        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='') {
            $s_sector_operation = trim($this->request->query['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "CooperativeRegistrations.sector_of_operation_type = '" . $s_sector_operation . "'";
        }

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);
            $search_condition[] = "CooperativeRegistrations.sector_of_operation = '" . $s_primary_activity . "'";
        }

        if (isset($this->request->query['secondary_activity']) && $this->request->query['secondary_activity'] !='') {
            $s_secondary_activity = trim($this->request->query['secondary_activity']);
            $this->set('s_secondary_activity', $s_secondary_activity);
            $search_condition[] = "CooperativeRegistrations.secondary_activity = '" . $s_secondary_activity . "'";
        }

         //=============================Urban=====================================

         if (isset($this->request->query['local_category']) && $this->request->query['local_category'] !='') {
            $s_local_category = trim($this->request->query['local_category']);
            $this->set('s_local_category', $s_local_category);
            $search_condition[] = "CooperativeRegistrations.urban_local_body_type_code = '" . $s_local_category . "'";
        }

        if (isset($this->request->query['local_body']) && $this->request->query['local_body'] !='') {
            $s_local_body = trim($this->request->query['local_body']);
            $this->set('s_local_body', $s_local_body);
            $search_condition[] = "CooperativeRegistrations.urban_local_body_code = '" . $s_local_body . "'";
        }

        if (isset($this->request->query['ward']) && $this->request->query['ward'] !='') {
            $s_ward = trim($this->request->query['ward']);
            $this->set('s_ward', $s_ward);
            $search_condition[] = "CooperativeRegistrations.locality_ward_code = '" . $s_ward . "'";
        }

        //=============================Urban=====================================

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "CooperativeRegistrations.district_code = '" . $s_district . "'";
        }

        if (isset($this->request->query['block']) && $this->request->query['block'] !='') {
            $s_block = trim($this->request->query['block']);
            $this->set('s_block', $s_block);
            $search_condition[] = "CooperativeRegistrations.block_code = '" . $s_block . "'";
        }

        if (isset($this->request->query['panchayat']) && $this->request->query['panchayat'] !='') {
            $s_panchayat = trim($this->request->query['panchayat']);
            $this->set('s_panchayat', $s_panchayat);
            $search_condition[] = "CooperativeRegistrations.gram_panchayat_code = '" . $s_panchayat . "'";
        }

        if (isset($this->request->query['village']) && $this->request->query['village'] !='') {
            $s_village = trim($this->request->query['village']);
            $this->set('s_village', $s_village);
            $search_condition[] = "CooperativeRegistrations.village_code = '" . $s_village . "'";
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

        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);

        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
        $this->set('sectors',$sectors);  
        
        $this->loadModel('Users');
        $user_all=$this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
        $this->set('user_all', $user_all);
       
        $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();
        $this->set('arr_localbodies',$arr_localbodies);
       
        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $this->set('arr_districts',$arr_districts);
        if($this->request->is('get')){
            if(!empty($this->request->query['export_excel'])) {
                $queryExport = $this->CooperativeRegistrations->find('all', [
                    'order' => ['created' => 'DESC'],
                    'conditions' => [$searchString]
                ])->where(['is_draft'=>0,'is_approved'=>0,'status'=>1]);
                if($this->request->session()->read('Auth.User.role_id') == 8)
                {
                    
                    $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();
        
                    $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
                    if(!empty($nodal_data_entry_ids)){
                        $queryExport = $queryExport->where(['created_by IN'=>$nodal_data_entry_ids]);
                    }else {
                        $queryExport = $queryExport->where(['created_by IN'=>0]);
                    }
                }
        

                $queryExport->hydrate(false);
                $ExportResultData = $queryExport->toArray();
                $fileName = "Pendinglist-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No",  "cooperative_society_name", "location", "state", "district/urban_local_body", "sector","registration_number","cooperative_id","created_by","reference_rear","date_registration");
                $data = array();
                $i=1;
                $hlocation = ['1'=> 'Urban','2' => 'Rural'];
                foreach($ExportResultData As $rows){
                    $data[]=array($i, $rows['cooperative_society_name'],$hlocation[$rows['location_of_head_quarter']],$stateOption[$rows['state_code']],$arr_districts[$rows['district_code']],$sectors[$rows['sector_of_operation']], $rows['registration_number'],$rows['cooperative_id'],$user_all[$rows['created_by']], $rows['reference_year'], $rows['date_registration']);
                    $i++;
                } 
                $this->exportInExcel($fileName, $headerRow, $data);
            }
        }

        //is_approved=>0 (pending),is_approved=>1 (accepted),is_approved=>2 (rejected)
        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['CooperativeRegistrations.updated' => 'DESC'],
            'conditions' => [$searchString],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>0,'CooperativeRegistrations.status'=>1]);

        //for District Nodal cooperative registrations list
        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            
            $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();

            $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
			if(!empty($nodal_data_entry_ids)){
				$registrationQuery = $registrationQuery->where(['CooperativeRegistrations.created_by IN'=>$nodal_data_entry_ids]);
			}else {
				$registrationQuery = $registrationQuery->where(['CooperativeRegistrations.created_by IN'=>0]);
			}
        }

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

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

        // if(!empty($this->request->query['sector_operation']))
        // {
        //     //for credit
        //     $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$this->request->query['sector_operation'],'status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        // }
        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('primary_activities',$primary_activities);


        // Start SecondaryActivities for dropdown
        $this->loadModel('SecondaryActivities');

        $secondary_activities = $this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $this->set('secondary_activities',$secondary_activities);

       //======================urban================================================

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 1)
       {
           $this->loadMOdel('UrbanLocalBodies');

           $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$this->request->query['state']])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
   
           $this->set('local_categories',$local_categories);
       }
        
       if(!empty($this->request->query['local_category']) && $this->request->query['location'] == 1)
       {
           

           $localbodies = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$this->request->query['local_category'],'state_code'=>$this->request->query['state']])->order(['local_body_name'=>'ASC'])->toArray();
          
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

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 2)
       {
           $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
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

       $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);
    }

    //For district nodal accepted list
    public function accepted()
    {

        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            //$this->Flash->error(__('You are not authorized.'));
            return $this->redirect(['action' => 'dataEntryPending']);    
            
        }

        $this->request->session()->delete('prev_corr_url');
        
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');


        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {
            $societyName = trim($this->request->query['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "CooperativeRegistrations.cooperative_society_name like '%" . $societyName . "%'";
        }

        if (!empty($this->request->query['registration_number'])) {
            $registrationNumber = trim($this->request->query['registration_number']);
            $this->set('registrationNumber', $registrationNumber);
            $search_condition[] = "CooperativeRegistrations.registration_number like '%" . $registrationNumber . "%'";
        }

        if (!empty($this->request->query['cooperative_id'])) {
            $cooperativeId = trim($this->request->query['cooperative_id']);
            $search_condition[] = "CooperativeRegistrations.cooperative_id_num like '%" . (int)substr($cooperativeId, 2) . "%'";
            $this->set('cooperativeId', $cooperativeId);
        }

        if (!empty($this->request->query['reference_year'])) {
            $referenceYear = trim($this->request->query['reference_year']);
            $this->set('referenceYear', $referenceYear);
            $search_condition[] = "CooperativeRegistrations.reference_year like '%" . $referenceYear . "%'";
        }

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $state = trim($this->request->query['state']);
            $this->set('state', $state);
            $search_condition[] = "CooperativeRegistrations.state_code = '" . $state . "'";
        }

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $location = trim($this->request->query['location']);
            $this->set('location', $location);
            $search_condition[] = "CooperativeRegistrations.location_of_head_quarter = '" . $location . "'";
        }

        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='') {
            $s_sector_operation = trim($this->request->query['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "CooperativeRegistrations.sector_of_operation_type = '" . $s_sector_operation . "'";
        }

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);
            $search_condition[] = "CooperativeRegistrations.sector_of_operation = '" . $s_primary_activity . "'";
        }

        if (isset($this->request->query['secondary_activity']) && $this->request->query['secondary_activity'] !='') {
            $s_secondary_activity = trim($this->request->query['secondary_activity']);
            $this->set('s_secondary_activity', $s_secondary_activity);
            $search_condition[] = "CooperativeRegistrations.secondary_activity = '" . $s_secondary_activity . "'";
        }

         //=============================Urban=====================================

         if (isset($this->request->query['local_category']) && $this->request->query['local_category'] !='') {
            $s_local_category = trim($this->request->query['local_category']);
            $this->set('s_local_category', $s_local_category);
            $search_condition[] = "CooperativeRegistrations.urban_local_body_type_code = '" . $s_local_category . "'";
        }

        if (isset($this->request->query['local_body']) && $this->request->query['local_body'] !='') {
            $s_local_body = trim($this->request->query['local_body']);
            $this->set('s_local_body', $s_local_body);
            $search_condition[] = "CooperativeRegistrations.urban_local_body_code = '" . $s_local_body . "'";
        }

        if (isset($this->request->query['ward']) && $this->request->query['ward'] !='') {
            $s_ward = trim($this->request->query['ward']);
            $this->set('s_ward', $s_ward);
            $search_condition[] = "CooperativeRegistrations.locality_ward_code = '" . $s_ward . "'";
        }

        //=============================Urban=====================================

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "CooperativeRegistrations.district_code = '" . $s_district . "'";
        }

        if (isset($this->request->query['block']) && $this->request->query['block'] !='') {
            $s_block = trim($this->request->query['block']);
            $this->set('s_block', $s_block);
            $search_condition[] = "CooperativeRegistrations.block_code = '" . $s_block . "'";
        }

        if (isset($this->request->query['panchayat']) && $this->request->query['panchayat'] !='') {
            $s_panchayat = trim($this->request->query['panchayat']);
            $this->set('s_panchayat', $s_panchayat);
            $search_condition[] = "CooperativeRegistrations.gram_panchayat_code = '" . $s_panchayat . "'";
        }

        if (isset($this->request->query['village']) && $this->request->query['village'] !='') {
            $s_village = trim($this->request->query['village']);
            $this->set('s_village', $s_village);
            $search_condition[] = "CooperativeRegistrations.village_code = '" . $s_village . "'";
        }

        
        if(!empty($search_condition)){
        //     echo '<pre>';
        // print_r($search_condition);die;
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
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

        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
        $this->set('sectors',$sectors);  
        
        $this->loadModel('Users');
        $user_all=$this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
        $this->set('user_all', $user_all);
       
        $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();
        $this->set('arr_localbodies',$arr_localbodies);
       
        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $this->set('arr_districts',$arr_districts);
        if($this->request->is('get')){
            if(!empty($this->request->query['export_excel'])) {
                $queryExport =$this->CooperativeRegistrations->find('all', [
                    'order' => ['CooperativeRegistrations.cooperative_id_num' => 'DESC'],
                    'conditions' => [$searchString],
                    'contain' => ['PrimaryActivities']
                ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>1,'CooperativeRegistrations.status'=>1]);
                if($this->request->session()->read('Auth.User.role_id') == 8)
                {
                    
                    $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();
        
                    $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
                    if(!empty($nodal_data_entry_ids)){
                        $queryExport = $queryExport->where(['created_by IN'=>$nodal_data_entry_ids]);
                    }else {
                        $queryExport = $queryExport->where(['created_by IN'=>0]);
                    }
                }
        

                $queryExport->hydrate(false);
                $ExportResultData = $queryExport->toArray();
                $fileName = "Acceptedlist-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No",  "cooperative_society_name", "location", "state", "district/urban_local_body", "sector","registration_number","cooperative_id","created_by","reference_rear","date_registration");
                $data = array();
                $i=1;
                $hlocation = ['1'=> 'Urban','2' => 'Rural'];
                foreach($ExportResultData As $rows){
                    $data[]=array($i, $rows['cooperative_society_name'],$hlocation[$rows['location_of_head_quarter']],$stateOption[$rows['state_code']],$arr_districts[$rows['district_code']],$sectors[$rows['sector_of_operation']], $rows['registration_number'],$rows['cooperative_id'],$user_all[$rows['created_by']], $rows['reference_year'], $rows['date_registration']);
                    $i++;
                } 
                $this->exportInExcel($fileName, $headerRow, $data);
            }
        }

        //is_approved=>0 (pending),is_approved=>1 (accepted),is_approved=>2 (rejected)
        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['CooperativeRegistrations.updated' => 'DESC'],
            'conditions' => [$searchString],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>1,'CooperativeRegistrations.status'=>1]);


        //for District Nodal cooperative registrations list
        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            
            $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();

            $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);

            if(!empty($nodal_data_entry_ids)){
			$registrationQuery = $registrationQuery->where(['CooperativeRegistrations.created_by IN'=>$nodal_data_entry_ids]);
			}else {
				$registrationQuery = $registrationQuery->where(['CooperativeRegistrations.created_by IN'=>0]);
			}
        }

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

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

        if(!empty($this->request->query['sector_operation']) || 1)
        {
            //for credit
            $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
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

           $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$this->request->query['state']])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
   
           $this->set('local_categories',$local_categories);
       }
        
       if(!empty($this->request->query['local_category']) && $this->request->query['location'] == 1)
       {
           

           $localbodies = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$this->request->query['local_category'],'state_code'=>$this->request->query['state']])->order(['local_body_name'=>'ASC'])->toArray();
          
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

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 2)
       {
           $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
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

       $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);
    }

    //For district nodal rejected list
    public function rejected()
    {

        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            //$this->Flash->error(__('You are not authorized.'));
            return $this->redirect(['action' => 'dataEntryPending']);    
            
        }
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');


        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {
            $societyName = trim($this->request->query['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "CooperativeRegistrations.cooperative_society_name like '%" . $societyName . "%'";
        }

        if (!empty($this->request->query['registration_number'])) {
            $registrationNumber = trim($this->request->query['registration_number']);
            $this->set('registrationNumber', $registrationNumber);
            $search_condition[] = "CooperativeRegistrations.registration_number like '%" . $registrationNumber . "%'";
        }

        if (!empty($this->request->query['cooperative_id'])) {
            $cooperativeId = trim($this->request->query['cooperative_id']);
            $search_condition[] = "CooperativeRegistrations.cooperative_id_num like '%" . (int)substr($cooperativeId, 2) . "%'";
            $this->set('cooperativeId', $cooperativeId);
        }

        if (!empty($this->request->query['reference_year'])) {
            $referenceYear = trim($this->request->query['reference_year']);
            $this->set('referenceYear', $referenceYear);
            $search_condition[] = "CooperativeRegistrations.reference_year like '%" . $referenceYear . "%'";
        }

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $state = trim($this->request->query['state']);
            $this->set('state', $state);
            $search_condition[] = "CooperativeRegistrations.state_code = '" . $state . "'";
        }

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $location = trim($this->request->query['location']);
            $this->set('location', $location);
            $search_condition[] = "CooperativeRegistrations.location_of_head_quarter = '" . $location . "'";
        }

        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='') {
            $s_sector_operation = trim($this->request->query['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "CooperativeRegistrations.sector_of_operation_type = '" . $s_sector_operation . "'";
        }

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);
            $search_condition[] = "CooperativeRegistrations.sector_of_operation = '" . $s_primary_activity . "'";
        }

        if (isset($this->request->query['secondary_activity']) && $this->request->query['secondary_activity'] !='') {
            $s_secondary_activity = trim($this->request->query['secondary_activity']);
            $this->set('s_secondary_activity', $s_secondary_activity);
            $search_condition[] = "CooperativeRegistrations.secondary_activity = '" . $s_secondary_activity . "'";
        }

         //=============================Urban=====================================

         if (isset($this->request->query['local_category']) && $this->request->query['local_category'] !='') {
            $s_local_category = trim($this->request->query['local_category']);
            $this->set('s_local_category', $s_local_category);
            $search_condition[] = "CooperativeRegistrations.urban_local_body_type_code = '" . $s_local_category . "'";
        }

        if (isset($this->request->query['local_body']) && $this->request->query['local_body'] !='') {
            $s_local_body = trim($this->request->query['local_body']);
            $this->set('s_local_body', $s_local_body);
            $search_condition[] = "CooperativeRegistrations.urban_local_body_code = '" . $s_local_body . "'";
        }

        if (isset($this->request->query['ward']) && $this->request->query['ward'] !='') {
            $s_ward = trim($this->request->query['ward']);
            $this->set('s_ward', $s_ward);
            $search_condition[] = "CooperativeRegistrations.locality_ward_code = '" . $s_ward . "'";
        }

        //=============================Urban=====================================

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "CooperativeRegistrations.district_code = '" . $s_district . "'";
        }

        if (isset($this->request->query['block']) && $this->request->query['block'] !='') {
            $s_block = trim($this->request->query['block']);
            $this->set('s_block', $s_block);
            $search_condition[] = "CooperativeRegistrations.block_code = '" . $s_block . "'";
        }

        if (isset($this->request->query['panchayat']) && $this->request->query['panchayat'] !='') {
            $s_panchayat = trim($this->request->query['panchayat']);
            $this->set('s_panchayat', $s_panchayat);
            $search_condition[] = "CooperativeRegistrations.gram_panchayat_code = '" . $s_panchayat . "'";
        }

        if (isset($this->request->query['village']) && $this->request->query['village'] !='') {
            $s_village = trim($this->request->query['village']);
            $this->set('s_village', $s_village);
            $search_condition[] = "CooperativeRegistrations.village_code = '" . $s_village . "'";
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

        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);

        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
        $this->set('sectors',$sectors);  
        
        $this->loadModel('Users');
        $user_all=$this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
        $this->set('user_all', $user_all);
       
        $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();
        $this->set('arr_localbodies',$arr_localbodies);
       
        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $this->set('arr_districts',$arr_districts);
        if($this->request->is('get')){
            if(!empty($this->request->query['export_excel'])) {
                $queryExport = $this->CooperativeRegistrations->find('all', [
                    'order' => ['cooperative_id_num' => 'DESC'],
                    'conditions' => [$searchString]
                ])->where(['is_draft'=>0,'is_approved'=>2,'status'=>1]);
        
              
                if($this->request->session()->read('Auth.User.role_id') == 8)
                {
                    
                    $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();
        
                    $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
        
                    if(!empty($nodal_data_entry_ids)){
                    $queryExport = $queryExport->where(['created_by IN'=>$nodal_data_entry_ids]);
                    }else {
                        $queryExport = $queryExport->where(['created_by IN'=>0]);
                    }
                }
        

                $queryExport->hydrate(false);
                $ExportResultData = $queryExport->toArray();
                $fileName = "Rejectedlist-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No",  "cooperative_society_name", "location", "state", "district/urban_local_body", "sector","registration_number","cooperative_id","created_by","reference_rear","date_registration");
                $data = array();
                $i=1;
                $hlocation = ['1'=> 'Urban','2' => 'Rural'];
                foreach($ExportResultData As $rows){
                    $data[]=array($i, $rows['cooperative_society_name'],$hlocation[$rows['location_of_head_quarter']],$stateOption[$rows['state_code']],$arr_districts[$rows['district_code']],$sectors[$rows['sector_of_operation']], $rows['registration_number'],$rows['cooperative_id'],$user_all[$rows['created_by']], $rows['reference_year'], $rows['date_registration']);
                    $i++;
                } 
                $this->exportInExcel($fileName, $headerRow, $data);
            }
        }

        //is_approved=>0 (pending),is_approved=>1 (accepted),is_approved=>2 (rejected)
        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['CooperativeRegistrations.updated' => 'DESC'],
            'conditions' => [$searchString],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>2,'CooperativeRegistrations.status'=>1]);

        //for District Nodal cooperative registrations list
        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            
            $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();

            $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);

            if(!empty($nodal_data_entry_ids)){
			$registrationQuery = $registrationQuery->where(['created_by IN'=>$nodal_data_entry_ids]);
			}else {
				$registrationQuery = $registrationQuery->where(['created_by IN'=>0]);
			}
        }

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

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
        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

      

        $this->set('primary_activities',$primary_activities);


        // Start SecondaryActivities for dropdown
        $this->loadModel('SecondaryActivities');

        $secondary_activities = $this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $this->set('secondary_activities',$secondary_activities);

       //======================urban================================================

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 1)
       {
           $this->loadMOdel('UrbanLocalBodies');

           $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$this->request->query['state']])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
   
           $this->set('local_categories',$local_categories);
       }
        
       if(!empty($this->request->query['local_category']) && $this->request->query['location'] == 1)
       {
           

           $localbodies = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$this->request->query['local_category'],'state_code'=>$this->request->query['state']])->order(['local_body_name'=>'ASC'])->toArray();
          
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

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 2)
       {
           $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
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

       $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);
    }

    //bulk approval for district nodal
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

            if($this->request->session()->read('Auth.User.role_id') != '2' || $this->request->session()->read('Auth.User.role_id') != 8)
            {
                $this->redirect($this->referer());
            }

            $remarks_data = [];
            $remarks_data['is_approved'] = $data['is_approved'];
            $remarks_data['remark'] = $data['remark'];
            $remarks_data['approved_by'] = $this->request->session()->read('Auth.User.id');
            
            // echo '<pre>';
            // print_r($remarks_data);
            // print_r($data);
            // die;

            $data['approved_by'] = $this->request->session()->read('Auth.User.id');

            

            foreach($data['arr_accept'] as $key => $id)
            {
                $CooperativeRegistration = $this->CooperativeRegistrations->get($id, [
                    'contain' => ['CRMS','CooperativeRegistrationsContactNumbers','CooperativeRegistrationsEmails','CooperativeRegistrationPacs','CooperativeRegistrationDairy','CooperativeRegistrationFishery','AreaOfOperationLevel','CooperativeRegistrationsLands']
                ]);

                $CooperativeRegistration = $this->CooperativeRegistrations->patchEntity($CooperativeRegistration, $data);


                if($this->CooperativeRegistrations->save($CooperativeRegistration)) 
                {
                    $remarks_data['cooperative_registration_id'] = $id;
                    
                    $CooperativeRegistrationsRemark = $this->CooperativeRegistrationsRemarks->newEntity();
        
                   $CooperativeRegistrationsRemark = $this->CooperativeRegistrationsRemarks->patchEntity($CooperativeRegistrationsRemark, $remarks_data);
                
                   $this->CooperativeRegistrationsRemarks->save($CooperativeRegistrationsRemark);
                
                } 
            }

            $this->Flash->success(__('Request has been accepted.'));

            if($this->request->session()->read('Auth.User.role_id') == 2)
            {
                //Admin
                if($data['is_approved'] == '1')
                {
                    return $this->redirect(['action' => 'adminAccepted']);    
                } else {
                    return $this->redirect(['action' => 'adminRejected']);    
                }
            }

            if($this->request->session()->read('Auth.User.role_id') == 8)
            {
                //District Nodal
                if($data['is_approved'] == '1')
                {
                    return $this->redirect(['action' => 'accepted']);    
                } else {
                    return $this->redirect(['action' => 'rejected']);    
                }
            }
        }

    }

    //approval by district nodal from view page
    public function approval()
    {
        if($this->request->is('post')){
            $data=$this->request->getData();

            $r_data = [];
            $r_data['is_approved'] = $data['is_approved'];
            $r_data['remark'] = $data['remark'];
            $r_data['approved_by'] = $this->request->session()->read('Auth.User.id');

            $data['approved_by'] = $this->request->session()->read('Auth.User.id');
            
            $data['approved_by'] = $this->request->session()->read('Auth.User.id');


            $this->loadMOdel('CooperativeRegistrations');  
            
            $CooperativeRegistration = $this->CooperativeRegistrations->get($data['cooperative_registration_id'], [
                'contain' => ['CRMS','CooperativeRegistrationsContactNumbers','CooperativeRegistrationsEmails','CooperativeRegistrationPacs','CooperativeRegistrationDairy','CooperativeRegistrationFishery','AreaOfOperationLevel','CooperativeRegistrationsLands']
            ]);

           
            //$c_data

            $CooperativeRegistration = $this->CooperativeRegistrations->patchEntity($CooperativeRegistration, $r_data);
            
            if($this->CooperativeRegistrations->save($CooperativeRegistration)) {
                $this->loadMOdel('CooperativeRegistrationsRemarks');
                $CooperativeRegistrationsRemark = $this->CooperativeRegistrationsRemarks->newEntity();
    
               $CooperativeRegistrationsRemark = $this->CooperativeRegistrationsRemarks->patchEntity($CooperativeRegistrationsRemark, $data);
            
               if($this->CooperativeRegistrationsRemarks->save($CooperativeRegistrationsRemark)) {
                  $this->Flash->success(__('Request has been accepted.'));

                if($this->request->session()->read('Auth.User.role_id') == 2)
                {
                    //Admin
                    if($data['is_approved'] == '1')
                    {
                        $this->Flash->success(__('Request has been accepted.'));
						//echo "I am in Accepted Block";
						//exit;
						return $this->redirect(['action' => 'adminAccepted']);
						
                    } else {
						$this->Flash->success(__('Request has been Rejected.'));
                        return $this->redirect(['action' => 'adminRejected']);    
                    }
					
                }
                
                if($this->request->session()->read('Auth.User.role_id') == 8)
                {
                    //District Nodal
                    if($data['is_approved'] == '1')
                  {
                    return $this->redirect(['action' => 'accepted']);    
                  } else {
                    return $this->redirect(['action' => 'rejected']);    
                  }
                }
                  
               } else {
                $this->Flash->error(__('Request is not accepted, please try again.'));
               }
            } else {
                $this->Flash->error(__('Request is not rejected, please try again.'));
            }
            
        }

    }

    /*
    public function rejectrequest()
    {
        
        if($this->request->is('post'))
        {
            $req=$this->request->getData();

            // echo '<pre>';
            // print_r($req);die;
            $id = $req['reject'];
            $remark = $req['remark_'.$id];
            //echo $remark;die;

            $this->loadMOdel('CooperativeRegistrations');  
            
            $CooperativeRegistration = $this->CooperativeRegistrations->get($id, [
                'contain' => ['CRMS','CooperativeRegistrationsContactNumbers','CooperativeRegistrationsEmails','CooperativeRegistrationPacs','CooperativeRegistrationDairy','CooperativeRegistrationFishery','AreaOfOperationLevel','CooperativeRegistrationsLands']
            ]);

            $data = [];
            $data['is_approved'] = 2;
            $data['remark'] = $remark;
            $data['approved_by'] = $this->request->session()->read('Auth.User.id');

            $CooperativeRegistration = $this->CooperativeRegistrations->patchEntity($CooperativeRegistration, $data);
            
            if($this->CooperativeRegistrations->save($CooperativeRegistration)) {
                $this->Flash->success(__('Request has been rejected.'));
            } else {
                $this->Flash->error(__('Request is not rejected, please try again.'));
            }
           //return $this->redirect(['action' => 'rejected']);                  
        }
        

    }*/

    // cooparative registration accepted list for Data Entry Operator
    public function dataEntryAccepted()
    {
        
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');


        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {

            $societyName = trim($this->request->query['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "CooperativeRegistrations.cooperative_society_name like '%" . $societyName . "%'";
        }

        if (!empty($this->request->query['registration_number'])) {
            $registrationNumber = trim($this->request->query['registration_number']);
            $this->set('registrationNumber', $registrationNumber);
            $search_condition[] = "CooperativeRegistrations.registration_number like '%" . $registrationNumber . "%'";
        }

        if (!empty($this->request->query['cooperative_id'])) {
            $cooperativeId = trim($this->request->query['cooperative_id']);
            $search_condition[] = "CooperativeRegistrations.cooperative_id_num like '%" . (int)substr($cooperativeId, 2) . "%'";
            $this->set('cooperativeId', $cooperativeId);
        //     $cooperativeId = trim($this->request->query['cooperative_id']);
        //     $this->set('cooperativeId', $cooperativeId);
        //    // $search_condition[] = "cooperative_id like '%" . $cooperativeId . "%'";

        //    $cooperativeId_num=  ltrim(substr($cooperativeId, 2));
        //    $cooperativeId_num=ltrim($cooperativeId_num,'0');

        //     $search_condition[] = "cooperative_id_num like '%" . $cooperativeId_num . "%'";
             
            
        }

        if (!empty($this->request->query['reference_year'])) {
            $referenceYear = trim($this->request->query['reference_year']);
            $this->set('referenceYear', $referenceYear);
            $search_condition[] = "CooperativeRegistrations.reference_year like '%" . $referenceYear . "%'";
        }

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $state = trim($this->request->query['state']);
            $this->set('state', $state);
            $search_condition[] = "CooperativeRegistrations.state_code = '" . $state . "'";
        }

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $location = trim($this->request->query['location']);
            $this->set('location', $location);
            $search_condition[] = "CooperativeRegistrations.location_of_head_quarter = '" . $location . "'";
        }

        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='') {
            $s_sector_operation = trim($this->request->query['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "CooperativeRegistrations.sector_of_operation_type = '" . $s_sector_operation . "'";
        }

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);
            $search_condition[] = "CooperativeRegistrations.sector_of_operation = '" . $s_primary_activity . "'";
        }

        if (isset($this->request->query['secondary_activity']) && $this->request->query['secondary_activity'] !='') {
            $s_secondary_activity = trim($this->request->query['secondary_activity']);
            $this->set('s_secondary_activity', $s_secondary_activity);
            $search_condition[] = "CooperativeRegistrations.secondary_activity = '" . $s_secondary_activity . "'";
        }

         //=============================Urban=====================================

         if (isset($this->request->query['local_category']) && $this->request->query['local_category'] !='') {
            $s_local_category = trim($this->request->query['local_category']);
            $this->set('s_local_category', $s_local_category);
            $search_condition[] = "CooperativeRegistrations.urban_local_body_type_code = '" . $s_local_category . "'";
        }

        if (isset($this->request->query['local_body']) && $this->request->query['local_body'] !='') {
            $s_local_body = trim($this->request->query['local_body']);
            $this->set('s_local_body', $s_local_body);
            $search_condition[] = "CooperativeRegistrations.urban_local_body_code = '" . $s_local_body . "'";
        }

        if (isset($this->request->query['ward']) && $this->request->query['ward'] !='') {
            $s_ward = trim($this->request->query['ward']);
            $this->set('s_ward', $s_ward);
            $search_condition[] = "CooperativeRegistrations.locality_ward_code = '" . $s_ward . "'";
        }

        //=============================Urban=====================================

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "CooperativeRegistrations.district_code = '" . $s_district . "'";
        }

        if (isset($this->request->query['block']) && $this->request->query['block'] !='') {
            $s_block = trim($this->request->query['block']);
            $this->set('s_block', $s_block);
            $search_condition[] = "CooperativeRegistrations.block_code = '" . $s_block . "'";
        }

        if (isset($this->request->query['panchayat']) && $this->request->query['panchayat'] !='') {
            $s_panchayat = trim($this->request->query['panchayat']);
            $this->set('s_panchayat', $s_panchayat);
            $search_condition[] = "CooperativeRegistrations.gram_panchayat_code = '" . $s_panchayat . "'";
        }

        if (isset($this->request->query['village']) && $this->request->query['village'] !='') {
            $s_village = trim($this->request->query['village']);
            $this->set('s_village', $s_village);
            $search_condition[] = "CooperativeRegistrations.village_code = '" . $s_village . "'";
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

        //is_approved=>0 (pending),is_approved=>1 (accepted),is_approved=>2 (rejected)
        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['CooperativeRegistrations.cooperative_id_num' => 'DESC'],
            'conditions' => [$searchString]
        ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>1,'CooperativeRegistrations.status'=>1]);

        //for District Nodal cooperative registrations list
        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $registrationQuery = $registrationQuery->where(['CooperativeRegistrations.created_by'=>$this->request->session()->read('Auth.User.id')]);
        }
        

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);

        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

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

           $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$this->request->query['state']])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
   
           $this->set('local_categories',$local_categories);
       }
        
       if(!empty($this->request->query['local_category']) && $this->request->query['location'] == 1)
       {
           

           $localbodies = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$this->request->query['local_category'],'state_code'=>$this->request->query['state']])->order(['local_body_name'=>'ASC'])->toArray();
          
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

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 2)
       {
           $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
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

       $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);
    }

    // cooparative registration rejected list for Data Entry Operator
    public function dataEntryRejected()
    {
        
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');


        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {
            $societyName = trim($this->request->query['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "CooperativeRegistrations.cooperative_society_name like '%" . $societyName . "%'";
        }

        if (!empty($this->request->query['registration_number'])) {
            $registrationNumber = trim($this->request->query['registration_number']);
            $this->set('registrationNumber', $registrationNumber);
            $search_condition[] = "CooperativeRegistrations.registration_number like '%" . $registrationNumber . "%'";
        }

        if (!empty($this->request->query['cooperative_id'])) {
            $cooperativeId = trim($this->request->query['cooperative_id']);
            $search_condition[] = "CooperativeRegistrations.cooperative_id_num like '%" . (int)substr($cooperativeId, 2) . "%'";
            $this->set('cooperativeId', $cooperativeId);
        }

        if (!empty($this->request->query['reference_year'])) {
            $referenceYear = trim($this->request->query['reference_year']);
            $this->set('referenceYear', $referenceYear);
            $search_condition[] = "CooperativeRegistrations.reference_year like '%" . $referenceYear . "%'";
        }

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $state = trim($this->request->query['state']);
            $this->set('state', $state);
            $search_condition[] = "CooperativeRegistrations.state_code = '" . $state . "'";
        }

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $location = trim($this->request->query['location']);
            $this->set('location', $location);
            $search_condition[] = "CooperativeRegistrations.location_of_head_quarter = '" . $location . "'";
        }

        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='') {
            $s_sector_operation = trim($this->request->query['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "CooperativeRegistrations.sector_of_operation_type = '" . $s_sector_operation . "'";
        }

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);
            $search_condition[] = "CooperativeRegistrations.sector_of_operation = '" . $s_primary_activity . "'";
        }

        if (isset($this->request->query['secondary_activity']) && $this->request->query['secondary_activity'] !='') {
            $s_secondary_activity = trim($this->request->query['secondary_activity']);
            $this->set('s_secondary_activity', $s_secondary_activity);
            $search_condition[] = "CooperativeRegistrations.secondary_activity = '" . $s_secondary_activity . "'";
        }

         //=============================Urban=====================================

         if (isset($this->request->query['local_category']) && $this->request->query['local_category'] !='') {
            $s_local_category = trim($this->request->query['local_category']);
            $this->set('s_local_category', $s_local_category);
            $search_condition[] = "CooperativeRegistrations.urban_local_body_type_code = '" . $s_local_category . "'";
        }

        if (isset($this->request->query['local_body']) && $this->request->query['local_body'] !='') {
            $s_local_body = trim($this->request->query['local_body']);
            $this->set('s_local_body', $s_local_body);
            $search_condition[] = "CooperativeRegistrations.urban_local_body_code = '" . $s_local_body . "'";
        }

        if (isset($this->request->query['ward']) && $this->request->query['ward'] !='') {
            $s_ward = trim($this->request->query['ward']);
            $this->set('s_ward', $s_ward);
            $search_condition[] = "CooperativeRegistrations.locality_ward_code = '" . $s_ward . "'";
        }

        //=============================Urban=====================================

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "CooperativeRegistrations.district_code = '" . $s_district . "'";
        }

        if (isset($this->request->query['block']) && $this->request->query['block'] !='') {
            $s_block = trim($this->request->query['block']);
            $this->set('s_block', $s_block);
            $search_condition[] = "CooperativeRegistrations.block_code = '" . $s_block . "'";
        }

        if (isset($this->request->query['panchayat']) && $this->request->query['panchayat'] !='') {
            $s_panchayat = trim($this->request->query['panchayat']);
            $this->set('s_panchayat', $s_panchayat);
            $search_condition[] = "CooperativeRegistrations.gram_panchayat_code = '" . $s_panchayat . "'";
        }

        if (isset($this->request->query['village']) && $this->request->query['village'] !='') {
            $s_village = trim($this->request->query['village']);
            $this->set('s_village', $s_village);
            $search_condition[] = "CooperativeRegistrations.village_code = '" . $s_village . "'";
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

        //is_approved=>0 (pending),is_approved=>1 (accepted),is_approved=>2 (rejected)
        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['CooperativeRegistrations.cooperative_id_num' => 'DESC'],
            'conditions' => [$searchString],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>2,'CooperativeRegistrations.status'=>1]);

        //for District Nodal cooperative registrations list
        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $registrationQuery = $registrationQuery->where(['CooperativeRegistrations.created_by'=>$this->request->session()->read('Auth.User.id')]);
        }

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

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

           $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$this->request->query['state']])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
   
           $this->set('local_categories',$local_categories);
       }
        
       if(!empty($this->request->query['local_category']) && $this->request->query['location'] == 1)
       {
           

           $localbodies = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$this->request->query['local_category'],'state_code'=>$this->request->query['state']])->order(['local_body_name'=>'ASC'])->toArray();
          
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

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 2)
       {
           $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
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

       $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);
    }

    public function getUrbanRural()
    {
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax'))
        {
            $operation_area_location=$this->request->data('operation_area_location');    
            
            //echo $operation_area_location;die;
            $this->loadMOdel('AreaOfOperations');
            $arr_area_of_operations=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'urban_rural'=>$operation_area_location])->order(['orderseq'=>'ASC']);
            
            $option_html='<option value="">Select</option>';
            
            if($arr_area_of_operations->count()>0){
                foreach($arr_area_of_operations as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.$value.'</option>';
                }
            }
            echo $option_html;
        }
        exit;
    }

    ######################
       // genrate unique nubmebr aftter submit
    #####################
    public function generateUniqueNumber($id=null)
    {
        $this->loadMOdel('CooperativeRegistrations');
        if($id !='')
        {
            $MaxCooperativeIdA =$this->CooperativeRegistrations->find('all')->select(['cooperative_id_num'=>'MAX(cooperative_id_num)'])->where(['id'=>$id])->order(['cooperative_id_num'=>'DESC'])->first();

        if($MaxCooperativeIdA->cooperative_id_num !='')
        {
             $cooperative_id_num=($MaxCooperativeIdA->cooperative_id_num );
        }else{
         $MaxCooperativeIdA =$this->CooperativeRegistrations->find('all')->select(['cooperative_id_num'=>'MAX(cooperative_id_num)'])->order(['cooperative_id_num'=>'DESC'])->first();

         $cooperative_id_num=($MaxCooperativeIdA->cooperative_id_num + 1);
        }  

        }else{
         $MaxCooperativeIdA =$this->CooperativeRegistrations->find('all')->select(['cooperative_id_num'=>'MAX(cooperative_id_num)'])->order(['cooperative_id_num'=>'DESC'])->first();
            $cooperative_id_num=($MaxCooperativeIdA->cooperative_id_num + 1);
        }

        return $cooperative_id_num;
    }

    public function pHousing()
    {
        $this->viewBuilder()->setLayout('ajax');

        $this->loadModel('HousingSocietyTypes');
        $societytypes =$this->HousingSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('societytypes',$societytypes);

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,47]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    public function pLabour()
    {
        $this->viewBuilder()->setLayout('ajax');

        $this->loadModel('LabourSocietyTypes');
        $lsocietytypes =$this->LabourSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('lsocietytypes',$lsocietytypes);

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,51]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    public function pTransport()
    {
        $this->viewBuilder()->setLayout('ajax');
        

        $this->loadModel('TransportSocietyTypes');
        $trsocietytypes =$this->TransportSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('trsocietytypes',$trsocietytypes);

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,51]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    public function pHandloom()
    {
        $this->viewBuilder()->setLayout('ajax');
        

        $this->loadModel('TransportSocietyTypes');
        $societytypes =$this->TransportSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('societytypes',$societytypes);

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,51]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    public function pAgriculture()
    {
        $this->viewBuilder()->setLayout('ajax');
        

        $this->loadModel('AgricultureSocietyTypes');   
        $agrisocietytypes =$this->AgricultureSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('agrisocietytypes',$agrisocietytypes);

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,51]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }
    // CooperativeRegistrationsTribal
    public function pTribal()
    {
        $this->viewBuilder()->setLayout('ajax');
        

        $this->loadModel('TribalSocietyTypes');   
        $tribalsocietytypes =$this->TribalSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('tribalsocietytypes',$tribalsocietytypes);

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $tribal_facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,102]])->order(['orderseq'=>'ASC']);
        $this->set('tribal_facilities',$tribal_facilities);
        
        $this->loadMOdel('CooperativeSocietyBanks');
        $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
        $this->set('list_banks', $list_banks);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    public function pTourism()
    {
        $this->viewBuilder()->setLayout('ajax');
        

        $this->loadModel('TourismSocietyTypes');   
        $tourismsocietytypes =$this->TourismSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('tourismsocietytypes',$tourismsocietytypes);

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,99]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $this->loadMOdel('CooperativeSocietyBanks');
        $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
        $this->set('list_banks', $list_banks);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    public function pWocoop()
    {
        $this->viewBuilder()->setLayout('ajax');
        

         $this->loadModel('TypeWomenCooperatives');   
        $womensocietytypes =$this->TypeWomenCooperatives->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        $this->set('womensocietytypes',$womensocietytypes);

      
        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,15]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $this->loadMOdel('CooperativeSocietyBanks');
        $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
        $this->set('list_banks', $list_banks);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    public function pBee()
    {
        $this->viewBuilder()->setLayout('ajax');
        

         $this->loadModel('BeeBeehiveTypes');   
        $beetypes =$this->BeeBeehiveTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type'=>1]);
        $this->set('beetypes',$beetypes);

        $beehivetypes =$this->BeeBeehiveTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type'=>2]);
        $this->set('beehivetypes',$beehivetypes);
         
        $this->loadMOdel('ProductProduceBySociety');
            $type_product=$this->ProductProduceBySociety->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>79]);
            $this->set('type_product', $type_product);
      
        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,79]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $this->loadMOdel('CooperativeSocietyBanks');
        $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
        $this->set('list_banks', $list_banks);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }
    public function pMulti()
    {
        $this->viewBuilder()->setLayout('ajax');
        

         $this->loadModel('SecondaryActivities');   
        $multitypes =$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        $this->set('multitypes',$multitypes);

         
        // $this->loadMOdel('ProductProduceBySociety');
        //     $type_product=$this->ProductProduceBySociety->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>79]);
        //     $this->set('type_product', $type_product);
      
        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,16]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $this->loadMOdel('CooperativeSocietyBanks');
        $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
        $this->set('list_banks', $list_banks);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    public function pSocial()
    {
        $this->viewBuilder()->setLayout('ajax');
        

         $this->loadModel('TypeSocialCulturalActivities');   
        $activitiestypes =$this->TypeSocialCulturalActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        $this->set('activitiestypes',$activitiestypes);

         
        $this->loadMOdel('TypeSocialCooperatives');
            $type_social=$this->TypeSocialCooperatives->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
            $this->set('type_social', $type_social);
      
        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,98]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $this->loadMOdel('CooperativeSocietyBanks');
        $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
        $this->set('list_banks', $list_banks);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }
    public function pCmiscellaneous()
    {
        $this->viewBuilder()->setLayout('ajax');
        

         $this->loadModel('TypeSocialCulturalActivities');   
        $activitiestypes =$this->TypeSocialCulturalActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        $this->set('activitiestypes',$activitiestypes);

         
        $this->loadMOdel('TypeSocialCooperatives');
            $type_social=$this->TypeSocialCooperatives->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
            $this->set('type_social', $type_social);
      
        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,35]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $this->loadMOdel('CooperativeSocietyBanks');
        $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
        $this->set('list_banks', $list_banks);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }
    public function pMiscellaneous()
    {
        $this->viewBuilder()->setLayout('ajax');
        

         $this->loadModel('TypeSocialCulturalActivities');   
        $activitiestypes =$this->TypeSocialCulturalActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        $this->set('activitiestypes',$activitiestypes);

         
        $this->loadMOdel('TypeSocialCooperatives');
            $type_social=$this->TypeSocialCooperatives->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
            $this->set('type_social', $type_social);
      
        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,98]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $this->loadMOdel('CooperativeSocietyBanks');
        $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
        $this->set('list_banks', $list_banks);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    public function pEducation()
    {
        $this->viewBuilder()->setLayout('ajax');
        

        $this->loadModel('EducationSocietyTypes');   
        $educationsocietytypes =$this->EducationSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('educationsocietytypes',$educationsocietytypes);

        $this->loadModel('LevelAndDurationCourses');   
        $levelofcourses =$this->LevelAndDurationCourses->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type' => 1]);
        $this->set('levelofcourses',$levelofcourses);
        $durationofcourses =$this->LevelAndDurationCourses->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type' => 2]);
        $this->set('durationofcourses',$durationofcourses);
        $leveldurationofcourses =$this->LevelAndDurationCourses->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        $this->set('leveldurationofcourses',$leveldurationofcourses);

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,84]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $this->loadMOdel('CooperativeSocietyBanks');
        $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
        $this->set('list_banks', $list_banks);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    public function pHandicraft()
    {
        $this->viewBuilder()->setLayout('ajax');
        

        $this->loadModel('TourismSocietyTypes');   
        $tourismsocietytypes =$this->TourismSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('tourismsocietytypes',$tourismsocietytypes);

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,14]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $this->loadMOdel('CooperativeSocietyBanks');
        $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
        $this->set('list_banks', $list_banks);

        $this->loadMOdel('TypeRawMaterial');
        $type_raw=$this->TypeRawMaterial->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        $this->set('type_raw', $type_raw);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    public function pUcb()
    {
        $this->viewBuilder()->setLayout('ajax');
        

        $this->loadModel('TourismSocietyTypes');   
        $tourismsocietytypes =$this->TourismSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('tourismsocietytypes',$tourismsocietytypes);

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,14]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $this->loadMOdel('CooperativeSocietyBanks');
        $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
        $this->set('list_banks', $list_banks);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    public function pJute()
    {
        $this->viewBuilder()->setLayout('ajax');
        

        $this->loadModel('TourismSocietyTypes');   
        $tourismsocietytypes =$this->TourismSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('tourismsocietytypes',$tourismsocietytypes);

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $jfacilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,90]])->order(['orderseq'=>'ASC']);
        $this->set('jfacilities',$jfacilities);

        $this->loadMOdel('CooperativeSocietyBanks');
        $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
        $this->set('list_banks', $list_banks);
        
        $this->loadMOdel('TypeRawMaterial');
        $type_jraw=$this->TypeRawMaterial->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>90]);
        $this->set('type_jraw', $type_jraw);

        $this->loadMOdel('ProductProduceBySociety');  
        $productproduce=$this->ProductProduceBySociety->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>90]);
        $this->set('productproduce', $productproduce);  



        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }
    public function pSericulture()
    {
        $this->viewBuilder()->setLayout('ajax');
        

        $this->loadModel('SerticultureSilkwormTypes');   
        $sertisocietytypes =$this->SerticultureSilkwormTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('sertisocietytypes',$sertisocietytypes);

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $sfacilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,96]])->order(['orderseq'=>'ASC']);
        $this->set('sfacilities',$sfacilities);

        $this->loadMOdel('CooperativeSocietyBanks');
        $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
        $this->set('list_banks', $list_banks);
        
        $this->loadMOdel('TypeRawMaterial');
        $type_jraw=$this->TypeRawMaterial->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>90]);
        $this->set('type_jraw', $type_jraw);

        $this->loadMOdel('ProductProduceBySociety');  
        $productproduce=$this->ProductProduceBySociety->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>90]);
        $this->set('productproduce', $productproduce);  



        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    // public function pWomen()
    // {
    //     $this->viewBuilder()->setLayout('ajax');
        

        // $this->loadModel('TypeWomenCooperatives');   
        // $womensocietytypes =$this->TypeWomenCooperatives->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        // $this->set('womensocietytypes',$womensocietytypes);

    //     $this->loadModel('OfficeBuildingTypes');
    //     $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
    //     $this->set('buildingTypes',$buildingTypes);

    //     $this->loadModel('CooperativeSocietyFacilities');
    //     $sfacilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,15]])->order(['orderseq'=>'ASC']);
    //     $this->set('sfacilities',$sfacilities);

    //     $this->loadMOdel('CooperativeSocietyBanks');
    //     $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
    //     $this->set('list_banks', $list_banks);
        
    //     $CooperativeRegistration=$this->request->data['cs'];
    //     $this->set('CooperativeRegistration',$CooperativeRegistration);
    // }
    public function pLivestock()
    {
        $this->viewBuilder()->setLayout('ajax');
        

        $this->loadModel('LivestockSocietyTypes');   
        $livesocietytypes =$this->LivestockSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('livesocietytypes',$livesocietytypes);
        $this->loadMOdel('LivestockPrimaryActivity');
        $type_lpa=$this->LivestockPrimaryActivity->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        $this->set('type_lpa', $type_lpa);

        $this->loadMOdel('ProductProduceBySociety');  
        $lproductproduce=$this->ProductProduceBySociety->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>54]);
        $this->set('lproductproduce', $lproductproduce); 


        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $lfacilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,54]])->order(['orderseq'=>'ASC']);
        $this->set('lfacilities',$lfacilities);

        $this->loadMOdel('CooperativeSocietyBanks');
        $list_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type'=> 2])->order(['orderseq'=>'ASC']);
        $this->set('list_banks', $list_banks);
        
         



        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    public function pMarketing()
    {
        $this->viewBuilder()->setLayout('ajax');

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }
    public function cThrift()
    {
        $this->viewBuilder()->setLayout('ajax');

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,18]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }
    public function pConsumer()
    {
        $this->viewBuilder()->setLayout('ajax');

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,80]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);
        $this->loadModel('HousingSocietyTypes');
        $societytypes =$this->HousingSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('societytypes',$societytypes);
        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    public function processing()
    {
        $this->viewBuilder()->setLayout('ajax');

        $this->loadModel('HousingSocietyTypes');
        $societytypes =$this->HousingSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('societytypes',$societytypes);

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,47]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    public function getBank()
    {
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax'))
        {
            $bank_type = [];
            $bank_type = $this->request->data('bank_type');    
            if(!empty($bank_type))
            {
                $bank_type[] = 0;
            } else {
                $bank_type[] = '-1';
            }
            
            //print_r($bank_type);die;
            //echo $operation_area_location;die;
            $this->loadMOdel('CooperativeSocietyBanks');
            $arr_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type IN'=>$bank_type])->order(['orderseq'=>'ASC']);
            

            // echo '<pre>';
            // print_r($arr_banks);die;
            //$option_html='<option value="">Select</option>';
            $option_html='';
            
            if($arr_banks->count()>0){
                foreach($arr_banks as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.$value.'</option>';
                }
            }
            echo $option_html;
        }
        exit;
    }


    public function editLog($CooperativeRegistration)
    {
        
        //insert cooperative registration table data in log table
        $this->loadMOdel('CooperativeRegistrationsLogs');

        $cr_data = [
            'cooperative_registration_id' => $CooperativeRegistration->id,
            'cooperative_society_name' => $CooperativeRegistration->cooperative_society_name,
            'cooperative_id' => $CooperativeRegistration->cooperative_id,
            'reference_year' => $CooperativeRegistration->reference_year,
            'date_registration' => $CooperativeRegistration->date_registration,
            'registration_number' => $CooperativeRegistration->registration_number,
            'is_multi_state' => $CooperativeRegistration->is_multi_state,
            'area_of_operation_id' => $CooperativeRegistration->area_of_operation_id,
            'water_body_type_id' => $CooperativeRegistration->water_body_type_id,
            'sector_of_operation_type' => $CooperativeRegistration->sector_of_operation_type,
            'sector_of_operation' => $CooperativeRegistration->sector_of_operation,
            'sector_of_operation_other' => $CooperativeRegistration->sector_of_operation_other,
            'secondary_activity' => $CooperativeRegistration->secondary_activity,
            'secondary_activity_other' => $CooperativeRegistration->secondary_activity_other,
            'functional_status' => $CooperativeRegistration->functional_status,
            'location_details_of_head_quarter' => $CooperativeRegistration->location_details_of_head_quarter,
            'location_of_head_quarter' => $CooperativeRegistration->location_of_head_quarter,
            'state_code' => $CooperativeRegistration->state_code,
            'district_code' => $CooperativeRegistration->district_code,
            'block_code' => $CooperativeRegistration->block_code,
            'gram_panchayat_code' => $CooperativeRegistration->gram_panchayat_code,
            'is_coastal' => $CooperativeRegistration->is_coastal,
            'village_code' => $CooperativeRegistration->village_code,
            'urban_local_body_type_code' => $CooperativeRegistration->urban_local_body_type_code,
            'urban_local_body_code' => $CooperativeRegistration->urban_local_body_code,
            'locality_ward_code' => $CooperativeRegistration->locality_ward_code,
            'pincode' => $CooperativeRegistration->pincode,
            'full_address' => $CooperativeRegistration->full_address,
            'subdistrict_code' => $CooperativeRegistration->subdistrict_code,
            'sector_code' => $CooperativeRegistration->sector_code,
            'contact_person' => $CooperativeRegistration->contact_person,
            'designation' => $CooperativeRegistration->designation,
            'designation_other' => $CooperativeRegistration->designation_other,
            'mobile' => $CooperativeRegistration->mobile,
            'landline' => $CooperativeRegistration->landline,
            'email' => $CooperativeRegistration->email,
            'is_affiliated_union_federation' => $CooperativeRegistration->is_affiliated_union_federation,
            'affiliated_union_federation_level' => $CooperativeRegistration->affiliated_union_federation_level,
            'affiliated_union_federation_name' => $CooperativeRegistration->affiliated_union_federation_name,
            'affiliated_union_federation_other' => $CooperativeRegistration->affiliated_union_federation_other,
            'members_of_society' => $CooperativeRegistration->members_of_society,
            'financial_audit' => $CooperativeRegistration->financial_audit,
            'audit_complete_year' => $CooperativeRegistration->audit_complete_year,
            'category_audit' => $CooperativeRegistration->category_audit,
            'is_profit_making' => $CooperativeRegistration->is_profit_making,
            'annual_turnover' => $CooperativeRegistration->annual_turnover,
            'annual_loss' => $CooperativeRegistration->annual_loss,
            'is_dividend_paid' => $CooperativeRegistration->is_dividend_paid,
            'dividend_rate' => $CooperativeRegistration->dividend_rate,
            'operation_area_location' => $CooperativeRegistration->operation_area_location,
            'is_draft' => $CooperativeRegistration->is_draft,
            'created_by' => $CooperativeRegistration->created_by,
            'created' => $CooperativeRegistration->created,
            'updated_by' => $CooperativeRegistration->updated_by,
            'updated' => $CooperativeRegistration->updated,
            'flag_name' => $CooperativeRegistration->flag_name,
            'is_approved' => $CooperativeRegistration->is_approved,
            'remark' => $CooperativeRegistration->remark,
            'approved_by' => $CooperativeRegistration->approved_by,
            'status' => $CooperativeRegistration->status,
            'cooperative_id_num' => $CooperativeRegistration->cooperative_id_num,
            'operational_district_code' => $CooperativeRegistration->operational_district_code,
            'under_computerised_scheme' => $CooperativeRegistration->under_computerised_scheme,
            'bank_type' => $CooperativeRegistration->bank_type,
            'cooperative_society_bank_id' => $CooperativeRegistration->cooperative_society_bank_id,
            'other_bank' => $CooperativeRegistration->other_bank,
            'logged_at' => date('Y-m-d H:i:s')
        ];

        $l_cooperativeRegistration = $this->CooperativeRegistrationsLogs->newEntity();
        
        $log_CooperativeRegistration = $this->CooperativeRegistrationsLogs->patchEntity($l_cooperativeRegistration, $cr_data);

        if($this->CooperativeRegistrationsLogs->save($log_CooperativeRegistration))
        {

            //=======================================================

            //insert multiple urban data if exist
            $sectorurbans_data = $this->AreaOfOperationLevelUrban->find('all')->where(['cooperative_registrations_id'=>$CooperativeRegistration->id])->group(['row_id'])->order(['row_id'=>'ASC'])->toArray();

            if(count($sectorurbans_data) > 0)
            {
                $this->loadMOdel('AreaOfOperationLevelUrbanLogs');

                foreach($sectorurbans_data as $s_key => $urban_row)
                {      
                    $AreaOfOperationLevelUrbanLogs = $this->AreaOfOperationLevelUrbanLogs->newEntity();
                    
                        $urban_data = [
                            'cooperative_registrations_log_id'=> $log_CooperativeRegistration->id,
                            'row_id'                        =>  $s_key,
                            'cooperative_registrations_id'  =>  $urban_row->cooperative_registrations_id,
                            'area_of_operation_id'          =>  $urban_row->area_of_operation_id,
                            'state_code'                    =>  $urban_row->state_code,
                            'district_code'                 =>  $urban_row->district_code,
                            'local_body_type_code'          =>  $urban_row->local_body_type_code,
                            'local_body_code'               =>  $urban_row->local_body_code,
                            'locality_ward_code'            =>  $urban_row->locality_ward_code,
                            'created_at'                    =>  $urban_row->created_at,
                            'logged_at'                     =>   date('Y-m-d H:i:s')
                        ];

                    $AreaOfOperationLevelUrbanLogs = $this->AreaOfOperationLevelUrbanLogs->patchEntity($AreaOfOperationLevelUrbanLogs, $urban_data);

                    $this->AreaOfOperationLevelUrbanLogs->save($AreaOfOperationLevelUrbanLogs);
                    
                }  
            }

            //==============================================================

            //insert multiple rural data if not exist
            if(isset($CooperativeRegistration->area_of_operation_level) && !empty($CooperativeRegistration->area_of_operation_level))
            {
                $this->loadMOdel('AreaOfOperationLevelLogs');
                foreach($CooperativeRegistration->area_of_operation_level as $rural_data)
                {
                    $AreaOfOperationLevelLogs = $this->AreaOfOperationLevelLogs->newEntity();
                    $area_data = [
                        'cooperative_registrations_log_id'=> $log_CooperativeRegistration->id,
                        'row_id'                        =>  $rural_data->row_id,
                        'cooperative_registrations_id'  =>  $rural_data->cooperative_registrations_id,
                        'area_of_operation_id'          =>  $rural_data->area_of_operation_id,
                        'state_code'                    =>  $rural_data->state_code,
                        'district_code'                 =>  $rural_data->district_code,
                        'block_code'                    =>  $rural_data->block_code,
                        'panchayat_code'                =>  $rural_data->panchayat_code,
                        'village_code'                  =>  $rural_data->village_code,
                        'gp_village_all'                =>  $rural_data->gp_village_all,
                        'created_at'                    =>  $rural_data->created_at,
                        'logged_at'                     =>   date('Y-m-d H:i:s')
                    ];
                    $AreaOfOperationLevelLogs = $this->AreaOfOperationLevelLogs->patchEntity($AreaOfOperationLevelLogs, $area_data);

                    $this->AreaOfOperationLevelLogs->save($AreaOfOperationLevelLogs);
                }
            }
                
            $this->CooperativeRegistrationsLogs->save($log_CooperativeRegistration); 
        
            //===================================================================================
            

            //insert pacs data if exist
            $arr_pacs = [1,20,22];
            if(in_array($CooperativeRegistration->sector_of_operation, $arr_pacs))
            {
                $this->loadMOdel('CooperativeRegistrationPacsLogs');
                
                $id = $CooperativeRegistration['cooperative_registration_pacs'][0]->id;
                $pacs = json_decode(json_encode($CooperativeRegistration['cooperative_registration_pacs'][0]),true);

                $pacs['cooperative_registrations_log_id'] = $log_CooperativeRegistration->id;
                $pacs['cooperative_registration_pacs_id'] = $id;
                $pacs['logged_at'] = date('Y-m-d H:i:s');
                unset($pacs['id']);

                $CooperativeRegistrationPacsLogs = $this->CooperativeRegistrationPacsLogs->newEntity();

                $CooperativeRegistrationPacsLogs = $this->CooperativeRegistrationPacsLogs->patchEntity($CooperativeRegistrationPacsLogs, $pacs);

                $this->CooperativeRegistrationPacsLogs->save($CooperativeRegistrationPacsLogs);

                if($pacs['is_socitey_has_land'] == 1)
                {
                    $this->loadMOdel('CooperativeRegistrationsLandsLogs');

                    $pacs_land_id = $CooperativeRegistration['cooperative_registrations_land']->id;
                    $pacs_land_data = json_decode(json_encode($CooperativeRegistration['cooperative_registrations_land']),true);

                    $pacs_land_data['cooperative_registrations_log_id'] = $log_CooperativeRegistration->id;
                    $pacs_land_data['cooperative_registrations_lands_id'] = $pacs_land_id;
                    $pacs_land_data['logged_at'] = date('Y-m-d H:i:s');
                    unset($pacs_land_data['id']);
                    
                    $CooperativeRegistrationsLandsLogs = $this->CooperativeRegistrationsLandsLogs->newEntity();

                    $CooperativeRegistrationsLandsLogs = $this->CooperativeRegistrationsLandsLogs->patchEntity($CooperativeRegistrationsLandsLogs, $pacs_land_data);

                    $this->CooperativeRegistrationsLandsLogs->save($CooperativeRegistrationsLandsLogs);
                    
                }
            }
            
            
            //============================================================
            //insert dairy data if exist
            if($CooperativeRegistration->sector_of_operation == 9)
            {
                $this->loadMOdel('CooperativeRegistrationDairyLogs');
                $id = $CooperativeRegistration['cooperative_registration_dairy'][0]->id;
                $dairy = json_decode(json_encode($CooperativeRegistration['cooperative_registration_dairy'][0]),true);

                $dairy['cooperative_registrations_log_id'] = $log_CooperativeRegistration->id;
                $dairy['cooperative_registration_dairy_id'] = $id;
                $dairy['logged_at'] = date('Y-m-d H:i:s');
                unset($dairy['id']);

                $CooperativeRegistrationDairyLogs = $this->CooperativeRegistrationDairyLogs->newEntity();

                $CooperativeRegistrationDairyLogs = $this->CooperativeRegistrationDairyLogs->patchEntity($CooperativeRegistrationDairyLogs, $dairy);

                $this->CooperativeRegistrationDairyLogs->save($CooperativeRegistrationDairyLogs);

                
            }

            //============================================================
            //insert fishery data if exist
            if($CooperativeRegistration->sector_of_operation == 10)
            {
                $this->loadMOdel('CooperativeRegistrationFisheryLogs');

                $id = $CooperativeRegistration['cooperative_registration_fishery'][0]->id;
                
                $fishery = json_decode(json_encode($CooperativeRegistration['cooperative_registration_fishery'][0]),true);

                $fishery['cooperative_registrations_log_id'] = $log_CooperativeRegistration->id;
                $fishery['cooperative_registration_fishery_id'] = $id;
                $fishery['logged_at'] = date('Y-m-d H:i:s');
                unset($fishery['id']);

                $CooperativeRegistrationFisheryLogs = $this->CooperativeRegistrationFisheryLogs->newEntity();

                $CooperativeRegistrationFisheryLogs = $this->CooperativeRegistrationFisheryLogs->patchEntity($CooperativeRegistrationFisheryLogs, $fishery);

                $this->CooperativeRegistrationFisheryLogs->save($CooperativeRegistrationFisheryLogs);
            }
        }

    }

    public function logDetail($id)
    {
        $this->loadModel('CooperativeRegistrationsLogs');

        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $this->Flash->error(__('You are not authorized.'));
            return $this->redirect(['action' => 'dataEntryPending']);    
            
        }

        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');


        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {
            $societyName = trim($this->request->query['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "CooperativeRegistrationsLogs.cooperative_society_name like '%" . $societyName . "%'";
        }

        if (!empty($this->request->query['registration_number'])) {
            $registrationNumber = trim($this->request->query['registration_number']);
            $this->set('registrationNumber', $registrationNumber);
            $search_condition[] = "CooperativeRegistrationsLogs.registration_number like '%" . $registrationNumber . "%'";
        }

        if (!empty($this->request->query['cooperative_id'])) {
            $cooperativeId = trim($this->request->query['cooperative_id']);
            $search_condition[] = "CooperativeRegistrationsLogs.cooperative_id_num like '%" . (int)substr($cooperativeId, 2) . "%'";
            $this->set('cooperativeId', $cooperativeId);
        }

        if (!empty($this->request->query['reference_year'])) {
            $referenceYear = trim($this->request->query['reference_year']);
            $this->set('referenceYear', $referenceYear);
            $search_condition[] = "CooperativeRegistrationsLogs.reference_year like '%" . $referenceYear . "%'";
        }

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $state = trim($this->request->query['state']);
            $this->set('state', $state);
            $search_condition[] = "CooperativeRegistrationsLogs.state_code = '" . $state . "'";
        }

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $location = trim($this->request->query['location']);
            $this->set('location', $location);
            $search_condition[] = "CooperativeRegistrationsLogs.location_of_head_quarter = '" . $location . "'";
        }

        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='') {
            $s_sector_operation = trim($this->request->query['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "CooperativeRegistrationsLogs.sector_of_operation_type = '" . $s_sector_operation . "'";
        }

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);
            $search_condition[] = "CooperativeRegistrationsLogs.sector_of_operation = '" . $s_primary_activity . "'";
        }

        if (isset($this->request->query['secondary_activity']) && $this->request->query['secondary_activity'] !='') {
            $s_secondary_activity = trim($this->request->query['secondary_activity']);
            $this->set('s_secondary_activity', $s_secondary_activity);
            $search_condition[] = "CooperativeRegistrationsLogs.secondary_activity = '" . $s_secondary_activity . "'";
        }

         //=============================Urban=====================================

         if (isset($this->request->query['local_category']) && $this->request->query['local_category'] !='') {
            $s_local_category = trim($this->request->query['local_category']);
            $this->set('s_local_category', $s_local_category);
            $search_condition[] = "CooperativeRegistrationsLogs.urban_local_body_type_code = '" . $s_local_category . "'";
        }

        if (isset($this->request->query['local_body']) && $this->request->query['local_body'] !='') {
            $s_local_body = trim($this->request->query['local_body']);
            $this->set('s_local_body', $s_local_body);
            $search_condition[] = "CooperativeRegistrationsLogs.urban_local_body_code = '" . $s_local_body . "'";
        }

        if (isset($this->request->query['ward']) && $this->request->query['ward'] !='') {
            $s_ward = trim($this->request->query['ward']);
            $this->set('s_ward', $s_ward);
            $search_condition[] = "CooperativeRegistrationsLogs.locality_ward_code = '" . $s_ward . "'";
        }

        //=============================Urban=====================================

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "CooperativeRegistrationsLogs.district_code = '" . $s_district . "'";
        }

        if (isset($this->request->query['block']) && $this->request->query['block'] !='') {
            $s_block = trim($this->request->query['block']);
            $this->set('s_block', $s_block);
            $search_condition[] = "CooperativeRegistrationsLogs.block_code = '" . $s_block . "'";
        }

        if (isset($this->request->query['panchayat']) && $this->request->query['panchayat'] !='') {
            $s_panchayat = trim($this->request->query['panchayat']);
            $this->set('s_panchayat', $s_panchayat);
            $search_condition[] = "CooperativeRegistrationsLogs.gram_panchayat_code = '" . $s_panchayat . "'";
        }

        if (isset($this->request->query['village']) && $this->request->query['village'] !='') {
            $s_village = trim($this->request->query['village']);
            $this->set('s_village', $s_village);
            $search_condition[] = "CooperativeRegistrationsLogs.village_code = '" . $s_village . "'";
        }

        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            $nodal_data_entry_ids = [];
            $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();
            
            if(!empty($nodal_data_entry_ids)){
                $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
                $nodal_data_entry_ids=implode(",",$nodal_data_entry_ids);
                
                $search_condition2 = "CooperativeRegistrationsLogs.created_by IN (" . $nodal_data_entry_ids . ")";
            } else{
                $search_condition2 = "CooperativeRegistrationsLogs.created_by IN (0)";
            }
        }

        $search_condition3='';
         if($this->request->session()->read('Auth.User.role_id') == 11)
        {
           $state= $this->request->session()->read('Auth.User.state_code');

          
             $search_condition3  = "CooperativeRegistrationsLogs.state_code = '" . $state . "'";

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

        $registrationQuery = $this->CooperativeRegistrationsLogs->find('all', [
            'order' => ['CooperativeRegistrationsLogs.logged_at' => 'DESC'],
            'conditions' => [$searchString,$search_condition2,$search_condition3],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrationsLogs.is_draft'=>0,'CooperativeRegistrationsLogs.status'=>1,'CooperativeRegistrationsLogs.cooperative_registration_id'=>$id]);

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

        $this->set(compact('CooperativeRegistrations','id'));

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

        // if(!empty($this->request->query['sector_operation']))
        // {
        //     //for credit
        //     $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$this->request->query['sector_operation'],'status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        // }
       
        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('primary_activities',$primary_activities);


        // Start SecondaryActivities for dropdown
        $this->loadModel('SecondaryActivities');

        $secondary_activities = $this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $this->set('secondary_activities',$secondary_activities);

       //======================urban================================================

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 1)
       {
           $this->loadMOdel('UrbanLocalBodies');

           $local_categories = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$this->request->query['state']])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();
   
           $this->set('local_categories',$local_categories);
       }
        
       if(!empty($this->request->query['local_category']) && $this->request->query['location'] == 1)
       {
           

           $localbodies = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$this->request->query['local_category'],'state_code'=>$this->request->query['state']])->order(['local_body_name'=>'ASC'])->toArray();
          
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

        if($this->request->session()->read('Auth.User.role_id') == 11)
        {
             $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1, 'id NOT IN'=>['20','22']])->toArray();
        }else
        {
             $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
        }

      
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);

         

/*

 mrig export excel code
       
        */

        // mrig start

$user_all=$this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
$this->set('user_all', $user_all);

       if($this->request->is('get')){
           
           if(!empty($this->request->query['export_excel']))
           { 
               
               $fileName = "history_-".date("d-m-y:h:s").".xls";
               $headerRow = array("S.No", "Cooperative Society Name","Location","State", "District/Urban Local Body","Sector Name","Registration Number","Cooperative ID","Created By","Reference Year","Date of Registration","Updated At");
               $data = array();

               $registrationQuery = $registrationQuery->toArray();

            
               foreach($registrationQuery as $key => $rows){
                   
                   if(empty($rows['district_code'])){
                       $district_or_urban_local_body_code = $rows['urban_local_body_code'];
                   }else{
                       $district_or_urban_local_body_code = $rows['district_code'];
                   }
                   $time =$CooperativeRegistration->date_registration;
                   
             
                  $data[]=array(($key+1), $rows['cooperative_society_name'], $arr_location[$rows['location_of_head_quarter']],$stateOption[$rows['state_code']],$arr_districts[$district_or_urban_local_body_code],  $sectors[$rows['sector_of_operation']],$rows['registration_number'],str_pad($rows['sector_of_operation'], 2, "0", STR_PAD_LEFT) . str_pad($rows['cooperative_id_num'], 7, "0", STR_PAD_LEFT),$user_all[$rows['created_by']],$rows['reference_year'],date_format($rows['date_registration'],"d/m/Y"),date_format($rows['logged_at'],"d/m/Y h:i:s"));
               }
            // echo "<pre>";
            // print_r($data);die;
               
               $this->exportInExcel($fileName, $headerRow, $data);

           }
          
       }

// mrig end

    }

    public function logView($id = null)
    {
        $this->loadMOdel('CooperativeRegistrationsLogs');
        $CooperativeRegistration = $this->CooperativeRegistrationsLogs->get($id, [
           'contain' => ['CooperativeRegistrationsLandsLogs','CooperativeRegistrationPacsLogs'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationDairyLogs'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationFisheryLogs'=>['sort'=>['id'=>'desc']]]
        ]);
        //print_r($CooperativeRegistration->cooperative_registrations_lands_log->land_owned);die;
         //->toArray();
            //'contain' => ['AreaOfOperationLevelUrbanLogs','CooperativeRegistrationsContactNumbers','CooperativeRegistrationsEmails','CooperativeRegistrationPacsLogs'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationDairyLogs'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationFisheryLogs'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationsLandsLogs']

        // echo "<pre>";
        // print_r($CooperativeRegistration['cooperative_registrations_lands_log']['land_owned']);
        //echo $CooperativeRegistration->cooperative_registrations_land->land_owned; die;
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
            $dbgv = $this->DistrictsBlocksGpVillages->find('all')->where(['status'=>1,'state_code'=>$CooperativeRegistration->state_code,'village_code'=>$CooperativeRegistration->village_code])->first(); 

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
            $this->loadModel('AreaOfOperationLevelLogs');
            $area_operation_level=$this->AreaOfOperationLevelLogs->find('all')->where(['cooperative_registrations_id'=>$CooperativeRegistration->cooperative_registration_id,'cooperative_registrations_log_id'=>$CooperativeRegistration->id])->order(['id'=>'ASC'])->toArray();
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
          $this->loadModel('AreaOfOperationLevelUrbanLogs');
          $area_operation_level_urban_row = $this->AreaOfOperationLevelUrbanLogs->find('all')->where(['cooperative_registrations_id'=>$CooperativeRegistration->cooperative_registration_id,'cooperative_registrations_log_id'=>$CooperativeRegistration->id])->order(['id'=>'ASC'])->group(['row_id'])->toArray();

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

              $area_operation_level_row=$this->AreaOfOperationLevelLogs->find('all')->where(['cooperative_registrations_id'=>$CooperativeRegistration->cooperative_registration_id,'cooperative_registrations_log_id'=>$CooperativeRegistration->id])->order(['id'=>'ASC'])->group(['row_id'])->toArray();
              $this->set('area_operation_level_row',$area_operation_level_row);

              $area_of_operation_id_rural = '';

           //   echo "<pre>";
           // print_r($area_operation_level_row);
              $area_operation_level_row_v_1=array();
              $area_operation_level_row_all_gp = array();
            foreach($area_operation_level_row as $key => $value123)
            {
                $area_operation_level_row_v=$this->AreaOfOperationLevelLogs->find('all')->where(['cooperative_registrations_id'=>$CooperativeRegistration->cooperative_registration_id,'cooperative_registrations_log_id'=>$CooperativeRegistration->id,'row_id'=>$value123['row_id']])->order(['id'=>'ASC'])->toArray();

                foreach($area_operation_level_row_v as $key1=>$value1)
                {
                    $area_of_operation_id_rural = $value1['area_of_operation_id'];
                   
                    $area_operation_level_row_v_1[$value123['row_id']][]=$value1['village_code'] ;

                    $area_operation_level_row_all_gp[$value123['row_id']][]=$value1['panchayat_code'];
                }

                $area_operation_level_row_all_gp[$value123['row_id']] = array_unique($area_operation_level_row_all_gp[$value123['row_id']]);
            }

            $this->set('area_operation_level_row_all_gp',$area_operation_level_row_all_gp);

             $this->set('area_operation_level_row_v_1',$area_operation_level_row_v_1);
         
            if(!empty($statelevelarray))
            {
                $this->loadMOdel('DistrictsBlocksGpVillages');
                $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'state_code IN'=>$statelevelarray])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toarray();  
                $this->set('gps',$gps); 

                
                $gpsv=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'state_code IN'=>$statelevelarray])->group(['village_code'])->order(['village_name'=>'ASC'])->toarray();  
                $this->set('gpsv',$gpsv); 

                $gp_code_name = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'state_code IN'=>$statelevelarray])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toarray();  

                $this->set('gp_code_name',$gp_code_name);
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
                
               $this->loadModel('HousingSocietyTypes');  
               $societytypes =$this->HousingSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
                $this->set('societytypes',$societytypes);

                $this->loadModel('LabourSocietyTypes');
                $lsocietytypes =$this->LabourSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
                $this->set('lsocietytypes',$lsocietytypes);
        
                $this->loadModel('TransportSocietyTypes');
        $trsocietytypes =$this->TransportSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('trsocietytypes',$trsocietytypes);

        $this->loadModel('EducationSocietyTypes');   
        $educationsocietytypes =$this->EducationSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('educationsocietytypes',$educationsocietytypes);

        $this->loadModel('LevelAndDurationCourses');   
        $levelofcourses =$this->LevelAndDurationCourses->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type' => 1])->toArray();
        $this->set('levelofcourses',$levelofcourses);
        $durationofcourses =$this->LevelAndDurationCourses->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type' => 2])->toArray();
        $this->set('durationofcourses',$durationofcourses);
        $leveldurationofcourses =$this->LevelAndDurationCourses->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
        $this->set('leveldurationofcourses',$leveldurationofcourses);
                

                 $this->loadModel('AreaOfOperations');
                $areaOfOperations=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC'])->toArray();


                $this->loadModel('CooperativeSocietyFacilities');
               $societyFacilities=$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();
               $this->set('societyFacilities', $societyFacilities);

               $this->loadModel('CooperativeSocietyBanks');
               
               $CooperativeRegistration['bank_type'] = explode(',',$CooperativeRegistration['bank_type']);
               $CooperativeRegistration->cooperative_society_bank_id = explode(',',$CooperativeRegistration->cooperative_society_bank_id);

        
                $b_type = array_merge($CooperativeRegistration['bank_type'],[0]);

                $arr_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type IN'=>$b_type])->order(['orderseq'=>'ASC'])->toArray();
                

        $this->set(compact('creditPrimaryActivities','presentFunctionalStatus','locationOfHeadquarter','blockName','districtName','panchayatName','villageName','nonCreditPrimaryActivities','area_of_operation_id_rural','area_of_operation_id_urban','areaOfOperations','arr_banks','id'));
    }

    public function getRegistrationAuthority(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $sector_of_operation_type=$this->request->data('sector_of_operation_type'); 

            if($sector_of_operation_type == 20 || $sector_of_operation_type == 22)
            {
                $sector_of_operation_type = 1;
            }

            $this->loadMOdel('RegistrationAuthorities');

            $Authorities=$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,'primary_activity IN'=>[0,$sector_of_operation_type]])->order(['authority_name'=>'ASC']);
            $option_html='<option value="">Select</option>';
            if($Authorities->count()>0){
                foreach($Authorities as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.$value.'</option>';
                }
            }
            echo $option_html;
        }
        exit;
    }

    public function pSugar()
    {
        $this->viewBuilder()->setLayout('ajax');

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        $CooperativeRegistration=$this->request->data['cs'];
        $this->set('CooperativeRegistration',$CooperativeRegistration);
    }

    // public function getIndustry()
    // {
    //     $sector_of_operation = $this->request->data['sector_of_operation'];
    //     $this->loadModel('IndustryType');
    // }
    
}