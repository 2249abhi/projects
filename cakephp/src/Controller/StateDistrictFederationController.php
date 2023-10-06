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
class StateDistrictFederationController extends AppController
{

    public function initialize()
    {   
        $this->loadModel('CooperativeRegistrations');
        parent::initialize();
        $this->loadModel('Users');
        $user_all=$this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
        $this->set('user_all', $user_all);
       
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['getDistricts','fedCode','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','getdccb','getfederationlevel','approval','bulkapproval','getUrbanRural','generateUniqueNumber','getBank','getRegistrationAuthority']);
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
           $state= $this->request->session()->read('Auth.User.state_code');

          
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
            ])->where(['is_draft'=>0,'status'=>1]);

            
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
            ])->where(['is_draft'=>0,'status'=>1]);
     
              
                 $queryExport->hydrate(false);
                 $ExportResultData = $queryExport->toArray();
                 $fileName = "report".date("d-m-y:h:s").".xls";
                 
   
                 $headerRow = array("S.No", "cooperative_society_name","Location","State","district/urban_local_body","sector","Registration Number","Cooperative ID","Reference Year","Date of Registration");
                 $data = array();
                 $i=1;
                
                
   
                  $state_name =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
                  $sOption = $state_name->toArray();
               
   
                 foreach($ExportResultData As $rows){
                   
                      // print_r($ExportResultData);
                    //    $rows;
                    //    echo $all_usern4[$all_usern2[$rows['user_payment']['updated_by']]];
                
                $data[] = [$i, $rows['cooperative_society_name'],$arr_location[$rows['location_of_head_quarter']],$sOption[$rows['state_code']],$sectors[$rows['sector_of_operation']],$rows['registration_number'],$rows['cooperative_id'],$rows['reference_year'],date('d/m/Y',strtotime($rows['date_registration']))];
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

    public function showMember()
    {
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){

            $this->loadModel('StateDistrictFederations');
            $this->loadModel('SdFederationMembers');
            
            $curr_id = $this->request->data('curr_id'); 

                $member_count = $this->SdFederationMembers->find('list',['keyField'=>'id','valueField'=>'cooperative_registration_id'])->where(['state_district_federation_id'=>$curr_id])->count();

                //================================================

                $sd_federation_members=$this->SdFederationMembers->find('list',['keyField'=>'id','valueField'=>'cooperative_registration_id'])->where(['state_district_federation_id'=>$curr_id])->toArray();

                $this->loadModel('Districts');
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')])->order(['name'=>'ASC'])->toArray();
                // echo "<pre>";
                // print_r($cooperative_society_name_arr);

                $this->loadModel('PrimaryActivities');
                $PrimaryActivitiesTotal=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
        
                $cooperative_society_name_arr = [];
                $x = '';
                $slNo = 1;
                $fed_society = array_values($sd_federation_members);
                
                    $cooperative_society_name_arr=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'is_draft'=>0,'is_approved !='=>2,'state_code'=>$this->request->session()->read('Auth.User.state_code'),'id IN'=>$fed_society])->toArray();
                    foreach($cooperative_society_name_arr as $key=>$value)
                    {

                      $x .= '<tr>
                       <td>'.$slNo.'</td>
                       <br>
                       <td>'.$value->cooperative_society_name.'</td>
                       <br>
                       <td>'.$PrimaryActivitiesTotal[$value->sector_of_operation].'</td>
                       
                       <br>
                       <td>'.$districts[$value->district_code].'</td>
                       <br>
                       <td>'.$value->registration_number.'</td>
                       <br>
                       <td>'.$value->date_registration.'</td>
                      <td> <input type="checkbox" checked class="member-checked" name="sd_federation_members['.$key.']" disabled value="'.$value->id.'" > </td>
                       </tr>';

                       $slNo ++; 
                    }

                //=====================================================

                $cooperative_society_name_arr=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'is_draft'=>0,'is_approved !='=>2,'id IN'=>$curr_id])->toArray();

                $res = [];
                $res['count'] = $member_count;
                $res['table'] = $x;

                $result = json_encode($res);
                $this->response->type('json');
                $this->response->body($result);
                return $this->response;
            
        }
        exit;
    }

    public function addMember()
    {
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){

            $this->loadModel('StateDistrictFederations');
            $this->loadModel('SdFederationMembers');

            
            $data['state_code'] = $this->request->session()->read('Auth.User.state_code');

            $members = $this->request->data('members'); 
            $curr_id = $this->request->data('curr_id'); 
            $sector = $this->request->data('sector');
            $district = $this->request->data('district_code');
            
            if($curr_id == 0)
            {
                $StateDistrictFederation = $this->StateDistrictFederations->newEntity();   

                $data['is_approved'] = 0;
                $data['approved_by'] = '';
                $data['status'] = 1;
                $data['is_draft'] = 1;
                $data['created_by'] = $this->request->session()->read('Auth.User.id');
                $data['created'] = date('Y-m-d H:i:s');
                $data['updated'] = date('Y-m-d H:i:s');

                $StateDistrictFederation = $this->StateDistrictFederations->patchEntity($StateDistrictFederation, $data,['associated'=>['SdFederationOtherMembers']]);
                $this->StateDistrictFederations->save($StateDistrictFederation);

                $curr_id = $StateDistrictFederation->id;

                
            } else {
                $StateDistrictFederation = $this->StateDistrictFederations->get($curr_id); 
            }
            // print_r($members);die;
            
                if(!empty($members))
                {
                    if($this->request->session()->read('Auth.User.role_id') == 11)
                    {
                        $this->SdFederationMembers->deleteAll(['state_district_federation_id'=>$curr_id,'primary_activity'=>$sector]);
                        
                    } 
                    
                    if($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8)
                    {
                        $this->SdFederationMembers->deleteAll(['state_district_federation_id'=>$curr_id,'primary_activity'=>$sector,'district_code'=>$district]);
                    }
                    
                    
                    
                    
                   foreach($members as $key=>$coop_reg_id)
                   {
                        $SdFederationMember = $this->SdFederationMembers->newEntity();
                        $SdFederationMember->state_district_federation_id = $curr_id;
                        $SdFederationMember->cooperative_registration_id =  $coop_reg_id;
                        $SdFederationMember->district_code =  $district ?? '';
                        $SdFederationMember->primary_activity =  $sector;
                        $this->SdFederationMembers->save($SdFederationMember);
                    }
                }   


                $member_count = $this->SdFederationMembers->find('list',['keyField'=>'id','valueField'=>'cooperative_registration_id'])->where(['state_district_federation_id'=>$curr_id])->count();

                //================================================

                $sd_federation_members=$this->SdFederationMembers->find('list',['keyField'=>'id','valueField'=>'cooperative_registration_id'])->where(['state_district_federation_id'=>$curr_id])->toArray();

                $this->loadModel('Districts');
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')])->order(['name'=>'ASC'])->toArray();
                // echo "<pre>";
                // print_r($cooperative_society_name_arr);

                $this->loadModel('PrimaryActivities');
                $PrimaryActivitiesTotal=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
        
                $cooperative_society_name_arr = [];
                $x = '';
                $slNo = 1;
                $fed_society = array_values($sd_federation_members);
                
                    $cooperative_society_name_arr=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'is_draft'=>0,'is_approved !='=>2,'state_code'=>$this->request->session()->read('Auth.User.state_code'),'id IN'=>$fed_society])->toArray();
                    foreach($cooperative_society_name_arr as $key=>$value)
                    {

                      $x .= '<tr>
                       <td>'.$slNo.'</td>
                       <br>
                       <td>'.$value->cooperative_society_name.'</td>
                       <br>
                       <td>'.$PrimaryActivitiesTotal[$value->sector_of_operation].'</td>
                       
                       <br>
                       <td>'.$districts[$value->district_code].'</td>
                       <br>
                       <td>'.$value->registration_number.'</td>
                       <br>
                       <td>'.$value->date_registration.'</td>
                      <td> <input type="checkbox" checked class="member-checked" name="sd_federation_members['.$key.']" disabled value="'.$value->id.'" > </td>
                       </tr>';

                       $slNo ++; 
                    }

                //=====================================================

                $cooperative_society_name_arr=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'is_draft'=>0,'is_approved !='=>2,'id IN'=>$curr_id])->toArray();

                $res = [];

                $res['data_id'] = $curr_id;
                $res['count'] = $member_count;
                $res['table'] = $x;

                $result = json_encode($res);
                $this->response->type('json');
                $this->response->body($result);
                return $this->response;
            
        }
        exit;
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
    
        $this->loadModel('StateDistrictFederations');
        
        $years = [];
        $l_year = date('Y')-122;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
        }
        if($this->request->is('post')){

            $data=$this->request->getData();
            if(!empty($data['curr_id']))
            {
                $StateDistrictFederation = $this->StateDistrictFederations->get($data['curr_id']);
            } else {
                $StateDistrictFederation = $this->StateDistrictFederations->newEntity();
            }
            


            //array of pacs, dairy & fishery
            $arr_pdf = [1,20,22,9,10];
            if(!in_array($data['sector_of_operation'],$arr_pdf))
            {
                unset($data['pacs']);
                unset($data['area']);
                unset($data['sd_federation_dairy']);
                unset($data['sd_federation_fishery']);
            }

            $data['is_approved'] = 0;
            // $data['remark'] = '';
            $data['approved_by'] = '';
            $data['status'] = 1;
            $data['created'] = date('Y-m-d H:i:s');
            $data['updated'] = date('Y-m-d H:i:s');
			$data['operational_district_code'] = $data['district_code'] ?? 0;

            $data['cooperative_federation_name'] = trim($data['cooperative_federation_name']);
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
            
       
         
        
			
            if(!empty($data['landline']))
            {
                $std = $data['std'];
                $landline = $data['landline'];
                $data['landline'] = $std.'-'.$landline;
            } else {
                unset($data['std']);
            }
            $data['created_by'] = $this->request->session()->read('Auth.User.id');

            // always save using ajax
            unset($data['sd_federation_members']);
            // if($data['is_federation_member'] == 0)
            // {
            //    $data['federation_member_count'] = 0; 
            //    unset($data['sd_federation_members']);
            // }



            if($data['other_member'] == 0)
            {
               $data['other_member_count'] = 0; 
               unset($data['sd_federation_other_members']);
            }



        if(!empty($data['sd_federation_other_members']) && $data['other_member'] == 1)
                {
                

                foreach($data['sd_federation_other_members'] as $key=>$other_member_data)
                {   


                    if($other_member_data['type_membership'] == 2 || $other_member_data['type_membership'] == 3)
                    {
                        
                        
                        
                        $ext = pathinfo($other_member_data['member_document']['name'], PATHINFO_EXTENSION);


                        $other_member_data['member_document']['name'] = 'document_member_'.$this->request->session()->read('Auth.User.state_code').'_'.$key.'_'.date('Y_m_d_H_i_s').'.'.$ext;


                        if($other_member_data['member_document']['name']!=''){
                            $fileName = $this->uploadPdf('member_document', $other_member_data['member_document']);
                            $data['sd_federation_other_members'][$key]['member_document']  = $fileName['filename'];
                        }
                    } else {
                        $data['sd_federation_other_members'][$key]['member_document'] = '';
                    }
                }
            } 




           
			//if(!empty($data['federation_type']) &&  !empty($data['affiliated_union_federation_name']) && !empty($data['sector_of_operation_type']) &&  !empty($data['sector_of_operation']) && !empty($data['state_code']) &&  !empty($data['district_code'])){
                //$federation_code = $data['federation_type'].'-'.$data['affiliated_union_federation_name'].'-'.$data['sector_of_operation_type'].'-'.$data['sector_of_operation'].'-'.$data['state_code'].'-'.$data['district_code'];
                //$data['federation_code']=$federation_code;
           // }
            
            $data['federation_code']=$this->fedCode($data['sector_of_operation_type'],$data['sector_of_operation'],$data['federation_type'],$data['location_of_head_quarter'],$data['state_code'],$data['district_code'],$data['registration_number']) ;
            
           
            
            $data['date_registration'] = date("Y-m-d",strtotime(str_replace("/","-",$data['date_registration'])));

            // echo "<pre>";
            // print_r($data);
            // die;

    
          $StateDistrictFederation = $this->StateDistrictFederations->patchEntity($StateDistrictFederation, $data,['associated'=>['SdFederationOtherMembers']]);


           
            if($this->StateDistrictFederations->save($StateDistrictFederation)) {
                
                
                $this->loadModel('SdFederationMembers');
                if(!empty($StateDistrictFederation['sd_federation_members'])){
                   foreach($StateDistrictFederation['sd_federation_members'] as $key=>$coop_reg_id){
                    $SdFederationMember = $this->SdFederationMembers->newEntity();
                    $SdFederationMember->state_district_federation_id = $StateDistrictFederation->id;
                    $SdFederationMember->cooperative_registration_id =  $coop_reg_id;
                    $this->SdFederationMembers->save($SdFederationMember);
                }
            }

            
            if($StateDistrictFederation->operation_area_location == 2 || $StateDistrictFederation->operation_area_location == 3){
            $this->loadModel('SdFederationRurals');
                foreach($StateDistrictFederation['sector'] as $key=>$sector_rural_data){
                 $SdFederationRural = $this->SdFederationRurals->newEntity();
                 $SdFederationRural->state_district_federation_id = $StateDistrictFederation->id;
                 $SdFederationRural->state_code = $StateDistrictFederation->state_code;
                 $SdFederationRural->district_code = $sector_rural_data['district_code'];
                 $SdFederationRural->block_code =  $sector_rural_data['block_code'];
                 $this->SdFederationRurals->save($SdFederationRural);
                 }
             }
                
            if($StateDistrictFederation->operation_area_location == 1 || $StateDistrictFederation->operation_area_location == 3){
            $this->loadModel('SdFederationUrbans');
            foreach($StateDistrictFederation['sector_urban'] as $key=>$sector_urban_data){
            $SdFederationUrban = $this->SdFederationUrbans->newEntity();
            $SdFederationUrban->state_district_federation_id = $StateDistrictFederation->id;
            $SdFederationUrban->state_code = $StateDistrictFederation->state_code;
            $SdFederationUrban->local_body_type_code = $sector_urban_data['local_body_type_code'];
            $SdFederationUrban->local_body_code = $sector_urban_data['local_body_code'];
            $SdFederationUrban->district_code = $sector_urban_data['district_code'];
            $SdFederationUrban->block_code =  $sector_urban_data['block_code'];
            $this->SdFederationUrbans->save($SdFederationUrban);
                }
            }
        
        
            
                if($data['sector_of_operation'] == 77)
                {

                    $this->loadModel('SdFederationAgriculture');
                   $data['sd_federation_agriculture']['farming_mech'] = implode(',',$data['sd_federation_agriculture']['farming_mech']);
                   $data['sd_federation_agriculture']['irrigation_means'] = implode(',',$data['sd_federation_agriculture']['irrigation_means']);
                    $data['sd_federation_agriculture']['state_district_federation_id'] = $StateDistrictFederation->id;

                    $agriculture = $this->SdFederationAgriculture->newEntity();
                    
                    $agriculture = $this->SdFederationAgriculture->patchEntity($agriculture, $data['sd_federation_agriculture']);
                    $this->SdFederationAgriculture->save($agriculture);

                   
                }

                // Non Credit Housing data
                if($data['sector_of_operation'] == 47)
                {

                    if($data['sd_federation_housing']['has_land']==1){
                       $this->loadModel('SdFederationLands');
                       $data['sd_federation_lands']['state_district_federation_id'] = $StateDistrictFederation->id;

                       $land = $this->SdFederationLands->newEntity();
                       $land = $this->SdFederationLands->patchEntity($land, $data['sd_federation_lands']);
                       $this->SdFederationLands->save($land);
                    }

                    $this->loadModel('SdFederationHousing');
                    $data['sd_federation_housing']['facilities'] = implode(',',$data['sd_federation_housing']['facilities']);
                    $data['sd_federation_housing']['state_district_federation_id'] = $StateDistrictFederation->id;

                    $housing = $this->SdFederationHousing->newEntity();
                    $housing = $this->SdFederationHousing->patchEntity($housing, $data['sd_federation_housing']);
                    $this->SdFederationHousing->save($housing);
                }

               


                // Non Credit Marketing data
                if($data['sector_of_operation'] ==  82)
                {
                    if($data['sd_federation_marketing']['has_land']==1){
                        $this->loadModel('SdFederationLands');
                        $data['sd_federation_lands']['state_district_federation_id'] = $StateDistrictFederation->id;

                        $housingLand = $this->SdFederationLands->newEntity();
                        $housingLand = $this->SdFederationLands->patchEntity($housingLand, $data['sd_federation_lands']);
                        $this->SdFederationLands->save($housingLand);
                    }

                    $this->loadModel('SdFederationMarketing');
                    $data['sd_federation_marketing']['state_district_federation_id'] = $StateDistrictFederation->id;
                    $data['sd_federation_marketing']['liecense_to_sell'] = implode(',',$data['sd_federation_marketing']['liecense_to_sell']);
                    $data['sd_federation_marketing']['sell_the_item'] = implode(',',$data['sd_federation_marketing']['sell_the_item']);

                    $marketing = $this->SdFederationMarketing->newEntity();
                    $marketing = $this->SdFederationMarketing->patchEntity($marketing, $data['sd_federation_marketing']);
                    $this->SdFederationMarketing->save($marketing);
                }

                
                   // Non Credit Sugar data
                if($data['sector_of_operation'] ==  11)
                {
                    $this->loadModel('SdFederationSugar');
                    $data['sd_federation_sugar']['crushing_period_start'] = date("Y-m-d",strtotime(str_replace("/","-",$data['sd_federation_sugar']['crushing_period_start'])));
                    $data['sd_federation_sugar']['crushing_period_end'] = date("Y-m-d",strtotime(str_replace("/","-",$data['sd_federation_sugar']['crushing_period_end'])));

                    $data['sd_federation_sugar']['state_district_federation_id'] = $StateDistrictFederation->id;
                    $data['sd_federation_sugar']['product_produced'] = implode(',',$data['sd_federation_sugar']['product_produced']);

                    $sugar = $this->SdFederationSugar->newEntity();
                    $sugar = $this->SdFederationSugar->patchEntity($sugar, $data['sd_federation_sugar']);
                    $this->SdFederationSugar->save($sugar);
                }




                // Non Credit Consumer data
                if($data['sector_of_operation'] == 80)
                {

                    $this->loadModel('SdFederationConsumer');
                    $data['sd_federation_consumer']['state_district_federation_id'] = $StateDistrictFederation->id;
                    $data['sd_federation_consumer']['facilities'] = implode(',',$data['sd_federation_consumer']['facilities']);

                    $consumer = $this->SdFederationConsumer->newEntity();
                    $consumer = $this->SdFederationConsumer->patchEntity($consumer, $data['sd_federation_consumer']);
                    $this->SdFederationConsumer->save($consumer);
                }
                

                    // Non Credit Dairy data
                    if($data['sector_of_operation'] == 9)
                    {

                        $this->loadModel('SdFederationDairy');
                        $data['sd_federation_dairy']['state_district_federation_id'] = $StateDistrictFederation->id;

                        $dairy = $this->SdFederationDairy->newEntity();
                        
                        $dairy = $this->SdFederationDairy->patchEntity($dairy, $data['sd_federation_dairy']);
                        $this->SdFederationDairy->save($dairy);
                    }

                    // Non Credit Fishery data
                 if($data['sector_of_operation'] == 10)
                 {

                     $this->loadModel('SdFederationFishery');
                     $data['sd_federation_fishery']['state_district_federation_id'] = $StateDistrictFederation->id;

                     $fishery = $this->SdFederationFishery->newEntity();
                     
                     $fishery = $this->SdFederationFishery->patchEntity($fishery, $data['sd_federation_fishery']);
                     $this->SdFederationFishery->save($fishery);
                 }



                 
                //queries for primary activity "Credit"
                if($data['sector_of_operation'] ==  18)
                {
                    $this->loadModel('SdFederationCredit');
                    $data['sd_federation_credit']['facilities'] = implode(',',$data['sd_federation_credit']['facilities']);
                    $data['sd_federation_credit']['state_district_federation_id'] = $StateDistrictFederation->id;
                  
                    $credit = $this->SdFederationCredit->newEntity();
                    $credit = $this->SdFederationCredit->patchEntity($credit, $data['sd_federation_credit']);
                    $this->SdFederationCredit->save($credit);
                }


                

                 //queries for primary activity "Bee"
            if($data['sector_of_operation'] ==  79)
            {
                $this->loadModel('SdFederationBee');
                $data['sd_federation_bee']['state_district_federation_id'] = $StateDistrictFederation->id;
                $data['sd_federation_bee']['type_bee'] = implode(',',$data['sd_federation_bee']['type_bee']);
                $data['sd_federation_bee']['type_product'] = implode(',',$data['sd_federation_bee']['type_product']);
                $data['sd_federation_bee']['facilities'] = implode(',',$data['sd_federation_bee']['facilities']);

                

                $bee = $this->SdFederationBee->newEntity();
                $bee = $this->SdFederationBee->patchEntity($bee, $data['sd_federation_bee']);
                $this->SdFederationBee->save($bee);
            }

                 
                 
            
                   //queries for primary activity "Education"
                    if($data['sector_of_operation'] ==  84)
                    {


                        if($data['sd_federation_education']['has_land']==1){
                            $this->loadModel('SdFederationLands');
                            $data['sd_federation_lands']['state_district_federation_id'] = $StateDistrictFederation->id;
     
                            $land = $this->SdFederationLands->newEntity();
                            $land = $this->SdFederationLands->patchEntity($land, $data['sd_federation_lands']);
                            $this->SdFederationLands->save($land);
                         }

                        $this->loadModel('SdFederationEducation');
                        $data['sd_federation_education']['state_district_federation_id'] = $StateDistrictFederation->id;
                        $data['sd_federation_education']['facilities'] = implode(',',$data['sd_federation_education']['facilities']);

                        $education = $this->SdFederationEducation->newEntity();
                        $education = $this->SdFederationEducation->patchEntity($education, $data['sd_federation_education']);
                        $this->SdFederationEducation->save($education);
                    }

                 

                 //queries for primary activity "Handicraft"
            if($data['sector_of_operation'] ==  14)
            {
                $this->loadModel('SdFederationHandicraft');
                $data['sd_federation_handicraft']['state_district_federation_id'] = $StateDistrictFederation->id;
                $data['sd_federation_handicraft']['type_raw'] = implode(',',$data['sd_federation_handicraft']['type_raw']);
                $data['sd_federation_handicraft']['type_produce'] = implode(',',$data['sd_federation_handicraft']['type_produce']);
                $data['sd_federation_handicraft']['facilities'] = implode(',',$data['sd_federation_handicraft']['facilities']);

                $handicraft = $this->SdFederationHandicraft->newEntity();
                $handicraft = $this->SdFederationHandicraft->patchEntity($handicraft, $data['sd_federation_handicraft']);
                $this->SdFederationHandicraft->save($handicraft);
            }

                 // Non Credit Handloom data
                 if($data['sector_of_operation'] == 13)
                 {
                 
                     $this->loadModel('SdFederationHandloom');
                     $data['sd_federation_handloom']['state_district_federation_id'] = $StateDistrictFederation->id;

                     $handloom = $this->SdFederationHandloom->newEntity();
                     $handloom = $this->SdFederationHandloom->patchEntity($handloom, $data['sd_federation_handloom']);
                     $this->SdFederationHandloom->save($handloom);
                 }

             

                 

                //queries for primary activity "Jute"
            if($data['sector_of_operation'] ==  90)
            {
                $this->loadModel('SdFederationJute');
                $data['sd_federation_jute']['state_district_federation_id'] = $StateDistrictFederation->id;
                $data['sd_federation_jute']['type_raw'] = implode(',',$data['sd_federation_jute']['type_raw']);
                $data['sd_federation_jute']['type_produce'] = implode(',',$data['sd_federation_jute']['type_produce']);
                $data['sd_federation_jute']['facilities'] = implode(',',$data['sd_federation_jute']['facilities']);

                $jute = $this->SdFederationJute->newEntity();
                $jute = $this->SdFederationJute->patchEntity($jute, $data['sd_federation_jute']);
                $this->SdFederationJute->save($jute);
            }

                 // Non Credit Labour data
                 if($data['sector_of_operation'] == 51)
                 {

                     $this->loadModel('SdFederationLabour');
                     $data['sd_federation_labour']['facilities'] = implode(',',$data['sd_federation_labour']['facilities']);
                     $data['sd_federation_labour']['state_district_federation_id'] = $StateDistrictFederation->id;

                     $labour = $this->SdFederationLabour->newEntity();
                     
                     $labour = $this->SdFederationLabour->patchEntity($labour, $data['sd_federation_labour']);
                     $this->SdFederationLabour->save($labour);
                 }

                  //queries for primary activity "Processing"
                if($data['sector_of_operation'] ==  31)
                {
                    $this->loadModel('SdFederationProcessing');
                    $data['sd_federation_processing']['state_district_federation_id'] = $StateDistrictFederation->id;

                    $processing = $this->SdFederationProcessing->newEntity();
                    $processing = $this->SdFederationProcessing->patchEntity($processing, $data['sd_federation_processing']);
                    $this->SdFederationProcessing->save($processing);
                }

                //queries for primary activity "Tribal SC/ST"
            if($data['sector_of_operation'] ==  103)
            {
                $this->loadModel('SdFederationTribal');
                $data['sd_federation_tribal']['state_district_federation_id'] = $StateDistrictFederation->id;
                $data['sd_federation_tribal']['type_bee'] = implode(',',$data['sd_federation_tribal']['type_bee']);

                $tribal = $this->SdFederationTribal->newEntity();
                $tribal = $this->SdFederationTribal->patchEntity($tribal, $data['sd_federation_bee']);
                $this->SdFederationTribal->save($tribal);
            }

             //queries for primary activity "Women"
             if($data['sector_of_operation'] == 15)
             {

                 $this->loadModel('SdFederationWocoop');
                 $data['sd_federation_wocoop']['facilities'] = implode(',',$data['sd_federation_wocoop']['facilities']);
                 $data['sd_federation_wocoop']['state_district_federation_id'] = $StateDistrictFederation->id;

                 $women = $this->SdFederationWocoop->newEntity();
                 $women = $this->SdFederationWocoop->patchEntity($women, $data['sd_federation_wocoop']);
                 $this->SdFederationWocoop->save($women);

             }


             //queries for primary activity "Multipurpose"
             if($data['sector_of_operation'] == 16)
             {

                 $this->loadModel('SdFederationMulti');
                 $data['sd_federation_multi']['sec_activity'] = implode(',',$data['sd_federation_multi']['sec_activity']);
                $data['sd_federation_multi']['facilities'] = implode(',',$data['sd_federation_multi']['facilities']);
                 $data['sd_federation_multi']['state_district_federation_id'] = $StateDistrictFederation->id;

                 $multi = $this->SdFederationMulti->newEntity();
                 $multi = $this->SdFederationMulti->patchEntity($multi, $data['sd_federation_multi']);
                 $this->SdFederationMulti->save($multi);

             }


              //queries for primary activity "Cmiscellaneous"
              if($data['sector_of_operation'] ==  35)
              {
                  $this->loadModel('SdFederationCmiscellaneous');
                  $data['sd_federation_cmiscellaneous']['state_district_federation_id'] = $StateDistrictFederation->id;
                  $data['sd_federation_cmiscellaneous']['facilities'] = implode(',',$data['sd_federation_cmiscellaneous']['facilities']);

                  $cmiscellaneous = $this->SdFederationCmiscellaneous->newEntity();
                  $cmiscellaneous = $this->SdFederationCmiscellaneous->patchEntity($cmiscellaneous, $data['sd_federation_cmiscellaneous']);
                  $this->SdFederationCmiscellaneous->save($cmiscellaneous);
              }

            // echo "<pre>";
            // print_r($data);
            // die;

                //queries for primary activity "Ucb"
                if($data['sector_of_operation'] ==  7)
                {
                    
                    
                    if($data['sd_federation_ucb']['is_gov_scheme_implemented']==1){
                        $this->loadModel('SdFederationImplementingSchemes');
                        foreach($data['sd_federation_implementing_schemes'] as $key=>$sch_arr){
                            $sch = $this->SdFederationImplementingSchemes->newEntity();
                            $sch->state_district_federation_id	=$StateDistrictFederation->id;
                            $sch->gov_scheme_name = $sch_arr['gov_scheme_name'];
                            $sch->gov_scheme_type = $sch_arr['gov_scheme_type'];
                            $sch->total_amount = $sch_arr['total_amount'];
                            // $sch = $this->SdFederationImplementingSchemes->patchEntity($sch, $data['sd_federation_implementing_schemes']);
                            $this->SdFederationImplementingSchemes->save($sch);
                        }
                    
                     }

                    $this->loadModel('SdFederationUcb');
                    $data['sd_federation_ucb']['state_district_federation_id'] = $StateDistrictFederation->id;

                    $ucb = $this->SdFederationUcb->newEntity();
                    $ucb = $this->SdFederationUcb->patchEntity($ucb, $data['sd_federation_ucb']);
                    $this->SdFederationUcb->save($ucb);
                }

             //livestock, marketing, miscellaneous, multipurpose, processing, sericulture, social, sugar, tourism, transport, tribal, women.............
                if($data['sector_of_operation'] ==  54)
                {
                    $this->loadModel('SdFederationLivestock');
                    $data['sd_federation_livestock']['state_district_federation_id'] = $StateDistrictFederation->id;

                    $data['sd_federation_livestock']['type_society'] = implode(',',$data['sd_federation_livestock']['type_society']);
                    $data['sd_federation_livestock']['type_produce'] = implode(',',$data['sd_federation_livestock']['type_produce']);
                    $data['sd_federation_livestock']['facilities'] = implode(',',$data['sd_federation_livestock']['facilities']);

                    $livestock = $this->SdFederationLivestock->newEntity();
                    $livestock = $this->SdFederationLivestock->patchEntity($livestock, $data['sd_federation_livestock']);
                    $this->SdFederationLivestock->save($livestock);
                }

                

                if($data['sector_of_operation'] ==  29)
                {
                    $this->loadModel('SdFederationMiscellaneous');
                    $data['sd_federation_miscellaneous']['state_district_federation_id'] = $StateDistrictFederation->id;

                    $miscellaneous = $this->SdFederationMiscellaneous->newEntity();
                    
                    $miscellaneous = $this->SdFederationMiscellaneous->patchEntity($miscellaneous, $data['sd_federation_miscellaneous']);
                    $this->SdFederationMiscellaneous->save($miscellaneous);
                }


                

                if($data['sector_of_operation'] ==  31)
                {
                    $this->loadModel('SdFederationProcessing');
                    $data['sd_federation_multi']['state_district_federation_id'] = $StateDistrictFederation->id;

                    $processing = $this->SdFederationProcessing->newEntity();
                    
                    $processing = $this->SdFederationProcessing->patchEntity($processing, $data['sd_federation_processing']);
                    $this->SdFederationProcessing->save($processing);
                }

                if($data['sector_of_operation'] ==  96)
                {
                    $this->loadModel('SdFederationSericulture');
                    $data['sd_federation_sericulture']['state_district_federation_id'] = $StateDistrictFederation->id;
                    $data['sd_federation_sericulture']['type_society'] = implode(',',$data['sd_federation_sericulture']['type_society']);
                    $data['sd_federation_sericulture']['facilities'] = implode(',',$data['sd_federation_sericulture']['facilities']);

                    $sericulture = $this->SdFederationSericulture->newEntity();                   
                    $sericulture = $this->SdFederationSericulture->patchEntity($sericulture, $data['sd_federation_sericulture']);
                    $this->SdFederationSericulture->save($sericulture);
                }


                if($data['sector_of_operation'] ==  98)
                {
                    $this->loadModel('SdFederationSocial');
                    $data['sd_federation_social']['state_district_federation_id'] = $StateDistrictFederation->id;
                    $data['sd_federation_social']['type_social_culture_activity'] = implode(',',$data['sd_federation_social']['type_social_culture_activity']);
                    $data['sd_federation_social']['facilities'] = implode(',',$data['sd_federation_social']['facilities']);


                    $social = $this->SdFederationSocial->newEntity();
                    $social = $this->SdFederationSocial->patchEntity($social, $data['sd_federation_social']);
                    $this->SdFederationSocial->save($social);
                }


                

                if($data['sector_of_operation'] ==  99)
                {
                    $this->loadModel('SdFederationTourism');
                    $data['sd_federation_tourism']['state_district_federation_id'] = $StateDistrictFederation->id;
                    $data['sd_federation_tourism']['facilities'] = implode(',',$data['sd_federation_tourism']['facilities']);

                    // echo "<pre>";
                    // print_r($data['sd_federation_tourism']); die;

                    $tourism = $this->SdFederationTourism->newEntity();
                    
                    $tourism = $this->SdFederationTourism->patchEntity($tourism, $data['sd_federation_tourism']);
                    $this->SdFederationTourism->save($tourism);
                }


                if($data['sector_of_operation'] ==  68)
                {
                    $this->loadModel('SdFederationTransport');
                    $data['sd_federation_transport']['state_district_federation_id'] = $StateDistrictFederation->id;

                    $transport= $this->SdFederationTransport->newEntity();
                    
                    $transport = $this->SdFederationTransport->patchEntity($transport, $data['sd_federation_transport']);
                    $this->SdFederationTransport->save($transport);
                }


                if($data['sector_of_operation'] ==  102)
                {
                    $this->loadModel('SdFederationTribal');
                    $data['sd_federation_tribal']['state_district_federation_id'] = $StateDistrictFederation->id;

                    $tribal = $this->SdFederationTribal->newEntity();
                    
                    $tribal = $this->SdFederationTribal->patchEntity($tribal, $data['sd_federation_tribal']);
                    $this->SdFederationTribal->save($tribal);
                }


                

                
             //save land
            //  if(isset($data['area']) && !empty($data['area']))
            //  {
            //      $this->loadModel('CooperativeRegistrationsLands');
            //      //$this->AreaOfOperationLevel->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                 
                 
            //      $data['area']['cooperative_registration_id'] = $CooperativeRegistration->id;
            //      $CooperativeRegistrationsLands = $this->CooperativeRegistrationsLands->newEntity();
                 
            //      $CooperativeRegistrationsLands = $this->CooperativeRegistrationsLands->patchEntity($CooperativeRegistrationsLands, $data['area']);

            //      $this->CooperativeRegistrationsLands->save($CooperativeRegistrationsLands);
            //  }

            //  if($data['sector_of_operation'] == 7)
            //  {
            //      $this->loadModel('SocietyImplementingSchemes');
            //     $data['society_implementing_schemes']['cooperative_registration_id'] = $CooperativeRegistration->id;
            //      $CooperativeRegistrationsSchemes = $this->SocietyImplementingSchemes->newEntity();
                 
            //      $CooperativeRegistrationsSchemes = $this->SocietyImplementingSchemes->patchEntity($CooperativeRegistrationsSchemes, $data['society_implementing_schemes']);

            //      $this->SocietyImplementingSchemes->save($CooperativeRegistrationsSchemes);
            //  }

             //Rural & both
            //  $arr_village_sector = [2,3];

            //  if(isset($data['sector']) && !empty($data['sector']) && in_array($data['operation_area_location'],$arr_village_sector))
            //  {
            //      $this->loadModel('AreaOfOperationLevel');
            //      $this->loadMOdel('DistrictsBlocksGpVillages');
            //      //$this->AreaOfOperationLevel->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                 
            //      foreach($data['sector'] as $s_key => $sector)
            //      {
            //          if($sector['panchayat_code'] == -1){

            //              $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$sector['block_code']])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray(); 

            //                      if(isset($gps) && !empty($gps))
            //                      {
            //                          foreach($gps as $gp_code => $gp_name)
            //                          {
            //                              $gp_villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$gp_code])->order(['village_name'=>'ASC'])->toArray();

            //                              if(isset($gp_villages) && !empty($gp_villages))
            //                              {
            //                                  foreach ($gp_villages as $gp_village_code => $gp_village_name) {

            //                                      $gp_areaOfOperationLevel = $this->AreaOfOperationLevel->newEntity();
            //                                      $bulk_gp_data = [
            //                                          'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
            //                                          'row_id'                        =>  $s_key,
            //                                          'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
            //                                          'state_code'                    =>  $data['state_code'],
            //                                          'district_code'                 =>  $sector['district_code'],
            //                                          'block_code'                    =>  $sector['block_code'],
            //                                          'panchayat_code'                =>  $gp_code,
            //                                          'village_code'                  =>  $gp_village_code,
            //                                          'gp_village_all'                =>  1
            //                                      ];

            //                                      $gp_areaOfOperationLevel = $this->AreaOfOperationLevel->patchEntity($gp_areaOfOperationLevel, $bulk_gp_data);

            //                                      $this->AreaOfOperationLevel->save($gp_areaOfOperationLevel);
            //                                  }
            //                              }
            //                          }
            //                      }

            //          } else if($sector['block_code'] == -1){
            //             $block_areaOfOperationLevel = $this->AreaOfOperationLevel->newEntity();
            //             $bulk_block_data = [
            //                 'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
            //                 'row_id'                        =>  $s_key,
            //                 'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
            //                 'state_code'                    =>  $data['state_code'],
            //                 'district_code'                 =>  $sector['district_code'],
            //                 'block_code'                    =>  '-1',
            //                 'panchayat_code'                =>  0,
            //                 'village_code'                  =>  0,
            //                 'gp_village_all'                =>  3
            //             ];

            //             $block_areaOfOperationLevel = $this->AreaOfOperationLevel->patchEntity($block_areaOfOperationLevel, $bulk_block_data);

            //             $this->AreaOfOperationLevel->save($block_areaOfOperationLevel);

            //          } else if($sector['village_code'][0] == -1) {

            //              $all_villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$sector['panchayat_code']])->order(['village_name'=>'ASC'])->toArray();

            //              if(isset($all_villages) && !empty($all_villages))
            //              {
            //                  foreach ($all_villages as $all_village_code => $all_village_name) {

            //                      $village_areaOfOperationLevel = $this->AreaOfOperationLevel->newEntity();
            //                      $bulk_village_data = [
            //                          'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
            //                          'row_id'                        =>  $s_key,
            //                          'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
            //                          'state_code'                    =>  $data['state_code'],
            //                          'district_code'                 =>  $sector['district_code'],
            //                          'block_code'                    =>  $sector['block_code'],
            //                          'panchayat_code'                =>  $sector['panchayat_code'],
            //                          'village_code'                  =>  $all_village_code,
            //                          'gp_village_all'                =>  2
            //                      ];

            //                      $village_areaOfOperationLevel = $this->AreaOfOperationLevel->patchEntity($village_areaOfOperationLevel, $bulk_village_data);

            //                      $this->AreaOfOperationLevel->save($village_areaOfOperationLevel);
            //                  }
            //              }
                         
            //          } else {
            //              $villages = $sector['village_code'];

            //              foreach($villages as $v_key => $village_code)
            //              {
            //                  if($village_code != '-1')
            //                  {
            //                      $AreaOfOperationLevel = $this->AreaOfOperationLevel->newEntity();
            //                      $area_data = [
            //                          'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
            //                          'row_id'                        =>  $s_key,
            //                          'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
            //                          'state_code'                    =>  $data['state_code'],
            //                          'district_code'                 =>  $sector['district_code'],
            //                          'block_code'                    =>  $sector['block_code'],
            //                          'panchayat_code'                =>  $sector['panchayat_code'],
            //                          'village_code'                  =>  $village_code,
            //                          'gp_village_all'                =>  0
            //                      ];
            //                      $AreaOfOperationLevel = $this->AreaOfOperationLevel->patchEntity($AreaOfOperationLevel, $area_data);

            //                      $this->AreaOfOperationLevel->save($AreaOfOperationLevel);
            //                  }
                             
            //              }
            //          }
            //      }  

            //  }

                //Urban & both
                // $arr_ward_sector = [1,3];

                // if(isset($data['sector_urban']) && !empty($data['sector_urban'])   && in_array($data['operation_area_location'],$arr_ward_sector))
                // {
                //     $this->loadModel('AreaOfOperationLevelUrban');
                //     //$this->AreaOfOperationLevel->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                    
                //     foreach($data['sector_urban'] as $s_key => $sector)
                //     {
                //         $wards = $sector['locality_ward_code'];
                //         foreach($wards as $v_key => $ward_code)
                //         {
                           
                //             $AreaOfOperationLevelUrban = $this->AreaOfOperationLevelUrban->newEntity();
                //             $area_data = [
                //                 'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
                //                 'row_id'                        =>  $s_key,
                //                 'area_of_operation_id'          =>  $data['area_of_operation_id_urban'],
                //                 'state_code'                    =>  $data['state_code'],
                //                 'district_code'                 =>  $sector['district_code'],
                //                 'local_body_type_code'          =>  $sector['local_body_type_code'],
                //                 'local_body_code'               =>  $sector['local_body_code'],
                //                 'locality_ward_code'            =>  $ward_code
                //             ];
                //             $AreaOfOperationLevelUrban = $this->AreaOfOperationLevelUrban->patchEntity($AreaOfOperationLevelUrban, $area_data);

                //             $this->AreaOfOperationLevelUrban->save($AreaOfOperationLevelUrban);
                //         }
                //     }  
                // }
                

                

               

                $this->Flash->success(__('The Cooperative data has been saved.'));

                // if($this->request->session()->read('Auth.User.role_id') == 14)
                // {
                    if($data['is_draft'] == 1)
                    {
                        return $this->redirect(['action' => 'draft']);    
                    } else {
                        return $this->redirect(['action' => 'list']);    
                    }
                    
                // }
                // return $this->redirect(['action' => 'index']);
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

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

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
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$StateDistrictFederation->district_code])->order(['name'=>'ASC']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown


        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$StateDistrictFederation->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown



        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$StateDistrictFederation->gram_panchayat_code])->order(['village_name'=>'ASC']); 
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
            $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$StateDistrictFederation->sector_of_operation_type,'status'=>1])->order(['orderseq'=>'ASC']);

            $this->loadModel('PrimaryActivities');
            $PrimaryActivitiesTotal=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        // End PrimaryActivities for dropdown
            
        // Start SecondaryActivities for dropdown
            $this->loadModel('SecondaryActivities');
            $SecondaryActivities=$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'sector_of_operation'=>$StateDistrictFederation->sector_of_operation_type])->order(['orderseq'=>'ASC']);

        // End SecondaryActivities for dropdown
        
        // Start PresentFunctionalStatus for dropdown
            $this->loadModel('PresentFunctionalStatus');
            $PresentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        // End PresentFunctionalStatus for dropdown

        
        // Start urban_local_bodies type for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$StateDistrictFederation->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown


        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$StateDistrictFederation->urban_local_body_type_code,'state_code'=>$StateDistrictFederation->state_code])->order(['local_body_name'=>'ASC']);
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown


        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$StateDistrictFederation->urban_local_body_code])->order(['ward_name'=>'ASC']);

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
        $miscellaneoustypes =$this->MiscellaneousCooperativeSocieties->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        $this->set('miscellaneoustypes',$miscellaneoustypes);


        

        //  Start register_authorty dropdown registration_authorities
        $this->loadModel('RegistrationAuthorities');
        $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['orderseq'=>'ASC']);
        $this->set('register_authorities',$register_authorities);
        // End registration_authorities for dropdown

        $this->loadModel('TypeOfMemberships');

        $arr_other_type_member=$this->TypeOfMemberships->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('arr_other_type_member',$arr_other_type_member); 

        $this->set(compact('PrimaryActivitiesTotal','CooperativeRegistration','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','years','state_code'));

        
        
    }

     public function fedCode($sector_of_operation_type = null ,$sector_of_operation=null,$federation_type=null,$location_of_head_quarter=null,$state=null,$district=null,$regid=null){
		 
		 if(strlen($sector_of_operation) == 1){
			 $a_sector_of_operation = '00'.$sector_of_operation ;
		 }elseif(strlen($sector_of_operation) == 2){
			  $a_sector_of_operation = '0'.$sector_of_operation ;
		 }else{
			  $a_sector_of_operation = $sector_of_operation ;
		 }
		 if(strlen($state) == 1){
			 $a_state = '0'.$state ;
		 }else{
			  $a_state = $state ;
		 }
		 if(strlen($district) == 1){
			 $a_district = '00'.$district ;
		 }elseif(strlen($district) == 2){
			  $a_district = '0'.$district ;
		 }else{
			  $a_district = $district ;
		 }
		$federation_code = '0'.$sector_of_operation_type.'/'.$a_sector_of_operation.'/0'.$federation_type.'/0'.$location_of_head_quarter.'/'.$a_state.'/'.$a_district.'/'.$regid ; 
		 return $federation_code ;
	 }

     public function member($id = null)
    {
         
        $this->loadModel('StateDistrictFederations');
        $this->loadMOdel('SdFederationMembers');

         $StateDistrictFederation = $this->StateDistrictFederations->get($id);


        $state_code= $this->request->session()->read('Auth.User.state_code');

        $member_count = $this->SdFederationMembers->find('all')->where(['state_district_federation_id'=>$id])->toArray();
        
        foreach($member_count as $k=>$v){
                $member_counts[] = $v['cooperative_registration_id'] ;
             }
       // echo "<pre>";
       //  print_r($member_counts);die;

        $this->loadMOdel('CooperativeRegistrations');
        if(!empty($member_count)){

        $cooperative_society_name_arr=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'is_draft'=>0,'is_approved !='=>2,'sector_of_operation'=>$StateDistrictFederation['sector_of_operation'],'id IN'=> $member_counts])->toArray();
        $this->set('cooperative_society_name_arr',$cooperative_society_name_arr);
        } 

        // else{
        // $cooperative_society_name_arr=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'is_draft'=>0,'is_approved !='=>2,'district_code'=>$StateDistrictFederation->district_code,'sector_of_operation'=>$StateDistrictFederation->sector_of_operation])->toArray();
        // $this->set('cooperative_society_name_arr',$cooperative_society_name_arr);
        // }
    
        // echo "<pre>";
        // print_r($cooperative_society_name_arr);die;

        // $cooperative_society_name_arr=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'is_draft'=>0,'is_approved !='=>2,'state_code'=>$state_code,'district_code'=>$StateDistrictFederation->district_code,'sector_of_operation'=>$StateDistrictFederation->sector_of_operation,'id IN'=> $member_counts])->toArray();
        // $this->set('cooperative_society_name_arr',$cooperative_society_name_arr);

        
        $sd_federation_members=$this->SdFederationMembers->find('all')->where(['state_district_federation_id'=>$id])->toArray();
        $this->set('sd_federation_members',$sd_federation_members);
  
            $this->loadModel('States');
            if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
            {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
            } else {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
            }
            
        $this->set('states',$states);

        $this->loadModel('Districts');

            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
            $this->set('districts',$districts);

        $this->set(compact('StateDistrictFederation'));
        
        
    }

    public function edit($state_district_federation_id = null)
    {
        
       
        $this->loadModel('StateDistrictFederations');
        // $StateDistrictFederation = $this->StateDistrictFederations->find('all')->where(['id'=>$state_district_federation_id])->first();
        $StateDistrictFederation = $this->StateDistrictFederations->get($state_district_federation_id, [
            'contain' => ['SdFederationUrbans','SdFederationRurals']
        ]);
        // echo "<pre>";
        // print_r($StateDistrictFederation);die;

        $state_code= $this->request->session()->read('Auth.User.state_code');

        $this->loadMOdel('CooperativeRegistrations');
        $cooperative_society_name_arr=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'is_draft'=>0,'is_approved !='=>2,'state_code'=>$state_code,'district_code'=>$StateDistrictFederation->district_code,'sector_of_operation'=>$StateDistrictFederation->sector_of_operation])->toArray();
        $this->set('cooperative_society_name_arr',$cooperative_society_name_arr);

        $this->loadMOdel('SdFederationMembers');
        // $sd_federation_members=$this->SdFederationMembers->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->toArray();
        // $this->set('sd_federation_members',$sd_federation_members);

        $this->loadMOdel('SdFederationOtherMembers');
        $sd_federation_other_members=$this->SdFederationOtherMembers->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->toArray();
        $this->set('sd_federation_other_members',$sd_federation_other_members);
        
        
        
        $this->loadMOdel('DistrictCentralCooperativeBank');
        $this->loadMOdel('LevelOfAffiliatedUnion');  

        $sector_details_affiliation_level = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1,'primary_activity_id'=>$StateDistrictFederation->sector_of_operation,'id !='=>1,'affiliated_level <'=>$StateDistrictFederation->federation_type])->order(['orderseq'=>'ASC'])->toArray();
        
        $this->set('sector_details_affiliation_level',$sector_details_affiliation_level);

        //for name of affiliation in "Sector Details Box"
        $a_union_level = $this->LevelOfAffiliatedUnion->find('all')->where(['id'=>$StateDistrictFederation->affiliated_union_federation_level])->first()->affiliated_level;

        
        if($a_union_level == 1)
        {
            $SecondaryActivities = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','dccb_flag'=>3])->order(['name'=>'ASC'])->toArray();
        } 
        else {
            $SecondaryActivities = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','primary_activity_flag'=>$StateDistrictFederation->sector_of_operation,'level_of_affiliated_union_id'=>$StateDistrictFederation->affiliated_union_federation_level])->order(['name'=>'ASC'])->toArray();
        }

       
       if($StateDistrictFederation->affiliated_union_federation_name ==-1){
                              $SecondaryActivities[-1] = 'Other';
                            }
                         
        // echo "<pre>";
        // print_r($StateDistrictFederation);
        // echo "<pre>";
        // print_r($a_union_level);
        // echo "<pre>";
        // print_r($SecondaryActivities);
        // die;

        $this->set('SecondaryActivities',$SecondaryActivities);

        
      //queries for table "Sd Federation Lands" common to Housing, Marketing
        if($StateDistrictFederation->sector_of_operation==47 || $StateDistrictFederation->sector_of_operation==82 || $StateDistrictFederation->sector_of_operation==84){

        $this->loadMOdel('SdFederationLands');
        $sd_fed_land = $this->SdFederationLands->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
        $this->set('sd_fed_land',$sd_fed_land);  
         
        }
        
        //queries for primary activity "Agriculture"
        if($StateDistrictFederation->sector_of_operation==77){
        $this->loadMOdel('SdFederationAgriculture');
        $sd_fed_agri = $this->SdFederationAgriculture->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
        $this->set('sd_fed_agri',$sd_fed_agri);  
        }

          //queries for primary activity "Housing"
        if($StateDistrictFederation->sector_of_operation==47){

        $this->loadModel('HousingSocietyTypes');
        $societytypes =$this->HousingSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('societytypes',$societytypes);

        $this->loadModel('CooperativeSocietyFacilities');
        $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,47]])->order(['orderseq'=>'ASC']);
        $this->set('facilities',$facilities);

        $this->loadMOdel('SdFederationHousing');
        $sd_fed_housing = $this->SdFederationHousing->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
        $this->set('sd_fed_housing',$sd_fed_housing); 
        }


        if($StateDistrictFederation->sector_of_operation==82){
            $this->loadMOdel('SdFederationMarketing');
            $sd_fed_marketing = $this->SdFederationMarketing->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_fed_marketing',$sd_fed_marketing);  
        }

        if($StateDistrictFederation->sector_of_operation==11){
            $this->loadMOdel('SdFederationSugar');
            $sd_fed_sugar = $this->SdFederationSugar->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_fed_sugar',$sd_fed_sugar); 
        }

        if($StateDistrictFederation->sector_of_operation==80){


            $this->loadModel('CooperativeSocietyFacilities');
            $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,80]])->order(['orderseq'=>'ASC']);
            $this->set('facilities',$facilities);

            $this->loadMOdel('SdFederationConsumer');
        $sd_fed_consumer = $this->SdFederationConsumer->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
        $this->set('sd_fed_consumer',$sd_fed_consumer);
        }


        //queries for primary activity "Dairy"
        if($StateDistrictFederation->sector_of_operation==9){
            $this->loadMOdel('SdFederationDairy');
            $sd_fed_dairy = $this->SdFederationDairy->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_fed_dairy',$sd_fed_dairy);  
            }

            //queries for primary activity "Fishery"
            if($StateDistrictFederation->sector_of_operation==10){
            $this->loadMOdel('SdFederationFishery');
            $sd_fed_fishery = $this->SdFederationFishery->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_fed_fishery',$sd_fed_fishery);  
            }

    

            //queries for primary activity "Handloom"
            if($StateDistrictFederation->sector_of_operation==13){
            $this->loadMOdel('SdFederationHandloom');
            $sd_fed_handloom = $this->SdFederationHandloom->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_fed_handloom',$sd_fed_handloom);
            }

            //queries for primary activity "Labour"
            if($StateDistrictFederation->sector_of_operation==51){
            $this->loadMOdel('SdFederationLabour');
            $sd_fed_labour = $this->SdFederationLabour->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_fed_labour',$sd_fed_labour);

            $this->loadModel('LabourSocietyTypes');
            $lsocietytypes =$this->LabourSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
            $this->set('lsocietytypes',$lsocietytypes);

            $this->loadModel('CooperativeSocietyFacilities');
            $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,51]])->order(['orderseq'=>'ASC']);
            $this->set('facilities',$facilities);
            }

            //queries for primary activity "Processing"
            if($StateDistrictFederation->sector_of_operation==31){
            $this->loadMOdel('SdFederationProcessing');
            $sd_fed_processing = $this->SdFederationProcessing->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_fed_processing',$sd_fed_processing);  
            }

            //queries for primary activity "Credit"
            if($StateDistrictFederation->sector_of_operation==18){
            $this->loadMOdel('SdFederationCredit');
            $sd_fed_credit = $this->SdFederationCredit->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_fed_credit',$sd_fed_credit);

            $this->loadModel('CooperativeSocietyFacilities');
            $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,18]])->order(['orderseq'=>'ASC']);
            $this->set('facilities',$facilities);
            }
            
            //queries for primary activity "Bee"
            if($StateDistrictFederation->sector_of_operation==79){

                $this->loadMOdel('BeeBeehiveTypes');
                $beehivetypes =$this->BeeBeehiveTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type'=>2]);
                $this->set('beehivetypes',$beehivetypes);
                 
                $this->loadMOdel('ProductProduceBySociety');
                $type_product=$this->ProductProduceBySociety->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>79]);
                $this->set('type_product', $type_product);
        
                $this->loadModel('CooperativeSocietyFacilities');
                $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,79]])->order(['orderseq'=>'ASC']);
                $this->set('facilities',$facilities);

                $this->loadModel('BeeBeehiveTypes');   
                $beetypes =$this->BeeBeehiveTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type'=>1]);
                $this->set('beetypes',$beetypes);

                $this->loadMOdel('SdFederationBee');
                $sd_fed_bee = $this->SdFederationBee->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
                $this->set('sd_fed_bee',$sd_fed_bee);  
                }

             
        
         //queries for primary activity "Education"
         if($StateDistrictFederation->sector_of_operation==84){

            $this->loadModel('EducationSocietyTypes');   
            $educationsocietytypes =$this->EducationSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
            $this->set('educationsocietytypes',$educationsocietytypes);
    
            $this->loadModel('LevelAndDurationCourses');   
            $levelofcourses =$this->LevelAndDurationCourses->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type' => 1]);
            $this->set('levelofcourses',$levelofcourses);

            $this->set('levelofcourses',$levelofcourses);
            $durationofcourses =$this->LevelAndDurationCourses->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1, 'type' => 2]);
            $this->set('durationofcourses',$durationofcourses);

            $this->set('durationofcourses',$durationofcourses);
            $leveldurationofcourses =$this->LevelAndDurationCourses->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
            $this->set('leveldurationofcourses',$leveldurationofcourses);
    
           
            $this->loadModel('CooperativeSocietyFacilities');
            $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,84]])->order(['orderseq'=>'ASC']);
            $this->set('facilities',$facilities);

            $this->loadMOdel('SdFederationEducation');
            $sd_fed_education = $this->SdFederationEducation->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_fed_education',$sd_fed_education);  
            }

           

            //queries for primary activity "Tribal SC/ST"
         if($StateDistrictFederation->sector_of_operation==102){
            $this->loadMOdel('SdFederationTribal');
            $sd_fed_tribal = $this->SdFederationTribal->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_fed_tribal',$sd_fed_tribal);

            $this->loadModel('TribalSocietyTypes');   
            $tribalsocietytypes =$this->TribalSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
            $this->set('tribalsocietytypes',$tribalsocietytypes);

            $this->loadModel('CooperativeSocietyFacilities');
            $tribal_facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,102]])->order(['orderseq'=>'ASC']);
            $this->set('tribal_facilities',$tribal_facilities);
            }

    
            //queries for primary activity "Handicraft"
            if($StateDistrictFederation->sector_of_operation==14){
                $this->loadMOdel('SdFederationHandicraft');
                $sd_fed_handicraft = $this->SdFederationHandicraft->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
                $this->set('sd_fed_handicraft',$sd_fed_handicraft);  

                $this->loadMOdel('TypeRawMaterial');
                $type_raw=$this->TypeRawMaterial->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
                $this->set('type_raw', $type_raw);

                $this->loadModel('CooperativeSocietyFacilities');
                $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,14]])->order(['orderseq'=>'ASC']);
                $this->set('facilities',$facilities);

                // echo "<pre>";
                // print_r($sd_fed_handicraft);
                // die;
                }

                //queries for primary activity "Transport"
            if($StateDistrictFederation->sector_of_operation ==  68){
                    
            $this->loadModel('SdFederationTransport');
            $sd_fed_transport = $this->SdFederationTransport->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
             $this->set('sd_fed_transport',$sd_fed_transport);


             $this->loadModel('TransportSocietyTypes');
            $trsocietytypes =$this->TransportSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
            $this->set('trsocietytypes',$trsocietytypes);

            }

            //queries for primary activity "Tourism"
            if($StateDistrictFederation->sector_of_operation ==  99){
                    
            $this->loadModel('SdFederationTourism');
            $sd_fed_tourism = $this->SdFederationTourism->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
             $this->set('sd_fed_tourism',$sd_fed_tourism);

             $this->loadModel('TourismSocietyTypes');   
            $tourismsocietytypes =$this->TourismSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
            $this->set('tourismsocietytypes',$tourismsocietytypes);

            $this->loadModel('CooperativeSocietyFacilities');
            $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,99]])->order(['orderseq'=>'ASC']);
            $this->set('facilities',$facilities);


            }

            //queries for primary activity "Livestock"
            if($StateDistrictFederation->sector_of_operation ==  54){
                    

            $this->loadModel('SdFederationLivestock');
            $sd_fed_livestock = $this->SdFederationLivestock->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
             $this->set('sd_fed_livestock',$sd_fed_livestock);

            $this->loadModel('LivestockSocietyTypes');   
            $livesocietytypes =$this->LivestockSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
            $this->set('livesocietytypes',$livesocietytypes);


            $this->loadMOdel('ProductProduceBySociety');  
            $lproductproduce=$this->ProductProduceBySociety->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>54]);
            $this->set('lproductproduce', $lproductproduce); 


            $this->loadModel('CooperativeSocietyFacilities');
            $lfacilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,54]])->order(['orderseq'=>'ASC']);
            $this->set('lfacilities',$lfacilities);



            }

            //queries for primary activity "Women"
            if($StateDistrictFederation->sector_of_operation==15){

                $this->loadModel('CooperativeSocietyFacilities');
                $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,15]])->order(['orderseq'=>'ASC']);
                $this->set('facilities',$facilities);

                $this->loadModel('TypeWomenCooperatives');   
                $womensocietytypes =$this->TypeWomenCooperatives->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
                $this->set('womensocietytypes',$womensocietytypes);

                $this->loadMOdel('SdFederationWocoop');
                $sd_fed_women = $this->SdFederationWocoop->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
                $this->set('sd_fed_women',$sd_fed_women);  
            }

            //queries for primary activity "Multipurpose"
            if($StateDistrictFederation->sector_of_operation==16){

                $this->loadModel('SecondaryActivities');   
                $multitypes =$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
                $this->set('multitypes',$multitypes);

                $this->loadModel('CooperativeSocietyFacilities');
                $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,16]])->order(['orderseq'=>'ASC']);
                $this->set('facilities',$facilities);

                $this->loadMOdel('SdFederationMulti');
                $sd_fed_multipurpose = $this->SdFederationMulti->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
                $this->set('sd_fed_multipurpose',$sd_fed_multipurpose);  
            }

            //queries for primary activity "Social"
            if($StateDistrictFederation->sector_of_operation==98){

                $this->loadMOdel('SdFederationSocial');
                $sd_federation_social = $this->SdFederationSocial->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
                $this->set('sd_federation_social',$sd_federation_social);

                $this->loadModel('TypeSocialCulturalActivities');   
                $activitiestypes =$this->TypeSocialCulturalActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
                $this->set('activitiestypes',$activitiestypes);

                $this->loadModel('CooperativeSocietyFacilities');
                $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,98]])->order(['orderseq'=>'ASC']);
                $this->set('facilities',$facilities);
                 
                $this->loadMOdel('TypeSocialCooperatives');
                    $type_social=$this->TypeSocialCooperatives->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
                    $this->set('type_social', $type_social);
            }


            //queries for primary activity "Jute"
            if($StateDistrictFederation->sector_of_operation==90){

                $this->loadMOdel('TypeRawMaterial');
                $type_jraw=$this->TypeRawMaterial->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>90]);
                $this->set('type_jraw', $type_jraw);

                $this->loadMOdel('ProductProduceBySociety');  
                $productproduce=$this->ProductProduceBySociety->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id'=>90]);
                $this->set('productproduce', $productproduce);  

                $this->loadModel('CooperativeSocietyFacilities');
                $jfacilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,90]])->order(['orderseq'=>'ASC']);
                $this->set('jfacilities',$jfacilities);

                $this->loadMOdel('SdFederationJute');
                $sd_fed_jute = $this->SdFederationJute->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
                $this->set('sd_fed_jute',$sd_fed_jute);  
                }

                //queries for primary activity "Sericulture"
            if($StateDistrictFederation->sector_of_operation==96){

                $this->loadMOdel('SdFederationSericulture');
                $sd_fed_sericulture = $this->SdFederationSericulture->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
                $this->set('sd_fed_sericulture',$sd_fed_sericulture);


                $this->loadModel('SerticultureSilkwormTypes');   
                $sertisocietytypes =$this->SerticultureSilkwormTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
                $this->set('sertisocietytypes',$sertisocietytypes);

                $this->loadModel('CooperativeSocietyFacilities');
                $sfacilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,96]])->order(['orderseq'=>'ASC']);
                $this->set('sfacilities',$sfacilities);
                  
                }


              //queries for primary activity "Cmiscellaneous"
              if($StateDistrictFederation->sector_of_operation==35){

                $this->loadModel('CooperativeSocietyFacilities');
                $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,35]])->order(['orderseq'=>'ASC']);
                $this->set('facilities',$facilities);

                $this->loadMOdel('SdFederationCmiscellaneous');
                $sd_fed_cmiscellaneous = $this->SdFederationCmiscellaneous->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
                $this->set('sd_fed_cmiscellaneous',$sd_fed_cmiscellaneous);  
                }

                //queries for primary activity "Non Credit Miscellaneous"
                  if($StateDistrictFederation->sector_of_operation==29){

    
                    $this->loadMOdel('SdFederationMiscellaneous');
                    $sd_federation_miscellaneous = $this->SdFederationMiscellaneous->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
                    $this->set('sd_federation_miscellaneous',$sd_federation_miscellaneous);  
                    }

                    //queries for primary activity "Ucb"
                    if($StateDistrictFederation->sector_of_operation==7){

                        $this->loadMOdel('SdFederationUcb');
                        $sd_fed_ucb = $this->SdFederationUcb->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
                        $this->set('sd_fed_ucb',$sd_fed_ucb);

                        $this->loadMOdel('SdFederationImplementingSchemes');
                        $sd_fed_imp_sch = $this->SdFederationImplementingSchemes->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->toArray();
                        $this->set('sd_fed_imp_sch',$sd_fed_imp_sch);

                        //   echo "<pre>";
                        //   print_r($sd_fed_imp_sch);
                        //   die;
                        }


        $years = [];
        $l_year = date('Y')-122;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
        }
        //$_POST['is_draft']==1 || $_POST['is_draft']==0
  
        // var_dump($this->request->is('post'));
        
        if ($this->request->is(['patch', 'post', 'put'])){
            $data=$this->request->getData();
            // echo "workin";die;
      
           // $data=$this->request->getData();
            // echo "<pre>";
            // print_r($data);die;
            


            //array of pacs, dairy & fishery
            $arr_pdf = [1,20,22,9,10];
            if(!in_array($data['sector_of_operation'],$arr_pdf))
            {
                unset($data['pacs']);
                unset($data['area']);
                unset($data['sd_federation_dairy']);
                unset($data['sd_federation_fishery']);
            }
            

            $data['is_approved'] = 0;
            // $data['remark'] = '';
            $data['approved_by'] = '';
            $data['status'] = 1;
            $data['created'] = date('Y-m-d H:i:s');
            $data['updated'] = date('Y-m-d H:i:s');
			$data['operational_district_code'] = $data['district_code'] ?? 0;

            $data['cooperative_federation_name'] = trim($data['cooperative_federation_name']);
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
            
       
         
        
			
            if(!empty($data['landline']))
            {
                $std = $data['std'];
                $landline = $data['landline'];
                $data['landline'] = $std.'-'.$landline;
            } else {
                unset($data['std']);
            }
            $data['created_by'] = $this->request->session()->read('Auth.User.id');

            //always updated using ajax
            unset($data['sd_federation_members']);
            // if($data['is_federation_member'] == 0)
            // {
            //    $data['federation_member_count'] = 0; 
               
            // }



            if($data['other_member'] == 0)
            {
               $data['other_member_count'] = 0; 
               unset($data['sd_federation_other_members']);
            }

            // echo "<pre>";
            // print_r($data);die;

            if(!empty($data['sd_federation_other_members']) && $data['other_member'] == 1)
                {
                

                foreach($data['sd_federation_other_members'] as $key=>$other_member_data)
                {   

                    

                    if($other_member_data['type_membership'] == 2 || $other_member_data['type_membership'] == 3)
                    {

                        if($other_member_data['member_document']['name'] == '')
                        {

                            $this->loadModel('SdFederationOtherMembers');
                            $member_document = $this->SdFederationOtherMembers->find('all')->where(['state_district_federation_id' => $state_district_federation_id,'type_membership'=>$other_member_data['type_membership']])->first()->member_document;

                            $data['sd_federation_other_members'][$key]['member_document']  = $member_document;
                        } else {
                        
                        
                        
                        $ext = pathinfo($other_member_data['member_document']['name'], PATHINFO_EXTENSION);


                        $other_member_data['member_document']['name'] = 'document_member_'.$this->request->session()->read('Auth.User.state_code').'_'.$key.'_'.date('Y_m_d_H_i_s').'.'.$ext;


                        if($other_member_data['member_document']['name']!=''){
                            $fileName = $this->uploadPdf('member_document', $other_member_data['member_document']);
                            $data['sd_federation_other_members'][$key]['member_document']  = $fileName['filename'];
                        }
                      }

                  } else {
                        $data['sd_federation_other_members'][$key]['member_document'] = '';
                    }
                }
            }


           
			//if(!empty($data['federation_type']) &&  !empty($data['affiliated_union_federation_name']) && !empty($data['sector_of_operation_type']) &&  !empty($data['sector_of_operation']) && !empty($data['state_code']) &&  !empty($data['district_code'])){
              //  $federation_code = $data['federation_type'].'-'.$data['affiliated_union_federation_name'].'-'.$data['sector_of_operation_type'].'-'.$data['sector_of_operation'].'-'.$data['state_code'].'-'.$data['district_code'];
              //  $data['federation_code']=$federation_code;
            //} 
			
			$data['federation_code']=$this->fedCode($data['sector_of_operation_type'],$data['sector_of_operation'],$data['federation_type'],$data['location_of_head_quarter'],$data['state_code'],$data['district_code'],$data['registration_number']) ;
            
            
            
           
            
            $data['date_registration'] = date("Y-m-d",strtotime(str_replace("/","-",$data['date_registration'])));


        

            $this->loadModel('SdFederationOtherMembers');
            $this->SdFederationOtherMembers->deleteAll([       
                'SdFederationOtherMembers.state_district_federation_id'=>$state_district_federation_id
              ]);

    
          $StateDistrictFederation = $this->StateDistrictFederations->patchEntity($StateDistrictFederation, $data,['associated'=>['SdFederationOtherMembers']]);

          
          
           
                if($this->StateDistrictFederations->save($StateDistrictFederation)) {
                    
                    
                    $this->loadModel('SdFederationMembers');
                    if(!empty($StateDistrictFederation['sd_federation_members'])){

                        $this->SdFederationMembers->deleteAll([       
                            'SdFederationMembers.state_district_federation_id'=>$state_district_federation_id
                          ]);


                       foreach($StateDistrictFederation['sd_federation_members'] as $key=>$coop_reg_id){
                        $SdFederationMember = $this->SdFederationMembers->newEntity();
                        $SdFederationMember->state_district_federation_id = $StateDistrictFederation->id;
                        $SdFederationMember->cooperative_registration_id =  $coop_reg_id;
                        $this->SdFederationMembers->save($SdFederationMember);
                    }
                }

            
                    if($StateDistrictFederation->operation_area_location == 2 || $StateDistrictFederation->operation_area_location == 3){
                    $this->loadModel('SdFederationRurals');

                    $this->SdFederationRurals->deleteAll([       
                        'SdFederationRurals.state_district_federation_id'=>$state_district_federation_id
                      ]);
                    
                        foreach($StateDistrictFederation['sector'] as $key=>$sector_rural_data){
                         $SdFederationRural = $this->SdFederationRurals->newEntity();
                         $SdFederationRural->state_district_federation_id = $StateDistrictFederation->id;
                         $SdFederationRural->state_code = $StateDistrictFederation->state_code;
                         $SdFederationRural->district_code = $sector_rural_data['district_code'];
                         $SdFederationRural->block_code =  $sector_rural_data['block_code'];
                         $this->SdFederationRurals->save($SdFederationRural);
                     }
                 }
                        
                 if($StateDistrictFederation->operation_area_location == 1 || $StateDistrictFederation->operation_area_location == 3){
                $this->loadModel('SdFederationUrbans');

                $this->SdFederationUrbans->deleteAll([       
                    'SdFederationUrbans.state_district_federation_id'=>$state_district_federation_id
                  ]);

                foreach($StateDistrictFederation['sector_urban'] as $key=>$sector_urban_data){
                 $SdFederationUrban = $this->SdFederationUrbans->newEntity();
                 $SdFederationUrban->state_district_federation_id = $StateDistrictFederation->id;
                 $SdFederationUrban->state_code = $StateDistrictFederation->state_code;
                 $SdFederationUrban->local_body_type_code = $sector_urban_data['local_body_type_code'];
                 $SdFederationUrban->local_body_code = $sector_urban_data['local_body_code'];
                 $SdFederationUrban->district_code = $sector_urban_data['district_code'];
                 $SdFederationUrban->block_code =  $sector_urban_data['block_code'];
                 $this->SdFederationUrbans->save($SdFederationUrban);
             }
            }


            
              if($data['sector_of_operation'] == 77)
                 {

                     $this->loadModel('SdFederationAgriculture');

                     $this->SdFederationAgriculture->deleteAll([       
                        'SdFederationAgriculture.state_district_federation_id'=>$state_district_federation_id
                      ]);


                    $data['sd_federation_agriculture']['farming_mech'] = implode(',',$data['sd_federation_agriculture']['farming_mech']);
                    $data['sd_federation_agriculture']['irrigation_means'] = implode(',',$data['sd_federation_agriculture']['irrigation_means']);
                     $data['sd_federation_agriculture']['state_district_federation_id'] = $StateDistrictFederation->id;

                     $agriculture = $this->SdFederationAgriculture->newEntity();
                     
                     $agriculture = $this->SdFederationAgriculture->patchEntity($agriculture, $data['sd_federation_agriculture']);
                     $this->SdFederationAgriculture->save($agriculture);

                     
                 }

                

                 if($data['sector_of_operation'] == 47)
                 {

                    $this->loadModel('SdFederationLands');
                    $this->loadModel('SdFederationHousing');

                    $this->SdFederationLands->deleteAll([       
                        'SdFederationLands.state_district_federation_id'=>$state_district_federation_id
                      ]);
                      $this->SdFederationHousing->deleteAll([       
                        'SdFederationHousing.state_district_federation_id'=>$state_district_federation_id
                      ]);

                     if($data['sd_federation_housing']['has_land']==1){
                        $data['sd_federation_lands']['state_district_federation_id'] = $StateDistrictFederation->id;
                        $land = $this->SdFederationLands->newEntity();
                        $land = $this->SdFederationLands->patchEntity($land, $data['sd_federation_lands']);
                        $this->SdFederationLands->save($land);
                     }

                     
                     $data['sd_federation_housing']['facilities'] = implode(',',$data['sd_federation_housing']['facilities']);
                     $data['sd_federation_housing']['state_district_federation_id'] = $StateDistrictFederation->id;

                     $housing = $this->SdFederationHousing->newEntity();
                     $housing = $this->SdFederationHousing->patchEntity($housing, $data['sd_federation_housing']);
                     $this->SdFederationHousing->save($housing);
                 }
        
        
                
                // Non Credit Marketing data
                if($data['sector_of_operation'] ==  82)
                {

                    $this->loadModel('SdFederationLands');
                    $this->loadModel('SdFederationMarketing');

                    $this->SdFederationLands->deleteAll([       
                        'SdFederationLands.state_district_federation_id'=>$state_district_federation_id
                      ]);
                      $this->SdFederationMarketing->deleteAll([       
                        'SdFederationMarketing.state_district_federation_id'=>$state_district_federation_id
                      ]);

                    if($data['sd_federation_marketing']['has_land']==1){
                       
                        $data['sd_federation_lands']['state_district_federation_id'] = $StateDistrictFederation->id;

                        $housingLand = $this->SdFederationLands->newEntity();
                        $housingLand = $this->SdFederationLands->patchEntity($housingLand, $data['sd_federation_lands']);
                        $this->SdFederationLands->save($housingLand);
                    }

    
                $data['sd_federation_marketing']['state_district_federation_id'] = $StateDistrictFederation->id;
                $data['sd_federation_marketing']['liecense_to_sell'] = implode(',',$data['sd_federation_marketing']['liecense_to_sell']);
                $data['sd_federation_marketing']['sell_the_item'] = implode(',',$data['sd_federation_marketing']['sell_the_item']);

                $marketing = $this->SdFederationMarketing->newEntity();
                $marketing = $this->SdFederationMarketing->patchEntity($marketing, $data['sd_federation_marketing']);
                $this->SdFederationMarketing->save($marketing);
            }
            


                // Non Credit Sugar data
                if($data['sector_of_operation'] ==  11)
                {
                    $this->loadModel('SdFederationSugar');
                    $this->SdFederationSugar->deleteAll([       
                        'SdFederationSugar.state_district_federation_id'=>$state_district_federation_id
                      ]);


                    $data['sd_federation_sugar']['crushing_period_start'] = date("Y-m-d",strtotime(str_replace("/","-",$data['sd_federation_sugar']['crushing_period_start'])));
                    $data['sd_federation_sugar']['crushing_period_end'] = date("Y-m-d",strtotime(str_replace("/","-",$data['sd_federation_sugar']['crushing_period_end'])));

                    $data['sd_federation_sugar']['state_district_federation_id'] = $StateDistrictFederation->id;
                    $data['sd_federation_sugar']['product_produced'] = implode(',',$data['sd_federation_sugar']['product_produced']);

                    $sugar = $this->SdFederationSugar->newEntity();
                    $sugar = $this->SdFederationSugar->patchEntity($sugar, $data['sd_federation_sugar']);
                    $this->SdFederationSugar->save($sugar);
                }

                    // Non Credit Consumer data
                    if($data['sector_of_operation'] == 80)
                    {

                        $this->loadModel('SdFederationConsumer');

                        $this->SdFederationConsumer->deleteAll([       
                            'SdFederationConsumer.state_district_federation_id'=>$state_district_federation_id
                          ]);                   

                        $data['sd_federation_consumer']['state_district_federation_id'] = $StateDistrictFederation->id;
                        $data['sd_federation_consumer']['facilities'] = implode(',',$data['sd_federation_consumer']['facilities']);

                        $consumer = $this->SdFederationConsumer->newEntity();
                        $consumer = $this->SdFederationConsumer->patchEntity($consumer, $data['sd_federation_consumer']);
                        $this->SdFederationConsumer->save($consumer);
                    }



            if($data['sector_of_operation'] == 9)
                 {

                     $this->loadModel('SdFederationDairy');

                     $this->SdFederationDairy->deleteAll([       
                        'SdFederationDairy.state_district_federation_id'=>$state_district_federation_id
                      ]);

                      $data['sd_federation_dairy']['state_district_federation_id'] = $StateDistrictFederation->id;
                     $dairy = $this->SdFederationDairy->newEntity();
                     
                     $dairy = $this->SdFederationDairy->patchEntity($dairy, $data['sd_federation_dairy']);
                     $this->SdFederationDairy->save($dairy);

                    

                     
                 }

                 if($data['sector_of_operation'] == 10)
                 {

                     $this->loadModel('SdFederationFishery');

                     $this->SdFederationFishery->deleteAll([       
                        'SdFederationFishery.state_district_federation_id'=>$state_district_federation_id
                      ]);

                      $data['sd_federation_fishery']['state_district_federation_id'] = $StateDistrictFederation->id;
                     $fishery = $this->SdFederationFishery->newEntity();
                     
                     $fishery = $this->SdFederationFishery->patchEntity($fishery, $data['sd_federation_fishery']);
                     $this->SdFederationFishery->save($fishery);

                     
                 }

                    // Non Credit Handloom data
                    if($data['sector_of_operation'] == 13)
                    {
                    
                        $this->loadModel('SdFederationHandloom');
                        $this->SdFederationHandloom->deleteAll([       
                            'SdFederationHandloom.state_district_federation_id'=>$state_district_federation_id
                          ]);

                        $data['sd_federation_handloom']['state_district_federation_id'] = $StateDistrictFederation->id;
   
                        $handloom = $this->SdFederationHandloom->newEntity();
                        $handloom = $this->SdFederationHandloom->patchEntity($handloom, $data['sd_federation_handloom']);
                        $this->SdFederationHandloom->save($handloom);
                    }


                 //queries for primary activity "Processing"
                if($data['sector_of_operation'] ==  31)
                {
                    $this->loadModel('SdFederationProcessing');

                    $this->SdFederationProcessing->deleteAll([       
                        'SdFederationProcessing.state_district_federation_id'=>$state_district_federation_id
                      ]);

                    $data['sd_federation_processing']['state_district_federation_id'] = $StateDistrictFederation->id;

                    $processing = $this->SdFederationProcessing->newEntity();
                    $processing = $this->SdFederationProcessing->patchEntity($processing, $data['sd_federation_processing']);
                    $this->SdFederationProcessing->save($processing);
                }

                //queries for primary activity "Credit"
                if($data['sector_of_operation'] ==  18)
                {
                    $this->loadModel('SdFederationCredit');

                    $this->SdFederationCredit->deleteAll([       
                        'SdFederationCredit.state_district_federation_id'=>$state_district_federation_id
                      ]);

                    $data['sd_federation_credit']['state_district_federation_id'] = $StateDistrictFederation->id;
                    $data['sd_federation_credit']['facilities'] = implode(',',$data['sd_federation_credit']['facilities']);
                  
                    $credit = $this->SdFederationCredit->newEntity();
                    $credit = $this->SdFederationCredit->patchEntity($credit, $data['sd_federation_credit']);
                    $this->SdFederationCredit->save($credit);
                }

                //queries for primary activity "Labour"
                if($data['sector_of_operation'] == 51)
                 {

                     $this->loadModel('SdFederationLabour');

                     $this->SdFederationLabour->deleteAll([       
                        'SdFederationLabour.state_district_federation_id'=>$state_district_federation_id
                      ]);


                    $data['sd_federation_labour']['facilities'] = implode(',',$data['sd_federation_labour']['facilities']);

                     $data['sd_federation_labour']['state_district_federation_id'] = $StateDistrictFederation->id;

                     $labour = $this->SdFederationLabour->newEntity();
                     
                     $labour = $this->SdFederationLabour->patchEntity($labour, $data['sd_federation_labour']);
                     $this->SdFederationLabour->save($labour);

                     
                 }

                

                  //queries for primary activity "Bee"
                    if($data['sector_of_operation'] ==  79)
                    {

                        $this->loadModel('SdFederationBee');

                        $this->SdFederationBee->deleteAll([       
                            'SdFederationBee.state_district_federation_id'=>$state_district_federation_id
                        ]);

                        
                        $data['sd_federation_bee']['state_district_federation_id'] = $StateDistrictFederation->id;
                        $data['sd_federation_bee']['type_bee'] = implode(',',$data['sd_federation_bee']['type_bee']);
                        $data['sd_federation_bee']['type_product'] = implode(',',$data['sd_federation_bee']['type_product']);
                        $data['sd_federation_bee']['facilities'] = implode(',',$data['sd_federation_bee']['facilities']);

                        $bee = $this->SdFederationBee->newEntity();
                        $bee = $this->SdFederationBee->patchEntity($bee, $data['sd_federation_bee']);
                        $this->SdFederationBee->save($bee);
                    }



                    //queries for primary activity "Education"
                    if($data['sector_of_operation'] ==  84)
                    {

                        $this->loadModel('SdFederationEducation');

                        $this->SdFederationEducation->deleteAll([       
                            'SdFederationEducation.state_district_federation_id'=>$state_district_federation_id
                        ]);
                        if($data['sd_federation_education']['has_land']==1){
                            $this->loadModel('SdFederationLands');
                            $data['sd_federation_lands']['state_district_federation_id'] = $StateDistrictFederation->id;
     
                            $land = $this->SdFederationLands->newEntity();
                            $land = $this->SdFederationLands->patchEntity($land, $data['sd_federation_lands']);
                            $this->SdFederationLands->save($land);
                         }
                        
                        $data['sd_federation_education']['state_district_federation_id'] = $StateDistrictFederation->id;
                        $data['sd_federation_education']['facilities'] = implode(',',$data['sd_federation_education']['facilities']);

                        $education = $this->SdFederationEducation->newEntity();
                        $education = $this->SdFederationEducation->patchEntity($education, $data['sd_federation_education']);
                        $this->SdFederationEducation->save($education);
                    }

                    //queries for primary activity "Tribal"
                    if($data['sector_of_operation'] ==  102)
                    {

                        $this->loadModel('SdFederationTribal');

                        $this->SdFederationTribal->deleteAll([       
                            'SdFederationTribal.state_district_federation_id'=>$state_district_federation_id
                        ]);

                        
                        $data['sd_federation_tribal']['state_district_federation_id'] = $StateDistrictFederation->id;
                        $data['sd_federation_tribal']['facilities'] = implode(',',$data['sd_federation_tribal']['facilities']);

                        $tribal = $this->SdFederationTribal->newEntity();
                        $tribal = $this->SdFederationTribal->patchEntity($tribal, $data['sd_federation_tribal']);
                        $this->SdFederationTribal->save($tribal);
                    }

                      //queries for primary activity "Handicraft"
            if($data['sector_of_operation'] ==  14)
            {
                $this->loadModel('SdFederationHandicraft');

                $this->SdFederationHandicraft->deleteAll([       
                    'SdFederationHandicraft.state_district_federation_id'=>$state_district_federation_id
                  ]);

                $data['sd_federation_handicraft']['state_district_federation_id'] = $StateDistrictFederation->id;
                $data['sd_federation_handicraft']['type_raw'] = implode(',',$data['sd_federation_handicraft']['type_raw']);
                $data['sd_federation_handicraft']['type_produce'] = implode(',',$data['sd_federation_handicraft']['type_produce']);
                $data['sd_federation_handicraft']['facilities'] = implode(',',$data['sd_federation_handicraft']['facilities']);

                $handicraft = $this->SdFederationHandicraft->newEntity();
                $handicraft = $this->SdFederationHandicraft->patchEntity($handicraft, $data['sd_federation_handicraft']);
                $this->SdFederationHandicraft->save($handicraft);
            }

            //queries for primary activity "Transport"
                if($data['sector_of_operation'] ==  68)
                {

                    $this->loadModel('SdFederationTransport');

                    $this->SdFederationTransport->deleteAll([      
                        'SdFederationTransport.state_district_federation_id'=>$state_district_federation_id
                    ]);

                    
                    $data['sd_federation_transport']['state_district_federation_id'] = $StateDistrictFederation->id;

                    $transport = $this->SdFederationTransport->newEntity();
                    $transport = $this->SdFederationTransport->patchEntity($transport, $data['sd_federation_transport']);
                    $this->SdFederationTransport->save($transport);
                }

                //queries for primary activity "Tourismt"
                if($data['sector_of_operation'] ==  99)
                {

                    $this->loadModel('SdFederationTourism');

                    $this->SdFederationTourism->deleteAll([      
                        'SdFederationTourism.state_district_federation_id'=>$state_district_federation_id
                    ]);

                    
                    $data['sd_federation_tourism']['state_district_federation_id'] = $StateDistrictFederation->id;
                    $data['sd_federation_tourism']['facilities'] = implode(',',$data['sd_federation_tourism']['facilities']);

                    $tourism = $this->SdFederationTourism->newEntity();
                    $tourism = $this->SdFederationTourism->patchEntity($tourism, $data['sd_federation_tourism']);
                    $this->SdFederationTourism->save($tourism);
                }

                //queries for primary activity "Livestock"
                if($data['sector_of_operation'] ==  54)
                {

                    $this->loadModel('SdFederationLivestock');

                    $this->SdFederationLivestock->deleteAll([      
                        'SdFederationLivestock.state_district_federation_id'=>$state_district_federation_id
                    ]);

                    
                    $data['sd_federation_livestock']['state_district_federation_id'] = $StateDistrictFederation->id;
                    $data['sd_federation_livestock']['type_society'] = implode(',',$data['sd_federation_livestock']['type_society']);
                    $data['sd_federation_livestock']['type_produce'] = implode(',',$data['sd_federation_livestock']['type_produce']);
                    $data['sd_federation_livestock']['facilities'] = implode(',',$data['sd_federation_livestock']['facilities']);

                    $livestock = $this->SdFederationLivestock->newEntity();
                    $livestock = $this->SdFederationLivestock->patchEntity($livestock, $data['sd_federation_livestock']);
                    $this->SdFederationLivestock->save($livestock);
                }

                //queries for primary activity "Women"
                if($data['sector_of_operation'] == 15)
                {

                    $this->loadModel('SdFederationWocoop');
                    $this->SdFederationWocoop->deleteAll([       
                        'SdFederationWocoop.state_district_federation_id'=>$state_district_federation_id
                      ]);
                      $data['sd_federation_wocoop']['facilities'] = implode(',',$data['sd_federation_wocoop']['facilities']);
                    $data['sd_federation_wocoop']['state_district_federation_id'] = $StateDistrictFederation->id;

                    $women = $this->SdFederationWocoop->newEntity();
                    $women = $this->SdFederationWocoop->patchEntity($women, $data['sd_federation_wocoop']);
                    $this->SdFederationWocoop->save($women);

                }


                 //queries for primary activity "Multipurpose"
                 if($data['sector_of_operation'] == 16)
                 {
 
                     $this->loadModel('SdFederationMulti');
                     $this->SdFederationMulti->deleteAll([       
                         'SdFederationMulti.state_district_federation_id'=>$state_district_federation_id
                       ]);
 
                       $data['sd_federation_multi']['sec_activity'] = implode(',',$data['sd_federation_multi']['sec_activity']);
                    $data['sd_federation_multi']['facilities'] = implode(',',$data['sd_federation_multi']['facilities']);
                     $data['sd_federation_multi']['state_district_federation_id'] = $StateDistrictFederation->id;
 
                     $multi = $this->SdFederationMulti->newEntity();
                     $multi = $this->SdFederationMulti->patchEntity($multi, $data['sd_federation_multi']);
                     $this->SdFederationMulti->save($multi);
 
                 }

                 //queries for primary activity "Social"
                 if($data['sector_of_operation'] == 98)
                 {
 
                     $this->loadModel('SdFederationSocial');
                     $this->SdFederationSocial->deleteAll([       
                         'SdFederationSocial.state_district_federation_id'=>$state_district_federation_id
                       ]);
 
                     
                     $data['sd_federation_social']['state_district_federation_id'] = $StateDistrictFederation->id;
                     $data['sd_federation_social']['type_social_culture_activity'] = implode(',',$data['sd_federation_social']['type_social_culture_activity']);
                     $data['sd_federation_social']['facilities'] = implode(',',$data['sd_federation_social']['facilities']);
 
                     $social = $this->SdFederationSocial->newEntity();
                     $social = $this->SdFederationSocial->patchEntity($social, $data['sd_federation_social']);
                     $this->SdFederationSocial->save($social);
 
                 }


                  //queries for primary activity "Jute"
                if($data['sector_of_operation'] ==  90)
                {
                    $this->loadModel('SdFederationJute');

                    $this->SdFederationJute->deleteAll([       
                        'SdFederationJute.state_district_federation_id'=>$state_district_federation_id
                      ]);

                    $data['sd_federation_jute']['state_district_federation_id'] = $StateDistrictFederation->id;
                    $data['sd_federation_jute']['type_raw'] = implode(',',$data['sd_federation_jute']['type_raw']);
                    $data['sd_federation_jute']['type_produce'] = implode(',',$data['sd_federation_jute']['type_produce']);
                    $data['sd_federation_jute']['facilities'] = implode(',',$data['sd_federation_jute']['facilities']);

                    $jute = $this->SdFederationJute->newEntity();
                    $jute = $this->SdFederationJute->patchEntity($jute, $data['sd_federation_jute']);
                    $this->SdFederationJute->save($jute);
                }

                //queries for primary activity "sericulture"
                if($data['sector_of_operation'] ==  96)
                {
                    $this->loadModel('SdFederationSericulture');

                    $this->SdFederationSericulture->deleteAll([       
                        'SdFederationSericulture.state_district_federation_id'=>$state_district_federation_id
                      ]);

                    $data['sd_federation_sericulture']['state_district_federation_id'] = $StateDistrictFederation->id;
                    $data['sd_federation_sericulture']['type_society'] = implode(',',$data['sd_federation_sericulture']['type_society']);
                    $data['sd_federation_sericulture']['facilities'] = implode(',',$data['sd_federation_sericulture']['facilities']);

                    $sericulture = $this->SdFederationSericulture->newEntity();
                    $sericulture = $this->SdFederationSericulture->patchEntity($sericulture, $data['sd_federation_sericulture']);
                    $this->SdFederationSericulture->save($sericulture);
                }


                //queries for primary activity "Cmiscellaneous"
                if($data['sector_of_operation'] ==  35)
                {
                    $this->loadModel('SdFederationCmiscellaneous');

                    $this->SdFederationCmiscellaneous->deleteAll([       
                        'SdFederationCmiscellaneous.state_district_federation_id'=>$state_district_federation_id
                      ]);

                      $data['sd_federation_cmiscellaneous']['state_district_federation_id'] = $StateDistrictFederation->id;
                      $data['sd_federation_cmiscellaneous']['facilities'] = implode(',',$data['sd_federation_cmiscellaneous']['facilities']);
    
                      $cmiscellaneous = $this->SdFederationCmiscellaneous->newEntity();
                      $cmiscellaneous = $this->SdFederationCmiscellaneous->patchEntity($cmiscellaneous, $data['sd_federation_cmiscellaneous']);
                      $this->SdFederationCmiscellaneous->save($cmiscellaneous);
                }

                //queries for primary activity "Non Credit Miscellaneous"
                if($data['sector_of_operation'] ==  29)
                {
                    $this->loadModel('SdFederationMiscellaneous');

                    $this->SdFederationMiscellaneous->deleteAll([       
                        'SdFederationMiscellaneous.state_district_federation_id'=>$state_district_federation_id
                      ]);

                      $data['sd_federation_miscellaneous']['state_district_federation_id'] = $StateDistrictFederation->id;
    
                      $nmiscellaneous = $this->SdFederationMiscellaneous->newEntity();
                      $nmiscellaneous = $this->SdFederationMiscellaneous->patchEntity($nmiscellaneous, $data['sd_federation_miscellaneous']);
                      $this->SdFederationMiscellaneous->save($nmiscellaneous);
                }

                if($data['sector_of_operation'] ==  7)
                {
                    $this->loadModel('SdFederationImplementingSchemes');
                    $this->loadModel('SdFederationUcb');
                    $this->SdFederationImplementingSchemes->deleteAll([       
                        'SdFederationImplementingSchemes.state_district_federation_id'=>$state_district_federation_id
                      ]);

                      $this->SdFederationUcb->deleteAll([       
                        'SdFederationUcb.state_district_federation_id'=>$state_district_federation_id
                      ]);
                    
                    if($data['sd_federation_ucb']['is_gov_scheme_implemented']==1){
                        
                        foreach($data['sd_federation_implementing_schemes'] as $key=>$sch_arr){
                            $sch = $this->SdFederationImplementingSchemes->newEntity();
                            $sch->state_district_federation_id	=$StateDistrictFederation->id;
                            $sch->gov_scheme_name = $sch_arr['gov_scheme_name'];
                            $sch->gov_scheme_type = $sch_arr['gov_scheme_type'];
                            $sch->total_amount = $sch_arr['total_amount'];
                            // $sch = $this->SdFederationImplementingSchemes->patchEntity($sch, $data['sd_federation_implementing_schemes']);
                            $this->SdFederationImplementingSchemes->save($sch);
                        }
                    
                     }

                   
                    $data['sd_federation_ucb']['state_district_federation_id'] = $StateDistrictFederation->id;

                    $ucb = $this->SdFederationUcb->newEntity();
                    $ucb = $this->SdFederationUcb->patchEntity($ucb, $data['sd_federation_ucb']);
                    $this->SdFederationUcb->save($ucb);
                }
               

                $this->Flash->success(__('The Cooperative data has been saved.'));

                // if($this->request->session()->read('Auth.User.role_id') == 14)
                // {
                    if($data['is_draft'] == 1)
                    {
                        return $this->redirect(['action' => 'draft']);    
                    } else {
                        return $this->redirect(['action' => 'list']);    
                    }
                    
                // }
                // return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
           
        }
     
       //for local body
        $this->loadModel('SdFederationUrbans');
        $sector_details_urban = $this->SdFederationUrbans->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->toArray();
        $this->set('sector_details_urban',$sector_details_urban);
       
       
        $this->loadMOdel('UrbanLocalBodies');
        $sector_details_local_bodies = [];
        foreach($sector_details_urban as $key=>$value){

            $sector_details_local_bodies[$key] = $this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$value->local_body_type_code,'state_code'=>$state_code])->order(['local_body_name'=>'ASC'])->toArray();
        }
        $this->set('sector_details_local_bodies',$sector_details_local_bodies);
        
         //for blocks
        $this->loadModel('SdFederationRurals');
        $sector_details_rural = $this->SdFederationRurals->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->toArray();
        $this->set('sector_details_rural',$sector_details_rural);
        
       
        $this->loadMOdel('Blocks');
        $sector_details_blocks = [];
        foreach($sector_details_rural as $key=>$value){

            $sector_details_blocks[$key] = $this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$value->district_code])->order(['name'=>'ASC'])->toArray();
            
          //  $sector_details_blocks = array('-1'=>'Select All') + $sector_details_blocks;
        }
        $this->set('sector_details_blocks',$sector_details_blocks);
      
        // Start SecondaryActivities for dropdown
        // $this->loadModel('SecondaryActivities');
        // $SecondaryActivities=$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'sector_of_operation'=>$CooperativeRegistration->sector_of_operation_type])->order(['orderseq'=>'ASC']);

        // echo "<pre>";
        // print_r($sector_details_urban);
        // echo "<pre>";
        // print_r($sector_details_local_bodies);
        // echo "<pre>";
        // print_r($sector_details_rural);
        // echo "<pre>";
        // print_r($sector_details_blocks);
        // die;


        $curr_federation = '';
        //array of pacs, dairy & fishery
        $arr_pdf = [1,20,22,9,10];
        
        if(!in_array($data['sector_of_operation'],$arr_pdf))
        {
            $this->loadMOdel('PrimaryActivities');
            $curr_federation = $this->PrimaryActivities->find('all')->where(['id' =>$StateDistrictFederation['sector_of_operation']])->first()->function_name ?? '';
            

            
            if(!empty($curr_federation))
            {
                $curr_federation = str_replace("-","_",$curr_federation);
                $curr_federation = $curr_federation.'.ctp'; 
            }
        }
    
        // echo $curr_federation;die;
       

      
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

        $this->loadModel('OfficeBuildingTypes');
        $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('buildingTypes',$buildingTypes);

        ####### degination dropdown#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        ###################end ############


        // Start States for dropdown
            $this->loadModel('Districts'); 
            // $districts_two = array("-1" => 'Select All');
            // $districts_one=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC'])->toArray();
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC'])->toArray(); 
           // $districts =  array_merge($districts_two,$districts_one) ;
            $this->set('districts',$districts);
        // End States for dropdown
        
        //sector district level
        // if($data['area_of_operation_id'] == 3)
        // {
        //     $sector_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$CooperativeRegistration->sector['state_code']])->order(['name'=>'ASC']);
        //     $this->set('sector_districts',$sector_districts);
        // }

        // Start Blocks for dropdown // in use
        $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$StateDistrictFederation->district_code])->order(['name'=>'ASC']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown


        // Start Gram Panchayats for dropdown  // in use
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$StateDistrictFederation->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown



        // Start villages for dropdown // in use
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$StateDistrictFederation->gram_panchayat_code])->order(['village_name'=>'ASC']); 
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
            $this->loadModel('PrimaryActivities'); // in use
            $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$StateDistrictFederation->sector_of_operation_type,'status'=>1])->order(['orderseq'=>'ASC']);

            $this->loadModel('PrimaryActivities');
            $PrimaryActivitiesTotal=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        // End PrimaryActivities for dropdown
            
        // Start SecondaryActivities for dropdown
            // $this->loadModel('SecondaryActivities');
            // $SecondaryActivities=$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'sector_of_operation'=>$StateDistrictFederation->sector_of_operation_type])->order(['orderseq'=>'ASC']);

        // End SecondaryActivities for dropdown
        
        // Start PresentFunctionalStatus for dropdown
            $this->loadModel('PresentFunctionalStatus');
            $PresentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        // End PresentFunctionalStatus for dropdown

        
        // Start urban_local_bodies type for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown


        // Start urban_local_bodies for dropdown
       $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$StateDistrictFederation->urban_local_body_type_code,'state_code'=>$StateDistrictFederation->state_code])->order(['local_body_name'=>'ASC']);
            
        $this->set('localbodies',$localbodies);
        // end urban_local_bodies for dropdown


        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$StateDistrictFederation->urban_local_body_code])->order(['ward_name'=>'ASC']);

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
        $miscellaneoustypes =$this->MiscellaneousCooperativeSocieties->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1]);
        $this->set('miscellaneoustypes',$miscellaneoustypes);


       

        //  Start register_authorty dropdown registration_authorities
        $this->loadModel('RegistrationAuthorities');
        $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['orderseq'=>'ASC']);
        $this->set('register_authorities',$register_authorities);
        // End registration_authorities for dropdown

        $this->loadModel('TypeOfMemberships');
        

        $arr_other_type_member=$this->TypeOfMemberships->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $this->set('arr_other_type_member',$arr_other_type_member); 

        // $this->loadMOdel('CooperativeSocietyBanks');
        // $arr_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type IN'=>$bank_type])->order(['orderseq'=>'ASC'])->toArray();

       

        $this->loadModel('CooperativeSocietyBanks');

        // $arr_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1,'bank_type IN'=>$StateDistrictFederation->bank_type])->order(['orderseq'=>'ASC']);
        $arr_banks=$this->CooperativeSocietyBanks->find('list',['keyField'=>'id','valueField'=>'bank_name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);


        $sd_federation_members=$this->SdFederationMembers->find('list',['keyField'=>'id','valueField'=>'cooperative_registration_id'])->where(['state_district_federation_id'=>$state_district_federation_id])->toArray();
        
        $cooperative_society_name_arr = [];
        $fed_society = array_values($sd_federation_members);
        if(isset($fed_society) && !empty($fed_society))
        {
            $cooperative_society_name_arr=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'is_draft'=>0,'is_approved !='=>2,'id IN'=>$fed_society])->toArray();
        }
        

        

        $this->set(compact('curr_federation','arr_banks','StateDistrictFederation','PrimaryActivitiesTotal','CooperativeRegistration','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','years','state_code','cooperative_society_name_arr'));

        
        
    }

    public function view($state_district_federation_id = null)
    {
        $this->loadModel('StateDistrictFederations');
        $this->loadMOdel('SdFederationMembers');
        $this->loadModel('Districts');
        $this->loadModel('SdFederationCredit');
        $CooperativeRegistration = $this->StateDistrictFederations->get($state_district_federation_id);
      

      // $CooperativeRegistration = $this->StateDistrictFederations->get($state_district_federation_id, [
      //       'contain' => ['SdFederationAgriculture']
      //   ]);


        $this->loadMOdel('SdFederationAgriculture');
        $sd_fed_agri = $this->SdFederationAgriculture->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
        $this->set('sd_fed_agri',$sd_fed_agri);

      //  $state_code = $this->request->session()->read('Auth.User.state_code');


        $member_count = $this->SdFederationMembers->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->toArray();
        
        foreach($member_count as $k=>$v){
                $member_counts[] = $v['cooperative_registration_id'] ;
             }
       
        $this->loadMOdel('CooperativeRegistrations');
        if(!empty($member_count)){
        $cooperative_society_name_arr=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'is_draft'=>0,'is_approved !='=>2,'district_code'=>$CooperativeRegistration->district_code,'sector_of_operation'=>$CooperativeRegistration->sector_of_operation,'id IN'=> $member_counts])->toArray();
        $this->set('cooperative_society_name_arr',$cooperative_society_name_arr);
        } 

        $this->loadMOdel('SdFederationMembers');
        $sd_federation_members=$this->SdFederationMembers->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->toArray();
        $this->set('sd_federation_members',$sd_federation_members);


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
            
            $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->toArray();

            $affiliated_union_fed = [
                '0' => 'No',
                '1' => 'Yes',
            ];

            $category_of_audit = [
                '1' => 'A: Score more than 70',
                '2' => 'B: Score between 50 to 70',
                '3' => 'B: Score between 50 to 70',
                '4' => 'C: Score between 35 and 50',
                '5' => 'D: Score less than 35',
                '6' => 'Audit has not given any Score',
            ];

            $bankType = [
                '1' => 'Cooperative Bank',
                '2' => 'Commercial Bank',
            ];
            // $StateDistrictFederation->bank_type = explode(',',$StateDistrictFederation->bank_type);
            // $StateDistrictFederation->cooperative_society_bank_id = explode(',',$StateDistrictFederation->cooperative_society_bank_id);

            $facilities_cmiscellaneous = [
                '1' => 'Do not have basic amenities',
                '61' => 'Credit facility to non-members',
                '62' => 'Business Correspondence',
                '63' => 'Consumer/Thrift Store',
                '64' => 'Fair Price Shop',
                '65' => 'LPG distributorship',
            ];
            
            $this->loadMOdel('SdFederationCredit');
            $sd_fed_credit = $this->SdFederationCredit->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_fed_credit',$sd_fed_credit);

            $this->loadMOdel('SdFederationUcb');
            $sd_federation_ucb = $this->SdFederationUcb->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_federation_ucb',$sd_federation_ucb);

            $this->loadMOdel('SdFederationCmiscellaneous');
            $sd_federation_cmiscellaneous = $this->SdFederationCmiscellaneous->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_federation_cmiscellaneous',$sd_federation_cmiscellaneous);

            $this->loadMOdel('SdFederationBee');
            $sd_fed_bee_farm = $this->SdFederationBee->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_fed_bee_farm',$sd_fed_bee_farm);

            $this->loadMOdel('SdFederationMarketing');
            $sd_federation_marketing = $this->SdFederationMarketing->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_federation_marketing',$sd_federation_marketing);

            $this->loadMOdel('SdFederationLands');
            $sd_federation_lands = $this->SdFederationLands->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_federation_lands',$sd_federation_lands);

            $this->loadMOdel('SdFederationSugar');
            $sd_federation_sugar = $this->SdFederationSugar->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_federation_sugar',$sd_federation_sugar);

            $this->loadMOdel('SdFederationHousing');
            $sd_federation_housing = $this->SdFederationHousing ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_federation_housing',$sd_federation_housing);
                        
            $this->loadMOdel('SdFederationDairy');
            $sd_federation_dairy = $this->SdFederationDairy ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_federation_dairy',$sd_federation_dairy);
            
            $this->loadMOdel('SdFederationConsumer');
            $sd_federation_consumer = $this->SdFederationConsumer ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_federation_consumer',$sd_federation_consumer);

            $this->loadMOdel('SdFederationLabour');
            $sd_federation_labour = $this->SdFederationLabour ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_federation_labour',$sd_federation_labour);
            
            $this->loadMOdel('SdFederationHandloom');
            $sd_federation_handloom = $this->SdFederationHandloom ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_federation_handloom',$sd_federation_handloom);
            
            $this->loadMOdel('SdFederationFishery');
            $sd_federation_fishery = $this->SdFederationFishery ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_federation_fishery',$sd_federation_fishery);
            
            $this->loadMOdel('SdFederationHandicraft');
            $sd_federation_handicraft = $this->SdFederationHandicraft ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_federation_handicraft',$sd_federation_handicraft);

            $this->loadMOdel('SdFederationProcessing');
            $sd_federation_processing = $this->SdFederationProcessing ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_federation_processing',$sd_federation_processing);

            $this->loadMOdel('SdFederationWocoop');
            $sd_federation_wocoop = $this->SdFederationWocoop ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_federation_wocoop',$sd_federation_wocoop);

            $this->loadMOdel('SdFederationMulti');
            $sd_federation_multi = $this->SdFederationMulti ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            $this->set('sd_federation_multi',$sd_federation_multi);

            // $this->loadMOdel('SdFederationFishery');
            // $sd_federation_fishery = $this->SdFederationFishery ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            // $this->set('sd_federation_fishery',$sd_federation_fishery);
            // $this->loadMOdel('SdFederationFishery');
            // $sd_federation_fishery = $this->SdFederationFishery ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            // $this->set('sd_federation_fishery',$sd_federation_fishery);
            // $this->loadMOdel('SdFederationFishery');
            // $sd_federation_fishery = $this->SdFederationFishery ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            // $this->set('sd_federation_fishery',$sd_federation_fishery);
            // $this->loadMOdel('SdFederationFishery');
            // $sd_federation_fishery = $this->SdFederationFishery ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            // $this->set('sd_federation_fishery',$sd_federation_fishery);
            // $this->loadMOdel('SdFederationFishery');
            // $sd_federation_fishery = $this->SdFederationFishery ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            // $this->set('sd_federation_fishery',$sd_federation_fishery);
            // $this->loadMOdel('SdFederationFishery');
            // $sd_federation_fishery = $this->SdFederationFishery ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            // $this->set('sd_federation_fishery',$sd_federation_fishery);
            // $this->loadMOdel('SdFederationFishery');
            // $sd_federation_fishery = $this->SdFederationFishery ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            // $this->set('sd_federation_fishery',$sd_federation_fishery);
            // $this->loadMOdel('SdFederationFishery');
            // $sd_federation_fishery = $this->SdFederationFishery ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            // $this->set('sd_federation_fishery',$sd_federation_fishery);
            // $this->loadMOdel('SdFederationFishery');
            // $sd_federation_fishery = $this->SdFederationFishery ->find('all')->where(['state_district_federation_id'=>$state_district_federation_id])->first();
            // $this->set('sd_federation_fishery',$sd_federation_fishery);

        $this->set(compact('CooperativeRegistration','creditPrimaryActivities','presentFunctionalStatus','locationOfHeadquarter','blockName','districtName','panchayatName','villageName','nonCreditPrimaryActivities','area_of_operation_id_rural','area_of_operation_id_urban','areaOfOperations','arr_banks','districts','affiliated_union_fed','category_of_audit','bankType','sd_fed_credit','sd_federation_ucb','sd_federation_cmiscellaneous','sd_fed_bee_farm','sd_federation_marketing','sd_federation_lands','sd_federation_sugar','SdFederationHousing','sd_federation_dairy','sd_federation_consumer','sd_federation_labour','sd_federation_handloom','sd_federation_fishery','sd_federation_handicraft','sd_federation_processing'));
       
        
    }

    public function draft(){

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
            $search_condition[] = "cooperative_federation_name like '%" . $societyName . "%'";
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
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }

     
        $state_code= $this->request->session()->read('Auth.User.state_code');
        //$role_id = $this->request->session()->read('Auth.User.role_id');
        // $this->loadMOdel('CooperativeFederations');
        // $cooperative_federations_object = $this->CooperativeFederations->find('all')->where(['state_code'=>$state_code,'role_id'=>$role_id])->first();

        $this->loadMOdel('StateDistrictFederations');
        // $search_query =  $this->StateDistrictFederations->find('all')->where(['state_code'=>$state_code]);

        $search_query = $this->StateDistrictFederations->find('all', [
            'order' => ['id' => 'DESC'],
            'conditions' => [$searchString,'created_by'=>$this->request->session()->read('Auth.User.id')]
        ])->where(['is_draft'=>1,'is_approved'=>0,'status'=>1,'state_code'=>$state_code]);

     
        // $search_query = $this->CooperativeFederations->find('all')->where([$searchString,'role_id'=>$role_id]);
       $this->paginate = ['limit' => 20];
       $state_district_federations_arr = $this->paginate($search_query);


        //Start Class of Membership for dropdown
        $class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        
    

        //Start type_of_memberships for dropdown
        $type_of_memberships=[1=>'Primary Cooperative Societies',2=>'District Union/Federations',3=>'State Federations',4=>'Multi-State Cooperative Society'];
       

        //Start Major Activities for dropdown
        $this->loadMOdel('MajorActivities');
        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 

        //Start location_of_head_quarter for dropdown
        $location_of_head_quarter=[1=>'Urban',2=>'Rural']; 
        
        $this->loadModel('States');
        $states_find=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
        
        if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2,59]))
        {
            $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
        }
       
        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$CooperativeFederations->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  

        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$CooperativeFederations->urban_local_body_type_code,'state_code'=>$CooperativeFederations->state_code])->order(['local_body_name'=>'ASC'])->toArray();

        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$CooperativeFederations->urban_local_body_code])->order(['ward_name'=>'ASC'])->toArray();
    
        $this->loadModel('Designations');
       $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();

        $this->set(compact('cooperative_society_name_arr','state_district_federations_arr','role_id','cooperative_federations_object','CooperativeFederations','class_of_memberships','type_of_memberships','major_activities','states_find','location_of_head_quarter','localbody_types','localbodies','localbodieswards','designations','FisheryFederations'));
    }

    public function list(){

        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');


        $search_condition = array();
        $page_length = !empty($this->request->data['page_length']) ? $this->request->data['page_length'] : 20;
        $page = !empty($this->request->data['page']) ? $this->request->data['page'] : 1;

        if (!empty($this->request->data['society_name'])) {
            $societyName = trim($this->request->data['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "cooperative_federation_name like '%" . $societyName . "%'";
        }

        if (!empty($this->request->data['registration_number'])) {
            $registrationNumber = trim($this->request->data['registration_number']);
            $this->set('registrationNumber', $registrationNumber);
            $search_condition[] = "registration_number like '%" . $registrationNumber . "%'";
        }

        if (!empty($this->request->data['federation_code'])) {
            $federationCode = trim($this->request->data['federation_code']); 
			$federationCodes = explode("-" ,$federationCode) ; 
            $this->set('federationCode', $federationCode); 
            $search_condition[] = "id ='" . (int)$federationCodes[2] . "%'";
        }


        if (isset($this->request->data['state']) && $this->request->data['state'] !='') {
            $state = trim($this->request->data['state']);
            $this->set('state', $state);
            $search_condition[] = "state_code = '" . $state . "'";
        }

        if (isset($this->request->data['district']) && $this->request->data['district'] !='') {
            $s_district = trim($this->request->data['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "district_code = '" . $s_district . "'";
        }

        if (isset($this->request->data['federation_type']) && $this->request->data['federation_type'] !='') {
            $fed_type = trim($this->request->data['federation_type']);
            $this->set('fed_type', $fed_type);
            $search_condition[] = "federation_type = '" . $fed_type . "'";
        }

        if (isset($this->request->data['primary_activity']) && $this->request->data['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->data['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);
            $search_condition[] = "sector_of_operation = '" . $s_primary_activity . "'";
        }

        if (isset($this->request->data['location']) && $this->request->data['location'] !='') {
            $location = trim($this->request->data['location']);
            $this->set('location', $location);
            $search_condition[] = "location_of_head_quarter = '" . $location . "'";
        }

        if (isset($this->request->data['sector_operation']) && $this->request->data['sector_operation'] !='') {
            $s_sector_operation = trim($this->request->data['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "sector_of_operation_type = '" . $s_sector_operation . "'";
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
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }
        
       
        $state_code= $this->request->session()->read('Auth.User.state_code');
        $this->loadMOdel('StateDistrictFederations');

        if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
        {
            $search_query = $this->StateDistrictFederations->find('all', [
                'order' => ['id' => 'DESC'],
                'conditions' => [$searchString]
            ])->where(['is_draft'=>0,'is_approved'=>0,'status'=>1]);

        }elseif($this->request->session()->read('Auth.User.role_id') == 11){

            $search_query = $this->StateDistrictFederations->find('all', [
                'order' => ['id' => 'DESC'],
                'conditions' => [$searchString]
            ])->where(['is_draft'=>0,'is_approved'=>0,'status'=>1,'state_code'=>$state_code]);

        } else {
            $search_query = $this->StateDistrictFederations->find('all', [
            'order' => ['id' => 'DESC'],
            'conditions' => [$searchString,'created_by'=>$this->request->session()->read('Auth.User.id')]
        ])->where(['is_draft'=>0,'is_approved'=>0,'status'=>1,'state_code'=>$state_code]);
        }

       $this->paginate = ['limit' => 20];
       $state_district_federations_arr = $this->paginate($search_query);
       $this->set(compact('state_district_federations_arr'));

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



       $districts = [];

       if(!empty($this->request->data['state']))
       {
           $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->data['state']])->order(['name'=>'ASC'])->toArray();
       }

       $this->set('districts',$districts);

       $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

        $this->set('arr_districts',$arr_districts);


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

        if(!empty($this->request->data['export_excel'])) {

                if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
                    {
                        $queryExport = $this->StateDistrictFederations->find('all', [
                            'order' => ['id' => 'DESC'],
                            'conditions' => [$searchString]
                        ])->where(['is_draft'=>0,'is_approved'=>0,'status'=>1]);

                    }elseif($this->request->session()->read('Auth.User.role_id') == 11){

                        $queryExport = $this->StateDistrictFederations->find('all', [
                            'order' => ['id' => 'DESC'],
                            'conditions' => [$searchString]
                        ])->where(['is_draft'=>0,'is_approved'=>0,'status'=>1,'state_code'=>$state_code]);

                    } else {
                        $queryExport = $this->StateDistrictFederations->find('all', [
                        'order' => ['id' => 'DESC'],
                        'conditions' => [$searchString,'created_by'=>$this->request->session()->read('Auth.User.id')]
                    ])->where(['is_draft'=>0,'is_approved'=>0,'status'=>1,'state_code'=>$state_code]);
                    }



                $queryExport->hydrate(false);
                $ExportResultData = $queryExport->toArray();
                $fileName = "cooperative_federations_list-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No",  "Cooperative Federation Name", "State Name", "District Name","Sector Name","Level of Federation","Registration No","No of Members","Federation Code");
                $data = array();
                $i=1;
                $federation_type = ['2'=> 'State Federation','3' => 'District Federation','4'=> 'Block/Taluka/Mandal Federation','5' => 'Regional Federation'];
                foreach($ExportResultData As $rows){ 
				$code = str_pad($rows['sector_of_operation'], 3, '0', STR_PAD_LEFT)."-".str_pad($rows['federation_type'], 2, '0', STR_PAD_LEFT)."-".str_pad($rows['id'], 5, '0', STR_PAD_LEFT);	
                    $data[]=array($i, $rows['cooperative_federation_name'], $stateOption[$rows['state_code']], $arr_districts[$rows['district_code']],  $primary_activities[$rows['sector_of_operation']], $federation_type[$rows['federation_type']], $rows['registration_number'], $rows['primary_cooperative_member_count'], $code);
                    $i++;
                } 
                $this->exportInExcel($fileName, $headerRow, $data);
            }

       
    }

    public function getFed(){
        $this->viewBuilder()->setLayout('ajax');
            if($this->request->is('ajax')){
                $primary_activity_selected_val= $this->request->data('primary_activity_selected_val');
                $district_code= $this->request->data('district_code');
                $state_code= $this->request->data('state_code');
                $curr_id= $this->request->data('curr_id');
                $x="";
                $member_count = 0;
                
                $this->loadMOdel('CooperativeRegistrations');
                $this->loadMOdel('SdFederationMembers');
               

                $sector_fed_members_arr = $this->SdFederationMembers->find('list',['keyField'=>'id','valueField'=>'cooperative_registration_id'])->where(['state_district_federation_id'=>$curr_id,'primary_activity'=>$primary_activity_selected_val])->toArray();
                $societies = [];
                if(!empty($sector_fed_members_arr))
                {
                    $societies = array_values($sector_fed_members_arr);   
                   
                }

               // $x.= '<tr><td colspan="9"><button class="btn btn-success" name="save_member" value="save_member" id="save_members" type="button" >Save Members</button></td></tr>';

                if($this->request->session()->read('Auth.User.role_id') == 11)
                {
                    $cooperative_society_name_arr=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'is_draft'=>0,'is_approved !='=>2,'state_code'=>$state_code,'sector_of_operation'=>$primary_activity_selected_val])->toArray();
                }

                if($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8)
                {
                    $cooperative_society_name_arr=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'is_draft'=>0,'is_approved !='=>2,'state_code'=>$state_code,'district_code'=>$district_code,'sector_of_operation'=>$primary_activity_selected_val])->toArray();
                }                

                $this->loadModel('Districts');
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC'])->toArray();
                // echo "<pre>";
                // print_r($cooperative_society_name_arr);

                $this->loadModel('PrimaryActivities');
                $PrimaryActivitiesTotal=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
                
                // die;
                
                
                  
                if(count($cooperative_society_name_arr) > 0){
                    $slNo = 1;
                    
                    foreach($cooperative_society_name_arr as $key=>$value){

                        $checked = "";
                        if(!empty($societies) && in_array($value->id,$societies))
                        {
                            $checked = "checked";
                        }

                       $x .= '<tr>
                        <td>'.$slNo.'</td>
                        <br>
                        <td>'.$value->cooperative_society_name.'</td>
                        <br>
                        <td>'.$PrimaryActivitiesTotal[$value->sector_of_operation].'</td>
                        <br>
                        <td>'.$districts[$value->district_code].'</td>
                        <br>
                        <td>'.$value->registration_number.'</td>
                        <br>
                        <td>'.$value->date_registration.'</td>
                       <td> <input type="checkbox" '.$checked.' class="member-checked" name="sd_federation_members['.$key.']" value="'.$value->id.'" > </td>
                        </tr>';

                        $slNo ++; }

                        
                }else{
                    
                    $x='<tr><td colspan="9">'.'Record not found!'.'</td></tr>';
                }

                $member_count = $this->SdFederationMembers->find('list',['keyField'=>'id','valueField'=>'cooperative_registration_id'])->where(['state_district_federation_id'=>$curr_id])->count();
               
                $res = [];

                $res['table'] = $x;
                $res['count'] = $member_count;

                $result = json_encode($res);
                $this->response->type('json');
                $this->response->body($result);
                return $this->response;
                //return $res;
            //    return $x;
                
            }
            //exit;
    }

    public function addOtherMemberRow()
    {
        
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            // echo "22";
            $row_count= $this->request->data('row_count');
            // echo $row_count;
            // die;
        }

        // $this->loadModel('HousingSocietyTypes');
        // $societytypes =$this->HousingSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        // $this->set('societytypes',$societytypes);

        // $this->loadModel('OfficeBuildingTypes');
        // $buildingTypes =$this->OfficeBuildingTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        // $this->set('buildingTypes',$buildingTypes);

        // $this->loadModel('CooperativeSocietyFacilities');
        // $facilities =$this->CooperativeSocietyFacilities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'primary_activity_id IN'=>[0,47]])->order(['orderseq'=>'ASC']);
        // $this->set('facilities',$facilities);

        // $CooperativeRegistration=$this->request->data['cs'];
        // $this->set('CooperativeRegistration',$CooperativeRegistration);
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
        $this->loadModel('StateDistrictFederations');
        $this->request->allowMethod(['post', 'delete']);
        $StateDistrictFederation = $this->StateDistrictFederations->get($id);


        $data['status'] = 0;
        $data['updated_by'] = $this->request->session()->read('Auth.User.id');
        $data['updated'] = date('Y-m-d H:i:s');
        $StateDistrictFederation = $this->StateDistrictFederations->patchEntity($StateDistrictFederation, $data);

        if($this->StateDistrictFederations->save($StateDistrictFederation)) {
            $this->Flash->success(__('The Federation Record has been deleted.'));
        }
        $this->redirect($this->referer());

    }

    public function getDistricts(){ 

        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
       
            $state_code=$this->request->data('state_code'); 
            // $state_code=$_GET['state_code'] ;  
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
    
    public function getPrimaryActivity(){
        $this->viewBuilder()->setLayout('ajax');
            if($this->request->is('ajax')){
                
                $sector_operation = $this->request->data('sector_operation');    

                

                $this->loadMOdel('PrimaryActivities');

                if($sector_operation==1){
                $pactivities = $this->PrimaryActivities->find('all')->where(['sector_of_operation'=>$sector_operation,'status'=>1, 'id NOT IN'=>[1,20,22]])->order(['name'=>'ASC']);
                }else{
                    $pactivities = $this->PrimaryActivities->find('all')->where(['sector_of_operation'=>$sector_operation,'status'=>1])->order(['name'=>'ASC']);
                }
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
                $form_filling_for = $this->request->data('form_filling_for');  //1 for state and 2 for district
                
                $this->loadMOdel('LevelOfAffiliatedUnion');          
                // $SecondaryActivities = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1,'primary_activity_id'=>$primary_activity_id,'id !='=>1])->order(['orderseq'=>'ASC'])->toArray();

                $SecondaryActivities = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1,'primary_activity_id'=>$primary_activity_id,'id !='=>1,'affiliated_level <'=>$form_filling_for])->order(['orderseq'=>'ASC'])->toArray();
                // echo "<pre>";
                // print_r($SecondaryActivities);die;
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

    

    public function otherMemberAddRow()
    {
        $this->viewBuilder()->setLayout('ajax');

        $this->loadModel('PrimaryActivities');
        $this->loadModel('Districts');
        
        $ohr2 = $this->request->data('ohr2'); 
        $this->set('ohr2',$ohr2);

        $arr_class_member = ['1'=>'Ordinary/Regular/Voting','2'=>'Associate/Nominal/Voting'];
        $arr_type_member = ['1'=>'Primary Cooperative Societies','2'=>'District Central Cooperative Bank'];//,'3'=>'Any Other (Please Specify)'
          // Start TypesOfMembership for dropdown
        $this->loadModel('TypeOfMemberships');

        $arr_other_type_member=$this->TypeOfMemberships->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        //$this->set('arr_other_type_member',$arr_other_type_member); 
        
    // End TypesOfMembership for dropdown
       
        $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')])->order(['name'=>'ASC']);

        $this->set(compact('ohr2','arr_class_member','arr_type_member','PrimaryActivities','districts','arr_other_type_member'));
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