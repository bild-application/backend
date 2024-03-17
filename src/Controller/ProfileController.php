<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Enum\RoleEnum;
use App\Form\ProfileEditType;
use App\Manager\ProfileManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
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

    /**
     * Return a profile. ROLE_USER
     */
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Return a profile',
        content: new Model(type: Profile::class, groups: ['profile'])
    )]
    #[Rest\Get(path: '/{id}', name: 'profile_show')]
    #[Rest\View(statusCode: Response::HTTP_OK, serializerGroups: ['profile'])]
    public function show(string $id): View
    {
        return $this->view($this->profileManager->fetch($id), Response::HTTP_OK);
    }

    /**
     * Returns list of user profiles. ROLE_USER
     */
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns list of user profiles',
        content: new Model(type: Profile::class, groups: ['profile'])
    )]
    #[Rest\Get(path: '', name: 'profile_index')]
    #[Rest\View(statusCode: Response::HTTP_OK, serializerGroups: ['profile'])]
    public function getList(): View
    {
        return $this->view($this->profileManager->getList(), Response::HTTP_OK);
    }

    /**
     * Create profile. ROLE_USER
     */
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Create profile',
        content: new Model(type: Profile::class, groups: ['profile'])
    )]
    #[OA\RequestBody(content: new Model(type: ProfileEditType::class))]
    #[Rest\Post(path: '', name: 'profile_create')]
    #[Rest\View(statusCode: Response::HTTP_CREATED, serializerGroups: ['profile'])]
    public function create(Request $request): View
    {
        return $this->view($this->profileManager->create($request->request->all()), Response::HTTP_CREATED);
    }

    /**
     * Delete a profile. ROLE_USER
     */
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'Delete a profile',
        content: []
    )]
    #[Rest\Delete(path: '/{id}', name: 'profile_delete')]
    #[Rest\View(statusCode: Response::HTTP_NO_CONTENT)]
    public function delete(string $id): View
    {
        return $this->view($this->profileManager->delete($id), Response::HTTP_NO_CONTENT);
    }
}
