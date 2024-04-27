<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * SecondaryActivities Controller
 *
 * @property \App\Model\Table\SecondaryActivitiesTable $SecondaryActivities
 *
 * @method \App\Model\Entity\SecondaryActivities[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SecondaryActivitiesController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadMOdel('SecondaryActivities');
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
        
        $sactivities = $this->SecondaryActivities->find('all', [
            'order' => ['orderseq'=>'ASC'],
            'conditions' => [$searchString]
        ]);
        
        //->find('All')->order(['sector_of_operation'=>'ASC'])->order(['orderseq'=>'ASC']);

        $this->paginate = ['limit' => 20];

        $sactivities = $this->paginate($sactivities);

        $status = ['1'=>'Active','0'=>'Inactive'];

        $this->set(compact('sactivities','status'));
    }

    /**
     * View method
     *
     * @param string|null $id SecondaryActivities id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        /*$designation = $this->Designations->get($id, [
            'contain' => ['Departments']
        ]);*/
        $sactivity = $this->SecondaryActivities->get($id);

        $this->set('sactivity', $sactivity);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sactivity = $this->SecondaryActivities->newEntity();

        if ($this->request->is('post')) {
            $sactivity = $this->SecondaryActivities->patchEntity($sactivity, $this->request->getData());

            if ($this->SecondaryActivities->save($sactivity)) {
                $this->Flash->success(__('The Secondary Activity has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Secondary Activity could not be saved. Please, try again.'));
        }
        $this->set(compact('sactivity'));
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
        $sactivity = $this->SecondaryActivities->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sactivity = $this->SecondaryActivities->patchEntity($sactivity, $this->request->getData());
            if ($this->SecondaryActivities->save($sactivity)) {
                $this->Flash->success(__('The Secondary Activity has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Secondary Activity could not be saved. Please, try again.'));
        }
        
        $this->set(compact('sactivity'));
    }

    /**
     * Delete method
     *
     * @param string|null $id SecondaryActivities id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sactivity = $this->SecondaryActivities->get($id);
        if ($this->SecondaryActivities->delete($sactivity)) {
            $this->Flash->success(__('The Secondary Activity has been deleted.'));
        } else {
            $this->Flash->error(__('The Secondary Activity could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
