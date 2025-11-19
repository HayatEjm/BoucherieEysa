<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est obligatoire.']),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank(['message' => 'La description est obligatoire.']),
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix (€/kg)',
                'constraints' => [
                    new NotBlank(['message' => 'Le prix est obligatoire.']),
                    new Positive(['message' => 'Le prix doit être positif.']),
                ],
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'Stock disponible',
                'constraints' => [
                    new NotBlank(['message' => 'Le stock est obligatoire.']),
                    new Positive(['message' => 'Le stock doit être positif.']),
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'placeholder' => 'Choisir une catégorie',
            ])
            ->add('unit', TextType::class, [
                'label' => 'Unité (kg, pièce, etc.)',
                'required' => false,
            ])
            ->add('minWeight', IntegerType::class, [
                'label' => 'Poids minimum (g)',
                'required' => false,
                'constraints' => [
                    new Positive(['message' => 'Le poids minimum doit être positif.']),
                ],
            ])
            ->add('maxWeight', IntegerType::class, [
                'label' => 'Poids maximum (g)',
                'required' => false,
                'constraints' => [
                    new Positive(['message' => 'Le poids maximum doit être positif.']),
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image du produit',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, WebP).',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
