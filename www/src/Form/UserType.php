<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['update'] === true) {
            $sendLabel = 'Modifier';
        } else {
            $sendLabel = 'Créer';
        }
        
        $builder
            ->add('email')
            ->add('password', PasswordType::class, [
                'empty_data' => '',
            ])
            ->add('firstname')
            ->add('lastname')
            ->add('send', SubmitType::class, [
                'label' => $sendLabel
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'update' => false
        ]);
    }
}
