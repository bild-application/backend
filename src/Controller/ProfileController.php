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
#[Route(path: '/api')]
class ProfileController extends AbstractFOSRestController
{
    public function __construct(
        private readonly ProfileManager $profileManager
    ) {
    }

    /**
     * Get a profile owned by logged user
     */
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Profile',
        content: new Model(type: Profile::class, groups: ['profile'])
    )]
    #[Rest\Get(path: '/profiles/{profileId}', name: 'profiles_get')]
    #[Rest\View(statusCode: Response::HTTP_OK, serializerGroups: ['profile'])]
    public function get(string $profileId): View
    {
        return $this->view($this->profileManager->get($profileId), Response::HTTP_OK);
    }

    /**
     * List profiles owned by logged user
     */
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Profiles list',
        content: new Model(type: Profile::class, groups: ['profile'])
    )]
    #[Rest\Get(path: '/profiles', name: 'profiles_list')]
    #[Rest\View(statusCode: Response::HTTP_OK, serializerGroups: ['profile'])]
    public function list(): View
    {
        return $this->view($this->profileManager->list(), Response::HTTP_OK);
    }

    /**
     * Create a profile owned by logged user
     */
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Profile created',
        content: new Model(type: Profile::class, groups: ['profile'])
    )]
    #[OA\RequestBody(content: new Model(type: ProfileEditType::class))]
    #[Rest\Post(path: '/profiles', name: 'profiles_create')]
    #[Rest\View(statusCode: Response::HTTP_CREATED, serializerGroups: ['profile'])]
    public function create(Request $request): View
    {
        return $this->view($this->profileManager->create($request->request->all()), Response::HTTP_CREATED);
    }

    /**
     * Update a profile owned by logged user
     */
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Profile created',
        content: new Model(type: Profile::class, groups: ['profile'])
    )]
    #[OA\RequestBody(content: new Model(type: ProfileEditType::class))]
    #[Rest\Put(path: '/profiles/{profileId}', name: 'profiles_update')]
    #[Rest\View(statusCode: Response::HTTP_OK, serializerGroups: ['profile'])]
    public function update(string $profileId, Request $request): View
    {
        return $this->view($this->profileManager->update($profileId, $request->request->all()), Response::HTTP_OK);
    }

    /**
     * Delete a profile owned by logged user
     */
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'Profile deleted',
        content: []
    )]
    #[Rest\Delete(path: '/profiles/{profileId}', name: 'profiles_delete')]
    #[Rest\View(statusCode: Response::HTTP_NO_CONTENT)]
    public function delete(string $profileId): View
    {
        return $this->view($this->profileManager->delete($profileId), Response::HTTP_NO_CONTENT);
    }
}
