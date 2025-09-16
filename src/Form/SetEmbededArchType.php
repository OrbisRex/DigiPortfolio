<?php

namespace App\Form;

use App\Entity\Set;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Form\Type\TextType;
use Symfony\Component\Form\AbstractType;
// Form types
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SetEmbededArchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Set']])
            ->add(
                'name',
                EntityType::class,
                [
                    'class' => Set::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'query_builder' => fn(FormRepositor $repo) => $repo->findAll(),
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Set::class,
        ]);
    }
}
