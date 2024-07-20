<?php

namespace App\Http;

/*
|--------------------------------------------------------------------------
| Base Controller
|--------------------------------------------------------------------------
|
| Our base controller that will bootstrap anything we need for HTTP
|
*/
class BaseController
{
    /**
     * Redirect to a given URL.
     *
     * This method provides a simple way to perform HTTP redirects.
     *
     * @param string $url The URL to redirect to.
     * @param int $statusCode HTTP status code for the redirect, default is 302.
     * @return void
     */
    protected function redirect(string $url, int $statusCode = 302): void
    {
        header('Location: ' . $url, true, $statusCode);

        exit;
    }

    /**
     * Set a session variable.
     *
     * @param string $key Session key.
     * @param mixed $value Session value.
     * @return void
     */
    protected function setSession(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session variable.
     *
     * @param string $key Session key.
     * @return mixed Session value or null if the key does not exist.
     */
    protected function getSession(string $key)
    {
        return $_SESSION[$key] ?? null;
    }
}
