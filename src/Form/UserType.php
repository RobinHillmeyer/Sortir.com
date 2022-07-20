<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Faker\Provider\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('username', TextType::class, [
                'label' => 'Pseudo : '
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom : '
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom : '
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone : '
            ])
            ->add('email', TextType::class, [
                'label' => 'Email : '
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe : '],
                'second_options' => ['label' => 'Confirmez votre mot de passe : ']
            ])
            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                'class' => Campus::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
