<?php

namespace App\Controller;

use App\Entity\Package;
use App\Enum\RoleEnum;
use App\Form\PackageCreateType;
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
#[Route(path: '/api/packages')]
class PackageController extends AbstractFOSRestController
{
    public function __construct(
        protected PackageManager $packageManager,
    ) {
    }

    /**
     * Get a package owned by logged user
     */
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Package',
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(ref: new Model(type: Package::class, groups: ["package"]))
        )
    )]
    #[Rest\Get(path: '/{id}', name: 'package_get')]
    #[Rest\View(statusCode: Response::HTTP_OK)]
    public function get(string $id): View
    {
        return $this->view($this->packageManager->get($id), Response::HTTP_OK);
    }

    /**
     * List packages owned by logged user
     */
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Packages list',
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "array",
                items: new OA\Items(ref: new Model(type: Package::class, groups: ["package"]))
            )
        )
    )]
    #[Rest\Get(path: '/', name: 'package_list')]
    #[Rest\View(statusCode: Response::HTTP_OK)]
    public function list(Request $request): View
    {
        return $this->view($this->packageManager->list($request->query->all()), Response::HTTP_OK);
    }

    /**
     * Create a package owned by a profile of logged user
     */
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Package created',
        content: new Model(type: Package::class, groups: ['package'])
    )]
    #[OA\RequestBody(content: new Model(type: PackageCreateType::class))]
    #[Rest\Post(path: '', name: 'package_create')]
    #[Rest\View(statusCode: Response::HTTP_CREATED, serializerGroups: ['package'])]
    public function create(Request $request): View
    {
        return $this->view($this->packageManager->create($request->request->all()), Response::HTTP_CREATED);
    }

    /**
     * Delete a package owned by a profile of logged user
     */
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'Package deleted',
        content: []
    )]
    #[Rest\Delete(path: '/{id}', name: 'package_delete')]
    #[Rest\View(statusCode: Response::HTTP_NO_CONTENT)]
    public function delete(string $id): View
    {
        return $this->view($this->packageManager->delete($id), Response::HTTP_NO_CONTENT);
    }
}
