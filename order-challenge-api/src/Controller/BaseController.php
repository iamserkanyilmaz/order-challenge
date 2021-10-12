<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class BaseController extends AbstractController
{
    /**
     * @param array $params
     * @param Constraint $rules
     * @return string|null
     */
    public function validate(array $params, Constraint $rules): ?string {
        $validator = Validation::createValidator();
        $errors = $validator->validate($params, $rules);

        if (count($errors) > 0) {
            return $this->showException($errors);
        }

        return null;
    }

    /**
     * @param ConstraintViolationListInterface $errors
     * @return string
     */
    public function showException(ConstraintViolationListInterface $errors): string{
        $message = '';
        foreach ($errors as $error) {
            $message .= $error->getPropertyPath() . ' ' . $error->getMessage() . '\n';
        }

        return $message;
    }
}
