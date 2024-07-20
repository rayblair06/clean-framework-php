<?php

use App\Core\App;
use App\Core\Response;
use App\Core\View;

/*
|--------------------------------------------------------------------------
| Global Helper functions
|--------------------------------------------------------------------------
|
| Register global helper functions here
|
*/

if (!function_exists('base_path')) {
    /**
     * Return the base path
     *
     * @param string $path
     * @return string
     */
    function base_path(string $path = ''): string
    {
        return BASE_PATH . $path;
    }
}

if (!function_exists('config')) {
    /**
     * Returns the configuration items as array
     *
     * @param string $path
     * @return array|string|null
     */
    function config(string $path = ''): array|string|null
    {
        static $configs = null;

        if ($configs === null) {
            $configs = [];
            $configPath = base_path('config');
            foreach (glob("$configPath/*.php") as $filename) {
                $key = basename($filename, '.php');
                $configs[$key] = require $filename;
            }
        }

        if ($path === '') {
            return $configs;
        }

        $pathParts = explode('.', $path);
        $value = $configs;
        foreach ($pathParts as $part) {
            if (!isset($value[$part])) {
                return null;
            }
            $value = $value[$part];
        }

        return $value;
    }
}

if (!function_exists('dd')) {
    /**
     * Dump the value and die
     *
     * @param mixed $value
     * @return void
     */
    function dd($value): void
    {
        dump($value);
        exit();
    }
}

if (!function_exists('view')) {
    /**
     * Helper for rendering a 200 status page from a template file
     *
     * Renders view contents, sends response then dies
     *
     * @param string $path
     * @param array $attributes
     * @param int $status_code
     * @return void
     */
    function view(string $path, array $attributes = [], int $status_code = 200): void
    {
        // TODO: Prototyping Latte templating engine
        $templatePath = base_path("views/{$path}");

        $latte = new Latte\Engine();
        // cache directory
        $latte->setTempDirectory(base_path('/cache/views'));

        // $params = new Latte\TemplateParameters($attributes);

        $content = $latte->renderToString($templatePath, $attributes);

        $response = App::resolve(Response::class);

        $response->setContent($content);
        $response->headers->set('Content-Type', 'text/html');

        match ($status_code) {
            200 => $response->setStatusCode(Response::HTTP_OK),
            404 => $response->setStatusCode(Response::HTTP_NOT_FOUND),
            500 => $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR),
        };

        $response->send();

        exit();
    }
}

if (!function_exists('e')) {
    /**
     * Convert special characters to HTML entities and return output
     *
     * Use this for safely echoing out strings
     *
     * @param string $string
     * @return string
     */
    function e(string $string): string
    {
        return htmlspecialchars($string, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('asset')) {
    /**
     * Return absolute urls for assets
     *
     * @param string $asset_path
     * @return string
     */
    function asset(string $asset_path = ''): string
    {
        return $_ENV['APP_URL'] . '/' . $asset_path;
    }
}

if (!function_exists('url')) {
    /**
     * Return absolute urls for url
     *
     * @param string $url_path
     * @param array $query
     * @return string
     */
    function url(string $url_path = '', array $query = []): string
    {
        $url = $_ENV['APP_URL'] . '/' . $url_path;

        $url = preg_replace('#(?<=http://|https://)([^/]+)/+#', '$1/', $url); // Remove duplicate slashes after http://

        if (count($query) === 0) {
            return $url;
        }

        return $url . '?' . http_build_query($query);
    }
}

if (!function_exists('abort')) {
    /**
     * Abort HTTP uri request
     *
     * @param string $code
     * @return void
     */
    function abort(string $code = '404'): void
    {
        view(
            '404.twig',
            [],
            $code
        );
    }
}
