<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Tag(name: 'Home')]
#[Route(path: '/api')]
class HomeController extends AbstractFOSRestController
{
    public function __construct()
    {
    }

    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Successful response',
    )]
    #[Rest\Get('/', name: 'api_home')]
    #[Rest\View(statusCode: Response::HTTP_OK)]
    public function register(): View
    {
        return $this->view(
            'hello'
        );
    }

}
