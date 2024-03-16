<?php

namespace App\Controller;

use App\Enum\RoleEnum;
use App\Manager\ProfileManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Profile')]
#[IsGranted(RoleEnum::ROLE_USER->value)]
#[Route(path: '/api/profiles')]
class ProfileController extends AbstractFOSRestController
{
    public function __construct(
        private readonly ProfileManager $profileManager
    ) {
    }

    #[Rest\Get(path: '', name: 'profile_index')]
    #[Rest\View(statusCode: Response::HTTP_OK, serializerGroups: ['profile'])]
    public function getList(): View
    {
        return $this->view($this->profileManager->getList(), Response::HTTP_OK);
    }

    #[Rest\Get(path: '/{id}', name: 'profile_show')]
    #[Rest\View(statusCode: Response::HTTP_OK, serializerGroups: ['profile'])]
    public function show(string $id): View
    {
        return $this->view($this->profileManager->fetch($id), Response::HTTP_OK);
    }

    #[Rest\Post(path: '', name: 'profile_create')]
    #[Rest\View(statusCode: Response::HTTP_CREATED, serializerGroups: ['profile'])]
    public function create(Request $request): View
    {
        return $this->view($this->profileManager->create($request->request->all()), Response::HTTP_CREATED);
    }

    #[Rest\Delete(path: '/{id}', name: 'profile_delete')]
    #[Rest\View(statusCode: Response::HTTP_NO_CONTENT)]
    public function delete(string $id): View
    {
        return $this->view($this->profileManager->delete($id), Response::HTTP_NO_CONTENT);
    }
}
