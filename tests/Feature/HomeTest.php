<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class HomeTest extends TestCase
{
    public function testAboutRoute()
    {
        $response = $this
            ->get('/');

        // dd($response);

        $this->assertEquals(200, $response->getStatusCode());


        $this->assertStringContainsString(
            'Hello World',
            $response->getBody()->getContents()
        );
    }
}
