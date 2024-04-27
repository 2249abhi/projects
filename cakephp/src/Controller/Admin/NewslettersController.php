<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\I18n\Time;

class NewslettersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    public function index()
    {
        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        if (!empty($this->request->getQuery('email'))) {
            $email = trim($this->request->getQuery('email'));
            $this->set('email', $email);
            $search_condition[] = "Newsletters.email like '%" . $email . "%'";
        }
        if (!empty($this->request->getQuery('fromdate'))) {
            $this->set('fromdate', $this->request->getQuery('fromdate'));
            $date_from = Time::createFromFormat('d-m-Y',$this->request->getQuery('fromdate'));
            $date_from = date('Y-m-d', strtotime($date_from));
            $search_condition[] = "DATE(Newsletters.created) >= '" . $date_from . "'";
        }
        if (!empty($this->request->getQuery('todate'))) {
            $this->set('todate', $this->request->getQuery('todate'));
            $date_to = Time::createFromFormat('d-m-Y',$this->request->getQuery('todate'));
            $date_to = date('Y-m-d', strtotime($date_to));
            $search_condition[] = "DATE(Newsletters.created) <= '" . $date_to . "'";
        }
        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }
        if ($page_length != 'all' && is_numeric($page_length)) {
            $page_length = $page_length;
        }else if ($page_length == 'all') {
            $selectedLen = 'all';
            $page_length = $this->Newsletters->find('all')->enableHydration('false')->count();
        } else {
            $page_length = $this->Newsletters->find('all')->enableHydration('false')->count();
        }

        if(!empty($this->request->getQuery('export_excel'))) {
            $queryExport = $this->Newsletters->find('all',[
                'conditions'=>[$searchString],
                'order'=>['id'=>'DESC'],
                'limit' =>$page_length
            ])->enableHydration(false)->toArray();

            $fileName = "newsletters_".date("YmdHis").".xls";
            $headerRow = array("S.No", "Email Id", "Status", "Subscribe", "Category", "Subscription Date & Time");
            $data = array();
            $i=1;
            foreach($queryExport As $rows){
                $status='In-Active';
                if($rows['status']==1){
                    $status='Active';
                }
                $subscribe='Subscribed';
                if($rows['is_unsubscribe']==1){
                    $subscribe='Un-Subscribed';
                }
                $category='Crisidex';
                if($rows['category_id']==3){
                    $category='MSME Pulse';
                }
                $data[]=array($i,$rows['email'],$status,$subscribe,$category,date('Y-m-d H:i A',strtotime($rows['created'])));
                $i++;
            }
            $this->exportInExcel($fileName, $headerRow, $data);
        }

        $postQuery = $this->Newsletters->find('all', [
            'order' => ['Newsletters.id' => 'desc'],
            'conditions' => [$searchString]
        ]);
        $this->paginate = ['limit' => $page_length,'maxLimit' => $page_length];
        $newsletters = $this->paginate($postQuery);
        $this->set('selectedLen', $page_length);
        $this->set(compact('newsletters','selectedLen'));
    }

    public function view($id = null)
    {
        $newsletter = $this->Newsletters->get($id, [
            'contain' => []
        ]);
        $this->set('newsletter', $newsletter);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $career = $this->Careers->get($id);
        if ($this->Newsletters->delete($career)) {
            $this->Flash->success(__('The Newsletters has been deleted.'));
        } else {
            $this->Flash->error(__('The Newsletters could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

}