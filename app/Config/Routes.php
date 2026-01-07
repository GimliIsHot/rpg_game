<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'MainController::index');
$routes->get('fetchNpc', 'MainController::fetchNpcInfo');
$routes->get('EnterOutskirts', 'MainController::Outskirts');