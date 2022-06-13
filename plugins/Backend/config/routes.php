<?php
use Cake\Routing\Router;

Router::plugin(
    'Backend',
    ['path' => '/backend'],
    function ($routes) {
        $routes->connect('/login', ['controller' => 'Authen', 'action' => 'login']);
        $routes->connect('/logout', ['controller' => 'Authen', 'action' => 'logout']);
        $routes->connect('/forgot', ['controller' => 'Authen', 'action' => 'forgot']);
        $routes->fallbacks('DashedRoute');
    }
);
