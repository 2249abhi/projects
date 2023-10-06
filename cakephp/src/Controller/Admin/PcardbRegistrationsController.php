<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\View\Helper\HtmlHelper; 
use Cake\View\Helper\FormHelper; 

class PcardbRegistrationsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
      //  $this->loadModel('Users');
       /* $user_all=$this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
        $this->set('user_all', $user_all); */
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['pcardbAddRow','schemeAddRow']);        
      //  $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','getdccb','getfederationlevel','approval','bulkapproval','getUrbanRural','generateUniqueNumber']);
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

//---------------------------------------------

public function index()
{  
    $this->loadModel('States'); 
    $this->loadModel('StateCooperativeBanks');
    $logged_user_id = $this->request->session()->read('Auth.User.id');      

    $states_list =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']])->hydrate(false)->toArray();
    $this->set(compact('states_list'));

    $bankExist = $this->StateCooperativeBanks->find()->where(['scb_dcb_flag'=>3,'created_by'=>$logged_user_id,'status'=>1,'is_delete'=>0])->first();

    $pcardb_count =$this->StateCooperativeBanks->find('all')->where(['scb_dcb_flag'=>4,'created_by'=>$logged_user_id,'status'=>1,'is_delete'=>0])->toArray();
    //echo $bankExist->id;


         $add_flag = 0;
         if(!empty($bankExist))
         {
             $add_flag = 1;
         }
         $this->set('add_flag',$add_flag);

         $this->set(compact('bankExist','pcardb_count'));

         $query =$this->StateCooperativeBanks->find('list',['keyField'=>'id','valueField'=>'scb_name','conditions'=>['scb_dcb_flag'=>3,'status'=>1,'is_delete'=>0],'order' => ['scb_name' => 'ASC']]);
        $query->hydrate(false);
        $scbName = $query->toArray();
        $this->set('scbName',$scbName);
}

//-----------------------------------------------------------------------

