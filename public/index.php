<?php

declare(strict_types=1);

use App\Bootstrap;
use App\Controller\AdminController;
use App\Controller\LandingController;
use App\Http\Request;
use App\Http\Router;

$basePath = dirname(__DIR__);

require $basePath . '/vendor/autoload.php';

Bootstrap::init($basePath);

$view = Bootstrap::view($basePath);
$landing = new LandingController($view, $basePath);
$admin = new AdminController($view);

$router = new Router();

$router->get('/', [$landing, 'index']);
$router->post('/request', [$landing, 'submit']);

$router->get('/admin/login', [$admin, 'loginForm']);
$router->post('/admin/login', [$admin, 'login']);
$router->post('/admin/logout', [$admin, 'logout']);
$router->get('/admin', [$admin, 'index']);
$router->post('/admin/status', [$admin, 'updateStatus']);

$request = Request::fromGlobals();
$router->dispatch($request)->send();
