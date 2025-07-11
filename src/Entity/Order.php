<?php
namespace App\Entity;

use App\Entity\OrderItem;
use App\Entity\Payment;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`order`')]  // Utilisation de backticks pour éviter le conflit avec le mot-clé SQL
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: OrderItem::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $items;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    private DateTime $pickupAt;

    #[ORM\Column(type: 'string')]
    private string $status;

    #[ORM\Column(type: 'string')]
    private string $paymentMethod;

    #[ORM\OneToOne(mappedBy: 'order', targetEntity: Payment::class, cascade: ['persist', 'remove'])]
    private ?Payment $payment = null;

    #[ORM\Column(length: 50)]
    private ?string $pickupNumber = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTime $pickupDate = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $pickupTimeSlot = null;

    #[ORM\Column(length: 100)]
    private ?string $customerName = null;

    #[ORM\Column(length: 20)]
    private ?string $customerPhone = null;

    #[ORM\Column(length: 100)]
    private ?string $customerEmail = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(length: 50, unique: true, nullable: true)]
    private ?string $orderNumber = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $pickupDateTime = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $totalHtCents = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $totalTvaCents = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $totalTtcCents = null;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->createdAt = new DateTime();
        $this->status = 'pending';
        $this->orderNumber = $this->generateOrderNumber();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setCommande($this);
        }
        return $this;
    }

    public function removeItem(OrderItem $item): static
    {
        if ($this->items->removeElement($item)) {
            if ($item->getCommande() === $this) {
                $item->setCommande(null);
            }
        }
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getPickupAt(): DateTime
    {
        return $this->pickupAt;
    }

    public function setPickupAt(DateTime $pickupAt): static
    {
        $this->pickupAt = $pickupAt;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): static
    {
        $this->payment = $payment;
        return $this;
    }

    public function getPickupNumber(): ?string
    {
        return $this->pickupNumber;
    }

    public function setPickupNumber(string $pickupNumber): static
    {
        $this->pickupNumber = $pickupNumber;
        return $this;
    }

    // ========== MÉTHODES POUR CHECKOUT FORM ==========
    
    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }
    
    public function setCustomerName(?string $customerName): static
    {
        $this->customerName = $customerName;
        return $this;
    }
    
    public function getCustomerPhone(): ?string
    {
        return $this->customerPhone;
    }
    
    public function setCustomerPhone(?string $customerPhone): static
    {
        $this->customerPhone = $customerPhone;
        return $this;
    }
    
    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }
    
    public function setCustomerEmail(?string $customerEmail): static
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }
    
    public function getPickupDate(): ?\DateTime
    {
        return $this->pickupDate;
    }
    
    public function setPickupDate(?\DateTime $pickupDate): static
    {
        $this->pickupDate = $pickupDate;
        return $this;
    }
    
    public function getNotes(): ?string
    {
        return $this->notes;
    }
    
    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;
        return $this;
    }
    
    public function getOrderItems(): Collection
    {
        return $this->items;
    }

    // ========== NOUVELLES MÉTHODES MANQUANTES ==========
    
    public function getPickupTimeSlot(): ?string
    {
        return $this->pickupTimeSlot;
    }
    
    public function setPickupTimeSlot(?string $pickupTimeSlot): static
    {
        $this->pickupTimeSlot = $pickupTimeSlot;
        return $this;
    }
    
    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }
    
    public function setOrderNumber(?string $orderNumber): static
    {
        $this->orderNumber = $orderNumber;
        return $this;
    }
    
    public function getPickupDateTime(): ?\DateTime
    {
        return $this->pickupDateTime;
    }
    
    public function setPickupDateTime(?\DateTime $pickupDateTime): static
    {
        $this->pickupDateTime = $pickupDateTime;
        return $this;
    }
    
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
    
    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->items->contains($orderItem)) {
            $this->items->add($orderItem);
            $orderItem->setCommande($this);
        }
        return $this;
    }
    
    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->items->removeElement($orderItem)) {
            if ($orderItem->getCommande() === $this) {
                $orderItem->setCommande(null);
            }
        }
        return $this;
    }
    
    public function recalculateTotals(): void
    {
        $totalHtCents = 0;
        $totalTvaCents = 0;
        $totalTtcCents = 0;
        
        foreach ($this->items as $item) {
            $totalHtCents += $item->getTotalHtCents() ?? 0;
            $totalTvaCents += $item->getTotalTvaCents() ?? 0;
            $totalTtcCents += $item->getTotalTtcCents() ?? 0;
        }
        
        $this->totalHtCents = $totalHtCents;
        $this->totalTvaCents = $totalTvaCents;
        $this->totalTtcCents = $totalTtcCents;
    }
    
    public function confirm(): void
    {
        $this->status = 'confirmed';
    }
    
    private function generateOrderNumber(): string
    {
        return 'CMD-' . date('Ymd') . '-' . uniqid();
    }
}
