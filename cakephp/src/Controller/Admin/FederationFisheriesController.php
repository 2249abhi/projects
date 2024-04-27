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
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 *
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FederationFisheriesController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['index','edit','view']);
    }

    // public function index(){

    //     // echo "woor";die;

    //     // $this->loadMOdel('AnimalHusbandries');
    //     $this->loadMOdel('States');
    //     $this->loadMOdel('Districts');
    //     $this->loadModel('Designations');
       
    //     // $this->loadMOdel('Blocks');
    //     // $this->loadMOdel('VillagesSubDistricts');

    //     $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
    //     $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$NationalFederation->state_code])->order(['name'=>'ASC']);
    //     $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1])->toArray();

    //     $this->loadModel('SectorOperations');
    //     $this->loadModel('PrimaryActivities');
    //     $this->loadModel('CooperativeSocietyTypes');
    //     $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1,'id'=>2])->toArray(); 
    //     $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>2,'status'=>1,'name'=>'Fishery Cooperative'])->order(['orderseq'=>'ASC'])->toArray();
    //     $CooperativeSocietyTypes=$this->CooperativeSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC']);
    //     // $search_condition = [];
    //     // $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
        
    //     // // $animal_husbandries = $this->paginate($this->AnimalHusbandries->find('all')->limit(20));
    
    //     // $states = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       
        

    //     // $search_condition = [];
    //     // if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
    //     //     $state = trim($this->request->query['state']);
    //     //     $this->set('state', $state);
    //     //     $search_condition[] = "state_code = '" . $state . "'";

    //     //     $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
    //     // }

    //     // if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
    //     //     $district = trim($this->request->query['district']);
    //     //     $this->set('district', $district);
    //     //     $search_condition[] = "district_code = '" . $district . "'";

    //     //     $blocks = $this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1])->where(['district_code'=>$this->request->query['district']])->order(['name'=>'ASC'])->toArray();
    //     // }

    //     // if (isset($this->request->query['block']) && $this->request->query['block'] !='') {
    //     //     $block = trim($this->request->query['block']);
    //     //     $this->set('block', $block);
    //     //     $search_condition[] = "block_code = '" . $block . "'";
    //     // }

        

    //     // if(!empty($search_condition)){
    //     //     $searchString = implode(" AND ",$search_condition);
    //     // }
    //     //  else {
    //     //     $searchString = '';
    //     // }
    
    //     // $query = $this->AnimalHusbandries->find('all'
    //     //     // 'order' => ['created' => 'DESC'],
           
    //     // )->select(['state_name','state_code','district_name','district_code','block_name','block_code','village_name','village_code','under_one_year_cattle','one_to_two_and_half_years','	in_milk_cattle','dry_cattle','not_calved_once_cattle','others_female_cattle','upto_one_and_half_years_cattle','used_for_breeding_only_cattle','agriculture_and_breeding_cattle','used_for_agriculture_only_cattle','bulluck_cart_farm_operation_cattle','others_male_cattle','total_cattle'])->where([$searchString]);
    //     // $this->paginate = ['limit' => 20];
    //     // $animal_husbandries = $this->paginate($query);

    

       
    // //    $this->set(compact('animal_husbandries','page', 'states','searchString','districts','blocks'));
    // $this->set(compact('states','districts','designations','sector_operations','PrimaryActivities','CooperativeSocietyTypes'));

    // }

    // public function index()
    // {
    
    //     $CooperativeRegistration = $this->CooperativeRegistrations->newEntity();
    //     $years = [];
    //     $l_year = date('Y')-122;

    //     for($i=date('Y'); $i>=$l_year; $i--)
    //     {
    //         $years[$i] = $i;
    //     }
    //     if($this->request->is('post')){

    //         if($this->request->session()->read('Auth.User.role_id') != 7)
    //         {
    //             return $this->redirect(['action' => 'add']);$this->Flash->error(__('You are not Authorize to add Cooperative Registration'));
    //         }
    //         $data=$this->request->getData();

    //         $data['is_approved'] = 0;
    //         $data['remark'] = '';
    //         $data['approved_by'] = '';
    //         $data['status'] = 1;
    //         $data['created'] = date('Y-m-d H:i:s');
	// 		$data['operational_district_code'] = $this->request->session()->read('Auth.User.district_code');
    //         if($this->request->session()->read('Auth.User.role_id') == 2)
    //         {
    //             $data['is_approved'] = 1;
    //             $data['approved_by'] = $this->request->session()->read('Auth.User.id');
    //         }
    //         // echo '<pre>';
    //         //  print_r($data['sector']);die;
	// 		//add his district based on user if urban
	// 		if(($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8) && $data['location_of_head_quarter'] == 1)
    //         {
    //             $data['district_code'] = $this->request->session()->read('Auth.User.district_code');
    //         }
			
    //         if(!empty($data['landline']))
    //         {
    //             $std = $data['std'];
    //             $landline = $data['landline'];
    //             $data['landline'] = $std.'-'.$landline;
    //         } else {
    //             unset($data['std']);
    //         }
    //         $data['created_by'] = $this->request->session()->read('Auth.User.id');
    //         ###################  ganrate unique d ##########################
    //          // $MaxCooperativeIdA =$this->CooperativeRegistrations->find('all')->select(['cooperative_id_num'=>'MAX(cooperative_id_num)'])->order(['cooperative_id_num'=>'DESC'])->first();               
    //          //    $cooperative_id_num=($MaxCooperativeIdA->cooperative_id_num + 1);
    //          //    $data['cooperative_id_num']=$cooperative_id_num;

    //          if($data['is_draft'] == 0)
    //        {
    //            $cooperative_id_num= $this->generateUniqueNumber(); 
    //            $data['cooperative_id_num'] = $cooperative_id_num;
    //         }

    //         //add his district based on user if urban
    //         if(($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8) && $data['location_of_head_quarter'] == 1)
    //         {
    //             $data['district_code'] = $this->request->session()->read('Auth.User.district_code');
    //         }
            
    //         $data['operational_district_code'] = $this->request->session()->read('Auth.User.district_code');


    //         if($data['location_of_head_quarter']==2 && !empty($data['state_code']) && !empty($data['district_code']) && !empty($data['block_code']) && !empty($data['gram_panchayat_code']) && !empty($data['village_code'])){

    //             $cooperative_id=(int)$data['state_code']+(int)$data['district_code']+(int)$data['block_code']+(int)$data['gram_panchayat_code']+(int)$data['village_code'];
    //             $MaxCooperativeId=$this->CooperativeRegistrations->find('all')->select(['id'=>'MAX(id)'])->first();
    //             $cooperative_id=$cooperative_id.($MaxCooperativeId->id+1);
    //             $data['cooperative_id']=$cooperative_id;
    //         }elseif($data['location_of_head_quarter']==1 && !empty($data['state_code']) && !empty($data['urban_local_body_type_code']) && !empty($data['urban_local_body_code'])){

    //             $cooperative_id=(int)$data['state_code']+(int)$data['urban_local_body_type_code']+(int)$data['urban_local_body_code']+(int)$data['locality_ward_code'];

    //             $MaxCooperativeId=$this->CooperativeRegistrations->find('all')->select(['id'=>'MAX(id)'])->first();
    //             $cooperative_id=$cooperative_id.($MaxCooperativeId->id+1);
    //             $data['cooperative_id']=$cooperative_id;

               

    //         }
            
			
    //         $multi_states=$data['multi_states'];

    //         if(empty($data['multi_states']))
    //         {
    //             $multi_states = [];
    //         }
    //         //unset($data['multi_states']);

    //         $crcns=$data['cooperative_registrations_contact_numbers'];
    //         //unset($data['cooperative_registrations_contact_numbers']);

            
    //         $cres=$data['cooperative_registrations_emails'];
    //         //unset($data['cooperative_registrations_emails']);

    //         if(count($multi_states)>1){
    //            $data['is_multi_state']=1; 
    //         }

    //         $data['reference_year']=date('Y');
    //         $data['date_registration'] = date("Y-m-d",strtotime(str_replace("/","-",$data['date_registration'])));

    //         // echo '<pre>';
    //         // print_r($data);die;

    //         $CooperativeRegistration = $this->CooperativeRegistrations->patchEntity($CooperativeRegistration, $data);
            
    //         if($this->CooperativeRegistrations->save($CooperativeRegistration)) {

    //             //Cooperative Registrations pacs data
    //          if($data['sector_of_operation'] == 1 || $data['sector_of_operation'] == 20 || $data['sector_of_operation'] == 22)
    //          {
    //              $this->loadModel('CooperativeRegistrationPacs');
    //              $data['pacs']['cooperative_registrations_id'] = $CooperativeRegistration->id;

    //             //  echo '<pre>';
    //             //  print_r($data['pacs']);die;
    //              $CooperativeRegistrationPac = $this->CooperativeRegistrationPacs->newEntity();
                 
    //              $CooperativeRegistrationPac = $this->CooperativeRegistrationPacs->patchEntity($CooperativeRegistrationPac, $data['pacs']);
    //              $this->CooperativeRegistrationPacs->save($CooperativeRegistrationPac);     
    //          }

    //          //Cooperative Registrations Dairy data
    //          if($data['sector_of_operation'] == 9)
    //          {
    //              $this->loadModel('CooperativeRegistrationDairy');
    //              $data['dairy']['cooperative_registrations_id'] = $CooperativeRegistration->id;

    //             //  echo '<pre>';
    //             //  print_r($data['pacs']);die;
    //              $dairy = $this->CooperativeRegistrationDairy->newEntity();
                 
    //              $dairy = $this->CooperativeRegistrationDairy->patchEntity($dairy, $data['dairy']);
    //              $this->CooperativeRegistrationDairy->save($dairy);     
    //          }

    //          //Cooperative Registrations Fishery data
    //          if($data['sector_of_operation'] == 10)
    //          {
    //              $this->loadModel('CooperativeRegistrationFishery');
    //              $data['fishery']['cooperative_registrations_id'] = $CooperativeRegistration->id;

    //              $fishery = $this->CooperativeRegistrationFishery->newEntity();
                 
    //              $fishery = $this->CooperativeRegistrationFishery->patchEntity($fishery, $data['fishery']);
    //              $this->CooperativeRegistrationFishery->save($fishery);     
    //          }
                
    //          //save land
    //          if(isset($data['area']) && !empty($data['area']))
    //          {
    //              $this->loadModel('CooperativeRegistrationsLands');
    //              //$this->AreaOfOperationLevel->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                 
                 
    //              $data['area']['cooperative_registration_id'] = $CooperativeRegistration->id;
    //              $CooperativeRegistrationsLands = $this->CooperativeRegistrationsLands->newEntity();
                 
    //              $CooperativeRegistrationsLands = $this->CooperativeRegistrationsLands->patchEntity($CooperativeRegistrationsLands, $data['area']);

    //              $this->CooperativeRegistrationsLands->save($CooperativeRegistrationsLands);
    //          }

    //          //Rural & both
    //          $arr_village_sector = [2,3];

    //          if(isset($data['sector']) && !empty($data['sector']) && in_array($data['operation_area_location'],$arr_village_sector))
    //          {
    //              $this->loadModel('AreaOfOperationLevel');
    //              $this->loadMOdel('DistrictsBlocksGpVillages');
    //              //$this->AreaOfOperationLevel->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                 
    //              foreach($data['sector'] as $s_key => $sector)
    //              {
    //                  if($sector['panchayat_code'] == -1){

    //                      $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$sector['block_code']])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray(); 

    //                              if(isset($gps) && !empty($gps))
    //                              {
    //                                  foreach($gps as $gp_code => $gp_name)
    //                                  {
    //                                      $gp_villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$gp_code])->order(['village_name'=>'ASC'])->toArray();

    //                                      if(isset($gp_villages) && !empty($gp_villages))
    //                                      {
    //                                          foreach ($gp_villages as $gp_village_code => $gp_village_name) {

    //                                              $gp_areaOfOperationLevel = $this->AreaOfOperationLevel->newEntity();
    //                                              $bulk_gp_data = [
    //                                                  'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
    //                                                  'row_id'                        =>  $s_key,
    //                                                  'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
    //                                                  'state_code'                    =>  $data['state_code'],
    //                                                  'district_code'                 =>  $sector['district_code'],
    //                                                  'block_code'                    =>  $sector['block_code'],
    //                                                  'panchayat_code'                =>  $gp_code,
    //                                                  'village_code'                  =>  $gp_village_code,
    //                                                  'gp_village_all'                =>  1
    //                                              ];

    //                                              $gp_areaOfOperationLevel = $this->AreaOfOperationLevel->patchEntity($gp_areaOfOperationLevel, $bulk_gp_data);

    //                                              $this->AreaOfOperationLevel->save($gp_areaOfOperationLevel);
    //                                          }
    //                                      }
    //                                  }
    //                              }

    //                  } else if($sector['village_code'][0] == -1) {

    //                      $all_villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$sector['panchayat_code']])->order(['village_name'=>'ASC'])->toArray();

    //                      if(isset($all_villages) && !empty($all_villages))
    //                      {
    //                          foreach ($all_villages as $all_village_code => $all_village_name) {

    //                              $village_areaOfOperationLevel = $this->AreaOfOperationLevel->newEntity();
    //                              $bulk_village_data = [
    //                                  'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
    //                                  'row_id'                        =>  $s_key,
    //                                  'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
    //                                  'state_code'                    =>  $data['state_code'],
    //                                  'district_code'                 =>  $sector['district_code'],
    //                                  'block_code'                    =>  $sector['block_code'],
    //                                  'panchayat_code'                =>  $sector['panchayat_code'],
    //                                  'village_code'                  =>  $all_village_code,
    //                                  'gp_village_all'                =>  2
    //                              ];

    //                              $village_areaOfOperationLevel = $this->AreaOfOperationLevel->patchEntity($village_areaOfOperationLevel, $bulk_village_data);

    //                              $this->AreaOfOperationLevel->save($village_areaOfOperationLevel);
    //                          }
    //                      }
                         
    //                  } else {
    //                      $villages = $sector['village_code'];

    //                      foreach($villages as $v_key => $village_code)
    //                      {
    //                          if($village_code != '-1')
    //                          {
    //                              $AreaOfOperationLevel = $this->AreaOfOperationLevel->newEntity();
    //                              $area_data = [
    //                                  'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
    //                                  'row_id'                        =>  $s_key,
    //                                  'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
    //                                  'state_code'                    =>  $data['state_code'],
    //                                  'district_code'                 =>  $sector['district_code'],
    //                                  'block_code'                    =>  $sector['block_code'],
    //                                  'panchayat_code'                =>  $sector['panchayat_code'],
    //                                  'village_code'                  =>  $village_code,
    //                                  'gp_village_all'                =>  0
    //                              ];
    //                              $AreaOfOperationLevel = $this->AreaOfOperationLevel->patchEntity($AreaOfOperationLevel, $area_data);

    //                              $this->AreaOfOperationLevel->save($AreaOfOperationLevel);
    //                          }
                             
    //                      }
    //                  }
    //              }  

    //          }

    //             //Urban & both
    //             $arr_ward_sector = [1,3];

    //             if(isset($data['sector_urban']) && !empty($data['sector_urban'])   && in_array($data['operation_area_location'],$arr_ward_sector))
    //             {
    //                 $this->loadModel('AreaOfOperationLevelUrban');
    //                 //$this->AreaOfOperationLevel->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                    
    //                 foreach($data['sector_urban'] as $s_key => $sector)
    //                 {
    //                     $wards = $sector['locality_ward_code'];
    //                     foreach($wards as $v_key => $ward_code)
    //                     {
                           
    //                         $AreaOfOperationLevelUrban = $this->AreaOfOperationLevelUrban->newEntity();
    //                         $area_data = [
    //                             'cooperative_registrations_id'  =>  $CooperativeRegistration->id,
    //                             'row_id'                        =>  $s_key,
    //                             'area_of_operation_id'          =>  $data['area_of_operation_id_urban'],
    //                             'state_code'                    =>  $data['state_code'],
    //                             'district_code'                 =>  $sector['district_code'],
    //                             'local_body_type_code'          =>  $sector['local_body_type_code'],
    //                             'local_body_code'               =>  $sector['local_body_code'],
    //                             'locality_ward_code'            =>  $ward_code
    //                         ];
    //                         $AreaOfOperationLevelUrban = $this->AreaOfOperationLevelUrban->patchEntity($AreaOfOperationLevelUrban, $area_data);

    //                         $this->AreaOfOperationLevelUrban->save($AreaOfOperationLevelUrban);
    //                     }
    //                 }  
    //             }
                

    //             if(!empty($multi_states)){
    //                 $this->loadModel('CooperativeRegistrationsMultiStates');
    //                 $this->CooperativeRegistrationsMultiStates->deleteAll(['cooperative_registration_id'=>$CooperativeRegistration->id]);
    //                 foreach($multi_states as $key=>$state_id){
    //                     $CRMS=$this->CooperativeRegistrationsMultiStates->newEntity();
    //                     $CRMS=$this->CooperativeRegistrationsMultiStates->patchEntity($CRMS,[]);
    //                     $CRMS->cooperative_registration_id=$CooperativeRegistration->id;
    //                     $CRMS->state_id=$state_id;
    //                     $this->CooperativeRegistrationsMultiStates->save($CRMS);
    //                 }
    //             }

               

    //             $this->Flash->success(__('The Cooperative data has been saved.'));

    //             if($this->request->session()->read('Auth.User.role_id') == 7)
    //             {
    //                 if($data['is_draft'] == 1)
    //                 {
    //                     return $this->redirect(['action' => 'draft']);    
    //                 } else {
    //                     return $this->redirect(['action' => 'dataEntryPending']);    
    //                 }
                    
    //             }
    //             return $this->redirect(['action' => 'index']);
    //         }
    //         $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
    //     }

    //     // Start States for dropdown
    //         $state_code= $this->request->session()->read('Auth.User.state_code');
    //         $this->loadModel('States');

    //         if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
    //         {
    //             $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
    //         } else {
    //             $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code]);
    //         }
            
    //     // End States for dropdown
    //     $this->set('states',$states);

    //     ####### degination dropdown#######
    //     $this->loadModel('Designations');
    //     $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
    //    $this->set('designations',$designations);
    //     ###################end ############


    //     // Start States for dropdown
    //         $this->loadModel('Districts');
    //         $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']);
    //         $this->set('districts',$districts);
    //     // End States for dropdown
        
    //     //sector district level
    //     // if($data['area_of_operation_id'] == 3)
    //     // {
    //     //     $sector_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$CooperativeRegistration->sector['state_code']])->order(['name'=>'ASC']);
    //     //     $this->set('sector_districts',$sector_districts);
    //     // }

    //     // Start Blocks for dropdown
    //     $this->loadMOdel('Blocks');
    //         $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$CooperativeRegistration->district_code])->order(['name'=>'ASC']);
    //         $this->set('blocks',$blocks);
    //     // end Blocks for dropdown


    //     // Start Gram Panchayats for dropdown
            
    //     $this->loadMOdel('DistrictsBlocksGpVillages');
    //     $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$CooperativeRegistration->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
    //     $this->set('gps',$gps);  
    //     // end Gram Panchayats for dropdown



    //     // Start villages for dropdown
            
    //     $this->loadMOdel('DistrictsBlocksGpVillages');
    //     $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$CooperativeRegistration->gram_panchayat_code])->order(['village_name'=>'ASC']); 
    //     $this->set('villages',$villages);  
    //     // end villages for dropdown
        



    //     // Start CooperativeSocietyTypes for dropdown
    //         $this->loadModel('CooperativeSocietyTypes');
    //         $CooperativeSocietyTypes=$this->CooperativeSocietyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC']);
    //     // End CooperativeSocietyTypes for dropdown
    
    //     // Start areaOfOperations for dropdown
    //         $this->loadModel('AreaOfOperations');
    //         $areaOfOperationsUrban=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'urban_rural'=>1])->order(['orderseq'=>'ASC']);
    //         $areaOfOperationsRural=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'urban_rural'=>2])->order(['orderseq'=>'ASC']);
    //         //$areaOfOperations=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
    //     // End areaOfOperations for dropdown
        

    //     // Start PrimaryActivities for dropdown
    //         $this->loadModel('PrimaryActivities');
    //         $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$CooperativeRegistration->sector_of_operation_type,'status'=>1])->order(['orderseq'=>'ASC']);

            
    //     // End PrimaryActivities for dropdown
            
    //     // Start SecondaryActivities for dropdown
    //         $this->loadModel('SecondaryActivities');
    //         $SecondaryActivities=$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'sector_of_operation'=>$CooperativeRegistration->sector_of_operation_type])->order(['orderseq'=>'ASC']);

    //     // End SecondaryActivities for dropdown
        
    //     // Start PresentFunctionalStatus for dropdown
    //         $this->loadModel('PresentFunctionalStatus');
    //         $PresentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

    //     // End PresentFunctionalStatus for dropdown

        
    //     // Start urban_local_bodies type for dropdown
            
    //     $this->loadMOdel('UrbanLocalBodies');
    //     $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$CooperativeRegistration->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
    //     $this->set('localbody_types',$localbody_types);  
    //     // end urban_local_bodies type for dropdown


    //     // Start urban_local_bodies for dropdown
    //     $this->loadMOdel('UrbanLocalBodies');
    //     $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$CooperativeRegistration->urban_local_body_type_code,'state_code'=>$CooperativeRegistration->state_code])->order(['local_body_name'=>'ASC']);
            
    //     $this->set('localbodies',$localbodies);  
    //     // end urban_local_bodies for dropdown


    //     // Start urban_local_bodies ward for dropdown
            
    //     $this->loadMOdel('UrbanLocalBodiesWards');
    //     $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$CooperativeRegistration->urban_local_body_code])->order(['ward_name'=>'ASC']);

    //     $this->set('localbodieswards',$localbodieswards);  
    //     // end urban_local_bodies for dropdown

    //     $this->loadModel('SectorOperations');

    //    $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
    
    //    $this->set('sector_operations',$sector_operations); 

    //          //  Start water_body_type for dropdown
    //         $this->loadModel('WaterBodyTypes');
    //         $water_body_type=$this->WaterBodyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,])->order(['id'=>'ASC']);
    //         $this->set('water_body_type',$water_body_type);
    //     // End water_body_type for dropdown


    //           //  Start register_authorty dropdown registration_authorities
    //         $this->loadModel('RegistrationAuthorities');
    //         $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['authority_name'=>'ASC']);
    //         $this->set('register_authorities',$register_authorities);
    //     // End registration_authorities for dropdown

    //     $this->set(compact('CooperativeRegistration','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','years','state_code'));

        
        
    // }

    public function index()
    {
        $this->loadMOdel('CooperativeFederations');
        $CooperativeFederation = $this->CooperativeFederations->newEntity();
        $years = [];
        $l_year = date('Y')-122;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
        }
        // if($this->request->is('post')){

        //     // if($this->request->session()->read('Auth.User.role_id') != 7)
        //     // {
        //     //     return $this->redirect(['action' => 'add']);$this->Flash->error(__('You are not Authorize to add Cooperative Registration'));
        //     // }
        //     $data=$this->request->getData();

           
        //     $data['status'] = 1;
        //     $data['created'] = date('Y-m-d H:i:s');
		// 	if(($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8) && $data['location_of_head_quarter'] == 1)
        //     {
        //         $data['district_code'] = $this->request->session()->read('Auth.User.district_code');
        //     }
		// 	$CooperativeFederation = $this->CooperativeFederations->patchEntity($CooperativeFederation, $data);
            
        //     if($this->CooperativeFederations->save($CooperativeFederation)) {
        //     //Cooperative Registrations Dairy data
        //      if($data['sector_of_operation'] == 9)
        //      {
        //          $this->loadModel('CooperativeFederationDairy');
        //          $data['cooperative_federations_id'] = $CooperativeFederation->id;

        //         //  echo '<pre>';
        //         //  print_r($data['pacs']);die;
        //          $dairy = $this->CooperativeFederationDairy->newEntity();
                 
        //          $dairy = $this->CooperativeFederationDairy->patchEntity($dairy, $data);
        //          $this->CooperativeFederationDairy->save($dairy);     
        //      }
        //      //Rural & both
        //      $arr_village_sector = [2,3];

        //      if(isset($data['sector']) && !empty($data['sector']) && in_array($data['operation_area_location'],$arr_village_sector))
        //      {
        //          $this->loadModel('CooperativeFederationAreaOperationRural');
        //          $this->loadMOdel('DistrictsBlocksGpVillages');
        //          //$this->AreaOfOperationLevel->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                 
        //          foreach($data['sector'] as $s_key => $sector)
        //          {
        //              if($sector['panchayat_code'] == -1){

        //                  $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$sector['block_code']])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->toArray(); 

        //                          if(isset($gps) && !empty($gps))
        //                          {
        //                              foreach($gps as $gp_code => $gp_name)
        //                              {
        //                                  $gp_villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$gp_code])->order(['village_name'=>'ASC'])->toArray();

        //                                  if(isset($gp_villages) && !empty($gp_villages))
        //                                  {
        //                                      foreach ($gp_villages as $gp_village_code => $gp_village_name) {

        //                                          $gp_areaOfOperationLevel = $this->CooperativeFederationAreaOperationRural->newEntity();
        //                                          $bulk_gp_data = [
        //                                              'cooperative_federations_id'  =>  $CooperativeFederation->id,
        //                                              'row_id'                        =>  $s_key,
        //                                              'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
        //                                              'state_code'                    =>  $data['state_code'],
        //                                              'district_code'                 =>  $sector['district_code'],
        //                                              'block_code'                    =>  $sector['block_code'],
        //                                              'panchayat_code'                =>  $gp_code,
        //                                              'village_code'                  =>  $gp_village_code,
        //                                              'gp_village_all'                =>  1
        //                                          ];

        //                                          $gp_areaOfOperationLevel = $this->CooperativeFederationAreaOperationRural->patchEntity($gp_areaOfOperationLevel, $bulk_gp_data);

        //                                          $this->CooperativeFederationAreaOperationRural->save($gp_areaOfOperationLevel);
        //                                      }
        //                                  }
        //                              }
        //                          }

        //              } else if($sector['village_code'][0] == -1) {

        //                  $all_villages = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$sector['panchayat_code']])->order(['village_name'=>'ASC'])->toArray();

        //                  if(isset($all_villages) && !empty($all_villages))
        //                  {
        //                      foreach ($all_villages as $all_village_code => $all_village_name) {

        //                          $village_areaOfOperationLevel = $this->CooperativeFederationAreaOperationRural->newEntity();
        //                          $bulk_village_data = [
        //                              'cooperative_federations_id'  =>  $CooperativeFederation->id,
        //                              'row_id'                        =>  $s_key,
        //                              'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
        //                              'state_code'                    =>  $data['state_code'],
        //                              'district_code'                 =>  $sector['district_code'],
        //                              'block_code'                    =>  $sector['block_code'],
        //                              'panchayat_code'                =>  $sector['panchayat_code'],
        //                              'village_code'                  =>  $all_village_code,
        //                              'gp_village_all'                =>  2
        //                          ];

        //                          $village_areaOfOperationLevel = $this->CooperativeFederationAreaOperationRural->patchEntity($village_areaOfOperationLevel, $bulk_village_data);

        //                          $this->CooperativeFederationAreaOperationRural->save($village_areaOfOperationLevel);
        //                      }
        //                  }
                         
        //              } else {
        //                  $villages = $sector['village_code'];

        //                  foreach($villages as $v_key => $village_code)
        //                  {
        //                      if($village_code != '-1')
        //                      {
        //                          $AreaOfOperationLevel = $this->CooperativeFederationAreaOperationRural->newEntity();
        //                          $area_data = [
        //                              'cooperative_federations_id'  =>  $CooperativeFederation->id,
        //                              'row_id'                        =>  $s_key,
        //                              'area_of_operation_id'          =>  $data['area_of_operation_id_rural'],
        //                              'state_code'                    =>  $data['state_code'],
        //                              'district_code'                 =>  $sector['district_code'],
        //                              'block_code'                    =>  $sector['block_code'],
        //                              'panchayat_code'                =>  $sector['panchayat_code'],
        //                              'village_code'                  =>  $village_code,
        //                              'gp_village_all'                =>  0
        //                          ];
        //                          $AreaOfOperationLevel = $this->CooperativeFederationAreaOperationRural->patchEntity($AreaOfOperationLevel, $area_data);

        //                          $this->CooperativeFederationAreaOperationRural->save($AreaOfOperationLevel);
        //                      }
                             
        //                  }
        //              }
        //          }  

        //      }

        //         //Urban & both
        //         $arr_ward_sector = [1,3];

        //         if(isset($data['sector_urban']) && !empty($data['sector_urban'])   && in_array($data['operation_area_location'],$arr_ward_sector))
        //         {
        //             $this->loadModel('CooperativeFederationAreaOperationUrban');
        //             //$this->AreaOfOperationLevel->deleteAll(['cooperative_registrations_id'=>$CooperativeRegistration->id]);
                    
        //             foreach($data['sector_urban'] as $s_key => $sector)
        //             {
        //                 $wards = $sector['locality_ward_code'];
        //                 foreach($wards as $v_key => $ward_code)
        //                 {
                           
        //                     $AreaOfOperationLevelUrban = $this->CooperativeFederationAreaOperationUrban->newEntity();
        //                     $area_data = [
        //                         'cooperative_federations_id'  =>  $CooperativeFederation->id,
        //                         'row_id'                        =>  $s_key,
        //                         'area_of_operation_id'          =>  $data['area_of_operation_id_urban'],
        //                         'state_code'                    =>  $data['state_code'],
        //                         'district_code'                 =>  $sector['district_code'],
        //                         'local_body_type_code'          =>  $sector['local_body_type_code'],
        //                         'local_body_code'               =>  $sector['local_body_code'],
        //                         'locality_ward_code'            =>  $ward_code
        //                     ];
        //                     $AreaOfOperationLevelUrban = $this->CooperativeFederationAreaOperationUrban->patchEntity($AreaOfOperationLevelUrban, $area_data);

        //                     $this->CooperativeFederationAreaOperationUrban->save($AreaOfOperationLevelUrban);
        //                 }
        //             }  
        //         }
        //     $this->Flash->success(__('The Dairy Cooperative Federation data has been saved.'));
        //     return $this->redirect(['action' => 'add']);
        // }
        //     $this->Flash->error(__('The Cooperative data could not be saved. One or more Mandatory field(s) are Empty. Please, Fill the form and try again.'));
        // }

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
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$CooperativeFederation->district_code])->order(['name'=>'ASC']);
            $this->set('blocks',$blocks);
        // end Blocks for dropdown


        // Start Gram Panchayats for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$CooperativeFederation->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
        // end Gram Panchayats for dropdown



        // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$CooperativeFederation->gram_panchayat_code])->order(['village_name'=>'ASC']); 
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
            $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['name'=>'Fishery Cooperative','status'=>1])->order(['orderseq'=>'ASC']);

            
        // End PrimaryActivities for dropdown
            
        // Start SecondaryActivities for dropdown
            $this->loadModel('SecondaryActivities');
            $SecondaryActivities=$this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'sector_of_operation'=>$CooperativeFederation->sector_of_operation_type])->order(['orderseq'=>'ASC']);

        // End SecondaryActivities for dropdown
        
        // Start PresentFunctionalStatus for dropdown
            $this->loadModel('PresentFunctionalStatus');
            $PresentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        // End PresentFunctionalStatus for dropdown

        
        // Start urban_local_bodies type for dropdown
            
        $this->loadMOdel('UrbanLocalBodies');
        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$CooperativeFederation->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
        $this->set('localbody_types',$localbody_types);  
        // end urban_local_bodies type for dropdown


        // Start urban_local_bodies for dropdown
        $this->loadMOdel('UrbanLocalBodies');
        $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$CooperativeFederation->urban_local_body_type_code,'state_code'=>$CooperativeFederation->state_code])->order(['local_body_name'=>'ASC']);
            
        $this->set('localbodies',$localbodies);  
        // end urban_local_bodies for dropdown


        // Start urban_local_bodies ward for dropdown
            
        $this->loadMOdel('UrbanLocalBodiesWards');
        $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$CooperativeFederation->urban_local_body_code])->order(['ward_name'=>'ASC']);

        $this->set('localbodieswards',$localbodieswards);  
        // end urban_local_bodies for dropdown

        $this->loadModel('SectorOperations');

       $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1,'id'=>2])->toArray();
    
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

        $class_of_memberships=[1=>'Ordinary /Regular/Voting',2=>'Associate/Nominal/Non-Voting'];
        	$this->set('class_of_memberships',$class_of_memberships);  
        //End Class of Membership for dropdown
        
        //Start type_of_memberships for dropdown
        	$type_of_memberships=[1=>'Primary Cooperative Societies',2=>'District Union/Federations',3=>'State Federations',4=>'Multi-State Cooperative Society'];
        	$this->set('type_of_memberships',$type_of_memberships);

            //Start Major Activities for dropdown
	        $this->loadMOdel('MajorActivities');
	        $major_activities=$this->MajorActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['id'=>'ASC'])->toArray(); 
	        $this->set('major_activities',$major_activities);


        $this->set(compact('CooperativeFederation','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','years','state_code'));

        
        
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

        $CooperativeFederation = $this->CooperativeRegistrations->get($id, [
            'contain' => ['CRMS','CooperativeRegistrationsContactNumbers','CooperativeRegistrationsEmails','CooperativeRegistrationPacs'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationDairy'=>['sort'=>['id'=>'desc']],'CooperativeRegistrationFishery'=>['sort'=>['id'=>'desc']],'AreaOfOperationLevel','CooperativeRegistrationsLands']
        ]);

        


        // $CooperativeRegistration['fishery'] = [];
        // $CooperativeRegistration['fishery'] = $CooperativeRegistration['cooperative_registration_fishery'][0];
        // echo '<pre>';
        // print_r($data['area']);die;

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
            //if edit by district nodal pending or rejected
            if($CooperativeRegistration['is_approved'] == 0 || $CooperativeRegistration['is_approved'] == 2)
            {
                $this->Flash->error(__('You are not authorized to edit'));
                //$this->redirect($this->referer());
                return $this->redirect(['action' => 'pending']);    
            }

            //if user not created by him for CS
            if($CooperativeRegistration['is_approved'] == 1)
            {
                $nodal_data_entry_ids = $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'created_by'=>$this->request->session()->read('Auth.User.id'),'state_code'=>$this->request->session()->read('Auth.User.state_code'),'district_code'=>$this->request->session()->read('Auth.User.district_code')])->toArray();

                $nodal_data_entry_ids = array_keys($nodal_data_entry_ids);

                if(empty($nodal_data_entry_ids) || !in_array($CooperativeRegistration['created_by'],$nodal_data_entry_ids)){

                    $this->Flash->error(__('You are not authorized to edit.'));
                    //$this->redirect($this->referer());
                    return $this->redirect(['action' => 'pending']);   
                }
                
            }
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

            if($this->request->session()->read('Auth.User.role_id') != 8)
            {
                $data['is_approved'] = 0;
                $data['remark'] = '';
                $data['approved_by'] = '';
            }
            $data['updated_by'] = $this->request->session()->read('Auth.User.id');
            $data['updated'] = date('Y-m-d H:i:s');
            $data['operational_district_code'] = $this->request->session()->read('Auth.User.district_code');
			
            if(!empty($data['landline']))
            {
                $std = $data['std'];
                $landline = $data['landline'];
                $data['landline'] = $std.'-'.$landline;
            } else {
                unset($data['std']);
            }
			
			//add his district based on user if urban
			if(($this->request->session()->read('Auth.User.role_id') == 7 || $this->request->session()->read('Auth.User.role_id') == 8) && $data['location_of_head_quarter'] == 1)
            {
                $data['district_code'] = $this->request->session()->read('Auth.User.district_code');
            }
			
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

            $CooperativeFederation = $this->CooperativeRegistrations->patchEntity($CooperativeRegistration, $data);
            
            if($this->CooperativeRegistrations->save($CooperativeRegistration)) {

                  //Cooperative Registrations pacs data
             if($data['sector_of_operation'] == 1 || $data['sector_of_operation'] == 20 || $data['sector_of_operation'] == 22)
             {
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

            $this->CooperativeRegistrationsLands->deleteAll(['cooperative_registration_id'=>$CooperativeRegistration->id]);
             
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
                    return $this->redirect(['action' => 'accepted']);    
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

       $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['authority_name'=>'ASC']);

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

               $federationlevel = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1,'primary_activity_id'=>$primary_id])->order(['id'=>'ASC'])->toArray();


           


             $this->loadModel('DistrictCentralCooperativeBank');            
            $dist_central_bannk = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toarray();

            if($CooperativeRegistration->affiliated_union_federation_level == 3)
            {

            }else
            {
            if($CooperativeRegistration->affiliated_union_federation_level == '1')
            {
                $dccb = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','state_code'=>$CooperativeRegistration->state_code,'dccb_flag'=>$CooperativeRegistration->affiliated_union_federation_level, 'primary_activity_flag'=>$CooperativeRegistration->sector_of_operation])->order(['name'=>'ASC'])->toArray();
            }else if($CooperativeRegistration->affiliated_union_federation_level=='2')
            {
                $dccb = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','state_code'=>$CooperativeRegistration->state_code, 'dccb_flag'=>$CooperativeRegistration->affiliated_union_federation_level, 'primary_activity_flag'=>$CooperativeRegistration->sector_of_operation])->order(['name'=>'ASC'])->toArray();
            }
            else if($CooperativeRegistration->affiliated_union_federation_level !='2' || $CooperativeRegistration->affiliated_union_federation_level !='1' || $CooperativeRegistration->affiliated_union_federation_level !='3')
            {

                 $federationlevel_enty = $this->LevelOfAffiliatedUnion->find('all')->where(['status'=>1,'id'=>$CooperativeRegistration->affiliated_union_federation_level])->order(['id'=>'ASC'])->first();

                $dccb = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','state_code'=>$CooperativeRegistration->state_code,'primary_activity_flag'=>$CooperativeRegistration->sector_of_operation,'entity_type'=>$federationlevel_enty['name_of_affiliated_union']])->order(['name'=>'ASC'])->toArray();
            }

            $this->set('dccb',$dccb);
        }




        $this->set(compact('CooperativeRegistration','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','pActivities','years','register_authorities','sectors','designations','water_body_type','federationlevel','sectorUrbans','area_of_operation_id_urban','area_of_operation_id_rural'));
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


