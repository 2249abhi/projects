<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Utility\Hash;

/**
 * GalleryCategories Controller
 *
 * @property \App\Model\Table\GalleryCategoriesTable $GalleryCategories
 *
 * @method \App\Model\Entity\GalleryCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GalleryCategoriesController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Articles']
        ];
        $galleryCategories = $this->paginate($this->GalleryCategories);
        $this->set(compact('galleryCategories'));
         
    }

    /**
     * View method
     *
     * @param string|null $id Gallery Category id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $galleryCategory = $this->GalleryCategories->get($id, [
            'contain' => ['Articles', 'Users', 'Galleries']
        ]);
        $this->set('galleryCategory', $galleryCategory);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $galleryCategory = $this->GalleryCategories->newEntity();
        if ($this->request->is('post')) {
            $galleryCategory = $this->GalleryCategories->patchEntity($galleryCategory, $this->request->getData());
            if ($this->GalleryCategories->save($galleryCategory)) {
                $this->Flash->success(__('The gallery category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The gallery category could not be saved. Please, try again.'));
        }
        $articles = $this->GalleryCategories->Articles->find('list', ['limit' => 200]);
        $this->set(compact('galleryCategory', 'articles', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Gallery Category id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $galleryCategory = $this->GalleryCategories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $galleryCategory = $this->GalleryCategories->patchEntity($galleryCategory, $this->request->getData());
            if ($this->GalleryCategories->save($galleryCategory)) {
                $this->Flash->success(__('The gallery category has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The gallery category could not be saved. Please, try again.'));
        }
        $articles = $this->GalleryCategories->Articles->find('list', ['limit' => 200]);
        $this->set(compact('galleryCategory', 'articles', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Gallery Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $galleryCategory = $this->GalleryCategories->get($id);
        if ($this->GalleryCategories->delete($galleryCategory)) {
            $this->Flash->success(__('The gallery category has been deleted.'));
        } else {
            $this->Flash->error(__('The gallery category could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/    
                        //Video Category Create By RISHABH
    
    /************************************************************************************************************/

    public function videoCategory() {
        $this->loadModel('VideoCategories');
        $data = $this->VideoCategories->find('all');
        $this->paginate = [ ];
        $videoCategories = $this->paginate($data);

        $this->set(compact('videoCategories'));
       
    }

    /**
     * View method
     *
     * @param string|null $id Gallery Category id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function videoCategoryView($id = null) {
        $this->loadModel('VideoCategories');
        $videoCategory = $this->VideoCategories->get($id, [
            'contain' => [ 'Users']
        ]);

        $this->set('galleryCategory', $videoCategory);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function videoCategoryAdd() {
        $this->loadModel('VideoCategories');
        $galleryCategory = $this->VideoCategories->newEntity();
        if ($this->request->is('post')) { 
            //debug($this->request->data);die;
            $galleryCategory = $this->VideoCategories->patchEntity($galleryCategory, $this->request->getData());
            if ($this->VideoCategories->save($galleryCategory)) {
                $this->Flash->success(__('The Video gallery category has been saved.'));

                return $this->redirect(['action' => 'videoCategory']);
            }
            $this->Flash->error(__('The gallery category could not be saved. Please, try again.'));
        }
        $articles = $this->VideoCategories->Articles->find('list', ['limit' => 200]);
        $this->set(compact('galleryCategory', 'articles', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Gallery Category id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function videoCategoryEdit($id = null) {
        $this->loadModel('VideoCategories');
        $galleryCategory = $this->VideoCategories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $galleryCategory = $this->VideoCategories->patchEntity($galleryCategory, $this->request->getData());
            if ($this->VideoCategories->save($galleryCategory)) {
                $this->Flash->success(__('The Video Gallery category has been saved.'));
                return $this->redirect(['action' => 'video-category']);
            }
            $this->Flash->error(__('The gallery category could not be saved. Please, try again.'));
        }
        $articles = $this->GalleryCategories->Articles->find('list', ['limit' => 200]);
        $this->set(compact('galleryCategory', 'articles', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Gallery Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function videoCategoryDelete($id = null) {
        $this->loadModel('VideoCategories');
        $this->request->allowMethod(['post', 'delete']);
        $galleryCategory = $this->VideoCategories->get($id);
        if ($this->VideoCategories->delete($galleryCategory)) {
            $this->Flash->success(__('The Video gallery category has been deleted.'));
        } else {
            $this->Flash->error(__('The gallery Video category could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'videoCategory']);
        
    }

}
