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
namespace App\Controller\Admin;

use App\View\Helper\SilverFormHelper;
use Cake\Cache\Cache;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\View\View;
use finfo;
use FPDI;

class PDF extends FPDI {
    protected $_tplIdx;
    public function Header()
    {
        if (null === $this->_tplIdx) {
            $this->setSourceFile('letterhead.pdf');
            $this->_tplIdx = $this->importPage(1);
        }
        $this->useTemplate($this->_tplIdx);
    }

    public function Footer(){
        $this->SetY(0);
    }
}

class HPDF extends FPDI {
    protected $_tplIdx;
    public function Header(){
    }

    public function Footer(){
    }
}

class DEMOPDF extends FPDI {
    protected $_tplIdx;
    function Header() {
        //$this->SetFont('','B',40);
        $this->SetTextColor(255, 192, 203);
        //$this->RotatedText(35, 220, 'Preview of Certificate of Origin', 45);
    }

    function RotatedText($x, $y, $txt, $angle) {
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    public function Footer(){
    }
}


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
        $this->loadComponent('Email');
        $this->loadComponent('Sanitize');
        $this->loadComponent('Flash', ['clear' => true]);
        $this->loadComponent('Paginator');
        /*$this->loadComponent('Csrf', [
            'httpOnly' => true
        ]);*/
        $this->loadComponent('Auth', [
            'authorize'=> 'Controller',
            'authenticate' => [
                'Form' => ['userModel' => 'Users',
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ]
                ]
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            'loginRedirect' => [
                'controller' => 'Dashboard',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
             // If unauthorized, return them to page they were just on
            'unauthorizedRedirect' => $this->referer(),

            'authorize'            => 'Controller',
            'flash'                => [
                'element' => 'error',
            ],
        ]);

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }

    public function beforeRender(Event $event)
    {
        $this->viewBuilder()->setTheme('AdminLTE');
        $this->viewBuilder()->setClassName('App');
        $this->set('Configure', new Configure);
        $this->set('languages', $this->languages);
		
		$this->loadModel('Roles');
        $loginUsernew = $this->request->getSession()->read('Auth.User');
        $getuser = $loginUsernew['role_id'];
        $cuser = $this->Roles->find('all')->where(['id' => $getuser])->first();
        $this->set('cuser',$cuser);
    }

    public function isAuthorized($user){
        if (isset($user['role_id']) && $user['role_id'] === 1) {
            return true;
        }

        // Default deny
        return false;
    }

