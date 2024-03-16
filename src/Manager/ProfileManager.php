<?php

namespace App\Manager;

use App\Entity\Profile;
use App\Form\ProfileEditType;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileManager extends AbstractManager
{
    public function __construct(
        protected TokenStorageInterface $tokenStorage,
        protected EntityManagerInterface $manager,
        protected FormFactoryInterface $formFactory,
        protected ProfileRepository $profileRepository,
    ) {
        parent::__construct($tokenStorage);
    }

    public function fetch(string $id): Profile
    {
        $profile = $this->profileRepository->fetch($id);

        if (!$profile) {
            throw new NotFoundHttpException();
        }

        if ($profile->getUser() !== $this->user) {
            throw new AccessDeniedException();
        }

        return $profile;
    }

    /**
     * @return Profile[]
     */
    public function getList(): array
    {
        return $this->profileRepository->getList($this->user);
    }

    /**
     * @param mixed[] $data
     */
    public function create(array $data): FormInterface|Profile
    {
        $profile = new Profile();

        $form = $this->formFactory->create(ProfileEditType::class, $profile);
        $form->submit($data);

        if (!$form->isValid()) {
            return $form;
        }

        $profile->setUser($this->user);

        $this->manager->persist($profile);
        $this->manager->flush();

        return $profile;
    }

    /**
     * @return null[]
     */
    public function delete(string $id): array
    {
        $profile = $this->fetch($id);

        $this->manager->remove($profile);
        $this->manager->flush();

        return [];
    }
}
