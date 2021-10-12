<?php


namespace App\Service\Discount;


use App\Entity\Discount;
use App\Entity\Order;

class ValidateService
{
    private $order;
    private $discount;
    private $errors;

/**
 * Validates constructor.
 * @param Order $order
 * @param Discount $discount
 */
    public function __construct(Order $order, Discount $discount)
    {
        $this->order = $order;
        $this->discount = $discount;
        $this->errors = [];
    }

    /**
     * @param $validators
     * @return bool
     */
    public function run($validators) : bool
    {
        foreach ($validators as $validator) {
            if ($validator->validate($this->order, $this->discount)) {
                return true;
            }
        }

        return false;
    }
}
