<?php

namespace App\Controller;

use App\Dto\Response\TokenResponse;
use App\Form\TokenFormType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Tag(name: 'Authentication')]
#[Route(path: '/api')]
class TokenController extends AbstractFOSRestController
{
    public function __construct()
    {
    }

    /**
     * Get a JWT token for the account
     */
    #[OA\RequestBody(content: new Model(type: TokenFormType::class))]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Token created',
        content: new Model(type: TokenResponse::class)
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Validation errors',
        content: new Model(type: TokenFormType::class)
    )]
    #[Rest\Post('/public/token', name: 'auth_get_token')]
    #[Rest\View(statusCode: Response::HTTP_CREATED, serializerGroups: ['user'])]
    public function get(): void
    {
    }
}
