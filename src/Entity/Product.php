<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\OrderItem;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\Column(type: 'string')]
    private string $image;

    #[ORM\Column(type: 'integer')]
    private int $stock;

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: OrderItem::class)]
    private Collection $orderItems;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Category $category = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $unit = null;

    // Nouvelles propriétés pour la gestion du poids
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Positive(message: 'Le poids minimum doit être positif.')]
    private ?int $minWeight = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Positive(message: 'Le poids maximum doit être positif.')]
    private ?int $maxWeight = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\PositiveOrZero(message: 'Le seuil d\'alerte doit être positif ou nul.')]
    private ?int $alertThreshold = null;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;
        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setProduct($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            if ($orderItem->getProduct() === $this) {
                $orderItem->setProduct(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    // Getter et Setter pour le poids minimum
    public function getMinWeight(): ?int
    {
        return $this->minWeight;
    }

    public function setMinWeight(?int $minWeight): static
    {
        $this->minWeight = $minWeight;
        return $this;
    }

    // Getter et Setter pour le poids maximum
    public function getMaxWeight(): ?int
    {
        return $this->maxWeight;
    }

    public function setMaxWeight(?int $maxWeight): static
    {
        $this->maxWeight = $maxWeight;
        return $this;
    }

    /**
     * Validation personnalisée cohérence min/max.
     */
    public function validateWeights(ExecutionContextInterface $context): void
    {
        if ($this->minWeight !== null && $this->maxWeight !== null) {
            if ($this->maxWeight < $this->minWeight) {
                $context->buildViolation('Le poids maximum doit être supérieur ou égal au poids minimum.')
                    ->atPath('maxWeight')
                    ->add();
            }
        }
        // Option: détecter valeurs irréalistes (> 20kg) pour une boucherie fine
        if ($this->maxWeight !== null && $this->maxWeight > 20000) {
            $context->buildViolation('Valeur de poids maximum anormalement élevée (> 20kg).')
                ->atPath('maxWeight')
                ->add();
        }
    }

    // Méthode simple pour vérifier si le produit est en stock
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    // Méthode pour savoir si le stock est faible (moins de 5)
    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= 5;
    }

    public function getAlertThreshold(): ?int
    {
        return $this->alertThreshold;
    }

    public function setAlertThreshold(?int $alertThreshold): static
    {
        $this->alertThreshold = $alertThreshold;
        return $this;
    }

    /**
     * Retourne le statut du stock basé sur le seuil d'alerte
     * 'good' : stock > seuil, 'low' : stock <= seuil mais > 0, 'critical' : stock = 0
     */
    public function getStockStatus(): string
    {
        if ($this->stock === 0) {
            return 'critical';
        }
        
        if ($this->alertThreshold !== null && $this->stock <= $this->alertThreshold) {
            return 'low';
        }
        
        return 'good';
    }
}
