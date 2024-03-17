<?php

namespace App\Manager;

use App\Entity\Content;
use App\Form\ContentEditType;
use App\Repository\ContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ContentManager extends AbstractManager
{
    public function __construct(
        protected TokenStorageInterface $tokenStorage,
        protected EntityManagerInterface $manager,
        protected FormFactoryInterface $formFactory,
        protected ContentRepository $contentRepository,
        protected ProfileManager $profileManager,
    ) {
        parent::__construct($tokenStorage);
    }

    /**
     * @return array<Content>
     */
    public function list(): array
    {
        if (!$this->user) {
            throw new AccessDeniedException();
        }

        return $this->contentRepository->list($this->user->getId());
    }

    public function fetch(string $id): Content
    {
        $content = $this->contentRepository->fetch($id);

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

        $content->setUser($this->user);

        if ($profileId) {
            $profile = $this->profileManager->fetch($profileId);
            $content->setProfile($profile);
        }

        $this->manager->persist($content);
        $this->manager->flush();

        return $content;
    }

    /**
     * @return null[]
     */
    public function delete(string $id): array
    {
        $content = $this->fetch($id);

        $this->manager->remove($content);
        $this->manager->flush();

        return [];
    }
}
