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
//affiliated to Union/Federation
class FederationReportsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','availableFedration']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
               
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        
                  
       set_time_limit(0);
       ini_set('memory_limit', '-1');

       $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
       $query->hydrate(false);
       $stateOption = $query->toArray();
       $this->set('sOption',$stateOption);

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
           


        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') 
        {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            $this->set('s_primary_activity', $s_primary_activity);

            if($s_primary_activity==1)
            {
                $search_condition2['sector_of_operation IN'] =array(1,59,22,20);
               // $search_condition2[] = "sector_of_operation IN(1,59,20,22)";

            }else{
               //  $search_condition2[] = "sector_of_operation = '" . $s_primary_activity . "'";
               $search_condition2['sector_of_operation'] =$s_primary_activity; 
             }
        } 
        
        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $s_state = trim($this->request->query['state']);
            $search_condition1['state_code'] = $s_state;
           
            $this->set('s_state', $s_state);

            $query =$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name','conditions'=>['state_code'=>$this->request->query['state']],'order' => ['name' => 'ASC']]);
     
            $query->hydrate(false);
            $dist_opt = $query->toArray();
            $this->set('dist_opt',$dist_opt);
        }

        if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
            $d_district_code = trim($this->request->query['district_code']);
            $search_condition1['district_code'] = $d_district_code;
            $this->set('d_district_code', $d_district_code);
        }
          

        $search_condition2['is_approved !='] = 2;     

            

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
       
      

          #################export Excel################
          if(!empty($this->request->query['primary_activity']))
          {
            if(!empty($this->request->query['export_excel']))
            { 

             $i=1;
           //  mysql_set_charset("utf8");
             $queryExport = $this->CooperativeRegistrations->find('all', [
             'order' => ['cooperative_society_name' => 'ASC'],  
             'contain'=>['AreaOfOperationLevel','CooperativeRegistrationPacs'],        
             'conditions' => [$searchString,$search_condition2,$search_condition1]            
             ]);

             $queryExport->hydrate(false);
             $ExportResultData = $queryExport->toArray();
             $fileName = "NCD".date("d-m-y:h:s").".xls";
             $data = array(); 
           
             $headerRow = array("S.No", "Name of the PACS/LAPS/FSS/Others(M)", "Registration Number of PACS", "Year of Registration", "Category-PACS/LAMPS/FSS/Other(M)", "Panchayat", "Village","Name of the Bank to which the PACS is Affilited","Total No.of members","No Of Villages Covered By Pacs","Office  Building");

             $buildingTypes = ['1'=>'Own Building','2'=>'Rented Building','3'=>'Rent Free Building', '4'=> 'Leased Building','5'=>'Building Provided by the Government']; 
             foreach($ExportResultData As $rows){

              $refrence_year=  date('Y',strtotime($rows['date_registration']));

             $data[] = [$i, $rows['cooperative_society_name'],$rows['registration_number'],$refrence_year,$sectors[$rows['sector_of_operation']], $dgbgV['panchayat'][$rows['gram_panchayat_code']], $dgbgV['village'][$rows['village_code']], $banckarray[$rows['affiliated_union_federation_name']],$rows['members_of_society'], count($rows['area_of_operation_level']),$buildingTypes[$rows['cooperative_registration_pacs'][0]['building_type']]]; 
             $i++;
             }

             $this->exportInExcel($fileName, $headerRow, $data);           

         }
             #################export Excel################



        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['cooperative_society_name' => 'ASC'],
           //'contain'=>['AreaOfOperationLevel','CooperativeRegistrationPacs'],
            'conditions' => [$searchString,$search_condition2 , $search_condition1]            
        ])->select(['id', 'sector_of_operation', 'is_affiliated_union_federation','state_code','district_code','affiliated_union_federation_level','is_coastal','water_body_type_id'])->where(['is_draft'=>0,'status'=>1])->toarray();

            $countfederation=[];

        if($s_state !='')
        {  
            foreach($registrationQuery as $key=>$value)
            {
                if($value['is_affiliated_union_federation']==1)
                {
                    $countfederation['af'][$value['district_code']]['YES'] += 1;
                }else{
                    $countfederation['naf'][$value['district_code']]['NO'] += 1;
                }
            }
        }
        else
        {
            foreach($registrationQuery as $key=>$value)
            {
                if($value['is_affiliated_union_federation']==1)
                {
                    $countfederation['af'][$value['state_code']]['YES'] += 1;
                }else{
                    $countfederation['naf'][$value['state_code']]['NO'] += 1;
                }
            }
        }
       
          // echo "<pre>";
       // print_r($countfederation);
        
        $this->set(compact('countfederation')); 
        if($s_state !='')
        {
            $state_code_list=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name','conditions'=>['flag'=>1,$search_condition1],'order' => ['name' => 'ASC']]);

        }else{
            $state_code_list=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1,$search_condition1],'order' => ['name' => 'ASC']]);
        }

    
        

       $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($state_code_list);
        //$CooperativeRegistrations = $this->paginate($this->CooperativeRegistrations->find('all')->order(['id'=>'DESC']));

        $this->set(compact('CooperativeRegistrations'));   
        } 
     

        
    }

