<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Utility\Hash;

/**
 * MultimediaCategories Controller
 *
 * @property \App\Model\Table\MultimediaCategoriesTable $MultimediaCategories
 *
 * @method \App\Model\Entity\GalleryCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MultimediaCategoriesController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => []
        ];
        $galleryCategories = $this->paginate($this->MultimediaCategories);
        $this->set(compact('galleryCategories'));
         
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $galleryCategory = $this->MultimediaCategories->newEntity();
        if ($this->request->is('post')) {
            $galleryCategory = $this->MultimediaCategories->patchEntity($galleryCategory, $this->request->getData());

            if ($this->request->getData('image')) {
                if($this->request->getData('image')['name']!=''){
                    $bannerImage = $this->uploadFiles('Multimedia', $this->request->getData('image'));
                    $galleryCategory->category_image = $bannerImage['filename'];
                }
            } else {
                $galleryCategory->image = null;
            }

            $galleryCategory->created = date('Y-m-d');
            if ($this->MultimediaCategories->save($galleryCategory)) {
                $this->Flash->success(__('The Multimedia category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Multimedia category could not be saved. Please, try again.'));
        }
        $this->set(compact('galleryCategory'));
    }




    /**
     * View method
     *
     * @param string|null $id Gallery Category id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $galleryCategory = $this->MultimediaCategories->get($id, [
            'contain' => ['Users', 'Galleries']
        ]);
        $this->set('galleryCategory', $galleryCategory);
    }

    

    /**
     * Edit method
     *
     * @param string|null $id Gallery Category id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $multimediaCategories = $galleryCategory = $this->MultimediaCategories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            //print_r($this->request->getData());exit;
            $galleryCategory = $this->MultimediaCategories->patchEntity($galleryCategory, $this->request->getData());
            
            if ($this->request->getData('image')) {
                if($this->request->getData('image')['name']!=''){
                    $bannerImage = $this->uploadFiles('Multimedia', $this->request->getData('image'));
                    $galleryCategory->category_image = $bannerImage['filename'];
                }
            }else{
                $galleryCategory->category_image = $multimediaCategories['category_image'];
            }
            //echo "<pre>";print_r($galleryCategory);exit;
            if ($this->MultimediaCategories->save($galleryCategory)) {
                $this->Flash->success(__('The Multimedia category has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Multimedia category could not be saved. Please, try again.'));
        }
        $this->set(compact('galleryCategory'));
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
        $galleryCategory = $this->MultimediaCategories->get($id);
        if ($this->MultimediaCategories->delete($galleryCategory)) {
            $this->Flash->success(__('The Multimedia category has been deleted.'));
        } else {
            $this->Flash->error(__('The Multimedia category could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }


}
