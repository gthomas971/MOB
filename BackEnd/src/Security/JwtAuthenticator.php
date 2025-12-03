<?php

namespace App\Security;

use App\Service\JwtService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class JwtAuthenticator extends AbstractAuthenticator
{
    public function __construct(private JwtService $jwt)
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $header = $request->headers->get('Authorization');

        if (!str_starts_with($header, 'Bearer ')) {
            throw new AuthenticationException('Invalid Authorization header');
        }

        $token = substr($header, 7);

        try {
            $data = $this->jwt->decodeToken($token);
        } catch (\Exception $e) {
            throw new AuthenticationException('Invalid or expired token');
        }

        return new SelfValidatingPassport(new UserBadge($data['email']));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?JsonResponse
    {
        return new JsonResponse(['message' => $exception->getMessage()], 401);
    }

    public function onAuthenticationSuccess(Request $request, $token, string $firewallName): ?JsonResponse
    {
        return null;
    }
}
