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
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 *
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AnimalHusbandriesController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['index','edit','view']);
    }

    public function index(){

        $this->loadMOdel('AnimalHusbandries');
        $this->loadMOdel('States');
        $this->loadMOdel('Districts');
        $this->loadMOdel('Blocks');
        // $this->loadMOdel('VillagesSubDistricts');

        $search_condition = [];
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
        
        // $animal_husbandries = $this->paginate($this->AnimalHusbandries->find('all')->limit(20));
    
        $states = $this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->where(['flag'=>1])->order(['name'=>'ASC'])->toArray();
       
        

        $search_condition = [];
        if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
            $state = trim($this->request->query['state']);
            $this->set('state', $state);
            $search_condition[] = "state_code = '" . $state . "'";

            $districts = $this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1])->where(['state_code'=>$this->request->query['state']])->order(['name'=>'ASC'])->toArray();
        }

        if (isset($this->request->query['district']) && $this->request->query['district'] !='') {
            $district = trim($this->request->query['district']);
            $this->set('district', $district);
            $search_condition[] = "district_code = '" . $district . "'";

            $blocks = $this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1])->where(['district_code'=>$this->request->query['district']])->order(['name'=>'ASC'])->toArray();
        }

        if (isset($this->request->query['block']) && $this->request->query['block'] !='') {
            $block = trim($this->request->query['block']);
            $this->set('block', $block);
            $search_condition[] = "block_code = '" . $block . "'";
        }

        

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        }
         else {
            $searchString = '';
        }
    
        $query = $this->AnimalHusbandries->find('all'
            // 'order' => ['created' => 'DESC'],
           
        )->select(['state_name','state_code','district_name','district_code','block_name','block_code','village_name','village_code','under_one_year_cattle','one_to_two_and_half_years','	in_milk_cattle','dry_cattle','not_calved_once_cattle','others_female_cattle','upto_one_and_half_years_cattle','used_for_breeding_only_cattle','agriculture_and_breeding_cattle','used_for_agriculture_only_cattle','bulluck_cart_farm_operation_cattle','others_male_cattle','total_cattle'])->where([$searchString]);
        $this->paginate = ['limit' => 20];
        $animal_husbandries = $this->paginate($query);

    

       
       $this->set(compact('animal_husbandries','page', 'states','searchString','districts','blocks'));

    }

    


    public function getDistricts(){
        // $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $state_code = $this->request->getQuery('stateIndex');
             
            
            $this->loadMOdel('Districts');
            $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC'])->toArray();
            
            echo json_encode($districts); die;
           
        }
        exit;
    }


    public function getBlocks(){
        // $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $district_code = $this->request->getQuery('districtIndex');
             
            
            $this->loadMOdel('Blocks');
            $blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$district_code])->order(['name'=>'ASC'])->toArray();
            
            echo json_encode($blocks); die;
           
        }
        exit;
    }



    // public function delete($id = null)
    // {
    //     echo $id;
    //     die;die;

    //     $this->request->allowMethod(['post', 'delete']);
    //     $animal_husbandry = $this->AnimalHusbandries->get($id);

    //     if ($this->AnimalHusbandries->delete($animal_husbandry)) {
    //         $this->Flash->success(__('The component has been deleted.'));
    //     } else {
    //         $this->Flash->error(__('The component could not be deleted. Please, try again.'));
    //     }

    //     return $this->redirect(['action' => 'index']);
    // }


    public function edit($id = null)
    {   
        
        $animal_husbandry = $this->AnimalHusbandries->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $form_data = $this->request->getData();
            
            $total_cattle = $form_data['under_one_year_cattle'] + $form_data['one_to_two_and_half_years'] + $form_data['in_milk_cattle'] + $form_data['dry_cattle'] + 
            $form_data['not_calved_once_cattle'] + $form_data['others_female_cattle'] + $form_data['upto_one_and_half_years_cattle'] + $form_data['used_for_breeding_only_cattle'] +
            $form_data['agriculture_and_breeding_cattle'] + $form_data['used_for_agriculture_only_cattle'] + $form_data['bulluck_cart_farm_operation_cattle'] + $form_data['others_male_cattle'];

            // $form_data['total_cattle'] = $total_cattle;

            $animal_husbandry->under_one_year_cattle = $form_data['under_one_year_cattle'];
            $animal_husbandry->one_to_two_and_half_years = $form_data['one_to_two_and_half_years'];
            $animal_husbandry->in_milk_cattle = $form_data['in_milk_cattle'];
            $animal_husbandry->dry_cattle = $form_data['dry_cattle'];

            $animal_husbandry->not_calved_once_cattle = $form_data['not_calved_once_cattle'];
            $animal_husbandry->others_female_cattle = $form_data['others_female_cattle'];
            $animal_husbandry->upto_one_and_half_years_cattle = $form_data['upto_one_and_half_years_cattle'];
            $animal_husbandry->used_for_breeding_only_cattle =$form_data['used_for_breeding_only_cattle'] ;

            $animal_husbandry->agriculture_and_breeding_cattle = $form_data['agriculture_and_breeding_cattle'];
            $animal_husbandry->used_for_agriculture_only_cattle = $form_data['used_for_agriculture_only_cattle'] ;
            $animal_husbandry->bulluck_cart_farm_operation_cattle = $form_data['bulluck_cart_farm_operation_cattle'];
            $animal_husbandry->others_male_cattle = $form_data['others_male_cattle'];

            $animal_husbandry->total_cattle =  $total_cattle;

            // $animal_husbandry = $this->AnimalHusbandries->patchEntity($animal_husbandry, $form_data);
           
            if ($this->AnimalHusbandries->save($animal_husbandry)) {
                $this->Flash->success(__('The component has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The component could not be saved. Please, try again.'));
        }
        $this->set(compact('animal_husbandry','id'));
    }


    public function view($id = null)
    {   
        
        $animal_husbandry = $this->AnimalHusbandries->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('animal_husbandry','id'));
    }
    
}