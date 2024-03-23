<?php

namespace App\Manager;

use App\Entity\User;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationManager
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
    ) {
    }

    /**
     * @param mixed[] $data
     */
    public function create(array $data): FormInterface|User
    {
        $user = new User();

        $form = $this->formFactory
            ->create(RegisterFormType::class, $user)
            ->submit($data);

        if (!$form->isValid()) {
            return $form;
        }

        // encode the plain password
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $data['password']['first']
            )
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
