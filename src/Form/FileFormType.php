<?php

namespace App\Form;

use App\Entity\ResourceFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class FileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('files', FileType::class, [
                'label' => 'Files to upload',
                'multiple' => true,
                'mapped' => false,
                'constraints' => [
                    new All([
                        new File([
                            'maxSize' => '1024k',
                            //'extensions' => ['jpg'],
                            //'extensionsMessage' => 'Please upload a valid JPG image.',
                        ])
                    ])
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ResourceFile::class,
        ]);
    }
}
