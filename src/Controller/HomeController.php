<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\RoleEnum;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
