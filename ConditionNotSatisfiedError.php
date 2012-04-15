<?php

namespace Fw;

class ConditionNotSatisfiedError extends \Exception {
    public function __construct($property, $value = NULL) {
        if (!is_null($value)) {
            parent::__construct("'$property' must be '$value'");
        } else {
            parent::__construct("'$property' is required");
        }
        $this->property = $property;
    }
}
