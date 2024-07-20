<?php

namespace App\Core;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class Response extends SymfonyResponse
{
    /**
     * Create a JSON response.
     *
     * @param mixed $data The data to be converted to JSON.
     * @param int $status HTTP status code.
     * @param array $headers Additional headers.
     * @return JsonResponse
     */
    public static function json($data, int $status = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    /**
     * Add common security headers to the response.
     *
     * @return SymfonyResponse
     */
    public function secureHeaders(): SymfonyResponse
    {
        $this->headers->set('X-Content-Type-Options', 'nosniff');
        $this->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $this->headers->set('X-XSS-Protection', '1; mode=block');

        return $this;
    }

    /**
     * Set cache control to no-cache.
     *
     * @return SymfonyResponse
     */
    public function noCache(): SymfonyResponse
    {
        $this->headers->addCacheControlDirective('no-cache', true);
        $this->headers->addCacheControlDirective('must-revalidate', true);

        return $this;
    }

    /**
     * A quick way to set the Content-Type of the response.
     *
     * @param string $type The MIME type to set.
     * @return SymfonyResponse
     */
    public function contentType(string $type): SymfonyResponse
    {
        $this->headers->set('Content-Type', $type);

        return $this;
    }
}
