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
class ContactUsController extends AppController
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
        $this->loadModel('ContactUs');
       
        $this->paginate = ['limit' => 10];
        $contacts = $this->paginate($this->ContactUs);
        $this->set(compact('contacts'));
        
        //print_r($contacts);exit;
    }
}
