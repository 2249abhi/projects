<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

/**
 * CooperativeRegistrations Controller
 *
 * @property \App\Model\Table\CooperativeRegistrationsTable $CooperativeRegistrations
 *
 * @method \App\Model\Entity\State[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SocietyCorrectionController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['registrationName']);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
          $this->Auth->allow(['registrationName']);


    }

    public function registrationDate()
    {
        
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        
        $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $this->request->session()->write('prev_corr_url', $actual_link);

        if($this->request->session()->read('Auth.User.role_id') != 8)
        {

            $this->Flash->error(__('Only district Nodal is authorised to access this page'));
            return $this->redirect(['controller'=>'dashboard','action' => 'index']);
        }

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
        

        //is_approved=>0 (pending),is_approved=>1 (accepted),is_approved=>2 (rejected)
        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['CooperativeRegistrations.updated' => 'DESC'],
            'conditions' => [$searchString],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>1,'CooperativeRegistrations.status'=>1,'is_multi_state'=>0])
        ->where(['OR'=>
                        [
                            ['CooperativeRegistrations.date_registration >'=> (date('Y')+1).'-01-01'],
                            ['CooperativeRegistrations.date_registration <' => '1900-01-01',]
                        ]
                    ]);

        

        $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();

        $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);

        if(!empty($nodal_data_entry_ids)){
        $registrationQuery = $registrationQuery->where(['CooperativeRegistrations.created_by IN'=>$nodal_data_entry_ids]);
        }else {
            $registrationQuery = $registrationQuery->where(['CooperativeRegistrations.created_by'=>0]);
        }

        if(!empty($this->request->query['export_excel'])) {
                
            $fileName = "registration_date-".date("d-m-y:h:s").".xls";
            $headerRow = array("S.No", "Cooperative Society Name","State","District/Urban Local Body", "Primary Activity/Sector","Registration No.","Date of Registration");
            $data = array();
            
            $registrationQuery = $registrationQuery->toArray();
            
            foreach($registrationQuery as $key => $rows){

                $district_local_body = $rows['location_of_head_quarter'] == 2 ? $arr_districts[$rows['district_code']] : $arr_localbodies[$rows['urban_local_body_code']];
                
                $data[]=array(($key+1), $rows['cooperative_society_name'],$stateOption[$rows['state_code']], $district_local_body, $sectors[$rows['sector_of_operation']],$rows['registration_number'],date("d/m/Y", strtotime($rows['date_registration'])));
            }
            $this->exportInExcelNew($fileName, $headerRow, $data);
        }

        $this->paginate = ['limit' => 20];
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

    public function adminRegistrationDate()
    {
        
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadMOdel('Blocks');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {
            $societyName = trim($this->request->query['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "CooperativeRegistrations.cooperative_society_name like '%" . $societyName . "%'";
        }

        if (isset($this->request->query['functional_status']) && $this->request->query['functional_status'] !='') {
            $s_functional_status = trim($this->request->query['functional_status']);
            $this->set('s_functional_status', $s_functional_status);
            $search_condition[] = "CooperativeRegistrations.functional_status = '" . $s_functional_status . "'";
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

        if($this->request->session()->read('Auth.User.role_id') == 11){
            $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')],'order' => ['name' => 'ASC']]);
        } else {
            $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        }
        
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
        
        $arr_blocks = $this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       $this->set('arr_blocks',$arr_blocks);

        //is_approved=>0 (pending),is_approved=>1 (accepted),is_approved=>2 (rejected)
        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['CooperativeRegistrations.updated' => 'DESC'],
            'conditions' => [$searchString],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>1,'CooperativeRegistrations.status'=>1,'is_multi_state'=>0])
        ->where(['OR'=>
                        [
                            ['CooperativeRegistrations.date_registration >'=> (date('Y')+1).'-01-01'],
                            ['CooperativeRegistrations.date_registration <' => '1900-01-01',]
                        ]
                    ]);

        if($this->request->session()->read('Auth.User.role_id') == 11){
            $registrationQuery = $registrationQuery->where(['CooperativeRegistrations.state_code'=>$this->request->session()->read('Auth.User.state_code')]);
        }
        
    
            if(!empty($this->request->query['export_excel'])) {
                
                $fileName = "registration_date-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No", "Cooperative Society Name","State","District/Urban Local Body", "Primary Activity/Sector","Registration No.","Date of Registration");
                $data = array();
                
                $registrationQuery = $registrationQuery->toArray();
                
                foreach($registrationQuery as $key => $rows){

                    $district_local_body = $rows['location_of_head_quarter'] == 2 ? $arr_districts[$rows['district_code']] : $arr_localbodies[$rows['urban_local_body_code']];
                    
                    $data[]=array(($key+1), $rows['cooperative_society_name'],$stateOption[$rows['state_code']], $district_local_body, $sectors[$rows['sector_of_operation']],$rows['registration_number'],date("d/m/Y", strtotime($rows['date_registration'])));
                }
                $this->exportInExcelNew($fileName, $headerRow, $data);
            }
        

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);

        $this->set(compact('CooperativeRegistrations'));

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

        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('presentFunctionalStatus',$presentFunctionalStatus);
    }

     public function registrationName()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        
        $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $this->request->session()->write('prev_corr_url', $actual_link);

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

      


        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
             $nodal_data_entry_ids = [];
             $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();
            
             if(!empty($nodal_data_entry_ids)){
                 $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
                 $nodal_data_entry_ids=implode(",",$nodal_data_entry_ids);
                
                 $search_condition[]= " created_by IN (" . $nodal_data_entry_ids . ")";
             } else{
                 $search_condition[] = " created_by IN ('0','00')";
             }
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
        
       


            //is_approved=>0 (pending),is_approved=>1 (accepted),is_approved=>2 (rejected)
        //     $registrationQuery = $this->CooperativeRegistrations->find('all', [
        //         'order' => ['CooperativeRegistrations.updated' => 'DESC'],
        //         'conditions' => [$searchString , $search_condition2],
        //         'contain' => ['PrimaryActivities']
        //     ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>1,'CooperativeRegistrations.status'=>1,'CooperativeRegistrations.cooperative_society_name NOT REGEXP'=>'/^[a-z0-9 .\-]+$/i'])->limit(15)->toarray();
        //    echo "<pre>";
          //  echo "searchin".$searchString;

          //  echo "<br>";
           // print_r($search_condition);

        // Get the table instance for the 'posts' table
        $postsTable = TableRegistry::getTableLocator()->get('CooperativeRegistrations');

        // The regular expression pattern you want to negate
        $regexPattern = '[A-Za-z0-9]';

        // Fetch posts where the title does not match the regex pattern using custom query
        $query = $postsTable->find()        
        ->where(function ($exp, $q) use ($regexPattern) {
        return $exp->not($q->newExpr()->add("cooperative_society_name REGEXP '{$regexPattern}'"));
        })
        ->where(['is_draft' =>0,'is_approved'=>1,'status'=>1,$searchString]);
       
        if(!empty($this->request->query['export_excel'])) {
                
            $fileName = "registration_name-".date("d-m-y:h:s").".xls";
            $headerRow = array("S.No", "Cooperative Society Name","State","District/Urban Local Body", "Primary Activity/Sector","Registration No.","Date of Registration");
            $data = array();
            
            $query = $query->toArray();

            // echo '<pre>';
            // print_r($query);die;
            
            foreach($query as $key => $rows){

                $district_local_body = $rows['location_of_head_quarter'] == 2 ? $arr_districts[$rows['district_code']] : $arr_localbodies[$rows['urban_local_body_code']];
                $cooperative_society_name= str_replace('	','',$this->strClean(trim($rows['cooperative_society_name'])));
                
                $data[]=array(($key+1), $cooperative_society_name,$stateOption[$rows['state_code']], $district_local_body, $sectors[$rows['sector_of_operation']],$rows['registration_number'],date("d/m/Y", strtotime($rows['date_registration'])));
            }
            $this->exportInExcelNew($fileName, $headerRow, $data);
        }

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($query);     
    


        $this->set(compact('CooperativeRegistrations'));

        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);

       

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

       $districts = [];

       if(!empty($this->request->query['state']))
       {
           $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
       }

       $this->set('districts',$districts);

       $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

        $this->set('arr_districts',$arr_districts);


       $this->loadModel('SectorOperations');

       $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sectors',$sectors);  
     //  print_r($CooperativeRegistrations);
     //  die;

    }

    public function members()
    {
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');


        if($this->request->session()->read('Auth.User.role_id') != 8)
        {
            $this->Flash->error(__('Only district Nodal is authorised to access this page'));
            return $this->redirect(['controller'=>'dashboard','action' => 'index']);
        }

        $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $this->request->session()->write('prev_corr_url', $actual_link);

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (isset($this->request->query['members']) && $this->request->query['members'] !='') {
            $members = trim($this->request->query['members']);
            $this->set('members', $members);

            /*
            // 0 to 5, 5 to 5000, 5000 to 10000, 10000 to 50000, 50000 to 1 Lakh and More than 1 Lakh
            if($members == 1)
            {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 <= 10 ";
            } elseif ($members == 2) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 5 and CooperativeRegistrations.members_of_society*1 <= 5000 ";
            } elseif ($members == 3) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 5000 and CooperativeRegistrations.members_of_society*1 <= 10000 ";
            } elseif ($members == 4) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 10000 and CooperativeRegistrations.members_of_society*1 <= 50000 ";
            } elseif ($members == 5) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 50000 and CooperativeRegistrations.members_of_society*1 <= 100000 ";
            } elseif ($members == 6) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 500000 ";
            }*/
            if($members == 1)
            {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 <= 10 ";
            } elseif ($members == 2) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 >= 20000 and CooperativeRegistrations.members_of_society*1 <= 50000 ";
            } elseif ($members == 3) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 50000 ";
            }
        } else{
            $members = 1;
            $this->set('members', $members);
           
            if($members == 1)
            {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 <= 10 ";
            } elseif ($members == 2) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 >= 20000 and CooperativeRegistrations.members_of_society*1 <= 50000 ";
            } elseif ($members == 3) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 50000 ";
            }
        }

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

        if (isset($this->request->query['functional_status']) && $this->request->query['functional_status'] !='') {
            $s_functional_status = trim($this->request->query['functional_status']);
            $this->set('s_functional_status', $s_functional_status);
            $search_condition[] = "CooperativeRegistrations.functional_status = '" . $s_functional_status . "'";
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
        

        //is_approved=>0 (pending),is_approved=>1 (accepted),is_approved=>2 (rejected)
        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['CooperativeRegistrations.updated' => 'DESC'],
            'conditions' => [$searchString],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>1,'CooperativeRegistrations.status'=>1,'is_multi_state'=>0]);//,'members_of_society*1 >'=>100000

        

        $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();

        $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);

        if(!empty($nodal_data_entry_ids)){
        $registrationQuery = $registrationQuery->where(['CooperativeRegistrations.created_by IN'=>$nodal_data_entry_ids]);
        }else {
            $registrationQuery = $registrationQuery->where(['CooperativeRegistrations.created_by IN'=>0]);
        }

        if(!empty($this->request->query['export_excel'])) {
                
            $fileName = "members-".date("d-m-y:h:s").".xls";
            $headerRow = array("S.No", "Cooperative Society Name","State","District/Urban Local Body", "Primary Activity/Sector","Registration No.","No Of Members");
            $data = array();
            
            $registrationQuery = $registrationQuery->toArray();
            
            foreach($registrationQuery as $key => $rows){

                $district_local_body = $rows['location_of_head_quarter'] == 2 ? $arr_districts[$rows['district_code']] : $arr_localbodies[$rows['urban_local_body_code']];
                
                $data[]=array(($key+1), $rows['cooperative_society_name'],$stateOption[$rows['state_code']], $district_local_body, $sectors[$rows['sector_of_operation']],$rows['registration_number'],$rows['members_of_society']);
            }
            $this->exportInExcelNew($fileName, $headerRow, $data);
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

       $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);

        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('presentFunctionalStatus',$presentFunctionalStatus);
    }

    public function adminMembers()
    {
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadMOdel('Blocks');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;


        if (isset($this->request->query['members']) && $this->request->query['members'] !='') {
            $members = trim($this->request->query['members']);
            $this->set('members', $members);

            // 0 to 5, 5 to 5000, 5000 to 10000, 10000 to 50000, 50000 to 1 Lakh and More than 1 Lakh
            // if($members == 1)
            // {
            //     $search_condition[] = "CooperativeRegistrations.members_of_society*1 <= 10 ";
            // } elseif ($members == 2) {
            //     $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 5 and CooperativeRegistrations.members_of_society*1 <= 5000 ";
            // } elseif ($members == 3) {
            //     $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 5000 and CooperativeRegistrations.members_of_society*1 <= 10000 ";
            // } elseif ($members == 4) {
            //     $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 10000 and CooperativeRegistrations.members_of_society*1 <= 50000 ";
            // } elseif ($members == 5) {
            //     $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 50000 and CooperativeRegistrations.members_of_society*1 <= 100000 ";
            // } elseif ($members == 6) {
            //     $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 500000 ";
            // }

            if($members == 1)
            {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 <= 10 ";
            } elseif ($members == 2) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 >= 20000 and CooperativeRegistrations.members_of_society*1 <= 50000 ";
            } elseif ($members == 3) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 50000 ";
            }
        } 
        else {

            $members = 1;
            $this->set('members', $members);

            /*
            // 0 to 5, 5 to 5000, 5000 to 10000, 10000 to 50000, 50000 to 1 Lakh and More than 1 Lakh
            if($members == 1)
            {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 <= 10 ";
            } elseif ($members == 2) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 5 and CooperativeRegistrations.members_of_society*1 <= 5000 ";
            } elseif ($members == 3) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 5000 and CooperativeRegistrations.members_of_society*1 <= 10000 ";
            } elseif ($members == 4) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 10000 and CooperativeRegistrations.members_of_society*1 <= 50000 ";
            } elseif ($members == 5) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 50000 and CooperativeRegistrations.members_of_society*1 <= 100000 ";
            } elseif ($members == 6) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 500000 ";
            }*/
            if($members == 1)
            {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 <= 10 ";
            } elseif ($members == 2) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 >= 20000 and CooperativeRegistrations.members_of_society*1 <= 50000 ";
            } elseif ($members == 3) {
                $search_condition[] = "CooperativeRegistrations.members_of_society*1 > 50000 ";
            }
        }


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

        if (isset($this->request->query['functional_status']) && $this->request->query['functional_status'] !='') {
            $s_functional_status = trim($this->request->query['functional_status']);
            $this->set('s_functional_status', $s_functional_status);
            $search_condition[] = "CooperativeRegistrations.functional_status = '" . $s_functional_status . "'";
        } else {
            $s_functional_status = 1;
            $this->set('s_functional_status', $s_functional_status);
            $search_condition[] = "CooperativeRegistrations.functional_status = '" . $s_functional_status . "'";
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

        
        if($this->request->session()->read('Auth.User.role_id') == 11){
            $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')],'order' => ['name' => 'ASC']]);
        } else {
            $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        }
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

        $arr_blocks = $this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       $this->set('arr_blocks',$arr_blocks);
        

        //is_approved=>0 (pending),is_approved=>1 (accepted),is_approved=>2 (rejected)
        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['CooperativeRegistrations.updated' => 'DESC'],
            'conditions' => [$searchString],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>1,'CooperativeRegistrations.status'=>1,'CooperativeRegistrations.is_multi_state'=>0]);//,'members_of_society*1 >'=>100000

        if($this->request->session()->read('Auth.User.role_id') == 11){
            $registrationQuery = $registrationQuery->where(['CooperativeRegistrations.state_code'=>$this->request->session()->read('Auth.User.state_code')]);
        }

        if(!empty($this->request->query['export_excel'])) {
                
            $fileName = "members-".date("d-m-y:h:s").".xls";
            $headerRow = array("S.No", "Cooperative Society Name","State","District/Urban Local Body", "Primary Activity/Sector","Registration No.","No Of Members");
            $data = array();
            
            $registrationQuery = $registrationQuery->toArray();
            
            foreach($registrationQuery as $key => $rows){

                $district_local_body = $rows['location_of_head_quarter'] == 2 ? $arr_districts[$rows['district_code']] : $arr_localbodies[$rows['urban_local_body_code']];
                
                $data[]=array(($key+1), $rows['cooperative_society_name'],$stateOption[$rows['state_code']], $district_local_body, $sectors[$rows['sector_of_operation']],$rows['registration_number'],$rows['members_of_society']);
            }
            $this->exportInExcelNew($fileName, $headerRow, $data);
        }

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);

        $this->set(compact('CooperativeRegistrations'));

        

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

        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'id'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('presentFunctionalStatus',$presentFunctionalStatus);
    }

    public function adminRegistrationNumber()
    {
        
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('Blocks'); 
		 $conn = ConnectionManager::get('default');

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {
            $societyName = trim($this->request->query['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "CooperativeRegistrations.cooperative_society_name like '%" . $societyName . "%'";
        }

        if (isset($this->request->query['functional_status']) && $this->request->query['functional_status'] !='') {
            $s_functional_status = trim($this->request->query['functional_status']);
            $this->set('s_functional_status', $s_functional_status);
            $search_condition[] = "CooperativeRegistrations.functional_status = '" . $s_functional_status . "'";
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

            $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
            
            $this->set('districts',$districts);
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
            $blocks = [];

            
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$this->request->query['district']])->order(['name'=>'ASC'])->toArray();
            $this->set('blocks',$blocks);
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

        if($this->request->session()->read('Auth.User.role_id') == 11){
            $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')],'order' => ['name' => 'ASC']]);
        } else {
            $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        }
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

        // $duplicate = $this->CooperativeRegistrations->find('all',array('fields'=>array('registration_number','COUNT(*) as count'), 'group'=>'registration_number', 'having'=>'count > 1'))->toArray();

        // echo '<pre>';
        // print_r($duplicate);die;

        $registrationNumQuery = $this->CooperativeRegistrations->find('all',[
            'conditions' => [$searchString]
        ]);

        if($this->request->session()->read('Auth.User.role_id') == 11){
            $registrationNumQuery = $registrationNumQuery->where(['CooperativeRegistrations.state_code'=>$this->request->session()->read('Auth.User.state_code')]);
        }

        $registrationNumQuery = $registrationNumQuery->select(['registration_number','count' => $registrationNumQuery->func()->count('*'),'state_code','district_code','location_of_head_quarter','urban_local_body_code','block_code','sector_of_operation'])
            ->where(['is_draft'=>0,'is_approved'=>1,'status'=>1,'is_multi_state'=>0,'functional_status'=>1 ,'registration_number <>' => '0', 'registration_number !=' => '--'])
            ->group('registration_number')
            ->group('sector_of_operation')
            ->group('location_of_head_quarter')
            ->group('village_code')
            ->group('locality_ward_code')
            ->having(['count >' => 1])->hydrate(false)->toArray();
			
            $regNumbers = [];
            if(isset($registrationNumQuery) && !empty($registrationNumQuery))
            {
                $regNumbers = array_unique(array_column($registrationNumQuery, 'registration_number'));
            } else {
                $regNumbers = [-1];
            } 
            $regN = array() ;
            $regNu = array() ;
            $regNupr =array() ;
            $regNudist =array() ; 
            $regNust =array() ;

            foreach($registrationNumQuery as $k=>$v){
                if($v['location_of_head_quarter'] == 1){
                $regN[] = $v['state_code']."-".$v['district_code']."-".$v['urban_local_body_code']."-".$v['sector_of_operation']."-".$v['registration_number'] ;
                }else if($v['location_of_head_quarter'] == 2){
                    $regN[] = $v['state_code']."-".$v['district_code']."-".$v['block_code']."-".$v['sector_of_operation']."-".$v['registration_number'] ;
                }
            } 
			//echo "<pre>" ; print_r($regN) ; exit ;  
			
			$dups = array();
           foreach(array_count_values($regN) as $val => $c){
           if($c > 1) $dups[] = $val;
		   }
    
           //echo "<pre>" ; print_r($dups) ; exit ; 
	
	
            foreach($regN as $ke=>$ve){
                $ar = explode("-",$ve) ; 
                $regNu[] = $ar[4] ;
                $regNupr[] = $ar[3] ; 
                $regNudist[] = $ar[1] ; 
                $regNust[] = $ar[0] ; 
				$regNuurorblock[] = $ar[2] ;
            } 
			
			 $conn = ConnectionManager::get('default'); 
			 $valueList = implode(', ', $regN); 
			 
			 $quotedValues = array_map(function($value) {
    return "'" . $value . "'";
}, $regN);

// Create a comma-separated list of quoted values
$valueList = implode(', ', $quotedValues); 

			 //echo  $valueList ; exit ;
           /*
        //echo "<pre>" ; print_r($regN) ; exit ;
		        $registrationQuery = $this->CooperativeRegistrations->find('all',[
            'conditions' => [$searchString]
        ])
        ->select([
        'cooperative_society_name' => 'CooperativeRegistrations.cooperative_society_name',
        'state_code' => 'CooperativeRegistrations.state_code',
        'location_of_head_quarter' => 'CooperativeRegistrations.location_of_head_quarter',
        'district_code' => 'CooperativeRegistrations.district_code',
        'urban_local_body_code' => 'CooperativeRegistrations.urban_local_body_code',
        'block_code' => 'CooperativeRegistrations.block_code',
		  'block_code' => 'CooperativeRegistrations.block_code',
		    'sector_of_operation' => 'CooperativeRegistrations.sector_of_operation',
			  'reference_year' => 'CooperativeRegistrations.reference_year',
			    'date_registration' => 'CooperativeRegistrations.date_registration',
				  'registration_number' => 'CooperativeRegistrations.registration_number',
				    'updated' => 'CooperativeRegistrations.updated',
					  'created' => 'CooperativeRegistrations.created',
					   'functional_status' => 'CooperativeRegistrations.functional_status'
    ])
    ->where(function ($exp, $q) use ($regN) {
        return $exp->or_([
            $exp->in(
                $q->func()->concat([
                    'CooperativeRegistrations.state_code',
                    '-',
                    'CooperativeRegistrations.district_code',
                    '-',
                    'CooperativeRegistrations.urban_local_body_code',
                    '-',
                    'CooperativeRegistrations.sector_of_operation',
                    '-',
                    'CooperativeRegistrations.registration_number'
                ]),
               ['2-24-248043-1-0026','20-336-6698-10-06/2017 LOHARDAGA','20-342-6613-22-20 SIM/31.03.2013'] 
            ),
            $exp->in(
                $q->func()->concat([
                    'CooperativeRegistrations.state_code',
                    '-',
                    'CooperativeRegistrations.district_code',
                    '-',
                    'CooperativeRegistrations.block_code',
                    '-',
                    'CooperativeRegistrations.sector_of_operation',
                    '-',
                    'CooperativeRegistrations.registration_number'
                ]),
                 ['2-24-248043-1-0026','20-336-6698-10-06/2017 LOHARDAGA','20-342-6613-22-20 SIM/31.03.2013'] 
            ),
        ]);
    })->sql(); */
 $registrationQuery = $conn->execute('SELECT id,cooperative_society_name,state_code,location_of_head_quarter,district_code,urban_local_body_code,block_code,urban_local_body_code,sector_of_operation,
reference_year,date_registration,registration_number,updated,created,functional_status
FROM `cooperative_registrations` 
where concat(state_code,"-",district_code,"-",urban_local_body_code,"-",sector_of_operation,"-",registration_number) 
in ( '.$valueList.') 
or concat(state_code,"-",district_code,"-",block_code,"-",sector_of_operation,"-",registration_number) 
in ( '.$valueList.') ');
$registrationQuery->execute();
$results = $registrationQuery->fetchAll('assoc');   
$ids =array() ;
foreach($results as $k=>$v){
	array_push($ids,$v['id']) ;
}
 $registrationQuery_new = $this->CooperativeRegistrations->find('all',[
            'conditions' => [$searchString]
        ]);
$registrationQuery_new  = $registrationQuery_new->select(['id','cooperative_society_name','state_code','location_of_head_quarter','district_code','urban_local_body_code','block_code','sector_of_operation','reference_year','date_registration','registration_number','updated','created','functional_status'])
            ->where(['is_draft'=>0,'is_approved'=>1,'status'=>1,'is_multi_state'=>0,'functional_status'=>1,'id IN'=>$ids])->order(['registration_number'=>'ASC']);


//echo "<pre>" ; print_r($ids) ; exit ;


        if($this->request->session()->read('Auth.User.role_id') == 11){
            $registrationQuery_new = $registrationQuery_new->where(['CooperativeRegistrations.state_code'=>$this->request->session()->read('Auth.User.state_code')]);
        }

        if(!empty($this->request->query['export_excel'])) {
            
            $fileName = "registration_number-".date("d-m-y:h:s").".xls";
            $headerRow = array("S.No", "Cooperative Society Name","State","District/Urban Local Body", "Primary Activity/Sector","Date of Registration","Registration No.");
            $data = array();
            
            $registrationQuery_new = $registrationQuery_new->toArray();
            
            foreach($registrationQuery_new as $key => $rows){

                $district_local_body = $rows['location_of_head_quarter'] == 2 ? $arr_districts[$rows['district_code']] : $arr_localbodies[$rows['urban_local_body_code']];
                $cooperative_society_name= str_replace('	','',$this->strClean(trim($rows['cooperative_society_name'])));

                $data[]=array(($key+1), $cooperative_society_name,$stateOption[$rows['state_code']], $district_local_body, $sectors[$rows['sector_of_operation']],date("d/m/Y", strtotime($rows['date_registration'])),$rows['registration_number']);
            }
            $this->exportInExcelNew($fileName, $headerRow, $data);
        }

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery_new);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

        $this->set(compact('CooperativeRegistrations'));


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

       if(!empty($this->request->query['state']))
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
       $arr_blocks = $this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       $this->set('arr_blocks',$arr_blocks);

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

        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('presentFunctionalStatus',$presentFunctionalStatus);
    }
    

    public function registrationNumber()
    {
        
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');

        $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $this->request->session()->write('prev_corr_url', $actual_link);
        
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

        if (isset($this->request->query['created_by']) && $this->request->query['created_by'] !='') {
            $s_created_by = trim($this->request->query['created_by']);
            $this->set('s_created_by', $s_created_by);
            $search_condition[] = "CooperativeRegistrations.created_by = '" . $s_created_by . "'";
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

        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);

        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
        $this->set('sectors',$sectors);  
        
        $this->loadModel('Users');
        $user_all=$this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'role_id'=>7,'state_code'=>$this->request->session()->read('Auth.User.state_code')])->order(['name'=>'ASC'])->toArray();
        $this->set('user_all', $user_all);
       
        $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();
        
       
        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $this->set('arr_districts',$arr_districts);

        $registrationNumQuery = $this->CooperativeRegistrations->find('all',[
            'conditions' => [$searchString]
        ]);

        $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();

        $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);

        $registrationNumQuery = $registrationNumQuery->select(['registration_number','count' => $registrationNumQuery->func()->count('*')])
            ->where(['is_draft'=>0,'is_approved'=>1,'status'=>1,'location_of_head_quarter'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code'),'functional_status'=>1])
            ->group('registration_number')
            ->group('sector_of_operation')
            ->group('locality_ward_code')
            ->having(['count >' => 1]);

            if($this->request->session()->read('Auth.User.role_id') == 8)
            {
                if(!empty($nodal_data_entry_ids)){
                    $registrationNumQuery = $registrationNumQuery->where(['CooperativeRegistrations.created_by IN'=>$nodal_data_entry_ids]);
                }else {
                        $registrationNumQuery = $registrationNumQuery->where(['CooperativeRegistrations.created_by IN'=>0]);
                }
            }
            
            $registrationNumQuery = $registrationNumQuery->toArray();

            $regNumbers = [];
            if(isset($registrationNumQuery) && !empty($registrationNumQuery))
            {
                $regNumbers = array_unique(array_column($registrationNumQuery, 'registration_number'));
            } else {
                $regNumbers = [0];
            }

            //=============================
            $registrationNumQuery1 = $this->CooperativeRegistrations->find('all',[
                'conditions' => [$searchString]
            ]);
            $registrationNumQuery1 = $registrationNumQuery1->select(['registration_number','count' => $registrationNumQuery1->func()->count('*')])
            ->where(['is_draft'=>0,'is_approved'=>1,'status'=>1,'location_of_head_quarter'=>2,'state_code'=>$this->request->session()->read('Auth.User.state_code')])
            ->group('registration_number')
            ->group('sector_of_operation')
            ->group('village_code')
            ->having(['count >' => 1]);

            if($this->request->session()->read('Auth.User.role_id') == 8)
            {
                if(!empty($nodal_data_entry_ids)){
                    $registrationNumQuery1 = $registrationNumQuery1->where(['CooperativeRegistrations.created_by IN'=>$nodal_data_entry_ids]);
                }else {
                        $registrationNumQuery1 = $registrationNumQuery1->where(['CooperativeRegistrations.created_by IN'=>0]);
                }
            }
            
            $registrationNumQuery1 = $registrationNumQuery1->toArray();

            $regNumbers1 = [];
            if(isset($registrationNumQuery1) && !empty($registrationNumQuery1))
            {
                $regNumbers1 = array_unique(array_column($registrationNumQuery1, 'registration_number'));
            } else {
                $regNumbers1 = [0];
            }
            
            //=============================

            $regNumbers = array_unique(array_merge($regNumbers1,$regNumbers));

        $registrationQuery = $this->CooperativeRegistrations->find('all',[
            'conditions' => [$searchString]
        ]);
        $registrationQuery = $registrationQuery->select(['id','cooperative_society_name','state_code','location_of_head_quarter','district_code','block_code','urban_local_body_code','sector_of_operation','reference_year','date_registration','registration_number','created_by','updated','created'])
							->where(['is_draft'=>0,'is_approved'=>1,'status'=>1,'is_multi_state'=>0,'functional_status'=>1,'registration_number IN'=>$regNumbers,'state_code'=>$this->request->session()->read('Auth.User.state_code')]);
    
        //if district nodal
        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            $registrationQuery = $registrationQuery->where(['created_by IN'=>$nodal_data_entry_ids]);
        }

            $registrationQuery = $registrationQuery->order(['registration_number'=>'ASC']);

            if(!empty($this->request->query['export_excel'])) {
            
                $fileName = "registration_number-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No", "Cooperative Society Name","State","District/Urban Local Body", "Primary Activity/Sector","Date of Registration","Registration No.");
                $data = array();
                
                $registrationQuery = $registrationQuery->toArray();
                
                foreach($registrationQuery as $key => $rows){
    
                    $district_local_body = $rows['location_of_head_quarter'] == 2 ? $arr_districts[$rows['district_code']] : $arr_localbodies[$rows['urban_local_body_code']];
                    
                    $data[]=array(($key+1), $rows['cooperative_society_name'],$stateOption[$rows['state_code']], $district_local_body, $sectors[$rows['sector_of_operation']],date("d/m/Y", strtotime($rows['date_registration'])),$rows['registration_number']);
                }
                $this->exportInExcelNew($fileName, $headerRow, $data);
            }

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

        $this->set(compact('CooperativeRegistrations'));

       
        

        // $duplicate = $this->CooperativeRegistrations->find('all',array('fields'=>array('registration_number','COUNT(*) as count'), 'group'=>'registration_number', 'having'=>'count > 1'))->toArray();

        // echo '<pre>';
        // print_r($duplicate);die;

        

        
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



       $districts = [];

       if(!empty($this->request->query['state']))
       {
           $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
       }

       $this->set('districts',$districts);

       $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

        $this->set('arr_districts',$arr_districts);

       $blocks = [];
       $this->loadMOdel('Blocks');
       if(!empty($this->request->query['district']) && $this->request->query['location'] == 2)
       {
           

           $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$this->request->query['district']])->order(['name'=>'ASC'])->toArray();
       }

       $this->set('blocks',$blocks);

       $arr_blocks = $this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       $this->set('arr_blocks',$arr_blocks);

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

       

        $this->set('arr_localbodies',$arr_localbodies);
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
        $this->loadModel('CooperativeRegistrations');
        $this->request->allowMethod(['post', 'delete']);
        $CooperativeRegistration = $this->CooperativeRegistrations->get($id);

        if($this->request->session()->read('Auth.User.role_id') == 8)
        {
            $this->Flash->error(__('You are not authorized.'));
            $this->redirect($this->referer());   
        }

        $data['status'] = 0;
        $data['updated_by'] = $this->request->session()->read('Auth.User.id');
        $data['updated'] = date('Y-m-d H:i:s');
        $CooperativeRegistration = $this->CooperativeRegistrations->patchEntity($CooperativeRegistration, $data);

        if($this->CooperativeRegistrations->save($CooperativeRegistration)) {
            $this->Flash->success(__('The Cooperative Registration has been deleted.'));
        }
        $this->redirect($this->referer());

    }

    public function mobile()
    {
        
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadMOdel('Blocks');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadModel('SectorOperations');
        $this->loadModel('SecondaryActivities');
        $this->loadMOdel('UrbanLocalBodiesWards');
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $this->loadModel('PresentFunctionalStatus');

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {
            $societyName = trim($this->request->query['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "CooperativeRegistrations.cooperative_society_name like '%" . $societyName . "%'";
        }

        if (isset($this->request->query['functional_status']) && $this->request->query['functional_status'] !='') {
            $s_functional_status = trim($this->request->query['functional_status']);
            $this->set('s_functional_status', $s_functional_status);
            $search_condition[] = "CooperativeRegistrations.functional_status = '" . $s_functional_status . "'";
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

        //State Nodal
        if($this->request->session()->read('Auth.User.role_id') == 11){

            $query = $query->where(['state_code'=>$this->request->session()->read('Auth.User.state_code')]);
        }

        //district Nodal or DEO
        if($this->request->session()->read('Auth.User.role_id') == 8 || $this->request->session()->read('Auth.User.role_id') == 7){

            $query = $query->where(['state_code'=>$this->request->session()->read('Auth.User.state_code'),'district_code'=>$this->request->session()->read('Auth.User.district_code')]);
        }
        
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);

        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
        $this->set('sectors',$sectors);  
        
        
        $user_all=$this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
        $this->set('user_all', $user_all);
       
        $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();
        $this->set('arr_localbodies',$arr_localbodies);
       
        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $this->set('arr_districts',$arr_districts);
        
        $arr_blocks = $this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       $this->set('arr_blocks',$arr_blocks);

        $arr_location = [
            '1'=> 'Urban',
            '2'=> 'Rural'
        ];
        $this->set('arr_location',$arr_location);

        

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
        

        $secondary_activities = $this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $this->set('secondary_activities',$secondary_activities);

       //======================urban================================================

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 1)
       {
           

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
           

           $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$this->request->query['district']])->order(['name'=>'ASC'])->toArray();
       }

       $this->set('blocks',$blocks);

       $panchayats = [];

       if(!empty($this->request->query['block']) && $this->request->query['location'] == 2)
       {
           

           $panchayats = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$this->request->query['block']])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  

           $this->set('panchayats',$panchayats); 
       }

       $villages = [];

       if(!empty($this->request->query['panchayat']) && $this->request->query['location'] == 2)
       {
           

           $villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$this->request->query['panchayat']])->order(['village_name'=>'ASC'])->toArray();

           $this->set('villages',$villages);  
       }

       

       $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);

        
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('presentFunctionalStatus',$presentFunctionalStatus);

        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['CooperativeRegistrations.updated' => 'DESC'],
            'conditions' => [$searchString],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>1,'CooperativeRegistrations.status'=>1,'is_multi_state'=>0])
        ->where(['OR'=>
                        [
                            ['CooperativeRegistrations.mobile'=> ''],
                            ['CooperativeRegistrations.mobile' => '0'],
                            ['CooperativeRegistrations.mobile' => '00'],
                            ['CooperativeRegistrations.mobile' => '0000000000'],
                            ['CooperativeRegistrations.mobile' => '1111111111'],
                            ['CooperativeRegistrations.mobile' => '2222222222'],
                            ['CooperativeRegistrations.mobile' => '3333333333'],
                            ['CooperativeRegistrations.mobile' => '4444444444'],
                            ['CooperativeRegistrations.mobile' => '5555555555'],
                            ['CooperativeRegistrations.mobile' => '6666666666'],
                            ['CooperativeRegistrations.mobile' => '7777777777'],
                            ['CooperativeRegistrations.mobile' => '8888888888'],
                            ['CooperativeRegistrations.mobile' => '9999999999']
                        ]
                    ]);
        

        if($this->request->session()->read('Auth.User.role_id') == 11){
            $registrationQuery = $registrationQuery->where(['CooperativeRegistrations.state_code'=>$this->request->session()->read('Auth.User.state_code')]);
        }
       

        //district Nodal or DEO
        if($this->request->session()->read('Auth.User.role_id') == 8 || $this->request->session()->read('Auth.User.role_id') == 7){

            $registrationQuery = $registrationQuery->where(['CooperativeRegistrations.state_code'=>$this->request->session()->read('Auth.User.state_code'),'CooperativeRegistrations.district_code'=>$this->request->session()->read('Auth.User.district_code')]);
        }
    
            if(!empty($this->request->query['export_excel'])) {
                
                $fileName = "registration_date-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No", "Cooperative Society Name","State","District/Urban Local Body", "Primary Activity/Sector","Registration No.","Mobile");
                $data = array();
                
                $registrationQuery = $registrationQuery->toArray();
                
                foreach($registrationQuery as $key => $rows){

                    $district_local_body = $rows['location_of_head_quarter'] == 2 ? $arr_districts[$rows['district_code']] : $arr_localbodies[$rows['urban_local_body_code']];
                    
                    $data[]=array(($key+1), $rows['cooperative_society_name'],$stateOption[$rows['state_code']], $district_local_body, $sectors[$rows['sector_of_operation']],$rows['registration_number'],$rows['mobile']);
                }
                $this->exportInExcelNew($fileName, $headerRow, $data);
            }
        

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);

        $this->set(compact('CooperativeRegistrations'));
    }
    

    public function federation()
    {
        
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadMOdel('Blocks');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadModel('SectorOperations');
        $this->loadModel('SecondaryActivities');
        $this->loadMOdel('UrbanLocalBodiesWards');
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $this->loadModel('PresentFunctionalStatus');

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {
            $societyName = trim($this->request->query['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "CooperativeRegistrations.cooperative_society_name like '%" . $societyName . "%'";
        }

        if (isset($this->request->query['functional_status']) && $this->request->query['functional_status'] !='') {
            $s_functional_status = trim($this->request->query['functional_status']);
            $this->set('s_functional_status', $s_functional_status);
            $search_condition[] = "CooperativeRegistrations.functional_status = '" . $s_functional_status . "'";
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


        //default for all admin level
        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);

        //State Nodal
        if($this->request->session()->read('Auth.User.role_id') == 11){

            $query = $query->where(['state_code'=>$this->request->session()->read('Auth.User.state_code')]);
        }

        //district Nodal or DEO
        if($this->request->session()->read('Auth.User.role_id') == 8 || $this->request->session()->read('Auth.User.role_id') == 7){

            $query = $query->where(['state_code'=>$this->request->session()->read('Auth.User.state_code'),'district_code'=>$this->request->session()->read('Auth.User.district_code')]);
        }
        
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);

        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
        $this->set('sectors',$sectors);  
        
        
        $user_all=$this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
        $this->set('user_all', $user_all);
       
        $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();
        $this->set('arr_localbodies',$arr_localbodies);
       
        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $this->set('arr_districts',$arr_districts);
        
        $arr_blocks = $this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       $this->set('arr_blocks',$arr_blocks);

        $arr_location = [
            '1'=> 'Urban',
            '2'=> 'Rural'
        ];
        $this->set('arr_location',$arr_location);

        

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
        

        $secondary_activities = $this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $this->set('secondary_activities',$secondary_activities);

       //======================urban================================================

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 1)
       {
           

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
           

           $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$this->request->query['district']])->order(['name'=>'ASC'])->toArray();
       }

       $this->set('blocks',$blocks);

       $panchayats = [];

       if(!empty($this->request->query['block']) && $this->request->query['location'] == 2)
       {
           

           $panchayats = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$this->request->query['block']])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  

           $this->set('panchayats',$panchayats); 
       }

       $villages = [];

       if(!empty($this->request->query['panchayat']) && $this->request->query['location'] == 2)
       {
           

           $villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$this->request->query['panchayat']])->order(['village_name'=>'ASC'])->toArray();

           $this->set('villages',$villages);  
       }

       

       $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);

        
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('presentFunctionalStatus',$presentFunctionalStatus);

        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['CooperativeRegistrations.updated' => 'DESC'],
            'conditions' => [$searchString],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrations.cooperative_society_name like'=>'%federation%','CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>1,'CooperativeRegistrations.status'=>1,'is_multi_state'=>0]);
        

        if($this->request->session()->read('Auth.User.role_id') == 11){
            $registrationQuery = $registrationQuery->where(['CooperativeRegistrations.state_code'=>$this->request->session()->read('Auth.User.state_code')]);
        }
       

        //district Nodal or DEO
        if($this->request->session()->read('Auth.User.role_id') == 8 || $this->request->session()->read('Auth.User.role_id') == 7){

            $registrationQuery = $registrationQuery->where(['CooperativeRegistrations.state_code'=>$this->request->session()->read('Auth.User.state_code'),'CooperativeRegistrations.district_code'=>$this->request->session()->read('Auth.User.district_code')]);
        }
    
            if(!empty($this->request->query['export_excel'])) {
                
                $fileName = "registration_date-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No", "Cooperative Society Name","State","District/Urban Local Body", "Primary Activity/Sector","Registration No.","Mobile");
                $data = array();
                
                $registrationQuery = $registrationQuery->toArray();
                
                foreach($registrationQuery as $key => $rows){

                    $district_local_body = $rows['location_of_head_quarter'] == 2 ? $arr_districts[$rows['district_code']] : $arr_localbodies[$rows['urban_local_body_code']];
                    
                    $data[]=array(($key+1), $rows['cooperative_society_name'],$stateOption[$rows['state_code']], $district_local_body, $sectors[$rows['sector_of_operation']],$rows['registration_number'],$rows['mobile']);
                }
                $this->exportInExcelNew($fileName, $headerRow, $data);
            }
        

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);

        $this->set(compact('CooperativeRegistrations'));
    }

    public function pincode()
    {
        
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadMOdel('Blocks');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadModel('SectorOperations');
        $this->loadModel('SecondaryActivities');
        $this->loadMOdel('UrbanLocalBodiesWards');
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $this->loadModel('PresentFunctionalStatus');

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['society_name'])) {
            $societyName = trim($this->request->query['society_name']);
            $this->set('societyName', $societyName);
            $search_condition[] = "CooperativeRegistrations.cooperative_society_name like '%" . $societyName . "%'";
        }

        if (isset($this->request->query['functional_status']) && $this->request->query['functional_status'] !='') {
            $s_functional_status = trim($this->request->query['functional_status']);
            $this->set('s_functional_status', $s_functional_status);
            $search_condition[] = "CooperativeRegistrations.functional_status = '" . $s_functional_status . "'";
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

        //State Nodal
        if($this->request->session()->read('Auth.User.role_id') == 11){

            $query = $query->where(['state_code'=>$this->request->session()->read('Auth.User.state_code')]);
        }

        //district Nodal or DEO
        if($this->request->session()->read('Auth.User.role_id') == 8 || $this->request->session()->read('Auth.User.role_id') == 7){

            $query = $query->where(['state_code'=>$this->request->session()->read('Auth.User.state_code'),'district_code'=>$this->request->session()->read('Auth.User.district_code')]);
        }
        
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);

        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
        $this->set('sectors',$sectors);  
        
        
        $user_all=$this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
        $this->set('user_all', $user_all);
       
        $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();
        $this->set('arr_localbodies',$arr_localbodies);
       
        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $this->set('arr_districts',$arr_districts);
        
        $arr_blocks = $this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       $this->set('arr_blocks',$arr_blocks);

        $arr_location = [
            '1'=> 'Urban',
            '2'=> 'Rural'
        ];
        $this->set('arr_location',$arr_location);

        

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
        

        $secondary_activities = $this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $this->set('secondary_activities',$secondary_activities);

       //======================urban================================================

       if(!empty($this->request->query['state']) && $this->request->query['location'] == 1)
       {
           

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
           

           $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$this->request->query['district']])->order(['name'=>'ASC'])->toArray();
       }

       $this->set('blocks',$blocks);

       $panchayats = [];

       if(!empty($this->request->query['block']) && $this->request->query['location'] == 2)
       {
           

           $panchayats = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$this->request->query['block']])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  

           $this->set('panchayats',$panchayats); 
       }

       $villages = [];

       if(!empty($this->request->query['panchayat']) && $this->request->query['location'] == 2)
       {
           

           $villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$this->request->query['panchayat']])->order(['village_name'=>'ASC'])->toArray();

           $this->set('villages',$villages);  
       }

       

       $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sectors',$sectors);  

       $arr_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();

        $this->set('arr_localbodies',$arr_localbodies);

        
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->set('presentFunctionalStatus',$presentFunctionalStatus);

        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['CooperativeRegistrations.updated' => 'DESC'],
            'conditions' => [$searchString],
            'contain' => ['PrimaryActivities']
        ])->where(['CooperativeRegistrations.is_draft'=>0,'CooperativeRegistrations.is_approved'=>1,'CooperativeRegistrations.status'=>1,'is_multi_state'=>0])
        ->where(['OR'=>
                        [
                            ['CooperativeRegistrations.pincode'=> ''],
                            ['CooperativeRegistrations.mobile' => '0'],
                            ['CooperativeRegistrations.mobile' => '00'],
                            ['CooperativeRegistrations.mobile' => '0000000000'],
                            ['CooperativeRegistrations.mobile' => '1111111111'],
                            ['CooperativeRegistrations.mobile' => '2222222222'],
                            ['CooperativeRegistrations.mobile' => '3333333333'],
                            ['CooperativeRegistrations.mobile' => '4444444444'],
                            ['CooperativeRegistrations.mobile' => '5555555555'],
                            ['CooperativeRegistrations.mobile' => '6666666666'],
                            ['CooperativeRegistrations.mobile' => '7777777777'],
                            ['CooperativeRegistrations.mobile' => '8888888888'],
                            ['CooperativeRegistrations.mobile' => '9999999999']
                        ]
                    ]);
        

        if($this->request->session()->read('Auth.User.role_id') == 11){
            $registrationQuery = $registrationQuery->where(['CooperativeRegistrations.state_code'=>$this->request->session()->read('Auth.User.state_code')]);
        }
       

        //district Nodal or DEO
        if($this->request->session()->read('Auth.User.role_id') == 8 || $this->request->session()->read('Auth.User.role_id') == 7){

            $registrationQuery = $registrationQuery->where(['CooperativeRegistrations.state_code'=>$this->request->session()->read('Auth.User.state_code'),'CooperativeRegistrations.district_code'=>$this->request->session()->read('Auth.User.district_code')]);
        }
    
            if(!empty($this->request->query['export_excel'])) {
                
                $fileName = "registration_date-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No", "Cooperative Society Name","State","District/Urban Local Body", "Primary Activity/Sector","Registration No.","Mobile");
                $data = array();
                
                $registrationQuery = $registrationQuery->toArray();
                
                foreach($registrationQuery as $key => $rows){

                    $district_local_body = $rows['location_of_head_quarter'] == 2 ? $arr_districts[$rows['district_code']] : $arr_localbodies[$rows['urban_local_body_code']];
                    
                    $data[]=array(($key+1), $rows['cooperative_society_name'],$stateOption[$rows['state_code']], $district_local_body, $sectors[$rows['sector_of_operation']],$rows['registration_number'],$rows['mobile']);
                }
                $this->exportInExcelNew($fileName, $headerRow, $data);
            }
        

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);

        $this->set(compact('CooperativeRegistrations'));
    }
}