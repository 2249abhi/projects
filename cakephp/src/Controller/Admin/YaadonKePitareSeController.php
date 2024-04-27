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
class YaadonKePitareSeController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->loadModel('YaadonKePitareSe');
        
        $galleryCategories = $this->paginate($this->YaadonKePitareSe->find()->order(['YaadonKePitareSe.created'=>'DESC']));
        $this->set(compact('galleryCategories'));
        //echo "<pre>"; print_r($galleryCategories);exit; 
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $this->loadModel('YaadonKePitareSe');
        $galleryCategory = $this->YaadonKePitareSe->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if($data['status']){
                $query = $this->YaadonKePitareSe->query();
                $query->update()
                      ->set(['status' => 0])
                      ->execute();  
            }

            $galleryCategory = $this->YaadonKePitareSe->patchEntity($galleryCategory,$this->request->getData());
            $galleryCategory->video_link  = $data['video_link'];
            $galleryCategory->youtube_id  = $data['youtube_id'];
            $galleryCategory->title  = $data['title'];
            $galleryCategory->event_date = date('Y-m-d', strtotime($data['event_date']));
            $galleryCategory->created = date('Y-m-d H:i:s');
            //echo "<pre>";print_r($galleryCategory);exit();
            
            if ($this->YaadonKePitareSe->save($galleryCategory)) {
                $this->Flash->success(__('The Video Yaadon ke pitare se has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Video Yaadon ke pitare se could not be saved. Please, try again.'));
        }
        $this->set(compact('galleryCategory', 'category', 'subcategory'));
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
        $this->loadModel('YaadonKePitareSe');
        
        $videoMultimedia = $galleryCategory = $this->YaadonKePitareSe->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if($data['status']){
                $query = $this->YaadonKePitareSe->query();
                $query->update()
                      ->set(['status' => 0])
                      ->execute();  
            } 
            $galleryCategory=$this->YaadonKePitareSe->patchEntity($galleryCategory, $this->request->getData());
            
            $galleryCategory->title  = $data['title'];
            $galleryCategory->video_link  = $data['video_link'];
            $galleryCategory->youtube_id  = $data['youtube_id'];
            $galleryCategory->event_date = date('Y-m-d', strtotime($data['event_date']));
            //$galleryCategory->created = date('Y-m-d H:i:s');

            //echo "<pre>";print_r($galleryCategory);exit();
            

            if ($this->YaadonKePitareSe->save($galleryCategory)) {
                $this->Flash->success(__('The Video Yaadon ke pitare se has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Video Yaadon ke pitare se could not be saved. Please, try again.'));
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
        $galleryCategory = $this->YaadonKePitareSe->get($id);
        if ($this->YaadonKePitareSe->delete($galleryCategory)) {
            $this->Flash->success(__('The Video Yaadon ke pitare se has been deleted.'));
        } else {
            $this->Flash->error(__('The Video Yaadon ke pitare se could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }


}
