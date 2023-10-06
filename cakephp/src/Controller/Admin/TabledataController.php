<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
class TabledataController extends AppController
{
    public function initialize()
    {
     parent::initialize();
     
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);       
    }

    public function index()
    {      
		$connection = ConnectionManager::get('default');
        $results = $connection->execute("SELECT DATABASE() as current_db ")->fetchAll('assoc');      
        $current_db=$results[0]['current_db'];
        //-------------------------------------------
        $databases_list = $connection->execute(" SHOW DATABASES WHERE `Database` NOT IN ('mysql', 'performance_schema', 'sys','information_schema') ")->fetchAll('assoc'); 
        //------------------------------------------
        $tables_in_db = $connection->execute(" SELECT table_name, table_type FROM information_schema.tables WHERE table_schema='$current_db' ORDER BY table_name ")->fetchAll('assoc');            
       // pp($current_db);
       // pp($databases_list);
       // pp($tables_in_db,1);
        $this->set(compact('current_db','databases_list','tables_in_db')); 
    }
//-----------------------------------------
    public function getTableFields(){
        foreach($_POST as $key=>$val){
            $req_value=$this->request->data($key);	
            if(!is_array($req_value))  $$key=trim($req_value);
            else  $$key=$req_value; 
            }        
          $this->autoRender = false;
          $this->layout=false; 

          $numeric_datatypes=array('BIT','TINYINT','BOOL','BOOLEAN','SMALLINT','MEDIUMINT','INT','INTEGER','BIGINT','FLOAT','DOUBLE','DECIMAL','DEC');

          $connection = ConnectionManager::get('default');
   
   $fields="<span class='tab_col'><input type='radio' value='' name='select_type' id='select_field' class=''> <label for='select_field'> Field</label></span><span class='tab_col'><input type='radio' value='*' checked name='select_type' id='select_star' class=''> <label for='select_star'> Select *</label></span><span class='tab_col'><input type='radio' value='count(*)' name='select_type' id='count_star' class=''> <label for='count_star'> count (*)</label></span><hr style='padding:1px; margin:1px; clear:both;'>";
   $conditions_arr=array('=','>','>=','<','<=','!=','like','is null='); /* ,'is not null=' */ 
   $not_fields=array('json','bytea');
   //------------------------------
   
   $fields_text=" SELECT column_name as column_name, data_type as data_type FROM  information_schema.columns WHERE  TABLE_SCHEMA = '$database_name' and  table_name = '$table_name' group by column_name  order by  column_name asc ";
 
   $fields_text_res = $connection->execute($fields_text)->fetchAll('assoc');   
   
   $fields_text_res_cond=$fields_text_res;
 
   $first=1;
   foreach($fields_text_res as $res){      
       
     $f_name =  $res['column_name'];     
     
      $fields.="
<span class='tab_col'><input type='checkbox' $checked value='$f_name' name='$f_name' id='$f_name' class='chk_fields'> <label for='$f_name'> $f_name </label></span><br/>";
     
      $first++;
   }
   //---------------  now condition fields is ascending order  ------------
     
  @array_multisort(array_map('strlen', $fields_text_res_cond), $fields_text_res_cond);
   
   foreach($fields_text_res_cond as $res){
    $f_name =  $res['column_name'];   
      
      $f_type= $res['data_type'];  

      $temp_col_datatype=$f_type;      

     $f_type= "<span class='tooltiptext'>$f_type</span>";

              $select_box="<select name='cond_$f_name' id='cond_$f_name' style='width:40px; border:1px solid #ccc;'>";
                  foreach($conditions_arr as $arr){
                  $select_box.="<option value='$arr'>$arr</option>";                    
                  }
              $select_box.="</select>";  
              
              //-----------
              $orderby_box="<select name='orderby_$f_name' id='orderby_$f_name' style='border:1px solid #ccc; width:40px;'>";                    
              $orderby_box.="<option value=''></option><option value='asc'>asc</option><option value='desc'>desc</option></select>&nbsp;";    
              //------------------------------------------------
              
    if(!in_array($temp_col_datatype, $not_fields))
      {             
       $textbox_class='';   
      // if(in_array(strtoupper($temp_col_datatype),$numeric_datatypes)) $textbox_class='numeric_val';
    
  $conditions_fields.="<div style='clear:both;'><div class='col-md-5 tooltipp'> $f_name $f_type</div> <div class='col-md-2'> $select_box </div>  <div class='col-md-5'><input maxlength=100 style='border:1px solid #ccc;' type='text' name='txt_$f_name' class='$textbox_class' id='txt_$f_name'></div></div>";
  $orderby_fields.="<div style='clear:both;'><div class='col-md-8'>$f_name</div><div class='col-md-4'> $orderby_box </div></div>"; 
  
      }
   }
  $json_data = array(			
                      "cols_fields"  => $fields ,
                      "conditions_fields"  => $conditions_fields,
                      "orderby_fields"  => $orderby_fields  
          );
echo json_encode($json_data); 
    } 

    //-----------------------------
    
    public function viewIndex()
    { 

        foreach($_POST as $key=>$val){
            $req_value=$this->request->data($key);	
            if(!is_array($req_value))  $$key=trim($req_value);
            else  $$key=$req_value; 
            }        
          $this->autoRender = false;
          $this->layout=false; 
          $connection = ConnectionManager::get('default');



$string_datatypes=array('CHAR','VARCHAR','TINYTEXT','TEXT','MEDIUMTEXT','LONGTEXT','ENUM','SET','char','varchar','tinytext','text','mediumtext','longtext','enum','set');  // lower and uppercase both

$varb_datatypes=array('BINARY','VARBINARY','binary','varbinary'); // lower and uppercase both

$blob_datatypes=array('TINYBLOB','BLOB','MEDIUMBLOB','LONGBLOB','tinyblob','blob','mediumblob','longblob');  // lower and uppercase both

$date_datatypes=array('DATE','DATETIME','TIMESTAMP','TIME','YEAR','date','datetime','timestamp','time','year');  // lower and uppercase both
          
$table_id=$table_selected; /* add table value to show as sent db_table_view */
     
$fields_text=" SELECT column_name as column_name, data_type as data_type FROM  information_schema.columns WHERE  TABLE_SCHEMA = '$database_name' and table_name = '$table_id'  group by column_name  ";  

$fields_text_res = $connection->execute($fields_text)->fetchAll('assoc'); 

//pp($fields_text_res,1);

$all_columns=array();
$asked_columns=array();
$orderby_columns=array();

$cond_inc=0;
foreach($fields_text_res as $val){
$post_variable=$val['column_name'];
$select_column=$val['column_name'];

//if($val['data_type']=='bytea' || $val['data_type']=='BYTEA') $select_column="length($select_column)";
if (in_array($val['data_type'], $varb_datatypes))  $select_column="INET6_NTOA($select_column)"; // mind the space in "

$all_columns[]=$select_column;     

if($_POST[$val['column_name']]!='')
{
 $asked_columns[]=$select_column;
}
// pr($select_column,1);

$cond_variable=$_POST['cond_'.$post_variable];
$where_variable=strtolower($_POST['txt_'.$post_variable]);

if($cond_variable=='like' ){        
    $where_variable= "%$where_variable%";
}
 
if($where_variable!=''){
    if($cond_inc==0) $var='where'; else $var='and'; 

    if (in_array($val['data_type'], $string_datatypes))   $post_variable=" LOWER($post_variable) ";
    if (in_array($val['data_type'], $varb_datatypes))  $post_variable="INET6_NTOA($post_variable)"; 
    if (in_array($val['data_type'], $blob_datatypes))  $post_variable=" LOWER(cast($post_variable as char)) ";
    if (in_array($val['data_type'], $date_datatypes))  $post_variable=" LOWER(cast($post_variable as char)) ";

$where_fields.=" $var $post_variable $cond_variable '$where_variable' ";
$cond_inc++;
}    

//-------------------  now order by field if asked -----------------------
$orderby_variable=$_POST['orderby_'.$post_variable];
if($orderby_variable!=''){
$orderby_columns[]="$post_variable $orderby_variable";
}

}
//----------------------------------------------
$all_columns_imp= implode(', ', $all_columns);  
$asked_columns_imp= implode(', ', $asked_columns);  
if($select_type == '*'){  $get_fields=$all_columns_imp; $post_data_arr= $all_columns; }
elseif($select_type == 'count(*)'){ $get_fields='count(*)'; $post_data_arr= array(); }
else{  $get_fields= $asked_columns_imp;  $post_data_arr= $asked_columns;  }


$orderby_fields=' ';
if(count($orderby_columns)>0){
$orderby_fields=" order by ".implode(', ',$orderby_columns);
}

$query_text="select $get_fields from $table_id $where_fields ";

 //  pp($query_text.$orderby_fields,1); 

$json_data = array(			
            "post_data"  =>$post_data_arr ,/* (count($arr)>0)?$arr:$all_fields */
            "table_id"  => $table_id ,
            "query_text"  => $query_text,
            "order_by"  => $orderby_fields,
);
echo json_encode($json_data); 

    }
