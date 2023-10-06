<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * CooperativeSocietyTypes Controller
 *
 * @property \App\Model\Table\CooperativeSocietyTypesTable $CooperativeSocietyTypes
 *
 * @method \App\Model\Entity\CooperativeSocietyTypes[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DistrictNodalEntriesController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadMOdel('NewDistrictNodalEntries');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['getDistricts']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */ 
    public function adminindex()
    {

        $this->loadModel('CooperativeRegistrations');
        $this->loadModel('Districts');
		$this->loadModel('States');
		$this->loadModel('UserDistricts');
		$this->loadModel('NewDistrictNodalEntries');

         $state_code = $this->request->session()->read('Auth.User.state_code');
         $role_id= $this->request->session()->read('Auth.User.role_id');
         $district_code = $this->request->session()->read('Auth.User.district_code');

        $nodal_data_entry_ids = [];


        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $s_district = trim($this->request->query['district']);
            $this->set('s_district', $s_district);
            $search_condition[] = "district_code = '" . $s_district . "'";
        }

        if (isset($this->request->query['status']) && $this->request->query['status'] !='') {
            $s_status = trim($this->request->query['status']);
            $this->set('s_status', $s_status);
            $search_condition[] = "status = '" . $s_status . "'";
        }

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }
        
         if($role_id==8)
        {
			$district_code = $this->request->session()->read('Auth.User.district_code');

            $arr_sign_districts = [];

            $arr_sign_districts = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'operational_district_code'])->where(['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code'),'approved_by'=>$this->request->session()->read('Auth.User.id'),'district_code !='=>0])->group(['district_code'])->toArray(); 
			
			$arr_districts = $this->UserDistricts->find('all')->where(['UserDistricts.user_id'=>$this->request->session()->read('Auth.User.id')])->contain(['Districts'])->hydrate(false)->toArray() ; 
		    $arr_districts_final = array($this->request->session()->read('Auth.User.district_code')) ;
		     //echo "<pre>" ; print_r($arr_districts) ; exit ;
             if(!empty($arr_districts)) {
             foreach($arr_districts as $key =>$value){
			 array_push($arr_districts_final,$value['district']['district_code'])  ;
		     }	}
             
            if(!empty($arr_sign_districts))
            {
                $arr_sign_districts = array_keys($arr_sign_districts);
            } else {
                $arr_sign_districts = [0];
            }
            $arr_sign_districts = $arr_districts_final ; 
			$districtcount = count($arr_sign_districts) ;
			$this->set('districtcount',$districtcount) ;
            $nentries = $this->NewDistrictNodalEntries->find('all', [
                'order' => ['district_nodal_name'=>'ASC'],
                'conditions' => [$searchString,'state_code'=>$state_code,'district_code In'=>$arr_sign_districts,'district_nodal_id' => $this->request->session()->read('Auth.User.id')]
            ])->group(['district_code']);
          //  print_r($nentries);

        }else
        {  
	    
		
		$nentries =  $this->NewDistrictNodalEntries->find('all')->group(['district_code']) ; 
           $nentries_bk = $this->DistrictNodalEntries->find('all', [
                'order' => ['district_nodal_name'=>'ASC'],
                'conditions' => [$searchString]
            ])->group(['district_code']); 
        }
        
        //->find('All')->order(['sector_of_operation'=>'ASC'])->order(['orderseq'=>'ASC']);

        $this->paginate = ['limit' => 20];

        $nentries = $this->paginate($nentries_bk);

      //  $state_code = $this->request->session()->read('Auth.User.state_code');

        

       // $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
          $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
		  $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
		  
		 
        

        $this->set(compact('nentries','districts','states'));
    }
    public function index()
    {

        $this->loadModel('CooperativeRegistrations');
        $this->loadModel('Districts');
		$this->loadModel('States');
		$this->loadModel('UserDistricts');
		$this->loadModel('NewDistrictNodalEntries');

         $state_code = $this->request->session()->read('Auth.User.state_code');
         $role_id= $this->request->session()->read('Auth.User.role_id');
         $district_code = $this->request->session()->read('Auth.User.district_code');

        $nodal_data_entry_ids = [];


        if (isset($this->request->query['name']) && $this->request->query['name'] !='') {
            $s_name = trim($this->request->query['name']);
            $this->set('d_name', $d_name);
            $search_condition[] = "name like '%" . $s_name . "%'";
        }

        if (isset($this->request->query['status']) && $this->request->query['status'] !='') {
            $s_status = trim($this->request->query['status']);
            $this->set('s_status', $s_status);
            $search_condition[] = "status = '" . $s_status . "'";
        }

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }
        
         if($role_id==8)
        {
			$district_code = $this->request->session()->read('Auth.User.district_code');

            $arr_sign_districts = [];

            $arr_sign_districts = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'operational_district_code'])->where(['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code'),'approved_by'=>$this->request->session()->read('Auth.User.id'),'district_code !='=>0])->group(['district_code'])->toArray(); 
			
			$arr_districts = $this->UserDistricts->find('all')->where(['UserDistricts.user_id'=>$this->request->session()->read('Auth.User.id')])->contain(['Districts'])->hydrate(false)->toArray() ; 
		    $arr_districts_final = array($this->request->session()->read('Auth.User.district_code')) ;
		     //echo "<pre>" ; print_r($arr_districts) ; exit ;
             if(!empty($arr_districts)) {
             foreach($arr_districts as $key =>$value){
			 array_push($arr_districts_final,$value['district']['district_code'])  ;
		     }	}
             
            if(!empty($arr_sign_districts))
            {
                $arr_sign_districts = array_keys($arr_sign_districts);
            } else {
                $arr_sign_districts = [0];
            }
            $arr_sign_districts = $arr_districts_final ; 
			$districtcount = count($arr_sign_districts) ;
			$this->set('districtcount',$districtcount) ;
            $nentries = $this->NewDistrictNodalEntries->find('all', [
                'order' => ['district_nodal_name'=>'ASC'],
                'conditions' => [$searchString,'state_code'=>$state_code,'district_code In'=>$arr_sign_districts,'district_nodal_id' => $this->request->session()->read('Auth.User.id')]
            ])->group(['district_code']);
          //  print_r($nentries);

        }else
        {  
	    
		
		$nentries =  $this->NewDistrictNodalEntries->find('all')->where(['district_nodal_id' => $this->request->session()->read('Auth.User.id') ,'district_code'=>$district_code , 'state_code'=> $state_code])->group(['district_code']) ; 
           $nentries_bk = $this->DistrictNodalEntries->find('all', [
                'order' => ['district_nodal_name'=>'ASC'],
                'conditions' => [$searchString]
            ])->group(['district_code']); 
        }
        
        //->find('All')->order(['sector_of_operation'=>'ASC'])->order(['orderseq'=>'ASC']);

        $this->paginate = ['limit' => 20];

        $nentries = $this->paginate($nentries);

      //  $state_code = $this->request->session()->read('Auth.User.state_code');

        

       // $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
          $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
		  $states=$this->States->find('all')->where(['id' => $state_code])->first();
		  
		 
        

        $this->set(compact('nentries','districts','states'));
    }

    /**
     * View method
     *
     * @param string|null $id CooperativeSocietyTypes id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function viewbk($id = null)
    {
        $this->loadModel('Districts');
        $this->loadModel('States');
        /*$designation = $this->Designations->get($id, [
            'contain' => ['Departments']
        ]);*/
        $nentry = $this->DistrictNodalEntries->get($id);

        $district = $this->Districts->find('all')->where(['id' => $nentry->district_code])->first();

        $state = $this->States->find('all')->where(['id' => $nentry->state_code])->first();
        $state_name = $state->name;
        $district_name = $district->name;


        $this->set(compact('nentry','district_name','state_name'));

    }
	public function view($id = null)
    { 
	
	$this->loadModel('CooperativeRegistrations');
        $this->loadModel('Districts'); 
		$this->loadModel('PrimaryActivities');
		$this->loadModel('NewDistrictNodalEntries');
		$this->loadModel('DistrictCoFedUn');
		$save = 'notok' ;
		$arraycount = 0 ;
		$DistrictCoFedUn = $this->DistrictCoFedUn->find('all')->where(['district_code' => $id,'district_nodal_id'=>$this->request->session()->read('Auth.User.id')])->contain(['PrimaryActivities'])->hydrate(false)->toArray() ;  
		
		//echo "<pre>" ; print_r($DistrictCoFedUn) ; exit ;
			//$arr_primary_act = $this->DistrictNodalEntries->find('all')->where(['status'=>1])->order(['orderseq'=>'ASC'])->contain(['PrimaryActivities'])->hydrate(false)->toArray() ;  
			//echo "<pre>" ; print_r($arr_primary_act) ; exit ;
			
			
			$arr_primary_act = $this->PrimaryActivities->find('all')->where(['status IN'=>[1,4],'id NOT IN'=>[1,9,10,20,22]])->order(['orderseq'=>'ASC'])->hydrate(false)->toArray() ; 
		
		
		//echo "<pre>" ; print_r($arr_primary_act) ; exit ;
		$this->set("arr_primary_act",$arr_primary_act) ;

       
        $state_code = $this->request->session()->read('Auth.User.state_code');
        $district_code = $this->request->session()->read('Auth.User.district_code'); 
		
		$valueArray =  $this->NewDistrictNodalEntries->find('all')->where(['district_nodal_id' => $this->request->session()->read('Auth.User.id') ,'district_code'=>$id , 'state_code'=> $state_code ,'PrimaryActivities.status IN'=>[1,4],'PrimaryActivities.id NOT IN'=>[1,9,10,20,22]])->contain(['PrimaryActivities'])->order(['PrimaryActivities.orderseq'=>'ASC'])->hydrate(false)->toArray() ; 
		//echo "<pre>" ; print_r($valueArray) ; exit ;
		$this->set('valueArray' ,$valueArray) ;
        
        $arr_sign_districts = [];

        $arr_sign_districts = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'operational_district_code'])->where(['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code'),'approved_by'=>$this->request->session()->read('Auth.User.id'),'district_code !='=>0])->group(['district_code'])->toArray();
        

        if(!empty($arr_sign_districts))
        {
            $arr_sign_districts = array_keys($arr_sign_districts);
        } else {
            $arr_sign_districts = [$district_code];
        }

        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$id])->order(['name'=>'ASC'])->toArray();

        if ($this->request->is('post')) {

            $data = $this->request->getData(); 
			
			//echo "<pre>" ; print_r( $data['primary_activity_count']) ; exit ;
               $primary_activity_count_array =  $data['primary_activity_count'] ; 
			    $primary_activity_array = $data['primaryactivitity_id'] ;
            $exists = $this->NewDistrictNodalEntries->exists(['district_code' => $data['district_code']]);
            if ($exists == true) {
				$this->NewDistrictNodalEntries->deleteAll(['district_code' => $data['district_code']]);
                //$this->Flash->error(__('Nodal Entry Already exist.'));
                //return $this->redirect($this->referer());
            }
                        
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['state_code'] = $state_code;
            $data['district_nodal_id'] = $this->request->session()->read('Auth.User.id');

            
			//echo "<pre>" ; print_r( $data['primaryactivitity_id']) ; exit ;
                foreach($primary_activity_count_array as $key=>$value){
					 $nentry = $this->NewDistrictNodalEntries->newEntity();
					
					$data['primary_activity_count'] = $value ;
					$data['primaryactivitity_id'] = $primary_activity_array[$arraycount] ; 
					
					$nentry = $this->NewDistrictNodalEntries->patchEntity($nentry, $data); 
					$nentry->primaryactivitity_id = $primary_activity_array[$arraycount];
					
					if($this->NewDistrictNodalEntries->save($nentry)){
				    $save = 'ok' ;
					}else{
						$save = 'notok' ;
					}
					$arraycount++;
				}
				//echo $arraycount ; exit ;
            if ($save == 'ok' ) {
                $this->Flash->success(__('The Nodal Entry has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Nodal Entry could not be saved. Please, try again.'));
        }

            // if($district_code!='')
            // {
            //     $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code,'district_code'=>$district_code]);
            // }else{
            //      $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code]);
            // }


        $uname = $this->request->session()->read('Auth.User.name');
        $this->set(compact('nentry','districts','uname','district_code','id','DistrictCoFedUn'));
		
	}

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $this->loadModel('CooperativeRegistrations');
        $this->loadModel('Districts'); 
		$this->loadModel('PrimaryActivities');
		$this->loadModel('NewDistrictNodalEntries');
		$this->loadModel('UserDistricts'); 
		$this->loadModel('DistrictCoFedUn');
		$save = 'notok' ;
		$arraycount = 0 ;
		$arraycount1 = 0 ;
		$arr_districts = $this->UserDistricts->find('all')->where(['UserDistricts.user_id'=>$this->request->session()->read('Auth.User.id')])->contain(['Districts'])->hydrate(false)->toArray() ; 
		$arr_districts_final = array($this->request->session()->read('Auth.User.district_code')) ;
		
        foreach($arr_districts as $key =>$value){
			 array_push($arr_districts_final,$value['district']['district_code'])  ;
		 }	
        //echo "<pre>" ; print_r($arr_districts_final) ; exit ;			 
			//$arr_primary_act = $this->DistrictNodalEntries->find('all')->where(['status'=>1])->order(['orderseq'=>'ASC'])->contain(['PrimaryActivities'])->hydrate(false)->toArray() ;  
			//echo "<pre>" ; print_r($arr_primary_act) ; exit ;
			
			
		$arr_primary_act = $this->PrimaryActivities->find('all')->where(['status IN'=>[1,4] ,'id NOT IN'=>[1,9,10,20,22]])->order(['orderseq'=>'ASC'])->hydrate(false)->toArray() ; 
		
		
		//echo "<pre>" ; print_r($arr_primary_act) ; exit ;
		$this->set("arr_primary_act",$arr_primary_act) ;

       
        $state_code = $this->request->session()->read('Auth.User.state_code');
        $district_code = $this->request->session()->read('Auth.User.district_code'); 
		
		//$valueArray =  $this->NewDistrictNodalEntries->find('all')->where(['district_nodal_id' => $this->request->session()->read('Auth.User.id') ,'district_code'=>$district_code , 'state_code'=> $state_code])->hydrate(false)->toArray() ; 
		
		//echo "<pre>" ; print_r($valueArray) ; exit ;
		//$this->set('valueArray' ,$valueArray) ;
        
        $arr_sign_districts = [];

        $arr_sign_districts = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'operational_district_code'])->where(['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code'),'approved_by'=>$this->request->session()->read('Auth.User.id'),'district_code !='=>0])->group(['district_code'])->toArray();
        
        //echo "<pre>" ; print_r($arr_sign_districts) ; exit ;
        if(!empty($arr_sign_districts))
        {
            $arr_sign_districts = array_keys($arr_sign_districts);
        } else {
            $arr_sign_districts = [$district_code];
        } 
		
		 $arr_sign_districts = $arr_districts_final ;
         $valueArray =  $this->NewDistrictNodalEntries->find('all')->where(['district_nodal_id' => $this->request->session()->read('Auth.User.id') ,'district_code'=>$id , 'state_code'=> $state_code ,'PrimaryActivities.status IN'=>[1,4] ,'PrimaryActivities.id NOT IN'=>[1,9,10,20,22]])->contain(['PrimaryActivities'])->order(['PrimaryActivities.orderseq'=>'ASC'])->hydrate(false)->toArray() ; 
         $valueArraysexist = array() ;		 
		 foreach($valueArray as $key =>$value){
			  array_push($valueArraysexist, $value['district_code']);
		 }
		 //echo "<pre>" ; print_r($valueArraysexist) ; exit ; 
		 $result = array_merge(array_diff($arr_sign_districts, $valueArraysexist), array_diff($valueArraysexist, $arr_sign_districts));
		 //echo "<pre>" ; print_r($result) ; exit ; 
        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'district_code IN'=>$result])->order(['name'=>'ASC'])->toArray();

        if ($this->request->is('post')) {

            $data = $this->request->getData();  
			//echo "<pre>" ; print_r( $data) ; exit ;
               $primary_activity_count_array =  $data['primary_activity_count'] ; 
			    $primary_activity_array = $data['primaryactivitity_id'] ;
            $exists = $this->NewDistrictNodalEntries->exists(['district_code' => $data['district_code'] ,'district_nodal_name'=> $data['district_nodal_name'],'district_nodal_id'=>$this->request->session()->read('Auth.User.id')]);
            if ($exists == true) {
				$this->NewDistrictNodalEntries->deleteAll(['district_code' => $data['district_code'] ,'district_nodal_name'=> $data['district_nodal_name'],'district_nodal_id'=>$this->request->session()->read('Auth.User.id')]);
            }
                        
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['state_code'] = $state_code;
            $data['district_nodal_id'] = $this->request->session()->read('Auth.User.id');

                foreach($primary_activity_count_array as $key=>$value){
					 $nentry = $this->NewDistrictNodalEntries->newEntity();
					
					$data['primary_activity_count'] = $value ;
					$data['primaryactivitity_id'] = $primary_activity_array[$arraycount] ; 
					
					$nentry = $this->NewDistrictNodalEntries->patchEntity($nentry, $data); 
					$nentry->primaryactivitity_id = $primary_activity_array[$arraycount];
					
					if($this->NewDistrictNodalEntries->save($nentry)){
				    $save = 'ok' ;
					}else{
						$save = 'notok' ;
					}
					$arraycount++;
				}
				$exists1 = $this->DistrictCoFedUn->exists(['district_code' => $data['district_code'] ,'district_nodal_name'=> $data['district_nodal_name'],'district_nodal_id'=>$this->request->session()->read('Auth.User.id')]);
				if($data['dcfu'] == 'yes'){
            if ($exists1 == true) {
				$this->DistrictCoFedUn->deleteAll(['district_code' => $data['district_code'] ,'district_nodal_name'=> $data['district_nodal_name'] ,'district_nodal_id'=>$this->request->session()->read('Auth.User.id')]);
            }
			if($data['name_coop'][0]!= ''){
				//echo "<pre>" ; print_r($data['name_coop']) ; exit ;
			$data1['created'] = date("Y-m-d H:i:s");
            $data1['state_code'] = $state_code; 
			$data1['district_code'] = $data['district_code'];
            $data1['district_nodal_id'] = $this->request->session()->read('Auth.User.id');
			$data1['district_nodal_name'] =  $data['district_nodal_name'] ;
			foreach($data['primaryactivitityid'] as $key=>$value){
					 $nentry1 = $this->DistrictCoFedUn->newEntity();
					
					$data1['primaryactivity_id'] = $value ;
					$data1['name'] = $data['name_coop'][$arraycount1] ; 
					
					$nentry1 = $this->DistrictCoFedUn->patchEntity($nentry1, $data1); 
					
					
				    $this->DistrictCoFedUn->save($nentry1);
					$arraycount1++;
				}
		}
		}
				
				//echo $arraycount ; exit ;
            if ($save == 'ok' ) {
                $this->Flash->success(__('The Nodal Entry has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Nodal Entry could not be saved. Please, try again.'));
        }
         
		 $primary_activities_dropdown = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();

        $uname = $this->request->session()->read('Auth.User.name');
        $this->set(compact('districts','uname','district_code','primary_activities_dropdown'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Designation id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
	  public function edit($id = null)
    {
        $this->loadModel('CooperativeRegistrations');
        $this->loadModel('Districts'); 
		$this->loadModel('PrimaryActivities');
		$this->loadModel('NewDistrictNodalEntries');
		$this->loadModel('DistrictCoFedUn');
		$save = 'notok' ;
		$arraycount = 0 ;
		$arraycount1 = 0 ;
			
        $state_code = $this->request->session()->read('Auth.User.state_code');
        $district_code = $this->request->session()->read('Auth.User.district_code'); 
		$DistrictCoFedUn = $this->DistrictCoFedUn->find('all')->where(['district_code' => $id,'district_nodal_id'=>$this->request->session()->read('Auth.User.id')])->hydrate(false)->toArray() ; 
		//echo $district_code."sss".$this->request->session()->read('Auth.User.id') ; 
        //echo "<pre>" ; print_r($DistrictCoFedUn) ; exit ;
		$valueArray =  $this->NewDistrictNodalEntries->find('all')->where(['district_nodal_id' => $this->request->session()->read('Auth.User.id') ,'district_code'=>$id , 'state_code'=> $state_code ,'PrimaryActivities.status IN'=>[1,4] ,'PrimaryActivities.id NOT IN'=>[1,9,10,20,22]])->contain(['PrimaryActivities'])->order(['PrimaryActivities.orderseq'=>'ASC'])->hydrate(false)->toArray() ; 
		
		//echo "<pre>" ; print_r($valueArray) ; exit ;
		$arr_primary_acts = $this->PrimaryActivities->find('all')->where(['status IN'=>[1,4],'id NOT IN'=>[1,9,10,20,22]])->order(['orderseq'=>'ASC'])->hydrate(false)->toArray() ;
		foreach($valueArray as $key => $value){
			$existPrimaryact[] = $value['primary_activity']['id'] ;
		}
        $existPrimaryactArray =  array_merge($existPrimaryact,[1,9,10,20,22]) ;
		$arr_primary_act = $this->PrimaryActivities->find('all')->where(['status IN'=>[1,4], 'id NOT IN' => $existPrimaryactArray])->order(['orderseq'=>'ASC'])->hydrate(false)->toArray() ; 
		
		
		//echo "<pre>" ; print_r($arr_primary_act) ; exit ;
		$this->set("arr_primary_act",$arr_primary_act) ;
		
		//echo "<pre>" ; print_r($existPrimaryact) ; exit ;
		$this->set('valueArray' ,$valueArray) ;
        
        $arr_sign_districts = [];

        $arr_sign_districts = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'operational_district_code'])->where(['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code'),'approved_by'=>$this->request->session()->read('Auth.User.id'),'district_code !='=>0])->group(['district_code'])->toArray();
        

        if(!empty($arr_sign_districts))
        {
            $arr_sign_districts = array_keys($arr_sign_districts);
        } else {
            $arr_sign_districts = [$district_code];
        }

        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$id])->order(['name'=>'ASC'])->toArray();

        if ($this->request->is('post')) {

               $data = $this->request->getData(); 
			
			   //echo "<pre>" ; print_r( $data['primary_activity_count']) ; exit ;
                $primary_activity_count_array =  $data['primary_activity_count'] ; 
			    $primary_activity_array = $data['primaryactivitity_id'] ;
				$exists = $this->NewDistrictNodalEntries->exists(['district_code' => $data['district_code'] ,'district_nodal_name'=> $data['district_nodal_name'],'district_nodal_id'=>$this->request->session()->read('Auth.User.id')]);
            if ($exists == true) {
				$this->NewDistrictNodalEntries->deleteAll(['district_code' => $data['district_code'] ,'district_nodal_name'=> $data['district_nodal_name'],'district_nodal_id'=>$this->request->session()->read('Auth.User.id')]);
            }
            
                        
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['state_code'] = $state_code;
            $data['district_nodal_id'] = $this->request->session()->read('Auth.User.id');

            
			//echo "<pre>" ; print_r( $data['primaryactivitity_id']) ; exit ;
                foreach($primary_activity_count_array as $key=>$value){
					 $nentry = $this->NewDistrictNodalEntries->newEntity();
					
					$data['primary_activity_count'] = $value ;
					$data['primaryactivitity_id'] = $primary_activity_array[$arraycount] ; 
					
					$nentry = $this->NewDistrictNodalEntries->patchEntity($nentry, $data); 
					$nentry->primaryactivitity_id = $primary_activity_array[$arraycount];
					
					if($this->NewDistrictNodalEntries->save($nentry)){
				    $save = 'ok' ;
					}else{
						$save = 'notok' ;
					}
					$arraycount++;
				}
				
				$exists1 = $this->DistrictCoFedUn->exists(['district_code' => $data['district_code'] ,'district_nodal_name'=> $data['district_nodal_name'],'district_nodal_id'=>$this->request->session()->read('Auth.User.id')]);
				if($data['dcfu'] == 'yes'){
            if ($exists1 == true) {
				$this->DistrictCoFedUn->deleteAll(['district_code' => $data['district_code'] ,'district_nodal_name'=> $data['district_nodal_name'] ,'district_nodal_id'=>$this->request->session()->read('Auth.User.id')]);
            }
			if($data['name_coop'][0]!= ''){
				//echo "<pre>" ; print_r($data['name_coop']) ; exit ;
			$data1['created'] = date("Y-m-d H:i:s");
            $data1['state_code'] = $state_code; 
			$data1['district_code'] = $data['district_code'];
            $data1['district_nodal_id'] = $this->request->session()->read('Auth.User.id');
			$data1['district_nodal_name'] =  $data['district_nodal_name'] ;
			foreach($data['primaryactivitityid'] as $key=>$value){
					 $nentry1 = $this->DistrictCoFedUn->newEntity();
					
					$data1['primaryactivity_id'] = $value ;
					$data1['name'] = $data['name_coop'][$arraycount1] ; 
					
					$nentry1 = $this->DistrictCoFedUn->patchEntity($nentry1, $data1); 
					
					
				    $this->DistrictCoFedUn->save($nentry1);
					$arraycount1++;
				}
		}
		}else{
            if ($exists1 == true) {
				$this->DistrictCoFedUn->deleteAll(['district_code' => $data['district_code'] ,'district_nodal_name'=> $data['district_nodal_name'] ,'district_nodal_id'=>$this->request->session()->read('Auth.User.id')]);
            }

        }
				//echo $arraycount ; exit ;
            if ($save == 'ok' ) {
                $this->Flash->success(__('The Nodal Entry has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Nodal Entry could not be saved. Please, try again.'));
        }

            // if($district_code!='')
            // {
            //     $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code,'district_code'=>$district_code]);
            // }else{
            //      $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code]);
            // }

        
            $primary_activities_dropdown = $this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->order(['orderseq'=>'ASC'])->toArray();
        $uname = $this->request->session()->read('Auth.User.name');
        $this->set(compact('nentry','districts','uname','district_code','DistrictCoFedUn','primary_activities_dropdown'));
    }
    public function editbk($id = null)
    {
        $this->loadModel('Districts');
        $this->loadModel('CooperativeRegistrations');

        $nentry = $this->DistrictNodalEntries->get($id);
        $state_code = $this->request->session()->read('Auth.User.state_code');
        //$district_code = $this->request->session()->read('Auth.User.district_code');
        
        $district_code = $nentry->district_code;

        // $arr_sign_districts = [];

        $arr_sign_districts = $this->CooperativeRegistrations->find('list',['keyField'=>'district_code','valueField'=>'operational_district_code'])->where(['status'=>1,'state_code'=>$this->request->session()->read('Auth.User.state_code'),'approved_by'=>$this->request->session()->read('Auth.User.id'),'district_code !='=>0])->group(['district_code'])->toArray();

        if(!empty($arr_sign_districts))
        {
            $arr_sign_districts = array_keys($arr_sign_districts);
        } else {
            $arr_sign_districts = [0];
        }


        if(!in_array($nentry->district_code,$arr_sign_districts) && $this->Auth->user('role_id') == 8)
        {
            $this->Flash->error(__('You are not allowed.'));
            return $this->redirect(['action' => 'index']);
        }

        $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$nentry->district_code])->order(['name'=>'ASC'])->toArray();
       
        
        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();

            $data['updated_by'] = $this->request->session()->read('Auth.User.id');

            $exists = $this->DistrictNodalEntries->exists(['district_code' => $data['district_code'],'id !='=>$id]);
            
            if ($exists == true) {
                $this->Flash->error(__('Nodal Entry Already exist.'));
                return $this->redirect($this->referer());
            }

            $nentry = $this->DistrictNodalEntries->patchEntity($nentry, $data);
            if ($this->DistrictNodalEntries->save($nentry)) {
                $this->Flash->success(__('The Nodal Entry has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Nodal Entry could not be saved. Please, try again.'));
        }
        
        $this->set(compact('nentry','districts','district_code'));
    }

    /**
     * Delete method
     *
     * @param string|null $id CooperativeSocietyTypes id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {   
	    $this->loadModel('NewDistrictNodalEntries');
		$this->loadModel('DistrictCoFedUn');
        $this->request->allowMethod(['post', 'delete','put']);
        //$nentry = $this->NewDistrictNodalEntries->find('all')->where(['district_code'=>$id]);
        if ($this->NewDistrictNodalEntries->deleteAll(['district_code' => $id ,'district_nodal_id'=>$this->request->session()->read('Auth.User.id')])) {
			$this->DistrictCoFedUn->deleteAll(['district_code' => $id ,'district_nodal_id'=>$this->request->session()->read('Auth.User.id')]) ;
            $this->Flash->success(__('The Nodal Entry of has been deleted.'));
        } else {
            $this->Flash->error(__('The Nodal Entry could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
