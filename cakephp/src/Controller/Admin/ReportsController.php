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
class ReportsController extends AppController
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
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
        $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('DistrictsBlocksGpVillages');

        
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $s_state = trim($this->request->query['state']);
            $this->set('s_state', $s_state);
        }

         if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $district = trim($this->request->query['district']);
            $this->set('district', $district);
        }

        

        if (isset($this->request->query['location']) && $this->request->query['location'] !='') {
            $s_location = trim($this->request->query['location']);
            $this->set('s_location', $s_location);
        }

       
        
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }
        

        
        $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
        $query->hydrate(false);
        $stateOption = $query->toArray();
        $this->set('sOption',$stateOption);


       if (isset($this->request->query['state']) && $this->request->query['state'] !='') 
       {
            $query =$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name','conditions'=>['state_code'=>$this->request->query['state']],'order' => ['name' => 'ASC']]);
     
            $query->hydrate(false);
            $dist_opt = $query->toArray();
            $this->set('dist_opt',$dist_opt);
        }

        $district_codes = [];
        $dairies = [];
        $pacs = [];
        $fisheries = [];

        if(!empty($this->request->query['state']))
        {
            //$district_codes = $this->CooperativeRegistrations->find('all',array('conditions' => array('district_code IS NOT NULL')))->select(['district_code'])->where(['state_code'=>$this->request->query['state']])->group(['district_code'])->extract('district_code');
            $condtion_district=[];
            if(!empty($this->request->query['district']))
            {
                $condtion_district['district_code'] = $this->request->query['district'];
            }

           // print_r($condtion_district);

           $district_codes= $this->Districts->find('all')->where(['flag'=>1,'state_code'=>$this->request->query['state'] ,$condtion_district])->order(['name'=>'ASC'])->select(['district_code']);

          // print_r($district_codes);

            //Panchayats
            $panchayats = $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['district_code IS NOT NULL'],'keyField'=>'district_code','valueField'=>'count']);
            
            $panchayats = $panchayats->select(['district_code','count' => $panchayats->func()->count('gram_panchayat_code')])->where(['state_code'=>$this->request->query['state'] , $condtion_district])->group(['district_code'])->toArray();

            //Villages
            $villages = $this->DistrictsBlocksGpVillages->find('list',['conditions' => ['district_code IS NOT NULL'],'keyField'=>'district_code','valueField'=>'count']);
            
            $villages = $villages->select(['district_code','count' => $villages->func()->count('village_code')])->where(['state_code'=>$this->request->query['state'] ,$condtion_district])->group(['district_code'])->toArray();

            //Dairies
            $dairies = $this->CooperativeRegistrations->find('list',['conditions' => ['district_code IS NOT NULL'],'keyField'=>'district_code','valueField'=>'count']);
            


            $dairies = $dairies->select(['district_code','count' => $dairies->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [9,37],'state_code'=>$this->request->query['state'], $condtion_district])->group(['district_code'])->toArray();

             $this->loadModel('DistrictNodalEntries');

           //  echo $this->request->query['state'];
            $total_district_nodal = $this->DistrictNodalEntries->find('all',['conditions' => ['state_code'=>$this->request->query['state'] ,$condtion_district]])->toarray();
            $district_nodal_tatal=array();


            //print_r($total_district_nodal);

            foreach($total_district_nodal as $key=>$value)
            {
                 $district_nodal_tatal['pacs'][$value['state_code']][$value['district_code']]=$value['pacs_count'];
                 $district_nodal_tatal['dairy'][$value['state_code']][$value['district_code']]=$value['dairy_count'];
                 $district_nodal_tatal['fisfhery'][$value['state_code']][$value['district_code']]=$value['fishery_count'];
            }

         // print_r($district_notal_tatal);
          //  die;

            $state_code=$this->request->query['state'];
            //Pacs
            $pacs = $this->CooperativeRegistrations->find('list',['conditions' => ['district_code IS NOT NULL'],'keyField'=>'district_code','valueField'=>'count']);
            
            $pacs = $pacs->select(['district_code','count' => $pacs->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [1,59],'state_code'=>$this->request->query['state'] ,$condtion_district])->group(['district_code'])->toArray();

            //Fisheries
            $fisheries = $this->CooperativeRegistrations->find('list',['conditions' => ['district_code IS NOT NULL'],'keyField'=>'district_code','valueField'=>'count']);
            
            $fisheries = $fisheries->select(['district_code','count' => $fisheries->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [10,43],'state_code'=>$this->request->query['state'], $condtion_district])->group(['district_code'])->toArray();

             $this->paginate = ['limit' => 20];
            $district_codes = $this->paginate($district_codes);

        }
              
       

        $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

    
        $this->set(compact('district_codes','arr_districts','panchayats','villages','dairies','pacs','fisheries','district_nodal_tatal','state_code'));
        
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
