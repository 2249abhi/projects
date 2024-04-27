<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * LocaleSources Controller
 *
 * @property \App\Model\Table\LocaleSourcesTable $LocaleSources
 *
 * @method \App\Model\Entity\LocaleSource[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LocaleSourcesController extends AppController
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
        $localeSources = $this->paginate($this->LocaleSources);

        $this->set(compact('localeSources'));
    }

    /**
     * View method
     *
     * @param string|null $id Locale Source id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $localeSource = $this->LocaleSources->get($id, [
            'contain' => ['LocaleTargets']
        ]);

        $this->set('localeSource', $localeSource);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $localeSource = $this->LocaleSources->newEntity();
        if ($this->request->is('post')) {
            $source = trim($this->request->getData('source'));
            $source = $this->Sanitize->stripAll($source);
            $source = $this->Sanitize->clean($source); 
            $queryDa = $this->LocaleSources->find('all',['conditions'=>['source'=>$source]])->first();
            if (!empty($queryDa)) {
                $this->Flash->warning(__('Record already exist!'));
            } else {
                if(empty($source)) {
                    $this->Flash->error(__("Empty record couldn't be saved. Please Enter Text"));
                } else {
                    $name= ['source'=> trim($source)];
                    $localeSource = $this->LocaleSources->patchEntity($localeSource,$name);
                    if($result = $this->LocaleSources->save($localeSource)) {
                        $this->Flash->success(__('The locale source has been saved.'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('The locale source could not be saved. Please, try again.'));
                    }
                }
            }
        }
        $this->set(compact('localeSource'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Locale Source id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $localeSource = $this->LocaleSources->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $source = trim($this->request->getData('source'));
            $source = $this->Sanitize->stripAll($source);
            $source = $this->Sanitize->clean($source); 
            $queryDa = $this->LocaleSources->find('all',['conditions'=>['source'=>$source]])->first();
            if (!empty($queryDa) && $queryDa->id != $id) {
                $this->Flash->warning(__('Record already exist!'));
            } else {
                if(empty($source)) {
                    $this->Flash->error(__("Empty record couldn't be saved. Please Enter Text"));
                } else {
                    $name = ['source'=> trim($source)];
                    $localeSource = $this->LocaleSources->patchEntity($localeSource,$name);
                    if($result = $this->LocaleSources->save($localeSource)) {
                        $this->Flash->success(__('The locale source has been saved.'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('The locale source could not be saved. Please, try again.'));
                    }
                }
            }
        }
        $this->set(compact('localeSource'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Locale Source id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $localeSource = $this->LocaleSources->get($id);
        if ($this->LocaleSources->delete($localeSource)) {
            $this->Flash->success(__('The locale source has been deleted.'));
        } else {
            $this->Flash->error(__('The locale source could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
