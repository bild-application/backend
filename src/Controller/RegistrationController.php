<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use App\Manager\Auth\SessionManager;
use App\Manager\RegistrationManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[OA\Tag(name: 'Authentication')]
#[Route(path: '/api')]
class RegistrationController extends AbstractFOSRestController
{
    public function __construct(
        private readonly RegistrationManager $registrationManager,
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
    #[Rest\Post('/public/register', name: 'auth_register')]
    #[Rest\View(statusCode: Response::HTTP_CREATED, serializerGroups: ['user'])]
    public function create(Request $request): View
    {
        return $this->view(
            $this->registrationManager->create($request->toArray())
        );
    }
}
