<?php

namespace App\Tests\Controller;

use App\Tests\AuthTest;

final class RoutesControllerTest extends AuthTest
{
    public function testIndex(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request('POST', '/api/v1/routes', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'fromStationId' => 'MX',
                'toStationId' => 'CABY',
                'analyticCode' => 'passager'
            ])
        );

        self::assertResponseIsSuccessful();

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertArrayHasKey('path', $responseData);
        self::assertArrayHasKey('segments', $responseData);
        self::assertArrayHasKey('distanceKm', $responseData);

        self::assertNotEmpty($responseData['segments']);
    }
}
