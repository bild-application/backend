<?php

namespace App\Form;

use App\Enum\ErrorEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TokenFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email([
                        'mode' => Email::VALIDATION_MODE_HTML5,
                        'message' => ErrorEnum::INVALID_EMAIL->value,
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => ErrorEnum::CONSTRAINT_NOT_BLANK->value,
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => ErrorEnum::PASSWORD_TOO_SHORT->value,
                        'maxMessage' => ErrorEnum::PASSWORD_TOO_LONG->value,
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
