<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AuthControllerTest extends WebTestCase
{
    protected function createAuthenticatedClient(): KernelBrowser {

        $client = static::createClient([], [
            'HTTPS' => true,
            'HTTP_HOST' => 'api.localhost',
        ]);

        $client->request('POST', '/api/v1/login', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'admin@admin.com',
                'password' => "password123"
            ])
        );

        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);

        if (!isset($data['token'])) {
            $this->fail('No token returned after authentication');
        }

        $token = $data['token'];

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token));

        return $client;
    }

    private function requestLogin(array $payload): KernelBrowser
    {
        $client = static::createClient([], [
            'HTTPS' => true,
            'HTTP_HOST' => 'api.localhost',
        ]);

        $client->request(
            'POST',
            '/api/v1/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        return $client;
    }

    public function testLoginFailsWhenEmailIsMissing()
    {
        $client = $this->requestLogin([
            'password' => 'password123'
        ]);

        $this->assertResponseStatusCodeSame(401);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('INVALID_CREDENTIALS', $response['code']);
    }

    public function testLoginFailsWhenPasswordIsMissing()
    {
        $client = $this->requestLogin([
            'email' => 'admin@admin.com'
        ]);

        $this->assertResponseStatusCodeSame(401);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('INVALID_CREDENTIALS', $response['code']);
    }

    public function testLoginFailsWithUnknownEmail()
    {
        $client = $this->requestLogin([
            'email' => 'unknown@example.com',
            'password' => 'something'
        ]);

        $this->assertResponseStatusCodeSame(401);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('INVALID_CREDENTIALS', $response['code']);
    }

    public function testLoginFailsWithWrongPassword()
    {
        $client = $this->requestLogin([
            'email' => 'admin@admin.com',
            'password' => 'wrongpass'
        ]);

        $this->assertResponseStatusCodeSame(401);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('INVALID_CREDENTIALS', $response['code']);
    }

    public function testLoginFailsWithInvalidJson()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{invalid_json]'
        );

        $this->assertResponseStatusCodeSame(500, "Invalid JSON should result in HTTP 500 or 400 depending on config");
    }
}
