<?php

namespace App\Service\Discount\Validators;

use App\Entity\Discount;
use App\Entity\Order;
use Doctrine\ORM\EntityManager;

/**
 * Class TotalAmountValidator
 * @package App\Service\Discount\Validators
 */
class TotalAmountValidator implements ValidatorInterface
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
        if (!is_null($discount->getTotalAmount()) && $order->getTotal() >= $discount->getTotalAmount()) {
            return true;
        }

        return false;
    }
}
