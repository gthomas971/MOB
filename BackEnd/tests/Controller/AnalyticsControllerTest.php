<?php

namespace App\Tests\Controller;

use App\Tests\AuthTest;

final class AnalyticsControllerTest extends AuthTest
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
