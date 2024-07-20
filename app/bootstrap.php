<?php

use App\Core\App;
use App\Core\Container;
use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use Twig\Environment;

define('APP_START', microtime(true));

session_start();

const BASE_PATH = __DIR__ . '/../';

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Register Dotenv
|--------------------------------------------------------------------------
|
| Basically, a .env file is an easy way to load custom configuration variables
| that your application needs without having to modify .htaccess files or
| Apache/nginx virtual hosts. This means you won't have to edit any files
| outside the project, and all the environment variables are always set no
| matter how you run your project - Apache, Nginx, CLI, and even PHP's built-in
| webserver. It's WAY easier than all the other ways you know of to set environment variables
|
*/
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);

try {
    $dotenv->load();
} catch (Exception $e) {
    throw new Exception('Missing .env file. Consider running "cp .env.example .env" in root.');
}

/*
|--------------------------------------------------------------------------
| Register Whoops Error Handler
|--------------------------------------------------------------------------
|
| PHP errors for cool kids
|
| Whoops is an error handler framework for PHP. Out-of-the-box,
| it provides a pretty error interface that helps you debug your web projects,
| but at heart it's a simple yet powerful stacked error handling system.
|
*/
$whoops = new Whoops\Run();

if ($_ENV['APP_ENV'] !== 'development') {
    // Force turning off errors in anything but development
    ini_set('display_errors', 0);
    error_reporting(-1);
} else {
    $whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());
}

$whoops->register();

/*
|--------------------------------------------------------------------------
| App Container Instance
|--------------------------------------------------------------------------
|
| Now that we've bootstrapped everything we need, registered our env variables
| and got error detection. Let's create an App container
| we can resolve singleton bindings out of!
|
*/

$container = new Container();

$container->bind(Database::class, function () {
    return new Database(
        config('database')
    );
});

/**
 * Auto register Repository bindings from repositories.php config
 */
$repositories = require base_path('/config/repositories.php');

foreach ($repositories as $repository) {
    $container->bind($repository, function () use ($repository) {
        return new $repository(
            App::resolve(Database::class)
        );
    });
}

/**
 * Auto register Services bindings from services.php config
 */
$services = require base_path('/config/services.php');

foreach ($services as $service => $bindings) {
    $container->bind($service, function () use ($service, $bindings) {
        $invoke_services = [];

        foreach ($bindings as $binding) {
            $invoke_services[] = App::resolve($binding);
        }

        return new $service(
            ...$invoke_services
        );
    });
}

App::setContainer($container);

/*
|--------------------------------------------------------------------------
| App Request/Response Instance
|--------------------------------------------------------------------------
|
| Bind our Request and Response Instances
| We are inheriting Symfony's Request/Response with additional features :)
|
*/
$container->bind(Request::class, function () {
    return Request::createFromGlobals();
});

$container->bind(Response::class, function () {
    return new Response();
});

App::setContainer($container);
