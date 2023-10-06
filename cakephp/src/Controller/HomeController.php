<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Utility\Text;
use Cake\Mailer\Email;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Chronos\Chronos;

/**
 * Home Controller
 *
 */

class HomeController extends AppController
{
	public function initialize()
    {
        parent::initialize();

        //$this->Auth->allow(['index']);
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
       // $this->viewBuilder()->setLayout('home');
    }
    
}