// public function getUrbanLocalBodies(){
//     $this->viewBuilder()->setLayout('ajax');
//         if($this->request->is('ajax')){
//             $state_code=$this->request->data('state_code');    
            
//             $this->loadMOdel('UrbanLocalBodies');
//             $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);
            
//             $option_html='<option value="">Select</option>';
//             if($localbody_types->count()>0){
//                 foreach($localbody_types as $key=>$value){
//                     $option_html.='<option value="'.$key.'">'.$value.'</option>';
//                 }
//             }
//             echo $option_html;
//         }
//         exit;
// }
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

// public function getLocalityWard(){
//     $this->viewBuilder()->setLayout('ajax');
//         if($this->request->is('ajax')){
//             $urban_local_body_code=$this->request->data('urban_local_body_code');    
            
//             $this->loadMOdel('UrbanLocalBodiesWards');
//             $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$urban_local_body_code])->order(['ward_name'=>'ASC']);
            
//             $option_html='<option value="">Select</option>';
//             if($localbodieswards->count()>0){
//                 foreach($localbodieswards as $key=>$value){
//                     $option_html.='<option value="'.$key.'">'.$value.'</option>';
//                 }
//             }
//             echo $option_html;
//         }
//         exit;
// }




// public function AddRowContactNumber(){

