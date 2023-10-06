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
class AllIndiaVillageReportController extends AppController
{
    //all-india-panchayat-report
    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','allindiareport','allindiarpanchyatreport','panchayatreports','statewisepacreport']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    
    public function villagereports()
    {
        $this->loadModel('States');             
        $this->loadModel('PrimaryActivities');        
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('DistrictsBlocksGpVillages');
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        

       // if(!empty($this->request->query['state']))
       if(1==1)
       {
        $villageA=[];
        $panchayatA=[];
        $ward=[];
        $this->loadMOdel('UrbanLocalBodiesWards');
        if($this->request->query['state'])
        {
        $state_code= $this->request->query['state'];
      //  $state_code=37;
        $condtion_district['state_code']=$state_code;
        $this->loadModel('DairyVillageReport'); 
      $check  =$this->DairyVillageReport->find('all')->where([$condtion_district ,'society_flag'=>1])->order(['state_code'=>'ASC'])->select(['state_code'])->group(['state_code'])->toarray();
      if(empty($check))
      {
        
        // $panchayats = $this->DistrictsBlocksGpVillages->find('all',['fields'=>'state_code','count'=>'vilagecount','gram_panchayat_code']);            
        // $panchayats = $panchayats->select(['state_code','vilagecount' => $panchayats->func()->count('DISTINCT village_code'), $gram_panchayat_code])->where([$condtion_district])->group(['gram_panchayat_code'])->toArray();

        //$rsA= $this->DistrictsBlocksGpVillages->find('all')->select(['state_code'])->where([$condtion_district])->group(['gram_panchayat_code'])->count();   
       

       $rs= $this->DistrictsBlocksGpVillages->find('all')->select(['state_code','state_name','village_code','gram_panchayat_code','village_name'])->where([$condtion_district])->group(['village_code'])->toArray();  
        
       $gppanc=[];
       $gppancname=[];

       $village=[];
       $villagename=[];

       foreach($rs as $key=>$value)
       {
            $village[$value['state_code']][$value['gram_panchayat_code']][$value['village_code']] +=1;
            $villagename[$value['village_code']]['name'] =$value['village_name'];
       }

    //    echo '<pre>';
    //    //print_r($village);
    //    print_r($villagename);
    //    die;
           
       $argpac=[];
       $argdairy=[];
       $arrayreport=$this->CooperativeRegistrations->find()
             ->contain(['AreaOfOperationLevel'=>[
               'queryBuilder' => function ($q) {
                     return $q
                        ->select([
                             'AreaOfOperationLevel.panchayat_code',
                             'AreaOfOperationLevel.village_code',
                            'AreaOfOperationLevel.cooperative_registrations_id',
                            'AreaOfOperationLevel.state_code',
                         ])->distinct('AreaOfOperationLevel.village_code');
                 }]])
             ->select(['CooperativeRegistrations.id'])               
           ->where(['CooperativeRegistrations.state_code'=>$state_code, 'CooperativeRegistrations.sector_of_operation' =>9,'CooperativeRegistrations.is_draft'=>0, 'CooperativeRegistrations.status'=>1,'CooperativeRegistrations.is_approved !='=>2])->toArray();

    //   echo "<pre>";
    //   echo count($arrayreport);die;
      // // print_r($arrayreport);
       //die;
        foreach($arrayreport as $ke1=>$value1)
        {
            foreach($value1['area_of_operation_level'] as $key2=> $value2)
            {
                //$argpac[$value2['state_code']][$value2['panchayat_code']] +=1;

                $argdairy[$value2['state_code']][$value2['panchayat_code']][$value2['village_code']] +=1;
            }

        }
            
        
       
        $acategories = $this->DairyVillageReport->newEntity();

        $state_code= $this->States->find('all')->where(['flag'=>1,$condtion_district])->order(['name'=>'ASC'])->select(['state_code','name'])->group(['state_code'])->toarray(); 
        // echo '<pre>';
        //     print_r($state_code);die; 
        foreach($state_code as $key=>$value)
        {

            $total_village = $this->DistrictsBlocksGpVillages->find('all')->select(['state_code'])->where([$condtion_district])->group(['village_code'])->count(); 

            
               
            $data['state_code']             = $value['state_code']; 
            $data['state_name']             = $value['name'];  
            $data['count_village']     = $total_village; 

            $partially_covered='';
            $full_covered='';
            foreach($argdairy[$value['state_code']] as  $key=>$valuer)
            {
               if($village[$value['state_code']][$key]==$valuer)
               {
                    $full_covered +=1;
               }
               else if($village[$value['state_code']][$key] > $valuer)
               {
                $partially_covered +=1;
               }
               
            }
            $data['count_partially_village']         = $partially_covered; 
            $data['count_covered_village']           = $full_covered;   
            $data['count_uncovered_village']         = $total_village-($partially_covered+$full_covered);
            $data['society_flag']                       = 1; 
            $data['total_dairy']                          = count($arrayreport);
              
            

            $acategories = $this->DairyVillageReport->patchEntity($acategories,  $data);

            if ($this->DairyVillageReport->save($acategories)) {
                echo "success";
                die;
                $this->Flash->success(__('The DairyVillageReport  has been saved.'));
            }
        }
    
        die;
        
    }else{
        echo "invalid";
        die;
    }
    }
}
}

public function statewisepacreport()
{

    $this->loadModel('States'); 
    $sOption = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
    if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
        $s_state = trim($this->request->query['state']);   
        
        $condtion_district['state_code'] = $this->request->query['state'];
        $condtiongp['state_code'] = $this->request->query['state'];      

     
        $this->set('s_state', $s_state);
    }

