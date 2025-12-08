<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AuthTest extends WebTestCase
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
}
