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
class StateCooperativeBankController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['sectorAddRow','national','addMember','addContact','addNational','getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getSocietyName','getCooperativeSocietyInfo']);
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    private function generateIdentificationNumber($id){
        return $unique_number=str_pad($id, 6, '0', STR_PAD_LEFT); 
    }
    
    /**
     * Add edit
     *
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     */
    public function edit($id=null){ 
		$this->loadModel('NationalFederations'); 
        //$NationalFederation = $this->NationalFederations->get($id,['contain'=>['NationalFederationsContactDetails','NationalFederationsMembers','NationalFederationsMajorActivities']]);
        $NationalFederation = $this->NationalFederations->newEntity();
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
	    // Start States for dropdown
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        // End States for dropdown
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
			$member_id = $this->request->data('member_id');    
			$state_id = $this->request->data('state_id');    
			$district_id = $this->request->data('district_id');    
			$block_id = $this->request->data('block_id');    
			$vill_id = $this->request->data('vill_id');    
			$gram_id = $this->request->data('gram_id');    
			$loc = $this->request->data('loc');    
			if (!empty($member_id)) {
				$search_condition[] = "cooperative_society_type_id = '" . $member_id . "'";
			}
			if (!empty($state_id)) {
				$search_condition[] = "state_code = '" . $state_id . "'";
			}
			if (!empty($district_id)) {
				$search_condition[] = "district_code = '" . $district_id . "'";
			}
			if (!empty($block_id)) {
				$search_condition[] = "block_code = '" . $block_id . "'";
			}
			if (!empty($vill_id)) {
				$search_condition[] = "village_code = '" . $vill_id . "'";
			}
			if (!empty($gram_id)) {
				$search_condition[] = "gram_panchayat_code = '" . $gram_id . "'";
			}
			if (!empty($loc)) {
				$search_condition[] = "location_of_head_quarter = '" . $loc . "'";
			}
			if(!empty($search_condition)){
				$searchString = implode(" AND ",$search_condition);
			} else {
				$searchString = '';
			}
			//echo $searchString; exit;
			$registrationQuery = $this->CooperativeRegistrations->find('all', [
				'order' => ['created' => 'DESC'],
				'conditions' => [$searchString]
			])->where(['is_draft'=>0])->toArray();
			//echo "<pre>"; print_r($registrationQuery); exit;
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
 
		$formAaDetails = [];
		//if($this->request->is('ajax')){
		if (!empty($socity_id)) {
			$this->set('socity_id',$socity_id); 
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
        //End Class of Membership for dropdown
        
        

	}
	
	public function add(){
		$this->loadMOdel('NationalFederations');
        $NationalFederation = $this->NationalFederations->newEntity();
        if($this->request->is('post')){
            $data=$this->request->getData();

            // echo '<pre>';
            // print_r($data);die;
            $MaxCooperativeId=$this->NationalFederations->find('all')->select(['cooperative_id'=>'MAX(id)'])->first();
            $cooperative_id=(int)$MaxCooperativeId->cooperative_id+1;
            $data['cooperative_id']=$cooperative_id;
            $data['identification_number']=$this->generateIdentificationNumber($cooperative_id);
            
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
			$data['is_new']=1;
            $data['created_by'] = $this->request->session()->read('Auth.User.id');
            $NationalFederation = $this->NationalFederations->patchEntity($NationalFederation,$data,['associated' => ['NationalFederationsContactDetails','NationalFederationsMajorActivities']]);
			//echo "<pre>"; print_r($NationalFederation); exit;
            
            if($result = $this->NationalFederations->save($NationalFederation)){
                
                $this->Flash->success(__('The Cooperative data has been saved.'));
                return $this->redirect(['action' => 'add-member',$result['id']]);
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
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC']);
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

            $NationalFederation->national_federations_id = $id;
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
		$this->set('PrimaryActivities',$pactivities);
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
        	$type_of_memberships=[5=>'Institutional Membership',4=>'Multi-State Cooperative Society',3=>'State Federations',6=>'Regional',2=>'District Union/Federations',1=>'Primary Cooperative Societies'];
        	$this->set('type_of_memberships',$type_of_memberships);  
        //End type_of_memberships for dropdown

        $this->set(compact('NationalFederation','national','id'));

    }
	
	/**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
		$this->loadMOdel('DistrictCentralCooperativeBank');
		$this->loadMOdel('Districts');
		$this->loadMOdel('States');
        $search_condition = array();

        $data = $this->request->getData();

        if(isset($this->request->query['page_length'])){
            $page_length = $this->request->query['page_length'];
        } else {
            $page_length = 20;
        }

        if(!empty($this->request->query['page'])){
            $this->set('page', $this->request->query['page']);
            $page = $this->request->query['page'];
        }else{
            $page = 1;
        }

         if(!empty($this->request->query['dccb_name'])) {
            $dccb_name = trim($this->request->query['dccb_name']);

            echo $dccb_name;
            $this->set('dccb_name', $dccb_name);
            $search_condition[] = "dccb_name like '%" . $dccb_name . "%'";
        }

       
        if (!empty($this->request->query['branch_code'])) {
            $branch_code = $this->request->query['branch_code'];
            $this->set('branch_code', $branch_code);
            $search_condition[] = "branch_code like '%" . $branch_code . "%'";
        }

        if (!empty($this->request->query['state_code']) ) {
            $state_code = $this->request->query['state_code'];
            $this->set('state_code', $state_code);
            $search_condition[] = "state_code = '" . $state_code . "'";

            $districtlist=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();

            $this->set(compact('districtlist'));
        }

        if (!empty($this->request->query['district_code'])) {
            $district_code = trim(!empty($this->request->query['district_code']));

            $districtlist=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
            $this->set(compact('districtlist'));

            $this->set('district_code', $district_code);
            $search_condition[] = "district_code = '" . $district_code . "'";
        }

        if ($this->request->query['status']!='') {
            $s_status = trim($this->request->query['status']);
            $this->set('s_status', $s_status);
            $search_condition[] = "status = '" . $s_status . "'";
        }
		if ($this->request->query['entity_type']!='') {
            $entity_type = trim($this->request->query['entity_type']);
            $this->set('entity_type', $entity_type);
            $search_condition[] = "entity_type = '" . $entity_type . "'";
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
		$userRole= $this->request->session()->read('Auth.User.role_id');
		if($userRole == 2){
			$auditcategories = $this->DistrictCentralCooperativeBank->find('all', [
            'order' => ['created_at'=>'DESC'],
            'conditions' => [$searchString,'entity_type'=>'STCB']
        ]);
		}
		else{
			$state_code= $this->request->session()->read('Auth.User.state_code');
			$auditcategories = $this->DistrictCentralCooperativeBank->find('all', [
            'order' => ['created_at'=>'DESC'],
            'conditions' => [$searchString,'state_code'=>$state_code,'entity_type'=>'STCB']
        ]);
		}
        
        
        //->find('All')->order(['sector_of_operation'=>'ASC'])->order(['orderseq'=>'ASC']);

        $this->paginate = ['limit' => 20];

        $auditcategories = $this->paginate($auditcategories);

        $status = ['1'=>'Active','0'=>'Inactive'];

        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
        $this->set(compact('auditcategories','status','districts','states'));
    }
	/**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function member($id)
    {

        $this->loadMOdel('DistrictCentralCooperativeBank');
		$this->loadMOdel('Districts');
		$this->loadMOdel('States');
        $search_condition = array();

        $data = $this->request->getData();

        if(isset($this->request->query['page_length'])){
            $page_length = $this->request->query['page_length'];
        } else {
            $page_length = 20;
        }

        if(!empty($this->request->query['page'])){
            $this->set('page', $this->request->query['page']);
            $page = $this->request->query['page'];
        }else{
            $page = 1;
        }

         if(!empty($this->request->query['dccb_name'])) {
            $dccb_name = trim($this->request->query['dccb_name']);

            echo $dccb_name;
            $this->set('dccb_name', $dccb_name);
            $search_condition[] = "dccb_name like '%" . $dccb_name . "%'";
        }

       
        if (!empty($this->request->query['branch_code'])) {
            $branch_code = $this->request->query['branch_code'];
            $this->set('branch_code', $branch_code);
            $search_condition[] = "branch_code like '%" . $branch_code . "%'";
        }

        if (!empty($this->request->query['state_code']) ) {
            $state_code = $this->request->query['state_code'];
            $this->set('state_code', $state_code);
            $search_condition[] = "state_code = '" . $state_code . "'";

            $districtlist=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();

            $this->set(compact('districtlist'));
        }

        if (!empty($this->request->query['district_code'])) {
            $district_code = trim(!empty($this->request->query['district_code']));

            $districtlist=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
            $this->set(compact('districtlist'));

            $this->set('district_code', $district_code);
            $search_condition[] = "district_code = '" . $district_code . "'";
        }

        if ($this->request->query['status']!='') {
            $s_status = trim($this->request->query['status']);
            $this->set('s_status', $s_status);
            $search_condition[] = "status = '" . $s_status . "'";
        }
		if ($this->request->query['entity_type']!='') {
            $entity_type = trim($this->request->query['entity_type']);
            $this->set('entity_type', $entity_type);
            $search_condition[] = "entity_type = '" . $entity_type . "'";
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
			$auditcategories = $this->DistrictCentralCooperativeBank->find('all', [
            'order' => ['created_at'=>'DESC'],
            'conditions' => [$searchString,'state_code'=>$id,'district_code !=' =>0]
        ]);
        
        
        //->find('All')->order(['sector_of_operation'=>'ASC'])->order(['orderseq'=>'ASC']);

        $this->paginate = ['limit' => 20];

        $auditcategories = $this->paginate($auditcategories);

        $status = ['1'=>'Active','0'=>'Inactive'];

        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toarray();
        $this->set(compact('auditcategories','status','districts','states'));

        
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
        if($this->request->is(['post','put'])){
            $data=$this->request->getData();
            $NationalFederation = $this->NationalFederationsMembers->patchEntity($NationalFederation,$data);
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
        	$type_of_memberships=[5=>'Institutional Membership',4=>'Multi-State Cooperative Society',3=>'State Federations',6=>'Regional',2=>'District Union/Federations',1=>'Primary Cooperative Societies'];
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
        	$type_of_memberships=[5=>'Institutional Membership',4=>'Multi-State Cooperative Society',3=>'State Federations',6=>'Regional',2=>'District Union/Federations',1=>'Primary Cooperative Societies'];
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
        	$type_of_memberships=[5=>'Institutional Membership',4=>'Multi-State Cooperative Society',3=>'State Federations',6=>'Regional',2=>'District Union/Federations',1=>'Primary Cooperative Societies'];
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
        if(!empty($NationalFederation->national_federations_contact_details))
        {
            $inc = count($NationalFederation->national_federations_contact_details)-1;    

            $selected_major_activities = explode(',',$NationalFederation->national_federations_contact_details[$inc]->major_activities_id);
        }

        $this->set('selected_major_activities',$selected_major_activities);
        
        
        if($this->request->is(['post','put'])){
            $data=$this->request->getData();

            // $MaxCooperativeId=$this->NationalFederations->find('all')->select(['cooperative_id'=>'MAX(cooperative_id)'])->first();
            // $cooperative_id=$MaxCooperativeId->cooperative_id+1;

            //$data['cooperative_id']=$cooperative_id;
            
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
            if(!empty($data['national_federations_contact_details'])){
                foreach($data['national_federations_contact_details'] as $key => $national_federations_contact_detail){
                    $data['national_federations_contact_details'][$key]['major_activities_id']= implode(',', $national_federations_contact_detail['major_activities_id']);
                }
            }
            $NationalFederation = $this->NationalFederations->patchEntity($NationalFederation,$data,['associated' => ['NationalFederationsContactDetails','NationalFederationsMajorActivities']]);
			
            
            if($result = $this->NationalFederations->save($NationalFederation)){
                
                $this->Flash->success(__('The Cooperative data has been saved.'));
                return $this->redirect(['action' => 'add-member',$result['id']]);
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
        $NationalFederation = $this->NationalFederations->get($id,['contain'=>['NationalFederationsContactDetails','NationalFederationsMembers']]);
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





}