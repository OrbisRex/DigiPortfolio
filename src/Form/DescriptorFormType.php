<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

//Entities
use App\Entity\Descriptor;

//Form types
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DescriptorFormType extends AbstractType
{
    private $levelChoices;
    
    public function __construct()
    {
        $this->levelChoices = ['Level - Basic' => 'basic', 'Level - Standard' => 'standard', 'Level - Advanced' => 'advanced', 'Level - Master' => 'master'];
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => FALSE, 
                'attr' => ['placeholder' => 'Name'],
                'data' => $options['name'],
            ])
            ->add('description', TextareaType::class, [
                'label' => FALSE,
                'attr' => ['placeholder' => 'Description'],
                'data' => $options['descriptor'],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => $this->levelChoices,
                'preferred_choices' => $this->swichLevelChoice($options['level_choice']),
                'label' => FALSE
            ])
            ->add('weight', ChoiceType::class, [
                'choices' => ['Relevance - Normal' => 1, 'Relevance - Important' => 2, 'Relevance - Very Important' => 3],
                'preferred_choices' => [1],
                'label' => FALSE
            ])
            ->add('add', SubmitType::class, ['label' => 'Save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Descriptor::class,
            'level_choice' => 'basic',
            'descriptor' => null,
            'name' => null,
        ]);
    }
    
    private function swichLevelChoice($level)
    {
        switch ($level){
            case 'basic':
                $currentLevel = ['standard'];
                break;
            case 'standard':
                $currentLevel = ['advanced'];
                break;
            case 'advanced':
                $currentLevel = ['master'];
                break;
            case 'master':
                $currentLevel = ['basic'];
                break;
            default:
                $currentLevel = ['basic'];
        }
        
        return $currentLevel;
    }
}
