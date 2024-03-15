<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\ErrorEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterFormType extends AbstractType
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
                'documentation' => [
                    'type' => 'string',
                    'description' => 'Email',
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => ErrorEnum::NEED_AGREE_TERMS->value,
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => ErrorEnum::ERROR_PASSWORD_NOT_MATCH->value,
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'options' => [
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
                ],

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false,
        ]);
    }
}
