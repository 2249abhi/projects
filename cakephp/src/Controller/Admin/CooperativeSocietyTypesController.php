<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * CooperativeSocietyTypes Controller
 *
 * @property \App\Model\Table\CooperativeSocietyTypesTable $CooperativeSocietyTypes
 *
 * @method \App\Model\Entity\CooperativeSocietyTypes[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CooperativeSocietyTypesController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadMOdel('CooperativeSocietyTypes');
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
        
        $stypes = $this->CooperativeSocietyTypes->find('all', [
            'order' => ['name'=>'ASC'],
            'conditions' => [$searchString]
        ]);
        
        //->find('All')->order(['sector_of_operation'=>'ASC'])->order(['orderseq'=>'ASC']);

        $this->paginate = ['limit' => 20];

        $stypes = $this->paginate($stypes);

        $status = ['1'=>'Active','0'=>'Inactive'];

        $this->set(compact('stypes','status'));
    }

    /**
     * View method
     *
     * @param string|null $id CooperativeSocietyTypes id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        /*$designation = $this->Designations->get($id, [
            'contain' => ['Departments']
        ]);*/
        $stype = $this->CooperativeSocietyTypes->get($id);

        $this->set('stype', $stype);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $stype = $this->CooperativeSocietyTypes->newEntity();

        if ($this->request->is('post')) {
            $stype = $this->CooperativeSocietyTypes->patchEntity($stype, $this->request->getData());

            if ($this->CooperativeSocietyTypes->save($stype)) {
                $this->Flash->success(__('The Cooperative Society Type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Cooperative Society Type could not be saved. Please, try again.'));
        }
        $this->set(compact('stype'));
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
        $stype = $this->CooperativeSocietyTypes->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $stype = $this->CooperativeSocietyTypes->patchEntity($stype, $this->request->getData());
            if ($this->CooperativeSocietyTypes->save($stype)) {
                $this->Flash->success(__('The Cooperative Society Type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Cooperative Society Type could not be saved. Please, try again.'));
        }
        
        $this->set(compact('stype'));
    }

    /**
     * Delete method
     *
     * @param string|null $id CooperativeSocietyTypes id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $stype = $this->CooperativeSocietyTypes->get($id);
        if ($this->CooperativeSocietyTypes->delete($stype)) {
            $this->Flash->success(__('The Cooperative Society Type has been deleted.'));
        } else {
            $this->Flash->error(__('The Cooperative Society Type could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
