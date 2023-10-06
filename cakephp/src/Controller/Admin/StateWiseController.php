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
class StateWiseController extends AppController
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
    $this->loadModel('States');
    $this->loadModel('Districts');
    $this->loadModel('SubDistricts');
    $this->loadModel('PrimaryActivities');
    $this->loadMOdel('UrbanLocalBodies');
    $this->loadMOdel('CooperativeRegistrations');
    $this->loadMOdel('DistrictsBlocksGpVillages');

    set_time_limit(0);
    ini_set('memory_limit', '-1');
    $search_condition = array();
    $condtion_districtA=[];
    $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
    $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

    if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
        $s_state = trim($this->request->query['state']);
        $condtion_districtA['state_code'] = $this->request->query['state'];
       
        $this->set('s_state', $s_state);
        $search_condition[] = "state_code like '%" . $state . "%'";
    }

    if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
        $d_district_code = trim($this->request->query['district_code']);
        $condtion_districtA['district_code'] = $this->request->query['district_code'];
        $this->set('d_district_code', $d_district_code);
        $search_condition[] = "district_code like '%" . $district_code . "%'";
    }
         

    if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
        $s_location = trim($this->request->query['location']);
        $this->set('s_location', $s_location);
        // $search_condition[] = "location like '%" . $location . "%'";
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

    

    
        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);


  
         $query2 =$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name','conditions'=>['state_code'=>$this->request->query['state']],'order' => ['name' => 'ASC']]);
         $query2->hydrate(false);
         $dist_opt = $query2->toArray();
         $this->set('dist_opt',$dist_opt);
   

    $district_codes = [];
    $dairies = [];
    $pacs = [];
    $fisheries = [];

   

  

        //print_r($condtion_district);

       $state_code1= $this->States->find('all')->where(['flag'=>1,$condtion_districtA])->order(['name'=>'ASC'])->select(['state_code']);
       $state_code= $this->DistrictsBlocksGpVillages->find('all')->where(['status'=>1,$condtion_districtA])->order(['state_name'=>'ASC'])->select(['district_code','state_code'])->group(['district_code']); 
     //  print_r($state_code);

        //Dairies
        //$dairies = $this->CooperativeRegistrations->find('list',['conditions' => ['district_code IS NOT NULL'],'keyField'=>'state_code','valueField'=>'count']);

       
        $dairies = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);            
        $dairies = $dairies->select(['district_code','count' => $dairies->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [9,37], 'is_draft'=>0, 'status'=>1, 'is_approved !='=>2, $search_condition])->group(['district_code'])->toArray();
       
        //Pacs
        $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);            
        $pacs = $pacs->select(['district_code','count' => $pacs->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [1,59,20,22],$search_condition,'is_draft'=>0, 'is_approved !='=>2,'status'=>1])->group(['district_code'])->toArray();          
       
        //Fisheries
        $fisheries = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);
        $fisheries = $fisheries->select(['district_code','count' => $fisheries->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [10,43],'is_draft'=>0,'is_approved !='=>2, 'status'=>1, $search_condition])->group(['district_code'])->toArray();
        
        $this->paginate = ['limit' => 36];
        $district_codes = $this->paginate($state_code);

    
                 

    //$arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        
    $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
    //sum of total no. of pac
    

    $this->set(compact('district_codes','arr_districts','panchayats','villages','dairies','pacs','fisheries','district_nodal_tatal','state_code','district_nodal_tatal_without_state','pacs_today','dairies_today','fisheries_today','urban_bodys','urban_bodys_wards','sum_quantity','sum_quantity2','sum_quantity3'));
     
    if($this->request->is('get')){
        if(!empty($this->request->query['export_excel'])) {
            $queryExport =  $this->DistrictsBlocksGpVillages->find('all')->where(['status'=>1,$condtion_districtA])->order(['state_name'=>'ASC'])->select(['district_code','state_code'])->group(['district_code']); 
            $queryExport->hydrate(false);
            $ExportResultData = $queryExport->toArray();
            $fileName = "Report-".date("d-m-y:h:s").".xls";
            $headerRow = array("S.No",  "State", "Districts",  "PACS (Total Data Entered)","Dairy (Total Data Entered)","Fisheries (Total Data Entered)","PACS, Dairy & Fishery (Total Data Entered)");
                $data = array();
                $i=1;
                $stateOption =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']])->toArray();
               
                
                $dairies = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);            
                $dairies = $dairies->select(['district_code','count' => $dairies->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [9,37], 'is_draft'=>0, 'status'=>1, 'is_approved !='=>2, $search_condition])->group(['district_code'])->toArray();
               
                //Pacs
                $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);            
                $pacs = $pacs->select(['district_code','count' => $pacs->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [1,59,20,22],$search_condition,'is_draft'=>0, 'is_approved !='=>2,'status'=>1])->group(['district_code'])->toArray();          
               
                //Fisheries
                $fisheries = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'count']);
                $fisheries = $fisheries->select(['district_code','count' => $fisheries->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [10,43],'is_draft'=>0,'is_approved !='=>2, 'status'=>1, $search_condition])->group(['district_code'])->toArray();
                $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
               
                foreach($ExportResultData As $rows){
                    $total_report = $pacs[$rows['district_code']] + $dairies[$rows['district_code']] + $fisheries[$rows['district_code']];
                    $data[]=array($i, $stateOption[$rows['state_code']],$arr_districts[$rows['district_code']],$pacs[$rows['district_code']], $dairies[$rows['district_code']], $fisheries[$rows['district_code']], $total_report);
                    $i++;
                } 
                $this->exportInExcel($fileName, $headerRow, $data);
        } 
    }

}

   
public function getDistricts(){
    $this->viewBuilder()->setLayout('ajax');
    if($this->request->is('ajax')){
        $state_code=$this->request->query('state_code');    
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