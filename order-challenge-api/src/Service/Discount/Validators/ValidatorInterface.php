<?php

namespace App\Service\Discount\Validators;

use App\Entity\Discount;
use App\Entity\Order;

/**
 * Interface ValidatorInterface
 * @package App\Service\Discount\Validators
 */
interface ValidatorInterface
{
    /**
     * @param Order $order
     * @param Discount $discount
     * @return mixed
     */
    public function validate(Order $order, Discount $discount);
}
