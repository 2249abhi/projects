<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Error\Exceptions;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('CakephpCaptcha.Captcha');
        $this->Auth->allow(['jcryption', 'forgotPassword','resetPassword','captcha']);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    private $startupStatus = [
        1=> 'In Progress',
        2=> 'Recognised as Startup by Haryana Govt',
        3=> 'Incomplete',
        4=> 'Reject'
    ];


    /**
     * login method
     *
     * @return \Cake\Network\Response|null
     */
 
    public function login()
    {
		session_start();
        $this->viewBuilder()->setLayout('login');
        if ($this->request->getSession()->check('Auth.User')) {
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        if ($this->request->is('post')) {
            $token = $this->request->getParam('_csrfToken');
            $jcryption = new \JCryption;
            $jcryption->decrypt();
            @$this->request->data = $_REQUEST;            
            $userdata = @$this->request->data;
            $errors = array();
            $captcha = $this->Captcha->check($userdata['captcha']);
            if (empty($captcha)) {
                $errors['captcha']['_empty'] = 'Invalid captcha. Please try again.';
            }
            
            $uname = $userdata['email'];
            $login_attempts_id = '';
            $previousFailedAttempt = '';
            if (empty($errors)) {

                /***   Checking for login attempts   ***/
                $attempt_status = 0;
                $cUser = $this->Users->find('all')->where(['email' => $uname])->first();
                if (!empty($cUser)) {
                    $uid = $cUser['id'];
                } else {
                    $uid = 0;
                }
                $ip = $_SERVER['REMOTE_ADDR'];

                if ($ip == '::1') {
                    $ipadd = '127.0.0.1';
                } else {
                    $ipadd = $ip;
                }
                $failed_attempts = 1;

                $login_attemptsTable = TableRegistry::get('login_attempts');
                $login_attemptsResult = $login_attemptsTable->find('all', ['conditions' => ['ipaddress' => $ipadd, 'last_attempt >' => new Time('-15 minutes')]]);
                $login_attemptsResult->enableHydration(false);
                $login_attemptsResultArr = $login_attemptsResult->toArray();

                //for deleting all records before 15 minutes
                $attempts_15minBefore = $login_attemptsTable->find('all', ['conditions' => ['ipaddress' => $ipadd, 'last_attempt <' => new Time('-15 minutes')]])->enableHydration(false)->toArray();
                
                if (!empty($attempts_15minBefore)) {
                    foreach ($attempts_15minBefore as $keyA => $valueA) {
                        $recordIdToDelete = $valueA['id'];
                        $recordEntity = $login_attemptsTable->get($recordIdToDelete);
                        $login_attemptsTable->delete($recordEntity);
                    }
                }

                if (empty($login_attemptsResultArr)) {
                    $login_attempts = $login_attemptsTable->newEntity();
                    $login_attempt_data = array(
                        'uid' => $uid,
                        'ipaddress' => $ipadd,
                        'failed_attempts' => $failed_attempts,
                    );
                    $login_attempts = $login_attemptsTable->patchEntity($login_attempts, $login_attempt_data);
                    $result = $login_attemptsTable->save($login_attempts);

                    if ($result) {
                        $attempt_status = 1;
                    } else {
                        $attempt_status = 0;
                    }
                } else {
                    $login_attempts_id = $login_attemptsResultArr[0]['id'];
                    $previousFailedAttempt = $login_attemptsResultArr[0]['failed_attempts'];
                    $login_attempts = $login_attemptsTable->get($login_attemptsResultArr[0]['id']);
                    $login_attempt_data['failed_attempts'] = $login_attempts->failed_attempts + 1;
                    $login_attempt_data['uid'] = $uid;

                    $login_attempts = $login_attemptsTable->patchEntity($login_attempts, $login_attempt_data);
                    $result = $login_attemptsTable->save($login_attempts);
                    //if($result['failed_attempts'] >= 6 && $hours==0 && $minutes<=15){
                    if ($result['failed_attempts'] >= 6) {
                        $attempt_status = 0;
                    } else {
                        $attempt_status = 1;
                    }
                }

                $validator = new Validator();
                $validator->email('email');
                $validError = $validator->errors(['email'=>$this->request->data['email']]);
                if(empty($validError)){
                    $this->Auth->setConfig('authenticate', [
                        'Form' => ['fields' => ['username' => 'email']],
                    ]);
                    $this->request = $this->request->withData('email', $this->request->data['email']);
                }else{
                    
                    $userExist = $this->Users->find()->where(['username'=>$this->request->data['email']])->orWhere(['email'=>$this->request->data['email']])->first();
                    $this->Auth->setConfig('authenticate', [
                        'Form' => ['fields' => ['username' => 'username']],
                    ]);
                    $this->request = $this->request->withData('username', $userExist->username);
                    $this->request = $this->request->withoutData('email');
                }

                /***   Ends   ***/
                $auth = $this->Auth->identify();
                if ($attempt_status == 1) {
                    if ($auth) {
                        $user_id = $auth['id'];
                        // for check already login or not.
                        $adminLogsTable = TableRegistry::get('admin_logs');
                        $loggedInCheck = $adminLogsTable->find('all', [
                                'conditions' => ['uid' => $user_id]
                            ])->last();
                        //if ($loggedInCheck['flag'] == 0) {
                            $loginAttemptsTable = TableRegistry::get('login_attempts');
                            $UserLoginExistsQ = $loginAttemptsTable->find('all', [
                                'conditions' => ['uid' => $user_id, 'last_attempt >' => new Time('-15 minutes')]
                            ]);
                            $UserLoginExists = $UserLoginExistsQ->enableHydration(false)->toArray();

                            if ($auth['status']) {
                                /* admin log code starts */
                                $ipaddress = $_SERVER['REMOTE_ADDR'];
                                if ($ipaddress == '::1') {
                                    $ipadd = '127.0.0.1';
                                } else {
                                    $ipadd = $ipaddress;
                                }
                                $uid = $auth['id'];
                                $adminLogTable = TableRegistry::get('admin_logs');
                                $adminLog = $adminLogTable->newEntity();
                                $adminLog->uid = $uid;
                                $adminLog->flag = 1;
                                $adminLog->logtime = date('Y-m-d H:i:s');
                                $adminLog->ipaddress = inet_pton($ipadd);//string inet_ntop ( string $in_addr )
                                $adminLogTable->save($adminLog);
                                /* admin log code ends */

                                $this->Auth->setUser($auth);
                                // code for updating login attempt status for already login
                                $UserLoginExistsQ = $loginAttemptsTable->find('all', [
                                    'conditions' => ['uid' => $user_id, 'last_attempt >' => new Time('-15 minutes')],
                                ]);
                                $UserLoginExists = $UserLoginExistsQ->enableHydration(false)->toArray();
                                if (!empty($UserLoginExists)) {
                                    $userLoggedInStatus = end($UserLoginExists)['login_flag'];
                                    $loginUserAttemptFlagEntity = $loginAttemptsTable->get(end($UserLoginExists)['id']);
                                    $loginUAttemptFlagData = [
                                        'login_flag' => 1,
                                    ];
                                    $loginUserAttemptFlagEntityPatch = $loginAttemptsTable->patchEntity($loginUserAttemptFlagEntity, $loginUAttemptFlagData);
                                    $loginAttemptsTable->save($loginUserAttemptFlagEntityPatch);
                                }
                                //ends here.
                                if ($login_attempts_id) {
                                    $this->set('userd', $this->Auth->user());
                                    $entity1 = $login_attemptsTable->get($login_attempts_id);
                                    $entityData = $login_attemptsTable->patchEntity($entity1, ['failed_attempts' => 0]);
                                    $login_attemptsTable->save($entityData);
                                }
                                return $this->redirect($this->Auth->redirectUrl());
                            } else {
                                $this->Flash->error(__('User is deactivated or blocked.'));
                                return $this->redirect($this->referer());
                            }
                        /*} else {
                            $this->Flash->error(__('Multiple login not allowed.'));
                        }*/
                    } else {
                        $this->Flash->error(__('Invalid credentials.'));
                    }
                } else {
                    $this->Flash->error(__('You have entered wrong credentials 5 or more times, User is blocked.'));
                    return $this->redirect($this->referer());
                }
            } else {
                if(!empty($errors['captcha']['_empty'])){
                    $this->Flash->error(__($errors['captcha']['_empty']));
                } else {
                    $this->Flash->error(__('Your Captcha is expire. Please refresh the page'));
                }
            }
        }
    }

    /**
     * logout method
     *
     * @return \Cake\Network\Response|null
     */

    public function logout()
    {
        $this->Flash->success(__('You are logged out '));
        $user_id = $this->request->session()->read('Auth.User.id');

        $adminLogTable = TableRegistry::get('admin_logs');
        $loggedInUser = $adminLogTable->find('all',[
            'conditions' => ['uid'=>$user_id],
        ])->last();

        $adminLog = $adminLogTable->get($loggedInUser['id']);
        $adminLog->flag = 0;
        $adminLogTable->save($adminLog);

        $loginAttemptsTable = TableRegistry::get('login_attempts');
        $UserLoginExists = $loginAttemptsTable->find('all',[
            'conditions' => ['uid'=>$user_id,'last_attempt >'=>new Time('-15 minutes')],
        ])->enableHydration(false)->toArray();
        if(!empty($UserLoginExists)){
            $loginUserAttemptFlagEntity = $loginAttemptsTable->get(end($UserLoginExists)['id']);
            $loginUserAttemptFlagEntityPatch = $loginAttemptsTable->patchEntity($loginUserAttemptFlagEntity, ['login_flag'=>0]);
            $loginAttemptsTable->save($loginUserAttemptFlagEntityPatch);
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        if($ip=='::1'){
            $ipadd = '127.0.0.1';
        } else {
            $ipadd = $ip;
        }
        $login_attempts_ipArr = $loginAttemptsTable->find('all', ['conditions' => ['ipaddress' => $ipadd]])->enableHydration(false)->toArray();

        $login_attempts_id = $login_attempts_ipArr[0]['id'];
        if($login_attempts_id){
            $entity = $loginAttemptsTable->get($login_attempts_id);
            $entityData = $loginAttemptsTable->patchEntity($entity, ['failed_attempts'=>0]);
            $loginAttemptsTable->save($entity);
        }
        //$this->Auth->config('logoutRedirect', ['controller' => 'Users', 'action' => 'login']);
        $this->redirect($this->Auth->logout());
    }

    /**
     * forgotPassword method
     *
     * @return \Cake\Network\Response|null
     */

    public function forgotPassword()
    {
        if ($this->request->getSession()->check('Auth.User')) {
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }
        $this->viewBuilder()->setLayout('login');        
        if (!empty($this->request->getData())) {
            $captcha = $this->Captcha->check($this->request->getData('captcha'));
            if (!empty($captcha)) {
                if (empty($this->request->getData('email'))) {
                    $this->Flash->error('Please Provide email ');
                } else {
                    $uemail = $this->request->data['email'];
                    $fu = $this->Users->find('all')->where(['email =' => $uemail])->enableHydration(false)->toArray();
                    if (!empty($fu)) {
                        $userid = $fu[0]['id'];
                        $username = $fu[0]['username'];
                        $password = $fu[0]['password'];
                        $email = $fu[0]['email'];
                        $email = new Email('default');
                        $email->template('forgot_password');
                        $email->emailFormat('html');
                        $email->viewVars(['uid' => $userid]);
                        $email->viewVars(['name' => $username]);
                        $email->viewVars(['password' => $password]);
                        $email->viewVars(['base_url' => Router::url('/admin/', true)]);

                        $status = $email->from([WEBSUPPORT => 'SIDBI'])
                            ->to($uemail)
                            ->subject('Forgot Password : SIDBI')
                            ->send();
                        $this->Flash->success('Check your inbox for a password reset email.');
                    } else {
                        $this->Flash->error('Email does not exist.');
                    }
                }
            } else {
                $this->Flash->error(__('Invalid captcha. Please try again.'));
            }
        }
    }

    public function userLog()
    {
        $this->loadModel('AdminLogs');
        $rid = $this->Auth->user('role_id');
        $adminLog = $this->AdminLogs->find('all')
            ->contain(['Users'])
            ->order(['AdminLogs.id'=>'DESC']);
        if ($rid != 1) {
            $adminLog = $adminLog->where(['uid' => $this->Auth->user('id')]);
        }
        $this->paginate = ['limit' => 20];
        $adminLog = $this->paginate($adminLog);
        $this->set(compact('adminLog'));
    }

    public function changePasswordHistory()
    {
        $changePasswordTable = TableRegistry::get('change_password_logs');
        $changePassword = $changePasswordTable->find('all')->where(['user_id'=>$this->Auth->user('id')]);
        $this->paginate = ['limit' => 20];
        $changePassword = $this->paginate($changePassword);
        $this->set(compact('changePassword'));
    }
    
    /**
     * Change Password method
     *
     * @return \Cake\Http\Response|void
     */

    public function changePassword()
    {
        $user = $this->Users->get($this->Auth->user('id'));
        if(!empty($_REQUEST))
        {
          
            $jcryption = new \JCryption;
            $jcryption->decrypt();
            $userdata = $_REQUEST;
            $user = $this->Users->patchEntity($user, [
                    'old_password'      => $userdata['old_password'],
                    'password'          => $userdata['new_password'],
                    'new_password'      => $userdata['new_password'],
                    'confirm_password'  => $userdata['confirm_password'],
                    'is_first_login'     =>0
                ],
                    ['validate' => 'password']
            );
            if($this->Users->save($user)) 
            {
                $changePasswordTable = TableRegistry::get('change_password_logs');
                $changePassword = $changePasswordTable->newEntity();
                $ipaddress = $_SERVER['REMOTE_ADDR'];
                if ($ipaddress == '::1') {
                    $ipadd = '127.0.0.1';
                } else {
                    $ipadd = $ipaddress;
                }
                $changePassword->user_id = $user->id;
               
                $changePassword->password = $user->password;
                $changePassword->change_time = date('Y-m-d H:i:s');
                $changePassword->ip_address = inet_pton($ipadd);
                $changePasswordTable->save($changePassword);
                $this->Flash->success('Your password has been changed successfully.');
                //Email code
                //$this->redirect(['action'=>'view']);
            } else {
                if($user->errors()){
                    $error_msg = [];
                    foreach( $user->errors() as $errors){
                        if(is_array($errors)){
                            foreach($errors as $error){
                                $error_msg[]    =   $error;
                            }
                        }else{
                            $error_msg[]    =   $errors;
                        }
                    }
                    if(!empty($error_msg)){
                        $this->Flash->error(
                                    __("Please fix the following error(s):".implode("\r\n", $error_msg))
                        );
                    }
                }else{
                    $this->Flash->error('Error changing password. Please try again!');
                }
            }
        }
        $this->set('user',$user);
    }

    /**
     * Change User Password method
     *
     * @return \Cake\Http\Response|void
     */

    public function changeUserPassword($userId=null)
    {
        if (empty($userId)) {
            $this->Flash->success('Select user for change password.');
            return $this->redirect(['controller'=>'Registration','action'=>'index']);
        }

        $user = $this->Users->get($userId);        
        if(!empty($this->request->data)){
            $user = $this->Users->patchEntity($user, [
                    'password' => $this->request->data['new_password'],
                    'password_hint' =>  $this->request->data['new_password']
                ]
            );
            if($this->Users->save($user)) {
                $this->Flash->success('Your password has been changed successfully');
                $this->redirect(['controller'=>'Registers','action'=>'index']);
            } else {
                if($user->errors()){
                    $error_msg = [];
                    foreach( $user->errors() as $errors){
                        if(is_array($errors)){
                            foreach($errors as $error){
                                $error_msg[]    =   $error;
                            }
                        }else{
                            $error_msg[]    =   $errors;
                        }
                    }
                    if(!empty($error_msg)){
                        $this->Flash->error(
                                    __("Please fix the following error(s):".implode("\r\n", $error_msg))
                        );
                    }
                }else{
                    $this->Flash->error('Error changing password. Please try again!');
                }
            }
        }
        $this->set('user',$user);
    }
    
    /**
     * Reset Password method
     *
     * @return \Cake\Http\Response|void
     */

    public function resetPassword($val=null)
    {
        $this->viewBuilder()->setLayout('login');
        $user_id   = !empty($this->request->query['q1'])? base64_decode($this->request->query['q1']) : null;
        $current_time  = !empty($this->request->query['q2'])? base64_decode($this->request->query['q2']) : null;
        $expire_time   = $current_time + 10*60;
        if(time() < $expire_time){
            if(!empty($user_id)) {
                $user = $this->Users->get($user_id);
                if ($this->request->is(['post','put','patch'])) {
                    $password = $this->request->data['password'];
                    $confirmPassword = $this->request->data['confirm_password'];
                    if($password==$confirmPassword){
                        $user = $this->Users->patchEntity($user, ['password' => $password]);
                        $user->password_hint = $confirmPassword;
                        if ($this->Users->save($user)) {
                            $this->Flash->success(__('Your password has been changed successfully.'));
                            $this->redirect(['controller' => 'Users', 'action' => 'login']);
                        } else {
                        $this->Flash->error(__('The password could not be changed. Please, try again.'));
                        }
                    }else{
                        $this->Flash->error(__('Confirm password does not match. Please, try again.'));
                    }
                }
            } else {
            //$this->redirect(['controller' => 'Users', 'action' => 'pnLogin']);
            }
        } else {
        $this->Flash->error(__('Your time is expire. Please, try again.'));
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->loadMOdel('PrimaryActivities');
        $this->loadModel('States');
        
        $this->loadModel('Roles');
        $this->loadModel('Districts');
        $loginUser = $this->Auth->user();
        $this->paginate = [
            'contain' => ['Roles']
        ];


        $search_condition = array();

        $data = $this->request->getData();

        if(!empty($this->request->query['name'])) {
            $name = trim($this->request->query['name']);
            $this->set('name', $name);
            $search_condition[] = "Users.name like '%" . $name. "%'";
        }
		 if(!empty($this->request->query['email'])) {
            $email = trim($this->request->query['email']);
            $this->set('email', $email);
            $search_condition[] = "Users.email like '%" . $email. "%'";
        }

        if($this->request->query['status'] != '') {
            $statusv = trim($this->request->query['status']);
            $this->set('statusv', $statusv);
            $search_condition[] = "Users.status=$statusv";

            //print_r($search_condition);
        }

        if(!empty($this->request->query['role_id'])) {
            $role_id = trim($this->request->query['role_id']);
            $this->set('role_id', $role_id);
            $search_condition[] = "Users.role_id=$role_id";
        }

        if(!empty($this->request->query['primary_act_code'])) {
            $primary_act_code = trim($this->request->query['primary_act_code']);
            $this->set('primary_act_code', $primary_act_code);
            $search_condition[] = "Users.primary_act_code=$primary_act_code";
        }

        if(!empty($this->request->query['state_code'])) {
            $state_code = trim($this->request->query['state_code']);
            $this->set('state_code', $state_code);
            $search_condition[] = "Users.state_code=$state_code";

            $districtlist=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();

             $this->set(compact('districtlist'));
        }



          if(!empty($this->request->query['district_code'])) {
            $district_code = trim($this->request->query['district_code']);

              $districtlist=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$state_code])->toArray();
               $this->set(compact('districtlist'));


            $this->set('district_code', $district_code);
            $search_condition[] = "Users.district_code=$district_code";


           //print_r($search_condition);
        }
		
		if($this->request->session()->read('Auth.User.role_id') == 11)
        {
			$state= $this->request->session()->read('Auth.User.state_code');
            $search_condition3  = "state_code = '" . $state . "'";
        }
		
        if(!empty($search_condition)){
            $searchString = implode(" AND ",$search_condition);
        } else {
            $searchString = '';
        }


        if($loginUser['role_id']==2){          
           $users = $this->paginate($this->Users->find('all')->where(['role_id IN'=>['7','8','11','13'], $searchString])->order(['Users.id'=>'DESC']));  
        } 
        else if($loginUser['role_id']==8){
           // $users = $this->paginate($this->Users->find('all')->where(['role_id IN'=>['7'], 'created_by'=>$loginUser['id']])); 
             $users = $this->paginate($this->Users->find('all')->where(['role_id IN'=>['7'],'district_code'=>$loginUser['district_code'],$searchString])->order(['Users.id'=>'DESC']));  

        }
		else  if($loginUser['role_id']==11){          
           $users = $this->paginate($this->Users->find('all')->where(['role_id IN'=>['7','8'], $searchString,$search_condition3])->order(['Users.id'=>'DESC']));  
        }
		else if($loginUser['role_id'] ==12){
            $users = $this->paginate($this->Users->find('all')->where(['role_id NOT IN'=>['1','2'], $searchString])->order(['Users.id'=>'DESC']));  
        }
        else{   
          $users = $this->paginate($this->Users->find('all')->where([$searchString])->order(['Users.id'=>'DESC']));    
        }

        

        $roles=$this->Roles->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['id IN'=>[7,8,11,13]])->toArray();
        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
        $pact=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();
        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
        $all_user= $this->Users->find('list',['keyField'=>'id','valueField'=>'name'])->toarray();
        $this->set(compact('users','districts','states','all_user','roles','pact'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Roles']
        ]);


          $this->loadModel('States');
          $this->loadModel('Districts');
          $this->loadMOdel('PrimaryActivities');

          $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();

          $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1])->toArray();
          $pact=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1])->toArray();

         
           $this->set(compact('districts','states','pact'));
        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadMOdel('PrimaryActivities');

         $loginUser = $this->Auth->user();
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
			$jcryption = new \JCryption;
            $jcryption->decrypt();
			@$this->request->data = $_REQUEST; 
            $data = @$this->request->getData();
            $data['created_by'] =   $loginUser['id'];
            $data['password_hint'] = $data['password'];

             if($data['role_id']==8)
             {
                $state_code     =   $data['state_code'];
                $district_code    =   $data['district_code'];
              $usercheck= $this->Users->find('list', ['limit' => 200])->where(['role_id'=>8,'state_code'=>$state_code,'district_code'=>$district_code])->toArray();

                if(count($usercheck) >0)
                {
                     //$this->Flash->error(__('The user already exists on this district'));
                     //return $this->redirect(['action' => 'add']);
                }

             }

            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
       
        if($loginUser['role_id']==2){
            $roles = $this->Users->Roles->find('list', ['limit' => 200])->where(['Roles.id IN '=>['8','11','13']]);
        }
        else if($loginUser['role_id']==8){
            $roles = $this->Users->Roles->find('list', ['limit' => 200])->where(['Roles.id IN '=>['7']]);
        }
		else if($loginUser['role_id']==11){
            $roles = $this->Users->Roles->find('list', ['limit' => 200])->where(['Roles.id IN '=>['8','7']]);
        }
        else{
            $roles = $this->Users->Roles->find('list', ['limit' => 200]);    
        }

        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$loginUser['state_code']]);
        $pactivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1]);
        
        
        $this->set(compact('user', 'roles','states','districts','loginUser','pactivities'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('States');
        $this->loadModel('Districts');
        $this->loadMOdel('PrimaryActivities');

        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['password_hint'] = $data['password'];
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $loginUser = $this->Auth->user();
        if($loginUser['role_id'] ==2){
            $roles = $this->Users->Roles->find('list', ['limit' => 200]);
        }
        else if($loginUser['role_id'] ==8){
            $roles = $this->Users->Roles->find('list', ['limit' => 200])->where(['Roles.id IN '=>['7']]);
        }
		else if($loginUser['role_id'] ==11){
            $roles = $this->Users->Roles->find('list', ['limit' => 200])->where(['Roles.id IN '=>['7','8']]);
        }
		
        else{
            $roles = $this->Users->Roles->find('list', ['limit' => 200]);    
        }

        $states=$this->States->find('list',['keyField'=>'state_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1]);
        $districts=$this->Districts->find('list',['keyField'=>'district_code','valueField'=>'name'])->order(['name'=>'ASC'])->where(['flag'=>1,'state_code'=>$user->state_code]);
        $pactivities=$this->PrimaryActivities->find('list',['keyField'=>'id','valueField'=>'name'])->order(['name'=>'ASC'])->where(['status'=>1]);
        $this->set(compact('user', 'roles', 'states','districts', 'loginUser', 'pactivities'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        $loginUser = $this->Auth->user();
        if ($user->id != $loginUser['id']) {
            if ($user->role_id != 1) {
                if ($this->Users->delete($user)) {
                    $this->Flash->success(__('The user has been deleted.'));
                } else {
                    $this->Flash->error(__('The user could not be deleted. Please, try again.'));
                }
            } else {
                $this->Flash->error(__('You are not authorized to delete this user.'));
            }
        } else {
            $this->Flash->error(__('You can not delete currently logged in User.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function __generatePasswordToken($user)
    {
        if (empty($user)) {
            return null;
        }
        $token = "";
        // Generate a random string 100 chars in length.
        for ($i = 0; $i < 100; $i++) {
            $d = rand(1, 100000) % 2;
            $d ? $token .= chr(rand(33, 79)) : $token .= chr(rand(80, 126));
        }
        (rand(1, 100000) % 2) ? $token = strrev($token) : $token = $token;

        // Generate a hash of random string
        $hash = Security::hash($token, 'sha256', true);;
        for ($i = 0; $i < 20; $i++) {
            $hash = Security::hash($hash, 'sha256', true);
        }
        $user['User']['reset_password_token'] = $hash;
        $user['User']['token_created_at'] = date('Y-m-d H:i:s');
    }

    public function jcryption()
    {
        $this->autoRender = false;
        $jc = new \JCryption;
        $jc->go();
        header('Content-type: text/plain');
    }

    public function captcha()
    {
        $this->autoRender = false;
        echo $this->Captcha->image(4);
    }

    public function startupRecognitionList()
    {
        $this->loadModel('StartupApplications');
        $applications = $this->paginate($this->StartupApplications->find()->contain(['Users'])->order(['StartupApplications.id'=>'DESC']));
        $status = $this->startupStatus;
        //pr($applications); die()
        $this->set(compact('applications','status'));
    }

    public function startupRecognitionEdit($id)
    {
        $this->loadModel('StartupApplications');
        $applications = $this->StartupApplications->get($id);
        $status = $this->startupStatus;
        if($this->request->is(['post','put'])){
            //pr($this->request->data); die();
            $applications = $this->StartupApplications->patchEntity($applications,$this->request->getData());
            if(!empty($this->request->data['approve'])){
                $applications->application_status_id = 1;
                $applications->startup_stage_id = 2;
            }elseif(!empty($this->request->data['reject'])){
                $applications->application_status_id = 4;
            }
            //pr($applications); die();
            if($this->StartupApplications->save($applications)){
                $this->Flash->success(__('You have successfully updated the status'));
                return $this->redirect(['action' => 'startupRecognitionList']);
            }else{
                $this->Flash->error(__('Some error occurred. Please, try again.'));
            }
        }
        $this->set(compact('applications','status'));
    }

    public function startupRecognitionView($id)
    {
        $this->loadModel('StartupApplications');
        $this->loadModel('StartupCategories');
        $applications = $this->StartupApplications->get($id,['contain'=>['Users','NatureOfStartup','Industries','Sectors','States','Districts']]);
        $cat_val = explode(',', $applications->category_id);
        $categories = $this->StartupCategories->find()->where(['id IN'=>$cat_val])->toArray();
        //print_r($categories);
        //pr($applications); die();
        $this->set(compact('applications','categories'));
    }

    public function downloadFile($filePath) {
        if($filePath){
            $filePath = base64_decode($filePath);
            ob_clean();
            if(file_exists($filePath)){
                $this->response->file($filePath,['download' => true]);
                return $this->response;
            }else{
                $this->Flash->error('File isn\'t available on server.');
                return $this->redirect($this->referer());
            }
        }else{
            $this->Flash->error('Not a valid file.');
            return $this->redirect($this->referer());
        }
    }

    public function getDistricts(){
        $this->viewBuilder()->setLayout('ajax');
        if($this->request->is('ajax')){
           $state_code=$this->request->query('state_code');  
           // $multiple=$this->request->data('multiple');    
            $this->loadMOdel('Districts');
            $Districts=$this->Districts->find('list',['keyFields'=>'district_code','valueField'=>'name'])->where(['flag'=>1,'state_code'=>$state_code])->order(['name'=>'ASC']);
                $option_html='<option value="">Select</option>';   
            if($Districts->count()>0){
                foreach($Districts as $key=>$value){
                    $option_html.='<option value="'.$key.'">'.$value.'</option>';
                }
            }
            echo $option_html;
        }
        exit;
    }

}