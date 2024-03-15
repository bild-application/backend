<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\Auth\RegistrationManager;
use App\Manager\Auth\SessionManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class AuthController extends AbstractFOSRestController
{
    public function __construct(
        private readonly RegistrationManager $registrationManager,
        private readonly SessionManager $sessionManager
    ) {
    }

    #[Rest\Post('/api/auth/register', name: 'api_auth_register')]
    public function register(Request $request): View
    {
        return $this->view(
            $this->registrationManager->attemptRegister($request->toArray())
        );
    }

    #[Rest\Post('/api/auth/login', name: 'api_auth_login')]
    public function login(#[CurrentUser] ?User $user, Request $request): View
    {
        return $this->view(
            $this->sessionManager->attemptLogin($request->toArray())
        );
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