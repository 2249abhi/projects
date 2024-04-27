<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
//use Cake\Network\Exception\NotFoundException;
//use Cake\Utility\Hash;
//use Cake\View\Exception\MissingTemplateException;
use Cake\Core\Configure;
//use Cake\Filesystem\File;
use Cake\ORM\TableRegistry;
use Cake\View\Helper\HtmlHelper;  /* called by jsingh  for datatable buttons */
use Cake\View\Helper\FormHelper;  /* called by jsingh  for datatable buttons */

class GismapsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }      
    
    public function overallcount()
    {   
        $overallcount='';        
        $this->set(compact('overallcount'));
    }

    public function listingLatLon()
    {   
        //$connection = ConnectionManager::get('default');
        
        $this->loadModel('States');
       //$this->loadModel('Districts');

        $states_list =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']])->hydrate(false)->toArray();
        $this->set(compact('states_list'));
    }

//-----------------------------------------------------------------------

    public function viewListingLatLon(){

        foreach($_POST as $key=>$val){
            $req_value=$this->request->data($key);	
            if(!is_array($req_value))  $$key=trim($req_value);
            else  $$key=$req_value; 
            }        

            $hlp_html = new HtmlHelper(new \Cake\View\View());
            $hlp_form = new FormHelper(new \Cake\View\View());

            //$page = $start;
            //$offset = $length;

          $this->autoRender = false;
          $this->layout=false; 
          $connection = ConnectionManager::get('default');

          $search_by=$search['value'];

          $limit_part='';
          if($length != -1)
          {
                $limit_part.="  LIMIT $length offset $start";
          }

          $select_fields=" select NFM.id, ST.name as state_name, DT.name as district_name, BL.name as block_name, NFM.area_state_code as state_code, NFM.area_district_code as district_code, NFM.area_block_code as block_code, NFM.name_of_member,  NFM.latitude, NFM.longitude   ";

          $where_part='';
          if(strlen($search_by) > 2) $where_part.=" and (NFM.name_of_member like '%$search_by%' or NFM.id like '%$search_by%' )  "; 
          /* ST.name like '%$search_by%' or DT.name like '%$search_by%' or BL.name like '%$search_by%' or */
          if($state_code > 0) $where_part.=" and ST.state_code='$state_code' ";
          if($district_code > 0) $where_part.=" and DT.district_code='$district_code' ";
          if($block_code > 0) $where_part.=" and BL.block_code='$block_code' ";

          $sugar_mills_joins=" from national_federations_members NFM 
          right join states ST on NFM.area_state_code=ST.state_code
          left join districts DT on NFM.area_district_code=DT.district_code
          left join blocks BL on NFM.area_block_code=BL.block_code
          where NFM.national_federations_id=8  $where_part ";       
        
            $order_by =  "order by ST.name, DT.name, BL.name $limit_part ";  
                      
            $sugar_mills_text=$select_fields . $sugar_mills_joins . $order_by;       

           // pp($_POST);    
           // pp($sugar_mills_text,1);
            $sugar_mills = $connection->execute($sugar_mills_text)->fetchAll('assoc');  
           
            $count_qry_text=" select count(*) ".$sugar_mills_joins;
            $total_count_qry = $connection->execute($count_qry_text)->fetchAll('assoc'); 
            $total_count=$total_count_qry[0]['count(*)']; 

          $data = array();
          $i=1;

          foreach( $sugar_mills as $row ) {
                      $counting=($i+$start);            
                      $nestedData=array(); 

                      $member_name=preg_replace('/\s+/', ' ', $row['name_of_member']);

                      $map_link="http://maps.google.com/maps?q=".$member_name;
                      $map_link="<a href='$map_link' class='map_link' target='_blank'>(MAP)</a>";

                      $id=$row['id'];
                      
                      $nestedData[] = "$counting";
                      $nestedData[] = $id; 
                      $nestedData[] = firstUpper($row['state_name']). " (". $row['state_code'] .")"; 
                      $nestedData[] = ($row['district_name']!='')?firstUpper($row['district_name']). " (". $row['district_code'] .")":'--'; 
                      $nestedData[] = ($row['block_name']!='')?firstUpper($row['block_name']). " (". $row['block_code'] .")":'--'; 
                      $nestedData[] = '<span style="white-space:break-spaces;">'. $member_name ." ". $map_link.'</span>';
                      $nestedData[] = ($row['latitude']!='')?$row['latitude'].', '.$row['longitude']:'' ;                       
                      $nestedData[] = "<input type='text' maxlength='50' name='lat_lon[$id]'  value=''>"; 
                      //$nestedData[] ='';                
                      $data[] = $nestedData;
                      $i++;
                  } 

                  $json_data = array(
                    "draw"            => intval( $draw ),   
                    "recordsTotal"    => intval( $total_count ),  // total number of records
                    "recordsFiltered" => intval( $total_count ), // total number of records after searching, if there is no searching then totalFiltered = totalData						
                    "data"            => $data   // total data array
                    );
                    echo json_encode($json_data); exit;  // send data as json format          
    }



 public function  updateLatlon(){

    foreach($_POST as $key=>$val){
        $req_value=$this->request->data($key);	
        if(!is_array($req_value))  $$key=trim($req_value);
        else  $$key=$req_value; 
        }
    
      $this->autoRender = false;
      $this->layout=false; 
      $federation_table = TableRegistry::get($model_name);

foreach($lat_lon as $key=>$val){
    if(strlen($val) > 10 && substr_count($val, ',')==1 )
    {
            $vals=explode(',', $val);
            $federation_data = $federation_table->get($key);
            $federation_data->latitude = trim($vals[0]);
            $federation_data->longitude = trim($vals[1]);
            $federation_table->save($federation_data);            
            //pp($federation_data,1);
}
        }
$this->Flash->success(__('Lat Lon updated successfully.'));
$this->redirect($_SERVER['HTTP_REFERER']);

    }

    //-----------------------------------------

    public function getDistricts(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $state_code=$this->request->query('state_code');    
            $this->loadMOdel('Districts');
            $Districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']);
            $option_html='<option value="">--Any--</option>';
            if($Districts->count()>0){
                foreach($Districts as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.firstUpper($value).'</option>';
                }
            }
            echo $option_html;
        }
        exit;
    }


    //-----------------------------

    public function dashboard(){

        
    }

    //-----------------------------------------
    
    public function getBlocks(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
            $district_code=$this->request->query('district_code');    
            $this->loadMOdel('Blocks');
            $Blocks=$this->Blocks->find('list',['keyField'=>'block_code','valueField'=>'name'])->where(['flag'=>1,'district_code'=>$district_code])->order(['name'=>'ASC']);
            $option_html='<option value="">--Any--</option>';
            if($Blocks->count()>0){
                foreach($Blocks as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.firstUpper($value).'</option>';
                }
            }
            echo $option_html;
        }
        exit;
    }
}
