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
 * CooperativeRegistrations Controller
 *
 * @property \App\Model\Table\CooperativeRegistrationsTable $CooperativeRegistrations
 *
 * @method \App\Model\Entity\State[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RegisterWisePacController extends AppController
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
        $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','getdccb','getfederationlevel','approval','bulkapproval','getUrbanRural']);
    }

    public function index()
    {
        set_time_limit(0);
      ini_set('memory_limit', '-1');
      $this->loadModel('States');
      $this->loadModel('Districts');   
      $this->loadModel('PrimaryActivities');    
      $this->loadMOdel('CooperativeRegistrations');  
  
      
      $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
      $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
  
      if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
          $s_state = trim($this->request->query['state']);
          $condtiongp['state_code'] = $this->request->query['state'];
         
          $this->set('s_state', $s_state);
      }
  
      if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
          $d_district_code = trim($this->request->query['district_code']);
          $this->set('d_district_code', $d_district_code);
      }
           
  
  
      if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
          $s_primary_activity = trim($this->request->query['primary_activity']);
          $condtion['sector_of_operation'] = $this->request->query['primary_activity'];
          $condtiongp['sector_of_operation'] = $this->request->query['primary_activity'];
          $this->set('s_primary_activity', $s_primary_activity);
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
  
       if (isset($this->request->query['state']) && $this->request->query['state'] !='') 
       {
             $query =$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name','conditions'=>['state_code'=>$this->request->query['state']],'order' => ['name' => 'ASC']]);
     
             $query->hydrate(false);
             $dist_opt = $query->toArray();
             $this->set('dist_opt',$dist_opt);
        }
  
          $district_codes = [];
          $dairies = [];
          $pacs = [];
          $fisheries = [];
  
     
  
    
        //  $district_codes = $this->CooperativeRegistrations->find('all',array('conditions' => array('district_code IS NOT NULL')))->select(['district_code'])->where(['state_code'=>$this->request->query['state']])->group(['district_code'])->extract('district_code');
          $condtion_district=[];
          $condtion_districtA=[];
          $condtion_location=[];
          if(!empty($this->request->query['district_code']))
          {
              $condtion_district['district_code'] = $this->request->query['district_code'];
          }
          if(!empty($this->request->query['state']))
          {
              $condtion_district['state_code'] = $this->request->query['state'];
              $condtion_districtA['state_code'] = $this->request->query['state'];
          }
          if(!empty($this->request->query['location']))
          {
              $condtion_location['location_of_head_quarter'] = $this->request->query['location'];
          }

          if($s_primary_activity==1)
          {
              $sector_search_condition[] = "sector_of_operation IN(1,20,22)";
          }else{
              $sector_search_condition[] = "sector_of_operation = '" . $s_primary_activity . "'";
          }
  
          //print_r($condtion_district);
  
          $state_code= $this->States->find('all')->where(['flag'=>1,$condtion_districtA])->order(['name'=>'ASC'])->select(['state_code']);
  
          //$this->set('district_codes',$state_code);  
  
         
         // $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
         // $pacs = $pacs->select(['state_code','count' => $pacs->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['1','20','22'],'is_draft'=>'0','status'=>'1','date_registration <' => 	'1923-12-31'])->group(['state_code'])->toarray();
         $this->paginate = ['limit' => 20];

         $district_codes = $this->paginate($state_code);
  
  
  
            $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
            $pacs = $pacs->select(['state_code','count' => $pacs->func()->count('members_of_society')])->where([$sector_search_condition,'is_draft'=>'0','status'=>'1','is_approved !=' => '2' ,'date_registration  >=' => '1900-01-01','date_registration <='=> '1923-12-31'])->group(['state_code'])->toarray();

            
          //  $this->paginate = ['limit' => 36];
          //  $district_codes = $this->paginate($state_code);
            
          
           $fpacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);  
           $fpacs = $fpacs->select(['state_code','count' => $fpacs->func()->count('members_of_society')])->where([$sector_search_condition,'is_draft'=>'0','status'=>'1','is_approved !=' => '2', 'date_registration  >='=> '1924-01-01','date_registration <='=> '1947-12-31'])->group(['state_code'])->toarray();
           
           $lpacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
           $lpacs = $lpacs->select(['state_code','count' => $lpacs->func()->count('members_of_society')])->where([$sector_search_condition,'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'date_registration  >='=> '1948-01-01','date_registration <='=> '1950-12-31'])->group(['state_code'])->toarray();
           
           $npacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
           $npacs = $npacs->select(['state_code','count' => $npacs->func()->count('members_of_society')])->where([$sector_search_condition,'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'date_registration  >='=> '1951-01-01','date_registration <='=> '1970-12-31'])->group(['state_code'])->toarray();
           
           $n1pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
           $n1pacs = $n1pacs->select(['state_code','count' => $n1pacs->func()->count('members_of_society')])->where([$sector_search_condition,'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'date_registration  >='=> '1971-01-01','date_registration <='=> '1990-12-31'])->group(['state_code'])->toarray();
           
           $n2pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
           $n2pacs = $n2pacs->select(['state_code','count' => $n2pacs->func()->count('members_of_society')])->where([$sector_search_condition,'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'date_registration >='=> '1991-01-01','date_registration <='=> '2010-12-31'])->group(['state_code'])->toarray();
           
           $n3pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
           $n3pacs = $n3pacs->select(['state_code','count' => $n3pacs->func()->count('members_of_society')])->where([$sector_search_condition,'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'date_registration  >='=> '2011-01-01','date_registration <='=> date('Y-m-d')])->group(['state_code'])->toarray();
           
           //fisheries
        //    $fis = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);           
        //    $fis = $fis->select(['state_code','count' => $fis->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2', 'date_registration <' => '1923-01-01'])->group(['state_code'])->toarray();
         
        //   $ffis = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        //   $ffis = $ffis->select(['state_code','count' => $ffis->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2' ,'date_registration >='=> '1923-01-01','date_registration <='=> '1947-12-31'])->group(['state_code'])->toarray();
          
        //   $lfis = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        //   $lfis = $lfis->select(['state_code','count' => $lfis->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2'  , 'date_registration >'=> '1947-12-31','date_registration <='=> '1950-12-31'])->group(['state_code'])->toarray();
          
        //   $nfis = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        //   $nfis = $nfis->select(['state_code','count' => $nfis->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2'  ,'date_registration >'=> '1950-12-31','date_registration <='=> '1957-12-31'])->group(['state_code'])->toarray();
           
        //   $nfis1 = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        //   $nfis1 = $nfis1->select(['state_code','count' => $nfis1->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2'  ,'date_registration >'=> '1957-12-31','date_registration <='=> '1997-12-31'])->group(['state_code'])->toarray();
            
        //   $nfis2 = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        //   $nfis2 = $nfis2->select(['state_code','count' => $nfis2->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2'  , 'date_registration >'=> '1997-12-31','date_registration <='=> '2017-12-31'])->group(['state_code'])->toarray();
           
        //   $nfis3 = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        //   $nfis3 = $nfis3->select(['state_code','count' => $nfis3->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2'  , 'date_registration >'=> '2017-12-31','date_registration <='=> '2023-12-31'])->group(['state_code'])->toarray();
           
           //dairy 
        //    $dai = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);           
        //    $dai = $dai->select(['state_code','count' => $dai->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['9','37'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2' ,'date_registration <' => '1923-01-01'])->group(['state_code'])->toarray();
         
        //   $fdai = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        //   $fdai = $fdai->select(['state_code','count' => $fdai->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['9','37'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2', 'date_registration >='=> '1923-01-01','date_registration <='=> '1947-12-31'])->group(['state_code'])->toarray();
          
        //   $ldai = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        //   $ldai = $ldai->select(['state_code','count' => $ldai->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['9','37'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'date_registration >'=> '1947-12-31','date_registration <='=> '1950-12-31'])->group(['state_code'])->toarray();
          
        //   $ndai = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        //   $ndai = $ndai->select(['state_code','count' => $ndai->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['9','37'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'date_registration >'=> '1950-12-31','date_registration <='=> '1957-12-31'])->group(['state_code'])->toarray();
          
        //   $ndai1 = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        //   $ndai1 = $ndai1->select(['state_code','count' => $ndai1->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['9','37'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'date_registration >'=> '1957-12-31','date_registration <='=> '1997-12-31'])->group(['state_code'])->toarray();
           
        //   $ndai2 = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        //   $ndai2 = $ndai2->select(['state_code','count' => $ndai2->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['9','37'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2' ,  'date_registration >'=> '1997-12-31','date_registration <='=> '2017-12-31'])->group(['state_code'])->toarray();
                        
        //   $ndai3 = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        //   $ndai3 = $ndai3->select(['state_code','count' => $ndai3->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['9','37'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'date_registration >'=> '2017-12-31','date_registration <='=> '2023-12-31'])->group(['state_code'])->toarray();
               
        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
     //   $village_code_array = $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1, $condtion_districtA] ,'keyField'=>'village_code','valueField'=>'village_name'] )->group(['village_code'])->toarray();
    
     $this->set(compact('primary_activities','dai','fdai','ldai','ndai','ndai1','ndai2','ndai3','fis','ffis','lfis','nfis','nfis1','nfis2','nfis3','pacs','fpacs','lpacs','npacs','n1pacs','n2pacs','n3pacs','district_codes','state_code'));
      
     if($this->request->is('get'))
     {
      if(!empty($this->request->query['export_excel']))
      {
         $queryExport =  $this->States->find('all')->where(['flag'=>1,$condtion_districtA])->order(['name'=>'ASC'])->select(['state_code']);
  
           
              $queryExport->hydrate(false);
              $ExportResultData = $queryExport->toArray();
              $fileName = "Payment_user".date("d-m-y:h:s").".xls";
              

              $headerRow = array("S.No", "State","(before 1923)", "(1923-1947)", "(1948-1950)", "(1951-1957)","(1958-1977)","(1978-2017)","(2018-2023)");
              $data = array();
              $i=1;
             
              if($s_primary_activity =='1'){
                $head = $pacs;
                $head1 = $fpacs;
                $head2 = $lpacs;
                $head3 = $npacs;
                $head4 = $n1pacs;
                $head5 = $n2pacs;
                $head6 = $n3pacs;


               }else if($s_primary_activity =='10')
               {
                $head = $fis;
                $head1 = $ffis;
                $head2 = $lfis;
                $head3 = $nfis;
                $head4 = $nfis1;
                $head5 = $nfis2;
                $head6 = $nfis3;
                
               }else if($s_primary_activity =='9'){
                $head = $dai;
                $head1 = $fdai;
                $head2 = $ldai;
                $head3 = $ndai;
                $head4 = $ndai1;
                $head5 = $ndai2;
                $head6 = $ndai3;
               }

               $state_name =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
               $sOption = $state_name->toArray();
            

              foreach($ExportResultData As $rows){
                
                   // print_r($ExportResultData);
                 //    $rows;
                 //    echo $all_usern4[$all_usern2[$rows['user_payment']['updated_by']]];
             
             $data[] = [$i, $sOption[$rows['state_code']],$head[$rows['state_code']],$head1[$rows['state_code']],$head2[$rows['state_code']] , $head3[$rows['state_code']],$head4[$rows['state_code']] , $head5[$rows['state_code']], $head6[$rows['state_code']]];
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
}