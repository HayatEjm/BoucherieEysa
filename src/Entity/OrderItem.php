<?php
namespace App\Entity;

use App\Entity\Product;
use App\Entity\Order;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $commande = null;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    #[ORM\Column(type: 'float')]
    private float $unitPrice;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $unitPriceHtCents = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $totalHtCents = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $totalTvaCents = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $totalTtcCents = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): static
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    public function getTotal(): float
    {
        return $this->unitPrice * $this->quantity;
    }

    public function getCommande(): ?Order
    {
        return $this->commande;
    }

    public function setCommande(?Order $commande): static
    {
        $this->commande = $commande;
        return $this;
    }

    // ========== MÉTHODES POUR LA GESTION DES PRIX EN CENTIMES ==========
    
    public function getUnitPriceHtCents(): ?int
    {
        return $this->unitPriceHtCents;
    }
    
    public function setUnitPriceHtCents(?int $unitPriceHtCents): static
    {
        $this->unitPriceHtCents = $unitPriceHtCents;
        return $this;
    }
    
    public function getTotalHtCents(): ?int
    {
        return $this->totalHtCents;
    }
    
    public function setTotalHtCents(?int $totalHtCents): static
    {
        $this->totalHtCents = $totalHtCents;
        return $this;
    }
    
    public function getTotalTvaCents(): ?int
    {
        return $this->totalTvaCents;
    }
    
    public function setTotalTvaCents(?int $totalTvaCents): static
    {
        $this->totalTvaCents = $totalTvaCents;
        return $this;
    }
    
    public function getTotalTtcCents(): ?int
    {
        return $this->totalTtcCents;
    }
    
    public function setTotalTtcCents(?int $totalTtcCents): static
    {
        $this->totalTtcCents = $totalTtcCents;
        return $this;
    }
    
    // ========== MÉTHODES DE CONVERSION EUROS ==========
    
    public function getTotalHt(): float
    {
        return $this->totalHtCents ? $this->totalHtCents / 100 : 0.0;
    }
    
    public function getTotalTva(): float
    {
        return $this->totalTvaCents ? $this->totalTvaCents / 100 : 0.0;
    }
    
    public function getTotalTtc(): float
    {
        return $this->totalTtcCents ? $this->totalTtcCents / 100 : 0.0;
    }
}
