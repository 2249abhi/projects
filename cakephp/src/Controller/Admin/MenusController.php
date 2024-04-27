<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Utility\Hash;

/**
 * Menus Controller
 *
 * @property \App\Model\Table\MenusTable $Menus
 *
 * @method \App\Model\Entity\Menu[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MenusController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    private $menuRedirection = [
        'self'       => 'Self',
        'new-window' => 'New Window',
    ];

    private $menuType = [
        'custom' => 'Custom',
        'object' => 'Article',
        'internal' => 'Internal',
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index($menu_region_id = null)
    {
        if (empty($menu_region_id)) {
            return $this->redirect(['controller' => 'MenuRegions', 'action' => 'index']);
        }

        $search_condition = array();
        $page_length = !empty($this->request->query['page_length']) ? $this->request->query['page_length'] : 10;
        $page = !empty($this->request->query['page']) ? $this->request->query['page'] : 1;
        if (!empty($this->request->query['title'])) {
            $title = trim($this->request->query['title']);
            $this->set('title', $title);
            $search_condition[] = "Menus.menu_title like '%" . $title . "%'";
        }

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }
        if ($page_length != 'all' && is_numeric($page_length)) {
            $this->paginate = [
                'limit' => $page_length,
            ];
        }

        
        $this->paginate = [
            'contain'    => ['Articles'],
            'conditions' => ['Menus.menu_region_id' => $menu_region_id,$searchString],
            //'order'      => ['Menus.lft' => 'ASC'],
        ];
        $menus           = $this->paginate($this->Menus);
        $menuType        = $this->menuType;
        $menuRedirection = $this->menuRedirection;
        $this->set('selectedLen', $page_length);
        $this->set(compact('menus', 'menu_region_id', 'menuType', 'menuRedirection'));
    }

    /**
     * View method
     *
     * @param string|null $id Menu id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($menu_region_id = null, $id = null)
    {
        if (empty($menu_region_id)) {
            return $this->redirect(['controller' => 'MenuRegions', 'action' => 'index']);
        }
        $menu = $this->Menus->get($id, [
            'contain' => ['MenuRegions', 'Articles'],
        ]);
        $menuType        = $this->menuType;
        $menuRedirection = $this->menuRedirection;
        $this->set(compact('menu', 'menu_region_id', 'menuType', 'menuRedirection'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($menu_region_id = null)
    {
        if(empty($menu_region_id)){
            return $this->redirect(['controller' => 'MenuRegions', 'action' => 'index']);
        }
        $menu                 = $this->Menus->newEntity();
        $menu->menu_region_id = $menu_region_id;
        if ($this->request->is('post')) {
            $data              = $this->request->getData();
            $menu_translations = [];
            if (isset($data['menu_translations'])) {
                $menu_translations = $data['menu_translations'];
                unset($data['menu_translations']);
            }
            $menu              = $this->Menus->patchEntity($menu, $data);
            $menu->parent_id   = ($menu->parent_id) ? $menu->parent_id : 0;
            $menu->object_type = ($menu->menu_type == 'object') ? 'article' : '';
            $menu->object_id   = ($menu->menu_type == 'object') ? $menu->object_id : 0;
            $menu->custom_link = ($menu->menu_type == 'custom') ? $menu->custom_link : '';
            $menu->internal_link = ($menu->menu_type == 'internal') ? $menu->internal_link : '';
            if ($this->Menus->save($menu)) {
                $menu_id = $menu->id;
                if (!empty($menu_translations)) {
                    $this->loadModel('MenuTranslations');
                    foreach ($menu_translations as $key => $_translation) {
                        if (empty($_translation['id'])) {
                            unset($menu_translations[$key]['id']);
                        }
                        $menu_translations[$key]['menu_id'] = $menu_id;
                    }
                    $menuTranslation  = $this->MenuTranslations->newEntity();
                    $menuTranslation  = $this->MenuTranslations->patchEntities($menuTranslation, $menu_translations);                    
                    $menuTranslations = $this->MenuTranslations->saveMany($menuTranslation);
                }
                $this->Flash->success(__('The menu has been saved.'));

                return $this->redirect(['action' => 'index', $menu->menu_region_id]);
            }
            $this->Flash->error(__('The menu could not be saved. Please, try again.'));
        }
        $menuRegions     = $this->Menus->MenuRegions->find('list');
        $menuParent      = $this->Menus->find('treeList', ['spacer' => ' - '])->where(['menu_region_id' => $menu->menu_region_id]);
        $menuType        = $this->menuType;
        $menuRedirection = $this->menuRedirection;
        $menuLanguages   = $this->languages;
        //Get Articales
        $this->loadModel('Articles');
        $articles = $this->Articles->find('list');
        $this->set(compact('menu', 'menuRegions', 'menuParent', 'menuType', 'menuRedirection', 'menuLanguages', 'articles'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Menu id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($menu_region_id = null, $id = null)
    {
        if (empty($menu_region_id)) {
            return $this->redirect(['controller' => 'MenuRegions', 'action' => 'index']);
        }
        $menu = $this->Menus->get($id, [
            'contain' => ['MenuTranslations'],
        ]);
        $menu['menu_translations'] = Hash::combine($menu['menu_translations'], '{n}.language_id', '{n}');
        $menu->menu_region_id      = $menu_region_id;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data              = $this->request->getData();
            $menu_translations = [];
            if (isset($data['menu_translations'])) {
                $menu_translations = $data['menu_translations'];
                unset($data['menu_translations']);
            }
            $menu              = $this->Menus->patchEntity($menu, $data);
            $menu->object_type = ($menu->menu_type == 'object') ? 'article' : '';
            $menu->object_id   = ($menu->menu_type == 'object') ? $menu->object_id : 0;
            $menu->custom_link = ($menu->menu_type == 'custom') ? $menu->custom_link : '';
            $menu->internal_link = ($menu->menu_type == 'internal') ? $menu->internal_link : '';
            if ($this->Menus->save($menu)) {
                $menu_id = $menu->id;
                if (!empty($menu_translations)) {
                    $this->loadModel('MenuTranslations');
                    foreach ($menu_translations as $key => $_translation) {
                        if (empty($_translation['id'])) {
                            unset($menu_translations[$key]['id']);
                        }
                        $menu_translations[$key]['menu_id'] = $menu_id;
                    }
                    $menuTranslation  = $this->MenuTranslations->newEntity();
                    $menuTranslation  = $this->MenuTranslations->patchEntities($menuTranslation, $menu_translations);
                    $menuTranslations = $this->MenuTranslations->saveMany($menuTranslation);
                }
                $this->Flash->success(__('The menu has been saved.'));

                return $this->redirect(['action' => 'index', $menu->menu_region_id]);
            }
            $this->Flash->error(__('The menu could not be saved. Please, try again.'));
        }
        $menuRegions = $this->Menus->MenuRegions->find('list');
        //Get Children Menus
        $getChildren = $this->Menus->find('children', ['fields' => ['id'], 'for' => $menu->id])
            ->enableHydration(false)->toArray();
        $where = ['id !=' => $menu->id];
        if (!empty($getChildren)) {
            $getChildren        = Hash::extract($getChildren, '{n}.id');
            $where['id NOT IN'] = $getChildren;
        }
        //Get Parent Menus
        $menuParent      = $this->Menus->find('treeList', ['spacer' => ' - '])->where($where);
        $menuType        = $this->menuType;
        $menuRedirection = $this->menuRedirection;
        $menuLanguages   = $this->languages;
        //Get Articales
        $this->loadModel('Articles');
        $articles = $this->Articles->find('list');
        $this->set(compact('menu', 'menuRegions', 'menuParent', 'menuType', 'menuRedirection', 'menuLanguages', 'articles'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Menu id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($menu_region_id = null, $id = null)
    {
        if (empty($menu_region_id)) {
            return $this->redirect(['controller' => 'MenuRegions', 'action' => 'index']);
        }
        $this->request->allowMethod(['post', 'delete']);
        $menu = $this->Menus->get($id);
        //Change Parent
        $parent_id = ($menu->parent_id) ? $menu->parent_id : 0;
        $pMenus    = $this->Menus->find('all')->where(['parent_id' => $id]);
        if (!empty($pMenus)) {
            foreach ($pMenus as $pMenu) {
                $pMenu = $this->Menus->patchEntity($pMenu, ['parent_id' => $parent_id]);
                $pMenu = $this->Menus->save($pMenu);
            }
        }
        $menu = $this->Menus->get($id);
        //Delete
        if ($this->Menus->delete($menu)) {
            //Delete Translations
            $this->loadModel('MenuTranslations');
            $this->MenuTranslations->deleteAll(['menu_id' => $id]);
            $this->Flash->success(__('The menu has been deleted.'));
        } else {
            $this->Flash->error(__('The menu could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index', $menu_region_id]);
    }
}
