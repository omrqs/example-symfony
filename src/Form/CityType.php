<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\State;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('state', EntityType::class, [
                'class' => State::class,
                'documentation' => [
                    'description' => 'State of city.',
                ]
            ])
            ->add('name', TextType::class, [
                'documentation' => [
                    'description' => 'Name of city.',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => City::class,
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
