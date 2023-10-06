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
use Cake\Datasource\ConnectionManager;
/**
 * CooperativeRegistrations Controller
 *
 * @property \App\Model\Table\CooperativeRegistrationsTable $CooperativeRegistrations
 *
  * @method \App\Model\Entity\State[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AreaOperationBlockReportController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','AvailableArea','getallindavilagecovereduncoveredpac']);
    }


    
    public function exportInExcelTwo($fileName, $headerRow, $data, $header1=null,$data1=null)
    {
        header('Content-type: application/ms-excel'); /// you can set csv format
        header('Content-Disposition: attachment; filename='.$fileName);
        ini_set('max_execution_time', 1600); //increase max_execution_time to 10 min if data set is very large
        if($header1!='')
        {
        $fileContent = implode("\t ", $header1)."\n";
        $fileContent .= implode("\t ", $headerRow)."\n";
        }else{
            $fileContent = implode("\t ", $headerRow)."\n";
        }
        foreach($data as $result) {
            $fileContent .=  implode("\t ", $result)."\n";
        }
        ob_end_clean();
        echo $fileContent;
        exit;
    }

    ######################################
    // cover area wise report 

    ####################################

    public function index()
    {
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $this->loadMOdel('PrimaryActivies');
        $this->loadMOdel('CoverageAreas');

      
      //  $viewasd =$this->CoverageAreas->find('all')->where(['id'=>1,])->order(['id'=>'ASC'])->toArray();

       
        $condtion=[];
        $search_condition=[];
        $condtiongp=[];
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        $block_opt = [];

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $s_state = trim($this->request->query['state']);   
            
            $condtion['state_code'] = $this->request->query['state'];
            $condtiongp['state_code'] = $this->request->query['state'];           

            $dist_opt = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$s_state])->order(['name'=>'ASC'])->toArray();
            $this->set('dist_opt', $dist_opt);
            $this->set('s_state', $s_state);
        }

        if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
            $district_code = trim($this->request->query['district_code']);
            $condtion['district_code'] = $this->request->query['district_code'];
            $condtiongp['district_code'] = $this->request->query['district_code'];
         //  $search_condition['area_of_operation_level.district_code']= $this->request->query['district_code'];
            $this->set('district_code', $district_code);

            //get all blocks
            $block_opt=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$district_code])->group(['block_code'])->toArray();

            $this->set('block_opt', $block_opt);

        }
        if (isset($this->request->query['block_code']) && $this->request->query['block_code'] !='') {
            $block_code = trim($this->request->query['block_code']);
            $condtion['block_code'] = $this->request->query['block_code'];
            $condtiongp['block_code'] = $this->request->query['block_code'];
         //  $search_condition['area_of_operation_level.district_code']= $this->request->query['district_code'];
            $this->set('block_code', $block_code);
        }
             

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $condtion_p['id'] = $this->request->query['primary_activity'];
            if($this->request->query['primary_activity']==1)
            {
                $condtion['sector_of_operation IN'] = array('1','59','20','22');
            }else
            {
                $condtion['sector_of_operation'] = $this->request->query['primary_activity'];
            }
            $this->set('s_primary_activity', $s_primary_activity);
        }

        $condtion['is_draft']=0;
		 $condtion['is_approved !=']=2;
		  $condtion['status']=1;
       
                    
        
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }


        $this->loadModel('PrimaryActivities');

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }

        if($this->request->query['covered'] ==2)
        {
            $covered==2;
        }else{
            $covered==1;
        }
           //print_r($condtion);
           set_time_limit(0);
           ini_set('memory_limit', '2000000000000000000M');


        if(!empty($this->request->query['state']) && !empty($this->request->query['primary_activity']))
        {

            if(!empty($this->request->query['export_excel'])) { 

                $response_array=$this->getcovereduncoverdvillage($s_state,$s_primary_activity);

                $district_array= $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'district_code','valueField'=>'district_name'] )->group(['district_code'])->toarray();

                $bloack_array= $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'block_code','valueField'=>'block_name'] )->group(['block_code'])->toarray();
                $gram_panchayat_array= $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'] )->group(['gram_panchayat_code'])->toarray();

                

                $total_village_code = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'district_code'])->where([$condtion])->group(['village_code'])->order(['id'=>'ASC'])->count();
                
                $queryExport = $this->CoverageAreas->find('all')->where([$condtion])->group(['panchayat_code'])->order(['district_code'=>'ASC','block_code'=>'ASC','panchayat_code'=>'ASC','village_code'=>'ASC']);

                $queryExport->hydrate(false);
                 $ExportResultData = $queryExport->toArray();
                $fileName = "area_operation_report".date("d-m-y:h:s").".xls";
                $data = array(); $i=1;
                $k=1;

                $total_district = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'district_code'])->where([$condtion])->group(['district_code'])->order(['id'=>'ASC'])->count();
                   
                $header1 =array(" ", "Total District",  count($district_array),"Number Of block", count($bloack_array),"No of Gram Panchayat", count($gram_panchayat_array),"Total Covered Village", $total_village_code );
                    
                    $headerRow = array("S.No", "District", "Block", "Panchayat", "Covered Village", "Total Covered Village", "Uncovered Village","Total Uncovered Village");
                    $indistrict_array  =   [];
                    $inbloack_array    =   [];
                    $inpanchayat_array =  [];
                    foreach($ExportResultData As $rows){

                        $coveredvillage=implode(' , ', $response_array['covered_village'][$rows['panchayat_code']]);
                        $uncoveredvillage=implode(' , ', $response_array['un_covered_village'][$rows['panchayat_code']]);

                        $district_name='';
                        $bloack_name='';
                        $panchayat_name='';

                        if(!in_array($rows['district_code'],$indistrict_array))
                         {
                                $district_name=$district_array[$rows['district_code']];
                         }
                         if(!in_array($rows['block_code'],$inbloack_array))
                         {
                                $bloack_name=$bloack_array[$rows['block_code']];
                         }
                         if(!in_array($rows['panchayat_code'],$inpanchayat_array))
                         {
                                $panchayat_name=$gram_panchayat_array[$rows['panchayat_code']];
                         }

                        $data[] = [$i,  $district_name, $bloack_name,  $panchayat_name, $coveredvillage, count($response_array['covered_village'][$rows['panchayat_code']]), $uncoveredvillage, count($response_array['un_covered_village'][$rows['panchayat_code']])];
                       
                        $indistrict_array[]     =   $rows['district_code'];
                        $inbloack_array[]       =   $rows['block_code'];
                        $inpanchayat_array[]    =   $rows['panchayat_code'];

                        $i++;
                    }
             
                $this->exportInExcelTwo($fileName, $headerRow, $data,$header1,$data1);
    
            }
          

            $pActivities =$this->CoverageAreas->find('all')->where([$condtion])->group(['panchayat_code'])->order(['district_code'=>'ASC','block_code'=>'ASC','panchayat_code'=>'ASC','village_code'=>'ASC']);

           // $total_state = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'state_code'])->where([$condtion])->group(['state_code'])->order(['id'=>'ASC'])->count();
            $this->set('total_state',$total_state);

            $total_district = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'district_code'])->where([$condtion])->group(['district_code'])->order(['id'=>'ASC'])->count();
            $this->set('total_district',$total_district);

            $total_block = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'district_code'])->where([$condtion])->group(['block_code'])->order(['id'=>'ASC'])->count();

            $this->set('total_block',$total_block);

            $total_panchayat_code_data = $this->CoverageAreas->find('list',['keyField'=>'panchayat_code','valueField'=>'id'])->where([$condtion])->group(['panchayat_code'])->order(['id'=>'ASC'])->toArray();
            
            $total_panchayat_code = count($total_panchayat_code_data);
            $this->set('total_panchayat_code',$total_panchayat_code);

            $total_village_code = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'district_code'])->where([$condtion])->group(['village_code'])->order(['id'=>'ASC'])->count();

            $this->set('total_village_code',$total_village_code);

            $this->paginate = ['limit' => 20];
            $pActivities = $this->paginate($pActivities);  

             $bloack_array= $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'block_code','valueField'=>'block_name'] )->group(['block_code'])->toarray();

             $gram_panchayat_array= $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'] )->group(['gram_panchayat_code'])->toarray();

            $total_panchayat_code_data = array_keys($total_panchayat_code_data);
           
            $total_uncovered_gps_name = array_values(array_diff_key($gram_panchayat_array, array_flip($total_panchayat_code_data)));
            $this->set('total_uncovered_gps_name',$total_uncovered_gps_name);
            
            $village_code_array = $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'village_code','valueField'=>'village_name'] )->group(['village_code'])->toarray();

             $response_array=$this->getcovereduncoverdvillage($s_state,$s_primary_activity);
             $this->set('response_array',$response_array);


        }

        $state_array = $this->States->find('list', [ 'conditions' => ['flag'=>1], 'keyField'=>'state_code','valueField'=>'name'])->toArray();
      
        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       
        $sOption = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
       // $this->loadModel('AreaOfOperations');
       // $area_of_operation_array= $this->AreaOfOperations->find('list',['conditions' => ['status'=>1] ,'keyField'=>'id','valueField'=>'name'] )->toarray();

        
        $this->set('primary_activities',$primary_activities);

         $this->set(compact('district_codes','arr_districts','panchayats','villages','state_code','pActivities','sOption','area_of_operation_array','state_array','bloack_array','gram_panchayat_array','village_code_array'));
        
    }

    ######################################
    // cover area wise report block level
    // created by ravindra 7-02-2023 

    ####################################

    public function areaoperation()
    {
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $this->loadMOdel('PrimaryActivies');
        $this->loadMOdel('CoverageAreas');

      
      //  $viewasd =$this->CoverageAreas->find('all')->where(['id'=>1,])->order(['id'=>'ASC'])->toArray();

       
        $condtion=[];
        $search_condition=[];
        $condtiongp=[];
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $s_state = trim($this->request->query['state']);   
            
            $condtion['state_code'] = $this->request->query['state'];
            $condtiongp['state_code'] = $this->request->query['state'];           

            $dist_opt = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$s_state])->order(['name'=>'ASC'])->toArray();
            $this->set('dist_opt', $dist_opt);
            $this->set('s_state', $s_state);
        }

        if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
            $district_code = trim($this->request->query['district_code']);
            $condtion['district_code'] = $this->request->query['district_code'];
            $condtiongp['district_code'] = $this->request->query['district_code'];

            $bloack_array_d= $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,'district_code'=>$district_code] ,'keyField'=>'block_code','valueField'=>'block_name'] )->group(['block_code'])->toarray();
         //  $search_condition['area_of_operation_level.district_code']= $this->request->query['district_code'];
            $this->set('district_code', $district_code);
            $this->set('bloack_array_d', $bloack_array_d);
        }
        if (isset($this->request->query['block_code']) && $this->request->query['block_code'] !='') {
            $block_code = trim($this->request->query['block_code']);
            $condtion['block_code'] = $this->request->query['block_code'];
            $condtiongp['block_code'] = $this->request->query['block_code'];
         //  $search_condition['area_of_operation_level.district_code']= $this->request->query['district_code'];
            $this->set('block_code', $block_code);
        }
             

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $condtion_p['id'] = $this->request->query['primary_activity'];
            if($this->request->query['primary_activity']==1)
            {
                $condtion['sector_of_operation IN'] = array('1','59','20','22');
            }else
            {
                $condtion['sector_of_operation'] = $this->request->query['primary_activity'];
            }
            $this->set('s_primary_activity', $s_primary_activity);
        }

        $condtion['is_draft']=0;
		 $condtion['is_approved !=']=2;
		  $condtion['status']=1;
       
                    
        
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }

        $this->loadModel('PrimaryActivities');

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }

        if($this->request->query['covered'] ==2)
        {
            $covered==2;
        }else{
            $covered==1;
        }
           //print_r($condtion);
           set_time_limit(0);
           ini_set('memory_limit', '2000000000000000000M');


        if(!empty($this->request->query['state']) && !empty($this->request->query['primary_activity']))
        {

            if(!empty($this->request->query['export_excel'])) { 

                $response_array=$this->getcovereduncoverdvillage($s_state,$s_primary_activity);

                $district_array= $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'district_code','valueField'=>'district_name'] )->group(['district_code'])->toarray();

                $bloack_array= $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'block_code','valueField'=>'block_name'] )->group(['block_code'])->toarray();
                $gram_panchayat_array= $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'] )->group(['gram_panchayat_code'])->toarray();

                $total_village_code = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'district_code'])->where([$condtion])->group(['village_code'])->order(['id'=>'ASC'])->count();
                
                $queryExport = $this->CoverageAreas->find('all')->where([$condtion])->group(['panchayat_code'])->order(['district_code'=>'ASC','block_code'=>'ASC','panchayat_code'=>'ASC','village_code'=>'ASC']);

                $queryExport->hydrate(false);
                 $ExportResultData = $queryExport->toArray();
                $fileName = "area_operation_report".date("d-m-y:h:s").".xls";
                $data = array(); $i=1;
                $k=1;

                $total_district = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'district_code'])->where([$condtion])->group(['district_code'])->order(['id'=>'ASC'])->count();
                   
                $header1 =array(" ", "Total District",  count($district_array),"Number Of block", count($bloack_array),"No of Gram Panchayat", count($gram_panchayat_array),"Total Covered Village", $total_village_code );
                    
                    $headerRow = array("S.No", "Panchayat", "Covered Village", "Total Covered Village", "Uncovered Village","Total Uncovered Village");
                    $indistrict_array  =   [];
                    $inbloack_array    =   [];
                    $inpanchayat_array =  [];
                    foreach($ExportResultData As $rows){

                        $coveredvillage=implode(' , ', $response_array['covered_village'][$rows['panchayat_code']]);
                        $uncoveredvillage=implode(' , ', $response_array['un_covered_village'][$rows['panchayat_code']]);

                        $district_name='';
                        $bloack_name='';
                        $panchayat_name='';

                        if(!in_array($rows['district_code'],$indistrict_array))
                         {
                                $district_name=$district_array[$rows['district_code']];
                         }
                         if(!in_array($rows['block_code'],$inbloack_array))
                         {
                                $bloack_name=$bloack_array[$rows['block_code']];
                         }
                         if(!in_array($rows['panchayat_code'],$inpanchayat_array))
                         {
                                $panchayat_name=$gram_panchayat_array[$rows['panchayat_code']];
                         }

                        $data[] = [$i, $panchayat_name, $coveredvillage, count($response_array['covered_village'][$rows['panchayat_code']]), $uncoveredvillage, count($response_array['un_covered_village'][$rows['panchayat_code']])];
                       
                        $indistrict_array[]     =   $rows['district_code'];
                        $inbloack_array[]       =   $rows['block_code'];
                        $inpanchayat_array[]    =   $rows['panchayat_code'];

                        $i++;
                    }
             
                $this->exportInExcelTwo($fileName, $headerRow, $data,$header1,$data1);
    
            }
          

            $pActivities =$this->CoverageAreas->find('all')->where([$condtion])->group(['panchayat_code'])->order(['district_code'=>'ASC','block_code'=>'ASC','panchayat_code'=>'ASC','village_code'=>'ASC']);

            $taolat_pac =$this->CoverageAreas->find('all')->where([$condtion])->group(['village_code'])->order(['district_code'=>'ASC','block_code'=>'ASC','panchayat_code'=>'ASC','village_code'=>'ASC'])->toarray();

                $panchayat_pac=[];
              
            foreach($taolat_pac as $key=>$value)
            {
                $panchayat_pac[$value['panchayat_code']] +=1;
            }

            $this->set('panchayat_pac',$panchayat_pac);

           // $total_state = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'state_code'])->where([$condtion])->group(['state_code'])->order(['id'=>'ASC'])->count();
            $this->set('total_state',$total_state);

            $total_district = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'district_code'])->where([$condtion])->group(['district_code'])->order(['id'=>'ASC'])->count();
            $this->set('total_district',$total_district);

            $total_block = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'district_code'])->where([$condtion])->group(['block_code'])->order(['id'=>'ASC'])->count();

            $this->set('total_block',$total_block);

            die('success');
            $total_panchayat_code = $this->CoverageAreas->find('list',['keyField'=>'panchayat_code','valueField'=>'id'])->where([$condtion])->group(['panchayat_code'])->order(['id'=>'ASC'])->toAray();
            echo '<pre>';
            print_r($total_panchayat_code);die;

            $this->set('total_panchayat_code',$total_panchayat_code);

            $total_village_code = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'district_code'])->where([$condtion])->group(['village_code'])->order(['id'=>'ASC'])->count();

            

            $this->set('total_village_code',$total_village_code);

            $this->paginate = ['limit' => 20];
            $pActivities = $this->paginate($pActivities);  

             $bloack_array= $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'block_code','valueField'=>'block_name'] )->group(['block_code'])->toarray();

             $gram_panchayat_array= $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'] )->group(['gram_panchayat_code'])->toarray();

             $village_code_array = $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'village_code','valueField'=>'village_name'] )->group(['village_code'])->toarray();

             $response_array=$this->getcovereduncoverdvillage($s_state,$s_primary_activity);
             $this->set('response_array',$response_array);


        }

        $state_array = $this->States->find('list', [ 'conditions' => ['flag'=>1], 'keyField'=>'state_code','valueField'=>'name'])->toArray();
      
        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       
        $sOption = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
       // $this->loadModel('AreaOfOperations');
       // $area_of_operation_array= $this->AreaOfOperations->find('list',['conditions' => ['status'=>1] ,'keyField'=>'id','valueField'=>'name'] )->toarray();

        
        $this->set('primary_activities',$primary_activities);

         $this->set(compact('district_codes','arr_districts','panchayats','villages','state_code','pActivities','sOption','area_of_operation_array','state_array','bloack_array','gram_panchayat_array','village_code_array'));
        
    }

    public function getcovereduncoverdvillage($state_code=null,$primary_activities=null,$district_code=null)
    {
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $this->loadMOdel('PrimaryActivies');
        $this->loadMOdel('CoverageAreas');
        set_time_limit(0);
        ini_set('memory_limit', '2000000000000000000M');
        $condtion['is_draft']=0;
        if($primary_activities !='')
        {
            if($primary_activities==1)
            {
                $condtion['sector_of_operation IN'] = array('1','59','20','22');
            }else
            {
                $condtion['sector_of_operation'] = $primary_activities;
            }
        }
        if($state_code!='')
        {
            $condtion['state_code'] = $state_code;
            $condtiongp['state_code'] = $state_code;
            
        }
        if($district_code !='')
        {
            $condtion['district_code'] = $district_code;
            $condtiongp['district_code'] = $district_code;
        }

        $covered_village_code_array=[];
        $result=$this->CoverageAreas->find('all')->where([$condtion])->group(['village_code'])->order(['id'=>'ASC']);
        foreach($result as $key=>$value)
        {
            $covered_village_code_array[]=$value['village_code'];
        }


        $village_code_array = $this->DistrictsBlocksGpVillages->find('all',['conditions' => ['status'=>1,$condtiongp]] )->group(['village_code'])->toarray();
            $response=[];
        foreach($village_code_array as $key=>$value)
        {
            if(in_array($value['village_code'],$covered_village_code_array) )
            {
                $response['covered_village'][$value['gram_panchayat_code']][]=$value['village_name'];
            }else{
                $response['un_covered_village'][$value['gram_panchayat_code']][]=$value['village_name'];
            }
        }
            return  $response;
    }


    public function getallindavilagecovereduncoveredpac($state=null,$distict=null)
    {
        set_time_limit(0);
        ini_set('memory_limit', '2000000000000000000M');

        $this->loadMOdel('DistrictsBlocksGpVillages');
        $this->loadMOdel('PrimaryActivies');
        $this->loadMOdel('CoverageAreas');
        $condtion['sector_of_operation IN'] = array('1','59','20','22');
        $condtion['is_draft']=0;
        $condtiongp=[];

        if($state !='')
        {
           // $condtion['state_code']=$state; 
            $condtiongp['state_code']=$state; 
        }
        if($distict !='')
        {
           // $condtion['district_code']=$distict; 
            $condtiongp['district_code']=$distict; 
          

        }

            $responsearry=[];
       

        $state_code_array= $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'id','valueField'=>'state_code'] )->group(['state_code'])->toarray();
      
        foreach($state_code_array as $key=>$value)
        {
            $condtion['state_code']=$value;
            $coverage_areaa = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'village_code'])->where([$condtion])->group(['village_code'])->order(['id'=>'ASC'])->count();

            $condtiongpa['state_code']=$value; 
            $count_state_code_array= $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongpa] ,'keyField'=>'id','valueField'=>'state_code'] )->group(['village_code'])->count();


            
            $responsearry[$value]['total_covered_area']  = $coverage_areaa;
            $responsearry[$value]['total_record']      = $count_state_code_array;
            $responsearry[$value]['total_un_covered_area']  = $count_state_code_array -$coverage_areaa;

            $pesentvalue=($coverage_areaa*100)/$count_state_code_array;
            $responsearry[$value]['covered_percent']  = number_format($pesentvalue,2);
           
        
        }
            print_r($responsearry);
            die;
      //  echo json_encode($responsearry);
       // return  echo json_encode($responsearry);


    }

    public function stateAreaReport()
    {
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $this->loadMOdel('PrimaryActivies');
        $this->loadMOdel('CoverageAreas');

      
      //  $viewasd =$this->CoverageAreas->find('all')->where(['id'=>1,])->order(['id'=>'ASC'])->toArray();

       
        $condtion=[];
        $search_condition=[];
        $condtiongp=[];
        $condtion_dst=[];
       
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $s_state = trim($this->request->query['state']);   
            
            $condtion['state_code'] = $this->request->query['state'];
            $condtiongp['state_code'] = $this->request->query['state'];
            

            $dist_opt = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$s_state])->order(['name'=>'ASC'])->toArray();
            $this->set('dist_opt', $dist_opt);
            $this->set('s_state', $s_state);
        }

        if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
            $district_code = trim($this->request->query['district_code']);
            $condtion['district_code'] = $this->request->query['district_code'];

            $condtion_dst['district_code'] = $this->request->query['district_code'];
            $condtiongp['district_code'] = $this->request->query['district_code'];
         //  $search_condition['area_of_operation_level.district_code']= $this->request->query['district_code'];
            $this->set('district_code', $district_code);
        }
        if (isset($this->request->query['block_code']) && $this->request->query['block_code'] !='') {
            $district_code = trim($this->request->query['block_code']);
            $condtion['block_code'] = $this->request->query['block_code'];
            $condtiongp['block_code'] = $this->request->query['block_code'];
         //  $search_condition['area_of_operation_level.district_code']= $this->request->query['district_code'];
            $this->set('block_code', $block_code);
        }
             

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $condtion_p['id'] = $this->request->query['primary_activity'];

            $condtion['sector_of_operation'] = $this->request->query['primary_activity'];
            $this->set('s_primary_activity', $s_primary_activity);
        }

        $condtion['is_draft']=0;
       
                    
        
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }


        $this->loadModel('PrimaryActivities');

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }

        if($this->request->query['covered'] ==2)
        {
            $covered==2;
        }else{
            $covered==1;
        }
            //print_r($condtion);

        if(!empty($this->request->query))
        {

        
            set_time_limit(0);
            ini_set('memory_limit', '2000000000000000000M');


            $districts_result = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code'),$condtion_dst])->order(['name'=>'ASC']);

           // $pActivities =$this->CoverageAreas->find('all')->where([$condtion])->order(['id'=>'ASC']);


            $total_district = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'district_code'])->where([$condtion])->group(['district_code'])->order(['id'=>'ASC'])->count();
            $this->set('total_district',$total_district);



            $total_block = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'district_code'])->where([$condtion])->group(['block_code'])->order(['id'=>'ASC'])->count();

            $this->set('total_block',$total_block);

            $total_panchayat_code = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'district_code'])->where([$condtion])->group(['panchayat_code'])->order(['id'=>'ASC'])->count();
            $this->set('total_panchayat_code',$total_panchayat_code);

            $total_village_code = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'village_code'])->where([$condtion])->group(['village_code'])->order(['id'=>'ASC'])->count();

            $total_village_code_array = $this->CoverageAreas->find('list',['keyField'=>'id','valueField'=>'village_code'])->where([$condtion])->group(['village_code'])->order(['id'=>'ASC'])->toarray();

            $this->set('total_village_code_array',$total_village_code_array);

            $this->set('total_village_code',$total_village_code);
            
            $this->paginate = ['limit' => 20];
            $districts_result = $this->paginate($districts_result);                    

            $bloack_array= $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'block_code','valueField'=>'block_name'] )->group(['block_code'])->toarray();

            $gram_panchayat_array= $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'] )->group(['gram_panchayat_code'])->toarray();

            $village_code_array = $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['status'=>1,$condtiongp] ,'keyField'=>'village_code','valueField'=>'village_name'] )->group(['village_code'])->toarray();

            $village_code_arrayAll = $this->DistrictsBlocksGpVillages->find('all',['conditions' => ['status'=>1,$condtiongp]] )->group(['village_code'])->order(['district_code'=>'ASC'])->toarray();

            $adbgpvArry=[];
            foreach($village_code_arrayAll as $key=>$value)
            {
                $adbgpvArry[$value['district_code']][$value['village_code']]['block_name'][]            =$value['block_name'];
                $adbgpvArry[$value['district_code']][$value['village_code']]['gram_panchayat_name'][]   =$value['gram_panchayat_name'];
                $adbgpvArry[$value['district_code']][$value['village_code']]['village_name'][]          =$value['village_name'];
                $adbgpvArry[$value['district_code']][$value['village_code']]['village_code'][]                 =$value['village_code'];
            }

        //    echo "<pre>";
        //   print_r($village_code_arrayAll);
        //    die;
           $this->set('adbgpvArry',$adbgpvArry);

        $this->set('village_code_arrayAll',$village_code_arrayAll);


        }
       
        $state_array = $this->States->find('list', [ 'conditions' => ['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')], 'keyField'=>'state_code','valueField'=>'name'])->toArray();
      
        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code')])->order(['name'=>'ASC'])->toArray();
       
        $sOption = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code') ])->order(['name'=>'ASC'])->toArray();
        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->loadModel('AreaOfOperations');
        $area_of_operation_array= $this->AreaOfOperations->find('list',['conditions' => ['status'=>1] ,'keyField'=>'id','valueField'=>'name'] )->toarray();

        $this->set('primary_activities',$primary_activities);

         $this->set(compact('district_codes','arr_districts','panchayats','villages','state_code','pActivities','sOption','area_of_operation_array','state_array','bloack_array','gram_panchayat_array','village_code_array','districts_result'));
        
    }


    public function StateAreaUrbanReport()
    {


        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('PrimaryActivies');
        $this->loadMOdel('UrbanLocalBodiesWards');

       
        $condtion=[];
        $search_condition=[];
        $condtiongp=[];
        $condtion_dst=[];
       
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $s_state = trim($this->request->query['state']);             
            $condtion['state_code'] = $this->request->query['state'];           
            $this->set('s_state', $s_state);
        }
             

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $condtion_p['id'] = $this->request->query['primary_activity'];
            $condtion_dst['sector_of_operation'] = $this->request->query['primary_activity'];
            $this->set('s_primary_activity', $s_primary_activity);
        }

        $condtion_dst['is_draft']=0;
       
                    
        
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }


        $this->loadModel('PrimaryActivities');

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }

        if($this->request->query['covered'] ==2)
        {
            $covered==2;
        }else{
            $covered==1;
        }
        set_time_limit(0);
        ini_set('memory_limit', '2000000000000000000M');
            //print_r($condtion);
         

        if(!empty($this->request->query))
        {
           
            
            $this->loadMOdel('CoverageUrban');

            $total_local_body_covered = $this->CoverageUrban->find('list',['keyField'=>'id','valueField'=>'local_body_code'])->where(['state_code'=>$this->request->session()->read('Auth.User.state_code'),$condtion_dst])->group(['local_body_code'])->order(['local_body_code'=>'ASC'])->toarray();


            $total_local_body_uncovered = $this->UrbanLocalBodiesWards->find('all',['conditions' => ['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code'),'ward_name !='=>'']] )->group(['local_body_code'])->order(['ward_name'=>'ASC'])->toarray();

            $total_local_body_ward_coverd = $this->CoverageUrban->find('list',['keyField'=>'id','valueField'=>'local_body_code'])->where(['state_code'=>$this->request->session()->read('Auth.User.state_code'),$condtion_dst])->group(['locality_ward_code'])->order(['locality_ward_code'=>'ASC'])->toarray();


            $ward_code_All = $this->UrbanLocalBodiesWards->find('all',['conditions' => ['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code'),'ward_name !='=>'']] )->group(['ward_code'])->order(['ward_name'=>'ASC']);

            $ward_arrayAll = $this->UrbanLocalBodiesWards->find('all',['conditions' => ['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code'),'ward_name !='=>'']] )->group(['ward_code'])->order(['ward_name'=>'ASC'])->toarray();

            $adbgpvArry=[];
            foreach($ward_arrayAll as $key=>$value)
            {
                $adbgpvArry[$value['local_body_code']][$value['local_body_code']]['ward_name'][]           =    $value['ward_name'];
                $adbgpvArry[$value['local_body_code']][$value['local_body_code']]['local_body_name'][]     =    $value['local_body_name'];
                $adbgpvArry[$value['local_body_code']][$value['local_body_code']]['ward_code'][]           =    $value['ward_code'];
              
            }
                $ward_code=[];
            foreach($total_local_body_ward_coverd as $key1=>$value1)
            {
                $ward_code[]=$value1['locality_ward_code'];
            }


            $this->paginate = ['limit' => 20];
            $ward_code_All = $this->paginate($ward_code_All);

        }
       
       
       
        $sOption = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code') ])->order(['name'=>'ASC'])->toArray();
        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
      

        $this->set('primary_activities',$primary_activities);

         $this->set(compact('state_code','pActivities','sOption','state_array','ward_code_All','adbgpvArry','ward_code','total_local_body_covered','total_local_body_uncovered','total_local_body_ward_coverd','ward_arrayAll'));

    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index_old()
    {
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $this->loadMOdel('PrimaryActivies');
        $this->loadMOdel('CoverageAreas');

      
      //  $viewasd =$this->CoverageAreas->find('all')->where(['id'=>1,])->order(['id'=>'ASC'])->toArray();

       
        $condtion=[];
        $search_condition=[];
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $s_state = trim($this->request->query['state']);   
            
            $condtion['state_code'] = $this->request->query['state'];
         
            $this->set('s_state', $s_state);
        }

        if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
            $district_code = trim($this->request->query['district_code']);
            $condtion['district_code'] = $this->request->query['district_code'];
           $search_condition['area_of_operation_level.district_code']= $this->request->query['district_code'];
            $this->set('district_code', $district_code);
        }
             

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $condtion_p['id'] = $this->request->query['primary_activity'];
            $this->set('s_primary_activity', $s_primary_activity);
        }

       
        
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }


        $this->loadModel('PrimaryActivities');

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }

        $pActivities=$this->PrimaryActivities->find('all')->where(['status'=>1, $condtion_p])->order(['orderseq'=>'ASC']);
         $this->loadModel('AreaOfOperations');
        //  $areaOfOperations=$this->AreaOfOperations->find('all')->where(['status'=>1])->order(['orderseq'=>'ASC'])->toarray();

          $society_array=[];       
          $conn = ConnectionManager::get('default');   

          
               ############## total area of operation ################
               $total_cr_area= $this->AreaOfOperations->find('all', [ 'conditions' => ['status'=>1]])->count();
               ############################################################

                ############## total state of operation ################
                $total_cr_state= $this->States->find('all', [ 'conditions' => ['flag'=>1]])->count();
                ############################################################
                 ############## total state of operation ################
                 $total_cr_district= $this->Districts->find('all', [ 'conditions' => ['flag'=>1]])->count();
                 ############################################################

                 ############## total Block operation ################
                    set_time_limit(0);
                    ini_set('memory_limit', '2000000000000000000M');
                                
                 $this->loadMOdel('Blocks');

                 $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->count();
                 ############################################################
                 ############## total gram_pnachayt code operation ################
                 $this->loadMOdel('DistrictsBlocksGpVillages');

                 $panchayats = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC'])->count();
                 ####################################################  
                 
               $villages = $this->DistrictsBlocksGpVillages->find('All')->where(['status'=>1,'village_code' !=''])->group(['village_code'])->order(['village_code'=>'ASC'])->count();
                
             

            foreach($pActivities as $key=>$value)
            { 

                $cr_total_socity = $this->CooperativeRegistrations->find('all', ['order' => ['created' => 'DESC'], 'conditions' => ['sector_of_operation'=>$value['id'],'is_draft'=>0,$condtion]])->count();

                   // print_r(count($cr_total_socity));

               $cr_area= $this->CooperativeRegistrations->find('all', ['order' => ['created' => 'DESC'], 'conditions' => ['sector_of_operation'=>$value['id'],'is_draft'=>0,$condtion]])->group(['area_of_operation_id'])->count();             


               $cr_state= $this->CooperativeRegistrations->find('all', ['order' => ['created' => 'DESC'], 'conditions' => ['sector_of_operation'=>$value['id'],'is_draft'=>0,$condtion]])->group(['state_code'])->count();            

               $p_id=$value['id'];


        
              $stmt = $conn->execute("SELECT count(area_of_operation_level.cooperative_registrations_id) as total , area_of_operation_level.state_code,area_of_operation_level.district_code,cooperative_registrations.sector_of_operation  FROM cooperative_registrations  INNER JOIN area_of_operation_level ON area_of_operation_level.cooperative_registrations_id=cooperative_registrations.id where cooperative_registrations.is_draft=0 and cooperative_registrations.sector_of_operation =  $p_id $searchString group by area_of_operation_level.district_code");
            //  $results = $stmt ->fetchAll('assoc');
                $results =  $stmt->count();

            

              $stmt_block = $conn->execute("SELECT count(area_of_operation_level.cooperative_registrations_id) as total , area_of_operation_level.state_code,area_of_operation_level.district_code,cooperative_registrations.sector_of_operation  FROM cooperative_registrations  INNER JOIN area_of_operation_level ON area_of_operation_level.cooperative_registrations_id=cooperative_registrations.id where cooperative_registrations.is_draft=0 and cooperative_registrations.sector_of_operation =  $p_id $searchString group by area_of_operation_level.block_code");             
              $results_block = $stmt_block ->fetchAll('assoc');

              $stmt_panchayat_code = $conn->execute("SELECT count(area_of_operation_level.cooperative_registrations_id) as total , area_of_operation_level.state_code,area_of_operation_level.district_code,cooperative_registrations.sector_of_operation  FROM cooperative_registrations  INNER JOIN area_of_operation_level ON area_of_operation_level.cooperative_registrations_id=cooperative_registrations.id where cooperative_registrations.is_draft=0 and cooperative_registrations.sector_of_operation =  $p_id $searchString  group by  area_of_operation_level.panchayat_code");
              $results_panchayat_code = $stmt_panchayat_code ->fetchAll('assoc');

              $stmt_village_code = $conn->execute("SELECT count(area_of_operation_level.cooperative_registrations_id) as total , area_of_operation_level.state_code,area_of_operation_level.district_code,cooperative_registrations.sector_of_operation  FROM cooperative_registrations  INNER JOIN area_of_operation_level ON area_of_operation_level.cooperative_registrations_id=cooperative_registrations.id where cooperative_registrations.is_draft=0 and cooperative_registrations.sector_of_operation =  $p_id $searchString  group by  area_of_operation_level.village_code");
              $results_stmt_village_code = $stmt_village_code ->fetchAll('assoc');

   
                $society_array[$value['id']]['area_count']              = $cr_area;
                $society_array[$value['id']]['total_area_count']        = $total_cr_area;
                $society_array[$value['id']]['state_count']             = $cr_state;
                $society_array[$value['id']]['total_state_count']       = $total_cr_state;
                $society_array[$value['id']]['district_count']          = $results;
                $society_array[$value['id']]['total_district_count']    = $total_cr_district;
                $society_array[$value['id']]['block_count']             = sizeof($results_block);
                $society_array[$value['id']]['total_block_count']       = $blocks;
                $society_array[$value['id']]['panchayat_code']          = sizeof($results_panchayat_code);
                $society_array[$value['id']]['total_panchayat_code']    = $panchayats;
                $society_array[$value['id']]['village_code']            = sizeof($results_stmt_village_code);
                $society_array[$value['id']]['total_village_code']      = $villages;
                $society_array[$value['id']]['total_sociaty']           = $cr_total_socity;                
                
          
            }      

            $this->paginate = ['limit' => 20];
            $pActivities = $this->paginate($pActivities);        

        //$arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $sOption = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

        $this->set('primary_activities',$primary_activities);

    
        $this->set(compact('district_codes','arr_districts','panchayats','villages','dairies','pacs','fisheries','district_nodal_tatal','state_code','district_nodal_tatal_without_state','pacs_today','dairies_today','fisheries_today','society_array','pActivities','sOption'));
        
    }

    public function AvailableArea($primary_id=null)
    {
        //echo "primary_id".$primary_id;
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('PrimaryActivities');
        $pActivities=$this->PrimaryActivities->find('all')->where(['status'=>1, 'id'=>$primary_id])->order(['orderseq'=>'ASC'])->toarray();

        $area_of_operation=[];

        $cr_area = $this->CooperativeRegistrations->find('all', ['fields'=>array('area_of_operation_id'),'order' => ['created' => 'DESC'], 'conditions' => ['sector_of_operation'=>$primary_id,'is_draft'=>0]])->group(['area_of_operation_id'])->toarray();

        foreach($cr_area as $key => $value)
        {
            $area_of_operation[]=$value['area_of_operation_id'];
        }
        
         ##############  area of operation ################
         $this->loadMOdel('AreaOfOperations');
         
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }
         $area_result = $this->AreaOfOperations->find('all', [ 'conditions' => ['status'=>1,'id IN'=>$area_of_operation]]);
         $this->paginate = ['limit' => 20];
            $area_result = $this->paginate($area_result);  

         ############################################################      
        $this->set(compact('area_result','pActivities'));

    }

    public function PendingArea($primary_id=null)
    {
        //echo "primary_id".$primary_id;
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('PrimaryActivities');
        $pActivities=$this->PrimaryActivities->find('all')->where(['status'=>1, 'id'=>$primary_id])->order(['orderseq'=>'ASC'])->toarray();

        $area_of_operation=[];

        $cr_area = $this->CooperativeRegistrations->find('all', ['fields'=>array('area_of_operation_id'),'order' => ['created' => 'DESC'], 'conditions' => ['sector_of_operation'=>$primary_id,'is_draft'=>0]])->group(['area_of_operation_id'])->toarray();

        foreach($cr_area as $key => $value)
        {
            $area_of_operation[]=$value['area_of_operation_id'];
        }
        
         ##############  area of operation ################
         $this->loadMOdel('AreaOfOperations');
         
        if ($page_length != 'all' && is_numeric($page_length)) 
        {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }
         $area_result = $this->AreaOfOperations->find('all', [ 'conditions' => ['status'=>1,'id NOT IN'=>$area_of_operation]]);
         $this->paginate = ['limit' => 20];
            $area_result = $this->paginate($area_result);  

         ############################################################      
        $this->set(compact('area_result','pActivities'));

    }
    public function AvailableState($primary_id=null)
    {
        //echo "primary_id".$primary_id;
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('PrimaryActivities');
        $pActivities=$this->PrimaryActivities->find('all')->where(['status'=>1, 'id'=>$primary_id])->order(['orderseq'=>'ASC'])->toarray();

        $state_code=[];

        $cr_area = $this->CooperativeRegistrations->find('all', ['fields'=>array('state_code'),'order' => ['created' => 'DESC'], 'conditions' => ['sector_of_operation'=>$primary_id,'is_draft'=>0]])->group(['state_code'])->toarray();

        foreach($cr_area as $key => $value)
        {
            $state_code[]=$value['state_code'];
        }
        
         ##############  sate of operation ################
         $this->loadMOdel('States');
         
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }
         $area_result = $this->States->find('all', [ 'conditions' => ['flag'=>1,'id IN'=>$state_code]]);
         $this->paginate = ['limit' => 20];
            $area_result = $this->paginate($area_result);  

         ############################################################      
        $this->set(compact('area_result','pActivities'));

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
            $district_code=$this->request->query('district_code');    
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

  

    
}
