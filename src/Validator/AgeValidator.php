<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AgeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\Age */

        if (null === $value || '' === $value) {
            return;
        }

        $valueExploded = explode(' ', $value);
        $ageNumberStr = $valueExploded[0];
        $ageType = $valueExploded[1];

        $validType = $ageType === 'y' || $ageType === 'm';
        $validAgeNumber = !!(int) $ageNumberStr || !strpos($ageNumberStr, '.');

        if ($validType && $validAgeNumber) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
