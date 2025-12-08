<?php

namespace App\Tests\Controller;

use App\Tests\AuthTest;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
final class RoutesControllerTest extends AuthTest
{
    private string $endpoint = '/api/v1/routes';

    private function post(array $payload, $authenticated = true): KernelBrowser
    {
        $client = $authenticated
            ? $this->createAuthenticatedClient()
            : static::createClient([], ['HTTPS' => true, 'HTTP_HOST' => 'api.localhost']);

        $client->request(
            'POST',
            $this->endpoint,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        return $client;
    }
    public function testIndex(): void
    {
        $client = $this->post([
            'fromStationId' => 'MX',
            'toStationId' => 'CABY',
            'analyticCode' => 'passager'
        ]);

        self::assertResponseIsSuccessful();

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertArrayHasKey('id', $responseData);
        self::assertArrayHasKey('path', $responseData);
        self::assertArrayHasKey('segments', $responseData);
        self::assertArrayHasKey('distanceKm', $responseData);

        self::assertIsArray($responseData['segments']);
        self::assertNotEmpty($responseData['segments']);
    }

    public function testMissingFromStation(): void
    {
        $client = $this->post([
            'toStationId' => 'CABY',
            'analyticCode' => 'passager'
        ]);

        self::assertResponseStatusCodeSame(400);

        $data = json_decode($client->getResponse()->getContent(), true);

        self::assertEquals('MISSING_PARAM', $data['code']);
        self::assertStringContainsString('fromStationId', $data['message']);
    }


    public function testMissingToStation(): void
    {
        $client = $this->post([
            'fromStationId' => 'MX',
            'analyticCode' => 'passager'
        ]);

        self::assertResponseStatusCodeSame(400);

        $data = json_decode($client->getResponse()->getContent(), true);

        self::assertEquals('MISSING_PARAM', $data['code']);
        self::assertStringContainsString('toStationId', $data['message']);
    }


    public function testMissingAnalyticCode(): void
    {
        $client = $this->post([
            'fromStationId' => 'MX',
            'toStationId' => 'CABY'
        ]);

        self::assertResponseStatusCodeSame(400);

        $data = json_decode($client->getResponse()->getContent(), true);

        self::assertEquals('MISSING_PARAM', $data['code']);
        self::assertStringContainsString('analyticCode', $data['message']);
    }


    public function testInvalidAnalyticCode(): void
    {
        $client = $this->post([
            'fromStationId' => 'MX',
            'toStationId' => 'CABY',
            'analyticCode' => 'unknown_code'
        ]);

        self::assertResponseStatusCodeSame(400);

        $data = json_decode($client->getResponse()->getContent(), true);

        self::assertEquals('ANALYTIC_CODE_NOT_FOUND', $data['code']);
        self::assertStringContainsString('unknown_code', $data['details'][0]);
    }


    public function testBadJson(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request(
            'POST',
            $this->endpoint,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{invalid json}'
        );

        self::assertResponseStatusCodeSame(400);
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertStringContainsString('INVALID_JSON', $response['code']);
    }


    public function testWrongMethod(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', $this->endpoint);

        self::assertResponseStatusCodeSame(405); // Method Not Allowed
    }


    public function testUnauthorized(): void
    {
        $client = $this->post([
            'fromStationId' => 'MX',
            'toStationId' => 'CABY',
            'analyticCode' => 'passager'
        ], authenticated: false);

        self::assertResponseStatusCodeSame(401);

        $raw = $client->getResponse()->getContent();
        self::assertIsString($raw);
        self::assertStringContainsString('Full authentication', $raw);
    }
}
