<?php

namespace App\Manager;

use App\Entity\Package;
use App\Form\PackageEditType;
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
     * @param array<mixed> $filters
     *
     * @return FormInterface|array<Package>
     */
    public function list(string $profileId): array
    {
        $profile = $this->profileManager->get($profileId);

        return $this->packageRepository->list($profile);
    }

    /**
     * @param mixed[] $data
     */
    public function create(array $data, string $profileId): FormInterface|Package
    {
        $profile = $this->profileManager->get($profileId);

        $package = new Package();

        $form = $this->formFactory->create(PackageEditType::class, $package);
        $form->submit($data);

        if (!$form->isValid()) {
            return $form;
        }

        $package->setProfile($profile);

        $this->manager->persist($package);
        $this->manager->flush();

        return $package;
    }

    /**
     * @param mixed[] $data
     */
    public function update(array $data, string $packageId): FormInterface|Package
    {
        $package = $this->get($packageId);

        $form = $this->formFactory->create(PackageEditType::class, $package);
        $form->submit($data, false);

        if (!$form->isValid()) {
            return $form;
        }

        $this->manager->flush();

        return $package;
    }

    /**
     * @return null[]
     */
    public function delete(string $packageId): array
    {
        $package = $this->get($packageId);

        $this->manager->remove($package);
        $this->manager->flush();

        return [];
    }
}
