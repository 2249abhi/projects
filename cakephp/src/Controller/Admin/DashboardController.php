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
		$this->loadModel('NewDistrictNodalEntries');
		$this->loadModel('UserDistricts'); 
        $this->Auth->allow(['getsectorraphs']);
		
		
		$state_code = $this->request->session()->read('Auth.User.state_code');
        $district_code = $this->request->session()->read('Auth.User.district_code');        
        $is_first_login = $this->Auth->user('is_first_login');
		$role_id =  $this->Auth->user('role_id');
		$user_id = $this->Auth->user('id'); 
		
       	
        if($is_first_login=='1' && $role_id != 66)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'changePassword']);
        }
		if($role_id == 8){
			$redirect = 0 ;
			$districts = $this->UserDistricts->find('all')->select(['district_id'])->where(['user_id' => $user_id])->hydrate(false)->toArray() ; 
		if(!empty($districts)){
			foreach($districts as $k => $v){ $allDistricts[] = $v['district_id'];} 
			}else{
				$allDistricts[] = array($district_code) ;
			}
        		
		$data = $this->NewDistrictNodalEntries->find('all')->select(['district_code'])->distinct('district_code')->where(['district_nodal_id' => $user_id])->hydrate(false)->toArray() ; 
		if(!empty($data)){
			foreach($data as $k => $v){
				$doneDistricts[] = $v['district_code'];
				} 
				if(count($allDistricts) <= count($doneDistricts)){
					$redirect = 0 ;
				}else{
					$redirect = 1 ; 
					//echo count($allDistricts),"sss".count($doneDistricts)  ; exit ;
				}
				}else{
					$redirect = 1 ;
				}
				/* if(count($allDistricts) == 1 && !empty($data)){
					$redirect = 0 ;
				}else{
					$redirect = 1 ;
				}*/
		   if($redirect == 1 ){
			
			return $this->redirect(['controller' => 'DistrictNodalEntries', 'action' => 'add']);
		   }
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
        $this->loadModel('DistrictReports');
       

        


        $this->set(compact('affliation_response','d_sectors','societies','sectors','members'));
    }
    public function getfederationdata($search_condition)
    {
        $search_condition2['is_approved !='] = 2; 
        $response=array();
         
        $registrationQuery_YES = $this->CooperativeRegistrations->find('all', [
            'order' => ['cooperative_society_name' => 'ASC'],
           //'contain'=>['AreaOfOperationLevel','CooperativeRegistrationPacs'],
            'conditions' => [$searchString,$search_condition2 , $search_condition]            
        ])->select(['id', 'sector_of_operation', 'is_affiliated_union_federation','state_code','district_code','operational_district_code','affiliated_union_federation_level','is_coastal','water_body_type_id'])->where(['is_draft'=>0,'status'=>1,'sector_of_operation_type'=>1,'sector_of_operation IN'=>[1,20,22],'is_affiliated_union_federation'=>1])->count();
        $registrationQuery_NO = $this->CooperativeRegistrations->find('all', [
            'order' => ['cooperative_society_name' => 'ASC'],
           //'contain'=>['AreaOfOperationLevel','CooperativeRegistrationPacs'],
            'conditions' => [$searchString,$search_condition2 , $search_condition]            
        ])->select(['id', 'sector_of_operation', 'is_affiliated_union_federation','state_code','district_code','operational_district_code','affiliated_union_federation_level','is_coastal','water_body_type_id'])->where(['is_draft'=>0,'status'=>1,'sector_of_operation_type'=>1,'sector_of_operation IN'=>[1,20,22],'is_affiliated_union_federation'=>0])->count();


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

        if($s_sector !=1)
        {

            if($this->request->session()->read('Auth.User.role_id') == 11)
            {
                $state_code=$this->request->session()->read('Auth.User.state_code');
                $pac_count = $this->CooperativeRegistrations->find('all')->where(['is_draft'=>'0','status'=>'1','is_approved !=' => '2','sector_of_operation'=>$s_sector,'state_code'=>$state_code])->count();
            }else{
                $pac_count = $this->CooperativeRegistrations->find('all')->where(['is_draft'=>'0','status'=>'1','is_approved !=' => '2','sector_of_operation'=>$s_sector])->count();
            }
            
        }else{
            if($this->request->session()->read('Auth.User.role_id') == 11)
            {
                $state_code=$this->request->session()->read('Auth.User.state_code');
                $pac_count = $this->CooperativeRegistrations->find('all')->where(['is_draft'=>'0','status'=>'1','is_approved !=' => '2','sector_of_operation IN'=>[1,20,22],'state_code'=>$state_code])->count();
            }
            else{
            $pac_count = $this->CooperativeRegistrations->find('all')->where(['is_draft'=>'0','status'=>'1','is_approved !=' => '2','sector_of_operation IN'=>[1,20,22]])->count();
            }
        }

        if($this->request->session()->read('Auth.User.role_id') == 11)
        {
            $s_state=$this->request->session()->read('Auth.User.state_code');
        }
      
        
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

                    if(empty($s_block))
                    {

                        $condtion['is_draft']=0;
                        $condtion['is_approved !=']=2;
                        $condtion['status']=1;
                        $condtion['status']=1;
                        
                        
                        
                        $total_village_count = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'count']);            
                        $total_village_count = $total_village_count->select(['gram_panchayat_code','count' => $total_village_count->func()->count('gram_panchayat_code')])->where([$search_condition,$gp_search_condition,'status'=>1,'district_code'=>$s_district])->group(['gram_panchayat_code'])->toArray();
    
                        $total_covered_village_count = $this->CoverageAreas->find('list',['keyField'=>'panchayat_code','valueField'=>'count']);            
                        $total_covered_village_count = $total_covered_village_count->select(['panchayat_code','count' => $total_covered_village_count->func()->count('panchayat_code')])->where([$search_condition,$gp_search_condition,$condtion,$sector_search_condition,'district_code'=>$s_district])->group(['panchayat_code','village_code'])->toArray();

                    }else{

                    

                    $condtion['is_draft']=0;
                    $condtion['is_approved !=']=2;
                    $condtion['status']=1;
                    
                    $total_village_count = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'count']);            
                    $total_village_count = $total_village_count->select(['gram_panchayat_code','count' => $total_village_count->func()->count('gram_panchayat_code')])->where([$search_condition,$gp_search_condition,'status'=>1 ,'block_code' => $s_block])->group(['gram_panchayat_code'])->toArray();

                    $total_covered_village_count = $this->CoverageAreas->find('list',['keyField'=>'panchayat_code','valueField'=>'count']);            
                    $total_covered_village_count = $total_covered_village_count->select(['panchayat_code','count' => $total_covered_village_count->func()->count('panchayat_code')])->where([$search_condition,$gp_search_condition,$condtion,$sector_search_condition,'block_code'=>$s_block])->group(['panchayat_code','village_code'])->toArray();
                    }

                

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

    public function getSectorGraphs123()
    {
        $this->viewBuilder()->setLayout('ajax');

        $type = $this->request->data['graph_type'];

        //echo '<pre>';
      //print_r($type);die;
        ///$this->set('CooperativeRegistration',$CooperativeRegistration);

        return $type;
    }

    public function getsectorraphs()
    {
        $this->viewBuilder()->setLayout('ajax');
        $this->loadModel('PrimaryActivities');
        $s_sector = $this->request->query['sector_operation'];   
        $arry_gp=[];
        $arry_gp= $this->gpPacsPieChart($s_sector,$s_state,$s_district,$s_block);
        $d_sectors = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();
        $response['coveredgp']=$arry_gp;
        $response['sector']=$d_sectors[$s_sector];           
        echo json_encode($response);
        exit;
      //  return $type;
    }

}