public function viewIndex(){
    

    $this->loadModel('StateCooperativeBanks');
    $logged_user_id = $this->request->session()->read('Auth.User.id');
   // $this->loadModel('ScbContactDetails');
    $conn = ConnectionManager::get('default');
    $html_link = new HtmlHelper(new \Cake\View\View());
    $form_link = new FormHelper(new \Cake\View\View());


     foreach($_POST as $key=>$val){
         $req_value=$this->request->data($key);	
         if(!is_array($req_value))  $$key=trim($req_value);
         else  $$key=$req_value; 
         }    
       $this->autoRender = false;
       $this->layout=false; 

       $search_by=$search['value']; // from datatable form serartch text box

       $where_part=" 1=1 ";

       if(strlen($search_by) > 0) $where_part.=" and (StateCooperativeBanks.scb_registration_no like '%$search_by%' or StateCooperativeBanks.scb_name like '%$search_by%'  )  "; 
       if($state_code > 0) $where_part.=" and StateCooperativeBanks.state_code='$state_code' ";
       if($district_code > 0) $where_part.=" and StateCooperativeBanks.district_code='$district_code' ";
     //  if($latest_audit_year > 0) $where_part.=" and StateCooperativeBanks.latest_audit_year='$latest_audit_year' ";
       //            
   $banks_query = $this->StateCooperativeBanks          
   ->find('all')
   ->select([
    // 'StateCooperativeBanks.functional_status',
     'StateCooperativeBanks.id', /* required for joins */
     'StateCooperativeBanks.scb_name',
     'StateCooperativeBanks.registration_date',
     'StateCooperativeBanks.scb_registration_no',
	 'StateCooperativeBanks.parent_id',
     'StateCooperativeBanks.status',    
     'StateCooperativeBanks.is_draft',    
     'States.name',
     'Districts.name',
     ])
   ->where(['StateCooperativeBanks.is_delete'=>0, 'StateCooperativeBanks.created_by'=>$logged_user_id, 'StateCooperativeBanks.scb_dcb_flag'=>4, $where_part])   // 'StateCooperativeBanks.status'=>1
   ->order(['StateCooperativeBanks.id'=>'DESC'])
   ->contain(['States','Districts']); // , 'ScbContactDetails'

//for Admin or Supper Admin only
        $allow_admin_role = [1,2,14];
 
         if(in_array($this->request->session()->read('Auth.User.role_id'), $allow_admin_role))
         {
        $banks_query = $this->StateCooperativeBanks          
       ->find('all')
        ->select([
         'StateCooperativeBanks.id', /* required for joins */
         'StateCooperativeBanks.scb_name',
         'StateCooperativeBanks.registration_date',
         'StateCooperativeBanks.scb_registration_no',
		 'StateCooperativeBanks.parent_id',
         'StateCooperativeBanks.status',    
         'StateCooperativeBanks.is_draft',    
         'States.name',
         'Districts.name',
         ])
   ->where(['StateCooperativeBanks.is_delete'=>0, 'StateCooperativeBanks.scb_dcb_flag'=>4, $where_part])   // 'StateCooperativeBanks.status'=>1
   ->order(['StateCooperativeBanks.id'=>'DESC'])
   ->contain(['States','Districts']); // , 'ScbContactDetails'
         }
//for Admin or Supper Admin only


   //pp($banks_query,1);

   $total_count=$banks_query->count(); 

   if($length != -1)
   {
    $banks_query->limit($length)->offset($start);
   }

   $banks_query=$banks_query->order(['States.name'=>'asc','Districts.name'=>'asc', 'StateCooperativeBanks.scb_name'=>'ASC']); 
   //pp($banks_query,1);

   $banks_query=$banks_query->toArray();

   
       $data = array();
       $i=1;    
       
       // pp($banks_query,1);

       foreach( $banks_query as $row ) {
		   
                   $counting=($i+$start);            
                   $nestedData=array(); 
				   $parent_id = $row['parent_id'];
                   $bankExist = $this->StateCooperativeBanks->find()->where(['scb_dcb_flag'=>3,'status'=>1,'is_delete'=>0,'id'=>$parent_id])->first();

                   $query =$this->StateCooperativeBanks->find('list',['keyField'=>'id','valueField'=>'scb_name','conditions'=>['scb_dcb_flag'=>3,'status'=>1,'is_delete'=>0]]);
                    $query->hydrate(false);
                    $scbName = $query->toArray();
                    $this->set('scbName',$scbName);
                    
                  // if($row['functional_status']==1) $f_st='Functional';
                  // elseif($row['functional_status']==2) $f_st='Under Liquidation';
                  // else $f_st='';

                   if($row['status']==0) $st="Inactive";
                   elseif($row['status']==1) $st="Active";
                   else $st='';

                   
                   if($row['is_draft']==0) $drft_st="Final";
                   elseif($row['is_draft']==1) $drft_st="Draft";
                   else $drft_st='';

                   $bank_name= $row['scb_name'];

                  // if($row['is_draft']==1){
                  //  $edit_link = $html_link->link('<i class="fa fa-edit" style=""></i>', ['controller' => 'pcardb-registrations', 'action' => 'add-edit', convert_str($row['id']),  '_full' => true],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]);
                  //   } elseif($row['is_draft']==0) $edit_link='';

                   $edit_link = $html_link->link('<i class="fa fa-edit" style=""></i>', ['controller' => 'pcardb-registrations', 'action' => 'add-edit', convert_str($row['id']),  '_full' => true],['class' => 'btn btn-warning  btn-xs', 'title' => __('Edit'), 'escape' => false]);

                   $view_link= $html_link->link('<i class="fa fa-eye" style=""></i>', ['controller' => 'pcardb-registrations', 'action' => 'view', convert_str($row['id']),  '_full' => true],['class' => 'btn btn-info btn-xs', 'title' => __('View'), 'escape' => false]);
                
                   $delete_link= $form_link->postLink('<i class="fa fa-trash-o"></i>', ['controller' => 'pcardb-registrations', 'action' => 'delete', convert_str($row['id'])], ['confirm' => __("Are you sure to delete this Bank - $bank_name?", convert_str($row['id'])),'class' => 'btn btn-danger btn-xs', 'title' => __('Delete'), 'escape' => false]);
                  
                   if($this->request->session()->read('Auth.User.role_id')!=1) $delete_link= '';

                   // $desig_id=$row['scb_contact_details'][0]['designation'];
                  // $stmt = $conn->execute("select name from designations where id='$desig_id' ");
                  // $results = $stmt ->fetchAll('assoc');                      
                  // $contact_details.=($results[0]['name']!='')?'Designation: '.$results[0]['name'].'<br>':''.'<br>';
                   
                   $nestedData[] = "$counting";                       
                   $nestedData[] = wordwrap($bank_name,'50','<br>');
                   $nestedData[] = wordwrap($bankExist['scb_name'],'50','<br>');
                   $nestedData[] = firstUpper($row['state']['name']); 
                   $nestedData[] = firstUpper($row['district']['name']); 
                   $nestedData[] = $row['scb_registration_no']; 
                   $nestedData[] = dateFormat($row['registration_date'],5); 
                   $nestedData[] = $drft_st; // $f_st; 
                   $nestedData[] = $st; //. $row['id'].'  '. urlencode(convert_str($row['id'])); 
                   $nestedData[] = $view_link.' '.$edit_link.' '.$delete_link; 
                   
                   $data[] = $nestedData;
                   $i++;
               } 

               $json_data = array(
                 "draw"            => intval( $draw ),   
                 "recordsTotal"    => intval( $total_count ),  // total number of records
                 "recordsFiltered" => intval( $total_count ), // total number of records after searching, if there is no searching then totalFiltered = totalData						
                 "data"            => $data   // total data array
                 );
                 echo json_encode($json_data); exit;  // send data as json format    
}

