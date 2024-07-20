<?php

namespace App\Core;

final class Router
{
    /**
     * Routes
     *
     * @var array
     */
    protected array $routes = [];

    /**
     * The controller namespace
     *
     * @var string|null
     */
    protected string $namespace = 'app/Http/Controllers';

    /**
     * Add route to route instance variable
     *
     * @param string $method
     * @param string $uri
     * @param string $controller
     * @return void
     */
    private function add(string $method, string $uri, string $controller): void
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
        ];
    }

    /**
     * Add GET route
     *
     * @param string|array $uri
     * @param string $controller
     * @return void
     */
    public function get(string|array $uri, string $controller): void
    {
        if (is_string($uri)) {
            $this->add('GET', $uri, $controller);
        } else {
            foreach ($uri as $link) {
                $this->add('GET', $link, $controller);
            }
        }
    }

    /**
     * Add POST route
     *
     * @param string|array $uri
     * @param string $controller
     * @return void
     */
    public function post(string|array $uri, string $controller): void
    {
        if (is_string($uri)) {
            $this->add('POST', $uri, $controller);
        } else {
            foreach ($uri as $link) {
                $this->add('POST', $link, $controller);
            }
        }
    }

    /**
     * Add PATCH route
     *
     * @param string|array $uri
     * @param string $controller
     * @return void
     */
    public function patch(string|array $uri, string $controller): void
    {
        if (is_string($uri)) {
            $this->add('PATCH', $uri, $controller);
        } else {
            foreach ($uri as $link) {
                $this->add('PATCH', $link, $controller);
            }
        }
    }

    /**
     * Add PUT route
     *
     * @param string|array $uri
     * @param string $controller
     * @return void
     */
    public function put(string|array $uri, string $controller): void
    {
        if (is_string($uri)) {
            $this->add('PUT', $uri, $controller);
        } else {
            foreach ($uri as $link) {
                $this->add('PUT', $link, $controller);
            }
        }
    }

    /**
     * Add DELETE route
     *
     * @param string|array $uri
     * @param string $controller
     * @return void
     */
    public function delete(string|array $uri, string $controller): void
    {
        if (is_string($uri)) {
            $this->add('DELETE', $uri, $controller);
        } else {
            foreach ($uri as $link) {
                $this->add('DELETE', $link, $controller);
            }
        }
    }

    /**
     * Get the given Namespace
     *
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * Return Controller to associated uri path and method
     *
     * @param string $uri
     * @param string $method
     * @return mixed
     */
    public function route(string $uri, string $method)
    {
        // Loop all routes
        foreach ($this->routes as $route) {
            // get routing matches
            $is_match = $this->patternMatches($route['uri'], $uri, $matches, PREG_OFFSET_CAPTURE);

            // is there a valid match?
            if ($is_match && $route['method'] === strtoupper($method)) {
                // Rework matches to only contain the matches, not the orig string
                $matches = array_slice($matches, 1);

                // Extract the matched URL parameters (and only the parameters)
                $params = array_map(function ($match, $index) use ($matches) {
                    // We have a following parameter: take the substring from the current param position until the next one's position (thank you PREG_OFFSET_CAPTURE)
                    if (isset($matches[$index + 1]) && isset($matches[$index + 1][0]) && is_array($matches[$index + 1][0])) {
                        if ($matches[$index + 1][0][1] > -1) {
                            return trim(substr($match[0][0], 0, $matches[$index + 1][0][1] - $match[0][1]), '/');
                        }
                    } // We have no following parameters: return the whole lot

                    return isset($match[0][0]) && $match[0][1] != -1 ? trim($match[0][0], '/') : null;
                }, $matches, array_keys($matches));

                // If the full class path isn't specified, try to resolve it
                if (!class_exists($route['controller'])) {
                    return require base_path($this->getNamespace() . $route['controller']);
                }

                // Resolve controller
                $controller = new $route['controller']();

                return $controller(...$params);
            }
        }

        // No match, return false
        return false;
    }

    /**
     * Replace all curly braces matches {} into word patterns (like Laravel)
     * Checks if there is a routing match
     *
     * @param string $pattern
     * @param string $uri
     * @param array $matches
     * @return bool
     */
    private function patternMatches(string $pattern, string $uri, ?array &$matches): bool
    {
        // Replace all curly braces matches {} into word patterns (like Laravel)
        $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $pattern);

        // we may have a match!
        return boolval(preg_match_all('#^' . $pattern . '$#', $uri, $matches, PREG_OFFSET_CAPTURE));
    }
}