############################# availableFeration report-Affiliated Report
 ##################availableFedration
public function availableFedration($primarykey=null,$district_code=null)
{
   

        $this->loadMOdel('NabardPacsData');
        $this->loadModel('Users');
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadMOdel('Blocks');
        $this->loadModel('SectorOperations');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('DistrictsBlocksGpVillages');
       $this->loadMOdel('DistrictCentralCooperativeBank');
       $this->loadMOdel('UrbanLocalBodiesWards');             
       set_time_limit(0);
       ini_set('memory_limit', '-1');


        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
      
        if($this->request->query['primary'])
        {
        
        if (isset($this->request->query['primary']) && $this->request->query['primary'] !='') {

          
            $primary = trim($this->request->query['primary']);
            $this->set('primary', $primary);
            if($primary==1)
            {
                $search_condition[] = "sector_of_operation IN ('1','59','20','22')";
            }else
            {
                 $search_condition[] = "sector_of_operation = '" . $primary . "'";
            }
        }

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "operational_district_code = '" . $s_district . "'";
        }
        $search_condition2['is_draft']='0';
        $search_condition2['status']='1';
        $search_condition2['is_approved !=']='2';
        $search_condition2['is_affiliated_union_federation']='1';
        
       

        

      
        $search_condition3='';
        

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

        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $this->set(compact('arr_districts'));
        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();    
    
        $this->set('sectors',$sectors); 

        $bank_name = $this->DistrictCentralCooperativeBank->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();    
    
        $this->set('bank_name',$bank_name); 
        #################export Excel################
            if(!empty($this->request->query['export_excel']))
             { 

                $i=1;

                $queryExport = $this->NabardPacsData->find('all', [
                'order' => ['cooperative_society_name' => 'ASC'],          
                'conditions' => [$searchString,$search_condition2,$search_condition3]            
                ]);

                $queryExport->hydrate(false);
                $ExportResultData = $queryExport->toArray();
                $fileName = "Nabard_Pac_Data".date("d-m-y:h:s").".xls";
                $data = array(); 

                $headerRow = array("S.No", "Name of the PACS/LAPS/FSS/Others(M)", "District Name", "Sector-PACS/LAMPS/FSS/Dairy/Fishary", "Registrtion Number", "Co-operatiove ID","Referance ID","Total No.of members","No Of Villages Covered By Pacs","Office  Building");

                foreach($ExportResultData As $rows)
                {
                    $data[] = [$i, trim($rows['cooperative_society_name']), trim($rows['registration_number']), $rows['reference_year'], $rows['cooperative_society_type'], trim($rows['gram_panchayat_name']), trim($rows['village_name']), trim($rows['affiliated_union_federation_name']),$rows['number_of_member'], $rows['no_village_covered'], $rows['building_type']]; 
                    $i++;
                }

                $this->exportInExcelNew($fileName, $headerRow, $data);           

            }
                #################export Excel################

        $registrationQuery = $this->CooperativeRegistrations->find('all', [
            'order' => ['cooperative_society_name' => 'ASC'],          
            'conditions' => [$searchString,$search_condition2,$search_condition3]            
        ]);

       // print_r($registrationQuery);
      //  die;

        $this->paginate = ['limit' => 20];
        $CooperativeRegistrations = $this->paginate($registrationQuery);
        $this->set(compact('CooperativeRegistrations'));

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

   


    
}
