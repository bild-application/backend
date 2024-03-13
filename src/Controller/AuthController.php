<?php

namespace App\Controller;

use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Rest\Route(path: 'api/auth')]
class AuthController extends AbstractFOSRestController
{
    #[Rest\Post(path: '/register', name: 'api_auth_register')]
    #[Rest\View(statusCode: Response::HTTP_CREATED, serializerGroups: ['user'])]
    public function register(): View
    {
        return $this->view([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/RegisterController.php',
        ], Response::HTTP_CREATED);
    }

    #[Rest\Post(path: '/login', name: 'api_auth_login')]
    #[Rest\View(statusCode: Response::HTTP_OK)]
    public function login(#[CurrentUser] ?User $user): View
    {
        if ($user === null) {
            return $this->view([
                'message' => 'missing credentials',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->view([
            'user' => $user->getUserIdentifier(),
        ], Response::HTTP_OK);
    }

    #[Rest\Get(path: '/check', name: 'api_auth_check')]
    #[Rest\View(statusCode: Response::HTTP_OK)]
    public function check(#[CurrentUser] ?User $user): View
    {
        if ($user === null) {
            throw new BadRequestHttpException(message: 'vas te faire enculer <3');
            //            return $this->view([
            //                'message' => 'unauthorized',
            //            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->view([
            'user' => $user->getUserIdentifier(),
        ], Response::HTTP_OK);
    }
}
