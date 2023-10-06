<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Utility\Text;
use Cake\Mailer\Email;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Chronos\Chronos;

/**
 * Home Controller
 *
 */

class GisController extends AppController
{
	public function initialize()
    {
        parent::initialize();

        //$this->Auth->allow(['index']);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
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
       // $this->loadMOdel('CooperativeRegistrations');
       // $this->loadMOdel('DistrictsBlocksGpVillages');
    
$states=$this->States->find('list', ['keyField' => 'state_code', 'valueField' => 'name'])->where(["flag"=>1])->order(['States.name'=>'ASC'])->toArray();
$this->set(compact('states'));   	
    }
//-------------------------------------------------------------------------
   public function getDistricts(){

    $this->loadModel('Districts');
    $data = $this->request->getData();

    foreach($data as $key=>$val){        
        if(!is_array($val))  $val=trim($val);       
       $$key=$val;
        }  

    $districts=$this->Districts->find('list', ['keyField' => 'district_code', 'valueField' => 'name'])->where(["flag"=>1, 'state_code'=>$state_code])->order(['Districts.name'=>'ASC']);
    $districts_val='<option value="select">Select District</option>';
    //pp($districts,1);
    foreach($districts as $key=>$val){
       $val= firstUpper($val);
        $districts_val.="<option value='$key'>$val</option>".'<br>';
    }
    echo $districts_val;
    exit;
    }
    //----------------------------------------------
    public function getBlocks(){

        $this->loadModel('Blocks');
        $data = $this->request->getData();
    
        foreach($data as $key=>$val){        
            if(!is_array($val))  $val=trim($val);       
           $$key=$val;
            }  
    
        $blocks=$this->Blocks->find('list', ['keyField' => 'block_code', 'valueField' => 'name'])->where(["flag"=>1, 'district_code'=>$district_code])->order(['Blocks.name'=>'ASC']);
        $blocks_val='<option value="select">Select Block</option>';
        //pp($blocks,1);
        foreach($blocks as $key=>$val){
           $val= firstUpper($val);
           $blocks_val.="<option value='$key'>$val</option>".'<br>';
        }
        echo $blocks_val;
        exit;
        }
    
}
