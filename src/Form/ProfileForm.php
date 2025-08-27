<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formulaire de modification du profil utilisateur
 * 
 * Permet de modifier :
 * - L'email
 * - Le mot de passe (optionnel)
 * - Plus tard : prénom, nom, téléphone, etc.
 */
class ProfileForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Email (obligatoire)
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'votre@email.com'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'L\'email est obligatoire']),
                    new Email(['message' => 'L\'email n\'est pas valide'])
                ]
            ])
            
            // Prénom
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Votre prénom'
                ],
                'help' => 'Nécessaire pour vous contacter lors du retrait de vos commandes',
                'constraints' => [
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])
            
            // Nom de famille
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Votre nom de famille'
                ],
                'help' => 'À compléter pour vos factures et documents officiels',
                'constraints' => [
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])
            
            // Téléphone
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Ex: 06 12 34 56 78'
                ],
                'help' => 'Nécessaire pour vous contacter lors du retrait de vos commandes',
                'constraints' => [
                    new Length([
                        'max' => 20,
                        'maxMessage' => 'Le téléphone ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])
            
            // Nouveau mot de passe (optionnel - si vide, on ne change pas)
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'mapped' => false, // Ne map pas automatiquement sur l'entité
                'first_options' => [
                    'label' => 'Nouveau mot de passe (optionnel)',
                    'attr' => [
                        'class' => 'form-input',
                        'placeholder' => 'Laissez vide pour ne pas changer'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmer le nouveau mot de passe',
                    'attr' => [
                        'class' => 'form-input',
                        'placeholder' => 'Confirmez le nouveau mot de passe'
                    ]
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères'
                    ])
                ]
            ])
            
            // Bouton de soumission
            ->add('save', SubmitType::class, [
                'label' => 'Mettre à jour mon profil',
                'attr' => [
                    'class' => 'btn-primary btn-full'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
