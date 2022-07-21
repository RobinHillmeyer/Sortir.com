<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Spot;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie : '
            ])
            ->add('startDateTime', DateTimeType::class, [
                'label' => 'Date et heure de la sortie : ',
                'widget' => 'single_text',
                'model_timezone' => 'Europe/Paris',
            ])
            ->add('registrationDeadLine', DateTimeType::class, [
                'label' => 'Date limite d\'inscription',
                'widget' => 'single_text',
                'model_timezone' => 'Europe/Paris'
            ])
            ->add('registrationNumberMax', TextType::class, [
                'label' => 'Nombre de places : '
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée : ',
            ])
            ->add('information', TextareaType::class, [
                'label' => 'Description et infos : '
            ])
            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                'class' => Campus::class,
                'placeholder' => '-- Choisissez un campus --',
                'choice_label' => 'name'
            ])
            ->add('spot', EntityType::class, [
                'label' => 'Lieu : ',
                'class' => Spot::class,
                'placeholder' => '-- Choisissez un lieu --',
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('spot1', SpotType::class, [
                'mapped' => false,
                'label' => null,
                'required' => false
            ])
            //->add('State') todo hydrater en auto l'état
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class
        ]);
    }
}
