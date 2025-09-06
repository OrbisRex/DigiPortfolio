<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

//Entities
use App\Entity\Feedback;
use App\Entity\Descriptor;

//Form types
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FeedbackFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descriptors', EntityType::class, [
                    'class' => Descriptor::class,
                    'choices' => $options['criteria'],
                    'choice_label' => 'description',
                    'label' => 'Criteria Descriptors',
                    'multiple' => true,
                    'expanded' => true,
                    'placeholder' => 'Criteria',
            ])
            ->add('note', TextareaType::class, [
                    'label' => false,
                    'attr' => array('placeholder' => 'Note'),
                    'required' => false,
            ])
            ->add('save', SubmitType::class, array('label' => 'Save'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Feedback::class,
            'criteria' => null,
        ]);
    }
}
