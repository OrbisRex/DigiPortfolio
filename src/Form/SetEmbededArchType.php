<?php

namespace App\Form;

use App\Entity\Set;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

//Form types
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Form\Type\TextType;

class SetEmbededArchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class, array('label' => FALSE, 'attr' => array('placeholder' => 'Set')))
            ->add('name', EntityType::class, [
                    'class' => Set::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'query_builder' => function(FormRepositor $repo) {
                        return $repo->findAll();
                    },
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
