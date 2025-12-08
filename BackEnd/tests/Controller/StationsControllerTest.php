<?php

namespace App\Tests\Controller;

use App\Tests\AuthTest;

final class StationsControllerTest extends AuthTest
{
    public function testIndex(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/v1/stations');

        self::assertResponseIsSuccessful();
    }

    public function testStationsUnauthorized(): void
    {
        $client = static::createClient([], [
            'HTTPS' => true,
            'HTTP_HOST' => 'api.localhost',
        ]);

        $client->request('GET', '/api/v1/stations');

        $this->assertResponseStatusCodeSame(401);

        $content = $client->getResponse()->getContent();

        $this->assertIsString($content);

        $this->assertStringContainsString('Full authentication is required', $content);
    }

}
