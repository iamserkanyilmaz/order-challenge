<?php

namespace App\Entity;

use App\Repository\DiscountRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DiscountRepository::class)
 */
class Discount
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reason;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalAmount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $discountAmount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $discountAmountType;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $categoryId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantityPurchased;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $freeCount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productCountCondition;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $applyType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(?float $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getDiscountAmount(): ?float
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(?float $discountAmount): self
    {
        $this->discountAmount = $discountAmount;

        return $this;
    }

    public function getDiscountAmountType(): ?string
    {
        return $this->discountAmountType;
    }

    public function setDiscountAmountType(?string $discountAmountType): self
    {
        $this->discountAmountType = $discountAmountType;

        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getQuantityPurchased()
    {
        return $this->quantityPurchased;
    }

    public function setQuantityPurchased($quantityPurchased): self
    {
        $this->quantityPurchased = $quantityPurchased;
    }

    public function getFreeCount(): ?int
    {
        return $this->freeCount;
    }

    public function setFreeCount(?int $freeCount): self
    {
        $this->freeCount = $freeCount;

        return $this;
    }

    public function getProductCountCondition(): ?string
    {
        return $this->productCountCondition;
    }

    public function setProductCountCondition(?string $productCountCondition): self
    {
        $this->productCountCondition = $productCountCondition;

        return $this;
    }

    public function getApplyType(): ?string
    {
        return $this->applyType;
    }

    public function setApplyType(?string $applyType): self
    {
        $this->applyType = $applyType;

        return $this;
    }
}
