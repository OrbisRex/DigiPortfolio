<?php

namespace App\Form;

use App\Entity\Criterion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
// Entities
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// Form types
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCriteriaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        dump($options);
        $builder
            ->add('criteria', EntityType::class, [
                'class' => Criterion::class,
                'choice_label' => 'criteria',
                'multiple' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Criterion::class,
        ]);
    }
}
