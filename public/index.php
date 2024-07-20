<?php

use App\Core\Router;

/*
|--------------------------------------------------------------------------
| Bootstrap Application (Main)
|--------------------------------------------------------------------------
|
| Bootstrap our main website application with everything we might need
| Autoloading, dotenv, error handling
|
*/
require_once __DIR__ . '/../app/bootstrap.php';

/*
|--------------------------------------------------------------------------
| Router
|--------------------------------------------------------------------------
|
| Bootstrap our Router and import our defined routes
|
*/
$router = new Router();

$routes = [
    require base_path('/routes/web.php'),
];

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['method'] ?? $_SERVER['REQUEST_METHOD'];

$route = $router->route(
    $uri,
    $method
);

if (!$route) {
    abort();
}
