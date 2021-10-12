<?php

namespace App\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ConstraintItemsValidator
 * @package App\Validator\Constraints
 */
class ConstraintItemsValidator extends ConstraintValidator
{

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        /**
         * @var $em EntityManager
         */
        if ($em = $constraint->getEntityManager()) {
            foreach ($value as $item){
                $product = $em->getRepository('App:Product')->find($item['id']);
                if ($item['quantity'] > $product->getStock()){
                    $this->context->addViolation($constraint->getMessage('stock'));
                }
            }
        }
    }
}
