<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

$routes->group('request', ['filter' => 'auth'], function($routes){

    // USER
    $routes->get('/', 'RequestController::index', ['filter'=>'role:user']);
    $routes->get('new', 'RequestController::new', ['filter'=>'role:user']);
    $routes->post('create', 'RequestController::create', ['filter'=>'role:user']);


    $routes->get('edit/(:num)', 'RequestController::edit/$1',['filter'=>'role:user']);
    $routes->post('resubmit/(:num)', 'RequestController::resubmit/$1',['filter'=>'role:user']);

    // MANAGER
    $routes->get('manager/(:num)/(:segment)', 
        'RequestController::managerAction/$1/$2',
        ['filter'=>'role:manager']
    );

    // ADMIN
    $routes->get('admin/(:num)/(:segment)', 
        'RequestController::adminAction/$1/$2',
        ['filter'=>'role:admin']
    );

    $routes->get('resubmit/(:num)', 
        'RequestController::resubmit/$1',
        ['filter'=>'role:user']
    );

    $routes->get('ajax-sort', 'RequestController::ajaxSort', ['filter'=>'role:user']);
    $routes->get('ajax-filter', 'RequestController::ajaxFilter', ['filter'=>'role:user']);

});


$routes->post('manager/action','RequestController::managerAction',['filter'=>'role:manager']);
$routes->get('manager/ajax-filter', 'RequestController::managerFilter', ['filter'=>'role:manager']);

$routes->post('admin/action','RequestController::adminAction',['filter'=>'role:admin']);

$routes->get('admin/history/(:num)','Admin::history/$1',['filter'=>'role:admin']);

// $routes->post('ajax/update-status','RequestController::ajaxUpdateStatus',['filter'=>'auth']);
$routes->get('admin/ajax-filter', 'RequestController::adminFilter', ['filter'=>'role:admin']);
$routes->get('admin/request/(:num)/history', 'RequestController::viewLogs/$1', ['filter'=>'role:admin']);

