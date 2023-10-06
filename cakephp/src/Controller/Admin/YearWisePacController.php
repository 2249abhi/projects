<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * CooperativeRegistrations Controller
 *
 * @property \App\Model\Table\CooperativeRegistrationsTable $CooperativeRegistrations
 *
 * @method \App\Model\Entity\State[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class YearWisePacController extends AppController
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
  
     
  
    
          //$district_codes = $this->CooperativeRegistrations->find('all',array('conditions' => array('district_code IS NOT NULL')))->select(['district_code'])->where(['state_code'=>$this->request->query['state']])->group(['district_code'])->extract('district_code');
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
          
  
          //print_r($condtion_district);
  
          $state_code= $this->States->find('all')->where(['flag'=>1,$condtion_districtA])->order(['name'=>'ASC'])->select(['state_code']);
           $this->paginate = ['limit' => 20];
         $district_codes = $this->paginate($state_code);
      
  
          
  
     
      
         
          //Pacs
          $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
          $pacs = $pacs->select(['state_code','count' => $pacs->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['1','20','22'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2','audit_complete_year <' => '1923'])->group(['state_code'])->toarray();
        
       
         $fpacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);  
         $fpacs = $fpacs->select(['state_code','count' => $fpacs->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['1','20','22'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2','audit_complete_year >='=> '1923','audit_complete_year <='=> '1943'])->group(['state_code'])->toarray();
         
         $lpacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
         $lpacs = $lpacs->select(['state_code','count' => $lpacs->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['1','20','22'],'is_draft'=>'0','status'=>'1', 'is_approved !=' => '2','audit_complete_year >='=> '1944','audit_complete_year <='=> '1963'])->group(['state_code'])->toarray();
         
         $npacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
         $npacs = $npacs->select(['state_code','count' => $npacs->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['1','20','22'],'is_draft'=>'0','status'=>'1', 'is_approved !=' => '2','audit_complete_year >='=> '1964','audit_complete_year <='=> '1983'])->group(['state_code'])->toarray();
        
          $n1pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
           $n1pacs = $n1pacs->select(['state_code','count' => $n1pacs->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['1','20','22'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'audit_complete_year >='=> '1984','audit_complete_year <='=> '2003'])->group(['state_code'])->toarray();
           
           $n2pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
           $n2pacs = $n2pacs->select(['state_code','count' => $n2pacs->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['1','20','22'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'audit_complete_year >='=> '2004','audit_complete_year <='=> '2023'])->group(['state_code'])->toarray();
           
         //fisheries
         $fis = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);           
         $fis = $fis->select(['state_code','count' => $fis->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2','audit_complete_year <' => '1923'])->group(['state_code'])->toarray();
       
        $ffis = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        $ffis = $ffis->select(['state_code','count' => $ffis->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2','audit_complete_year >='=> '1923','audit_complete_year <='=> '1943'])->group(['state_code'])->toarray();
        //$ffis = $ffis->select(['state_code','count' => $ffis->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2','audit_complete_year between''=> '1923','and'=> '1943'])->group(['state_code'])->toarray();
       // print_r( $ffis);
        
        $lfis = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        $lfis = $lfis->select(['state_code','count' => $lfis->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2','audit_complete_year >='=> '1944','audit_complete_year <='=> '1963'])->group(['state_code'])->toarray();
        
        $nfis = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        $nfis = $nfis->select(['state_code','count' => $nfis->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2','audit_complete_year >='=> '1964','audit_complete_year <='=> '1983'])->group(['state_code'])->toarray();
        
        $nfis1 = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        $nfis1 = $nfis1->select(['state_code','count' => $nfis1->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2'  , 'audit_complete_year >='=> '1984','audit_complete_year <='=> '2003'])->group(['state_code'])->toarray();
          
        $nfis2 = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        $nfis2 = $nfis2->select(['state_code','count' => $nfis2->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['10','43'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2'  ,'audit_complete_year >='=> '2004','audit_complete_year <='=> '2023'])->group(['state_code'])->toarray();
         
         //dairy 
         $dai = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);           
         $dai = $dai->select(['state_code','count' => $dai->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['9','37'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2','audit_complete_year <' => '1923'])->group(['state_code'])->toarray();
       
        $fdai = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        $fdai = $fdai->select(['state_code','count' => $fdai->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['9','37'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2','audit_complete_year >='=> '1923','audit_complete_year <='=> '1943'])->group(['state_code'])->toarray();
        
        $ldai = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        $ldai = $ldai->select(['state_code','count' => $ldai->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['9','37'],'is_draft'=>'0','status'=>'1', 'is_approved !=' => '2','audit_complete_year >='=> '1944','audit_complete_year <='=> '1963'])->group(['state_code'])->toarray();
        
        $ndai = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        $ndai = $ndai->select(['state_code','count' => $ndai->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['9','37'],'is_draft'=>'0','status'=>'1', 'is_approved !=' => '2','audit_complete_year >='=> '1964','audit_complete_year <='=> '1983'])->group(['state_code'])->toarray();
         
        $ndai1 = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        $ndai1 = $ndai1->select(['state_code','count' => $ndai1->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['9','37'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2' ,  'audit_complete_year >='=> '1984','audit_complete_year <='=> '2003'])->group(['state_code'])->toarray();
         
        $ndai2 = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
        $ndai2 = $ndai2->select(['state_code','count' => $ndai2->func()->count('members_of_society')])->where(['sector_of_operation IN' => ['9','37'],'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'audit_complete_year >='=> '2004','audit_complete_year <='=> '2023'])->group(['state_code'])->toarray();
                      
      
        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        //$village_code_array = $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1, $condtion_districtA] ,'keyField'=>'village_code','valueField'=>'village_name'] )->group(['village_code'])->toarray();
  
  
      //$arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
          
      $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
    
  
      $this->set(compact('village_code_array','primary_activities','dai','fdai','ldai','ndai','ndai1','ndai2','fis','nfis1','nfis2','ffis','lfis','nfis','fpacs','lpacs','npacs','n1pacs','n2pacs','district_codes','arr_districts','panchayats','villages','dairies','pacs','fisheries','district_nodal_tatal','state_code','district_nodal_tatal_without_state','pacs_today','dairies_today','fisheries_today','urban_bodys','urban_bodys_wards','sum_quantity','sum_quantity2','sum_quantity3','sum_quantity4'));
      
   }
  public function village()
   {
      $this->loadModel('States');
      $this->loadModel('Districts');
      $this->loadModel('SubDistricts');
      $this->loadModel('PrimaryActivities');
      $this->loadMOdel('UrbanLocalBodies');
      $this->loadMOdel('CooperativeRegistrations');
      $this->loadMOdel('DistrictsBlocksGpVillages');

      $CooperativeRegistration = $this->CooperativeRegistrations->get($id, [
        'contain' => []
      ]);

      $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
      $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
  
      if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
          $s_state = trim($this->request->query['state']);
          $condtiongp['state_code'] = $this->request->query['state'];
         
          $this->set('s_state', $s_state);
      }
      if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
        $s_state = trim($this->request->query['state']);
        $condtiongp['sector_of_operation'] = $this->request->query['primary_activity'];

        print_r($condtiongp);
       die;
       
        $this->set('s_state', $s_state);
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