<?php

namespace App\Form;

use App\Entity\State;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'documentation' => [
                    'description' => 'Name of state.',
                ],
            ])
            ->add('abrev', TextType::class, [
                'documentation' => [
                    'description' => 'Abbreviation of state.',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => State::class,
            'allow_extra_fields' => true,
        ]);
    }
    
    /**
     * Get block prefix.
     */
    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
