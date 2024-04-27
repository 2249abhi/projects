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
use Cake\ORM\TableRegistry;

/**
 * News Controller
 *
 * @property \App\Model\Table\NewsTable $News
 *
 * @method \App\Model\Entity\News[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TestController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['index']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
		
        //$this->loadModel('cooperative_registrations');
      

          $this->data = TableRegistry::get('cooperative_registrations');

    
           // $query = $cooperative_registrations->find('all')->toarray();


             $up_milk = TableRegistry::get('andamanand_nicobar_fishries');

    
            $query_up_milk = $up_milk->find('all')->order('id ASC')->limit(1000)->offset(0)->toarray();


          /*  foreach ($query_up_milk as $key => $value) 

            {

                $data['state_id']=$value['state_code'];
                
                 $cr =$cooperative_registrations->newEntity();

                  $cr = $cooperative_registrations->patchEntity($cr, $data);
                    if($cooperative_registrations->save($cr)) 
                    {

                       echo  $cooperative_registrations->id;
                    }
            }*/
                 $duplicate='';
            foreach ($query_up_milk as $key => $value) 

            {

                echo $value['id'];
               
                $tableAdd = $this->data->newEntity(); // By defining this, it creates a reserves space for inserting the data items.
                $tableAdd->state_code                        =$value['state_code'];
                $tableAdd->cooperative_society_name       =$value['name_of_the_society'];
                // $tableAdd->registration_number                 =$value['registration_date_no'];
                  $tableAdd->district_code                        =$value['district_code'];
                   $tableAdd->gram_panchayat_code                 =$value['panchayat_code'];
                    $tableAdd->flag_name                  ='andamanand_nicobarIslands_fishries';
                   
                   
                     //$tableAdd->cooperative_id                   =$value['sl_no'];

                      /*  $refrence_year= explode('/',$value['registration_date_no']);
                        if(count($refrence_year) > 2)
                        {
                                    echo "----yers";
                               echo  $tableAdd->reference_year                  = end($refrence_year);
                        }*/
                       
                       $tableAdd->subdistrict_code               =$value['subdistrict_code'];
                        $tableAdd->sector_code                     =$value['sector_code'];
                        // $tableAdd->district_name                   =$value['district'];

                            $cooperative_registrations=        TableRegistry::get('cooperative_registrations');
                         $queryR = $cooperative_registrations->find('all')->where(['cooperative_id'=>$value['sl_no']])->toarray();


                                   
                         if(count($queryR) >0)
                         {

                            echo "duplicate";
                            echo "<br>";
                            echo  $duplicate.=$value['sl_no'].',';
                         }



                     
                Echo "<br>";
              // echo $value['sl_no'];              
             // $this->data->save($tableAdd);
            }

                echo "<pre>";
               print_r($tableAdd);

        echo "test";
        die;
        /*$search_condition = array();
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
        $news = $this->paginate($postQuery);*/
        
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
