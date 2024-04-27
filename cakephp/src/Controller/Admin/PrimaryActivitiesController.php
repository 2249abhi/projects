<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * PrimaryActivities Controller
 *
 * @property \App\Model\Table\PrimaryActivitiesTable $PrimaryActivities
 *
 * @method \App\Model\Entity\PrimaryActivities[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PrimaryActivitiesController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadMOdel('PrimaryActivities');
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
        $this->loadModel('SectorOperations');

        if (isset($this->request->query['name']) && $this->request->query['name'] !='') {
            $s_name = trim($this->request->query['name']);
            $this->set('s_name', $s_name);
            $search_condition[] = "name like '%" . $s_name . "%'";
        }

        if (isset($this->request->query['sector_operation']) && $this->request->query['sector_operation'] !='') {
            $s_sector_operation = trim($this->request->query['sector_operation']);
            $this->set('s_sector_operation', $s_sector_operation);
            $search_condition[] = "sector_of_operation = '" . $s_sector_operation . "'";
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
        
        $pactivities = $this->PrimaryActivities->find('all', [
            'order' => ['sector_of_operation' => 'ASC','orderseq'=>'ASC'],
            'conditions' => [$searchString]
        ]);

        $this->paginate = ['limit' => 20];

        $pactivities = $this->paginate($pactivities);

        $status = ['1'=>'Active', '0'=>'Inactive'];

        $sector_operations = $this->SectorOperations->find('list',['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC'])->where(['status'=>1])->toArray();

        $this->set(compact('pactivities','status','sector_operations'));
    }

    /**
     * View method
     *
     * @param string|null $id PrimaryActivities id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        /*$designation = $this->Designations->get($id, [
            'contain' => ['Departments']
        ]);*/
        $pactivity = $this->PrimaryActivities->get($id, [
            'contain' => ['SectorOperations']
        ]);

        $this->set('pactivity', $pactivity);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $pactivity = $this->PrimaryActivities->newEntity();

        if ($this->request->is('post')) {
            $pactivity = $this->PrimaryActivities->patchEntity($pactivity, $this->request->getData());

            if ($this->PrimaryActivities->save($pactivity)) {
                $this->Flash->success(__('The Primary Activity has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Primary Activity could not be saved. Please, try again.'));
        }
        $soperations = $this->PrimaryActivities->SectorOperations->find('list', ['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC']);
        $this->set(compact('pactivity','soperations'));
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
        $pactivity = $this->PrimaryActivities->get($id, [
            'contain' => ['SectorOperations']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pactivity = $this->PrimaryActivities->patchEntity($pactivity, $this->request->getData());
            if ($this->PrimaryActivities->save($pactivity)) {
                $this->Flash->success(__('The Primary Activity has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Primary Activity could not be saved. Please, try again.'));
        }
        $soptions = $this->PrimaryActivities->SectorOperations->find('list', ['keyField'=>'id','valueField'=>'name'])->order(['id'=>'ASC']);
        $this->set(compact('pactivity', 'soptions'));
    }

    /**
     * Delete method
     *
     * @param string|null $id PrimaryActivities id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pactivity = $this->PrimaryActivities->get($id);
        if ($this->PrimaryActivities->delete($pactivity)) {
            $this->Flash->success(__('The Primary Activity has been deleted.'));
        } else {
            $this->Flash->error(__('The Primary Activity could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
