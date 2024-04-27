<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Utility\Hash;

/**
 * Galleries Controller
 *
 * @property \App\Model\Table\GalleriesTable $Galleries
 *
 * @method \App\Model\Entity\Gallery[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GalleriesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['GalleryCategories']
        ];
        $galleries = $this->paginate($this->Galleries);

        $this->set(compact('galleries'));
        //debug($galleries).die;
    }

    /**
     * View method
     *
     * @param string|null $id Gallery id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $gallery = $this->Galleries->get($id, [
            'contain' => ['GalleryCategories']
        ]);

        $this->set('gallery', $gallery);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $gallery = $this->Galleries->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            //echo '<pre>'; print_r($data);die;
            $gallery = $this->Galleries->patchEntity($gallery, $data);
            if($data['filename']['name']!=''){
                $fileName = $this->uploadImage('galleries', $data['filename']);
                $gallery->filename  = $fileName['filename'];
                $gallery->filemime  = $fileName['type'];
                $gallery->filesize  = $fileName['size'];
            }
            $gallery->gallery_category_id  = $data['gallery_category_id'];                
            $gallery->title  = $data['title'];
            $gallery->sort_order  = $data['sort_order'];
            $gallery->description  = $data['description'];
            $gallery->is_home  = $data['is_home'];
            $gallery->is_landing  = $data['is_landing'];
            $gallery->status  = $data['status'];//echo '<pre>'; print_r($gallery);die;
            if ($this->Galleries->save($gallery)) {
                $this->Flash->success(__('The gallery photo has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The gallery could not be saved. Please, try again.'));
        }
        $galleryCategories = $this->Galleries->GalleryCategories->find('list', ['limit' => 200]);
        $this->set(compact('gallery', 'galleryCategories'));
    }
    /**
     * Edit method
     * @param string|null $id Gallery id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $gallery = $this->Galleries->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            $gallery = $this->Galleries->patchEntity($gallery, $data);//echo '<pre>'; print_r($gallery);
            if($data['filename']['name']!=''){
                $fileName = $this->uploadImage('galleries', $data['filename']);
                $gallery->filename  = $fileName['filename'];
                $gallery->filemime  = $fileName['type'];
                $gallery->filesize  = $fileName['size'];
                $gallery->gallery_category_id  = $data['gallery_category_id'];                
                $gallery->title  = $data['title'];
                //print_r($gallery);die;
            } else {
                $gallery->filename  = $data['old_filename'];
                $gallery->filemime  = $data['old_filemime'];
                $gallery->filesize  = $data['old_filesize'];
                $gallery->gallery_category_id  = $data['gallery_category_id'];                
                $gallery->title  = $data['title'];
            }//echo '<pre>'; print_r($gallery);die;
            if ($this->Galleries->save($gallery)) {
                $this->Flash->success(__('The gallery has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The gallery could not be saved. Please, try again.'));
        }
        $galleryCategories = $this->Galleries->GalleryCategories->find('list', ['limit' => 200]);
        $this->set(compact('gallery', 'galleryCategories'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Gallery id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $gallery = $this->Galleries->get($id);
        if ($this->Galleries->delete($gallery)) {
            $this->Flash->success(__('The gallery has been deleted.'));
        } else {
            $this->Flash->error(__('The gallery could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
