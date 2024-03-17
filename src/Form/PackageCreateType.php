<?php

namespace App\Form;

use App\Entity\Package;
use App\Entity\Profile;
use App\Enum\ErrorEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class PackageCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotNull(message: ErrorEnum::CONSTRAINT_NOT_NULL->value),
                ],
            ])
            ->add('profile', EntityType::class, [
                'class' => Profile::class,
                'constraints' => [
                    new NotNull(message: ErrorEnum::CONSTRAINT_NOT_NULL->value),
                ],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Package::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }
}
