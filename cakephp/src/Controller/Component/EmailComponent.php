<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Mailer\Email;

/**
 * Email Component
 *
 * @author        Zankat Kalpesh
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
class EmailComponent extends Component
{
    public function send(array $data, $emailConfig = 'default')
    {
        $emailConfig = ($emailConfig != null) ? $emailConfig : 'default';
        if (!isset($data['from'])) {
            $data['from'] = 'do-not-reply@silvertouch.com';
        }
        $data['setFrom'] = $data['from'];
        unset($data['from']);

        //Email Obj
        $email = new Email($emailConfig);
        //set templatepath
        $prefix = $this->request->getParam('prefix');
        if (!empty($prefix)) {
            if (isset($data['setTemplate']) && !is_array($data['setTemplate'])) {
                $data['setTemplate'] = ucfirst($prefix) . '/' . $data['setTemplate'];
            }
        }
        //set property
        foreach ($data as $method => $args) {
            $email->{$method}($args);
        }
        //send
        $status = $email->send();
        return $status;
    }

}
