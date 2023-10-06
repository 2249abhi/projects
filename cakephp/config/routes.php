<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Cache\Cache;
use Cake\Core\Plugin;
use Cake\ORM\TableRegistry;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;
use Cake\Utility\Hash;
use Cake\Utility\Text;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 * Cache: Routes are cached to improve performance, check the RoutingMiddleware
 * constructor in your `src/Application.php` file to change this behavior.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    //Get Language
    $languages = Cache::read('site-language', 'languages');
    if ($languages === false) {
        $languageModel = TableRegistry::get('Languages');
        $getLanguages  = $languageModel->languageCache();
        $languages     = Cache::read('site-language', 'languages');
    }
    $dLang = false;
    if ($languages !== false) {
        $dLang = Hash::extract($languages, '{n}[is_default=1].culture');
        $dLang = (!empty($dLang)) ? $dLang[0] : false;
        $languages = Hash::extract($languages, '{n}.culture');
        $languages = implode('|', $languages);
    }
    /**
     * Home Page
     */
    $hOption = [
        'controller' => 'Home',
        'action'     => 'index',
    ];
    if (!empty($languages)) {
        //URL:- /:language/
        $routes->connect('/:language/', $hOption)
            ->setPatterns(['language' => $languages])
            ->setPersist(['language']);
    }
    //URL:- /
    $routes->connect('/', $hOption);

    //Get Articles
    $articleLinks = Cache::read('rewrite_rules', 'articles');
    if ($articleLinks === false) {
        $articleModel = TableRegistry::get('Articles');
        $getArticles  = $articleModel->articleCache();
        $articleLinks = Cache::read('rewrite_rules', 'articles');
    }
    if ($articleLinks !== false) {
        foreach ($articleLinks as $articleLink) {
            //Language Translations Url
            if (!empty($articleLink['url'])) {
                //URL:- /:language/<custom>
                $articleUrl = strtolower(Text::slug($articleLink['url']));
                $linkOption = [
                    'controller' => 'Articles',
                    'action'     => 'page',
                    'id'         => $articleLink['id'],
                ];
                if (!empty($languages)) {
                    $routes->connect('/:language/' . $articleUrl, $linkOption)
                        ->setPass(['id'])
                        ->setPatterns(['language' => $languages])
                        ->setPersist(['language']);
                }
                //URL:- /<custom>
                $routes->connect('/' . $articleUrl, $linkOption, ['pass' => ['id']]);
            }
        }
    }

    //Default Artical /page/:id and /:language/page/:id Route
    //URL:- /:language/page/:id
    if (!empty($languages)) {
        $routes->connect(
            '/:language/page/:id',
            ['controller' => 'Articles', 'action' => 'page']
        )
        ->setPass(['id'])
        ->setPatterns(['id' => '\d+', 'language' => $languages])
        ->setPersist(['language']);
    }
    //URL:- /page/:id
    $routes->connect(
        '/page/:id',
        ['controller' => 'Articles', 'action' => 'page'],
        ['id' => '\d+', 'pass' => ['id']]
    );

    //Get News
    $newsLinks = Cache::read('rewrite_rules', 'news');
    if ($newsLinks === false) {
        $newsModel = TableRegistry::get('News');
        $getNews   = $newsModel->newsCache();
        $newsLinks = Cache::read('rewrite_rules', 'news');
    }
    
    if ($newsLinks !== false) {
        foreach ($newsLinks as $newsLink) {
            //Language Translations Url
            if (!empty($newsLink['news_url'])) {
                //URL:- /:language/<custom>
                $newsUrl = strtolower(Text::slug($newsLink['news_url']));
                $linkOption = [
                    'controller' => 'News',
                    'action'     => 'page',
                    'id'         => $newsLink['id'],
                ];
                if (!empty($languages)) {
                    $routes->connect('/:language/news/' . $newsUrl, $linkOption)
                        ->setPass(['id'])
                        ->setPatterns(['language' => $languages])
                        ->setPersist(['language']);
                }
                //URL:- /<custom>
                $routes->connect('/news/' . $newsUrl, $linkOption, ['pass' => ['id']]);
            }
        }
    }

    //Default News /page/:id and /:language/page/:id Route
    //URL:- /:language/page/:id
    if (!empty($languages)) {
        $routes->connect(
            '/:language/page/:id',
            ['controller' => 'News', 'action' => 'page']
        )
        ->setPass(['id'])
        ->setPatterns(['id' => '\d+', 'language' => $languages])
        ->setPersist(['language']);
    }
    //URL:- /page/:id
    $routes->connect(
        '/page/:id',
        ['controller' => 'News', 'action' => 'page'],
        ['id' => '\d+', 'pass' => ['id']]
    );


    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    if (!empty($languages)) {
        $routes->connect('/:language/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute'])->setPatterns(['language' => $languages])->setPersist(['language']);
        $routes->connect('/:language/:controller/:action/*', [], ['routeClass' => 'DashedRoute'])->setPatterns(['language' => $languages])->setPersist(['language']);
    }
    $routes->fallbacks(DashedRoute::class);
});

//Admin Routes
Router::prefix('admin', function (RouteBuilder $routes) {

    //Get Language
    $languages = Cache::read('site-language', 'languages');
    if ($languages === false) {
        $languageModel = TableRegistry::get('Languages');
        $getLanguages  = $languageModel->languageCache();
        $languages     = Cache::read('site-language', 'languages');
    }
    $dLang = false;
    if ($languages !== false) {
        $dLang = Hash::extract($languages, '{n}[is_default=1].culture');
        $dLang = (!empty($dLang)) ? $dLang[0] : false;
        $languages = Hash::extract($languages, '{n}.culture');
        $languages = implode('|', $languages);
    }

    /**
     * Here, we are connecting '/' (base path) to a controller called 'Dashboard',
     * its action called 'index', and we pass a param to select the view file
     * to use (in this case, src/Template/Admin/Dashboard/index.ctp)...
     */
    $dashboard = [
        'controller' => 'Dashboard',
        'action'     => 'index',
        'prefix'     => 'admin'
    ];
    if (!empty($languages)) {
        //URL:- /:language/
        $routes->connect('/:language/', $dashboard)
            ->setPatterns(['language' => $languages])
            ->setPersist(['language']);
    }
    $routes->connect('/', $dashboard);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    if (!empty($languages)) {
        $routes->connect('/:language/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute'])->setPatterns(['language' => $languages])->setPersist(['language']);
        $routes->connect('/:language/:controller/:action/*', [], ['routeClass' => 'DashedRoute'])->setPatterns(['language' => $languages])->setPersist(['language']);
    }
    $routes->fallbacks(DashedRoute::class);
});

//Api Routes
Router::prefix('Api', function (RouteBuilder $routes) {
    $routes->setExtensions(['json','xml']);

    /**
     * Here, we are connecting '/' (base path) to a controller called 'Dashboard',
     * its action called 'index', and we pass a param to select the view file
     * to use (in this case, src/Template/Admin/Dashboard/index.ctp)...
     */
    $dashboard = [
        'controller' => 'Dashboard',
        'action'     => 'index',
    ];

    $routes->connect('/', $dashboard);
    
    $routes->fallbacks(DashedRoute::class);
});
