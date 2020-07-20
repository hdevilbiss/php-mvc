<?php
// 2020-03-29 PHP 7.3.9
/* FRONT CONTROLLER */

/* Require the Composer Autoloader for class autoloading */
require_once dirname(__DIR__) . '/vendor/autoload.php';

/* Create the error and exception handlers */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/* Sessions */
session_start();//placed after the error handlers

/* Create a Router object from the Core namespace */
$router = new Core\Router();

/* Create Routing Table */
$router->add('',['controller'=>'Home','action'=>'index']);
// The second parameter, an array, is an optional array, which can specify controllers and actions, as well as optional parameters, such as namespaces, tokens, and keys.

/* Routes without parameters */
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');

//Admin namespace
$router->add(
    'admin/{controller}/{action}',
    [
    'namespace'=>'Admin'
    ]
);

//Login Action
$router->add(
    'login',
    [
        'controller'=>'Login',
        'action'=>'new'
        ]
    );

//Logout Action
$router->add(
    'logout',
    [
        'controller'=>'Login',
        'action'=>'destroy'
        ]
);


// New User activation route
$router->add(
    'signup/activate/{token:[\da-f]+}',
    [
        'controller' => 'Signup',
        'action' => 'activate'
    ]
);

//Password Reset Action using hexdex token
$router->add(
    'password/reset/{token:[\da-f]+}',
    [
        'controller' => 'Password',
        'action' => 'reset'
    ]
);


//var_dump($router->getRoutes());

/* DISPATCH THE CURRENT URL */
$router->dispatch($_SERVER['QUERY_STRING']);
?>