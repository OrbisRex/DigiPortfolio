<?php

namespace App\Form;

use App\Entity\Assignment;
use App\Entity\Criterion;
use App\Entity\Set;
// Entities
use App\Entity\Subject;
use App\Entity\Topic;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// Form types
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignmentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', EntityType::class, [
                'class' => Subject::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => false,
                'placeholder' => 'Choose a subject',
            ])
            ->add('topic', EntityType::class, [
                'class' => Topic::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => false,
                'placeholder' => 'Choose a topic',
            ])
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Name'],
            ])
            ->add('note', TextareaType::class, [
                'label' => 'Indicators/Notes',
            ])
            ->add('set', EntityType::class, [
                'class' => Set::class,
                'choice_label' => 'name',
                'label' => false,
                'placeholder' => 'Choose a set',
            ])
            ->add('criteria', EntityType::class, [
                'class' => Criterion::class,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assignment::class,
        ]);
    }
}
