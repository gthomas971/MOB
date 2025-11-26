<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RoutesControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/routes', [
            'start' => 'MX',
            'end' => 'CABY'
        ]);

        self::assertResponseIsSuccessful();

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertArrayHasKey('path', $responseData);
        self::assertArrayHasKey('segments', $responseData);
        self::assertArrayHasKey('totalDistance', $responseData);

        self::assertNotEmpty($responseData['segments']);
    }
}