    public function beforeFilter(Event $event) {
        $userArray = $this->Auth->user();
        $this->Auth->allow(['login','forgotPassword','logout','emailcheck']);
        $this->set('userArray',$userArray);
        if($userArray['id']){
            $response = $this->getuserPermissions($userArray['role_id']);
            $this->set('roledata',$response['roledata']);
            if($userArray['role_id'] != 1){
                $CusAllowaction=array('login');                
                $CusAllowController = array('masters');
                
                $response['action']=array_merge($response['action'],$CusAllowaction);           
                $Allowcontroller = array_merge($response['controller'],$CusAllowController);    
                $Allowcontroller=array_map('strtolower',$Allowcontroller);              
                $Allowaction=array_map('strtolower',$response['action']);
                $this->Auth->allow($Allowaction);   
                $action = strtolower($this->request->params['action']);
                $controller = strtolower($this->request->params['controller']);
                
                if(empty(in_array($action, $Allowaction)) || empty(in_array($controller, $Allowcontroller))){
                    $this->Flash->error(__('Oops, you are not authorized to access this area.'),['clear'=> true]);
                    //return $this->redirect(['controller' => 'Dashboard','action' => 'index']);
                    return $this->redirect($this->referer());
                }
            }
        }
        $this->Auth->setConfig('loginRedirect',['controller' => 'Dashboard','action' => 'index']);
    
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
    
    function getuserPermissions($role_id){
        
        $this->loadModel('Rolespermissions');
        $this->loadModel('Modules');
        $rolepermis = $this->Rolespermissions->find('all',
                                    ['conditions'=>['role_id'=>$role_id]])->select(['id','role_id','mid','navigationshow'])
                                    ->contain([
                                        'Child' => function ($q) {
                                            return $q->enableAutoFields(false)
                                                     ->select(['id','name','action','controller','pid']);
                                        }
                                    ])->order(['Child.depth'=>'ASC'])->enableHydration(false)->toArray();
        
        $action=array();
        $controller=array();
        $roledata=array();  $i=0;
        foreach($rolepermis as $permission){
            $moduleP = $this->Modules->find('all',['conditions' => ['id' => $permission['child']['pid']],'fields'=>['id','name','action','controller','icon']])->enableHydration(false)->first();
            $roledata[$moduleP['id']]['ParentModule']=$moduleP;
            
            if(empty($permission['navigationshow'])){
            $roledata[$moduleP['id']]['ChildModule'][]=$permission['child'];
            }
            if(empty($roledata[$moduleP['id']]['ChildModule'])){
                unset($roledata[$moduleP['id']]);
            }
            
            $action[]=str_replace("-","",$permission['child']['action']);
            $controller[]=str_replace("-","",$permission['child']['controller']);
            
        }
        ksort($roledata);
        $response=array();
        array_push($controller,"dashboard","users");
        array_push($action,"logout");
        $response['roledata']=$roledata;
        $response['action']=array_unique($action);
        $response['controller']=array_unique($controller);
        return $response;       
    }
    
    
    public function exportInExcel($fileName, $headerRow, $data)
    {
        header('Content-type: application/ms-excel'); /// you can set csv format
        header('Content-Disposition: attachment; filename='.$fileName);
        ini_set('max_execution_time', 1600); //increase max_execution_time to 10 min if data set is very large
        $fileContent = implode("\t ", $headerRow)."\n";
        foreach($data as $result) {
            $fileContent .=  implode("\t ", $result)."\n";
        }
        ob_end_clean();
        echo $fileContent;
        exit;
    }
    
    public function sendSMS($message,$mnumber){
        if (function_exists("curl_init")) 
        {
            // initialize a new curl resource
            $ch = curl_init();
            $baseURL = "https://164.100.14.211/failsafe/HttpLink?username=dsscic.sms&pin=K!n4nac%252h";
            $replyTo = "CICIND";
            $recipient = $mnumber;
            $messageBody = $message;
            // URL encode message body
            $messageBody = urlencode($messageBody);
            $URI = $baseURL;
            $URI .= "&signature=".$replyTo;
            $URI .= "&mnumber=" . $recipient;
            $URI .= "&message=" . $messageBody;
            // Set URL to connect to
            curl_setopt($ch, CURLOPT_URL, $URI);
            // Set header supression
            curl_setopt($ch, CURLOPT_HEADER, 0);
            // Disable SSL peer verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // Indicate that the message should be returned to a variable
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // Make request
            $content = curl_exec($ch);
            //print($content);
            curl_close($ch);
        } else {
            print("ERROR: curl library is not installed");
        } 
    }

    /*
     * function for removing extra whites spaces, new lines, tabs, html syntaxes.
     */
    public function strClean($string){
        //for white spaces, new line, tabs
        $string = trim(preg_replace('/\s\s+/', ' ', $string));
        //for removing html syntaxes.
        strip_tags($string);
        return $string;
    }

    public function get_tiny_url($url)  { 
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data; 
    }

    /***
     * @param $folder
     * @param $file
     * @param $itemId
     *
     */
    public function uploadFiles($folder, $file, $itemId = null) {
        $folder_url = UPLOAD_FILE.$folder;
        $rel_url = $folder;
        if(!is_dir($folder_url)) {
            mkdir($folder_url);
        }
        if($itemId) {
            $folder_url = UPLOAD_FILE.$folder.'/'.$itemId;
            $rel_url = $folder.'/'.$itemId;
            if(!is_dir($folder_url)) {
                mkdir($folder_url);
            }
        }
        // list of permitted file types, this is only images but documents can be added
        $permitted = array('image/gif','image/jpeg','image/pjpeg','image/png','application/pdf','application/x-download','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-powerpoint','application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.presentationml.slideshow', 'application/vnd.openxmlformats-officedocument.presentationml.presentation','text/plain','application/zip','application/x-rar','application/x-rar-compressed','application/octet-stream','application/x-zip-compressed', 'multipart/x-zip','application/octet-stream','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.presentationml.presentation','video/mp4','video/ogg','video/3gp','video/mov');
        
        $filename = str_replace(array('\\','/',':','*','?','"','<','>','|','%20'),' ',$file['name']);
        $filename = str_replace(' ', '-', $filename);
        $filetempname = $file['tmp_name'];
        $finfo      = new \finfo(FILEINFO_MIME);
        $fres       = $finfo->file($filetempname);        
        $infoArr    = explode(';',$fres);        
        $invalidfile = substr_count($filename, ".");        
        if ($invalidfile>1) {
            $result['errors'] = "$filename cannot be uploaded. Invalid File. e.g. double dot.";
            return $result;
        } else if(!in_array($infoArr[0], $permitted)){
            $result['errors'] = "$filename cannot be uploaded. $infoArr[0] is not allowed.";
            return $result;
        } else if($infoArr[0] != $file['type']) {
            $result['errors'] = "$filename cannot be uploaded. Select correct file type.";
            return $result;
        } else if($file['error'] == 0) {
            switch($file['error']) {
                case 0:
                    if(!file_exists($folder_url.'/'.$filename)) {
                        $full_url = $folder_url.'/'.$filename;
                        $url = $rel_url.'/'.$filename;
                        $fileName = $filename;
                        $success = move_uploaded_file($file['tmp_name'], $full_url);
                    } else {
                        ini_set('date.timezone', 'Asia/Kolkata');
                        $now = date('Y-m-d-His');
                        $length     = 5;
                        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
                        $str_length = substr(str_shuffle($characters), 0, $length); 
                        $full_url = $folder_url.'/'.$now."-".$str_length."-".$filename;
                        $url = $rel_url.'/'.$now."-".$str_length."-".$filename;
                        $fileName = $now."-".$str_length."-".$filename;
                        $success = move_uploaded_file($file['tmp_name'], $full_url);
                    }
                    if($success) {
                        $result = [
                            'filename'=>$fileName,
                            'url'=> $url,
                            'type'=>$infoArr[0],
                            'size'=>$file['size']
                        ];
                    } else {
                        $result['errors'] = "Error uploaded $filename. Please try again.";
                    }
                    break;
                case 3:
                    $result['errors'] = "Error uploading $filename. Please try again.";
                    break;
                default:
                    $result['errors'] = "System error uploading $filename. Contact webmaster.";
                    break;
            }
        } elseif($file['error'] == 4) {
            $result['nofiles'] = "No file Selected";
        }else {
            $result['errors'] = "$filename cannot be uploaded. Only Image is allowed.";
        }
        return $result;
    }

    /***
     * @param $folder
     * @param $file
     * @param $itemId
     *
     */
    public function uploadImage($folder, $file, $itemId = null) {//echo '<pre>'; print_r($file);die;
        $folder_url = UPLOAD_FILE.$folder;
        $rel_url = $folder;
        if(!is_dir($folder_url)) {
            mkdir($folder_url);
        }
        if($itemId) {
            $folder_url = UPLOAD_FILE.$folder.'/'.$itemId;
            $rel_url = $folder.'/'.$itemId;
            if(!is_dir($folder_url)) {
                mkdir($folder_url);
            }
        }
        // list of permitted file types, this is only images but documents can be added
        $permitted = array('image/gif','image/jpeg','image/pjpeg','image/png','image/jpg');
        $filename = str_replace(' ', '_', $file['name']);
        //$filename = str_replace(' ', '_', $file);
        //echo $filename;die;
        $typeOK = false;
        foreach($permitted as $type) {
            if($type == $file['type']) {
                $typeOK = true;
                break;
            }
        }
            if($file['error'] == 0) {
                switch($file['error']) {
                    case 0:
                        if(!file_exists($folder_url.'/'.$filename)) {
                            $newFileName = $filename;
                            $full_url = $folder_url.'/'.$filename;
                            $url = $rel_url.'/'.$filename;//echo $full_url;die;
                            $success = move_uploaded_file($file['tmp_name'], $full_url);
                            //echo '<pre>'; print_r($success); die;
                        } else {
                            ini_set('date.timezone', 'Asia/Kolkata');
                            $now = date('Y-m-d-His');
                            $newFileName = $now."_".$filename;
                            $full_url = $folder_url.'/'.$now."_".$filename;
                            $url = $rel_url.'/'.$now."_".$filename;
                            $success = move_uploaded_file($file['tmp_name'], $full_url);
                        }//echo '<pre>'; print_r($success); die;
                        if($success) {
                            $result = [
                                'filename'=>$newFileName,
                                'url'=> $url,
                                'type'=>$file['type'],
                                'size'=>$file['size']
                            ];
                        } else {
                            $result['errors'] = "Error uploaded $filename. Please try again.";
                        }
                        break;
                    case 3:
                        $result['errors'] = "Error uploading $filename. Please try again.";
                        break;
                    default:
                        $result['errors'] = "System error uploading $filename. Contact webmaster.";
                        break;
                }
            } elseif($file['error'] == 4) {
                $result['nofiles'] = "No file Selected";
            }else {
                $result['errors'] = "$filename cannot be uploaded. Only Image is allowed.";
            }
            return $result;
    }

    /***
     * @param $folder
     * @param $file
     * @param $itemId
     *
     */
    public function uploadPdf($folder, $file, $itemId = null) {
        $folder_url = UPLOAD_FILE.$folder;
        $rel_url = $folder;
        if(!is_dir($folder_url)) {
            mkdir($folder_url);
        }
        if($itemId) {
            $folder_url = WWW_ROOT.$folder.'/'.$itemId;
            $rel_url = $folder.'/'.$itemId;
            if(!is_dir($folder_url)) {
                mkdir($folder_url);
            }
        }
        // list of permitted file types, this is only images but documents can be added
        $permitted = array('application/pdf','application/x-download');
           $filename = str_replace(' ', '_', $file['name']);
        $typeOK = false;
        foreach($permitted as $type) {
            if($type == $file['type']) {
                $typeOK = true;
                break;
            }
        }
         if($file['error'] == 0) {
            switch($file['error']) {
                case 0:
                    if(!file_exists($folder_url.'/'.$filename)) {
                        $full_url = $folder_url.'/'.$filename;
                        $url = $rel_url.'/'.$filename;
                        $success = move_uploaded_file($file['tmp_name'], $full_url);
                    } else {
                        ini_set('date.timezone', 'Asia/Kolkata');
                        $now = date('Y-m-d-His');
                        $full_url = $folder_url.'/'.$now."_".$filename;
                        $url = $rel_url.'/'.$now."_".$filename;
                        $success = move_uploaded_file($file['tmp_name'], $full_url);
                    }
                    if($success) {
                        $result = [
                            'filename'=>$filename,
                            'url'=>$url,
                            'type'=>$file['type'],
                            'size'=>$file['size']
                        ];
                    } else {
                        $result['errors'] = "Error uploaded $filename. Please try again.";
                    }
                    break;
                case 3:
                    $result['errors'] = "Error uploading $filename. Please try again.";
                    break;
                default:
                    $result['errors'] = "System error uploading $filename. Contact webmaster.";
                    break;
            }
        } elseif($file['error'] == 4) {
            $result['nofiles'] = "No file Selected";
        } 
        else {
            $result['errors'] = "$filename cannot be uploaded. Only PDF is allowed.";
        }
        return $result;
    }

    public function getIpAddress(){
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function auditrail($user_id,$user_type,$action,$module)
    {
        $this->loadModel('Auditrail');
        $auditrail = $this->Auditrail->newEntity();
        $data = [
            'ip_address' => $this->getIpAddress(),
            'user_id' => $user_id,
            'user_type' => $user_type,
            'action' => $action,
            'module' => $module,
        ];
        $auditrail = $this->Auditrail->patchEntity($auditrail, $data);
        $result = $this->Auditrail->save($auditrail);
    }

    /****** Function to create pdf file********/

    public function CreatePdfDemo($content, $title){
        $this->autoRender = false;
        $pdf = new DEMOPDF();
        $pdf->AddPage();
        $pdf->SetTopMargin(5);
        $pdf->SetLeftMargin(10);
        $pdf->SetRightMargin(10);
        $pdf->SetFillColor(53,127,63);
        $pdf->SetFont('times', 'R', 11);
        $pdf->SetFont('courier', 'R', 11);
        $pdf->SetFont('Helvetica', 'R', 11);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetCompression(true);
        $pdf->SetTitle($title);
        $pdf->writeHTML($content, false, false, false, false, 'L');

        //$img_file2 = './img/emblem-dark.png';
        // $img_file = './img/log1.png';                                
        // $bMargin = $pdf->getBreakMargin();                
        // $auto_page_break = $pdf->getAutoPageBreak();
      
        // //$pdf->Image($img_file2, 80, 22, 15, 25, '', '', '', false, 300, '', false, false, 0);
        // $pdf->Image($img_file, 100, 25, 15, 18, '', '', '', false, 50, '', false, false, 0);

        $tempfile = $title.date("Y").'.pdf';
        $filename = WWW_ROOT."/files/certificate/".$tempfile;
        $pdf->Output($filename,'F');
        return $tempfile;
        //exit();
    }

     
    public function exportInExcelNew($fileName, $headerRow, $data)
    {
         header('Content-Encoding: UTF-8');
        header('Content-type: application/ms-excel ; charset=UTF-8'); /// you can set csv format
        header('Content-Disposition: attachment; filename='.$fileName);
        ini_set('max_execution_time', 1600); //increase max_execution_time to 10 min if data set is very large
        $fileContent = implode("\t ", $headerRow)."\n";
        foreach($data as $result) {
            $fileContent .=  implode("\t ", $result)."\n";
        }
        ob_end_clean();

         echo chr(255) . chr(254) . mb_convert_encoding($fileContent, 'UTF-16LE', 'UTF-8');
        //echo $fileContent;
        exit;
    }

}
