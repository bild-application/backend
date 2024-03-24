<?php

namespace App\Manager;

use App\Entity\Content;
use App\Facade\FileSystemFacade;
use App\Form\ContentEditType;
use App\Repository\ContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContentManager extends AbstractManager
{
    public function __construct(
        protected TokenStorageInterface $tokenStorage,
        protected EntityManagerInterface $manager,
        protected FormFactoryInterface $formFactory,
        protected ContentRepository $contentRepository,
        protected ProfileManager $profileManager,
        protected FileSystemFacade $fileSystemFacade,
        protected ValidatorInterface $validator
    ) {
        parent::__construct($tokenStorage);
    }

    /**
     * @return ConstraintViolationListInterface|array<Content>
     */
    public function list(string $profileId): ConstraintViolationListInterface|array
    {
        if (!$this->user) {
            throw new AccessDeniedException();
        }

        $this->profileManager->get($profileId);

        return $this->contentRepository->listForProfile($this->user->getId(), $profileId);
    }

    public function get(string $id): Content
    {
        $content = $this->contentRepository->get($id);

        if (!$content) {
            throw new NotFoundHttpException();
        }

        if ($content->getUser() !== $this->user) {
            throw new AccessDeniedException();
        }

        return $content;
    }

    /**
     * @param mixed[] $data
     */
    public function create(array $data, ?string $profileId): FormInterface|Content
    {
        $content = new Content();

        $form = $this->formFactory->create(ContentEditType::class, $content);
        $form->submit($data);

        if (!$form->isValid()) {
            return $form;
        }

        if ($profileId) {
            $content->setProfile($this->profileManager->get($profileId));
        }

        $image = $form->get('image')->getData();

        $imagePath = $this->fileSystemFacade->store($image, Content::STORAGE_FOLDER);

        $content->setImage($imagePath);
        $content->setUser($this->user);

        $this->manager->persist($content);
        $this->manager->flush();

        return $content;
    }

    /**
     * @return null[]
     */
    public function delete(string $id): array
    {
        $content = $this->get($id);

        $this->fileSystemFacade->getStorage()->delete($content->getImage());

        $this->manager->remove($content);
        $this->manager->flush();

        return [];
    }
}
