<?php

namespace App\Tests\Controller;

use App\Tests\AuthTest;

final class AnalyticsControllerTest extends AuthTest
{
    private string $endpoint = '/api/v1/stats/distances';
    public function testDistancesSuccess(): void
    {
        $now = new \DateTimeImmutable();
        $lastYear = $now->sub(new \DateInterval('P1Y'));

        $client = $this->createAuthenticatedClient();

        $client->request(
            'GET',
            sprintf(
                $this->endpoint.'?from=%s&to=%s&groupBy=month',
                $lastYear->format('Y-m-d'),
                $now->format('Y-m-d')
            )
        );

        self::assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('items', $response);
        $this->assertEquals('month', $response['groupBy']);
    }

    public function testInvalidFromDate(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request(
            'GET',
            $this->endpoint.'?from=NOT_A_DATE&to=2024-01-01'
        );

        self::assertResponseStatusCodeSame(400);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('DATE_INVALIDE', $response['code']);
        $this->assertStringContainsString('NOT_A_DATE', $response['details'][0]);
    }

    public function testInvalidDateRange(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request(
            'GET',
            $this->endpoint.'?from=2024-05-01&to=2024-01-01'
        );

        self::assertResponseStatusCodeSame(400);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('PLAGE_DATES_INVALIDE', $response['code']);
    }

    public function testInvalidGroupBy(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request(
            'GET',
            $this->endpoint.'?groupBy=week'
        );

        self::assertResponseStatusCodeSame(400);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('GROUPBY_INVALIDE', $response['code']);
        $this->assertStringContainsString('week', $response['details'][0]);
    }

    public function testNoParametersDefaultsToNone(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', $this->endpoint);

        self::assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('none', $response['groupBy']);
        $this->assertArrayHasKey('items', $response);
    }
}
