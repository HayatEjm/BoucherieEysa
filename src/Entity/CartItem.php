<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * ENTITÉ CARTITEM - Je représente un article dans le panier
 * 
 * POURQUOI CETTE ENTITÉ ?
 * - Je relie un produit à un panier avec une quantité
 * - Je stocke le prix au moment de l'ajout (pour éviter les changements de prix)
 * - Je calcule automatiquement le sous-total (quantité × prix)
 * 
 * RELATIONS :
 * - J'appartiens à un Cart (ManyToOne)
 * - Je référence un Product (ManyToOne)
 * 
 * EXEMPLE CONCRET :
 * - Cart ID 1 + Product "Steak" + Quantité 2 + Prix 15.50€ = 31.00€ de sous-total
 */
#[ORM\Entity(repositoryClass: CartItemRepository::class)]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Je suis lié à un panier
     * POURQUOI ? Pour savoir à quel panier j'appartiens
     * RELATION ManyToOne : Plusieurs articles peuvent être dans le même panier
     */
    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cart $cart = null;

    /**
     * Je référence un produit
     * POURQUOI ? Pour savoir quel produit je représente
     * RELATION ManyToOne : Plusieurs paniers peuvent contenir le même produit
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    /**
     * Je stocke la quantité demandée
     * POURQUOI ? Le client peut vouloir 2 steaks, 1 rôti, etc.
     */
    #[ORM\Column]
    private ?int $quantity = null;

    /**
     * Je stocke le prix unitaire au moment de l'ajout (en centimes)
     * POURQUOI ? Si le prix du produit change, mon prix reste le même
     * EXEMPLE : Steak à 15.50€ = 1550 centimes
     */
    #[ORM\Column]
    private ?int $unitPriceCents = null;

    /**
     * Je stocke la date d'ajout au panier
     * POURQUOI ? Pour savoir quand j'ai été ajouté (utile pour l'ordre d'affichage)
     */
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $addedAt = null;

    /**
     * CONSTRUCTEUR - Je m'initialise avec la date d'ajout
     */
    public function __construct()
    {
        $this->addedAt = new \DateTime(); // Je note quand j'ai été ajouté
    }

    // ========== GETTERS ET SETTERS ==========

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        $this->cart = $cart;
        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;
        // Je copie automatiquement le prix actuel du produit
        if ($product && !$this->unitPriceCents) {
            $this->setUnitPrice($product->getPrice());
        }
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Je retourne le prix unitaire en euros (conversion depuis les centimes)
     * POURQUOI ? Plus pratique à afficher que les centimes
     */
    public function getUnitPrice(): float
    {
        return $this->unitPriceCents / 100;
    }

    /**
     * Je définis le prix unitaire en euros (conversion vers les centimes)
     * POURQUOI ? Je stocke en centimes mais on me donne des euros
     */
    public function setUnitPrice(float $unitPrice): static
    {
        $this->unitPriceCents = (int) round($unitPrice * 100);
        return $this;
    }

    public function getUnitPriceCents(): ?int
    {
        return $this->unitPriceCents;
    }

    public function setUnitPriceCents(int $unitPriceCents): static
    {
        $this->unitPriceCents = $unitPriceCents;
        return $this;
    }

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeInterface $addedAt): static
    {
        $this->addedAt = $addedAt;
        return $this;
    }

    // ========== MÉTHODES UTILITAIRES ==========

    /**
     * Je calcule automatiquement mon sous-total en euros
     * POURQUOI ? Pour afficher le total de cette ligne (quantité × prix)
     * EXEMPLE : 2 steaks à 15.50€ = 31.00€
     */
    public function getTotal(): float
    {
        return $this->getTotalCents() / 100;
    }

    /**
     * Je calcule automatiquement mon sous-total en centimes
     * POURQUOI ? Plus précis pour les calculs, évite les erreurs d'arrondi
     * EXEMPLE : 2 steaks à 1550 centimes = 3100 centimes
     */
    public function getTotalCents(): int
    {
         if ($this->unitPriceCents === null || $this->quantity === null) {
        return 0;
    }
         return (int) round(($this->quantity / 1000) * $this->unitPriceCents);
    }
    

    /**
     * Je fournis une représentation textuelle de moi-même
     * POURQUOI ? Utile pour le debug et l'affichage dans les logs
     * EXEMPLE : "2x Steak de bœuf à 15.50€ = 31.00€"
     */
    public function __toString(): string
    {
        $productName = $this->product ? $this->product->getName() : 'Produit inconnu';
        return sprintf(
            '%dx %s à %.2f€ = %.2f€',
            $this->quantity,
            $productName,
            $this->getUnitPrice(),
            $this->getTotal()
        );
    }

    /**
     * Je vérifie si je correspond à un produit donné
     * POURQUOI ? Pour savoir si ce produit est déjà dans le panier
     */
    public function isForProduct(Product $product): bool
    {
        return $this->product && $this->product->getId() === $product->getId();
    }

    /**
     * J'augmente ma quantité
     * POURQUOI ? Quand on ajoute le même produit, on augmente la quantité
     * EXEMPLE : J'ai 2 steaks, on en ajoute 1, je passe à 3 steaks
     */
    public function increaseQuantity(int $amount = 1): static
    {
        $this->quantity += $amount;
        return $this;
    }

    /**
     * Je diminue ma quantité
     * POURQUOI ? Quand on retire des produits du panier
     * SÉCURITÉ : Je ne descends jamais en dessous de 0
     */
    public function decreaseQuantity(int $amount = 1): static
    {
        $this->quantity = max(0, $this->quantity - $amount);
        return $this;
    }

    /**
     * Je vérifie si ma quantité est valide
     * POURQUOI ? Pour m'assurer qu'on ne peut pas avoir 0 ou moins d'articles
     */
    public function isValidQuantity(): bool
    {
        return $this->quantity > 0;
    }
}
