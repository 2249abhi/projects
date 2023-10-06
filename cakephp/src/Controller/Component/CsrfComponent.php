<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\Component;

use Cake\Controller\Component\CsrfComponent as CakeCsrfComponent;
use Cake\Event\Event;
use Cake\Controller\Component;
use Cake\Http\Exception\InvalidCsrfTokenException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\I18n\Time;
use Cake\Utility\Security;


/**
 * Provides CSRF protection & validation.
 *
 * This component adds a CSRF token to a cookie. The cookie value is compared to
 * request data, or the X-CSRF-Token header on each PATCH, POST,
 * PUT, or DELETE request.
 *
 * If the request data is missing or does not match the cookie data,
 * an InvalidCsrfTokenException will be raised.
 *
 * This component integrates with the FormHelper automatically and when
 * used together your forms will have CSRF tokens automatically added
 * when `$this->Form->create(...)` is used in a view.
 *
 * @deprecated 3.5.0 Use Cake\Http\Middleware\CsrfProtectionMiddleware instead.
 */
class CsrfComponent extends CakeCsrfComponent
{

    
    public function startup(Event $event)
    {
        $controller = $event->getSubject();
        $request = $controller->getRequest();
        $session = $request->getSession();
        $response = $controller->getResponse();
        $cookieName = $this->_config['cookieName'];

        $cookieData = $session->read($cookieName);
        //$cookieData = $request->getCookie($cookieName);
        if ($cookieData) {
            $request = $request->withParam('_csrfToken', $cookieData);
        }

        if ($request->is('requested')) {
            $controller->setRequest($request);

            return;
        }

        if ($request->is('get') && $cookieData === null) {
            list($request, $response) = $this->_setCookie($request, $response);
            $controller->setResponse($response);
        }
        if ($request->is(['put', 'post', 'delete', 'patch']) || $request->getData()) {
            $this->_validateToken($request);
            $request = $request->withoutData($this->_config['field']);
            if(!$request->is(['ajax'])){
                list($request, $response) = $this->_setCookie($request, $response);
                $controller->setResponse($response);
            }
        }
        $controller->setRequest($request);
    }

    /**
     * Set the cookie in the response.
     *
     * Also sets the request->params['_csrfToken'] so the newly minted
     * token is available in the request data.
     *
     * @param \Cake\Http\ServerRequest $request The request object.
     * @param \Cake\Http\Response $response The response object.
     * @return array An array of the modified request, response.
     */
    protected function _setCookie(ServerRequest $request, Response $response)
    {
        $expiry = new Time($this->_config['expiry']);
        $value = hash('sha512', Security::randomBytes(16), false);

        $request = $request->withParam('_csrfToken', $value);
        $request->getSession()->write($this->_config['cookieName'],$value);
        /*$response = $response->withCookie($this->_config['cookieName'], [
            'value' => $value,
            'expire' => $expiry->format('U'),
            'path' => $request->getAttribute('webroot'),
            'secure' => $this->_config['secure'],
            'httpOnly' => $this->_config['httpOnly'],
        ]);*/

        return [$request, $response];
    }

    /**
     * Validate the request data against the cookie token.
     *
     * @param \Cake\Http\ServerRequest $request The request to validate against.
     * @throws \Cake\Http\Exception\InvalidCsrfTokenException when the CSRF token is invalid or missing.
     * @return void
     */
    protected function _validateToken(ServerRequest $request)
    {
        $cookie = $request->getSession()->read($this->_config['cookieName']);
        $post = $request->getData($this->_config['field']);
        $header = $request->getHeaderLine('X-CSRF-Token');

        if (!$cookie) {
            throw new InvalidCsrfTokenException(__d('cake', 'Missing CSRF token cookie'));
        }

        if (!Security::constantEquals($post, $cookie) && !Security::constantEquals($header, $cookie)) {
            throw new InvalidCsrfTokenException(__d('cake', 'CSRF token mismatch.'));
        }
    }
}