//--------------------------------------------------

public function delete($id = null)
{
    $this->loadModel('StateCooperativeBanks');
   
    $this->request->allowMethod(['post', 'delete']);
    $logged_user=$this->request->session()->read('Auth.User.id');
    $logged_user_role_id=$this->request->session()->read('Auth.User.role_id');
    
    $id=convert_str($id,1);

    $StateCooperativeBanks = $this->StateCooperativeBanks->get($id);

    if($logged_user_role_id == 1 || $logged_user_role_id==2)
    {
        $this->loadModel('Users');
        $created_by = $StateCooperativeBanks['created_by'];
        $cUser = $this->Users->find('all')->where(['id' => $created_by])->first();

        if(empty($created_by) || $cUser['id'] != $created_by)
        {
            $this->Flash->error(__('You are not authorized for this operation.'));
            return $this->redirect(['action' => 'index']);    
        }
    }

    $data['status'] = 0;
    $data['is_delete'] = 1;
    $data['updated_by'] = $logged_user;
    $data['updated'] = date('Y-m-d H:i:s');
    $StateCooperativeBanks = $this->StateCooperativeBanks->patchEntity($StateCooperativeBanks, $data);

    if($this->StateCooperativeBanks->save($StateCooperativeBanks)) {
        $this->Flash->success(__('The PCARDB bank has been deleted.'));
    }
    $this->redirect($this->referer());
}

public function member($id = null)
    {

        $this->loadModel('States');
        $this->loadModel('StateCooperativeBanks');
        $this->loadModel('Users');
        $this->loadModel('Districts');


        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        

        if (!empty($this->request->query['scb_name'])) {
            $this->set('scb_name', $this->request->query['scb_name']);
            $scb_name = trim($this->request->query['scb_name']);
            
            $search_condition[] = "scb_name like '%" . $scb_name . "%'";
        }

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
                $s_state = trim($this->request->query['state']);
                $search_condition2['state_code']= $s_state;             
                $this->set('s_state', $s_state);
            }


        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
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
        if(!empty($id)){
                
                
                $registrationQuery = $this->StateCooperativeBanks->find('all', [
                'order' => ['id' => 'DESC'],
                'conditions' => [$searchString,$search_condition2]
                ])->where(['parent_id'=>$id,'scb_dcb_flag'=>4,'status'=>1,'is_delete'=>0]);

            }



            $this->paginate = ['limit' => 20];
            $PcardbMembers = $this->paginate($registrationQuery);

            $bankExist = $this->StateCooperativeBanks->find()->where(['scb_dcb_flag'=>3,'status'=>1,'is_delete'=>0,'id'=>$id])->first();
			

                   $query =$this->StateCooperativeBanks->find('list',['keyField'=>'id','valueField'=>'scb_name','conditions'=>['scb_dcb_flag'=>3,'status'=>1,'is_delete'=>0]]);
                    $query->hydrate(false);
                    $scbName = $query->toArray();
                    $this->set('scbName',$scbName);

                    $sataus = ['1'=>'Active','0'=>'Inactive'];


        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
            $query->hydrate(false);
            $stateOption = $query->toArray();
            $this->set('sOption',$stateOption);

            $get_district =$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
            $get_district->hydrate(false);
            $districtOption = $get_district->toArray();
            $this->set('dOption',$districtOption);

