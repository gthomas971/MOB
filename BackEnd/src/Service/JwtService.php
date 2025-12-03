<?php

namespace App\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    public function __construct(
        private readonly string $secret
    ) {}

    public function generateToken(array $payload): string
    {
        $payload['exp'] = time() + 3600;
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function decodeToken(string $token): array
    {
        return (array) JWT::decode($token, new Key($this->secret, 'HS256'));
    }
}
