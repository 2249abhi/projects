<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * Roles Controller
 *
 * @property \App\Model\Table\UsersTable $Roles
 *
 * @method \App\Model\Entity\Role[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExtendedLoanController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        
        // set_time_limit(0);
        // ini_set('memory_limit', '-1');     
    

        $this->loadMOdel('PrimaryActivities');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadModel('States');
        $this->loadModel('Users');
        $this->loadModel('Roles');
        $this->loadModel('Districts');

         
        $condtion=[];
        $search_condition=[];
        $condtiongp=[];
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $s_state = trim($this->request->query['state']);   
            
            $condtion['state_code'] = $this->request->query['state'];
            $condtiongp['state_code'] = $this->request->query['state'];
            $this->set('s_state', $s_state);
           }
        
        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);
        }

        
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }
      
      
        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }

        $all_usern4 =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $this->set('all_usern4',$all_usern4);
        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
        $this->set('districts',$districts);
     
        set_time_limit(0);
        ini_set('memory_limit', '-1');
  
          
        $this->loadMOdel('CooperativeRegistrations');            
        $this->loadMOdel('CooperativeRegistrationPacs');
            
            $users = $this->CooperativeRegistrations->find('all', [
                    'contain' => ['CooperativeRegistrationPacs','CooperativeRegistrationFishery'],                  
                    'conditions' => ['CooperativeRegistrations.is_draft'=> 0,'CooperativeRegistrations.is_approved !='=> 2,'CooperativeRegistrations.status' => 1,'CooperativeRegistrations.sector_of_operation IN' => ['1','59','20','22']]])->toArray();
            
                    $statearray=array();
                    foreach($users as $key=>$value)
                   {
                    foreach($value['cooperative_registration_pacs'] as $key=>$value1)
                    {
                        
                        
                        if($value1['pack_total_outstanding_loan'] == 0 )
                        {
                             $statearray[$value['state_code']]['noloan'] +=1;
                        }
                        if($value1['pack_total_outstanding_loan'] >0 &&  $value1['pack_total_outstanding_loan'] < 1000000)
                        {
                            $statearray[$value['state_code']]['less_10'] +=1;
                        }
                        if($value1['pack_total_outstanding_loan'] >=1000000 &&  $value1['pack_total_outstanding_loan'] < 10000000)
                        {
                            
                         $statearray[$value['state_code']]['10_to_less_1'] +=1;
                        }
                        if($value1['pack_total_outstanding_loan'] >=10000000 &&  $value1['pack_total_outstanding_loan'] < 50000000)
                        {
                            
                         $statearray[$value['state_code']]['1cr_to_less_1'] +=1;
                        }
                        if($value1['pack_total_outstanding_loan'] >=50000000 &&  $value1['pack_total_outstanding_loan'] < 100000000)
                        {
                            
                         $statearray[$value['state_code']]['5cr_to_less_1'] +=1;
                        }
                        if($value1['pack_total_outstanding_loan'] >= 100000000 )
                        {
                             $statearray[$value['state_code']]['10cr_to_less_1'] +=1;
                        }
                    }
                   }
                   $sOption = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toarray();
                   $sOption1 = $this->CooperativeRegistrations->find('all')->where([$condtion,'status'=>1,'is_approved !='=>2,'is_draft'=>0])->group(['state_code']);
       
                    $this->paginate = ['limit' => 20];
                    $$sOption1 = $this->paginate($sOption1); 
                    $this->set(compact('users','sOption','states','state_name','district_name','npacs','details','sOption1','statearray'));
                    if($this->request->is('get'))
                    {
                     if(!empty($this->request->query['export_excel']))
                     {
                        $queryExport =  $this->CooperativeRegistrations->find('all')->where([$condtion,'status'=>1,'is_approved !='=>2,'is_draft'=>0])->group(['state_code']);
                 
                          
                             $queryExport->hydrate(false);
                             $ExportResultData = $queryExport->toArray();
                             $fileName = "Payment_user".date("d-m-y:h:s").".xls";
                             
               
                             $headerRow = array("S.No", "State","No loan extended to member", "Less than 10 lakh", "10 lakh to less than one crore", "One crore to five crore","five crore to ten crore","More than ten crore");
                             $data = array();
                             $i=1;
               
                             $sOption = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toarray();
                          
                             $users = $this->CooperativeRegistrations->find('all', [
                               'contain' => ['CooperativeRegistrationPacs','CooperativeRegistrationFishery'],                  
                               'conditions' => ['CooperativeRegistrations.is_draft'=> 0,'CooperativeRegistrations.is_approved !='=> 2,'CooperativeRegistrations.status' => 1,'CooperativeRegistrations.sector_of_operation IN' => ['1','59','20','22']]])->toArray();
                                
                           $statearray=array();
               
                           foreach($users as $key=>$value)
                           {
                               foreach($value['cooperative_registration_pacs'] as $key=>$value1)
                               {
                                   
                                   
                                   if($value1['pack_total_outstanding_loan'] == 0 )
                                   {
                                        $statearray[$value['state_code']]['noloan'] +=1;
                                   }
                                   // echo '<pre>';
                                   // print_r($statearray);
                                   // die;
                                   if($value1['pack_total_outstanding_loan'] >0 &&  $value1['pack_total_outstanding_loan'] < 1000000)
                                   {
                                       $statearray[$value['state_code']]['less_10'] +=1;
                                   }
                                   if($value1['pack_total_outstanding_loan'] >=1000000 &&  $value1['pack_total_outstanding_loan'] < 10000000)
                                   {
                                       
                                    $statearray[$value['state_code']]['10_to_less_1'] +=1;
                                   }
                                   if($value1['pack_total_outstanding_loan'] >=10000000 &&  $value1['pack_total_outstanding_loan'] < 50000000)
                                   {
                                       
                                    $statearray[$value['state_code']]['1cr_to_less_1'] +=1;
                                   }
                                   if($value1['pack_total_outstanding_loan'] >=50000000 &&  $value1['pack_total_outstanding_loan'] < 100000000)
                                   {
                                       
                                    $statearray[$value['state_code']]['5cr_to_less_1'] +=1;
                                   }
                                   if($value1['pack_total_outstanding_loan'] >= 100000000 )
                                   {
                                        $statearray[$value['state_code']]['10cr_to_less_1'] +=1;
                                   }
                              }
                            
                           }
                           
               
                             foreach($ExportResultData As $rows){
                              $data[] = [$i, $sOption[$rows['state_code']],$statearray[$rows['state_code']]['noloan'],$statearray[$rows['state_code']]['less_10'], $statearray[$rows['state_code']]['10_to_less_1'],$statearray[$rows['state_code']]['1cr_to_less_1'],$statearray[$rows['state_code']]['5cr_to_less_1'],$statearray[$rows['state_code']]['10cr_to_less_1']];
                                $i++;
                            }
                            // die;
                            $this->exportInExcelNew($fileName, $headerRow, $data);           
               
                     }
                    }
                      
                  
                   }
   
    


   

    public function getDistricts(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $state_code=$this->request->query('state_code');    
            $this->loadMOdel('Districts');
           //$districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

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

}
