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
class PhotoMultimediaController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {

        $search_condition = array();
        if (!empty($this->request->getQuery('multimedia_category_id'))) {
            $multimedia_category_id = trim($this->request->getQuery('multimedia_category_id'));
            $this->set('multimedia_category_id', $multimedia_category_id);
            $search_condition[] = "PhotoMultimedia.multimedia_category_id like '%" . $multimedia_category_id . "%'";
        }
        if (!empty($this->request->getQuery('multimedia_subcategory_id'))) {
            $multimedia_subcategory_id = trim($this->request->getQuery('multimedia_subcategory_id'));
            $this->set('multimedia_subcategory_id', $multimedia_subcategory_id);
            $search_condition[] = "PhotoMultimedia.multimedia_subcategory_id like '%" . $multimedia_subcategory_id . "%'";
        }
        if (!empty($this->request->getQuery('title'))) {
            $title = trim($this->request->getQuery('title'));
            $this->set('title', $title);
            $search_condition[] = "PhotoMultimedia.title like '%" . $title . "%'";
        }

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }
        
        $this->paginate = [
            'contain' => ['MultimediaCategories', 'MultimediaSubcategories']
        ];
        $galleryCategories = $this->paginate($this->PhotoMultimedia->find()->where([$searchString]));

        $this->loadModel('MultimediaCategories');
        $category = $this->MultimediaCategories->find('list')->where(['status'=>1, 'type'=>'1'])->toArray();
        $this->set(compact('galleryCategories', 'category'));
        //echo "<pre>"; print_r($galleryCategories);exit; 
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $this->loadModel('MultimediaCategories');
        $category = $this->MultimediaCategories->find('list')->where(['status'=>1, 'type'=>'1'])->toArray();
        
        $galleryCategory = $this->PhotoMultimedia->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            //echo "<pre>";print_r($data);exit();
            $galleryCategory = $this->PhotoMultimedia->patchEntity($galleryCategory, $this->request->getData());
            
            if ($this->request->getData('image')) {
                if($this->request->getData('image')['name']!=''){
                    $bannerImage = $this->uploadFiles('Multimedia', $this->request->getData('image'));
                    $galleryCategory->category_image = $bannerImage['filename'];
                }
            } else {
                //$galleryCategory->category_image = $data['image_old'];
            }

            $galleryCategory->multimedia_category_id  = $data['multimedia_category_id'];
            $galleryCategory->multimedia_subcategory_id  = $data['multimedia_subcategory_id'];
            $galleryCategory->event_date = date('Y-m-d', strtotime($data['event_date']));
            $galleryCategory->created = date('Y-m-d');
            //echo "<pre>";print_r($galleryCategory);exit();
            
            if ($this->PhotoMultimedia->save($galleryCategory)) {
                $this->Flash->success(__('The Multimedia Sub category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Multimedia Sub category could not be saved. Please, try again.'));
        }
        $this->set(compact('galleryCategory', 'category', 'subcategory'));
    }

    public function getSubCategory(){

        if ($this->request->is(['ajax'])) {
            $data = $this->request->data;
            $this->loadModel('MultimediaSubcategories');
            $subcategory = $this->MultimediaSubcategories->find('list')->where(['status'=>1, 'multimedia_category_id'=>$data['category_id']])->toArray();
            echo "<option value =''>Select Category</value>";
            foreach ($subcategory as $key => $value) {
                echo "<option value = $key>".$value."</value>";
            }
        }
        exit();
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
        $this->loadModel('MultimediaCategories');
        $category = $this->MultimediaCategories->find('list')->where(['status'=>1, 'type'=>'1'])->toArray();
        
        $galleryCategory = $this->PhotoMultimedia->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data = $this->request->getData();
            //echo "<pre>";print_r($data);exit();
            $galleryCategory = $this->PhotoMultimedia->patchEntity($galleryCategory, $this->request->getData());
            
            if ($this->request->getData('image')) {
                if($this->request->getData('image')['name']!=''){
                    $bannerImage = $this->uploadFiles('Multimedia', $this->request->getData('image'));
                    $galleryCategory->category_image = $bannerImage['filename'];
                }
            } else {
                $galleryCategory->category_image = $data['image_old'];
            }

            $galleryCategory->multimedia_category_id  = $data['multimedia_category_id'];
            $galleryCategory->multimedia_subcategory_id  = $data['multimedia_subcategory_id'];
            $galleryCategory->event_date = date('Y-m-d', strtotime($data['event_date']));
            $galleryCategory->created = date('Y-m-d');
            //echo "<pre>";print_r($galleryCategory);exit();

            if ($this->PhotoMultimedia->save($galleryCategory)) {
                $this->Flash->success(__('The Photo has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Multimedia category could not be saved. Please, try again.'));
        }
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
        //print_r($id);exit();
        $this->loadModel('PhotoMultimedia');
        $galleryCategory = $this->PhotoMultimedia->get($id);
        if ($this->PhotoMultimedia->delete($galleryCategory)) {
            $this->Flash->success(__('The Multimedia Photo has been deleted.'));
        } else {
            $this->Flash->error(__('The Multimedia Photo could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }


}
