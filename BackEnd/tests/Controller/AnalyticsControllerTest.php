<?php

namespace App\Tests\Controller;

use App\Tests\AuthControllerTest;

final class AnalyticsControllerControllerTest extends AuthControllerTest
{
    public function testIndex(): void
    {
        $now = new \DateTime();
        $lastYear = $now->sub(new \DateInterval('P1Y'));

        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/api/v1/stations', [
            'from' => $lastYear->format('Y-m-d'),
            'to'   => $now->format('Y-m-d'),
        ]);

        self::assertResponseIsSuccessful();


    }
}
