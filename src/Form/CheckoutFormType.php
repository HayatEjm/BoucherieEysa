<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FORM TYPE CHECKOUT - Formulaire de finalisation de commande
 * 
 * RESPONSABILITÉS :
 * - Collecte des informations client
 * - Sélection du créneau de retrait
 * - Validation des données
 * - Protection CSRF automatique
 * 
 * ARCHITECTURE MVC :
 * - Vue : Templates Twig
 * - Modèle : Entité Order
 * - Contrôleur : CheckoutController
 */
class CheckoutFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ========== INFORMATIONS CLIENT ==========
            ->add('customerName', TextType::class, [
                'label' => 'Nom complet *',
                'attr' => [
                    'placeholder' => 'Ex: Jean Dupont',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir votre nom complet'
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le nom doit faire au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])
            
            ->add('customerPhone', TelType::class, [
                'label' => 'Téléphone *',
                'attr' => [
                    'placeholder' => 'Ex: 06 12 34 56 78',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir votre numéro de téléphone'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(?:\+33|0)[1-9](?:[0-9]{8})$/',
                        'message' => 'Veuillez saisir un numéro de téléphone français valide'
                    ])
                ]
            ])
            
            ->add('customerEmail', EmailType::class, [
                'label' => 'Email *',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex: jean.dupont@email.com',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir votre adresse email'
                    ]),
                    new Assert\Email([
                        'message' => 'Veuillez saisir une adresse email valide'
                    ])
                ]
            ])

            // ========== CRÉNEAU DE RETRAIT ==========
            ->add('pickupDate', DateType::class, [
                'label' => 'Date de retrait *',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                    'min' => (new \DateTime())->format('Y-m-d'), // Pas de date dans le passé
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez choisir une date de retrait'
                    ]),
                    new Assert\GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de retrait ne peut pas être dans le passé'
                    ])
                ]
            ])
            
            ->add('pickupTimeSlot', ChoiceType::class, [
                'label' => 'Créneau horaire *',
                'choices' => $this->getAvailableTimeSlots(),
                'placeholder' => 'Choisissez un créneau',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez choisir un créneau horaire'
                    ])
                ]
            ])

            // ========== COMMENTAIRES OPTIONNELS ==========
            ->add('notes', TextareaType::class, [
                'label' => 'Commentaires ou instructions particulières',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex: Merci de bien cuire le rôti, allergie aux noix...',
                    'class' => 'form-control',
                    'rows' => 3,
                ],
                'constraints' => [
                    new Assert\Length([
                        'max' => 1000,
                        'maxMessage' => 'Les commentaires ne peuvent pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])

            // ========== BOUTON DE SOUMISSION ==========
            ->add('submit', SubmitType::class, [
                'label' => 'Confirmer ma commande',
                'attr' => [
                    'class' => 'btn-eysa btn-eysa-primary btn-large',
                    'id' => 'submit-order',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'checkout_form',
        ]);
    }

    /**
     * Retourne les créneaux horaires disponibles
     * TODO: Cette logique devrait être dans un service dédié
     */
    private function getAvailableTimeSlots(): array
    {
        return [
            '09:00 - 10:00' => '09:00-10:00',
            '10:00 - 11:00' => '10:00-11:00',
            '11:00 - 12:00' => '11:00-12:00',
            '14:00 - 15:00' => '14:00-15:00',
            '15:00 - 16:00' => '15:00-16:00',
            '16:00 - 17:00' => '16:00-17:00',
            '17:00 - 18:00' => '17:00-18:00',
        ];
    }

    /**
     * Nom du formulaire (utilisé pour les IDs HTML)
     */
    public function getBlockPrefix(): string
    {
        return 'checkout';
    }
}