//   $this->viewBuilder()->setLayout('ajax');


//    $hr2=$this->request->data['hr2'];
//    $this->set('hr2',$hr2);

// }
// public function AddRowEmail(){

//   $this->viewBuilder()->setLayout('ajax');


//    $hr2=$this->request->data['hr2'];
//    $this->set('hr2',$hr2);

// }
    
    // public function dataEntryPending()
    // {
    //     $this->loadModel('States');

    //     $search_condition = array();
    //     $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
    //     $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

    //     if (!empty($this->request->query['society_name'])) {
    //         $societyName = trim($this->request->query['society_name']);
    //         $this->set('societyName', $societyName);
    //         $search_condition[] = "cooperative_society_name like '%" . $societyName . "%'";
    //     }

    //     if (!empty($this->request->query['registration_number'])) {
    //         $registrationNumber = trim($this->request->query['registration_number']);
    //         $this->set('registrationNumber', $registrationNumber);
    //         $search_condition[] = "registration_number like '%" . $registrationNumber . "%'";
    //     }

    //     if (!empty($this->request->query['cooperative_id'])) {
    //         $cooperativeId = trim($this->request->query['cooperative_id']);
    //         $search_condition[] = "cooperative_id_num like '%" . (int)substr($cooperativeId, 2) . "%'";
    //         $this->set('cooperativeId', $cooperativeId);
    //     }

    //     if (!empty($this->request->query['reference_year'])) {
    //         $referenceYear = trim($this->request->query['reference_year']);
    //         $this->set('referenceYear', $referenceYear);
    //         $search_condition[] = "reference_year like '%" . $referenceYear . "%'";
    //     }

    //     if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
    //         $state = trim($this->request->query['state']);
    //         $this->set('state', $state);
    //         $search_condition[] = "state_code = '" . $state . "'";
    //     }

    //     if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
    //         $location = trim($this->request->query['location']);
    //         $this->set('location', $location);
    //         $search_condition[] = "location_of_head_quarter = '" . $location . "'";
    //     }

    //     if(!empty($search_condition)){
    //         $searchString = implode(" AND ",$search_condition);
    //     } else {
    //         $searchString = '';
    //     }
    //     if ($page_length != 'all' && is_numeric($page_length)) {
    //         $this->paginate = [
    //             'limit' => $page_length,
    //         ];
    //     }
        
    //     $registrationQuery = $this->CooperativeRegistrations->find('all', [
    //         'order' => ['cooperative_id_num' => 'DESC'],
    //         'conditions' => [$searchString,'created_by'=>$this->request->session()->read('Auth.User.id')]
    //     ])->where(['is_draft'=>0,'is_approved'=>0,'status'=>1]);

    //     $this->paginate = ['limit' => 10];
    //     $CooperativeRegistrations = $this->paginate($registrationQuery);
    //     //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

    //     $this->set(compact('CooperativeRegistrations'));

        
    //     $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
    //     $query->hydrate(false);
    //     $stateOption = $query->toArray();
    //     $this->set('sOption',$stateOption);

    //     $arr_location = [
    //         '1'=> 'Urban',
    //         '2'=> 'Rural'
    //     ];
    //     $this->set('arr_location',$arr_location);
    // }


    // public function draft()
    // {
    //     $this->loadModel('States');

    //     $search_condition = array();
    //     $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
    //     $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

    //     if (!empty($this->request->query['society_name'])) {
    //         $societyName = trim($this->request->query['society_name']);
    //         $this->set('societyName', $societyName);
    //         $search_condition[] = "cooperative_society_name like '%" . $societyName . "%'";
    //     }

    //     if (!empty($this->request->query['registration_number'])) {
    //         $registrationNumber = trim($this->request->query['registration_number']);
    //         $this->set('registrationNumber', $registrationNumber);
    //         $search_condition[] = "registration_number like '%" . $registrationNumber . "%'";
    //     }

    //     if (!empty($this->request->query['cooperative_id'])) {
    //         $cooperativeId = trim($this->request->query['cooperative_id']);
    //         $search_condition[] = "cooperative_id_num like '%" . (int)substr($cooperativeId, 2) . "%'";
    //         $this->set('cooperativeId', $cooperativeId);
    //     }

    //     if (!empty($this->request->query['reference_year'])) {
    //         $referenceYear = trim($this->request->query['reference_year']);
    //         $this->set('referenceYear', $referenceYear);
    //         $search_condition[] = "reference_year like '%" . $referenceYear . "%'";
    //     }

    //     if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
    //         $state = trim($this->request->query['state']);
    //         $this->set('state', $state);
    //         $search_condition[] = "state_code = '" . $state . "'";
    //     }

    //     if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
    //         $location = trim($this->request->query['location']);
    //         $this->set('location', $location);
    //         $search_condition[] = "location_of_head_quarter = '" . $location . "'";
    //     }

    //     if(!empty($search_condition)){
    //         $searchString = implode(" AND ",$search_condition);
    //     } else {
    //         $searchString = '';
    //     }
    //     if ($page_length != 'all' && is_numeric($page_length)) {
    //         $this->paginate = [
    //             'limit' => $page_length,
    //         ];
    //     }
        
    //     $registrationQuery = $this->CooperativeRegistrations->find('all', [
    //         'order' => ['id' => 'DESC'],
    //         'conditions' => [$searchString,'created_by'=>$this->request->session()->read('Auth.User.id')]
    //     ])->where(['is_draft'=>1,'status'=>1]);

    //     $this->paginate = ['limit' => 10];
    //     $CooperativeRegistrations = $this->paginate($registrationQuery);
    //     //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

    //     $this->set(compact('CooperativeRegistrations'));

        
    //     $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
    //     $query->hydrate(false);
    //     $stateOption = $query->toArray();
    //     $this->set('sOption',$stateOption);

    //     $arr_location = [
    //         '1'=> 'Urban',
    //         '2'=> 'Rural'
    //     ];
    //     $this->set('arr_location',$arr_location);
    // }

    
    
    // public function getPrimaryActivity(){
    //     $this->viewBuilder()->setLayout('ajax');
    //         if($this->request->is('ajax')){
                
    //             $sector_operation = $this->request->data('sector_operation');    

    //             $this->loadMOdel('PrimaryActivities');

    //             $pactivities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['sector_of_operation'=>$sector_operation,'status'=>1])->order(['orderseq'=>'ASC']);
              
    //             $option_html='<option value="">Select</option>';

    //             if($pactivities->count()>0){
    //                 foreach($pactivities as $key=>$value){
    //                     $option_html.='<option value="'.$key.'">'.$value.'</option>';
    //                 }
    //             }

    //             echo $option_html;
    //         }
    //         exit;
    // }
    // public function getSecondaryActivity(){
    //     $this->viewBuilder()->setLayout('ajax');
    //         if($this->request->is('ajax')){
                
    //             $sector_operation = $this->request->data('sector_operation');    

    //             $this->loadMOdel('SecondaryActivities');

    //             $SecondaryActivities = $this->SecondaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);
              
    //             $option_html='<option value="">Select</option>';

    //             if($SecondaryActivities->count()>0){
    //                 foreach($SecondaryActivities as $key=>$value){
    //                     $option_html.='<option value="'.$key.'">'.$value.'</option>';
    //                 }
    //             }

    //             echo $option_html;
    //         }
    //         exit;
    // }

