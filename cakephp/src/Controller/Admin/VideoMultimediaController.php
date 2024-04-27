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
class VideoMultimediaController extends AppController {

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
            $search_condition[] = "VideoMultimedia.multimedia_category_id like '%" . $multimedia_category_id . "%'";
        }
        /*if (!empty($this->request->getQuery('multimedia_subcategory_id'))) {
            $multimedia_subcategory_id = trim($this->request->getQuery('multimedia_subcategory_id'));
            $this->set('multimedia_subcategory_id', $multimedia_subcategory_id);
            $search_condition[] = "VideoMultimedia.multimedia_subcategory_id like '%" . $multimedia_subcategory_id . "%'";
        }*/
        if (!empty($this->request->getQuery('title'))) {
            $title = trim($this->request->getQuery('title'));
            $this->set('title', $title);
            $search_condition[] = "VideoMultimedia.title like '%" . $title . "%'";
        }

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }

        $this->paginate = [
            'contain' => ['MultimediaCategories']
        ];
        $galleryCategories = $this->paginate($this->VideoMultimedia->find()->where([$searchString]));

        $this->loadModel('MultimediaCategories');
        $category = $this->MultimediaCategories->find('list')->where(['status'=>1, 'type'=>'2'])->toArray();
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
        $category = $this->MultimediaCategories->find('list')->where(['status'=>1, 'type'=>2])->toArray();
        
        $galleryCategory = $this->VideoMultimedia->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            //echo "<pre>";print_r($data);exit();
            $galleryCategory = $this->VideoMultimedia->patchEntity($galleryCategory, $this->request->getData());
            
            if ($this->request->getData('image')) {
                if($this->request->getData('image')['name']!=''){
                    $bannerImage = $this->uploadFiles('Multimedia', $this->request->getData('image'));
                    $galleryCategory->category_image = $bannerImage['filename'];
                }
            } else {
                //$galleryCategory->category_image = $category['category_image'];
            }

            $galleryCategory->multimedia_category_id  = $data['multimedia_category_id'];
            $galleryCategory->video_link  = $data['video_link'];
            $galleryCategory->title  = $data['title'];
            $galleryCategory->event_date = date('Y-m-d', strtotime($data['event_date']));
            $galleryCategory->created = date('Y-m-d');
            //echo "<pre>";print_r($galleryCategory);exit();
            
            if ($this->VideoMultimedia->save($galleryCategory)) {
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
            //echo "<pre>"; print_r($subcategory);exit;
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
        $category = $this->MultimediaCategories->find('list')->where(['status'=>1, 'type'=>2])->toArray();
        
        $videoMultimedia = $galleryCategory = $this->VideoMultimedia->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data = $this->request->getData();
            //echo "<pre>";print_r($data);exit();
            $galleryCategory = $this->VideoMultimedia->patchEntity($galleryCategory, $this->request->getData());
            
            if ($this->request->getData('image')) {
                if($this->request->getData('image')['name']!=''){
                    $bannerImage = $this->uploadFiles('Multimedia', $this->request->getData('image'));
                    $galleryCategory->category_image = $bannerImage['filename'];
                }
            } else {
                $galleryCategory->category_image = $videoMultimedia['category_image'];
            }
            
            $galleryCategory->title  = $data['title'];
            $galleryCategory->multimedia_category_id  = $data['multimedia_category_id'];
            $galleryCategory->video_link  = $data['video_link'];
            $galleryCategory->event_date = date('Y-m-d', strtotime($data['event_date']));
            $galleryCategory->created = date('Y-m-d');
            //echo "<pre>";print_r($galleryCategory);exit();

            if ($this->VideoMultimedia->save($galleryCategory)) {
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
        $galleryCategory = $this->MultimediaSubcategories->get($id);
        if ($this->MultimediaSubcategories->delete($galleryCategory)) {
            $this->Flash->success(__('The Multimedia Sub category has been deleted.'));
        } else {
            $this->Flash->error(__('The Multimedia Sub category could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }


}
