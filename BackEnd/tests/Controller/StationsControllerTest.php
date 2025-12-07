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
}
