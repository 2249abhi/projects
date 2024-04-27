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
class ScardbRegistrationsController extends AppController
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
         $this->loadModel('StateCooperativeBanks');

         $logged_user_id=$this->request->session()->read('Auth.User.id'); 
 
         $bankExist = $this->StateCooperativeBanks->find()->where(['created_by'=>$logged_user_id,  'status'=>1,'is_delete'=>0])->first();
 
 
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
 
         if(in_array($this->request->session()->read('Auth.User.role_id'), $allow_admin_role))
         {
            $where_array=['scb_dcb_flag'=>1,'is_delete'=>0,'status'=>1];
         } else {
                       //for SCB nodal only
             $where_array=['is_delete'=>0,'status'=>1,'created_by'=>$logged_user_id];           
         }

         $registrationQuery = $this->StateCooperativeBanks->find('all', [
            'order' => ['id' => 'DESC'],
            'conditions' => [$search_condition]
        ])->where($where_array)->contain(['ScbContactDetails']);
           
         $this->paginate = ['limit' => 20];
         $StateCooperativeBanks = $this->paginate($registrationQuery);

         $this->set(compact('StateCooperativeBanks'));
 
         $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
         $query->hydrate(false);
         $stateOption = $query->toArray();
         $this->set('sOption',$stateOption);

     }
 
   
  
    /**
     * View method
     *
     * @param string|null $id State id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
   

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
  

    public function add()
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

           
            $this->loadModel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        
            $district_code = $this->request->session()->read('Auth.User.district_code');
            $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$district_code])->order(['name'=>'ASC']);
            $this->set('blocks',$blocks);
        
            $block_code = $this->request->session()->read('Auth.User.block_code');
            $this->loadMOdel('DistrictsBlocksGpVillages');
            $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
            $this->set('gps',$gps);  
       
            $gram_panchayat_code = $this->request->session()->read('Auth.User.gram_panchayat_code');
            $this->loadMOdel('DistrictsBlocksGpVillages');
           $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$gram_panchayat_code])->order(['village_name'=>'ASC']); 
           $this->set('villages',$villages);  

         
            $this->loadMOdel('UrbanLocalBodies');
            $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  
            $this->set('localbody_types',$localbody_types); 
            
            $this->loadMOdel('UrbanLocalBodies');
            $urban_local_body_type_code = $this->request->session()->read('Auth.User.urban_local_body_type_code');
            $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$urban_local_body_type_code,'state_code'=>$state_code])->order(['local_body_name'=>'ASC']);
            $this->set('localbodies',$localbodies); 

            $this->loadMOdel('UrbanLocalBodiesWards');
            $urban_local_body_code = $this->request->session()->read('Auth.User.urban_local_body_code');

            $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$urban_local_body_code  ])->order(['ward_name'=>'ASC']);
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


    
    
}