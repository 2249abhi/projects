<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
/**
 * CooperativeRegistrations Controller
 *
 * @property \App\Model\Table\CooperativeRegistrationsTable $CooperativeRegistrations
 *
 * @method \App\Model\Entity\State[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MemberReportsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','getdccb','getfederationlevel','approval','bulkapproval','getUrbanRural']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadModel('PrimaryActivities');
        $this->loadModel('States');
        $this->loadModel('Districts');

        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $states = $query->toArray();

        $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC'])->toArray();

        if (isset($this->request->query['functional_status']) && $this->request->query['functional_status'] !='') {
            
            $functional_status = trim($this->request->query['functional_status']);
            $search_condition[] = "CooperativeRegistrations.functional_status = '" . $functional_status . "'";
        }

        $flag = 0;

        $districts = [];

        if($this->request->session()->read('Auth.User.role_id') == 11)
        {
            $state= $this->request->session()->read('Auth.User.state_code');
            $this->set('s_state', $state);

             $states=$this->States->find('list',['keyField'=>'id','valueField'=>'name'])->where(['state_code'=>$state])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
             $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$state])->order(['name'=>'ASC'])->toArray();
             $flag = 1;
        }

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            
            $state = trim($this->request->query['state']);
            $this->set('s_state', $state);
            $search_condition[] = "CooperativeRegistrations.state_code = '" . $state . "'";
            $flag = 1;

            $district_query =$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name','conditions'=>['flag'=>1,'state_code'=>$this->request->query['state']],'order' => ['name' => 'ASC']]);
            $district_query->hydrate(false);
            $districts = $district_query->toArray();
        }

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $district = trim($this->request->query['district']);
            $this->set('s_district', $district);
            $search_condition[] = "CooperativeRegistrations.district_code = '" . $district . "'";

            $district_query =$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name','conditions'=>['flag'=>1,'state_code'=>$this->request->query['state'],'district_code'=>$this->request->query['district']],'order' => ['name' => 'ASC']]);
            $district_query->hydrate(false);
            $districts = $district_query->toArray();
        }

        
        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }
        $sector_data = [];
        $arr_state_data = [];

        if($flag == 0)
        {
            $sector_data = $this->CooperativeRegistrations->find('all',[
                        'conditions' => [$searchString]
                    ])->join([
                    'states' => [
                        'table' => 'states',
                        'type' => 'INNER',
                        'conditions' => 'states.state_code = CooperativeRegistrations.state_code'
                    ],
                    'primary_activities' => [
                        'table' => 'primary_activities',
                        'type' => 'INNER',
                        'alias' => 'pactivities',
                        'conditions' => 'pactivities.id = CooperativeRegistrations.sector_of_operation'
                    ]])
                    ->select(['states.id','CooperativeRegistrations.sector_of_operation','total' => 'sum(CooperativeRegistrations.members_of_society)'])
                    ->where(['is_draft'=>0,'CooperativeRegistrations.status'=>1,'is_approved !='=>2,'members_of_society*1 <'=>100000])
                    ->group(['states.state_code'])
                    ->group(['CooperativeRegistrations.sector_of_operation'])
                    ->order(['states.name'=>'ASC'])
                    ->toArray();
                

               
            foreach ($sector_data as $key => $value) {
                
                $arr_state_data[$value['states']['id']][$value['sector_of_operation']] = $value['total'];
            }


        } else {

            foreach ($districts as $district_code => $district_name) 
            {

                $sector_data[$district_code] = $this->CooperativeRegistrations->find('all',[
                            'conditions' => [$searchString]
                        ])->join([
                        'districts' => [
                            'table' => 'districts',
                            'type' => 'INNER',
                            'conditions' => 'districts.district_code = CooperativeRegistrations.district_code'
                        ],
                        'states' => [
                            'table' => 'states',
                            'type' => 'INNER',
                            'conditions' => 'states.state_code = CooperativeRegistrations.state_code'
                        ],
                        'primary_activities' => [
                            'table' => 'primary_activities',
                            'type' => 'INNER',
                            'alias' => 'pactivities',
                            'conditions' => 'pactivities.id = CooperativeRegistrations.sector_of_operation'
                        ]])
                        ->select(['districts.id','CooperativeRegistrations.sector_of_operation','total' => 'sum(CooperativeRegistrations.members_of_society)'])
                        ->where(['is_draft'=>0,'CooperativeRegistrations.status'=>1,'is_approved !='=>2,'members_of_society*1 <'=>100000,'CooperativeRegistrations.district_code'=>$district_code,'CooperativeRegistrations.state_code'=>$state])
                        //->group(['states.state_code'])
                        ->group(['CooperativeRegistrations.sector_of_operation'])
                        ->order(['districts.name'=>'ASC'])
                        ->toArray();

                        
            }
            
            foreach ($sector_data as $sector_district_code => $sector_district_data) 
            {
                
                foreach ($PrimaryActivities as $master_pid => $master_pid_name) 
                {
                    foreach ($sector_district_data as $key => $data) {
                        
                        if($data['sector_of_operation'] == $master_pid && $data['districts']['id'] == $sector_district_code)
                        {
                            $arr_state_data[$sector_district_code][$data['sector_of_operation']] = $data['total'];
                        }
                    }
                }
            
            }

        }
        

        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

        

        $this->set(compact('arr_state_data','PrimaryActivities','states','presentFunctionalStatus','districts','flag')) ;

    }


    public function memberCountLessthenOneLakh()
    {
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadModel('PrimaryActivities');
        $this->loadModel('States');
        $this->loadModel('Districts');

        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $states = $query->toArray();

        $PrimaryActivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['name'=>'ASC'])->toArray();

        if (isset($this->request->query['functional_status']) && $this->request->query['functional_status'] !='') {
            
            $functional_status = trim($this->request->query['functional_status']);
            $search_condition[] = "CooperativeRegistrations.functional_status = '" . $functional_status . "'";
        }

        $flag = 0;

        $districts = [];

        if($this->request->session()->read('Auth.User.role_id') == 11)
        {
            $state= $this->request->session()->read('Auth.User.state_code');
            $this->set('s_state', $state);

             $states=$this->States->find('list',['keyField'=>'id','valueField'=>'name'])->where(['state_code'=>$state])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
             $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$state])->order(['name'=>'ASC'])->toArray();
             $flag = 1;
        }

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            
            $state = trim($this->request->query['state']);
            $this->set('s_state', $state);
            $search_condition[] = "CooperativeRegistrations.state_code = '" . $state . "'";
            $flag = 1;

            $district_query =$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name','conditions'=>['flag'=>1,'state_code'=>$this->request->query['state']],'order' => ['name' => 'ASC']]);
            $district_query->hydrate(false);
            $districts = $district_query->toArray();
        }

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $district = trim($this->request->query['district']);
            $this->set('s_district', $district);
            $search_condition[] = "CooperativeRegistrations.district_code = '" . $district . "'";

            $district_query =$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name','conditions'=>['flag'=>1,'state_code'=>$this->request->query['state'],'district_code'=>$this->request->query['district']],'order' => ['name' => 'ASC']]);
            $district_query->hydrate(false);
            $districts = $district_query->toArray();
        }

        
        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }
        $sector_data = [];
        $arr_state_data = [];

        if($flag == 0)
        {
            $sector_data = $this->CooperativeRegistrations->find('all',[
                'conditions' => [$searchString]
            ])->join([
            'states' => [
                'table' => 'states',
                'type' => 'INNER',
                'conditions' => 'states.state_code = CooperativeRegistrations.state_code'
            ],
            'primary_activities' => [
                'table' => 'primary_activities',
                'type' => 'INNER',
                'alias' => 'pactivities',
                'conditions' => 'pactivities.id = CooperativeRegistrations.sector_of_operation'
            ]])
            ->select(['states.id','CooperativeRegistrations.sector_of_operation','total' => 'sum(CooperativeRegistrations.members_of_society)'])
            ->where(['is_draft'=>0,'CooperativeRegistrations.status'=>1,'is_approved !='=>2,'members_of_society*1 <'=>1000000])
            ->group(['states.state_code'])
            ->group(['CooperativeRegistrations.sector_of_operation'])
            ->order(['states.name'=>'ASC'])
            ->toArray();
        
            foreach ($sector_data as $key => $value) {
                
                $arr_state_data[$value['states']['id']][$value['sector_of_operation']] = $value['total'];
            }

        } else {

            foreach ($districts as $district_code => $district_name) 
            {

                $sector_data[$district_code] = $this->CooperativeRegistrations->find('all',[
                            'conditions' => [$searchString]
                        ])->join([
                        'districts' => [
                            'table' => 'districts',
                            'type' => 'INNER',
                            'conditions' => 'districts.district_code = CooperativeRegistrations.district_code'
                        ],
                        'states' => [
                            'table' => 'states',
                            'type' => 'INNER',
                            'conditions' => 'states.state_code = CooperativeRegistrations.state_code'
                        ],
                        'primary_activities' => [
                            'table' => 'primary_activities',
                            'type' => 'INNER',
                            'alias' => 'pactivities',
                            'conditions' => 'pactivities.id = CooperativeRegistrations.sector_of_operation'
                        ]])
                        ->select(['districts.id','CooperativeRegistrations.sector_of_operation','total' => 'sum(CooperativeRegistrations.members_of_society)'])
                        ->where(['is_draft'=>0,'CooperativeRegistrations.status'=>1,'is_approved !='=>2,'members_of_society*1 <'=>1000000,'CooperativeRegistrations.district_code'=>$district_code,'CooperativeRegistrations.state_code'=>$state])
                        //->group(['states.state_code'])
                        ->group(['CooperativeRegistrations.sector_of_operation'])
                        ->order(['districts.name'=>'ASC'])
                        ->toArray();

                        
            }
            
            foreach ($sector_data as $sector_district_code => $sector_district_data) 
            {
                
                foreach ($PrimaryActivities as $master_pid => $master_pid_name) 
                {
                    foreach ($sector_district_data as $key => $data) {
                        
                        if($data['sector_of_operation'] == $master_pid && $data['districts']['id'] == $sector_district_code)
                        {
                            $arr_state_data[$sector_district_code][$data['sector_of_operation']] = $data['total'];
                        }
                    }
                }
            
            }

        }
        

        $this->loadModel('PresentFunctionalStatus');
        $presentFunctionalStatus=$this->PresentFunctionalStatus->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

        

        $this->set(compact('arr_state_data','PrimaryActivities','states','presentFunctionalStatus','districts','flag')) ;

    }

   
}