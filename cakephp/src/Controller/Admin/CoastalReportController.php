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
class CoastalReportController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->loadModel('CooperativeRegistrations');
        $this->loadModel('States');
        $this->loadModel('Districts');       
        $this->loadModel('PrimaryActivities');       
        $this->loadMOdel('CooperativeRegistrations');  
        $this->loadMOdel('WaterBodyTypes');
        
        
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        $districts = [];

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $search_condition['CooperativeRegistrations.state_code'] = $this->request->query['state'];

            
            $district_query =$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name','conditions'=>['flag'=>1,'state_code'=>$this->request->query['state']],'order' => ['name' => 'ASC']]);
            $district_query->hydrate(false);
            $districts = $district_query->toArray();
        }
        
        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $search_condition['CooperativeRegistrations.district_code'] = $this->request->query['district'];
        }

        if (isset($this->request->query['primary_activity']) && $this->request->query['primary_activity'] !='') {
            $search_condition['CooperativeRegistrations.sector_of_operation'] = $this->request->query['primary_activity'];
        }

        if (isset($this->request->query['water_body_type']) && $this->request->query['water_body_type'] !='') {
            $search_condition['CooperativeRegistrations.water_body_type_id'] = $this->request->query['water_body_type'];
        }
        
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }

        

        


        $registrationQuery = $this->CooperativeRegistrations->find('all', ['conditions' => [$search_condition]])
        ->join([
            'states' => [
                'table' => 'states',
                'type' => 'INNER',
                'conditions' => 'states.state_code = CooperativeRegistrations.state_code'
            ],
            // ,
            // 'districts' => [
            //     'table' => 'districts',
            //     'type' => 'INNER',
            //     'conditions' => 'districts.district_code = CooperativeRegistrations.district_code'
            // ],
            // 'blocks' => [
            //     'table' => 'blocks',
            //     'type' => 'INNER',
            //     'conditions' => 'blocks.block_code = CooperativeRegistrations.block_code'
            // ]
            // ,
            // 'districts_blocks_gp_villages' => [
            //     'table' => 'districts_blocks_gp_villages',
            //     'type' => 'INNER',
            //     'alias' => 'panchayat',
            //     'conditions' => 'panchayat.gram_panchayat_code = CooperativeRegistrations.gram_panchayat_code'
            // ]
            // ,
            // 'districts_blocks_gp_villages1' => [
            //     'table' => 'districts_blocks_gp_villages',
            //     'type' => 'INNER',
            //     'alias' => 'village',
            //     'conditions' => 'village.village_code = CooperativeRegistrations.village_code'
            // ],
			'primary_activities' => [
                'table' => 'primary_activities',
                'type' => 'INNER',
				'alias' => 'pactivities',
                'conditions' => 'pactivities.id = CooperativeRegistrations.sector_of_operation'
            ],
			'water_body_types' => [
                'table' => 'water_body_types',
                'type' => 'INNER',
                'conditions' => 'water_body_types.id = CooperativeRegistrations.water_body_type_id'
            ]
            
            ])
            // ->select(['CooperativeRegistrations.id','CooperativeRegistrations.cooperative_society_name','states.name','districts.name','blocks.name','panchayat.gram_panchayat_name','village.village_name','pactivities.name','water_body_types.name'])->where(['CooperativeRegistrations.is_coastal'=>'1','CooperativeRegistrations.location_of_head_quarter'=>'2','CooperativeRegistrations.is_draft'=>'0','CooperativeRegistrations.status'=>'1','CooperativeRegistrations.is_approved !='=>'2']);

            ->select(['CooperativeRegistrations.id','CooperativeRegistrations.cooperative_society_name','states.name','pactivities.name','water_body_types.name'])->where(['CooperativeRegistrations.is_coastal'=>'1','CooperativeRegistrations.location_of_head_quarter'=>'2','CooperativeRegistrations.is_draft'=>'0','CooperativeRegistrations.status'=>'1','CooperativeRegistrations.is_approved !='=>'2']);
			$this->paginate = ['limit' => $page_length];
            if($this->request->is('post')){
                
                if(!empty($this->request->data['export_excel'])) {
                    
                    $fileName = "Coastal-".date("d-m-y:h:s").".xls";
                    $headerRow = array("S.No", "Cooperative Society Name","State","District", "Block","Gram Panchayat","Village","Sector of Operation","Waterbody");
                    $data = array();
                    
                    $registrationQuery = $registrationQuery
                    ->limit($page_length)
                    ->page($page)
                    ->toArray();
                    // echo '<pre>';
                    // print_r($registrationQuery);die;
                    
                    foreach($registrationQuery as $key => $rows){
                        
                       $data[]=array(($key+1), $rows['cooperative_society_name'],$rows['states']['name']);
                    }
                    $this->exportInExcel($fileName, $headerRow, $data);
                }
            }

			$CooperativeRegistrations = $this->paginate($registrationQuery);
			//print_r($CooperativeRegistrations);
        
            $this->set('selectedLen', $page_length);

        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
        
        $water_body_types = $this->WaterBodyTypes->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC']);

        $primary_activities = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        
        $this->set(compact('CooperativeRegistrations','states','districts','primary_activities','water_body_types'));
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
