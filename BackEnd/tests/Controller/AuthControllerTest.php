<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AuthControllerTest extends WebTestCase
{
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

        $this->assertResponseStatusCodeSame(400);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertStringContainsString('email', $response['detail']);
    }

    public function testLoginFailsWhenPasswordIsMissing()
    {
        $client = $this->requestLogin([
            'email' => 'admin@admin.com'
        ]);

        $this->assertResponseStatusCodeSame(400);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertStringContainsString('password', $response['detail']);
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
             '{invalid_json]'
         );

         $this->assertResponseStatusCodeSame(400);

         $response = json_decode($client->getResponse()->getContent(), true);
         $this->assertEquals('Invalid JSON.', $response['detail']);
     }
}
