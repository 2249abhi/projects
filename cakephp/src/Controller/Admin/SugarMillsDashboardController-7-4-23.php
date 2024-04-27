<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 *
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SugarMillsDashboardController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        
        $is_first_login = $this->Auth->user('is_first_login');
        if($is_first_login=='1')
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'changePassword']);
        }
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
        $this->loadModel('DistrictNodalEntries');
        $this->loadModel('CooperativeRegistrations');
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $this->loadMOdel('PacsPanchayatReport');
        $this->loadMOdel('CoverageAreas');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('FederationSugarMills'); 
        
        //=========================graph data==============================================
        $role_id =  $this->Auth->user('role_id');
        $state_code = $this->request->session()->read('Auth.User.state_code');
        $district_code = $this->request->session()->read('Auth.User.district_code');
        
        $states = [];
        $districts = [];
        $pacs_graph = [];
        $dairy_graph = [];
        $fishery_graph = [];
        $nodal_entry_id = '';
        $total_functional = [];
        $total_under_liquition = [];
        $non_functional = [];
        $blocks = [];
        $gp_search_condition = [];
        $search_condition = [];
        $state_condition = [];

        // if(empty($this->request->data['state']))
        // {
        //     $this->request->data['state'] = 35;
        // }

        $states = $this->Districts->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
        

        if(empty($this->request->data['sector']))
        {
            $this->request->data['sector'] = 1;
        }

        if($role_id == 7 || $role_id == 8)
        {
            $state_condition['state_code'] = $state_code;
            //dstrict
            $search_condition['state_code'] = $state_code;
            $s_state = $state_code;
            $this->set('s_state', $s_state);

            $search_condition['district_code'] = $district_code;
            $s_district = $district_code;
            $this->set('s_district', $s_district);
        }

        if($role_id == 11)
        {//state
            $search_condition['state_code'] = $state_code;
            $s_state = $state_code;
            $this->set('s_state', $s_state);

            $state_condition['state_code'] = $state_code;
        }

        if (isset($this->request->data['state']) && $this->request->data['state'] !='') {
            $s_state = trim($this->request->data['state']);
            $this->set('s_state', $s_state);
            $search_condition['state_code'] = $this->request->data['state'];
        }

        if (isset($this->request->data['district']) && $this->request->data['district'] !='') {
            $s_district = trim($this->request->data['district']);
            $this->set('s_district', $s_district);
            $search_condition['district_code'] = $this->request->data['district'];
        }

        if (isset($this->request->data['sector']) && $this->request->data['sector'] !='') {
            $s_sector = trim($this->request->data['sector']);
            $this->set('s_sector', $s_sector);

            if($s_sector==1)
            {
                $sector_search_condition[] = "sector_of_operation IN(1,20,22)";
            }else{
                $sector_search_condition[] = "sector_of_operation = '" . $s_sector . "'";
            }
        }

        

        if (isset($this->request->data['block']) && $this->request->data['block'] !='') {
            $s_block = trim($this->request->data['block']);
            $this->set('s_block', $s_block);
           $gp_search_condition['block_code'] = $this->request->data['block'];
            
        }
        
        //graph for admin
        $arr_graph_allowed = [1,2,7,8,10,11,12,14];
       
        $total_functional = $this->FederationSugarMills->find('all', [
                'conditions' => [$search_condition]            
            ])->where(['operational_status' => 'Closed'])->count();
        
        $non_functional =  $this->FederationSugarMills->find('all', [
                'conditions' => [$search_condition]            
            ])->where(['operational_status' => 'Running'])->count();
           // $non_functional = $non_functional->select(['sector_of_operation','count' => $non_functional->func()->count('sector_of_operation')])->where([$search_condition,'is_approved !='=>2,'functional_status'=>'3','is_draft'=>0,'status'=>1])->group(['sector_of_operation'])->toArray();

           

        if($role_id==8)
        {
			
            $nodal_entry_id = $this->DistrictNodalEntries->find('all', [
                'order' => ['district_nodal_name'=>'ASC'],
                'conditions' => [$searchString,'state_code'=>$state_code,'district_code'=>$district_code]
            ])->first()->id;
        }

        //=========================graph data==============================================

      
        //==================================================line chart=====================================================================

        $this->set(compact('pacs_graph','dairy_graph','fishery_graph','role_id','nodal_entry_id','states','districts','total_functional','total_under_liquition','non_functional','arr_gp_data','sectors','blocks','member'));
        //   $affliation_response=$this->getfederationdata($search_condition);
        //     $this->set(compact('affliation_response'));
    }
   


    public function getDistricts(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $state_code=$this->request->data('state_code');   
            $this->loadModel('Districts');
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

  
}