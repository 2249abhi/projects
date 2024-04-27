<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * Banners Controller
 *
 * @property \App\Model\Table\BannersTable $Banners
 *
 * @method \App\Model\Entity\Banner[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SpeechAndConferencesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('SpeechAndConferences');
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
        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
        $data = $this->request->getQuery();
        $data = $this->Sanitize->clean($data);
        $data['event_title'] = $this->Sanitize->stripAll(isset($data['event_title'])?$data['event_title']:'');

        if (!empty($data['event_title'])) {
            $event_title = trim($data['event_title']);
            $this->set('event_title', $event_title);
            $search_condition[] = "SpeechAndConferences.title like '%" . $event_title . "%'";
        }
        if (isset($data['status']) && $data['status'] !='') {
            $status = trim($data['status']);
            $this->set('status', $status); 
            $search_condition[] = "SpeechAndConferences.status = '" . $status . "'";
        }

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }

        if($this->request->is('get')){
            if(!empty($this->request->query['export_excel'])) {
                $queryExport = $this->SpeechAndConferences->find('all',[
                    'conditions'=>[$searchString],
                    'limit' => $page_length,
                    'page'=> $page,
                    'order' => ['SpeechAndConferences.id' => 'asc'],
                ]);
                $queryExport->hydrate(false);
                $ExportResultData = $queryExport->toArray();
                $fileName = "SpeechAndConferences-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No", " Event Title "," Event order "," Event Date ", 'Status');
                $data = array();
                $i=1;
                $stat = ['0'=>'Inactive', 'Active'];
                
                foreach($ExportResultData As $rows){
                    $data[]=array($i, $rows['event_title'],$rows['event_order'],date("d M Y",strtotime($rows['event_date'])), $stat[$rows['status']]);
                    $i++;
                }
                $this->exportInExcel($fileName, $headerRow, $data);
            }
        }

        $rolesQuery = $this->SpeechAndConferences->find('all', [
            'order' => ['SpeechAndConferences.id' => 'asc'],
            'conditions' => [$searchString]
        ]);
        $this->paginate = ['limit' => 10];
        $document_types = $this->paginate($rolesQuery);
        $this->set('selectedLen', $page_length);
        $this->set(compact('document_types','pagCatList'));
    }

    /**
     * View method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $doc_type = $this->Events->get($id, [
            
        ]);

        $this->set('doc_type', $doc_type);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $doc_type = $this->SpeechAndConferences->newEntity();
        if ($this->request->is('post')) {
            $data       = $this->request->getData();
            $data       = $this->Sanitize->clean($data);
            $doc_type   = $this->SpeechAndConferences->patchEntity($doc_type, $data);
            
            $doc_type->youtube_link = $data['youtube_link'];
            $doc_type->youtube_id   = $data['youtube_id'];
            $doc_type->title        = $data['title'];
            $doc_type->publish_date = $data['publish_date'];
            $doc_type->status       = $data['status'];
            $doc_type->event_order  = $data['event_order'];
            $doc_type->created      = date('Y-m-d');

            //echo "<pre>";print_r($doc_type);exit();
            if ($this->SpeechAndConferences->save($doc_type)) {
                $this->Flash->success(__('The Speech And Conferences has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Speech And Conferences could not be saved. Please, try again.'));
        }
        $this->set(compact('doc_type'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $doc_type = $this->SpeechAndConferences->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data       = $this->Sanitize->clean($this->request->getData());
            
            $doc_type = $this->SpeechAndConferences->patchEntity($doc_type, $data);
            
            /*$doc_type->title        = $data['title'];
            $doc_type->youtube_link = $data['youtube_link'];
            $doc_type->youtube_id   = $data['youtube_id'];
            $doc_type->publish_date = $data['publish_date'];
            $doc_type->event_order  = $data['event_order'];
            $doc_type->status       = $data['status'];
            $doc_type->created      = date('Y-m-d');*/
            
            //echo "<pre>";print_r($doc_type);exit();
            if ($this->SpeechAndConferences->save($doc_type)) {
                $this->Flash->success(__('The Speech And Conferences has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Speech And Conferences could not be saved. Please, try again.'));
        }
        $this->set(compact('doc_type'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $role = $this->SpeechAndConferences->get($id);
        $modi = date("d-m-y h:i:s");
        $query = $this->SpeechAndConferences->query();
            $query->update()
            ->set(['status' => 0,'modified'=>$modi])
            ->where(['id' => $id])
            ->execute(); 
        if ($query) {
            $this->Flash->success(__('The Speech And Conferences has been deleted.'));
        } else {
            $this->Flash->error(__('The Speech And Conferences could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
