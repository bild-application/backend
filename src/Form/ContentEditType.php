<?php

namespace App\Form;

use App\Entity\Content;
use App\Enum\ErrorEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;

class ContentEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotNull(message: ErrorEnum::CONSTRAINT_NOT_NULL->value),
                ],
            ])
            ->add('image', FileType::class, [
                'constraints' => [
                    new NotNull(message: ErrorEnum::CONSTRAINT_NOT_NULL->value),
                    new File(
                        options: [
                            "extensions" => ['jpg', 'png'],
                            "extensionsMessage" => ErrorEnum::CONSTRAINT_EXTENSION_IMAGE->value,
                            "maxSize" => "2M", // 2 megabyte
                            "maxSizeMessage" => ErrorEnum::CONSTRAINT_MAX_SIZE->value,
                        ]
                    ),
                ],
                'documentation' => [
                    'type' => 'string',
                    'description' => 'Content image: PNG/JPG',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Content::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }
}
