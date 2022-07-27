<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                'required' => false,
                'class' => Campus::class,
                'choice_label' => 'name',
                'placeholder' => '-- Choisir un campus --'

            ])
            ->add('keyWord', TextType::class, [
                'label' => 'Le nom de la sortie contient : ',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('dateFrom', DateType::class, [
                'label' => 'Entre',
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('dateTo', DateType::class, [
                'label' => 'et',
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('isPromoter', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false
            ])
            ->add('isSub', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false
            ])
            ->add('isntSub', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false
            ])
            ->add('isTripEnd', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}