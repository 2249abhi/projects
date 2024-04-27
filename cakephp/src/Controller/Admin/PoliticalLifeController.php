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
class PoliticalLifeController extends AppController
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
		$this->loadModel('PoliticalLife');
        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
        $data = $this->request->getQuery();
        $data = $this->Sanitize->clean($data);
        $data['title'] = $this->Sanitize->stripAll(isset($data['title'])?$data['title']:'');

        if (!empty($data['title'])) {
            $title = trim($data['title']);
            $this->set('title', $title);
            $search_condition[] = "PoliticalLife.title like '%" . $title . "%'";
        }
        if (isset($data['status']) && $data['status'] !='') {
            $status = trim($data['status']);
            $this->set('status', $status); 
            $search_condition[] = "PoliticalLife.status = '" . $status . "'";
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

        /*if($this->request->is('get')){
            if(!empty($this->request->query['export_excel'])) {
                $queryExport = $this->PoliticalLife->find('all',[
                    'conditions'=>[$searchString],
                    'limit' => $page_length,
                    'page'=> $page,
                    'order' => ['Blogs.id' => 'asc'],
                ]);
                $queryExport->hydrate(false);
                $ExportResultData = $queryExport->toArray();
                $fileName = "PoliticalLife-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No","Title "," Content ", 'Status');
                $data = array();
                $i=1;
                $stat = ['0'=>'Inactive', 'Active'];
                foreach($ExportResultData As $rows){
                    $data[]=array($i, $rows['title'],$rows['content'], $stat[$rows['status']]);
                    $i++;
                }
                $this->exportInExcel($fileName, $headerRow, $data);
            }
        }*/

        $rolesQuery = $this->PoliticalLife->find('all', [
            'order' => ['PoliticalLife.id' => 'asc'],
            'conditions' => [$searchString]
        ]);//echo '<pre>'; print_r($rolesQuery);die;
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
		$this->loadModel('PoliticalLife');
        $doc_type = $this->PoliticalLife->newEntity();
        if ($this->request->is('post')) {
            $data       = $this->request->getData();
			//echo "<pre>"; print_r($data); exit;
            $data       = $this->Sanitize->clean($data);
            $doc_type = $this->PoliticalLife->patchEntity($doc_type, $data);
            if($data['image']['name']!=''){
                $fileName = $this->uploadImage('political_life', $data['image']);
                $doc_type->image  = $fileName['filename'];//echo "<pre>"; print_r($fileName); exit;
            }//echo "<pre>"; print_r($doc_type); exit;
            if ($this->PoliticalLife->save($doc_type)) {
                $this->Flash->success(__('The PoliticalLife has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The PoliticalLife could not be saved. Please, try again.'));
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
    {   //echo $id;die;
        $doc_type = $this->PoliticalLife->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data       = $this->request->getData();
            $data       = $this->Sanitize->clean($data);
            $doc_type = $this->PoliticalLife->patchEntity($doc_type, $data);
            if($data['image']['name']!=''){
                $fileName = $this->uploadImage('political_life', $data['image']);
                $doc_type->image  = $fileName['filename'];//echo "<pre>"; print_r($fileName); exit;
            }
			$modi = date("d-m-y h:i:s");
			$doc_type->modified = $modi;//echo '<pre>'; print_r($doc_type);die;
            if ($this->PoliticalLife->save($doc_type)) {
                $this->Flash->success(__('The PoliticalLife has been saved.'));
                //echo 'save';die;
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The PoliticalLife could not be saved. Please, try again.'));
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
        $PLife = $this->PoliticalLife->get($id);
        if ($this->PoliticalLife->delete($PLife)) {
            $this->Flash->success(__('The PoliticalLife has been deleted.'));
        } else {
            $this->Flash->error(__('The PoliticalLife could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
