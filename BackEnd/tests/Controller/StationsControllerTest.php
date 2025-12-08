<?php

namespace App\Tests\Controller;

use App\Tests\AuthControllerTest;

final class StationsControllerControllerTest extends AuthControllerTest
{
    public function testIndex(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/v1/stations');

        self::assertResponseIsSuccessful();
    }
}
