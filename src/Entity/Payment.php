<?php
namespace App\Entity;

use App\Entity\Order;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\OneToOne(inversedBy: 'payment', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order = null;

    #[ORM\Column(type: 'float')]
    private float $amount;

    #[ORM\Column(type: 'string')]
    private string $method; // "stripe", "paypal", "cb"

    #[ORM\Column(type: 'string')]
    private string $status; // "pending", "success", "failed"

    #[ORM\Column(type: 'string')]
    private string $transactionId; // ID de Stripe ou PayPal

    #[ORM\Column(type: 'datetime')]
    private DateTime $paidAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): static
    {
        $this->order = $order;
        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;
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

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function setTransactionId(string $transactionId): static
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    public function getPaidAt(): DateTime
    {
        return $this->paidAt;
    }

    public function setPaidAt(DateTime $paidAt): static
    {
        $this->paidAt = $paidAt;
        return $this;
    }
}