//-------------Export Code Satrt----------------------------
            if($this->request->is('get')){
            if(!empty($this->request->query['export_excel'])) {
                $queryExport = $this->StateCooperativeBanks->find('all', [
                    'order' => ['id' => 'DESC'],
                    'conditions' => [$searchString]
                ])->where(['parent_id'=>$id,'scb_dcb_flag'=>4,'status'=>1,'is_delete'=>0]);
        

                $queryExport->hydrate(false);
                $ExportResultData = $queryExport->toArray();
                $fileName = "PCARDB_Member-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No",  "Bank Name (PCARDB)", "State", "District","Registration Number","Registration Date", "Status");
                $data = array();
                $i=1;
                $hlocation = ['1'=> 'Urban','2' => 'Rural'];
                foreach($ExportResultData As $rows){
                    $data[]=array($i, $rows['scb_name'],$stateOption[$rows['state_code']],$districtOption[$rows['district_code']], $rows['scb_registration_no'], $rows['registration_date'],$sataus[$rows['status']]);
                    $i++;
                } 
                $this->exportInExcel($fileName, $headerRow, $data);
            }
        }
//------------------Export Code End-----------------------

        $this->set(compact('PcardbMembers','id','bankExist','sataus'));
        
    }


//-----------------------------------------
    
     public function addEdit($id = null)
     {          
        $this->loadModel('StateCooperativeBanks');
        $this->loadModel('RegistrationAuthorities');
        $this->loadModel('States');  
        $this->loadModel('Districts');
        $this->loadModel('Blocks');           
        $this->loadModel('UrbanLocalBodiesWards');   
        $this->loadModel('DistrictsBlocksGpVillages');   
        $this->loadModel('UrbanLocalBodies');   
        $this->loadModel('Designations');
        $this->loadModel('AreaOfOperations');
        $this->loadModel('ScbImplementingSchemes');  
        $this->loadModel('ScbContactDetails');  
        $this->loadModel('ScbAreaOfOperationLevel');  
        $this->loadModel('ScbAreaOfOperationLevelUrban');  
        
        $logged_user_state_code= $this->request->session()->read('Auth.User.state_code');
        $logged_user_id= $this->request->session()->read('Auth.User.id');
        $logged_user_role_id= $this->request->session()->read('Auth.User.role_id');

        $bankExist = $this->StateCooperativeBanks->find()->where(['scb_dcb_flag'=>3,'created_by'=>$logged_user_id,'status'=>1,'is_delete'=>0])->first();
        $pcardb_count =$this->StateCooperativeBanks->find('all')->where(['scb_dcb_flag'=>4,'created_by'=>$logged_user_id,'status'=>1,'is_delete'=>0])->toArray();
 
 
         $add_flag = 0;
         if(empty($bankExist))
         {
             $add_flag = 1;
         }
         $this->set('add_flag',$add_flag);


        //  if( $bankExist['number_of_member'] > count($pcardb_count))
        // {
        //     return $this->redirect(['controller' => 'scardb-registrations','action' => 'index']);$this->Flash->error(__('You are not Authorize to add PCARDB'));
        // }

        $scardb_user_id = $this->StateCooperativeBanks->find('all')->where(['created_by'=>$logged_user_id])->first();

        $scardb_id = $scardb_user_id->id;
                 
        //$id=convert_str($id, 1);

        if($id > 0){
            $heading_title='Edit ';
            $identifcation_no= str_pad($id, 6, '0', STR_PAD_LEFT);            
            
             $PCARDB_Bank = $this->StateCooperativeBanks->get($id, [
                'contain' => ['ScbContactDetails'=>['sort'=>['contact_person'=>'ASC']],'ScbImplementingSchemes'=>['sort'=>['govt_scheme_name'=>'ASC']] ]
            ]);    

           
        }
        else{
            $PCARDB_Bank = $this->StateCooperativeBanks->newEntity();
            $heading_title='Add New ';

            $curr_id = $this->StateCooperativeBanks->find()->select(['id'])->last();       
            $identifcation_no= str_pad(($curr_id['id']+1), 6, '0', STR_PAD_LEFT) ; 
            /*
            $raw_qry = new SugarMillsDashboardController();
            $table_name= $this->StateCooperativeBanks->table();
            $auto_inc=$raw_qry->query( "SELECT Auto_increment as next_id FROM information_schema.tables  WHERE table_name='$table_name' ");  
            $identifcation_no= str_pad(($auto_inc[0]['next_id'] ), 6, '0', STR_PAD_LEFT) ; */
        }

        // ----------------- add edit starts ----------------------------------

        //if($this->request->is('post')){
            if ($this->request->is(['patch', 'post', 'put'])){

            $data       = $this->request->getData();
            $data       = $this->Sanitize->clean($data);

          //  pp($_POST,1);

            if($id > 0){
                $data['updated'] = date('Y-m-d H:i:s');
                $this->ScbContactDetails->deleteAll(['state_cooperative_bank_id' => $PCARDB_Bank->id]); 
                $this->ScbImplementingSchemes->deleteAll(['state_cooperative_bank_id' => $PCARDB_Bank->id]);
            }else{
                $data['status'] = 1; // later in form
                $data['is_delete'] = 0;
                $data['created_at'] = date('Y-m-d H:i:s');                                
                $data['scb_dcb_flag'] = 4;
            }
            $data['parent_id'] = $scardb_id; 
            $data['created_by'] = $logged_user_id;   
            if(!empty($data['scb_contact_details']))
            {
                         // code later on 

            }

               //for scheme 
               if($data['latest_audit_year'] == '')
               {
                   unset($data['latest_audit_year']);
               }

                //for scheme 
                if($data['is_scheme_implemented'] == 0)
                {
                    unset($data['scb_implementing_schemes']);
                }
              
                // profit making-
                if($data['is_profit_making'] == 1)
                {
                    unset($data['net_loss']);
                }
                if($data['is_profit_making'] == 0)
                {
                    unset($data['net_profit']);
                }
                if($data['is_dividend_paid'] == 0)
                {
                    unset($data['dividend_paid_rate']);
                }
                if($data['has_office_building'] == 0)
                {
                    unset($data['office_building_type']);
                }

                if($data['location_urban_rural'] == 1) // urban
                {
                    unset($data['block_code']);
                    unset($data['gp_code']);
                    unset($data['village_code']);
                }
                if($data['location_urban_rural'] == 2) // rural
                {
                    unset($data['urban_local_body_category_code']);
                    unset($data['urban_local_body_code']);
                    unset($data['locality_ward_code']);
                }
              //  $data['other_member_count']=$data['other_member'];
              //  unset($data['other_member']); // by jat

              
               $PCARDB_Bank = $this->StateCooperativeBanks->patchEntity($PCARDB_Bank, $data);

               $PCARDB_Bank=$this->StateCooperativeBanks->save($PCARDB_Bank);
                
               if($PCARDB_Bank) {

                if($data['operation_area_location'] == 1 || $data['operation_area_location'] == 3) // FOR urban or both
                {                    
                    $this->ScbAreaOfOperationLevelUrban->deleteAll(['scb_id' => $PCARDB_Bank->id]);

                    foreach($data['scb_area_of_operation_level_urban'] as $key => $ar_opn_data){               
                    $data['scb_area_of_operation_level_urban'][$key]['scb_id']=$PCARDB_Bank->id;
                    $data['scb_area_of_operation_level_urban'][$key]['state_code']=$data['state_code'];
                    $data['scb_area_of_operation_level_urban'][$key]['created_at']=date('Y-m-d H:i:s');
                    }
                    $ar_opn_post  = $this->ScbAreaOfOperationLevelUrban->newEntity();
                    $ar_opn_post  = $this->ScbAreaOfOperationLevelUrban->patchEntities($ar_opn_post, $data['scb_area_of_operation_level_urban']);
                    $ar_opn_post =  $this->ScbAreaOfOperationLevelUrban->saveMany($ar_opn_post);
                    
                }
                //-----------------------------
                if($data['operation_area_location'] == 2 || $data['operation_area_location'] == 3) // FOR  rural or both
                {                    
                    $this->ScbAreaOfOperationLevel->deleteAll(['scb_id' => $PCARDB_Bank->id]);

                    foreach($data['scb_area_of_operation_level'] as $key => $ar_opn_data){               
                    $data['scb_area_of_operation_level'][$key]['scb_id']=$PCARDB_Bank->id;
                    $data['scb_area_of_operation_level'][$key]['state_code']=$data['state_code'];
                    $data['scb_area_of_operation_level'][$key]['created_at']=date('Y-m-d H:i:s');
                    }
                    $ar_opn_post  = $this->ScbAreaOfOperationLevel->newEntity();
                    $ar_opn_post  = $this->ScbAreaOfOperationLevel->patchEntities($ar_opn_post, $data['scb_area_of_operation_level']);
                    $ar_opn_post =  $this->ScbAreaOfOperationLevel->saveMany($ar_opn_post);                    
                }
                /*
                if($data['is_scheme_implemented'] == 1 && !empty($data['scb_implementing_schemes']))
                {                    
                 //$this->ScbImplementingSchemes->deleteAll(['state_cooperative_bank_id' => $PCARDB_Bank->id]);
                    
                    foreach($data['scb_implementing_schemes'] as $key => $_scheme_data){               
                    $data['scb_implementing_schemes'][$key]['state_cooperative_bank_id']=$PCARDB_Bank->id;
                    }
                    $schemes_post  = $this->ScbImplementingSchemes->newEntity();
                    $schemes_post  = $this->ScbImplementingSchemes->patchEntities($schemes_post, $data['scb_implementing_schemes']);
                    $schemes_post =  $this->ScbImplementingSchemes->saveMany($schemes_post);
                    
                } */


                
                $this->Flash->success(__('The PCARDB data has been saved.'));
                //return $this->redirect(['action' => 'add-member',$result['id']]);
                return $this->redirect(['action' => 'index']);
            } 
            else{
                $this->Flash->error(__('The PCARDB data could not be saved. Please try again.'));
            }           
        }

      // ----------------- add edit ends ----------------------------------
 /*
         $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
         $query->hydrate(false);
         $stateOption = $query->toArray();
*/
		
         if(in_array($logged_user_role_id,[1,2]))
         {
            $stateOption=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$PCARDB_Bank->state_code])->order(['name'=>'ASC']);
         } else {
			 if($logged_user_state_code==4){
				 $state_code1 = [3,4];
				 $stateOption=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code IN'=>$state_code1]);
				$districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$logged_user_state_code])->order(['name'=>'ASC']);
			 }else {
				 
            $stateOption=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$logged_user_state_code]);
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$logged_user_state_code])->order(['name'=>'ASC']); 
			 }
		 }

         $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$PCARDB_Bank->district_code])->group(['block_code']);
         
         $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$PCARDB_Bank->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  

         $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$PCARDB_Bank->gp_code])->order(['village_name'=>'ASC']); 

         $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$PCARDB_Bank->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  

         $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$PCARDB_Bank->urban_local_body_category_code,'state_code'=>$PCARDB_Bank->state_code])->order(['local_body_name'=>'ASC']);
         
         $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$PCARDB_Bank->urban_local_body_code])->order(['ward_name'=>'ASC']);

         $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['authority_name'=>'ASC']);
         
         $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
     
         $areaOfOperationsUrban=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'urban_rural'=>1])->order(['orderseq'=>'ASC']);
         
         $areaOfOperationsRural=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'urban_rural'=>2])->order(['orderseq'=>'ASC']);
        
        $scardb_banks=$this->StateCooperativeBanks->find('list',['keyField'=>'id','valueField'=>'scb_name'])->where(['status'=>1,'scb_dcb_flag'=>3, 'is_delete'=>0])->order(['scb_name'=>'ASC']);
       
       // $areaOfOperationsUrban=$this->ScbAreaOfOperationLevelUrban->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'urban_rural'=>1])->order(['orderseq'=>'ASC']);
       $ScbAreaOfOperationLevel = $this->ScbAreaOfOperationLevel->find('all')->where(['scb_id' => $id])->toArray();
       $ScbAreaOfOperationLevelUrban = $this->ScbAreaOfOperationLevelUrban->find('all')->where(['scb_id' => $id])->toArray();

      $scb_aop_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$ScbAreaOfOperationLevelUrban[0]['local_body_type_code'],'state_code'=>$PCARDB_Bank->state_code])->order(['local_body_name'=>'ASC']);
         
      $scb_aop_localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$ScbAreaOfOperationLevelUrban[0]['local_body_code']])->order(['ward_name'=>'ASC']);
     
      $scb_aop_blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$ScbAreaOfOperationLevel[0]['district_code']])->group(['block_code']);
       
        $years = [];
        $l_year = date('Y')-23;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
        }
         $this->set(compact('scb_aop_blocks','scb_aop_localbodieswards','scb_aop_localbodies','ScbAreaOfOperationLevel','ScbAreaOfOperationLevelUrban','localbodieswards','years', 'localbodies','localbody_types','gps','villages','blocks','districts','stateOption','heading_title','register_authorities','designations','areaOfOperationsUrban','areaOfOperationsRural','scardb_banks','identifcation_no','PCARDB_Bank'));
         
     }
