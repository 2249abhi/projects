<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * Roles Controller
 *
 * @property \App\Model\Table\RolesTable $Roles
 *
 * @method \App\Model\Entity\Role[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NotificationsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
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
		$this->loadModel('Notifications');
        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
        $data = $this->request->getQuery();
        $data = $this->Sanitize->clean($data);
        $data['description'] = $this->Sanitize->stripAll(isset($data['description'])?$data['description']:'');

        if (!empty($data['description'])) {
            $description = trim($data['description']);
            $this->set('description', $description);
            $search_condition[] = "Notifications.description like '%" . $description . "%'";
        }
        if (isset($data['status']) && $data['status'] !='') {
            $status = trim($data['status']);
            $this->set('status', $status); 
            $search_condition[] = "Notifications.status = '" . $status . "'";
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
                $queryExport = $this->Notifications->find('all',[
                    'conditions'=>[$searchString],
                    'limit' => $page_length,
                    'page'=> $page,
                    'order' => ['Notifications.id' => 'asc'],
                ]);
                $queryExport->hydrate(false);
                $ExportResultData = $queryExport->toArray();
                $fileName = "Notifications-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No"," Notification Description "," Notification Date "," Notification Order ", 'Status');
                $data = array();
                $i=1;
                $stat = ['0'=>'Inactive', 'Active'];
                foreach($ExportResultData As $rows){
                    $data[]=array($i, $rows['description'],date("d M Y",strtotime($rows['notification_date'])),$rows['notification_order'], $stat[$rows['status']]);
                    $i++;
                }
                $this->exportInExcel($fileName, $headerRow, $data);
            }
        }

        $rolesQuery = $this->Notifications->find('all', [
            'order' => ['Notifications.id' => 'asc'],
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
        $doc_type = $this->Notifications->get($id, [
            
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
		$this->loadModel('Notifications');
        $doc_type = $this->Notifications->newEntity();
        if ($this->request->is('post')) {
            $data       = $this->request->getData();
			//echo "<pre>"; print_r($data); exit;
            $data       = $this->Sanitize->clean($data);
            $doc_type = $this->Notifications->patchEntity($doc_type, $data);
            if ($this->Notifications->save($doc_type)) {
                $this->Flash->success(__('The notifications has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The notifications could not be saved. Please, try again.'));
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
        $doc_type = $this->Notifications->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data       = $this->request->getData();
            $data       = $this->Sanitize->clean($data);
            $doc_type = $this->Notifications->patchEntity($doc_type, $data);
			$modi = date("d-m-y h:i:s");
			$doc_type->modified = $modi;
            if ($this->Notifications->save($doc_type)) {
                $this->Flash->success(__('The notifications has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The notifications could not be saved. Please, try again.'));
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
        $role = $this->Notifications->get($id);
		$modi = date("d-m-y h:i:s");
		$query = $this->Notifications->query();
			$query->update()
			->set(['status' => 0,'modified'=>$modi])
			->where(['id' => $id])
			->execute(); 
        if ($query) {
            $this->Flash->success(__('The notifications has been deleted.'));
        } else {
            $this->Flash->error(__('The notifications could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	/**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function photoList()
    {
		$this->loadModel('PhotoListVideos');
		$search_condition = array();
		if (!empty($this->request->getQuery('title'))) {
            $postTitle = trim($this->request->getQuery('title')); 
            $this->set('title', $postTitle);
            $search_condition[] = "PhotoListVideos.title like '%" . $postTitle . "%'";
        }
		
		if (isset($this->request->query['status']) && $this->request->getQuery('status') !='') {
            $status = trim($this->request->getQuery('status'));
            $this->set('status', $status);
            $search_condition[] = "PhotoListVideos.status = '" . $status . "'";
        }

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }
		//pr($search_condition); die;
        $postQuery = $this->PhotoListVideos->find('all', [
            'order' => ['PhotoListVideos.id' => 'desc'],
			'conditions' => [$searchString]
        ]);
        $this->paginate = ['limit' => 10];
        $news = $this->paginate($postQuery);
        
        $this->set(compact('news'));   	    
		
    }
	/**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function addPhoto()
    {
		$this->loadModel('PhotoListVideos');
        $doc_type = $this->PhotoListVideos->newEntity();
        if ($this->request->is('post')) {
            $data       = $this->request->getData();
			//echo "<pre>"; print_r($data); exit;
            $data       = $this->Sanitize->clean($data);
            $doc_type = $this->PhotoListVideos->patchEntity($doc_type, $data);
			if($data['image']['name']!=''){
                $fileName = $this->uploadFiles('events', $data['image']);
                $doc_type->image  = $fileName['filename'];
                              
            }
            if ($this->PhotoListVideos->save($doc_type)) {
                $this->Flash->success(__('The notifications has been saved.'));

                return $this->redirect(['action' => 'photoList']);
            }
            $this->Flash->error(__('The notifications could not be saved. Please, try again.'));
        }
        $this->set(compact('doc_type'));
    }
}
