<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;

/**
 * CooperativeRegistrations Controller
 *
 * @property \App\Model\Table\CooperativeRegistrationsTable $CooperativeRegistrations
 *
 * @method \App\Model\Entity\State[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ScbRegistrationsController extends AppController
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

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {  

        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadModel('StateCooperativeBanks');


        $bankExist = $this->StateCooperativeBanks->find()->where(['created_by'=>$this->request->session()->read('Auth.User.id'),'status'=>1,'is_delete'=>0])->first();


        $add_flag = 0;
        if(empty($bankExist))
        {
            $add_flag = 1;
        }

        $this->set('add_flag',$add_flag);

        if (!empty($this->request->query['name'])) {
            $name = trim($this->request->query['name']);
            $this->set('name', $name);
            $search_condition[] = "scb_name like '%" . $name . "%'";
        }
        
        if (isset($this->request->query['state_code']) && $this->request->query['state_code'] !='') {
            $s_state = trim($this->request->query['state_code']);
            $this->set('s_state', $s_state);
            $search_condition[] = "state_code = '" . $s_state . "'";
        }

        if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
            $s_district = trim($this->request->query['district_code']);
            $this->set('s_district', $s_district);
            $search_condition[] = "district_code = '" . $s_district . "'";
        }

        $allow_admin_role = [1,2,14,16,17,18,19,20,22,23];

        if(in_array($this->request->session()->read('Auth.User.role_id'),$allow_admin_role))
        {
            $registrationQuery = $this->StateCooperativeBanks->find('all', [
                'order' => ['id' => 'DESC'],
                'conditions' => [$search_condition]
            ])->where(['scb_dcb_flag'=>1,'is_delete'=>0,'status'=>1]);//
        } else {
            //for SCB nodal only
            $registrationQuery = $this->StateCooperativeBanks->find('all', [
                'order' => ['id' => 'DESC'],
                'conditions' => [$search_condition]
            ])->where(['is_delete'=>0,'status'=>1,'created_by'=>$this->request->session()->read('Auth.User.id')]);//
        }

        


        $this->paginate = ['limit' => 20];
        $StateCooperativeBanks = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

        $this->set(compact('StateCooperativeBanks'));

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

        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['cooperative_id_num' => 'DESC'],
            'conditions' => [$searchString,$search_condition2,$search_condition3]
        ])->where(['is_draft'=>0,'is_approved'=>0,'status'=>1]);

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

        $this->loadModel('StateCooperativeBanks');


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

        $registrationQuery = $this->StateCooperativeBanks->find('all', [
            'order' => ['id' => 'DESC'],
            'conditions' => [$searchString]
        ])->where(['is_deleted'=>0,'status'=>1]);

        $this->paginate = ['limit' => 20];
        $StateCooperativeBanks = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

        $this->set(compact('StateCooperativeBanks'));

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

     

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('StateCooperativeBanks');
        $this->loadModel('CooperativeRegistrations');
        $this->loadModel('Districts');
        
    
        $StateCooperativeBank = $this->StateCooperativeBanks->newEntity();
        $years = [];
        $l_year = date('Y')-23;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
        }

        $bankExist = $this->StateCooperativeBanks->find()->where(['created_by'=>$this->request->session()->read('Auth.User.id'),'status'=>1,'is_delete'=>0])->first();


        $add_flag = 0;
        if(empty($bankExist))
        {
            $add_flag = 1;
        }

        $allow_role = [1,2,14,16,17,18,19,20,22,23,24];

        //allow only admin and SCB Nodal to access add page
        if(in_array($this->request->session()->read('Auth.User.role_id'),$allow_role))
        {
            //$states=$this->DistrictCentralCooperativeBank->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['entity_type' => 'stcb'])->order(['name'=>'ASC'])->where(['flag'=>1]);
            
        } else {
            return $this->redirect(['action' => 'index']);$this->Flash->error(__('You are not Authorize to add SCB'));
        }

        // for SCB Nodal

        if(!empty($bankExist) && $this->request->session()->read('Auth.User.role_id') == 24)
        {
            return $this->redirect(['action' => 'index']);$this->Flash->error(__('You are not Authorize to add SCB'));
        }

        $this->loadMOdel('DistrictCentralCooperativeBank');

        $bank_name = $this->DistrictCentralCooperativeBank->find('all')->where(['entity_type' => 'stcb','state_code'=>$this->request->session()->read('Auth.User.state_code')])->first();

        $StateCooperativeBank->scb_name = $bank_name->name;

        $district_code = $this->Districts->find('all')->where(['state_code' => $this->request->session()->read('Auth.User.state_code')])->first()->district_code;

        if(!empty($district_code))
        {
            $StateCooperativeBank->society_district_code = $district_code;    
        }

        $society=$this->CooperativeRegistrations->find('all')->where(['is_affiliated_union_federation'=>1,'status'=>1,'operational_district_code'=>$district_code,'sector_of_operation IN '=>[1,20,22]])->order(['cooperative_society_name'=>'ASC'])->toArray();

        $StateCooperativeBank->pacs_member_count = count($society);

        $society1=$this->CooperativeRegistrations->find('all')->where(['is_affiliated_union_federation'=>0,'status'=>1,'operational_district_code'=>$district_code,'sector_of_operation IN '=>[1,20,22]])->order(['cooperative_society_name'=>'ASC'])->toArray();

        $district_name = $this->Districts->find('all')->where(['district_code' => $district_code])->first()->name;
        
        if($this->request->is('post')){

            $data=$this->request->getData();
            
            $data['status'] = 1;
            $data['is_delete'] = 0;
            $data['created'] = date('Y-m-d H:i:s');
            $data['updated'] = date('Y-m-d H:i:s');
            //$data['operational_district_code'] = $this->request->session()->read('Auth.User.district_code');
            // echo '<pre>';
            //  print_r($data['sector']);die;
            //add his district based on user if urban
            
            if(!empty($data['scb_contact_details']))
            {
                // echo '<pre>';
                // print_r($data['scb_contact_details']);die;
                foreach($data['scb_contact_details'] as $key => $contact_details)
                {
                    // echo '<pre>';
                    // print_r($contact_details);die;
                    $std = '';
                    $landline = '';
                    if(!empty($contact_details['landline']) && !empty($contact_details['std']))
                    {
                        //die('success1');
                        $std = $contact_details['std'];
                        $landline = $contact_details['landline'];
                        $contact_details['landline'] = $std.'-'.$landline;
                        $data['scb_contact_details'][$key]['landline'] = $std.'-'.$landline;
                        unset($data['scb_contact_details'][$key]['std']);
                    }

                }
                    
            }
            
            // if(!empty($data['landline']))
            // {
            //     $std = $data['std'];
            //     $landline = $data['landline'];
            //     $data['landline'] = $std.'-'.$landline;
            // } else {
            //     unset($data['std']);
            // }

            $data['area_of_operation_state_code'] = implode(',',$data['area_of_operation_state_code']);
            
            

            $data['created_by'] = $this->request->session()->read('Auth.User.id');

            //add his district based on user if urban
            if(($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8) && $data['location_urban_rural'] == 1)
            {
                $data['district_code'] = $this->request->session()->read('Auth.User.district_code');
            }
            
            //unset($data['scb_contact_details']);

            //unset($data['scb_scheme_details']);

            //=============================================================

            //for DCCB Member
            if($data['dccb_bank_member'] == 0)
            {
                unset($data['dccb_bank_is_member']);
                $data['dccb_bank_member_count'] = 0;
            } else {
                //pacs_is_member_affiliated
                $data['dccb_bank_is_member'] = implode(',',$data['dccb_bank_is_member']);
            }
            

            if($data['pacs_member'] == 0)
            {
                unset($data['pacs_is_member_affiliated']);
                unset($data['pacs_is_member_not_affiliated']);

                $data['pacs_member_count'] = 0;
            } else {
                array_unique($data['pacs_is_member_affiliated']);
                array_unique($data['pacs_is_member_not_affiliated']);


               if(!empty($data['pacs_is_member_affiliated']) && !empty($data['pacs_is_member_not_affiliated']))
               {
                    $data['pacs_is_member_affiliated'] = array_merge($data['pacs_is_member_affiliated'],$data['pacs_is_member_not_affiliated']);
               }

               if(empty($data['pacs_is_member_affiliated']) && !empty($data['pacs_is_member_not_affiliated']))
               {
                    $data['pacs_is_member_affiliated'] = $data['pacs_is_member_not_affiliated'];
               }

                $data['pacs_is_member_affiliated'] = implode(',',$data['pacs_is_member_affiliated']);

                $data['society_district_code'] = implode(',',$data['society_district_code']);
                
            }

            //DCCB Other Membership Details
            if($data['other_member'] == 0)
            {
               $data['other_member'] = 0; 
               unset($data['dcb_scb_other_members']);
            }
            //scheme add
            if($data['is_scheme_implemented'] == 0)
            {
               $data['is_scheme_implemented'] = 0; 
               unset($data['scb_implementing_schemes']);
            }

            $data['pacs_is_member_not_affiliated'] = '';
            //=============================================================

            $data['scb_dcb_flag'] = 1;
            
            // unset($data['other_member']);
            // unset($data['other_member_count']);
            // unset($data['scb_others']);
            // echo '<pre>';
            // print_r($data);die;

            foreach($data['dcb_scb_other_members'] as $key=>$other_member_data)
            {
                if($other_member_data['type_membership'] == 2 && !empty($other_member_data['member_document']))
                {
                    // echo $other_member_data['document'];die;
                    $ext = pathinfo($other_member_data['member_document']['name'], PATHINFO_EXTENSION);

                     

                    $other_member_data['member_document']['name'] = 'document_member_'.$this->request->session()->read('Auth.User.state_code').'_'.date('Y_m_d_H_i_s').'.'.$ext;

                    //echo $other_member_data['document']['name'];die;

                    if($other_member_data['member_document']['name']!=''){
                        $fileName = $this->uploadPdf('member_document', $other_member_data['member_document']);
                        // echo '<pre>';
                        // print_r($fileName);die;
                        $data['dcb_scb_other_members'][$key]['member_document']  = $fileName['filename'];//echo "<pre>"; print_r($fileName); exit;
                    }
                } else {
                    $data['dcb_scb_other_members'][$key]['member_document'] = '';
                }
            }

            $StateCooperativeBank = $this->StateCooperativeBanks->patchEntity($StateCooperativeBank, $data,['associated' => ['ScbImplementingSchemes','ScbContactDetails','DcbScbOtherMembers']]);


            
            if($this->StateCooperativeBanks->save($StateCooperativeBank)) {
                
                $this->Flash->success(__('The data has been saved.'));
                //return $this->redirect(['action' => 'add-member',$result['id']]);
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

        $all_states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
            $this->set('all_states',$all_states);    

        ####### degination dropdown#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        ###################end ############


        // Start States for dropdown
            
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
            $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

            
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


              //  Start register_authorty dropdown registration_authorities
            $this->loadModel('RegistrationAuthorities');
            $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['authority_name'=>'ASC']);
            $this->set('register_authorities',$register_authorities);
        // End registration_authorities for dropdown

        $all_districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC'])->toArray();
        

        $this->loadMOdel('DistrictCentralCooperativeBank');

        $dccb_banks = $this->DistrictCentralCooperativeBank->find('all')->where(['dccb_flag' => '1','primary_activity_flag'=>'1','state_code'=>$this->request->session()->read('Auth.User.state_code')])->toArray();
        
        $this->set(compact('StateCooperativeBank','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','years','state_code','all_districts','dccb_banks','society','society1','district_name'));
        
    }

    public function addscardb()
    {
        $this->loadModel('StateCooperativeBanks');
        $this->loadModel('ScardbDetails');

        $this->loadModel('Districts');
        $StateCooperativeBank = $this->StateCooperativeBanks->newEntity();
        $years = [];
        $l_year = date('Y')-23;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
        }

        $bankExist = $this->StateCooperativeBanks->find()->where(['created_by'=>$this->request->session()->read('Auth.User.id'),'status'=>1,'is_delete'=>0])->first();


        $add_flag = 0;
        if(empty($bankExist))
        {
            $add_flag = 1;
        }
        if($this->request->is('post')){

            $data=$this->request->getData();
            
            $data['status'] = 1;
            $data['is_delete'] = 0;
            $data['created'] = date('Y-m-d H:i:s');
            $data['updated'] = date('Y-m-d H:i:s');
            $data['parent_id'] = 0;
            $data['scb_dcb_flag'] = 3;
			//$data['operational_district_code'] = $this->request->session()->read('Auth.User.district_code');
            // echo '<pre>';
            //  print_r($data);die;
			//add his district based on user if urban
			
            if(!empty($data['scb_contact_details']))
            {
                // echo '<pre>';
                // print_r($data['scb_contact_details']);die;
                foreach($data['scb_contact_details'] as $key => $contact_details)
                {
                    // echo '<pre>';
                    // print_r($contact_details);die;
                    $std = '';
                    $landline = '';
                    if(!empty($contact_details['landline']) && !empty($contact_details['std']))
                    {
                        //die('success1');
                        $std = $contact_details['std'];
                        $landline = $contact_details['landline'];
                        $contact_details['landline'] = $std.'-'.$landline;
                        $data['scb_contact_details'][$key]['landline'] = $std.'-'.$landline;
                        unset($data['scb_contact_details'][$key]['std']);
                    }

                }
                    
            }
			
            // if(!empty($data['landline']))
            // {
            //     $std = $data['std'];
            //     $landline = $data['landline'];
            //     $data['landline'] = $std.'-'.$landline;
            // } else {
            //     unset($data['std']);
            // }

            $data['area_of_operation_state_code'] = implode(',',$data['area_of_operation_state_code']);
            
            

            $data['created_by'] = $this->request->session()->read('Auth.User.id');

            //add his district based on user if urban
            if(($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8) && $data['location_urban_rural'] == 1)
            {
                $data['district_code'] = $this->request->session()->read('Auth.User.district_code');
            }

            //===============================================
            //for scheme 
            if($data['is_scheme_implemented'] == 0)
            {
                unset($data['scb_implementing_schemes']);
            }
            
             //for DCCB Member
             if($data['dccb_bank_member'] == 0)
             {
                 unset($data['dccb_bank_is_member']);
                 $data['dccb_bank_member_count'] = 0;
             } else {
                 //pacs_is_member_affiliated
                 $data['dccb_bank_is_member'] = implode(',',$data['dccb_bank_is_member']);
             }
             
 
             if($data['pacs_member'] == 0)
             {
                 unset($data['pacs_is_member_affiliated']);
                 unset($data['pacs_is_member_not_affiliated']);
 
                 $data['pacs_member_count'] = 0;
             } else {
                 array_unique($data['pacs_is_member_affiliated']);
                 array_unique($data['pacs_is_member_not_affiliated']);
 
                 $data['pacs_is_member_affiliated'] = array_merge($data['pacs_is_member_affiliated'],$data['pacs_is_member_not_affiliated']);

                 $data['pacs_is_member_affiliated'] = implode(',',$data['pacs_is_member_affiliated']);
                // $data['pacs_is_member_not_affiliated'] = implode(',',$data['pacs_is_member_not_affiliated']);
 
                $data['pacs_is_member_not_affiliated'] = '';
                
                 $data['society_district_code'] = implode(',',$data['society_district_code']);
                 
                 
             }

            //  if(!empty($data['pacs_is_member_affiliated']) && $data['pacs_member'] == 1)
            //  {
                
            //  }

             //other
             if($data['other_member'] == 0)
             {
                $data['other_member'] = 0; 
                unset($data['dcb_scb_other_members']);
             }

            //===============================================
            
            //unset($data['scb_contact_details']);

            //unset($data['scb_scheme_details']);


            
            
            // unset($data['other_member']);
            // unset($data['other_member_count']);
            // unset($data['scb_others']);

            // echo '<pre>';
            // print_r($data);die;


            $StateCooperativeBank = $this->StateCooperativeBanks->patchEntity($StateCooperativeBank, $data,['associated' => ['ScbImplementingSchemes','ScbContactDetails','ScardbDetails']]);


            
            if($this->StateCooperativeBanks->save($StateCooperativeBank)) {
                
                $this->Flash->success(__('The data has been saved.'));
                //return $this->redirect(['action' => 'add-member',$result['id']]);
                return $this->redirect(['action' => 'index']);
            } 

            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
        }
        $state_code= $this->request->session()->read('Auth.User.state_code');
            $this->loadModel('States');

            if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
            {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
            } else {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code]);
            }
            $this->loadMOdel('UrbanLocalBodies');
            $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$CooperativeRegistration->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
            $this->set('localbody_types',$localbody_types); 
            
            $this->loadMOdel('UrbanLocalBodies');
            $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$CooperativeRegistration->urban_local_body_type_code,'state_code'=>$CooperativeRegistration->state_code])->order(['local_body_name'=>'ASC']);
            $this->set('localbodies',$localbodies); 

            $this->loadMOdel('UrbanLocalBodiesWards');
            $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$CooperativeRegistration->urban_local_body_code])->order(['ward_name'=>'ASC']);
            $this->set('localbodieswards',$localbodieswards);
            
            $this->loadModel('PresentFunctionalStatus');
            $PresentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

            $this->loadModel('Designations');
            $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
            $this->set('designations',$designations);

            $this->set(compact('StateCooperativeBank','states','years','state_code','PresentFunctionalStatus'));


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
        $this->loadModel('StateCooperativeBanks');
        $this->loadModel('Districts');
        $this->loadMOdel('Blocks');
        $this->loadMOdel('DistrictsBlocksGpVillages');      
        $this->loadMOdel('ScbImplementingSchemes');
        $this->loadMOdel('DistrictCentralCooperativeBank');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('PrimaryActivities');
        $this->loadModel('DcbScbOtherMembers');
        
        
        
        $StateCooperativeBank = $this->StateCooperativeBanks->get($id, [
            'contain' => ['ScbContactDetails'=>['sort'=>['id'=>'ASC']],'ScbImplementingSchemes'=>['sort'=>['id'=>'ASC']],'DcbScbOtherMembers'=>['sort'=>['id'=>'ASC']] ]
        ]);

        // echo '<pre>';
        // print_r($StateCooperativeBank);die;
        
        $sectors = [];
        $sectorUrbans = [];
        $area_of_operation_id_urban = '';
        $area_of_operation_id_rural = '';

        $years = [];
        $l_year = date('Y')-23;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
        }
        

        //if data entry user
        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $this->loadMOdel('Users');
            $created_by = $StateCooperativeBank['created_by'];
            $cUser = $this->Users->find('all')->where(['id' => $created_by])->first();

            if(empty($created_by) || $cUser['id'] != $created_by)
            {
                $this->Flash->error(__('You are not authorized.'));
                return $this->redirect(['action' => 'add']);    
            }
        }
        
        if ($this->request->is(['patch', 'post', 'put'])){
            $data=$this->request->getData();

            // echo '<pre>==';
            // print_r($data);die;
            //only for SCB nodal
            if($this->request->session()->read('Auth.User.role_id') != 24)
            {
                return $this->redirect(['action' => 'add']);$this->Flash->error(__('You are not Authorize to add Cooperative Registration'));
            }
            
            $data['area_of_operation_state_code'] = implode(',',$data['area_of_operation_state_code']);

            $data['status'] = 1;
            $data['is_delete'] = 0;
            $data['created_by'] = $this->request->session()->read('Auth.User.id');
            $data['updated'] = date('Y-m-d H:i:s');
            
            
            if(!empty($data['scb_contact_details']))
            {
                // echo '<pre>';
                // print_r($data['scb_contact_details']);die;
                foreach($data['scb_contact_details'] as $key => $contact_details)
                {
                    // echo '<pre>';
                    // print_r($contact_details);die;
                    $std = '';
                    $landline = '';
                    if(!empty($contact_details['landline']) && !empty($contact_details['std']))
                    {
                        //die('success1');
                        $std = $contact_details['std'];
                        $landline = $contact_details['landline'];
                        $contact_details['landline'] = $std.'-'.$landline;
                        $data['scb_contact_details'][$key]['landline'] = $std.'-'.$landline;
                        unset($data['scb_contact_details'][$key]['std']);
                    }

                }
                    
            }

            //add his district based on user if urban
            if(($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8) && $data['location_urban_rural'] == 1)
            {
                $data['district_code'] = $this->request->session()->read('Auth.User.district_code');
            }
            
            //unset($data['scb_contact_details']);

            //unset($data['scb_scheme_details']);

            //=========================================================

            //for DCCB Member
            if($data['dccb_bank_member'] == 0)
            {
                unset($data['dccb_bank_is_member']);
                $data['dccb_bank_member_count'] = 0;
            } else {
                //pacs_is_member_affiliated
                $data['dccb_bank_is_member'] = implode(',',$data['dccb_bank_is_member']);
            }
            

            if($data['pacs_member'] == 0)
            {
                unset($data['pacs_is_member_affiliated']);
                unset($data['pacs_is_member_not_affiliated']);

                $data['pacs_member_count'] = 0;
            } else {
                array_unique($data['pacs_is_member_affiliated']);
                array_unique($data['pacs_is_member_not_affiliated']);


               if(!empty($data['pacs_is_member_affiliated']) && !empty($data['pacs_is_member_not_affiliated']))
               {
                    $data['pacs_is_member_affiliated'] = array_merge($data['pacs_is_member_affiliated'],$data['pacs_is_member_not_affiliated']);
               }

               if(empty($data['pacs_is_member_affiliated']) && !empty($data['pacs_is_member_not_affiliated']))
               {
                    $data['pacs_is_member_affiliated'] = $data['pacs_is_member_not_affiliated'];
               }

                $data['pacs_is_member_affiliated'] = implode(',',$data['pacs_is_member_affiliated']);

                $data['society_district_code'] = implode(',',$data['society_district_code']);
                
            }

            //DCCB Other Membership Details
            if($data['other_member'] == 0)
            {
               $data['other_member_count'] = 0; 
               unset($data['dcb_scb_other_members']);
            }
            //scheme add
            if($data['is_scheme_implemented'] == 0)
            {
               $data['is_scheme_implemented'] = 0; 
               unset($data['scb_implementing_schemes']);
            }


            $data['pacs_is_member_not_affiliated'] = '';

            $data['scb_dcb_flag'] = 1;
            //=========================================================

            // echo '<pre>';
            // print_r($data);die;

            if(!empty($data['dcb_scb_other_members']) && $data['other_member'] == 1)
            {
                
                foreach($data['dcb_scb_other_members'] as $key=>$other_member_data)
                {
                    
                    if($other_member_data['type_membership'] == 2)
                    {

                        if($other_member_data['member_document']['name'] == '')
                        {
                            //$member_document = $this->DcbScbOtherMembers->find('all')->where(['state_cooperative_bank_id' => $id,'type_membership'=>2])->first()->member_document;
                            
                            $member_document = $this->DcbScbOtherMembers->find('all')->where(['state_cooperative_bank_id' => $id,'type_membership'=>2])->first()->member_document;

                            $data['dcb_scb_other_members'][$key]['member_document']  = $member_document;
                        } else {
                            // echo $other_member_data['document'];die;
                            $ext = pathinfo($other_member_data['member_document']['name'], PATHINFO_EXTENSION);

                            $other_member_data['member_document']['name'] = 'document_member_'.$this->request->session()->read('Auth.User.state_code').'_'.date('Y_m_d_H_i_s').'.'.$ext;

                            //echo $other_member_data['document']['name'];die;

                            if($other_member_data['member_document']['name']!=''){
                                $fileName = $this->uploadPdf('member_document', $other_member_data['member_document']);
                                // echo '<pre>';
                                // print_r($fileName);die;
                                $data['dcb_scb_other_members'][$key]['member_document']  = $fileName['filename'];//echo "<pre>"; print_r($fileName); exit;
                            }
                        }
                        
                    } else {
                        $data['dcb_scb_other_members'][$key]['member_document'] = '';
                    }
                }
            }

            $conn = ConnectionManager::get('default');
            $stmt = $conn->execute('DELETE FROM dcb_scb_other_members where state_cooperative_bank_id ="'.$id.'"');

            $this->ScbImplementingSchemes->deleteAll(['state_cooperative_bank_id' => $id]);

            $StateCooperativeBank = $this->StateCooperativeBanks->patchEntity($StateCooperativeBank, $data,['associated' => ['ScbImplementingSchemes','ScbContactDetails','DcbScbOtherMembers']]);
            
            if($this->StateCooperativeBanks->save($StateCooperativeBank)) {
                
                $this->Flash->success(__('The data has been saved.'));
                //return $this->redirect(['action' => 'add-member',$result['id']]);
                return $this->redirect(['action' => 'index']);
            
            }
            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
            
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

            $all_states = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
            $this->set('all_states',$all_states);
        // End States for dropdown


        
        // Start States for dropdown
            $this->loadModel('Districts');
            if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
            {
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$StateCooperativeBank->state_code])->order(['name'=>'ASC']);
            } else {
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']); 
            }
               
            //$districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$CooperativeRegistration->state_code])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        // End States for dropdown
        

        // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            //$blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$CooperativeRegistration->district_code])->order(['name'=>'ASC']);
            $blocks=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$StateCooperativeBank->district_code])->group(['block_code']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown


        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$StateCooperativeBank->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown


        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$StateCooperativeBank->gp_code])->order(['village_name'=>'ASC']); 
        $this->set('villages',$villages);  
        // end villages for dropdown

        // Start CooperativeSocietyTypes for dropdown
            $this->loadModel('CooperativeSocietyTypes');
            $CooperativeSocietyTypes=$this->CooperativeSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC']);
        // End CooperativeSocietyTypes for dropdown
    
       
        

        // Start PrimaryActivities for dropdown
            $this->loadModel('PrimaryActivities');

            $pActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
            $this->set('pActivities',$pActivities); 
            
            $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        // End PrimaryActivities for dropdown
            
        // Start SecondaryActivities for dropdown
            $this->loadModel('SecondaryActivities');
            $SecondaryActivities=$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'sector_of_operation'=>$StateCooperativeBank->sector_of_operation_type])->order(['orderseq'=>'ASC']);

        // End SecondaryActivities for dropdown
        
        // Start PresentFunctionalStatus for dropdown
            $this->loadModel('PresentFunctionalStatus');
            $PresentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        // End PresentFunctionalStatus for dropdown

        
        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$StateCooperativeBank->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies for dropdown
    

        // Start urban_local_bodies for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$StateCooperativeBank->urban_local_body_category_code,'state_code'=>$StateCooperativeBank->state_code])->order(['local_body_name'=>'ASC']);

        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown


        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$StateCooperativeBank->urban_local_body_code])->order(['ward_name'=>'ASC']);

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown

        $this->loadModel('SectorOperations');

       $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sector_operations',$sector_operations); 

       $this->loadModel('RegistrationAuthorities');

       $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['authority_name'=>'ASC']);

       $this->loadModel('Designations');
       $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();

        // Start water_body_types for dropdown
        $this->loadModel('WaterBodyTypes');            
           $water_body_type = $this->WaterBodyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();

       $this->loadMOdel('LevelOfAffiliatedUnion'); 


              // $federationlevel = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1,'primary_activity_id'=>$CooperativeRegistration->sector_of_operation])->order(['id'=>'ASC'])->toArray();


        $federationlevel = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1,'primary_activity_id'=>$primary_id])->order(['id'=>'ASC'])->toArray();

        $all_districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

        $dccb_banks = $this->DistrictCentralCooperativeBank->find('all')->where(['dccb_flag' => '1','primary_activity_flag'=>'1','state_code'=>$this->request->session()->read('Auth.User.state_code')])->toArray();

        $society = [];
        $society1 = [];

        

        
        //if($StateCooperativeBank->pacs_is_member_affiliated == '' || $StateCooperativeBank->pacs_is_member_affiliated == null)
        if($StateCooperativeBank->pacs_member == 0)
        {
            $district_code = $this->Districts->find('all')->where(['state_code' => $this->request->session()->read('Auth.User.state_code')])->first()->district_code;

            if(!empty($district_code))
            {
                $StateCooperativeBank->society_district_code = $district_code;
                
            }
            
            $society=$this->CooperativeRegistrations->find('all')->where(['is_affiliated_union_federation'=>1,'status'=>1,'operational_district_code'=>$district_code,'sector_of_operation IN '=>[1,20,22]])->order(['cooperative_society_name'=>'ASC'])->toArray();
            $StateCooperativeBank->pacs_member_count = count($society);    

            $society1=$this->CooperativeRegistrations->find('all')->where(['is_affiliated_union_federation'=>0,'status'=>1,'operational_district_code'=>$district_code,'sector_of_operation IN '=>[1,20,22]])->order(['cooperative_society_name'=>'ASC'])->toArray();

        } else {

            $arr_pacs_is_member_affiliated = explode(',',$StateCooperativeBank->pacs_is_member_affiliated);
            $arr_society_district_code = explode(',',$StateCooperativeBank->society_district_code);

            $society =$this->CooperativeRegistrations->find('all')->where(['id IN'=>$arr_pacs_is_member_affiliated,'operational_district_code IN'=>$arr_society_district_code,'status'=>1,'sector_of_operation IN '=>[1,20,22]])->order(['operational_district_code'=>'ASC'])->toArray();

            $society1 =$this->CooperativeRegistrations->find('all')->where(['id NOT IN'=>$arr_pacs_is_member_affiliated,'operational_district_code IN'=>$arr_society_district_code,'status'=>1,'sector_of_operation IN '=>[1,20,22]])->order(['operational_district_code'=>'ASC'])->toArray();
        }

        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$StateCooperativeBank->sector_of_operation,'status'=>1])->order(['orderseq'=>'ASC'])->toArray();

        

        $this->set(compact('StateCooperativeBank','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','pActivities','years','register_authorities','sectors','designations','water_body_type','federationlevel','sectorUrbans','area_of_operation_id_urban','area_of_operation_id_rural','all_districts','dccb_banks','society','society1','primary_activities'));
    }

    /**
     * View method
     *
     * @param string|null $id State id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function View($id)
    {
        $this->loadModel('StateCooperativeBanks');
        $this->loadModel('Districts');
        $this->loadMOdel('Blocks');
        $this->loadMOdel('DistrictsBlocksGpVillages');      
        $this->loadMOdel('ScbImplementingSchemes');

        $StateCooperativeBank = $this->StateCooperativeBanks->get($id, [
            'contain' => ['ScbContactDetails'=>['sort'=>['id'=>'ASC']],'ScbImplementingSchemes'=>['sort'=>['id'=>'ASC']] ]
        ]);

        

        $sectors = [];
        $sectorUrbans = [];
        $area_of_operation_id_urban = '';
        $area_of_operation_id_rural = '';
        
        // Start States for dropdown
          //  $this->loadModel('States');
             $state_code= $this->request->session()->read('Auth.User.state_code');

             
            $this->loadModel('States');
            


            

            $all_states = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
            
            $this->set('all_states',$all_states);
        // End States for dropdown


        // Start States for dropdown
            $this->loadModel('Districts');
            if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
            {
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$StateCooperativeBank->state_code])->order(['name'=>'ASC']);
            } else {
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']); 
            }
               
            //$districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$CooperativeRegistration->state_code])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        // End States for dropdown
        

        // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            //$blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$CooperativeRegistration->district_code])->order(['name'=>'ASC']);
            $blocks=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$StateCooperativeBank->district_code])->group(['block_code']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown


        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$StateCooperativeBank->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown


        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$StateCooperativeBank->gp_code])->order(['village_name'=>'ASC']); 
        $this->set('villages',$villages);  
        // end villages for dropdown
        



        // Start CooperativeSocietyTypes for dropdown
            $this->loadModel('CooperativeSocietyTypes');
            $CooperativeSocietyTypes=$this->CooperativeSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC']);
        // End CooperativeSocietyTypes for dropdown
    
       
        

        // Start PrimaryActivities for dropdown
            $this->loadModel('PrimaryActivities');

            $pActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$StateCooperativeBank->sector_of_operation_type,'status'=>1])->order(['orderseq'=>'ASC']);
            $this->set('pActivities',$pActivities); 
            
            // $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$StateCooperativeBank->sector_of_operation_type,'status'=>1])->order(['orderseq'=>'ASC']);
            $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

        // End PrimaryActivities for dropdown
            
        // Start SecondaryActivities for dropdown
            $this->loadModel('SecondaryActivities');
            $SecondaryActivities=$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'sector_of_operation'=>$StateCooperativeBank->sector_of_operation_type])->order(['orderseq'=>'ASC']);

        // End SecondaryActivities for dropdown
        
        // Start PresentFunctionalStatus for dropdown
            $this->loadModel('PresentFunctionalStatus');
            $PresentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        // End PresentFunctionalStatus for dropdown

        
        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$StateCooperativeBank->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies for dropdown
    

        // Start urban_local_bodies for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$StateCooperativeBank->urban_local_body_category_code,'state_code'=>$StateCooperativeBank->state_code])->order(['local_body_name'=>'ASC']);

        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown

        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$StateCooperativeBank->urban_local_body_code])->order(['ward_name'=>'ASC']);

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown

        $this->loadModel('SectorOperations');

       $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sector_operations',$sector_operations); 

       $this->loadModel('RegistrationAuthorities');

       $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['authority_name'=>'ASC']);

       $this->loadModel('Designations');
       $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();

        // Start water_body_types for dropdown
        $this->loadModel('WaterBodyTypes');            
           $water_body_type = $this->WaterBodyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();

       $this->loadMOdel('LevelOfAffiliatedUnion'); 

       $locationOfHeadquarter = [
        '1' => 'Urban',
        '2' => 'Rural',
    ];

    $states_find=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
    $districts_find=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
    $blocks_find=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
    $panchayats_find = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  
    $villages_find = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1])->order(['village_name'=>'ASC'])->toArray();


              // $federationlevel = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1,'primary_activity_id'=>$CooperativeRegistration->sector_of_operation])->order(['id'=>'ASC'])->toArray();
        $society = [];
        $arr_society_district_code = explode(',',$StateCooperativeBank->society_district_code); 
        $this->loadMOdel('CooperativeRegistrations');
        if(!empty($arr_society_district_code))
        {
            $society =$this->CooperativeRegistrations->find('all')->where(['is_affiliated_union_federation'=>1,'status'=>1,'operational_district_code IN'=>$arr_society_district_code,'sector_of_operation IN '=>[1,20,22]])->order(['operational_district_code'=>'ASC'])->toArray();
        }
              
              $this->loadModel('DcbScbOtherMembers');
              $dcb_scb_other_members = $this->DcbScbOtherMembers->find('all')->where(['state_cooperative_bank_id'=>$StateCooperativeBank->id])->toArray();

              $this->loadMOdel('DistrictCentralCooperativeBank');
              $dccb_banks = $this->DistrictCentralCooperativeBank->find('all')->where(['dccb_flag' => '1','primary_activity_flag'=>'1','state_code'=>$state_code])->toArray();
        

        $this->set(compact('villages_find','blocks_find','panchayats_find','districts_find','states_find','StateCooperativeBank','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','pActivities','years','register_authorities','sectors','designations','water_body_type','sectorUrbans','area_of_operation_id_urban','area_of_operation_id_rural','locationOfHeadquarter','dcb_scb_other_members','dccb_banks','society'));
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
        $this->loadModel('StateCooperativeBanks');

        $this->request->allowMethod(['post', 'delete']);

        $StateCooperativeBank = $this->StateCooperativeBanks->get($id);

        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $this->loadMOdel('Users');
            // echo '<pre>';
            // print_r($CooperativeRegistration);die;   
            $created_by = $StateCooperativeBank['created_by'];
            $cUser = $this->Users->find('all')->where(['id' => $created_by])->first();

            if(empty($created_by) || $cUser['id'] != $created_by)
            {
                $this->Flash->error(__('You are not authorized.'));
                return $this->redirect(['action' => 'index']);
            }
        }


        $data['is_delete'] = 1;
        $data['updated_by'] = $this->request->session()->read('Auth.User.id');
        $data['updated'] = date('Y-m-d H:i:s');
        $StateCooperativeBank = $this->StateCooperativeBanks->patchEntity($StateCooperativeBank, $data);

        if($this->StateCooperativeBanks->save($StateCooperativeBank)) {
            $this->Flash->success(__('The SCB has been deleted.'));
        }
        $this->redirect($this->referer());

      
    }


public function deleteMember($id = null)
    {
        $this->loadModel('StateCooperativeBanks');

        $this->request->allowMethod(['post', 'delete']);

        $StateCooperativeBank = $this->StateCooperativeBanks->get($id);

        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $this->loadMOdel('Users');
            // echo '<pre>';
            // print_r($CooperativeRegistration);die;   
            $created_by = $StateCooperativeBank['created_by'];
            $cUser = $this->Users->find('all')->where(['id' => $created_by])->first();

            if(empty($created_by) || $cUser['id'] != $created_by)
            {
                $this->Flash->error(__('You are not authorized.'));
                return $this->redirect(['action' => 'index']);
            }
        }


        $data['is_delete'] = 1;
        $data['updated_by'] = $this->request->session()->read('Auth.User.id');
        $data['updated'] = date('Y-m-d H:i:s');
        $StateCooperativeBank = $this->StateCooperativeBanks->patchEntity($StateCooperativeBank, $data);

        if($this->StateCooperativeBanks->save($StateCooperativeBank)) {
            $this->Flash->success(__('The SCB has been deleted.'));
        }
        $this->redirect($this->referer());

      
    }

    /**
     * Delete method
     *
     * @param string|null $id State id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deleteMember_old($id = null)
    {
        $this->loadModel('DcbScbMembers');

        $this->request->allowMethod(['post', 'delete']);

        $DcbScbMember = $this->DcbScbMembers->get($id);

        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $this->loadMOdel('Users');
            // echo '<pre>';
            // print_r($CooperativeRegistration);die;   
            $created_by = $DcbScbMember['created_by'];
            $cUser = $this->Users->find('all')->where(['id' => $created_by])->first();

            if(empty($created_by) || $cUser['id'] != $created_by)
            {
                $this->Flash->error(__('You are not authorized.'));
                return $this->redirect(['action' => 'index']);
            }
        }


        $data['is_delete'] = 1;
        $data['created_by'] = $this->request->session()->read('Auth.User.id');
        $data['updated'] = date('Y-m-d H:i:s');
        $DcbScbMember = $this->DcbScbMembers->patchEntity($DcbScbMember, $data);

        if($this->DcbScbMembers->save($DcbScbMember)) {
            $this->Flash->success(__('The Member has been deleted.'));
        }
        $this->redirect($this->referer());

      
    }

     public function addMember()
    {
        $this->loadModel('StateCooperativeBanks');
        $this->loadModel('CooperativeRegistrations');
        $this->loadModel('Districts');
        
    
        $StateCooperativeBank = $this->StateCooperativeBanks->newEntity();
        $years = [];
        $l_year = date('Y')-23;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
        }


        $bankExist = $this->StateCooperativeBanks->find()->where(['scb_dcb_flag'=>2,'created_by'=>$this->request->session()->read('Auth.User.id'),'status'=>1,'is_delete'=>0])->first();
        //$bankExist = $this->StateCooperativeBanks->find()->where(['scb_dcb_flag'=>1,'created_by'=>$this->request->session()->read('Auth.User.created_by'),'status'=>1,'is_delete'=>0])->first();
        // echo "<pre>";
        //  print_r($bankExist->id); die;

        $add_flag = 0;
        if(empty($bankExist))
        {
            $add_flag = 1;
        }

        if($this->request->session()->read('Auth.User.role_id') != 25)
        {
            return $this->redirect(['action' => 'member-list']);$this->Flash->error(__('You are not Authorize to add DCCB'));
        }

        
        if(!empty($bankExist))
        {
            return $this->redirect(['action' => 'member-list']);$this->Flash->error(__('You are not Authorize to add DCCB'));
        }

        $this->loadMOdel('DistrictCentralCooperativeBank');

        $dcb_bank_list = $this->DistrictCentralCooperativeBank->find('list')->where(['entity_type' => 'DCCB','district_code'=>$this->request->session()->read('Auth.User.district_code')])->toArray();
        // echo "<pre>";
        // print_r($dcb_bank_list);die;

       // $bank_name = $this->DistrictCentralCooperativeBank->find('all')->where(['entity_type' => 'stcb','state_code'=>$this->request->session()->read('Auth.User.state_code')])->first();

       
        $district_code = $this->Districts->find('all')->where(['state_code' => $this->request->session()->read('Auth.User.state_code')])->first()->district_code;

        if(!empty($district_code))
        {
            $StateCooperativeBank->society_district_code = $district_code;    
        }

        $society=$this->CooperativeRegistrations->find('all')->where(['is_affiliated_union_federation'=>1,'status'=>1,'operational_district_code'=>$district_code,'sector_of_operation IN '=>[1,20,22]])->order(['cooperative_society_name'=>'ASC'])->toArray();

        $StateCooperativeBank->pacs_member_count = count($society);

        $society1=$this->CooperativeRegistrations->find('all')->where(['is_affiliated_union_federation'=>0,'status'=>1,'operational_district_code'=>$district_code,'sector_of_operation IN '=>[1,20,22]])->order(['cooperative_society_name'=>'ASC'])->toArray();

        $district_name = $this->Districts->find('all')->where(['district_code' => $district_code])->first()->name;
        
        
        if($this->request->is('post')){

            $data=$this->request->getData();
            
            $data['status'] = 1;
            $data['is_delete'] = 0;
            $data['created'] = date('Y-m-d H:i:s');
            $data['updated'] = date('Y-m-d H:i:s');

            $data['scb_dcb_flag'] = 2;
            
             if(!empty($bankExist))
            {
                $data['parent_id'] = $bankExist->id;
            } else {
                $data['parent_id'] = 0;
            }

            //$data['operational_district_code'] = $this->request->session()->read('Auth.User.district_code');

            //add his district based on user if urban
            
            if(!empty($data['scb_contact_details']))
            {
                // echo '<pre>';
                // print_r($data['scb_contact_details']);die;
                foreach($data['scb_contact_details'] as $key => $contact_details)
                {
                    // echo '<pre>';
                    // print_r($contact_details);die;
                    $std = '';
                    $landline = '';
                    if(!empty($contact_details['landline']) && !empty($contact_details['std']))
                    {
                        //die('success1');
                        $std = $contact_details['std'];
                        $landline = $contact_details['landline'];
                        $contact_details['landline'] = $std.'-'.$landline;
                        $data['scb_contact_details'][$key]['landline'] = $std.'-'.$landline;
                        unset($data['scb_contact_details'][$key]['std']);
                    }

                }
                    
            }
            
            // if(!empty($data['landline']))
            // {
            //     $std = $data['std'];
            //     $landline = $data['landline'];
            //     $data['landline'] = $std.'-'.$landline;
            // } else {
            //     unset($data['std']);
            // }

            $data['area_of_operation_state_code'] = implode(',',$data['area_of_operation_state_code']);
       
            

            $data['created_by'] = $this->request->session()->read('Auth.User.id');

            //add his district based on user if urban
            if(($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8) && $data['location_urban_rural'] == 1)
            {
                $data['district_code'] = $this->request->session()->read('Auth.User.district_code');
            }
            
            //===============================================

             //for DCCB Member
             if($data['dccb_bank_member'] == 0)
             {
                 unset($data['dccb_bank_is_member']);
                 $data['dccb_bank_member_count'] = 0;
             } else {
                 //pacs_is_member_affiliated
                 $data['dccb_bank_is_member'] = implode(',',$data['dccb_bank_is_member']);
             }
             
 
             if($data['pacs_member'] == 0)
             {
                 unset($data['pacs_is_member_affiliated']);
                 unset($data['pacs_is_member_not_affiliated']);
 
                 $data['pacs_member_count'] = 0;
             } else {
                array_unique($data['pacs_is_member_affiliated']);
                array_unique($data['pacs_is_member_not_affiliated']);


               if(!empty($data['pacs_is_member_affiliated']) && !empty($data['pacs_is_member_not_affiliated']))
               {
                    $data['pacs_is_member_affiliated'] = array_merge($data['pacs_is_member_affiliated'],$data['pacs_is_member_not_affiliated']);
               }

               if(empty($data['pacs_is_member_affiliated']) && !empty($data['pacs_is_member_not_affiliated']))
               {
                    $data['pacs_is_member_affiliated'] = $data['pacs_is_member_not_affiliated'];
               }

                $data['pacs_is_member_affiliated'] = implode(',',$data['pacs_is_member_affiliated']);
 
                 $data['society_district_code'] = implode(',',$data['society_district_code']);
                 
             }

             //DCCB Other Membership Details
             if($data['other_member'] == 0)
             {
                $data['other_member'] = 0; 
                unset($data['dcb_scb_other_members']);
             }
             //scheme add
             if($data['is_scheme_implemented'] == 0)
             {
                $data['is_scheme_implemented'] = 0; 
                unset($data['scb_implementing_schemes']);
             }

            //===============================================


            //unset($data['scb_contact_details']);

            //unset($data['scb_scheme_details']);

            // array_unique($data['pacs_is_member_affiliated']);
            // array_unique($data['pacs_is_member_not_affiliated']);

            // $data['dccb_bank_is_member'] = implode(',',$data['dccb_bank_is_member']);

            // $data['pacs_is_member_affiliated'] = implode(',',$data['pacs_is_member_affiliated']);

            // $data['pacs_is_member_not_affiliated'] = implode(',',$data['pacs_is_member_not_affiliated']);

            
            
            // unset($data['other_member']);
            // unset($data['other_member_count']);
            // unset($data['scb_others']);

            
             if(!empty($data['dcb_scb_other_members']) && $data['other_member'] == 1)
            {
                
                foreach($data['dcb_scb_other_members'] as $key=>$other_member_data)
                        {
                            if($other_member_data['type_membership'] == 2 && !empty($other_member_data['member_document']))
                            {
                                
                                // echo $other_member_data['document'];die;
                                $ext = pathinfo($other_member_data['member_document']['name'], PATHINFO_EXTENSION);

                                $other_member_data['member_document']['name'] = 'document_member_'.$this->request->session()->read('Auth.User.state_code').'_'.date('Y_m_d_H_i_s').'.'.$ext;

                                //echo $other_member_data['document']['name'];die;

                                if($other_member_data['member_document']['name']!=''){
                                    $fileName = $this->uploadPdf('member_document', $other_member_data['member_document']);
                                    // echo '<pre>';
                                    // print_r($fileName);die;
                                    $data['dcb_scb_other_members'][$key]['member_document']  = $fileName['filename'];//echo "<pre>"; print_r($fileName); exit;
                                }
                            } else {
                                $data['dcb_scb_other_members'][$key]['member_document'] = '';
                            }
                        }
            }
            
            $StateCooperativeBank = $this->StateCooperativeBanks->patchEntity($StateCooperativeBank, $data,['associated' => ['ScbImplementingSchemes','ScbContactDetails','DcbScbOtherMembers']]);


            
            if($this->StateCooperativeBanks->save($StateCooperativeBank)) {
                
                $this->Flash->success(__('The data has been saved.'));
                //return $this->redirect(['action' => 'add-member',$result['id']]);
                return $this->redirect(['action' => 'member-list']);
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

        $all_states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
            $this->set('all_states',$all_states);    

        ####### degination dropdown#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        ###################end ############


        // Start States for dropdown
            
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
            $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

            
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


              //  Start register_authorty dropdown registration_authorities
            $this->loadModel('RegistrationAuthorities');
            $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['authority_name'=>'ASC']);
            $this->set('register_authorities',$register_authorities);
        // End registration_authorities for dropdown

        $all_districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC'])->toArray();
        

        $this->loadMOdel('DistrictCentralCooperativeBank');

        $dccb_banks = $this->DistrictCentralCooperativeBank->find('all')->where(['dccb_flag' => '1','primary_activity_flag'=>'1','state_code'=>$this->request->session()->read('Auth.User.state_code')])->toArray();
        
        $this->set(compact('StateCooperativeBank','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','years','state_code','all_districts','dccb_banks','society','society1','district_name','dcb_bank_list'));
        
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function addMember_old()
    {
        $this->loadModel('StateCooperativeBanks');
        $this->loadModel('DcbScbMembers');

        $bankExist = $this->StateCooperativeBanks->find()->where(['created_by'=>$this->request->session()->read('Auth.User.created_by'),'status'=>1,'is_delete'=>0,'scb_dcb_flag'=>1])->first();

        $member_entered = $this->StateCooperativeBanks->find()->where(['created_by'=>$this->request->session()->read('Auth.User.id'),'status'=>1,'is_delete'=>0,'scb_dcb_flag'=>2])->count();

        if($member_entered >= $bankExist->number_of_member)
        {
            $this->Flash->error(__('All Member are already entered.'));
            return $this->redirect(['action' => 'memberList']);
        }

        $bankExist->number_of_member;
        if($this->request->session()->read('Auth.User.role_id') != 25)
        {
            $this->Flash->error(__('You are not Authorize to add SCB Member'));
            return $this->redirect(['action' => 'memberList']);
        }

        if(empty($bankExist))
        {
            $this->Flash->error(__('SCB Detail is not entered by SCB Nodal'));
            return $this->redirect(['action' => 'memberList']);
        }
    
        $DcbScbMember = $this->DcbScbMembers->newEntity();
        
        if($this->request->is('post')){

            //if not scb deo
            if($this->request->session()->read('Auth.User.role_id') != 25)
            {
                return $this->redirect(['action' => 'index']);$this->Flash->error(__('You are not Authorize to add Member'));
            }

            $district_code = $this->request->session()->read('Auth.User.district_code');

            $data=$this->request->getData();

            if(!empty($data['under_computerised_scheme']))
            {   
                $this->loadModel('CooperativeRegistrations');

                $query = $this->CooperativeRegistrations->query();
                $query->update()
                    ->set(['under_computerised_scheme' => 1])
                    ->where(['id IN' => $data['under_computerised_scheme']])
                    ->execute();
            }

            unset($data['under_computerised_scheme']);
            
            $data['state_cooperative_bank_id'] = $bankExist->id;
            $data['status'] = 1;
            $data['is_delete'] = 0;
            $data['created'] = date('Y-m-d H:i:s');
            $data['updated'] = date('Y-m-d H:i:s');
            //$data['operational_district_code'] = $this->request->session()->read('Auth.User.district_code');
            

            $data['society_id'] = implode(',',$data['society_id']);

            

            //add his district based on user if urban
            if(($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8) && $data['location_urban_rural'] == 1)
            {
                $data['district_code'] = $this->request->session()->read('Auth.User.district_code');
            }
           
            $data['created_by'] = $this->request->session()->read('Auth.User.id');

            //add his district based on user if urban
            if($data['location_urban_rural'] == 1)
            {
                $data['district_code'] = $this->request->session()->read('Auth.User.district_code');
            }
            
            // echo '<pre>';
            // print_r($data);die;
            $DcbScbMember = $this->DcbScbMembers->patchEntity($DcbScbMember, $data,['associated' => ['DcbImplementingSchemes']]);
            
            if($this->DcbScbMembers->save($DcbScbMember)) {

                
                
                $this->Flash->success(__('Member has been added.'));
                //return $this->redirect(['action' => 'add-member',$result['id']]);
                return $this->redirect(['action' => 'memberList']);
            
            }
            $this->Flash->error(__('The Member Could not be saved. Please, Fill the form and try again.'));
        }

        $years = [];
        $l_year = date('Y')-23;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
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

        $all_states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
            $this->set('all_states',$all_states);    

        ####### degination dropdown#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        ###################end ############


        // Start States for dropdown
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code,'district_code'=>$this->request->session()->read('Auth.User.district_code')])->order(['name'=>'ASC']);
            $this->set('districts',$districts);

            $all_districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']);
            $this->set('all_districts',$all_districts);
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
            $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

            
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


              //  Start register_authorty dropdown registration_authorities
            $this->loadModel('RegistrationAuthorities');
            $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['authority_name'=>'ASC']);
            $this->set('register_authorities',$register_authorities);
        // End registration_authorities for dropdown

        $this->set(compact('StateCooperativeBank','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','years','state_code'));
        
        
    }

    public function editMember($id = null)
    {
        $this->loadModel('StateCooperativeBanks');
        $this->loadModel('Districts');
        $this->loadMOdel('Blocks');
        $this->loadMOdel('DistrictsBlocksGpVillages');      
        $this->loadMOdel('ScbImplementingSchemes');
        $this->loadMOdel('DistrictCentralCooperativeBank');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('PrimaryActivities');
        $this->loadModel('DcbScbOtherMembers');
        
        
        
        $StateCooperativeBank = $this->StateCooperativeBanks->get($id, [
            'contain' => ['ScbContactDetails'=>['sort'=>['id'=>'ASC']],'ScbImplementingSchemes'=>['sort'=>['id'=>'ASC']],'DcbScbOtherMembers'=>['sort'=>['id'=>'ASC']] ]
        ]);

        // echo '<pre>';
        // print_r($StateCooperativeBank);die;

        $dcb_bank_list = $this->DistrictCentralCooperativeBank->find('list')->where(['entity_type' => 'DCCB','district_code'=>$this->request->session()->read('Auth.User.district_code')])->toArray();
        
        $sectors = [];
        $sectorUrbans = [];
        $area_of_operation_id_urban = '';
        $area_of_operation_id_rural = '';

        $years = [];
        $l_year = date('Y')-23;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
        }
        

        //if data entry user
        if($this->request->session()->read('Auth.User.role_id') == 7)
        {
            $this->loadMOdel('Users');
            $created_by = $StateCooperativeBank['created_by'];
            $cUser = $this->Users->find('all')->where(['id' => $created_by])->first();

            if(empty($created_by) || $cUser['id'] != $created_by)
            {
                $this->Flash->error(__('You are not authorized.'));
                return $this->redirect(['action' => 'add']);    
            }
        }
        
        if ($this->request->is(['patch', 'post', 'put'])){
            $data=$this->request->getData();

            // echo '<pre>';
            // print_r($data);die;
            //only for SCB nodal
            if($this->request->session()->read('Auth.User.role_id') != 25)
            {
                return $this->redirect(['action' => 'add']);$this->Flash->error(__('You are not Authorize to add Cooperative Registration'));
            }
            
            $data['area_of_operation_state_code'] = implode(',',$data['area_of_operation_state_code']);

            $data['status'] = 1;
            $data['is_delete'] = 0;
            $data['created_by'] = $this->request->session()->read('Auth.User.id');
            $data['updated'] = date('Y-m-d H:i:s');
            
            
            if(!empty($data['scb_contact_details']))
            {
                // echo '<pre>';
                // print_r($data['scb_contact_details']);die;
                foreach($data['scb_contact_details'] as $key => $contact_details)
                {
                    // echo '<pre>';
                    // print_r($contact_details);die;
                    $std = '';
                    $landline = '';
                    if(!empty($contact_details['landline']) && !empty($contact_details['std']))
                    {
                        //die('success1');
                        $std = $contact_details['std'];
                        $landline = $contact_details['landline'];
                        $contact_details['landline'] = $std.'-'.$landline;
                        $data['scb_contact_details'][$key]['landline'] = $std.'-'.$landline;
                        unset($data['scb_contact_details'][$key]['std']);
                    }

                }
                    
            }

            //add his district based on user if urban
            if(($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8) && $data['location_urban_rural'] == 1)
            {
                $data['district_code'] = $this->request->session()->read('Auth.User.district_code');
            }
            
            //unset($data['scb_contact_details']);

            //unset($data['scb_scheme_details']);


            //===============================================

             //for DCCB Member
             if($data['dccb_bank_member'] == 0)
             {
                 unset($data['dccb_bank_is_member']);
                 $data['dccb_bank_member_count'] = 0;
             } else {
                 //pacs_is_member_affiliated
                 $data['dccb_bank_is_member'] = implode(',',$data['dccb_bank_is_member']);
             }
             
 
             if($data['pacs_member'] == 0)
             {
                 unset($data['pacs_is_member_affiliated']);
                 unset($data['pacs_is_member_not_affiliated']);
 
                 $data['pacs_member_count'] = 0;
             } else {
                // if(!empty($data['pacs_is_member_affiliated']))
                // {
                //     array_unique($data['pacs_is_member_affiliated']);
                // }

                // if(!empty($data['pacs_is_member_not_affiliated']))
                // {
                //     array_unique($data['pacs_is_member_not_affiliated']);
                // }


               if(!empty($data['pacs_is_member_affiliated']) && !empty($data['pacs_is_member_not_affiliated']))
               {
                    $data['pacs_is_member_affiliated'] = array_merge($data['pacs_is_member_affiliated'],$data['pacs_is_member_not_affiliated']);
               }

               if(empty($data['pacs_is_member_affiliated']) && !empty($data['pacs_is_member_not_affiliated']))
               {
                    $data['pacs_is_member_affiliated'] = $data['pacs_is_member_not_affiliated'];
               }

                $data['pacs_is_member_affiliated'] = implode(',',$data['pacs_is_member_affiliated']);

                $data['society_district_code'] = implode(',',$data['society_district_code']);

                 //unset($data['pacs_is_member_not_affiliated']);

                 
             }

             //DCCB Other Membership Details
             if($data['other_member'] == 0)
             {
                $data['other_member'] = 0; 
                unset($data['dcb_scb_other_members']);
             }
             //scheme add
             if($data['is_scheme_implemented'] == 0)
             {
                $data['is_scheme_implemented'] = 0; 
                unset($data['scb_implementing_schemes']);
             }

            //===============================================

            //=========================================================

            // array_unique($data['pacs_is_member_affiliated']);
            // array_unique($data['pacs_is_member_not_affiliated']);

            // $data['dccb_bank_is_member'] = implode(',',$data['dccb_bank_is_member']);

            // $data['pacs_is_member_affiliated'] = implode(',',$data['pacs_is_member_affiliated']);

            // $data['pacs_is_member_not_affiliated'] = implode(',',$data['pacs_is_member_not_affiliated']);

            // $data['society_district_code'] = implode(',',$data['society_district_code']);

            $data['scb_dcb_flag'] = 2;
            //=========================================================

            // echo '<pre>';
            // print_r($data);die;

            

            if(!empty($data['dcb_scb_other_members']) && $data['other_member'] == 1)
            {
                
                foreach($data['dcb_scb_other_members'] as $key=>$other_member_data)
                {
                    
                    if($other_member_data['type_membership'] == 2)
                    {

                        if($other_member_data['member_document']['name'] == '')
                        {
                            //$member_document = $this->DcbScbOtherMembers->find('all')->where(['state_cooperative_bank_id' => $id,'type_membership'=>2])->first()->member_document;
                            
                            $member_document = $this->DcbScbOtherMembers->find('all')->where(['state_cooperative_bank_id' => $id,'type_membership'=>2])->first()->member_document;

                            $data['dcb_scb_other_members'][$key]['member_document']  = $member_document;
                        } else {
                            
                            // echo $other_member_data['document'];die;
                            $ext = pathinfo($other_member_data['member_document']['name'], PATHINFO_EXTENSION);
                            
                            $other_member_data['member_document']['name'] = 'document_member_'.$this->request->session()->read('Auth.User.state_code').'_'.date('Y_m_d_H_i_s').'.'.$ext;

                            //echo $other_member_data['document']['name'];die;

                            if($other_member_data['member_document']['name']!=''){
                                $fileName = $this->uploadPdf('member_document', $other_member_data['member_document']);
                                // echo '<pre>';
                                // print_r($fileName);die;
                                $data['dcb_scb_other_members'][$key]['member_document']  = $fileName['filename'];//echo "<pre>"; print_r($fileName); exit;
                            }
                        }
                        
                    } else {
                        $data['dcb_scb_other_members'][$key]['member_document'] = '';
                    }
                }
            }

            $conn = ConnectionManager::get('default');
            $stmt = $conn->execute('DELETE FROM dcb_scb_other_members where state_cooperative_bank_id ="'.$id.'"');

            $this->ScbImplementingSchemes->deleteAll(['state_cooperative_bank_id' => $id]);

            $StateCooperativeBank = $this->StateCooperativeBanks->patchEntity($StateCooperativeBank, $data,['associated' => ['ScbImplementingSchemes','ScbContactDetails','DcbScbOtherMembers']]);
            
            if($this->StateCooperativeBanks->save($StateCooperativeBank)) {
                
                $this->Flash->success(__('The data has been saved.'));
                //return $this->redirect(['action' => 'add-member',$result['id']]);
                return $this->redirect(['action' => 'memberList']);
            
            }
            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
            
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

            $all_states = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
            $this->set('all_states',$all_states);
        // End States for dropdown


        
        // Start States for dropdown
            $this->loadModel('Districts');
            if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
            {
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$StateCooperativeBank->state_code])->order(['name'=>'ASC']);
            } else {
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']); 
            }
               
            //$districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$CooperativeRegistration->state_code])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        // End States for dropdown
        

        // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            //$blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$CooperativeRegistration->district_code])->order(['name'=>'ASC']);
            $blocks=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$StateCooperativeBank->district_code])->group(['block_code']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown


        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$StateCooperativeBank->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown


        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$StateCooperativeBank->gp_code])->order(['village_name'=>'ASC']); 
        $this->set('villages',$villages);  
        // end villages for dropdown

        // Start CooperativeSocietyTypes for dropdown
            $this->loadModel('CooperativeSocietyTypes');
            $CooperativeSocietyTypes=$this->CooperativeSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC']);
        // End CooperativeSocietyTypes for dropdown
    
       
        

        // Start PrimaryActivities for dropdown
            $this->loadModel('PrimaryActivities');

            $pActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
            $this->set('pActivities',$pActivities); 
            
            $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        // End PrimaryActivities for dropdown
            
        // Start SecondaryActivities for dropdown
            $this->loadModel('SecondaryActivities');
            $SecondaryActivities=$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'sector_of_operation'=>$StateCooperativeBank->sector_of_operation_type])->order(['orderseq'=>'ASC']);

        // End SecondaryActivities for dropdown
        
        // Start PresentFunctionalStatus for dropdown
            $this->loadModel('PresentFunctionalStatus');
            $PresentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        // End PresentFunctionalStatus for dropdown

        
        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$StateCooperativeBank->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies for dropdown
    

        // Start urban_local_bodies for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$StateCooperativeBank->urban_local_body_category_code,'state_code'=>$StateCooperativeBank->state_code])->order(['local_body_name'=>'ASC']);

        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown


        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$StateCooperativeBank->urban_local_body_code])->order(['ward_name'=>'ASC']);

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown

        $this->loadModel('SectorOperations');

       $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sector_operations',$sector_operations); 

       $this->loadModel('RegistrationAuthorities');

       $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['authority_name'=>'ASC']);

       $this->loadModel('Designations');
       $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();

        // Start water_body_types for dropdown
        $this->loadModel('WaterBodyTypes');            
           $water_body_type = $this->WaterBodyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();

       $this->loadMOdel('LevelOfAffiliatedUnion'); 


              // $federationlevel = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1,'primary_activity_id'=>$CooperativeRegistration->sector_of_operation])->order(['id'=>'ASC'])->toArray();


        $federationlevel = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1,'primary_activity_id'=>$primary_id])->order(['id'=>'ASC'])->toArray();
        $state_codes= $this->request->session()->read('Auth.User.state_code');

        $all_districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['state_code'=>$state_codes,'flag'=>1])->order(['name'=>'ASC'])->toArray();

        $dccb_banks = $this->DistrictCentralCooperativeBank->find('all')->where(['dccb_flag' => '1','primary_activity_flag'=>'1','state_code'=>$this->request->session()->read('Auth.User.state_code')])->toArray();

        $society = [];
        $society1 = [];

        $arr_society_district_code = explode(',',$StateCooperativeBank->society_district_code); 
        $pacs_is_member_affiliated = explode(',',$StateCooperativeBank->pacs_is_member_affiliated); 

          if(!empty($arr_society_district_code))
        {
            $society =$this->CooperativeRegistrations->find('all')->where(['status'=>1,'operational_district_code IN'=>$arr_society_district_code,'id In'=>$pacs_is_member_affiliated,'sector_of_operation IN '=>[1,20,22]])->order(['operational_district_code'=>'ASC'])->toArray();
           
        }

        if(!empty($arr_society_district_code))
        {
            $society1=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'operational_district_code IN'=>$arr_society_district_code,'id NOT In'=>$pacs_is_member_affiliated,'sector_of_operation IN '=>[1,20,22]])->order(['operational_district_code'=>'ASC'])->toArray();
        }
        

        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$StateCooperativeBank->sector_of_operation,'status'=>1])->order(['orderseq'=>'ASC'])->toArray();

        

        $this->set(compact('StateCooperativeBank','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','pActivities','years','register_authorities','sectors','designations','water_body_type','federationlevel','sectorUrbans','area_of_operation_id_urban','area_of_operation_id_rural','all_districts','dccb_banks','society','society1','primary_activities','dcb_bank_list'));
    }

    /**
     * edit method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function editMember_old($id)
    {
        $this->loadModel('StateCooperativeBanks');
        $this->loadModel('DcbScbMembers');

        $bankExist = $this->StateCooperativeBanks->find()->where(['created_by'=>$this->request->session()->read('Auth.User.created_by')])->first();
        
        if($this->request->session()->read('Auth.User.role_id') != 25)
        {
            $this->Flash->error(__('You are not Authorize to add SCB Member'));
            return $this->redirect(['action' => 'memberList']);
        }

        if(empty($bankExist))
        {
            $this->Flash->error(__('SCB Detail is not entered by SCB Nodal'));
            return $this->redirect(['action' => 'memberList']);
        }
        
        $DcbScbMember = $this->DcbScbMembers->get($id, [
            'contain' => ['DcbImplementingSchemes'=>['sort'=>['id'=>'ASC']] ]
        ]);

          
        
        if($this->request->is('put')){
            
            //if not scb deo
            // if($this->request->session()->read('Auth.User.role_id') != 23)
            // {
            //     return $this->redirect(['action' => 'index']);$this->Flash->error(__('You are not Authorize to edit Member'));
            // }

            $district_code = $this->request->session()->read('Auth.User.district_code');

            $data=$this->request->getData();

            
            

            if(!empty($data['under_computerised_scheme']))
            {   
                
                $this->loadModel('CooperativeRegistrations');

                $com_zero = [];
                $com_zero = array_diff($data['society_id'],$data['under_computerised_scheme']);

                $query = $this->CooperativeRegistrations->query();
                $query->update()
                    ->set(['under_computerised_scheme' => 1])
                    ->where(['id IN' => $data['under_computerised_scheme']])
                    ->execute();

                $query1 = $this->CooperativeRegistrations->query();
                $query1->update()
                    ->set(['under_computerised_scheme' => 0])
                    ->where(['id IN' => $com_zero])
                    ->execute();

            }

            

            unset($data['under_computerised_scheme']);
            
            $data['state_cooperative_bank_id'] = $bankExist->id;
            $data['status'] = 1;
            $data['is_delete'] = 0;
            $data['created'] = date('Y-m-d H:i:s');
            $data['updated'] = date('Y-m-d H:i:s');
            $data['created_by'] = $this->request->session()->read('Auth.User.id');

            $data['society_id'] = implode(',',$data['society_id']);

            //add his district based on user if urban
            if($data['location_urban_rural'] == 1)
            {
                $data['district_code'] = $this->request->session()->read('Auth.User.district_code');
            }
            
            // echo '<pre>';
            // print_r($data);die;
            $DcbScbMember = $this->DcbScbMembers->patchEntity($DcbScbMember, $data, ['associated' => ['DcbImplementingSchemes']]);
            
            if($this->DcbScbMembers->save($DcbScbMember)) {
                
                $this->Flash->success(__('Member has been updated.'));
                //return $this->redirect(['action' => 'add-member',$result['id']]);
                return $this->redirect(['action' => 'memberList']);
            
            }
            $this->Flash->error(__('The Member Could not be saved. Please, Fill the form and try again.'));
        }

        $years = [];
        $l_year = date('Y')-23;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
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

        $all_states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
            $this->set('all_states',$all_states);    

        ####### degination dropdown#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        ###################end ############


        // Start States for dropdown
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code,'district_code'=>$this->request->session()->read('Auth.User.district_code')])->order(['name'=>'ASC']);
            $this->set('districts',$districts);

            $all_districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']);
            $this->set('all_districts',$all_districts);
        // End States for dropdown
        
        

        // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$DcbScbMember->district_code])->order(['name'=>'ASC']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown


        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$DcbScbMember->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown



        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$DcbScbMember->gp_code])->order(['village_name'=>'ASC']); 
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
            $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

            
        // End PrimaryActivities for dropdown
            
        // Start SecondaryActivities for dropdown
            $this->loadModel('SecondaryActivities');
            $SecondaryActivities=$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'sector_of_operation'=>$DcbScbMember->sector_of_operation_type])->order(['orderseq'=>'ASC']);

        // End SecondaryActivities for dropdown
        
        // Start PresentFunctionalStatus for dropdown
            $this->loadModel('PresentFunctionalStatus');
            $PresentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        // End PresentFunctionalStatus for dropdown

        
        // Start urban_local_bodies type for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$DcbScbMember->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown


        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$DcbScbMember->urban_local_body_category_code,'state_code'=>$DcbScbMember->state_code])->order(['local_body_name'=>'ASC']);
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown


        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$DcbScbMember->urban_local_body_code])->order(['ward_name'=>'ASC']);

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


              //  Start register_authorty dropdown registration_authorities
            $this->loadModel('RegistrationAuthorities');
            $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['authority_name'=>'ASC']);
            $this->set('register_authorities',$register_authorities);
        // End registration_authorities for dropdown

        $this->loadModel('CooperativeRegistrations');

            $society_ids = $this->CooperativeRegistrations->find('list',['keyField'=>'id','valueField'=>'cooperative_society_name'])->where(['status'=>1,'operational_district_code'=>$DcbScbMember->society_district_code,'sector_of_operation'=>$DcbScbMember->primary_activity])->order(['cooperative_society_name'=>'ASC']);

            $arr_select_society_ids = explode(',',$DcbScbMember->society_id);
            $selected_society_ids = $this->CooperativeRegistrations->find('all')->where(['status'=>1,'id IN'=>$arr_select_society_ids])->order(['cooperative_society_name'=>'ASC'])->toArray();

            $arr_society = [];
            $arr_society = $this->CooperativeRegistrations->find('all')->where(['status'=>1,'operational_district_code'=>$DcbScbMember->society_district_code,'sector_of_operation'=>$DcbScbMember->primary_activity])->order(['cooperative_society_name'=>'ASC'])->toArray();

            $society_option='<option value="">Select</option>';

            
            $option_html='';
            if(count($arr_society)>0){
                foreach($arr_society as $key=>$value){
                    $selected = '';
                    
                    if(in_array($value['id'],$arr_select_society_ids))
                    {
                        $selected = ' selected ';
                    }


                    $option_html.='<option cs="'.$value['under_computerised_scheme'].'"  s_name="'.$value['cooperative_society_name'].'" reg_no="'.$value['registration_number'].'"  reg_date="'.$value['date_registration'].'" '.$selected.' value="'.$value['id'].'">'.$value['cooperative_society_name'].'</option>';
                }
            }

            

        $this->set(compact('StateCooperativeBank','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','years','state_code','DcbScbMember','society_ids','selected_society_ids','option_html'));
        
        
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
    
    


    

    
    
    public function getPrimaryActivity(){
        $this->viewBuilder()->setLayout('ajax');
            if($this->request->is('ajax')){
                
                $sector_operation = $this->request->data('sector_operation');    

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
                if($dccb=='1')
                {
                  $SecondaryActivities = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','state_code'=>$state_code,'dccb_flag'=>$dccb, 'primary_activity_flag'=>$primary_activity_id])->order(['name'=>'ASC'])->toArray();
                }else if($dccb=='2')
                {
                     $SecondaryActivities = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','state_code'=>$state_code, 'dccb_flag'=>$dccb, 'primary_activity_flag'=>$primary_activity_id])->order(['name'=>'ASC'])->toArray();
                 }
                 else if($dccb !='2' || $dccb !='1' || $dccb !='3')
                 {
                   
                    $SecondaryActivities = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','state_code'=>$state_code,'primary_activity_flag'=>$primary_activity_id,'entity_type'=>$entity_type])->order(['name'=>'ASC'])->toArray();
                 }

              if($dccb =='3')
              {
                 $option_html='<option value="">Select</option>';

             }else{
              
                $option_html='<option value="">Select</option>';
                
                if(count($SecondaryActivities)==0){
                    $option_html.='<option value="0">To Be Provided</option>';
                }
                if(count($SecondaryActivities)>0){
                    foreach($SecondaryActivities as $key=>$value){
                        $option_html.='<option value="'.$key.'">'.$value.'</option>';
                    }
                }
            }

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
                $SecondaryActivities = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1,'primary_activity_id'=>$primary_activity_id])->order(['id'=>'ASC'])->toArray();
                          
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

    public function dcbSchemeAddRow()
    {
        $this->viewBuilder()->setLayout('ajax');
        $hr3 = $this->request->data('hr3'); 
        //Start Major Activities for dropdown
        $this->loadMOdel('MajorActivities');
        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
        $this->set('major_activities',$major_activities);
        ####### degination dropdown start#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        $this->set('shr2',$hr3);
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

    

    public function sectorAddRow()
    {
        $this->viewBuilder()->setLayout('ajax');
        $hr2 = $this->request->data('hr2'); 
        //Start Major Activities for dropdown
        $this->loadMOdel('MajorActivities');
        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
        $this->set('major_activities',$major_activities);
        ####### degination dropdown start#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        $this->set('mhr2',$hr2);
    }

    public function scardbAddRow()
    {
        $this->viewBuilder()->setLayout('ajax');
        $hr3 = $this->request->data('hr3'); 
		//Start Major Activities for dropdown
		$this->loadMOdel('MajorActivities');
		$major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
		$this->set('major_activities',$major_activities);
		####### degination dropdown start#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        $this->set('mhr3',$hr3);
    }


    public function schemeAddRow()
    {
        $this->viewBuilder()->setLayout('ajax');
        $hr3 = $this->request->data('hr3'); 
        //Start Major Activities for dropdown
        $this->loadMOdel('MajorActivities');
        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
        $this->set('major_activities',$major_activities);
        ####### degination dropdown start#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        $this->set('shr2',$hr3);
    }

    public function memberList()
    {
        $this->loadModel('StateCooperativeBanks');
        $this->loadModel('DcbScbMembers');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('Users');

        
        $bankExist = $this->StateCooperativeBanks->find()->where(['created_by'=>$this->request->session()->read('Auth.User.id'),'status'=>1,'is_delete'=>0])->first();


        $add_flag = 0;
        if(empty($bankExist))
        {
            $add_flag = 1;
        }

        $this->set('add_flag',$add_flag);

        if (!empty($this->request->data['name'])) {
            $name = trim($this->request->data['name']);
            $this->set('name', $name);
            $search_condition[] = "scb_name like '%" . $name . "%'";
        }
        
        $state_code= $this->request->session()->read('Auth.User.state_code');
        if (isset($this->request->data['state_code']) && $this->request->data['state_code'] !='') {
            $s_state = trim($this->request->data['state_code']);
            $this->set('s_state', $s_state);
            $search_condition[] = "state_code = '" . $s_state . "'";
        }

        if (isset($this->request->data['district_code']) && $this->request->data['district_code'] !='') {
            $s_district = trim($this->request->data['district_code']);
            $this->set('s_district', $s_district);
            $search_condition[] = "district_code = '" . $s_district . "'";
        }

        $arr_role =[24,25];
        if(!in_array($this->request->session()->read('Auth.User.role_id'),$arr_role))
        {
            $valid_bank_ids = $this->StateCooperativeBanks->find('list',['keyField'=>'id','valueField'=>'scb_name'])->where(['status'=>1,'is_delete'=>0])->toArray();
            $valid_bank_ids = array_keys($valid_bank_ids);
            
            $registrationQuery = $this->DcbScbMembers->find('all', [
                'order' => ['id' => 'DESC'],
                'conditions' => [$search_condition]
            ])->where(['is_delete'=>0,'status'=>1,'state_cooperative_bank_id IN'=>$valid_bank_ids]);
        }

        //nodal
        if($this->request->session()->read('Auth.User.role_id') == 24)
        {
            
            $registrationQuery = $this->StateCooperativeBanks->find('all', [
                'order' => ['id' => 'DESC'],
                'conditions' => [$search_condition]
            ])->where(['is_delete'=>0,'status'=>1]);

            $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'created_by'=>$this->request->session()->read('Auth.User.id')])->toArray();
        
            $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);
            if(!empty($nodal_data_entry_ids)){
                $registrationQuery = $registrationQuery->where(['created_by IN'=>$nodal_data_entry_ids]);
            }else {
                $registrationQuery = $registrationQuery->where(['created_by IN'=>0]);
            }
        }

        //intern
        if($this->request->session()->read('Auth.User.role_id') == 25)
        {
            $registrationQuery = $this->StateCooperativeBanks->find('all', [
                'order' => ['id' => 'DESC'],
                'conditions' => [$search_condition]
            ])->where(['is_delete'=>0,'status'=>1,'created_by'=>$this->request->session()->read('Auth.User.id')]);
        }
        

        $this->paginate = ['limit' => 20];
        $DcbScbMembers = $this->paginate($registrationQuery);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));
        

        $districts = [];
        
       
        
        
            $arr_admin = [1,2,14,16,17,18,19,20,22,23];

            if(in_array($this->request->session()->read('Auth.User.role_id'),$arr_admin))
            {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
            } else {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
            }


            if(in_array($this->request->session()->read('Auth.User.role_id'),$arr_admin))
            {
                if(!empty($s_state))
                {
                    $state_code = $s_state;
                }

                $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$state_code])->order(['name'=>'ASC'])->toArray();
            } else {
                $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$state_code])->order(['name'=>'ASC'])->toArray();
            }
            $all_districts = [];
            $all_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

        $arr_bank_list = $this->StateCooperativeBanks->find('list',['keyField'=>'id','valueField'=>'scb_name'])->where(['status'=>1,'is_delete'=>0])->toArray();
        $this->loadModel('DistrictCentralCooperativeBank');

        $query =$this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name','conditions'=>['entity_type'=>'DCCB'],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $cooperateBankOption = $query->toArray();
        $this->set('bOption',$cooperateBankOption);

        $this->set(compact('DcbScbMembers','states','districts','bank_name','arr_bank_list','all_districts'));
    }


    public function viewMember($id)
    {
        $this->loadModel('StateCooperativeBanks');
        $this->loadModel('Districts');
        $this->loadMOdel('Blocks');
        $this->loadMOdel('DistrictsBlocksGpVillages');      
        $this->loadMOdel('ScbImplementingSchemes');
        $this->loadMOdel('DistrictCentralCooperativeBank');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('PrimaryActivities');

        $StateCooperativeBank = $this->StateCooperativeBanks->get($id, [
            'contain' => ['ScbContactDetails'=>['sort'=>['id'=>'ASC']],'ScbImplementingSchemes'=>['sort'=>['id'=>'ASC']],'DcbScbOtherMembers'=>['sort'=>['id'=>'ASC']] ]
        ]);

        $query =$this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name','conditions'=>['entity_type'=>'DCCB'],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $cooperateBankOption = $query->toArray();
        $this->set('bOption',$cooperateBankOption);

        $this->loadModel('Designations');

        $query1 =$this->Designations->find('list',['keyField'=>'id','valueField'=>'name','conditions'=>['status'=>1],'order' => ['name' => 'ASC']]);
        $query1->hydrate(false);
        $cooperateBankDesignation = $query1->toArray();
        $this->set('dOption',$cooperateBankDesignation);

        $query2 =$this->Districts->find('list',['keyField'=>'id','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query2->hydrate(false);
        $cooperateBankDistrict = $query2->toArray();
        $this->set('distOption',$cooperateBankDistrict);


        $sectors = [];
        $sectorUrbans = [];
        $area_of_operation_id_urban = '';
        $area_of_operation_id_rural = '';
        
        // Start States for dropdown
          //  $this->loadModel('States');
             $state_code= $this->request->session()->read('Auth.User.state_code');

             
            $this->loadModel('States');
            


            

            $all_states = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
            
            $this->set('all_states',$all_states);
        // End States for dropdown


        // Start States for dropdown
            $this->loadModel('Districts');
            if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
            {
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$StateCooperativeBank->state_code])->order(['name'=>'ASC']);
            } else {
                $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']); 
            }
               
            //$districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$CooperativeRegistration->state_code])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        // End States for dropdown
        

        // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            //$blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$CooperativeRegistration->district_code])->order(['name'=>'ASC']);
            $blocks=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$StateCooperativeBank->district_code])->group(['block_code']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown


        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$StateCooperativeBank->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown


        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$StateCooperativeBank->gp_code])->order(['village_name'=>'ASC']); 
        $this->set('villages',$villages);  
        // end villages for dropdown
        



        // Start CooperativeSocietyTypes for dropdown
            $this->loadModel('CooperativeSocietyTypes');
            $CooperativeSocietyTypes=$this->CooperativeSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC']);
        // End CooperativeSocietyTypes for dropdown
    
       
        

        // Start PrimaryActivities for dropdown
            $this->loadModel('PrimaryActivities');

            $pActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$StateCooperativeBank->sector_of_operation_type,'status'=>1])->order(['orderseq'=>'ASC']);
            $this->set('pActivities',$pActivities); 
            
            $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$StateCooperativeBank->sector_of_operation_type,'status'=>1])->order(['orderseq'=>'ASC']);

        // End PrimaryActivities for dropdown
            
        // Start SecondaryActivities for dropdown
            $this->loadModel('SecondaryActivities');
            $SecondaryActivities=$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'sector_of_operation'=>$StateCooperativeBank->sector_of_operation_type])->order(['orderseq'=>'ASC']);

        // End SecondaryActivities for dropdown
        
        // Start PresentFunctionalStatus for dropdown
            $this->loadModel('PresentFunctionalStatus');
            $PresentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        // End PresentFunctionalStatus for dropdown

        
        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$StateCooperativeBank->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies for dropdown
    

        // Start urban_local_bodies for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$StateCooperativeBank->urban_local_body_category_code,'state_code'=>$StateCooperativeBank->state_code])->order(['local_body_name'=>'ASC']);

        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown

        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$StateCooperativeBank->urban_local_body_code])->order(['ward_name'=>'ASC']);

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown

        $this->loadModel('SectorOperations');

       $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sector_operations',$sector_operations); 

       $this->loadModel('RegistrationAuthorities');

       $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['authority_name'=>'ASC']);

       $this->loadModel('Designations');
       $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();

        // Start water_body_types for dropdown
        $this->loadModel('WaterBodyTypes');            
           $water_body_type = $this->WaterBodyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();

       $this->loadMOdel('LevelOfAffiliatedUnion'); 

       $locationOfHeadquarter = [
        '1' => 'Urban',
        '2' => 'Rural',
    ];

    $states_find=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
    $districts_find=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
    $blocks_find=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
    $panchayats_find = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  
    $villages_find = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1])->order(['village_name'=>'ASC'])->toArray();


              // $federationlevel = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1,'primary_activity_id'=>$CooperativeRegistration->sector_of_operation])->order(['id'=>'ASC'])->toArray();
    $society = [];
    $society1 = [];

        $arr_society_district_code = explode(',',$StateCooperativeBank->society_district_code); 
        $pacs_is_member_affiliated = explode(',',$StateCooperativeBank->pacs_is_member_affiliated); 

          if(!empty($arr_society_district_code))
        {
            $society =$this->CooperativeRegistrations->find('all')->where(['status'=>1,'operational_district_code IN'=>$arr_society_district_code,'id In'=>$pacs_is_member_affiliated,'sector_of_operation IN '=>[1,20,22]])->order(['operational_district_code'=>'ASC'])->toArray();
           
        }

        if(!empty($arr_society_district_code))
        {
            $society1=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'operational_district_code IN'=>$arr_society_district_code,'id NOT In'=>$pacs_is_member_affiliated,'sector_of_operation IN '=>[1,20,22]])->order(['operational_district_code'=>'ASC'])->toArray();
        }
        

        $all_districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

        $query =$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name','conditions'=>['status'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $primaryOption = $query->toArray();
        $this->set('pOption',$primaryOption);


        $this->set(compact('villages_find','blocks_find','panchayats_find','districts_find','states_find','StateCooperativeBank','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','pActivities','years','register_authorities','sectors','designations','water_body_type','sectorUrbans','area_of_operation_id_urban','area_of_operation_id_rural','locationOfHeadquarter','designations','society','society1','all_districts'));
    }

    public function viewMember_old($id)
    {
        $this->loadModel('DcbScbMembers');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('PrimaryActivities');
        $this->loadModel('CooperativeRegistrations');
        


        $DcbScbMember = $this->DcbScbMembers->get($id);

        $districts = [];
        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->session()->read('Auth.User.state_code')])->order(['name'=>'ASC'])->toArray();

        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('states',$stateOption);

        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

        $locationOfHeadquarter = [
            '1' => 'Urban',
            '2' => 'Rural',
        ];

        $districtName = '';

        if(!empty($DcbScbMember->district_code))
        {
            $this->loadModel('Districts');
            
            $district = $this->Districts->find('all')->where(['district_code'=>$DcbScbMember->district_code,'flag'=>1])->first();
            $districtName = $district['name'];
        }

        $blockName = '';

        if(!empty($DcbScbMember->block_code))
        {
            $this->loadModel('Blocks');
            
            $block = $this->Blocks->find('all')->where(['block_code'=>$DcbScbMember->block_code,'flag'=>1])->first();
            $blockName = $block['name'];
        }

        $panchayatName = '';
        $this->loadMOdel('DistrictsBlocksGpVillages');

        if(!empty($DcbScbMember->gp_code))
        {   
            $gpc = $this->DistrictsBlocksGpVillages->find('all')->where(['status'=>1,'gram_panchayat_code'=>$DcbScbMember->gp_code])->first();  

            $panchayatName = $gpc['gram_panchayat_name'];
        }



        $villageName = '';

        if(!empty($DcbScbMember->village_code))
        {
            $dbgv = $this->DistrictsBlocksGpVillages->find('all')->where(['status'=>1,'state_code'=>$DcbScbMember->state_code,'village_code'=>$DcbScbMember->village_code])->first(); 

            $villageName = $dbgv['village_name'];
        }

        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$DcbScbMember->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies for dropdown

        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$DcbScbMember->urban_local_body_category_code,'state_code'=>$DcbScbMember->state_code])->order(['local_body_name'=>'ASC'])->toArray();
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown
        

        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$DcbScbMember->urban_local_body_code])->order(['ward_name'=>'ASC'])->toArray();

        $this->set('localbodieswards',$localbodieswards);        

        $society_ids = explode(',',$DcbScbMember->society_id);
        $societies = $this->CooperativeRegistrations->find('all')->where(['id in'=>$society_ids])->order(['cooperative_society_name'=>'ASC'])->toArray();

        $all_districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$DcbScbMember->state_code])->order(['name'=>'ASC'])->toArray();

        $this->set(compact('DcbScbMember','states','districts','primary_activities','locationOfHeadquarter','districtName','blockName','panchayatName','villageName','localbody_types','localbodies','localbodieswards','societies','all_districts','society_ids'));
    }

    public function getSociety()
    {
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $district_code=$this->request->data('district_code');    
            $primary_activity=$this->request->data('primary_activity');    
            $this->loadModel('CooperativeRegistrations');

            $society=$this->CooperativeRegistrations->find('all')->where(['status'=>1,'operational_district_code'=>$district_code,'sector_of_operation'=>$primary_activity])->order(['cooperative_society_name'=>'ASC'])->toArray();
            
            $option_html='';
            if(count($society)>0){
                foreach($society as $key=>$value){

                    $option_html.='<option cs="'.$value['under_computerised_scheme'].'" s_name="'.$value['cooperative_society_name'].'" reg_no="'.$value['registration_number'].'"  reg_date="'.$value['date_registration'].'" value="'.$value['id'].'">'.$value['cooperative_society_name'].'</option>';
                }
            }
            echo $option_html;
        }
        exit;

    }

    public function getAllSociety()
    {
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $district_code=$this->request->data('district_code');
            //$primary_activity=$this->request->data('primary_activity');    
            $this->loadModel('CooperativeRegistrations');
            $this->loadModel('PrimaryActivities');
            $this->loadModel('Districts');

            $district_name = '';
            //$district_name = $this->Districts->find('all')->where(['district_code' => $district_code])->first()->name;

            $all_districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')])->order(['name'=>'ASC'])->toArray();

            $society_affiliated =$this->CooperativeRegistrations->find('all')->where(['is_affiliated_union_federation'=>1,'status'=>1,'operational_district_code IN'=>$district_code,'sector_of_operation IN '=>[1,20,22]])->order(['operational_district_code'=>'ASC'])->toArray();

            $option_html='';
            if(count($society_affiliated)>0){
                foreach($society_affiliated as $key=>$value){

                    //$option_html.='<option cs="'.$value['under_computerised_scheme'].'" s_name="'.$value['cooperative_society_name'].'" reg_no="'.$value['registration_number'].'"  reg_date="'.$value['date_registration'].'" value="'.$value['id'].'">'.$value['cooperative_society_name'].'</option>';

                    $option_html.= '<tr><td class="inc">'.($key+1).'</td><td>'.$value['cooperative_society_name'].'</td><td>'.$all_districts[$value['operational_district_code']].'</td><td>'.$value['registration_number'].'</td><td>'.$value['date_registration'].'</td><td style="min-width: 140px;"><input class="pacs_is_member" type="checkbox" name="pacs_is_member_affiliated[]" checked  value="'.$value['id'].'"></td></tr>';
                }
            }
            echo $option_html;
        }
        exit;

    }

    public function getAllSociety1()
    {
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $district_code=$this->request->data('district_code');
            //$primary_activity=$this->request->data('primary_activity');    
            $this->loadModel('CooperativeRegistrations');
            $this->loadModel('PrimaryActivities');
            $this->loadModel('Districts');

            $district_name = '';
            //$district_name = $this->Districts->find('all')->where(['district_code' => $district_code])->first()->name;

            $society_not_affiliated =$this->CooperativeRegistrations->find('all')->where(['is_affiliated_union_federation'=>0,'status'=>1,'operational_district_code IN'=>$district_code,'sector_of_operation IN '=>[1,20,22]])->order(['operational_district_code'=>'ASC'])->toArray();

            $all_districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')])->order(['name'=>'ASC'])->toArray();
            
            $option_html='';
            if(count($society_not_affiliated)>0){
                foreach($society_not_affiliated as $key=>$value){

                    //$option_html.='<option cs="'.$value['under_computerised_scheme'].'" s_name="'.$value['cooperative_society_name'].'" reg_no="'.$value['registration_number'].'"  reg_date="'.$value['date_registration'].'" value="'.$value['id'].'">'.$value['cooperative_society_name'].'</option>';

                    $option_html.= '<tr><td class="inc">'.($key+1).'</td><td>'.$value['cooperative_society_name'].'</td><td>'.$all_districts[$value['operational_district_code']].'</td><td>'.$value['registration_number'].'</td><td>'.$value['date_registration'].'</td><td style="min-width: 140px;"><input class="pacs_is_member" type="checkbox" name="pacs_is_member_not_affiliated[]"  value="'.$value['id'].'"></td></tr>';
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
       
        $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')])->order(['name'=>'ASC']);

        $this->set(compact('ohr2','arr_class_member','arr_type_member','PrimaryActivities','districts'));
    }


    
    
}