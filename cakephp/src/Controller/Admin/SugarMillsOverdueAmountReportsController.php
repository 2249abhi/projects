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
class SugarMillsOverdueAmountReportsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['getDistricts','getBlocks','getGp','getVillages','getUrbanLocalBodies','getUrbanLocalBody','getLocalityWard','getPrimaryActivity','testing','index']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->loadModel('States');
        $this->loadMOdel('FederationSugarMills');


            $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
            $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

            if (isset($this->request->query['state']) && $this->request->query['state'] !='') {
                $s_state = trim($this->request->query['state']);

                $condtion_state_search['state_code']= $s_state;

               
                $this->set('s_state', $s_state);
            }

            if ($page_length != 'all' && is_numeric($page_length)) {
                $this->paginate = [
                    'limit' => $page_length,
                ];
            }
 
        $condtion_state['operational_status']='Running';
        

            $state_codes= $this->States->find('all')->where(['flag'=>1,$condtion_state_search])->order(['name'=>'ASC'])->select(['state_code','name'])->group(['state_code']);

            $sugar_mills = $this->FederationSugarMills->find('all',['conditions' => [$condtion_state],'keyField'=>'state_code','valueField'=>'count']);
        
            $sugar_mills_datas = $sugar_mills->select(['state_code','count' => $sugar_mills->func()->count('id'),'total_overdue'=>'SUM(overdues_loan)','total_outstanding_working_capital_loan'=>'SUM(outstanding_working_capital_loan)','total_outstanding_term_loan'=>'SUM(outstanding_term_loan)','operational_status'])->where([$condtion_state])->order(['id'=>'ASC'])->group(['state_code'])->toArray();


            $count_array=array();
            
            foreach($sugar_mills_datas as $key=>$value)

            {

                $count_running_sugar_mill[$value['state_code']]=$value['count'];
                $running_overdue_loan[$value['state_code']]=$value['total_overdue'];
                $running_outstanding_working_capital_loan[$value['state_code']]=$value['total_outstanding_working_capital_loan'];
                $running_outstanding_term_loan[$value['state_code']]=$value['total_outstanding_term_loan'];
               
            }

            $condtion_stateA['operational_status']='Closed';

            $sugar_millsA = $this->FederationSugarMills->find('all',['conditions' => [$condtion_state_search,$condtion_stateA],'keyField'=>'state_code','valueField'=>'count']);
        
            $sugar_mills_datasA = $sugar_millsA->select(['state_code','count' => $sugar_millsA->func()->count('id'),'total_overdue'=>'SUM(overdues_loan)','total_outstanding_working_capital_loan'=>'SUM(outstanding_working_capital_loan)','total_outstanding_term_loan'=>'SUM(outstanding_term_loan)','operational_status'])->where([$condtion_stateA])->order(['id'=>'ASC'])->group(['state_code'])->toArray();


            $count_arrayA=array();
            foreach($sugar_mills_datasA as $key=>$value)

            {

                $count_close_sugar_mill[$value['state_code']]=$value['count'];
                $close_overdue_loan[$value['state_code']]=$value['total_overdue'];
                $close_outstanding_working_capital_loan[$value['state_code']]=$value['total_outstanding_working_capital_loan'];
                $close_outstanding_term_loan[$value['state_code']]=$value['total_outstanding_term_loan'];

            }


             $query =$this->States->find('list',['keyField'=>'state_code','valueField'=>'name','conditions'=>['flag'=>1],'order' => ['name' => 'ASC']]);
            $query->hydrate(false);
            $stateOption = $query->toArray();
            $this->set('sOption',$stateOption);

            //$this->paginate = ['limit' => 36];
           //$state_codes = $this->paginate($state_codes);

            if(!empty($this->request->query['export_excel']))
                { 
        
                $i=1;
                $fileName = "SugarMillsOverduesReport".date("d-m-y:h:s").".xls";
                $data = array(); 
            
                //$ExportResultData= $this->FederationSugarMills->find('all')->where([$condtion_state_search])->order(['id'=>'ASC'])->toarray(); 

            
             $headerRow = array("S.No", "State Name",'No of Running Mills','Running Mills Overdues Loan','No of Closed Mills','Closed Mills Overdues Loan','Total No of Sugar Mills', 'Total Overdues Loan', 'Total Outstanding');


                foreach($sugar_mills_datasA As $rows)
                {        


                //$data[] = [$i, $stateOption[$rows['state_code']]?? 0, $rows['operational_status'], $rows['overdues_loan'], $rows['outstanding_working_capital_loan'],$rows['outstanding_term_loan'],($rows['outstanding_working_capital_loan']+$rows['outstanding_term_loan'])]; 

                $data[] = [$i, $stateOption[$rows['state_code']]?? 0, $count_running_sugar_mill[$rows['state_code']] ?? 0, $running_overdue_loan[$rows['state_code']] ?? 0, $count_close_sugar_mill[$rows['state_code']] ?? 0, $close_overdue_loan[$rows['state_code']] ?? 0, ($count_running_sugar_mill[$rows['state_code']] + $count_close_sugar_mill[$rows['state_code']]) ?? 0, ($running_overdue_loan[$rows['state_code']] + $close_overdue_loan[$rows['state_code']]) ?? 0, ($running_outstanding_working_capital_loan[$rows['state_code']] + $running_outstanding_term_loan[$rows['state_code']] + $close_outstanding_working_capital_loan[$rows['state_code']] + $close_outstanding_term_loan[$rows['state_code']]) ?? 0];


                $i++;
                }

                $this->exportInExcelNew($fileName, $headerRow, $data);                        
        
                }


            
        $this->set(compact('sugar_mills_datas','state_codes','all_data_sugar_mills','count_running_sugar_mill','running_overdue_loan','count_close_sugar_mill','close_overdue_loan','running_outstanding_working_capital_loan','running_outstanding_term_loan','close_outstanding_working_capital_loan','close_outstanding_term_loan','sugar_mills_datasA'));
        
    }

 
}
