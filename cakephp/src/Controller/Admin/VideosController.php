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
class VideosController extends AppController
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
		$this->loadModel('Videos');
        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
        $data = $this->request->getQuery();
        $data = $this->Sanitize->clean($data);
        $data['title'] = $this->Sanitize->stripAll(isset($data['title'])?$data['title']:'');

        if (!empty($data['title'])) {
            $title = trim($data['title']);
            $this->set('title', $title);
            $search_condition[] = "Videos.title like '%" . $title . "%'";
        }
        if (isset($data['status']) && $data['status'] !='') {
            $status = trim($data['status']);
            $this->set('status', $status); 
            $search_condition[] = "Videos.status = '" . $status . "'";
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
                $queryExport = $this->Videos->find('all',[
                    'conditions'=>[$searchString],
                    'limit' => $page_length,
                    'page'=> $page,
                    'order' => ['Videos.id' => 'asc'],
                ]);
                $queryExport->hydrate(false);
                $ExportResultData = $queryExport->toArray();
                $fileName = "Videos-".date("d-m-y:h:s").".xls";
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

        $rolesQuery = $this->Videos->find('all', [
            'order' => ['Videos.id' => 'asc'],
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
        $doc_type = $this->Videos->get($id, [
            
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
		$this->loadModel('Videos');
        $doc_type = $this->Videos->newEntity();
        if ($this->request->is('post')) {
            $data       = $this->request->getData();
			//echo "<pre>"; print_r($data); exit;
            $data       = $this->Sanitize->clean($data);
            $doc_type = $this->Videos->patchEntity($doc_type, $data);
            if($data['video_image']['name']!=''){
                $fileName = $this->uploadImage('videos', $data['video_image']);
                $doc_type->video_image  = $fileName['filename'];
            }
            if ($this->Videos->save($doc_type)) {
                $this->Flash->success(__('The Video has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Video could not be saved. Please, try again.'));
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
        $doc_type = $this->Videos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data       = $this->request->getData();
            $data       = $this->Sanitize->clean($data);
            $doc_type = $this->Videos->patchEntity($doc_type, $data);
            if($data['video_image']['name']!=''){
                $fileName = $this->uploadImage('videos', $data['video_image']);
                $doc_type->video_image  = $fileName['filename'];
            }
			$modi = date("d-m-y h:i:s");
			$doc_type->modified = $modi;
            if ($this->Videos->save($doc_type)) {
                $this->Flash->success(__('The video has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The videos could not be saved. Please, try again.'));
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
        $video = $this->Videos->get($id);
        if ($this->Videos->delete($video)) {
            $this->Flash->success(__('The Video has been deleted.'));
        } else {
            $this->Flash->error(__('The Video could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    /*public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $role = $this->Videos->get($id);
		$modi = date("d-m-y h:i:s");
		$query = $this->Videos->query();
			$query->update()
			->set(['status' => 0])
			->where(['id' => $id,'modified'=>$modi])
			->execute(); 
        if ($query) {
            $this->Flash->success(__('The Video has been deleted.'));
        } else {
            $this->Flash->error(__('The Video could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }*/
}
