<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

//Entities
use App\Entity\Criteria;

//Form types
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddCriteriaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        dump($options);
        $builder
            ->add('criteria', EntityType::class, [
                    'class' => Criteria::class,
                    'choice_label' => 'criteria',
                    'multiple' => true,
            ])
            ->add('save', SubmitType::class, array('label' => 'Save'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Criteria::class,
        ]);
    }
}
