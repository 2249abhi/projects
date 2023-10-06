<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * NationalFederations Controller
 *
 * @property \App\Model\Table\NationalFederationsTable $NationalFederations
 *
 * @method \App\Model\Entity\State[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NationalFederationsController extends AppController
{ 

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['sectorAddRow','national','addMember','addContact','addNational','getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getSocietyName','getCooperativeSocietyInfo','getSocietyDetails']);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    private function generateIdentificationNumber($id){
        return $unique_number=str_pad($id, 6, '0', STR_PAD_LEFT); 
    }
    public function add(){
    
        $NationalFederation = $this->NationalFederations->newEntity();
        if($this->request->is('post')){
            $data=$this->request->getData();

            $MaxCooperativeId=$this->NationalFederations->find('all')->select(['cooperative_id'=>'MAX(cooperative_id)'])->first();
            $cooperative_id=$MaxCooperativeId->cooperative_id+1;

            $data['cooperative_id']=$cooperative_id;
            $data['reference_year']=date('Y');
            $data['date_registration']=date("Y",strtotime(str_replace("/","-",$data['date_registration'])))=='1970'?null:date("Y-m-d",strtotime(str_replace("/","-",$data['date_registration'])));

            $national_federations_major_activities=$data['national_federations_major_activities']['major_activities_id'];
            if(!empty($national_federations_major_activities)){
            	unset($data['national_federations_major_activities']);
            	foreach ($national_federations_major_activities as $key=>$national_federations_major_activity_id){
					
					if(!empty($national_federations_major_activity['id'])){
						$data['national_federations_major_activities'][$key]['id']=$national_federations_major_activity['id'];	
					}
					$data['national_federations_major_activities'][$key]['major_activities_id']=$national_federations_major_activity_id;            		
            	}
            }
            if(!empty($data['national_federations_members'])){
                foreach($data['national_federations_members'] as $key => $national_federations_member){
                    $data['national_federations_members'][$key]['sector_of_operation_ids']= implode(',', $national_federations_member['sector_of_operation_ids']);
                }
            }
            if(!empty($data['national_federations_contact_details'])){
                foreach($data['national_federations_contact_details'] as $key => $national_federations_contact_detail){
                    $data['national_federations_contact_details'][$key]['major_activities_id']= implode(',', $national_federations_contact_detail['major_activities_id']);
                }
            }
            
            $data['is_draft']=0;
            $data['created_by'] = $this->request->session()->read('Auth.User.id');
            
            $NationalFederation = $this->NationalFederations->patchEntity($NationalFederation,$data,['associated' => ['NationalFederationsContactDetails','NationalFederationsMembers','NationalFederationsMajorActivities']]);

            
            if($this->NationalFederations->save($NationalFederation)){
                
                $this->Flash->success(__('The Cooperative data has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
        }

        // Start States for dropdown
        $state_code= $this->request->session()->read('Auth.User.state_code');
        $this->loadModel('States');
        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
        $this->set('states',$states);    
        // End States for dropdown
        

        ####### degination dropdown start#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1])->toArray();
       $this->set('designations',$designations);
        ###################degination dropdown end ############
		// Start PresentFunctionalStatus for dropdown
        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
		$this->set('presentFunctionalStatus',$presentFunctionalStatus);
        // Start urban_local_bodies type for dropdown
         
		// Start affiliationWithGovtDept for dropdown
		$this->loadModel('AffiliationWithGovtDept');
		$affiliationWithGovtDept=$this->AffiliationWithGovtDept->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC']);
		$this->set('affiliationWithGovtDept',$affiliationWithGovtDept);
        // End affiliationWithGovtDept for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$NationalFederation->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown


        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$NationalFederation->urban_local_body_type_code,'state_code'=>$NationalFederation->state_code])->order(['local_body_name'=>'ASC']);
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown
		
        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$NationalFederation->urban_local_body_code])->order(['ward_name'=>'ASC']);

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown

        // Start States for dropdown
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$NationalFederation->state_code])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        // End States for dropdown
        
        // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$NationalFederation->district_code])->order(['name'=>'ASC']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown

        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$NationalFederation->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown



        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$NationalFederation->gram_panchayat_code])->order(['village_name'=>'ASC']); 
        $this->set('villages',$villages);  
        // end villages for dropdown
        
            

        //Start Major Activities for dropdown
	        $this->loadMOdel('MajorActivities');
	        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
	        $this->set('major_activities',$major_activities);

        //End Major Activities for dropdown
	    
        //Start Class of Membership for dropdown
        	$class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships);  
        //End Class of Membership for dropdown
        
        //Start type_of_memberships for dropdown
        	$type_of_memberships=[1=>'Primary Cooperative Societies',2=>'District Union/Federations',3=>'State Federations',4=>'Multi-State Cooperative Society'];
        	$this->set('type_of_memberships',$type_of_memberships);  
        //End type_of_memberships for dropdown	

        	


        $this->set(compact('NationalFederation'));

    }


    /**
     * Add edit
     *
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     */
    public function edit($id=null){
    
        $NationalFederation = $this->NationalFederations->get($id,['contain'=>['NationalFederationsContactDetails','NationalFederationsMembers','NationalFederationsMajorActivities']]);
        if($this->request->is(['post','put'])){
            $data=$this->request->getData();

            
            $data['date_registration']=date("Y",strtotime(str_replace("/","-",$data['date_registration'])))=='1970'?null:date("Y-m-d",strtotime(str_replace("/","-",$data['date_registration'])));

            $national_federations_major_activities=$data['national_federations_major_activities']['major_activities_id'];
            unset($data['national_federations_major_activities']);
            if(!empty($data['national_federations_members'])){
                foreach($data['national_federations_members'] as $key => $national_federations_member){
                    $data['national_federations_members'][$key]['sector_of_operation_ids']= implode(',', $national_federations_member['sector_of_operation_ids']);
                }
            }
            if(!empty($data['national_federations_contact_details'])){
                foreach($data['national_federations_contact_details'] as $key => $national_federations_contact_detail){
                    $data['national_federations_contact_details'][$key]['major_activities_id']= implode(',', $national_federations_contact_detail['major_activities_id']);
                }
            }
            
            $data['is_draft']=0;
            $data['created_by'] = $this->request->session()->read('Auth.User.id');
            $NationalFederation = $this->NationalFederations->patchEntity($NationalFederation,$data,['associated' => ['NationalFederationsContactDetails','NationalFederationsMembers','NationalFederationsMajorActivities']]);
            
            
            if($this->NationalFederations->save($NationalFederation)){
                
            	if(!empty($national_federations_major_activities)){
            	$this->loadModel('NationalFederationsMajorActivities');
            	$this->NationalFederationsMajorActivities->deleteAll(['national_federations_id'=>$id]);
            	foreach ($national_federations_major_activities as $key=>$national_federations_major_activity_id){
					$major_activities=$this->NationalFederationsMajorActivities->newEntity();
                    $major_activities=$this->NationalFederationsMajorActivities->patchEntity($major_activities,[]);
                    $major_activities->national_federations_id=$id;
                    $major_activities->major_activities_id=$national_federations_major_activity_id;
                    $this->NationalFederationsMajorActivities->save($major_activities);

            	}
            	}

                $this->Flash->success(__('The Cooperative data has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
        }

        // Start States for dropdown
        $state_code= $this->request->session()->read('Auth.User.state_code');
        $this->loadModel('States');

        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
        $this->set('states',$states);    
        // End States for dropdown
        

        ####### degination dropdown start#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        ###################degination dropdown end ############
		// Start PresentFunctionalStatus for dropdown
        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
		$this->set('presentFunctionalStatus',$presentFunctionalStatus);
        // Start urban_local_bodies type for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$NationalFederation->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown
		// Start affiliationWithGovtDept for dropdown
		$this->loadModel('AffiliationWithGovtDept');
		$affiliationWithGovtDept=$this->AffiliationWithGovtDept->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC']);
		$this->set('affiliationWithGovtDept',$affiliationWithGovtDept);
        // End affiliationWithGovtDept for dropdown

        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$NationalFederation->urban_local_body_type_code,'state_code'=>$NationalFederation->state_code])->order(['local_body_name'=>'ASC']);
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown


        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$NationalFederation->urban_local_body_code])->order(['ward_name'=>'ASC']);

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown

        // Start States for dropdown
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$NationalFederation->state_code])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        // End States for dropdown
        
        // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$NationalFederation->district_code])->order(['name'=>'ASC']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown

        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$NationalFederation->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown


        // Start villages for dropdown
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$NationalFederation->gram_panchayat_code])->order(['village_name'=>'ASC']); 
        $this->set('villages',$villages);  
        // end villages for dropdown
        

        //Start Major Activities for dropdown
	        $this->loadMOdel('MajorActivities');
	        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
	        $this->set('major_activities',$major_activities);

        //End Major Activities for dropdown
	        //Start Class of Membership for dropdown
        	$class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships);  
        //End Class of Membership for dropdown
        
        //Start type_of_memberships for dropdown
        	$type_of_memberships=[1=>'Primary Cooperative Societies',2=>'District Union/Federations',3=>'State Federations',4=>'Multi-State Cooperative Society'];
        	$this->set('type_of_memberships',$type_of_memberships);  
        //End type_of_memberships for dropdown	

        	


        $this->set(compact('NationalFederation'));

    }

    public function phoneNumberAddRow()
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
    public function emailIdAddRow()
    {
        $this->viewBuilder()->setLayout('ajax');
        $hr2 = $this->request->data('hr2'); 
        $this->set('ehr2',$hr2);
    }
    public function deleteAllRecord(){
    	$this->autoRender=false;
        $this->viewBuilder()->setLayout('ajax');
        $id = $this->request->data('id'); 

        $table_name= $this->request->data('table_name'); 

        $TableRegistryObj=TableRegistry::get($table_name);
		$TableData=$TableRegistryObj->get($id);	

		$TableRegistryObj->delete($TableData);

    }

    public function membersAddRow()
    {
        $this->viewBuilder()->setLayout('ajax');
        $hr2 = $this->request->data('hr2'); 
        $this->set('hr2',$hr2);
        //Start Major Activities for dropdown
            $this->loadMOdel('MajorActivities');
            $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
            $this->set('major_activities',$major_activities);

        //End Major Activities for dropdown

        //Start type_of_memberships for dropdown
        	$type_of_memberships=[1=>'Primary Cooperative Societies',2=>'District Union/Federations',3=>'State Federations',4=>'Multi-State Cooperative Society'];
        	$this->set('type_of_memberships',$type_of_memberships);  
        //End type_of_memberships for dropdown	

		//Start Class of Membership for dropdown
        	$class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships);  
        //End Class of Membership for dropdown
        // Start States for dropdown
        $this->loadModel('States');
        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
        $this->set('states',$states);    
        // End States for dropdown
            
        ####### degination dropdown start#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        ###################degination dropdown end ############
	


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


        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (!empty($this->request->query['cooperative_society_name'])) {
            $cooperative_society_name = trim($this->request->query['cooperative_society_name']);
            $this->set('cooperative_society_name', $cooperative_society_name);
            $search_condition[] = "cooperative_society_name like '%" . $cooperative_society_name . "%'";
        }
        if (!empty($this->request->query['identification_number'])) {
            $identification_number = trim($this->request->query['identification_number']);
            $this->set('identification_number', $cooperative_society_name);
            $search_condition[] = "identification_number like '%" . $identification_number . "%'";
        }
        if (!empty($this->request->query['registration_number'])) {
            $registration_number = trim($this->request->query['registration_number']);
            $this->set('registration_number', $registration_number);
            $search_condition[] = "registration_number like '%" . $registration_number . "%'";
        }
        if (!empty($this->request->query['date_registration'])) {
            $date_registration = trim($this->request->query['date_registration']);
            $this->set('date_registration', $date_registration);
            $date_registration=date("Y",strtotime(str_replace("/","-",$date_registration)))=='1970'?null:date("Y-m-d",strtotime(str_replace("/","-",$date_registration)));
            $search_condition[] = "DATE(date_registration) = '" . $date_registration . "'";
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
		
        $registrationQuery = $this->NationalFederations->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => [$searchString,$search_condition2,$search_condition3]
        ])->where(['is_draft'=>0]);

        $this->paginate = ['limit' => 20];
        $NationalFederations = $this->paginate($registrationQuery);
        $this->set(compact('NationalFederations'));

        
    }


    public function view($id = null)
    {
        $NationalFederation = $this->NationalFederations->get($id,['contain'=>['NationalFederationsContactDetails','NationalFederationsMembers','NationalFederationsMajorActivities']]);
        $this->set(compact('NationalFederation'));
        // Start States for dropdown
        $state_code= $this->request->session()->read('Auth.User.state_code');
        $this->loadModel('States');

        if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
        {
            $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
        } else {
            $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$NationalFederation->state_code])->toArray();
        }
		
        $this->set('states',$states);    
        // End States for dropdown
        $this->loadModel('AffiliationWithGovtDept');
		$affiliationWithGovtDept = $this->AffiliationWithGovtDept->find('all')->where([$NationalFederation['affiliation_with_govt_dept']])->order(['id'=>'ASC'])->first();
		$this->set('affiliationWithGovtDept',$affiliationWithGovtDept['name']);
        // End affiliationWithGovtDept for dropdown

        ####### degination dropdown start#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1])->toArray();
       $this->set('designations',$designations);
        ###################degination dropdown end ############


        // Start urban_local_bodies type for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$NationalFederation->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown


        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$NationalFederation->urban_local_body_type_code,'state_code'=>$NationalFederation->state_code])->order(['local_body_name'=>'ASC'])->toArray();
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown


        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$NationalFederation->urban_local_body_code])->order(['ward_name'=>'ASC'])->toArray();

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown


        // Start States for dropdown
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$NationalFederation->state_code])->order(['name'=>'ASC'])->toArray();
            $this->set('districts',$districts);
        // End States for dropdown
        
        // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$NationalFederation->district_code])->order(['name'=>'ASC'])->toArray();
            $this->set('blocks',$blocks);
        // end Blocks for dropdown

        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$NationalFederation->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown

        // Start villages for dropdown
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$NationalFederation->gram_panchayat_code])->order(['village_name'=>'ASC'])->toArray(); 
        $this->set('villages',$villages);  
        // end villages for dropdown
        
        //Start Major Activities for dropdown
	        $this->loadMOdel('MajorActivities');
	        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
	        $this->set('major_activities',$major_activities);

        //End Major Activities for dropdown	

	    //Start Class of Membership for dropdown
        	$class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships);  
        //End Class of Membership for dropdown
            
        //Start type_of_memberships for dropdown
        	$type_of_memberships=[1=>'Primary Cooperative Societies',2=>'District Union/Federations',3=>'State Federations',4=>'Multi-State Cooperative Society'];
        	$this->set('type_of_memberships',$type_of_memberships);  
        //End type_of_memberships for dropdown	

        //Start Class of Membership for dropdown
            $class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
            $this->set('class_of_memberships',$class_of_memberships);  
        //End Class of Membership for dropdown
        
            

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
        $NationalFederation=$this->NationalFederations->get($id);
        if($this->NationalFederations->delete($NationalFederation)){
            $this->Flash->success(__('The National Federation has been deleted.'));
        }else{
            $this->Flash->error(__('The National Federation could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
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
            $this->loadMOdel('Blocks');
            $Blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$district_code])->order(['name'=>'ASC']);
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
	public function addMain(){
    
        $NationalFederation = $this->NationalFederations->newEntity();
        if($this->request->is('post')){
            $data=$this->request->getData();

            $MaxCooperativeId=$this->NationalFederations->find('all')->select(['cooperative_id'=>'MAX(cooperative_id)'])->first();
            $cooperative_id=$MaxCooperativeId->cooperative_id+1;

            $data['cooperative_id']=$cooperative_id;
            $data['reference_year']=date('Y');
            $data['date_registration']=date("Y",strtotime(str_replace("/","-",$data['date_registration'])))=='1970'?null:date("Y-m-d",strtotime(str_replace("/","-",$data['date_registration'])));

            $national_federations_major_activities=$data['national_federations_major_activities']['major_activities_id'];
            if(!empty($national_federations_major_activities)){
            	unset($data['national_federations_major_activities']);
            	foreach ($national_federations_major_activities as $key=>$national_federations_major_activity_id){
					
					if(!empty($national_federations_major_activity['id'])){
						$data['national_federations_major_activities'][$key]['id']=$national_federations_major_activity['id'];	
					}
					$data['national_federations_major_activities'][$key]['major_activities_id']=$national_federations_major_activity_id;            		
            	}
            }
            if(!empty($data['national_federations_members'])){
                foreach($data['national_federations_members'] as $key => $national_federations_member){
                    $data['national_federations_members'][$key]['sector_of_operation_ids']= implode(',', $national_federations_member['sector_of_operation_ids']);
                }
            }
            if(!empty($data['national_federations_contact_details'])){
                foreach($data['national_federations_contact_details'] as $key => $national_federations_contact_detail){
                    $data['national_federations_contact_details'][$key]['major_activities_id']= implode(',', $national_federations_contact_detail['major_activities_id']);
                }
            }
            
            $data['is_draft']=0;
            $data['created_by'] = $this->request->session()->read('Auth.User.id');
            $NationalFederation = $this->NationalFederations->patchEntity($NationalFederation,$data,['associated' => ['NationalFederationsContactDetails','NationalFederationsMembers','NationalFederationsMajorActivities']]);

            
            if($this->NationalFederations->save($NationalFederation)){
                
                $this->Flash->success(__('The Cooperative data has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
        }

        // Start States for dropdown
        $state_code= $this->request->session()->read('Auth.User.state_code');
        $this->loadModel('States');
        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
        $this->set('states',$states);    
        // End States for dropdown
        

        ####### degination dropdown start#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        ###################degination dropdown end ############
		// Start PresentFunctionalStatus for dropdown
        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
		$this->set('presentFunctionalStatus',$presentFunctionalStatus);
        // Start urban_local_bodies type for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$NationalFederation->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown

		$this->loadMOdel('Federation');
		$districtFederation=$this->Federation->find('list',['keyField'=>'id','valueField'=>'federation_name'])->where(['status'=>1,'district_code !='=>0])->order(['federation_name'=>'ASC']);
            
        $this->set('districtFederation',$districtFederation);  

        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$NationalFederation->urban_local_body_type_code,'state_code'=>$NationalFederation->state_code])->order(['local_body_name'=>'ASC']);
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown

		$this->loadModel('SectorOperations');

       $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sector_operations',$sector_operations);
        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$NationalFederation->urban_local_body_code])->order(['ward_name'=>'ASC']);

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown

        // Start States for dropdown
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$NationalFederation->state_code])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        // End States for dropdown
		
		// Start affiliationWithGovtDept for dropdown
            $this->loadModel('AffiliationWithGovtDept');
            $affiliationWithGovtDept=$this->AffiliationWithGovtDept->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC']);
            $this->set('affiliationWithGovtDept',$affiliationWithGovtDept);
        // End affiliationWithGovtDept for dropdown
        
        // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$NationalFederation->district_code])->order(['name'=>'ASC']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown

        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$NationalFederation->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown



        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$NationalFederation->gram_panchayat_code])->order(['village_name'=>'ASC']); 
        $this->set('villages',$villages);  
        // end villages for dropdown
        
            

        //Start Major Activities for dropdown
	        $this->loadMOdel('MajorActivities');
	        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
	        $this->set('major_activities',$major_activities);

        //End Major Activities for dropdown
	    
        //Start Class of Membership for dropdown
        	$class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships);  
        //End Class of Membership for dropdown
        
        //Start type_of_memberships for dropdown
        	$type_of_memberships=[4=>'Multi-State Cooperative Society',3=>'State Federations',2=>'District Union/Federations',1=>'Primary Cooperative Societies'];
        	$this->set('type_of_memberships',$type_of_memberships);  
        //End type_of_memberships for dropdown	

        	
 

        $this->set(compact('NationalFederation'));

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
	public function getSocietyName(){
        $this->viewBuilder()->setLayout('ajax');
		if($this->request->is('ajax')){
			$this->loadMOdel('CooperativeRegistrations');
			$member_id = $this->request->data('type_id');    
			$state_id = $this->request->data('state_code');    
			$district_id = $this->request->data('district_code');    
			$sector_id = $this->request->data('sector_id');    
 
			$loc = $this->request->data('lock_id');    
			$registrationQuery = [];
			if(!empty($member_id) && !empty($state_id) && empty($district_id) && !empty($sector_id) && !empty($loc)){ 
				$registrationQuery = $this->CooperativeRegistrations->find('all', [
				'order' => ['created' => 'DESC'],
				'conditions' => ['cooperative_society_type_id'=>$member_id,'state_code'=>$state_id,'sector_of_operation'=>$sector_id,'location_of_head_quarter'=>$loc]
			])->toArray(); 
			
			}
			if(!empty($member_id) && !empty($state_id) && !empty($district_id) && !empty($sector_id) && !empty($loc)){ 
				$registrationQuery = $this->CooperativeRegistrations->find('all', [
				'order' => ['created' => 'DESC'],
				'conditions' => ['cooperative_society_type_id'=>$member_id,'state_code'=>$state_id,'district_code'=>$district_id,'sector_of_operation'=>$sector_id,'location_of_head_quarter'=>$loc]
			])->toArray(); 
			//echo "<pre>".$member_id."---".$state_id."===".$district_id."=====".$block_id."===".$vill_id."===".$gram_id."===".$loc; print_r($registrationQuery); exit;
			}
			$option_html='<option value="">Select</option>';

			if($registrationQuery){
				foreach($registrationQuery as $key=>$value){
					$option_html.='<option value="'.$value['id'].'">'.$value['cooperative_society_name'].'</option>';
				}
			}
			echo $option_html;
		}
		exit;
    }
	public function getCooperativeSocietyInfo(){
		$this->viewBuilder()->setLayout('ajax');
		$this->loadModel('CooperativeRegistrations');
		$socity_id = $this->request->data('location_of_head_quarter'); 
		$member_id = $this->request->data('member_id'); 
		$role_id = $this->request->session()->read('Auth.User.role_id');
		$formAaDetails = [];
		//if($this->request->is('ajax')){
		if (!empty($socity_id)) {
			$this->set('socity_id',$socity_id); 
			$this->set('member_id',$member_id); 
		}
		// Start PresentFunctionalStatus for dropdown
        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
		$this->set('presentFunctionalStatus',$presentFunctionalStatus);
        // Start urban_local_bodies type for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$NationalFederation->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown

		$this->loadMOdel('Federation');
		$districtFederation=$this->Federation->find('list',['keyField'=>'id','valueField'=>'federation_name'])->where(['status'=>1,'district_code !='=>0])->order(['federation_name'=>'ASC']);
            
        $this->set('districtFederation',$districtFederation);
		
		$this->loadMOdel('PrimaryActivities');

		$pactivities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
		$this->set('PrimaryActivities',$pactivities);
        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$NationalFederation->urban_local_body_type_code,'state_code'=>$NationalFederation->state_code])->order(['local_body_name'=>'ASC']);
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown
		// Start States for dropdown
        $state_code= $this->request->session()->read('Auth.User.state_code');
        $this->loadModel('States');
        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
        $this->set('states',$states);    
        // End States for dropdown
		$this->loadModel('SectorOperations');
		
       $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sector_operations',$sector_operations);
        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$NationalFederation->urban_local_body_code])->order(['ward_name'=>'ASC']);

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown

        // Start States for dropdown
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$NationalFederation->state_code])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        // End States for dropdown
		
		 // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$NationalFederation->district_code])->order(['name'=>'ASC']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown
		 // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$NationalFederation->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown



        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$NationalFederation->gram_panchayat_code])->order(['village_name'=>'ASC']); 
        $this->set('villages',$villages);  
        // end villages for dropdown
		
		// Start affiliationWithGovtDept for dropdown
            $this->loadModel('AffiliationWithGovtDept');
            $affiliationWithGovtDept=$this->AffiliationWithGovtDept->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC']);
            $this->set('affiliationWithGovtDept',$affiliationWithGovtDept);
        // End affiliationWithGovtDept for dropdown

        //Start Major Activities for dropdown
	        $this->loadMOdel('MajorActivities');
	        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
	        $this->set('major_activities',$major_activities);

        //End Major Activities for dropdown
	    
        //Start Class of Membership for dropdown
        	$class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships);  
        	$this->set('role_id',$role_id);  
        //End Class of Membership for dropdown
        
        

	}
	
	public function addNational(){
    
		$national = $this->NationalFederations->find('all')->where(['user_id'=>$this->request->session()->read('Auth.User.id')])->first();
		if(!empty($national)){
			return $this->redirect(['action' => 'edit-national',$national['id']]);
			 //$NationalFederation = $this->NationalFederations->get($national['id'],['contain'=>['NationalFederationsContactDetails']]);
		}
		else{
			$NationalFederation = $this->NationalFederations->newEntity();
		}
        
        if($this->request->is('post')){
            $data=$this->request->getData();

            $MaxCooperativeId=$this->NationalFederations->find('all')->select(['cooperative_id'=>'MAX(id)'])->first();
            $cooperative_id=(int)$MaxCooperativeId->cooperative_id+1;
            $data['cooperative_id']=$cooperative_id;
            $data['identification_number']=$this->generateIdentificationNumber($cooperative_id);
            
            $data['reference_year']=date('Y');
			$data['reference_year']=date('Y');
            $data['date_registration']=date("Y-m-d",strtotime(str_replace("/","-",$data['date_registration'])));

            $national_federations_major_activities=$data['national_federations_major_activities']['major_activities_id'];
            if(!empty($national_federations_major_activities)){
            	unset($data['national_federations_major_activities']);
            	foreach ($national_federations_major_activities as $key=>$national_federations_major_activity_id){
					
					if(!empty($national_federations_major_activity['id'])){
						$data['national_federations_major_activities'][$key]['id']=$national_federations_major_activity['id'];	
					}
					$data['national_federations_major_activities'][$key]['major_activities_id']=$national_federations_major_activity_id;            		
            	}
            }
            if(!empty($data['national_federations_members'])){
                foreach($data['national_federations_members'] as $key => $national_federations_member){
                    $data['national_federations_members'][$key]['sector_of_operation_ids']= implode(',', $national_federations_member['sector_of_operation_ids']);
                }
            }
            if(!empty($data['national_federations_contact_details'])){
                foreach($data['national_federations_contact_details'] as $key => $national_federations_contact_detail){
                    $data['national_federations_contact_details'][$key]['major_activities_id']= implode(',', $national_federations_contact_detail['major_activities_id']);
                }
            }
            
            $data['is_draft']=0;
			$data['is_new']=1;
            $data['created_by'] = $this->request->session()->read('Auth.User.id');
			$data['user_id'] = $this->request->session()->read('Auth.User.id');
            $NationalFederation = $this->NationalFederations->patchEntity($NationalFederation,$data,['associated' => ['NationalFederationsContactDetails','NationalFederationsMajorActivities']]);
			$NationalFederation['major_activities_id'] = implode(',',$data['major_activities_id']);
			//echo "<pre>"; print_r($NationalFederation); exit;
            
            if($result = $this->NationalFederations->save($NationalFederation)){
                
                $this->Flash->success(__('The Cooperative data has been saved.'));
                return $this->redirect(['action' => 'member',$result['id']]);
            }
            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
        }

        // Start States for dropdown
        $state_code= $this->request->session()->read('Auth.User.state_code');
        $this->loadModel('States');
        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
        $this->set('states',$states);    
        // End States for dropdown
        

        ####### degination dropdown start#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        ###################degination dropdown end ############
		// Start PresentFunctionalStatus for dropdown
        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
		$this->set('presentFunctionalStatus',$presentFunctionalStatus);
        // Start urban_local_bodies type for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$NationalFederation->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown

		$this->loadMOdel('Federation');
		$districtFederation=$this->Federation->find('list',['keyField'=>'id','valueField'=>'federation_name'])->where(['status'=>1,'district_code !='=>0])->order(['federation_name'=>'ASC']);
            
        $this->set('districtFederation',$districtFederation);  

        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$NationalFederation->urban_local_body_type_code,'state_code'=>$NationalFederation->state_code])->order(['local_body_name'=>'ASC']);
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown

		$this->loadModel('SectorOperations');

       $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sector_operations',$sector_operations);
        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$NationalFederation->urban_local_body_code])->order(['ward_name'=>'ASC']);

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown

        // Start States for dropdown
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$NationalFederation->state_code])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        // End States for dropdown
		
		// Start affiliationWithGovtDept for dropdown
            $this->loadModel('AffiliationWithGovtDept');
            $affiliationWithGovtDept=$this->AffiliationWithGovtDept->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC']);
            $this->set('affiliationWithGovtDept',$affiliationWithGovtDept);
        // End affiliationWithGovtDept for dropdown
        
        // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$NationalFederation->district_code])->order(['name'=>'ASC']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown

        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$NationalFederation->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown



        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$NationalFederation->gram_panchayat_code])->order(['village_name'=>'ASC']); 
        $this->set('villages',$villages);  
        // end villages for dropdown
        
            

        //Start Major Activities for dropdown
	        $this->loadMOdel('MajorActivities');
	        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
	        $this->set('major_activities',$major_activities);

        //End Major Activities for dropdown
	    
        //Start Class of Membership for dropdown
        	$class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships);  
        //End Class of Membership for dropdown
        
        //Start type_of_memberships for dropdown
        	$type_of_memberships=[4=>'Multi-State Cooperative Society',3=>'State Federations',2=>'District Union/Federations',1=>'Primary Cooperative Societies'];
        	$this->set('type_of_memberships',$type_of_memberships);  
        //End type_of_memberships for dropdown	

        	


        $this->set(compact('NationalFederation'));

    }
	
	public function addMember($id){
    
		$this->loadMOdel('NationalFederationsMembers');
		$this->loadMOdel('NationalFederations');
        $NationalFederation = $this->NationalFederationsMembers->newEntity();
		$national = $this->NationalFederations->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => ['id'=>$id]
        ])->first();
		$nationalF = $this->NationalFederationsMembers->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => ['national_federations_id'=>$id]
        ])->count();
		if($national['total_number_of_members'] <= $nationalF){
			return $this->redirect(['action' => 'member',$id]); 
		}
        if($this->request->is('post') && $national['total_number_of_members'] >= $nationalF){
            $data=$this->request->getData();
            $NationalFederation = $this->NationalFederationsMembers->patchEntity($NationalFederation,$data);
			$NationalFederation['other_primary'] = $data['other_primary'];
            $NationalFederation->national_federations_id = $id;
            $NationalFederation->user_id = $this->request->session()->read('Auth.User.id');;
			//echo "<pre>"; print_r($NationalFederation); exit;
            if($this->NationalFederationsMembers->save($NationalFederation)){
        
                $this->Flash->success(__('The Cooperative member data has been saved.'));
                return $this->redirect(['action' => 'member',$id]);
            }  
            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
        }

        // Start States for dropdown
        $state_code= $this->request->session()->read('Auth.User.state_code');
        $this->loadModel('States');
        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
        $this->set('states',$states);    
        // End States for dropdown
        

        ####### degination dropdown start#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        ###################degination dropdown end ############
		// Start PresentFunctionalStatus for dropdown
        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
		$this->set('presentFunctionalStatus',$presentFunctionalStatus);
        // Start urban_local_bodies type for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$NationalFederation->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown

		$this->loadMOdel('Federation');
		$districtFederation=$this->Federation->find('list',['keyField'=>'id','valueField'=>'federation_name'])->where(['status'=>1,'district_code !='=>0])->order(['federation_name'=>'ASC']);
            
        $this->set('districtFederation',$districtFederation);
		
		$this->loadMOdel('PrimaryActivities');

		$pactivities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
		$pactivitiesOth = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['id'=>8])->order(['orderseq'=>'ASC']);
		//$pactivities = array_merge($pactivities,$pactivitiesOth);
		$this->set('primaryActivities',$pactivities);
        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$NationalFederation->urban_local_body_type_code,'state_code'=>$NationalFederation->state_code])->order(['local_body_name'=>'ASC']);
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown

		$this->loadModel('SectorOperations');

       $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sector_operations',$sector_operations);
        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$NationalFederation->urban_local_body_code])->order(['ward_name'=>'ASC']);

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown
		$this->loadMOdel('MajorActivities');
		$major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
		$this->set('major_activities',$major_activities);
        // Start States for dropdown
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$NationalFederation->state_code])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        // End States for dropdown
		
		 // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$NationalFederation->district_code])->order(['name'=>'ASC']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown
		 // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$NationalFederation->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown



        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$NationalFederation->gram_panchayat_code])->order(['village_name'=>'ASC']); 
        $this->set('villages',$villages);  
        // end villages for dropdown
		
		// Start affiliationWithGovtDept for dropdown
            $this->loadModel('AffiliationWithGovtDept');
            $affiliationWithGovtDept=$this->AffiliationWithGovtDept->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC']);
            $this->set('affiliationWithGovtDept',$affiliationWithGovtDept);
        // End affiliationWithGovtDept for dropdown

        //Start Major Activities for dropdown
	        $this->loadMOdel('MajorActivities');
	        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
	        $this->set('major_activities',$major_activities);

        //End Major Activities for dropdown
	    
        //Start Class of Membership for dropdown
        	$class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships);  
        //End Class of Membership for dropdown
        
        //Start type_of_memberships for dropdown
        	$type_of_memberships=[5=>'Institutional Membership',4=>'Multi-State Cooperative Society',3=>'State Federations',6=>'Regional Union',2=>'District Union/Federations',1=>'Primary Cooperative Societies'];
        	$this->set('type_of_memberships',$type_of_memberships);  
        //End type_of_memberships for dropdown

        $this->set(compact('NationalFederation','national','id'));

    }
	
	/**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function national()
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

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
                $s_state = trim($this->request->query['state']);
                $search_condition3['state_code']= $s_state;             
                $this->set('s_state', $s_state);
            }

        if (!empty($this->request->query['cooperative_society_name'])) {
            $cooperative_society_name = trim($this->request->query['cooperative_society_name']);
            $this->set('cooperative_society_name', $cooperative_society_name);
            $search_condition[] = "cooperative_society_name like '%" . $cooperative_society_name . "%'";
        }
        if (!empty($this->request->query['identification_number'])) {
            $identification_number = trim($this->request->query['identification_number']);
            $this->set('identification_number', $cooperative_society_name);
            $search_condition[] = "identification_number like '%" . $identification_number . "%'";
        }
        if (!empty($this->request->query['registration_number'])) {
            $registration_number = trim($this->request->query['registration_number']);
            $this->set('registration_number', $registration_number);
            $search_condition[] = "registration_number like '%" . $registration_number . "%'";
        }
        if (!empty($this->request->query['date_registration'])) {
            $date_registration = trim($this->request->query['date_registration']);
            $this->set('date_registration', $date_registration);
            $date_registration=date("Y",strtotime(str_replace("/","-",$date_registration)))=='1970'?null:date("Y-m-d",strtotime(str_replace("/","-",$date_registration)));
            $search_condition[] = "DATE(date_registration) = '" . $date_registration . "'";
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

        
        $registrationQuery = $this->NationalFederations->find('all', [
            'order' => ['id' => 'DESC'],
            'conditions' => [$searchString,$search_condition2,$search_condition3]
        ])->where(['is_draft'=>0,'is_new'=>1]);

		//for national fedration created by list
        if($this->request->session()->read('Auth.User.role_id') == 13)
        {   
			$registrationQuery = $registrationQuery->where(['user_id'=>$this->request->session()->read('Auth.User.id')]);
        }
		if($this->request->session()->read('Auth.User.role_id') == 21)
        {   
			$registrationQuery = $registrationQuery->where(['user_id'=>$this->request->session()->read('Auth.User.parent_id')]);
        }
		if($this->request->session()->read('Auth.User.role_id') == 15){
			$user_id = $this->request->session()->read('Auth.User.id');
			$nationalNodal = $this->Users->find('all')->where(['parent_id'=>$user_id])->toArray();
			if(!empty($nationalNodal)){
				$nodalId = array_column($nationalNodal,'id');
				$registrationQuery = $registrationQuery->where(['user_id IN'=>$nodalId]);
			}
			else{
				$registrationQuery = $registrationQuery->where(['user_id'=>'']);
			}
		}
		//echo "wwwwww".$this->request->session()->read('Auth.User.role_id'); exit;
        $this->paginate = ['limit' => 20];
        $NationalFederations = $this->paginate($registrationQuery);

        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
            $query->hydrate(false);
            $stateOption = $query->toArray();
            $this->set('sOption',$stateOption);
		
        $this->set(compact('NationalFederations'));

        
    }
	/**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function member($id = null)
    {

        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('NationalFederationsMembers');
        $this->loadMOdel('NationalFederations');
        $this->loadMOdel('Federation');
		
		// if($this->request->session()->read('Auth.User.role_id') == 13 || $this->request->session()->read('Auth.User.role_id') == 15){
			// if($this->request->session()->read('Auth.User.role_id') == 15 && empty($id)){
				// $user_id = $this->request->session()->read('Auth.User.id');
				//$nationalNodal = $this->Users->find('all')->where(['parent_id'=>$user_id])->first();
				//$nodalId = array_column($nationalNodal,'id');
				// $userid = $this->Users->get($user_id);
				//echo "<pre>"; print_r($nodalId); exit;
				// $user_id = $userid['parent_id'];
			// }
			// else{
				// $user_id  = $this->request->session()->read('Auth.User.id');
			// }
			// $national = $this->NationalFederations->find('all')->where(['user_id'=>$user_id])->first();
			// $getuser = $this->NationalFederationsMembers->find('all')->where(['national_federations_id'=>$national['id']])->first();
		// }
		// else{
			// $national = $this->NationalFederations->find('all')->where(['id'=>$id])->first();
			// $getuser = $this->NationalFederationsMembers->find('all')->where(['national_federations_id'=>$id])->first();
		// }
        // echo "sssssss"; exit;
        // $getuserid = $getuser['national_federations_id'];
        // $fname = $this->Federation->find('all')->where(['id' => $getuserid])->first();
         // $this->set('fname',$fname);

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
                $s_state = trim($this->request->query['state']);
                $search_condition2['area_state_code']= $s_state;             
                $this->set('s_state', $s_state);
            }

        if (!empty($this->request->query['name_of_member'])) {
			$this->set('name_of_member', $this->request->query['name_of_member']);
            $name_of_member = trim($this->request->query['name_of_member']);
            
            $search_condition[] = "name_of_member like '%" . $name_of_member . "%'";
        }
        if (!empty($this->request->query['type_of_memberships_id'])) {
            $type_of_memberships_id = trim($this->request->query['type_of_memberships_id']);
            $this->set('type_of_memberships_id', $type_of_memberships_id);
            $search_condition[] = "type_of_memberships_id like '%" . $type_of_memberships_id . "%'";
        }
        if (!empty($this->request->query['primary_activity'])) {
            $primary_activity = trim($this->request->query['primary_activity']);
            $this->set('primary_activity', $primary_activity);
            $search_condition[] = "primary_activity like '%" . $primary_activity . "%'";
        }

        if (!empty($this->request->query['class_of_memberships_id'])) {
            $class_of_memberships_id = trim($this->request->query['class_of_memberships_id']);
            $this->set('class_of_memberships_id', $class_of_memberships_id);
            $search_condition[] = "class_of_memberships_id like '%" . $class_of_memberships_id . "%'";
        }
		if (!empty($this->request->query['approved_status'])) {
            $approved_status = trim($this->request->query['approved_status']);
            $this->set('approved_status', $approved_status);
            $search_condition[] = "approved_status like '%" . $approved_status . "%'";
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
		if(($this->request->session()->read('Auth.User.role_id') == 13 || $this->request->session()->read('Auth.User.role_id') == 15) && empty($id)){
			if($this->request->session()->read('Auth.User.role_id') == 15 && empty($id)){
				$user_id = $this->request->session()->read('Auth.User.id');
				$nationalNodal = $this->Users->find('all')->where(['parent_id'=>$user_id])->toArray();
				
				//$nodalId = array_column($nationalNodal,'id');
				
				$nationalInfo = $this->NationalFederations->find('all')->where(['user_id IN'=>$user_id])->first();
				//$nodalIds = array_column($nationalInfo,'id');
				if(!empty($nationalInfo)){
				 $registrationQuery = $this->NationalFederationsMembers->find('all', [
					'order' => ['id' => 'DESC'],
					'conditions' => [$searchString,$search_condition2]
				])->where(['national_federations_id IN'=>$nationalInfo['id']]);
				}
				//echo "<pre>"; print_r($registrationQuery);exit;
				//$getuser = $this->NationalFederationsMembers->find('all')->where(['national_federations_id'=>$national['id']])->first();
				//$userid = $this->Users->get($user_id);
				//echo "<pre>"; print_r($nodalId); exit;
				//$user_id = $userid['parent_id'];
			}
			else{
				$user_id  = $this->request->session()->read('Auth.User.parent_id');
				$ids  = $this->request->session()->read('Auth.User.id');
				$national = $this->NationalFederations->find('all')->where(['user_id'=>$user_id])->first();
				if(!empty($national)){
					$registrationQuery = $this->NationalFederationsMembers->find('all', [
					'order' => ['id' => 'DESC'],
					'conditions' => [$searchString,$search_condition2]
				])->where(['national_federations_id'=>$national['id'],'user_id'=>$ids]);
				}
				 
			}
		}
		else{ 
			
			$national = $this->NationalFederations->find('all')->where(['id'=>$id])->first();
			$registrationQuery = $this->NationalFederationsMembers->find('all', [
				'order' => ['id' => 'DESC'],
				'conditions' => [$searchString,$search_condition2]
			])->where(['national_federations_id'=>$national['id']]);
			
		}
		
       
		 //Start type_of_memberships for dropdown
        	$type_of_memberships=[5=>'Institutional Membership',4=>'Multi-State Cooperative Society',3=>'State Federations',6=>'Regional Union',2=>'District Union/Federations',1=>'Primary Cooperative Societies'];
        	$this->set('type_of_memberships',$type_of_memberships);  
        //End type_of_memberships for dropdown	
		$this->loadMOdel('SectorOperations');
		$sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
		$this->set('sector_operations',$sector_operations);
		$this->loadMOdel('PrimaryActivities');
		//Start Major Activities for dropdown
	        $this->loadMOdel('MajorActivities');
	        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
	        $this->set('major_activities',$major_activities);

        //End Major Activities for dropdown
		$pactivities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
		$this->set('PrimaryActivities',$pactivities);
		//Start Class of Membership for dropdown
        	$class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships); 
		if(!empty($registrationQuery)){
			$this->paginate = ['limit' => 20];
			$NationalFederations = $this->paginate($registrationQuery);
		}
		else{
			$NationalFederations = [];
		}
        
		if(!empty($this->request->query['export_excel'])) {
			if(($this->request->session()->read('Auth.User.role_id') == 13 || $this->request->session()->read('Auth.User.role_id') == 15) && empty($id)){
				if($this->request->session()->read('Auth.User.role_id') == 15 && empty($id)){
					$user_id = $this->request->session()->read('Auth.User.id');
					$nationalNodal = $this->Users->find('all')->where(['parent_id'=>$user_id])->toArray();
					
					//$nodalId = array_column($nationalNodal,'id');
					
					$nationalInfo = $this->NationalFederations->find('all')->where(['user_id IN'=>$user_id])->first();
					//$nodalIds = array_column($nationalInfo,'id');
					if(!empty($nationalInfo)){
					 $registrationQuery = $this->NationalFederationsMembers->find('all', [
						'order' => ['id' => 'DESC'],
						'conditions' => [$searchString]
					])->where(['national_federations_id IN'=>$nationalInfo['id']]);
					}
					//echo "<pre>"; print_r($registrationQuery);exit;
					//$getuser = $this->NationalFederationsMembers->find('all')->where(['national_federations_id'=>$national['id']])->first();
					//$userid = $this->Users->get($user_id);
					//echo "<pre>"; print_r($nodalId); exit;
					//$user_id = $userid['parent_id'];
				}
				else{
					$user_id  = $this->request->session()->read('Auth.User.parent_id');
					$ids  = $this->request->session()->read('Auth.User.id');
					$national = $this->NationalFederations->find('all')->where(['user_id'=>$user_id])->first();
					if(!empty($national)){
						$registrationQuery = $this->NationalFederationsMembers->find('all', [
						'order' => ['id' => 'DESC'],
						'conditions' => [$searchString]
					])->where(['national_federations_id'=>$national['id'],'user_id'=>$ids]);
					}
				}
			}
			else{ 
				
				$national = $this->NationalFederations->find('all')->where(['id'=>$id])->first();
				$registrationQuery = $this->NationalFederationsMembers->find('all', [
					'order' => ['id' => 'DESC'],
					'conditions' => [$searchString]
				])->where(['national_federations_id'=>$national['id']])->toArray();;
				
			}
			//echo "<pre>"; print_r($registrationQuery); exit;
                $fileName = "Federations_Members-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No", " Name of the National Cooperative Society "," Name of Member Organisation "," Class of Membership ", 'Type of Membership',"Primary Activities / Sector", " Name of Stateor UT "," State / UT "," District "," Block ", 'Gram Panchayat',"Village", " Category of Urban Local Body "," Name of Urban Body "," Name of Locality or Ward ","Pin Code"," Name of the Contact Person ", 'Designation of the Contact Person',"Phone Number of Contact Person", " E-mail ID of Contact Person ");
                $data = array();
                $i=1;
                $stat = ['0'=>'Under Implementation', 'Under Maintenance'];
				$this->loadModel('States');
				$states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
				//echo "<pre>"; print_r($registrationQuery); exit;
                foreach($registrationQuery As $project_detail){
					
					if($project_detail['location_of_head_quarter'] == 2){
						$loc = 'Rural';
					}
					else{
						$loc = 'Urban';
					}
					$this->loadModel('Districts');
					$districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$project_detail->area_state_code])->order(['name'=>'ASC'])->toArray();
					$this->loadMOdel('Blocks');
					$blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'block_code'=>$project_detail->area_block_code])->order(['name'=>'ASC'])->toArray();
					$this->loadMOdel('DistrictsBlocksGpVillages');
					$gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$project_detail->area_block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray(); 
					$this->loadMOdel('DistrictsBlocksGpVillages');
					$villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$project_detail->area_gram_panchayat_code])->order(['village_name'=>'ASC'])->toArray(); 
					$this->loadMOdel('UrbanLocalBodies');
					$localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'localbody_type_code'=>$project_detail->urban_local_body_type_code])->order(['localbody_type_name'=>'ASC'])->toArray();  
					$this->loadMOdel('UrbanLocalBodies');
					$localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_code'=>$project_detail->urban_local_body_code])->order(['local_body_name'=>'ASC'])->toArray();
					$this->loadMOdel('UrbanLocalBodiesWards');
					$localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'ward_code'=>$project_detail->locality_ward_code])->order(['ward_name'=>'ASC'])->toArray();
					$this->loadModel('Designations');
					$designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1])->toArray();
					$class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
					$type_of_memberships=[5=>'Institutional Membership',4=>'Multi-State Cooperative Society',3=>'State Federations',6=>'Regional Union',2=>'District Union/Federations',1=>'Primary Cooperative Societies'];
					$this->loadMOdel('MajorActivities');
					$major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
					
                    $data[]=array($i, $national['cooperative_society_name'],$this->Sanitize->stripAll($project_detail['name_of_member']),$class_of_memberships[$project_detail['class_of_memberships_id']],$type_of_memberships[$project_detail['type_of_memberships_id']], $major_activities[$project_detail['primary_activity']],$loc,$states[$project_detail['area_state_code']],$districts[$project_detail['area_district_code']], $blocks[$project_detail['area_block_code']],$gps[$project_detail['area_gram_panchayat_code']],$villages[$project_detail['area_village_code']], $localbody_types[$project_detail['urban_local_body_type_code']], $localbodies[$project_detail['urban_local_body_code']], $localbodieswards[$project_detail['locality_ward_code']],$project_detail['pincode'],$project_detail['name_of_contact_person'],$designations[$project_detail['designation']],$project_detail['phone_number_of_contact_person'],$project_detail['email']); 
                    $i++;
                }
				//echo "<pre>"; print_r($data); exit;
                $this->exportInExcel($fileName, $headerRow, $data);
            }
        $this->set(compact('NationalFederations','id','national'));

        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
            $query->hydrate(false);
            $stateOption = $query->toArray();
            $this->set('sOption',$stateOption);

        
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
	public function editMember($id){
		$this->loadMOdel('NationalFederationsMembers');
		$this->loadMOdel('NationalFederations');
        $NationalFederation = $this->NationalFederationsMembers->get($id);
		$national = $this->NationalFederations->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => ['id'=>$NationalFederation['national_federations_id']]
        ])->first();
		//echo "<pre>"; print_r($NationalFederation); exit;
        if($this->request->is(['post','put'])){
            $data=$this->request->getData();
			
            $NationalFederation = $this->NationalFederationsMembers->patchEntity($NationalFederation,$data);
			$NationalFederation['other_primary'] = $data['other_primary'];
            if($this->NationalFederationsMembers->save($NationalFederation)){
        
                $this->Flash->success(__('The Cooperative member data has been saved.'));
                return $this->redirect(['action' => 'member',$national['id']]);
            }  
            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
        }

        // Start States for dropdown
        $state_code= $this->request->session()->read('Auth.User.state_code');
        $this->loadModel('States');
        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
        $this->set('states',$states); 
		// End States for dropdown
		$this->loadModel('CooperativeRegistrations');		
        $registrationQuery=$this->CooperativeRegistrations->find('list',['keyField'=>'id','valueField'=>'cooperative_society_name'])->order(['cooperative_society_name'=>'ASC'])->where(['id'=>$NationalFederation['cooprative_society_id']]);
		$this->set('registrationQuery',$registrationQuery);

        ####### degination dropdown start#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        ###################degination dropdown end ############
		// Start PresentFunctionalStatus for dropdown
        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
		$this->set('presentFunctionalStatus',$presentFunctionalStatus);
        // Start urban_local_bodies type for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$NationalFederation->area_state_code])->order(['localbody_type_name'=>'ASC'])->toArray();  
		//echo "<pre>"; print_r($localbody_types); exit;
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown

		$this->loadMOdel('Federation');
		$districtFederation=$this->Federation->find('list',['keyField'=>'id','valueField'=>'federation_name'])->where(['status'=>1,'district_code !='=>0])->order(['federation_name'=>'ASC']);
            
        $this->set('districtFederation',$districtFederation);
		
		$this->loadMOdel('PrimaryActivities');

		$pactivities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
		$this->set('primaryActivities',$pactivities);
        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$NationalFederation->urban_local_body_type_code,'state_code'=>$NationalFederation->area_state_code])->order(['local_body_name'=>'ASC'])->toArray();
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown

		$this->loadModel('SectorOperations');

       $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sector_operations',$sector_operations);
        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'ward_code'=>$NationalFederation->locality_ward_code])->order(['ward_name'=>'ASC'])->toArray();

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown
		$this->loadMOdel('MajorActivities');
		$major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
		$this->set('major_activities',$major_activities);
        // Start States for dropdown
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$NationalFederation->area_state_code])->order(['name'=>'ASC'])->toArray();
            $this->set('districts',$districts);
        // End States for dropdown
		
		 // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'block_code'=>$NationalFederation->area_block_code])->order(['name'=>'ASC']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown
		 // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'gram_panchayat_code'=>$NationalFederation->area_gram_panchayat_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown

	

        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'village_code'=>$NationalFederation->area_village_code])->order(['village_name'=>'ASC']); 
        $this->set('villages',$villages);  
        // end villages for dropdown
		
		// Start affiliationWithGovtDept for dropdown
            $this->loadModel('AffiliationWithGovtDept');
            $affiliationWithGovtDept=$this->AffiliationWithGovtDept->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC']);
            $this->set('affiliationWithGovtDept',$affiliationWithGovtDept);
        // End affiliationWithGovtDept for dropdown

        //Start Major Activities for dropdown
	        $this->loadMOdel('MajorActivities');
	        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
	        $this->set('major_activities',$major_activities);

        //End Major Activities for dropdown
	    
        //Start Class of Membership for dropdown
        	$class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships);  
        //End Class of Membership for dropdown
        
        //Start type_of_memberships for dropdown
        	$type_of_memberships=[5=>'Institutional Membership',4=>'Multi-State Cooperative Society',3=>'State Federations',6=>'Regional Union',2=>'District Union/Federations',1=>'Primary Cooperative Societies'];
        	$this->set('type_of_memberships',$type_of_memberships);  
        //End type_of_memberships for dropdown

        	


        $this->set(compact('NationalFederation','national','id'));
	}
	
	public function viewMember($id){
		$this->loadMOdel('NationalFederationsMembers');
		$this->loadMOdel('NationalFederations');
        $NationalFederation = $this->NationalFederationsMembers->get($id);
		
		$national = $this->NationalFederations->find('all', [
            'order' => ['created' => 'DESC'],
            'conditions' => ['id'=>$NationalFederation['national_federations_id']]
        ])->first();
		//echo "<pre>"; print_r($national); exit;
        // Start States for dropdown
        $state_code= $this->request->session()->read('Auth.User.state_code');
        $this->loadModel('States');
        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
        $this->set('states',$states);    
        // End States for dropdown
        //Start type_of_memberships for dropdown
        	$type_of_memberships=[5=>'Institutional Membership',4=>'Multi-State Cooperative Society',3=>'State Federations',6=>'Regional Union',2=>'District Union/Federations',1=>'Primary Cooperative Societies'];
        	$this->set('type_of_memberships',$type_of_memberships);  
        //End type_of_memberships for dropdown	
		$this->loadMOdel('SectorOperations');
		$sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
       $this->set('sector_operations',$sector_operations);
	   $this->loadMOdel('PrimaryActivities');
		//Start Major Activities for dropdown
	        $this->loadMOdel('MajorActivities');
	        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
	        $this->set('major_activities',$major_activities);
		
        //End Major Activities for dropdown
		$pactivities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
		$this->set('PrimaryActivities',$pactivities);
		//Start Class of Membership for dropdown
        	$class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships);

        ####### degination dropdown start#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1])->toArray();
       $this->set('designations',$designations);
        ###################degination dropdown end ############
		// Start PresentFunctionalStatus for dropdown
        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
		$this->set('presentFunctionalStatus',$presentFunctionalStatus);
        // Start urban_local_bodies type for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'localbody_type_code'=>$NationalFederation->urban_local_body_type_code])->order(['localbody_type_name'=>'ASC'])->toArray();  
		//echo "<pre>"; print_r($localbody_types); exit;
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown

		$this->loadMOdel('Federation');
		$districtFederation=$this->Federation->find('list',['keyField'=>'id','valueField'=>'federation_name'])->where(['status'=>1,'district_code !='=>0])->order(['federation_name'=>'ASC']);
            
        $this->set('districtFederation',$districtFederation);
		
		$this->loadMOdel('PrimaryActivities');

		$pactivities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
		$this->set('PrimaryActivities',$pactivities);
        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_code'=>$NationalFederation->urban_local_body_code])->order(['local_body_name'=>'ASC'])->toArray();
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown

		$this->loadModel('SectorOperations');

       $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sector_operations',$sector_operations);
        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'ward_code'=>$NationalFederation->locality_ward_code])->order(['ward_name'=>'ASC'])->toArray();
		
        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown
		$this->loadMOdel('MajorActivities');
		$major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
		$this->set('major_activities',$major_activities);
        // Start States for dropdown
		
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$NationalFederation->area_district_code])->order(['name'=>'ASC'])->toArray();
            $this->set('districts',$districts);
        // End States for dropdown
		 
		 // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'block_code'=>$NationalFederation->area_block_code])->order(['name'=>'ASC'])->toArray();
            $this->set('blocks',$blocks);
        // end Blocks for dropdown
		 // Start Gram Panchayats for dropdown
          
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'gram_panchayat_code'=>$NationalFederation->area_gram_panchayat_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown

	

        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'village_code'=>$NationalFederation->area_village_code])->order(['village_name'=>'ASC'])->toArray(); 
        $this->set('villages',$villages);  
        // end villages for dropdown
		
		// Start affiliationWithGovtDept for dropdown
            $this->loadModel('AffiliationWithGovtDept');
            $affiliationWithGovtDept=$this->AffiliationWithGovtDept->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC'])->toArray();
            $this->set('affiliationWithGovtDept',$affiliationWithGovtDept);
        // End affiliationWithGovtDept for dropdown

        //Start Major Activities for dropdown
	        $this->loadMOdel('MajorActivities');
	        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
	        $this->set('major_activities',$major_activities);

        //End Major Activities for dropdown
	    
        //Start Class of Membership for dropdown
        	$class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships);  
        //End Class of Membership for dropdown
        
        //Start type_of_memberships for dropdown
        	$type_of_memberships=[5=>'Institutional Membership',4=>'Multi-State Cooperative Society',3=>'State Federations',6=>'Regional Union',2=>'District Union/Federations',1=>'Primary Cooperative Societies'];
        	$this->set('type_of_memberships',$type_of_memberships);  
        //End type_of_memberships for dropdown
		$this->loadModel('States');
        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
        $this->set('states',$states); 
		
        $this->set(compact('NationalFederation','national','id'));
	}
	/**
     * Delete method
     *
     * @param string|null $id State id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deleteMember($id = null)
    {
		$this->loadMOdel('NationalFederationsMembers');
        $this->request->allowMethod(['post', 'delete']);
        $NationalFederation=$this->NationalFederationsMembers->get($id);
		$member = $NationalFederation['national_federations_id'];
        if($this->NationalFederationsMembers->delete($NationalFederation)){
            $this->Flash->success(__('The National Federation has been deleted.'));
        }else{
            $this->Flash->error(__('The National Federation could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'member/',$member]);
    }
	
	public function editNational($id){
    
        $NationalFederation = $this->NationalFederations->get($id,['contain'=>['NationalFederationsContactDetails']]);

        $selected_major_activities = [];
        if(!empty($NationalFederation->major_activities_id))
        {
            //$inc = count($NationalFederation->national_federations_contact_details)-1;    

            $selected_major_activities = explode(',',$NationalFederation->major_activities_id);
        }

        $this->set('selected_major_activities',$selected_major_activities);
        
        
        if($this->request->is(['post','put'])){
            $data=$this->request->getData();

            // $MaxCooperativeId=$this->NationalFederations->find('all')->select(['cooperative_id'=>'MAX(cooperative_id)'])->first();
            // $cooperative_id=$MaxCooperativeId->cooperative_id+1;

            //$data['cooperative_id']=$cooperative_id;
            //echo "<pre>";print_r($data['major_activities_id']); exit;
            $data['reference_year']=date('Y');
            $data['date_registration']=date("Y-m-d",strtotime(str_replace("/","-",$data['date_registration'])));

            $national_federations_major_activities=$data['national_federations_major_activities']['major_activities_id'];
            if(!empty($national_federations_major_activities)){
            	unset($data['national_federations_major_activities']);
            	foreach ($national_federations_major_activities as $key=>$national_federations_major_activity_id){
					
					if(!empty($national_federations_major_activity['id'])){
						$data['national_federations_major_activities'][$key]['id']=$national_federations_major_activity['id'];	
					}
					$data['national_federations_major_activities'][$key]['major_activities_id']=$national_federations_major_activity_id;            		
            	}
            }
            if(!empty($data['national_federations_contact_details'])){
                foreach($data['national_federations_contact_details'] as $key => $national_federations_contact_detail){
                    $data['national_federations_contact_details'][$key]['major_activities_id']= implode(',', $national_federations_contact_detail['major_activities_id']);
                }
            }
            $NationalFederation = $this->NationalFederations->patchEntity($NationalFederation,$data,['associated' => ['NationalFederationsContactDetails','NationalFederationsMajorActivities']]);
			$NationalFederation['major_activities_id'] = implode(',',$data['major_activities_id']);
			//echo "<pre>"; print_r($NationalFederation); exit;
            $NationalFederation['user_id'] = $this->request->session()->read('Auth.User.id');
            if($result = $this->NationalFederations->save($NationalFederation)){
                
                $this->Flash->success(__('The Cooperative data has been saved.'));
                return $this->redirect(['action' => 'member',$result['id']]);
            }
            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
        }

        // Start States for dropdown
        $state_code= $this->request->session()->read('Auth.User.state_code');
        $this->loadModel('States');
        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
        $this->set('states',$states);    
        // End States for dropdown
        

        ####### degination dropdown start#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
       $this->set('designations',$designations);
        ###################degination dropdown end ############
		// Start PresentFunctionalStatus for dropdown
        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
		$this->set('presentFunctionalStatus',$presentFunctionalStatus);
        // Start urban_local_bodies type for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$NationalFederation->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown

		$this->loadMOdel('Federation');
		$districtFederation=$this->Federation->find('list',['keyField'=>'id','valueField'=>'federation_name'])->where(['status'=>1,'district_code !='=>0])->order(['federation_name'=>'ASC']);
            
        $this->set('districtFederation',$districtFederation);  

        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$NationalFederation->urban_local_body_type_code,'state_code'=>$NationalFederation->state_code])->order(['local_body_name'=>'ASC']);
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown

		$this->loadModel('SectorOperations');

       $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
       $this->set('sector_operations',$sector_operations);
        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$NationalFederation->urban_local_body_code])->order(['ward_name'=>'ASC']);

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown

        // Start States for dropdown
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$NationalFederation->state_code])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        // End States for dropdown
		
		// Start affiliationWithGovtDept for dropdown
            $this->loadModel('AffiliationWithGovtDept');
            $affiliationWithGovtDept=$this->AffiliationWithGovtDept->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC']);
            $this->set('affiliationWithGovtDept',$affiliationWithGovtDept);
        // End affiliationWithGovtDept for dropdown
        
        // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$NationalFederation->district_code])->order(['name'=>'ASC']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown

        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$NationalFederation->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown



        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$NationalFederation->gram_panchayat_code])->order(['village_name'=>'ASC']); 
        $this->set('villages',$villages);  
        // end villages for dropdown
        
            

        //Start Major Activities for dropdown
	        $this->loadMOdel('MajorActivities');
	        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 

            $this->set('major_activities',$major_activities);
            

        //End Major Activities for dropdown
	    
        //Start Class of Membership for dropdown
        	$class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships);  
        //End Class of Membership for dropdown
        
        //Start type_of_memberships for dropdown
        	$type_of_memberships=[4=>'Multi-State Cooperative Society',3=>'State Federations',2=>'District Union/Federations',1=>'Primary Cooperative Societies'];
        	$this->set('type_of_memberships',$type_of_memberships);  
        //End type_of_memberships for dropdown	

        	


        $this->set(compact('NationalFederation'));

    }
	
	public function viewNational($id = null) 
    {
        $NationalFederation = $this->NationalFederations->get($id,['contain'=>['NationalFederationsContactDetails','NationalFederationsMembers','NationalFederationsMajorActivities']]);
		//echo "<pre>"; print_r($NationalFederation); exit;
        $this->set(compact('NationalFederation'));
        // Start States for dropdown
        $state_code= $this->request->session()->read('Auth.User.state_code');
        $this->loadModel('States');

        if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
        {
            $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
        } else {
            $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$NationalFederation->state_code])->toArray();
        }
		
        $this->set('states',$states);    
        // End States for dropdown
        $this->loadModel('AffiliationWithGovtDept');
		$affiliationWithGovtDept = $this->AffiliationWithGovtDept->find('all')->where([$NationalFederation['affiliation_with_govt_dept']])->order(['id'=>'ASC'])->first();
		$this->set('affiliationWithGovtDept',$affiliationWithGovtDept['name']);
        // End affiliationWithGovtDept for dropdown

        ####### degination dropdown start#######
        $this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1])->toArray();
       $this->set('designations',$designations);
        ###################degination dropdown end ############


        // Start urban_local_bodies type for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$NationalFederation->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown


        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$NationalFederation->urban_local_body_type_code,'state_code'=>$NationalFederation->state_code])->order(['local_body_name'=>'ASC'])->toArray();
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown


        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$NationalFederation->urban_local_body_code])->order(['ward_name'=>'ASC'])->toArray();

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown


        // Start States for dropdown
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$NationalFederation->state_code])->order(['name'=>'ASC'])->toArray();
            $this->set('districts',$districts);
        // End States for dropdown
        
        // Start Blocks for dropdown
        $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$NationalFederation->district_code])->order(['name'=>'ASC'])->toArray();
            $this->set('blocks',$blocks);
        // end Blocks for dropdown

        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$NationalFederation->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();   
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown

        // Start villages for dropdown
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$NationalFederation->gram_panchayat_code])->order(['village_name'=>'ASC'])->toArray(); 
        $this->set('villages',$villages);  
        // end villages for dropdown
        
        //Start Major Activities for dropdown
	        $this->loadMOdel('MajorActivities');
	        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
	        $this->set('major_activities',$major_activities);

        //End Major Activities for dropdown	

	    //Start Class of Membership for dropdown
        	$class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships);  
        //End Class of Membership for dropdown
            
        //Start type_of_memberships for dropdown
        	$type_of_memberships=[1=>'Primary Cooperative Societies',2=>'District Union/Federations',3=>'State Federations',4=>'Multi-State Cooperative Society'];
        	$this->set('type_of_memberships',$type_of_memberships);  
        //End type_of_memberships for dropdown	

        //Start Class of Membership for dropdown
            $class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
            $this->set('class_of_memberships',$class_of_memberships);  
        //End Class of Membership for dropdown
    }

	public function getSocietyDetails(){
        $this->viewBuilder()->setLayout('ajax');
		$registrationQuery = [];
		if($this->request->is('ajax')){
			$this->loadMOdel('CooperativeRegistrations');
			$id = $this->request->data('id');    
			$loc = $this->request->data('loc');    
			if(!empty($id)){ 
				$registrationQuery = $this->CooperativeRegistrations->find('all', [
				'order' => ['created' => 'DESC'],
				'conditions' => ['id'=>$id]
			])->first(); 
				//echo "<pre>"; print_r($registrationQuery['block_code']); exit;
			}
			// Start Blocks for dropdown
			$this->loadMOdel('Blocks');
			$blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['block_code'=>$registrationQuery['block_code']])->order(['name'=>'ASC'])->toArray();
			$this->set('blocks',$blocks);
			$this->loadMOdel('DistrictsBlocksGpVillages');
			$gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['gram_panchayat_code'=>$registrationQuery['gram_panchayat_code']])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray();   
			$this->set('gps',$gps);  
			// end Gram Panchayats for dropdown

			// Start villages for dropdown
			$this->loadMOdel('DistrictsBlocksGpVillages');
			$villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['village_code'=>$registrationQuery['village_code']])->order(['village_name'=>'ASC'])->toArray(); 
			$this->set('villages',$villages); 
			
			$this->loadMOdel('UrbanLocalBodies');
			$localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['localbody_type_code'=>$registrationQuery['urban_local_body_type_code']])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  
			$this->set('localbody_types',$localbody_types);  
			// end urban_local_bodies type for dropdown
			

			// Start urban_local_bodies for dropdown
			$this->loadMOdel('UrbanLocalBodies');
			$localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['localbody_code'=>$registrationQuery['urban_local_body_code']])->order(['local_body_name'=>'ASC'])->toArray();
				
			$this->set('localbodies',$localbodies);  
			// end urban_local_bodies for dropdown


			// Start urban_local_bodies ward for dropdown
				
			$this->loadMOdel('UrbanLocalBodiesWards');
			$localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['ward_code'=>$registrationQuery->locality_ward_code])->order(['ward_name'=>'ASC'])->toArray();

			$this->set('localbodieswards',$localbodieswards); 
		}
		$this->loadModel('Designations');
        $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1])->toArray();
       $this->set('designations',$designations);
		$this->set('registrationQuery',$registrationQuery);  
		$this->set('loc',$loc);  
    }
	public function approved($id = null){
		$this->loadModel('NationalFederationsMembers');
		$nationalF = $this->NationalFederationsMembers->get($id);
		if($this->request->is(['post','put'])){
            $data=$this->request->getData();
			
            $NationalFederation = $this->NationalFederationsMembers->patchEntity($nationalF,$data);
			$NationalFederation['approved_date'] = date("Y-m-d");
			$NationalFederation['approved_by'] = $this->request->session()->read('Auth.User.id');
			//echo "<pre>"; print_r($NationalFederation); exit;
            if($this->NationalFederationsMembers->save($NationalFederation)){
        
                $this->Flash->success(__('The Cooperative member status has been updated.'));
                return $this->redirect(['action' => 'member',$nationalF['national_federations_id']]);
            }  
            $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
		}	
		$this->set(compact('nationalF'));
	} 




}