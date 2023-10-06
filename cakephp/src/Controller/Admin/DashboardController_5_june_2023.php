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
class DashboardController extends AppController
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
            
            //functional report data
            $total_functional = $this->CooperativeRegistrations->find('list',['keyField'=>'sector_of_operation','valueField'=>'count']);
            $total_functional = $total_functional->select(['sector_of_operation','count' => $total_functional->func()->count('sector_of_operation')])->where([$search_condition,'functional_status'=>'1','is_approved !='=>2,'is_draft'=>0,'status'=>1])->group(['sector_of_operation'])->toArray();

            $total_under_liquition = $this->CooperativeRegistrations->find('list',['keyField'=>'sector_of_operation','valueField'=>'count']);
            $total_under_liquition = $total_under_liquition->select(['sector_of_operation','count' => $total_under_liquition->func()->count('sector_of_operation')])->where([$search_condition,'functional_status'=>'2','is_approved !='=>2,'is_draft'=>0,'status'=>1])->group(['sector_of_operation'])->toArray();
           

            $non_functional = $this->CooperativeRegistrations->find('list',['keyField'=>'sector_of_operation','valueField'=>'count']);
            $non_functional = $non_functional->select(['sector_of_operation','count' => $non_functional->func()->count('sector_of_operation')])->where([$search_condition,'is_approved !='=>2,'functional_status'=>'3','is_draft'=>0,'status'=>1])->group(['sector_of_operation'])->toArray();

           

        if($role_id==8)
        {
			
            $nodal_entry_id = $this->DistrictNodalEntries->find('all', [
                'order' => ['district_nodal_name'=>'ASC'],
                'conditions' => [$searchString,'state_code'=>$state_code,'district_code'=>$district_code]
            ])->first()->id;
        }

        //=========================graph data==============================================

        $arr_gp_data = [];
        $arr_gp_data = $this->gpPacsPieChart($s_sector,$s_state,$s_district,$s_block);
        $member = [];
        $member = $this->lineChart($search_condition);
        //==================================================line chart=====================================================================

        $this->set(compact('pacs_graph','dairy_graph','fishery_graph','role_id','nodal_entry_id','states','districts','total_functional','total_under_liquition','non_functional','arr_gp_data','sectors','blocks','member'));
          $affliation_response=$this->getfederationdata($search_condition);
            $this->set(compact('affliation_response'));
    }
    public function getfederationdata($search_condition)
    {
        $search_condition2['is_approved !='] = 2; 
        $response=array();
         
        $registrationQuery_YES = $this->CooperativeRegistrations->find('all', [
            'order' => ['cooperative_society_name' => 'ASC'],
           //'contain'=>['AreaOfOperationLevel','CooperativeRegistrationPacs'],
            'conditions' => [$searchString,$search_condition2 , $search_condition]            
        ])->select(['id', 'sector_of_operation', 'is_affiliated_union_federation','state_code','district_code','operational_district_code','affiliated_union_federation_level','is_coastal','water_body_type_id'])->where(['is_draft'=>0,'status'=>1,'sector_of_operation_type'=>1,'is_affiliated_union_federation'=>1])->count();
        $registrationQuery_NO = $this->CooperativeRegistrations->find('all', [
            'order' => ['cooperative_society_name' => 'ASC'],
           //'contain'=>['AreaOfOperationLevel','CooperativeRegistrationPacs'],
            'conditions' => [$searchString,$search_condition2 , $search_condition]            
        ])->select(['id', 'sector_of_operation', 'is_affiliated_union_federation','state_code','district_code','operational_district_code','affiliated_union_federation_level','is_coastal','water_body_type_id'])->where(['is_draft'=>0,'status'=>1,'sector_of_operation_type'=>1,'is_affiliated_union_federation'=>0])->count();


            $countfederation=[];

            $countfederation['af']['YES']= $registrationQuery_YES;
            $countfederation['naf']['NO']=$registrationQuery_NO;
            // foreach($registrationQuery as $key=>$value)
            // {
            //     if($value['is_affiliated_union_federation']==1)
            //     {
            //         $countfederation['af']['YES'] += 1;
            //     }else{
            //         $countfederation['naf']['NO'] += 1;
            //     }
            // }
       
               // print_r($countfederation);
       
               // print_r($countfederation);
        $response['affilated']=$countfederation['af']['YES'];
        $response['not_affilated']=$countfederation['naf']['NO'];
        $response['total']=$countfederation['af']['YES'] + $countfederation['naf']['NO'];

        return $response;

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

    public function lineChart($search_condition)
    {

        $this->loadModel('CooperativeRegistrations');

        $society1900 = $this->CooperativeRegistrations->find('list',['keyField'=>'sector_of_operation','valueField'=>'count'])->group(['sector_of_operation']);            
        $society1900 = $society1900->select(['sector_of_operation','count' => $society1900->func()->count('status')])->where([$search_condition,'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'date_registration >='=> '1900-01-01','date_registration <=' => '1923-12-31'])->group(['sector_of_operation'])->toarray();

        
        if(empty($society1900))
        {
            $society1900[1] = 0;
            $society1900[20] = 0;
            $society1900[22] = 0;
            $society1900[9] = 0;
            $society1900[10] = 0;
        }

        $member[1]['1900'] = $society1900[1]+$society1900[20]+$society1900[22];
        $member[9]['1900'] = $society1900[9];
        $member[10]['1900'] = $society1900[10];

        
        $society1924 = $this->CooperativeRegistrations->find('list',['keyField'=>'sector_of_operation','valueField'=>'count'])->group(['sector_of_operation']);  
        $society1924 = $society1924->select(['sector_of_operation','count' => $society1924->func()->count('status')])->where([$search_condition,'is_draft'=>'0','status'=>'1','is_approved !=' => '2', 'date_registration >='=> '1924-01-01','date_registration <='=> '1947-12-31'])->group(['sector_of_operation'])->toarray();

        
        if(empty($society1924))
        {
            $society1924[1] = 0;
            $society1924[20] = 0;
            $society1924[22] = 0;
            $society1924[9] = 0;
            $society1924[10] = 0;
        }

        $member[1]['1924'] = $society1924[1] +$society1924[20] + $society1924[22]+$member[1]['1900'];
        $member[9]['1924'] = $society1924[9] +$member[9]['1900'];
        $member[10]['1924'] = $society1924[10] +$member[10]['1900'];
        
        
        $society1948 = $this->CooperativeRegistrations->find('list',['keyField'=>'sector_of_operation','valueField'=>'count'])->group(['sector_of_operation']);            
        $society1948 = $society1948->select(['sector_of_operation','count' => $society1948->func()->count('status')])->where([$search_condition,'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'date_registration >='=> '1948-01-01','date_registration <='=> '1950-12-31'])->group(['sector_of_operation'])->toarray();

        if(empty($society1948))
        {
            $society1948[1] = 0;
            $society1948[20] = 0;
            $society1948[22] = 0;
            $society1948[9] = 0;
            $society1948[10] = 0;
        }

        $member[1]['1948'] = $society1948[1]+$society1948[20]+$society1948[22]+$member[1]['1924'];
        $member[9]['1948'] = $society1948[9]+$member[9]['1924'];
        $member[10]['1948'] = $society1948[10]+$member[10]['1924'];
        
        $society1951 = $this->CooperativeRegistrations->find('list',['keyField'=>'sector_of_operation','valueField'=>'count'])->group(['sector_of_operation']);            
        $society1951 = $society1951->select(['sector_of_operation','count' => $society1951->func()->count('status')])->where([$search_condition,'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'date_registration >='=> '1951-01-01','date_registration <='=> '1970-12-31'])->group(['sector_of_operation'])->toarray();

        if(empty($society1951))
        {
            $society1951[1] = 0;
            $society1951[20] = 0;
            $society1951[22] = 0;
            $society1951[9] = 0;
            $society1951[10] = 0;
        }

        $member[1]['1951'] = $society1951[1]+$society1951[20]+$society1951[22]+$member[1]['1948'];
        $member[9]['1951'] = $society1951[9]+$member[9]['1948'];
        $member[10]['1951'] = $society1951[10]+$member[10]['1948'];
        
        
        $society1971 = $this->CooperativeRegistrations->find('list',['keyField'=>'sector_of_operation','valueField'=>'count'])->group(['sector_of_operation']);            
        $society1971 = $society1971->select(['sector_of_operation','count' => $society1971->func()->count('status')])->where([$search_condition,'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'date_registration >='=> '1971-01-01','date_registration <='=> '1990-12-31'])->group(['sector_of_operation'])->toarray();

        if(empty($society1971))
        {
            $society1971[1] = 0;
            $society1971[20] = 0;
            $society1971[22] = 0;
            $society1971[9] = 0;
            $society1971[10] = 0;
            
        }

        $member[1]['1971'] = $society1971[1]+$society1971[20]+$society1971[22]+$member[1]['1951'];
        $member[9]['1971'] = $society1971[9]+$member[9]['1951'];
        $member[10]['1971'] = $society1971[10]+$member[10]['1951'];

        $society1991 = $this->CooperativeRegistrations->find('list',['keyField'=>'sector_of_operation','valueField'=>'count'])->group(['sector_of_operation']);            
        $society1991 = $society1991->select(['sector_of_operation','count' => $society1991->func()->count('status')])->where([$search_condition,'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'date_registration >='=> '1991-01-01','date_registration <='=> '2010-12-31'])->group(['sector_of_operation'])->toarray();

        if(empty($society1991))
        {
            $society1991[1] = 0;
            $society1991[20] = 0;
            $society1991[22] = 0;
            $society1991[9] = 0;
            $society1991[10] = 0;
        }

        $member[1]['1991'] = $society1991[1]+$society1991[20]+$society1991[22]+$member[1]['1971'];
        $member[9]['1991'] = $society1991[9]+$member[9]['1971'];
        $member[10]['1991'] = $society1991[10]+$member[10]['1971'];
        
        $society2011 = $this->CooperativeRegistrations->find('list',['keyField'=>'sector_of_operation','valueField'=>'count'])->group(['sector_of_operation']);            
        $society2011 = $society2011->select(['sector_of_operation','count' => $society2011->func()->count('status')])->where([$search_condition,'is_draft'=>'0','status'=>'1','is_approved !=' => '2' , 'date_registration >='=> '2011-01-01','date_registration <='=> date('Y-m-d')])->group(['sector_of_operation'])->toarray();

        if(empty($society2011))
        {
            $society2011[1] = 0;
            $society2011[20] = 0;
            $society2011[22] = 0;
            $society2011[9] = 0;
            $society2011[10] = 0;
        }

        $member[1]['2011'] = $society2011[1]+$society2011[20]+$society2011[22]+$member[1]['1991'];
        $member[9]['2011'] = $society2011[9]+$member[9]['1991'];
        $member[10]['2011'] = $society2011[10]+$member[10]['1991'];

        return $member;
    }


    public function gpPacsPieChart($s_sector,$s_state,$s_district,$s_block)
    {

        $this->loadModel('PrimaryActivities');
        $this->loadModel('PacsPanchayatReport');
        $this->loadModel('DistrictsBlocksGpVillages');
        $this->loadModel('CoverageAreas');
        $this->loadModel('CooperativeRegistrations');

        $notcovered_gp = 0;
        $full_covered_gp = 0;
        $partial_covered_gp = 0;
        $all_gp = 0;

        $sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();


        $pac_count = $this->CooperativeRegistrations->find('all')->where(['is_draft'=>'0','status'=>'1','is_approved !=' => '2','sector_of_operation IN'=>[1,20,22]])->count();
        
        unset($sectors[20]);
        unset($sectors[22]);

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
            'notcovered_gp' => $notcovered_gp,'full_covered_gp' => $full_covered_gp,'partial_covered_gp' => $partial_covered_gp,'all_gp' => $all_gp,'pac_count'=>$pac_count
        ];

        // echo '<pre>';
        // print_r($arr_gp_data);die;

        return $arr_gp_data;
    }

}
