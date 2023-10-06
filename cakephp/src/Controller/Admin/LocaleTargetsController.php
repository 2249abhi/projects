<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;

/**
 * LocaleTargets Controller
 *
 * @property \App\Model\Table\LocaleTargetsTable $LocaleTargets
 *
 * @method \App\Model\Entity\LocaleTarget[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LocaleTargetsController extends AppController
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
        return $this->redirect(['action' => 'add']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('Languages');
        $this->loadModel('LocaleSources');
        $search_condition = array();
        $stringQuery  = array();
        $setQuery = $this->request->getQuery('string_contains');
        if($this->request->getQuery()){
            if (!empty($this->request->getQuery('string_contains'))) {
                $stringContains = trim($this->request->getQuery('string_contains'));
                $stringContains = $this->Sanitize->stripAll($stringContains);
                $stringContains = $this->Sanitize->clean($stringContains);
                $this->set('stringContains', $stringContains);
                $stringQuery = $this->LocaleSources->find('all',
                    ['conditions'=>['source like'=>"%$stringContains%"]])->enableHydration(false)->first();
                if (!empty($stringQuery)) {
                    $translationLanguage = trim($this->request->getQuery('translation_language'));
                    $this->set('translationLanguage', $translationLanguage);
                    if (!empty($translationLanguage)) {
                        if ($translationLanguage == 'all') {             
                            if (!empty($this->request->getQuery('search_in'))) {
                                $searchInSet = trim($this->request->getQuery('search_in'));
                                $this->set('searchInSet', $searchInSet);
                                if ($searchInSet && !empty($translationLanguage)) {
                                    $search_condition[] = "LocaleTargets.locale_source_id = '" . $stringQuery['id'] . "'";                                    
                                }
                            }
                        } else {
                            $search_condition[] = "LocaleTargets.locale_source_id = '" . $stringQuery['id'] . "'"; 
                            $search_condition[] = "LocaleTargets.language = '" . $translationLanguage . "'";
                        }
                    } else {
                        $search_condition[] = "LocaleTargets.locale_source_id = '" . $stringQuery['id'] . "'";
                    }
                    if(!empty($search_condition)){
                        $searchString = implode(" AND ",$search_condition);
                    } else {
                        $searchString = '';
                    }
                    $filterData = $this->LocaleTargets->find('all', [
                        'contain' => ['LocaleSources'],
                        'order' => ['LocaleSources.id' => 'asc'],
                        'conditions' => [$searchString]
                    ])->enableHydration(false)->toArray();
                    $this->set(compact('filterData'));
                }
            }
        }

        
        if ($this->request->is('post')) {
            if (!empty($this->request->getData('translated'))) {
                $lang_translations = $this->request->getData('translated') ;
                foreach ($lang_translations as $key => $_translation) {
                    if (empty($_translation['id'])) {
                        unset($lang_translations[$key]['id']);
                    }
                    $findData = $this->LocaleTargets->find('all',['conditions'=>['language'=>$_translation['language'],'locale_source_id'=>$_translation['locale_source_id']]])->enableHydration(false)->first();
                    if (!empty($findData['id'])) {
                        $localeTarget = $this->LocaleTargets->get($findData['id']);
                    } else {
                        $localeTarget  = $this->LocaleTargets->newEntity();
                    }
                }
                $localeTarget  = $this->LocaleTargets->patchEntities($localeTarget, $lang_translations);
                $localeTargets = $this->LocaleTargets->saveMany($localeTarget);

                $this->Flash->success(__('The locale target has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The locale target could not be saved. Please, try again.'));
        }
        $localeSources = $this->LocaleTargets->LocaleSources->find('list', ['limit' => 200]);
        $languagesList = $this->Languages->find('list', ['keyField'=>'culture','valueField'=>'name','conditions'=>['id !='=>1]])->toArray();        
        $this->set(compact('localeTarget','localeSources','languagesList','stringQuery','setQuery'));
    }

}
