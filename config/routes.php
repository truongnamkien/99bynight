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
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/*
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
    // Register scoped middleware for in scopes.
//    $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware([
//                'httpOnly' => true,
//    ]));

    /*
     * Apply a middleware to the current route scope.
     * Requires middleware to be registered through `Application::routes()` with `registerMiddleware()`
     */
//    $routes->applyMiddleware('csrf');

    /*
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home', LANGUAGE_VIETNAMESE]);
    $routes->connect('/en', ['controller' => 'Pages', 'action' => 'display', 'home', LANGUAGE_ENGLISH]);
    $routes->connect('/vi', ['controller' => 'Pages', 'action' => 'display', 'home', LANGUAGE_VIETNAMESE]);

    $routes->connect('/contact', ['controller' => 'Pages', 'action' => 'display', 'contact', LANGUAGE_ENGLISH]);
    $routes->connect('/lien-he', ['controller' => 'Pages', 'action' => 'display', 'contact', LANGUAGE_VIETNAMESE]);
    $routes->connect('/contact/submit', ['controller' => 'Pages', 'action' => 'contactSubmit']);

    $routes->connect('/pages/:slug',
        ['controller' => 'Pages', 'action' => 'detail', LANGUAGE_ENGLISH],
        ['pass' => ['slug']]
    );
    $routes->connect('/thong-tin/:slug',
        ['controller' => 'Pages', 'action' => 'detail', LANGUAGE_VIETNAMESE],
        ['pass' => ['slug']]
    );

    $routes->connect('/loadBlogs', ['controller' => 'Blogs', 'action' => 'loadBlogs']);
    $routes->connect('/blogs', ['controller' => 'Blogs', 'action' => 'index', LANGUAGE_ENGLISH]);
    $routes->connect('/tin-tuc', ['controller' => 'Blogs', 'action' => 'index',  LANGUAGE_VIETNAMESE]);
    $routes->connect('/blogs/category/:slug',
        ['controller' => 'Blogs', 'action' => 'category', LANGUAGE_ENGLISH],
        ['pass' => ['slug']]
    );
    $routes->connect('/tin-tuc/chu-de/:slug',
        ['controller' => 'Blogs', 'action' => 'category', LANGUAGE_VIETNAMESE],
        ['pass' => ['slug']]
    );
    $routes->connect('/blogs/detail/:slug',
        ['controller' => 'Blogs', 'action' => 'detail', LANGUAGE_ENGLISH],
        ['pass' => ['slug']]
    );
    $routes->connect('/tin-tuc/chi-tiet/:slug',
        ['controller' => 'Blogs', 'action' => 'detail', LANGUAGE_VIETNAMESE],
        ['pass' => ['slug']]
    );

    $routes->connect('/specialists', ['controller' => 'Specialists', 'action' => 'index', LANGUAGE_ENGLISH]);
    $routes->connect('/chuyen-khoa', ['controller' => 'Specialists', 'action' => 'index',  LANGUAGE_VIETNAMESE]);
    $routes->connect('/specialists/category/:slug',
        ['controller' => 'Specialists', 'action' => 'category', LANGUAGE_ENGLISH],
        ['pass' => ['slug']]
    );
    $routes->connect('/chuyen-khoa/danh-sach/:slug',
        ['controller' => 'Specialists', 'action' => 'category', LANGUAGE_VIETNAMESE],
        ['pass' => ['slug']]
    );
    $routes->connect('/specialists/detail/:slug',
        ['controller' => 'Specialists', 'action' => 'detail', LANGUAGE_ENGLISH],
        ['pass' => ['slug']]
    );
    $routes->connect('/chuyen-khoa/chi-tiet/:slug',
        ['controller' => 'Specialists', 'action' => 'detail', LANGUAGE_VIETNAMESE],
        ['pass' => ['slug']]
    );

    $routes->connect('/healthcares', ['controller' => 'HealthCares', 'action' => 'index', LANGUAGE_ENGLISH]);
    $routes->connect('/kham-suc-khoe', ['controller' => 'HealthCares', 'action' => 'index',  LANGUAGE_VIETNAMESE]);
    $routes->connect('/healthcares/package/:slug',
        ['controller' => 'HealthCares', 'action' => 'package', LANGUAGE_ENGLISH],
        ['pass' => ['slug']]
    );
    $routes->connect('/kham-suc-khoe/chuong-trinh/:slug',
        ['controller' => 'HealthCares', 'action' => 'package', LANGUAGE_VIETNAMESE],
        ['pass' => ['slug']]
    );
    $routes->connect('/healthcares/detail/:slug',
        ['controller' => 'HealthCares', 'action' => 'detail', LANGUAGE_ENGLISH],
        ['pass' => ['slug']]
    );
    $routes->connect('/kham-suc-khoe/chi-tiet/:slug',
        ['controller' => 'HealthCares', 'action' => 'detail', LANGUAGE_VIETNAMESE],
        ['pass' => ['slug']]
    );

    $routes->connect('/services/:slug',
        ['controller' => 'Services', 'action' => 'detail', LANGUAGE_ENGLISH],
        ['pass' => ['slug']]
    );
    $routes->connect('/dich-vu/:slug',
        ['controller' => 'Services', 'action' => 'detail', LANGUAGE_VIETNAMESE],
        ['pass' => ['slug']]
    );
    $routes->connect('/backend', ['controller' => 'Authen', 'action' => 'login', 'plugin' => 'Backend']);

    /*
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *
     * ```
     * $routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);
     * $routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);
     * ```
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
    $routes->fallbacks(DashedRoute::class);
});

/*
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * Router::scope('/api', function (RouteBuilder $routes) {
 *     // No $routes->applyMiddleware() here.
 *     // Connect API actions here.
 * });
 * ```
 */
