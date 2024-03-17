<?php

namespace App\Controller;

use App\Entity\Content;
use App\Enum\RoleEnum;
use App\Form\ProfileEditType;
use App\Manager\ContentManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Content')]
#[IsGranted(RoleEnum::ROLE_USER->value)]
#[Route(path: '/api/contents')]
class ContentController extends AbstractFOSRestController
{
    public function __construct(
        protected ContentManager $contentManager,
    ) {
    }

    /**
     * List contents. ROLE_USER
     */
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'List contents',
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "array",
                items: new OA\Items(ref: new Model(type: Content::class, groups: ["content"]))
            )
        )
    )]
    #[Rest\Get(path: '', name: 'content_list')]
    #[Rest\View(statusCode: Response::HTTP_OK, serializerGroups: ['content'])]
    public function list(): View
    {
        return $this->view($this->contentManager->list(), Response::HTTP_OK);
    }

    /**
     * Show content. ROLE_USER
     */
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Show content',
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(ref: new Model(type: Content::class, groups: ["content"]))
        )
    )]
    #[Rest\Get(path: '/{contentId}', name: 'content_show')]
    #[Rest\View(statusCode: Response::HTTP_OK, serializerGroups: ['content'])]
    public function show(string $contentId): View
    {
        return $this->view($this->contentManager->fetch($contentId), Response::HTTP_OK);
    }

    /**
     * Create content. ROLE_USER
     */
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Create content',
        content: new Model(type: Content::class, groups: ['content'])
    )]
    #[OA\RequestBody(content: new Model(type: ProfileEditType::class))]
    #[Rest\Post(path: '', name: 'content_create')]
    #[Rest\Post(path: '/{profileId}', name: 'content_create_profile')]
    #[Rest\View(statusCode: Response::HTTP_CREATED, serializerGroups: ['content'])]
    public function create(Request $request, ?string $profileId): View
    {
        return $this->view($this->contentManager->create($request->request->all(), $profileId), Response::HTTP_CREATED);
    }

    /**
     * Delete a content. ROLE_USER
     */
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'Delete a content',
        content: []
    )]
    #[Rest\Delete(path: '/{id}', name: 'content_delete')]
    #[Rest\View(statusCode: Response::HTTP_NO_CONTENT)]
    public function delete(string $id): View
    {
        return $this->view($this->contentManager->delete($id), Response::HTTP_NO_CONTENT);
    }
}