//    public function getdccb()
//     {
//          $this->viewBuilder()->setLayout('ajax');
//             if($this->request->is('ajax')){
                
//                 $dccb                   = $this->request->data('union_level');  
//                 $state_code             = $this->request->data('state');  
//                 $primary_activity_id    = $this->request->data('primary_activity');  
//                 $entity_type            = $this->request->data('entity_type'); 
                

//                 $this->loadMOdel('DistrictCentralCooperativeBank');
//                 if($dccb=='1')
//                 {
//                   $SecondaryActivities = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','state_code'=>$state_code,'dccb_flag'=>$dccb, 'primary_activity_flag'=>$primary_activity_id])->order(['name'=>'ASC'])->toArray();
//                 }else if($dccb=='2')
//                 {
//                      $SecondaryActivities = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','state_code'=>$state_code, 'dccb_flag'=>$dccb, 'primary_activity_flag'=>$primary_activity_id])->order(['name'=>'ASC'])->toArray();
//                  }
//                  else if($dccb !='2' || $dccb !='1' || $dccb !='3')
//                  {
                   
//                     $SecondaryActivities = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'name !='=>'','state_code'=>$state_code,'primary_activity_flag'=>$primary_activity_id,'entity_type'=>$entity_type])->order(['name'=>'ASC'])->toArray();
//                  }

