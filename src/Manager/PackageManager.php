<?php

namespace App\Manager;

use App\Entity\Package;
use App\Form\PackageCreateType;
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

    public function fetch(string $id): Package
    {
        $package = $this->packageRepository->fetch($id);

        if (!$package) {
            throw new NotFoundHttpException();
        }

        if ($package->getProfile()->getUser() !== $this->user) {
            throw new AccessDeniedException();
        }

        return $package;
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

        $this->manager->persist($package);
        $this->manager->flush();

        return $package;
    }

    /**
     * @return null[]
     */
    public function delete(string $id): array
    {
        $package = $this->fetch($id);

        $this->manager->remove($package);
        $this->manager->flush();

        return [];
    }
}