    $this->loadModel('PacsPanchayatReport'); 
    $state_code  =$this->PacsPanchayatReport->find('all')->where([$condtion_district ,'society_flag'=>1])->order(['state_name'=>'ASC']);  
    
  

    $this->paginate = ['limit' => 20];
   $state_code = $this->paginate($state_code);
   $this->set(compact('state_code','sOption'));


}


   


    function allindiapaclocation()
    {
        $this->loadModel('States');
       // $this->loadModel('Districts');
       // $this->loadModel('SubDistricts');
        $this->loadModel('PrimaryActivities');
       // $this->loadMOdel('UrbanLocalBodies');
        $this->loadMOdel('CooperativeRegistrations');
        $this->loadMOdel('DistrictsBlocksGpVillages');
        set_time_limit(120);
        ini_set('memory_limit', '-1');        
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $s_state = trim($this->request->query['state']);
            $condtion_districtA['state_code']= $s_state;
            $this->set('s_state', $s_state);
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
    //    if (isset($this->request->query['state']) && $this->request->query['state'] !='') 
    //    {
    //          $query =$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name','conditions'=>['state_code'=>$this->request->query['state']],'order' => ['name' => 'ASC']]);     
    //          $query->hydrate(false);
    //          $dist_opt = $query->toArray();
    //          $this->set('dist_opt',$dist_opt);
    //     }

        $district_codes = [];
        $dairies = [];
        $pacs = [];
        $fisheries = [];     

       if(1==1)
       {
        $villageA=[];
        $panchayatA=[];
        $ward=[];
        $panchayat_code=[];
        $village_code=[];
        $panchayatgrouparray=[];
        $villagesgrouparray=[];
        
        $this->loadMOdel('UrbanLocalBodiesWards');
        foreach($stateOption as $key=>$value)
        {
          //  echo $key;
           $panchayatA[$key]  =  $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'state_code','valueField'=>'panchayat_code','conditions'=>['state_code'=>$key]])->group(['gram_panchayat_code'])->count();
           $villageA[$key]  =  $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'state_code','valueField'=>'village_code','conditions'=>['state_code'=>$key]])->group(['village_code'])->count();
           $ward[$key]  =  $this->UrbanLocalBodiesWards->find('list',['keyField'=>'state_code','valueField'=>'ward_code','conditions'=>['state_code'=>$key]])->group(['ward_code'])->count();
           $pacs_panchayat[$key] = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'id', 'conditions'=>['state_code'=>$key, 'sector_of_operation_type' => 1,'is_draft'=>0, 'status'=>1,'is_approved !='=>2,'location_of_head_quarter'=>2]])->group('gram_panchayat_code')->count();
           $pacs_vilage[$key] = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'id', 'conditions'=>['state_code'=>$key, 'sector_of_operation_type' => 1,'is_draft'=>0, 'status'=>1,'is_approved !='=>2,'location_of_head_quarter'=>2]])->group('village_code')->count();
           $pacs_ward[$key] = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'id', 'conditions'=>['state_code'=>$key, 'sector_of_operation_type' => 1,'is_draft'=>0, 'status'=>1,'is_approved !='=>2,'location_of_head_quarter'=>1]])->group('locality_ward_code')->count();
            $total_pac[$key]=$this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'id', 'conditions'=>['state_code'=>$key, 'sector_of_operation_type' => 1,'is_draft'=>0, 'status'=>1,'is_approved !='=>2]])->group('id')->count();

            // $arrayreport=$this->CooperativeRegistrations->find()
            // ->contain(['AreaOfOperationLevel'=>[
            //     'queryBuilder' => function ($q) {
            //         return $q
            //             ->select([
            //                 'AreaOfOperationLevel.panchayat_code',
            //                 'AreaOfOperationLevel.village_code',
            //                 'AreaOfOperationLevel.cooperative_registrations_id'
            //             ]);
            //     }]])
            // ->select(['CooperativeRegistrations.id'])               
            // ->where(['CooperativeRegistrations.state_code'=>$key, 'CooperativeRegistrations.sector_of_operation_type' =>1,'CooperativeRegistrations.is_draft'=>0, 'CooperativeRegistrations.status'=>1,'CooperativeRegistrations.is_approved !='=>2])->toarray();
          // echo "<pre>";
        //  print_r($arrayreport); 
         // die;
         
        //     foreach($arrayreport as $key1=>$valuea)
        //     {
        //         foreach($valuea['area_of_operation_level'] as $key123=>$value123)
        //         {                   
        //             //in_array("Glenn", $people) $panchayat_code[]=$value123['panchayat_code'];
        //             if(!in_array($value123['panchayat_code'], $panchayat_code))
        //             {
        //                 $panchayatgrouparray[$key][] =1;
        //             }
        //             if(!in_array($value123['village_code'], $village_code))
        //             {
        //                 $villagesgrouparray[$key][] =1;
        //             }


        //             $panchayat_code[]= $value123['panchayat_code'];

        //             $village_code[]=$value123['village_code'];

        //         }
             
        //     }     

        //     $villaget_operation[$key]= count($villagesgrouparray[$key]);

        //     $panchayat_operation[$key]= count($panchayatgrouparray[$key]);


         }
      //  echo "<pre>";
        //print_r($panchayat_operation);


            $this->set(compact('pacs_panchayat','pacs_vilage','pacs_ward','total_pac','panchayat_operation','villaget_operation'));          
             

            $state_code= $this->States->find('all')->where(['flag'=>1,$condtion_districtA])->order(['name'=>'ASC'])->select(['state_code'])->group(['state_code']);      
          
           // $pacs = $this->CooperativeRegistrations->find('list',['keyField'=>'state_code','valueField'=>'count']);            
           // $pacs = $pacs->select(['state_code','count' => $pacs->func()->count('sector_of_operation')])->where(['sector_of_operation IN' => [1,59,20,22],////$condtion_district,'is_draft'=>0, 'status'=>1, $condtion_location])->group(['state_code'])->toArray();          

         

            $this->paginate = ['limit' => 20];
           $state_code = $this->paginate($state_code);

        }
         
       // $arr_districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();

    
        $this->set(compact('district_codes','arr_districts','panchayatA','villageA','dairies','pacs','fisheries','district_nodal_tatal','state_code','fisheries_today','urban_bodys','ward'));

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
