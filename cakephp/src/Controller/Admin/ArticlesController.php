<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Utility\Hash;
use Cake\View\Exception\MissingTemplateException;
use Cake\Core\Configure;
use Cake\Filesystem\File;
use Cake\ORM\TableRegistry;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 *
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    private $linkRedirection = [
        'self'       => 'Self',
        'new-window' => 'New Window',
    ];

    private $linkType = [
        'custom' => 'Custom',
        'article' => 'Article',
        'internal' => 'Internal',
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $search_condition = array();
        if (!empty($this->request->getQuery('title'))) {
            $title = trim($this->request->getQuery('title'));
            $this->set('title', $title);
            $search_condition[] = "Articles.title like '%" . $title . "%'";
        }
        if (!empty($this->request->getQuery('sub_title'))) {
            $subTitle = trim($this->request->getQuery('sub_title'));
            $this->set('sub_title', $subTitle);
            $search_condition[] = "Articles.subtitle like '%" . $subTitle . "%'";
        }
        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }
        $postQuery = $this->Articles->find('all', [
            'contain' => ['Users'],
            'order' => ['Articles.id' => 'desc'],
            'conditions' => [$searchString]
        ]);
        $this->paginate = ['limit' => 10];
        $articles = $this->paginate($postQuery);
        $this->set(compact('articles'));

    }

    /**
     * View method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $article = $this->Articles->get($id, [
            'contain' => ['ArticleTranslations','ArticleLinks']
        ]);

        $this->set('article', $article);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $data                 = $this->request->getData();
            $article_translations = [];
            if (isset($data['article_translations'])) {
                $article_translations = $data['article_translations'];
                unset($data['article_translations']);
            }
            if (isset($data['article_links'])) {
                $article_links = $data['article_links'];
                unset($data['article_links']);
            }
            if (isset($data['field_upload_file'])) {
                $field_upload_file = $data['field_upload_file'];
                unset($data['field_upload_file']);
            }
            
            $article->created_at = date('Y-m-d H:i:s');
            $article             = $this->Articles->patchEntity($article, $data);
            if($data['header_image']['name']!=''){
                $headerImage = $this->uploadImage('article', $data['header_image'],'header_image');
                $article->header_image = $headerImage['filename'];
            }
            if ($this->Articles->save($article)) {
                $article_id = $article->id;
                if (!empty($article_translations)) {
                    $this->loadModel('ArticleTranslations');
                    foreach ($article_translations as $key => $_translation) {
                        if (empty($_translation['id'])) {
                            unset($article_translations[$key]['id']);
                        }
                        $article_translations[$key]['article_id'] = $article_id;
                    }
                    $articleTranslation  = $this->ArticleTranslations->newEntity();
                    $articleTranslation  = $this->ArticleTranslations->patchEntities($articleTranslation, $article_translations);
                    $articleTranslations = $this->ArticleTranslations->saveMany($articleTranslation);
                }
                if (!empty($article_links)) {
                    $this->loadModel('ArticleLinks');
                    foreach ($article_links as $key => $_links) {
                        if (empty($_links['id'])) {
                            unset($article_links[$key]['id']);
                        }
                        $article_links[$key]['article_id'] = $article_id;
                        $article_links[$key]['custom_link'] = ($_links['link_type'] == 'custom') ? $_links['custom_link'] : '';
                        $article_links[$key]['internal_link'] = ($_links['link_type'] == 'internal') ? $_links['internal_link'] : '';
                    }
                    $articleLinks  = $this->ArticleLinks->newEntity();
                    $articleLinks  = $this->ArticleLinks->patchEntities($articleLinks, $article_links);
                    $articleLinkss = $this->ArticleLinks->saveMany($articleLinks);
                }
                if (!empty($field_upload_file)) {
                    $this->loadModel('ArticleImages');
                    foreach ($field_upload_file as $key => $_upload_file) {
                        if (empty($_upload_file['id'])) {
                            unset($field_upload_file[$key]['id']);
                        }
                        $field_upload_file[$key]['article_id'] = $article_id;
                    }
                    $articleImage  = $this->ArticleImages->newEntity();
                    $articleImage  = $this->ArticleImages->patchEntities($articleImage, $field_upload_file);
                    $articleImages = $this->ArticleImages->saveMany($articleImage);
                }
                $this->request->session()->delete('file_session');
                $this->Flash->success(__('The article has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The article could not be saved. Please, try again.'));
        }
        $linkType        = $this->linkType;
        $linkRedirection = $this->linkRedirection;
        $articleLanguages  = $this->languages;
        $system_languge_id = SYSTEM_LANGUAGE_ID;
        $articles = $this->Articles->find('list');        
        $this->loadModel('ArticleImages');
        
        $sessionFiles = $this->request->session()->read('file_session');
        $sessionFilesData = [];
        if(!empty($sessionFiles)){
            $sessionFilesData = $this->ArticleImages->find('all') ->where(['changed IN' => $sessionFiles])->enableHydration(false)->toArray();
        }
        $this->set(compact('article', 'articleLanguages', 'system_languge_id','linkType','linkRedirection','articles','sessionFilesData'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $article = $this->Articles->get($id, [
            'contain' => ['ArticleTranslations','ArticleLinks','ArticleImages'],
        ]);
        $article['article_translations'] = Hash::combine($article['article_translations'], '{n}.language_id', '{n}');
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data                 = $this->request->getData();
            $article_translations = [];
            if (isset($data['article_translations'])) {
                $article_translations = $data['article_translations'];
                unset($data['article_translations']);
            }
            if (isset($data['article_links'])) {
                $article_links = $data['article_links'];
                unset($data['article_links']);
            }
            if (isset($data['field_upload_file'])) {
                $field_upload_file = $data['field_upload_file'];
                unset($data['field_upload_file']);
            }
            $article->modified_at = date('Y-m-d H:i:s');
            $article              = $this->Articles->patchEntity($article, $data);
            if($data['header_image']['name']!=''){
                $headerImage = $this->uploadImage('article', $data['header_image'],'header_image');
                $article->header_image = $headerImage['filename'];
            } else {
                $article->header_image = $data['old_header_image'];
            }
            if ($this->Articles->save($article)) {
                $article_id = $article->id;
                if (!empty($article_translations)) {
                    $this->loadModel('ArticleTranslations');
                    foreach ($article_translations as $key => $_translation) {
                        if (empty($_translation['id'])) {
                            unset($article_translations[$key]['id']);
                        }
                        $article_translations[$key]['article_id'] = $article_id;
                    }
                    $articleTranslation  = $this->ArticleTranslations->newEntity();
                    $articleTranslation  = $this->ArticleTranslations->patchEntities($articleTranslation, $article_translations);
                    $articleTranslations = $this->ArticleTranslations->saveMany($articleTranslation);
                    $this->Articles->articleCache();
                }
                if (!empty($article_links)) {
                    $this->loadModel('ArticleLinks');
                    foreach ($article_links as $key => $_links) {
                        if (empty($_links['id'])) {
                            unset($article_links[$key]['id']);
                        }
                        $article_links[$key]['article_id'] = $article_id;
                        $article_links[$key]['custom_link'] = ($_links['link_type'] == 'custom') ? $_links['custom_link'] : '';
                        $article_links[$key]['internal_link'] = ($_links['link_type'] == 'internal') ? $_links['internal_link'] : '';
                    }
                    $articleLinks  = $this->ArticleLinks->newEntity();
                    $articleLinks  = $this->ArticleLinks->patchEntities($articleLinks, $article_links);
                    $articleLinkss = $this->ArticleLinks->saveMany($articleLinks);
                }
                if (!empty($field_upload_file)) {
                    $this->loadModel('ArticleImages');
                    foreach ($field_upload_file as $key => $_upload_file) {
                        if (empty($_upload_file['id'])) {
                            unset($field_upload_file[$key]['id']);
                        }
                        $field_upload_file[$key]['article_id'] = $article_id;
                        $field_upload_file[$key]['changed'] = REQUEST_TIME;
                    }
                    $articleImage  = $this->ArticleImages->newEntity();
                    $articleImage  = $this->ArticleImages->patchEntities($articleImage, $field_upload_file);
                    $articleImages = $this->ArticleImages->saveMany($articleImage);
                }
                $this->Articles->articleCache();
                $this->Flash->success(__('The article has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The article could not be saved. Please, try again.'));
        }
        $linkType        = $this->linkType;
        $linkRedirection = $this->linkRedirection;
        $articleLanguages  = $this->languages;
        $system_languge_id = SYSTEM_LANGUAGE_ID;
        $articles = $this->Articles->find('list');
        $this->set(compact('article', 'articleLanguages', 'system_languge_id','linkType','linkRedirection','articles'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            $this->loadModel('ArticleTranslations');
            $this->ArticleTranslations->deleteAll(['article_id' => $id]);
            $this->Flash->success(__('The article has been deleted.'));
        } else {
            $this->Flash->error(__('The article could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getOptions($value='')
    {
        $this->viewBuilder()->layout('ajax');
        $param = $_POST['param'];
        $arrayData = array();
        if($param=='article') {
            $arrayData = $this->Articles->find('list');
        }
        $this->set('arrayData',$arrayData);
    }

    public function autoUploadFiles()
    {
        $this->viewBuilder()->layout('ajax');
        $data   = $this->request->getData();
        $result = '';
        $sessionFilesData = [];
        if($data['article_files']){
            $session = $this->request->session();
            $this->loadModel('ArticleImages');
            $articleImage = $this->ArticleImages->newEntity();            
            $uploadedFiles = $this->uploadFiles('article', $data['article_files'],'articlefiles');
            if (empty($uploadedFiles['errors'])) {
                if (!empty($data['article_id'])) {
                    $articleImage->article_id = $data['article_id'];
                }
                $articleImage = $this->ArticleImages->patchEntity($articleImage, $data);
                $articleImage->filename = $uploadedFiles['filename'];
                $articleImage->fileurl = $uploadedFiles['url'];
                $articleImage->filemime = $uploadedFiles['type'];
                $articleImage->filesize = $uploadedFiles['size'];
                $articleImage->user_id = $this->Auth->user('id');
                $articleImage->created = REQUEST_TIME;
                $articleImage->changed = REQUEST_TIME;

                if($saveArticleImage = $this->ArticleImages->save($articleImage)){
                    if (empty($data['article_id'])) {
                        if (empty($session->read('file_session'))) {
                            $session_array = array();
                        } else {
                            $session_array = $session->read('file_session');
                        }
                        array_push($session_array,$saveArticleImage->changed);
                        $session->write('file_session',$session_array);
                        //$session->delete('file_session');
                        $sessionFiles = $session->read('file_session');
                        $sessionFilesData = $this->ArticleImages->find('all') ->where(['changed IN' => $sessionFiles])->enableHydration(false)->toArray();
                    } else {
                        $sessionFilesData = $this->ArticleImages->find('all') ->where(['article_id' =>$data['article_id']])->enableHydration(false)->toArray();
                    }
                }
            } else {
                echo "error|".$uploadedFiles['errors'];
            }
        }
        $this->set('sessionFilesData',$sessionFilesData);
    }

    public function deleteArticleImage($value='')
    {
        $this->viewBuilder()->layout('ajax');
        $this->loadModel('ArticleImages');
        $data   = $this->request->getData();
        $fileData = $this->ArticleImages->get($data['id']);
        $fileName = $fileData->filename;
        if ($this->ArticleImages->delete($fileData)) {        
            $file = new File(UPLOAD_FILE . 'article/articlefiles/'.$fileName, false, 0777);
            if ($file->exists()) {
                if($file->delete()) {
                    echo "success";
                }
            }
        }
        exit();
    }

    public function deleteRow()
    {
        $this->viewBuilder()->setLayout('ajax');
        $row_id         = $_POST['id'];
        $table_name     = $_POST['table_name'];
        $custumTable    = TableRegistry::getTableLocator()->get($table_name);
        $removeQuery    = $custumTable->get($row_id);
        if($custumTable->delete($removeQuery)){
            echo 'removed';            
        }
        exit;  
    }
}
