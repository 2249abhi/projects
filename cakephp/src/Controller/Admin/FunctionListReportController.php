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
class FunctionListReportController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','AvailableArea','generateUniqueNumber']);
    }


    function generateUniqueNumber()
    {
        set_time_limit(0);
        ini_set('memory_limit', '2000000000000000000M');
        $this->loadModel('CooperativeRegistrations');
        $registrationQuery = $this->CooperativeRegistrations->find('all', ['order' => ['id' => 'asc']])->where(['is_draft' =>0])->toArray();
        echo "<pre>";
       // print_r($registrationQuery);
        echo "==================";

      echo count($registrationQuery);
       die;
           
        $conn = ConnectionManager::get('default');
        $k=1;
        for($i=0; $i < count($registrationQuery); $i++)
        {
            echo "<br>";
           echo "id---". $registrationQuery[$i]['id'];
           echo "----unique_id---". $unique_id = str_pad($k, 10, "0", STR_PAD_LEFT);

           $data['id']           =$id       =   $registrationQuery[$i]['id'];
           $data['cooperative_id_num'] = $cooperative_id_num =   $unique_id;

           $stmt = $conn->execute("UPDATE cooperative_registrations SET cooperative_id_num = $cooperative_id_num  WHERE id = $id");
           $results =  $stmt->count();        
        
            $k++;
        }
        echo "test";
        die;
    }



    ######################################
    // cover Function list report 

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
        $this->loadMOdel('SectorOperations');
        

      
      //  $viewasd =$this->CoverageAreas->find('all')->where(['id'=>1,])->order(['id'=>'ASC'])->toArray();

       
        $condtion=[];
        //$search_condition=[];
        $condtiongp=[];
        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (isset($this->request->query['society_name']) && $this->request->query['society_name'] !='') {
            $society_name = trim($this->request->query['society_name']);
           // $condtion['cooperative_society_name'] = $this->request->query['society_name'];
            $condtiongp['cooperative_society_name'] = $this->request->query['society_name'];

         //  $search_condition['area_of_operation_level.society_name']= $this->request->query['society_name'];
            $this->set('society_name', $society_name);
            $search_condition[] = "cooperative_society_name like '%" . $society_name . "%'";
        }
        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $s_state = trim($this->request->query['state']);   
            
            $condtion['state_code'] = $this->request->query['state'];
            $condtiongp['state_code'] = $this->request->query['state'];
            

            $dist_opt = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$s_state])->order(['name'=>'ASC'])->toArray();
            $this->set('dist_opt', $dist_opt);
         
        }
        $this->set('s_state', $s_state);
        if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
            $district_code = trim($this->request->query['district_code']);
            $condtion['district_code'] = $this->request->query['district_code'];
            $condtiongp['district_code'] = $this->request->query['district_code'];
         //  $search_condition['area_of_operation_level.district_code']= $this->request->query['district_code'];
            $this->set('district_code', $district_code);
        }
       
             

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
            if($s_primary_activity==1)
            {
                       //$condtion['sector_of_operation IN'] = $this->request->query['primary_activity'];
                        $condtion['sector_of_operation IN'] =array(1,59,22,20);
            }else
            {
                   $condtion['sector_of_operation'] = $this->request->query['primary_activity'];
            }
         
            $this->set('s_primary_activity', $s_primary_activity);
        }

        if (isset($this->request->query['functional_status']) && $this->request->query['functional_status'] !='') {
            $functional_status = trim($this->request->query['functional_status']);
            $condtion['functional_status'] = $this->request->query['functional_status'];
            $this->set('functional_status', $functional_status);
        }


        $condtion['is_draft']=0;
        $condtion['status']=1;
       $condtion['is_approved !=']=2;
                    
        
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

            $coperation_list = $this->CooperativeRegistrations->find('all')->where([$condtion,$searchString])->order(['id'=>'ASC']);   
            $this->paginate = ['limit' => 20];
            $coperation_list = $this->paginate($coperation_list);   
            
            $total_functional = $this->CooperativeRegistrations->find('all')->where([$condtion,$searchString,'functional_status'=>'1'])->order(['id'=>'ASC'])->toarray();
            $total_under_liquition = $this->CooperativeRegistrations->find('all')->where([$condtion,$searchString,'functional_status'=>'2'])->order(['id'=>'ASC'])->toarray();
            $non_functional = $this->CooperativeRegistrations->find('all')->where([$condtion,$searchString,'functional_status'=>'3'])->order(['id'=>'ASC'])->toarray();
            $this->set('total_functional',$total_functional);
            $this->set('total_under_liquition',$total_under_liquition);
            $this->set('non_functional',$non_functional);

        }

        $state_array = $this->States->find('list', [ 'conditions' => ['flag'=>1], 'keyField'=>'state_code','valueField'=>'name'])->toArray();
      
        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       
        
       // $arr_blocks = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->order(['block_name'=>'ASC'])->toArray();
        
      //  $arr_panchayat = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->order(['gram_panchayat_name'=>'ASC'])->toArray();
       
        $sOption = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->loadModel('AreaOfOperations');
        $area_of_operation_array= $this->AreaOfOperations->find('list',['conditions' => ['status'=>1] ,'keyField'=>'id','valueField'=>'name'] )->toarray();
        $this->set('primary_activities',$primary_activities);


        $this->loadModel('AuditCategories');
        $audit_cat_array = $this->AuditCategories->find('list',['conditions' => ['status'=>1] ,'keyField'=>'id','valueField'=>'name'] )->toarray();

        $this->set('audit_cat_array',$audit_cat_array);
       

        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

          $sectorOperations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();

        $this->set('sectorOperations', $sectorOperations);

         $this->set(compact('arr_panchayat','arr_blocks','district_codes','arr_districts','panchayats','villages','state_code','pActivities','sOption','area_of_operation_array','state_array','bloack_array','gram_panchayat_array','coperation_list','presentFunctionalStatus'));
        
    }

    
    ######################################
    // Audit category Report 

    ####################################

    public function auditCategoryReport()
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
            $this->set('s_state', $s_state);
            
            $condtion['state_code'] = $this->request->query['state'];
            $condtiongp['state_code'] = $this->request->query['state'];
            

            $dist_opt = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$s_state])->order(['name'=>'ASC'])->toArray();
            $this->set('dist_opt', $dist_opt);
            
        }

        if (isset($this->request->query['district_code']) && $this->request->query['district_code'] !='') {
            $district_code = trim($this->request->query['district_code']);
            $this->set('district_code', $district_code);
            echo "rrr";
            $condtion['district_code'] = $this->request->query['district_code'];
            $condtiongp['district_code'] = $this->request->query['district_code'];
         //  $search_condition['area_of_operation_level.district_code']= $this->request->query['district_code'];
            $this->set('district_code', $district_code);
        }
       
             

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $s_primary_activity = trim($this->request->query['primary_activity']);
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
         $this->loadModel('AuditCategories');
        $audit_cat_array = $this->AuditCategories->find('list',['conditions' => ['status'=>1] ,'keyField'=>'id','valueField'=>'name'] )->toarray();
        $this->set('audit_cat_array',$audit_cat_array);

        if(!empty($this->request->query))
        {
        
            set_time_limit(0);
            ini_set('memory_limit', '2000000000000000000M');           

            $coperation_list = $this->CooperativeRegistrations->find('all')->where([$condtion])->order(['id'=>'ASC']);   
            $this->paginate = ['limit' => 20];
            $coperation_list = $this->paginate($coperation_list);   
            
            $audit_category_list = $this->CooperativeRegistrations->find('all')->where([$condtion])->order(['id'=>'ASC'])->toarray();
            $total_count_array=[];
            $i=1;
            foreach($audit_category_list as $key=>$value)
            {
                if($value['category_audit'] !='')
                {
                $total_count_array[$value['category_audit']][] = +$i;

                $i++;
                }

            }
           // print_r($total_count_array);
            $this->set('audit_category_list',$audit_category_list);            
            $this->set('total_count_array',$total_count_array);

        }

        $state_array = $this->States->find('list', [ 'conditions' => ['flag'=>1], 'keyField'=>'state_code','valueField'=>'name'])->toArray();
      
        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       
        $sOption = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $this->loadModel('AreaOfOperations');
        $area_of_operation_array= $this->AreaOfOperations->find('list',['conditions' => ['status'=>1] ,'keyField'=>'id','valueField'=>'name'] )->toarray();
        $this->set('primary_activities',$primary_activities);        
       

        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

         $this->set(compact('district_codes','arr_districts','panchayats','villages','state_code','pActivities','sOption','area_of_operation_array','state_array','bloack_array','gram_panchayat_array','coperation_list','presentFunctionalStatus'));
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
