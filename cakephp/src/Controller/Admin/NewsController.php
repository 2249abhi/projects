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
use Cake\Utility\Text;

/**
 * News Controller
 *
 * @property \App\Model\Table\NewsTable $News
 *
 * @method \App\Model\Entity\News[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NewsController extends AppController
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
		if (!empty($this->request->getQuery('title'))) {
            $postTitle = trim($this->request->getQuery('title')); 
            $this->set('title', $postTitle);
            $search_condition[] = "News.title like '%" . $postTitle . "%'";
        }
		
		if (isset($this->request->query['status']) && $this->request->getQuery('status') !='') {
            $status = trim($this->request->getQuery('status'));
            $this->set('status', $status);
            $search_condition[] = "News.status = '" . $status . "'";
        }

        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }
		//pr($search_condition); die;
        $postQuery = $this->News->find('all', [
            'contain' => ['Users'],
            'order' => ['News.id' => 'desc'],
			'conditions' => [$searchString]
        ]);
        $this->paginate = ['limit' => 10];
        $news = $this->paginate($postQuery);
        
        $this->set(compact('news'));   	    
		
		/*
		$this->paginate = [
            'contain' => ['Users']
        ];
        $news = $this->paginate($this->News);

        $this->set(compact('news')); */
    }

    /**
     * View method
     *
     * @param string|null $id News id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $news = $this->News->get($id, [
            'contain' => ['Users', 'NewsTranslation', 'NewsTranslations']
        ]);

        $this->set('news', $news);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $news = $this->News->newEntity();
        if ($this->request->is('post')) {
            $data              = $this->request->getData();
            $news_translations = [];
            if (isset($data['news_translations'])) {
                $news_translations = $data['news_translations'];
                unset($data['news_translations']);
            }            
            $news = $this->News->patchEntity($news, $data);
            if($data['header_image']['name']!=''){
                $headerImage = $this->uploadFiles('news', $data['header_image']);
                $news->header_image = $headerImage['filename'];
            }
            if($data['upload_document_1']['name']!=''){
                $doc1 = $this->uploadFiles('news', $data['upload_document_1']);
                $news->upload_document_1 = $doc1['filename'];
            }
            if($data['upload_document_2']['name']!=''){
                $doc2 = $this->uploadFiles('news', $data['upload_document_2']);
                $news->upload_document_2 = $doc2['filename'];
            }
            $news->news_url = strtolower(Text::slug($data['title']));
            if ($this->News->save($news)) {
                $news_id = $news->id;
                if (!empty($news_translations)) {
                    $this->loadModel('NewsTranslations');
                    foreach ($news_translations as $key => $_translation) {
                        if (empty($_translation['id'])) {
                            unset($news_translations[$key]['id']);
                        }
                        $news_translations[$key]['news_id'] = $news_id;
                    }
                    $newsTranslation  = $this->NewsTranslations->newEntity();
                    $newsTranslation  = $this->NewsTranslations->patchEntities($newsTranslation, $news_translations);
                    $newsTranslations = $this->NewsTranslations->saveMany($newsTranslation);
                    //$this->News->newsCache();
                }
                $this->Flash->success(__('The news has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The news could not be saved. Please, try again.'));
        }
        $newsLanguages  = $this->languages;
        $system_languge_id = SYSTEM_LANGUAGE_ID;
        $this->set(compact('news', 'newsLanguages', 'system_languge_id'));
    }

    /**
     * Edit method
     *
     * @param string|null $id News id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $news = $this->News->get($id, [
            'contain' => ['NewsTranslations']
        ]);
        $news['news_translations'] = Hash::combine($news['news_translations'], '{n}.language_id', '{n}');
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data              = $this->request->getData();
            $news_translations = [];
            if (isset($data['news_translations'])) {
                $news_translations = $data['news_translations'];
                unset($data['news_translations']);
            }            
            $news = $this->News->patchEntity($news, $data);
            if($data['header_image']['name']!=''){
                $headerImage = $this->uploadFiles('news', $data['header_image']);
                $news->header_image = $headerImage['filename'];
            } else {
                $news->header_image = $data['old_header_image'];
            }
            if($data['upload_document_1']['name']!=''){
                $doc1 = $this->uploadFiles('news', $data['upload_document_1']);
                $news->upload_document_1 = $doc1['filename'];
            } else {
                $news->upload_document_1 = $data['old_upload_document_1'];
            }
            if($data['upload_document_2']['name']!=''){
                $doc2 = $this->uploadFiles('news', $data['upload_document_2']);
                $news->upload_document_2 = $doc2['filename'];
            } else {
                $news->upload_document_2 = $data['old_upload_document_2'];
            }
            $news->news_url = strtolower(Text::slug($data['title']));
            if ($this->News->save($news)) {
                $news_id = $news->id;
                if (!empty($news_translations)) {
                    $this->loadModel('NewsTranslations');
                    foreach ($news_translations as $key => $_translation) {
                        if (empty($_translation['id'])) {
                            unset($news_translations[$key]['id']);
                        }
                        $news_translations[$key]['news_id'] = $news_id;
                    }
                    $newsTranslation  = $this->NewsTranslations->newEntity();
                    $newsTranslation  = $this->NewsTranslations->patchEntities($newsTranslation, $news_translations);
                    $newsTranslations = $this->NewsTranslations->saveMany($newsTranslation);
                    //$this->News->newsCache();
                }
                $this->Flash->success(__('The news has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The news could not be saved. Please, try again.'));
        }
        $newsLanguages  = $this->languages;
        $system_languge_id = SYSTEM_LANGUAGE_ID;
        $this->set(compact('news', 'newsLanguages', 'system_languge_id'));
    }

    /**
     * Delete method
     *
     * @param string|null $id News id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $news = $this->News->get($id);
        if ($this->News->delete($news)) {
            
            $this->Flash->success(__('The news has been deleted.'));
        } else {
            $this->Flash->error(__('The news could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
}
