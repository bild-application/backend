<?php

namespace App\Manager;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\ProfileEditType;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class ProfileManager
{
    public function __construct(
        protected EntityManagerInterface $manager,
        protected FormFactoryInterface $formFactory,
        protected ProfileRepository $profileRepository,
    ) {
    }

    /**
     * @param mixed[] $data
     */
    public function create(array $data, ?User $user): FormInterface|Profile
    {
        $profile = new Profile();

        $form = $this->formFactory->create(ProfileEditType::class, $profile);
        $form->submit($data);

        if (!$form->isValid()) {
            return $form;
        }

        $profile->setUser($user);

        $this->manager->persist($profile);
        $this->manager->flush();

        return $profile;
    }
}