//               if($dccb =='3')
//               {
//                  $option_html='<option value="">Select</option>';

//              }else{
              
//                 $option_html='<option value="">Select</option>';
                
//                 if(count($SecondaryActivities)==0){
//                     $option_html.='<option value="0">To Be Provided</option>';
//                 }
//                 if(count($SecondaryActivities)>0){
//                     foreach($SecondaryActivities as $key=>$value){
//                         $option_html.='<option value="'.$key.'">'.$value.'</option>';
//                     }
//                 }
//             }

//                 echo $option_html;
//             }
//             exit;
//     }

    // public function getfederationlevel()
    // {
    //         $this->viewBuilder()->setLayout('ajax');
    //         if($this->request->is('ajax')){                
    //             $primary_activity_id = $this->request->data('primary_activity'); 


    //             $this->loadMOdel('LevelOfAffiliatedUnion');          
    //             $SecondaryActivities = $this->LevelOfAffiliatedUnion->find('list',['keyField'=>'id','valueField'=>'name_of_affiliated_union'])->where(['status'=>1,'primary_activity_id'=>$primary_activity_id])->order(['id'=>'ASC'])->toArray();
                          
    //             $option_html='<option value="">Select</option>';
    //             if(count($SecondaryActivities)>0){
    //                 foreach($SecondaryActivities as $key=>$value){
    //                     $option_html.='<option value="'.$key.'">'.$value.'</option>';
    //                 }
                
    //                 }

    //             echo $option_html;
    //         }
    //         exit;

    // }
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

        //  echo json_encode($uw2);
        //  print_r(json_encode($districts));
        //  print_r(json_encode($localbody_types));
        //  die;
        $this->set('districts',$districts);
       $this->set('uw2',$uw2);
       $this->set('localbody_types',$localbody_types);
        
        
    }

   

    //bulk approval for district nodal
    // public function bulkapproval()
    // {
    //     if($this->request->is('post')){
    //         $data=$this->request->getData();
    //         $this->loadMOdel('CooperativeRegistrations');  
    //         $this->loadMOdel('CooperativeRegistrationsRemarks');
            
    //         // echo '<pre>';
    //         // print_r($r_data);
    //         // print_r($data);
    //         // die;

    //         $remarks_data = [];
    //         $remarks_data['is_approved'] = $data['is_approved'];
    //         $remarks_data['remark'] = $data['remark'];
    //         $remarks_data['approved_by'] = $this->request->session()->read('Auth.User.id');

    //         $data['approved_by'] = $this->request->session()->read('Auth.User.id');
    //         // echo '<pre>';
    //         // print_r($remarks_data);
    //         // print_r($data);
    //         // die;

    //         $data['approved_by'] = $this->request->session()->read('Auth.User.id');

            

    //         foreach($data['arr_accept'] as $key => $id)
    //         {
    //             $CooperativeRegistration = $this->CooperativeRegistrations->get($id, [
    //                 'contain' => ['CRMS','CooperativeRegistrationsContactNumbers','CooperativeRegistrationsEmails','CooperativeRegistrationPacs','CooperativeRegistrationDairy','CooperativeRegistrationFishery','AreaOfOperationLevel','CooperativeRegistrationsLands']
    //             ]);

    //             $CooperativeRegistration = $this->CooperativeRegistrations->patchEntity($CooperativeRegistration, $data);


    //             if($this->CooperativeRegistrations->save($CooperativeRegistration)) 
    //             {
    //                 $remarks_data['cooperative_registration_id'] = $id;
                    
    //                 $CooperativeRegistrationsRemark = $this->CooperativeRegistrationsRemarks->newEntity();
        
    //                $CooperativeRegistrationsRemark = $this->CooperativeRegistrationsRemarks->patchEntity($CooperativeRegistrationsRemark, $remarks_data);
                
    //                $this->CooperativeRegistrationsRemarks->save($CooperativeRegistrationsRemark);
                
    //             } 
    //         }

    //         $this->Flash->success(__('Request has been accepted.'));

    //         if($this->request->session()->read('Auth.User.role_id') == 2)
    //         {
    //             //Admin
    //             if($data['is_approved'] == '1')
    //             {
    //                 return $this->redirect(['action' => 'adminAccepted']);    
    //             } else {
    //                 return $this->redirect(['action' => 'adminRejected']);    
    //             }
    //         }

    //         if($this->request->session()->read('Auth.User.role_id') == 8)
    //         {
    //             //District Nodal
    //             if($data['is_approved'] == '1')
    //             {
    //                 return $this->redirect(['action' => 'accepted']);    
    //             } else {
    //                 return $this->redirect(['action' => 'rejected']);    
    //             }
    //         }
    //     }

    // }

    //approval by district nodal from view page
    // public function approval()
    // {
    //     if($this->request->is('post')){
    //         $data=$this->request->getData();

    //         $r_data = [];
    //         $r_data['is_approved'] = $data['is_approved'];
    //         $r_data['remark'] = $data['remark'];
    //         $r_data['approved_by'] = $this->request->session()->read('Auth.User.id');

    //         $data['approved_by'] = $this->request->session()->read('Auth.User.id');
            
    //         $data['approved_by'] = $this->request->session()->read('Auth.User.id');


    //         $this->loadMOdel('CooperativeRegistrations');  
            
    //         $CooperativeRegistration = $this->CooperativeRegistrations->get($data['cooperative_registration_id'], [
    //             'contain' => ['CRMS','CooperativeRegistrationsContactNumbers','CooperativeRegistrationsEmails','CooperativeRegistrationPacs','CooperativeRegistrationDairy','CooperativeRegistrationFishery','AreaOfOperationLevel','CooperativeRegistrationsLands']
    //         ]);

           
    //         //$c_data

    //         $CooperativeRegistration = $this->CooperativeRegistrations->patchEntity($CooperativeRegistration, $r_data);
            
    //         if($this->CooperativeRegistrations->save($CooperativeRegistration)) {
    //             $this->loadMOdel('CooperativeRegistrationsRemarks');
    //             $CooperativeRegistrationsRemark = $this->CooperativeRegistrationsRemarks->newEntity();
    
    //            $CooperativeRegistrationsRemark = $this->CooperativeRegistrationsRemarks->patchEntity($CooperativeRegistrationsRemark, $data);
            
    //            if($this->CooperativeRegistrationsRemarks->save($CooperativeRegistrationsRemark)) {
    //               $this->Flash->success(__('Request has been accepted.'));

    //             if($this->request->session()->read('Auth.User.role_id') == 2)
    //             {
    //                 //Admin
    //                 if($data['is_approved'] == '1')
    //                 {
    //                     $this->Flash->success(__('Request has been accepted.'));
	// 					//echo "I am in Accepted Block";
	// 					//exit;
	// 					return $this->redirect(['action' => 'adminAccepted']);
						
    //                 } else {
	// 					$this->Flash->success(__('Request has been Rejected.'));
    //                     return $this->redirect(['action' => 'adminRejected']);    
    //                 }
					
    //             }
                
    //             if($this->request->session()->read('Auth.User.role_id') == 8)
    //             {
    //                 //District Nodal
    //                 if($data['is_approved'] == '1')
    //               {
    //                 return $this->redirect(['action' => 'accepted']);    
    //               } else {
    //                 return $this->redirect(['action' => 'rejected']);    
    //               }
    //             }
                  
    //            } else {
    //             $this->Flash->error(__('Request is not accepted, please try again.'));
    //            }
    //         } else {
    //             $this->Flash->error(__('Request is not rejected, please try again.'));
    //         }
            
    //     }

    // }

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

  

    // public function getUrbanRural()
    // {
    //     $this->viewBuilder()->setLayout('ajax');
    //     if($this->request->is('ajax'))
    //     {
    //         $operation_area_location=$this->request->data('operation_area_location');    
            
    //         //echo $operation_area_location;die;
    //         $this->loadMOdel('AreaOfOperations');
    //         $arr_area_of_operations=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'urban_rural'=>$operation_area_location])->order(['orderseq'=>'ASC']);
            
    //         $option_html='<option value="">Select</option>';
            
    //         if($arr_area_of_operations->count()>0){
    //             foreach($arr_area_of_operations as $key=>$value){
    //                 $option_html.='<option value="'.$key.'">'.$value.'</option>';
    //             }
    //         }
    //         echo $option_html;
    //     }
    //     exit;
    // }

    ######################
       // genrate unique nubmebr aftter submit
    #####################
    // public function generateUniqueNumber($id=null)
    // {
    //     $this->loadMOdel('CooperativeRegistrations');
    //     if($id !='')
    //     {
    //         $MaxCooperativeIdA =$this->CooperativeRegistrations->find('all')->select(['cooperative_id_num'=>'MAX(cooperative_id_num)'])->where(['id'=>$id])->order(['cooperative_id_num'=>'DESC'])->first();

    //     if($MaxCooperativeIdA->cooperative_id_num !='')
    //     {
    //          $cooperative_id_num=($MaxCooperativeIdA->cooperative_id_num );
    //     }else{
    //      $MaxCooperativeIdA =$this->CooperativeRegistrations->find('all')->select(['cooperative_id_num'=>'MAX(cooperative_id_num)'])->order(['cooperative_id_num'=>'DESC'])->first();

    //      $cooperative_id_num=($MaxCooperativeIdA->cooperative_id_num + 1);
    //     }  

    //     }else{
    //      $MaxCooperativeIdA =$this->CooperativeRegistrations->find('all')->select(['cooperative_id_num'=>'MAX(cooperative_id_num)'])->order(['cooperative_id_num'=>'DESC'])->first();
    //         $cooperative_id_num=($MaxCooperativeIdA->cooperative_id_num + 1);
    //     }

    //     return $cooperative_id_num;
    // }


    
}