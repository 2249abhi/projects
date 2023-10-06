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
class GramPanchayatChartController extends AppController
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
        

        $blocks = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'block_code','valueField'=>'block_name'])->where(['status'=>1,'district_code'=>$s_district])->group(['block_code'])->toArray();

        
        
        //graph for admin
        $arr_graph_allowed = [1,2,7,8,10,11,12,14];

	    // if(in_array($role_id,$arr_graph_allowed))
        // {

            $all_district_nodal = $this->DistrictNodalEntries->find('all')->where($search_condition)->toarray();

            $district_nodal_tatal =[];
            $district_nodal_tatal_without_state =[];

            foreach($all_district_nodal as $key=>$value)
            {
                    
                    $district_nodal_total ['pacs'][$value['state_code']][]           =   $value['pacs_count'];
                    $district_nodal_total['dairy'] [$value['state_code']] []         =   $value['dairy_count'];
                    $district_nodal_total['fisfhery'][$value['state_code']] []       =   $value['fishery_count'];

                    $district_nodal_total_without_state['pacs'][]           =   $value['pacs_count'];
                    $district_nodal_total_without_state['dairy'][]         =   $value['dairy_count'];
                    $district_nodal_total_without_state['fishery'][]       =   $value['fishery_count'];
            }


            //Pacs
            $pacs_today = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);
            $pacs_today = $pacs_today->select(['state_code','count' => $pacs_today->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [1,59,20,22],'is_draft'=>0, 'DATE(created)' => date('Y-m-d'), $search_condition])->group(['state_code'])->toArray();

            $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);            
            $pacs = $pacs->select(['state_code','count' => $pacs->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [1,59,20,22],'is_draft'=>0, $search_condition])->group(['state_code'])->toArray();

            //Dairies
            $dairies_today = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);            
            $dairies_today = $dairies_today->select(['state_code','count' => $dairies_today->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [9,37],'is_draft'=>0,'DATE(created)' => date('Y-m-d'), $search_condition])->group(['state_code'])->toArray();

            $dairies = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);            
            $dairies = $dairies->select(['state_code','count' => $dairies->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [9,37], 'is_draft'=>0, $search_condition])->group(['state_code'])->toArray();


            //fishery
            $fisheries_today = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);
                
            $fisheries_today = $fisheries_today->select(['state_code','count' => $fisheries_today->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [10,43],'is_draft'=>0,'DATE(created)' => date('Y-m-d'), $search_condition])->group(['state_code'])->toArray();

            $fisheries = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);
                
            $fisheries = $fisheries->select(['state_code','count' => $fisheries->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [10,43],'is_draft'=>0, $search_condition])->group(['state_code'])->toArray();
        

            //echo array_sum($pacs);die;
            

            $pacs_graph['total_record'] = array_sum($district_nodal_total_without_state['pacs']) ?? 0;
            $pacs_graph['data_entered_today'] = array_sum($pacs_today) ?? 0;
            $pacs_graph['total_data_entered'] = array_sum($pacs) ?? 0;

            

            $dairy_graph['total_record'] = array_sum($district_nodal_total_without_state['dairy']) ?? 0;
            $dairy_graph['data_entered_today'] = array_sum($dairies_today) ?? 0;
            $dairy_graph['total_data_entered'] = array_sum($dairies) ?? 0;

            

            $fishery_graph['total_record'] = array_sum($district_nodal_total_without_state['fishery']) ?? 0;
            $fishery_graph['data_entered_today'] = array_sum($fisheries_today) ?? 0;
            $fishery_graph['total_data_entered'] = array_sum($fisheries) ?? 0;


            //functional report data
            $total_functional = $this->CooperativeRegistrations->find('list',['keyField'=>'sector_of_operation','valueField'=>'count']);
            $total_functional = $total_functional->select(['sector_of_operation','count' => $total_functional->func()->count('sector_of_operation')])->where([$search_condition,'functional_status'=>'1','is_draft'=>0,'status'=>1])->group(['sector_of_operation'])->toArray();

            $total_under_liquition = $this->CooperativeRegistrations->find('list',['keyField'=>'sector_of_operation','valueField'=>'count']);
            $total_under_liquition = $total_under_liquition->select(['sector_of_operation','count' => $total_under_liquition->func()->count('sector_of_operation')])->where([$search_condition,'functional_status'=>'2','is_draft'=>0,'status'=>1])->group(['sector_of_operation'])->toArray();
           

            $non_functional = $this->CooperativeRegistrations->find('list',['keyField'=>'sector_of_operation','valueField'=>'count']);
            $non_functional = $non_functional->select(['sector_of_operation','count' => $non_functional->func()->count('sector_of_operation')])->where([$search_condition,'functional_status'=>'3','is_draft'=>0,'status'=>1])->group(['sector_of_operation'])->toArray();

            
            //print_r($total_under_liquition);
            // print_r($dairy_graph);
            // print_r($fishery_graph);
            // die;
            
        //}

        if($role_id==8)
        {
			
            $nodal_entry_id = $this->DistrictNodalEntries->find('all', [
                'order' => ['district_nodal_name'=>'ASC'],
                'conditions' => [$searchString,'state_code'=>$state_code,'district_code'=>$district_code]
            ])->first()->id;
        }

        $states =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1,$state_condition],'order' => ['name' => 'ASC']])->toArray();

        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$s_state])->order(['name'=>'ASC'])->toArray();

        //=========================graph data==============================================

        //==============================gp pie chart data=================================================

        $notcovered_gp = 0;
        $full_covered_gp = 0;
        $partial_covered_gp = 0;
        $all_gp = 0;

        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1,'id IN'=>[1,9]])->toArray();
        // unset($sectors[10]);
        // unset($sectors[20]);
        // unset($sectors[22]);

        if(!empty($s_sector))
        {
            if(empty($s_state))
            {
                //die('national level');
                //all india
                $gp_data = $this->PacsPanchayatReport->find('all')->where(['society_flag'=>$s_sector])->toArray();


                if(!empty($gp_data))
                {
                    foreach($gp_data as $key => $data)
                    {
                        $all_gp += $data['count_gp_panchayat'];
                        $partial_covered_gp += $data['count_partially_panchayat'];
                        $full_covered_gp += $data['count_covered_panchayat'];
                        $notcovered_gp += $data['count_uncovered_panchayat'];
                    }
                }

            } else{

                if(empty($s_district))
                {
                    $gp_data = $this->PacsPanchayatReport->find('all')->where(['state_code'=>$s_state,'society_flag'=>$s_sector])->first();

                    if(!empty($gp_data))
                    {
                            $all_gp = $gp_data['count_gp_panchayat'];
                            $partial_covered_gp = $gp_data['count_partially_panchayat'];
                            $full_covered_gp = $gp_data['count_covered_panchayat'];
                            $notcovered_gp = $gp_data['count_uncovered_panchayat'];
                    }
                    

                } else {


                    $condtion['is_draft']=0;
                    $condtion['is_approved !=']=2;
                    $condtion['status']=1;
                    
                    $total_village_count = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'count']);            
                    $total_village_count = $total_village_count->select(['gram_panchayat_code','count' => $total_village_count->func()->count('gram_panchayat_code')])->where([$search_condition,$gp_search_condition,'status'=>1])->group(['gram_panchayat_code'])->toArray();

                    $total_covered_village_count = $this->CoverageAreas->find('list',['keyField'=>'panchayat_code','valueField'=>'count']);            
                    $total_covered_village_count = $total_covered_village_count->select(['panchayat_code','count' => $total_covered_village_count->func()->count('panchayat_code')])->where([$search_condition,$gp_search_condition,$condtion,$sector_search_condition])->group(['panchayat_code','village_code'])->toArray();

                

                    $all_gp = count($total_village_count);
                    // $uncovered_gp_arr = [];
                    // $full_covered_gp_arr = [];
                    // $partial_covered_gp_arr = [];

                
                
                    foreach($total_village_count as $gp_code => $village_count)
                    {
                        if (!array_key_exists($gp_code,$total_covered_village_count))
                        {
                            //get not covered GP by village
                            $notcovered_gp = $notcovered_gp + 1;
                        // $uncovered_gp_arr[] = $gp_code;
                        }

                        if (array_key_exists($gp_code,$total_covered_village_count))
                        {
                            //get full covered GP by village
                            if($total_village_count[$gp_code] == $total_covered_village_count[$gp_code])
                            {
                                $full_covered_gp = $full_covered_gp + 1;
                            // $full_covered_gp_arr[] = $gp_code;
                            }

                            //get partial covered GP by village
                            if($total_village_count[$gp_code] > $total_covered_village_count[$gp_code])
                            {
                                $partial_covered_gp = $partial_covered_gp + 1;
                                //$partial_covered_gp_arr[] = $gp_code; 
                            }

                        }

                    }

                }
       
            }

        }

        //==============================gp pie chart data=================================================
        $arr_gp_data = [];
        $arr_gp_data = [
            'notcovered_gp' => $notcovered_gp,'full_covered_gp' => $full_covered_gp,'partial_covered_gp' => $partial_covered_gp,'all_gp' => $all_gp
        ];

        $this->set(compact('pacs_graph','dairy_graph','fishery_graph','role_id','nodal_entry_id','states','districts','total_functional','total_under_liquition','non_functional','arr_gp_data','sectors','blocks'));
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
