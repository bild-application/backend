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
     * Create package. ROLE_USER
     */
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Create package',
        content: new Model(type: Package::class, groups: ['package'])
    )]
    #[OA\RequestBody(content: new Model(type: PackageCreateType::class))]
    #[Rest\Post(path: '/{profileId}', name: 'package_create')]
    #[Rest\View(statusCode: Response::HTTP_CREATED, serializerGroups: ['package'])]
    public function create(Request $request, string $profileId): View
    {
        return $this->view($this->packageManager->create($request->request->all(), $profileId), Response::HTTP_CREATED);
    }

    /**
     * Delete a package. ROLE_USER
     */
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'Delete a package',
        content: []
    )]
    #[Rest\Delete(path: '/{id}', name: 'package_delete')]
    #[Rest\View(statusCode: Response::HTTP_NO_CONTENT)]
    public function delete(string $id): View
    {
        return $this->view($this->packageManager->delete($id), Response::HTTP_NO_CONTENT);
    }
}
