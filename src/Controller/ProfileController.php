<?php

namespace App\Controller;

use App\Enum\RoleEnum;
use App\Manager\ProfileManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

//#[OA\Tag(name: 'Profile')]
#[IsGranted(RoleEnum::ROLE_USER)]
#[Route(path: '/api/profiles')]
class ProfileController extends AbstractFOSRestController
{
    public function __construct(
        private ProfileManager $profileManager
    ) {
    }

    #[Rest\Post(path: '', name: 'profile_create')]
    #[Rest\View(statusCode: Response::HTTP_CREATED, serializerGroups: ['profile'])]
    public function create(Request $request): View
    {
        return $this->view($this->profileManager->create($request->request->all()), Response::HTTP_CREATED);
    }
}
