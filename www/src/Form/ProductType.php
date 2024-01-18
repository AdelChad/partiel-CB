<?php

namespace App\Form;

use App\Entity\Fds;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'mapped'   => true,
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ],
            ])
            ->add('description', TextareaType::class,[
                'mapped'   => true,
                'required' => true,
            ])
            ->add('fileImg', FileType::class, [
                'label' => 'Image',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',    // Ajoutez le type MIME pour .pjn
                            'image/jpeg',  // Ajoutez le type MIME pour .jpgge
                            'image/jpg'
                        ],
                        'mimeTypesMessage' => 'Veuillez charger une image avec une extension valide (.png, .jpeg, .jpg).',
                    ])
                ],
            ])
            ->add('price', NumberType::class,[
                'label'    => 'Prix',
                'mapped'   => true,
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^\d+(\.\d+)?$/',
                        'message' => 'Le prix doit être un nombre décimal.',
                    ])
                ]
            ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}