//---------------------------------------

     public function view($id)
     {          
        $this->loadModel('StateCooperativeBanks');
        $this->loadModel('RegistrationAuthorities');
        $this->loadModel('States');  
        $this->loadModel('Districts');
        $this->loadModel('Blocks');           
        $this->loadModel('UrbanLocalBodiesWards');   
        $this->loadModel('DistrictsBlocksGpVillages');   
        $this->loadModel('UrbanLocalBodies');   
        $this->loadModel('Designations');
        $this->loadModel('AreaOfOperations');
        $this->loadModel('ScbImplementingSchemes');  
        $this->loadModel('ScbContactDetails');  
        $this->loadModel('ScbAreaOfOperationLevel');  
        $this->loadModel('ScbAreaOfOperationLevelUrban');  
        
        $logged_user_state_code= $this->request->session()->read('Auth.User.state_code');
        $logged_user_id= $this->request->session()->read('Auth.User.id');
        $logged_user_role_id= $this->request->session()->read('Auth.User.role_id');
                   
        $id=convert_str($id, 1);

        if($id > 0){
            $heading_title='View ';
            $identifcation_no= str_pad($id, 6, '0', STR_PAD_LEFT);            
            
             $PCARDB_Bank = $this->StateCooperativeBanks->get($id, [
                'contain' => ['ScbContactDetails'=>['sort'=>['contact_person'=>'ASC']],'ScbImplementingSchemes'=>['sort'=>['govt_scheme_name'=>'ASC']] ]
            ]);    

           
        }
       

         if(in_array($logged_user_role_id,[1,2]))
         {
            $stateOption=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$PCARDB_Bank->state_code])->order(['name'=>'ASC']);
         } else {
            $stateOption=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$logged_user_state_code]);
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$logged_user_state_code])->order(['name'=>'ASC']); 
         }

         $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$PCARDB_Bank->district_code])->group(['block_code']);
         
         $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$PCARDB_Bank->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  

         $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$PCARDB_Bank->gp_code])->order(['village_name'=>'ASC']); 

         $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$PCARDB_Bank->state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC']);  

         $localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$PCARDB_Bank->urban_local_body_category_code,'state_code'=>$PCARDB_Bank->state_code])->order(['local_body_name'=>'ASC']);
         
         $localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$PCARDB_Bank->urban_local_body_code])->order(['ward_name'=>'ASC']);

         $register_authorities =$this->RegistrationAuthorities->find('list',['keyField'=>'id','valueField'=>'authority_name'])->where(['status'=>1,])->order(['authority_name'=>'ASC']);
         
         $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
     
         $areaOfOperationsUrban=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'urban_rural'=>1])->order(['orderseq'=>'ASC']);
         
         $areaOfOperationsRural=$this->AreaOfOperations->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1,'urban_rural'=>2])->order(['orderseq'=>'ASC']);
        
        $scardb_banks=$this->StateCooperativeBanks->find('list',['keyField'=>'id','valueField'=>'scb_name'])->where(['status'=>1,'scb_dcb_flag'=>3, 'is_delete'=>0])->order(['scb_name'=>'ASC']);
       
    
        $ScbAreaOfOperationLevel = $this->ScbAreaOfOperationLevel->find('all')->where(['scb_id' => $id])->toArray();
       $ScbAreaOfOperationLevelUrban = $this->ScbAreaOfOperationLevelUrban->find('all')->where(['scb_id' => $id])->toArray();

      $scb_aop_localbodies=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1,'localbody_type_code'=>$ScbAreaOfOperationLevelUrban[0]['local_body_type_code'],'state_code'=>$PCARDB_Bank->state_code])->order(['local_body_name'=>'ASC']);
         
      $scb_aop_localbodieswards=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1,'local_body_code'=>$ScbAreaOfOperationLevelUrban[0]['local_body_code']])->order(['ward_name'=>'ASC']);
     
      $scb_aop_blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$ScbAreaOfOperationLevel[0]['district_code']])->group(['block_code']);
       
        $years = [];
        $l_year = date('Y')-23;

        for($i=date('Y'); $i>=$l_year; $i--)
        {
            $years[$i] = $i;
        }
         $this->set(compact('scb_aop_blocks','scb_aop_localbodieswards','scb_aop_localbodies','ScbAreaOfOperationLevel','ScbAreaOfOperationLevelUrban','localbodieswards','years', 'localbodies','localbody_types','gps','villages','blocks','districts','stateOption','heading_title','register_authorities','designations','areaOfOperationsUrban','areaOfOperationsRural','scardb_banks','identifcation_no','PCARDB_Bank'));
         
     }

     public function View_OLD($id)
     {

        $id=convert_str($id, 1);
         $this->loadModel('StateCooperativeBanks');
         $this->loadModel('States');
         $this->loadModel('Districts');
         $this->loadMOdel('Blocks');
         $this->loadMOdel('DistrictsBlocksGpVillages');      
         $this->loadMOdel('ScbImplementingSchemes');
         $this->loadMOdel('ScardbDetails');
         $this->loadModel('CooperativeSocietyTypes');
 
         $StateCooperativeBank = $this->StateCooperativeBanks->get($id, [
             'contain' => ['ScbContactDetails'=>['sort'=>['id'=>'ASC']],'ScardbDetails'=>['sort'=>['id'=>'ASC']],'ScbImplementingSchemes'=>['sort'=>['id'=>'ASC']] ]
         ]);
          
 
         $sectors = [];
         $sectorUrbans = [];
         $area_of_operation_id_urban = '';
         $area_of_operation_id_rural = '';
         
         // Start States for dropdown
           //  $this->loadModel('States');
              $state_code= $this->request->session()->read('Auth.User.state_code');
             
             $all_states = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
             
             $this->set('all_states',$all_states);
         // End States for dropdown
 
 
         // Start States for dropdown
            
             if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
             {
                 $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$StateCooperativeBank->state_code])->order(['name'=>'ASC']);
             } else {
                 $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']); 
             }
                
             //$districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$CooperativeRegistration->state_code])->order(['name'=>'ASC']);
             $this->set('districts',$districts);
         // End States for dropdown
         
 
         
             //$blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$CooperativeRegistration->district_code])->order(['name'=>'ASC']);
             $blocks=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$StateCooperativeBank->district_code])->group(['block_code']);
             $this->set('blocks',$blocks);
         // end Blocks for dropdown
 
 
         // Start Gram Panchayats for dropdown
             
         

         $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$StateCooperativeBank->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
         $this->set('gps',$gps);  
         // end Gram Panchayats for dropdown
 
 
         // Start villages for dropdown
             
         
         $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$StateCooperativeBank->gp_code])->order(['village_name'=>'ASC']); 
         $this->set('villages',$villages);  
         // end villages for dropdown
         
 
 
 
         // Start CooperativeSocietyTypes for dropdown
            
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
         $localbody_types_find=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();  
         $localbodies_find=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_code','valueField'=>'local_body_name'])->where(['status'=>1])->order(['local_body_name'=>'ASC'])->toArray();
         $localbodieswards_find=$this->UrbanLocalBodiesWards->find('list',['keyField'=>'ward_code','valueField'=>'ward_name'])->where(['status'=>1])->order(['ward_name'=>'ASC'])->toArray();
 
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
         
 
         $this->set(compact('localbody_types_find','localbodies_find','localbodieswards_find','villages_find','blocks_find','panchayats_find','districts_find','states_find','StateCooperativeBank','states','CooperativeSocietyTypes','areaOfOperationsUrban','areaOfOperationsRural','PrimaryActivities','SecondaryActivities','PresentFunctionalStatus','pActivities','years','register_authorities','sectors','designations','water_body_type','sectorUrbans','area_of_operation_id_urban','area_of_operation_id_rural','locationOfHeadquarter','dcb_scb_other_members','dccb_banks','society'));
     }
 

     public function pcardbAddRow()
     {
         $this->viewBuilder()->setLayout('ajax');
         $i = $this->request->data('show_contact_persons')+1;        
         $this->loadModel('Designations');
         $designations=$this->Designations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['orderseq'=>'ASC'])->where(['status'=>1]);
      
         $this->set(compact('i','designations'));
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

        $localbody_types=$this->UrbanLocalBodies->find('list',['keyField'=>'localbody_type_code','valueField'=>'localbody_type_name'])->where(['status'=>1,'state_code'=>$state_code])->group(['localbody_type_code'])->order(['localbody_type_name'=>'ASC'])->toArray();

        $this->set('districts',$districts);
       $this->set('uw2',$uw2);
       $this->set('localbody_types',$localbody_types);
        
    }






 
    
}