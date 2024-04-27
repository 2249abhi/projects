<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * MenuRegions Controller
 *
 * @property \App\Model\Table\MenuRegionsTable $MenuRegions
 *
 * @method \App\Model\Entity\MenuRegion[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MenuRegionsController extends AppController
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
        $menuRegions = $this->paginate($this->MenuRegions);

        $this->set(compact('menuRegions'));
    }

    /**
     * View method
     *
     * @param string|null $id Menu Region id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $menuRegion = $this->MenuRegions->get($id, [
            'contain' => ['Menus']
        ]);

        $this->set('menuRegion', $menuRegion);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $menuRegion = $this->MenuRegions->newEntity();
        if ($this->request->is('post')) {
            $menuRegion = $this->MenuRegions->patchEntity($menuRegion, $this->request->getData());
            if ($this->MenuRegions->save($menuRegion)) {
                $this->Flash->success(__('The menu region has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The menu region could not be saved. Please, try again.'));
        }
        $this->set(compact('menuRegion'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Menu Region id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $menuRegion = $this->MenuRegions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $menuRegion = $this->MenuRegions->patchEntity($menuRegion, $this->request->getData());
            if ($this->MenuRegions->save($menuRegion)) {
                $this->Flash->success(__('The menu region has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The menu region could not be saved. Please, try again.'));
        }
        $this->set(compact('menuRegion'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Menu Region id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $menuRegion = $this->MenuRegions->get($id);
        if ($this->MenuRegions->delete($menuRegion)) {
            $this->Flash->success(__('The menu region has been deleted.'));
        } else {
            $this->Flash->error(__('The menu region could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
