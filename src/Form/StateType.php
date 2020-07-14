<?php

namespace App\Form;

use App\Document\State;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('abrev', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => State::class,
            'allow_extra_fields' => true,
        ]);
    }
    
    /**
     * Get block prefix.
     *
     * @return string|null
     */
    public function getBlockPrefix()
    {
        return null;
    }
}
