<?php

namespace App\Form;

use App\Entity\Order;
use App\Service\PickupSlotService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
    public function __construct(
        private PickupSlotService $pickupSlotService
    ) {
    }
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
            
            ->add('pickupTimeSlot', TextType::class, [
                'label' => 'Créneau horaire *',
                'attr' => [
                    'class' => 'form-control',
                    'data-slot-input' => 'true', // Marqueur pour JavaScript
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
     * Retourne les créneaux horaires disponibles depuis le service
     * Phase 1 : créneaux 30min fixes depuis config YAML avec filtrage 2h
     * Phase 2 : créneaux dynamiques avec capacité en BDD
     */
    private function getAvailableTimeSlots(): array
    {
        // Utiliser aujourd'hui comme date de référence pour le filtrage initial
        // (l'utilisateur verra les créneaux filtrés pour aujourd'hui par défaut)
        $today = new \DateTime();
        $todaySlots = $this->pickupSlotService->getAvailableSlotsForDate($today);
        
        // Si c'est aujourd'hui et qu'il reste des créneaux filtrés, les utiliser
        // Sinon, afficher tous les créneaux (pour les jours futurs)
        $slots = [];
        
        if (!empty($todaySlots)) {
            // Utiliser les créneaux filtrés d'aujourd'hui (avec délai 2h)
            foreach ($todaySlots as $slotData) {
                $slots[] = [
                    'value' => $slotData['time'],
                    'label' => $slotData['time']
                ];
            }
        } else {
            // Aucun créneau aujourd'hui, afficher tous les créneaux disponibles
            $slots = $this->pickupSlotService->getAllTimeSlots();
        }
        
        // Formatter pour le select: ['10h00' => '10:00', '10h30' => '10:30', ...]
        $choices = [];
        foreach ($slots as $slot) {
            $label = str_replace(':', 'h', $slot['value']); // '10:00' → '10h00'
            $choices[$label] = $slot['value'];
        }
        
        return $choices;
    }

    /**
     * Nom du formulaire (utilisé pour les IDs HTML)
     */
    public function getBlockPrefix(): string
    {
        return 'checkout';
    }
}
