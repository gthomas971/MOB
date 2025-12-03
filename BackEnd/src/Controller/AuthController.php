<?php

namespace App\Controller;

use App\Service\JwtService;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, UserRepository $users, JwtService $jwt): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $users->findOneBy(['email' => $data['email']]);
        if (!$user || !password_verify($data['password'], $user->getPassword())) {
            return new JsonResponse(['message' => 'Invalid credentials'], 401);
        }

        return new JsonResponse([
            'token' => $jwt->generateToken([
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ])
        ]);
    }
}
