<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * LogoSliders Controller
 *
 * @property \App\Model\Table\LogoSlidersTable $LogoSliders
 *
 * @method \App\Model\Entity\LogoSlider[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LogoSlidersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    private $logoCategory = [
        '1' => 'Home',
        '2' => 'Footer',
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $logoSliders = $this->paginate($this->LogoSliders);
        $logoCategory    = $this->logoCategory;
        $this->set(compact('logoSliders','logoCategory'));
    }

    /**
     * View method
     *
     * @param string|null $id Logo Slider id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $logoSlider = $this->LogoSliders->get($id, [
            'contain' => []
        ]);
        $logoCategory    = $this->logoCategory;
        $this->set(compact('logoSlider','logoCategory'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $logoSlider = $this->LogoSliders->newEntity();
        if ($this->request->is('post')) {
            $logoSlider = $this->LogoSliders->patchEntity($logoSlider, $this->request->getData());
            if($this->request->getData('logo_image')['name']!=''){
                $logoImage = $this->uploadImage('logo', $this->request->getData('logo_image'));
                $logoSlider->logo_image = $logoImage['filename'];
            }
            if ($this->LogoSliders->save($logoSlider)) {
                $this->Flash->success(__('The logo slider has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The logo slider could not be saved. Please, try again.'));
        }
        $logoCategory    = $this->logoCategory;
        $this->set(compact('logoSlider','logoCategory'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Logo Slider id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $logoSlider = $this->LogoSliders->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $logoSlider = $this->LogoSliders->patchEntity($logoSlider, $this->request->getData());
            if(!empty($this->request->getData('logo_image'))){
                $logoImage = $this->uploadImage('logo', $this->request->getData('logo_image'));
                $logoSlider->logo_image = $logoImage['filename'];
            } else {
                $logoSlider->logo_image = $this->request->getData('old_logo_image');
            }
            if ($this->LogoSliders->save($logoSlider)) {
                $this->Flash->success(__('The logo slider has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The logo slider could not be saved. Please, try again.'));
        }
        $logoCategory    = $this->logoCategory;
        $this->set(compact('logoSlider','logoCategory'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Logo Slider id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $logoSlider = $this->LogoSliders->get($id);
        if ($this->LogoSliders->delete($logoSlider)) {
            $this->Flash->success(__('The logo slider has been deleted.'));
        } else {
            $this->Flash->error(__('The logo slider could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
