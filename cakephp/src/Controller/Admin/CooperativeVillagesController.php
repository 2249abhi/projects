<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * CooperativeRegistrations Controller
 *
 * @property \App\Model\Table\CooperativeRegistrationsTable $CooperativeRegistrations
 *
 * @method \App\Model\Entity\State[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CooperativeVillagesController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
        $user_all=$this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->where(['status'=>1])->toArray();
        $this->set('user_all', $user_all);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','getdccb','getfederationlevel','approval','bulkapproval','getUrbanRural']);
    }

    public function addVillage()
    {
       
       //$DistrictsBlocksGpVillage = $this->DistrictsBlocksGpVillages->newEntity();
        if($this->request->is('post')){
          //$data=$this->request->getData();
          $state_code = $this->request->data('state_code');
          $district_code = $this->request->data('district_code');
          $block_code = $this->request->data('block_code');
          $gram_panchayat_code = $this->request->data('gram_panchayat_code');
          $village_name = $this->request->data('village_name');
          $status = $this->request->data('status');
          $this->loadModel('DistrictsBlocksGpVillages');
          $fetch_data = $this->DistrictsBlocksGpVillages->find('all')->where(['district_code' =>  $district_code, 'state_code' => $state_code, 'block_code' =>  $block_code, 'gram_panchayat_code' => $gram_panchayat_code])->first();
          $district_name = $fetch_data['district_name'];
          $state_name = $fetch_data['state_name'];
          $block_name = $fetch_data['block_name'];
          $gram_panchayat_name = $fetch_data['gram_panchayat_name'];

          $v_code =$this->DistrictsBlocksGpVillages->find('all')->select('village_code')->hydrate(false)->max('village_code'); 
          $village_code = $v_code['village_code']+1;
         $fetch_data = $this->DistrictsBlocksGpVillages->find()->where(['village_name' => $village_name,'gram_panchayat_code'=>$gram_panchayat_code])->count();
        if($fetch_data > 0){
            $this->Flash->error(__('This Village name is Already available'), ['key' => 'error']);
         }
        //  if( $village_name == $fetch_data['village_name']){
        //     $this->Flash->error(__('This Village name is Already available'), ['key' => 'error']);
        // }
        else{
         // $users_table = TableRegistry::get('DistrictsBlocksGpVillages');
         $users =  $this->DistrictsBlocksGpVillages->newEntity();
          //$users = $users_table->newEntity();
            $users->state_code = $state_code;
            $users->state_name = $state_name;
            $users->district_code = $district_code;
            $users->district_name = $district_name;
            $users->block_code = $block_code;
            $users->block_name = $block_name;
            $users->gram_panchayat_code  = $gram_panchayat_code;
            $users->gram_panchayat_name = $gram_panchayat_name;
            $users->village_name  = $village_name;
            $users->village_code = $village_code;
            $users->status = $status;

            // $exists = $user_table->exists(['village_name' => $village_name]);
            
            if($users_table->save($users)) {
                $this->Flash->success(__('The data has been saved.'));
                return $this->redirect(['action' => 'addVillage']);
             }
        }
             }

        // Start States for dropdown
            $state_code= $this->request->session()->read('Auth.User.state_code');
            $this->loadModel('States');
            $this->loadMOdel('DistrictsBlocksGpVillages');
            $states = $this->DistrictsBlocksGpVillages->find('list',['keyField'=>'state_code','valueField'=>'state_name'])->where(['status'=>1])->group(['state_code'])->order(['state_name'=>'ASC']);
            // if(in_array($this->request->session()->read('Auth.User.role_id'),[1,2]))
            // {
            //     $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
            // } else {
            //     $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code]);
            // }
            
        // End States for dropdown
        $this->set('states',$states);
      // Start States for dropdown
            $this->loadModel('Districts');
             $districts=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'district_code','valueField'=>'district_name'])->where(['status'=>1,'state_code'=>$states->state_code])->order(['district_name'=>'ASC']); 
            //$districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']);
            $this->set('districts',$districts);
        // Start Blocks for dropdown
        $this->loadModel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$CooperativeRegistration->district_code])->order(['name'=>'ASC']);
            $this->set('blocks',$blocks);
      // Start Gram Panchayats for dropdown
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $gps=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$CooperativeRegistration->block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);  
        $this->set('gps',$gps);  
       // Start villages for dropdown
            
        $this->loadMOdel('DistrictsBlocksGpVillages');
        $villages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$CooperativeRegistration->gram_panchayat_code])->order(['village_name'=>'ASC']); 
        $this->set('villages',$villages);
        $this->set(compact('CooperativeRegistration','states','state_code'));
    }

    public function getDistricts(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $state_code=$this->request->data('state_code');    
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
    public function getGp(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
             $block_code=$this->request->data('block_code');    
            $this->loadMOdel('DistrictsBlocksGpVillages');
            $DistrictsBlocksGpVillages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'gram_panchayat_code','valueField'=>'gram_panchayat_name'])->where(['status'=>1,'block_code'=>$block_code])->group(['gram_panchayat_code'])->order(['gram_panchayat_name'=>'ASC']);
            $option_html='<option value="">Select</option>';
            if($DistrictsBlocksGpVillages->count()==0){
                $option_html.='<option value="0">Gram Panchayat</option>';
            }
            if($DistrictsBlocksGpVillages->count()>0){
                foreach($DistrictsBlocksGpVillages as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.$value.'</option>';
                }
            }
            echo $option_html;
        }
        exit;
    }
    public function getVillages(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $gp_code=$this->request->data('gp_code');    
            $this->loadMOdel('DistrictsBlocksGpVillages');
            $DistrictsBlocksGpVillages=$this->DistrictsBlocksGpVillages->find('list',['keyField'=>'village_code','valueField'=>'village_name'])->where(['status'=>1,'gram_panchayat_code'=>$gp_code])->order(['village_name'=>'ASC']);
            //$option_html='';
            $option_html='<option value="">Select</option>';
            if($DistrictsBlocksGpVillages->count()==0){
                $option_html.='<option value="0">Village</option>';
               //$option_html='';
            }
            if($DistrictsBlocksGpVillages->count()>0){
                //$rowNo = 1;
              
                foreach($DistrictsBlocksGpVillages as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.$value.'</option>';
                    // $option_html.= "<b>" . $rowNo . "</b>" . " " . $value ."<br>";
                    // $rowNo++;
                }
            }
            echo $option_html;
        }
        exit;
    }   
}