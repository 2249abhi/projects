<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * AreaOfOperations Controller
 *
 * @property \App\Model\Table\AreaOfOperationsTable $AreaOfOperations
 *
 * @method \App\Model\Entity\AreaOfOperations[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AreaOfOperationsController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadMOdel('AreaOfOperations');
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
        

        if (isset($this->request->query['name']) && $this->request->query['name'] !='') {
            $s_name = trim($this->request->query['name']);
            $this->set('s_name', $s_name);
            $search_condition[] = "name like '%" . $s_name . "%'";
        }

        if (isset($this->request->query['status']) && $this->request->query['status'] !='') {
            $s_status = trim($this->request->query['status']);
            $this->set('s_status', $s_status);
            $search_condition[] = "status = '" . $s_status . "'";
        }

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }
        
        $aoperations = $this->AreaOfOperations->find('all', [
            'order' => ['name'=>'ASC'],
            'conditions' => [$searchString]
        ]);
        
        //->find('All')->order(['sector_of_operation'=>'ASC'])->order(['orderseq'=>'ASC']);

        $this->paginate = ['limit' => 20];

        $aoperations = $this->paginate($aoperations);

        $status = ['1'=>'Active','0'=>'Inactive'];

        $this->set(compact('aoperations','status'));
    }

    /**
     * View method
     *
     * @param string|null $id AreaOfOperations id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        /*$designation = $this->Designations->get($id, [
            'contain' => ['Departments']
        ]);*/
        $aoperations = $this->AreaOfOperations->get($id);

        $this->set('aoperations', $aoperations);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $aoperation = $this->AreaOfOperations->newEntity();

        if ($this->request->is('post')) {
            $aoperation = $this->AreaOfOperations->patchEntity($aoperation, $this->request->getData());

            if ($this->AreaOfOperations->save($aoperation)) {
                $this->Flash->success(__('The Area of Operations has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Area of Operations could not be saved. Please, try again.'));
        }
        $this->set(compact('aoperation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Designation id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $aoperation = $this->AreaOfOperations->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $aoperation = $this->AreaOfOperations->patchEntity($aoperation, $this->request->getData());
            if ($this->AreaOfOperations->save($aoperation)) {
                $this->Flash->success(__('The Area of Operations has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Area of Operations could not be saved. Please, try again.'));
        }
        
        $this->set(compact('aoperation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id AreaOfOperations id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $aoperation = $this->AreaOfOperations->get($id);
        if ($this->AreaOfOperations->delete($aoperation)) {
            $this->Flash->success(__('The Area of Operations has been deleted.'));
        } else {
            $this->Flash->error(__('The Area of Operations could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
