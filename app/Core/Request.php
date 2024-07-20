<?php

namespace App\Core;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class Request extends SymfonyRequest
{
    /**
     * Get a specific input value.
     *
     * @param string $key The key of the input parameter.
     * @param mixed $default Default value if the key is not found.
     * @return mixed
     */
    public function input(string $key, $default = null)
    {
        return $this->get($key, $default);
    }

    /**
     * Get payload data as an array from a JSON request.
     *
     * @return array|null
     */
    public function getJsonPayload(): ?array
    {
        $data = json_decode($this->getContent(), true);

        return is_array($data) ? $data : null;
    }

    /**
     * Determines if the request is of type JSON.
     *
     * @return bool
     */
    public function isJson(): bool
    {
        return 0 === strpos($this->headers->get('Content-Type'), 'application/json');
    }

    /**
     * Get the client's IP address.
     *
     * @return string|null
     */
    public function clientIp(): ?string
    {
        return $this->getClientIp();
    }

    /**
     * Get the full URL of the request.
     *
     * @return string
     */
    public function fullUrl(): string
    {
        return $this->getSchemeAndHttpHost() . $this->getRequestUri();
    }

    /**
     * Check if the request is an AJAX request.
     *
     * @return bool
     */
    public function isAjax(): bool
    {
        return 'XMLHttpRequest' == $this->headers->get('X-Requested-With');
    }
}
