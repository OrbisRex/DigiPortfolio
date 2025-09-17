<?php

namespace App\Form;

use App\Entity\Descriptor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
// Entities
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// Form types
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DescriptorFormType extends AbstractType
{
    private $levelChoices;

    public function __construct()
    {
        $this->levelChoices = ['Level - Basic' => 'basic', 'Level - Standard' => 'standard', 'Level - Advanced' => 'advanced', 'Level - Master' => 'master'];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Name'],
                'data' => $options['name'],
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Description'],
                'data' => $options['descriptor'],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => $this->levelChoices,
                'preferred_choices' => $this->swichLevelChoice($options['level_choice']),
                'label' => false,
            ])
            ->add('weight', ChoiceType::class, [
                'choices' => ['Relevance - Normal' => 1, 'Relevance - Important' => 2, 'Relevance - Very Important' => 3],
                'preferred_choices' => [1],
                'label' => false,
            ])
            ->add('add', SubmitType::class, ['label' => 'Save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Descriptor::class,
            'level_choice' => 'basic',
            'descriptor' => null,
            'name' => null,
        ]);
    }

    private function swichLevelChoice($level): array
    {
        $currentLevel = match ($level) {
            'basic' => ['standard'],
            'standard' => ['advanced'],
            'advanced' => ['master'],
            'master' => ['basic'],
            default => ['basic'],
        };

        return $currentLevel;
    }
}
