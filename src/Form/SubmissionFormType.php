<?php

namespace App\Form;

use App\Entity\Assignment;
use App\Entity\Person;
use App\Entity\Submission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
// Entities
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
// Repositories
// Form types
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubmissionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Name'],
            ])
            ->add('note', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Note'],
                'required' => false,
            ])
            ->add('link', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Link'],
                'required' => false,
            ])
            ->add('text', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Text'],
            ])
            ->add('assignment', EntityType::class, [
                'class' => Assignment::class,
                'choices' => $options['assignments'],
                'label' => false,
                'placeholder' => 'Assignemnt',
                'choice_label' => 'name',
                'multiple' => false,
            ])
            ->add('owner', EntityType::class, [
                'class' => Person::class,
                'choices' => $options['students'],
                'label' => false,
                'placeholder' => 'Owner',
                'choice_label' => 'name',
                'multiple' => false,
                'disabled' => $options['disabled_owner'],
                'data' => $options['selected_user'],
            ])
            ->add('files', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('version', HiddenType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Save',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Submission::class,
            'assignments' => null,
            'students' => null,
            'disabled_owner' => false,
            'selected_user' => null,
        ]);
    }
}
