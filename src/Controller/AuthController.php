<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class AuthController extends AbstractController
{
    #[Route('/api/auth/register', name: 'api_auth_register')]
    public function register(): Response
    {
        return new Response(json_encode([
            'message' => 'ok',
        ], JSON_THROW_ON_ERROR), Response::HTTP_CREATED);
    }

    #[Route('/api/auth/login', name: 'api_auth_login')]
    public function login(#[CurrentUser] ?User $user): Response
    {
        if ($user === null) {
            return new Response(json_encode([
                'message' => 'missing credentials',
            ], JSON_THROW_ON_ERROR), Response::HTTP_BAD_REQUEST);
        }

        return new Response(json_encode([
            'user' => $user->getUserIdentifier(),
        ], JSON_THROW_ON_ERROR), Response::HTTP_OK);
    }

    #[Route('/api/auth/check', name: 'api_auth_check')]
    public function check(#[CurrentUser] ?User $user): Response
    {
        if ($user === null) {
            return new Response(json_encode([
                'message' => 'not logged in',
            ], JSON_THROW_ON_ERROR), Response::HTTP_UNAUTHORIZED);
        }

        return new Response(json_encode([
            'user' => $user->getUserIdentifier(),
        ], JSON_THROW_ON_ERROR), Response::HTTP_OK);
    }
}
