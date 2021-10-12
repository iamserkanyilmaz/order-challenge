<?php

namespace App\Service\Discount\Validators;

use App\Entity\Discount;
use App\Entity\Order;
use Doctrine\ORM\EntityManager;

/**
 * Class CategoryValidator
 * @package App\Service\Discount\Validators
 */
class CategoryAndProductCountValidator implements ValidatorInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * TotalAmountValidator constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Order $order
     * @param Discount $discount
     * @return bool|mixed
     */
    public function validate(Order $order, Discount $discount)
    {
        if (is_null($discount->getCategoryId()) || is_null($discount->getQuantityPurchased())) {
            return false;
        }

        foreach ($order->getItems() as $item) {
            if ($item->getProduct()->getCategory() == $discount->getCategoryId() &&
                $item->getQuantity() >= $discount->getQuantityPurchased()) {
                return true;
            }
        }

        return false;
    }
}
