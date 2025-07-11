<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * 🛒 ENTITÉ CART - Je représente un panier de courses
 * 
 * POURQUOI CETTE ENTITÉ ?
 * - Je stocke les informations générales du panier (total, date création, session)
 * - Je suis liée à plusieurs CartItem (articles du panier)
 * - Je permets de sauvegarder le panier en base de données
 * 
 * RELATIONS :
 * - Un Cart a plusieurs CartItem (OneToMany)
 * - Je peux être liée à un User plus tard (ManyToOne - optionnel)
 */
#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Je stocke l'ID de session pour identifier le panier
     * POURQUOI ? Pour retrouver le panier d'un visiteur non connecté
     */
    #[ORM\Column(length: 255)]
    private ?string $sessionId = null;

    /**
     * Je stocke la date de création du panier
     * POURQUOI ? Pour nettoyer les vieux paniers abandonnés
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * Je stocke la date de dernière mise à jour
     * POURQUOI ? Pour savoir quand le panier a été modifié pour la dernière fois
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * Je stocke le total du panier en centimes (pour éviter les problèmes de virgules)
     * POURQUOI ? Plus précis que les float, évite les erreurs d'arrondi
     * EXEMPLE : 12.50€ = 1250 centimes
     */
    #[ORM\Column]
    private ?int $totalCents = 0;

    /**
     * Je stocke le statut du panier
     * POURQUOI ? Pour différencier panier actif, commandé, abandonné
     * VALEURS : 'active', 'ordered', 'abandoned'
     */
    #[ORM\Column(length: 50, options: ['default' => 'active'])]
    private ?string $status = 'active';

    /**
     * Je contiens tous les articles du panier
     * RELATION OneToMany : Un panier peut avoir plusieurs articles
     */
    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartItem::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $cartItems;

    /**
     * CONSTRUCTEUR - Je m'initialise avec les valeurs par défaut
     */
    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
        $this->createdAt = new \DateTime(); // Je me crée maintenant
        $this->updatedAt = new \DateTime(); // Je me mets à jour maintenant
    }

    // ========== GETTERS ET SETTERS ==========

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): static
    {
        $this->sessionId = $sessionId;
        $this->updateTimestamp(); // Je mets à jour ma date de modification
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Je retourne le total en euros (conversion depuis les centimes)
     * POURQUOI ? Plus pratique à afficher que les centimes
     */
    public function getTotal(): float
    {
        return $this->totalCents / 100;
    }

    /* ========================================================================
       MÉTHODES TVA - BOUCHERIE (5,5%)
       ======================================================================== */

    /**
     * Je retourne le taux de TVA appliqué en boucherie
     * 👩‍💻 POUR TOI : 5,5% pour les produits alimentaires de première nécessité
     */
    public function getTaxRate(): float
    {
        return 5.5; // TVA boucherie = 5,5%
    }

    /**
     * Je calcule le montant de la TVA en euros
     * FORMULE : Total TTC / (1 + taux/100) = Total HT, puis Total TTC - Total HT = TVA
     */
    public function getTaxAmount(): float
    {
        $totalTTC = $this->getTotal();
        $taxRate = $this->getTaxRate();
        
        // Calcul du total HT : TTC / (1 + taux/100)
        $totalHT = $totalTTC / (1 + ($taxRate / 100));
        
        // TVA = TTC - HT
        $taxAmount = $totalTTC - $totalHT;
        
        // Je m'assure d'avoir 2 chiffres après la virgule
        return round($taxAmount, 2);
    }

    /**
     * Je calcule le total Hors Taxes (HT)
     * FORMULE : Total TTC / (1 + taux/100)
     */
    public function getTotalHT(): float
    {
        $totalTTC = $this->getTotal();
        $taxRate = $this->getTaxRate();
        
        $totalHT = $totalTTC / (1 + ($taxRate / 100));
        
        return round($totalHT, 2);
    }

    /**
     * Je retourne le total TTC (Toutes Taxes Comprises)
     * 👩‍💻 POUR TOI : C'est le même que getTotal() mais plus explicite
     */
    public function getTotalTTC(): float
    {
        return $this->getTotal();
    }

    // ========== GETTERS ET SETTERS POUR STATUS ==========

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        $this->updateTimestamp();
        return $this;
    }

    // ========== GESTION DES ARTICLES DU PANIER ==========

    /**
     * @return Collection<int, CartItem>
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): static
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems->add($cartItem);
            $cartItem->setCart($this);
        }
        $this->updateTimestamp();
        return $this;
    }

    public function removeCartItem(CartItem $cartItem): static
    {
        if ($this->cartItems->removeElement($cartItem)) {
            // set the owning side to null (unless already changed)
            if ($cartItem->getCart() === $this) {
                $cartItem->setCart(null);
            }
        }
        $this->updateTimestamp();
        return $this;
    }

    /**
     * Je compte le nombre d'articles dans le panier
     * POURQUOI ? Pour afficher le badge du panier
     */
    public function getItemCount(): int
    {
        return $this->cartItems->count();
    }

    /**
     * Je recalcule le total du panier en fonction des articles
     * POURQUOI ? Après ajout/suppression d'articles
     */
    public function recalculateTotal(): void
    {
        $total = 0;
        foreach ($this->cartItems as $item) {
            $total += $item->getTotal();
        }
        $this->totalCents = (int) ($total * 100); // Conversion en centimes
        $this->updateTimestamp();
    }

    /**
     * Vérifie si le panier est vide
     */
    public function isEmpty(): bool
    {
        return $this->cartItems->isEmpty();
    }

    /**
     * Obtient le nombre total d'articles dans le panier
     */
    public function getTotalItems(): int
    {
        $total = 0;
        foreach ($this->cartItems as $item) {
            $total += $item->getQuantity();
        }
        return $total;
    }

    /**
     * Vide complètement le panier
     */
    public function clear(): self
    {
        $this->cartItems->clear();
        return $this;
    }

    /**
     * Je mets à jour automatiquement ma date de modification
     * POURQUOI ? Pour tracker quand j'ai été modifié pour la dernière fois
     */
    private function updateTimestamp(): void
    {
        $this->updatedAt = new \DateTime();
    }
}
