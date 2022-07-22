<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Spot;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom : '
            ])
            ->add('street', TextType::class, [
                'label' => 'Rue : '
            ])
            ->add('latitude', TextType::class, [
                'label' => 'Latitude : '
            ])
            ->add('longitude', TextType::class, [
                'label' => 'Longitude : '
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'placeholder' => '-- Choisissez une ville --',
                'label' => 'Ville : ',
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Spot::class,
        ]);
    }
}
