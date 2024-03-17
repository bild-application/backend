<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use App\Manager\Auth\RegistrationManager;
use App\Manager\Auth\SessionManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[OA\Tag(name: 'Authentication')]
#[Route(path: '/api/auth')]
class AuthController extends AbstractFOSRestController
{
    public function __construct(
        private readonly RegistrationManager $registrationManager,
        private readonly SessionManager $sessionManager
    ) {
    }

    #[OA\RequestBody(content: new Model(type: RegisterFormType::class))]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'User created',
        content: new Model(type: User::class, groups: ['user'])
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Validation errors',
        content: new Model(type: RegisterFormType::class)
    )]
    #[Rest\Post('/register', name: 'auth_register')]
    #[Rest\View(statusCode: Response::HTTP_CREATED, serializerGroups: ['user'])]
    public function register(Request $request): View
    {
        return $this->view(
            $this->registrationManager->attemptRegister($request->toArray())
        );
    }

    #[Rest\Post('/login', name: 'auth_login')]
    public function login(#[CurrentUser] ?User $user, Request $request): View
    {
        return $this->view(
            $this->sessionManager->attemptLogin($request->toArray())
        );
    }

    #[Rest\Get('/check', name: 'auth_check')]
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
