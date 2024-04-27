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
class DownloadController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Download');
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

        if (!empty($this->request->query['role_name'])) {
            $roleName = trim($this->request->query['role_name']);
            $this->set('roleName', $roleName);
            $search_condition[] = "Download.name like '%" . $roleName . "%'";
        }
        if (isset($this->request->query['status']) && $this->request->query['status'] !='') {
            $status = trim($this->request->query['status']);
            $this->set('status', $status);
            $search_condition[] = "Download.status = '" . $status . "'";
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
                $queryExport = $this->Download->find('all',[
                    'conditions'=>[$searchString],
                    'limit' => $page_length,
                    'page'=> $page,
                    'order' => ['Download.id' => 'asc'],
                ]);
                $queryExport->hydrate(false);
                $ExportResultData = $queryExport->toArray();
                $fileName = "Download-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No",  " Title ", "Content", " Priority ", 'Status');
                $data = array();
                $i=1;
                $stat = ['0'=>'Inactive', 'Active'];
                foreach($ExportResultData As $rows){
                    $data[]=array($i, $rows['title'],$rows['content'],$rows['priority'], $stat[$rows['status']]);
                    $i++;
                } 
                $this->exportInExcel($fileName, $headerRow, $data);
            }
        }

        $rolesQuery = $this->Download->find('all', [
            'contain' => [],
            'order' => ['Download.id' => 'asc'],
            'conditions' => [$searchString]
        ]);

        $this->paginate = ['limit' => 10];
        $roles = $this->paginate($rolesQuery);
        $this->set('selectedLen', $page_length);
        $this->set(compact('roles','pagCatList'));
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
        $role = $this->Download->get($id, [
            'contain' => []
        ]);

        $this->set('role', $role);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $role = $this->Download->newEntity();
        if ($this->request->is('post')) {
            $role = $this->Download->patchEntity($role, $this->request->getData());
            //echo "<pre>"; print_r($this->request->getData()); exit;
            if(isset($this->request->data['image']['error']) && $this->request->data['image']['error']==0){
                $photo = $this->uploadFiles('download', $this->request->data['image']); 
                $role->image = $photo['filename'];
            }

            if(isset($this->request->data['download_doc']['error']) && $this->request->data['download_doc']['error']==0){
                $photo = $this->uploadFiles('download', $this->request->data['download_doc']); 
                $role->download_doc = $photo['filename'];
            }
            $role->created = date('Y-m-d');
            //echo "<pre>"; print_r($role); exit;
            
            if ($this->Download->save($role)) {
                $this->Flash->success(__('The Download has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Download could not be saved. Please, try again.'));
        }
        $this->set(compact('role'));
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
        $role = $this->Download->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $role = $this->Download->patchEntity($role, $this->request->getData());

            if(isset($this->request->data['image']['error']) && $this->request->data['image']['error']==0){
                $photo = $this->uploadFiles('download', $this->request->data['image']); 
                $role->image = $photo['filename'];
            }else{
                $role->image = @$this->request->data['image_old'];
            }

            if(isset($this->request->data['download_doc']['error']) && $this->request->data['download_doc']['error']==0){
                $photo = $this->uploadFiles('download', $this->request->data['download_doc']); 
                $role->download_doc = $photo['filename'];
            }else{
                $role->download_doc = @$this->request->data['download_doc_old'];
            }
            
            $role->modified = date('Y-m-d');

            if ($this->Download->save($role)) {
                $this->Flash->success(__('The Download has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Download could not be saved. Please, try again.'));
        }
        $this->set(compact('role'));
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
        $role = $this->Download->get($id);
        if ($this->Download->delete($role)) {
            $this->Flash->success(__('The download has been deleted.'));
        } else {
            $this->Flash->error(__('The download could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
