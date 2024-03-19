<?php

namespace App\Manager;

use App\Entity\Package;
use App\Form\PackageCreateType;
use App\Form\PackageFilterType;
use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PackageManager extends AbstractManager
{
    public function __construct(
        protected TokenStorageInterface $tokenStorage,
        protected EntityManagerInterface $manager,
        protected FormFactoryInterface $formFactory,
        protected PackageRepository $packageRepository,
        protected ProfileManager $profileManager,
    ) {
        parent::__construct($tokenStorage);
    }

    public function get(string $id): Package
    {
        $package = $this->packageRepository->get($id);

        if (!$package) {
            throw new NotFoundHttpException();
        }

        if ($package->getProfile()->getUser() !== $this->user) {
            throw new AccessDeniedException();
        }

        return $package;
    }

    /**
     * @return array<Package>
     */
    public function list(array $filters): FormInterface|array
    {
        if (!$this->user) {
            throw new AccessDeniedException();
        }

        $form = $this->formFactory->create(PackageFilterType::class);
        $form->submit($filters);

        if (!$form->isValid()) {
            return $form;
        }

        $profileId = $form->getData()['profile']?->getId();

        return $this->packageRepository->list($this->user->getId(), $profileId);
    }

    /**
     * @param mixed[] $data
     */
    public function create(array $data): FormInterface|Package
    {
        $package = new Package();

        $form = $this->formFactory->create(PackageCreateType::class, $package);
        $form->submit($data);

        if (!$form->isValid()) {
            return $form;
        }

        if ($package->getProfile() && $package->getProfile()->getUser() !== $this->user) {
            throw new AccessDeniedException();
        }

        $this->manager->persist($package);
        $this->manager->flush();

        return $package;
    }

    /**
     * @return null[]
     */
    public function delete(string $id): array
    {
        $package = $this->get($id);

        $this->manager->remove($package);
        $this->manager->flush();

        return [];
    }
}