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
class SugarMillsFederationController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','example']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->loadModel('States');
        $this->loadModel('Districts');  
        $this->loadMOdel('FederationSugarMills'); 
            // federation_sugar_mills
        $district_codes = [];
        $dairies = [];
        $pacs = [];
        $fisheries = [];
        $condtion_district=[];

        //$aa = $this->FederationSugarMills->find('all')->toArray();     

       // print_r($aa);
      
    
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 20;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $s_state = trim($this->request->query['state']);
           
            $this->set('s_state', $s_state);          
            $condtion_district['state_code'] = $this->request->query['state'];
            $condtion_districtA['state_code'] = $this->request->query['state'];
          
        } 
        if (isset($this->request->query['sugar_mill_name']) && $this->request->query['sugar_mill_name'] !='') {
            $sugar_mill_name = trim($this->request->query['sugar_mill_name']);
            $this->set('sugar_mill_name', $sugar_mill_name);
            $search_condition[] = "sugar_mill_name like '%" . $sugar_mill_name . "%'";
        } 

        if (isset($this->request->query['operational_status']) && $this->request->query['operational_status'] !='') {
            $operational_status = trim($this->request->query['operational_status']);
            $this->set('operational_status', $operational_status);
           
            $condtion_district['operational_status'] = $operational_status;
        }
        if (isset($this->request->query['ownership']) && $this->request->query['ownership'] !='') {
            $ownership = trim($this->request->query['ownership']);
            $this->set('ownership', $ownership);
           
            $condtion_district['ownership_status'] = $ownership;
        }

        if (isset($this->request->query['short_name']) && $this->request->query['short_name'] !='') {
            $short_name = trim($this->request->query['short_name']);
            $this->set('short_name', $short_name);
            $search_condition[] = "short_name like '%" . $short_name . "%'";
        } 
       

        
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }     

    //print_r($condtion_district);
    //die;
            $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
            $query->hydrate(false);
            $stateOption = $query->toArray();
            $this->set('sOption',$stateOption);   
            $this->loadMOdel('Districts');
            $Districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toarray();       

            $this->set('Districts',$Districts);
            
                $aa = $this->FederationSugarMills->find('all')->where([$condtion_district,$search_condition])->order(['sugar_mill_name'=>'ASC']);    

                $this->paginate = ['limit' => 20];
                $district_codes = $this->paginate($aa);
                
                
                if(!empty($this->request->query['export_excel']))
                { 
        
                    $i=1;
                    $fileName = "SugarMillsFedrationReport".date("d-m-y:h:s").".xls";
                    $data = array(); 
                
                    $ExportResultData= $this->FederationSugarMills->find('all')->where([$condtion_district,$search_condition])->order(['sugar_mill_name'=>'ASC'])->toarray();  
                
                    $headerRow = array("S.No", "Sugar Mill Name", "State Name",'District Name', 'Contact Number', 'Email','contact_person_name','Location','Operational Status','Ownership','Actual Capacity','Ethanol Capacity(KLPD)','Cogen Capacity(MW)','Profit-Loss(IN LAKHS)');
                  
                    foreach($ExportResultData As $rows)
                    {        
                       // $total=$pacs[$rows['state_code']] + $dairies[$rows['state_code']] + $fisheries[$rows['state_code']];
                    //   $pacs=
                    $data[] = [$i, $rows['sugar_mill_name'], $stateOption[$rows['state_code']]?? 0, $Districts[$rows['district_code']],$rows['phone_number_of_contact_person'],$rows['email'],$rows['name_of_contact_person'] ,$rows['short_name'] ?? 0 , $rows['operational_status'], $rows['ownership_status'], $rows['actual_capacity'],$rows['ethanol_capacity'],$rows['cogen_capacity'],$rows['profit_loss']]; 
                    $i++;
                    }
            
                    $this->exportInExcelNew($fileName, $headerRow, $data);                        
        
                }
            
           
            
       
        $this->set(compact('district_codes','dairies','pacs','fisheries','state_code'));
        
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
