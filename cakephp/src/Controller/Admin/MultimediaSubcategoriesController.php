<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Utility\Hash;

/**
 * MultimediaSubcategories Controller
 *
 * @property \App\Model\Table\MultimediaSubcategoriesTable $MultimediaSubcategories
 *
 * @method \App\Model\Entity\GalleryCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MultimediaSubcategoriesController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => ['MultimediaCategories']
        ];
        $galleryCategories = $this->paginate($this->MultimediaSubcategories);
        $this->set(compact('galleryCategories'));
        //echo "<pre>"; print_r($galleryCategories);exit;
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $this->loadModel('MultimediaCategories');
        $category = $this->MultimediaCategories->find('list')->where(['status'=>1])->toArray();
        //print_r($category);exit();
        $galleryCategory = $this->MultimediaSubcategories->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $galleryCategory = $this->MultimediaSubcategories->patchEntity($galleryCategory, $data);

            if ($this->request->getData('image')) {
                if($this->request->getData('image')['name']!=''){
                    $bannerImage = $this->uploadFiles('Multimedia', $this->request->getData('image'));
                    $galleryCategory->category_image = $bannerImage['filename'];
                }
            } else {
                $galleryCategory->image = null;
            }

            $galleryCategory->multimedia_category_id = $data['category_id'];
            $galleryCategory->event_date = date('Y-m-d', strtotime($data['event_date']));
            $galleryCategory->created = date('Y-m-d');
            //echo "<pre>";print_r($galleryCategory);exit();
            if ($this->MultimediaSubcategories->save($galleryCategory)) {
                $this->Flash->success(__('The Multimedia Sub category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Multimedia Sub category could not be saved. Please, try again.'));
        }
        $this->set(compact('galleryCategory', 'category'));
    }

    /**
     * View method
     *
     * @param string|null $id Gallery Category id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $galleryCategory = $this->MultimediaSubcategories->get($id, [
            'contain' => ['Users']
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
        $multimediaSubcategories = $galleryCategory = $this->MultimediaSubcategories->get($id, [
            'contain' => []
        ]);
        $this->loadModel('MultimediaCategories');
        $category = $this->MultimediaCategories->find('list')->where(['status'=>1])->toArray();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $galleryCategory = $this->MultimediaSubcategories->patchEntity($galleryCategory, $data);

            if ($this->request->getData('category_image')) {
                if($this->request->getData('category_image')['name']!=''){
                    $bannerImage = $this->uploadFiles('Multimedia', $this->request->getData('category_image'));
                    $galleryCategory->category_image = $bannerImage['filename'];
                }
            }else{
                $galleryCategory->category_image = $multimediaSubcategories['category_image'];
            }

            $galleryCategory->multimedia_category_id = $data['multimedia_category_id'];
            $galleryCategory->event_date = date('Y-m-d', strtotime($data['event_date']));
            $galleryCategory->created = date('Y-m-d');
            //echo "<pre>";print_r($galleryCategory);exit();
            if ($this->MultimediaSubcategories->save($galleryCategory)) {
                $this->Flash->success(__('The Multimedia category has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Multimedia category could not be saved. Please, try again.'));
        }
        //echo "<pre>";print_r($galleryCategory);exit();
        $this->set(compact('galleryCategory', 'category'));
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
        $galleryCategory = $this->MultimediaSubcategories->get($id);
        if ($this->MultimediaSubcategories->delete($galleryCategory)) {
            $this->Flash->success(__('The Multimedia Sub category has been deleted.'));
        } else {
            $this->Flash->error(__('The Multimedia Sub category could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }


}
