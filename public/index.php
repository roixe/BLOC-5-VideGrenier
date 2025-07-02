<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * Front controller
 *
 * PHP version 7.0
 */

session_start();

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'UserController', 'action' => 'login']);
$router->add('register', ['controller' => 'UserController', 'action' => 'register']);
$router->add('logout', ['controller' => 'UserController', 'action' => 'logout', 'private' => true]);
$router->add('account', ['controller' => 'UserController', 'action' => 'account', 'private' => true]);
$router->add('product', ['controller' => 'Product', 'action' => 'index', 'private' => true]);
$router->add('product/{id:\d+}', ['controller' => 'Product', 'action' => 'show']);
$router->add('product/contact/{id:\d+}', ['controller' => 'Product', 'action' => 'contact', 'params' => ['id']]);
$router->add('{controller}/{action}');

/*
 * Gestion des erreurs dans le routing
 */
try {
    $router->dispatch($_SERVER['QUERY_STRING']);
} catch(Exception $e){
    switch($e->getMessage()){
        case 'You must be logged in':
            header('Location: /login');
            break;
    }
}
