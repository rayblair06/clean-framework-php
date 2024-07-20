<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client([
            'base_uri' => 'http://localhost',
            // 'timeout'  => 2.0,
            'http_errors' => false,
        ]);
    }

    public function get($uri, $attributes = [])
    {
        $response = $this->client->request(
            'GET',
            $uri
            // json_encode($data)
        );

        return $response;
    }
}
