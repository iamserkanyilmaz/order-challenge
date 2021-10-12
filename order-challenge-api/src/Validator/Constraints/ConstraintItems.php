<?php

namespace App\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;

/**
 * Class ConstraintItems
 * @package App\Validator\Constraints
 * @Annotation
 */
class ConstraintItems extends Constraint
{
    protected $messages = [
        'stock'=> 'There no stock.'
    ];

    protected $_em;

    /**
     * ConstraintAttribute constructor.
     *
     * @param $options
     */
    public function __construct($options)
    {
        $this->_em     = $options['entityManager'];

        parent::__construct();
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->_em;
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getMessage($type)
    {
        return $this->messages[$type];
    }
}
