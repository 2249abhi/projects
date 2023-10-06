<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;


class DairyFederationsController extends AppController
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

    
    public function add()
    {
     
        $this->loadMOdel('CooperativeFederations');
        $CooperativeFederation = $this->CooperativeFederations->newEntity();
        $years = [];
        $l_year = date('Y')-122;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
        }
        
       
      
        if($this->request->is('post')){

            //for data format
            $data=$this->request->getData();
            $date_string=date_create($data['registration_date']);
            $date = date_format($date_string,"Y/m/d");
            $data['registration_date'] = $date;
         
            $login_user_id = $this->request->session()->read('Auth.User.id');
            $role_id= $this->request->session()->read('Auth.User.role_id');
          
           $data['role_id'] = $role_id;
           $data['updated_by'] = $login_user_id;
            $data['status'] = 1;
            $data['created'] = date('Y-m-d H:i:s');
			
            
			$CooperativeFederation = $this->CooperativeFederations->patchEntity($CooperativeFederation, $data);
         
            if($this->CooperativeFederations->save($CooperativeFederation)) {
                
            // Dairy Federations data
            
             if($data['federation_doing_business'] == 1)
             {
                 $this->loadModel('DairyFederations');

                 $data['cooperative_federations_id'] = $CooperativeFederation->id;
            
                 $DairyFederation = $this->DairyFederations->newEntity();
                 
                 $DairyFederation = $this->DairyFederations->patchEntity($DairyFederation, $data);
               
                 $this->DairyFederations->save($DairyFederation);
                 
             }
            
            $this->Flash->success(__('The Dairy Cooperative Federation data has been saved.'));
            return $this->redirect(['action' => 'add-member',$CooperativeFederation->id]);
            
        }
            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
        }


        
            $state_code= $this->request->session()->read('Auth.User.state_code');
           
            
            // role_id=58 means "State Dairy Federation Nodal" is "login user"
            $this->loadMOdel('DistrictCentralCooperativeBank');
            $cooperative_society_name = $this->DistrictCentralCooperativeBank->find()->select(['name'])->where(['state_code'=>$state_code,'entity_type'=>"State Dairy Federation"])->first();
           
           
            $this->loadModel('States');
            if(in_array($this->request->session()->read('Auth.User.role_id'),[1,58]))
            {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
            }
            

            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC'])->toArray();
            
        
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1])->toArray();

            $this->loadModel('PresentFunctionalStatus');
            $PresentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
      
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  
    

        $this->set(compact('cooperative_society_name','CooperativeFederation','states','PresentFunctionalStatus','years','state_code','districts','localbody_types','designations'));

    }



    public function addMember($cooperative_federations_id)
    {
        $this->loadMOdel('DairyMembers');
        $DairyMember = $this->DairyMembers->newEntity();
        
        if($this->request->is('post')){
            
            $data=$this->request->getData();

            
            //for date formate
            $data['cooperative_federations_id'] = $cooperative_federations_id; 
            $data['status'] = 1;
            $data['created'] = date('Y-m-d H:i:s');


			$DairyMember = $this->DairyMembers->patchEntity($DairyMember, $data);
            
            if($this->DairyMembers->save($DairyMember)) {
           
           
            $this->Flash->success(__('The Dairy Cooperative Federation data has been saved.'));
            return $this->redirect(['action' => 'members-list',$cooperative_federations_id]);
        }
            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
        }

        // Start States for dropdown
            $state_code= $this->request->session()->read('Auth.User.state_code');

            $this->loadModel('States');
            if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2,58]))
            {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
            }
            $this->set('states',$states);

            // Start States for dropdown
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC'])->toArray();
            $this->set('districts',$districts);
            // End States for dropdown

       
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1])->toArray();
       $this->set('designations',$designations);
       


       
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
     


         $class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships);  
        

            //Start type_of_memberships for dropdown
        	$type_of_memberships=[1=>'Primary Cooperative Societies',2=>'District Union/Federations',3=>'State Federations',4=>'Multi-State Cooperative Society'];
        	$this->set('type_of_memberships',$type_of_memberships);

            //Start Major Activities for dropdown
	        $this->loadMOdel('MajorActivities');
	        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
	        $this->set('major_activities',$major_activities);


        $this->set(compact('CooperativeFederation','states','years','state_code','FisheryMember','cooperative_federations_id'));

    }

    public function list(){

        if (!empty($this->request->query['state'])) {
            $state = trim($this->request->query['state']);
            $this->set('state', $state);
            $search_condition[] = "state_code like '%" . $state . "%'";
        }

        if (!empty($this->request->query['state_cooperative_federation_name'])) {
            $state_cooperative_federation_name = trim($this->request->query['state_cooperative_federation_name']);
            $this->set('state_cooperative_federation_name', $state_cooperative_federation_name);
            $search_condition[] = "cooperative_society_name like '%" . $state_cooperative_federation_name . "%'";
        }

        if (!empty($this->request->query['registration_number'])) {
            $registration_number = trim($this->request->query['registration_number']);
            $this->set('registration_number', $registration_number);
            $search_condition[] = "registration_number like '%" . $registration_number . "%'";
        }

        if (!empty($this->request->query['registration_date'])) {
            $registration_date = trim($this->request->query['registration_date']);

            $date_string=date_create($registration_date);
            $date = date_format($date_string,"Y-m-d");

            $this->set('date', $date);
            $search_condition[] = "registration_date like '%" . $date . "%'";
        }

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        }
        //  else {
        //     $searchString = '';
        // }

        $state_code= $this->request->session()->read('Auth.User.state_code');
        $role_id = $this->request->session()->read('Auth.User.role_id');
        $this->loadMOdel('CooperativeFederations');
        //down: code to get cooperative_federations_id before form in list template
        $cooperative_federations_object = $this->CooperativeFederations->find('all')->where(['state_code'=>$state_code,'role_id'=>$role_id])->first();
        

        
        $search_query = $this->CooperativeFederations->find('all')->where([$searchString,'role_id'=>$role_id]);
        $this->paginate = ['limit' => 20];
        $CooperativeFederations = $this->paginate($search_query);



        //Start Class of Membership for dropdown
        $class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        
    

        //Start type_of_memberships for dropdown
        $type_of_memberships=[1=>'Primary Cooperative Societies',2=>'District Union/Federations',3=>'State Federations',4=>'Multi-State Cooperative Society'];
       

        //Start Major Activities for dropdown
        $this->loadMOdel('MajorActivities');
        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 

        //Start location_of_head_quarter for dropdown
        $location_of_head_quarter=[1=>'Urban',2=>'Rural']; 

        $this->loadMOdel('States');
        $states_find=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
       
        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$CooperativeFederations->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  

        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$CooperativeFederations->urban_local_body_type_code,'state_code'=>$CooperativeFederations->state_code])->order(['local_body_name'=>'ASC'])->toArray();

        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$CooperativeFederations->urban_local_body_code])->order(['ward_name'=>'ASC'])->toArray();
    
        $this->loadModel('Designations');
       $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();

        $this->set(compact('cooperative_federations_object','CooperativeFederations','class_of_memberships','type_of_memberships','major_activities','states_find','location_of_head_quarter','localbody_types','localbodies','localbodieswards','designations','FisheryFederations'));
    }

    public function membersList($cooperative_federations_id){


        // if (!empty($this->request->query['state'])) {
        //     $state = trim($this->request->query['state']);
        //     $this->set('state', $state);
        //     $search_condition[] = "state_code like '%" . $state . "%'";
        // }

        if (!empty($this->request->query['member_name'])) {
            $member_name = trim($this->request->query['member_name']);
            $this->set('member_name', $member_name);
            $search_condition[] = "name_of_member like '%" . $member_name . "%'";
        }

        if (!empty($this->request->query['class_of_membership'])) {
            $class_of_membership = trim($this->request->query['class_of_membership']);
            $this->set('class_of_membership', $class_of_membership);
            $search_condition[] = "class_of_membership_id like '%" . $class_of_membership . "%'";
        }

        if (!empty($this->request->query['type_of_membership'])) {
            $type_of_membership = trim($this->request->query['type_of_membership']);
            $this->set('type_of_membership', $type_of_membership);
            $search_condition[] = "type_of_membership_id like '%" . $type_of_membership . "%'";
        }

       
        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        }
        //  else {
        //     $searchString = '';
        // }



        $this->loadMOdel('CooperativeFederations');
        $CooperativeFederation=$this->CooperativeFederations->get($cooperative_federations_id);
        

        $this->loadMOdel('DairyMembers');
        $DairyMembers_query = $this->DairyMembers->find('all')->where(['cooperative_federations_id'=>$cooperative_federations_id,$searchString]);
        $this->paginate = ['limit' => 20];
        $DairyMembers = $this->paginate($DairyMembers_query);

      


        //Start Class of Membership for dropdown
        $class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        
        //Start type_of_memberships for dropdown
        $type_of_memberships=[1=>'Primary Cooperative Societies',2=>'District Union/Federations',3=>'State Federations',4=>'Multi-State Cooperative Society'];

        $this->loadMOdel('States');
        $state_code= $this->request->session()->read('Auth.User.state_code');
        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toarray();
        $states_find=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();

        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1])->toArray();
       

        $this->set(compact('cooperative_federations_id','CooperativeFederation','DairyMembers','cooperative_federations_id','type_of_memberships','class_of_memberships','states','states_find','designations'));

    }

    public function view($cooperative_federations_id){
       
        $this->loadMOdel('CooperativeFederations');
        $CooperativeFederation=$this->CooperativeFederations->get($cooperative_federations_id);

        $date_string=date_create($CooperativeFederation->registration_date);
            $date = date_format($date_string,"d/m/Y");
        
        $this->loadMOdel('DairyFederations');
        $DairyFederation=$this->DairyFederations->find('all')->where(['cooperative_federations_id'=>$cooperative_federations_id])->order(['id'=>'ASC'])->first(); 
        // echo "<pre>";
        // print_r($DairyFederation);
        // die;

       $yesNo = ['1'=>'Yes','0'=>'No'];

       
       $this->loadModel('PresentFunctionalStatus');
       $PresentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

       
        $categoryAudit = ['1'=>'A: Score more than 70','2'=>'B: Score between 50 to 70','3'=>'C: Score between 35 and 50', '4'=> 'D: Score less than 35','5'=>'Audit has not given any Score'];

        
        $this->loadMOdel('MajorActivities');
        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 

        $location_of_head_quarter=[1=>'Urban',2=>'Rural']; 

        $state_code= $this->request->session()->read('Auth.User.state_code');
        $this->loadModel('States');
            if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2,58]))
            {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
            }
        
        $this->loadModel('Districts');
        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC'])->toArray();
       
           
        // $this->loadMOdel('Blocks');
        // $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$CooperativeFederation->district_code])->order(['name'=>'ASC'])->toArray();
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $blocks=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$CooperativeFederation->district_code])->group(['block_code'])->toArray();
      
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$CooperativeFederation->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  
       
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$CooperativeFederation->gram_panchayat_code])->order(['village_name'=>'ASC'])->toArray(); 
       
        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$CooperativeFederation->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  

        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$CooperativeFederation->urban_local_body_type_code,'state_code'=>$CooperativeFederation->state_code])->order(['local_body_name'=>'ASC'])->toArray();

        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$CooperativeFederation->urban_local_body_code])->order(['ward_name'=>'ASC'])->toArray();
    
        $this->loadModel('Designations');
       $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();

        $this->set(compact('date','CooperativeFederation','DairyFederation','major_activities','states','districts','blocks','gps','villages','location_of_head_quarter','localbody_types','localbodies','localbodieswards','designations','yesNo','PresentFunctionalStatus','categoryAudit'));
    }

   
    public function edit($cooperative_federations_id)
    {
        
        $this->loadMOdel('CooperativeFederations');
        $CooperativeFederation=$this->CooperativeFederations->get($cooperative_federations_id);
        
        $this->loadMOdel('DairyFederations');
        $DairyFederation=$this->DairyFederations->find('all')->where(['cooperative_federations_id'=>$cooperative_federations_id])->order(['id'=>'ASC'])->first(); 

      

        
        $years = [];
        $l_year = date('Y')-122;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
        }
        
        
        if($this->request->is(['patch', 'post', 'put'])){

       
            $data=$this->request->getData();
            
            // down: code for date formate
            $date_string=date_create($data['registration_date']);
            $date = date_format($date_string,"Y/m/d");
            $data['registration_date'] = $date;
         
            
          
            $data['status'] = 1;
            $data['created'] = date('Y-m-d H:i:s');

            
			$CooperativeFederation = $this->CooperativeFederations->patchEntity($CooperativeFederation, $data);
            
            if($this->CooperativeFederations->save($CooperativeFederation)) {

            //Saving Dairy data
             if($data['federation_doing_business'] == 1)
             {
                 $this->loadModel('DairyFederations');

                 $data['cooperative_federations_id'] = $CooperativeFederation->id;
            
                 if(empty($DairyFederation)){
                    $DairyFederation = $this->DairyFederations->newEntity();
                 }
                $DairyFederation= $this->DairyFederations->patchEntity($DairyFederation, $data);
                
                
                
                $this->DairyFederations->save($DairyFederation);     
             }
          
            $this->Flash->success(__('The Dairy Cooperative Federation data has been saved.'));
            return $this->redirect(['action' => 'list']);
        }
            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
        }
            
        
            $state_code= $this->request->session()->read('Auth.User.state_code');
            $this->loadModel('States');
            if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2,58]))
            {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
            }
          

         
            $date_string=date_create($CooperativeFederation->registration_date);
            $date = date_format($date_string,"d/m/Y");
           


     
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$CooperativeFederation->state_code])->order(['name'=>'ASC'])->toArray();
          
    
           
     
    
    

            $this->loadMOdel('DistrictsBlocksGpVillages');
            $blocks=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$CooperativeFederation->district_code])->group(['block_code'])->toArray();


                
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$CooperativeFederation->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  
        



   
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$CooperativeFederation->gram_panchayat_code])->order(['village_name'=>'ASC'])->toArray(); 
       
        
      
            $this->loadModel('PresentFunctionalStatus');
            $PresentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        

        
       
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$CooperativeFederation->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  
        


      
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$CooperativeFederation->urban_local_body_type_code,'state_code'=>$CooperativeFederation->state_code])->order(['local_body_name'=>'ASC'])->toArray();
      


      
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$CooperativeFederation->urban_local_body_code])->order(['ward_name'=>'ASC'])->toArray();
      
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1])->toArray();


        $this->set(compact('date','cooperative_federations_id','CooperativeFederation','DairyFederation','years','state_code','states','districts','blocks','gps','villages','localbody_types','PresentFunctionalStatus','localbodies','localbodieswards','designations'));

    
    }

    public function viewMember($cooperative_federations_id,$dairy_members_id){
        // echo $cooperative_federations_id; echo $dairy_members_id;die;
        $this->loadMOdel('DairyMembers');
        $DairyMember=$this->DairyMembers->find('all')->where(['cooperative_federations_id'=>$cooperative_federations_id,'id'=>$dairy_members_id])->first(); 

        $location_of_head_quarter=[1=>'Urban',2=>'Rural']; 

        $state_code= $this->request->session()->read('Auth.User.state_code');
        $this->loadModel('States');
            if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2,58]))
            {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
            }
        
        $this->loadModel('Districts');
        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$DairyMember->district_code])->order(['name'=>'ASC'])->toArray();
        // echo "<pre>";
        // print_r($districts);
        // echo "<pre>";
        // print_r($DairyMember);
        // die;
           
        $this->loadMOdel('Blocks');
        $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'block_code'=>$DairyMember->block_code])->order(['name'=>'ASC'])->toArray();
        // echo "<pre>";
        // print_r($blocks);die;
      
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'gram_panchayat_code'=>$DairyMember->gram_panchayat_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  
       
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'village_code'=>$DairyMember->village_code])->order(['village_name'=>'ASC'])->toArray(); 
        
         
        $this->loadMOdel('UrbanLocalBodies');
        // $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'localbody_type_code'=>$DairyMember->localbody_type_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$DairyMember->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  

 
        $this->loadMOdel('UrbanLocalBodies');
        // $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_code'=>$DairyMember->localbody_code])->order(['local_body_name'=>'ASC'])->toArray();
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$DairyMember->urban_local_body_type_code])->order(['local_body_name'=>'ASC'])->toArray();

       
        $this->loadMOdel('UrbanLocalBodiesWards');
        // $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'ward_code'=>$DairyMember->ward_code])->order(['ward_name'=>'ASC'])->toArray();
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$DairyMember->urban_local_body_code])->order(['ward_name'=>'ASC'])->toArray();



        $class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
       
        $type_of_memberships=[1=>'Primary Cooperative Societies',2=>'District Union/Federations',3=>'State Federations',4=>'Multi-State Cooperative Society'];
     
        $this->loadMOdel('MajorActivities');
        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 

        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1])->toArray();
       
       
        
        $this->set(compact('cooperative_federations_id','DairyMember','location_of_head_quarter','states','state_code','districts','blocks','gps','villages','localbody_types','localbodies','localbodieswards','class_of_memberships','type_of_memberships','major_activities','designations'));
    }

    public function editMember($cooperative_federations_id,$dairy_members_id){

        
        $this->loadMOdel('DairyMembers');
        $DairyMember=$this->DairyMembers->find('all')->where(['cooperative_federations_id'=>$cooperative_federations_id,'id'=>$dairy_members_id])->first(); 

        if($this->request->is(['patch', 'post', 'put'])){

           
                $data=$this->request->getData();
                
                $data['status'] = 1;
                $data['created'] = date('Y-m-d H:i:s');
    
                $DairyMember = $this->DairyMembers->patchEntity($DairyMember, $data);
                
                if($this->DairyMembers->save($DairyMember)) {
              
                $this->Flash->success(__('The Fishery Cooperative Federation Member data has been saved.'));
                return $this->redirect(['action' => 'members-list',$cooperative_federations_id]);
            }
                $this->Flash->error(__('The Fishery Cooperative Federation Member data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
            }



        

        $location_of_head_quarter=[1=>'Urban',2=>'Rural']; 

        $state_code= $this->request->session()->read('Auth.User.state_code');
        $this->loadModel('States');
            if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2,58]))
            {
                $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
            }
        
        $this->loadModel('Districts');
        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC'])->toArray();
       
           
       
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $blocks=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$DairyMember->district_code])->group(['block_code'])->toArray();
      
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$DairyMember->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  
       
        
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$DairyMember->gram_panchayat_code])->order(['village_name'=>'ASC'])->toArray(); 
        
         
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  
  
 
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$DairyMember->urban_local_body_type_code])->order(['local_body_name'=>'ASC'])->toArray();
   

        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$DairyMember->urban_local_body_code])->order(['ward_name'=>'ASC'])->toArray();
        

        $class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
       
        $type_of_memberships=[1=>'Primary Cooperative Societies',2=>'District Union/Federations',3=>'State Federations',4=>'Multi-State Cooperative Society'];
     

        $this->loadMOdel('MajorActivities');
        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 


        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1])->toArray();
       
       
        
        $this->set(compact('cooperative_federations_id','DairyMember','location_of_head_quarter','states','state_code','districts','blocks','gps','villages','localbody_types','localbodies','localbodieswards','class_of_memberships','type_of_memberships','major_activities','designations'));
    }
    
    public function deleteMember($cooperative_federations_id,$dairy_members_id){

        $this->request->allowMethod(['post', 'delete']);
       
       
        $this->loadMOdel('DairyMembers');
        $DairyMember = $this->DairyMembers->get($dairy_members_id);
        
        if($this->DairyMembers->delete($DairyMember)){
           

            $this->Flash->success(__('The Fishery Cooperative Federation Member has been deleted.'));
        }else{
            $this->Flash->error(__('The Fishery Cooperative Federation Member could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'members-list',$cooperative_federations_id]);
    }

    public function delete($cooperative_federations_id){
      
        
        $this->request->allowMethod(['post', 'delete']);
      
        $this->loadMOdel('CooperativeFederations');
        $CooperativeFederation = $this->CooperativeFederations->get($cooperative_federations_id);
        // echo "<pre>";
        // print_r($CooperativeFederation);die;
        if($this->CooperativeFederations->delete($CooperativeFederation)){

            
            $this->loadMOdel('DairyFederations');
            $DairyFederation=$this->DairyFederations->find('all')->where(['cooperative_federations_id'=>$cooperative_federations_id])->first(); 
            if(!empty($DairyFederation)){
            $this->DairyFederations->delete($DairyFederation);
            }
           
            //delete details related to all rows in fishery members table
            $this->loadModel('DairyMembers');
            $this->DairyMembers->deleteAll(['cooperative_federations_id'=>$cooperative_federations_id]);

            $this->Flash->success(__('The Dairy Cooperative Federation Detail has been deleted.'));
        }else{
            $this->Flash->error(__('The Dairy Cooperative Federation Detail could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'list']);
    }
    /**
     * Delete method
     *
     * @param string|null $id State id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    // public function delete()
    // {
    //     $this->request->allowMethod(['post', 'delete']);
    //     $CooperativeRegistration = $this->CooperativeRegistrations->get($id);
    //     $this->FisheryMembers->delete(['id'=>$id]);

        // if($this->request->session()->read('Auth.User.role_id') == 7)
        // {
        //     $this->loadMOdel('Users');
        //     // echo '<pre>';
        //     // print_r($CooperativeRegistration);die;   
        //     $created_by = $CooperativeRegistration['created_by'];
        //     $cUser = $this->Users->find('all')->where(['id' => $created_by])->first();

        //     if(empty($created_by) || $cUser['id'] != $created_by)
        //     {
        //         $this->Flash->error(__('You are not authorized.'));
        //         return $this->redirect(['action' => 'dataEntryPending']);    
        //     }
        // }


        // $data['status'] = 0;
        // $data['updated_by'] = $this->request->session()->read('Auth.User.id');
        // $data['updated'] = date('Y-m-d H:i:s');
        // $CooperativeRegistration = $this->CooperativeRegistrations->patchEntity($CooperativeRegistration, $data);

        // if($this->CooperativeRegistrations->save($CooperativeRegistration)) {
        //     $this->Flash->success(__('The Cooperative Registration has been deleted.'));
        // }
        // $this->redirect($this->referer());

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
    // }

    public function getDistricts(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            // $state_code=$this->request->data('state_code');  
            $state_code= $this->request->session()->read('Auth.User.state_code');  
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
            // $state_code=$this->request->data('state_code');    
            $state_code= $this->request->session()->read('Auth.User.state_code');
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
            // $state_code=$this->request->data('state_code');    
            $state_code= $this->request->session()->read('Auth.User.state_code');
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
            'conditions' => [$searchString,'created_by'=>$this->request->session()->read('Auth.User.id')]
        ])->where(['is_draft'=>0,'is_approved'=>0,'status'=>1]);

        $this->paginate = ['limit' => 10];
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
        $this->loadModel('States');

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
            'order' => ['id' => 'DESC'],
            'conditions' => [$searchString,'created_by'=>$this->request->session()->read('Auth.User.id')]
        ])->where(['is_draft'=>1,'status'=>1]);

        $this->paginate = ['limit' => 10];
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

            $remarks_data = [];
            $remarks_data['is_approved'] = $data['is_approved'];
            $remarks_data['remark'] = $data['remark'];
            $remarks_data['approved_by'] = $this->request->session()->read('Auth.User.id');

            $data['approved_by'] = $this->request->session()->read('Auth.User.id');
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
    
}