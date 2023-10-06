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
class UnderComputerizeReportsController extends AppController
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
           
  
  
    //   if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
    //       $s_primary_activity = trim($this->request->query['primary_activity']);
    //       $condtion['sector_of_operation'] = $this->request->query['primary_activity'];
    //       $condtiongp['sector_of_operation'] = $this->request->query['primary_activity'];
    //       $this->set('s_primary_activity', $s_primary_activity);
    //   }
  
     
      
      if ($page_length != 'all' && is_numeric($page_length)) {
          $this->paginate = [
              'limit' => $page_length,
          ];
      }
      
  
      
         $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
         $query->hydrate(false);
         $stateOption = $query->toArray();
         $this->set('sOption',$stateOption);  
  

          $state_code= $this->States->find('all')->where(['flag'=>1,  $condtiongp])->order(['name'=>'ASC'])->select(['state_code']);
          $this->paginate = ['limit' => 20];
          $district_codes = $this->paginate($state_code);
           
            
          $pacsn = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
          $pacsn = $pacsn->select(['state_code','count' => $pacsn->func()->count('members_of_society')])->where(['sector_of_operation_type'=>1,'is_draft'=>'0','status'=>'1','is_approved !=' => '2'])->group(['state_code'])->toarray();

          
  
  
            $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);            
            $pacs = $pacs->select(['state_code','count' => $pacs->func()->count('members_of_society')])->where(['sector_of_operation_type'=>1,'is_draft'=>'0','status'=>'1','is_approved !=' => '2' ,'under_computerised_scheme'=> '1'])->group(['state_code'])->toarray();

            
          //  $this->paginate = ['limit' => 36];
          //  $district_codes = $this->paginate($state_code);
            
          
           $fpacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count'])->group(['state_code']);  
           $fpacs = $fpacs->select(['state_code','count' => $fpacs->func()->count('members_of_society')])->where(['sector_of_operation_type'=>1,'is_draft'=>'0','status'=>'1','is_approved !=' => '2', 'under_computerised_scheme'=> '0'])->group(['state_code'])->toarray();
        
           $this->set(compact('pacs','fpacs','pacsn','district_codes','state_code'));
      
     if($this->request->is('get'))
     {
      if(!empty($this->request->query['export_excel']))
      {
        
         $queryExport =  $this->States->find('all')->where(['flag'=>1,$condtion_districtA])->order(['name'=>'ASC'])->select(['state_code']);
  
           
              $queryExport->hydrate(false);
              $ExportResultData = $queryExport->toArray();
              $fileName = "report".date("d-m-y:h:s").".xls";
              

              $headerRow = array("S.No", "State Name","Total PACS","PACS Under Computerized Scheme","PACS Without Computerized Scheme");
              $data = array();
              $i=1;
             
             
             
               $state_name =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
               $sOption = $state_name->toArray();
            

              foreach($ExportResultData As $rows){
                
                   // print_r($ExportResultData);
                 //    $rows;
                 //    echo $all_usern4[$all_usern2[$rows['user_payment']['updated_by']]];
             
             $data[] = [$i, $sOption[$rows['state_code']],$pacsn[$rows['state_code']],$pacs[$rows['state_code']],$fpacs[$rows['state_code']]];
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