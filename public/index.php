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
// The second parameter, an array, is an optional array to specify controllers, actions, namespaces, etc

/* Routes without parameters */
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');

//Admin namespace
$router->add('admin/{controller}/{action}',['namespace'=>'Admin']);

//Login
$router->add('login',['controller'=>'Login','action'=>'new']);
$router->add('logout',['controller'=>'Login','action'=>'destroy']);

//var_dump($router->getRoutes());

/* DISPATCH THE CURRENT URL */
$router->dispatch($_SERVER['QUERY_STRING']);
?>