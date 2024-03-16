<?php

namespace App\Manager\Auth;

use App\Entity\User;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SessionManager
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
    public function attemptLogin(array $data): FormInterface
    {
        $user = new User();

        $form = $this->formFactory
            ->create(RegisterFormType::class, $user)
            ->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->security->login($user, 'json_login', 'main');
        }

        return $form;
    }
}
