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
class BlogsController extends AppController
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
		$this->loadModel('Blogs');
        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
        $data = $this->request->getQuery();
        $data = $this->Sanitize->clean($data);
        $data['title'] = $this->Sanitize->stripAll(isset($data['title'])?$data['title']:'');

        if (!empty($data['title'])) {
            $title = trim($data['title']);
            $this->set('title', $title);
            $search_condition[] = "Blogs.title like '%" . $title . "%'";
        }
        if (isset($data['status']) && $data['status'] !='') {
            $status = trim($data['status']);
            $this->set('status', $status); 
            $search_condition[] = "Blogs.status = '" . $status . "'";
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
                $queryExport = $this->Blogs->find('all',[
                    'conditions'=>[$searchString],
                    'limit' => $page_length,
                    'page'=> $page,
                    'order' => ['Blogs.id' => 'asc'],
                ]);
                $queryExport->hydrate(false);
                $ExportResultData = $queryExport->toArray();
                $fileName = "Videos-".date("d-m-y:h:s").".xls";
                $headerRow = array("S.No","Title ","  Date "," Writer "," Content ", 'Status');
                $data = array();
                $i=1;
                $stat = ['0'=>'Inactive', 'Active'];
                foreach($ExportResultData As $rows){
                    $data[]=array($i, $rows['title'],date("d M Y",strtotime($rows['date'])),$rows['writer'],$rows['content'], $stat[$rows['status']]);
                    $i++;
                }
                $this->exportInExcel($fileName, $headerRow, $data);
            }
        }

        $rolesQuery = $this->Blogs->find('all', [
            'order' => ['Blogs.id' => 'asc'],
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
		$this->loadModel('Blogs');
        $doc_type = $this->Blogs->newEntity();
        if ($this->request->is('post')) {
            $data       = $this->request->getData();
			//echo "<pre>"; print_r($data); exit;
            $data       = $this->Sanitize->clean($data);
            $doc_type = $this->Blogs->patchEntity($doc_type, $data);
            if($data['blog_image']['name']!=''){
                $fileName = $this->uploadImage('blogs', $data['blog_image']);
                $doc_type->blog_image  = $fileName['filename'];//echo "<pre>"; print_r($fileName); exit;
            }//echo "<pre>"; print_r($doc_type); exit;
            if ($this->Blogs->save($doc_type)) {
                $this->Flash->success(__('The Blog has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Blog could not be saved. Please, try again.'));
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
        $doc_type = $this->Blogs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data       = $this->request->getData();
            $data       = $this->Sanitize->clean($data);
            $doc_type = $this->Blogs->patchEntity($doc_type, $data);
            if($data['blog_image']['name']!=''){
                $fileName = $this->uploadImage('blogs', $data['blog_image']);
                $doc_type->blog_image  = $fileName['filename'];
            }
			$modi = date("d-m-y h:i:s");
			$doc_type->modified = $modi;//echo '<pre>'; print_r($doc_type);die;
            if ($this->Blogs->save($doc_type)) {
                $this->Flash->success(__('The blog has been saved.'));
                //echo 'save';die;
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The blogs could not be saved. Please, try again.'));
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
        $blog = $this->Blogs->get($id);
        if ($this->Blogs->delete($blog)) {
            $this->Flash->success(__('The Blog has been deleted.'));
        } else {
            $this->Flash->error(__('The Blog could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
