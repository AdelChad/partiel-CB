<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return sprintf('%s (%s %s)', $user->getEmail(), $user->getFirstname(), $user->getLastname());
                },
                'label' => 'Utilisateur',
                'placeholder' => 'Veuillez sélectionner un utilisateur',
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => function (Product $product) {
                    return sprintf('%s (%s)', $product->getTitle(), $product->getPrice().'€');
                },
                'label' => 'Produit',
                'placeholder' => 'Veuillez sélectionner un produit',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            
        ]);
    }
}
