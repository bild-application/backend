<?php

namespace App\Controller;

use App\Entity\Package;
use App\Enum\RoleEnum;
use App\Form\PackageEditType;
use App\Manager\PackageManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Package')]
#[IsGranted(RoleEnum::ROLE_USER->value)]
#[Route(path: '/api')]
class PackageController extends AbstractFOSRestController
{
    public function __construct(
        protected PackageManager $packageManager,
    ) {
    }

    /**
     * Get the list of profile packages
     */
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Package list',
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "array",
                items: new OA\Items(ref: new Model(type: Package::class, groups: ["package"]))
            )
        )
    )]
    #[Rest\Get(path: '/profiles/{profileId}/packages', name: 'profiles_packages_list')]
    #[Rest\View(statusCode: Response::HTTP_OK)]
    public function list(string $profileId): View
    {
        return $this->view($this->packageManager->list($profileId), Response::HTTP_OK);
    }

    /**
     * Creates a package for a profile
     */
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Package created',
        content: new Model(type: Package::class, groups: ['package'])
    )]
    #[OA\RequestBody(content: new Model(type: PackageEditType::class))]
    #[Rest\Post(path: '/profiles/{profileId}/packages', name: 'profiles_packages_create')]
    #[Rest\View(statusCode: Response::HTTP_CREATED, serializerGroups: ['package'])]
    public function create(Request $request, string $profileId): View
    {
        return $this->view($this->packageManager->create($request->request->all(), $profileId), Response::HTTP_CREATED);
    }

    /**
     * Updates a package
     */
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Updated package',
        content: new Model(type: Package::class, groups: ['package'])
    )]
    #[OA\RequestBody(content: new Model(type: PackageEditType::class))]
    #[Rest\Patch(path: '/packages/{packageId}', name: 'profiles_packages_update')]
    #[Rest\View(statusCode: Response::HTTP_OK, serializerGroups: ['package'])]
    public function update(Request $request, string $packageId): View
    {
        return $this->view($this->packageManager->update($request->request->all(), $packageId), Response::HTTP_OK);
    }

    /**
     * Deletes a package
     */
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'No content',
        content: []
    )]
    #[Rest\Delete(path: '/packages/{packageId}', name: 'package_delete')]
    #[Rest\View(statusCode: Response::HTTP_NO_CONTENT)]
    public function delete(string $packageId): View
    {
        return $this->view($this->packageManager->delete($packageId), Response::HTTP_NO_CONTENT);
    }
}
