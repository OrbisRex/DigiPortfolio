<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Set;
use App\Repository\SetRepository;

class SetEmbededArchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
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
                    'query_builder' => fn (SetRepository $repo) => $repo->findAll(),
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Set::class,
        ]);
    }
}