//--------------------
public function executeQuery(){
                     
    foreach($_POST as $key=>$val){
        $req_value=$this->request->data($key);	
        if(!is_array($req_value))  $$key=trim($req_value);
        else  $$key=$req_value; 
        }        
      $this->autoRender = false;
      $this->layout=false;     
//pp($_POST,1);
$this->set(compact('query_text','post_data','table_id','order_by','doc_orientation'));     
$this->render('execute_query');

}

//-------------------------------------

public function viewExecuteQuery(){
          
    foreach($_POST as $key=>$val){
        $req_value=$this->request->data($key);	
        if(!is_array($req_value))  $$key=trim($req_value);
        else  $$key=$req_value; 
        }        
      $this->autoRender = false;
      $this->layout=false;   
// pp($_POST,1);

$cols_data_array = explode(',', $cols_data);

$limit_part='';
if($length != -1)
{
$limit_part.="  LIMIT $length offset $start";
}

//--------------------------------------------------------
$data = array();
$i=1;
$get_type=  substr_count($query_text, 'count(*)');

//pr($query_text,1);
$connection = ConnectionManager::get('default');
if($get_type==1)
{
$count_qry_text= " $query_text ";
$total_count_qry = $connection->execute($count_qry_text)->fetchAll('assoc');        
$total_count=$total_count_qry[0]['count(*)'];            
          $nestedData=array();                 
          $nestedData[] = "1";
          $nestedData[] = $total_count;                
        //  $nestedData[] ='';                
          $data[] = $nestedData; 
          $total_count=1;
//$main_qry_text= $count_qry_text;
}
else{       
$part=  explode(' from ', $query_text); 
$count_qry_text=" select count(*) from ".$part[1];
$total_count_qry = $connection->execute($count_qry_text)->fetchAll('assoc'); 
$total_count=$total_count_qry[0]['count(*)']; 
//pr($total_count,1);

$main_qry_text=" $query_text $order_by $limit_part ";
// pr('volvo');
// pr($main_qry_text,1);
$main_qry= $connection->execute($main_qry_text)->fetchAll('assoc');

//pp($main_qry,1);
foreach( $main_qry as $row ) {
$counting=($i+$start);
  //$row=$row[0];                
           
          $nestedData=array(); 
          
          $nestedData[] = "$counting";
          foreach($cols_data_array as $arr){
              
             // if (in_array($arr, $bytea_arr)){ $nestedData[] = 'large blob object';}
             // else 
              
              $text=htmlspecialchars($row[$arr], ENT_QUOTES);
              if(strlen($text)>55) $text ="<div style='overflow: scroll;  scrollbar-width: none; max-height:150px;'>$text</div>";
                  $nestedData[] = $text;               
          }          
        //  $nestedData[] ='';                
          $data[] = $nestedData;
          $i++;
      }    
}

//pr($count_qry_text);
//pr($main_qry_text,1);



/*
try {
//$var_msg = "This is an exception example";
$total_count_qry=$this->$model->query($count_qry_text);
throw new Exception($var_msg);
}
catch (Exception $e) {
echo "Message: " . $e->getMessage();
echo "";
echo "getCode(): " . $e->getCode();
echo "";
echo "__toString(): " . $e->__toString();
} 

if($this->$model->query($count_qry_text)){  pr(1,1);}
else {
pr(2,1);
}

*/

$json_data = array(
"draw"            => intval( $draw ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
"recordsTotal"    => intval( $total_count ),  // total number of records
"recordsFiltered" => intval( $total_count ), // total number of records after searching, if there is no searching then totalFiltered = totalData						
"data"            => $data   // total data array
      );
echo json_encode($json_data); exit;  // send data as json format 
    }


}
