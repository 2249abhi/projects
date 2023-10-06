<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * Banners Controller
 *
 * @property \App\Model\Table\BannersTable $Banners
 *
 * @method \App\Model\Entity\Banner[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BannersController extends AppController
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
        $search_condition = array();
        $userArray = $this->Auth->user();
        if ($userArray['role_id'] == 3) {
            $search_condition[] = "banners.status = 3";
        } elseif ($userArray['role_id'] == 2) {
            $search_condition[] = "banners.status = 4";
        } elseif ($userArray['role_id'] == 4) {
            $search_condition[] = "banners.status = 2";
        } else {
            $search_condition = array();
        }
        $bannerCategories = $this->Banners->BannerCategories->find('list', ['limit' => 200]);

        if($this->request->is('post')||$this->request->is('get')) {
            $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
            $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;

            if (!empty($this->request->query['title'])) {
                $bannerTitle = trim($this->request->query['title']);
                $this->set('bannerTitle', $bannerTitle);
                $search_condition[] = "Banners.title like '%" . $bannerTitle . "%'";
            }
            if (!empty($this->request->query['banner_category_id'])) {
                $catId = trim($this->request->query['banner_category_id']);
                $this->set('bannerCatId', $catId);
                $search_condition[] = "Banners.banner_category_id = '" . $catId . "'";
            }
            if (isset($this->request->query['status']) && $this->request->query['status'] !='') {
                $status = trim($this->request->query['status']);
                $this->set('status', $status);
                $search_condition[] = "Banners.status = '" . $status . "'";
            }

            if(!empty($search_condition)){
                $searchString = implode(" AND ",$search_condition);
            } else {
                $searchString = '';
            }

            $bannerQuery = $this->Banners->find('all', [
                'contain' => ['Users','BannerCategories'],
                'order' => ['Banners.id' => 'desc'],
                'conditions' => [$searchString]
            ]);

            $this->paginate = ['limit' => 10];
            $banners = $this->paginate($bannerQuery);
            $this->set('selectedLen', $page_length);
            $this->set(compact('banners','bannerCategories'));
        }
    }

    /**
     * View method
     *
     * @param string|null $id Banner id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $banner = $this->Banners->get($id, [
            'contain' => ['BannerCategories', 'Users']
        ]);

        $this->set('banner', $banner);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $banner = $this->Banners->newEntity();
        if ($this->request->is('post')) {
            $banner = $this->Banners->patchEntity($banner, $this->request->getData());
            if ($this->request->getData('banner_image')) {
                if($this->request->getData('banner_image')['name']!=''){
                    $bannerImage = $this->uploadFiles('banners', $this->request->getData('banner_image'));
                    $banner->banner_image = $bannerImage['filename'];
                }
            } else {
                $banner->banner_image = null;
            }
            if ($this->request->getData('banner_video')) {
                if($this->request->getData('banner_video')['name']!=''){
                    $bannerVideo = $this->uploadFiles('banners', $this->request->getData('banner_video'));
                    $banner->banner_video = $bannerVideo['filename'];
                }
            } else {
                $banner->banner_video = null;
            }
            
			$banner->status = 1; 
            if ($this->Banners->save($banner)) {
                $this->Flash->success(__('Changes saved successfully.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The banner could not be saved. Please, try again.'));
        }
        $bannerCategories = $this->Banners->BannerCategories->find('list', ['limit' => 200]);
        $this->set(compact('banner', 'bannerCategories'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Banner id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $banner = $this->Banners->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
			
            $banner = $this->Banners->patchEntity($banner, $this->request->getData());
            if ($this->request->getData('banner_image')) {
                if($this->request->getData('banner_image')['name']!=''){
                    $bannerImage = $this->uploadImage('banners', $this->request->getData('banner_image'));
                    $banner->banner_image = $bannerImage['filename'];
					
                } else {
                    $banner->banner_image = $this->request->getData('old_banner_image');
                }
            } else {
                $banner->banner_image = null;
            }
            if ($this->request->getData('banner_video')) {
                if($this->request->getData('banner_video')['name']!=''){
                    $bannerVideo = $this->uploadFiles('banners', $this->request->getData('banner_video'));
                    $banner->banner_video = $bannerVideo['filename'];
                } else {
                    $banner->banner_video = $this->request->getData('old_banner_video');
                }
            } else {
                $banner->banner_video = null;
            }

                $banner->status = 1;

			//echo "<pre>"; print_r($banner); exit;
            if ($this->Banners->save($banner)) {
                $this->Flash->success(__('Changes saved successfully.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The banner could not be saved. Please, try again.'));
        }
        $bannerCategories = $this->Banners->BannerCategories->find('list', ['limit' => 200]);
        $users = $this->Banners->Users->find('list', ['limit' => 200]);
        $this->set(compact('banner', 'bannerCategories', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Banner id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $userArray = $this->Auth->user();
        $this->request->allowMethod(['post', 'delete']);
        $banner = $this->Banners->get($id);
        if ($userArray['id'] == $banner['user_id'] || $userArray['role_id'] == 1) {
            if ($this->Banners->delete($banner)) {
                $this->Flash->success(__('The banner has been deleted.'));
            } else {
                $this->Flash->error(__('The banner could not be deleted. Please, try again.'));
            }
        } else {
            $this->Flash->error(__('Oops, you are not authorized to delete this banner.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
