<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Cache\Cache;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use App\View\Helper\SilverFormHelper;
use Cake\View\View;
use finfo;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    
    public $languages;
    
    public $SilverForm;
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */ 
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        
       // $this->redirect(['prefix'=>'admin','controller'=>'users','action'=>'login']);

        $this->loadComponent('Email');
        $this->loadComponent('Sanitize');
        $this->loadComponent('Flash', ['clear' => true]);
        $this->loadComponent('Paginator');
        //$this->loadComponent('Csrf');
    }

    public function beforeFilter(Event $event)
    {
        $params = $this->request->getAttribute('params');
        //Get and Set Language
        $this->languages = Cache::read('site-language', 'languages');
        $langUrls        = [];
        if ($this->languages !== false) {
            $langParams = $params;
            unset($langParams['pass']);
            unset($langParams['_matchedRoute']);
            unset($langParams['_csrfToken']);
            $dLang = '';
            foreach ($this->languages as $language) {
                $culture                = $language['culture'];
                $langParams['language'] = $culture;
                $langUrls[$culture]     = Router::url($langParams, ['_full' => true]);
                if ($language['is_default'] == 1) {
                    $dLang = $culture;
                }
            }
            if (!isset($params['language']) && !empty($dLang) && isset($langUrls[$dLang])) {
                return $this->redirect($langUrls[$dLang]);
            }
            Configure::write('CurrentLanguageUrls', $langUrls);
        }

        $language = (isset($params['language'])) ? $params['language'] : '';
        $this->request->withParam('language', $language);

        //Get Language
        if ($this->languages !== false) {
            $setLanguages = Hash::combine($this->languages, '{n}.culture', '{n}');
            if (isset($setLanguages[$language])) {
                Configure::write('language', $setLanguages[$language]);
            }
        }
        $this->SilverForm = new SilverFormHelper(new View());

        parent::beforeFilter($event);
    }

    public function beforeRender(Event $event)
    {
        $this->viewBuilder()->setClassName('App');
        $this->set('Configure', new Configure);
        $this->set('languages', $this->languages);
    }

    public function isAuthorized($user = null)
    {
        //return (bool) ($user['role_id'] === 1);
        return true;
    }

    public function getCurrentLanguage($value='')
    {
        return new Configure;
    }


}
